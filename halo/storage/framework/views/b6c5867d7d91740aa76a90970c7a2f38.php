<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Orders Management</h2>
            <p class="text-gray-600">View and manage customer orders</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <form method="GET" action="<?php echo e(route('admin.orders.index')); ?>" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Search by Transaction ID..." 
                       value="<?php echo e(request('search')); ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="min-w-[150px]">
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="unpaid" <?php echo e(request('status') == 'unpaid' ? 'selected' : ''); ?>>Unpaid</option>
                    <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                    <option value="preparing" <?php echo e(request('status') == 'preparing' ? 'selected' : ''); ?>>Preparing</option>
                    <option value="ready" <?php echo e(request('status') == 'ready' ? 'selected' : ''); ?>>Ready</option>
                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Filter
            </button>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                Reset
            </a>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <?php if($orders->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Table</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($order->transaction_id); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Table <?php echo e($order->table->table_number); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($order->orderItems->count()); ?> items
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo e($order->payment_method === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                            <?php echo e($order->payment_method === 'cash' ? 'Cash' : 'Online'); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo e($order->status === 'completed' ? 'bg-green-100 text-green-800' : ''); ?>

                                            <?php echo e($order->status === 'ready' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                            <?php echo e($order->status === 'preparing' ? 'bg-purple-100 text-purple-800' : ''); ?>

                                            <?php echo e($order->status === 'paid' ? 'bg-teal-100 text-teal-800' : ''); ?>

                                            <?php echo e($order->status === 'unpaid' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                            <?php echo e($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : ''); ?>">
                                            <?php echo e(ucfirst($order->status)); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($order->created_at->format('d M Y, H:i')); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="text-blue-600 hover:text-blue-900">
                                            View
                                        </a>
                                        <?php if($order->payment_method === 'cash' && $order->status === 'unpaid'): ?>
                                            <form action="<?php echo e(route('admin.orders.markPaid', $order)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="text-green-600 hover:text-green-900" 
                                                        onclick="return confirm('Mark this order as paid?')">
                                                    Mark Paid
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <?php echo e($orders->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500">No orders found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Auto-refresh every 10 seconds
    let refreshInterval = setInterval(function() {
        // Preserve current filters when refreshing
        window.location.reload();
    }, 10000); // 10 seconds

    // Clear interval when user is about to leave the page
    window.addEventListener('beforeunload', function() {
        clearInterval(refreshInterval);
    });

    // Show last refresh time
    function updateLastRefresh() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const refreshBadge = document.getElementById('lastRefresh');
        if (refreshBadge) {
            refreshBadge.textContent = 'Last refresh: ' + timeString;
        }
    }

    // Update on page load
    updateLastRefresh();

    // Add refresh badge to page header
    window.addEventListener('DOMContentLoaded', function() {
        const pageHeader = document.querySelector('.space-y-6 > div:first-child');
        if (pageHeader && !document.getElementById('lastRefresh')) {
            const badge = document.createElement('div');
            badge.className = 'text-sm text-gray-500 mt-1';
            badge.id = 'lastRefresh';
            pageHeader.querySelector('div').appendChild(badge);
            updateLastRefresh();
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\Semester 7\Proyek Teknologi Informasi\warkop_ori\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>