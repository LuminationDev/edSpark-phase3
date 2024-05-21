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
        Schema::table('waitlists', function (Blueprint $table) {
            // FOREIGN KEYS
            $table->foreign('loan_id')
                ->references('id')->on('loans')->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waitlists', function (Blueprint $table) {
            $table->dropForeign(['loan_id']);
            $table->dropForeign(['user_id']);
        });
    }
};
