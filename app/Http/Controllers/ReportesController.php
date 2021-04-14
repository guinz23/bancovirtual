<?php

namespace TrackYourMoney\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\Command\WhereamiCommand;
use TrackYourMoney\Categorias;

class ReportesController extends Controller
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

    public function index()
    {
        return view('reportes.dashboard');
    }

    public function querys(Request $request)
    {
        $moneda_local = DB::table('users_coins AS t1')
            ->select('t1.id', 't2.description', 't1.rate')
            ->join('coins AS t2', 't1.coin_id', '=', 't2.id')
            ->where('t1.local', '=', '1')
            ->where('t1.user_id', '=', auth()->user()->id)->get();

        $monto_ingreso = 0;
        $monto_gasto = 0;

        if (intval($request->input('tipodeconsulta')) == 1) {


            $ingresos = DB::table('transactions AS t1')
                ->select('t1.monto', 't3.rate', 't3.coin_id')
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->whereBetween(DB::raw('CAST(t1.created_at AS date)'), [$request->input('fecha1'), $request->input('fecha2')])
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Ingreso')
                ->get();

            foreach ($ingresos as $ingreso) {
                foreach ($moneda_local as $local) {
                    if ($ingreso->coin_id == $local->id) {
                        $monto_ingreso += $ingreso->monto;
                    } else {
                        $conversion = $ingreso->monto * $ingreso->rate;
                        $monto_ingreso += $conversion;
                    }
                }
            }

            $gastos = DB::table('transactions AS t1')
                ->select('t1.monto', 't3.rate', 't3.coin_id')
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->whereBetween(DB::raw('CAST(t1.created_at AS date)'), [$request->input('fecha1'), $request->input('fecha2')])
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Gasto')
                ->get();

            foreach ($gastos as $gasto) {
                foreach ($moneda_local as $local) {
                    if ($gasto->coin_id == $local->id) {
                        $monto_gasto += $gasto->monto;
                    } else {
                        $conversion = $gasto->monto * $gasto->rate;
                        $monto_gasto += $conversion;
                    }
                }
            }
        }
        if (intval($request->input('tipodeconsulta')) == 2) {
            $ingresos = DB::table('transactions AS t1')
                ->select('t1.monto', 't3.rate', 't3.coin_id')
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 1 month)'))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Ingreso')
                ->get();

            foreach ($ingresos as $ingreso) {
                foreach ($moneda_local as $local) {
                    if ($ingreso->coin_id == $local->id) {
                        $monto_ingreso += $ingreso->monto;
                    } else {
                        $conversion = $ingreso->monto * $ingreso->rate;
                        $monto_ingreso += $conversion;
                    }
                }
            }

            $gastos = DB::table('transactions AS t1')
                ->select('t1.monto', 't3.rate', 't3.coin_id')
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 1 month)'))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Gasto')
                ->get();

            foreach ($gastos as $gasto) {
                foreach ($moneda_local as $local) {
                    if ($gasto->coin_id == $local->id) {
                        $monto_gasto += $gasto->monto;
                    } else {
                        $conversion = $gasto->monto * $gasto->rate;
                        $monto_gasto += $conversion;
                    }
                }
            }
        }
        if (intval($request->input('tipodeconsulta')) == 3) {
            $ingresos = DB::table('transactions AS t1')
                ->select('t1.monto', 't3.rate', 't3.coin_id')
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 12 month)'))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Ingreso')
                ->get();

            foreach ($ingresos as $ingreso) {
                foreach ($moneda_local as $local) {
                    if ($ingreso->coin_id == $local->id) {
                        $monto_ingreso += $ingreso->monto;
                    } else {
                        $conversion = $ingreso->monto * $ingreso->rate;
                        $monto_ingreso += $conversion;
                    }
                }
            }

            $gastos = DB::table('transactions AS t1')
                ->select('t1.monto', 't3.rate', 't3.coin_id')
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 12 month)'))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Gasto')
                ->get();

            foreach ($gastos as $gasto) {
                foreach ($moneda_local as $local) {
                    if ($gasto->coin_id == $local->id) {
                        $monto_gasto += $gasto->monto;
                    } else {
                        $conversion = $gasto->monto * $gasto->rate;
                        $monto_gasto += $conversion;
                    }
                }
            }
        }
        if (intval($request->input('tipodeconsulta')) == 4) {
            $ingresos = DB::table('transactions AS t1')
                ->select('t1.monto', 't3.rate', 't3.coin_id')
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->whereMonth('t1.created_at', '=', intval($request->input('mes')))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Ingreso')
                ->get();

            foreach ($ingresos as $ingreso) {
                foreach ($moneda_local as $local) {
                    if ($ingreso->coin_id == $local->id) {
                        $monto_ingreso += $ingreso->monto;
                    } else {
                        $conversion = $ingreso->monto * $ingreso->rate;
                        $monto_ingreso += $conversion;
                    }
                }
            }

            $gastos = DB::table('transactions AS t1')
                ->select('t1.monto', 't3.rate', 't3.coin_id')
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->whereMonth('t1.created_at', '=', intval($request->input('mes')))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Gasto')
                ->get();

            foreach ($gastos as $gasto) {
                foreach ($moneda_local as $local) {
                    if ($gasto->coin_id == $local->id) {
                        $monto_gasto += $gasto->monto;
                    } else {
                        $conversion = $gasto->monto * $gasto->rate;
                        $monto_gasto += $conversion;
                    }
                }
            }
        }
        if (intval($request->input('tipodeconsulta')) == 5) {
            $ingresos = DB::table('transactions AS t1')
                ->select('t1.monto', 't3.rate', 't3.coin_id')
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->whereYear('t1.created_at', '=', intval($request->input('anno')))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Ingreso')
                ->get();

            foreach ($ingresos as $ingreso) {
                foreach ($moneda_local as $local) {
                    if ($ingreso->coin_id == $local->id) {
                        $monto_ingreso += $ingreso->monto;
                    } else {
                        $conversion = $ingreso->monto * $ingreso->rate;
                        $monto_ingreso += $conversion;
                    }
                }
            }

            $gastos = DB::table('transactions AS t1')
                ->select('t1.monto', 't3.rate', 't3.coin_id')
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->whereYear('t1.created_at', '=', intval($request->input('anno')))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Gasto')
                ->get();

            foreach ($gastos as $gasto) {
                foreach ($moneda_local as $local) {
                    if ($gasto->coin_id == $local->id) {
                        $monto_gasto += $gasto->monto;
                    } else {
                        $conversion = $gasto->monto * $gasto->rate;
                        $monto_gasto += $conversion;
                    }
                }
            }
        }

        return response()->json(["ingresos" => $monto_ingreso, "gastos" => $monto_gasto]);
    }

    public function categ_padres(Request $request)
    {
        $moneda_loc = 0;

        $moneda_local = DB::table('users_coins AS t1')
            ->select('t1.id', 't2.description', 't1.rate')
            ->join('coins AS t2', 't1.coin_id', '=', 't2.id')
            ->where('t1.local', '=', '1')
            ->where('t1.user_id', '=', auth()->user()->id)->get();

        foreach ($moneda_local as $moneda) {
            $moneda_loc = $moneda->id;
        }



        if (intval($request->input('tipodeconsulta')) == 1) {

            $agrup_categ_padre_ingresos = DB::table('transactions AS t1')
                ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                ->whereBetween(DB::raw('CAST(t1.created_at AS date)'), [$request->input('fecha1'), $request->input('fecha2')])
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Ingreso')
                ->whereNull('t4.categoria_id')
                ->groupBy('t1.categoria_id', 't4.descripcion')
                ->get();


            $agrup_categ_padre_gastos = DB::table('transactions AS t1')
                ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                ->whereBetween(DB::raw('CAST(t1.created_at AS date)'), [$request->input('fecha1'), $request->input('fecha2')])
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Gasto')
                ->whereNull('t4.categoria_id')
                ->groupBy('t1.categoria_id', 't4.descripcion')
                ->get();
        }
        if (intval($request->input('tipodeconsulta')) == 2) {

            $agrup_categ_padre_ingresos = DB::table('transactions AS t1')
                ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 1 month)'))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Ingreso')
                ->whereNull('t4.categoria_id')
                ->groupBy('t1.categoria_id', 't4.descripcion')
                ->get();


            $agrup_categ_padre_gastos = DB::table('transactions AS t1')
                ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 1 month)'))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Gasto')
                ->whereNull('t4.categoria_id')
                ->groupBy('t1.categoria_id', 't4.descripcion')
                ->get();
        }
        if (intval($request->input('tipodeconsulta')) == 3) {

            $agrup_categ_padre_ingresos = DB::table('transactions AS t1')
                ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 12 month)'))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Ingreso')
                ->whereNull('t4.categoria_id')
                ->groupBy('t1.categoria_id', 't4.descripcion')
                ->get();

            $agrup_categ_padre_gastos = DB::table('transactions AS t1')
                ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 12 month)'))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Gasto')
                ->whereNull('t4.categoria_id')
                ->groupBy('t1.categoria_id', 't4.descripcion')
                ->get();
        }
        if (intval($request->input('tipodeconsulta')) == 4) {

            $agrup_categ_padre_ingresos = DB::table('transactions AS t1')
                ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                ->whereMonth('t1.created_at', '=', intval($request->input('mes')))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Ingreso')
                ->whereNull('t4.categoria_id')
                ->groupBy('t1.categoria_id', 't4.descripcion')
                ->get();

            $agrup_categ_padre_gastos = DB::table('transactions AS t1')
                ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                ->whereMonth('t1.created_at', '=', intval($request->input('mes')))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Gasto')
                ->whereNull('t4.categoria_id')
                ->groupBy('t1.categoria_id', 't4.descripcion')
                ->get();
        }
        if (intval($request->input('tipodeconsulta')) == 5) {

            $agrup_categ_padre_ingresos = DB::table('transactions AS t1')
                ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                ->whereYear('t1.created_at', '=', intval($request->input('anno')))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Ingreso')
                ->whereNull('t4.categoria_id')
                ->groupBy('t1.categoria_id', 't4.descripcion')
                ->get();

            $agrup_categ_padre_gastos = DB::table('transactions AS t1')
                ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                ->whereYear('t1.created_at', '=', intval($request->input('anno')))
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t3.user_id', '=', auth()->user()->id)
                ->where('t1.tipo', '=', 'Gasto')
                ->whereNull('t4.categoria_id')
                ->groupBy('t1.categoria_id', 't4.descripcion')
                ->get();
        }

        return response()->json(["cat_padre_ingresos" => $agrup_categ_padre_ingresos, "cat_padre_gastos" => $agrup_categ_padre_gastos]);
    }

    public function categ_hijos(Request $request)
    {
        $moneda_loc = 0;

        $moneda_local = DB::table('users_coins AS t1')
            ->select('t1.id', 't2.description', 't1.rate')
            ->join('coins AS t2', 't1.coin_id', '=', 't2.id')
            ->where('t1.local', '=', '1')
            ->where('t1.user_id', '=', auth()->user()->id)->get();

        foreach ($moneda_local as $moneda) {
            $moneda_loc = $moneda->id;
        }

        if (intval($request->input('tipodeconsulta')) == 1) {

            $categoria = DB::table('categorias AS t1')
                ->select('t1.id')
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t1.descripcion', '=', $request->input('categoria'))
                ->get();

            foreach ($categoria as $categ) {


                $agrup_categ_ingresos = DB::table('transactions AS t1')
                    ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->whereBetween(DB::raw('CAST(t1.created_at AS date)'), [$request->input('fecha1'), $request->input('fecha2')])
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t3.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Ingreso')
                    ->where('t4.categoria_id', '=', $categ->id)
                    ->groupBy('t1.categoria_id', 't4.descripcion')
                    ->get();


                $agrup_categ_gastos = DB::table('transactions AS t1')
                    ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->whereBetween(DB::raw('CAST(t1.created_at AS date)'), [$request->input('fecha1'), $request->input('fecha2')])
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t3.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Gasto')
                    ->where('t4.categoria_id', '=', $categ->id)
                    ->groupBy('t1.categoria_id', 't4.descripcion')
                    ->get();
            }
        }
        if (intval($request->input('tipodeconsulta')) == 2) {

            $categoria = DB::table('categorias AS t1')
                ->select('t1.id')
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t1.descripcion', '=', $request->input('categoria'))
                ->get();

            foreach ($categoria as $categ) {

                $agrup_categ_ingresos = DB::table('transactions AS t1')
                    ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 1 month)'))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t3.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Ingreso')
                    ->where('t4.categoria_id', '=', $categ->id)
                    ->groupBy('t1.categoria_id', 't4.descripcion')
                    ->get();


                $agrup_categ_gastos = DB::table('transactions AS t1')
                    ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 1 month)'))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t3.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Gasto')
                    ->where('t4.categoria_id', '=', $categ->id)
                    ->groupBy('t1.categoria_id', 't4.descripcion')
                    ->get();
            }
        }
        if (intval($request->input('tipodeconsulta')) == 3) {

            $categoria = DB::table('categorias AS t1')
                ->select('t1.id')
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t1.descripcion', '=', $request->input('categoria'))
                ->get();

            foreach ($categoria as $categ) {

                $agrup_categ_ingresos = DB::table('transactions AS t1')
                    ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 12 month)'))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t3.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Ingreso')
                    ->where('t4.categoria_id', '=', $categ->id)
                    ->groupBy('t1.categoria_id', 't4.descripcion')
                    ->get();

                $agrup_categ_gastos = DB::table('transactions AS t1')
                    ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 12 month)'))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t3.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Gasto')
                    ->where('t4.categoria_id', '=', $categ->id)
                    ->groupBy('t1.categoria_id', 't4.descripcion')
                    ->get();
            }
        }
        if (intval($request->input('tipodeconsulta')) == 4) {

            $categoria = DB::table('categorias AS t1')
                ->select('t1.id')
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t1.descripcion', '=', $request->input('categoria'))
                ->get();

            foreach ($categoria as $categ) {

                $agrup_categ_ingresos = DB::table('transactions AS t1')
                    ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->whereMonth('t1.created_at', '=', intval($request->input('mes')))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t3.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Ingreso')
                    ->where('t4.categoria_id', '=', $categ->id)
                    ->groupBy('t1.categoria_id', 't4.descripcion')
                    ->get();

                $agrup_categ_gastos = DB::table('transactions AS t1')
                    ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->whereMonth('t1.created_at', '=', intval($request->input('mes')))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t3.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Gasto')
                    ->where('t4.categoria_id', '=', $categ->id)
                    ->groupBy('t1.categoria_id', 't4.descripcion')
                    ->get();
            }
        }
        if (intval($request->input('tipodeconsulta')) == 5) {

            $categoria = DB::table('categorias AS t1')
                ->select('t1.id')
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t1.descripcion', '=', $request->input('categoria'))
                ->get();

            foreach ($categoria as $categ) {

                $agrup_categ_ingresos = DB::table('transactions AS t1')
                    ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->whereYear('t1.created_at', '=', intval($request->input('anno')))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t3.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Ingreso')
                    ->where('t4.categoria_id', '=', $categ->id)
                    ->groupBy('t1.categoria_id', 't4.descripcion')
                    ->get();

                $agrup_categ_gastos = DB::table('transactions AS t1')
                    ->select('t4.descripcion', DB::raw('SUM(if(t2.monedas_id = ' . $moneda_loc . ', t1.monto, t1.monto * t3.rate)) AS suma'))
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('users_coins AS t3', 't2.monedas_id', '=', 't3.coin_id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->whereYear('t1.created_at', '=', intval($request->input('anno')))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t3.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Gasto')
                    ->where('t4.categoria_id', '=', $categ->id)
                    ->groupBy('t1.categoria_id', 't4.descripcion')
                    ->get();
            }
        }

        return response()->json(["cat_ingresos" => $agrup_categ_ingresos, "cat_gastos" => $agrup_categ_gastos]);
    }

    public function categ_hijos_transacc(Request $request)
    {
        if (intval($request->input('tipodeconsulta')) == 1) {

            $categoria = DB::table('categorias AS t1')
                ->select('t1.id')
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t1.descripcion', '=', $request->input('categoria'))
                ->get();

            foreach ($categoria as $categ) {


                $agrup_categ_ingresos = DB::table('transactions AS t1')
                    ->select('t2.nombre', 't4.descripcion', 't1.tipo', 't1.monto', 't1.detalle')
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->whereBetween(DB::raw('CAST(t1.created_at AS date)'), [$request->input('fecha1'), $request->input('fecha2')])
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Ingreso')
                    ->where('t1.categoria_id', '=', $categ->id)
                    ->get();


                $agrup_categ_gastos = DB::table('transactions AS t1')
                    ->select('t2.nombre', 't4.descripcion', 't1.tipo', 't1.monto', 't1.detalle')
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->whereBetween(DB::raw('CAST(t1.created_at AS date)'), [$request->input('fecha1'), $request->input('fecha2')])
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Gasto')
                    ->where('t1.categoria_id', '=', $categ->id)
                    ->get();
            }
        }
        if (intval($request->input('tipodeconsulta')) == 2) {

            $categoria = DB::table('categorias AS t1')
                ->select('t1.id')
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t1.descripcion', '=', $request->input('categoria'))
                ->get();

            foreach ($categoria as $categ) {

                $agrup_categ_ingresos = DB::table('transactions AS t1')
                    ->select('t2.nombre', 't4.descripcion', 't1.tipo', 't1.monto', 't1.detalle')
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 1 month)'))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Ingreso')
                    ->where('t1.categoria_id', '=', $categ->id)
                    ->get();


                $agrup_categ_gastos = DB::table('transactions AS t1')
                    ->select('t2.nombre', 't4.descripcion', 't1.tipo', 't1.monto', 't1.detalle')
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 1 month)'))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Gasto')
                    ->where('t1.categoria_id', '=', $categ->id)
                    ->get();
            }
        }
        if (intval($request->input('tipodeconsulta')) == 3) {

            $categoria = DB::table('categorias AS t1')
                ->select('t1.id')
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t1.descripcion', '=', $request->input('categoria'))
                ->get();

            foreach ($categoria as $categ) {

                $agrup_categ_ingresos = DB::table('transactions AS t1')
                    ->select('t2.nombre', 't4.descripcion', 't1.tipo', 't1.monto', 't1.detalle')
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 12 month)'))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Ingreso')
                    ->where('t1.categoria_id', '=', $categ->id)
                    ->get();

                $agrup_categ_gastos = DB::table('transactions AS t1')
                    ->select('t2.nombre', 't4.descripcion', 't1.tipo', 't1.monto', 't1.detalle')
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->where(DB::raw('t1.created_at'), '>=', DB::raw('date_sub(curdate(), interval 12 month)'))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Gasto')
                    ->where('t1.categoria_id', '=', $categ->id)
                    ->get();
            }
        }
        if (intval($request->input('tipodeconsulta')) == 4) {

            $categoria = DB::table('categorias AS t1')
                ->select('t1.id')
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t1.descripcion', '=', $request->input('categoria'))
                ->get();

            foreach ($categoria as $categ) {

                $agrup_categ_ingresos = DB::table('transactions AS t1')
                    ->select('t2.nombre', 't4.descripcion', 't1.tipo', 't1.monto', 't1.detalle')
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->whereMonth('t1.created_at', '=', intval($request->input('mes')))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Ingreso')
                    ->where('t1.categoria_id', '=', $categ->id)
                    ->get();

                $agrup_categ_gastos = DB::table('transactions AS t1')
                    ->select('t2.nombre', 't4.descripcion', 't1.tipo', 't1.monto', 't1.detalle')
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->whereMonth('t1.created_at', '=', intval($request->input('mes')))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Gasto')
                    ->where('t1.categoria_id', '=', $categ->id)
                    ->get();
            }
        }
        if (intval($request->input('tipodeconsulta')) == 5) {

            $categoria = DB::table('categorias AS t1')
                ->select('t1.id')
                ->where('t1.user_id', '=', auth()->user()->id)
                ->where('t1.descripcion', '=', $request->input('categoria'))
                ->get();

            foreach ($categoria as $categ) {

                $agrup_categ_ingresos = DB::table('transactions AS t1')
                    ->select('t2.nombre', 't4.descripcion', 't1.tipo', 't1.monto', 't1.detalle')
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->whereYear('t1.created_at', '=', intval($request->input('anno')))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Ingreso')
                    ->where('t1.categoria_id', '=', $categ->id)
                    ->get();

                $agrup_categ_gastos = DB::table('transactions AS t1')
                    ->select('t2.nombre', 't4.descripcion', 't1.tipo', 't1.monto', 't1.detalle')
                    ->join('cuentas AS t2', 't1.cuenta_id', '=', 't2.id')
                    ->join('categorias AS t4', 't1.categoria_id', '=', 't4.id')
                    ->whereYear('t1.created_at', '=', intval($request->input('anno')))
                    ->where('t1.user_id', '=', auth()->user()->id)
                    ->where('t1.tipo', '=', 'Gasto')
                    ->where('t1.categoria_id', '=', $categ->id)
                    ->get();
            }
        }

        return response()->json(["cat_ingresos_transacc" => $agrup_categ_ingresos, "cat_gastos_transacc" => $agrup_categ_gastos]);
    }
}
