@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up">
    <h1 class="t4l-section-title">Admin Dashboard</h1>

    <p class="t4l-text-block" style="margin-bottom: 1.5rem;">
        Manage products, categories, and orders for Trendy4Less.
    </p>

    <div class="t4l-admin-grid">
        <a href="{{ route('admin.products.index') }}" class="t4l-admin-card">
            <h2>Products</h2>
            <p>{{ $productsCount }} total products</p>
        </a>

        <a href="{{ route('admin.categories.index') }}" class="t4l-admin-card">
            <h2>Categories</h2>
            <p>{{ $categoriesCount }} categories</p>
        </a>

        <a href="{{ route('admin.orders.index') }}" class="t4l-admin-card">
            <h2>Orders</h2>
            <p>{{ $ordersCount }} orders ({{ $pendingOrdersCount }} pending)</p>
        </a>
    </div>

    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:1.5rem;">
        @csrf
        <button type="submit" class="t4l-btn-secondary">Logout</button>
    </form>
</section>
@endsection