<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Auction;
use App\InventoryAuction;
use App\Ticket;
use App\Inventory;

class AuctionController extends Controller
{
    //

    public function index(Request $request){
        if ($request->ajax()){
            $auction = \DB::table('inventories')
                        ->select(\DB::raw('pawn.ticket_number,inventories.transaction_date,pawn.principal,CONCAT(customers.first_name, " ", customers.last_name) as customer, pawn.id as ticket_id'))
                        ->join('tickets', function ($join) {
                            $join->on('tickets.inventory_id', '=', 'inventories.id')
                                ->where('tickets.id', function($query){
                                    $query->select(\DB::raw('MAX(id) as id'))
                                            ->from('tickets')
                                            ->whereRaw('inventories.id = tickets.inventory_id');
                                });
                        })
                        ->join('pawn_tickets', function($join){
                            $join->on('tickets.id', '=', 'pawn_tickets.ticket_id');
                        })
                        ->join('tickets as pawn', function($join){
                            $join->on('pawn.id', '=', 'pawn_tickets.pawn_id');
                        })
                        ->join('customers', 'customers.id', '=', 'inventories.customer_id')
                        ->leftJoin('notices', function($join){
                            $join->on('notices.ticket_id', '=', 'pawn.id')
                                ->where('notices.status', 0);
                        })                       
                        ->whereNotNull('notices.notice_date')
                        ->where('inventories.status', 0)
                        ->where('inventories.branch_id', '=', \Auth::user()->branch_id)

                         ->get();     
                        //  dd($foreclosed);         
              return Datatables::of($auction)
                        ->addIndexColumn()
                        ->editColumn('principal', function($row){
                            return number_format($row->principal,2);
                        })  
                        ->editColumn('transaction_date', function($row){
                            return date('M d, Y', strtotime($row->transaction_date));
                        })
                        ->addColumn('checkbox', function($row){
                            return '<input type="checkbox" name="auction" value="'.$row->ticket_id.'" class="item" onClick="toggleState()"/>';
                        })
                        ->rawColumns(['checkbox'])
                        ->make(true);
            }

        // dd($foreclosed);

        return view('auction');
    }

    public function store(Request $request){
        // dd($request);
        $ids = json_decode($request->id);
        $control_id = Auction::max('control_id') + 1;
        $request['auction_date'] = date('Y-m-d', strtotime($request['auction_date']));



        foreach($ids as $key => $value){
            $ticket = Ticket::find($value);
            $inventory = Inventory::findOrfail($ticket->inventory_id)->update(
                array(
                    'status' => 1,
                    'auction_date' => $request['auction_date']
                )
            );
            $inventory_auction = InventoryAuction::create(array(
                'inventory_id' => $ticket->inventory_id,
                'ticket_id' => $value,
                'control_id' => $control_id
            ));
        }
        $auction = Auction::create(array('control_id' => $control_id, 
        'auction_date' => $request['auction_date']
        // 'inventory_auction_number' => $request['inventory_auction_number'],
        // 'price' => $request['price']
         ));


        return response()->json(['success' => true]);

    }
}
