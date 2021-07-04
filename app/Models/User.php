<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static $genders = ['M' => 'Male', 'F' =>  'Female'];

    
    public function getProfileimageAttribute()
    {
        if($this->image  && file_exists(public_path('/images/'.$this->image))){
            $profile = '/images/'.$this->image;
        }else{
            $profile = 'https://openarmsopenminds.com/wp-content/uploads/2019/08/dummy-profile-pic.png';
        }
        return $profile;
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_friends', 'friend_id', 'user_id')->withPivot('status')->withTimestamps();
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_friends', 'user_id', 'friend_id')->withPivot('status')->withTimestamps();
    }

}
