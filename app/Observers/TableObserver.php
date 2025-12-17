<?php

namespace App\Observers;

use App\Models\Table;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class TableObserver
{
    /**
     * Handle the Table "creating" event.
     * Generate UUID before the table is saved.
     */
    public function creating(Table $table): void
    {
        if (empty($table->uuid)) {
            $table->uuid = (string) Str::uuid();
        }
    }

    /**
     * Handle the Table "created" event.
     * Generate QR code after the table is created.
     */
    public function created(Table $table): void
    {
        try {
            // Generate the scan URL using APP_URL from config (not request URL)
            $baseUrl = config('app.url');
            $scanUrl = $baseUrl . "/scan/{$table->uuid}";
            
            // Create directory if it doesn't exist
            $directory = public_path('images/qrcodes');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Generate QR Code using external API (no GD required)
            $filename = "table-{$table->table_number}-{$table->uuid}.png";
            $filepath = $directory . '/' . $filename;
            
            // Use QR Server API to generate QR code
            $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($scanUrl);
            $qrImage = file_get_contents($qrApiUrl);
            
            if ($qrImage !== false) {
                file_put_contents($filepath, $qrImage);
                
                // Update the table record with the QR code path
                $table->updateQuietly([
                    'qr_code_image_path' => "images/qrcodes/{$filename}"
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't fail table creation
            \Log::error("Failed to generate QR code for table {$table->id}: " . $e->getMessage());
        }
    }

    /**
     * Handle the Table "updated" event.
     */
    public function updated(Table $table): void
    {
        //
    }

    /**
     * Handle the Table "deleted" event.
     */
    public function deleted(Table $table): void
    {
        // Delete QR code image when table is deleted
        if ($table->qr_code_image_path) {
            $filepath = public_path($table->qr_code_image_path);
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }

    /**
     * Handle the Table "restored" event.
     */
    public function restored(Table $table): void
    {
        //
    }

    /**
     * Handle the Table "force deleted" event.
     */
    public function forceDeleted(Table $table): void
    {
        // Delete QR code image when table is force deleted
        if ($table->qr_code_image_path) {
            $filepath = public_path($table->qr_code_image_path);
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }
}
