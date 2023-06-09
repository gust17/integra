<?php

namespace App\Http\Controllers;

use App\Models\Consignante;
use App\Models\ConsignanteMaster;
use Illuminate\Http\Request;

class ConsignanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consignantes_masters = ConsignanteMaster::all();
        $consignantes = Consignante::all();
        return view('consignante.index',compact('consignantes','consignantes_masters'));
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

        Consignante::create($request->all());
        return redirect()->back()->with('success', 'Consignante criado com sucesso');;
    }

    /**
     * Display the specified resource.
     */
    public function show(Consignante $consignante)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consignante $consignante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consignante $consignante)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consignante $consignante)
    {
        //
    }
}
