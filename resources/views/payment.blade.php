<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment - Warkop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="mx-auto h-16 w-16 text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Complete Your Payment</h1>
                <p class="text-gray-600">Order #{{ $order->transaction_id }}</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Table</span>
                    <span class="font-semibold">{{ $order->table->table_number }}</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Items</span>
                    <span class="font-semibold">{{ $order->orderItems->count() }} items</span>
                </div>
                <div class="flex justify-between items-center border-t pt-2">
                    <span class="text-lg font-bold">Total</span>
                    <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <button id="pay-button" class="w-full bg-blue-600 text-white px-6 py-4 rounded-lg text-lg font-medium hover:bg-blue-700 mb-4">
                Pay Now
            </button>

            <p class="text-sm text-gray-500">
                Secure payment powered by Midtrans
            </p>
        </div>
    </div>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            @if($order->snap_token && !str_starts_with($order->snap_token, 'dummy'))
                snap.pay('{{ $order->snap_token }}', {
                    onSuccess: function(result){
                        // Update status via AJAX before redirect
                        fetch('{{ route("midtrans.finish") }}?order_id={{ $order->transaction_id }}', {
                            method: 'GET'
                        }).then(() => {
                            window.location.href = '{{ route("order.success", $order->id) }}';
                        });
                    },
                    onPending: function(result){
                        window.location.href = '{{ route("order.success", $order->id) }}';
                    },
                    onError: function(result){
                        alert('Payment failed! ' + result.status_message);
                    },
                    onClose: function(){
                        alert('You closed the payment popup without finishing the payment');
                    }
                });
            @else
                // For testing without Midtrans
                if(confirm('Midtrans not configured. Proceed to success page for testing?')) {
                    window.location.href = '{{ route("order.success", $order->id) }}';
                }
            @endif
        };
    </script>
</body>
</html>
