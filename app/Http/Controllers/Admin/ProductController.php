<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('q') . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function show(Product $productId): View
    {
        $product = Product::find($productId)->first();

        $relatedProducts = Product::query()
            ->where('id', '!=', $productId)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function create(): View
    {
        $variants = Variant::all();

        return view('admin.products.create', compact('variants'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);

        $variantIds = $validated['variant_ids'];
        unset($validated['variant_ids']);

        $validated['slug'] = $this->uniqueSlug($validated['name']);

        $path = $request->file('image')->store('products', 's3');
        $validated['image_path'] = $path;

        $product = Product::create($validated);

        $product->variants()->sync($variantIds);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $variants = Variant::all();

        return view('admin.products.edit', compact('product', 'variants'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateProduct($request, forUpdate: true);

        $variantIds = $validated['variant_ids'];
        unset($validated['variant_ids']);

        if ($validated['name'] !== $product->name) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], $product->id);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 's3');

            if ($product->image_path && ! str_starts_with($product->image_path, 'http')) {
                Storage::disk('s3')->delete($product->image_path);
            }

            $validated['image_path'] = $path;
        }

        $product->update($validated);

        $product->variants()->sync($variantIds);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Shared validation rules for create and update.
     */
    private function validateProduct(Request $request, bool $forUpdate = false): array
    {
        $rules = [
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'price'         => ['required', 'numeric', 'min:0'],
            'gender'        => ['required', 'in:men,women,unisex'],
            'category'      => ['required', 'in:glasses,sunglasses'],
            'variant_ids'   => ['required', 'array', 'min:1'],
            'variant_ids.*' => ['integer', 'exists:variants,id'],
        ];

        $rules['image'] = $forUpdate
            ? ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096']
            : ['required', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096'];

        return $request->validate($rules);
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image_path && ! str_starts_with($product->image_path, 'http')) {
            Storage::disk('s3')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }


    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:products,id',
        ]);

        $count = Product::whereIn('id', $validated['ids'])->count();

        Product::whereIn('id', $validated['ids'])->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', $count . ' product(s) deleted successfully.');
    }


    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (
            Product::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
