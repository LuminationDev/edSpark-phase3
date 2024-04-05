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
        Schema::table('software_softwaretype', function (Blueprint $table) {
            $table->foreign('software_id')->references('id')->on('softwares')->onDelete('cascade');
            $table->foreign('softwaretype_id')->references('id')->on('software_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('software_softwaretype', function (Blueprint $table) {
            $table->dropForeign(['software_id']);
            $table->dropForeign(['softwaretype_id']);
        });
    }
};
