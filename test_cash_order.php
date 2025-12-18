<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Creating test cash order...\n";

$table = App\Models\Table::first();
$menu = App\Models\Menu::first();

$order = App\Models\Order::create([
    'table_id' => $table->id,
    'transaction_id' => 'TRX-CASH-TEST-' . time(),
    'status' => 'pending',
    'payment_method' => 'cash',
    'total_amount' => 50000
]);

App\Models\OrderItem::create([
    'order_id' => $order->id,
    'menu_id' => $menu->id,
    'quantity' => 2,
    'price' => $menu->price,
    'subtotal' => $menu->price * 2
]);

echo "✓ Order Created!\n";
echo "Order ID: " . $order->transaction_id . "\n";
echo "Payment Method: " . $order->payment_method . "\n";
echo "Status: " . $order->status . "\n";
echo "\nLogin to admin panel to mark this order as paid!\n";
