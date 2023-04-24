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
    return redirect()->route('home');
});

//Auth::routes(['register' => false]);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('contrato',[\App\Http\Controllers\ContratoController::class,'contratos']);
Route::post('consulta',[\App\Http\Controllers\ContratoController::class,'consulta']);
Route::get('consulta/{master}/{consignante}/{averbador}',[\App\Http\Controllers\ContratoController::class,'repostaConsulta']);
Route::get('relatoriocontrato/{consignataria}/{averbador}',[\App\Http\Controllers\ConsignatariaController::class,'relatorio']);
Route::get('/consignataria/importados/{averbador}/{consignataria}',[\App\Http\Controllers\ConsignatariaController::class,'importados']);
Route::resource('consignatarias', \App\Http\Controllers\ConsignatariaController::class);
Route::resource('pessoas', \App\Http\Controllers\PessoaController::class);
Route::resource('contratos', \App\Http\Controllers\ContratoController::class)->middleware('auth');
Route::post('contrato-import', [\App\Http\Controllers\ContratoController::class, 'import'])->name('contrato.import');
Route::get('consignatarias/{id}/validadas', [\App\Http\Controllers\ConsignatariaController::class, 'validada'])->name('consignataria.validada');
Route::get('consignataria/import', [\App\Http\Controllers\ConsignatariaController::class, 'create_import'])->name('consignataria.index');
Route::get('consignatarias/{id}/nao-validadas', [\App\Http\Controllers\ConsignatariaController::class, 'naovalidada'])->name('consignataria.naovalidada');
Route::get('consignatarias/{id}/contratos_sem_pessoa', [\App\Http\Controllers\ConsignatariaController::class, 'sem_pessoa'])->name('consignataria.sem_pessoa');
Route::get('consignatarias/{id}/contratos_sem_servidor', [\App\Http\Controllers\ConsignatariaController::class, 'sem_servidor'])->name('consignataria.sem_servidor');
Route::get('consignatarias/{id}/sem_prefeitura', [\App\Http\Controllers\ConsignatariaController::class, 'sem_prefeitura'])->name('consignataria.sem_prefeitura');
Route::get('consignatarias/{id}/sem_banco', [\App\Http\Controllers\ConsignatariaController::class, 'sem_banco'])->name('consignataria.sem_banco');
Route::get('consignatarias/{id}/obs', [\App\Http\Controllers\ConsignatariaController::class, 'obs'])->name('consignataria.obs');
Route::get('consignatarias/{id}/novo-contrato', [\App\Http\Controllers\ConsignatariaController::class, 'novo_contrato'])->name('consignataria.novo-contrato');
Route::get('consignatarias/{id}/semelhantes', [\App\Http\Controllers\ConsignatariaController::class, 'semelhante'])->name('consignataria.semelhante');
Route::get('consignatarias/{id}/sem-banco', [\App\Http\Controllers\ConsignatariaController::class, 'sem_banco'])->name('consignataria.sem_banco');
Route::post('contrato-bancos', [\App\Http\Controllers\ContratoController::class, 'importBanco'])->name('contratos.bancos');
Route::post('consignatarias/contrato-semelhante/pesquisa', [\App\Http\Controllers\ConsignatariaController::class, 'pesquisa_semelhante'])->name('consignataria.semelhante.pesquisa');
Route::post('pessoa-import', [\App\Http\Controllers\PessoaController::class, 'import'])->name('pessoa.import');
Route::post('servidor-import', [\App\Http\Controllers\ServidorController::class, 'import'])->name('servidor.import');
Route::get('pessoa/import', [\App\Http\Controllers\PessoaController::class, 'create_import']);
Route::get('servidores/import', [\App\Http\Controllers\ServidorController::class, 'create_import']);
Route::resource('consignante-master', \App\Http\Controllers\ConsignanteMasterController::class);
Route::resource('consignante', \App\Http\Controllers\ConsignanteController::class);
Route::resource('averbadors', \App\Http\Controllers\AverbadorController::class);
Route::get('consignataria/banco/import', [\App\Http\Controllers\ContratoController::class, 'banco_import']);

