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
        Schema::create('user_survey_action_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_survey_domain_id')
                ->references('id')
                ->on('user_survey_domains')
                ->onDelete('cascade');

            $table->string('element');
            $table->text('action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_survey_action_plans');
    }
};
