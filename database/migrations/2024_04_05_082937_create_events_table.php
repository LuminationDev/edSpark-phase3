<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->string('title');
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->string('cover_image')->nullable();
            $table->longText('event_location')->nullable();
            $table->json('extra_content')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->unsignedBigInteger('event_type_id')->nullable();
            $table->unsignedBigInteger('event_format_id')->nullable();
            $table->enum('status', ['DRAFT', 'PENDING', 'UNPUBLISHED', 'PUBLISHED', 'ARCHIVED', 'REJECTED'])->default('DRAFT');;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
