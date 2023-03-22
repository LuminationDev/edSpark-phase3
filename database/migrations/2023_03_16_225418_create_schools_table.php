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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('allowEditIds')->nullable();
            $table->string('name');
            $table->longText('content_blocks')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->longText('tech_used')->nullable();
            $table->longText('pedagogical_approaches')->nullable();
            $table->longText('tech_landscape')->nullable();
            $table->boolean('isFeatured')->default(0);
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
        Schema::dropIfExists('schools');
    }
};
