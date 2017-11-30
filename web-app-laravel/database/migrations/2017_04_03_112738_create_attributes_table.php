<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function(Blueprint $table){
            $table->increments('id');
            $table->string('name', 50);
            $table->string('value');
            $table->boolean('type');
        });

        Schema::create('attribute_event', function(Blueprint $table){
            $table->increments('id');
            $table->integer('attribute_id')->unsigned();
            $table->integer('event_id')->unsigned();

            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_event');
        
        Schema::dropIfExists('attributes');

    }
}
