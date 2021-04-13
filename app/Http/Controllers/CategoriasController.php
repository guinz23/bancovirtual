<?php

namespace TrackYourMoney\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TrackYourMoney\Categorias;
use TrackYourMoney\User;

class CategoriasController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	} 
    
    public function index()
    {
        $coins = DB::table('users_coins')
            ->select('users_coins.id', 'users_coins.rate', 'users_coins.user_id', 'users_coins.coin_id', 'users_coins.local', 'coins.name', 'coins.symbol', 'coins.description')
            ->join('users', 'users.id', '=', 'users_coins.user_id')
            ->join('coins', 'coins.id', '=', 'users_coins.coin_id')
            ->where('users_coins.user_id', '=',  auth()->id() )
            ->get();
        $categorias_all = DB::table('categorias AS t1')
                        ->select('t1.id', 't1.user_id', 't1.categoria_id', 't1.tipo', 't1.descripcion AS padre', 't2.descripcion AS hijo', 't1.presupuesto', 't1.created_at', 't1.updated_at')
                        ->leftJoin('categorias AS t2', 't1.categoria_id', '=', 't2.id')
                        ->where('t1.user_id', '=', auth()->user()->id)
                        ->get();
        $categorias = User::find(auth()->user()->id)->categorias;
        return view('categorias.categoria', compact('categorias_all', 'categorias', 'coins'));
    }

    public function store()
    {
        $categoria = new Categorias();
        $categoria->user_id = auth()->user()->id;
        $categoria->categoria_id = request('padre');
        $categoria->moneda_id = request('coin');
        $categoria->tipo = request('tipo');
        $categoria->descripcion = request('descripcion');
        $categoria->presupuesto = request('presupuesto');
        $categoria->rebajo = request('presupuesto');
        $categoria->save();
        return redirect('/categorias')->with('success', 'Categoría creada con éxito');
    }

    public function destroy(string $id)
    {
        $tiene_hijos = DB::table('categorias')
                    ->select('id')
                    ->where('categoria_id', '=', $id)
                    ->get();
        if($tiene_hijos->count() == 0) {
            $categoria = Categorias::find($id);
            $categoria->delete();
            return redirect('/categorias')->with('success', 'Categoría eliminada con éxito');
        }
        else {
            return redirect('/categorias')->with('info', 'La categoría seleccionada no se puede eliminar, ya que esta posee categorías asociadas');
        }
    }

    public function edit($id)
    {
        $seleccionada = Categorias::find($id);
        $categorias = User::find(auth()->user()->id)->categorias;
        $coins = DB::table('users_coins')
            ->select('users_coins.id', 'users_coins.rate', 'users_coins.user_id', 'users_coins.coin_id', 'users_coins.local', 'coins.name', 'coins.symbol', 'coins.description')
            ->join('users', 'users.id', '=', 'users_coins.user_id')
            ->join('coins', 'coins.id', '=', 'users_coins.coin_id')
            ->where('users_coins.user_id', '=',  auth()->id() )
            ->get();
        return view('categorias.update', compact('seleccionada', 'categorias', 'coins'));
    }

    public function update($id)
    {   
        $categoria = Categorias::find($id);
        $categoria->user_id = auth()->user()->id;
        $categoria->categoria_id = request('padre');
        $categoria->moneda_id = request('coin');
        $categoria->tipo = request('tipo');
        $categoria->descripcion = request('descripcion');
        $categoria->presupuesto = request('presupuesto');
        $categoria->rebajo = request('presupuesto');
        $categoria->save();
        return redirect('/categorias')->with('success', 'Categoría actualizada con éxito');
    }
}