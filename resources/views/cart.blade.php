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
                        <div class="flex items-center">
                            <!-- Image -->
                            @if($item['image_path'] && file_exists(public_path($item['image_path'])))
                                <img src="{{ asset($item['image_path']) }}" alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-400 text-xs">No Image</span>
                                </div>
                            @endif
                            
                            <!-- Info -->
                            <div class="flex-1 ml-4">
                                <h3 class="font-semibold text-gray-800">{{ $item['name'] }}</h3>
                                <p class="text-blue-600 font-medium">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                
                                <!-- Quantity Controls -->
                                <div class="flex items-center mt-2">
                                    <form action="{{ route('cart.update', $menuId) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}">
                                        <button type="submit" class="bg-gray-200 text-gray-700 px-3 py-1 rounded-l hover:bg-gray-300">-</button>
                                    </form>
                                    
                                    <span class="bg-gray-100 px-4 py-1 text-gray-800 font-medium">{{ $item['quantity'] }}</span>
                                    
                                    <form action="{{ route('cart.update', $menuId) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                        <button type="submit" class="bg-gray-200 text-gray-700 px-3 py-1 rounded-r hover:bg-gray-300">+</button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Subtotal & Remove -->
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800 mb-2">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </p>
                                <form action="{{ route('cart.remove', $menuId) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </form>
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
            <div class="flex space-x-2">
                <a href="{{ route('menu') }}" class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg text-center font-medium hover:bg-gray-400">
                    Add More Items
                </a>
                <a href="{{ route('checkout') }}" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg text-center font-medium hover:bg-blue-700">
                    Proceed to Checkout
                </a>
            </div>
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
</body>
</html>
