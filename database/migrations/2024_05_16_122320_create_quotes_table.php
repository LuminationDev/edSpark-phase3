<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
            $table->unsignedBigInteger('version_id');
            $table->foreign('version_id')->references('version')->on('catalogue_versions')->onDelete('no action');
            $table->json('quote_content');
            $table->decimal('total_price_ex_gst', 16, 2);
            $table->enum('status', ['ACTIVE', 'ABANDONED', 'COMPLETED'])->default('ACTIVE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['version_id']);
        });
        Schema::dropIfExists('quotes');
    }
};
