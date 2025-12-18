<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the old status column
            $table->dropColumn('status');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            // Create new status column with updated enum values
            $table->enum('status', ['unpaid', 'paid', 'preparing', 'ready', 'completed', 'cancelled'])
                  ->default('unpaid')
                  ->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'paid', 'completed'])
                  ->default('pending')
                  ->after('transaction_id');
        });
    }
};
