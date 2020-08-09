<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory_item;
use App\Inventory;
use App\Ticket;
use App\Ticket_item;
use App\Item_type;
use App\Rate;
use App\Inventory_other_charges;
use App\Payment;
use App\Pawn_ticket;

use DataTables;

class InventoryController extends Controller
{
    //

    public function index(Request $request){
        // dd(1);
        // $data = Inventory::with(['customer', 'pawnTickets'])->first();
        // dd($data);

        // $total_net =  $data->pawnTickets->pawn_parent->ticket_net
        // ->where(function($query){
        //         $query->where('inventory_id', 1)
        //             ->whereNotIn('transaction_type', ['pawn', 'repawn']);
        // })->sum('net');
        // dd($data->pawnTickets->pawn_parent);
        if ($request->ajax()){
            $customer = Inventory::with(['customer', 'pawnTickets'])->get();

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
                            // dd($data);
                            $net = $data->pawnTickets->whereIn('transaction_type', ['pawn', 'repawn'])->where('inventory_id', $data->id)->latest()->first();
                            // dd($net->where('inventory_id', $net->inventory_id)->first());
                            // dd($data->pawnTickets->pawn_parent_many);
                            // dd($net);

                            return number_format($net->net, 2);
                        })
                        ->editColumn('gross', function ($data) {
                            // return number_format($data->pawnTickets->net, 2);
                            // dd($data->pawnTickets->pawn_parent_many);
                            // $payment = 0;
                            // foreach($data->pawnTickets->pawn_parent_many as $key => $value){
                            //     $payment += isset($value->payment) ? $value->payment->amount : 0;
                            // }
                            $payment = isset($data->inventory_payment) ? $data->inventory_payment->where('inventory_id', $data->id)->sum('amount') : NULL;

                            // dd($data->id);
                            $total_net =  isset($data->pawnTickets->pawn_parent) ? $data->pawnTickets->pawn_parent->ticket_net
                            ->where(function($query) use ($data){
                                    $query->where('inventory_id', $data->id)
                                        ->where('status' , 0);
                            })->sum('net') : NULL;
                            // dd($total_net);
                            // dd($total_net - $payment);
                            return number_format($total_net - $payment ,2);
                        })
                        ->editColumn('principal', function ($data) {
                            $principal = $data->pawnTickets->whereIn('transaction_type', ['pawn', 'repawn'])->where('inventory_id', $data->id)->latest()->first();
                            return number_format($principal->principal, 2);
                        })

