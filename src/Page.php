<?php namespace JetCMS\Models;

use Carbon\Carbon;
use Eloquent;

class Page extends Eloquent {

    protected $appends = ['url'];

    static protected $activePage = null;

    public function setActivePage()
    {
        self::$activePage = $this;
    }

    static public function getActivePage()
    {
        return self::$activePage;
    }

    public function getMakeAliasAttribute()
    {
        if (!empty($this->context))
        {
            return $this->context.'/'.$this->alias;
        }
        return $this->alias;
    }

	public function menu()
    {
        return $this->belongsTo('App\Menu');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function sitemap()
    {
        return $this->belongsTo('App\Sitemap');
    }

    public function tag()
    {
        return $this->belongsToMany('App\Tag','page_tag','id')->withTimestamps();
    }

    public function setTagAttribute($accessPages)
    {
        $this->tag()->detach();
        if ( ! $accessPages) return;
        if ( ! $this->exists) $this->save();
        $this->tag()->attach($accessPages);
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'comment')->where('active',true);
    }

    public function fields()
    {
        return $this->hasMany('App\Field','page_id','id');
    }

    public function getAliasAttribute()
    {
        if (empty($this->attributes['alias'])){
            if ( $this->exists) {
                if (empty($this->attributes['alias'])){
                    $this->alias = 'id'.$this->id;
                    $this->save();
                }
            }
        }
        if (isset($this->attributes['alias']))
        {
            return $this->attributes['alias'];
        }
        return null;
    }

    public function getUrlAttribute()
    {
        if (!empty($this->attributes['context'] and $this->attributes['context'] != 'page')){
            if ($this->attributes['alias'] == '/'){
                return '/'.$this->attributes['context'];
            }else{
                return '/'.$this->attributes['context'].'/'.$this->attributes['alias'];
            }

        }
        if ($this->attributes['alias'] == '/'){
            return $this->attributes['alias'];
        }else{
            return '/'.$this->attributes['alias'];
        }

    }

/*переменовать*/

    public function getPageFieldToArrayAttribute()
    {
        $arr = [];

        foreach ($this->fields as $val)
        {
            $arr[$val->name] = $val->value;
        }

        return $arr;
    }

        /*--------*/

    public function scopeContext($query,$value)
    {
        return $query->where('context',$value);
    }

    public function scopeAlias($query,$alias)
    {
        return $query->where('alias',$alias);
    }

    public function scopeActive($query)
    {
        return $query->where('active',true)->where('publish', '<', Carbon::now());
    }
}