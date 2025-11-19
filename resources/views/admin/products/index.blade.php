@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem;">
        <h1 class="t4l-section-title" style="margin-bottom:0;">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="t4l-btn-primary t4l-btn-small">+ Add Product</a>
    </div>

    <table class="t4l-cart-table" style="margin-top:1rem;">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Active</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>{{ $product->is_active ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('admin.products.edit', $product) }}" class="t4l-cart-edit-btn"
                        aria-label="Edit product">
                        {{-- Pencil icon --}}
                        <svg viewBox="0 0 24 24" class="t4l-cart-edit-svg" aria-hidden="true">
                            <path d="M5 19l1.5-4.5L15 6l3 3-8.5 8.5L5 19Z" fill="none" stroke="currentColor"
                                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M14 5.5 16.5 3 21 7.5 18.5 10" fill="none" stroke="currentColor" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>

                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="t4l-cart-remove-btn" aria-label="Delete product">&times;</button>
                    </form>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:1rem;">
        {{ $products->links() }}
    </div>
</section>
@endsection