                        ->addColumn('ticket_date', function ($data) {
                            $maturity = isset($data->maturity_date) ? date('m/d/Y', strtotime($data->maturity_date)) : "";
                            $expiration = isset($data->expiration_date) ? date('m/d/Y', strtotime($data->expiration_date)) : "";
                            $auction = isset($data->auction_date) ? date('m/d/Y', strtotime($data->auction_date)) : "";

                            return $maturity."<br/>".$expiration."<br/>".$auction;
                        })
                        ->addColumn('action', function($row){
                            // dd($row->deleted_at);
                            // dd(Inventory::pawnTickets);
                            $count = $row->pawnTickets->where([['inventory_id', $row->id],['transaction_type', 'renew']])->count();

                            $view_route = route('inventory.show', ['id' => $row['id']]);
                            $edit_route = route('inventory.edit', ['id' => $row['id']]);
                            // $icon = isset($row->deleted_at) ? 'restore' : 'delete';
                            $btn = '<a href="'.$view_route.'" class="btn btn-responsive ordinario-button btn-sm"><span class="material-icons">view_list</span></a>'; 
                            // $btn .= '<button type="button" class="btn btn-sm btn-warning remove" id="'.$row['id'].'" data-name="customer"><span class="material-icons">'.$icon.'</span></button> ';
                            
                            $btn .= $count == 0 ? '<a href="'.$edit_route.'" class="btn btn-responsive btn-success btn-sm"><span class="material-icons">edit</span></a>' : ""; 

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
        $data = Inventory::with(['customer', 'pawnTickets','inventoryItems', 'pawnTickets.other_charges', 'pawnTickets.other_charges.inventory_other_charges', 'pawnTickets.attachment', 'customer.attachments', 'inventoryItems.ticket_item'])
        ->find($request->id);
        // dd($data->inventoryItems);
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
        $ticket = Ticket::with(['encoder', 'attachment', 'pawn_tickets', 'pawn_tickets.payment'])->where('inventory_id', $request->id)->get();
        // dd($ticket);
        $inventory = Inventory::with('customer', 'branch', 'item_category', 'inventoryItems', 'item.item_type')->findOrFail($request->id);
        $current_pawn = Ticket::whereIn('transaction_type', array('pawn', 'repawn'))->where([['status', '=', 0], ['inventory_id', $request->id]])->first();
        $ticket_update = Ticket::where('inventory_id', $request->id)->whereNotIn('transaction_type', array('pawn'))->latest()->first();
        // dd($ticket_update);
        // dd($inventory);
        // dd($current_pawn);
        return view('view_inventory', compact('id', 'ticket', 'inventory', 'current_pawn', 'ticket_update'));
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
            $Inventory = Inventory::create($inventory_data->only('inventory_number','transaction_status','customer_id','branch_id','item_category_id','ticket_number', 'processed_by', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date')->toArray());
            $data['inventory_id'] = $Inventory->id;

            $ticket_data = collect($data);
            $ticket = Ticket::create($ticket_data->only('transaction_type','inventory_id', 'attachment_id','ticket_number','transaction_date','maturity_date','expiration_date','auction_date','processed_by','appraised_value', 'principal', 'net', 'attachment_number', 'discount', 'charges', 'is_special_rate')->toArray());
            $data['ticket_id'] = $ticket->id;
            $request['pawn_id'] = $ticket->id;
            $pawn_ticket = Pawn_ticket::create(array('pawn_id' => $request['pawn_id'], 'ticket_id' => $request['pawn_id']));

             foreach($data['item_type_id'] as $key => $value){
                 $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                 $request->image[$key]->move(public_path('item_image'), $image_path);
     
                 $inventory_item_data = array(
                     'inventory_id' => $data['inventory_id'],
                     'item_type_id' => $value,
                     'item_type_weight' => $data['item_type_weight'][$key],
                     'description' => $data['description'][$key],
                     'image' => $image_path,
                     'item_name' => $data['item_name'][$key],
                     'item_name_weight' => $data['item_name_weight'][$key],
                     'item_karat' => $data['item_karat'][$key],
                     'item_karat_weight' => $data['item_karat_weight'][$key],
                     'created_at'=>date('Y-m-d H:i:s'),
                     'updated_at'=> date('Y-m-d H:i:s')
                 );
                 $inventory_item = Inventory_item::insertGetId($inventory_item_data);
                //  $data['inventory_item_id'] = $inventory_item->id;
                 $ticket_item = Ticket_item::insert(array(
                     'ticket_id' => $data['ticket_id'],
                     'inventory_item_id' => $inventory_item,
                     'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                     'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                     'created_at'=>date('Y-m-d H:i:s'),
                     'updated_at'=> date('Y-m-d H:i:s')
                 ));
             }
     
             if(isset($request['other_charges_id'])){
                foreach($request['other_charges_id'] as $key => $value){
                        if($value != null){
                            $inventory_charges_data = array(
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
        // dd($request['data_id']);
        $inventory_item = Inventory_item::findOrFail($request->id)->delete();
        $inventory_item = Ticket_item::findOrFail($request['data_id'])->delete();
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
            $data['charges'] = $request['other_charges'];
            $data['is_special_rate'] = isset($request['is_special_rate']) ? $request['is_special_rate'] : 0;
            $inventory_data = collect($data);
            $inventory = Inventory::findOrfail($request->id)->update($inventory_data->only('inventory_number','transaction_status','customer_id','branch_id','item_category_id', 'ticket_number', 'processed_by', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date')->toArray());
            $ticket = Ticket::findOrfail($request->ticket_id)->update($inventory_data->only('transaction_type','inventory_id', 'attachment_id','ticket_number','transaction_date','maturity_date','expiration_date','auction_date','processed_by', 'net', 'attachment_number', 'is_special_rate', 'appraised_value', 'principal', 'charges', 'discount')->toArray());
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
                        'description' => $data['description'][$key],
                        'item_name' => $data['item_name'][$key],
                        'item_name_weight' => $data['item_name_weight'][$key],
                        'item_karat' => $data['item_karat'][$key],
                        'item_karat_weight' => $data['item_karat_weight'][$key],
                        'updated_at'=> date('Y-m-d H:i:s')
                     );
                    if($request['image'][$key] != null){
                        $inventory_item_data['image'] = $image_path;
                    }
                    $inventory_item_update = Inventory_item::findOrfail($request['inventory_item_id'][$key])
                            ->update($inventory_item_data);
                    $ticket_item = Ticket_item::findOrFail($request['ticket_item_id'][$key])
                            ->update(array('item_type_appraised_value' => $data['item_type_appraised_value'][$key], 'item_name_appraised_value' => $data['item_name_appraised_value'][$key]));
                 }else{
                        $inventory_item_data = array(
                            'inventory_id' => $request['id'],
                            'item_type_id' => $value,
                            'item_type_weight' => $data['item_type_weight'][$key],
                            'description' => $data['description'][$key],
                            'image' => $image_path,
                            'item_name' => $data['item_name'][$key],
                            'item_name_weight' => $data['item_name_weight'][$key],
                            'item_karat' => $data['item_karat'][$key],
                            'item_karat_weight' => $data['item_karat_weight'][$key],
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=> date('Y-m-d H:i:s')
                        );
                        $inventory_item = Inventory_item::insertGetId($inventory_item_data);
                        $ticket_item = Ticket_item::insert(array(
                            'ticket_id' => $request['ticket_id'],
                            'inventory_item_id' => $inventory_item,
                            'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                            'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=> date('Y-m-d H:i:s')
                        ));
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
            if(isset($request['inventory_deleted_item'])){
                foreach($request['inventory_deleted_item'] as $key => $value){
                    $update_item = Inventory_item::find($value)->delete();
                    $update_ticket_item = Ticket_item::find($request['ticket_deleted_item'][$key])->delete();
                }     
            }
            if(isset($request['deleted_other_charges'])){
                foreach($request['deleted_other_charges'] as $key => $value){
                    $other_charges_update = Inventory_other_charges::find($value)->delete();
                    // dd($discount);
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
        // dd($request->pawn_id);
        $pawn_id = $request['pawn_id'];
        $ticket = Ticket::max('ticket_number') + 1;  
        $ticket_number = sprintf('%05d', $ticket);
        $inventory = Inventory::with(['pawnTickets' => function($query) use ($pawn_id){
            $query->where('id', $pawn_id);
        },'pawnTickets.encoder', 'pawnTickets.item_tickets', 'pawnTickets.item_tickets.inventory_items'])
        ->with(['branch' , 'customer', 'customer.attachments', 'item_category'])
        ->find($request->id);
        // dd($inventory);
        // $tickets = Ticket::with(['pawn_tickets', 'pawn_tickets.payment'])->where([['inventory_id', $request->id], ['transaction_type', '=', 'renew']])->get();
        $tickets = Ticket::with(['pawn_parent_many', 'pawn_parent_many.ticket_child' => function($query){
            $query->where('transaction_type', 'renew');
        }, 'pawn_parent_many.payment'])->find($pawn_id); // for renewal table
        // dd($tickets);
        $ticket_payment = Inventory::with(['pawnTickets','pawnTickets.pawn_parent', 'pawnTickets.pawn_parent_many.payment', 'pawnTickets.pawn_parent.ticket_net']) // payment and net
        ->find($request['id']);
        // dd($ticket_payment);
        // dd($request->id);
        // $total_net =  isset($ticket_payment->pawn_parent) ? $ticket_payment->pawn_parent->ticket_net
        // ->where(function($query) use ($request){
        //         $query->where('inventory_id', $request->id)
        //             ->whereNotIn('transaction_type', ['pawn', 'repawn']);
        // })
        // ->orWhere(function($query) use ($request){
        //     $query->where('inventory_id', $request->id)
        //     ->whereIn('transaction_type', ['pawn', 'repawn'])
        //     ->where('status', 1);
        // })->sum('net') : NULL;
        // $payment = 0;
        // foreach($ticket_payment->pawnTickets->pawn_parent_many as $key => $value){
        //     $payment += isset($value->payment) ? $value->payment->amount : 0;
        // }
        $payment = isset($ticket_payment->inventory_payment) ? $ticket_payment->inventory_payment->where('inventory_id', $request->id)->sum('amount') : NULL;
        $total_net =  isset($ticket_payment->pawnTickets->pawn_parent) ? $ticket_payment->pawnTickets->pawn_parent->ticket_net
        ->where(function($query) use ($request){
                $query->where('inventory_id', $request->id)
                    ->where('status' , 0);
        })->whereNotIn('transaction_type', ['pawn','repawn'])->sum('net') : NULL;
        // dd($total_net);
        // dd($payment);
        // $total_net = isset($ticket_payment->pawn_parent) ? $ticket_payment->pawn_parent->ticket_net->where('transaction_type', 'renew')->sum('net') : NULL; // to get the renewal net
        $prev_balance = isset($tickets) && isset($ticket_payment->pawnTickets->pawn_parent) ? $total_net - $payment : NULL;
        // dd($payment);
        // dd($tickets->pawn_parent->payment->sum('amount'));
        $tickets_latest = Ticket::where('inventory_id', $request->id)->latest()->first();   
        $id = $request->id;
        $or = Payment::max('or_number') + 1;  
        $or_number = sprintf('%05d', $or);

        // dd($ticket_payment);
        return view('form_renew', compact('ticket_number', 'inventory', 'tickets', 'tickets_latest', 'id', 'or_number', 'pawn_id', 'prev_balance'));
    }

    public function submitRenew(Request $request){
        // dd($request);
        $data = $request->validate([
            'transaction_date' => 'required',
            'maturity_date' => 'required',
            'expiration_date' => 'required',
            'auction_date' => 'required',
            'attachment_number' => 'required',
            'attachment_id' => 'required',
            'payment' => 'required'
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
            // $ticket_update = Ticket::findOrFail($request->pawn_id)->update($request->only('interest', 'penalty', 'interest_text', 'penalty_text', 'charges', 'discount'));

            $ticket = Ticket::create(
                $request->only('inventory_id', 'attachment_id','ticket_number','transaction_date','maturity_date','expiration_date','auction_date',
                'processed_by', 'net', 'attachment_number', 'authorized_representative','discount', 'interest', 'interest_text',
                'penalty', 'penalty_text', 'advance_interest', 'transaction_type', 'interbranch', 'interbranch_renewal', 'charges')
            );
            // dd($ticket);

            $inventory_data = Inventory::findOrfail($request->inventory_id)->first();
            $inventory = Inventory::findOrfail($request->inventory_id)->update($request->only('ticket_number','transaction_date','maturity_date','expiration_date','auction_date'));
            $request['ticket_id'] = $ticket->id;
            $pawn_ticket = Pawn_ticket::create($request->only('pawn_id', 'ticket_id'));
            $pawn_ticket_id = $pawn_ticket->id;
            $payment = Payment::create(
                array(
                    'transaction_type' => $request['transaction_type'],
                    'pawn_ticket_id' => $pawn_ticket_id,
                    'or_number' => $request['or_number'],
                    'amount' => $request['payment'],
                    'inventory_id' => $request['inventory_id']
                )
            );
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
            return response()->json(['success' => true, 'link' => route('inventory.show', $request['inventory_id'])]);
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
        $pawn_id = $request['pawn_id'];
        $ticket = Ticket::max('ticket_number') + 1;  
        $or = Payment::max('or_number') + 1;  
        $or_number = sprintf('%05d', $or);
        $ticket_number = sprintf('%05d', $ticket);
        $inventory = Inventory::with(['pawnTickets' => function($query) use ($pawn_id){
            $query->where('id', $pawn_id);
        }, 'pawnTickets.encoder', 'pawnTickets.item_tickets', 'pawnTickets.item_tickets.inventory_items'])
        ->with(['branch', 'customer', 'customer.attachments', 'item_category', 'item_category'])
        ->find($request->id);
        // dd($inventory);
        // $tickets = Ticket::where('inventory_id', $request->id)->get();
        $tickets = Ticket::with(['pawn_parent_many', 'pawn_parent_many.ticket_child' => function($query){
            $query->where('transaction_type', 'renew');
        },'pawn_parent_many.payment'])->find($pawn_id); // for renewal table
        // dd($tickets);
        $ticket_payment = Inventory::with(['pawnTickets','pawnTickets.pawn_parent', 'pawnTickets.pawn_parent_many.payment', 'pawnTickets.pawn_parent.ticket_net']) // payment and net
        ->find($request['id']);
        $id = $request->id;
        // dd($ticket_payment);
        $payment = isset($ticket_payment->inventory_payment) ? $ticket_payment->inventory_payment->where('inventory_id', $request->id)->sum('amount') : NULL;

        // dd($payment);
        $total_net =  isset($ticket_payment->pawnTickets->pawn_parent) ? $ticket_payment->pawnTickets->pawn_parent->ticket_net
        ->where(function($query) use ($request){
                $query->where('inventory_id', $request->id)
                    ->where('status' , 0);
        })->whereNotIn('transaction_type', ['pawn','repawn'])->sum('net') : NULL;
        // dd($total_net);
        // $total_net = isset($ticket_payment->pawn_parent) ? $ticket_payment->pawn_parent->ticket_net->where('inventory_id', $request->id)->sum('net') : NULL; // to get the renewal net
        // $total_net = isset($ticket_payment->pawn_parent) ? $ticket_payment->pawn_parent->ticket_net->where(['transaction_type', 'renew']])->sum('net') : NULL; // to get the renewal net
        $prev_balance = isset($tickets) && isset($ticket_payment->inventory_payment) ? $total_net - $payment : NULL;
        // dd($total_net);
        $tickets_latest = Ticket::where('inventory_id', $request->id)->latest()->first();   

        return view('form_redeem', compact('ticket_number', 'inventory', 'id', 'tickets', 'or_number', 'prev_balance', 'pawn_id', 'tickets_latest'));
    }

    public function submitRedeem(Request $request){
        // dd($request);

        try{
            $request['transaction_date'] = date('Y-m-d', strtotime($request['transaction_date']));
            $request['authorized_representative'] = isset($request['authorized_representative']) ? $request['authorized_representative'] : 0;
            $request['interbranch'] = isset($request['interbranch']) ? $request['interbranch'] : 0;
            $request['charges'] = $request['other_charges'];
            $ticket = Ticket::create(
                $request->only('inventory_id', 'attachment_id','ticket_number','transaction_date',
                'processed_by', 'net', 'attachment_number', 'authorized_representative','discount', 'interest', 'interest_text',
                'penalty', 'penalty_text', 'advance_interest', 'transaction_type', 'interbranch', 'charges')
            );    
            $request['ticket_id'] = $ticket->id;
            $pawn_ticket = Pawn_ticket::create($request->only('pawn_id', 'ticket_id'));
            $pawn_ticket_id = $pawn_ticket->id;
            $payment = Payment::create(
                array(
                    'transaction_type' => $request['transaction_type'],
                    'pawn_ticket_id' => $pawn_ticket_id,
                    'or_number' => $request['or_number'],
                    'amount' => $request['payment'],
                    'inventory_id' => $request['inventory_id']
                )
            );
            $inventory_data = Inventory::findOrfail($request->inventory_id)->first();
            // $inventory_item_data = Inventory_item::where('inventory_id', $request->inventory_id)->get();
            // $inventory_item_count = $inventory_item_data->count();
            // $request_item_count = count($request['item']);
            $request['status'] = 1;
            $request['maturity_date'] = NULL;
            $request['expiration_date'] = NULL;
            $request['auction_date'] = NULL;
            $inventory = Inventory::findOrfail($request->inventory_id)->update($request->only('transaction_date','ticket_number', 'maturity_date', 'expiration_date', 'auction_date'));
            $ticket_pawn = Ticket::findOrfail($request->pawn_id)->update($request->only('status'));
            // foreach($request['item'] as $key => $value){
                // $inventory_item = Inventory_item::findOrfail($value)->update(array('status' => 2));
            // }

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
            return response()->json(['success' => true, 'link' => route('inventory.show', $request['inventory_id'])]);


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
        $payment_display = Pawn_ticket::with('pawn_ticket_payment')->where('ticket_id', $request['ticket_id'])->first();
        $pawn_id = $payment_display->pawn_id;
        $request['pawn_id'] = $pawn_id;
        $inventory = Inventory::with(['pawnTickets' => function($query) use ($pawn_id){
            $query->where('id', $pawn_id);
        },'pawnTickets.encoder', 'pawnTickets.item_tickets', 'pawnTickets.item_tickets.inventory_items'])
        ->with(['branch' , 'customer', 'customer.attachments', 'item_category'])
        ->find($request->id);
        // $request['pawn_id'] = $inventory->pawnTickets->where('inventory_id', $request->id)->whereIn('transaction_type', array('pawn', 'repawn'))->where('status', 0)->first()->id;
        // dd($request['pawn_id']);


        $tickets_latest = Ticket::where('inventory_id', $request->id)->latest()->first();
        $ticket_original = Ticket::find($request['pawn_id']);
        $tickets_current = Inventory::with(['pawnTickets' => function($query) use ($ticket_id){
            $query->where('id', $ticket_id);
        }, 'pawnTickets.other_charges', 'pawnTickets.other_charges.inventory_other_charges'])->find($request->id);



        $tickets = Ticket::where('inventory_id', $request->id)->with(['pawn_parent_many' => function($query) use ($request){
            $query->whereNotIn('ticket_id', [$request->ticket_id, $request->pawn_id]);
        }, 'pawn_parent_many.ticket_child' => function($query){
            $query->where('transaction_type', 'renew');
        }, 'pawn_parent_many.payment'])->first(); 
        // for renewal table

        $net = $inventory->pawnTickets()->whereNotIn('transaction_type', ['pawn', 'repawn'])->whereNotIn('id', [$request['ticket_id']])->sum('net');
        // dd($net);   
        $payment_balance = $inventory->inventory_payment->where('inventory_id', $request->id)->sum('amount') - $payment_display->payment->amount;
        $prev_balance =  $net - $payment_balance;
        // dd($prev_balance); 

            return view('form_renew', compact('inventory', 'tickets', 'tickets_latest', 'tickets_current', 'id', 'payment_display', 'prev_balance', 'pawn_id', 'ticket_id', 'ticket_original'));

    }

    public function repawn(Request $request){
        $ticket = Ticket::max('ticket_number') + 1;  
        $ticket_number = sprintf('%05d', $ticket);
        $inventory = Inventory::find($request->id);
        $item_type_data = Item_type::where('item_category_id', 1)->get();
        $karat_rate = array();
        foreach($inventory->inventoryItems as $key => $value){
            // $rate_data[] = Rate::where('item_type_id', $value->item_type_id)->where('branch_id', \Auth::user()->branch_id)->orderBy('id')->get();
            $rate = Rate::where('item_type_id', $value->item_type_id)->where('branch_id', \Auth::user()->branch_id)->where('karat', $value->item_karat)->orderBy('id')->first();
            $price[] = ($value->item_type_weight * $rate->gram) * $rate->regular_rate;
            $item_type[] = Item_type::find($value->item_type_id);
            $karat_rate['gram'][] = $rate->gram;
            $karat_rate['regular_rate'][] = $rate->regular_rate;
            $karat_rate['special_rate'][] = $rate->special_rate;
            // dd($value);
        }
        $tickets_latest = Ticket::where('inventory_id', $request->id)->latest()->first();

        // dd($karat_rate);
        // dd($rate);
        // rate_appraised_value = ($(item_type_weight).val() * gram) * regular_rate;

        // dd($item_type);
        // dd($request->id);
        return view('form_repawn', compact('ticket_number', 'inventory', 'item_type_data', 'price', 'item_type', 'karat_rate', 'tickets_latest'));
    }

    public function submitRepawn(Request $request){
        try{
            // dd(($request['item_type_id']));
            \DB::beginTransaction();
            $data = $request->validate([
                'transaction_type' => 'required',
                'transaction_status' => 'required',
                'branch_id' => 'required',
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
                'item_type_appraised_value.*' => 'required',
                'item_name_appraised_value.*' => 'required'
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
            $inventory = Inventory::findOrFail($request['inventory_id'])->update($inventory_data->only('transaction_status', 'ticket_number', 'processed_by', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date')->toArray());
            // $Inventory = Inventory::create($inventory_data->only('inventory_number','transaction_status','customer_id','branch_id','item_category_id','ticket_number', 'processed_by', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date')->toArray());
            $data['inventory_id'] = $request['inventory_id'];

            $ticket_data = collect($data);
            $ticket = Ticket::create($ticket_data->only('transaction_type','inventory_id', 'attachment_id','ticket_number','transaction_date','maturity_date','expiration_date','auction_date','processed_by','appraised_value', 'principal', 'net', 'attachment_number', 'discount', 'charges', 'is_special_rate')->toArray());
            $data['ticket_id'] = $ticket->id;
            $pawn_ticket = Pawn_ticket::create(array('pawn_id' => $data['ticket_id'], 'ticket_id' => $data['ticket_id']));
             foreach($data['item_type_appraised_value'] as $key => $value){
                 if(!isset($request['inventory_item_id'][$key])){
                    $item_data = $request->validate([
                        'image.'.$key.'' => 'required',
                        'item_name.'.$key.'' => 'required',
                        'item_name_weight.'.$key.'' => 'required',
                        'item_karat.'.$key.'' => 'required',
                        'item_karat_weight.'.$key.'' => 'required',                        
                        'item_type_weight.'.$key.'' => 'required',
                        'description.'.$key.'' => 'required',
                        'item_type_id.'.$key.'' => 'required',
                    ]);
                    $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                    $request->image[$key]->move(public_path('item_image'), $image_path);
        
                    $inventory_item_data = array(
                        'inventory_id' => $data['inventory_id'],
                        'item_type_id' => $item_data['item_type_id'][$key],
                        'item_type_weight' => $item_data['item_type_weight'][$key],
                        'description' => $item_data['description'][$key],
                        'image' => $image_path,
                        'item_name' => $item_data['item_name'][$key],
                        'item_name_weight' => $item_data['item_name_weight'][$key],
                        'item_karat' => $item_data['item_karat'][$key],
                        'item_karat_weight' => $item_data['item_karat_weight'][$key],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s')
                    );
                    $inventory_item = Inventory_item::insertGetId($inventory_item_data);
                    $ticket_item = Ticket_item::insert(array(
                        'ticket_id' => $data['ticket_id'],
                        'inventory_item_id' => $inventory_item,
                        'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                        'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s')
                    ));
                 }else{
                    $ticket_item = Ticket_item::insert(array(
                        'ticket_id' => $data['ticket_id'],
                        'inventory_item_id' => $request['inventory_item_id'][$key],
                        'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                        'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s')
                    ));
                 }

                //  $data['inventory_item_id'] = $inventory_item->id;

             }
     
             if(isset($request['other_charges_id'])){
                foreach($request['other_charges_id'] as $key => $value){
                        if($value != null){
                            $inventory_charges_data = array(
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
             $request->session()->flash('status', 'The repawn was successfully created!');
            //  return response()->json(['success' => true, 'link' => route('pawn_print', $request['inventory_id']), 'create' => true]);
             return response()->json(['success' => true, 'create' => true, 'link' => route('inventory.show', $data['inventory_id'])]);
            
         }catch(\PDOException $e){
             \DB::rollBack();
             //  dd($e->getMessage());
              return response()->json(['status' => $e->getMessage(), 'success' => false]);

         }    
    }

    public function updateRenew(Request $request){
        // dd($request);

        try{
            $request['transaction_date'] = date('Y-m-d', strtotime($request['transaction_date']));
            $request['maturity_date'] = date('Y-m-d', strtotime($request['maturity_date']));
            $request['expiration_date'] = date('Y-m-d', strtotime($request['expiration_date']));
            $request['auction_date'] = date('Y-m-d', strtotime($request['auction_date']));
            $request['authorized_representative'] = isset($request['authorized_representative']) ? $request['authorized_representative'] : 0;
            $request['interbranch'] = isset($request['interbranch']) ? $request['interbranch'] : 0;
            $request['interbranch_renewal'] = isset($request['interbranch_renewal']) ? $request['interbranch_renewal'] : 0;
            $request['charges'] = $request['other_charges'];
            // $ticket_update = Ticket::findOrFail($request->pawn_id)->update($request->only('interest', 'penalty', 'interest_text', 'penalty_text', 'charges', 'discount'));

            // $ticket = Ticket::create(
            //     $request->only('inventory_id', 'attachment_id','ticket_number','transaction_date','maturity_date','expiration_date','auction_date',
            //     'processed_by', 'net', 'attachment_number', 'authorized_representative','discount', 'interest', 'interest_text',
            //     'penalty', 'penalty_text', 'advance_interest', 'transaction_type', 'interbranch', 'interbranch_renewal', 'charges')
            // );
            $ticket = Ticket::findOrfail($request->id)->update($request->only('attachment_id', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date',
            'net', 'attachment_number', 'authorized_representative', 'discount', 'interest', 'penalty', 'advance_interest', 'interbranch', 'interbranch_renewal', 'charges' ));
            // dd($ticket);

            $inventory_data = Inventory::findOrfail($request->inventory_id)->first();
            $inventory = Inventory::findOrfail($request->inventory_id)->update($request->only('transaction_date','maturity_date','expiration_date','auction_date'));
            // $request['ticket_id'] = $request->id
            // $pawn_ticket = Pawn_ticket::create($request->only('pawn_id', 'ticket_id'));
            // $pawn_ticket_id = $pawn_ticket->id;
            // $payment = Payment::create(
            //     array(
            //         'transaction_type' => $request['transaction_type'],
            //         'pawn_ticket_id' => $pawn_ticket_id,
            //         'or_number' => $request['or_number'],
            //         'amount' => $request['payment'],
            //         'inventory_id' => $request['inventory_id']
            //     )
            // );
            $payment = Payment::findOrFail($request->payment_id)->update(array('amount' => $request['payment']));


            if(isset($request['other_charges_id'])){
                foreach($request['other_charges_id'] as $key => $value){
                    if(isset($request['inventory_other_charges_id'][$key])){
                       $inventory_item_update = Inventory_other_charges::findOrfail($request['inventory_other_charges_id'][$key])
                               ->update(
                                   array(
                                       'other_charges_id' => $value,
                                       'amount' => $request['other_charges_amount'][$key]
                                   )
                               );
   
                    }else{
                        if($value != null){
                            $inventory_charges_data = array(
                                'inventory_id' => $request['inventory_id'],
                                'ticket_id' => $request['id'],
                                'other_charges_id' => $value,
                                'amount' => $request['other_charges_amount'][$key]

                            );
                            $inventory_charges = Inventory_other_charges::insert($inventory_charges_data);
                        }
                    }
   
                }
             }
            if(isset($request['inventory_deleted_item'])){
                foreach($request['inventory_deleted_item'] as $key => $value){
                    $update_item = Inventory_item::find($value)->delete();
                    $update_ticket_item = Ticket_item::find($request['ticket_deleted_item'][$key])->delete();
                }     
            }
            if(isset($request['deleted_other_charges'])){
                foreach($request['deleted_other_charges'] as $key => $value){
                    $other_charges_update = Inventory_other_charges::find($value)->delete();
                    // dd($discount);
                }
            }
        
            \DB::commit();
            $request->session()->flash('status', 'The renewal was successfully updated!');
            return response()->json(['success' => true, 'link' => route('inventory.show', $request['inventory_id'])]);
        }catch(\PDOException $e){
            \DB::rollBack();
            //  dd($e->getMessage());
             return response()->json(['status' => $e->getMessage(), 'success' => false]);
        
        }

    }

    public function showUpdateRedeem(Request $request){
        // dd($request);
        $ticket_id = $request['ticket_id'];
        $id = $request->id;
        $payment_display = Pawn_ticket::with('pawn_ticket_payment')->where('ticket_id', $request['ticket_id'])->first();
        $pawn_id = $payment_display->pawn_id;
        $request['pawn_id'] = $pawn_id;
        $inventory = Inventory::with(['pawnTickets' => function($query) use ($pawn_id){
            $query->where('id', $pawn_id);
        },'pawnTickets.encoder', 'pawnTickets.item_tickets', 'pawnTickets.item_tickets.inventory_items'])
        ->with(['branch' , 'customer', 'customer.attachments', 'item_category'])
        ->find($request->id);
        // $request['pawn_id'] = $inventory->pawnTickets->where('inventory_id', $request->id)->whereIn('transaction_type', array('pawn', 'repawn'))->where('status', 0)->first()->id;
        // dd($request['pawn_id']);


        $tickets_latest = Ticket::where('inventory_id', $request->id)->whereNotIn('id', [$ticket_id])->latest()->first();
        $ticket_original = Ticket::find($request['pawn_id']);
        $tickets_current = Inventory::with(['pawnTickets' => function($query) use ($ticket_id){
            $query->where('id', $ticket_id);
        }, 'pawnTickets.other_charges', 'pawnTickets.other_charges.inventory_other_charges'])->find($request->id);



        $tickets = Ticket::where('inventory_id', $request->id)->with(['pawn_parent_many' => function($query) use ($request){
            $query->whereNotIn('ticket_id', [$request->ticket_id, $request->pawn_id]);
        }, 'pawn_parent_many.ticket_child' => function($query){
            $query->where('transaction_type', 'renew');
        }, 'pawn_parent_many.payment'])->first(); 
        // for renewal table

        $net = $inventory->pawnTickets()->whereNotIn('transaction_type', ['pawn', 'repawn'])->whereNotIn('id', [$request['ticket_id']])->sum('net');
        // dd($net);   
        $payment_balance = $inventory->inventory_payment->where('inventory_id', $request->id)->sum('amount') - $payment_display->payment->amount;
        $prev_balance =  $net - $payment_balance;
        // dd($payment_balance); 

            return view('form_redeem', compact('inventory', 'tickets', 'tickets_latest', 'tickets_current', 'id', 'payment_display', 'prev_balance', 'pawn_id', 'ticket_id', 'ticket_original'));

    }

    public function updateRedeem(Request $request){
        // dd($request);
        try{
            $request['transaction_date'] = date('Y-m-d', strtotime($request['transaction_date']));
            $request['authorized_representative'] = isset($request['authorized_representative']) ? $request['authorized_representative'] : 0;
            $request['interbranch'] = isset($request['interbranch']) ? $request['interbranch'] : 0;
            $request['charges'] = $request['other_charges'];
            // $ticket_update = Ticket::findOrFail($request->pawn_id)->update($request->only('interest', 'penalty', 'interest_text', 'penalty_text', 'charges', 'discount'));


            $ticket = Ticket::findOrfail($request->id)->update($request->only('attachment_id', 'transaction_date','net', 'attachment_number', 
            'authorized_representative', 'discount', 'interest', 'penalty', 'advance_interest', 'interbranch', 'charges' ));
            // dd($ticket);

            $inventory = Inventory::findOrfail($request->inventory_id)->update($request->only('transaction_date'));

            $payment = Payment::findOrFail($request->payment_id)->update(array('amount' => $request['payment']));


            if(isset($request['other_charges_id'])){
                foreach($request['other_charges_id'] as $key => $value){
                    if(isset($request['inventory_other_charges_id'][$key])){
                       $inventory_item_update = Inventory_other_charges::findOrfail($request['inventory_other_charges_id'][$key])
                               ->update(
                                   array(
                                       'other_charges_id' => $value,
                                       'amount' => $request['other_charges_amount'][$key]
                                   )
                               );
   
                    }else{
                        if($value != null){
                            $inventory_charges_data = array(
                                'inventory_id' => $request['inventory_id'],
                                'ticket_id' => $request['id'],
                                'other_charges_id' => $value,
                                'amount' => $request['other_charges_amount'][$key]

                            );
                            $inventory_charges = Inventory_other_charges::insert($inventory_charges_data);
                        }
                    }
   
                }
             }
            if(isset($request['inventory_deleted_item'])){
                foreach($request['inventory_deleted_item'] as $key => $value){
                    $update_item = Inventory_item::find($value)->delete();
                    $update_ticket_item = Ticket_item::find($request['ticket_deleted_item'][$key])->delete();
                }     
            }
            if(isset($request['deleted_other_charges'])){
                foreach($request['deleted_other_charges'] as $key => $value){
                    $other_charges_update = Inventory_other_charges::find($value)->delete();
                    // dd($discount);
                }
            }
        
            \DB::commit();
            $request->session()->flash('status', 'The redeem was successfully updated!');
            return response()->json(['success' => true, 'link' => route('inventory.show', $request['inventory_id'])]);
        }catch(\PDOException $e){
            \DB::rollBack();
            //  dd($e->getMessage());
             return response()->json(['status' => $e->getMessage(), 'success' => false]);
        
        }
    }

    public function showUpdateRepawn(Request $request){

        // $ticket = Ticket::max('ticket_number') + 1;  
        // $ticket_number = sprintf('%05d', $ticket);
        $inventory = Inventory::find($request->id);
        $item_type_data = Item_type::where('item_category_id', 1)->get();
        $karat_rate = array();
        foreach($inventory->inventoryItems as $key => $value){
            // $rate_data[] = Rate::where('item_type_id', $value->item_type_id)->where('branch_id', \Auth::user()->branch_id)->orderBy('id')->get();
            $rate = Rate::where('item_type_id', $value->item_type_id)->where('branch_id', \Auth::user()->branch_id)->where('karat', $value->item_karat)->orderBy('id')->first();
            $price[] = ($value->item_type_weight * $rate->gram) * $rate->regular_rate;
            $item_type[] = Item_type::find($value->item_type_id);
            $karat_rate['gram'][] = $rate->gram;
            $karat_rate['regular_rate'][] = $rate->regular_rate;
            $karat_rate['special_rate'][] = $rate->special_rate;
            // dd($value);
        }
        $tickets_latest = Ticket::where('inventory_id', $request->id)->whereNotIn('id', [$request->ticket_id])->latest()->first();
        $tickets_current = Ticket::with('inventory', 'item_tickets', 'item_tickets.inventory_items', 'other_charges')->find($request->ticket_id);
        $inventory->inventoryItems = Ticket::find($request->ticket_id)->item_tickets;
        // dd($tickets_current);
        // dd($tickets_current);
        // dd($tickets_current);
        // dd($karat_rate);
        // dd($rate);
        // dd($tickets_current);
        // dd($tickets_current->inventory->attachment_id);
        // rate_appraised_value = ($(item_type_weight).val() * gram) * regular_rate;

        // dd($item_type);
        // dd($request->id);
        return view('form_repawn', compact('inventory', 'item_type_data', 'price', 'item_type', 'karat_rate', 'tickets_latest', 'tickets_current'));

    }

    public function updateRepawn(Request $request){
        $data = $request->validate([
            'transaction_type' => 'required',
            'transaction_status' => 'required',
            'branch_id' => 'required',
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
            'item_type_appraised_value.*' => 'required',
            'item_name_appraised_value.*' => 'required'
        ]);
        try{
            \DB::beginTransaction();
            $data['net'] = $request['net_proceeds'];
            $data['transaction_date'] = date('Y-m-d', strtotime($request['transaction_date']));
            $data['maturity_date'] = date('Y-m-d', strtotime($request['maturity_date']));
            $data['expiration_date'] = date('Y-m-d', strtotime($request['expiration_date']));
            $data['auction_date'] = date('Y-m-d', strtotime($request['auction_date']));
            $data['is_special_rate'] = isset($request['is_special_rate']) ? $request['is_special_rate'] : 0;
            $data['charges'] = $request['other_charges'];
            $inventory_data = collect($data);
            $inventory = Inventory::findOrfail($request->inventory_id)->update($inventory_data->only('transaction_date', 'maturity_date', 'expiration_date', 'auction_date')->toArray());
            $ticket = Ticket::findOrfail($request->id)->update($inventory_data->only('attachment_id','transaction_date','maturity_date','expiration_date','auction_date', 'net', 'attachment_number', 'is_special_rate', 'appraised_value', 'principal', 'charges', 'discount')->toArray());
            //  $request['ticket_id'] = $ticket->id;
            foreach($data['item_type_appraised_value'] as $key => $value){
                if(!isset($request['inventory_item_id'][$key])){
                   $item_data = $request->validate([
                       'image.'.$key.'' => 'required',
                       'item_name.'.$key.'' => 'required',
                       'item_name_weight.'.$key.'' => 'required',
                       'item_karat.'.$key.'' => 'required',
                       'item_karat_weight.'.$key.'' => 'required',                        
                       'item_type_weight.'.$key.'' => 'required',
                       'description.'.$key.'' => 'required',
                       'item_type_id.'.$key.'' => 'required',
                   ]);
                   $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                   $request->image[$key]->move(public_path('item_image'), $image_path);
       
                   $inventory_item_data = array(
                       'inventory_id' => $request['inventory_id'],
                       'item_type_id' => $item_data['item_type_id'][$key],
                       'item_type_weight' => $item_data['item_type_weight'][$key],
                       'description' => $item_data['description'][$key],
                       'image' => $image_path,
                       'item_name' => $item_data['item_name'][$key],
                       'item_name_weight' => $item_data['item_name_weight'][$key],
                       'item_karat' => $item_data['item_karat'][$key],
                       'item_karat_weight' => $item_data['item_karat_weight'][$key],
                       'created_at'=>date('Y-m-d H:i:s'),
                       'updated_at'=> date('Y-m-d H:i:s')
                   );
                   $inventory_item = Inventory_item::insertGetId($inventory_item_data);
                   $ticket_item = Ticket_item::insert(array(
                       'ticket_id' => $request['id'],
                       'inventory_item_id' => $inventory_item,
                       'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                       'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                       'created_at'=>date('Y-m-d H:i:s'),
                       'updated_at'=> date('Y-m-d H:i:s')
                   ));
                }else{
                   $ticket_item = Ticket_item::findOrFail($request['ticket_item_id'][$key])->update(array(
                       'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                       'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                   ));
                }

               //  $data['inventory_item_id'] = $inventory_item->id;

            }

            
             if(isset($request['other_charges_id'])){
                foreach($request['other_charges_id'] as $key => $value){
                    if(isset($request['inventory_other_charges_id'][$key])){
                       $inventory_item_update = Inventory_other_charges::findOrfail($request['inventory_other_charges_id'][$key])
                               ->update(
                                   array(
                                       'inventory_id' => $request['inventory_id'],
                                       'ticket_id' => $request['id'],
                                       'other_charges_id' => $value,
                                       'amount' => $request['other_charges_amount'][$key]
                                   )
                               );
   
                    }else{
                        if($value != null){
                            $inventory_charges_data = array(
                                'inventory_id' => $request['inventory_id'],
                                'ticket_id' => $request['id'],
                                'other_charges_id' => $value,
                                'amount' => $request['other_charges_amount'][$key]

                            );
                            $inventory_charges = Inventory_other_charges::insert($inventory_charges_data);
                        }
                    }
   
                }
             }
            if(isset($request['inventory_deleted_item'])){
                foreach($request['inventory_deleted_item'] as $key => $value){
                    $update_ticket_item = Ticket_item::find($request['ticket_deleted_item'][$key])->delete();
                }     
            }
            if(isset($request['deleted_other_charges'])){
                foreach($request['deleted_other_charges'] as $key => $value){
                    $other_charges_update = Inventory_other_charges::find($value)->delete();
                    // dd($discount);
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
    

}
