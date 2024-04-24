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
            $table->foreign('version_id')
                ->references('version')
                ->on('catalogue_versions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalogues', function (Blueprint $table) {
            $table->dropForeign('version_id');
        });
    }
};
