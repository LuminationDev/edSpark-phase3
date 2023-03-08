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
        Schema::table('advice_metas', function (Blueprint $table) {
            // FOREIGN KEY
            $table->foreign('advice_id')
                ->references('id')->on('advices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advice_metas', function (Blueprint $table) {
            $table->dropForeign(['advice_id']);
        });
    }
};
