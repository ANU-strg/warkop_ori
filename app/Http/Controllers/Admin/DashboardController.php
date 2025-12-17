<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tables' => Table::count(),
            'total_categories' => Category::count(),
            'total_menus' => Menu::count(),
            'active_orders' => Order::whereIn('status', ['pending', 'paid'])->count(),
        ];

        $recentOrders = Order::with(['table', 'orderItems.menu'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
