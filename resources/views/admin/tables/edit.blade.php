@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Table {{ $table->table_number }}</h2>
            <p class="text-gray-600">Update table information</p>
        </div>
        <a href="{{ route('admin.tables.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            ← Back
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <form action="{{ route('admin.tables.update', $table) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="table_number" class="block text-sm font-medium text-gray-700 mb-2">Table Number *</label>
                    <input type="text" name="table_number" id="table_number" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('table_number') border-red-500 @enderror"
                           value="{{ old('table_number', $table->table_number) }}" required>
                    @error('table_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Must be unique across all tables</p>
                </div>

                <div class="flex items-center justify-end space-x-2">
                    <a href="{{ route('admin.tables.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        Update Table
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
