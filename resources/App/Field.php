<?php namespace App;

use JetCMS\Models\Field as BaseModel;

/**
 * App\Fields
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $name
 * @property string $value
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Page $page
 * @method static \Illuminate\Database\Query\Builder|\App\PageField whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PageField wherePageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PageField whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PageField whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PageField whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PageField whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PageField whereUpdatedAt($value)
 */
class Field extends BaseModel {

}