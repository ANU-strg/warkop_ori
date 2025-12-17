<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu - Warkop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Fixed Header -->
    <div class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-3xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">🍜 Warkop Menu</h1>
                    <p class="text-sm text-gray-600">Table {{ $tableInfo['table_number'] }}</p>
                </div>
                <a href="{{ route('cart.index') }}" class="relative inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Cart
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>
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

    <!-- Menu Content -->
    <div class="max-w-3xl mx-auto px-4 py-6 pb-24">
        @forelse($categories as $category)
            @if($category->menus->count() > 0)
                <!-- Category Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $category->name }}</h2>
                    
                    <!-- Menu Items -->
                    <div class="space-y-4">
                        @foreach($category->menus as $menu)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                                <div class="flex">
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
                                    
                                    <!-- Add Button -->
                                    <div class="flex items-center pr-4">
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium">
                                                + Add
                                            </button>
                                        </form>
                                    </div>
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
    @if($cartCount > 0)
        <div class="fixed bottom-4 right-4 sm:hidden">
            <a href="{{ route('cart.index') }}" class="bg-blue-600 text-white rounded-full p-4 shadow-lg flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center">{{ $cartCount }}</span>
            </a>
        </div>
    @endif
</body>
</html>
