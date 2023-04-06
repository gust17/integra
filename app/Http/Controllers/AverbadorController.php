<?php

namespace App\Http\Controllers;

use App\Models\Averbador;
use App\Models\ConsignanteMaster;
use Illuminate\Http\Request;

class AverbadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $averbadors = Averbador::all();
        $consignantes_masters = ConsignanteMaster::all();
        return view('averbador.index',compact('averbadors','consignantes_masters'));
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
        Averbador::create($request->all());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Averbador $averbador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Averbador $averbador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Averbador $averbador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Averbador $averbador)
    {
        //
    }
}
