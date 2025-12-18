<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Table;

echo "Regenerating QR codes for all tables...\n\n";

$tables = Table::all();
$baseUrl = config('app.url');

foreach ($tables as $table) {
    echo "Processing Table {$table->table_number}...\n";
    
    try {
        // Generate the scan URL
        $scanUrl = $baseUrl . "/scan/{$table->uuid}";
        echo "  Scan URL: {$scanUrl}\n";
        
        // Create directory if it doesn't exist
        $directory = public_path('images/qrcodes');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Generate QR Code using external API
        $filename = "table-{$table->table_number}-{$table->uuid}.png";
        $filepath = $directory . '/' . $filename;
        
        // Use QR Server API to generate QR code
        $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($scanUrl);
        $qrImage = file_get_contents($qrApiUrl);
        
        if ($qrImage !== false) {
            file_put_contents($filepath, $qrImage);
            
            // Update the table record with the QR code path
            $table->update([
                'qr_code_image_path' => "images/qrcodes/{$filename}"
            ]);
            
            echo "  ✓ QR code generated: {$filename}\n";
        } else {
            echo "  ✗ Failed to download QR code\n";
        }
    } catch (\Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

echo "Done! All QR codes have been regenerated.\n";
