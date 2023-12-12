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
        Schema::create('hardware_year_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hardware_id');
            $table->unsignedBigInteger('year_level_id');
            $table->timestamps();

            $table->foreign('hardware_id')->references('id')->on('hardwares')->onDelete('cascade');
            $table->foreign('year_level_id')->references('id')->on('year_levels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hardware_year_levels', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['hardware_id']);
            $table->dropForeign(['year_level_id']);
        });

        Schema::dropIfExists('hardware_year_levels');
    }
};
