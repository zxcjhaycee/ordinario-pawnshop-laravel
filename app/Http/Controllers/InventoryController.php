<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory_item;
use App\Inventory;
use App\Ticket;
use App\Item_type;
use App\Rate;
use App\Inventory_other_charges;

use DataTables;

class InventoryController extends Controller
{
    //

    public function index(Request $request){
        // dd(1);
        if ($request->ajax()){
            $customer = Inventory::with('customer')->get();
            // dd($customer);
            // $customer = Customer::all();
                return Datatables::of($customer)
                        ->addIndexColumn()

                        ->editColumn('transaction_date', function ($data) {
                            return date('m/d/Y', strtotime($data->transaction_date));
                        })
                        ->editColumn('customer', function ($data) {
                            return $data->customer->first_name." ".$data->customer->last_name;
                        })
                        ->editColumn('net', function ($data) {
                            return number_format($data->net, 2);
                        })
                        ->editColumn('principal', function ($data) {
                            return number_format($data->principal, 2);
                        })

                        ->addColumn('ticket_date', function ($data) {
                            $maturity = date('m/d/Y', strtotime($data->maturity_date));
                            $expiration = date('m/d/Y', strtotime($data->expiration_date));
                            $auction = date('m/d/Y', strtotime($data->auction_date));

                            return $maturity."<br/>".$expiration."<br/>".$auction;
                        })
                        ->addColumn('action', function($row){
                            // dd($row->deleted_at);
                            $view_route = route('inventory.show', ['id' => $row['id']]);
                            $edit_route = route('inventory.edit', ['id' => $row['id']]);
                            // $icon = isset($row->deleted_at) ? 'restore' : 'delete';
                            $btn = '<a href="'.$view_route.'" class="btn btn-responsive ordinario-button btn-sm"><span class="material-icons">view_list</span></a>'; 
                            // $btn .= '<button type="button" class="btn btn-sm btn-warning remove" id="'.$row['id'].'" data-name="customer"><span class="material-icons">'.$icon.'</span></button> ';
                            $btn .= '<a href="'.$edit_route.'" class="btn btn-responsive btn-success btn-sm"><span class="material-icons">edit</span></a>'; 

                            return $btn;
                        })
                        ->rawColumns(['action', 'ticket_date'])
                        ->make(true);
            }
        return view('inventory');
    }

    public function create(){
        $ticket = Ticket::max('ticket_number') + 1;  
        $ticket_number = sprintf('%05d', $ticket);
        return view('form_inventory', compact('ticket_number'));
    }

    public function edit(Request $request){
        // $attachment = Inventory::with('pawnTickets')->find($request->id);
        $data = Inventory::with(['customer', 'pawnTickets','inventoryItems', 'pawnTickets.other_charges', 'pawnTickets.other_charges.inventory_other_charges', 'pawnTickets.attachment', 'customer.attachments'])
        ->find($request->id);

        $item_type_data = Item_type::where('item_category_id', 1)->get();
        foreach($data['inventoryItems'] as $key => $value){
            $rate_data[] = Rate::where('item_type_id', $value->item_type_id)->where('branch_id', \Auth::user()->branch_id)->orderBy('id')->get();
        }
        // dd($rate_data);
        // dd($data);
        // dd($data->pawnTickets->other_charges->sum('amount'));
        return view('form_inventory', compact('data', 'item_type_data', 'rate_data'));
    }
    public function show(Request $request){
        $id = $request->id;
        $ticket = Ticket::with('encoder', 'attachment')->where('inventory_id', $request->id)->get();
        // dd($ticket);
        $inventory = Inventory::with('customer', 'branch', 'item_category', 'item', 'item.item_type')->where('id', $request->id)->first();
        // dd($inventory);
        return view('view_inventory', compact('id', 'ticket', 'inventory'));
    }

    public function form_pawn(Request $request){
        $id = $request->id;
        return view('form_pawn', compact('id'));
    }

