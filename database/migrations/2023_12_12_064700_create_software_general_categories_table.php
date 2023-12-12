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
        Schema::create('software_general_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('software_id');
            $table->unsignedBigInteger('general_category_id');
            $table->timestamps();

            $table->foreign('software_id')->references('id')->on('softwares')->onDelete('cascade');
            $table->foreign('general_category_id')->references('id')->on('general_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('software_general_categories', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['software_id']);
            $table->dropForeign(['general_category_id']);
        });

        Schema::dropIfExists('software_general_categories');
    }
};
