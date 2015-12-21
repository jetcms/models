<?php namespace JetCMS\Models;

use Eloquent;

class Image extends Eloquent {

    public function gallery()
    {
        return $this->belongsTo('App\Gallery');
    }
    
}