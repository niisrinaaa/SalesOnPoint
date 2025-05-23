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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained('robux_packages')->nullOnDelete();
            $table->string('username_roblox');
            $table->integer('robux_amount');
            $table->decimal('price_paid', 12, 0);
            $table->enum('payment_method', ['bank_transfer', 'e_wallet', 'credit_card']);
            $table->enum('payment_status', ['pending', 'pending_verification', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_reference')->nullable();
            $table->string('payment_proof')->nullable();
            $table->enum('delivery_status', ['waiting', 'processing', 'completed', 'failed'])->default('waiting');
            $table->string('transaction_code')->unique();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'payment_status']);
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