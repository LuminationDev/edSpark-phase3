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
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['usertype_id']);

            // Drop the column
            $table->dropColumn('usertype_id');
        });

        // Drop the user_types table
        Schema::dropIfExists('user_types');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->id();
            $table->string('user_type_name');
            $table->text('user_type_value');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            // Add the usertype_id column back
            $table->bigInteger('usertype_id')->unsigned()->nullable();

            // Add foreign key constraint
            $table->foreign('usertype_id')->references('id')->on('user_types')->onDelete('cascade');
        });
    }
};