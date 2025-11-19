<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

public function store(Request $request)
{
    $data = $request->validate([
        'category_id'           => ['required', 'exists:categories,id'],
        'name'                  => ['required', 'string', 'max:255'],
        'description'           => ['nullable', 'string'],
        'price'                 => ['required', 'numeric', 'min:0'],
        'sale_price'  => ['nullable', 'numeric', 'min:0'],
        'image'                 => ['required', 'image', 'max:2048'], // main image
        'colors'                => ['nullable', 'array'],
        'colors.*.name'         => ['nullable', 'string', 'max:255'],
        'colors.*.hex_color'    => ['nullable', 'string', 'max:20'],
        'colors.*.image'        => ['nullable', 'image', 'max:2048'],
    ]);
if (!empty($data['sale_price']) && $data['sale_price'] >= $data['price']) {
    $data['sale_price'] = null; // ignore invalid sale price
}

    $data['slug'] = \Illuminate\Support\Str::slug($data['name']) . '-' . \Illuminate\Support\Str::random(5);
    $data['is_active'] = $request->has('is_active');

    // main image
    $mainPath = $request->file('image')->store('products', 'public');
    $data['image_path'] = $mainPath;

    // remove colors from $data before create
    unset($data['colors']);

    $product = Product::create($data);

    // handle color variants
    if ($request->has('colors')) {
        foreach ($request->colors as $idx => $colorData) {
            // skip empty rows
            if (
                empty($colorData['name']) &&
                empty($colorData['hex_color']) &&
                !($request->file("colors.$idx.image"))
            ) {
                continue;
            }

            $imageFile = $request->file("colors.$idx.image");
            if (!$imageFile) {
                continue; // we require image for color variant
            }

            $colorPath = $imageFile->store('product-colors', 'public');

            ProductColor::create([
                'product_id' => $product->id,
                'name'       => $colorData['name'] ?? 'Color',
                'hex_color'  => $colorData['hex_color'] ?? null,
                'image_path' => $colorPath,
            ]);
        }
    }

    return redirect()->route('admin.products.index')->with('status', 'Product created.');
}


    public function edit(Product $product)
{
    $categories = Category::orderBy('name')->get();
    $product->load('colors');

    return view('admin.products.edit', compact('product', 'categories'));
}


  public function update(Request $request, Product $product)
{
    $data = $request->validate([
        'category_id'           => ['required', 'exists:categories,id'],
        'name'                  => ['required', 'string', 'max:255'],
        'description'           => ['nullable', 'string'],
        'price'                 => ['required', 'numeric', 'min:0'],
        'sale_price'  => ['nullable', 'numeric', 'min:0'],
        'image'                 => ['nullable', 'image', 'max:2048'], // main image (optional)
        'colors'                => ['nullable', 'array'],
        'colors.*.name'         => ['nullable', 'string', 'max:255'],
        'colors.*.hex_color'    => ['nullable', 'string', 'max:20'],
        'colors.*.image'        => ['nullable', 'image', 'max:2048'],
    ]);
if (!empty($data['sale_price']) && $data['sale_price'] >= $data['price']) {
    $data['sale_price'] = null; // ignore invalid sale price
}

    $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
    $data['is_active'] = $request->has('is_active');

    // main image update
    if ($request->hasFile('image')) {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $mainPath = $request->file('image')->store('products', 'public');
        $data['image_path'] = $mainPath;
    }

    unset($data['colors']);

    $product->update($data);

    // handle new color variants (we're not editing existing here, just adding)
    if ($request->has('colors')) {
        foreach ($request->colors as $idx => $colorData) {
            if (
                empty($colorData['name']) &&
                empty($colorData['hex_color']) &&
                !($request->file("colors.$idx.image"))
            ) {
                continue;
            }

            $imageFile = $request->file("colors.$idx.image");
            if (!$imageFile) {
                continue;
            }

            $colorPath = $imageFile->store('product-colors', 'public');

            ProductColor::create([
                'product_id' => $product->id,
                'name'       => $colorData['name'] ?? 'Color',
                'hex_color'  => $colorData['hex_color'] ?? null,
                'image_path' => $colorPath,
            ]);
        }
    }

    return redirect()->route('admin.products.index')->with('status', 'Product updated.');
}




    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('status', 'Product deleted.');
    }

    // show not needed for admin here, we use frontend show
    public function show(Product $product)
    {
        return redirect()->route('admin.products.edit', $product);
    }
}