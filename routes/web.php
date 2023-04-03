<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::resource('consignatarias',\App\Http\Controllers\ConsignatariaController::class);
Route::resource('pessoas',\App\Http\Controllers\PessoaController::class);
Route::resource('contratos',\App\Http\Controllers\ContratoController::class);
Route::post('contrato-import',[\App\Http\Controllers\ContratoController::class,'import'])->name('contrato.import');
Route::get('consignatarias/{id}/validadas',[\App\Http\Controllers\ConsignatariaController::class,'validada'])->name('consignataria.validada');
Route::get('consignataria/import',[\App\Http\Controllers\ConsignatariaController::class,'create_import'])->name('consignataria.import');
Route::get('consignatarias/{id}/nao-validadas',[\App\Http\Controllers\ConsignatariaController::class,'naovalidada'])->name('consignataria.naovalidada');
Route::get('consignatarias/{id}/contratos_sem_pessoa',[\App\Http\Controllers\ConsignatariaController::class,'sem_pessoa'])->name('consignataria.sem_pessoa');
Route::get('consignatarias/{id}/contratos_sem_servidor',[\App\Http\Controllers\ConsignatariaController::class,'sem_servidor'])->name('consignataria.sem_servidor');
Route::get('consignatarias/{id}/novo-contrato',[\App\Http\Controllers\ConsignatariaController::class,'novo_contrato'])->name('consignataria.novo-contrato');
Route::get('consignatarias/{id}/semelhantes',[\App\Http\Controllers\ConsignatariaController::class,'semelhante'])->name('consignataria.semelhante');
Route::get('consignatarias/{id}/sem-banco',[\App\Http\Controllers\ConsignatariaController::class,'sem_banco'])->name('consignataria.sem_banco');
Route::post('contrato-bancos',[\App\Http\Controllers\ContratoController::class,'importBanco'])->name('contratos.bancos');
Route::post('pessoa-import',[\App\Http\Controllers\PessoaController::class,'import'])->name('pessoa.import');
Route::get('pessoa/import',[\App\Http\Controllers\PessoaController::class,'create_import']);
Route::resource('consignante-master',\App\Http\Controllers\ConsignanteMasterController::class);

Route::get('/ajax-modal/{id}', [\App\Http\Controllers\ContratoController::class,'modal'])->name('ajax.modal');


Route::post('importar-consignatarias',[\App\Http\Controllers\ConsignatariaController::class,'import'])->name('importar-consignatarias');
