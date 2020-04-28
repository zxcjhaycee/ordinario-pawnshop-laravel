<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Branch;

class UserController extends Controller
{
    //
    public function index(){

        $user = User::with('branch')->withTrashed()->get();
        return view('user', compact('user'));
        
    }

    public function create(){

        $branch = Branch::all();

        return view('form_user', compact('branch'));
    }

    public function store(Request $request, User $user){

        $data = $request->validate([
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:5',
            'branch_id' => 'required|exists:App\Branch,id',
            'access' => 'required|in:admin,user',
            'auth_code' => 'required|min:3|alpha_num',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user->create($data);
        
        return back()->with('status', 'The user was successfully created');
    }

    public function edit(Request $request){
        $data = User::where('id', $request->id)->first();
        $branch = Branch::all();

        return view('form_user', compact('data', 'branch'));        
    }

    public function update(Request $request){
        $data = $request->validate([
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'password' => 'required|min:5',
            'username' => 'required|unique:users,username,'.$request->id,
            'branch_id' => 'required|exists:App\Branch,id',
            'access' => 'required|in:admin,user',
            'auth_code' => 'required|min:3|alpha_num',
        ]);
        $data['password'] = Hash::make($data['password']);

        $user = User::findOrfail($request->id)->update($data);

        return back()->with('status', 'The user was successfully updated');

    }

    public function destroy(Request $request){
        $user = User::withTrashed()->findOrFail($request->id);
        if($user->trashed()){
            $user->restore();
            $message = array('status' => 'The user was successfully restored!');
        }else{
            $user->delete();
            $message = array('status' => 'The user was successfully deleted!');
        }

        return back()->with($message);
    }
}
