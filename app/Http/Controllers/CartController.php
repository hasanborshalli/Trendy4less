<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected string $cartKey = 'cart';

    public function index()
{
    $cart = session($this->cartKey, []);

    $items = [];
    $total = 0;

    foreach ($cart as $key => $item) {
        // Make sure the structure is valid
        if (!isset($item['price'], $item['quantity'], $item['subtotal'])) {
            continue;
        }

        $items[$key] = $item;
        $total += $item['subtotal'];
    }

    // Use the correct view path you actually have
    return view('shop.cart', compact('items', 'total'));
}

 public function add(Request $request, Product $product)
    {
        if (! $product->is_active) {
            return back()->with('status', 'This product is not available.');
        }

        // quantity + optional color
        $validated = $request->validate([
            'quantity'  => ['nullable', 'integer', 'min:1', 'max:99'],
            'color_id'  => ['nullable', 'integer'],
        ]);

        $qty     = $validated['quantity'] ?? 1;
        $colorId = $validated['color_id'] ?? null;

        $color = null;
        if ($colorId) {
            // ensure color belongs to this product
            $color = ProductColor::where('id', $colorId)
                ->where('product_id', $product->id)
                ->first();
        }

        $cart = session($this->cartKey, []);

        // key separates same product with different colors
        $key = $product->id . ':' . ($color ? $color->id : 'default');

        $price = $product->effective_price;


        if (isset($cart[$key])) {
            // existing line → just increase qty
            $cart[$key]['quantity'] += $qty;
            $cart[$key]['subtotal']  = $cart[$key]['quantity'] * $price;
        } else {
            // new line
            $imagePath = $color && $color->image_path
                ? $color->image_path
                : $product->image_path;

            $cart[$key] = [
                'product_id' => $product->id,
                'color_id'   => $color?->id,
                'color_name' => $color?->name,
                'name'       => $product->name,
                'price'      => $price,
                'quantity'   => $qty,
                'image_path' => $imagePath,
                'subtotal'   => $price * $qty,
            ];
        }

        session([$this->cartKey => $cart]);

        return back()->with('status', 'Added to cart!');
    }

    public function remove(string $key)
{
    $cart = session($this->cartKey, []);

    if (isset($cart[$key])) {
        unset($cart[$key]);
        session([$this->cartKey => $cart]);
    }

    return back()->with('status', 'Item removed from cart.');
}
    public function clear()
    {
        session()->forget($this->cartKey);

        return back()->with('status', 'Cart cleared.');
    }
}