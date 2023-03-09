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
        Schema::create('hardwares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_owner')->nullable();
            $table->string('product_name');
            $table->longText('product_content');
            $table->text('product_excerpt')->nullable();
            $table->float('price');
            $table->dateTime('created')->nullable();
            $table->dateTime('modified')->nullable();
            $table->string('product_SKU')->nullabel();
            $table->unsignedBigInteger('product_category')->nullable();
            $table->unsignedBigInteger('product_inventory')->nullable();
            $table->unsignedBigInteger('product_brand')->nullable();
            $table->boolean('product_isLoan')->nullable();
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
        Schema::dropIfExists('hardwares');
    }
};
