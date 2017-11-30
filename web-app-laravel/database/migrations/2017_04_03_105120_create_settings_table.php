<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function(Blueprint $table){
            $table->increments('id');
            $table->string('name', 50);
            $table->string('network_ip', 100);
            $table->integer('port_number');            
            $table->string('client_id', 50);
            $table->string('username', 50);
            $table->string('password', 50);

            $table->integer('network_type_id')->unsigned();
            $table->integer('user_profile_id')->unsigned();

            $table->foreign('network_type_id')->references('id')->on('network_types')->onDelete('restrict');
            $table->foreign('user_profile_id')->references('id')->on('user_profiles')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
