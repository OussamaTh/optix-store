<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
        $genders = (array) $request->input('gender', []);
        $categories = (array) $request->input('category', []);
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');

        $products = Product::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('q') . '%');
            })
            ->when(! empty($genders), fn($q) => $q->whereIn('gender', $genders))
            ->when(! empty($categories), fn($q) => $q->whereIn('category', $categories))
            ->when($priceMin !== null && $priceMin !== '', fn($q) => $q->where('price', '>=', (float) $priceMin))
            ->when($priceMax !== null && $priceMax !== '', fn($q) => $q->where('price', '<=', (float) $priceMax))
            ->when($request->input('sort') === 'price_asc', fn($q) => $q->orderBy('price', 'asc'))
            ->when($request->input('sort') === 'price_desc', fn($q) => $q->orderBy('price', 'desc'))
            ->when(! $request->filled('sort') || $request->input('sort') === 'newest', fn($q) => $q->orderBy('created_at', 'desc'))
            ->paginate(12)
            ->withQueryString();


        $baseCountQuery = fn() => Product::query()
            ->when($request->filled('q'), fn($q) => $q->where('name', 'like', '%' . $request->input('q') . '%'));

        $genderCounts = $baseCountQuery()
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total', 'gender');

        $categoryCounts = $baseCountQuery()
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        $priceRange = $baseCountQuery()
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();




        return view('products.index', compact(
            'products',
            'genderCounts',
            'categoryCounts',
            'priceRange',
        ));
    }

    public function show(Product $productId): View
    {
        $product = $productId->load('variants');

        $relatedProducts = Product::query()
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
