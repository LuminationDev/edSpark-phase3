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
        Schema::table('user_survey_action_plans', function (Blueprint $table) {
            $table->boolean('selected')->after('action')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_survey_action_plans', function (Blueprint $table) {
            $table->dropColumn('selected');
        });
    }
};
