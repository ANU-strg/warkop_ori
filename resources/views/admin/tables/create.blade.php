@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Create New Table</h2>
            <p class="text-gray-600">Add a new table to the system</p>
        </div>
        <a href="{{ route('admin.tables.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            ← Back
        </a>
    </div>

    <!-- Create Form -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <form action="{{ route('admin.tables.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="table_number" class="block text-sm font-medium text-gray-700 mb-2">Table Number *</label>
                    <input type="number" name="table_number" id="table_number" min="1" step="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('table_number') border-red-500 @enderror"
                           value="{{ old('table_number') }}" required>
                    @error('table_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Enter number only (e.g., 1, 2, 3, 10, etc.). Must be unique.</p>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        Create Table
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Auto QR Code Generation</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>When you create a table, a QR code will be automatically generated. Customers can scan this QR code to access the menu for this specific table.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
