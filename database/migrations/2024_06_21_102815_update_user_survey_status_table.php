<?php

use App\Models\UserSurvey;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `user_surveys` MODIFY `status` ENUM('In Progress', 'Abandoned', 'Complete','Superseded') NOT NULL");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `user_surveys` MODIFY `status` ENUM('In Progress', 'Abandoned', 'Pending') NOT NULL");

    }
};
