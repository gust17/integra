<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('consignante_master/{id}',function ($id){
    $consignante_master = \App\Models\ConsignanteMaster::find($id);



    return $consignante_master->consignantes;
});

Route::get('consignante/{id}',function ($id){
    $consignante = \App\Models\Consignante::find($id);



    return $consignante->averbadors;
});

Route::get('pessquisapessoa',[\App\Http\Controllers\PessoaController::class,'pesquisa']);

