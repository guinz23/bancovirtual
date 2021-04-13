<?php

namespace TrackYourMoney;

use Illuminate\Database\Eloquent\Model;

class Cuentas extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = ['user_id', 'monedas_id', 'nombre', 'descripcion', 'saldo'];
}
