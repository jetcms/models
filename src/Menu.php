<?php namespace JetCMS\Models;

use Baum\Node;
use Cache;

class Menu extends Node {

	protected $appends = ['level_lable','is_active'];

	static protected $activeMenu = null;

   	static public function setActiveMenuId($id)
    {
        self::$activeMenu = Menu::find($id);
    }

    static public function setActiveMenuObject(Menu $obj)
    {
        self::$activeMenu = $obj;
    }

    static public function getActiveMenu()
    {
        return self::$activeMenu;
    }

    static public function getActiveMenuId()
    {
    	if (self::$activeMenu)
    	{
    		return self::$activeMenu->id;
    	}
    	else
    	{
    		return null;
    	}
    }

    static public function getActiveMenuRoot()
    {
    	
		if (self::$activeMenu)
    	{
    		return Cache::remember( 'active_menu_root'.self::$activeMenu->id, config('jetcms.web.cache_time'), function()
	    	{
				return Menu::$activeMenu->getRoot();
			});
    	}
    	else
    	{
    		return null;
    	}
	}

    static public function getActiveMenuRootId()
    {
    	if (self::$activeMenu)
    	{
    		return self::getActiveMenuRoot()->id;
    	}
    	else
    	{
    		return null;
    	}
    }

    static public function getActiveLevel($id = null)
    {
    	if ($id === null)
    	{
    		$id = self::$activeMenu;
    	}

    	if ($id !== null)
    	{
    		return Cache::remember( 'active_menu_level' . Menu::getActiveMenuId(), config('jetcms.web.cache_time'), function()
		    {
				$menuActive = Menu::getActiveMenu();
		        $menu = array();
		        if ($menuActive)
		        {
		            $m = $menuActive->ancestorsAndSelf()->where('active',true)->with( 'childs' )->get();
		            foreach ($m as $v)
		            {
		                $menu[] = $v->childs;
		            }
		            return $menu;
		        }
			    return [];    
		    });
    	}
    	else
    	{
    		return [];
    	}
    }

    static public function getTree()
    {
    	$menu = Cache::remember( 'active_menu_tree', config('jetcms.web.cache_time'), function()
	    {
			return Menu::orderBy('lft','asc')->get();
		});

		$tree = [];
		if ($menu)
		{
			foreach ($menu as $key => $val) 
			{
				
				if ($val->parent_id !== null)
				{
					if (!isset($tree[$val->parent_id]))
					{
						$tree[$val->parent_id] = [];
					}
					$tree[$val->parent_id][] = $val->toArray();
				}
				else
				{
					if (!isset($tree[0]))
					{
						$tree[$val->parent_id] = [];
					}
					$tree[0][] = $val->toArray();
				}
				
			}
		}
		unset($tree['']);
		return $tree;
    }

	public function getLevellableAttribute()
	{
		$prefix = '';

		for ( $i=0; $i < $this->attributes['depth']; $i++ )
		{
			$prefix .= '- ';
		}

	    return $prefix.$this->attributes['lable'];
	}

	public function getIsActiveAttribute()
	{
		if (!self::getActiveMenu())
		{
			return false;
		}

		$parent = Cache::remember( 'jetcms_active_menu'.self::getActiveMenu()->id, 1, function()
    	{
			return self::getActiveMenu()->getAncestorsAndSelf();
		});	
		$ids = [];
		foreach ($parent as $key => $val) 
		{
			$ids[] = $val->id;
		}

		if (in_array($this->id, $ids))
		{
			return true;
		}
		return false;
	}

	public function childs()
    {
        return $this->hasMany( 'App\Menu', 'parent_id', 'id' )->orderBy('lft','asc');
    }

}