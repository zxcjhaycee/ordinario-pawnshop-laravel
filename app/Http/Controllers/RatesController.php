<?php

namespace App\Http\Controllers;

use App\Rate;
use App\Branch;
use App\Item_category;
use App\Item_type;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($branch_id = null, $item_category_id = null)
    {
        // dd("Hello");
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
        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        // dd($check);
        if(!$check){
            throw ValidationException::withMessages(['rate_auth_code_error' => 'The auth code is incorrect !']);
        }
        $rate->create($request->only('branch_id', 'item_type_id', 'karat', 'gram', 'regular_rate', 'special_rate', 'description'));
        return back()->with('rate_status', 'Rate has been created !');
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

    public function getItemType(Request $request){
         $item_type = Item_type::where('item_category_id', $request->id)->get();
        return response()->json($item_type);
    }

    public function getKarat(Request $request){
        $item_karat = Rate::where('item_type_id', $request->id)->where('branch_id', $request->branch_id)->orderBy('id')->get();
        // dd($request->id);
        return response()->json($item_karat);

    }


}
