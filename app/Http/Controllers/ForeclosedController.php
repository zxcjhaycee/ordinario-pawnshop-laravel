<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Ticket;
use App\Inventory;
use App\Ticket_item;
use DataTables;

class ForeclosedController extends Controller
{
    //
    public function index(Request $request){
        if ($request->ajax()){
            $foreclosed = \DB::table('inventories')
                        ->select(\DB::raw('tickets.ticket_number,tickets.transaction_date,tickets.maturity_date,tickets.expiration_date,pawn.principal,CONCAT(customers.first_name, " ", customers.last_name) as customer, 
                        pawn.id as ticket_id,inventories.auction_date, notices.notice_date, inventories.item_category_id, inventories.id as inventory_id, notices.status'))
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
                        ->whereRaw('(tickets.expiration_date <= '.date('Ymd').')')

                        ->where('inventories.status', 0)
                        ->where('inventories.branch_id', '=', \Auth::user()->branch_id)

                         ->get();     
                        //  dd($foreclosed);         
              return Datatables::of($foreclosed)
                        ->addIndexColumn()
                        ->editColumn('transaction_date', function($row){
                            return date('M d, Y', strtotime($row->transaction_date));
                        })
                        ->editColumn('maturity_date', function($row){
                            return date('M d, Y', strtotime($row->maturity_date));
                        })
                        ->editColumn('expiration_date', function($row){
                            return date('M d, Y', strtotime($row->expiration_date));
                        })
                        ->editColumn('auction_date', function($row){
                            return $row->notice_date != null ? date('M d, Y', strtotime($row->auction_date)) : "";
                        })
                        ->addColumn('item', function($row){
                            $items = Ticket_item::where('ticket_id', $row->ticket_id)->with(['inventory_items'])->get();
                            // dd($item);
                            $string = '';
                            foreach($items as $item){
                                if($row->item_category_id == 1){
                                    $string .= $item->inventory_items->item_type->item_type." ".$item->inventory_items->item_name." ".$item->inventory_items->item_karat."K ".$item->inventory_items->item_type_weight."g (".$item->inventory_items->description.") <br/>";

                                }else{
                                    $string .= $item->inventory_items->item_name." (".$item->inventory_items->description.") <br/>";

                                }
                            }
                            return $string;
                            // return $row->notice_date != null ? date('M d, Y', strtotime($row->auction_date)) : "";
                        })

                        ->addColumn('action', function($row){
                            // $view_route = route('branch.edit', ['id' => $row['id']]);
                            // $icon = isset($row->deleted_at) ? 'restore' : 'delete';
                            // $btn = '<button type="button" class="btn btn-sm ordinario-button" id="foreclosed"><span class="material-icons">lock</span></button>';                           
                            $btn = '<a href="'.route('pawn.renew', ['id' => $row->inventory_id, 'pawn_id' => $row->ticket_id]).'" class="btn btn-success btn-sm"><span class="material-icons">autorenew</span></a>';
                            // if($row->notice_date === NULL){
                            //     $btn .= '<button type="button" class="btn btn-warning btn-sm notice" id="'.$row->ticket_id.'"><span class="material-icons">announcement</span></button>';
                            // }
                                return $btn;
                        })
                        ->rawColumns(['action','dates', 'item'])
                        ->make(true);
            }

        // dd($foreclosed);

        return view('foreclosed');
    }

    public function updateForeclosed(Request $request){
        // dd($request);
        $ticket_update = Ticket::find($request->pawn_id)
                        ->update(array(
                            'foreclosed_date' => date('Ymd')
                        ));
        return response()->json(array('status' => 'success'));

    }
}
