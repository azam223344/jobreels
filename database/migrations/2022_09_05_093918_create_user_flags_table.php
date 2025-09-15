<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFlagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_flags')) {
            Schema::create('user_flags', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('description');
                $table->boolean('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('flaged_users')) {
            Schema::create('flaged_users', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('flag_id')->index();
                $table->unsignedInteger('reported_user_id')->index();
                $table->unsignedInteger('user_id')->index();
                $table->string('description')->nullable();
                $table->string('action_taken');
                $table->string('action_description'); 
                $table->boolean('resolved')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flaged_users');
        Schema::dropIfExists('user_flags');
    }
}
