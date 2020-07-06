<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Other_charges;
use DataTables;

class OtherChargesController extends Controller
{
    //

    public function index(Request $request){

        if ($request->ajax()){
            $other_charges = Other_charges::withTrashed()->get();
            
                return Datatables::of($other_charges)
                        ->addIndexColumn()
                        ->addColumn('status', function($row){
                            return isset($row->deleted_at) ? '<span class="text-danger">Inactive</span>' : '<span class="text-success">Active</span>';
                        })
                        ->addColumn('action', function($row){
                            $view_route = route('other_charges.edit', ['id' => $row['id']]);
                            $icon = isset($row->deleted_at) ? 'restore' : 'delete';
                            $btn = '<a href="'.$view_route.'" class="btn btn-sm ordinario-button"><span class="material-icons">edit</span></a>';                           
                            $btn .= '<button type="button" class="btn btn-sm btn-warning remove" id="'.$row['id'].'" data-name="other_charges"><span class="material-icons">'.$icon.'</span></button> ';
                            // $btn = '';
                                return $btn;
                        })
                        ->rawColumns(['action', 'status'])
                        ->make(true);
            }

        return view('other_charges');
    }
    public function show(Request $request){
        // dd($request);

    }

    public function create(){
        // dd("hellO!");
        return view('form_other_charges');
    }

    public function store(Request $request){
        // dd($request);
        $data = $request->validate([
            'charge_type' => 'required',
            'amount' => 'required|numeric'
        ]);

        $other_charges = Other_charges::create($data);

        return back()->with('status', 'The other_charges was successfully created');
    }

    public function edit(Request $request){
        $data = Other_charges::withTrashed()->findOrFail($request->id);
        
        return view('form_other_charges', compact('data'));
    }

    public function update(Request $request){
        // dd($request);
        $data = $request->validate([
            'charge_type' => 'required',
            'amount' => 'required|numeric'
        ]);

        $other_charges = Other_charges::withTrashed()->findOrFail($request->id)->update($data);

        return back()->with('status', 'The other_charges was successfully updated');

    }

    public function destroy(Request $request){


        $other_charges = Other_charges::withTrashed()->findOrFail($request->id);
        if($other_charges->trashed()){
            $other_charges->restore();
            $message = array('status' => 'The other_charges was successfully restored!');
        }else{
            $other_charges->delete();
            $message = array('status' => 'The other_charges was successfully deleted!');
        }

        return response()->json($message);

    }
    public function search(Request $request){
        // dd($request);
        $data = array();
        if(isset($request->search)){
            // $customer = Customer::where(\DB::raw('concat(first_name," ",last_name," ", suffix)'), 'like', '%'.$request->search.'%')->take(10)->get();
            $other_charges = Other_charges::where('charge_type', 'like', '%'.$request->search.'%')->take(10)->get();
            if($other_charges->count() > 0){
                foreach($other_charges as $key => $value){
                    $data[] = array(
                        'action' => 'amount',
                        'id' => $value->id,
                        'data-amount' => $value->amount,
                        'text' => $value->charge_type
                    );
                }
            }else{
                $data[] = array(
                    'link' => '/settings/other_charges/create',
                    'id' => 'link',
                    'text' => 'Can\'t find? Add Other Charges'
                );
            }

        return response()->json($data);
        }
    }
}
