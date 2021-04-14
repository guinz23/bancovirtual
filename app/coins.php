<?php

namespace TrackYourMoney;

use Illuminate\Database\Eloquent\Model;

class coins extends Model
{
     //Relación uno a muchos con las monedas
     public function Coins()
     {
         return $this->hasMany(Coins::class);
     }
     protected $fillable = ['name', 'simbolo', 'nombre', 'description'];
}
