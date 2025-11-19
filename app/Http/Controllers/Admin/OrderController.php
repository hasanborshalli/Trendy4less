<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
{
    $items = [];
    $products = collect();

    if ($order->items_json) {
        $items = json_decode($order->items_json, true) ?? [];

        $ids = collect($items)
            ->pluck('product_id')
            ->filter()
            ->unique();

        if ($ids->isNotEmpty()) {
            $products = Product::whereIn('id', $ids)->get()->keyBy('id');
        }
    }

    return view('admin.orders.show', compact('order', 'items', 'products'));
}


    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'customer_name'    => ['required', 'string', 'max:255'],
            'customer_phone'   => ['required', 'string', 'max:255'],
            'customer_address' => ['required', 'string'],
            'status'           => ['required', 'string', 'max:50'],
            'notes'            => ['nullable', 'string'],
        ]);

        $order->update($data);

        return redirect()->route('admin.orders.index')->with('status', 'Order updated.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return back()->with('status', 'Order deleted.');
    }

    // Optional: manual create order from panel
    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name'    => ['required', 'string', 'max:255'],
            'customer_phone'   => ['required', 'string', 'max:255'],
            'customer_address' => ['required', 'string'],
            'status'           => ['required', 'string', 'max:50'],
            'notes'            => ['nullable', 'string'],
            'total_amount'     => ['nullable', 'numeric', 'min:0'],
        ]);

        $data['items_json'] = $data['items_json'] ?? null;

        Order::create($data);

        return redirect()->route('admin.orders.index')->with('status', 'Order created.');
    }
}