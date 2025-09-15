<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateUsersTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('password');
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('industry')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('active')->default(1);
            $table->string('profile_picture')->default('default.png');
            $table->boolean('active_publisher')->default(0);
            $table->string('type')->default('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
