<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('partner_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id');
            $table->unsignedBigInteger('user_id');
            $table->text('content');
            $table->string('status');  // Consider using an enum if there's a fixed set of statuses.
            $table->timestamps();

            // Foreign key constraints.
            $table->foreign('partner_id')
                ->references('id')->on('partners')
                ->onDelete('cascade');  // If a partner is deleted, associated profiles are also deleted. Adjust as needed.

            // Assuming the users table is named 'users'. Adjust if different.
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('no action');  // If a user is deleted, associated profiles are also deleted. Adjust as needed.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_profiles');
    }
};
