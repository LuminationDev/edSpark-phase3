<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('school_metas', function (Blueprint $table) {
            $table->foreign('school_id')
                ->references('id')->on('school_profiles')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_metas', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
        });
    }
};
