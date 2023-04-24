<?php

namespace App\Http\Controllers;

use App\Models\ConsignanteMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsignanteMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consignante_masters = ConsignanteMaster::all();
        return view('consignante_master.index', compact('consignante_masters'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',

        ]);

        ConsignanteMaster::create($request->all());

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(ConsignanteMaster $consignanteMaster)
    {
        return view('consignante_master.show',compact('consignanteMaster'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConsignanteMaster $consignanteMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConsignanteMaster $consignanteMaster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConsignanteMaster $consignanteMaster)
    {
        //
    }
}
