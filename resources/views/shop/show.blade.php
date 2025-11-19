@extends('layouts.app')
@section('title', $product->name . ' | Trendy4Less Lebanon')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->description), 150))
@section('og_title', $product->name . ' | Trendy4Less')
@section('og_description', \Illuminate\Support\Str::limit(strip_tags($product->description), 150))
@section('og_image', asset('storage/'.$product->image_path))


@section('content')
<section class="t4l-section t4l-product-page t4l-fade-up">
    @php
    $defaultColor = $product->colors->firstWhere('is_default', true)
    ?? $product->colors->first();

    // what image to show first (default color image if exists, otherwise product main)
    $mainImagePath = $defaultColor?->image_path ?? $product->image_path;
    @endphp

    <div class="t4l-product-page-media">
        <div style="position:relative; display:inline-block;">
            <img src="{{ asset('storage/'.$mainImagePath) }}" alt="{{ $product->name }}"
                class="t4l-product-page-img t4l-product-main-img"
                data-main-img="{{ asset('storage/'.$mainImagePath) }}">
            @if($product->is_on_sale)
            <span class="t4l-sale-badge">Sale</span>
            @endif
        </div>
        @if($product->colors->count())
        <div class="t4l-color-swatches">
            {{-- Main image dot --}}
            <button type="button" class="t4l-color-dot t4l-color-dot--active"
                data-img="{{ asset('storage/'.$mainImagePath) }}" data-color-id="" title="Main"
                style="background:#ffffff; border:1px solid #e5e7eb;"></button>

            {{-- Color variants --}}
            @foreach($product->colors as $color)
            <button type="button" class="t4l-color-dot" data-img="{{ asset('storage/'.$color->image_path) }}"
                data-color-id="{{ $color->id }}" title="{{ $color->name }}"
                style="background: {{ $color->hex_color ?: '#e5e7eb' }};"></button>
            @endforeach
        </div>
        @endif
    </div>

    <div class="t4l-product-page-info">
        <span class="t4l-product-category">{{ $product->category->name ?? 'Category' }}</span>
        <h1>{{ $product->name }}</h1>
        <p class="t4l-product-page-description">{{ $product->description }}</p>
        <p class="t4l-product-page-price">
            @if($product->is_on_sale)
            <span class="t4l-product-price--old">
                ${{ number_format($product->price, 2) }}
            </span>
            <span class="t4l-product-price--sale" style="font-size:1.1rem;">
                ${{ number_format($product->effective_price, 2) }}
            </span>
            @else
            ${{ number_format($product->price, 2) }}
            @endif
        </p>

        <form method="POST" action="{{ route('cart.add', $product) }}" class="t4l-product-page-form">
            @csrf

            {{-- selected color will be set from the dots --}}
            <input type="hidden" name="color_id" id="selected-color-id" value="">

            <div class="t4l-qty-row">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1">
            </div>

            <button type="submit" class="t4l-btn-primary">Add to Cart</button>
        </form>
    </div>
</section>

@if($product->colors->count())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainImg   = document.querySelector('.t4l-product-main-img');
        const dots      = document.querySelectorAll('.t4l-color-dot');
        const colorInput = document.getElementById('selected-color-id');
        const form      = document.querySelector('.t4l-product-page-form');

        if (!mainImg || !dots.length || !colorInput || !form) return;

        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                const newImg  = dot.getAttribute('data-img');
                const colorId = dot.getAttribute('data-color-id');

                if (newImg) {
                    mainImg.src = newImg;
                }

                // "" for main image, or actual color id for variants
                colorInput.value = colorId || '';

                dots.forEach(d => d.classList.remove('t4l-color-dot--active'));
                dot.classList.add('t4l-color-dot--active');
            });
        });

        // If you want to *force* user to select a color, you can use this:
        // form.addEventListener('submit', function (e) {
        //     if (!colorInput.value && {{ $product->colors->count() }} > 0) {
        //         e.preventDefault();
        //         alert('Please choose a color before adding to cart.');
        //     }
        // });
    });
</script>
@endif
@endsection