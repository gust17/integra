<?php

namespace App\Http\Controllers;

use App\Imports\ConsignatariaImport;
use App\Models\Averbador;
use App\Models\Consignataria;
use App\Models\Contrato;
use App\Models\Pessoa;
use App\Models\Servidor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ConsignatariaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consignatarias = Consignataria::all();
        return view('consignatarias.index', compact('consignatarias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Consignataria::create($request->all());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Consignataria $consignataria)
    {
// Obter todos os contratos da consignatária
        $contratos = $consignataria->contratos;

// Obter todas as pessoas inativas e seus IDs
        $pessoas_inativas = Pessoa::where('ativo', 0)->pluck('id');

// Obter servidores inativos com pessoa associada e seus IDs
        $servidor_inativo_comPessoa = Servidor::where('ativo', 0)->whereNotIn('pessoa_id', $pessoas_inativas)->pluck('id');

// Obter servidores inativos sem pessoa associada e seus IDs
        $servidor_inativo_semPessoa = Servidor::where('ativo', 0)->whereIn('pessoa_id', $pessoas_inativas)->pluck('id');

// Obter contratos de servidores inativos com pessoa associada e origem na prefeitura
        $contratos_servidorInativos_comPessoa_Prefeitura = $contratos->whereIn('servidor_id', $servidor_inativo_comPessoa)->where('origem', 0);

// Obter contratos de servidores inativos com pessoa associada e origem no banco
        $contratos_servidorInativos_comPessoa_Banco = $contratos->whereIn('servidor_id', $servidor_inativo_comPessoa)->where('origem', 1);

// Obter contratos de servidores inativos sem pessoa associada e origem na prefeitura
        $contratos_servidorInativos_semPessoa_Prefeitura = $contratos->whereIn('servidor_id', $servidor_inativo_semPessoa)->where('origem', 0);

// Obter contratos de servidores inativos sem pessoa associada e origem no banco
        $contratos_servidorInativos_semPessoa_Banco = $contratos->whereIn('servidor_id', $servidor_inativo_semPessoa)->where('origem', 1);

        $servidor_inativo = Servidor::where('ativo', 0)->get();
// Passar os contratos e a consignatária para a view correspondente
        return view('consignatarias.show_contratos', compact('contratos', 'consignataria', 'contratos_servidorInativos_semPessoa_Banco', 'contratos_servidorInativos_comPessoa_Banco', 'contratos_servidorInativos_comPessoa_Prefeitura', 'contratos_servidorInativos_semPessoa_Prefeitura', 'servidor_inativo'));
        //
    }

    public function relatorio($consignataria, $averbador)
    {
        $consignataria = Consignataria::find($consignataria);
// Obter todos os contratos da consignatária
        $contratos = $consignataria->contratos->where('averbador_id', $averbador);


        $servidor_inativo = Servidor::where('ativo', 0)->pluck('id');
        //dd($servidor_inativo);
        //dd($contratos->where('origem',0)->where('status',0)->whereNull('obs')->whereNotIn('servidor_id',$servidor_inativo->pluck('id')));
        $servidor_inativo = Servidor::where('ativo', 0)->get();
// Obter todas as pessoas inativas e seus IDs
        $pessoas_inativas = Pessoa::where('ativo', 0)->pluck('id');
        //dd($contratos->where('origem', 1)->whereIn('servidor_id', $servidor_inativo->pluck('id')));
        $matriculas = Servidor::whereIn('pessoa_id', $pessoas_inativas)->where('ativo', 1)->get();
        //dd($matriculas);
        $pessoas_ativas = Pessoa::where('ativo', 1)->pluck('id');

// Obter servidores inativos com pessoa associada e seus IDs
        $servidor_inativo_comPessoa = Servidor::where('ativo', 0)->whereIn('pessoa_id', $pessoas_ativas)->pluck('id');

// Obter servidores inativos sem pessoa associada e seus IDs
        $servidor_inativo_semPessoa = Servidor::where('ativo', 0)->whereIn('pessoa_id', $pessoas_inativas)->pluck('id');

        //dd($contratos->where('origem', 1)->where('status', 1)->whereIn('servidor_id',$servidor_inativo));
        //   dd($servidor_inativo_semPessoa);
// Obter contratos de servidores inativos com pessoa associada e origem na prefeitura
        $contratos_servidorInativos_comPessoa_Prefeitura = $contratos->whereIn('servidor_id', $servidor_inativo_comPessoa)->where('origem', 0);

// Obter contratos de servidores inativos com pessoa associada e origem no banco
        $contratos_servidorInativos_comPessoa_Banco = $contratos->whereIn('servidor_id', $servidor_inativo_comPessoa)->where('origem', 1);

       // dd($contratos_servidorInativos_comPessoa_Banco);

// Obter contratos de servidores inativos sem pessoa associada e origem na prefeitura
        $contratos_servidorInativos_semPessoa_Prefeitura = $contratos->whereIn('servidor_id', $servidor_inativo_semPessoa)->where('origem', 0);

// Obter contratos de servidores inativos sem pessoa associada e origem no banco
        $contratos_servidorInativos_semPessoa_Banco = $contratos->whereIn('servidor_id', $servidor_inativo_semPessoa)->where('origem', 1);

        $servidor_inativo = Servidor::where('ativo', 0)->get();

        $averbador = Averbador::find($averbador);
// Passar os contratos e a consignatária para a view correspondente
        return view('consignatarias.show_contratos', compact('contratos', 'consignataria', 'contratos_servidorInativos_semPessoa_Banco', 'contratos_servidorInativos_comPessoa_Banco', 'contratos_servidorInativos_comPessoa_Prefeitura', 'contratos_servidorInativos_semPessoa_Prefeitura', 'servidor_inativo','averbador'));
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consignataria $consignataria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consignataria $consignataria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consignataria $consignataria)
    {
        //
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $consignatariaImport = new ConsignatariaImport($request->consignataria);


        //Excel::import($pessoaImport, $file);
        Excel::import($consignatariaImport, $file);

        return redirect()->back();
    }


    public function validada($id)
    {
        $consignataria = Consignataria::find($id);
        $contratos = $consignataria->contratos->where('status', 1)->whereNull('obs');

        //dd($contratos);

        return view('consignatarias.show_contratos_validados', compact('contratos', 'consignataria'));

    }

    public function naovalidada($id)
    {
        $consignataria = Consignataria::find($id);
        $contratos = $consignataria->contratos;
        $contratos_semelhantes = $contratos->whereNotNull('contrato_id')->whereNull('obs');
        $contratos = $contratos->where('status', "!=", 1)->whereNull('obs')->whereNull('contrato_id')->whereNotIn('id', $contratos_semelhantes->pluck('contrato_id')->toArray())->where('n_parcela_referencia', '!=', 1);

        // $contratos = $consignataria->contratos->where('status', '!=', 1)->whereNull('obs');
        $title = 'Não Validadas';
        return view('consignatarias.show_contratos_geral', compact('contratos', 'consignataria', 'title'));

    }

    public function sem_pessoa($id)
    {
        $consignataria = Consignataria::find($id);
        $contratos = $consignataria->contratos()->whereHas('servidor', function ($query) {
            $query->whereHas('pessoa', function ($query) {
                $query->where('ativo', false);
            });
        })->get();

        $title = "Sem Pessoa";
        return view('consignatarias.show_contratos_geral', compact('contratos', 'consignataria', 'title'));

    }

    public function sem_servidor($id)
    {
        $consignataria = Consignataria::find($id);

        $contratos = $consignataria->contratos()->whereHas('servidor', function ($query) {
            $query->where('ativo', false)->whereHas('pessoa', function ($query) {
                $query->where('ativo', true);
            });
        })->get();

        $title = "Sem Servidor(Matricula)";

        return view('consignatarias.show_contratos_geral', compact('contratos', 'consignataria', 'title'));

    }

    public function semelhante($id)
    {
        $consignataria = Consignataria::find($id);

        $contratos = $consignataria->contratos->whereNotNull('contrato_id')->whereNull('obs');
        $title = "Semelhantes";
        return view('consignatarias.show_contratos_semelhante', compact('contratos', 'consignataria', 'title'));
    }

    public function obs($id)
    {
        $consignataria = Consignataria::find($id);
        $contratos = $consignataria->contratos->whereNotNull('obs');
        $title = "Com Observação";
        return view('consignatarias.show_contratos_geral', compact('contratos', 'consignataria', 'title'));
    }

    public function sem_prefeitura($id)
    {
        $consignataria = Consignataria::find($id);
        $contratos = $consignataria->contratos;

        $contratos = $contratos->where('origem', 1)->where('status', '!=', 1)->whereNull('contrato_id')->where('n_parcela_referencia', '!=', 1);

        // dd($contratos);
        $title = "Não encontrados na prefeitura";
        return view('consignatarias.show_contratos_geral', compact('contratos', 'consignataria', 'title'));
    }

    public function sem_banco($id)
    {
        $consignataria = Consignataria::find($id);
        $contratos = $consignataria->contratos;
        $contratos_semelhantes = $contratos->whereNotNull('contrato_id')->whereNull('obs');
        // $contratos = $contratos->where('origem',0)->whereNull('obs')->where('status','!=',1)->whereNotIn('id', $contratos_semelhantes->pluck('contrato_id'));

        $contratos = $contratos->where('origem', 0)->whereNull('obs')->where('status', '!=', 1)->whereNotIn('id', $contratos_semelhantes->pluck('contrato_id'))->where('n_parcela_referencia', 1);
        //dd($contratos->count());
        $title = "Não encontrados no Arquivo do banco";
        return view('consignatarias.show_contratos_geral', compact('contratos', 'consignataria', 'title'));

    }

    public function novo_contrato($id)
    {
        $consignataria = Consignataria::find($id);
        $id_reterirar_consulta = $consignataria->contratos->whereNotNull('contrato_id')->pluck('contrato_id')->toArray();
        $contratos = $consignataria->contratos->where('n_parcela_referencia', 1)->whereNotIn('id', $id_reterirar_consulta)->whereNull('contrato_id');
        $title = "Renegociação ou novo contrato";
        return view('consignatarias.show_contratos_geral', compact('contratos', 'consignataria', 'title'));
    }


    public function create_import()
    {
        $consignatarias = Consignataria::all();

        return view('consignatarias.import', compact('consignatarias'));
        # code...
    }


    public function pesquisa_semelhante(Request $request)
    {

        $consignataria = Consignataria::find($request->consignataria);

        $contratos = $consignataria->contratos->whereNotNull('contrato_id');

        if ($request->matricula_inexistente) {
            $matriculas = Servidor::where("ativo", 0)->pluck('id')->toArray();
            $contratos = $contratos->whereIn('servidor_id', $matriculas);
        }
        if ($request->matricula) {
            $matriculasInexistentes = $contratos->filter(function ($contrato) {
                return $contrato->servidor_id != $contrato->semelhante->servidor->id;
            })->pluck('id');
            $contratos = $matriculasInexistentes->count() ? $contratos->whereIn('id', $matriculasInexistentes) : $contratos;
        }

        // Filtra os contratos com descontos diferentes, se necessário
        if ($request->desconto) {
            $descontosDiferentes = $contratos->filter(function ($contrato) {
                return $contrato->valor_parcela != $contrato->semelhante->valor_parcela;
            })->pluck('id');
            $contratos = $descontosDiferentes->count() ? $contratos->whereIn('id', $descontosDiferentes) : $contratos;
        }
        if ($request->parcela) {
            $parcelasDiferentes = $contratos->filter(function ($contrato) {
                return $contrato->n_parcela_referencia != $contrato->semelhante->n_parcela_referencia;
            })->pluck('id');
            $contratos = $parcelasDiferentes->count() ? $contratos->whereIn('id', $parcelasDiferentes) : $contratos;
        }

        // Filtra os contratos com prazos diferentes, se necessário
        if ($request->prazo) {
            $prazosDiferentes = $contratos->filter(function ($contrato) {
                return $contrato->total_parcela != $contrato->semelhante->total_parcela;
            })->pluck('id');
            $contratos = $prazosDiferentes->count() ? $contratos->whereIn('id', $prazosDiferentes) : $contratos;
        }
        # code...


        $title = "Semelhantes Filtro";
        return view('consignatarias.show_contratos_semelhante', compact('contratos', 'consignataria', 'title'));
    }

    public function importados($averbador,$consignataria)
    {
        $contratos = Contrato::where('averbador_id',$averbador)->where('consignataria_id',$consignataria)->get();

        return view('contratos.importados',compact('contratos'));

    }
}
