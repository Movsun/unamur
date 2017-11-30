<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoraActivationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lora_activations', function(Blueprint $table){
            $table->increments('id');
            // $table->string('device_eui')->unique();
            $table->string('lora_mode');
            $table->string('device_address')->nullable();
            $table->string('network_session_key')->nullable();
            $table->string('application_session_key')->nullable();
            $table->string('application_eui')->nullable();
            $table->string('application_key')->nullable();

            $table->integer('device_id')->unsigned();
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
        Schema::dropIfExists('lora_activations');
    }
}
