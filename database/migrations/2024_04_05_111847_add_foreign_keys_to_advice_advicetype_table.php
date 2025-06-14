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
        Schema::table('advice_advicetype', function (Blueprint $table) {
            $table->foreign('advice_id')->references('id')->on('advices')->onDelete('cascade');
            $table->foreign('advicetype_id')->references('id')->on('advice_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advice_advicetype', function (Blueprint $table) {
            $table->dropForeign(['advice_id']);
            $table->dropForeign(['advicetype_id']);
        });
    }
};
