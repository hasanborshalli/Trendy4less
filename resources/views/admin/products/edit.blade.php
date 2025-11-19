@extends('layouts.app')

@section('content')
<section class="t4l-section t4l-fade-up" style="max-width: 640px;">
    <h1 class="t4l-section-title">Edit Product</h1>

    {{-- MAIN UPDATE FORM --}}
    <form method="POST" action="{{ route('admin.products.update', $product) }}" class="t4l-admin-login-form"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="t4l-form-group">
            <label>Category</label>
            <select name="category_id" required>
                <option value="">Choose category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ?
                    'selected':'' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="t4l-form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="t4l-form-group">
            <label>Description</label>
            <textarea name="description" rows="3">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="t4l-form-group">
            <label>Price</label>
            <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="t4l-form-group">
            <label>Sale Price (optional)</label>
            <input type="number" name="sale_price" step="0.01" min="0"
                value="{{ old('sale_price', $product->sale_price) }}">
            <small style="color:#6b7280; font-size:0.8rem;">
                Leave empty to remove the sale.
            </small>
        </div>

        <div class="t4l-form-group">
            <label>Image</label>
            @if($product->image_path)
            <div style="margin-bottom:0.5rem;">
                <img src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}"
                    style="max-width:120px; border-radius:0.5rem; border:1px solid #e5e7eb;">
            </div>
            @endif

            <input type="file" name="image" accept="image/*">
            <small style="color:#6b7280; font-size:0.8rem;">Leave empty to keep current image.</small>
        </div>

        {{-- ADD NEW COLOR VARIANTS (still part of update form) --}}
        <section style="margin-top:1.6rem;">
            <h2 style="font-size:1.0rem; margin-bottom:0.6rem;">Add New Color Variants</h2>

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

        <div class="t4l-form-group" style="flex-direction:row; align-items:center; gap:0.5rem; margin-top:1rem;">
            <input type="checkbox" id="is_active" name="is_active" {{ old('is_active', $product->is_active) ? 'checked'
            : '' }}>
            <label for="is_active" style="margin:0;">Active</label>
        </div>

        <button type="submit" class="t4l-btn-primary" style="width:100%; justify-content:center; margin-top:0.5rem;">
            Update
        </button>
    </form>

    {{-- EXISTING COLORS (SEPARATE FORMS FOR DELETE) --}}
    <section style="margin-top:1.6rem;">
        <h3 style="font-size:0.95rem; margin-bottom:0.5rem;">Existing Colors</h3>

        @if($product->colors->count())
        <table class="t4l-cart-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Hex</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->colors as $color)
                <tr>
                    <td>
                        <img src="{{ asset('storage/'.$color->image_path) }}" alt="{{ $color->name }}"
                            style="width:48px; height:48px; object-fit:contain; border-radius:0.5rem; border:1px solid #e5e7eb;">
                    </td>
                    <td>{{ $color->name }}</td>
                    <td>
                        @if($color->hex_color)
                        <span style="display:inline-flex; align-items:center; gap:0.4rem;">
                            <span
                                style="width:16px; height:16px; border-radius:999px; background:{{ $color->hex_color }}; border:1px solid #e5e7eb;"></span>
                            {{ $color->hex_color }}
                        </span>
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.products.colors.destroy', [$product, $color]) }}"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="t4l-cart-remove-btn" aria-label="Delete color">&times;</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="font-size:0.9rem; color:#6b7280;">No color variants yet.</p>
        @endif
    </section>
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