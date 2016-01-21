<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJetcmsModelsTable extends Migration
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

        Schema::create('pages', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('title', 255);
            $table->text('description');
            $table->text('content');

            $table->string('alias', 255);
            $table->string('policies', 255);
            $table->string('context', 255);
            $table->string('image', 255);
            $table->string('gallery');

            $table->integer('menu_id');
            $table->integer('user_id');
            $table->string('template',255);

            $table->dateTime('publish');
            $table->boolean('active')->nullable();
            $table->integer('sitemap_id');
            $table->integer('sort');
            $table->timestamps();
        });

        Schema::create('fields', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('page_id')->nullable();

            $table->string('name', 255)->nullable();
            $table->text('value')->nullable();

            $table->string('type', 255)->nullable();

            $table->timestamps();
        });

        Schema::create('comments', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('lable', 255)->nullable();
            $table->text('content')->nullable();
            $table->morphs('comment');
            $table->integer('reply_id')->nullable();
            $table->boolean('active')->nullable();
            $table->timestamps();
        });

        Schema::create('tags', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('lable', 255);
            $table->string('context', 255);
            $table->timestamps();
        });

        Schema::create('page_tag', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('page_id')->unsigned()->index();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->integer('tag_id')->unsigned()->index();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->timestamps();
        });

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
        Schema::drop('pages');

        Schema::drop('menus');

        Schema::drop('comments');

        Schema::drop('fields');

        Schema::drop('tags');

        Schema::drop('page_tag');

        Schema::drop('sitemap');
    }
}
