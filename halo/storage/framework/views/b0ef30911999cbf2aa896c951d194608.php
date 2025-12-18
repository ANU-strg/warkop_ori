<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Tables Management</h2>
            <p class="text-gray-600">Manage restaurant tables and QR codes</p>
        </div>
        <a href="<?php echo e(route('admin.tables.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
            + Add New Table
        </a>
    </div>

    <!-- Tables List -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <?php if($tables->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Table Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">UUID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Scan Link</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">QR Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($table->id); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Table <?php echo e($table->table_number); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600 font-mono">
                                        <span title="<?php echo e($table->uuid); ?>"><?php echo e(substr($table->uuid, 0, 8)); ?>...</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <?php $scanUrl = config('app.url') . '/scan/' . $table->uuid; ?>
                                        <a href="<?php echo e($scanUrl); ?>" target="_blank" class="text-blue-600 hover:text-blue-900 hover:underline">
                                            /scan/<?php echo e(substr($table->uuid, 0, 8)); ?>...
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php if($table->qr_code_image_path): ?>
                                            <a href="<?php echo e(route('admin.tables.show', $table)); ?>" class="text-blue-600 hover:text-blue-900">View QR</a>
                                        <?php else: ?>
                                            <span class="text-gray-400">No QR</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($table->created_at->format('d M Y')); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="<?php echo e(route('admin.tables.show', $table)); ?>" class="text-blue-600 hover:text-blue-900">View</a>
                                        <a href="<?php echo e(route('admin.tables.edit', $table)); ?>" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                        <form action="<?php echo e(route('admin.tables.destroy', $table)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <?php echo e($tables->links()); ?>

                </div>
            <?php else: ?>
                <p class="text-gray-500">No tables found. Create your first table!</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\Semester 7\Proyek Teknologi Informasi\warkop_ori\resources\views/admin/tables/index.blade.php ENDPATH**/ ?>