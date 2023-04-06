<?php

namespace App\Http\Controllers;

use App\Imports\ContratoBancoImport;
use App\Imports\ContratoImport;
use App\Imports\PessoaImport;
use App\Models\ConsignanteMaster;
use App\Models\Contrato;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consignantes_masters = ConsignanteMaster::all();
        return view('contratos.index',compact('consignantes_masters'));
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
    public function show(Contrato $contrato)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contrato $contrato)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contrato $contrato)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contrato $contrato)
    {
        //
    }

    public function import(Request $request)
    {
        $file = $request->file('file');

        //dd($request->all());

        //dd($request->valor_parcela);

        $contratoImport = new ContratoImport(
            $request->cpf,
            $request->matricula,
            $request->nm_consignataria,
            $request->valor_parcela,
            $request->parcela_atual,
            $request->cod_verba,
            $request->prazo_total,
            $request->n_contrato,
            $request->data_efetivacao,
            $request->data_primeiro_desconto,
            $request->data_ultimo_desconto,
            $request->valor_liberado,
            $request->valor_financiado,
            $request->total_saldo_devedor,

        );

        //dd($pessoaImport);
        // Chame o método import e passe a instância de PessoaImport como argumento
        Excel::import($contratoImport, $file,null,\Maatwebsite\Excel\Excel::CSV);
    }

    public function importBanco(Request $request)
    {
        $file = $request->file('file');

        //dd($request->all());


        $contratoImport = new ContratoBancoImport(
            $request->cpf,
            $request->nome,
            $request->matricula,
            $request->valor_parcela,
            $request->parcela_atual,
            $request->cod_verba,
            $request->prazo_total,
            $request->n_contrato,
            $request->data_efetivacao,
            $request->data_primeiro_desconto,
            $request->data_ultimo_desconto,
            $request->valor_liberado,
            $request->valor_financiado,
            $request->total_saldo_devedor,
            $request->consignataria_id,
            $request->prazo_remanescente
        );


        // Chame o método import e passe a instância de PessoaImport como argumento
        Excel::import($contratoImport, $file,null,\Maatwebsite\Excel\Excel::CSV);
    }

    public function modal($id)
    {
        $contrato = Contrato::find($id); // Obtem o contrato pelo ID

        $servidors = $contrato->servidor->pessoa->servidors->pluck('id')->toArray(); // Obtém os IDs de todos os servidores da pessoa associada ao contrato
        $valor_parcela = $contrato->valor_parcela;

        $contrato_semelhante = Contrato::whereIn('servidor_id', $servidors)
            ->whereBetween('valor_parcela', [$valor_parcela - 1, $valor_parcela + 1])
            ->where('id', '!=', $contrato->id)
            ->with('servidor.pessoa')
            ->first(); // Obtém o primeiro contrato que pertence a um dos servidores da pessoa associada ao contrato atual e que tem o mesmo valor de parcela, mas não é o contrato atual

        return [$contrato_semelhante,$contrato];

    }


}
