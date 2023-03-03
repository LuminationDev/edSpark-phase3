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
        Schema::create('taxonomymeta', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('taxonomy_id');
            $table->string('taxonomymeta_title');
            $table->text('taxonomymeta_value');
            $table->timestamps();

            // FOREIGN KEY
            $table->foreign('taxonomy_id')
                ->references('id')->on('taxonomy')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxonomymeta');
    }
};
