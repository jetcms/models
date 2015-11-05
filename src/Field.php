<?php namespace JetCMS\Models;

use Eloquent;

class Field extends Eloquent {

    public function page()
    {
        return $this->belongsTo('App\Page');
    }

}