<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VariantController extends Controller
{
    public function index(Request $request): View
    {
        $variants = Variant::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('q') . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.variants.index', compact('variants'));
    }

    public function create(): View
    {
        return view('admin.variants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name']);

        Variant::create($validated);

        return redirect()->route('admin.variants.index')->with('success', 'Variant created successfully.');
    }

    public function edit(Variant $variant): View
    {
        return view('admin.variants.edit', compact('variant'));
    }

    public function update(Request $request, Variant $variant): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validated['name'] !== $variant->name) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], $variant->id);
        }

        $variant->update($validated);

        return redirect()->route('admin.variants.index')->with('success', 'Variant updated successfully.');
    }

    public function destroy(Variant $variant): RedirectResponse
    {
        // Check if variant is used in cart items (prompt: restrict on delete is database level, but checking here or letting DB fail is good).
        // Since database has restrict on delete, a direct delete might throw an exception if used in cart.
        // Let's handle it gracefully:
        if ($variant->products()->count() > 0) {
            return redirect()->route('admin.variants.index')->with('error', 'Cannot delete variant because it is assigned to one or more products.');
        }

        $variant->delete();

        return redirect()->route('admin.variants.index')->with('success', 'Variant deleted successfully.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (
            Variant::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
