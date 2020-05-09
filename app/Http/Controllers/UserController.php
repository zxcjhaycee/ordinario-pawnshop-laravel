<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Branch;
use DataTables;
class UserController extends Controller
{
    //
    public function index(Request $request){

        if ($request->ajax()){
            $user = User::with('branch')->withTrashed()->get();
            // dd($user);
                return Datatables::of($user)
                        ->addIndexColumn()
                        ->addColumn('full_name', function($row){
                            return $row->first_name.' '.$row->last_name;
                        })
                        ->addColumn('status', function($row){
                            return isset($row->deleted_at) ? '<span class="text-danger">Inactive</span>' : '<span class="text-success">Active</span>';
                        })
                        ->editColumn('branch', function ($data) {
                            return $data->branch->branch;
                        })
                        ->editColumn('access', function ($data) {
                            return ucwords($data->access);
                        })
                        ->addColumn('action', function($row){
                            $view_route = route('user.edit', ['id' => $row['id']]);
                            $icon = isset($row->deleted_at) ? 'restore' : 'delete';
                            $btn = '<a href="'.$view_route.'" class="btn btn-sm ordinario-button"><span class="material-icons">edit</span></a>';                           
                            $btn .= '<button type="button" class="btn btn-sm btn-warning remove" id="'.$row['id'].'" data-name="user"><span class="material-icons">'.$icon.'</span></button> ';
                            // $btn = '';
                                return $btn;
                        })
                        ->rawColumns(['action','status'])
                        ->make(true);
            }
        return view('user');
        
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

        // return back()->with($message);
        return response()->json($message);
    }
}
