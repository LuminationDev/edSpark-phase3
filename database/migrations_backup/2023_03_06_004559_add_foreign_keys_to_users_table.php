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
        Schema::table('users', function (Blueprint $table) {
            // FOREIGN KEYS
            $table->foreign('role_id')
                ->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('site_id')
                ->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('usertype_id')
                ->references('id')->on('user_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['site_id']);
            $table->dropForeign(['usertype_id']);
        });
    }
};

