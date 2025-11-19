@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1rem;">
        <h1 class="t4l-section-title" style="margin-bottom:0;">Order #{{ $order->id }}</h1>

        <a href="{{ route('admin.orders.edit', $order) }}" class="t4l-btn-secondary t4l-btn-small">
            Edit Order
        </a>
    </div>

    <div class="t4l-order-details-layout">
        {{-- Customer info --}}
        <div class="t4l-order-card">
            <h2>Customer</h2>
            <p><strong>Name:</strong> {{ $order->customer_name }}</p>
            <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
            <p><strong>Address:</strong> {{ $order->customer_address }}</p>
            @if($order->notes)
            <p><strong>Notes:</strong> {{ $order->notes }}</p>
            @endif
        </div>

        {{-- Order meta --}}
        <div class="t4l-order-card">
            <h2>Order Info</h2>
            <p>
                <strong>Status:</strong>
                <span class="t4l-status-badge t4l-status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
            <p><strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}</p>
            <p><strong>Created:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
            <p><strong>Updated:</strong> {{ $order->updated_at->format('Y-m-d H:i') }}</p>
            <p><strong>Order Number:</strong> {{ $order->order_number ?? ('#'.$order->id) }}</p>

        </div>
    </div>

    <div style="margin-top:2rem;">
        <h2 style="font-size:1.1rem; margin-bottom:0.6rem;">Items</h2>

        <table class="t4l-cart-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Product</th>
                    <th style="width:80px;">Price</th>
                    <th style="width:60px;">Qty</th>
                    <th style="width:90px;">Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @forelse($items as $item)
                @php
                $productId = $item['product_id'] ?? null;
                $product = $productId && isset($products[$productId]) ? $products[$productId] : null;
                @endphp

                <tr>
                    <td>
                        @if($product && $product->image_path)
                        <img src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}"
                            style="width:50px; height:50px; object-fit:contain; border-radius:0.5rem; border:1px solid #e5e7eb;">
                        @else
                        <div
                            style="width:50px; height:50px; border-radius:0.5rem; border:1px dashed #e5e7eb; display:flex; align-items:center; justify-content:center; font-size:0.7rem; color:#9ca3af;">
                            No image
                        </div>
                        @endif
                    </td>
                    <td>
                        {{ $item['name'] ?? ($product->name ?? 'Unknown') }}

                        @if(!empty($item['color_name']))
                        <div style="font-size:0.8rem; color:#6b7280;">
                            Color: {{ $item['color_name'] }}
                        </div>
                        @endif
                    </td>

                    <td>${{ number_format($item['price'] ?? 0, 2) }}</td>
                    <td>{{ $item['quantity'] ?? 0 }}</td>
                    <td>${{ number_format($item['subtotal'] ?? 0, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No items found for this order.</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</section>
@endsection