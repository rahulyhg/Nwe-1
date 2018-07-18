<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cviebrock\EloquentSluggable\Sluggable;

class Employer extends Authenticatable
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
            'company_slug' => [
                'source' => 'company_name'
            ]
        ];
    }

    public function rows()
    {
        return $this->hasMany('App\CvRow', 'employer_id');
    }

    public function getAvatarAttribute($value)
    {
        if(!empty($value)){
            return config('app.api_url').$value;
        }
        return $value;
    }

    public function isAdmin()
    {
        return ($this->role == 1) ? true : false; // this looks for an admin column in your users table
    }

    public function delete()
    {
        $this->rows()->delete();
        return parent::delete();
    }
}
