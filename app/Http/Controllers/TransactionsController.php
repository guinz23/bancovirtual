<?php

namespace TrackYourMoney\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TrackYourMoney\Categorias;
use TrackYourMoney\Cuentas;
use TrackYourMoney\Transactions;
use TrackYourMoney\User;

class TransactionsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Carga la información de las transacciones
     */
    public function index($id)
    {
        $cuentas = DB::table('cuentas AS t1')
            ->select('t1.id', 't1.user_id', 't2.id AS users_coins_id', 't3.description AS moneda', 't1.nombre', 't1.descripcion', 't1.saldo', 't1.created_at', 't1.updated_at')
            ->join('users_coins AS t2', 't1.monedas_id', '=', 't2.id')
            ->join('coins AS t3', 't2.coin_id', '=', 't3.id')
            ->where('t1.id', '=', $id)->get();

        $categorias = User::find(auth()->user()->id)->categorias;

        $transacciones = DB::table('transactions AS t1')
            ->select('t1.id', 't1.cuenta_id', 't3.nombre', 't2.descripcion', 't1.categoria_id', 't1.tipo', 't1.monto', 't1.detalle', 't1.traslado', 't1.created_at', 't1.updated_at')
            ->join('categorias AS t2', 't1.categoria_id', '=', 't2.id')
            ->join('cuentas AS t3', 't1.cuenta_id', '=', 't3.id')
            ->where('t1.cuenta_id', '=', $id)->get();

        $progress_bar = DB::table('categorias AS t1')
            ->select('t1.id', 't1.descripcion', DB::raw('(t1.rebajo/t1.presupuesto)*100 AS resultado'))
            ->where('t1.user_id', '=', auth()->user()->id)->get();

        return view('transactions.dashboard', ['cuentas' => $cuentas, 'categorias' => $categorias, 'transacciones' => $transacciones, 'progress_bar' => $progress_bar]);
    }

    public function todasMisCuentas()
    {
        $todas_mis_cuentas = DB::table('cuentas AS t1')
            ->select('t1.id', 't1.user_id', 't2.id AS users_coins_id', 't3.description AS moneda', 't1.nombre', 't1.descripcion', 't1.saldo', 't1.created_at', 't1.updated_at')
            ->join('users_coins AS t2', 't1.monedas_id', '=', 't2.id')
            ->join('coins AS t3', 't2.coin_id', '=', 't3.id')
            ->where('t1.user_id', '=', auth()->user()->id)->get();
        return response()->json(["destinos" => $todas_mis_cuentas]);
    }

    public function store()
    {
        $moneda_local = DB::table('users_coins AS t1')
            ->select('t1.id', 't2.description', 't1.rate')
            ->join('coins AS t2', 't1.coin_id', '=', 't2.id')
            ->where('t1.local', '=', '1')
            ->where('t1.user_id', '=', auth()->user()->id)->get();

        $moneda_cuenta_origen = DB::table('cuentas')
            ->select('id', 'monedas_id', 'saldo')
            ->where('id', '=', request('cuenta1'))->get();

        $moneda_cuenta_destino = DB::table('cuentas')
            ->select('id', 'monedas_id', 'saldo')
            ->where('id', '=', request('cuentas'))->get();

        $moneda_secundaria_tasa = DB::table('cuentas AS t1')
            ->select('t2.rate')
            ->join('users_coins AS t2', 't1.monedas_id', '=', 't2.id')
            ->where('t1.id', '=', request('cuentas'))->get();

        if (request('tipo') == 'Traslado') {
            foreach ($moneda_cuenta_origen as $origen) {
                foreach ($moneda_cuenta_destino as $destino) {
                    if ($origen->monedas_id == $destino->monedas_id) {
                        
                        $categoria = Categorias::find(request('selectCategoria'));
                        if ($origen->saldo == 0) {
                            return redirect('/transacciones/' . request('cuenta1'))->with('info', 'Fondos insuficientes');
                        }else if ($categoria->rebajo == 0) {
                            return redirect('/transacciones/' . request('cuenta1'))->with('info', 'La categoría seleccionada supera el presupuesto');
                        }else if (request('monto') > $origen->saldo) {
                            return redirect('/transacciones/' . request('cuenta1'))->with('info', 'El monto supera el saldo de la cuenta');
                        } else {

                            $id_transaccion = 0;

                            $trasaccion = new Transactions();
                            $trasaccion->user_id = auth()->user()->id;
                            $trasaccion->cuenta_id = request('cuentas');
                            $trasaccion->categoria_id = request('selectCategoria');
                            $trasaccion->tipo = 'Ingreso';
                            $trasaccion->monto = request('monto');
                            $trasaccion->detalle = request('detalle');

                            $cuenta = Cuentas::find($destino->id);
                            $cuenta->saldo = $destino->saldo + request('monto');
                            $cuenta->save();

                            $trasaccion->save();

                            $id_transaccion = $trasaccion->id;

                            $trasaccion = new Transactions();
                            $trasaccion->user_id = auth()->user()->id;
                            $trasaccion->cuenta_id = request('cuenta1');
                            $trasaccion->categoria_id = request('selectCategoria');
                            $trasaccion->tipo = 'Gasto';
                            $trasaccion->monto = request('monto');
                            $trasaccion->detalle = request('detalle');
                            $trasaccion->traslado = $id_transaccion;
                            $trasaccion->destino = request('cuentas');

                            $cuenta = Cuentas::find($origen->id);
                            $cuenta->saldo = $origen->saldo - request('monto');
                            $cuenta->save();

                            $categoria = Categorias::find(request('selectCategoria'));
                            $categoria->rebajo = $categoria->rebajo - request('monto');
                            $categoria->save();

                            $trasaccion->save();


                            return redirect('/transacciones/' . request('cuenta1'))->with('success', 'Transacción creada con éxito');
                        }
                    } else {
                        foreach ($moneda_local as $local) {
                            foreach ($moneda_cuenta_origen as $origen) {
                                if ($local->id == $origen->monedas_id) {
                                    foreach ($moneda_secundaria_tasa as $tasa) {
                                        $categoria = Categorias::find(request('selectCategoria'));
                                        if ($origen->saldo == 0) {
                                            return redirect('/transacciones/' . request('cuenta1'))->with('info', 'Fondos insuficientes');
                                        }else if ($categoria->rebajo == 0) {
                                            return redirect('/transacciones/' . request('cuenta1'))->with('info', 'La categoría seleccionada supera el presupuesto'); 
                                        }else if (request('monto') > $origen->saldo) {
                                            return redirect('/transacciones/' . request('cuenta1'))->with('info', 'El monto supera el saldo de la cuenta');
                                        } else {
                                            $nuevo_monto = request('monto') / $tasa->rate;
                                            $id_transaccion = 0;

                                            $trasaccion = new Transactions();
                                            $trasaccion->user_id = auth()->user()->id;
                                            $trasaccion->cuenta_id = request('cuentas');
                                            $trasaccion->categoria_id = request('selectCategoria');
                                            $trasaccion->tipo = 'Ingreso';
                                            $trasaccion->monto = $nuevo_monto;
                                            $trasaccion->detalle = request('detalle');

                                            $cuenta = Cuentas::find($destino->id);
                                            $cuenta->saldo = $destino->saldo + $nuevo_monto;
                                            $cuenta->save();

                                            $trasaccion->save();
                                            $id_transaccion = $trasaccion->id;

                                            $trasaccion = new Transactions();
                                            $trasaccion->user_id = auth()->user()->id;
                                            $trasaccion->cuenta_id = request('cuenta1');
                                            $trasaccion->categoria_id = request('selectCategoria');
                                            $trasaccion->tipo = 'Gasto';
                                            $trasaccion->monto = request('monto');
                                            $trasaccion->detalle = request('detalle');
                                            $trasaccion->traslado = $id_transaccion;
                                            $trasaccion->destino = request('cuentas');

                                            $cuenta = Cuentas::find($origen->id);
                                            $cuenta->saldo = $origen->saldo - request('monto');
                                            $cuenta->save();

                                            $categoria = Categorias::find(request('selectCategoria'));
                                            $categoria->rebajo = $categoria->rebajo - request('monto');
                                            $categoria->save();

                                            $trasaccion->save();


                                            return redirect('/transacciones/' . request('cuenta1'))->with('success', 'Transacción creada con éxito');
                                        }
                                    }
                                } else {
                                    return redirect('/transacciones/' . request('cuenta1'))->with('info', 'La moneda de la cuenta de origen no es su moneda local, por favor cambie su moneda local');
                                }
                            }
                        }
                    }
                }
            }
        } else {
            if (request('tipo') == 'Gasto') {
                foreach ($moneda_cuenta_origen as $origen) {
                    $categoria = Categorias::find(request('selectCategoria'));
                    if ($origen->saldo == 0) {
                        return redirect('/transacciones/' . request('cuenta1'))->with('info', 'Fondos insuficientes');
                    }else if ($categoria->rebajo == 0) {
                        return redirect('/transacciones/' . request('cuenta1'))->with('info', 'La categoría seleccionada supera el presupuesto'); 
                    } 
                    else if (request('monto') > $origen->saldo) {
                        return redirect('/transacciones/' . request('cuenta1'))->with('info', 'El monto supera el saldo de la cuenta');
                    } else {
                        $trasaccion = new Transactions();
                        $trasaccion->user_id = auth()->user()->id;
                        $trasaccion->cuenta_id = request('cuenta1');
                        $trasaccion->categoria_id = request('selectCategoria');
                        $trasaccion->tipo = request('tipo');
                        $trasaccion->monto = request('monto');
                        $trasaccion->detalle = request('detalle');

                        $cuenta = Cuentas::find($origen->id);
                        $cuenta->saldo = $origen->saldo - request('monto');
                        $cuenta->save();

                        $categoria = Categorias::find(request('selectCategoria'));
                        $categoria->rebajo = $categoria->rebajo - request('monto');
                        $categoria->save();

                        $trasaccion->save();
                        return redirect('/transacciones/' . request('cuenta1'))->with('success', 'Transacción creada con éxito');
                    }
                }
            }
            foreach ($moneda_cuenta_origen as $origen) {
                $trasaccion = new Transactions();
                $trasaccion->user_id = auth()->user()->id;
                $trasaccion->cuenta_id = request('cuenta1');
                $trasaccion->categoria_id = request('selectCategoria');
                $trasaccion->tipo = request('tipo');
                $trasaccion->monto = request('monto');
                $trasaccion->detalle = request('detalle');

                $cuenta = Cuentas::find($origen->id);
                $cuenta->saldo = $origen->saldo + request('monto');
                $cuenta->save();

                $categoria = Categorias::find(request('selectCategoria'));
                $categoria->presupuesto = $categoria->presupuesto + request('monto');
                $categoria->rebajo = $categoria->rebajo + request('monto');
                $categoria->save();

                $trasaccion->save();
                return redirect('/transacciones/' . request('cuenta1'))->with('success', 'Transacción creada con éxito');
            }
        }
    }

    public function edit($id, $cuenta, $cateselecc)
    {
        $cuentas = DB::table('cuentas AS t1')
            ->select('t1.id', 't1.nombre')
            ->where('t1.id', '=', $cuenta)
            ->get();

        $categorias = User::find(auth()->user()->id)->categorias;
        $categoriasele = Categorias::find($cateselecc);
        $transaccion = Transactions::find($id);


        if ($transaccion->traslado) {
            $traslado = DB::table('cuentas AS t1')
                ->select('t1.id', 't1.nombre')
                ->where('t1.id', '=', $transaccion->traslado)
                ->get();
        } else {
            $traslado = '';
        }

        if ($transaccion->destino) {
            $destinos = DB::table('cuentas AS t1')
                ->select('t1.id', 't1.nombre')
                ->where('t1.id', '=', $transaccion->destino)
                ->get();
        } else {
            $destinos = '';
        }


        return view('transactions.update', compact('transaccion', 'cuentas', 'categorias', 'traslado', 'destinos', 'categoriasele'));
    }

    public function update($id)
    {
        $moneda_local = DB::table('users_coins AS t1')
            ->select('t1.id', 't2.description', 't1.rate')
            ->join('coins AS t2', 't1.coin_id', '=', 't2.id')
            ->where('t1.local', '=', '1')
            ->where('t1.user_id', '=', auth()->user()->id)->get();
        $moneda_secundaria_tasa = DB::table('cuentas AS t1')
            ->select('t2.rate')
            ->join('users_coins AS t2', 't1.monedas_id', '=', 't2.id')
            ->where('t1.id', '=', request('cuentas'))->get();

        $transaccion = Transactions::find($id);
        $transaccion->detalle = request('detalle');
        $transaccion->monto = request('monto');
        $transaccion->cuenta_id = request('cuenta1');
        $transaccion->monto = request('monto');
        if ($transaccion->traslado) {
            if (request('tipo') != 'Traslado') {
                $cuentas = DB::table('cuentas AS t1')
                    ->select('t1.saldo')
                    ->where('t1.id', '=', $transaccion->destino)->get();
                $temp = Transactions::find($transaccion->traslado);
                foreach ($cuentas as $cuent) {
                    $cuenta = Cuentas::find($transaccion->destino);
                    $cuenta->saldo = $cuent->saldo - $temp->monto;
                    $cuenta->save();
                }
                $temp->delete();
                $transaccion->traslado = null;
                $transaccion->destino = null;

                if ($transaccion->categoria_id != request('selectCategoria')) {
                    if (request('tipo') == 'Gasto') {
                        $categoria_antigua = Categorias::find($transaccion->categoria_id);
                        $categoria_antigua->rebajo = $categoria_antigua->rebajo + request('monto');
                        $categoria_antigua->save();
                        $transaccion->categoria_id = request('selectCategoria');
                        $categoria_nueva = Categorias::find($transaccion->categoria_id);
                        $categoria_nueva->rebajo = $categoria_nueva->rebajo - request('monto');
                        $categoria_nueva->save();
                    }
                } else {
                    if (request('tipo') == 'Gasto') {
                        $categoria_antigua = Categorias::find($transaccion->categoria_id);
                        $categoria_antigua->rebajo = $categoria_antigua->rebajo + request('monto');
                        $categoria_antigua->save();
                        $transaccion->categoria_id = request('selectCategoria');
                        $categoria_nueva = Categorias::find($transaccion->categoria_id);
                        $categoria_nueva->rebajo = $categoria_nueva->rebajo - request('monto');
                        $categoria_nueva->save();
                    }
                }
                $transaccion->tipo = request('tipo');
            }
            if (request('tipo') == 'Traslado') {
                $cuentas = DB::table('cuentas AS t1')
                    ->select('t1.saldo')
                    ->where('t1.id', '=', $transaccion->destino)->get();
                $temp = Transactions::find($transaccion->traslado);
                if ($transaccion->destino != request('cuentas')) {
                    foreach ($cuentas as $cuent) {
                        $cuenta = Cuentas::find($transaccion->destino);
                        $cuenta->saldo = $cuent->saldo - $temp->monto;
                        $cuenta->save();
                    }

                    $cuenta = Cuentas::find(request('cuentas'));

                    foreach ($moneda_local as $local) {

                        if ($cuenta->monedas_id == $local->id) {
                            $cuenta->saldo = $cuenta->saldo + request('monto');
                        } else {
                            $monto_nuevo = 0;
                            foreach ($moneda_secundaria_tasa as $rate) {
                                $monto_nuevo = request('monto') / $rate->rate;
                                $cuenta->saldo = $cuenta->saldo + $monto_nuevo;
                            }
                        }
                    }

                    $cuenta->save();

                    if ($transaccion->categoria_id != request('selectCategoria')) {
                        $categoria_antigua = Categorias::find($transaccion->categoria_id);
                        $categoria_antigua->rebajo = $categoria_antigua->rebajo + request('monto');
                        $categoria_antigua->save();
                        $transaccion->categoria_id = request('selectCategoria');
                        $categoria_nueva = Categorias::find($transaccion->categoria_id);
                        $categoria_nueva->rebajo = $categoria_nueva->rebajo - request('monto');
                        $categoria_nueva->save();
                    }

                    $nueva_transaccion = new Transactions();
                    $nueva_transaccion->user_id = auth()->user()->id;
                    $nueva_transaccion->cuenta_id = request('cuentas');
                    $nueva_transaccion->categoria_id = request('selectCategoria');
                    $nueva_transaccion->tipo = 'Ingreso';
                    foreach ($moneda_local as $local) {

                        if ($cuenta->monedas_id == $local->id) {
                            $nueva_transaccion->monto = request('monto');
                        } else {
                            $monto_nuevo = 0;
                            foreach ($moneda_secundaria_tasa as $rate) {
                                $monto_nuevo = request('monto') / $rate->rate;
                                $nueva_transaccion->monto = $monto_nuevo;
                            }
                        }
                    }
                    $nueva_transaccion->detalle = $transaccion->detalle;
                    $nueva_transaccion->save();
                    $transaccion->traslado = $nueva_transaccion->id;
                    $transaccion->destino = request('cuentas');
                    $transaccion->tipo = 'Gasto';

                    $temp->delete();
                } else {
                    $transaccion->detalle = request('detalle');
                    $cuentas = DB::table('cuentas AS t1')
                        ->select('t1.saldo')
                        ->where('t1.id', '=', $transaccion->destino)->get();
                    $cuenta = Cuentas::find($transaccion->destino);
                    $temp = Transactions::find($transaccion->traslado);
                    foreach ($cuentas as $cuent) {
                        foreach ($moneda_local as $local) {
                            if ($cuenta->monedas_id == $local->id) {
                                $cuenta->saldo = request('monto');
                            } else {
                                $monto_nuevo = 0;
                                foreach ($moneda_secundaria_tasa as $rate) {
                                    $eliminar_monto = $cuenta->saldo - $temp->monto;
                                    $monto_nuevo = request('monto') / $rate->rate;
                                    $cuenta->saldo = $eliminar_monto + $monto_nuevo;
                                    $temp->detalle = request('detalle');
                                    $temp->monto = $monto_nuevo;
                                }
                            }
                        }
                    }

                    $categoria_antigua = Categorias::find($transaccion->categoria_id);
                    $categoria_antigua->rebajo = $categoria_antigua->rebajo + request('monto');
                    $categoria_antigua->save();
                    $transaccion->categoria_id = request('selectCategoria');
                    $categoria_nueva = Categorias::find($transaccion->categoria_id);
                    $categoria_nueva->rebajo = $categoria_nueva->rebajo - request('monto');
                    $categoria_nueva->save();

                    $temp->save();
                    $cuenta->save();


                    $cuenta = Cuentas::find(request('cuenta1'));
                    $cuenta->saldo = request('monto');
                    $cuenta->save();
                }
            }
        }
        if (request('tipo') == 'Traslado') {
            $cuenta = Cuentas::find(request('cuentas'));

            foreach ($moneda_local as $local) {

                if ($cuenta->monedas_id == $local->id) {
                    $cuenta->saldo = $cuenta->saldo + request('monto');
                } else {
                    $monto_nuevo = 0;
                    foreach ($moneda_secundaria_tasa as $rate) {
                        $monto_nuevo = request('monto') / $rate->rate;
                        $cuenta->saldo = $cuenta->saldo + $monto_nuevo;
                    }
                }
            }

            $cuenta->save();

            if ($transaccion->categoria_id != request('selectCategoria')) {
                $categoria_antigua = Categorias::find($transaccion->categoria_id);
                $categoria_antigua->rebajo = $categoria_antigua->rebajo + request('monto');
                $categoria_antigua->save();
                $transaccion->categoria_id = request('selectCategoria');
                $categoria_nueva = Categorias::find($transaccion->categoria_id);
                $categoria_nueva->rebajo = $categoria_nueva->rebajo - request('monto');
                $categoria_nueva->save();
            } else {
                $transaccion->categoria_id = request('selectCategoria');
                $categoria_nueva = Categorias::find($transaccion->categoria_id);
                $categoria_nueva->rebajo = $categoria_nueva->rebajo - request('monto');
                $categoria_nueva->save();
            }

            $nueva_transaccion = new Transactions();
            $nueva_transaccion->user_id = auth()->user()->id;
            $nueva_transaccion->cuenta_id = request('cuentas');
            $nueva_transaccion->categoria_id = request('selectCategoria');
            $nueva_transaccion->tipo = 'Ingreso';
            foreach ($moneda_local as $local) {

                if ($cuenta->monedas_id == $local->id) {
                    $nueva_transaccion->monto = request('monto');
                } else {
                    $monto_nuevo = 0;
                    foreach ($moneda_secundaria_tasa as $rate) {
                        $monto_nuevo = request('monto') / $rate->rate;
                        $nueva_transaccion->monto = $monto_nuevo;
                    }
                }
            }
            $nueva_transaccion->detalle = $transaccion->detalle;
            $nueva_transaccion->save();
            $transaccion->traslado = $nueva_transaccion->id;
            $transaccion->destino = request('cuentas');
            $transaccion->tipo = 'Gasto';
        } else {
            $cuenta = Cuentas::find(request('cuenta1'));

            if (request('tipo') == 'Ingreso') {
                $cuenta->saldo = $cuenta->saldo + request('monto');
                $transaccion->traslado = null;
                $transaccion->destino = null;
                $transaccion->tipo = request('tipo');
                if ($transaccion->categoria_id != request('selectCategoria')) {
                    $categoria_antigua = Categorias::find($transaccion->categoria_id);
                    $categoria_antigua->rebajo = $categoria_antigua->rebajo + request('monto');
                    $categoria_antigua->save();
                } else {
                    $categoria_antigua = Categorias::find($transaccion->categoria_id);
                    $categoria_antigua->rebajo = $categoria_antigua->rebajo + request('monto');
                    $categoria_antigua->save();
                }
            }
            if (request('tipo') == 'Gasto') {
                $cuenta->saldo = $cuenta->saldo - request('monto');
                $transaccion->traslado = null;
                $transaccion->destino = null;
                $transaccion->tipo = request('tipo');
                if ($transaccion->categoria_id != request('selectCategoria')) {
                    $categoria_antigua = Categorias::find($transaccion->categoria_id);
                    $categoria_antigua->rebajo = $categoria_antigua->rebajo + request('monto');
                    $categoria_antigua->save();
                    $transaccion->categoria_id = request('selectCategoria');
                    $categoria_nueva = Categorias::find($transaccion->categoria_id);
                    $categoria_nueva->rebajo = $categoria_nueva->rebajo - request('monto');
                    $categoria_nueva->save();
                } else {
                    $categoria_antigua = Categorias::find($transaccion->categoria_id);
                    $categoria_antigua->rebajo = $categoria_antigua->rebajo - request('monto');
                    $categoria_antigua->save();
                }
            }
            $cuenta->save();
        }
        $transaccion->save();
        return redirect('/transacciones/' . request('cuenta1'))->with('success', 'Transacción actualizada con éxito');
    }

    public function destroy($id, $cuenta_redirect)
    {
        $transaccion = Transactions::find($id);
        if ($transaccion->traslado) {
            $cuentas = DB::table('cuentas AS t1')
                ->select('t1.saldo')
                ->where('t1.id', '=', $transaccion->destino)->get();
            $temp = Transactions::find($transaccion->traslado);
            foreach ($cuentas as $cuent) {
                $cuenta = Cuentas::find($transaccion->destino);
                $cuenta->saldo = $cuent->saldo - $temp->monto;
                $cuenta->save();
            }
            $temp->delete();

            $cuenta_origen = Cuentas::find($transaccion->cuenta_id);
            $cuenta_origen->saldo = $cuenta_origen->saldo + $transaccion->monto;
            $cuenta_origen->save();

            $categoria = Categorias::find($transaccion->categoria_id);
            $categoria->rebajo = $categoria->rebajo + $transaccion->monto;
            $categoria->save();

            $transaccion->delete();
            return redirect('/transacciones/' . $cuenta_redirect)->with('success', 'Transacción eliminada con éxito');
        }
        if($transaccion->tipo == 'Ingreso')
        {
            $cuenta_origen = Cuentas::find($transaccion->cuenta_id);
            $cuenta_origen->saldo = $cuenta_origen->saldo - $transaccion->monto;
            $cuenta_origen->save();

            $transaccion->delete();
            return redirect('/transacciones/' . $cuenta_redirect)->with('success', 'Transacción eliminada con éxito');
        }
        if($transaccion->tipo == 'Gasto')
        {
            $cuenta_origen = Cuentas::find($transaccion->cuenta_id);
            $cuenta_origen->saldo = $cuenta_origen->saldo + $transaccion->monto;
            $cuenta_origen->save();

            $categoria = Categorias::find($transaccion->categoria_id);
            $categoria->rebajo = $categoria->rebajo + $transaccion->monto;
            $categoria->save();

            $transaccion->delete();
            return redirect('/transacciones/' . $cuenta_redirect)->with('success', 'Transacción eliminada con éxito');
        }
    }
}
