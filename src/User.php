<?php namespace JetCMS\Models;

//use App\Role;
use Carbon;
use Hash;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;

use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $appends = ['set_password'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password','roles'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }
    /**
     * Does the user have a particular role?
     *
     * @param $name
     * @return bool
     */
    public function hasRole($name)
    {
        foreach ($this->roles as $role)
        {
            if ($role->name == $name) return true;
        }
        return false;
    }
    /**
     * Assign a role to the user
     *
     * @param $role
     * @return mixed
     */
    public function assignRole($role)
    {
        return $this->roles()->attach($role);
    }
    /**
     * Remove a role from a user
     *
     * @param $role
     * @return mixed
     */
    public function removeRole($role)
    {
        return $this->roles()->detach($role);
    }

    public function scopeGetUserRole($query,$type = null)
    {
        return $query->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->select('users.*','role_user.role_id')
            ->where('role_user.role_id',$type);
    }

    public function setRolesAttribute($roles)
    {
        $this->roles()->detach();
        if ( ! $roles) return;
        if ( ! $this->exists) $this->save();
        $this->roles()->attach($roles);
    }

    public function getSetPasswordAttribute()
    {
        return null;
    }

    public function setSetPasswordAttribute($password)
    {
        if ($password)
        {
            $this->attributes['password'] = Hash::make($password);
        }
        return null;
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function getAvatarAttribute()
    {
        if (file_exists(public_path($this->attributes['avatar'])) and is_file(public_path($this->attributes['avatar'])))
        {
            return $this->attributes['avatar'];
        }
        else
        {
            return 'img/avatar.png';
        }
    }
}
