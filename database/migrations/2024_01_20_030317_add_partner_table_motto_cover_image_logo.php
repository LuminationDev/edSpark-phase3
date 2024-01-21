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
        Schema::table('partner_profiles', function (Blueprint $table) {
            $table->text('introduction')->nullable()->before('content');
            $table->text('motto')->nullable()->before('content');
            $table->string('cover_image')->nullable()->after('content');
            $table->string('logo')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner_profiles', function (Blueprint $table) {
            $table->dropColumn(['introduction', 'motto', 'cover_image', 'logo']);
        });
    }
};
