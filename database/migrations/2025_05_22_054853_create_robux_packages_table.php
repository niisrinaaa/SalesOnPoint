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
        Schema::create('robux_packages', function (Blueprint $table) {
        $table->id();
        $table->integer('amount')->comment('Jumlah Robux');
        $table->decimal('price', 10, 2)->comment('Harga dalam Rupiah');
        $table->integer('stock')->default(0);
        $table->boolean('is_active')->default(true);
        $table->string('description')->nullable();
        $table->timestamps();
        
        $table->index(['is_active', 'stock']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robux_packages');
    }
};
