<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking duplicate tables...\n";

$duplicates = DB::table('tables')
    ->select('table_number', DB::raw('COUNT(*) as count'))
    ->groupBy('table_number')
    ->having('count', '>', 1)
    ->get();

if ($duplicates->count() > 0) {
    foreach ($duplicates as $dup) {
        echo "Found duplicate: {$dup->table_number} ({$dup->count} times)\n";
        
        $tables = DB::table('tables')
            ->where('table_number', $dup->table_number)
            ->orderBy('id')
            ->get();
        
        foreach ($tables as $index => $table) {
            if ($index > 0) {
                DB::table('tables')->where('id', $table->id)->delete();
                echo "Deleted duplicate ID: {$table->id}\n";
            }
        }
    }
    echo "\n✓ Duplicates removed!\n";
} else {
    echo "✓ No duplicates found!\n";
}

echo "\nRemaining tables:\n";
$allTables = DB::table('tables')->orderBy('table_number')->get();
foreach ($allTables as $table) {
    echo "- ID {$table->id}: Table {$table->table_number}\n";
}
