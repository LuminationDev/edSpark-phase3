<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hardwares', function (Blueprint $table) {
            // FOREIGN KEYS
            $table->foreign('owner_id')
                ->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')
                ->references('id')->on('product_categories')->onDelete('cascade');
            $table->foreign('inventory_id')
                ->references('id')->on('product_inventories')->onDelete('cascade');
            $table->foreign('brand_id')
                ->references('id')->on('product_brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hardwares', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['inventory_id']);
            $table->dropForeign(['brand_id']);
        });
    }
};
