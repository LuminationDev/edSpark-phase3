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
        Schema::table('advices', function (Blueprint $table) {
            // FOREIGN KEYS
            $table->foreign('author_id')
                ->references('id')->on('users')->onDelete('cascade');
            $table->foreign('advicetype_id')
                ->references('id')->on('advice_types')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advices', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropForeign(['advicetype_id']);
        });
    }
};
