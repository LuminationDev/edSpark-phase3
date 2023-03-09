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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('product_category_name');
            $table->text('product_category_description')->nullable();
            $table->unsignedBigInteger('product_brand')->nullable();
            $table->unsignedBigInteger('product_inventory')->nullable();
            $table->dateTime('product_created_at')->nullable();
            $table->dateTime('product_modified_at')->nullable();
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
        Schema::dropIfExists('product_categories');
    }
};
