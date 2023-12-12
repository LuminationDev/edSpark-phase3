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
        Schema::create('software_learning_areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('software_id');
            $table->unsignedBigInteger('learning_area_id');
            $table->timestamps();

            $table->foreign('software_id')->references('id')->on('softwares')->onDelete('cascade');
            $table->foreign('learning_area_id')->references('id')->on('learning_areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('software_learning_areas', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['software_id']);
            $table->dropForeign(['learning_area_id']);
        });

        Schema::dropIfExists('software_learning_areas');
    }
};
