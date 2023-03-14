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
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('event_title');
            $table->longText('event_content');
            $table->text('event_excerpt')->nullable();
            $table->string('cover_image')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('event_status');
            $table->unsignedBigInteger('eventtype_id')->nullable();
            //TODO: Terms and Taxonomy relations
            $table->unsignedBigInteger('attendees_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('capacity_id')->nullable();

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
