<?php

namespace App\Http\Controllers;

use App\Ordinario;
use Illuminate\Http\Request;

class OrdinarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ordinario  $ordinario
     * @return \Illuminate\Http\Response
     */
    public function show(Ordinario $ordinario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ordinario  $ordinario
     * @return \Illuminate\Http\Response
     */
    public function edit(Ordinario $ordinario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ordinario  $ordinario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ordinario $ordinario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ordinario  $ordinario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordinario $ordinario)
    {
        //
    }
}
