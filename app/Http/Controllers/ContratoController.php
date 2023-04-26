<?php

namespace App\Http\Controllers;

use App\Imports\ContratoBancoImport;
use App\Imports\ContratoImport;
use App\Imports\PessoaImport;
use App\Models\Averbador;
use App\Models\ConsignanteMaster;
use App\Models\Consignataria;
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
        $consignatarias = Consignataria::all();
        $consignantes_masters = ConsignanteMaster::all();
        return view('contratos.index', compact('consignantes_masters', 'consignatarias'));
    }

    public function banco_import()
    {
        $consignantes_masters = ConsignanteMaster::all();
        $consignatarias = Consignataria::all();

        return view('contratos.import', compact('consignatarias', 'consignantes_masters'));
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
        $contratoImport = new ContratoImport(
            $request->cpf,
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
            $request->averbador_id,
            $request->prazo_remanescente,
            $request->consignante_id,
            $request->inicio,
            $request->obs,
            $request->nome,
            $request->consignantaria_id,
        );

        Excel::import($contratoImport, $file, null, \Maatwebsite\Excel\Excel::CSV);

        return redirect()->back();
    }

    public function importBanco(Request $request)
    {
        $file = $request->file('file');

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
            $request->prazo_remanescente,
            $request->consignante_id,
            $request->averbador_id,
            $request->inicio
        );


        Excel::import($contratoImport, $file, null, \Maatwebsite\Excel\Excel::CSV, [
            'ignoreEmpty' => true,
            'ignoreEmptyRowAndColumn' => true,
            'headingRow' => 66

        ]);
        return redirect()->back();
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

        return [$contrato_semelhante, $contrato];

    }

    public function contratos()
    {
        $consignantes_Masters = ConsignanteMaster::all();
        return view('consignante_master.contratos', compact('consignantes_Masters'));
    }

    public function consulta(Request $request)
    {
        $validated = $request->validate([
            'consignante_master_id' => 'required',
            'consignante_id' => 'required',
            'averbador_id' => 'required',
        ]);
        //dd($request->all());
        return redirect(url("consulta/$request->consignante_master_id/$request->consignante_id/$request->averbador_id"));
    }

    public function repostaConsulta($master,
                                    $consignante,
                                    $averbador)
    {
        $consignatarias = Consignataria::all();
        $averbador = Averbador::find($averbador);

        return view('contratos.showbancos', compact('consignatarias', 'averbador'));
    }


}
