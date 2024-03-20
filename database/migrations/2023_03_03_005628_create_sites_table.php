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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            // Erick 7th March removed site_uid's ->unique()
            $table->string('site_uid')->nullable();
            $table->string('site_id')->unique()->nullable();
            $table->string('site_name');
            $table->text('site_value')->nullable();
            $table->string('category_code')->nullable();
            $table->string('category_desc')->nullable();
            $table->string('site_type_code')->nullable();
            $table->text('site_type_desc')->nullable();
            $table->string('site_sub_type_code')->nullable();
            $table->text('site_sub_type_desc')->nullable();
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
        Schema::dropIfExists('sites');
    }
};
