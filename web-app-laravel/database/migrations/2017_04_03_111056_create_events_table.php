<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_profile_id')->unsigned();
            $table->foreign('user_profile_id')->references('id')->on('user_profiles')->onDelete('cascade');
            $table->string('type', 30);
            $table->string('interface_model', 10);
            $table->text('request_event')->nullable();
        });

        Schema::create('device_event', function(Blueprint $table){
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->integer('device_id')->unsigned();
            $table->string('type')->nullable();
            $table->string('value')->nullable();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade
                ');
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
        Schema::dropIfExists('device_event');
        
        Schema::dropIfExists('events');

    }
}
