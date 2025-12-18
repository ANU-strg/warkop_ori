<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index()
    {
        // Check if table session exists
        if (!session()->has('table')) {
            return redirect()->route('home')->with('error', 'Please scan a QR code first.');
        }

        // Check if cart is empty
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu')->with('error', 'Your cart is empty!');
        }

        $tableInfo = session('table');
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return view('checkout', compact('tableInfo', 'cart', 'subtotal'));
    }

    /**
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        // Validate payment method
        $request->validate([
            'payment_method' => 'required|in:online,cash',
        ]);

        // Check if table session exists
        if (!session()->has('table')) {
            return redirect()->route('home')->with('error', 'Please scan a QR code first.');
        }

        // Check if cart is empty
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu')->with('error', 'Your cart is empty!');
        }

        $tableInfo = session('table');

        // Calculate total
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Generate unique transaction ID
        $transactionId = 'TRX-' . strtoupper(Str::random(10));

        // Create order
        $order = Order::create([
            'table_id' => $tableInfo['id'],
            'transaction_id' => $transactionId,
            'status' => 'unpaid',
            'payment_method' => $request->payment_method,
            'total_amount' => $totalAmount,
        ]);

        // Create order items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        // If payment method is cash, redirect directly to success page
        if ($request->payment_method === 'cash') {
            // Clear cart
            session()->forget('cart');
            
            return redirect()->route('order.success', $order->id)
                ->with('info', 'Please pay at the cashier counter.');
        }

        // For online payment, get Midtrans Snap Token
        $snapToken = $this->getSnapToken($order);
        
        // Update order with snap token
        $order->update(['snap_token' => $snapToken]);

        // Clear cart
        session()->forget('cart');

        // Redirect to payment page
        return redirect()->route('payment', $order->id);
    }

    /**
     * Get Midtrans Snap Token
     */
    private function getSnapToken($order)
    {
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production', false);
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized', true);
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds', true);

        $params = [
            'transaction_details' => [
                'order_id' => $order->transaction_id,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => 'Table ' . $order->table->table_number,
                'email' => 'customer@warkop.com',
            ],
            'item_details' => [],
            'callbacks' => [
                'finish' => route('midtrans.finish'),
                'unfinish' => route('midtrans.unfinish'),
                'error' => route('midtrans.error'),
            ],
        ];

        // Add order items
        foreach ($order->orderItems as $item) {
            $params['item_details'][] = [
                'id' => $item->menu_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => $item->menu->name,
            ];
        }

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Midtrans Error: ' . $e->getMessage());
            // If Midtrans fails, return dummy token for testing
            return 'dummy-token-' . time();
        }
    }

    /**
     * Show payment page
     */
    public function payment($orderId)
    {
        $order = Order::with(['table', 'orderItems.menu'])->findOrFail($orderId);

        // Check if order belongs to current table session
        if (!session()->has('table') || session('table.id') != $order->table_id) {
            abort(403, 'Unauthorized access to this order.');
        }

        return view('payment', compact('order'));
    }

    /**
     * Order success page
     */
    public function success($orderId)
    {
        $order = Order::with(['table', 'orderItems.menu'])->findOrFail($orderId);

        // Check if order belongs to current table session
        if (!session()->has('table') || session('table.id') != $order->table_id) {
            abort(403, 'Unauthorized access to this order.');
        }

        return view('order-success', compact('order'));
    }
}
