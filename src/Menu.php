<?php namespace JetCMS\Models;

use Baum\Node;

class Menu extends Node {

	protected $appends = ['level_lable'];

	public function getLevellableAttribute()
	{
		$prefix = '';

		for ( $i=0; $i < $this->attributes['depth']; $i++ )
		{
			$prefix .= '- ';
		}

	    return $prefix.$this->attributes['lable'];
	}

}