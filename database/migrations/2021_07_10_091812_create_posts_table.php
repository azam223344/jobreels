<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('breed_id')->unsigned()->index();
            $table->integer('color_id')->unsigned()->index();
            $table->integer('state_id')->unsigned()->index();
            $table->boolean('shipping_available')->default(0);
            $table->integer('price');
            $table->string('description');
            $table->string('video');
            $table->string('status');
            $table->string('status_description');
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
        Schema::dropIfExists('posts');
    }
}
