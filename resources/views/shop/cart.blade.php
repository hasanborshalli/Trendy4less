@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up">
    <h1 class="t4l-section-title">Your Cart</h1>

    @if(empty($items))
    <p>Your cart is empty.</p>
    @else
    <table class="t4l-cart-table">
        <thead>
            <tr>
                <th>Product</th>
                <th style="width:80px;">Qty</th>
                <th style="width:90px;">Price</th>
                <th style="width:110px;">Line Total</th>
                <th style="width:40px;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $key => $item)
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        @if(!empty($item['image_path']))
                        <img src="{{ asset('storage/'.$item['image_path']) }}" alt="{{ $item['name'] }}"
                            style="width:56px; height:56px; object-fit:contain; border-radius:0.5rem; border:1px solid #e5e7eb;">
                        @endif

                        <div>
                            <div>{{ $item['name'] }}</div>

                            @if(!empty($item['color_name']))
                            <div style="font-size:0.8rem; color:#6b7280; margin-top:0.1rem;">
                                Color: {{ $item['color_name'] }}
                            </div>
                            @endif
                        </div>
                    </div>
                </td>

                <td>{{ $item['quantity'] }}</td>
                <td>${{ number_format($item['price'], 2) }}</td>
                <td>${{ number_format($item['subtotal'], 2) }}</td>

                <td>
                    <form method="POST" action="{{ route('cart.remove', $key) }}">
                        @csrf
                        <button type="submit" class="t4l-cart-remove-btn" aria-label="Remove item">
                            &times;
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="t4l-cart-summary">
        <p>Total: <strong>${{ number_format($total, 2) }}</strong></p>

        <form method="POST" action="{{ route('cart.clear') }}">
            @csrf
            <button type="submit" class="t4l-btn-secondary">Clear Cart</button>
        </form>

        <a href="{{ route('checkout.show') }}" class="t4l-btn-primary">
            Proceed to Checkout
        </a>
    </div>
    @endif
</section>
@endsection