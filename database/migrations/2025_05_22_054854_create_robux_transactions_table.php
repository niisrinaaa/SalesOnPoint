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
        Schema::create('robux_transactions', function (Blueprint $table) {
        $table->id();
        $table->string('username_roblox', 100);
        $table->foreignId('package_id')->constrained('robux_packages');
        $table->foreignId('user_id')->nullable()->constrained('users'); // Optional jika ada login
        $table->integer('robux_amount');
        $table->decimal('price_paid', 10, 2);
        $table->string('transaction_code', 50)->unique();
        $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
        $table->enum('delivery_status', ['waiting', 'processing', 'completed', 'failed'])->default('waiting');
        $table->string('payment_method')->nullable();
        $table->string('payment_reference')->nullable();
        $table->timestamp('paid_at')->nullable();
        $table->timestamp('delivered_at')->nullable();
        $table->timestamps();
        
        $table->index(['payment_status', 'delivery_status']);
        $table->index('transaction_code');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robux_transactions');
    }
};
