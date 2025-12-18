<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $menu = Menu::findOrFail($request->menu_id);

        // Check if menu is available
        if (!$menu->is_available) {
            return back()->with('error', 'This item is currently unavailable.');
        }

        // Get cart from session
        $cart = session('cart', []);

        // If item already in cart, update quantity
        if (isset($cart[$menu->id])) {
            $cart[$menu->id]['quantity'] += $request->quantity;
            // Update notes if provided
            if ($request->filled('notes')) {
                $cart[$menu->id]['notes'] = $request->notes;
            }
        } else {
            // Add new item to cart
            $cart[$menu->id] = [
                'menu_id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $request->quantity,
                'image_path' => $menu->image_path,
                'notes' => $request->notes ?? null,
            ];
        }

        // Save cart to session
        session(['cart' => $cart]);

        return back()->with('success', "{$menu->name} added to cart!");
    }

    /**
     * View cart
     */
    public function index()
    {
        // Check if table session exists
        if (!session()->has('table')) {
            return redirect()->route('home')->with('error', 'Please scan a QR code first.');
        }

        $tableInfo = session('table');
        $cart = session('cart', []);
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return view('cart', compact('tableInfo', 'cart', 'subtotal'));
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $menuId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session('cart', []);

        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity'] = $request->quantity;
            session(['cart' => $cart]);
            
            return back()->with('success', 'Cart updated!');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    /**
     * Remove item from cart
     */
    public function remove($menuId)
    {
        $cart = session('cart', []);

        if (isset($cart[$menuId])) {
            unset($cart[$menuId]);
            session(['cart' => $cart]);
            
            return back()->with('success', 'Item removed from cart!');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        session()->forget('cart');
        
        return back()->with('success', 'Cart cleared!');
    }
}
