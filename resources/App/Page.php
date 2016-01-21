<?php namespace App;

use JetCMS\Models\Page as BaseModel;
use Carbon;

class Page extends BaseModel {

}

/**
 * Sitemap
 */
Page::created(function($model)
{
    $url = Sitemap::where('loc',$model->url)->first();
    if (!$model->active and !$model->publish < Carbon::now()) {
        if ($url) {
            $url->delete();
        }
    }
});

Page::updated(function($model)
{
    $url = Sitemap::where('loc',$model->url)->first();
    if (!$model->active and !$model->publish < Carbon::now()) {
        if ($url) {
            $url->delete();
        }
    }
});
