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
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('post_title');
            $table->longText('post_content');
            $table->text('post_excerpt');
            $table->dateTime('post_date');
            $table->dateTime('post_modified');
            $table->string('post_status');
            $table->unsignedBigInteger('post_type')->nullable();
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
        Schema::dropIfExists('communities');
    }
};
