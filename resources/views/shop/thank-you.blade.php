@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up" style="max-width: 640px; text-align:center; margin-top:2.5rem;">
    <div class="t4l-thankyou-icon">
        ✓
    </div>

    <h1 class="t4l-section-title" style="margin-bottom:0.6rem;">
        Thank you for your order! 🎉
    </h1>

    @if($orderNumber)
    <p class="t4l-text-block" style="margin-bottom:0.4rem;">
        Your order number is <strong>{{ $orderNumber }}</strong>.
    </p>
    @endif


    <p class="t4l-text-block" style="margin-bottom:0.4rem;">
        Your order will be delivered within <strong>3–5 business days</strong>.
    </p>

    <p class="t4l-text-block" style="margin-bottom:1rem;">
        We’ll contact you on WhatsApp or phone to confirm your order and delivery details.
    </p>

    <a href="{{ route('shop.index') }}" class="t4l-btn-primary" style="justify-content:center;">
        Continue Shopping
    </a>
</section>
@endsection