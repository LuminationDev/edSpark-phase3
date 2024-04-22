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
        Schema::table('hardwares', function (Blueprint $table) {
            $table->foreign('owner_id')
                ->references('id')->on('users')->onDelete('no action');
            $table->foreign('category_id')
                ->references('id')->on('hardware_categories')->onDelete('no action');
            $table->foreign('brand_id')
                ->references('id')->on('hardware_brands')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hardwares', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['brand_id']);
        });
    }
};
