@extends('layouts.app')
@section('title', 'Trendy4Less | Trendy Products with Delivery All Over Lebanon')
@section('meta_description', 'Trendy4Less is an online shop in Lebanon offering trendy products at affordable prices,
with fast delivery all over the country.')
@section('og_title', 'Trendy4Less | Online Shop in Lebanon')
@section('og_description', 'Shop trendy, affordable products with delivery all over Lebanon.')

@section('content')
<section class="t4l-hero">
    <div class="t4l-hero-text t4l-fade-up">
        <h1>Trendy4Less</h1>
        <p>Trendy products at smart prices. Shop online and enjoy fast delivery all over Lebanon.</p>
        <a href="{{ route('shop.index') }}" class="t4l-btn-primary">Shop Now</a>
    </div>
    <div class="t4l-hero-image t4l-fade-up t4l-delay-1">
        <div class="t4l-hero-card">
            <p>New Arrivals</p>
            <h2>Stay Trendy. Pay Less.</h2>
        </div>
    </div>
</section>

<section class="t4l-section t4l-fade-up t4l-delay-2">
    <h2 class="t4l-section-title">Why Trendy4Less?</h2>
    <div class="t4l-features">
        <div class="t4l-feature-card">
            <h3>Carefully Picked Items</h3>
            <p>We choose products that are trendy, practical, and budget-friendly.</p>
        </div>
        <div class="t4l-feature-card">
            <h3>Fast Delivery</h3>
            <p>Delivery to all Lebanese areas with clear, fair delivery fees.</p>
        </div>
        <div class="t4l-feature-card">
            <h3>Online Support</h3>
            <p>Need help choosing? Reach out to us anytime on WhatsApp or Instagram.</p>
        </div>
    </div>
</section>
@endsection