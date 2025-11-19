@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem;">
        <h1 class="t4l-section-title" style="margin-bottom:0;">Orders</h1>

        {{-- Optional manual create --}}
        {{-- <a href="{{ route('admin.orders.create') }}" class="t4l-btn-primary t4l-btn-small">+ Add Order</a> --}}
    </div>

    <table class="t4l-cart-table" style="margin-top:1rem;">
        <thead>
            <tr>
                <th>#</th>
                <th>Order No.</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Total</th>
                <th>Status</th>
                <th>Created</th>
                <th></th>
            </tr>

        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->order_number ?? '-' }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->customer_phone }}</td>
                <td>${{ number_format($order->total_amount, 2) }}</td>
                <td>
                    <span class="t4l-status-badge t4l-status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    {{-- View --}}
                    <a href="{{ route('admin.orders.show', $order) }}" class="t4l-cart-view-btn"
                        aria-label="View order">
                        <svg viewBox="0 0 24 24" class="t4l-cart-view-svg" aria-hidden="true">
                            <path d="M3 12s3.5-6 9-6 9 6 9 6-3.5 6-9 6-9-6-9-6Z" fill="none" stroke="currentColor"
                                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="12" cy="12" r="2.4" fill="none" stroke="currentColor" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>

                    {{-- Edit --}}
                    <a href="{{ route('admin.orders.edit', $order) }}" class="t4l-cart-edit-btn"
                        aria-label="Edit order">
                        <svg viewBox="0 0 24 24" class="t4l-cart-edit-svg" aria-hidden="true">
                            <path d="M5 19l1.5-4.5L15 6l3 3-8.5 8.5L5 19Z" fill="none" stroke="currentColor"
                                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M14 5.5 16.5 3 21 7.5 18.5 10" fill="none" stroke="currentColor" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>

                    {{-- Delete --}}
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="t4l-cart-remove-btn" aria-label="Delete order">&times;</button>
                    </form>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:1rem;">
        {{ $orders->links() }}
    </div>
</section>
@endsection