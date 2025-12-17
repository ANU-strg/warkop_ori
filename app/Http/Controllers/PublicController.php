<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Table;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Handle QR code scan and store table info in session.
     * Route: GET /scan/{uuid}
     */
    public function scan($uuid)
    {
        // Find the table by UUID
        $table = Table::where('uuid', $uuid)->first();
        
        // If table not found, show error
        if (!$table) {
            abort(404, 'Table not found. Please scan a valid QR code.');
        }
        
        // Store table information in session
        session([
            'table' => [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'uuid' => $table->uuid,
            ]
        ]);
        
        // Redirect to the menu/order page
        return redirect()->route('menu')->with('success', "Welcome to Table {$table->table_number}!");
    }
    
    /**
     * Display the menu page with all items grouped by category.
     * Route: GET /menu
     */
    public function menu()
    {
        // Check if table session exists
        if (!session()->has('table')) {
            return redirect()->route('home')->with('error', 'Please scan a QR code first.');
        }
        
        $tableInfo = session('table');
        
        // Get all categories with their available menus
        $categories = Category::with(['menus' => function($query) {
            $query->where('is_available', true)->orderBy('name');
        }])->get();
        
        // Get cart from session
        $cart = session('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        
        return view('menu', compact('tableInfo', 'categories', 'cart', 'cartCount'));
    }
}
