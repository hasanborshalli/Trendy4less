@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up" style="max-width: 640px;">
    <h1 class="t4l-section-title">Add Order</h1>

    @if ($errors->any())
    <div class="t4l-alert" style="background:#fee2e2; border-color:#fecaca; color:#b91c1c; margin-bottom:1rem;">
        <ul style="margin:0; padding-left:1.1rem;">
            @foreach ($errors->all() as $error)
            <li style="font-size:0.88rem;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.orders.store') }}" class="t4l-admin-login-form">
        @csrf

        <div class="t4l-form-group">
            <label>Customer Name</label>
            <input type="text" name="customer_name" value="{{ old('customer_name') }}" required>
        </div>

        <div class="t4l-form-group">
            <label>Phone</label>
            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required>
        </div>

        <div class="t4l-form-group">
            <label>Address</label>
            <textarea name="customer_address" rows="3" required>{{ old('customer_address') }}</textarea>
        </div>

        <div class="t4l-form-group">
            <label>Status</label>
            <select name="status" required>
                <option value="pending" {{ old('status')==='pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ old('status')==='confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="shipped" {{ old('status')==='shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ old('status')==='delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ old('status')==='cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div class="t4l-form-group">
            <label>Total (optional)</label>
            <input type="number" name="total_amount" step="0.01" min="0" value="{{ old('total_amount') }}">
        </div>

        <div class="t4l-form-group">
            <label>Notes</label>
            <textarea name="notes" rows="2">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="t4l-btn-primary" style="width:100%; justify-content:center; margin-top:0.5rem;">
            Create Order
        </button>
    </form>
</section>
@endsection