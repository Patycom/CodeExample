<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use App\Notifications\VerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'remember_token',
        'firstname',
        'lastname',
        'birthday'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    public function sendEmailVerificationNotification()
//    {
//        $this->notify(new VerifyEmailNotification());
//    }


    public function news(){

        return $this->hasMany('App\News');
    }

    public function comments(){

        return $this->hasMany('App\Comment');
    }

    public function roles(){

        return $this->belongsToMany('App\Role', 'user_role');
    }

    public function status(){

        return $this->belongsToMany('App\Status', 'status_user');
    }


    public function accessPermision ($check_permission, $require = FALSE){

        if (is_array($check_permission)){

            foreach ($check_permission as $permissionName) {

                $accessPermision = $this->accessPermision($permissionName);

                if($accessPermision && !$require){
                    return TRUE;

                } elseif (!$accessPermision && $require) {
                    return FALSE;
                }
            }

            return $require;

        } else {
            foreach ($this->roles as $role) {
                foreach ($role->permissions as $permission) {
                    if (Str::is($check_permission,$permission->name) ) {

                        return TRUE;
                    }
                }
            }
        }
        return FALSE;
    }//accessPermision


    public function hasRole($name, $require = FALSE){

        if (is_array($name)) {

            foreach ($name as $roleName) {

                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$require) {
                    return TRUE;
                } elseif (!$hasRole && $require){
                    return FALSE;
                }
            }
            return $require;
        } else {

            foreach ($this->roles as  $role) {

                if ($name == $role->name){
                    return TRUE;

                }
            }
        }
        return FALSE;
    }//hasRole


    public function hasStatus($name, $require = FALSE){

        if (is_array($name)) {

            foreach ($name as $statusName) {

                $hasStatus = $this->hasStatus($statusName);

                if ($hasStatus && !$require) {
                    return TRUE;
                } elseif (!$hasStatus && $require){
                    return FALSE;
                }
            }
            return $require;

        } else {

            foreach ($this->status as  $stat) {

                if ($name == $stat->name){
                    return TRUE;

                }
            }
        }
        return FALSE;
    }//hasStatus


    public function saveStatus($inputStatus){

        if (!empty($inputStatus)){
            $result = $this->status()->sync($inputStatus);

        } else {
            $result = $this->status()->detach();
        }

        return $result;
    }//savePermissions
}



