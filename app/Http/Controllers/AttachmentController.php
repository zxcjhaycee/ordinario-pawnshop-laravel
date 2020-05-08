<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attachment;

class AttachmentController extends Controller
{
    //

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

        $attachment = Attachment::create($data);

        return back()->with(['status' => 'The attachment was succesfully created!']);
    }
}
