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
            $table->foreign('product_brand')
                ->references('id')->on('product_brands')->onDelete('cascade');
            $table->foreign('product_category')
                ->references('id')->on('product_categories')->onDelete('cascade');
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
            $table->dropForeign(['product_brand']);
            $table->dropForeign(['product_category']);
        });
    }
};
