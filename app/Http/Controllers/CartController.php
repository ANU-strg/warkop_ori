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

        // Check if request is AJAX
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$menu->name} added to cart!",
                'cartCount' => array_sum(array_column($cart, 'quantity'))
            ]);
        }

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
            
            // Check if request is AJAX
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated!',
                    'cartCount' => array_sum(array_column($cart, 'quantity'))
                ]);
            }
            
            return back()->with('success', 'Cart updated!');
        }

        // Check if request is AJAX
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.'
            ], 404);
        }

        return back()->with('error', 'Item not found in cart.');
    }

    /**
     * Update notes for cart item
     */
    public function updateNote(Request $request, $menuId)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = session('cart', []);

        if (isset($cart[$menuId])) {
            $cart[$menuId]['notes'] = $request->notes;
            session(['cart' => $cart]);
            
            return back()->with('success', 'Note updated!');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, $menuId)
    {
        $cart = session('cart', []);

        if (isset($cart[$menuId])) {
            unset($cart[$menuId]);
            session(['cart' => $cart]);
            
            // Check if request is AJAX
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart!',
                    'cartCount' => array_sum(array_column($cart, 'quantity'))
                ]);
            }
            
            return back()->with('success', 'Item removed from cart!');
        }

        // Check if request is AJAX
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.'
            ], 404);
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

    /**
     * Save notes for all cart items and redirect to checkout
     */
    public function saveNotes(Request $request)
    {
        $cart = session('cart', []);
        $notes = $request->input('notes', []);

        // Update notes for each item in cart
        foreach ($notes as $menuId => $note) {
            if (isset($cart[$menuId])) {
                $cart[$menuId]['notes'] = $note ? trim($note) : null;
            }
        }

        // Save updated cart to session
        session(['cart' => $cart]);

        // Redirect to checkout page
        return redirect()->route('checkout');
    }
}
