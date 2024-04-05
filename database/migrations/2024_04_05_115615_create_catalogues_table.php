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
        Schema::create('catalogues', function (Blueprint $table) {
            $table->id();
            $table->string('unique_reference')->unique();
            $table->string('type');
            $table->string('brand');
            $table->string('name');
            $table->string('vendor');
            $table->string('category')->nullable();
            $table->string('price_inc_gst')->nullable();
            $table->string('processor')->nullable();
            $table->string('storage')->nullable();
            $table->string('memory')->nullable();
            $table->string('form_factor')->nullable();
            $table->string('display')->nullable();
            $table->string('graphics')->nullable();
            $table->string('wireless')->nullable();
            $table->string('webcam')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('warranty')->nullable();
            $table->string('battery_life')->nullable();
            $table->string('weight')->nullable();
            $table->string('stylus')->nullable();
            $table->string('other')->nullable();
            $table->string('available_now')->nullable();
            $table->string('corporate')->nullable();
            $table->string('administration');
            $table->string('curriculum')->nullable();
            $table->string('image')->nullable();
            $table->string('product_number')->nullable();
            $table->string('price_expiry')->nullable();
            $table->timestamps();

            // Indexing columns
            $table->index(['type', 'brand', 'vendor'], 'catalogue_index')
                ->collation('utf8mb4_general_ci')
                ->length(['type' => 191, 'brand' => 191, 'vendor' => 191]);;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogue');
    }
};

