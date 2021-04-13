<?php

namespace TrackYourMoney;

use Illuminate\Database\Eloquent\Model;

use TrackYourMoney\User;

class SocialProfile extends Model
{
    protected $fillable = ['user_id', 'social_id', 'social_name', 'social_avatar'];
    //Relación uno a muchos (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}