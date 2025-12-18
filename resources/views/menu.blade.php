<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu - Warkop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .category-filter-btn {
            background-color: #e5e7eb;
            color: #374151;
        }
        .category-filter-btn.active {
            background-color: #2563eb;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Fixed Header -->
    <div class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-3xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center gap-3">
    <img 
        src="{{ asset('images/logo.png') }}" 
        alt="Warung Mamcis"
        class="h-10 w-auto"
    >

    <div>
        <h1 class="text-xl font-bold text-gray-800">
            Warung Mamcis
        </h1>
        <p class="text-sm text-gray-600">
            Table {{ $tableInfo['table_number'] }}
        </p>
    </div>
</div>

                <a href="{{ route('cart.index') }}" class="relative inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Cart
                    <span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center {{ $cartCount > 0 ? '' : 'hidden' }}">{{ $cartCount }}</span>
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
    @if(session('success'))
        <div class="max-w-3xl mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-3xl mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Pending Payment Alert -->
    @if(isset($pendingOrder) && $pendingOrder)
        <div class="max-w-3xl mx-auto px-4 mt-4">
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-lg shadow-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-lg font-semibold text-amber-800 mb-1">⚠️ Pending Payment!</h3>
                        <p class="text-sm text-amber-700 mb-3">
                            You have an unpaid order <strong class="font-mono">#{{ $pendingOrder->order_number }}</strong>
                            <br>
                            <span class="text-amber-600">Total: Rp {{ number_format($pendingOrder->total_amount, 0, ',', '.') }}</span>
                        </p>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('payment', $pendingOrder->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-lg transition shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Complete Payment Now
                            </a>
                            <button onclick="if(confirm('Are you sure you want to cancel this order? This action cannot be undone.')) { document.getElementById('cancel-form-{{ $pendingOrder->id }}').submit(); }" class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                                Cancel Order
                            </button>
                            <form id="cancel-form-{{ $pendingOrder->id }}" action="{{ route('order.cancel', $pendingOrder->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Category Filter -->
    <div class="max-w-3xl mx-auto px-4 mt-4">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Filter by Category</h3>
            <div class="flex flex-wrap gap-2">
                <button onclick="filterByCategory('all')" class="category-filter-btn active px-4 py-2 rounded-full text-sm font-medium transition-all" data-category="all">
                    All Menu
                </button>
                @foreach($categories as $category)
                    <button onclick="filterByCategory('{{ $category->id }}')" class="category-filter-btn px-4 py-2 rounded-full text-sm font-medium transition-all" data-category="{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Menu Content -->
    <div class="max-w-3xl mx-auto px-4 py-6 pb-24">
        @forelse($categories as $category)
            @if($category->menus->count() > 0)
                <!-- Category Header -->
                <div class="mb-6 category-section" data-category-id="{{ $category->id }}">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $category->name }}</h2>
                    
                    <!-- Menu Items -->
                    <div class="space-y-4">
                        @foreach($category->menus as $menu)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition menu-item" data-name="{{ strtolower($menu->name) }}" data-description="{{ strtolower($menu->description ?? '') }}">
                                <div class="flex">
                                    <!-- Clickable Area for Details -->
                                    <div class="flex flex-1 cursor-pointer" onclick="showMenuDetail({{ $menu->id }})">
                                        <!-- Image -->
                                        @if($menu->image_path && file_exists(public_path($menu->image_path)))
                                            <img src="{{ asset($menu->image_path) }}" alt="{{ $menu->name }}" class="w-24 h-24 object-cover">
                                        @else
                                            <div class="w-24 h-24 bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No Image</span>
                                            </div>
                                        @endif
                                        
                                        <!-- Info -->
                                        <div class="flex-1 p-4">
                                            <h3 class="font-semibold text-gray-800 mb-1">{{ $menu->name }}</h3>
                                            @if($menu->description)
                                                <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ $menu->description }}</p>
                                            @endif
                                            <p class="text-lg font-bold text-blue-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Quantity Control / Add Button -->
                                    <div class="flex items-center pr-4">
                                        @php
                                            $inCart = isset($cart[$menu->id]);
                                            $quantity = $inCart ? $cart[$menu->id]['quantity'] : 0;
                                        @endphp
                                        
                                        @if($inCart)
                                            <!-- Quantity Controls -->
                                            <div class="flex items-center bg-blue-50 rounded-lg">
                                                <button onclick="updateQuantity({{ $menu->id }}, -1)" class="px-3 py-2 text-blue-600 hover:bg-blue-100 rounded-l-lg transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                    </svg>
                                                </button>
                                                <span class="px-4 py-2 text-blue-700 font-bold min-w-[40px] text-center" id="qty-{{ $menu->id }}">{{ $quantity }}</span>
                                                <button onclick="updateQuantity({{ $menu->id }}, 1)" class="px-3 py-2 text-blue-600 hover:bg-blue-100 rounded-r-lg transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @else
                                            <!-- Add Button -->
                                            <form class="add-to-cart-form" data-menu-id="{{ $menu->id }}">
                                                @csrf
                                                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition-all">
                                                    + Add
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Hidden data for modal -->
                                <div class="hidden menu-data" 
                                     data-id="{{ $menu->id }}"
                                     data-name="{{ $menu->name }}"
                                     data-description="{{ $menu->description ?? 'No description available.' }}"
                                     data-price="{{ $menu->price }}"
                                     data-price-formatted="Rp {{ number_format($menu->price, 0, ',', '.') }}"
                                     data-category="{{ $menu->category->name ?? 'Uncategorized' }}"
                                     data-image="{{ $menu->image_path && file_exists(public_path($menu->image_path)) ? asset($menu->image_path) : '' }}"
                                     data-available="{{ $menu->is_available ? 'true' : 'false' }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500">No menu items available at the moment.</p>
            </div>
        @endforelse
    </div>

    <!-- Floating Cart Button (Mobile) -->
    <div id="floatingCart" class="fixed bottom-4 right-4 sm:hidden {{ $cartCount > 0 ? '' : 'hidden' }}">
        <a href="{{ route('cart.index') }}" class="bg-blue-600 text-white rounded-full p-4 shadow-lg flex items-center justify-center relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center">{{ $cartCount }}</span>
        </a>
    </div>

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

    <!-- Menu Detail Modal -->
    <div id="menuDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Menu Detail</h2>
                <button onclick="closeMenuDetail()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Image -->
                <div id="modalImage" class="mb-6">
                    <!-- Will be populated by JS -->
                </div>

                <!-- Category Badge -->
                <div class="mb-4">
                    <span id="modalCategory" class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                        <!-- Will be populated by JS -->
                    </span>
                    <span id="modalAvailability" class="inline-block ml-2">
                        <!-- Will be populated by JS -->
                    </span>
                </div>

                <!-- Name -->
                <h3 id="modalName" class="text-2xl font-bold text-gray-800 mb-3">
                    <!-- Will be populated by JS -->
                </h3>

                <!-- Description -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Description</h4>
                    <p id="modalDescription" class="text-gray-600 leading-relaxed">
                        <!-- Will be populated by JS -->
                    </p>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Price</h4>
                    <p id="modalPrice" class="text-3xl font-bold text-blue-600">
                        <!-- Will be populated by JS -->
                    </p>
                </div>

                <!-- Add to Cart Button -->
                <div class="flex gap-3">
                    <button onclick="closeMenuDetail()" class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-medium">
                        Close
                    </button>
                    <button id="modalAddToCart" onclick="addToCartFromModal()" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal Functions
        let currentMenuId = null;

        function showMenuDetail(menuId) {
            currentMenuId = menuId;
            
            // Find menu data
            const menuItem = document.querySelector(`.menu-data[data-id="${menuId}"]`);
            if (!menuItem) return;
            
            const name = menuItem.dataset.name;
            const description = menuItem.dataset.description;
            const price = menuItem.dataset.priceFormatted;
            const category = menuItem.dataset.category;
            const image = menuItem.dataset.image;
            const available = menuItem.dataset.available === 'true';
            
            // Populate modal
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalDescription').textContent = description;
            document.getElementById('modalPrice').textContent = price;
            document.getElementById('modalCategory').textContent = category;
            
            // Set image
            const modalImage = document.getElementById('modalImage');
            if (image) {
                modalImage.innerHTML = `<img src="${image}" alt="${name}" class="w-full h-64 object-cover rounded-lg">`;
            } else {
                modalImage.innerHTML = `<div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center"><span class="text-gray-400">No Image Available</span></div>`;
            }
            
            // Set availability badge
            const availabilityBadge = document.getElementById('modalAvailability');
            if (available) {
                availabilityBadge.innerHTML = '<span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Available</span>';
            } else {
                availabilityBadge.innerHTML = '<span class="inline-block bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">Not Available</span>';
            }
            
            // Enable/disable add to cart button
            const addButton = document.getElementById('modalAddToCart');
            if (available) {
                addButton.disabled = false;
                addButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                addButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
            } else {
                addButton.disabled = true;
                addButton.classList.add('bg-gray-400', 'cursor-not-allowed');
                addButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            }
            
            // Show modal
            document.getElementById('menuDetailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }

        function closeMenuDetail() {
            document.getElementById('menuDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore scroll
            currentMenuId = null;
        }

        function addToCartFromModal() {
            if (!currentMenuId) return;
            
            const button = document.getElementById('modalAddToCart');
            const originalText = button.innerHTML;
            
            // Disable button and show loading
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            const formData = new FormData();
            formData.append('menu_id', currentMenuId);
            formData.append('quantity', 1);
            
            // Send AJAX request
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    const cartCount = document.querySelectorAll('.cart-count');
                    const floatingCart = document.getElementById('floatingCart');
                    
                    cartCount.forEach(el => {
                        el.textContent = data.cartCount;
                        el.classList.remove('hidden');
                    });
                    
                    if (floatingCart && data.cartCount > 0) {
                        floatingCart.classList.remove('hidden');
                    }
                    
                    // Show success and close modal
                    button.innerHTML = '✓ Added to Cart';
                    button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    button.classList.add('bg-green-600');
                    
                    setTimeout(() => {
                        closeMenuDetail();
                        button.innerHTML = originalText;
                        button.classList.remove('bg-green-600');
                        button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                        button.disabled = false;
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Failed to add to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                alert(error.message || 'Failed to add to cart. Please try again.');
            });
        }

        // Close modal when clicking outside
        document.getElementById('menuDetailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMenuDetail();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMenuDetail();
            }
        });

        // AJAX Add to Cart (from list view)
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                const formData = new FormData(this);
                
                // Disable button and show loading
                button.disabled = true;
                button.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                
                // Send AJAX request
                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count
                        const cartCount = document.querySelectorAll('.cart-count');
                        const floatingCart = document.getElementById('floatingCart');
                        
                        cartCount.forEach(el => {
                            el.textContent = data.cartCount;
                            if (data.cartCount > 0) {
                                el.classList.remove('hidden');
                            }
                        });
                        
                        // Show floating cart button if hidden
                        if (floatingCart && data.cartCount > 0) {
                            floatingCart.classList.remove('hidden');
                        }
                        
                        // Replace "+ Add" button with quantity controls
                        const menuId = this.dataset.menuId;
                        const parentDiv = this.closest('.flex.items-center.pr-4');
                        
                        parentDiv.innerHTML = `
                            <div class="flex items-center bg-blue-50 rounded-lg">
                                <button onclick="updateQuantity(${menuId}, -1)" class="px-3 py-2 text-blue-600 hover:bg-blue-100 rounded-l-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <span class="px-4 py-2 text-blue-700 font-bold min-w-[40px] text-center" id="qty-${menuId}">1</span>
                                <button onclick="updateQuantity(${menuId}, 1)" class="px-3 py-2 text-blue-600 hover:bg-blue-100 rounded-r-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        `;
                    } else {
                        throw new Error(data.message || 'Failed to add to cart');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.innerHTML = originalText;
                    button.disabled = false;
                    alert(error.message || 'Failed to add to cart. Please try again.');
                });
            });
        });

        // Search functionality
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

        // Category Filter Functions
        function filterByCategory(categoryId) {
            const sections = document.querySelectorAll('.category-section');
            const buttons = document.querySelectorAll('.category-filter-btn');
            
            // Update button states
            buttons.forEach(btn => {
                if (btn.dataset.category === categoryId) {
                    btn.classList.add('active');
                    btn.classList.remove('bg-gray-200', 'text-gray-700');
                    btn.classList.add('bg-blue-600', 'text-white');
                } else {
                    btn.classList.remove('active', 'bg-blue-600', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-700');
                }
            });
            
            // Show/hide sections
            if (categoryId === 'all') {
                sections.forEach(section => section.classList.remove('hidden'));
            } else {
                sections.forEach(section => {
                    if (section.dataset.categoryId === categoryId) {
                        section.classList.remove('hidden');
                    } else {
                        section.classList.add('hidden');
                    }
                });
            }
        }

        // Quantity Update Function (for - + buttons in menu list)
        function updateQuantity(menuId, change) {
            const qtyElement = document.getElementById('qty-' + menuId);
            const currentQty = parseInt(qtyElement.textContent);
            const newQty = currentQty + change;
            
            // If trying to go below 1, remove item from cart
            if (newQty < 1) {
                if (confirm('Remove this item from cart?')) {
                    removeFromCart(menuId);
                }
                return;
            }
            
            // Disable buttons during update
            const buttons = event.currentTarget.parentElement.querySelectorAll('button');
            buttons.forEach(btn => btn.disabled = true);
            
            // Send AJAX request
            fetch(`/cart/${menuId}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: newQty })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update quantity display
                    qtyElement.textContent = newQty;
                    
                    // Update cart count
                    const cartCounts = document.querySelectorAll('.cart-count');
                    cartCounts.forEach(el => {
                        el.textContent = data.cartCount;
                    });
                    
                    // If quantity is 0, reload page to show "Add" button
                    if (newQty === 0) {
                        location.reload();
                    }
                } else {
                    alert(data.message || 'Failed to update quantity');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update quantity. Please try again.');
            })
            .finally(() => {
                buttons.forEach(btn => btn.disabled = false);
            });
        }

        // Remove item from cart
        function removeFromCart(menuId) {
            // Send DELETE request
            fetch(`/cart/${menuId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    const cartCounts = document.querySelectorAll('.cart-count');
                    cartCounts.forEach(el => {
                        el.textContent = data.cartCount;
                        if (data.cartCount === 0) {
                            el.classList.add('hidden');
                        }
                    });
                    
                    // Hide floating cart if empty
                    const floatingCart = document.getElementById('floatingCart');
                    if (floatingCart && data.cartCount === 0) {
                        floatingCart.classList.add('hidden');
                    }
                    
                    // Replace quantity control with "+ Add" button
                    const parentDiv = document.querySelector(`#qty-${menuId}`).closest('.flex.items-center.pr-4');
                    parentDiv.innerHTML = `
                        <form class="add-to-cart-form" data-menu-id="${menuId}">
                            <input type="hidden" name="menu_id" value="${menuId}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition-all">
                                + Add
                            </button>
                        </form>
                    `;
                    
                    // Re-attach event listener to the new form
                    const newForm = parentDiv.querySelector('.add-to-cart-form');
                    attachAddToCartListener(newForm);
                } else {
                    alert(data.message || 'Failed to remove item');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to remove item. Please try again.');
            });
        }

        // Function to attach add-to-cart listener
        function attachAddToCartListener(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                const formData = new FormData(this);
                
                // Disable button and show loading
                button.disabled = true;
                button.innerHTML = '<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                
                // Send AJAX request
                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count
                        const cartCount = document.querySelectorAll('.cart-count');
                        const floatingCart = document.getElementById('floatingCart');
                        
                        cartCount.forEach(el => {
                            el.textContent = data.cartCount;
                            if (data.cartCount > 0) {
                                el.classList.remove('hidden');
                            }
                        });
                        
                        // Show floating cart button if hidden
                        if (floatingCart && data.cartCount > 0) {
                            floatingCart.classList.remove('hidden');
                        }
                        
                        // Replace "+ Add" button with quantity controls
                        const menuId = this.dataset.menuId;
                        const parentDiv = this.closest('.flex.items-center.pr-4');
                        
                        parentDiv.innerHTML = `
                            <div class="flex items-center bg-blue-50 rounded-lg">
                                <button onclick="updateQuantity(${menuId}, -1)" class="px-3 py-2 text-blue-600 hover:bg-blue-100 rounded-l-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <span class="px-4 py-2 text-blue-700 font-bold min-w-[40px] text-center" id="qty-${menuId}">1</span>
                                <button onclick="updateQuantity(${menuId}, 1)" class="px-3 py-2 text-blue-600 hover:bg-blue-100 rounded-r-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        `;
                    } else {
                        throw new Error(data.message || 'Failed to add to cart');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.innerHTML = originalText;
                    button.disabled = false;
                    alert(error.message || 'Failed to add to cart. Please try again.');
                });
            });
        }

        // Initialize category filter - set "All" as active
        document.addEventListener('DOMContentLoaded', function() {
            const allButton = document.querySelector('.category-filter-btn[data-category="all"]');
            if (allButton) {
                allButton.classList.add('bg-blue-600', 'text-white');
                allButton.classList.remove('bg-gray-200', 'text-gray-700');
            }
        });
    </script>
</body>
</html>
