<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('catalogues', function (Blueprint $table) {
            $table->unsignedBigInteger('version_id')->nullable()->after('id');
            $table->string('cover_image')->nullable()->after('vendor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalogues', function (Blueprint $table) {
            $table->dropColumn('version_id');
            $table->dropColumn('cover_image');
        });
    }
};
