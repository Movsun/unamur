<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policies', function (Blueprint $table){
            $table->increments('id');
            $table->integer('user_profile_id')->unsigned();
            $table->text('request');
            $table->string('type');
            $table->integer('device_id')->unsigned();
            $table->string('description');
            $table->string('condition_type');
            $table->integer('subject_id')->unsigned();
            $table->string('action');

            $table->foreign('user_profile_id')->references('id')->on('user_profiles')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('user_profiles')->onDelete('cascade');

            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policies');
    }
}
