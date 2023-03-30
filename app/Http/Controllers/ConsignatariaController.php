<?php

namespace App\Http\Controllers;

use App\Imports\ConsignatariaImport;
use App\Models\Consignataria;
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Consignataria $consignataria)
    {
        //dd($consignataria);


        // dd($servidorSemPessoa);

// Recupera os contratos da consignatária
        $contratos = $consignataria->contratos;

// Filtra os contratos que não possuem servidor ativo
        $contratosSemPessoa = $contratos->whereNotNull('pessoa_existente');
        $contratosSemServidor = $contratos->whereNotNull('servidor_existente');
        $possivelRenegociacao = $contratos->where('n_parcela_referencia', 1)->where('status', '!=', 1);
        $contratos_semelhantes = $contratos->whereNotNull('contrato_id');


        return view('consignatarias.show_contratos', compact('contratos', 'consignataria', 'contratosSemServidor', 'contratosSemPessoa', 'possivelRenegociacao', 'contratos_semelhantes'));
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

        Excel::import(new ConsignatariaImport(), $file);

        return redirect()->back();
    }


    public function validada($id)
    {
        $consignataria = Consignataria::find($id);
        $contratos = $consignataria->contratos->where('status', 1);

        return view('consignatarias.show_contratos_validados', compact('contratos', 'consignataria'));

    }

    public function naovalidada($id)
    {
        $consignataria = Consignataria::find($id);
        $contratos = $consignataria->contratos->where('status', '!=', 1);
        $title = 'Não Validadas';
        return view('consignatarias.show_contratos_geral', compact('contratos', 'consignataria','title'));

    }

    public function sem_pessoa($id)
    {

        $consignataria = Consignataria::find($id);
        $contratos = $consignataria->contratos->where('pessoa_existente', 0)->whereNotNull('pessoa_existente');
        $title = "Sem Pessoa";
        return view('consignatarias.show_contratos_geral', compact('contratos', 'consignataria', 'title'));

    }

    public function sem_servidor($id)
    {
        $consignataria = Consignataria::find($id);
        $contratos = $consignataria->contratos->where('servidor_existente', 0)->whereNotNull('servidor_existente');
        $title = "Sem Servidor(Matricula)";
        return view('consignatarias.show_contratos_geral', compact('contratos', 'consignataria', 'title'));
    }

    public function semelhante($id)
    {
        $consignataria = Consignataria::find($id);

        $contratos = $consignataria->contratos->whereNotNull('contrato_id');
        $title = "Semelhantes";
        return view('consignatarias.show_contratos_semelhante', compact('contratos', 'consignataria', 'title'));
    }

    public function sem_banco($id)
    {
        $consignataria = Consignataria::find($id);
        $contratos = $consignataria->contratos->where('contrato', 0);
        $title = "não encontrados no Arquivo do banco";
        return view('consignatarias.show_contratos_geral', compact('contratos', 'consignataria', 'title'));

    }

    public function novo_contrato($id)
    {
        $consignataria = Consignataria::find($id);
        $contratos = $consignataria->contratos->where('n_parcela_referencia', 1);
        $title = "Renegociação ou novo contrato";
        return view('consignatarias.show_contratos_geral', compact('contratos', 'consignataria', 'title'));
    }
}
