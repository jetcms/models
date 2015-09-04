<?php namespace JetCMS\Models;

use Eloquent;

class Form extends Eloquent {

	public function fields()
    {
        return $this->hasMany('App\FormField')->orderBy('sort', 'asc');
    }

}