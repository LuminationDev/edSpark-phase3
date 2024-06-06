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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor');
            $table->string('business_name');
            $table->string('abn')->nullable();
            $table->string('email_enquiries')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_general_enquiries')->nullable();
            $table->string('fax')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('website')->nullable();
            $table->string('portal')->nullable();
            $table->string('email_orders')->nullable();
            $table->text('warranty_support_info')->nullable();
            $table->text('buyers_guide')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('confirmed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
