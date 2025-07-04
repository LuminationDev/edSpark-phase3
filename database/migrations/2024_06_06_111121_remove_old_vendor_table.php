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
        Schema::dropIfExists('vendors');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name');
            $table->string('address')->nullable();
            $table->string('abn')->nullable();
            $table->string('order_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('contact')->nullable();
            $table->string('direct_phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }
};
