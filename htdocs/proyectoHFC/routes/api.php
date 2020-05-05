<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//WEBSERVICE REST - HADA
Route::post('autenticacion', 'ApiServices\Autenticacion\AutenticacionController@Authenticate');
//Route::post('autenticacion', 'ApiServices\ServiceMulticonsulta\ServiceMulticonsultaController@Authenticate');

Route::post('estadoClienteHFC', 'ApiServices\ServiceMulticonsulta\ServiceMulticonsultaController@getInfoBasicaServicioHFCxCliente');
Route::post('estadoServicioHFC', 'ApiServices\ServiceMulticonsulta\ServiceMulticonsultaController@getInfoEstadoServicioHFCxCliente');

//WEBSERVICE REST - PCI
Route::post('pruebasCablemodem', 'ApiServices\ServicePci\ServicePciController@getPruebasCablemodem');
Route::post('pruebasCablemodemIW', 'ApiServices\ServicePci\ServicePciController@getPruebasCablemodemIW');


