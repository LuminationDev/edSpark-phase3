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
        // Schema::dropIfExists('analytics');

        Schema::create('analytics_gathering', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('user')->unsigned();
            $table->BigInteger('product')->unsigned()->nullable();
            $table->BigInteger('event')->unsigned()->nullable();
            $table->BigInteger('post_or_article')->unsigned()->nullable();
            $table->BigInteger('category_id')->unsigned()->nullable();
            $table->BigInteger('tag_id')->unsigned()->nullable();
            $table->timestamps();


        });

        Schema::table('analytics_gathering', function(Blueprint $table) {
            $table->foreign('user')->references('id')->on('users');
            $table->foreign('product')->references('id')->on('products');
            $table->foreign('event')->references('id')->on('events');
            $table->foreign('post_or_article')->references('id')->on('posts_and_articles');
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
        Schema::dropIfExists('analytics');
    }
};
