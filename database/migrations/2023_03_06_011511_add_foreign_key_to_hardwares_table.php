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
            $table->foreign('product_owner')
                ->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_category')
                ->references('id')->on('product_categories')->onDelete('cascade');
            $table->foreign('product_inventory')
                ->references('id')->on('product_inventories')->onDelete('cascade');
            $table->foreign('product_brand')
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
            $table->dropForeign(['product_owner']);
            $table->dropForeign(['product_category']);
            $table->dropForeign(['product_inventory']);
            $table->dropForeign(['product_brand']);
        });
    }
};
