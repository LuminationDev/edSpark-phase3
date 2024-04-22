<?php

use App\Models\UserSurvey;
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
        Schema::create('user_surveys', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('survey_id')
                ->references('version')
                ->on('surveys')
                ->onDelete('cascade');
            $table->enum('status', UserSurvey::$STATUS_TYPES);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_surveys');
    }
};
