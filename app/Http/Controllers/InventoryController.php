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
        // dd($request['page']);
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
            $customer = Inventory::with(['customer', 'pawnTickets'])->where('branch_id', \Auth::user()->branch_id)->get();

                return Datatables::of($customer)
                        ->addIndexColumn()

                        ->editColumn('transaction_date', function ($data) {
                            return date('m/d/Y', strtotime($data->transaction_date));
                        })
                        ->editColumn('customer', function ($data) {
                            return $data->customer->first_name." ".$data->customer->last_name;
                        })
                        ->editColumn('net', function ($data) {
                            $net = $data->pawnTickets->whereIn('transaction_type', ['pawn', 'repawn'])->where('inventory_id', $data->id)->latest()->first();
                            return number_format($net->net, 2);
                        })
                        ->editColumn('gross', function ($data) {

                            $payment = isset($data->inventory_payment) ? $data->inventory_payment->where('inventory_id', $data->id)->sum('amount') : NULL;

                            // dd($data->id);
                            $total_net = $data->pawnTickets->where('inventory_id', $data->id)->where('status', 0)->sum('net');

                            // dd($total_net);
                            // dd($total_net - $payment);
                            return number_format($total_net - $payment ,2);
                        })
                        ->editColumn('principal', function ($data) {
                            $principal = $data->pawnTickets->whereIn('transaction_type', ['pawn', 'repawn'])->where('inventory_id', $data->id)->latest()->first();
                            return number_format($principal->principal, 2);
                        })
                        ->editColumn('transaction_status', function ($data) {
                            $payment = isset($data->inventory_payment) ? $data->inventory_payment->where('inventory_id', $data->id)->sum('amount') : NULL;
                            $total_net = $data->pawnTickets->where('inventory_id', $data->id)->where('status', 0)->sum('net');
                            $status = round($total_net,2) - round($payment,2) > 0 ? '<i style="color:red">(OUTSTANDING)</i>' : '<i style="color:green">(CLEARED)</i>'; 
                            return $data->transaction_status.'<br/>'.$status;
                        })

                        ->addColumn('action', function($row){
                            $count = $row->pawnTickets->where([['inventory_id', $row->id],['transaction_type', 'renew']])->count();
                            $view_route = route('inventory.show', ['id' => $row['id']]);
                            $btn = '<a href="'.$view_route.'" class="btn btn-responsive ordinario-button btn-sm"><span class="material-icons">view_list</span></a>'; 
            
                            return $btn;
                        })
                        ->rawColumns(['action', 'ticket_date', 'transaction_status'])
                        ->make(true);

                    }
            
        return view('inventory');
    }

    public function create(){

    }

    public function edit(Request $request){

    }
    public function show(Request $request){
        $id = $request->id;
        $ticket = Ticket::with(['encoder', 'attachment', 'payment'])->where('inventory_id', $request->id)->get();
        // dd($ticket);
        $inventory = Inventory::with('customer', 'branch', 'item_category', 'inventoryItems', 'item.item_type')->findOrFail($request->id);
        $current_pawn = Ticket::whereIn('transaction_type', array('pawn', 'repawn'))->where([['status', '=', 0], ['inventory_id', $request->id]])->first();
        $ticket_update = Ticket::where('inventory_id', $request->id)->whereNotIn('transaction_type', array('pawn'))->latest()->first();
        // dd($ticket_update);
        // dd($inventory);
        // dd($current_pawn);
        return view('view_inventory', compact('id', 'ticket', 'inventory', 'current_pawn', 'ticket_update'));
    }


    public function store(Request $request){
        // dd($request);


        
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




    

}
