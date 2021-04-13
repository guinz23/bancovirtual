<?php

namespace TrackYourMoney\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TrackYourMoney\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cuentas = DB::table('cuentas AS t1')
                        ->select('t1.id', 't1.user_id', 't2.id AS users_coins_id', 't3.description AS moneda', 't1.nombre', 't1.descripcion', 't1.saldo', 't1.created_at', 't1.updated_at')
                        ->join('users_coins AS t2', 't1.monedas_id', '=', 't2.id')
                        ->join('coins AS t3', 't2.coin_id', '=', 't3.id')
                        ->where('t1.user_id', '=', auth()->user()->id)->get();
        //Todas las monedas que le pertenecen a un Usuario (Cuentas)
        $monedas = DB::table('users_coins AS t1')
                        ->select('t1.id', 't2.symbol', 't2.name')
                        ->join('coins AS t2', 't1.coin_id', '=', 't2.id')
                        ->where('t1.user_id', '=', auth()->user()->id)
                        ->get();
        return view('home', [
            'cuentas' => $cuentas,
            'monedas' => $monedas
        ]);
    }
}