<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Helpers\Avatars;

class User extends Authenticatable {
    protected $table = 'tools_logins';

    protected $dates = ['date_orig', 'date_update'];

    protected $avatar;

    public $timestamps = false;

    /* Timestamps workaround */
    const CREATED_AT = 'date_orig';
    const UPDATED_AT = 'date_update';

    use Notifiable;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname',
        'lname',
        'pwd',
        'email',
        'role',
        'avatar',
        'date_orig',
        'date_update'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pwd',
        'remember_token',
    ];

    function getFullNameAttribute() {
        return $this->user_fname . ' ' . $this->user_lname;
    }

    public function getAuthPassword() {
        return $this->pwd;
    }

    /*public function roles() {
        return $this->belongsToMany('App\Models\Role')
                    ->withTimestamps();
    }

    public function hasRole($name) {
        foreach ($this->roles as $role) {
            if ($role->name == $name) {
                return true;
            }
        }
        return false;
    }

    public function assignRole($role) {
        return $this->roles()
                    ->attach($role);
    }

    public function removeRole($role) {
        return $this->roles()
                    ->detach($role);
    }

    public function social() {
        return $this->hasMany('App\Models\Social');
    }

    public function homeUrl() {
        if ($this->hasRole('user')) {
            $url = route('user.home');
        } else {
            $url = route('admin.home');
        }
        return $url;
    }*/

}
