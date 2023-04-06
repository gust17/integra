<?php

use App\Models\Pessoa;
use App\Models\Servidor;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

//Auth::routes(['register' => false]);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::resource('consignatarias', \App\Http\Controllers\ConsignatariaController::class);
Route::resource('pessoas', \App\Http\Controllers\PessoaController::class);
Route::resource('contratos', \App\Http\Controllers\ContratoController::class)->middleware('auth');
Route::post('contrato-import', [\App\Http\Controllers\ContratoController::class, 'import'])->name('contrato.import');
Route::get('consignatarias/{id}/validadas', [\App\Http\Controllers\ConsignatariaController::class, 'validada'])->name('consignataria.validada');
Route::get('consignataria/import', [\App\Http\Controllers\ConsignatariaController::class, 'create_import'])->name('consignataria.index');
Route::get('consignatarias/{id}/nao-validadas', [\App\Http\Controllers\ConsignatariaController::class, 'naovalidada'])->name('consignataria.naovalidada');
Route::get('consignatarias/{id}/contratos_sem_pessoa', [\App\Http\Controllers\ConsignatariaController::class, 'sem_pessoa'])->name('consignataria.sem_pessoa');
Route::get('consignatarias/{id}/contratos_sem_servidor', [\App\Http\Controllers\ConsignatariaController::class, 'sem_servidor'])->name('consignataria.sem_servidor');
Route::get('consignatarias/{id}/novo-contrato', [\App\Http\Controllers\ConsignatariaController::class, 'novo_contrato'])->name('consignataria.novo-contrato');
Route::get('consignatarias/{id}/semelhantes', [\App\Http\Controllers\ConsignatariaController::class, 'semelhante'])->name('consignataria.semelhante');
Route::get('consignatarias/{id}/sem-banco', [\App\Http\Controllers\ConsignatariaController::class, 'sem_banco'])->name('consignataria.sem_banco');
Route::post('contrato-bancos', [\App\Http\Controllers\ContratoController::class, 'importBanco'])->name('contratos.bancos');
Route::post('consignatarias/contrato-semelhante/pesquisa', [\App\Http\Controllers\ConsignatariaController::class, 'pesquisa_semelhante'])->name('consignataria.semelhante.pesquisa');
Route::post('pessoa-import', [\App\Http\Controllers\PessoaController::class, 'import'])->name('pessoa.import');
Route::get('pessoa/import', [\App\Http\Controllers\PessoaController::class, 'create_import']);
Route::resource('consignante-master', \App\Http\Controllers\ConsignanteMasterController::class);
Route::resource('consignante', \App\Http\Controllers\ConsignanteController::class);
Route::resource('averbadors', \App\Http\Controllers\AverbadorController::class);

Route::get('testeimport', function () {
    return view('teste.import');
});
Route::post('testeimport', function (Request $request) {
    $file = $request->file('file');
    $fileContents = $file->get();
    $lines = explode("\n", $fileContents);

    foreach ($lines as $line) {
        //  89133-43260284915
        //dd($line);
        $matricula = intval(trim(substr($line, 0, 19)));
        // 43260284915
        $cpf = trim(substr($line, 20, 11));
        //dd($cpf);
        $pessoa = Pessoa::where('cpf', $cpf)->exists();


        //dd($matricula);
        $servidor = Servidor::where('matricula', $matricula)->exists();

        $margem = trim(substr($line, 343, 15));
        $margem = floatval(str_insert($margem, ".", -2));
        dd($margem);
        // dd($matricula);
        if (!empty($pessoa)) {
            $pessoa = Pessoa::where('cpf', $cpf)->get();

            dd($pessoa);
            // faça alddgo com cada linha
        }
    }
    dd($file);
    //return view('teste.import');
});

Route::get('/ajax-modal/{id}', [\App\Http\Controllers\ContratoController::class, 'modal'])->name('ajax.modal');


Route::post('importar-consignatarias', [\App\Http\Controllers\ConsignatariaController::class, 'import'])->name('consignataria.import');
