<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use DataTables;
use Illuminate\Validation\ValidationException;
use App\User;

class BranchController extends Controller
{
    //

    public function index(Request $request){

        if ($request->ajax()){
            $branch = Branch::withTrashed()->get();
                return Datatables::of($branch)
                        ->addIndexColumn()
                        ->addColumn('status', function($row){
                            return isset($row->deleted_at) ? '<span class="text-danger">Inactive</span>' : '<span class="text-success">Active</span>';
                        })
                        ->addColumn('action', function($row){
                            $view_route = route('branch.edit', ['id' => $row['id']]);
                            $icon = isset($row->deleted_at) ? 'restore' : 'delete';
                            $btn = '<a href="'.$view_route.'" class="btn btn-sm ordinario-button"><span class="material-icons">edit</span></a>';                           
                            $btn .= '<button type="button" class="btn btn-sm btn-warning remove" id="'.$row['id'].'" data-name="branch"><span class="material-icons">'.$icon.'</span></button> ';
                            // $btn = '';
                                return $btn;
                        })
                        ->rawColumns(['action','status'])
                        ->make(true);
            }

        return view('branch');
    }

    public function store(Request $request){

        $data = $request->validate([
            'branch' => 'required',
            'address' => 'required',
            'tin' => 'required',
            'contact_number' => 'required'
        ]);
        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        if(!$check){
            throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }
        $branch = Branch::create($data);

        return back()->with('status', 'The branch was successfully created');
    }

    public function create(){
        return view('form_branch');
    }

    public function edit(Request $request){
        $data = Branch::withTrashed()->where('id', $request->id)->first();

        return view('form_branch', compact('data'));

    }

    public function update(Request $request){
        $data = $request->validate([
            'branch' => 'required',
            'address' => 'required',
            'tin' => 'required',
            'contact_number' => 'required'
        ]);
        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        if(!$check){
            throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }
        $branch = Branch::withTrashed()->findOrfail($request->id)->update($data);

        return back()->with('status', 'The branch was successfully updated');


    }

    public function destroy(Request $request){

        $branch = Branch::withTrashed()->findOrFail($request->id);
        if($branch->trashed()){
            $branch->restore();
            $message = array('status' => 'The branch was successfully restored!');
        }else{
            $branch->delete();
            $message = array('status' => 'The branch was successfully deleted!');
        }

        // return back()->with($message);
        return response()->json($message);
    }

    public function updateUserBranch(Request $request){
        // dd($request);
        $updateUserBranch = User::find(\Auth::user()->id)->update(array('branch_id' => $request->id));
        
        return response()->json(array('status' => 'success'));

    }
}
