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
            $table->dropUnique('site_id');
        });

// Alter the column data type
        Schema::table('sites', function (Blueprint $table) {
            $table->unsignedBigInteger('site_id')->change();
        });

// Recreate the unique index
        Schema::table('sites', function (Blueprint $table) {
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
// Drop the unique index temporarily
        Schema::table('sites', function (Blueprint $table) {
            $table->dropUnique('site_id');
        });

// Alter the column data type
        Schema::table('sites', function (Blueprint $table) {
            $table->string('site_id')->unique()->nullable()->change();
        });

// Recreate the unique index
        Schema::table('sites', function (Blueprint $table) {
            $table->unique('site_id');
        });
    }
};