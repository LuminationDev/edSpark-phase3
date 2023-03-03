<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullName');
            $table->string('displayName');
            $table->string('email');
            $table->string('password');
            $table->string('status');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('site_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('meta_id');
            $table->timestamps();

            // FOREIGN KEYS
            $table->foreign('role_id')
                ->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')
                ->references('id')->on('userpermissions')->onDelete('cascade');
            $table->foreign('site_id')
                ->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('type_id')
                ->references('id')->on('usertypes')->onDelete('cascade');
            $table->foreign('meta_id')
                ->references('id')->on('usermeta')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
