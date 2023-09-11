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
        Schema::create('auto_saves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->string('post_type');
            $table->string('status')->default('auto-saved');
            $table->longText('content'); // Storing the actual content. will be json encoded
            $table->timestamp('exp_date')->nullable(); // Expiration date for the auto-save
            $table->boolean('is_active')->default(true); // Indicates if the auto-save is the most recent
            $table->timestamps();

            // Setting up foreign key only for the user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes for faster lookups
            $table->index('user_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auto_saves');
    }
};
