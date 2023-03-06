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
        Schema::table('events', function (Blueprint $table) {
            // FOREIGN KEYS
            $table->foreign('author')
                ->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_type')
                ->references('id')->on('event_types')->onDelete('cascade');
            $table->foreign('attendees_id')
                ->references('id')->on('attendees')->onDelete('cascade');
            $table->foreign('location_id')
                ->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('capacity_id')
                ->references('id')->on('capacities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['author']);
            $table->dropForeign(['event_type']);
            $table->dropForeign(['attendees_id']);
            $table->dropForeign(['location_id']);
            $table->dropForeign(['capacity_id']);
        });
    }
};
