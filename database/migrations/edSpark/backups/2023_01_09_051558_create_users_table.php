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
        // Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->BigInteger('role')->unsigned();
            $table->BigInteger('location')->unsigned();
            $table->BigInteger('category_id')->unsigned()->nullable();
            $table->BigInteger('tag_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('role')->references('id')->on('user_roles')->unsigned();

        });

        Schema::table('users', function (Blueprint $table) {

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
        Schema::dropIfExists('users');
    }
};
