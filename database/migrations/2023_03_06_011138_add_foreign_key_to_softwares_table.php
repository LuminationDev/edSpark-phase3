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
        Schema::table('softwares', function (Blueprint $table) {
            // FOREIGN KEYS
            $table->foreign('author_id')
                ->references('id')->on('users')->onDelete('cascade');
            $table->foreign('post_type')
                ->references('id')->on('software_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('softwares', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropForeign(['post_type']);
        });
    }
};
