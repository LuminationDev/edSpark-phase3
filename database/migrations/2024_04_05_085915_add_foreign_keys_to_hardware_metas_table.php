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
        Schema::table('hardware_metas', function (Blueprint $table) {
            $table->foreign('hardware_id')
                ->references('id')->on('hardwares')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hardware_metas', function (Blueprint $table) {
            $table->dropForeign(['hardware_id']);
        });
    }
};
