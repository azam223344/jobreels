<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreelancersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('freelancer_id')->nullable();
        });
        
        Schema::create('freelancers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('photo')->nullable();
            $table->string('photo_of_govt_id')->nullable();;
            $table->string('photo_with_govt_id')->nullable();;
            $table->text('bills')->nullable();;
            $table->string('portfolio_website')->nullable();;
            $table->text('description')->nullable();;
            $table->string('salary_requirements')->nullable();;
            $table->string('full_time')->nullable();;
            $table->string('hourly_rate')->nullable();;
            $table->text('skills_experience')->nullable();;
            $table->text('skills_assessment')->nullable();;
            $table->string('upwork')->nullable();;
            $table->string('fiverr')->nullable();;
            $table->string('linkedin')->nullable();;
            $table->string('instagram')->nullable();;
            $table->string('facebook')->nullable();;
            $table->string('youtube')->nullable();;
            $table->string('tiktok')->nullable();;
            $table->string('twitter')->nullable();;
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
        Schema::dropIfExists('freelancers');
    }
}
