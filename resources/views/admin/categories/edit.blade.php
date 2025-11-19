@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up" style="max-width: 640px;">
    <h1 class="t4l-section-title">Edit Category</h1>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="t4l-admin-login-form">
        @csrf
        @method('PUT')

        <div class="t4l-form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
        </div>

        <button type="submit" class="t4l-btn-primary" style="width:100%; justify-content:center; margin-top:0.5rem;">
            Update
        </button>
    </form>
</section>
@endsection