Route::get('testeimport', function () {
    $consignantes_masters = \App\Models\ConsignanteMaster::all();

    return view('teste.import', compact('consignantes_masters'));
});
Route::post('testeimport', function (Request $request) {
    //dd($request->all());
    $file = $request->file('file');
    $fileContents = $file->get();
    $lines = explode("\n", $fileContents);

    foreach ($lines as $line) {

        $matricula = intval(trim(substr($line, $request->matricula_inicio, $request->matricula_tamanho)));
        $nome = (trim(substr($line, $request->name_inicio, $request->name_tamanho)));
        $cpf = (trim(substr($line, $request->cpf_inicio, $request->cpf_tamanho)));


        $pessoa = Pessoa::where('cpf', $cpf)->first();
        if (!empty($pessoa)) {
            $pessoa = Pessoa::where('cpf', $cpf)->get();

            dd($pessoa);


        } else {
            dd($nome);
        }


        $servidor = Servidor::where('matricula', $matricula)->exists();


        if ($servidor) {
            $servidor = Servidor::where('matricula', $matricula)->first();
            dd($servidor->pessoa->name, $nome);
        } else {

        }

        $margem = trim(substr($line, 343, 15));
        $margem = floatval(str_insert($margem, ".", -2));

        // dd($matricula);
        if (!empty($pessoa)) {
            //  $pessoa = Pessoa::where('cpf', $cpf)->get();

            // dd($pessoa);
            // faça alddgo com cada linha
        }
    }
    dd($file);
    //return view('teste.import');
});

Route::get('/ajax-modal/{id}', [\App\Http\Controllers\ContratoController::class, 'modal'])->name('ajax.modal');


Route::post('importar-consignatarias', [\App\Http\Controllers\ConsignatariaController::class, 'import'])->name('consignataria.import');


Route::get('pessoateste', function () {
    $contratos = \App\Models\Contrato::where('consignataria_id', 2)->get();
    //dd($contratos);

    //dd($contratos->toArray());
    foreach ($contratos as $contrato) {
        $contrato->delete();
        // $contrato->fill(['origem' => 0]);
        //  $contrato->save();


    }
});

Route::get('validabanco', function () {
    $consignataria = \App\Models\Consignataria::find(3);
    $contratos = $consignataria->contratos->whereNotNull('contrato_id');

    foreach ($contratos as $contrato) {
        $delatar = \App\Models\Contrato::destroy($contrato->contrato_id);

        $contrato->fill(['status' => 1, 'contrato_id' => null]);
        $contrato->save();
    }
});

Route::get('demitidos', function () {
    $nomes = [
        "ABNER EDSON FELDMANN",
"ABNER EDSON FELDMANN",
"AMARILDO FERREIRA",
"APARECIDO CRISTOVAM",
"DABNEY VIEIRA LEONARDO",
"DENILSON ACELINO LOPES",
"DIMAS RODRIGUES DA SILVA",
"DIMAS RODRIGUES DA SILVA",
"ELIAS MARTINS JUNIOR",
"JAYR BITENCOURT DA SILVA",
"JOAO BATISTA CARDOSO",
"JOSE HONORATO DE JESUS",
"JOSE HONORATO DE JESUS",
"JOSE HONORATO DE JESUS",
"JOSE MARIA SOARES",
"MARCOS AURELIO DA GAMA",
"MURILO HAMES",
"MURILO HAMES",
"OSNELITO NASCIMENTO",
"ROGERIO DA COSTA",
"SALETE DE OLIVEIRA",
"WILSON JANUARIO FERREIRA",
"WILSON JANUARIO FERREIRA",




    ];

    $buscas = Pessoa::whereIn('name',$nomes)->pluck('id')->toArray();


    $matriculas = Servidor::whereIn('pessoa_id',$buscas)->pluck('id')->toArray();


    $contratos = \App\Models\Contrato::whereIn('servidor_id',$matriculas)->where('status',1)->get();

    //dd($contratos->toArray());

   foreach ($contratos as $contrato){
        $contrato->update(['status' => 0]);
   }




});
