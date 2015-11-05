<?php namespace JetCMS\Models;

use Eloquent;

class Tag extends Eloquent {
/*
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function accessPages()
    {
        return $this->belongsToMany('App\Page');
    }

    public function setUsersAttribute($users)
    {
        $this->users()->detach();
        if ( ! $users) return;
        if ( ! $this->exists) $this->save();
        $this->users()->attach($users);
    }

    public function setAccessPagesAttribute($pages)
    {
        $this->accessPages()->detach();
        if ( ! $pages) return;
        if ( ! $this->exists) $this->save();
        $this->accessPages()->attach($pages);
    }
*/
}