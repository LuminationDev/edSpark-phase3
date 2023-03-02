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
        // Schema::dropIfExists('product_loan_system');

        Schema::create('product_loan_system', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('product')->unsigned();
            $table->BigInteger('user')->unsigned();
            $table->date('start_date');
            $table->date('end_date');
            $table->BigInteger('category_id')->unsigned()->nullable();
            $table->BigInteger('tag_id')->unsigned()->nullable();
            $table->timestamps();


        });

        Schema::table('product_loan_system', function(Blueprint $table) {
            $table->foreign('product')->references('id')->on('products');
            $table->foreign('user')->references('id')->on('users');
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
        Schema::dropIfExists('product_loan_system');
    }
};
