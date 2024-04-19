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
        Schema::table('taxonomy_metas', function (Blueprint $table) {
            // FOREIGN KEY
            $table->foreign('taxonomy_id')
                ->references('id')->on('taxonomies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxonomy_metas', function (Blueprint $table) {
            $table->dropForeign(['taxonomy_id']);
        });
    }
};
