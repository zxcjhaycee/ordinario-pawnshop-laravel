<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use \NumberFormatter;
use App\Http\Controllers\MC_TableFpdf;
use Illuminate\Validation\ValidationException;

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
use App\User;
use App\Branch;
use App\InventoryAuction;
use App\Notice;
// use Carbon\Carbon; 

class PawnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // $customer = Ticket::with(['inventory', 'pawn_parent', 'pawn_parent.ticket_child'])->whereIn('transaction_type', ['pawn', 'repawn'])->first();
        // dd($customer->pawn_parent->ticket_child);
        // dd($customer->pawn_parent->ticket_child->has('payment')->first()->payment);
        // $payment = \DB::table('pawn_tickets')->select(\DB::raw('SUM(payments.amount) as amount'))
        //             ->leftJoin('payments', function($query){
        //                 $query->on('pawn_tickets.ticket_id', 'payments.ticket_id');
        //             })->where('pawn_id', 2)->whereNotNull('payments.id')->first();
        // dd($payment);
        if ($request->ajax()){
            if(\Auth::user()->isAdmin()){
                $customer = Ticket::with(['inventory'])->whereIn('transaction_type', ['pawn', 'repawn'])
                ->orderBy('ticket_number')
                ->whereHas('inventory', function($query){
                    $query->where('branch_id', \Auth::user()->branch_id);
                })->get();
            }else{
                $customer = Ticket::with(['inventory'])->whereIn('transaction_type', ['pawn', 'repawn'])
                ->whereHas('inventory', function($query){
                    $query->where('branch_id', \Auth::user()->branch_id);
                })
                ->orderBy('ticket_number')->get();
            }

            // dd($customer->pawn_parent->ticket_child->payment);

            // dd($customer);
            // $customer = Customer::all();
                return Datatables::of($customer)
                        ->addIndexColumn()

                        ->editColumn('transaction_date', function ($data) {
                            return date('m/d/Y', strtotime($data->transaction_date));
                        })
                        ->editColumn('customer', function ($data) {
                            return $data->inventory->customer->first_name." ".$data->inventory->customer->last_name;
                        })
                        ->editColumn('net', function ($data) {
                            // dd($data);
                            // $net = $data->pawnTickets->whereIn('transaction_type', ['pawn', 'repawn'])->where('inventory_id', $data->id)->latest()->first();
                            $net = $data->net;
                            // dd($data);
                            // dd($net->where('inventory_id', $net->inventory_id)->first());
                            // dd($data->pawnTickets->pawn_parent_many);
                            // dd($net);

                            return number_format($net, 2);
                        })
                        ->editColumn('gross', function ($data) {
                            $payment = \DB::table('pawn_tickets')
                            ->select(\DB::raw('SUM(payments.amount) as amount, SUM(tickets.net) as net'))
                            ->leftJoin('payments', function($query){
                                $query->on('pawn_tickets.ticket_id', 'payments.ticket_id');
                            })
                            ->leftJoin('tickets', 'tickets.id', '=', 'pawn_tickets.ticket_id')
                            ->where('pawn_id', $data->id)->where('tickets.status', 0)->orderBy('tickets.id')->first();
                            // $payment = NULL;
                            // if($data->find($data->id)->pawn_parent->where('pawn_id', $data->id)->where('ticket_id', '!=', $data->id)->first()){
                                // $payment = $data->find($data->id)->pawn_parent->where('pawn_id', $data->id)->where('ticket_id', '!=', $data->id)->first()->ticket_child->payment->groupBy('ticket_id')->sum('amount');
                            // }
                            // $payment = $data->pawn_parent->where('pawn_id', $data->id)->first()->ticket_child->has('payment')->first()->payment->sum('amount');
                            // $total_net = $data->pawn_parent->ticket_child->where('id', $data->id)->whereNotIn('transaction_type', ['pawn', 'repawn'])->get();
                            // $total_net = $data->pawn_parent->ticket_child->where('id', $data->id)->sum('net');
                            // dd($payment);
                            // return number_format($total_net - $payment ,2);
                            // $payment = Pawn_ticket::where('pawn_id', $data->id)->whereNotIn('ticket_id', [$data->id])->first();

                            return number_format(round($payment->net,2) - round($payment->amount,2),2);
                        })
                        ->editColumn('principal', function ($data) {
                            // $principal = $data->pawnTickets->whereIn('transaction_type', ['pawn', 'repawn'])->where('inventory_id', $data->id)->latest()->first();
                            $principal = $data->principal;
                            return number_format($principal, 2);
                        })
                        ->editColumn('transaction_status', function ($data) {
                            $payment = \DB::table('pawn_tickets')
                            ->select(\DB::raw('SUM(payments.amount) as amount, SUM(tickets.net) as net'))
                            ->leftJoin('payments', function($query){
                                $query->on('pawn_tickets.ticket_id', 'payments.ticket_id');
                            })
                            ->leftJoin('tickets', 'tickets.id', '=', 'pawn_tickets.ticket_id')
                            ->where('pawn_id', $data->id)->where('tickets.status', 0)->orderBy('tickets.id')->first();
                            switch(true){
                                case $data->inventory->expiration_date <= date('Y-m-d') && $data->status == 0 && $data->inventory->status == 0:
                                    $status = '<i style="color:red">(EXPIRED)</i>';
                                break;
                                case $data->inventory->status == 1:
                                    $status = '<i style="color:red">(AUCTIONED)</i>';
                                break;
                                case round($payment->net,2) - round($payment->amount,2) > 0:
                                    $status = '<i style="color:red">(OUTSTANDING)</i>';
                                break;
                                case round($payment->net,2) - round($payment->amount,2) <= 0:
                                    $status = '<i style="color:green">(CLEARED)</i>';
                                break;

                            }
                            // $status = round($payment->net,2) - round($payment->amount,2) > 0 ? '<i style="color:red">(OUTSTANDING)</i>' : '<i style="color:green">(CLEARED)</i>'; 
                            
                            return $status;
                            // dd($payment);
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
                            // $count = $row->pawnTickets->where([['inventory_id', $row->id],['transaction_type', 'renew']])->count();
                            $count = $row->pawn_parent->where('pawn_id', $row->id)->whereNotIn('ticket_id', [$row->id])->count();
                            $view_route = route('pawn.show', ['id' => $row['id']]);

                            $edit_route = $row->transaction_type == 'pawn' ? route('pawn.edit', ['id' => $row['id']]) : route('repawn_update', ['ticket_id' => $row->id, 'id' => $row->inventory_id ]);
                            // $icon = isset($row->deleted_at) ? 'restore' : 'delete';
                            $btn = '<a href="'.$view_route.'" class="btn btn-responsive ordinario-button btn-sm"><span class="material-icons">view_list</span></a>'; 
                            // $btn .= '<button type="button" class="btn btn-sm btn-warning remove" id="'.$row['id'].'" data-name="customer"><span class="material-icons">'.$icon.'</span></button> ';
                            
                            $btn .= $count == 0 ? '<a href="'.$edit_route.'" class="btn btn-responsive btn-success btn-sm"><span class="material-icons">edit</span></a>' : ""; 

                            return $btn;
                        })
                        ->rawColumns(['action', 'ticket_date', 'transaction_status'])
                        ->make(true);
            }

        return view('pawn');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $ticket = Ticket::max('ticket_number') + 1;  
        $ticket_number = sprintf('%05d', $ticket);
        $branch = \Auth::user()->isAdmin() ? Branch::all() : Branch::where('id', \Auth::user()->branch_id)->get();
        // dd($branch);
        return view('form_inventory', compact('ticket_number', 'branch'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    try{
        // dd(($request['item_type_id']));
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
            'image.*' => '',
            'item_name.*' => 'required',
            'item_name_weight.*' => 'required',
            'item_name_appraised_value.*' => 'required',
            'item_karat.*' => 'required',
            'item_karat_weight.*' => 'required',
            'item_category_id' => 'required',

        ]);
        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        // dd($check);
        if(!$check){
            return response()->json(['status' => 'The auth code is incorrect !', 'auth_code_error' => false]);
            // throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }

        \DB::beginTransaction();

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
                $image_path = '';
                if(isset($request['image'][$key]) && $request['image'][$key] != ''){
                    $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                    $request->image[$key]->move(public_path('item_image'), $image_path);
                }

                if($request['item_category_id'] == 1){
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
                }else{
                    $inventory_item_data = array(
                        'inventory_id' => $data['inventory_id'],
                        'item_type_id' => $value,
                        'description' => $data['description'][$key],
                        'image' => $image_path,
                        'item_name' => $data['item_name'][$key],
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s')
                    );
                }

                $inventory_item = Inventory_item::insertGetId($inventory_item_data);
            //  $data['inventory_item_id'] = $inventory_item->id;
                $ticket_item_array = array(
                    'ticket_id' => $data['ticket_id'],
                    'inventory_item_id' => $inventory_item,
                    'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=> date('Y-m-d H:i:s')
                );
                $ticket_item_array['item_type_appraised_value'] = $request['item_category_id'] == 1 ? $data['item_type_appraised_value'][$key] : 0;
                $ticket_item = Ticket_item::insert($ticket_item_array);
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
            return response()->json(['success' => true, 'link' => route('pawn.show', $data['ticket_id'])]);
        
        }catch(\PDOException $e){
            \DB::rollBack();
            //  dd($e->getMessage());
            return response()->json(['status' => $e->getMessage(), 'success' => false]);

        }    
    }
    public function pawnPrint(MC_TableFpdf $fpdf, Request $request){
        // dd($request->id);
        $ticket_id = $request['ticket_id'];
        $data = Inventory::with(['pawnTickets' => function($query) use ($ticket_id){
            $query->where('pawnTickets.id', '=', $ticket_id);
        }, 'pawnTickets.encoder', 'pawnTickets.other_charges'])
        ->with(['customer', 'inventoryItems', 'inventoryItems.item_type', 'branch' , 'pawnTickets.attachment'])
        ->find($request->id);
        // dd($data);
        // dd(Carbon::now());
        $formatter = new NumberFormatter("en", \NumberFormatter::SPELLOUT);

        $fpdf->AddPage();

        $fpdf->SetFont('Arial','',8.5);
        $fpdf->SetX(10);
        $fpdf->Write(0,'Processed By: '. $data->pawnTickets->encoder->first_name." " .$data->pawnTickets->encoder->last_name);
        
        $fpdf->SetX(75);
        $fpdf->Write(0,'OPEN Monday - Sunday 08:00 AM to 05:00 PM');
        
        $fpdf->SetX(170);
        $fpdf->Write(0,date('Y-m-d H:i:s'));
        
        $fpdf->SetFont('Arial','',13);
        $fpdf->SetXY(97,20);
        $fpdf->Write(0,'Sanglaan');
        
        $fpdf->SetFont('Arial','',10);
        $fpdf->SetXY(20,30);
        $fpdf->Write(0,'ORIGINAL');
        
        $fpdf->SetFont('Arial','',7);
        $fpdf->SetXY(95,28);
        $fpdf->Write(0, $data->branch->branch.' Branch');
        $fpdf->SetXY(17,32);
        // $fpdf->Write(0, $data->branch->address);
        $fpdf->Cell(0, 0, $data->branch->address, 0, 0, 'C');
        
        $fpdf->SetXY(17,36);
        // $fpdf->Write(0,'FOR INQUIRY Call/Text');
        $fpdf->Cell(0, 0,'FOR INQUIRY Call/Text '.$data->branch->contact_number, 0, 0, 'C');

        $fpdf->SetXY(17,40);
        // $fpdf->Write(0,'TIN '.$data->branch->tin.' Non-Vat');
        $fpdf->Cell(0, 0,'TIN '.$data->branch->tin.' Non-Vat', 0, 0, 'C');

        $fpdf->SetFont('Arial','',10);
        $fpdf->SetXY(170,30);
        $fpdf->Write(0,'PT'. $data->pawnTickets->ticket_number);
        
        $fpdf->SetFont('Arial','',8);
        $fpdf->SetXY(10,45);
        $fpdf->Write(0,'Date Loan Granted: ');
        $fpdf->SetFont('Arial','I',8);
        $fpdf->Write(0, date('M d, Y', strtotime($data->pawnTickets->transaction_date)));
        
        $fpdf->SetFont('Arial','',8);
        $fpdf->SetXY(156,45);
        $fpdf->Write(0,'Maturity Date: ');
        $fpdf->SetFont('Arial','I',8);
        $fpdf->Write(0, date('M d, Y', strtotime($data->pawnTickets->maturity_date)));
        $fpdf->SetXY(135,50);
        $fpdf->SetFont('Arial','',8);
        $fpdf->Write(0,'Loan Redemption Expiry Date: ');
        $fpdf->SetFont('Arial','I',8);
        $fpdf->Write(0, date('M d, Y', strtotime($data->pawnTickets->expiration_date)));
        $fpdf->SetFont('Arial','',8);
        $fpdf->SetXY(10,55);
        $fpdf->Write(5,'Pawnee ');
        $fpdf->SetFont('Arial','B',8);
        $fpdf->Write(5, $data->customer->first_name." ".$data->customer->last_name." ".$data->customer->suffix);
        $fpdf->SetFont('Arial','',8);
        $fpdf->Write(5,', residing at ');
        $fpdf->SetFont('Arial','I',8);
        $fpdf->Write(5,$data->customer->present_address);
        $fpdf->SetFont('Arial','',8);
        $fpdf->Write(5,' for a loan of ');
        $fpdf->SetFont('Arial','B',8);
        $fpdf->Write(5, strtoupper($formatter->format($data->inventoryItems->sum('item_type_appraised_value'))).' (P '.number_format($data->inventoryItems->sum('item_type_appraised_value'),2 ).'),');

        $fpdf->SetFont('Arial','',8);
        $fpdf->Write(5,' with ');
        $fpdf->SetFont('Arial','I',8);
        $fpdf->Write(5,'three ');
        $fpdf->SetFont('Arial','',8);
        $fpdf->Write(5,'percent ('.number_format($data->interest_percentage,2).'%) interest per month, pledged in security for the loan as described and appraised at '.strtoupper($formatter->format($data->inventoryItems->sum('item_type_appraised_value'))).' (P '.number_format($data->inventoryItems->sum('item_type_appraised_value'),2).'), subject to the terms and conditions of the pawn.');
        
        
        $fpdf->SetXY(30,75);
        $fpdf->SetFont('Arial','B',8);
        $fpdf->Write(5,' (Description of Pawn) ');
        
        $fpdf->SetXY(10,82);
        $fpdf->SetFont('Arial','',8);
        $item = array();
        $rate = array();
        foreach($data->inventoryItems as $key => $value){

            $fpdf->MultiCell(85, 3, ucwords($value->item_type->item_type). " " .$value->item_name. " " . $value->item_karat."K " . $value->item_type_weight ."(g) (" .$value->description.") ");
            $fpdf->Ln(2);
            $item[] = ucwords($value->item_type->item_type). " " .$value->item_name. " " . $value->item_karat."K " . $value->item_type_weight ."(g) (" .$value->description.") ";
            $rate[] = "(".ucwords($value->item_type->item_type).") ".number_format($value->item_type_appraised_value,2);
        }
        /*
        while($i <= 1){
            // $fpdf->Write(5,'Gold Ring 24K 5g (The quick brown fox jumps over the lazy dog near the riverbanks) ');
            $fpdf->MultiCell(85, 3, 'Gold Ring 24K 5g (Gold with damage) ');
            $fpdf->Ln(2);
            $i++;
        }
        */
        $fpdf->Write(5,' ID Presented : '.$data->pawnTickets->attachment->type);
        $fpdf->Ln();
        $fpdf->Write(5,' ID Number : '.$data->pawnTickets->attachment_number);
        $fpdf->Ln();
        
        $fpdf->SetXY(15,142);
        $fpdf->Write(10,' ________________________________________________ ');
        $fpdf->SetXY(27,146);
        
        $fpdf->Write(10,' (Signature or Thumb Mark of Pawnee) ');
        
        
        // right
        $fpdf->SetXY(120,80);
        $fpdf->SetFont('Arial','',8);
        $fpdf->MultiCell(35, 7, 'Principal',1,'C');
        $fpdf->SetXY(120,87);
        $fpdf->MultiCell(35, 7, 'Service Charge',1,'C');
        $fpdf->SetXY(120,94);
        $fpdf->MultiCell(35, 7, 'Net Proceeds',1,'C');
        // left
        $fpdf->SetXY(155,80);
        $fpdf->SetFont('Arial','',8);
        $fpdf->MultiCell(45, 7, 'P '.number_format($data->principal,2),1,'C');
        $fpdf->SetXY(155,87);
        $fpdf->MultiCell(45, 7,'P '.number_format($data->pawnTickets->other_charges->sum('amount'), 2),1,'C');
        $fpdf->SetXY(155,94);
        $fpdf->MultiCell(45, 7, 'P '.number_format($data->net, 2),1,'C');
        
        $fpdf->SetXY(118,103);
        
        $fpdf->Write(5,' Monthly Effective Interest Rate :  '.$data->interest_percentage.'%');
        $fpdf->Ln();
        $fpdf->SetXY(118,108);
        
        $fpdf->Write(5,' Contact Number : '. $data->customer->contact_number);
        $fpdf->SetXY(118,142);
        
        $fpdf->Write(10,' ________________________________________________ ');
        $fpdf->SetXY(130,147);
        $fpdf->Write(10,' (Pawnshop Authorized Representative) ');
        
        
        $fpdf->SetXY(10,170);
        // $fpdf->Write(0,' REMINDER TO THE PAWNEE: THE PLEDGED ARTICLE(S) COVERED BY THIS CERTIFICATE WILL BE SOLD IN A PUBLIC AUCTION ON ');
        $fpdf->MultiCell(190, 4, 'REMINDER TO THE PAWNEE: THE PLEDGED ARTICLE(S) COVERED BY THIS CERTIFICATE WILL BE SOLD IN A PUBLIC AUCTION ON',0,'C');
        // $fpdf->SetFont('Arial','I',8);
        // $fpdf->MultiCell(190, 4,date('M d, Y', strtotime($_GET['auction_date'])) ,0,'C');
        
        $fpdf->MultiCell(190, 4, date('M d, Y', strtotime($data->pawnTickets->auction_date)). ' AT ABOVE ADDRESS IF NOT REDEEMED OR RENEWED WITHIN THE REDEMPTION PERIOD',0,'C');
        
        $fpdf->Ln();
        $fpdf->SetXY(45,185);
        
        $fpdf->MultiCell(125, 4, 'Pawner is advised to read and understand the Terms and Conditions on the reverse side hereof 100 Bkits (50 x 2) 001-5000 BIR Permit OCN-4AU0000377499*1-27-2006',0,'C');
        
        
        $fpdf->AddPage();

        $fpdf->SetXY(20,20);

        $fpdf->SetWidths(array(35,130));
        $fpdf->SetHeight(5);
        $fpdf->SetAligns(array('C','L'));
        $fpdf->Row(array('PT# : ', $data->pawnTickets->ticket_number));
        $fpdf->SetX(20);
        $fpdf->Row(array('Date of Loan : ',  date('M d, Y', strtotime($data->pawnTickets->transaction_date))));
        $fpdf->SetX(20);
        $fpdf->Row(array('Maturity Date : ',  date('M d, Y', strtotime($data->pawnTickets->maturity_date))));
        $fpdf->SetX(20);
        $fpdf->Row(array('Name : ',  strtoupper($data->customer->first_name). " " .strtoupper($data->customer->last_name). " " .strtoupper($data->customer->suffix)));
        $fpdf->SetX(20);
        $fpdf->Row(array('Birthdate : ',  date('M d, Y', strtotime($data->customer->birthdate))));
        $fpdf->SetX(20);
        $fpdf->Row(array('Address : ',  $data->customer->present_address));
        $fpdf->SetX(20);
        $fpdf->Row(array('Description of Pawn : ', implode("\n", $item)));
        $fpdf->SetX(20);
        $fpdf->Row(array('Pledge Loan : ',  number_format($data->pawnTickets->net,2)));
        $fpdf->SetX(20);
        $fpdf->Row(array('Signature : ',  " "));
        $fpdf->SetX(20);
        $fpdf->Row(array('Contact Number : ', $data->customer->contact_number));
        $fpdf->SetX(20);
        $fpdf->Row(array('Appraiser : ', 'XXX, '. $data->pawnTickets->encoder->first_name));
        $fpdf->SetX(20);
        $fpdf->Row(array('CPG : ', implode("\n" , $rate)));

        $pdfContent = $fpdf->Output('', "S");
    
         return response($pdfContent, 200,
        [
            'Content-Type'        => 'application/pdf',
            'Content-Length'      =>  strlen($pdfContent),
            'Cache-Control'       => 'private, max-age=0, must-revalidate',
            'Pragma'              => 'public'
        ]);
    }

    public function pawnPrint_test(Request $request){
    $ticket_id = $request['ticket_id'];
    // dd($ticket_id);
    $data = Inventory::with(['pawnTickets' => function($query) use ($ticket_id){
        $query->where('id', '=', $ticket_id);
    }, 'pawnTickets.encoder', 'pawnTickets.other_charges', 'pawnTickets.item_tickets','pawnTickets.attachment',
     'pawnTickets.pawn_tickets', 'pawnTickets.pawn_tickets.ticket_items', 'pawnTickets.pawn_tickets.ticket_items.inventory_items',
     'pawnTickets.pawn_tickets.ticket_items.inventory_items.item_type', 'pawnTickets.pawn_tickets.ticket_parent', 'pawnTickets.payment'])
    ->with(['customer', 'branch'])
    ->where('id', $request->id)
    ->first();
    $formatter = new NumberFormatter("en", \NumberFormatter::SPELLOUT);

    // dd($data);
    // dd($data->pawnTickets->item_tickets->sum('item_name_appraised_value'));
    $pdf = \PDF::loadView('pdf.pawn', array('data' => $data, 'formatter' => $formatter));
    return $pdf->inline();


    }

    public function show(Request $request)
    {
        //
        $id = $request->id;
        // $ticket = Ticket::with(['encoder', 'attachment', 'payment'])->where('inventory_id', $request->id)->get();
        // dd($ticket);
        // $inventory = Inventory::with('customer', 'branch', 'item_category', 'inventoryItems', 'item.item_type')->findOrFail($request->id);
        $ticket = Ticket::with('item_tickets', 'item_tickets.inventory_items')->find($request->id);
        // dd($ticket);
        // dd($ticket);
        // dd($ticket->pawn_parent->ticket_child);
        // $current_pawn = Ticket::whereIn('transaction_type', array('pawn', 'repawn'))->where([['status', '=', 0], ['inventory_id', $request->id]])->first();
        // $ticket_update = Ticket::where('id', $request->id)->first()->pawn_parent->latest()->first();
        $ticket_update = Ticket::with(['pawn_parent' => function($query){
            $query->orderBy('ticket_id', 'DESC');
        }])->where('id', $request->id)->first();
        // dd($ticket_update->pawn_parent->ticket_id);
        // dd($inventory);
        // dd($current_pawn);
        return view('view_pawn', compact('id', 'ticket', 'ticket_update'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PawnAuction  $pawnAuction
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // dd($request);
        //
        // $attachment = Inventory::with('pawnTickets')->find($request->id);
        // $data = Inventory::with(['customer', 'pawnTickets','inventoryItems', 'pawnTickets.other_charges', 'pawnTickets.other_charges.inventory_other_charges', 'pawnTickets.attachment', 'customer.attachments', 'inventoryItems.ticket_item'])
        // ->find($request->id);
        // dd($data->inventoryItems);
        $data = Ticket::with('item_tickets', 'inventory')->findorFail($request->id);
        $branch = \Auth::user()->isAdmin() ? Branch::all() : Branch::where('id', \Auth::user()->branch_id)->get();

        // dd($data);
        $item_type_data = Item_type::where('item_category_id', $data->inventory->item_category_id)->get();
        foreach($data->item_tickets as $key => $value){
            $rate_data[] = Rate::where('item_type_id', $value->inventory_items->item_type_id)->where('branch_id', $data->inventory->branch_id)->orderBy('id')->get();
        }
        // dd($rate_data);
        $other_charges['discount'] = 0;
        $other_charges['charges'] = 0;
        foreach($data->other_charges as $key => $value){
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
        return view('form_inventory', compact('data', 'item_type_data', 'rate_data', 'other_charges', 'branch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PawnAuction  $pawnAuction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $data = $request->validate([
            'inventory_number' => 'required',
            'transaction_status' => 'required',
            'customer_id' => 'required',
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
            'image.*' => 'required',
        ]);
        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        // dd($check);
        if(!$check){
            return response()->json(['status' => 'The auth code is incorrect !', 'auth_code_error' => false]);
            // throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }
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
            $inventory = Inventory::findOrfail($request->inventory_id)->update($inventory_data->only('inventory_number','transaction_status','customer_id','item_category_id', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date')->toArray());
            $ticket = Ticket::findOrfail($request->id)->update($inventory_data->only('attachment_id','transaction_date','maturity_date','expiration_date','auction_date', 'net', 'attachment_number', 'is_special_rate', 'appraised_value', 'principal', 'charges', 'discount')->toArray());
            //  $request['ticket_id'] = $ticket->id;
             foreach($data['item_type_id'] as $key => $value){


                 if(isset($request['inventory_item_id'][$key])){
                    if($request['item_category_id'] == 1){ // jewelry
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
                         $ticket_item_data = array(
                            'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                            'item_name_appraised_value' => $data['item_name_appraised_value'][$key]
                         );
                    }else{
                        $inventory_item_data = array(
                            'item_type_id' => $value,
                            'description' => $data['description'][$key],
                            'item_name' => $data['item_name'][$key],
                            'updated_at'=> date('Y-m-d H:i:s')
                         );
                         $ticket_item_data = array(
                            'item_type_appraised_value' => 0,
                            'item_name_appraised_value' => $data['item_name_appraised_value'][$key]

                         );
                    }

                     $inventory_item = Inventory_item::findOrFail($request['inventory_item_id'][$key]);
                     if($request['image'][$key] != null && $request['image'][$key] != $inventory_item->image){
                        $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                        $request->image[$key]->move(public_path('item_image'), $image_path);
                        $inventory_item_data['image'] = $image_path;
                        // $image_array = 'image' => $image_path;
                     }

                    $inventory_item_update = Inventory_item::findOrfail($request['inventory_item_id'][$key])
                            ->update($inventory_item_data);
                    $ticket_item = Ticket_item::findOrFail($request['ticket_item_id'][$key])
                            ->update($ticket_item_data);
                 }else{
                        $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                        $request->image[$key]->move(public_path('item_image'), $image_path);
                        if($request['item_category_id'] == 1){ // jewelry
                            $inventory_item_data = array(
                                'inventory_id' => $request['inventory_id'],
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
                            $ticket_item_data = array(
                                'ticket_id' => $request['id'],
                                'inventory_item_id' => $inventory_item,
                                'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                                'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=> date('Y-m-d H:i:s')
                            );
                            $ticket_item = Ticket_item::insert($ticket_item_data);
                        }else{
                            $inventory_item_data = array(
                                'inventory_id' => $request['inventory_id'],
                                'item_type_id' => $value,
                                'description' => $data['description'][$key],
                                'image' => $image_path,
                                'item_name' => $data['item_name'][$key],
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=> date('Y-m-d H:i:s')
                            );
                            $inventory_item = Inventory_item::insertGetId($inventory_item_data);
                            $ticket_item_data = array(
                                'ticket_id' => $request['id'],
                                'inventory_item_id' => $inventory_item,
                                'item_type_appraised_value' => 0,
                                'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                                'created_at'=>date('Y-m-d H:i:s'),
                                'updated_at'=> date('Y-m-d H:i:s')
                            );
                            $ticket_item = Ticket_item::insert($ticket_item_data);
                        }

                 }

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
             return response()->json(['success' => true, 'link' => route('pawn.show', $data['ticket_id'])]);
            //  return response()->json(['success' => true]);
         }catch(\PDOException $e){
             \DB::rollBack();
             //  dd($e->getMessage());
              return response()->json(['status' => $e->getMessage(), 'success' => false]);

         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PawnAuction  $pawnAuction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pawn $pawnAuction)
    {
        //
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
        }])->find($pawn_id); // for renewal table
        // dd($tickets);
        // $ticket_payment = Inventory::with(['pawnTickets','pawnTickets.pawn_parent', 'pawnTickets.pawn_parent.ticket_net']) // payment and net
        // ->find($request['id']);

        
        $payment = isset($inventory->inventory_payment) ? $inventory->inventory_payment->where('inventory_id', $request->id)->sum('amount') : NULL;
        // $total_net =  isset($ticket_payment->pawnTickets->pawn_parent) ? $ticket_payment->pawnTickets->pawn_parent->ticket_net
        // ->where(function($query) use ($request){
        //         $query->where('inventory_id', $request->id)
        //             ->where('status' , 0);
        // })->whereNotIn('transaction_type', ['pawn','repawn'])->sum('net') : NULL;
        $total_net = $tickets->where('inventory_id', $request->id)->where('status', 0)->whereNotIn('transaction_type', ['pawn','repawn'])->sum('net');
        $prev_balance = isset($tickets) ? $total_net - $payment : NULL;
        // dd($total_net);
        // dd($tickets->pawn_parent->payment->sum('amount'));
        $tickets_latest = Ticket::where('inventory_id', $request->id)->latest()->first();   
        $id = $request->id;
        $or = Payment::max('or_number') + 1;  
        $or_number = sprintf('%05d', $or);

        // dd($ticket_payment);
        return view('form_renew', compact('ticket_number', 'inventory', 'tickets', 'tickets_latest', 'id', 'or_number', 'pawn_id', 'prev_balance'));
    }

    public function showUpdateRenew(Request $request){
        // dd($request);
        $ticket_id = $request['ticket_id'];
        $id = $request->id;
        // $payment_display = Pawn_ticket::where('ticket_id', $request['ticket_id'])->first();
        // dd($payment_display);
        // $pawn_id = $payment_display->pawn_id;
        // $request['pawn_id'] = $pawn_id;
        


        // $request['pawn_id'] = $inventory->pawnTickets->where('inventory_id', $request->id)->whereIn('transaction_type', array('pawn', 'repawn'))->where('status', 0)->first()->id;
        // dd($request['pawn_id']);


        $tickets_latest = Ticket::where('inventory_id', $request->id)->latest()->first();
        $tickets_current = Inventory::with(['pawnTickets' => function($query) use ($ticket_id){
            $query->where('id', $ticket_id);
        }, 'pawnTickets.other_charges', 'pawnTickets.other_charges.inventory_other_charges', 'pawnTickets.pawn_tickets', 'pawnTickets.payment'])->find($request->id);
        // dd(Inventory::find($request->id)->pawnTickets->where('id', '<', $ticket_id)->get(); // get the prev record
        // dd($ticket_id);
        $tickets_prev = Ticket::where('inventory_id', $request->id)->where('id', '<', $ticket_id)->orderBy('id', 'desc')->first(); // get the prev record
        // dd($tickets_prev);
        $pawn_id = $tickets_current->pawnTickets->pawn_tickets->pawn_id;
        $request['pawn_id'] = $tickets_current->pawnTickets->pawn_tickets->pawn_id;
        $inventory = Inventory::with(['pawnTickets' => function($query) use ($pawn_id){
            $query->where('id', $pawn_id);
        },'pawnTickets.encoder', 'pawnTickets.item_tickets', 'pawnTickets.item_tickets.inventory_items'])
        ->with(['branch' , 'customer', 'customer.attachments', 'item_category'])
        ->find($request->id);
        $ticket_original = Ticket::find($request['pawn_id']);

        $tickets = Ticket::where('inventory_id', $request->id)->with(['pawn_parent_many' => function($query) use ($request){
            $query->whereNotIn('ticket_id', [$request->ticket_id, $request->pawn_id]);
        }, 'pawn_parent_many.ticket_child' => function($query){
            $query->where('transaction_type', 'renew');
        }])->first(); 
        // for renewal table

        $net = $inventory->pawnTickets()->whereNotIn('transaction_type', ['pawn', 'repawn'])->whereNotIn('id', [$request['ticket_id']])->sum('net');
        // dd($net);   
        $payment_balance = $inventory->inventory_payment->where('inventory_id', $request->id)->sum('amount') - $tickets_current->pawnTickets->payment->amount;
        $prev_balance =  round($net,2) - round($payment_balance,2);
        // dd($net); 

            return view('form_renew', compact('inventory', 'tickets', 'tickets_latest', 'tickets_current', 'id', 'prev_balance', 'pawn_id', 'ticket_id', 'ticket_original', 'tickets_prev'));

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
        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        // dd($check);
        if(!$check){
            return response()->json(['status' => 'The auth code is incorrect !', 'auth_code_error' => false]);
            // throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }
        try{
            \DB::beginTransaction();
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
            $remove_notice = Notice::where('ticket_id', $request->pawn_id)->update(array('status' => 1));
            $pawn_ticket_id = $pawn_ticket->id;
            $payment = Payment::create(
                array(
                    'transaction_type' => $request['transaction_type'],
                    'ticket_id' => $request['ticket_id'],
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
            $fetch_id = Ticket::where('inventory_id', $request['inventory_id'])->whereIn('transaction_type', ['pawn', 'repawn'])->where('status', 0)->first();

            return response()->json(['success' => true, 'link' => route('pawn.show', $fetch_id->id)]);
        }catch(\PDOException $e){
            \DB::rollBack();
            //  dd($e->getMessage());
             return response()->json(['status' => $e->getMessage(), 'success' => false]);
        
        }

    }

    public function updateRenew(Request $request){
        // dd($request);
        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        // dd($check);
        if(!$check){
            return response()->json(['status' => 'The auth code is incorrect !', 'auth_code_error' => false]);
            // throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }
        try{
            \DB::beginTransaction();
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
            $fetch_id = Ticket::where('inventory_id', $request['inventory_id'])->whereIn('transaction_type', ['pawn', 'repawn'])->where('status', 0)->first();
            return response()->json(['success' => true, 'link' => route('pawn.show', $fetch_id['id'])]);
        }catch(\PDOException $e){
            \DB::rollBack();
            //  dd($e->getMessage());
             return response()->json(['status' => $e->getMessage(), 'success' => false]);
        
        }

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
        }])->find($pawn_id); // for renewal table
        // dd($tickets);
        $ticket_payment = Inventory::with(['pawnTickets','pawnTickets.pawn_parent']) // payment and net
        ->find($request['id']);
        $id = $request->id;
        // dd($ticket_payment);
        $payment = isset($ticket_payment->inventory_payment) ? $ticket_payment->inventory_payment->where('inventory_id', $request->id)->sum('amount') : NULL;

        // dd($payment);
        $total_net = $tickets->where('inventory_id', $request->id)->where('status', 0)->whereNotIn('transaction_type', ['pawn','repawn'])->sum('net');
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
        $data = $request->validate([
            'transaction_date' => 'required',
            'attachment_number' => 'required',
            'attachment_id' => 'required',
            'payment' => 'required'
        ]);
        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        // dd($check);
        if(!$check){
            return response()->json(['status' => 'The auth code is incorrect !', 'auth_code_error' => false]);
            // throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }
        try{
            \DB::beginTransaction();
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
            $remove_notice = Notice::where('ticket_id', $request->pawn_id)->update(array('status' => 1));

            $pawn_ticket_id = $pawn_ticket->id;
            $payment = Payment::create(
                array(
                    'transaction_type' => $request['transaction_type'],
                    'ticket_id' => $request['ticket_id'],
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
            $fetch_id = Ticket::where('inventory_id', $request['inventory_id'])->whereIn('transaction_type', ['pawn', 'repawn'])->latest()->first();

            return response()->json(['success' => true, 'link' => route('pawn.show', $fetch_id['id'])]);


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
        // $payment_display = Pawn_ticket::with('pawn_ticket_payment')->where('ticket_id', $request['ticket_id'])->first();


        // $request['pawn_id'] = $inventory->pawnTickets->where('inventory_id', $request->id)->whereIn('transaction_type', array('pawn', 'repawn'))->where('status', 0)->first()->id;
        // dd($request['pawn_id']);


        $tickets_latest = Ticket::where('inventory_id', $request->id)->whereNotIn('id', [$ticket_id])->latest()->first();
        $tickets_current = Inventory::with(['pawnTickets' => function($query) use ($ticket_id){
            $query->where('id', $ticket_id);
        }, 'pawnTickets.other_charges', 'pawnTickets.other_charges.inventory_other_charges', 'pawnTickets.pawn_tickets'])->find($request->id);
        $tickets_prev = Ticket::where('inventory_id', $request->id)->where('id', '<', $ticket_id)->orderBy('id', 'desc')->first(); // get the prev record
        $pawn_id = $tickets_current->pawnTickets->pawn_tickets->pawn_id;
        $request['pawn_id'] = $pawn_id;
        $ticket_original = Ticket::find($request['pawn_id']);
        $inventory = Inventory::with(['pawnTickets' => function($query) use ($pawn_id){
            $query->where('id', $pawn_id);
        },'pawnTickets.encoder', 'pawnTickets.item_tickets', 'pawnTickets.item_tickets.inventory_items'])
        ->with(['branch' , 'customer', 'customer.attachments', 'item_category'])
        ->find($request->id);
        // dd($pawn_id);
        $tickets = Ticket::where('inventory_id', $request->id)->with(['pawn_parent_many' => function($query) use ($request){
            $query->whereNotIn('ticket_id', [$request->ticket_id, $request->pawn_id]);
        }, 'pawn_parent_many.ticket_child' => function($query){
            $query->where('transaction_type', 'renew');
        }])->first(); 
        // for renewal table

        $net = $inventory->pawnTickets()->whereNotIn('transaction_type', ['pawn', 'repawn'])->whereNotIn('id', [$request['ticket_id']])->sum('net');
        // dd($net);   
        $payment_balance = $inventory->inventory_payment->where('inventory_id', $request->id)->sum('amount') - $tickets_current->pawnTickets->payment->amount;
        $prev_balance =  round($net,2) - round($payment_balance,2);
        // dd($net); 

            return view('form_redeem', compact('inventory', 'tickets', 'tickets_latest', 'tickets_current', 'id', 'prev_balance', 'pawn_id', 'ticket_id', 'ticket_original', 'tickets_prev'));

    }

    public function updateRedeem(Request $request){
        // dd($request);
        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        // dd($check);
        
        if(!$check){
            return response()->json(['status' => 'The auth code is incorrect !', 'auth_code_error' => false]);
            // throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }
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
            $fetch_id = Ticket::where('inventory_id', $request['inventory_id'])->whereIn('transaction_type', ['pawn', 'repawn'])->first();

            return response()->json(['success' => true, 'link' => route('pawn.show', $fetch_id['id'])]);
        }catch(\PDOException $e){
            \DB::rollBack();
            //  dd($e->getMessage());
             return response()->json(['status' => $e->getMessage(), 'success' => false]);
        
        }
    }

    public function repawn(Request $request){
        $ticket = Ticket::max('ticket_number') + 1;  
        $ticket_number = sprintf('%05d', $ticket);
        $inventory = Inventory::with(['pawnTickets' => function($query){
            $query->whereIn('transaction_type', ['pawn', 'repawn'])
                    ->where('repawn', 0);
        }, 'pawnTickets.item_tickets'])->find($request->id);
        $item_type_data = Item_type::where('item_category_id', $inventory->item_category_id)->get();
        // dd($item_type_data);
        // dd($inventory);
        $karat_rate = array();
        foreach($inventory->pawnTickets->item_tickets as $key => $value){
            $rate_data[] = Rate::where('item_type_id', $value->inventory_items->item_type_id)->where('branch_id', $inventory->branch_id)->orderBy('id')->get();
            // $rate_data[] = Rate::where('item_type_id', $value->item_type_id)->where('branch_id', \Auth::user()->branch_id)->orderBy('id')->get();

            if($inventory->item_category_id == 1){
                $rate = Rate::where('item_type_id', $value->inventory_items->item_type_id)->where('branch_id', $inventory->branch_id)->where('karat', $value->inventory_items->item_karat)->orderBy('id')->first();
                $price[] = ($value->inventory_items->item_type_weight * $rate->gram) * $rate->regular_rate;
            }else{
                $rate = Rate::where('item_type_id', $value->inventory_items->item_type_id)->where('branch_id', $inventory->branch_id)->where('description', $value->inventory_items->item_name)->orderBy('id')->first();
                $price[] = $rate->regular_rate;
            }
            $item_type[] = Item_type::find($value->inventory_items->item_type_id);
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
        return view('form_repawn', compact('ticket_number', 'inventory', 'item_type_data', 'price', 'item_type', 'karat_rate', 'tickets_latest', 'rate_data'));
    }

    public function submitRepawn(Request $request){
        // dd($request);
        try{
            // dd(($request['item_type_id']));
            \DB::beginTransaction();
            $data = $request->validate([
                'transaction_type' => 'required',
                'transaction_status' => 'required',
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
                'item_name_appraised_value.*' => 'required',
                'image.*' => 'required',
                'item_name.*' => 'required',
                'item_name_weight.*' => 'required',
                'item_karat.*' => 'required',
                'item_karat_weight.*' => 'required',                        
                'item_type_weight.*' => 'required',
                'description.*' => 'required',
                'item_type_id.*' => 'required',
            ]);
            $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
            // dd($check);
            
            if(!$check){
                return response()->json(['status' => 'The auth code is incorrect !', 'auth_code_error' => false]);
                // throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
            }
            $data['is_special_rate'] = isset($request['is_special_rate']) ? $request['is_special_rate'] : 0;
            $data['net'] = $request['net_proceeds'];
            $data['transaction_date'] = date('Y-m-d', strtotime($request['transaction_date']));
            $data['maturity_date'] = date('Y-m-d', strtotime($request['maturity_date']));
            $data['expiration_date'] = date('Y-m-d', strtotime($request['expiration_date']));
            $data['auction_date'] = date('Y-m-d', strtotime($request['auction_date']));
            $data['charges'] = $request['other_charges'];
            $data['discount'] = $request['discount'];
            // $data['repawn'] = 1;
            $ticket_update = Ticket::where('inventory_id', '=', $request['inventory_id'])->whereIn('transaction_type', ['pawn', 'repawn'])->update(array('repawn' => 1));
            $inventory_data = collect($data);
            $inventory = Inventory::findOrFail($request['inventory_id'])->update($inventory_data->only('transaction_status', 'ticket_number', 'processed_by', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date')->toArray());
            // $Inventory = Inventory::create($inventory_data->only('inventory_number','transaction_status','customer_id','branch_id','item_category_id','ticket_number', 'processed_by', 'transaction_date', 'maturity_date', 'expiration_date', 'auction_date')->toArray());
            $data['inventory_id'] = $request['inventory_id'];

            $ticket_data = collect($data);
            $ticket = Ticket::create($ticket_data->only('transaction_type','inventory_id', 'attachment_id','ticket_number','transaction_date','maturity_date','expiration_date','auction_date','processed_by','appraised_value', 'principal', 'net', 'attachment_number', 'discount', 'charges', 'is_special_rate')->toArray());
            $data['ticket_id'] = $ticket->id;
            $pawn_ticket = Pawn_ticket::create(array('pawn_id' => $data['ticket_id'], 'ticket_id' => $data['ticket_id']));
             foreach($data['item_name_appraised_value'] as $key => $value){
                 if(!isset($request['inventory_item_id'][$key])){

                    if($request['item_category_id'] == 1){ // jewelry
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
                        $ticket_item_data = array(
                            'ticket_id' => $data['ticket_id'],
                            'inventory_item_id' => $inventory_item,
                            'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                            'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                            'item_status' => 'new',
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=> date('Y-m-d H:i:s')
                        );
                        $ticket_item = Ticket_item::insert($ticket_item_data);
                    }else{
                        $item_data = $request->validate([
                            'image.'.$key.'' => 'required',
                            'item_name.'.$key.'' => 'required',
                            'description.'.$key.'' => 'required',
                            'item_type_id.'.$key.'' => 'required',
                        ]);
                        $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                        $request->image[$key]->move(public_path('item_image'), $image_path);
                        $inventory_item_data = array(
                            'inventory_id' => $data['inventory_id'],
                            'item_type_id' => $item_data['item_type_id'][$key],
                            'description' => $item_data['description'][$key],
                            'image' => $image_path,
                            'item_name' => $item_data['item_name'][$key],
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=> date('Y-m-d H:i:s')
                        );
                        $inventory_item = Inventory_item::insertGetId($inventory_item_data);
                        $ticket_item_data = array(
                            'ticket_id' => $data['ticket_id'],
                            'inventory_item_id' => $inventory_item,
                            'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                            'item_type_appraised_value' => 0,
                            'item_status' => 'new',
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=> date('Y-m-d H:i:s')
                        );
                        $ticket_item = Ticket_item::insert($ticket_item_data);
                    }

                 }else{
                     $ticket_item_data = array(
                        'ticket_id' => $data['ticket_id'],
                        'inventory_item_id' => $request['inventory_item_id'][$key],
                        'item_name_appraised_value' => $data['item_name_appraised_value'][$key],
                        'item_status' => 'old',
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s')
                     );
                     $ticket_item_data['item_type_appraised_value'] = $request['item_category_id'] == 1 ? $data['item_type_appraised_value'][$key] : 0;
                    $ticket_item = Ticket_item::insert($ticket_item_data);
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
             return response()->json(['success' => true, 'link' => route('pawn.show', $ticket->id)]);
            
         }catch(\PDOException $e){
             \DB::rollBack();
             //  dd($e->getMessage());
              return response()->json(['status' => $e->getMessage(), 'success' => false]);

         }    
    }





    public function showUpdateRepawn(Request $request){

        // $ticket = Ticket::max('ticket_number') + 1;  
        // $ticket_number = sprintf('%05d', $ticket);
        $inventory = Inventory::with(['pawnTickets' => function($query) use ($request){
            $query->where('id', $request->ticket_id);
        }, 'pawnTickets.item_tickets'])->find($request->id);
        $item_type_data = Item_type::where('item_category_id', $inventory->item_category_id)->get();
        $karat_rate = array();
        foreach($inventory->pawnTickets->item_tickets as $key => $value){
            $rate_data[] = Rate::where('item_type_id', $value->inventory_items->item_type_id)->where('branch_id', $inventory->branch_id)->orderBy('id')->get();
            // $rate[] = Rate::where('item_type_id', $value->item_type_id)->where('branch_id', \Auth::user()->branch_id)->orderBy('id')->get();
            // $price[] = ($value->item_type_weight * $rate->gram) * $rate->regular_rate;
            $item_type[] = Item_type::find($value->inventory_items->item_type_id);
            // $karat_rate['gram'][] = $rate->gram;
            // $karat_rate['regular_rate'][] = $rate->regular_rate;
            // $karat_rate['special_rate'][] = $rate->special_rate;
        }
        // dd($item_type_data);
        $tickets_latest = Ticket::where('inventory_id', $request->id)->whereNotIn('id', [$request->ticket_id])->latest()->first();
        $tickets_current = Ticket::with('inventory', 'item_tickets', 'item_tickets.inventory_items', 'other_charges')->find($request->ticket_id);
        // $inventory->inventoryItems = Ticket::find($request->ticket_id)->item_tickets;
        // dd($rate);
        // dd($request->id);
        return view('form_repawn', compact('inventory', 'item_type_data', 'item_type', 'karat_rate', 'tickets_latest', 'tickets_current', 'rate_data'));

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
            'item_name_appraised_value.*' => 'required',
            'image.*' => 'required',
            'item_name.*' => 'required',
            'item_name_weight.*' => 'required',
            'item_karat.*' => 'required',
            'item_karat_weight.*' => 'required',                        
            'item_type_weight.*' => 'required',
            'description.*' => 'required',
            'item_type_id.*' => 'required',
        ]);
        $check = User::where('auth_code', $request->user_auth_code)->find(\Auth::user()->id);
        // dd($check);
        if(!$check){
            return response()->json(['status' => 'The auth code is incorrect !', 'auth_code_error' => false]);
            // throw ValidationException::withMessages(['auth_code_error' => 'The auth code is incorrect !']);
        }
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
            foreach($data['item_name_appraised_value'] as $key => $value){
                if(!isset($request['inventory_item_id'][$key])){
                    if($request['item_category_id'] == 1){
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
                        $item_data = $request->validate([
                            'image.'.$key.'' => 'required',
                            'item_name.'.$key.'' => 'required',
                            'description.'.$key.'' => 'required',
                            'item_type_id.'.$key.'' => 'required',
                        ]);
                        $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                        $request->image[$key]->move(public_path('item_image'), $image_path);
            
                        $inventory_item_data = array(
                            'inventory_id' => $request['inventory_id'],
                            'item_type_id' => $item_data['item_type_id'][$key],
                            'description' => $item_data['description'][$key],
                            'image' => $image_path,
                            'item_name' => $item_data['item_name'][$key],
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=> date('Y-m-d H:i:s')
                        );
                        $inventory_item = Inventory_item::insertGetId($inventory_item_data);
                        $ticket_item = Ticket_item::insert(array(
                            'ticket_id' => $request['id'],
                            'inventory_item_id' => $inventory_item,
                            'item_name_appraised_value' => 0,
                            'item_type_appraised_value' => $data['item_type_appraised_value'][$key],
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=> date('Y-m-d H:i:s')
                        ));
                    }

                }else{
                    if($request['item_status'][$key] == 'new'){
                        if($request['item_category_id'] == 1){
                            $item_data = $request->validate([
                                'item_name.'.$key.'' => 'required',
                                'item_name_weight.'.$key.'' => 'required',
                                'item_karat.'.$key.'' => 'required',
                                'item_karat_weight.'.$key.'' => 'required',                        
                                'item_type_weight.'.$key.'' => 'required',
                                'description.'.$key.'' => 'required',
                                'item_type_id.'.$key.'' => 'required',
                            ]);
                            $item_array = array(
                                'item_type_id' => $item_data['item_type_id'][$key],
                                'item_type_weight' => $item_data['item_type_weight'][$key],
                                'description' => $item_data['description'][$key],
                                'item_name' => $item_data['item_name'][$key],
                                'item_name_weight' => $item_data['item_name_weight'][$key],
                                'item_karat' => $item_data['item_karat'][$key],
                                'item_karat_weight' => $item_data['item_karat_weight'][$key],
                            );
                            $inventory_item = Inventory_item::findOrFail($request['inventory_item_id'][$key]);
                            if($request['image'][$key] != null && $request['image'][$key] != $inventory_item->image){
                               $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                               $request->image[$key]->move(public_path('item_image'), $image_path);
                               $item_array['image'] = $image_path;
                               // $image_array = 'image' => $image_path;
                            }
                            $update_item = Inventory_item::find($request['inventory_item_id'][$key])
                            ->update($item_array);
                        }else{
                            $item_data = $request->validate([
                                'item_name.'.$key.'' => 'required',
                                'description.'.$key.'' => 'required',
                                'item_type_id.'.$key.'' => 'required',
                            ]);
                            $item_array = array(
                                'item_type_id' => $item_data['item_type_id'][$key],
                                'description' => $item_data['description'][$key],
                                'item_name' => $item_data['item_name'][$key],
                            );
                            $inventory_item = Inventory_item::findOrFail($request['inventory_item_id'][$key]);
                            if($request['image'][$key] != null && $request['image'][$key] != $inventory_item->image){
                                $image_path = $key.'_'.time().'.'.$request->image[$key]->extension(); 
                                $request->image[$key]->move(public_path('item_image'), $image_path);
                                $item_array['image'] = $image_path;
                                // $image_array = 'image' => $image_path;
                             }
                            $update_item = Inventory_item::find($request['inventory_item_id'][$key])
                            ->update($item_array);
                        }
                    }
                    $ticket_item_array = array(
                        'item_name_appraised_value' => $data['item_name_appraised_value'][$key]
                    );
                    $ticket_item_array['item_type_appraised_value'] = $request['item_category_id'] == 1 ? $data['item_type_appraised_value'][$key] : 0;
                   $ticket_item = Ticket_item::findOrFail($request['ticket_item_id'][$key])->update($ticket_item_array);
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
            //  return response()->json(['success' => true]);
             return response()->json(['success' => true, 'link' => route('pawn.show', $ticket->id)]);

         }catch(\PDOException $e){
             \DB::rollBack();
             //  dd($e->getMessage());
              return response()->json(['status' => $e->getMessage(), 'success' => false]);

         }

    }
    public function auction(Request $request){
        // dd($request);
        $ticket = Ticket::find($request->id);
        $inventory = Inventory::findOrfail($ticket->inventory_id)->update(
            array(
                'status' => 1
            )
        );
        $inventory_auction = InventoryAuction::create(array(
            'inventory_id' => $ticket->inventory_id,
            'ticket_id' => $request->id,
            'inventory_auction_number' => $request['inventory_auction_number'],
            'price' => $request['price']
        ));
        /*
        $inventory_item = Inventory_item::where([
            ['inventory_id', '=', $request->inventory_id],
            ['status', '=', 0]
            ])->update(
                array(
                    'status' => 1
                )
            );
            */
            return response()->json(['success' => true]);
        }

}
