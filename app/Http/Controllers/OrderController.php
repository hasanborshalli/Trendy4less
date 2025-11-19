<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function showCheckout()
{
    $cart = session('cart', []); // or session($this->cartKey, []);

    if (empty($cart)) {
        return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
    }

    $items  = [];
    $total  = 0;

    // collect all product IDs from cart lines
    $productIds = [];
    foreach ($cart as $item) {
        if (isset($item['product_id'])) {
            $productIds[] = $item['product_id'];
        }
    }

    if (empty($productIds)) {
        return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
    }

    // load products and key by id
    $products = Product::whereIn('id', array_unique($productIds))
        ->get()
        ->keyBy('id');

    foreach ($cart as $key => $item) {
        if (
            !isset($item['product_id'], $item['price'], $item['quantity'], $item['subtotal'])
        ) {
            continue;
        }

        $product = $products[$item['product_id']] ?? null;

        $items[$key] = [
            'product'     => $product,
            'name'        => $item['name'],
            'color_name'  => $item['color_name'] ?? null,
            'image_path'  => $item['image_path'] ?? null,
            'price'       => $item['price'],
            'quantity'    => $item['quantity'],
            'subtotal'    => $item['subtotal'],
        ];

        $total += $item['subtotal'];
    }

    if (empty($items)) {
        return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
    }

    return view('shop.checkout', compact('items', 'total'));
}


    public function placeOrder(Request $request)
{
    $cart = session('cart', []); // or session($this->cartKey, []);

    if (empty($cart)) {
        return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
    }

    $data = $request->validate([
        'customer_name'    => ['required', 'string', 'max:255'],
        'customer_phone'   => ['required', 'string', 'max:255'],
        'customer_address' => ['required', 'string'],
        'notes'            => ['nullable', 'string'],
    ]);

    // collect product IDs from cart lines
    $productIds = [];
    foreach ($cart as $item) {
        if (isset($item['product_id'])) {
            $productIds[] = $item['product_id'];
        }
    }

    if (empty($productIds)) {
        return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
    }

    // load products and key by id for safety (price/name can come from DB)
    $products = Product::whereIn('id', array_unique($productIds))
        ->get()
        ->keyBy('id');

    $itemsPayload = [];
    $total = 0;

    foreach ($cart as $key => $item) {
        if (
            !isset($item['product_id'], $item['price'], $item['quantity'])
        ) {
            continue;
        }

        $product = $products[$item['product_id']] ?? null;
        if (! $product || ! $product->is_active) {
            continue;
        }

        $qty   = (int) $item['quantity'];
        if ($qty <= 0) {
            continue;
        }

        // you can choose: use $product->price or $item['price']
        $price    = $product->effective_price;
        $subtotal = $price * $qty;
        $total   += $subtotal;

        $itemsPayload[] = [
            'product_id' => $product->id,
            'color_id'   => $item['color_id']   ?? null,
            'color_name' => $item['color_name'] ?? null,
            'name'       => $product->name,
            'price'      => $price,
            'quantity'   => $qty,
            'subtotal'   => $subtotal,
        ];
    }

    if ($total <= 0 || empty($itemsPayload)) {
        return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
    }

    $order = Order::create([
        'customer_name'    => $data['customer_name'],
        'customer_phone'   => $data['customer_phone'],
        'customer_address' => $data['customer_address'],
        'status'           => 'pending',
        'total_amount'     => $total,
        'notes'            => $data['notes'] ?? null,
        'items_json'       => json_encode($itemsPayload),
    ]);

    // Build order number like MM-YY-0003
    $prefix   = now()->format('m-y'); // e.g. "03-25"
    $sequence = str_pad((string) $order->id, 4, '0', STR_PAD_LEFT); // "0003"

    $order->order_number = $prefix . '-' . $sequence;
    $order->save();

    // clear cart
    session()->forget('cart');

    // store order number for thank you page
    session()->flash('last_order_number', $order->order_number);

    return redirect()->route('order.thankyou');
}

   public function thankYou()
{
    $orderNumber = session('last_order_number'); // may be null if user opens directly

    return view('shop.thank-you', compact('orderNumber'));
}


}