<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing unique constraint...\n\n";

try {
    App\Models\Table::create([
        'table_number' => '1' // Already exists
    ]);
    echo "❌ FAILED: Duplicate table was created (unique constraint not working)\n";
} catch (\Illuminate\Database\QueryException $e) {
    if (str_contains($e->getMessage(), 'Duplicate entry')) {
        echo "✅ SUCCESS: Unique constraint is working!\n";
        echo "   Error: {$e->getMessage()}\n";
    } else {
        echo "❌ FAILED: Different error: {$e->getMessage()}\n";
    }
}

echo "\n\nTesting with new table number...\n";
try {
    $newTable = App\Models\Table::create([
        'table_number' => '10'
    ]);
    echo "✅ SUCCESS: New table created!\n";
    echo "   Table {$newTable->table_number} with UUID {$newTable->uuid}\n";
} catch (\Exception $e) {
    echo "❌ FAILED: {$e->getMessage()}\n";
}
