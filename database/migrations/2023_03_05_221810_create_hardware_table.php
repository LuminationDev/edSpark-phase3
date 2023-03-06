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
            $table->unsignedBigInteger('product_owner');
            $table->string('product_name');
            $table->longText('product_content');
            $table->text('product_excerpt');
            $table->float('price');
            $table->dateTime('created');
            $table->dateTime('modified');
            $table->string('product_SKU');
            $table->unsignedBigInteger('product_category');
            $table->unsignedBigInteger('product_inventory');
            $table->unsignedBigInteger('product_brand');
            $table->boolean('product_isLoan');
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
