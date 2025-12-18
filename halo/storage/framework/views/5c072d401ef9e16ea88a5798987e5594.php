<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?> - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center gap-3">
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3">
        <img 
            src="<?php echo e(asset('images/logo.png')); ?>" 
            alt="Warung Mamcis"
            class="h-9 w-auto"
        >
        <span class="text-xl font-bold text-gray-800">
            Admin Warung Mamcis 
        </span>
    </a>
</div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('admin.dashboard') ? 'border-indigo-400' : 'border-transparent'); ?> text-sm font-medium text-gray-900">
                                Dashboard
                            </a>
                            <a href="<?php echo e(route('admin.orders.index')); ?>" class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('admin.orders.*') ? 'border-indigo-400' : 'border-transparent'); ?> text-sm font-medium text-gray-900">
                                Orders
                            </a>
                            <a href="<?php echo e(route('admin.tables.index')); ?>" class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('admin.tables.*') ? 'border-indigo-400' : 'border-transparent'); ?> text-sm font-medium text-gray-900">
                                Tables
                            </a>
                            <a href="<?php echo e(route('admin.categories.index')); ?>" class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('admin.categories.*') ? 'border-indigo-400' : 'border-transparent'); ?> text-sm font-medium text-gray-900">
                                Categories
                            </a>
                            <a href="<?php echo e(route('admin.menus.index')); ?>" class="inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('admin.menus.*') ? 'border-indigo-400' : 'border-transparent'); ?> text-sm font-medium text-gray-900">
                                Menus
                            </a>
                        </div>
                    </div>
                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
    <span class="text-sm font-semibold text-gray-800">
        Admin
    </span>
</div>

                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <?php if(session('success')): ?>
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\KULIAH\Semester 7\Proyek Teknologi Informasi\warkop_ori\resources\views/admin/layout.blade.php ENDPATH**/ ?>