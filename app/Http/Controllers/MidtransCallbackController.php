<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    /**
     * Handle Midtrans payment notification/callback
     */
    public function receive(Request $request)
    {
        try {
            // Set Midtrans configuration
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production', false);

            // Create notification instance
            $notification = new \Midtrans\Notification();

            // Get transaction details
            $transactionStatus = $notification->transaction_status;
            $transactionId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            // Log notification for debugging
            Log::info('Midtrans Notification Received', [
                'transaction_id' => $transactionId,
                'status' => $transactionStatus,
                'fraud' => $fraudStatus
            ]);

            // Find order
            $order = Order::where('transaction_id', $transactionId)->first();

            if (!$order) {
                Log::error('Order not found for transaction: ' . $transactionId);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Handle different payment status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    // Payment success via credit card
                    $order->update([
                        'status' => 'paid',
                        'paid_at' => now()
                    ]);
                }
            } elseif ($transactionStatus == 'settlement') {
                // Payment success
                $order->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);
            } elseif ($transactionStatus == 'pending') {
                // Payment pending (e.g., bank transfer not yet paid)
                $order->update([
                    'status' => 'unpaid'
                ]);
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                // Payment denied/expired/cancelled
                $order->update([
                    'status' => 'cancelled'
                ]);
            }

            Log::info('Order updated successfully', [
                'order_id' => $order->id,
                'new_status' => $order->status
            ]);

            return response()->json(['message' => 'Notification processed successfully']);

        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    /**
     * Handle payment success redirect from Midtrans
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        
        if ($orderId) {
            $order = Order::where('transaction_id', $orderId)->first();
            
            if ($order) {
                // Update status to paid if not already
                if ($order->status === 'unpaid') {
                    $order->update([
                        'status' => 'paid',
                        'paid_at' => now()
                    ]);
                    
                    Log::info('Order marked as paid via finish callback', [
                        'order_id' => $order->id,
                        'transaction_id' => $orderId
                    ]);
                }
                
                return redirect()->route('order.success', $order->id)
                    ->with('success', 'Payment completed successfully!');
            }
        }

        return redirect()->route('menu')->with('error', 'Order not found');
    }

    /**
     * Handle payment failure/unfinish from Midtrans
     */
    public function unfinish(Request $request)
    {
        return redirect()->route('menu')
            ->with('warning', 'Payment was not completed. Please try again.');
    }

    /**
     * Handle payment error from Midtrans
     */
    public function error(Request $request)
    {
        return redirect()->route('menu')
            ->with('error', 'Payment failed. Please try again or contact support.');
    }
}
