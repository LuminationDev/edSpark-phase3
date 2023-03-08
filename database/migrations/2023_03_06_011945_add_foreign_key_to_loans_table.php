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
        Schema::table('loans', function (Blueprint $table) {
            // FOREIGN KEYS
            $table->foreign('product_id')
                ->references('id')->on('hardwares')->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')->onDelete('cascade');
            $table->foreign('inventory_id')
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
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['inventory_id']);
        });
    }
};
