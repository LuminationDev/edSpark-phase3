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
        Schema::table('Softwares', function (Blueprint $table) {
            $table->string('template')->after('softwaretype_id')->nullable();
            $table->json('extra_content')->after('template')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Softwares', function (Blueprint $table) {
            $table->dropColumn('template');
            $table->dropColumn('extra_content');
        });
    }
};
