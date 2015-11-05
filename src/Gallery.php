<?php namespace JetCMS\Models;

use Eloquent;

class Gallery extends Eloquent {

	protected $table = 'gallery';

    public function images()
    {
        return $this->hasMany('App\Image');
    }

}