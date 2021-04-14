<?php

namespace TrackYourMoney;

use Illuminate\Database\Eloquent\Model;

class usersCoins extends Model
{
    //Relación uno a uno con tabla monedas
    public function Coin()
    {
        return $this->belongsTo(Coins::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = ['rate', 'user_id', 'coin_id', 'local'];
}

