<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
{
    $categories = Category::orderBy('name')->get();

    // 👇 add 'colors' here
    $query = Product::with(['category', 'colors'])
        ->where('is_active', true);

    if ($request->filled('category')) {
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('slug', $request->category);
        });
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    $products = $query->orderBy('created_at', 'desc')
        ->paginate(12)
        ->withQueryString();

    return view('shop.index', compact('categories', 'products'));
}


    public function show(Product $product)
    {
        if (! $product->is_active) {
            abort(404);
        }
$product->load(['category', 'colors']);
        return view('shop.show', compact('product'));
    }
}