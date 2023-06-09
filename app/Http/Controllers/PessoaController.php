<?php

namespace App\Http\Controllers;

use App\Imports\PessoaImport;
use App\Models\ConsignanteMaster;
use App\Models\Pessoa;
use App\Models\Servidor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PessoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pessoas = Pessoa::withCount('servidors')->get();
        return view('pessoa.index', compact('pessoas'));
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
        $validatedData = $request->validate([
            'nome' => 'required',
            'cpf' => 'required|unique:pessoas', // supondo que você utiliza o pacote "brazucas/cpf"
            'matricula' => 'required',
            // supondo que você quer que todos os valores do array sejam numéricos
        ]);


        $pessoa = Pessoa::create($request->all());

        $matriculas = explode(',', $request->matricula);
        foreach ($matriculas as $matricula) {
            Servidor::create([
                    'pessoa_id' => $pessoa->id,
                    'matricula' => $matricula
                ]
            );
        }

        return redirect()->back();
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Pessoa $pessoa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pessoa $pessoa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pessoa $pessoa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pessoa $pessoa)
    {
        //
    }

    public function create_import(Request $request)
    {
        $consignantes_masters = ConsignanteMaster::all();
        return view('pessoa.import', compact('consignantes_masters'));

        // return redirect()->back();
    }

    public function import(Request $request)
    {
        $file = $request->file('file');

        //dd($request->all());

        $pessoaImport = new PessoaImport($request->nome, $request->cpf, $request->matricula, $request->consignante_id);


        Excel::import($pessoaImport, $file);

        return redirect()->back();
    }

    public function pesquisa(Request $request)
    {


        if ($request->has('cpf')) {
            $pessoas = Pessoa::where('cpf', $request->cpf)->withCount('servidors')->get();
        } elseif ($request->has('q')) {
            $pessoas = Pessoa::where('name', 'like', '%' . $request->q . '%')->withCount('servidors')->get();
        } else {
            $pessoas = collect();
        }

        return response()->json($pessoas);
    }


}
