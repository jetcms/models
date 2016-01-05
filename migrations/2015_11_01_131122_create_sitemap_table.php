<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitemapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sitemap', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('loc',255);
            $table->dateTime('lastmod');
            $table->string('changefreq');
            $table->decimal('priority',2);
            $table->boolean('in_sitemap')->nullable();

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
        Schema::drop('sitemap');
    }
}