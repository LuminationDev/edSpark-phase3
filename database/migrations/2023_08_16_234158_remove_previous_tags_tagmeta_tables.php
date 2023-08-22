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
        Schema::table('tag_metas', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
        });
        Schema::dropIfExists('tags');
        Schema::dropIfExists('tag_metas');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->integer('object_id');
            $table->string('tag_name');
            $table->text('tag_description');
            $table->timestamps();
        });
        Schema::create('tag_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->string('tag_meta_key');
            $table->text('tag_meta_value');
            $table->timestamps();
        });

        Schema::table('tag_metas', function (Blueprint $table) {
            // FOREIGN KEY
            $table->foreign('tag_id')
                ->references('id')->on('tags')->onDelete('cascade');
        });
    }
};
