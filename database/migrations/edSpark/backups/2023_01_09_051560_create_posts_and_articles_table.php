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
        // Schema::dropIfExists('posts');

        Schema::create('posts_and_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->BigInteger('author')->unsigned();
            $table->BigInteger('category_id')->unsigned()->nullable();
            $table->BigInteger('tag_id')->unsigned()->nullable();
            $table->timestamps();


        });

        Schema::table('posts_and_articles', function($table) {
            $table->foreign('author')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts_and_articles');
    }
};
