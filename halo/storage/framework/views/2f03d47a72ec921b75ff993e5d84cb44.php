<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Menu Management</h2>
            <p class="text-gray-600">Manage menu items</p>
        </div>
        <a href="<?php echo e(route('admin.menus.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
            + Add New Menu
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <?php if($menus->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border rounded-lg overflow-hidden hover:shadow-lg transition">
                            <?php if($menu->image_path && file_exists(public_path($menu->image_path))): ?>
                                <img src="<?php echo e(asset($menu->image_path)); ?>" alt="<?php echo e($menu->name); ?>" class="w-full h-48 object-cover">
                            <?php else: ?>
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No Image</span>
                                </div>
                            <?php endif; ?>
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900"><?php echo e($menu->name); ?></h3>
                                    <span class="px-2 py-1 text-xs rounded <?php echo e($menu->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e($menu->is_available ? 'Available' : 'Unavailable'); ?>

                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2"><?php echo e($menu->category->name); ?></p>
                                <p class="text-sm text-gray-500 mb-3 line-clamp-2"><?php echo e($menu->description); ?></p>
                                <p class="text-lg font-bold text-blue-600 mb-3">Rp <?php echo e(number_format($menu->price, 0, ',', '.')); ?></p>
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('admin.menus.edit', $menu)); ?>" class="flex-1 text-center px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">Edit</a>
                                    <form action="<?php echo e(route('admin.menus.destroy', $menu)); ?>" method="POST" class="flex-1" onsubmit="return confirm('Are you sure?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="w-full px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="mt-6"><?php echo e($menus->links()); ?></div>
            <?php else: ?>
                <p class="text-gray-500">No menu items found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\Semester 7\Proyek Teknologi Informasi\warkop_ori\resources\views/admin/menus/index.blade.php ENDPATH**/ ?>