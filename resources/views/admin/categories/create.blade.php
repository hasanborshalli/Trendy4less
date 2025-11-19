@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up" style="max-width: 640px;">
    <h1 class="t4l-section-title">Add Category</h1>

    <form method="POST" action="{{ route('admin.categories.store') }}" class="t4l-admin-login-form">
        @csrf

        <div class="t4l-form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <button type="submit" class="t4l-btn-primary" style="width:100%; justify-content:center; margin-top:0.5rem;">
            Save
        </button>
    </form>
</section>
@endsection