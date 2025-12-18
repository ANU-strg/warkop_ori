<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Table <?php echo e($table->table_number); ?></h2>
            <p class="text-gray-600">View table details and QR code</p>
        </div>
        <a href="<?php echo e(route('admin.tables.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            ← Back
        </a>
    </div>

    <!-- Table Details -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Table Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Table Number</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?php echo e($table->table_number); ?></dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">UUID</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">
                                <div class="flex items-center gap-2">
                                    <span id="uuid-text"><?php echo e($table->uuid); ?></span>
                                    <button onclick="copyUUID()" class="text-blue-600 hover:text-blue-800 text-xs">
                                        📋 Copy
                                    </button>
                                </div>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Scan URL</dt>
                            <dd class="mt-1 text-sm text-blue-600">
                                <?php $scanUrl = config('app.url') . '/scan/' . $table->uuid; ?>
                                <a href="<?php echo e($scanUrl); ?>" target="_blank" class="hover:underline break-all">
                                    <?php echo e($scanUrl); ?>

                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?php echo e($table->created_at->format('d M Y, H:i')); ?></dd>
                        </div>
                    </dl>

                    <div class="mt-6 space-x-2">
                        <a href="<?php echo e(route('admin.tables.edit', $table)); ?>" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                            Edit Table
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">QR Code</h3>
                    <?php if($table->qr_code_image_path && file_exists(public_path($table->qr_code_image_path))): ?>
                        <div class="text-center">
                            <img src="<?php echo e(asset($table->qr_code_image_path)); ?>" alt="QR Code for Table <?php echo e($table->table_number); ?>" class="mx-auto border-4 border-gray-200 rounded-lg shadow-lg">
                            <p class="mt-4 text-sm text-gray-600">Scan this QR code to access the menu</p>
                            <a href="<?php echo e(asset($table->qr_code_image_path)); ?>" download="table-<?php echo e($table->table_number); ?>-qr.png" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Download QR Code
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center p-8 bg-gray-50 rounded-lg">
                            <p class="text-gray-500">QR Code not available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyUUID() {
    const uuidText = document.getElementById('uuid-text').innerText;
    navigator.clipboard.writeText(uuidText).then(() => {
        alert('UUID copied to clipboard!');
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\Semester 7\Proyek Teknologi Informasi\warkop_ori\resources\views/admin/tables/show.blade.php ENDPATH**/ ?>