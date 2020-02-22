<?php

namespace App\Http\Controllers;

use App\PawnAuction;
use Illuminate\Http\Request;

class PawnAuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function pawnView()
    {
        //
        return view('sangla');
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
     * @param  \App\PawnAuction  $pawnAuction
     * @return \Illuminate\Http\Response
     */
    public function show(PawnAuction $pawnAuction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PawnAuction  $pawnAuction
     * @return \Illuminate\Http\Response
     */
    public function edit(PawnAuction $pawnAuction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PawnAuction  $pawnAuction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PawnAuction $pawnAuction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PawnAuction  $pawnAuction
     * @return \Illuminate\Http\Response
     */
    public function destroy(PawnAuction $pawnAuction)
    {
        //
    }
}
