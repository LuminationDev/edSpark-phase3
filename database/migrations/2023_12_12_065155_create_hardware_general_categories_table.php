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
        Schema::create('hardware_general_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hardware_id');
            $table->unsignedBigInteger('general_category_id');
            $table->timestamps();

            $table->foreign('hardware_id')->references('id')->on('hardwares')->onDelete('cascade');
            $table->foreign('general_category_id')->references('id')->on('general_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hardware_general_categories', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['hardware_id']);
            $table->dropForeign(['general_category_id']);
        });

        Schema::dropIfExists('hardware_general_categories');
    }
};
