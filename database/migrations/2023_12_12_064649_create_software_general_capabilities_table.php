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
        Schema::create('software_general_capabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('software_id');
            $table->unsignedBigInteger('general_capability_id');
            $table->timestamps();

            $table->foreign('software_id')->references('id')->on('softwares')->onDelete('cascade');
            $table->foreign('general_capability_id')->references('id')->on('general_capabilities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('software_general_capabilities', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['software_id']);
            $table->dropForeign(['general_capability_id']);
        });

        Schema::dropIfExists('software_general_capabilities');
    }
};
