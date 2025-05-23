<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('robux_transactions', function (Blueprint $table) {
            // Drop constraint dulu
            $table->dropForeign(['package_id']);

            // Ubah kolom jadi nullable (kalau belum)
            $table->unsignedBigInteger('package_id')->nullable()->change();

            // Tambahkan constraint ulang dengan nullOnDelete (set null)
            $table->foreign('package_id')
                  ->references('id')->on('robux_packages')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('robux_transactions', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->unsignedBigInteger('package_id')->nullable(false)->change();
            $table->foreign('package_id')->references('id')->on('robux_packages')->cascadeOnDelete();
        });
    }
};
