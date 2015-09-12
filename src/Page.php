<?php namespace JetCMS\Models;

use Eloquent;

class Page extends Eloquent {

	//protected $fillable = ['name', 'email', 'password','roles'];

    static protected $activePage = null;

    public function setActivePage()
    {
        self::$activePage = $this;
    }

    static public function getActivePage()
    {
        return self::$activePage;
    }

	public function menu()
    {
        return $this->belongsTo('App\Menu');
    }

    public function accessPages()
    {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }

    public function setAccessPagesAttribute($accessPages)
    {
        $this->accessPages()->detach();
        if ( ! $accessPages) return;
        if ( ! $this->exists) $this->save();
        $this->accessPages()->attach($accessPages);
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'comment');
    }

    public function pagefield()
    {
        return $this->hasMany('App\PageField');
    }


}