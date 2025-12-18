<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Tables in database:\n";
echo "==================\n\n";

$tables = App\Models\Table::all();

foreach ($tables as $table) {
    echo "✓ Table {$table->table_number}\n";
    echo "  UUID: {$table->uuid}\n";
    echo "  Scan: " . url("/scan/{$table->uuid}") . "\n\n";
}

echo "Total: {$tables->count()} tables\n";
