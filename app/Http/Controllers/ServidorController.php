<?php

namespace App\Http\Controllers;

use App\Imports\PessoaImport;
use App\Imports\ServidorImport;
use App\Models\ConsignanteMaster;
use App\Models\Servidor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ServidorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Servidor $servidor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Servidor $servidor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Servidor $servidor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servidor $servidor)
    {
        //
    }

    public function create_import(Request $request)
    {
        $consignantes_masters = ConsignanteMaster::all();
        return view('servidor.import', compact('consignantes_masters'));

        // return redirect()->back();
    }

    public function import(Request $request)
    {
        $file = $request->file('file');

       // dd($request->all());

        $servidorImport = new ServidorImport($request->nome, $request->cpf, $request->matricula, $request->consignante_id);


        Excel::import($servidorImport, $file);

        return redirect()->back();
    }
}
