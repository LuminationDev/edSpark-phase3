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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');
            $table->foreignId('user_survey_domain_id')
                ->references('id')
                ->on('user_survey_domains')
                ->onDelete('cascade');
            $table->enum('answer', ['0', '1', '2']);
            $table->text('answer_text')->nullable();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
