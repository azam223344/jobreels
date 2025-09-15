<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangesToFlagedContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flaged_contents', function (Blueprint $table) {
            $table->string('action_taken')->nullable()->change();
            $table->dropColumn('action description');
            $table->string('action_description')->nullable();
            $table->boolean('resolved')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flaged_contents', function (Blueprint $table) {
            //
        });
    }
}
