<?php

namespace TrackYourMoney;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use TrackYourMoney\SocialProfile;

class User extends Authenticatable
{
    use Notifiable;
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

    //Avatar
    public function user_avatar()
    {
        $social_profile = $this->socialProfile->first();
        
        if ($social_profile) {
            return $social_profile->social_avatar;
        }else{
            return 'https://picsum.photos/300/300';
        }
    }

    //Relación uno a muchos (socialprofile)
    public function socialProfile()
    {
        return $this->hasMany(SocialProfile::class);
    }

    //Relación uno a muchos (cuentas)
    public function cuentas()
    {
        return $this->hasMany(Cuentas::class);
    }

    //Relación uno a muchos (categorias)
    public function categorias()
    {
        return $this->hasMany(Categorias::class);
    }
    
    //Relación uno a muchos (monedas del usuario)
    public function usersCoins()
    {
        return $this->hasMany(usersCoins::class);
    }
}
