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

    // main image = default color image if exists, otherwise product main
    $mainImagePath = $defaultColor?->image_path ?? $product->image_path;
    @endphp

    <div class="t4l-product-page-media">
        <div style="position:relative; display:inline-block;">
            @if($mainImagePath)
            <img src="{{ asset('storage/'.$mainImagePath) }}" alt="{{ $product->name }}"
                class="t4l-product-page-img t4l-product-main-img"
                data-main-img="{{ asset('storage/'.$mainImagePath) }}">
            @else
            <div class="t4l-placeholder-image">No image</div>
            @endif

            @if($product->is_on_sale)
            <span class="t4l-sale-badge">Sale</span>
            @endif

            @unless($product->is_active)
            {{-- Out of stock overlay on image --}}
            <div class="t4l-out-of-stock-overlay">
                <span class="t4l-out-of-stock-text">Out of stock</span>
            </div>
            @endunless
        </div>

        @if($product->colors->count())
        <div class="t4l-color-swatches">
            {{-- Main image dot (Default) --}}
            @if($product->image_path)
            <button type="button" class="t4l-color-dot t4l-color-dot--main t4l-color-dot--active"
                data-img="{{ asset('storage/'.$product->image_path) }}" data-color-id="" title="Main image">
                <span>D</span>
            </button>
            @endif

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

        @unless($product->is_active)
        {{-- Out of stock info instead of form --}}
        <p class="t4l-product-out-label" style="margin-top:0.5rem; color:#b91c1c; font-weight:500;">
            This product is currently out of stock.
        </p>
        @else
        {{-- Only show Add to Cart form when product is active --}}
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
        @endunless
    </div>
</section>

@if($product->colors->count())
<div id="t4l-color-dialog" class="t4l-dialog-overlay" style="display:none;">
    <div class="t4l-dialog">
        <h2 class="t4l-dialog-title">Choose a color 🎨</h2>
        <p class="t4l-dialog-text">
            Please pick a color for this product before adding it to your cart.
        </p>
        <button type="button" class="t4l-btn-primary t4l-dialog-close">
            Got it
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
            const mainImg    = document.querySelector('.t4l-product-main-img');
            const dots       = document.querySelectorAll('.t4l-color-dot');
            const colorInput = document.getElementById('selected-color-id');
            const form       = document.querySelector('.t4l-product-page-form');
            const hasColors  = {{ $product->colors->count() }} > 0;
            const isActive   = {{ $product->is_active ? 'true' : 'false' }};


            // Color switching
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

            // Custom dialog instead of alert
            const dialog = document.getElementById('t4l-color-dialog');

            function openColorDialog() {
                if (dialog) {
                    dialog.style.display = 'flex';
                }
            }

            function closeColorDialog() {
                if (dialog) {
                    dialog.style.display = 'none';
                }
            }

            if (dialog) {
                // Close on button click
                dialog.querySelectorAll('.t4l-dialog-close').forEach(btn => {
                    btn.addEventListener('click', closeColorDialog);
                });

                // Close when clicking outside the box
                dialog.addEventListener('click', function (e) {
                    if (e.target === dialog) {
                        closeColorDialog();
                    }
                });
            }

            form.addEventListener('submit', function (e) {
                if (hasColors && !colorInput.value) {
                    e.preventDefault();
                    openColorDialog();
                }
            });
        });
</script>
@endif
@endsection