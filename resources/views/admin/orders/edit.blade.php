@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up" style="max-width: 640px;">
    <h1 class="t4l-section-title">Edit Order #{{ $order->id }}</h1>

    @if ($errors->any())
    <div class="t4l-alert" style="background:#fee2e2; border-color:#fecaca; color:#b91c1c; margin-bottom:1rem;">
        <ul style="margin:0; padding-left:1.1rem;">
            @foreach ($errors->all() as $error)
            <li style="font-size:0.88rem;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="t4l-admin-login-form">
        @csrf
        @method('PUT')

        <div class="t4l-form-group">
            <label>Customer Name</label>
            <input type="text" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}" required>
        </div>

        <div class="t4l-form-group">
            <label>Phone</label>
            <input type="text" name="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}"
                required>
        </div>

        <div class="t4l-form-group">
            <label>Address</label>
            <textarea name="customer_address" rows="3"
                required>{{ old('customer_address', $order->customer_address) }}</textarea>
        </div>

        <div class="t4l-form-group">
            <label>Status</label>
            <select name="status" required>
                @php
                $statuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
                @endphp
                @foreach($statuses as $status)
                <option value="{{ $status }}" {{ old('status', $order->status) === $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="t4l-form-group">
            <label>Notes</label>
            <textarea name="notes" rows="2">{{ old('notes', $order->notes) }}</textarea>
        </div>

        <button type="submit" class="t4l-btn-primary" style="width:100%; justify-content:center; margin-top:0.5rem;">
            Save Changes
        </button>
    </form>
</section>
@endsection