<?php

namespace App\Http\Controllers;

use App\Models\Consignataria;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $consignatarias = Consignataria::all();

        $naovalidadas = $consignatarias->filter(function ($consignataria) {
            return $consignataria->contratos->where('status', '!=', 1)->count() > 0;
        });


        return view('home',compact('naovalidadas'));
    }
}
