<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Cart - Warkop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-4">
            <div class="flex items-center">
                <a href="<?php echo e(route('menu')); ?>" class="text-gray-600 hover:text-gray-800 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Your Cart</h1>
                    <p class="text-sm text-gray-600">Table <?php echo e($tableInfo['table_number']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <?php if(session('success')): ?>
        <div class="max-w-3xl mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <?php echo e(session('success')); ?>

            </div>
        </div>
    <?php endif; ?>

    <!-- Cart Content -->
    <div class="max-w-3xl mx-auto px-4 py-6">
        <?php if(!empty($cart)): ?>
            <div class="space-y-4 mb-6">
                <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuId => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <div class="flex items-start gap-4">
                            <!-- Image -->
                            <?php if($item['image_path'] && file_exists(public_path($item['image_path']))): ?>
                                <img src="<?php echo e(asset($item['image_path'])); ?>" alt="<?php echo e($item['name']); ?>" class="w-20 h-20 object-cover rounded flex-shrink-0">
                            <?php else: ?>
                                <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                                    <span class="text-gray-400 text-xs">No Image</span>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <!-- Header: Name & Price -->
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-800 text-lg"><?php echo e($item['name']); ?></h3>
                                        <p class="text-blue-600 font-medium mt-1">Rp <?php echo e(number_format($item['price'], 0, ',', '.')); ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-800">
                                            Rp <?php echo e(number_format($item['price'] * $item['quantity'], 0, ',', '.')); ?>

                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Notes Input -->
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Special Request</label>
                                    <textarea 
                                        id="note-<?php echo e($menuId); ?>" 
                                        name="notes[<?php echo e($menuId); ?>]"
                                        rows="2" 
                                        placeholder="Add note (e.g., Pedas, bagian dada, es sedikit...)" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                    ><?php echo e($item['notes'] ?? ''); ?></textarea>
                                    <p class="text-xs text-gray-500 mt-1">💡 Note will be saved when you checkout</p>
                                </div>
                                
                                <!-- Quantity Controls & Remove -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <form action="<?php echo e(route('cart.update', $menuId)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="quantity" value="<?php echo e(max(1, $item['quantity'] - 1)); ?>">
                                            <button type="submit" 
                                                    class="px-3 py-1 rounded-l transition-colors <?php echo e($item['quantity'] <= 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>"
                                                    <?php echo e($item['quantity'] <= 1 ? 'disabled' : ''); ?>>
                                                -
                                            </button>
                                        </form>
                                        
                                        <span class="bg-gray-100 px-4 py-1 text-gray-800 font-medium"><?php echo e($item['quantity']); ?></span>
                                        
                                        <form action="<?php echo e(route('cart.update', $menuId)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="quantity" value="<?php echo e($item['quantity'] + 1); ?>">
                                            <button type="submit" class="bg-gray-200 text-gray-700 px-3 py-1 rounded-r hover:bg-gray-300">+</button>
                                        </form>
                                    </div>
                                    
                                    <!-- Remove Button -->
                                    <form action="<?php echo e(route('cart.remove', $menuId)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Summary -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="text-lg font-bold text-gray-800">Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></span>
                </div>
            </div>

            <!-- Checkout Button -->
            <form id="checkout-form" method="POST" action="<?php echo e(route('cart.saveNotes')); ?>">
                <?php echo csrf_field(); ?>
                <div class="flex space-x-2">
                    <a href="<?php echo e(route('menu')); ?>" class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg text-center font-medium hover:bg-gray-400">
                        Add More Items
                    </a>
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg text-center font-medium hover:bg-blue-700">
                        Proceed to Checkout
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Your cart is empty</h3>
                <p class="text-gray-600 mb-6">Add some delicious items to get started!</p>
                <a href="<?php echo e(route('menu')); ?>" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700">
                    Browse Menu
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Collect notes before checkout
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            // Get all textareas
            const textareas = document.querySelectorAll('textarea[name^="notes"]');
            textareas.forEach(textarea => {
                // Create hidden input for each note
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = textarea.name;
                input.value = textarea.value;
                this.appendChild(input);
            });
        });
    </script>
</body>
</html>
<?php /**PATH D:\KULIAH\Semester 7\Proyek Teknologi Informasi\warkop_ori\resources\views/cart.blade.php ENDPATH**/ ?>