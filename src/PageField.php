<?php namespace JetCMS\Models;

use Eloquent;

class PageField extends Eloquent {

    public function page()
    {
        return $this->belongsTo('App\Page');
    }

}