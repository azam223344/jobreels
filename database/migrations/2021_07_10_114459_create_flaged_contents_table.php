<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlagedContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flaged_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('flag_id')->unsigned()->index();
            $table->integer('post_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->string('description')->nullable();
            $table->string('action_taken');
            $table->string('action description');
            $table->boolean('resolved')->default(0);
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
        Schema::dropIfExists('flaged_contents');
    }
}
