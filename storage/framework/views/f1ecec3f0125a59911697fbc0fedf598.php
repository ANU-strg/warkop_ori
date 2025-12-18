<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Menu - Warkop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Fixed Header -->
    <div class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-3xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center mb-3">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">🍜 Warkop Menu</h1>
                    <p class="text-sm text-gray-600">Table <?php echo e($tableInfo['table_number']); ?></p>
                </div>
                <a href="<?php echo e(route('cart.index')); ?>" class="relative inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Cart
                    <?php if($cartCount > 0): ?>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center"><?php echo e($cartCount); ?></span>
                    <?php endif; ?>
                </a>
            </div>
            
            <!-- Search Bar -->
            <div class="relative">
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="Search menu..."
                    class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <button 
                    id="clearSearch"
                    class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 hidden"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
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

    <?php if(session('error')): ?>
        <div class="max-w-3xl mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?php echo e(session('error')); ?>

            </div>
        </div>
    <?php endif; ?>

    <!-- Menu Content -->
    <div class="max-w-3xl mx-auto px-4 py-6 pb-24">
        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php if($category->menus->count() > 0): ?>
                <!-- Category Header -->
                <div class="mb-6 category-section">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4"><?php echo e($category->name); ?></h2>
                    
                    <!-- Menu Items -->
                    <div class="space-y-4">
                        <?php $__currentLoopData = $category->menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition menu-item" data-name="<?php echo e(strtolower($menu->name)); ?>" data-description="<?php echo e(strtolower($menu->description ?? '')); ?>">
                                <div class="flex">
                                    <!-- Image -->
                                    <?php if($menu->image_path && file_exists(public_path($menu->image_path))): ?>
                                        <img src="<?php echo e(asset($menu->image_path)); ?>" alt="<?php echo e($menu->name); ?>" class="w-24 h-24 object-cover">
                                    <?php else: ?>
                                        <div class="w-24 h-24 bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">No Image</span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Info -->
                                    <div class="flex-1 p-4">
                                        <h3 class="font-semibold text-gray-800 mb-1"><?php echo e($menu->name); ?></h3>
                                        <?php if($menu->description): ?>
                                            <p class="text-sm text-gray-600 mb-2 line-clamp-2"><?php echo e($menu->description); ?></p>
                                        <?php endif; ?>
                                        <p class="text-lg font-bold text-blue-600">Rp <?php echo e(number_format($menu->price, 0, ',', '.')); ?></p>
                                    </div>
                                    
                                    <!-- Add Button -->
                                    <div class="flex items-center pr-4">
                                        <form action="<?php echo e(route('cart.add')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="menu_id" value="<?php echo e($menu->id); ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium">
                                                + Add
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-12">
                <p class="text-gray-500">No menu items available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Floating Cart Button (Mobile) -->
    <?php if($cartCount > 0): ?>
        <div class="fixed bottom-4 right-4 sm:hidden">
            <a href="<?php echo e(route('cart.index')); ?>" class="bg-blue-600 text-white rounded-full p-4 shadow-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center"><?php echo e($cartCount); ?></span>
            </a>
        </div>
    <?php endif; ?>

    <!-- No Results Message -->
    <div id="noResults" class="hidden max-w-3xl mx-auto px-4 py-12">
        <div class="text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-gray-500 text-lg mb-2">No menu found</p>
            <p class="text-gray-400 text-sm mb-4">Try searching with different keywords</p>
            <button onclick="clearSearch()" class="text-blue-600 hover:text-blue-800 font-medium">Clear search</button>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearch');
        const menuItems = document.querySelectorAll('.menu-item');
        const categorySections = document.querySelectorAll('.category-section');
        const noResults = document.getElementById('noResults');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            
            // Show/hide clear button
            clearBtn.classList.toggle('hidden', query === '');
            
            if (query === '') {
                // Show all items
                menuItems.forEach(item => item.classList.remove('hidden'));
                categorySections.forEach(section => section.classList.remove('hidden'));
                noResults.classList.add('hidden');
                return;
            }
            
            let hasResults = false;
            
            // Filter menu items
            categorySections.forEach(section => {
                const items = section.querySelectorAll('.menu-item');
                let sectionHasVisible = false;
                
                items.forEach(item => {
                    const name = item.dataset.name;
                    const description = item.dataset.description;
                    const matches = name.includes(query) || description.includes(query);
                    
                    if (matches) {
                        item.classList.remove('hidden');
                        sectionHasVisible = true;
                        hasResults = true;
                    } else {
                        item.classList.add('hidden');
                    }
                });
                
                // Show/hide category section
                if (sectionHasVisible) {
                    section.classList.remove('hidden');
                } else {
                    section.classList.add('hidden');
                }
            });
            
            // Show/hide no results message
            noResults.classList.toggle('hidden', hasResults);
        });

        clearBtn.addEventListener('click', clearSearch);

        function clearSearch() {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        }
    </script>
</body>
</html>
<?php /**PATH D:\KULIAH\Semester 7\Proyek Teknologi Informasi\warkop_ori\resources\views/menu.blade.php ENDPATH**/ ?>