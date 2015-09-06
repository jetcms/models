<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_images', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('gallery_id')->nullable();
            
            $table->string('lable', 255)->nullable();          
            $table->string('image', 255)->nullable();
            $table->string('url', 255)->nullable();

            $table->text('description')->nullable(); 

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
        Schema::drop('gallery_images');
    }
}
