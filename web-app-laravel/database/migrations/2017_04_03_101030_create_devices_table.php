<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function(Blueprint $table){
            $table->increments('id');
            // $table->integer('user_profile_id')->unsigned();
            $table->string('device_eui', 50)->unique();
            $table->string('device_name', 50)->default('');
            $table->string('device_description', 50)->default('');
            $table->string('device_version', 30)->default('');
            $table->string('device_type', 30)->default('');
            $table->string('share_status', 30)->default('');

        });

        Schema::create('device_user_profile', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_profile_id')->unsigned();
            $table->integer('device_id')->unsigned();

            $table->foreign('user_profile_id')->references('id')->on('user_profiles')->onDelete('cascade');
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
        Schema::dropIfExists('device_user_profile');

        Schema::dropIfExists('devices');

    }
}
