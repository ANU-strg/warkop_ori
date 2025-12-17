@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Order Details</h2>
            <p class="text-gray-600">{{ $order->transaction_id }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            ← Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center border-b pb-4 last:border-b-0">
                            @if($item->menu->image_path && file_exists(public_path($item->menu->image_path)))
                                <img src="{{ asset($item->menu->image_path) }}" alt="{{ $item->menu->name }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-400 text-xs">No Image</span>
                                </div>
                            @endif
                            
                            <div class="flex-1 ml-4">
                                <h4 class="font-semibold text-gray-800">{{ $item->menu->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $item->menu->category->name }}</p>
                                <p class="text-sm text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</p>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-t mt-4 pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-800">Total Amount</span>
                        <span class="text-2xl font-bold text-blue-600">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary & Actions -->
        <div class="space-y-6">
            <!-- Order Info Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $order->transaction_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Table</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $order->table->table_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'paid' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Items</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $order->orderItems->count() }} items</dd>
                    </div>
                    @if($order->snap_token)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Token</dt>
                            <dd class="mt-1 text-xs text-gray-600 font-mono break-all">{{ $order->snap_token }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Status Update Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Status</h3>
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-3 mb-4">
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 {{ $order->status === 'pending' ? 'bg-yellow-50 border-yellow-200' : '' }}">
                            <input type="radio" name="status" value="pending" {{ $order->status === 'pending' ? 'checked' : '' }} class="mr-3">
                            <div>
                                <div class="font-medium text-gray-800">Pending</div>
                                <div class="text-xs text-gray-500">Waiting for payment</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 {{ $order->status === 'paid' ? 'bg-blue-50 border-blue-200' : '' }}">
                            <input type="radio" name="status" value="paid" {{ $order->status === 'paid' ? 'checked' : '' }} class="mr-3">
                            <div>
                                <div class="font-medium text-gray-800">Paid</div>
                                <div class="text-xs text-gray-500">Payment received, preparing order</div>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 {{ $order->status === 'completed' ? 'bg-green-50 border-green-200' : '' }}">
                            <input type="radio" name="status" value="completed" {{ $order->status === 'completed' ? 'checked' : '' }} class="mr-3">
                            <div>
                                <div class="font-medium text-gray-800">Completed</div>
                                <div class="text-xs text-gray-500">Order delivered</div>
                            </div>
                        </label>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-medium">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
