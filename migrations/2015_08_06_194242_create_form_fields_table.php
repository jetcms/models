<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_fields', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('form_id');

            $table->string('name',255)->nullable();
            $table->string('lable',255)->nullable();

            $table->text('description')->nullable();

            $table->boolean('type')->nullable();
            $table->boolean('require')->nullable();
            $table->boolean('active')->nullable();
            $table->integer('order')->nullable();

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
        Schema::drop('form_fields');
    }
}
