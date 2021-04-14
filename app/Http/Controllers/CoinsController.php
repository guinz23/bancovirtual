<?php

namespace TrackYourMoney\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use TrackYourMoney\coins;
use TrackYourMoney\usersCoins;

class CoinsController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	} 
    /**
     * show coins dashboard
     */
    public function index()
    {
        $coins = DB::table('users_coins')
        ->select('users_coins.id', 'users_coins.rate', 'users_coins.user_id', 'users_coins.coin_id', 'users_coins.local', 'coins.name', 'coins.symbol', 'coins.description')
        ->join('users', 'users.id', '=', 'users_coins.user_id')
        ->join('coins', 'coins.id', '=', 'users_coins.coin_id')
        ->where('users_coins.user_id', '=',  auth()->id() )
        ->get();
        $names = DB::table('coins')
        ->get();
        return view('coins.dashboard', ['coins' => $coins, 'names' =>$names]);
    }
    
    /**
     * Show create and update coins view
     */
    public function create(Request $request)
    {
        if ($request->input('miSelect')  != 'otra') {
            $coin = Coins::where('name', [$request->input('miSelect')] )->get();
            
            $userco = new usersCoins();
            $userco->rate = $request->input('tasa');
            $userco->user_id = auth()->id();
            $userco->coin_id = $coin[0]->id;
            $userco->local = $request->input('local') =='local' ? true : false;
            $userco->save();
        }else{
            $coin = new Coins();
            $coin->name = $request->input('name');
            $coin->symbol = $request->input('simbolo');
            $coin->description = $request->input('desc');
            $coin->save();

            $userco = new usersCoins();
            $userco->rate = $request->input('tasa');
            $userco->user_id = auth()->id();
            $userco->coin_id = $coin->id;
            $userco->local = $request->input('local')=='local' ? true : false;
            $userco->save();
        }
        return redirect()->route('coins')->with('success', 'Moneda creada con éxito');
    }

    public function loadupdate(int $idmoneda)
    {
        $coins = DB::table('users_coins')
        ->select('users_coins.id', 'users_coins.rate', 'users_coins.user_id', 'users_coins.coin_id', 'users_coins.local', 'coins.name', 'coins.symbol', 'coins.description')
        ->join('users', 'users.id', '=', 'users_coins.user_id')
        ->join('coins', 'coins.id', '=', 'users_coins.coin_id')
        ->where('users_coins.id', '=',  $idmoneda)
        ->get();
        $userco = usersCoins::find($idmoneda);
        $id = $userco->coin_id;
        $coin = coins::find($id);
        $names = DB::table('coins')
        ->get();
        return view('coins.edit', ['coin' => $coin, 'userco' =>$userco, 'names' => $names, 'coins' => $coins]);
    }

    public function delete(int $idmoneda)
    {
        $userco = usersCoins::find($idmoneda);
        $userco->delete();
        return redirect()->route('coins');
    }

    public function update(Request $request)
    {
        if ($request->miSelect  != 'otra') {
            $coin = Coins::where('name', [$request->miSelect] )->get();
            
            $userco = new usersCoins();
            $userco->id = $request->id;
            $userco->rate = $request->tasa;
            $userco->user_id = auth()->id();
            $userco->coin_id = $coin[0]->id;
            $userco->local = $request->local =='local' ? true : false;
            $userco->where('id', $userco->id)->update(['rate'=>$userco->rate, 'coin_id'=>$userco->coin_id, 'local'=>$userco->local]);
        }
        return redirect()->route('coins')->with('success', 'Moneda actualizada con éxito');
    }
}