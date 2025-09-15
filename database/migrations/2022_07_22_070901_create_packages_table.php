<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->string('name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('paddle_id_monthly')->nullable();
            $table->string('amount_monthly')->nullable();
            $table->string('discounted_amount_monthly')->nullable();
            $table->string('paddle_id_annually')->nullable();
            $table->string('amount_annually')->nullable();
            $table->string('discounted_amount_annually')->nullable();
            $table->text('details')->nullable();
            $table->text('highlighted_details')->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('packages');
    }
}
