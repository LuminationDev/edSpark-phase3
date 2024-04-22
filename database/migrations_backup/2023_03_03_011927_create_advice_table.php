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
        Schema::create('advices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('post_title');
            $table->longText('post_content');
            $table->text('post_excerpt')->nullable();
            $table->string('cover_image')->nullable();
            $table->dateTime('post_date')->nullable();
            $table->dateTime('post_modified')->nullable();
            $table->string('post_status');
            $table->unsignedBigInteger('advicetype_id')->nullable(); // not needed
            //TODO: Terms and Taxonomy relations
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
        Schema::dropIfExists('advices');
    }
};
