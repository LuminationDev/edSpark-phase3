<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('labellables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('label_id');
            $table->morphs('labellable');
            $table->timestamps();

            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
            $table->unique(['label_id','labellable_type','labellable_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('labellables');
    }
};