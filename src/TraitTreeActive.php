<?php namespace JetCMS\Models;

use Cache;

trait TraitTreeActive {

    /**
     * Активная запись в форма хронения модель
     * @var self
     */
    static protected $active_model = [];

    static protected $map = null;

    /**
     * Суфикс для названия кеша
     * @var string
     */
    //static protected $prefix_name_chahe = 'jetcms_base_model_tree';

    /**
     * Получает все записи и заносит их в массив, ключь в массиве это ID запеси
     * @param bool $active
     * @return mixed
     */
    static public function createMap($active = true)
    {
        if (!self::$map){
            self::$map = Cache::remember(self::$prefix_name_chahe . '_map_' . $active, config('jetcms.model
            .cache_time', null),

                function () use ($active) {
                    if ($active) {
                        $list = self::orderBy('lft', 'asc')->active()->get();
                    } else {
                        $list = self::orderBy('lft', 'asc')->get();
                    }
                    $map = [];
                    foreach ($list as $val) {
                        $map[$val->id] = $val;
                    }
                    return $map;
                });
        }
        return  self::$map;
    }

    /**
     * получает все записи и заносит их в двухмерный массив
     * где первый масив имеет ключи соответствующии полу parent_id
     * @param bool $active
     * @return mixed
     */
    static public function createMapParent($active = true)
    {
        return Cache::remember(self::$prefix_name_chahe.'_map_parent_'.$active, config('jetcms.model.cache_time',null),
            function() use ($active) {
                $list = self::createMap($active);
                $mapParent = [];
                foreach ($list as $val)
                {
                    if ($val->parent_id === null)
                    {
                        $mapParent[0][] = $val;
                    }
                    else
                    {
                        $mapParent[$val->parent_id][] = $val;
                    }

                }

                return $mapParent;
            });
    }

    /**
     * создает двухмерный массив где первй массив имет ключи соответствующи ID предкам данного объекта
     * @return array
     */
    public function getMapLevel($self_in = false)
    {
        $mapParent = $this->createMapParent();
        $parent = $this->getParentsId($self_in);
        $level = [];
        if (!is_array($parent) and !is_array($mapParent))
        {
            return $level;
        }

        foreach ($parent as $val)
        {
            if (isset($mapParent[$val]))
            {
                $level[] = $mapParent[$val];
            }
        }
        return $level;
    }

    /**
     * Получает предков тякущего объекта если передать true то вернет только ID предков по умолчанию возврощает обеъкты
     * массив ачинается с root и спускается непосредственно к объекту
     * @param bool|false $return_id
     * @return array
     */
    public function getParents($self_in = false)
    {
        if ($self_in) {
            $id = $this->id;
        } else {
            $id = $this->parent_id;
        }

        $map = self::createMap();
        $parentId = [];

        if (!is_array($map))
        {
            return $parentId;
        }

        while (isset($map[$id]) and $map[$id]->parent_id !== null)
        {
            $parentId[$id] = $map[$id];
            $id = $map[$id]->parent_id;
        }
        if (isset($map[$id])) {
            $parentId[$id] = $map[$id];
        }


        $parentId = array_reverse($parentId);

        return $parentId;
    }

    public function getParentsId($self_in = false)
    {
        $parents = $this->getParents($self_in);
        $ids = [];
        foreach ($parents as $key=>$val) {
            $ids[$key] = $val->id;
        }
        return $ids;
    }

    public function getChilds($id = null)
    {

        $ids = [];

        if ($id === null) {
            $id = $this->id;
        }

        $map = $this->createMapParent();

        if (isset($map[$id])){
            foreach ($map[$id] as $val){
                $ids[] = $val;
                $ids = array_merge($ids,$this->getChilds($val->id));
            }
        }
        return $ids;
    }

    public function getChildsId ($self_in = false)
    {
        $ids = [];
        $id = $this->id;
        if ($self_in){
            $ids[] = $id;
        }

        $arr = $this->getChilds($id);

        if (is_array($arr)) {
            foreach($arr as $val) {
                $ids[] = $val->id;
            }
        }
        return $ids;
    }

    public function childs()
    {
        return $this->hasMany( $this, 'parent_id', 'id' )->orderBy('lft','asc');
    }

    public function parent()
    {
        return $this->hasOne( $this, 'id', 'parent_id' );
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }


    public function getIsActiveAttribute()
    {
        if (!self::$active_model) {
            return false;
        }

        $parents = self::$active_model->getParentsId(true);

        if (is_array($parents)){
            if (in_array($this->id,$parents)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Получить активную запись
     */
    static public function getActiveModel ()
    {
        return self::$active_model;
    }

    /**
     * устонавить активную запись по ID
     * @param $id
     */
    static public function setActiveId ($id)
    {
        $model = self::find($id);
        if ($model)
        {
            self::$active_model = $model;
        }
    }

    /**
     * устонавить активную запись по ID
     * @param $name
     */
    static public function setActiveName ($name)
    {
        $model = self::where('name',$name)->first();
        if ($model)
        {
            self::$active_model = $model;
        }
    }

    /**
     * Установить активную запись передав модель
     * @param $model
     */
    static public function setActiveModel (self $model)
    {
        self::$active_model = $model;
    }

    /**
     * Устоновить текущий обект активной записью
     */
    public function setActiveThis ()
    {
        self::$active_model = $this;
    }
}