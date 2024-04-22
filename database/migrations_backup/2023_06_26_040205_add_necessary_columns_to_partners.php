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
        Schema::table('partners', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('email');
            $table->string('cover_image')->nullable()->after('logo');
            $table->longText('motto')->nullable()->after('cover_image');
            $table->longText('introduction')->nullable()->after('motto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('logo');
            $table->dropColumn('cover_image');
            $table->dropColumn('motto');
            $table->dropColumn('introduction');
        });
    }
};
