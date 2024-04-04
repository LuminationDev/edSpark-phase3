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
        Schema::create('softwares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->string('title');
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('status', ['DRAFT', 'PENDING', 'UNPUBLISHED', 'PUBLISHED', 'ARCHIVED', 'REJECTED'])->default('DRAFT');;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('softwares');
    }
};
