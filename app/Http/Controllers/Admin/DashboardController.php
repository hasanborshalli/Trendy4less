<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $productsCount = Product::count();
        $categoriesCount = Category::count();
        $ordersCount = Order::count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'productsCount',
            'categoriesCount',
            'ordersCount',
            'pendingOrdersCount'
        ));
    }
}