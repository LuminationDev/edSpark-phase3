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
            $table->longText('content_blocks')->nullable()->after('email');
            $table->string('logo')->nullable()->after('content_blocks');
            $table->string('cover_image')->nullable()->after('logo');
            $table->string('motto')->nullable()->after('cover_image');
            $table->string('introduction')->nullable()->after('motto');
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
            $table->dropColumn('content_blocks');
            $table->dropColumn('logo');
            $table->dropColumn('cover_image');
            $table->dropColumn('motto');
            $table->dropColumn('introduction');
        });
    }
};
