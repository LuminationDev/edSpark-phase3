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
        Schema::create('waitlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('current_loan_length');
            $table->dateTime('date_requested');
            $table->dateTime('time_requested');
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
        Schema::dropIfExists('waitlists');
    }
};
