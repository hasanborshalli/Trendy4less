<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductColorController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'hex_color' => ['nullable', 'string', 'max:20'],
            'image'     => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('image')->store('product-colors', 'public');

        $color = ProductColor::create([
            'product_id' => $product->id,
            'name'       => $data['name'],
            'hex_color'  => $data['hex_color'] ?? null,
            'image_path' => $path,
            'is_default' => $request->boolean('is_default'),
        ]);

        // if this is default, clear others
        if ($color->is_default) {
            ProductColor::where('product_id', $product->id)
                ->where('id', '!=', $color->id)
                ->update(['is_default' => false]);
        }

        return back()->with('status', 'Color added.');
    }

    public function destroy(Product $product, ProductColor $color)
    {
        // ensure this color belongs to this product
        if ($color->product_id !== $product->id) {
            abort(404);
        }

        Storage::disk('public')->delete($color->image_path);
        $color->delete();

        return back()->with('status', 'Color removed.');
    }
}