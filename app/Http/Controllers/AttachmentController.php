<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attachment;
use DataTables;
use Illuminate\Validation\ValidationException;
use App\User;

class AttachmentController extends Controller
{
    //

    public function index(Request $request){
        if ($request->ajax()){
            $attachment = Attachment::withTrashed()->get();
                return Datatables::of($attachment)
                        ->addIndexColumn()
                        ->addColumn('status', function($row){
                            return isset($row->deleted_at) ? '<span class="text-danger">Inactive</span>' : '<span class="text-success">Active</span>';
                        })
                        ->addColumn('action', function($row){
                            $view_route = route('attachment.edit', ['id' => $row['id']]);
                            $icon = isset($row->deleted_at) ? 'restore' : 'delete';
                            $btn = '<a href="'.$view_route.'" class="btn btn-sm ordinario-button"><span class="material-icons">edit</span></a>';                           
                            $btn .= '<button type="button" class="btn btn-sm btn-warning remove" id="'.$row['id'].'" data-name="attachment"><span class="material-icons">'.$icon.'</span></button> ';
                            // $btn = '';
                                return $btn;
                        })
                        ->rawColumns(['action','status'])
                        ->make(true);
            }

        return view('attachment');
    }
    public function search(Request $request){
        // dd($request->search);
        $data = array();
        if(isset($request->search)){
            $attachments = Attachment::where('type', 'like', '%'.$request->search.'%')->take(10)->get();
            // dd($attachments[0]);
            if($attachments->count() > 0){
                foreach($attachments as $key => $value){
                    $data[] = array(
                        'id' => $value->id,
                        'text' => $value->type
                    );
                }
            }else{
                $data[] = array(
                    'id' => 'link',
                    'text' => 'Can\'t find? Add Attachment'
                );
            }

        }

        return response()->json($data);
    }

    public function create(){
        return view('form_attachment');
    }

    public function store(Request $request){
        // dd($request);
        $data = $request->validate([
            'type' => 'required'
        ]);

        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        if(!$check){
            throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }

        $attachment = Attachment::create($data);

        return back()->with(['status' => 'The attachment was succesfully created!']);
    }

    public function edit(Request $request){
        $data = Attachment::withTrashed()->where('id', $request->id)->first();

        return view('form_attachment', compact('data'));
    }

    public function update(Request $request){
        // dd($request);
        $data = $request->validate([
            'type' => 'required'
        ]);
        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        if(!$check){
            throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }
        

        $attachment = Attachment::withTrashed()->findOrFail($request->id)->update($data);

        return back()->with(['status' => 'The attachment was succesfully updated!']);

    }

    public function destroy(Request $request){

        $attachment = Attachment::withTrashed()->findOrFail($request->id);
        if($attachment->trashed()){
            $attachment->restore();
            $message = array('status' => 'The attachment was successfully restored!');
        }else{
            $attachment->delete();
            $message = array('status' => 'The attachment was successfully deleted!');
        }

        // return back()->with($message);
        return response()->json($message);
    }
}
