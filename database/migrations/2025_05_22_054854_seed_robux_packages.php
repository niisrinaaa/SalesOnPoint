<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         DB::table('robux_packages')->insert([
        ['amount' => 100, 'price' => 10900, 'stock' => 100, 'is_active' => true, 'description' => 'Paket Starter'],
        ['amount' => 500, 'price' => 50000, 'stock' => 50, 'is_active' => true, 'description' => 'Paket Populer'],
        ['amount' => 1000, 'price' => 95000, 'stock' => 30, 'is_active' => true, 'description' => 'Paket Hemat'],
        ['amount' => 5000, 'price' => 450000, 'stock' => 10, 'is_active' => true, 'description' => 'Paket Premium'],
    ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
