@extends('layouts.app')
@section('title', 'Shop | Trendy4Less Lebanon Online Store')
@section('meta_description', 'Browse all trendy products on Trendy4Less. Filter by category, search by name, and get
delivery all over Lebanon.')

@section('content')
<section class="t4l-section t4l-fade-up">
    <div class="t4l-shop-header">
        <h1 class="t4l-section-title">Shop</h1>
        <form method="GET" action="{{ route('shop.index') }}" class="t4l-shop-filters">
            <div class="t4l-filter-group">
                <label>Category</label>
                <select name="category" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->slug }}" {{ request('category')===$category->slug ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="t4l-filter-group">
                <label>Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products...">
            </div>

            <button type="submit" class="t4l-btn-secondary">Filter</button>
        </form>
    </div>

    <div class="t4l-product-grid">
        @forelse($products as $product)
        <div class="t4l-product-card t4l-fade-up">
            <a href="{{ route('shop.show', $product->slug) }}" class="t4l-product-image-wrapper">
                @if($product->image_path)
                <img src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}"
                    data-main-img="{{ asset('storage/'.$product->image_path) }}">
                @else
                <div class="t4l-placeholder-image">No image</div>
                @endif

                @if($product->is_on_sale)
                <span class="t4l-sale-badge">Sale</span>
                @endif
            </a>

            @if($product->colors->count())
            <div class="t4l-card-color-swatches">
                {{-- Main image dot --}}
                @if($product->image_path)
                <button type="button" class="t4l-card-color-dot t4l-card-color-dot--active" title="Main"
                    data-img="{{ asset('storage/'.$product->image_path) }}" data-color-id=""
                    style="background:#ffffff; border:1px solid #e5e7eb;"></button>
                @endif

                {{-- Color dots --}}
                @foreach($product->colors->take(4) as $color)
                <button type="button" class="t4l-card-color-dot" title="{{ $color->name }}"
                    data-img="{{ asset('storage/'.$color->image_path) }}" data-color-id="{{ $color->id }}"
                    style="background: {{ $color->hex_color ?: '#e5e7eb' }};"></button>
                @endforeach


                @if($product->colors->count() > 4)
                <span class="t4l-card-color-more">+{{ $product->colors->count() - 4 }}</span>
                @endif
            </div>
            @endif

            <div class="t4l-product-body">
                <span class="t4l-product-category">{{ $product->category->name ?? 'Category' }}</span>
                <h3 class="t4l-product-title">{{ $product->name }}</h3>
                <p class="t4l-product-description">
                    {{ \Illuminate\Support\Str::limit($product->description, 80) }}
                </p>
                <div class="t4l-product-footer">
                    @if($product->is_on_sale)
                    <span class="t4l-product-price t4l-product-price--old">
                        ${{ number_format($product->price, 2) }}
                    </span>
                    <span class="t4l-product-price t4l-product-price--sale">
                        ${{ number_format($product->effective_price, 2) }}
                    </span>
                    @else
                    <span class="t4l-product-price">
                        ${{ number_format($product->price, 2) }}
                    </span>
                    @endif
                    <form method="POST" action="{{ route('cart.add', $product) }}">
                        @csrf
                        <input type="hidden" name="color_id" class="t4l-card-color-input" value="">
                        <button type="submit" class="t4l-btn-primary t4l-btn-small">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>

        @empty
        <p>No products found.</p>
        @endforelse
    </div>

    <div class="t4l-pagination">
        {{ $products->links() }}
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.t4l-product-card');

        cards.forEach(card => {
            const img        = card.querySelector('.t4l-product-image-wrapper img');
            const dots       = Array.from(card.querySelectorAll('.t4l-card-color-dot'));
            const wrapper    = card.querySelector('.t4l-product-image-wrapper');
            const colorInput = card.querySelector('.t4l-card-color-input');

            if (!img || !dots.length || !wrapper || !colorInput) return;

            const mainSrc = img.getAttribute('data-main-img') || img.src;

            // Build list of images: main first, then each dot's image (unique)
            const images = [];
            if (mainSrc) images.push(mainSrc);

            dots.forEach(dot => {
                const url = dot.getAttribute('data-img');
                if (url && !images.includes(url)) {
                    images.push(url);
                }
            });

            let currentIndex = 0;

            function updateActiveDot() {
                const currentSrc = images[currentIndex];
                let activeDot = null;

                dots.forEach(dot => {
                    if (dot.getAttribute('data-img') === currentSrc) {
                        dot.classList.add('t4l-card-color-dot--active');
                        activeDot = dot;
                    } else {
                        dot.classList.remove('t4l-card-color-dot--active');
                    }
                });

                if (activeDot) {
                    const colorId = activeDot.getAttribute('data-color-id') || '';
                    colorInput.value = colorId;
                }
            }

            function setImageByIndex(index) {
                if (!images.length) return;
                currentIndex = ((index % images.length) + images.length) % images.length;
                img.src = images[currentIndex];
                updateActiveDot();
            }

            // Init: start on main image and set color_id accordingly (empty = main)
            setImageByIndex(0);

            // Dot click → set image & color_id
            dots.forEach(dot => {
                dot.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const url = dot.getAttribute('data-img');
                    const idx = images.indexOf(url);
                    if (idx !== -1) {
                        setImageByIndex(idx);
                    }
                });
            });

            // Swipe support on mobile
            let touchStartX = null;

            wrapper.addEventListener('touchstart', function (e) {
                if (e.touches.length > 1) return; // ignore multi-touch
                touchStartX = e.touches[0].clientX;
            }, { passive: true });

            wrapper.addEventListener('touchend', function (e) {
                if (touchStartX === null) return;

                const deltaX = e.changedTouches[0].clientX - touchStartX;
                const threshold = 40; // how much swipe to detect

                if (Math.abs(deltaX) > threshold) {
                    // This was a swipe, not a tap → don't open product link
                    e.preventDefault();
                    e.stopPropagation();

                    if (deltaX < 0) {
                        // swipe left → next image
                        setImageByIndex(currentIndex + 1);
                    } else {
                        // swipe right → previous image
                        setImageByIndex(currentIndex - 1);
                    }
                }

                touchStartX = null;
            });
        });
    });
</script>
@endsection