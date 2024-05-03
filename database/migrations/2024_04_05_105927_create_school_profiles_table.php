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
        Schema::create('school_profiles', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('school_id');
                $table->unsignedBigInteger('site_id')->nullable();
                $table->string('name');
                $table->longText('content_blocks')->nullable();
                $table->string('logo')->nullable();
                $table->string('cover_image')->nullable();
                $table->longText('tech_used')->nullable();
                $table->longText('pedagogical_approaches')->nullable();
                $table->longText('tech_landscape')->nullable();
                $table->boolean('is_featured')->default(0);
                $table->enum('status', ['DRAFT', 'PENDING', 'UNPUBLISHED', 'PUBLISHED', 'ARCHIVED', 'REJECTED'])->default('DRAFT');;
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_profiles');
    }
};
