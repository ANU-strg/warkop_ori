<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - Warkop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Success Icon -->
            <div class="mb-6">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Order Placed Successfully!</h1>
                <p class="text-gray-600">Your order has been received and is being prepared</p>
            </div>

            <!-- Order Details -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm text-gray-600">Order ID</span>
                    <span class="font-mono text-sm font-semibold"><?php echo e($order->transaction_id); ?></span>
                </div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm text-gray-600">Table</span>
                    <span class="font-semibold"><?php echo e($order->table->table_number); ?></span>
                </div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm text-gray-600">Payment Method</span>
                    <span class="px-3 py-1 <?php echo e($order->payment_method === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?> text-xs font-semibold rounded-full">
                        <?php echo e($order->payment_method === 'cash' ? 'Cash' : 'Online'); ?>

                    </span>
                </div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm text-gray-600">Payment Status</span>
                    <span class="px-3 py-1 <?php echo e($order->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?> text-xs font-semibold rounded-full">
                        <?php echo e($order->status === 'paid' ? 'Paid' : 'Unpaid'); ?>

                    </span>
                </div>
                <div class="border-t pt-3 mt-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-800 font-bold">Total Amount</span>
                        <span class="text-xl font-bold text-green-600">Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="text-left mb-6">
                <h3 class="font-semibold text-gray-800 mb-3">Order Items:</h3>
                <div class="space-y-2">
                    <?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700"><?php echo e($item->quantity); ?>x <?php echo e($item->menu->name); ?></span>
                            <span class="text-gray-800">Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-3">
                <?php if($order->payment_method === 'cash' && $order->status === 'unpaid'): ?>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-left">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-yellow-800 mb-1">Payment Pending</p>
                                <p class="text-sm text-yellow-700">
                                    Please proceed to the cashier counter to complete your payment.
                                    Show this order ID: <strong><?php echo e($order->transaction_id); ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left">
                        <p class="text-sm text-blue-800">
                            <strong>Please wait at your table.</strong> Your order will be prepared and served shortly.
                        </p>
                    </div>
                <?php endif; ?>
                
                <a href="<?php echo e(route('menu')); ?>" class="block w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700">
                    Order More Items
                </a>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\KULIAH\Semester 7\Proyek Teknologi Informasi\warkop_ori\resources\views/order-success.blade.php ENDPATH**/ ?>