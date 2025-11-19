@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem;">
        <h1 class="t4l-section-title" style="margin-bottom:0;">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="t4l-btn-primary t4l-btn-small">+ Add Category</a>
    </div>

    <table class="t4l-cart-table" style="margin-top:1rem;">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Slug</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="t4l-cart-edit-btn"
                        aria-label="Edit category">
                        {{-- Pencil icon --}}
                        <svg viewBox="0 0 24 24" class="t4l-cart-edit-svg" aria-hidden="true">
                            <path d="M5 19l1.5-4.5L15 6l3 3-8.5 8.5L5 19Z" fill="none" stroke="currentColor"
                                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M14 5.5 16.5 3 21 7.5 18.5 10" fill="none" stroke="currentColor" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>

                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="t4l-cart-remove-btn" aria-label="Delete category">&times;</button>
                    </form>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection