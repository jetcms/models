<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageFeldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_fields', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('page_id')->nullable();
            
            $table->string('name', 255)->nullable();          
            $table->text('value')->nullable(); 

            $table->string('type', 255)->nullable();  

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
        Schema::drop('page_fields');
    }
}
