<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 255);

            $table->string('lable', 255)->nullable();          
            $table->text('content')->nullable();

            $table->boolean('active')->nullable();
			
			$table->integer('image_id')->nullable();

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
        Schema::drop('gallery');
    }
}
