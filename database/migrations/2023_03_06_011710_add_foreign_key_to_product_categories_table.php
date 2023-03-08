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
            $table->foreign('product_brand')
                ->references('id')->on('product_brands')->onDelete('cascade');
            $table->foreign('product_inventory')
                ->references('id')->on('product_inventories')->onDelete('cascade');
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
            $table->dropForeign(['product_brand']);
            $table->dropForeign(['product_inventory']);
        });
    }
};
