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
        Schema::table('capacities', function (Blueprint $table) {
            // FOREIGN KEY
            $table->foreign('event_id')
                ->references('id')->on('events')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('capacities', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
        });
    }
};
