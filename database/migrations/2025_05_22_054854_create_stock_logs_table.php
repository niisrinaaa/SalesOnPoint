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
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('robux_packages')->onDelete('cascade');
            $table->integer('old_stock');
            $table->integer('new_stock');
            $table->integer('change_amount');
            $table->enum('change_type', ['restock', 'sold', 'adjustment']);
            $table->text('notes')->nullable();
            $table->string('admin_user')->nullable();
            $table->timestamps();

            $table->index(['package_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};