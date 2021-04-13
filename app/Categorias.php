<?php

namespace TrackYourMoney;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable=[
       'user_id','categoria_id','tipo','descripcion','presupuesto','rebajo'
    ];
}