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
        Schema::create('event_formats', function (Blueprint $table) {
            $table->id();
            $table->string('event_format_name');
            $table->string('event_format_value');
            $table->timestamps(); // Adds created_at and updated_at columns
        });

        Schema::table('events', function (Blueprint $table) {
            // Add event_format_id column
            $table->unsignedBigInteger('event_format_id')->nullable()->after('eventtype_id');

            // Add foreign key constraint
            $table->foreign('event_format_id')
                ->references('id')
                ->on('event_formats')
                ->onDelete('set null'); // Set null when the referenced record is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['event_format_id']);

            // Drop event_format_id column
            $table->dropColumn('event_format_id');
        });

        Schema::dropIfExists('event_formats');
    }
};
