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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('email');
            $table->string('organisation_name')->nullable();
            $table->string('issue_url')->nullable();
            $table->text('content')->nullable();
            $table->text('images')->nullable();
            $table->enum('status', ['DRAFT', 'PENDING', 'UNPUBLISHED', 'PUBLISHED', 'ARCHIVED', 'REJECTED'])->default('DRAFT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