    public function store(Request $request){
        // dd($request);
        $request['is_special_rate'] = isset($request['is_special_rate']) ? $request['is_special_rate'] : 0;
        // dd($request);
        try{
            \DB::beginTransaction();
            $request['net'] = $request['net_proceeds'];
            $request['transaction_date'] = date('Y-m-d', strtotime($request['transaction_date']));
            $request['maturity_date'] = date('Y-m-d', strtotime($request['maturity_date']));
            $request['expiration_date'] = date('Y-m-d', strtotime($request['expiration_date']));
            $request['auction_date'] = date('Y-m-d', strtotime($request['auction_date']));

             $Inventory = Inventory::create($request->only('transaction_type','inventory_number','transaction_status','customer_id','branch_id','item_category_id','is_special_rate','net', 'ticket_number', 'principal', 'processed_by', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date', 'appraised_value'));
             $request['inventory_id'] = $Inventory->id;
             $ticket = Ticket::create($request->only('inventory_id', 'attachment_id','ticket_number','transaction_date','maturity_date','expiration_date','auction_date','processed_by', 'net', 'attachment_number'));
             $request['ticket_id'] = $ticket->id;

             foreach($request['item_type_id'] as $key => $value){
                 $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                 $request->image[$key]->move(public_path('item_image'), $image_path);
     
                 $inventory_item_data[] = array(
                     'inventory_id' => $request['inventory_id'],
                     'item_type_id' => $value,
                     'item_type_weight' => $request['item_type_weight'][$key],
                     'item_type_appraised_value' => $request['item_type_appraised_value'][$key],
                     'description' => $request['description'][$key],
                     'image' => $image_path,
                     'item_name' => $request['item_name'][$key],
                     'item_name_weight' => $request['item_name_weight'][$key],
                     'item_name_appraised_value' => $request['item_name_appraised_value'][$key],
                     'item_karat' => $request['item_karat'][$key],
                     'item_karat_weight' => $request['item_karat_weight'][$key],
                     'created_at'=>date('Y-m-d H:i:s'),
                     'updated_at'=> date('Y-m-d H:i:s')
                 );
             }
     
             $attachment = Inventory_item::insert($inventory_item_data);
             if(isset($request['other_charges_id'])){
                foreach($request['other_charges_id'] as $key => $value){
                        if($value != null){
                            $inventory_charges_data[] = array(
                                'inventory_id' => $request['inventory_id'],
                                'ticket_id' => $request['ticket_id'],
                                'other_charges_id' => $value,
                                'amount' => $request['other_charges_amount'][$key]
                            );
                            $inventory_charges = Inventory_other_charges::insert($inventory_charges_data);
                        }
                    }

             }


             \DB::commit();
             $request->session()->flash('status', 'The pawn was successfully created!');
             return response()->json(['success' => true, 'link' => route('pawn_print', $request['inventory_id']), 'create' => true]);
         }catch(\PDOException $e){
             \DB::rollBack();
             //  dd($e->getMessage());
              return response()->json(['status' => $e->getMessage(), 'success' => false]);

         }    
        
    }

    public function removeItem(Request $request){
        // dd($request->id);
        $inventory_item = Inventory_item::findOrFail($request->id)->delete();
        return response()->json(['status' => 'The item was successfully removed!', 'success' => true, 'inventory_item' => true]);

    }
    public function removeOtherCharges(Request $request){

        $inventory_other_charges = Inventory_other_charges::findOrFail($request->id)->delete();
        return response()->json(['status' => 'The other_charges was successfully removed!', 'success' => true, 'other_charges' => true]);
    }

    public function update(Request $request){
        // dd($request);
        // dd($request);
        try{
            \DB::beginTransaction();
            $request['net'] = $request['net_proceeds'];
            $request['transaction_date'] = date('Y-m-d', strtotime($request['transaction_date']));
            $request['maturity_date'] = date('Y-m-d', strtotime($request['maturity_date']));
            $request['expiration_date'] = date('Y-m-d', strtotime($request['expiration_date']));
            $request['auction_date'] = date('Y-m-d', strtotime($request['auction_date']));
            $request['is_special_rate'] = isset($request['is_special_rate']) ? $request['is_special_rate'] : 0;
             $inventory = Inventory::findOrfail($request->id)->update($request->only('transaction_type','inventory_number','transaction_status','customer_id','branch_id','item_category_id','is_special_rate','net', 'ticket_number', 'principal', 'processed_by', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date', 'appraised_value'));
             $ticket = Ticket::findOrfail($request->ticket_id)->update($request->only('inventory_id', 'attachment_id','ticket_number','transaction_date','maturity_date','expiration_date','auction_date','processed_by', 'net', 'attachment_number'));
            //  $request['ticket_id'] = $ticket->id;

             foreach($request['item_type_id'] as $key => $value){

                 if($request['image'][$key] != null){
                    $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                    $request->image[$key]->move(public_path('item_image'), $image_path);
                    // $image_array = 'image' => $image_path;
                 }

                 if(isset($request['inventory_item_id'][$key])){
                     $inventory_item_data = array(
                        'item_type_id' => $value,
                        'item_type_weight' => $request['item_type_weight'][$key],
                        'item_type_appraised_value' => $request['item_type_appraised_value'][$key],
                        'description' => $request['description'][$key],
                        'item_name' => $request['item_name'][$key],
                        'item_name_weight' => $request['item_name_weight'][$key],
                        'item_name_appraised_value' => $request['item_name_appraised_value'][$key],
                        'item_karat' => $request['item_karat'][$key],
                        'item_karat_weight' => $request['item_karat_weight'][$key],
                        'updated_at'=> date('Y-m-d H:i:s')
                     );
                    if($request['image'][$key] != null){
                        $inventory_item_data['image'] = $image_path;
                    }
                    $inventory_item_update = Inventory_item::findOrfail($request['inventory_item_id'][$key])
                            ->update($inventory_item_data);
                           
                 }else{
                        $inventory_item_data = array(
                            'inventory_id' => $request['id'],
                            'item_type_id' => $value,
                            'item_type_weight' => $request['item_type_weight'][$key],
                            'item_type_appraised_value' => $request['item_type_appraised_value'][$key],
                            'description' => $request['description'][$key],
                            'image' => $image_path,
                            'item_name' => $request['item_name'][$key],
                            'item_name_weight' => $request['item_name_weight'][$key],
                            'item_name_appraised_value' => $request['item_name_appraised_value'][$key],
                            'item_karat' => $request['item_karat'][$key],
                            'item_karat_weight' => $request['item_karat_weight'][$key],
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=> date('Y-m-d H:i:s')
                        );
                        $attachment = Inventory_item::insert($inventory_item_data);

                 }

             }
     
             if(isset($request['other_charges_id'])){
                foreach($request['other_charges_id'] as $key => $value){
                    if(isset($request['inventory_other_charges_id'][$key])){
                       $inventory_item_update = Inventory_other_charges::findOrfail($request['inventory_other_charges_id'][$key])
                               ->update(
                                   array(
                                       'inventory_id' => $request['id'],
                                       'ticket_id' => $request['ticket_id'],
                                       'other_charges_id' => $value,
                                       'amount' => $request['other_charges_amount'][$key]
                                   )
                               );
   
                    }else{
                        if($value != null){
                            $inventory_charges_data = array(
                                'inventory_id' => $request['id'],
                                'ticket_id' => $request['ticket_id'],
                                'other_charges_id' => $value,
                                'amount' => $request['other_charges_amount'][$key]
                            );
                            $inventory_charges = Inventory_other_charges::insert($inventory_charges_data);
                        }
                    }
   
                }
             }


             \DB::commit();
             $request->session()->flash('status', 'The pawn was successfully updated!');
             return response()->json(['success' => true]);
         }catch(\PDOException $e){
             \DB::rollBack();
             //  dd($e->getMessage());
              return response()->json(['status' => $e->getMessage(), 'success' => false]);

         }
    }

    public function showRenew(Request $request){
        // dd($request->id);
        $ticket = Ticket::max('ticket_number') + 1;  
        $ticket_number = sprintf('%05d', $ticket);
        $inventory = Inventory::with(['pawnTickets' => function($query){
            $query->where('transaction_type', '=', 'pawn');
        },'pawnTickets.encoder'])
        ->with(['item' => function ($query){
            $query->where('status', '=', 0);
        }, 'item.item_type'])
        ->with(['branch' , 'customer', 'customer.attachments', 'item_category'])
        ->find($request->id);
        // dd($inventory);
        $tickets = Ticket::where('inventory_id', $request->id)->get();
        $tickets_latest = Ticket::where('inventory_id', $request->id)->latest()->first();
        $id = $request->id;
        // dd($tickets_latest);
        return view('form_renew', compact('ticket_number', 'inventory', 'tickets', 'tickets_latest', 'id'));
    }

    public function submitRenew(Request $request){
        // dd($request);

        try{
            $request['transaction_date'] = date('Y-m-d', strtotime($request['transaction_date']));
            $request['maturity_date'] = date('Y-m-d', strtotime($request['maturity_date']));
            $request['expiration_date'] = date('Y-m-d', strtotime($request['expiration_date']));
            $request['auction_date'] = date('Y-m-d', strtotime($request['auction_date']));
            $request['authorized_representative'] = isset($request['authorized_representative']) ? $request['authorized_representative'] : 0;
            $request['interbranch'] = isset($request['interbranch']) ? $request['interbranch'] : 0;
            $request['interbranch_renewal'] = isset($request['interbranch_renewal']) ? $request['interbranch_renewal'] : 0;
            $request['discount_remarks'] = isset($request['remarks']) ? $request['remarks'] : "";

            $ticket = Ticket::create(
                $request->only('inventory_id', 'attachment_id','ticket_number','transaction_date','maturity_date','expiration_date','auction_date',
                'processed_by', 'net', 'attachment_number', 'authorized_representative', 'discount_remarks','discount', 'interest', 'interest_text',
                'penalty', 'penalty_text', 'advance_interest', 'transaction_type', 'interbranch', 'interbranch_renewal')
            );


            $inventory_data = Inventory::findOrfail($request->inventory_id)->first();
            $interest = $inventory_data['interest'];
            $penalty = $inventory_data['penalty'];
            $discount = $inventory_data['discount'];
            $net = $inventory_data['net'];
            $request['interest'] = $request['interest'] + $request['interest_text'] + $interest;
            $request['penalty'] = $request['penalty'] + $request['penalty_text'] + $penalty; 
            $request['discount'] = $request['discount'] + $discount; 
            $request['net'] = $request['net'] + $net;
            $inventory = Inventory::findOrfail($request->inventory_id)->update($request->only('interest','penalty','discount','net','ticket_number','transaction_date','maturity_date','expiration_date','auction_date'));

            if(isset($request['other_charges_id'])){
                foreach($request['other_charges_id'] as $key => $value){
                    if($value != null){
                        $inventory_charges_data = array(
                            'inventory_id' => $request['inventory_id'],
                            'ticket_id' => $ticket->id,
                            'other_charges_id' => $value,
                            'amount' => $request['other_charges_amount'][$key]
                        );
                        $inventory_charges = Inventory_other_charges::insert($inventory_charges_data);
                    }

                }
            }

            \DB::commit();
            $request->session()->flash('status', 'The renewal was successfully created!');
            return response()->json(['success' => true]);
        }catch(\PDOException $e){
            \DB::rollBack();
            //  dd($e->getMessage());
             return response()->json(['status' => $e->getMessage(), 'success' => false]);
        
        }

    }

    public function auction(Request $request){
        // dd($request);
        $inventory = Inventory::findOrfail($request->inventory_id)->update(
            array(
                'status' => 1
            )
        );
        $inventory_item = Inventory_item::where([
            ['inventory_id', '=', $request->inventory_id],
            ['status', '=', 0]
            ])->update(
                array(
                    'status' => 1
                )
            );

        return back()->with('status', 'The status was succesfully updated to Auction!');
    }

    public function showRedeem(Request $request){
        $ticket = Ticket::max('ticket_number') + 1;  
        $ticket_number = sprintf('%05d', $ticket);
        $inventory = Inventory::with(['pawnTickets' => function($query){
            $query->where('transaction_type', '=', 'pawn');
        }])
        ->with(['branch', 'pawnTickets.encoder', 'customer', 'customer.attachments', 'item_category', 'item', 'item.item_type', 'item_category'])
        ->find($request->id);
        // dd($inventory);
        $tickets = Ticket::where('inventory_id', $request->id)->get();
        // $tickets_latest = Ticket::where('inventory_id', $request->id)->latest()->first();
        $id = $request->id;
        // dd($tickets_latest);
        return view('form_redeem', compact('ticket_number', 'inventory', 'id', 'tickets'));
    }

    public function submitRedeem(Request $request){
        // dd($request);

        try{
            $request['transaction_date'] = date('Y-m-d', strtotime($request['transaction_date']));
            $request['authorized_representative'] = isset($request['authorized_representative']) ? $request['authorized_representative'] : 0;
            $request['interbranch'] = isset($request['interbranch']) ? $request['interbranch'] : 0;
            $request['discount_remarks'] = isset($request['remarks']) ? $request['remarks'] : "";
            $ticket = Ticket::create(
                $request->only('inventory_id', 'attachment_id','ticket_number','transaction_date',
                'processed_by', 'net', 'attachment_number', 'authorized_representative', 'discount_remarks','discount', 'interest', 'interest_text',
                'penalty', 'penalty_text', 'advance_interest', 'transaction_type', 'interbranch')
            );    
            $inventory_data = Inventory::findOrfail($request->inventory_id)->first();
            $interest = $inventory_data['interest'];
            $penalty = $inventory_data['penalty'];
            $discount = $inventory_data['discount'];
            $net = $inventory_data['net'];
            $request['interest'] = $request['interest'] + $request['interest_text'] + $interest;
            $request['penalty'] = $request['penalty'] + $request['penalty_text'] + $penalty; 
            $request['discount'] = $request['discount'] + $discount; 
            $request['net'] = $request['net'] + $net;
            $inventory_item_data = Inventory_item::where('inventory_id', $request->inventory_id)->get();
            $inventory_item_count = $inventory_item_data->count();
            $request_item_count = count($request['item']);
            $request['status'] = $request_item_count == $inventory_item_count ? 1 : 0;
            $inventory = Inventory::findOrfail($request->inventory_id)->update($request->only('interest','penalty','discount','net','ticket_number','status'));

            foreach($request['item'] as $key => $value){
                $inventory_item = Inventory_item::findOrfail($value)->update(array('status' => 2));
            }

            if(isset($request['other_charges_id'])){
                foreach($request['other_charges_id'] as $key => $value){
                    if($value != null){
                        $inventory_charges_data = array(
                            'inventory_id' => $request['inventory_id'],
                            'ticket_id' => $ticket->id,
                            'other_charges_id' => $value,
                            'amount' => $request['other_charges_amount'][$key]
                        );
                        $inventory_charges = Inventory_other_charges::insert($inventory_charges_data);
                    }

                }
            }

            \DB::commit();
            $request->session()->flash('status', 'The redeem was successfully created!');
            return response()->json(['success' => true]);


        }catch(\PDOException $e){
            \DB::rollBack();
            //  dd($e->getMessage());
             return response()->json(['status' => $e->getMessage(), 'success' => false]);
        
        } 

    }
}
