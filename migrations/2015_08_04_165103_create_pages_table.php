<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->text('content')->nullable();

            $table->string('alias', 255)->nullable();
            $table->string('policies', 255)->nullable();
            $table->string('image', 255)->nullable();

            $table->integer('menu_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('template',255)->nullable();
            
            $table->dateTime('publish');
            $table->boolean('active')->nullable();
            $table->integer('sitemap_id')->nullable();
            $table->integer('sort')->nullable();
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
        Schema::drop('pages');
    }
}
