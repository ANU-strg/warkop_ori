@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Menu Management</h2>
            <p class="text-gray-600">Manage menu items</p>
        </div>
        <a href="{{ route('admin.menus.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
            + Add New Menu
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            @if($menus->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($menus as $menu)
                        <div class="border rounded-lg overflow-hidden hover:shadow-lg transition">
                            @if($menu->image_path && file_exists(public_path($menu->image_path)))
                                <img src="{{ asset($menu->image_path) }}" alt="{{ $menu->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No Image</span>
                                </div>
                            @endif
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $menu->name }}</h3>
                                    <span class="px-2 py-1 text-xs rounded {{ $menu->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $menu->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ $menu->category->name }}</p>
                                <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $menu->description }}</p>
                                <p class="text-lg font-bold text-blue-600 mb-3">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.menus.edit', $menu) }}" class="flex-1 text-center px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">Edit</a>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">{{ $menus->links() }}</div>
            @else
                <p class="text-gray-500">No menu items found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
