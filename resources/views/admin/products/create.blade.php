@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up" style="max-width: 640px;">
    <h1 class="t4l-section-title">Add Product</h1>
    @if ($errors->any())
    <div class="t4l-alert" style="background:#fee2e2; border-color:#fecaca; color:#b91c1c; margin-bottom:1rem;">
        <ul style="margin:0; padding-left:1.1rem;">
            @foreach ($errors->all() as $error)
            <li style="font-size:0.88rem;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}" class="t4l-admin-login-form"
        enctype="multipart/form-data">
        @csrf

        <div class="t4l-form-group">
            <label>Category</label>
            <select name="category_id" required>
                <option value="">Choose category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected':'' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="t4l-form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="t4l-form-group">
            <label>Description</label>
            <textarea name="description" rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="t4l-form-group">
            <label>Price</label>
            <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" required>
        </div>
        <div class="t4l-form-group">
            <label>Sale Price (optional)</label>
            <input type="number" name="sale_price" step="0.01" min="0" value="{{ old('sale_price') }}">
            <small style="color:#6b7280; font-size:0.8rem;">
                Leave empty if there is no sale.
            </small>
        </div>

        <div class="t4l-form-group">
            <label>Main Image</label>
            <input type="file" name="image" accept="image/*" required>
        </div>

        <section style="margin-top:1.6rem;">
            <h2 style="font-size:1.0rem; margin-bottom:0.6rem;">Color Variants (optional)</h2>

            <div id="color-rows">
                <div class="t4l-color-row">
                    <div class="t4l-form-group">
                        <label>Color Name</label>
                        <input type="text" name="colors[0][name]" placeholder="Gold, Silver...">
                    </div>

                    <div class="t4l-form-group">
                        <label>Color Picker</label>
                        <input type="color" name="colors[0][hex_color]" value="#ffffff">
                    </div>

                    <div class="t4l-form-group">
                        <label>Color Image</label>
                        <input type="file" name="colors[0][image]" accept="image/*">
                    </div>
                </div>
            </div>

            <button type="button" class="t4l-btn-secondary t4l-btn-small" id="add-color-row" style="margin-top:0.4rem;">
                + Add another color
            </button>
        </section>

        <div class="t4l-form-group" style="flex-direction:row; align-items:center; gap:0.5rem;">
            <input type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
            <label for="is_active" style="margin:0;">Active</label>
        </div>

        <button type="submit" class="t4l-btn-primary" style="width:100%; justify-content:center; margin-top:0.5rem;">
            Save
        </button>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('color-rows');
        const addBtn = document.getElementById('add-color-row');
        let index = 1;

        if (addBtn) {
            addBtn.addEventListener('click', function () {
                const wrapper = document.createElement('div');
                wrapper.className = 't4l-color-row';
                wrapper.style.marginTop = '0.8rem';

                wrapper.innerHTML = `
                    <div class="t4l-form-group">
                        <label>Color Name</label>
                        <input type="text" name="colors[${index}][name]" placeholder="Gold, Silver...">
                    </div>

                    <div class="t4l-form-group">
                        <label>Color Picker</label>
                        <input type="color" name="colors[${index}][hex_color]" value="#ffffff">
                    </div>

                    <div class="t4l-form-group">
                        <label>Color Image</label>
                        <input type="file" name="colors[${index}][image]" accept="image/*">
                    </div>
                `;

                container.appendChild(wrapper);
                index++;
            });
        }
    });
</script>

@endsection