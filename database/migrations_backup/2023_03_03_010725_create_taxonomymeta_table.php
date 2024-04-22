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
        Schema::create('taxonomy_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxonomy_id')->nullable();
            $table->string('taxonomy_meta_key');
            $table->text('taxonomy_meta_value');
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
        Schema::dropIfExists('taxonomy_metas');
    }
};
