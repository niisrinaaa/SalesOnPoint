<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('stock_logs', function (Blueprint $table) {
        $table->dropForeign(['package_id']);

        $table->foreign('package_id')
              ->references('id')->on('robux_packages')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('stock_logs', function (Blueprint $table) {
        $table->dropForeign(['package_id']);

        $table->foreign('package_id')
              ->references('id')->on('robux_packages')
              ->onDelete('restrict'); // default constraint sebelumnya
    });
}

};
