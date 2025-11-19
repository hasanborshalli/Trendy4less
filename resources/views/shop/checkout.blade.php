@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up">
    <h1 class="t4l-section-title">Checkout</h1>

    @if ($errors->any())
    <div class="t4l-alert" style="background:#fee2e2; border-color:#fecaca; color:#b91c1c; margin-bottom:1rem;">
        <ul style="margin:0; padding-left:1.1rem;">
            @foreach ($errors->all() as $error)
            <li style="font-size:0.88rem;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="t4l-checkout-layout">
        {{-- Order summary --}}
        <div>
            <h2 style="font-size:1.1rem; margin-bottom:0.7rem;">Order Summary</h2>

            <table class="t4l-cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="width:60px;">Qty</th>
                        <th style="width:90px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $row)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                @if(!empty($row['image_path']))
                                <img src="{{ asset('storage/'.$row['image_path']) }}" alt="{{ $row['name'] }}"
                                    style="width:56px; height:56px; object-fit:contain; border-radius:0.5rem; border:1px solid #e5e7eb;">
                                @endif

                                <div>
                                    <div>{{ $row['name'] }}</div>
                                    @if(!empty($row['color_name']))
                                    <div style="font-size:0.8rem; color:#6b7280;">
                                        Color: {{ $row['color_name'] }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $row['quantity'] }}</td>
                        <td>${{ number_format($row['price'], 2) }}</td>
                        <td>${{ number_format($row['subtotal'], 2) }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            <div class="t4l-cart-summary" style="margin-top:1rem;">
                <div>
                    <strong>Total:</strong>
                </div>
                <div>
                    <strong>${{ number_format($total, 2) }}</strong>
                </div>
            </div>
        </div>

        {{-- Customer info form --}}
        <div>
            <h2 style="font-size:1.1rem; margin-bottom:0.7rem;">Your Details</h2>

            <form method="POST" action="{{ route('checkout.place') }}" class="t4l-admin-login-form">
                @csrf

                <div class="t4l-form-group">
                    <label for="customer_name">Full Name</label>
                    <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}"
                        required>
                </div>

                <div class="t4l-form-group">
                    <label for="customer_phone">Phone / WhatsApp</label>
                    <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}"
                        required>
                </div>

                <div class="t4l-form-group">
                    <label for="customer_address">Address (City, Area, Street, etc.)</label>
                    <textarea id="customer_address" name="customer_address" rows="3"
                        required>{{ old('customer_address') }}</textarea>
                </div>

                <div class="t4l-form-group">
                    <label for="notes">Notes (optional)</label>
                    <textarea id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="t4l-btn-primary"
                    style="width:100%; justify-content:center; margin-top:0.5rem;">
                    Place Order
                </button>

                <p style="margin-top:0.6rem; font-size:0.8rem; color:#6b7280;">
                    We will contact you on WhatsApp or phone to confirm your order and delivery details.
                </p>
            </form>
        </div>
    </div>
</section>
@endsection