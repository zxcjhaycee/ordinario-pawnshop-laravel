<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;

class BranchController extends Controller
{
    //

    public function index(){

        $branch = Branch::withTrashed()->get();
        return view('branch', compact('branch'));
    }

    public function store(Request $request){

        $data = $request->validate([
            'branch' => 'required'
        ]);

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
            'branch' => 'required'
        ]);
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

        return back()->with($message);
    }
}
