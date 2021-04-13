<?php

namespace TrackYourMoney\Http\Controllers;

use Illuminate\Http\Request;
use TrackYourMoney\Cuentas;

class CuentasController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	} 
    
    public function store()
    {
        $cuentas = new Cuentas();
        $cuentas->user_id = auth()->user()->id;
        $cuentas->monedas_id = request('monedas');
        $cuentas->nombre = request('nombre');
        $cuentas->descripcion = request('descripcion');
        $cuentas->saldo = request('saldo');
        $cuentas->save();
        return redirect('/home')->with('success', 'Cuenta creada con éxito');
    }

    public function edit($id)
    {
        $cuenta = Cuentas::find($id);
        return view('cuentas.updatecuenta', compact('cuenta'));
    }

    public function update($id)
    {   
        $cuenta = Cuentas::find($id);
        $cuenta->nombre = request('nombre');
        $cuenta->descripcion = request('descripcion');
        $cuenta->saldo = request('saldo');
        $cuenta->save();
        return redirect('/home')->with('success', 'Cuenta actualizada con éxito');
    }

    public function destroy(string $id)
    {
        $cuenta = Cuentas::find($id);
        $cuenta->delete();
        return redirect('/home')->with('success', 'Cuenta eliminada con éxito');
    }
}