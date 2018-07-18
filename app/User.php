<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cviebrock\EloquentSluggable\Sluggable;

class User extends Authenticatable
{
    use Notifiable;
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','hash_forgot_password'
    ];

    public function routeNotificationForFirebase()
    {
        return $this->device_tokens;
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


    public function getAvatarAttribute($value)
    {
        if(!empty($value)){
            return config('app.api_url').$value;
        }
        return $value;
    }

    public function workform(){
        return $this->belongsTo('App\WorkForm', 'work_form');
    }
    public function worktype(){
        return $this->belongsTo('App\WorkType', 'work_type');
    }
    

}
