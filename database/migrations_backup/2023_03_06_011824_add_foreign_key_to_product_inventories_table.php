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
        Schema::table('product_inventories', function (Blueprint $table) {
            // FOREIGN KEYS
            $table->foreign('brand_id')
                ->references('id')->on('product_brands')->onDelete('no action');
            $table->foreign('category_id')
                ->references('id')->on('product_categories')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_inventories', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['category_id']);
        });
    }
};
