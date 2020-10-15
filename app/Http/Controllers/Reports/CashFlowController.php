<?php

namespace App\Http\Controllers\Reports;

use App\Branch;
use App\CashFlow;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CashFlowController extends Controller
{
    //

    public function index(Request $request){
        // $branch = Branch::all();
        $branch = Branch::all();
        $date = $request['date'];
        $branch_selected = $request['branch_id'];
        $cashFlow = CashFlow::with('cashFlowsDetails')->where('date', $date)->where('branch_id', $branch_selected)->first();
        // dd($cashFlow->cashFlowsDetails->where('denomination', 'one_thousand')->all());
        return view('cashflow', compact('branch', 'branch_selected', 'date', 'cashFlow'));
    }

    public function store(Request $request, CashFlow $cashFlow){
        $check = User::where('auth_code', $request->auth_code)->find(\Auth::user()->id);
        if(!$check){
            throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }
        $cashFlow = $cashFlow->create([
            'branch_id' => $request['branch_id'],
            'date' => $request['date'],
            'grand_total' => $request['grand_total']
        ]);

        foreach($request->denomination as $key => $value){
            if(isset($value['count']) && $value['count'] != 0){
                $cashFlow->cashFlowsDetails()->create([
                    'denomination' => $key,
                    'count' => $value['count'],
                    'subtotal' => $value['subtotal']
                ]);
            }
        }

        return redirect()->back()->with('status', 'The Cashflow is succesfully submitted!');
    }

    public function submit(Request $request){

        return redirect()->route('cash_flow.index', ['branch_id' => $request->branch_id, 'date' => date('Y-m-d', strtotime($request->date))]);
    }

    public function update(Request $request, CashFlow $cashFlow){
        $check = User::where('auth_code', $request->auth_code)->find(\Auth::user()->id);
        if(!$check){
            throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }
        $cashFlow->update([
            'grand_total' => $request['grand_total']
        ]);

        foreach($request->denomination as $key => $value){
            if(isset($value['count']) && $value['count'] != 0){
                $cashFlow->cashFlowsDetails()->updateOrCreate(
                    ['denomination' => $key],
                    ['denomination' => $key,
                        'count' => $value['count'],
                        'subtotal' => $value['subtotal']]
                );
            }else{
                if($cashFlow->cashFlowsDetails->where('denomination', $key)->first() != null){
                    $cashFlow->cashFlowsDetails->where('denomination', $key)->first()->delete();
                }
            }
        }
        return redirect()->back()->with('status', 'The Cashflow is succesfully updated!');;

    }
}
