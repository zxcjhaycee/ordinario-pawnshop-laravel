<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\CustomerAttachment;
use DataTables;

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
                'email' => 'required|email',
                'contact_number' => 'required|digits:11',
                'alternate_number' => 'required|digits:11',
                'present_address' => 'required',
                'present_area' => 'required',
                'present_city' => 'required',
                'present_zip_code' => 'required|numeric',
                'permanent_address' => 'required',
                'permanent_area' => 'required',
                'permanent_city' => 'required',
                'permanent_zip_code' => 'required|numeric',
                'attachment.*' => 'required|mimes:jpeg,bmp,jpeg,jpg,png',
                'attachment_number.*' => 'required',
                // 'attachment_type' => 'required||exists:App\Attachment,id'
                'attachment_type.*' => 'required'
            ]);
            try{
               \DB::beginTransaction();
                $request['birthdate'] = date('Y-m-d', strtotime($request['birthdate']));
                $request['attachment_id'] = $request['attachment_type'];
                $request['number'] = $request['attachment_number'];
                $attachment_data = array();
                $customer = Customer::create($request->all());
                foreach($request['attachment_id'] as $key => $value){
                    $attachment_path = $key.'_'.time().'.'.$request->attachment[$key]->extension(); 
                    $request->attachment[$key]->move(public_path('attachment'), $attachment_path);
        
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
            'first_name' => 'required|alpha',
            'middle_name' => '',
            'last_name' => 'required|alpha',
            'birthdate' => 'required|date',
            'suffix' => '',
            'sex' => 'required|in:male,female',
            'civil_status' => 'required|in:single,married,seperated,divorced,widowed',
            'email' => 'required|email',
            'contact_number' => 'required|digits:11',
            'alternate_number' => 'required|digits:11',
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

        // dd($data);
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

        return response()->json(['status' => 'The customer was successfully updated!', 'success' => true]);


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
        return response()->json(['status' => 'The attachment was successfully removed!', 'success' => true]);

    }
}
