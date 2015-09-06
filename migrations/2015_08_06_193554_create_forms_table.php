<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('name', 255);

            $table->string('url', 255);

            $table->text('lable')->nullable();
            $table->text('description')->nullable();

            $table->text('message')->nullable();
            $table->text('title_message')->nullable();
            
            $table->boolean('active')->nullable();
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
        Schema::drop('forms');
    }
}
