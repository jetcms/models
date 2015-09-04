<?php namespace JetCMS\Models;

use Eloquent;

class GalleryImage extends Eloquent {

    public function gallery()
    {
        return $this->belongsTo('App\Gallery');
    }

}