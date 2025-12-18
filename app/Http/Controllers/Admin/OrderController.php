<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display all orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['table', 'orderItems'])->latest();

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by transaction ID
        if ($request->has('search') && $request->search != '') {
            $query->where('transaction_id', 'like', '%' . $request->search . '%');
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show order detail
     */
    public function show(Order $order)
    {
        $order->load(['table', 'orderItems.menu']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Block manual update for online payment
        if ($order->payment_method === 'online') {
            return back()->with('error', 'Cannot manually update status for online payments. Status is managed by Midtrans automatically.');
        }

        // For cash payment, only allow unpaid/paid status
        if ($order->payment_method === 'cash') {
            $request->validate([
                'status' => 'required|in:unpaid,paid',
            ]);
        }

        $data = ['status' => $request->status];
        
        // If marking as paid, record the paid timestamp
        if ($request->status === 'paid' && $order->status !== 'paid') {
            $data['paid_at'] = now();
        }

        $order->update($data);

        return back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Mark cash order as paid (for cashier)
     */
    public function markAsPaid(Order $order)
    {
        // Only allow for cash payments that are unpaid
        if ($order->payment_method !== 'cash') {
            return back()->with('error', 'This order is not a cash payment!');
        }

        if ($order->status === 'paid') {
            return back()->with('info', 'Order is already marked as paid.');
        }

        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Order marked as paid successfully!');
    }
}
