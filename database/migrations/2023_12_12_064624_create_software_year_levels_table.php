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
        Schema::create('software_year_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('software_id');
            $table->unsignedBigInteger('year_level_id');
            $table->timestamps();

            $table->foreign('software_id')->references('id')->on('softwares')->onDelete('cascade');
            $table->foreign('year_level_id')->references('id')->on('year_levels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('software_year_levels', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['software_id']);
            $table->dropForeign(['year_level_id']);
        });

        Schema::dropIfExists('software_year_levels');
    }
};
