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
        Schema::table('product_categories', function (Blueprint $table) {
            // FOREIGN KEY
            $table->foreign('brand_id')
                ->references('id')->on('product_brands')->onDelete('no action');
            $table->foreign('inventory_id')
                ->references('id')->on('product_inventories')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['inventory_id']);
        });
    }
};
