<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Trendy4Less</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- SEO --}}
    <title>@yield('title', 'Trendy4Less | Online Shop in Lebanon')</title>
    <meta name="description"
        content="@yield('meta_description', 'Trendy4Less is an online shop delivering trendy products all over Lebanon at affordable prices.')">

    {{-- Open Graph for social share --}}
    <meta property="og:title" content="@yield('og_title', 'Trendy4Less | Online Shop in Lebanon')" />
    <meta property="og:description"
        content="@yield('og_description', 'Shop trendy products with delivery all over Lebanon.')" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="@yield('og_url', url()->current())" />
    <meta property="og:image" content="@yield('og_image', asset('og-default.jpg'))" />

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    {{-- Using plain CSS from public, no Vite --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @php
    $orgLd = [
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'Trendy4Less',
    'url' => url('/'),
    'logo' => asset('favicon.png'),
    'sameAs' => [
    'https://instagram.com/trendy4less',
    ],
    ];
    @endphp

    <script type="application/ld+json">
        {!! json_encode($orgLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>


</head>

<body class="t4l-body">
    <header class="t4l-header">
        <div class="t4l-container t4l-nav">
            <a href="{{ route('home') }}" class="t4l-logo-wrap">
                {{-- Put your logo image in: public/images/trendy4less-logo.png --}}
                <img src="{{ asset('img/trendy4less-logo.jpg') }}" alt="Trendy4Less logo" class="t4l-logo-img">
                <span class="t4l-logo-text">Trendy4Less</span>
            </a>

            @php
            $isAdminRoute = request()->routeIs('admin.*');
            @endphp

            <nav class="t4l-nav-links">
                @if($isAdminRoute)
                {{-- Admin navigation --}}
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.products.index') }}">Products</a>
                <a href="{{ route('admin.categories.index') }}">Categories</a>
                <a href="{{ route('admin.orders.index') }}">Orders</a>
                @else
                {{-- Public site navigation --}}
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('shop.index') }}">Shop</a>
                <a href="{{ route('about') }}">About</a>

                @php
                $cartArray = session('cart', []);
                $cartDistinctCount = is_array($cartArray) ? count($cartArray) : 0;
                @endphp

                <a href="{{ route('cart.index') }}" class="t4l-cart-link" aria-label="Cart">
                    <span class="t4l-cart-icon-wrapper">
                        <span class="t4l-cart-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" class="t4l-cart-svg">
                                <path d="M3 4h2l2 12h10l2-8H8" fill="none" stroke="currentColor" stroke-width="1.6"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="10" cy="19" r="1.4" fill="currentValue" />
                                <circle cx="17" cy="19" r="1.4" fill="currentValue" />
                            </svg>
                        </span>

                        @if($cartDistinctCount > 0)
                        <span class="t4l-cart-badge">{{ $cartDistinctCount }}</span>
                        @endif
                    </span>
                </a>
                @endif
            </nav>



        </div>
    </header>

    <main class="t4l-main">
        <div class="t4l-container">
            @if(session('status'))
            <div class="t4l-alert">
                {{ session('status') }}
            </div>
            @endif

            @yield('content')
        </div>

        {{-- Moving sentence before footer --}}
        @include('partials.delivery-banner')
    </main>

    <footer class="t4l-footer">
        <div class="t4l-container t4l-footer-grid">
            {{-- Brand / description --}}
            <div class="t4l-footer-col">
                <div class="t4l-footer-brand">
                    {{-- Same logo file as navbar --}}
                    <img src="{{ asset('img/trendy4less-logo.jpg') }}" alt="Trendy4Less logo"
                        class="t4l-footer-logo-img">
                    <span class="t4l-footer-brand-name">Trendy4Less</span>
                </div>
                <p class="t4l-footer-text">
                    Trendy products at smart prices. Online shopping with delivery all over Lebanon.
                </p>
            </div>

            {{-- Quick links --}}
            <div class="t4l-footer-col">
                <h4 class="t4l-footer-title">Quick Links</h4>
                <ul class="t4l-footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('shop.index') }}">Shop</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('policies') }}">Policies</a></li>
                    <li><a href="{{ route('terms') }}">Terms &amp; Conditions</a></li>
                </ul>
            </div>

            {{-- Contact with platform logos --}}
            <div class="t4l-footer-col">
                <h4 class="t4l-footer-title">Contact Us</h4>
                <ul class="t4l-footer-links t4l-footer-social-list">
                    <li>
                        <a href="https://wa.me/96176912741?text=Hello%20Trendy4Less%2C%20I%20have%20a%20question%20about%20a%20product."
                            class="t4l-footer-social-link">
                            <span class="t4l-footer-social-icon">
                                {{-- Phone icon --}}
                                <img src="/img/call.svg" alt="">
                            </span>
                            <span>+961 76 912 741</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://instagram.com/trendy4less" target="_blank" class="t4l-footer-social-link">
                            <span class="t4l-footer-social-icon t4l-footer-social-icon--ig">
                                {{-- Instagram logo --}}
                                <svg viewBox="0 0 24 24" class="t4l-footer-social-svg">
                                    <rect x="4" y="4" width="16" height="16" rx="5" ry="5" fill="none"
                                        stroke="currentColor" stroke-width="1.6" />
                                    <circle cx="12" cy="12" r="3.3" fill="none" stroke="currentColor"
                                        stroke-width="1.6" />
                                    <circle cx="17" cy="7" r="1" fill="currentColor" />
                                </svg>
                            </span>
                            <span>@trendy4less</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/profile.php?id=100068628627192" target="_blank"
                            class="t4l-footer-social-link">
                            <span class="t4l-footer-social-icon t4l-footer-social-icon--fb">
                                {{-- Facebook logo --}}
                                <svg viewBox="0 0 24 24" class="t4l-footer-social-svg">
                                    <path
                                        d="M13 21v-7h2.5l.5-3H13V8.5c0-.9.3-1.5 1.7-1.5H16V4.2C15.7 4.1 14.8 4 13.8 4 11.4 4 10 5.3 10 8v3H7.5v3H10v7h3Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <span>Trendy4Less</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.tiktok.com/@trendy4less.shop" target="_blank"
                            class="t4l-footer-social-link">
                            <span class="t4l-footer-social-icon t4l-footer-social-icon--tt">
                                {{-- TikTok logo (simplified) --}}
                                <svg viewBox="0 0 24 24" class="t4l-footer-social-svg">
                                    <path
                                        d="M15 5.2c.7.9 1.6 1.6 2.7 1.9l.9.2v2.6a5.3 5.3 0 0 1-3.5-1.2v5.5a4.6 4.6 0 1 1-3.9-4.5v2.7a1.9 1.9 0 1 0 1.4 1.8V4.5h2.4v.7Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <span>@trendy4less</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="t4l-footer-bottom">
            <div class="t4l-container t4l-footer-bottom-inner">
                <p>© {{ date('Y') }} Trendy4Less. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @php
    $showWhatsAppFloat = request()->routeIs([
    'home',
    'shop.index',
    'about',
    'cart.index',
    'checkout.show',
    ]);
    @endphp

    @if($showWhatsAppFloat)
    <a href="https://wa.me/96176912741?text=Hello%20Trendy4Less%2C%20I%20have%20a%20question%20about%20a%20product."
        class="t4l-whatsapp-float" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
        <img src="/img/whatsapp.svg" alt="WhatsApp" class="t4l-whatsapp-icon">
    </a>
    @endif

</body>

</html>