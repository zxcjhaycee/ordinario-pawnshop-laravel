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
        $other_charges['discount'] = 0;
        $other_charges['charges'] = 0;
        foreach($data->pawnTickets->other_charges as $key => $value){
            if($value->inventory_other_charges->charge_type == 'discount'){
                $other_charges['discount'] += $value->amount;
            }
            if($value->inventory_other_charges->charge_type == 'charges'){
                $other_charges['charges'] += $value->amount;
            }

        }
        // dd($rate_data);
        // dd($data);
        // dd($data->pawnTickets->other_charges->sum('amount'));
        return view('form_inventory', compact('data', 'item_type_data', 'rate_data', 'other_charges'));
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


       
        // dd($request);
        try{
            // dd(($request['item_type_id']));
            \DB::beginTransaction();
            $data = $request->validate([
                'transaction_type' => 'required',
                'inventory_number' => 'required',
                'transaction_status' => 'required',
                'customer_id' => 'required',
                'branch_id' => 'required',
                'item_category_id' => 'required',
                'net_proceeds' => 'required',
                'ticket_number' => ' required',
                'principal' => 'required',
                'processed_by' => 'required',
                'transaction_date' => 'required',
                'maturity_date' => 'required',
                'expiration_date' => 'required',
                'auction_date' => 'required', 
                'appraised_value' => 'required',
                'attachment_id' => 'required',
                'attachment_number' => 'required',
                'item_type_id.*' => 'required',
                'item_type_weight.*' => 'required',
                'description.*' => 'required',
                'item_type_appraised_value.*' => 'required',
                'image.*' => 'required',
                'item_name.*' => 'required',
                'item_name_weight.*' => 'required',
                'item_name_appraised_value.*' => 'required',
                'item_karat.*' => 'required',
                'item_karat_weight.*' => 'required',
                'item_category_id' => 'required',

            ]);
            $data['is_special_rate'] = isset($request['is_special_rate']) ? $request['is_special_rate'] : 0;
            $data['net'] = $request['net_proceeds'];
            $data['transaction_date'] = date('Y-m-d', strtotime($request['transaction_date']));
            $data['maturity_date'] = date('Y-m-d', strtotime($request['maturity_date']));
            $data['expiration_date'] = date('Y-m-d', strtotime($request['expiration_date']));
            $data['auction_date'] = date('Y-m-d', strtotime($request['auction_date']));
            $data['charges'] = $request['other_charges'];
            $data['discount'] = $request['discount'];
            $inventory_data = collect($data);
            $Inventory = Inventory::create($inventory_data->only('transaction_type','inventory_number','transaction_status','customer_id','branch_id','item_category_id','is_special_rate','net', 'ticket_number', 'principal', 'processed_by', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date', 'appraised_value', 'charges', 'discount')->toArray());
            $data['inventory_id'] = $Inventory->id;

            $ticket_data = collect($data);
            $ticket = Ticket::create($ticket_data->only('inventory_id', 'attachment_id','ticket_number','transaction_date','maturity_date','expiration_date','auction_date','processed_by', 'net', 'attachment_number', 'discount', 'charges')->toArray());
            $data['ticket_id'] = $ticket->id;

             foreach($data['item_type_id'] as $key => $value){
                 $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                 $request->image[$key]->move(public_path('item_image'), $image_path);
     
                 $inventory_item_data[] = array(
                     'inventory_id' => $data['inventory_id'],
                     'item_type_id' => $value,
                     'item_type_weight' => $data['item_type_weight'][$key],
                     'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                     'description' => $data['description'][$key],
                     'image' => $image_path,
                     'item_name' => $data['item_name'][$key],
                     'item_name_weight' => $data['item_name_weight'][$key],
                     'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                     'item_karat' => $data['item_karat'][$key],
                     'item_karat_weight' => $data['item_karat_weight'][$key],
                     'created_at'=>date('Y-m-d H:i:s'),
                     'updated_at'=> date('Y-m-d H:i:s')
                 );
             }
     
             $attachment = Inventory_item::insert($inventory_item_data);
             if(isset($request['other_charges_id'])){
                foreach($request['other_charges_id'] as $key => $value){
                        if($value != null){
                            $inventory_charges_data[] = array(
                                'inventory_id' => $data['inventory_id'],
                                'ticket_id' => $data['ticket_id'],
                                'other_charges_id' => $value,
                                'amount' => $request['other_charges_amount'][$key]
                            );
                            $inventory_charges = Inventory_other_charges::insert($inventory_charges_data);
                        }
                    }

             }


             \DB::commit();
             $request->session()->flash('status', 'The pawn was successfully created!');
            //  return response()->json(['success' => true, 'link' => route('pawn_print', $request['inventory_id']), 'create' => true]);
             return response()->json(['success' => true, 'create' => true, 'link' => route('inventory.show', $data['inventory_id'])]);
            
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
        $data = $request->validate([
            'transaction_type' => 'required',
            'inventory_number' => 'required',
            'transaction_status' => 'required',
            'customer_id' => 'required',
            'branch_id' => 'required',
            'item_category_id' => 'required',
            'net_proceeds' => 'required',
            'ticket_number' => ' required',
            'principal' => 'required',
            'processed_by' => 'required',
            'transaction_date' => 'required',
            'maturity_date' => 'required',
            'expiration_date' => 'required',
            'auction_date' => 'required', 
            'appraised_value' => 'required',
            'attachment_id' => 'required',
            'attachment_number' => 'required',
            'item_type_id.*' => 'required',
            'item_type_weight.*' => 'required',
            'description.*' => 'required',
            'item_type_appraised_value.*' => 'required',
            'item_name.*' => 'required',
            'item_name_weight.*' => 'required',
            'item_name_appraised_value.*' => 'required',
            'item_karat.*' => 'required',
            'item_karat_weight.*' => 'required',
            'item_category_id' => 'required',

        ]);
        try{
            \DB::beginTransaction();
            $data['net'] = $request['net_proceeds'];
            $data['transaction_date'] = date('Y-m-d', strtotime($request['transaction_date']));
            $data['maturity_date'] = date('Y-m-d', strtotime($request['maturity_date']));
            $data['expiration_date'] = date('Y-m-d', strtotime($request['expiration_date']));
            $data['auction_date'] = date('Y-m-d', strtotime($request['auction_date']));
            $data['is_special_rate'] = isset($request['is_special_rate']) ? $request['is_special_rate'] : 0;
            $inventory_data = collect($data);
            $inventory = Inventory::findOrfail($request->id)->update($inventory_data->only('transaction_type','inventory_number','transaction_status','customer_id','branch_id','item_category_id','is_special_rate','net', 'ticket_number', 'principal', 'processed_by', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date', 'appraised_value')->toArray());
            $ticket = Ticket::findOrfail($request->ticket_id)->update($inventory_data->only('inventory_id', 'attachment_id','ticket_number','transaction_date','maturity_date','expiration_date','auction_date','processed_by', 'net', 'attachment_number')->toArray());
            //  $request['ticket_id'] = $ticket->id;

             foreach($data['item_type_id'] as $key => $value){

                 if($request['image'][$key] != null){
                    $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                    $request->image[$key]->move(public_path('item_image'), $image_path);
                    // $image_array = 'image' => $image_path;
                 }

                 if(isset($request['inventory_item_id'][$key])){
                     $inventory_item_data = array(
                        'item_type_id' => $value,
                        'item_type_weight' => $data['item_type_weight'][$key],
                        'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                        'description' => $data['description'][$key],
                        'item_name' => $data['item_name'][$key],
                        'item_name_weight' => $data['item_name_weight'][$key],
                        'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                        'item_karat' => $data['item_karat'][$key],
                        'item_karat_weight' => $data['item_karat_weight'][$key],
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
                            'item_type_weight' => $data['item_type_weight'][$key],
                            'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                            'description' => $data['description'][$key],
                            'image' => $image_path,
                            'item_name' => $data['item_name'][$key],
                            'item_name_weight' => $data['item_name_weight'][$key],
                            'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                            'item_karat' => $data['item_karat'][$key],
                            'item_karat_weight' => $data['item_karat_weight'][$key],
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
        $data = $request->validate([
            'transaction_date' => 'required',
            'maturity_date' => 'required',
            'expiration_date' => 'required',
            'auction_date' => 'required',
            'attachment_number' => 'required',
            'attachment_id' => 'required'
        ]);
        try{
            $request['transaction_date'] = date('Y-m-d', strtotime($request['transaction_date']));
            $request['maturity_date'] = date('Y-m-d', strtotime($request['maturity_date']));
            $request['expiration_date'] = date('Y-m-d', strtotime($request['expiration_date']));
            $request['auction_date'] = date('Y-m-d', strtotime($request['auction_date']));
            $request['authorized_representative'] = isset($request['authorized_representative']) ? $request['authorized_representative'] : 0;
            $request['interbranch'] = isset($request['interbranch']) ? $request['interbranch'] : 0;
            $request['interbranch_renewal'] = isset($request['interbranch_renewal']) ? $request['interbranch_renewal'] : 0;
            $request['charges'] = $request['other_charges'];
            $ticket = Ticket::create(
                $request->only('inventory_id', 'attachment_id','ticket_number','transaction_date','maturity_date','expiration_date','auction_date',
                'processed_by', 'net', 'attachment_number', 'authorized_representative','discount', 'interest', 'interest_text',
                'penalty', 'penalty_text', 'advance_interest', 'transaction_type', 'interbranch', 'interbranch_renewal', 'charges')
            );
            // dd($ticket);

            $inventory_data = Inventory::findOrfail($request->inventory_id)->first();
            $interest = $inventory_data['interest'];
            $penalty = $inventory_data['penalty'];
            $discount = $inventory_data['discount'];
            $charges = $inventory_data['charges'];
            $net = $inventory_data['net'];
            $request['interest'] = $request['interest'] + $request['interest_text'] + $interest;
            $request['penalty'] = $request['penalty'] + $request['penalty_text'] + $penalty; 
            $request['discount'] = $request['discount'] + $discount; 
            $request['charges'] = $request['charges'] + $charges; 
            $request['net'] = $request['net'] + $net;
            $inventory = Inventory::findOrfail($request->inventory_id)->update($request->only('interest','penalty','discount','net','ticket_number','transaction_date','maturity_date','expiration_date','auction_date', 'charges'));

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
        }, 'item' => function($query){
            $query->where('status', '=', 0);
        }, 'item.item_type'])
        ->with(['branch', 'pawnTickets.encoder', 'customer', 'customer.attachments', 'item_category', 'item_category'])
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

    public function showUpdateRenew(Request $request){
        // dd($request);
        $ticket_id = $request['ticket_id'];
        $id = $request->id;
        $inventory = Inventory::with(['pawnTickets' => function($query){
                $query->where('transaction_type', '=', 'pawn');
            },'pawnTickets.encoder'])
        ->with(['item' => function ($query){
            $query->where('status', '=', 0);
        }, 'item.item_type'])
        ->with(['branch' , 'customer', 'customer.attachments', 'item_category'])
        ->find($request->id);
        $tickets_latest = Ticket::where('inventory_id', $request->id)->latest()->first();
        $tickets_current = Inventory::with(['pawnTickets' => function($query) use ($ticket_id){
            $query->where('id', $ticket_id);
        }, 'pawnTickets.other_charges', 'pawnTickets.other_charges.inventory_other_charges'])->find($request->id);
        // dd($tickets_current);
        // $tickets = Ticket::where([['inventory_id', $request->id], ['id', '!=', $ticket_id]])->get();
        $tickets = Ticket::where('inventory_id', $request->id)->whereNotIn('id', [$ticket_id])->get();
        // dd($tickets_current);
              return view('form_renew', compact('inventory', 'tickets', 'tickets_latest', 'tickets_current', 'id'));

    }
}
