<?php

namespace App\Http\Controllers;

use App\Rate;
use App\Branch;
use App\Item_category;
use App\Item_type;
use Illuminate\Http\Request;

class RatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($branch_id = null, $item_category_id = null)
    {
        $rates = new Rate;
        $item_category = Item_category::all();
        $branch = Branch::all();
        $item_types = Item_type::where('item_category_id', '=', $item_category_id)->get();
        return view('settings.rates.index', compact('rates', 'item_category', 'branch', 'branch_id', 'item_category_id', 'item_types'));
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
    public function store(Request $request, Rate $rate)
    {
        $rate->create($request->only('branch_id', 'item_type_id', 'karat', 'gram', 'regular_rate', 'special_rate', 'description'));
        return back()->with('success', 'Rate has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function show(Rate $rate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function edit(Rate $rate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rate $rate)
    {

        $rate->update($request->validate([
            'gram' => 'required',
            'regular_rate' => 'required',
            'special_rate' => 'required',
        ]));
        return back()->with('success', 'Rate has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        $rate->delete();
        return back()->with('success', 'Rate has been deleted');
    }

    public function getBranchItem(Request $request)
    {
        $branchItem = $request->only('branch_id', 'item_category_id');
        return redirect('/settings/rates/' . $branchItem['branch_id'] . '/' . $branchItem['item_category_id']);
    }
}
