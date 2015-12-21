<?php namespace JetCMS\Models;

use Eloquent;

class Comment extends Eloquent {

	public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function object()
    {
         return $this->morphTo();
    }

}