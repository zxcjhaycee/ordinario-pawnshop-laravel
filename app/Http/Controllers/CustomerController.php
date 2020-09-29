<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Customer;
use App\CustomerAttachment;
use DataTables;
use App\User;

class CustomerController extends Controller
{
    //

    public function index(Request $request){
        // $customer = Customer::withTrashed()->get();
        // return view('customer', compact('customer'));
        if ($request->ajax()){
        $customer = Customer::withTrashed()->get();
        // $customer = Customer::all();
            return Datatables::of($customer)
                    ->addIndexColumn()
                    ->addColumn('full_name', function($row){
                        return $row->first_name.' '.$row->last_name.' '.$row->suffix;
                    })
                    ->editColumn('birthdate', function ($data) {
                        return date('m/d/Y', strtotime($data->birthdate));
                    })
                    ->editColumn('sex', function ($data) {
                        return ucwords($data->sex);
                    })
                    ->addColumn('action', function($row){
                        // dd($row->deleted_at);
                        $view_route = route('customer.show', ['id' => $row['id']]);
                        $icon = isset($row->deleted_at) ? 'restore' : 'delete';
                        $btn = '<a href="'.$view_route.'" class="btn btn-responsive ordinario-button btn-sm"><span class="material-icons">view_list</span></a>'; 
                        $btn .= '<button type="button" class="btn btn-sm btn-warning remove" id="'.$row['id'].'" data-name="customer"><span class="material-icons">'.$icon.'</span></button> ';

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('customer');
    }

    public function create(){

        return view('form_customer');
    }

    public function show(Request $request){
        
        // $customer = Customer::find($request->id);
        $customer = Customer::withTrashed()->with('attachments')->find($request->id);

        return view('view_customer', compact('customer'));

    }

    public function store(Request $request){
        // dd($request);
        $request->validate([
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'birthdate' => 'required|date',
            'sex' => 'required|in:male,female',
            'civil_status' => 'required|in:single,married,seperated,divorced,widowed',
            'email' => 'email',
            'contact_number' => 'required',
            // 'alternate_number' => 'required',
            'present_address' => 'required',
            'present_area' => 'required',
            'present_city' => 'required',
            'present_zip_code' => 'required|numeric',
            'permanent_address' => 'required',
            'permanent_area' => 'required',
            'permanent_city' => 'required',
            'permanent_zip_code' => 'required|numeric',
            // 'attachment.*' => 'required|mimes:jpeg,bmp,jpeg,jpg,png',
            'attachment_number.*' => 'required',
            // 'attachment_type' => 'required||exists:App\Attachment,id'
            'attachment_type.*' => 'required'
        ]);
        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        // dd($check);
        if(!$check){
            return response()->json(['status' => 'The auth code is incorrect !', 'auth_code_error' => false]);
            // throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }

            try{
               \DB::beginTransaction();
                $request['birthdate'] = date('Y-m-d', strtotime($request['birthdate']));
                $request['attachment_id'] = $request['attachment_type'];
                $request['number'] = $request['attachment_number'];
                $attachment_data = array();
                $customer = Customer::create($request->only('first_name', 'middle_name', 'last_name', 'suffix', 'birthdate', 'sex', 'civil_status', 'email',
                                            'contact_number', 'alternate_number', 'present_address', 'present_address_two', 'present_area', 'present_city', 'present_zip_code',
                                                'permanent_address', 'permanent_address_two', 'permanent_area', 'permanent_city', 'permanent_zip_code'));
                $attachment_path = '';
                foreach($request['attachment_id'] as $key => $value){
                    if(isset($request['attachment'][$key])){
                        $attachment_path = $key.'_'.time().'.'.$request->attachment[$key]->extension(); 
                        $request->attachment[$key]->move(public_path('attachment'), $attachment_path);
                    }
        
                    $attachment_data[] = array(
                        'customer_id' => $customer->id,
                        'attachment_id' => $value,
                        'number' => $request['number'][$key],
                        'path' => $attachment_path,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s')
                    );
                }
        
                $attachment = CustomerAttachment::insert($attachment_data);
                \DB::commit();
                $request->session()->flash('status', 'The customer was successfully created!');
                return response()->json(['status' => 'The customer was successfully created!', 'success' => true]);
            }catch(\PDOException $e){
                \DB::rollBack();
                //  dd($e->getMessage());
                 return response()->json(['status' => $e->getMessage(), 'success' => false]);

            }
           
    
    }

    public function edit(Request $request){

        
        $data = Customer::find($request->id);

        return view('form_customer', compact('data'));
    }

    public function update(Request $request){
       $data = $request->validate([
            'first_name' => 'required',
            'middle_name' => '',
            'last_name' => 'required',
            'birthdate' => 'required|date',
            'suffix' => '',
            'sex' => 'required|in:male,female',
            'civil_status' => 'required|in:single,married,seperated,divorced,widowed',
            'email' => 'required|email',
            'contact_number' => 'required',
            'alternate_number' => 'required',
            'present_address' => 'required',
            'present_address_two' => '',
            'present_area' => 'required',
            'present_city' => 'required',
            'present_zip_code' => 'required|numeric',
            'permanent_address' => 'required',
            'permanent_address_two' => 'required',
            'permanent_area' => 'required',
            'permanent_city' => 'required',
            'permanent_zip_code' => 'required|numeric',
            'attachment.*' => 'required|mimes:jpeg,bmp,jpeg,jpg,png',
            'attachment_number.*' => 'required',
            // 'attachment_type.*' => 'required||exists:App\Attachment,id'
            'attachment_type.*' => 'required'
        ]);

        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        // dd($check);
        if(!$check){
            return response()->json(['status' => 'The auth code is incorrect !', 'auth_code_error' => false]);
            // throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }
        // dd($data);
        try{
            \DB::beginTransaction();

            $data['birthdate'] = date('Y-m-d', strtotime($data['birthdate']));
            $customer = Customer::findOrfail($request->id)->update($data);
            if(isset($data['attachment_type']) && isset($data['attachment_number']) && isset($data['attachment'])){
    
                $data['attachment_id'] = $data['attachment_type'];
                $data['number'] = $data['attachment_number'];
                $attachment_data = array();
                foreach($data['attachment_id'] as $key => $value){
                    $attachment_path = $key.'_'.time().'.'.$request->attachment[$key]->extension(); 
                    $request->attachment[$key]->move(public_path('attachment'), $attachment_path);
        
                    $attachment_data[] = array(
                        'customer_id' => $request->id,
                        'attachment_id' => $value,
                        'number' => $data['number'][$key],
                        'path' => $attachment_path,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s')
                    );
                }
    
                $attachment = CustomerAttachment::insert($attachment_data);
            }            
            \DB::commit();
            return response()->json(['status' => 'The customer was successfully updated!', 'success' => true]);
        }catch(\PDOException $e){
            \DB::rollBack();
            //  dd($e->getMessage());
             return response()->json(['status' => $e->getMessage(), 'success' => false]);
        }




    }

    public function destroy(Request $request){

        $customer = Customer::withTrashed()->findOrFail($request->id);
        if($customer->trashed()){
            $customer->restore();
            $message = array('status' => 'The customer was successfully restored!');
        }else{
            $customer->delete();
            $message = array('status' => 'The customer was successfully deleted!');
        }

        return response()->json($message);

    }
    public function remove_attachment(Request $request){

        $customerattacment = CustomerAttachment::findOrFail($request->id)->delete();
        return response()->json(['status' => 'The attachment was successfully removed!', 'success' => true, 'reload' => true]);

    }

    public function search(Request $request){
        $data = array();
        if(isset($request->search)){
            $customer = Customer::where(\DB::raw('concat(first_name," ",last_name)'), 'like', '%'.$request->search.'%')->take(10)->get();
            // dd($attachments[0]);
            if($customer->count() > 0){
                foreach($customer as $key => $value){
                    $data[] = array(
                        'action' => 'attachment',
                        'id' => $value->id,
                        'text' => $value->first_name." ".$value->last_name." ".$value->suffix
                    );
                }
            }else{
                $data[] = array(
                    'link' => '/settings/customer/create',
                    'id' => 'link',
                    'text' => 'Can\'t find? Add Customer'
                );
            }

        }

        return response()->json($data);
    }

    public function search_attachment(Request $request){
        $attachment = Customer::find($request->customer_id)->attachments()->orderBy('type')->get();
        $data = array();
        foreach($attachment as $key => $value){
            // echo $value->type;
            $data['type'][] = $value->type;
            $data['id'][] = $value->id;
            $data['attachment_number'][] = $value->pivot->number;


        }
        
        return response()->json($data);
    }
}
