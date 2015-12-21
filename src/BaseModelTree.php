<?php namespace JetCMS\Models;

use Baum\Node;

class BaseModelTree extends Node {

    protected $appends = ['level_lable','is_active'];

    protected $table = 'model_tree';

    public function getLevelLableAttribute()
    {
        $prefix = '';

        for ( $i=0; $i < $this->attributes['depth']; $i++ )
        {
            $prefix .= '- ';
        }

        return $prefix.$this->attributes['lable'];
    }

}