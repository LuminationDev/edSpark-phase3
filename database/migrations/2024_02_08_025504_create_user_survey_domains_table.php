<?php

use App\Models\UserSurvey;
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
        Schema::create('user_survey_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_survey_id')
                ->references('id')
                ->on('user_surveys')
                ->onDelete('cascade');
            $table->enum('status', UserSurvey::$STATUS_TYPES);
            $table->string('domain');
            $table->integer('element_count');
            $table->integer('completed_element_count');
            $table->integer('question_count');
            $table->integer('completed_question_count');
            $table->integer('next_question_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_survey_domains');
    }
};
