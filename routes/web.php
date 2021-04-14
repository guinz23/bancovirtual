<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('login/{driver}', 'Auth\LoginController@redirectToProvider');

Route::get('login/{driver}/callback', 'Auth\LoginController@handleProviderCallback');

// Cuentas
Route::post('/crearcuenta', 'CuentasController@store');

Route::get('/editarcuenta/{id}', 'CuentasController@edit');

Route::put('/cuenta/{id}', 'CuentasController@update');

Route::get('/eliminarcuenta/{id}', 'CuentasController@destroy');

//

// Categorías
Route::get('/categorias', 'CategoriasController@index')->name('categorias');

Route::post('/crearcategoria', 'CategoriasController@store');

Route::get('/eliminarcategoria/{id}', 'CategoriasController@destroy');

Route::get('/actualizarcategorias/{id}', 'CategoriasController@edit');

Route::put('/categoria/{id}', 'CategoriasController@update');
//

// Monedas

Route::get('/coins', 'CoinsController@index')->name('coins');

Route::post('/create-coins', 'CoinsController@create')->name('create-coins');

Route::get('/loadupdate-coins/{id}', 'CoinsController@loadupdate')->name('loadupdate-coins');

Route::post('/update-coins', 'CoinsController@update')->name('update-coins');

Route::get('/delete-coins/{id}', 'CoinsController@delete')->name('delete-coins');
//

// Transacciones

Route::get('/transacciones/{id}', 'TransactionsController@index');

Route::get('/todascuentas', 'TransactionsController@todasMisCuentas');

Route::post('/store', 'TransactionsController@store');

Route::get('/eliminartransacc/{id}/{cuenta}', 'TransactionsController@destroy');

Route::get('/editar/{id}/{cuenta}/{categoria}', 'TransactionsController@edit');

Route::put('/actualizartransacc/{id}', 'TransactionsController@update');


//

// Reportes

Route::get('/reportes','ReportesController@index');

Route::post('/transacciones', 'ReportesController@querys');

Route::post('/cat_padres', 'ReportesController@categ_padres');

Route::post('/cat_hijos', 'ReportesController@categ_hijos');

Route::post('/cat_hijos_transacc', 'ReportesController@categ_hijos_transacc');
//
