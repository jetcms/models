<?php namespace JetCMS\Models;

use Eloquent;
use \SleepingOwl\Admin\Traits\OrderableModel;

class FormField extends Eloquent {

	use OrderableModel;

    public function form()
    {
        return $this->belongsTo('App\Form');
    }

    public function getOrderField()
	{
	    return 'order';
	}

}