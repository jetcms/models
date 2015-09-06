<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('lable', 255);
            $table->char('accesskey')->nullable();
            $table->integer('tabindex')->nullable();

            $table->string('name', 255)->nullable();
            $table->string('url', 255)->nullable();

            $table->integer('parent_id')->nullable();
            $table->integer('lft')->nullable()->index();
            $table->integer('rgt')->nullable()->index();
            $table->integer('depth')->nullable();
            //$table->integer('order');

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
        Schema::drop('menus');
    }
}
