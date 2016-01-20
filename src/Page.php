<?php namespace JetCMS\Models;

use App\Field;
use Carbon\Carbon;
use Eloquent;
use Validator;

class Page extends Eloquent {

    protected $appends = ['url','field_array'];
    protected $fieldsArray = null;
    protected $dates = ['publish'];

    public $validator = null;

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
        return $this->belongsToMany('App\Tag','page_tag')->withTimestamps();
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

    public function getGalleryAttribute($value)
    {
        return preg_split('/,/', $value, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function setGalleryAttribute($photos)
    {
        $this->attributes['gallery'] = implode(',', $photos);
    }

    public function fieldsToArray()
    {
        if ($this->fieldsArray) return $this->fieldsArray;
        $arr = [];
        foreach ($this->fields as $val)
        {
            $arr[$val->name] = $val->value;
        }
        $this->fieldsArray = $arr;
        return $arr;
    }

    public function field($name,$default = null)
    {
        if (isset($this->fieldsToArray()[$name])) {
            return $this->fieldsToArray()[$name];

        }else{
            return $default;
        }
    }

    public function getFieldArrayAttribute()
    {
        return $this->fieldsToArray();
    }

    public function setFieldArrayAttribute($field)
    {
        $create = [];

        if ( ! $field) return;
        if ( ! $this->exists) $this->save();

        $fields = Field::where('page_id',$this->id)->get();

        if (is_array($field)){
            foreach ($field as $key=>$val){
                $isset = false;
                foreach($fields as $v){
                    if ($v->name == $key){
                        $isset = true;
                        if (!empty($val)){
                            $v->value = $val;
                        }else{
                            Field::destroy($v->id);
                        }
                    }
                }
                if( ! $isset){
                    $create[$key] = $val;
                }
            }
            if (sizeof($create)>0){
                foreach($create as $key=>$val){
                    if (!empty($val)){
                        $f = new Field();
                        $f->name = $key;
                        $f->value = $val;
                        $fields[] = $f;
                    }
                }
            }
            $this->fields()->saveMany($fields);
        }
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = trim($value);
    }

    public function setContextAttribute($value)
    {
        $this->attributes['context'] = trim($value);
    }

    public function setAliasAttribute($value)
    {
        $this->attributes['alias'] = trim($value);
    }

    public function setPublishAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['publish'] = Carbon::now();
        }else{
            $this->attributes['publish'] = $value;
        }
    }

    public function setSortAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['sort'] = 500;
        }else{
            $this->attributes['sort'] = $value;
        }
    }

    public function scopeContext($query,$value)
    {
        return $query->where('context',$value);
    }

    public function scopeSort($query,$value = 'DESC')
    {
        $query->orderBy('sort',$value);
        $query->orderBy('publish',$value);
        return $query;
    }

    public function scopeAlias($query,$alias)
    {
        return $query->where('alias',$alias);
    }

    public function scopeActive($query)
    {
        return $query->where('active',true)->where('publish', '<', Carbon::now());
    }

    public function save(array $options = []){

        $this->validator = Validator::make(
            array(
                'title' => trim($this->title),
                'alias' => trim($this->alias)
            ),
            array(
                'title' => 'unique:pages,title,'.$this->id,
                'alias' => 'unique:pages,alias,'.$this->id.',id,context,'.trim($this->context)
            ),
            array(
                'title' => 'double Title',
                'alias' => 'double Alias this context'
            )
        );

        if ($this->validator->fails())
        {
            return false;
        }

        return parent::save();
    }

}