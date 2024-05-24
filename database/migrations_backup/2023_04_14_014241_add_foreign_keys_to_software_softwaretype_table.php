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
        Schema::table('software_softwaretype', function (Blueprint $table) {
            $table->foreign('software_id')->references('id')->on('softwares')->onDelete('cascade');
            $table->foreign('softwaretype_id')->references('id')->on('software_types')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('software_softwaretype', function (Blueprint $table) {
            $table->dropForeign(['software_id']);
            $table->dropForeign(['softwaretype_id']);
        });
    }
};
