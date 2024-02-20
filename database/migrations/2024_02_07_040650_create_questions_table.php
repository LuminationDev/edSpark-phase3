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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')
                ->references('version')
                ->on('surveys')
                ->onDelete('cascade');
            $table->string('domain');
            $table->text('question');
            $table->string("chapter");
            $table->string("generated_variable");
            $table->string("domain_print")->nullable();
            $table->text('advice')->nullable();
            $table->text("description")->nullable();
            $table->text('question_example')->nullable();
            $table->string("variable_suffix")->nullable();
            $table->string("dependencies")->nullable();
            $table->integer("phase")->nullable();
            $table->string("phase_description")->nullable();
            $table->string("category")->nullable();
            $table->text("chapter_print")->nullable();
            $table->string("category_print")->nullable();
            $table->text("chapter_description")->nullable();
            $table->timestamps();

            $table->index('survey_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
