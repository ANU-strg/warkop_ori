<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Order Details</h2>
            <p class="text-gray-600"><?php echo e($order->transaction_id); ?></p>
        </div>
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            ← Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center border-b pb-4 last:border-b-0">
                            <?php if($item->menu->image_path && file_exists(public_path($item->menu->image_path))): ?>
                                <img src="<?php echo e(asset($item->menu->image_path)); ?>" alt="<?php echo e($item->menu->name); ?>" class="w-16 h-16 object-cover rounded">
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-400 text-xs">No Image</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex-1 ml-4">
                                <h4 class="font-semibold text-gray-800"><?php echo e($item->menu->name); ?></h4>
                                <p class="text-sm text-gray-600"><?php echo e($item->menu->category->name); ?></p>
                                <p class="text-sm text-gray-500">Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?> x <?php echo e($item->quantity); ?></p>
                                <?php if($item->notes): ?>
                                    <div class="mt-2 bg-yellow-50 border border-yellow-200 rounded p-2">
                                        <p class="text-xs text-gray-700">
                                            <span class="font-semibold text-yellow-800">📝 Note:</span> 
                                            <span class="text-gray-800"><?php echo e($item->notes); ?></span>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800">
                                    Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div class="border-t mt-4 pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-800">Total Amount</span>
                        <span class="text-2xl font-bold text-blue-600">
                            Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary & Actions -->
        <div class="space-y-6">
            <!-- Order Info Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono"><?php echo e($order->transaction_id); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Table</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo e($order->table->table_number); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo e($order->status === 'completed' ? 'bg-green-100 text-green-800' : ''); ?>

                                <?php echo e($order->status === 'ready' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                <?php echo e($order->status === 'preparing' ? 'bg-purple-100 text-purple-800' : ''); ?>

                                <?php echo e($order->status === 'paid' ? 'bg-teal-100 text-teal-800' : ''); ?>

                                <?php echo e($order->status === 'unpaid' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                <?php echo e($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : ''); ?>">
                                <?php echo e(ucfirst($order->status)); ?>

                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo e($order->payment_method === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                <?php echo e($order->payment_method === 'cash' ? 'Cash' : 'Online'); ?>

                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo e($order->created_at->format('d M Y, H:i')); ?></dd>
                    </div>
                    <?php if($order->paid_at): ?>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Paid At</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?php echo e($order->paid_at->format('d M Y, H:i')); ?></dd>
                        </div>
                    <?php endif; ?>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Items</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo e($order->orderItems->count()); ?> items</dd>
                    </div>
                    <?php if($order->snap_token): ?>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Token</dt>
                            <dd class="mt-1 text-xs text-gray-600 font-mono break-all"><?php echo e($order->snap_token); ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>

            <!-- Status Update Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Status</h3>
                
                <?php if($order->payment_method === 'cash' && $order->status === 'unpaid'): ?>
                    <!-- Quick Mark as Paid for Cash Orders -->
                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start mb-3">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-yellow-800 mb-1">Cash Payment - Unpaid</p>
                                <p class="text-sm text-yellow-700">Customer needs to pay at cashier</p>
                            </div>
                        </div>
                        <form action="<?php echo e(route('admin.orders.markPaid', $order)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 font-medium"
                                    onclick="return confirm('Confirm that customer has paid Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>?')">
                                ✓ Mark as Paid
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if($order->payment_method === 'online'): ?>
                    <!-- Info for Online Payment -->
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-blue-800 mb-1">Online Payment via Midtrans</p>
                                <p class="text-sm text-blue-700">Status is automatically managed by Midtrans. Manual updates are disabled for online payments.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Show current status only, no update form -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">Current Status:</p>
                            <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full 
                                <?php echo e($order->status === 'completed' ? 'bg-green-100 text-green-800' : ''); ?>

                                <?php echo e($order->status === 'ready' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                <?php echo e($order->status === 'preparing' ? 'bg-purple-100 text-purple-800' : ''); ?>

                                <?php echo e($order->status === 'paid' ? 'bg-teal-100 text-teal-800' : ''); ?>

                                <?php echo e($order->status === 'unpaid' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                <?php echo e($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : ''); ?>">
                                <?php echo e(ucfirst($order->status)); ?>

                            </span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($order->payment_method === 'cash'): ?>
                <form action="<?php echo e(route('admin.orders.updateStatus', $order)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    
                    <div class="space-y-3 mb-4">
                        <?php if($order->payment_method === 'cash'): ?>
                            <!-- Simple status for cash payment -->
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 <?php echo e($order->status === 'unpaid' ? 'bg-yellow-50 border-yellow-200' : ''); ?>">
                                <input type="radio" name="status" value="unpaid" <?php echo e($order->status === 'unpaid' ? 'checked' : ''); ?> class="mr-3">
                                <div>
                                    <div class="font-medium text-gray-800">Unpaid</div>
                                    <div class="text-xs text-gray-500">Waiting for cash payment</div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 <?php echo e($order->status === 'paid' ? 'bg-teal-50 border-teal-200' : ''); ?>">
                                <input type="radio" name="status" value="paid" <?php echo e($order->status === 'paid' ? 'checked' : ''); ?> class="mr-3">
                                <div>
                                    <div class="font-medium text-gray-800">Paid</div>
                                    <div class="text-xs text-gray-500">Cash received</div>
                                </div>
                            </label>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-medium">
                        Update Status
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\Semester 7\Proyek Teknologi Informasi\warkop_ori\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>