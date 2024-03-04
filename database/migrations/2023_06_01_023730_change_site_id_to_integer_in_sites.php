<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
// Drop the unique index temporarily
        Schema::table('sites', function (Blueprint $table) {
            $table->dropUnique('sites_site_id_unique');
            $table->unsignedBigInteger('site_id')->change();
            $table->unique('site_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropUnique('sites_site_id_unique');
            $table->string('site_id')->unique()->nullable()->change();
            $table->unique('site_id');
        });
    }
};