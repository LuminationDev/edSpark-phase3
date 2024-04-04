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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('site_id')->unique()->nullable();
            $table->string('site_name');
            $table->text('site_value')->nullable();
            $table->string('category_code')->nullable();
            $table->string('category_desc')->nullable();
            $table->string('site_type_code')->nullable();
            $table->text('site_type_desc')->nullable();
            $table->string('site_sub_type_code')->nullable();
            $table->text('site_sub_type_desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
