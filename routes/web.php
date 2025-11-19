<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductColorController as AdminProductColorController;

Route::get('/sitemap.xml', [SitemapController::class, 'index']);

// Pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/policies', [PageController::class, 'policies'])->name('policies');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

//ordering
Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout.show');
Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('checkout.place');
Route::get('/thank-you', [OrderController::class, 'thankYou'])->name('order.thankyou');


// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Admin auth (no middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
});
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Admin protected area
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('orders', AdminOrderController::class);

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::post('products/{product}/colors', [AdminProductColorController::class, 'store'])->name('products.colors.store');
    Route::delete('products/{product}/colors/{color}', [AdminProductColorController::class, 'destroy'])->name('products.colors.destroy');

});