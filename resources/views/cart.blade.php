<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cart - Warkop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-4">
            <div class="flex items-center">
                <a href="{{ route('menu') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Your Cart</h1>
                    <p class="text-sm text-gray-600">Table {{ $tableInfo['table_number'] }}</p>
                </div>
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

    <!-- Cart Content -->
    <div class="max-w-3xl mx-auto px-4 py-6">
        @if(!empty($cart))
            <div class="space-y-4 mb-6">
                @foreach($cart as $menuId => $item)
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <div class="flex items-start gap-4">
                            <!-- Image -->
                            @if($item['image_path'] && file_exists(public_path($item['image_path'])))
                                <img src="{{ asset($item['image_path']) }}" alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded flex-shrink-0">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                                    <span class="text-gray-400 text-xs">No Image</span>
                                </div>
                            @endif
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <!-- Header: Name & Price -->
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-800 text-lg">{{ $item['name'] }}</h3>
                                        <p class="text-blue-600 font-medium mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-800">
                                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Notes Input -->
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Special Request</label>
                                    <textarea 
                                        id="note-{{ $menuId }}" 
                                        name="notes[{{ $menuId }}]"
                                        rows="2" 
                                        placeholder="Add note (e.g., Pedas, bagian dada, es sedikit...)" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                    >{{ $item['notes'] ?? '' }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">💡 Note will be saved when you checkout</p>
                                </div>
                                
                                <!-- Quantity Controls & Remove -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <form action="{{ route('cart.update', $menuId) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}">
                                            <button type="submit" 
                                                    class="px-3 py-1 rounded-l transition-colors {{ $item['quantity'] <= 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                                                    {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                -
                                            </button>
                                        </form>
                                        
                                        <span class="bg-gray-100 px-4 py-1 text-gray-800 font-medium">{{ $item['quantity'] }}</span>
                                        
                                        <form action="{{ route('cart.update', $menuId) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                            <button type="submit" class="bg-gray-200 text-gray-700 px-3 py-1 rounded-r hover:bg-gray-300">+</button>
                                        </form>
                                    </div>
                                    
                                    <!-- Remove Button -->
                                    <form action="{{ route('cart.remove', $menuId) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="text-lg font-bold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Checkout Button -->
            <form id="checkout-form" method="POST" action="{{ route('cart.saveNotes') }}">
                @csrf
                <div class="flex space-x-2">
                    <a href="{{ route('menu') }}" class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg text-center font-medium hover:bg-gray-400">
                        Add More Items
                    </a>
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg text-center font-medium hover:bg-blue-700">
                        Proceed to Checkout
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Your cart is empty</h3>
                <p class="text-gray-600 mb-6">Add some delicious items to get started!</p>
                <a href="{{ route('menu') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700">
                    Browse Menu
                </a>
            </div>
        @endif
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
