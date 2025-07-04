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
        Schema::table('events', function (Blueprint $table) {
            $table->foreign('author_id')
                ->references('id')->on('users')->onDelete('no action');
            $table->foreign('event_format_id')
                ->references('id')
                ->on('event_formats')
                ->onDelete('set null');
            $table->foreign('event_type_id')
                ->references('id')
                ->on('event_types')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropForeign(['event_format_id']);
            $table->dropForeign(['event_type_id']);
        });
    }
};
