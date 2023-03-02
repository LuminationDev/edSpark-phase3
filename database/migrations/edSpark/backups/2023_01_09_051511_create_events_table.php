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
        // Schema::dropIfExists('events');

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->BigInteger('location')->unsigned();
            $table->BigInteger('category_id')->unsigned()->nullable();
            $table->BigInteger('tag_id')->unsigned()->nullable();
            $table->timestamps();


        });

        Schema::table('events', function(Blueprint $table) {
            $table->foreign('location')->references('id')->on('user_location');
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
        Schema::dropIfExists('events');
    }
};
