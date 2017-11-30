<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_actions', function(Blueprint $table){
            $table->increments('id');
            $table->string('type', 50);
            $table->string('description', 100);
        });

        Schema::create('device_request_action', function(Blueprint $table){
            $table->increments('id');
            $table->integer('device_id')->unsigned();
            $table->integer('request_action_id')->unsigned();

            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('request_action_id')->references('id')->on('request_actions')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_request_action');
        
        Schema::dropIfExists('request_actions');
        
    }
}
