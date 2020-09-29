<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MC_TableFpdf;
use App\Branch;
use DataTables;
use App\Ticket;
use App\Inventory;
use App\Notice;

class NoticeListingController extends Controller
{
    //

    public function index(Request $request){
        $branch = Branch::all();
        $date = $request['date'];
        $branch_value = $request['branch'];
        if ($request->ajax()){
            $dateYear = date('Y', strtotime($request['date']));
            $dateMonth = date('m', strtotime($request['date']));
            $branch_value = $request['branch'];
            /*
            $notice_listing = \DB::table('tickets')
                                ->select(\DB::raw('tickets.ticket_number, CONCAT(customers.first_name, " ", customers.last_name) as customer, inventories.transaction_date, customers.present_address, tickets.id as ticket_id'))
                                ->join('inventories', 'inventories.id', '=', 'tickets.inventory_id')
                                ->join('customers', 'customers.id', '=', 'inventories.customer_id')
                                ->whereNull('notice_date')
                                ->where('tickets.expiration_date', '<=', date('Ymd'))
                                ->get();
        */
            $notice_listing = \DB::table('inventories')
            ->select(\DB::raw('pawn.ticket_number, CONCAT(customers.first_name, " ", customers.last_name) as customer, inventories.transaction_date, customers.present_address, pawn.id as ticket_id'))
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
            ->whereMonth('tickets.expiration_date', '<=', $dateMonth)
            ->whereYear('tickets.expiration_date', '<=', $dateYear)
            ->whereNull('notices.notice_date')
            ->where('inventories.branch_id', '=', $branch_value)
            ->where('inventories.status', 0)
                ->get();                       
                return Datatables::of($notice_listing)
                        ->addIndexColumn()
                        ->editColumn('transaction_date', function($row){
                            return date('M d, Y', strtotime($row->transaction_date));
                        })
                        ->addColumn('checkbox', function($row){
                            return '<input type="checkbox" name="notice" value="'.$row->ticket_id.'" class="item" onClick="toggleState()"/>';
                        })
                        ->rawColumns(['action', 'checkbox'])
                        ->make(true);
            }
        
        return view('notice_listing', compact('branch', 'date','branch_value'));
    }

    public function store(Request $request){
        $ids = json_decode($request->id);
        // dd($id);
        $yr = date('y');
        $notice_ctrl = Notice::max('notice_ctrl') + 1;
        $notice_ctrl_number = sprintf('%05d', $notice_ctrl);
        $jewelry_auction_date = date('Y-m-d', strtotime($request['jewelry_date']));
        $non_auction_date = date('Y-m-d', strtotime($request['non_jewelry_date']));
        foreach($ids as $key => $value){
            $ticket = Ticket::find($value);
            $notice = Notice::create(['ticket_id'=> $value, 'inventory_id' => $ticket->inventory_id, 'notice_date' => date('Ymd'), 'notice_yr' => $yr, 'notice_ctrl' => $notice_ctrl_number]);
            
            /*
            $inventory = Inventory::find($ticket->inventory_id);
            if($inventory->item_category_id == 1){
                $inventory->update(array(
                    'auction_date' => $jewelry_auction_date,
                    'transaction_date' => date('Ymd'),
                    'maturity_date' => NULL,
                    'expiration_date' => NULL,
                ));
            }else{
                $inventory->update(array(
                    'auction_date' => $non_auction_date,
                    'transaction_date' => date('Ymd'),
                    'maturity_date' => NULL,
                    'expiration_date' => NULL,
                ));
            }
            */
        }

        return response()->json(['status' => 'success', 'link' => route('notice_listing.search', ['notice_yr' => $yr, 'notice_ctrl' => $notice_ctrl_number])]);

    }

    public function single_store(Request $request){

        $ticket = Ticket::find($request->id);
        $notice = Notice::create(['ticket_id'=> $ticket->id, 'inventory_id' => $ticket->inventory_id, 'notice_date' => date('Ymd', strtotime($request['notice_date']))]);
            

        return response()->json(['status' => 'success', 'link' => route('notice_listing.search')]);

    }

    public function print(MC_TableFpdf $fpdf, Request $request){
        // dd($request->jewelry_date);
        // $notice_listing = Ticket::with('notice')->where('notice_yr', '=', $request->notice_yr)->where('notice_ctrl', '=', $request->notice_ctrl)->get();
        $notice_listing = \DB::table('notices')
                            ->select(\DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as customer, customers.present_address, tickets.ticket_number'))
                            ->join('tickets', 'tickets.id', 'notices.ticket_id')
                            ->join('inventories', 'inventories.id', 'tickets.inventory_id')
                            ->join('customers', 'inventories.customer_id', 'customers.id')
                            ->where('notices.notice_yr', '=', $request->notice_yr)
                            ->where('notices.notice_ctrl', '=', $request->notice_ctrl)
                            ->get();
        // $notice_listing = Notice::where('notice_yr', '=', $request->notice_yr)->where('notice_ctrl', '=', $request->notice_ctrl)->with(['ticket', 'ticket.inventory'])->get();
        // dd($notice_listing);
        $jewelry_auction = \DB::table('inventories')
                            ->select(\DB::raw('inventories.auction_date, inventories.item_category_id'))
                            ->join('tickets', 'tickets.inventory_id', 'inventories.id')
                            ->join('notices', 'notices.ticket_id', 'tickets.id')
                            ->where('notices.notice_yr', '=', $request->notice_yr)
                            ->where('notices.notice_ctrl', '=', $request->notice_ctrl)
                            ->groupBy('item_category_id')
                            ->get();
        // dd($jewelry_auction);
        // dd($notice_listing);
        
        $fpdf->AddPage();
        // header
        $fpdf->Image('img/Logo Only.png',33,5,12);
        // Arial bold 15
        $fpdf->SetFont('Arial','B',18);

        $fpdf->setX(45);
        $fpdf->Write(10,'ORDINARIO PAWNSHOP AND JEWELRY');
        $fpdf->SetFont('Arial','B',10);
        $fpdf->setXY(80,15);
        $fpdf->Write(10,'Daet, Camarines Norte 4600 ');
        $fpdf->setXY(87,20);
        $fpdf->Write(10,'Tel No: 0544403834');

        // Line break
        $fpdf->Ln(23);
            // header
        // Set font
        $fpdf->SetFont('Arial','B',11);
        // Move to 8 cm to the right
        $fpdf->SetXY(20, 35);

        $fpdf->Cell(20,20,'NOTICE LISTING : ' .$request->notice_yr."-".$request->notice_ctrl);

        $fpdf->SetFont('Arial','',12);
        $fpdf->SetXY(20, 45);
        foreach($jewelry_auction as $dates){
            if($dates->item_category_id == 1){
                $fpdf->Cell(20,20,'Auction Date (Jewelry) : '. date('M d, Y', strtotime($dates->auction_date)));
            }
            if($dates->item_category_id == 2){
                $fpdf->SetXY(20, 50);
                $fpdf->Cell(20,20,'Auction Date (Non-Jewelry) : '. date('M d, Y', strtotime($dates->auction_date)));
            }
        }



        // table

        /*
        $fpdf->Cell(20,10,'No', 1,0,'C');

        $fpdf->Cell(60,10,'Customer', 1,0,'C');
        $fpdf->Cell(80,10,'Address', 1,0,'C');
        $fpdf->Cell(25,10,'PT #', 1,0,'C');
        $fpdf->Ln();
        */
        $fpdf->SetY(70);
        $fpdf->SetLeftMargin(15);
        $fpdf->SetFont('Arial','B',11);
        // $fpdf->SetHeight(array(5,5,5,5));

        $fpdf->SetWidths(array(20,60,70,30));
        $fpdf->SetHeight(5);
        $fpdf->SetAligns(array('C','C','C','C'));

        $fpdf->Row(array('No','Customer','Address','PT #'));

        // $fpdf->SetXY(50, 65);

        // $fpdf->SetXY(110, 65);

        // $fpdf->SetXY(175, 65);
        // $number = 1;
        $count = 1;
        foreach($notice_listing as $key => $value){
            
            $fpdf->SetX(15);
            $fpdf->SetFont('Arial','',11);
            // $customer = 'The quick brown fox jumps over the lazy dog near the riverbanks';
            // $customer = $value->inventory->customer->first_name." ".$value->inventory->customer->last_name;
            // $address = $value->inventory->customer->present_address;
            $fpdf->SetAligns(array('C','C','L','C'));

            $fpdf->Row(array($count++,$value->customer,$value->present_address,$value->ticket_number));

            // $fpdf->MultiCell( 20, 10, $customer, 1);
            // $fpdf->MultiCell( 60, 10, "Hello", 1);
            // $fpdf->Cell(60,10,$customer, 1,0,'C');
            // $fpdf->Cell(80,10,'Address', 1,0,'C');
            // $fpdf->Cell(25,10,'PT #', 1,0,'C');
            // $fpdf->Ln();


            // $number++;
        }
        // $fpdf->Output();
        $pdfContent = $fpdf->Output('', "S");
    
        return response($pdfContent, 200,
        [
           'Content-Type'        => 'application/pdf',
           'Content-Length'      =>  strlen($pdfContent),
           'Cache-Control'       => 'private, max-age=0, must-revalidate',
           'Pragma'              => 'public'
        ]);

    }

    public function search(Request $request){
        $notice_yr = $request['notice_yr'];
        $notice_ctrl = $request['notice_ctrl'];
        if ($request->ajax()){
            $notice_listing = \DB::table('tickets')
                                ->select(\DB::raw('tickets.ticket_number, CONCAT(customers.first_name, " ", customers.last_name) as customer, inventories.transaction_date, customers.present_address, tickets.id as ticket_id,inventories.id as inventory_id'))
                                ->join('inventories', 'inventories.id', '=', 'tickets.inventory_id')
                                ->join('customers', 'customers.id', '=', 'inventories.customer_id')
                                ->join('notices', 'notices.ticket_id', '=', 'tickets.id')
                                ->where('notices.notice_yr', $request->notice_yr)
                                ->where('notices.notice_ctrl', $request->notice_ctrl)
                                ->whereNotNull('notices.notice_date')
                                ->get();
                return Datatables::of($notice_listing)
                        ->addIndexColumn()
                        ->editColumn('transaction_date', function($row){
                            return date('M d, Y', strtotime($row->transaction_date));
                        })
                        ->addColumn('action', function($row){
                            return '<a href="'.route('single_print', $row->ticket_id).'">View Notices</a>';
                        })
                        ->addColumn('checkbox', function($row){
                            return '<input type="checkbox" name="notice" value="'.$row->ticket_id.'" class="item" onClick="toggleState()"/>';
                        })
                        ->rawColumns(['action', 'checkbox'])
                        ->make(true);
            }
        return view('search_notice_listing', compact('notice_yr', 'notice_ctrl'));
    }

    public function submitSearch(Request $request){
        $notice_yr = $request['notice_year'];
        $notice_ctrl = $request['notice_ctrl'];
        return redirect()->route('notice_listing.search', ['notice_yr' => $notice_yr, 'notice_ctrl' => $notice_ctrl]);
    }

    public function single_print_test(MC_TableFpdf $fpdf,Request $request){
        $data = \DB::table('inventories')
                ->select(\DB::raw('tickets.notice_date, tickets.ticket_number, tickets.principal, inventories.auction_date'))
                ->join('tickets', 'tickets.inventory_id', 'inventories.id')
                ->where('tickets.id', '=', $request->id)
                ->first();

        $data_items = \DB::table('ticket_items')
                        ->select(\DB::raw('item_types.item_type, inventory_items.item_name, inventory_items.item_karat, inventory_items.item_type_weight,
                        inventory_items.description'))
                        ->join('inventory_items', 'inventory_items.id', 'ticket_items.inventory_item_id')
                        ->join('item_types', 'item_types.id', 'inventory_items.item_type_id')
                        ->where('ticket_items.ticket_id', '=', $request->id)
                        ->get();
        // dd($data_item);
        $item_display = '';
        foreach($data_items as $item){
            $item_display .= ucwords(strtolower($item->item_type)). " " .$item->item_name. " " . $item->item_karat."K " . $item->item_type_weight ."(g) (" .$item->description.") ";
        }
        $fpdf->AddPage();
        // header
        $fpdf->Image('img/Logo Only.png',33,5,12);
        // Arial bold 15
        $fpdf->SetFont('Arial','B',18);

        $fpdf->setX(45);
        $fpdf->Write(10,'ORDINARIO PAWNSHOP AND JEWELRY');
        $fpdf->SetFont('Arial','B',10);
        $fpdf->setXY(80,15);
        $fpdf->Write(10,'Daet, Camarines Norte 4600 ');
        $fpdf->setXY(87,20);
        $fpdf->Write(10,'Tel No: 0544403834');
        $fpdf->SetFont('Arial','',11);

        // Line break
        $fpdf->Ln(23);
            // header
        $fpdf->setX(20);
        $fpdf->Cell(20,20, date('M d, Y', strtotime($data->notice_date)));
        $fpdf->Ln(10);
        $fpdf->setX(20);
        $fpdf->Cell(20,20, 'Dear Sir/Madam');
        $fpdf->Ln(10);
        $fpdf->setX(20);
        $fpdf->Cell(20,20, 'This is to remid you that the following articles are expired. To avoid foreclosure, this management hereby');
        $fpdf->Ln(5);
        $fpdf->setX(20);
        $fpdf->Cell(20,20, 'advices you to renew or redeem your pawned article(s) before the date below otherwise this office shall');
        $fpdf->Ln(5);
        $fpdf->setX(20);
        $fpdf->Cell(20,20, 'acquire possession as provided by Law and will sell the foreclosed property/ies on the following dates.');

        $fpdf->SetY(90);
        $fpdf->SetLeftMargin(20);
        $fpdf->SetFont('Arial','B',11);
        $fpdf->SetWidths(array(20,100,30,30));
        $fpdf->SetHeight(5);
        $fpdf->SetAligns(array('C','C','C','C'));

        $fpdf->Row(array('PT','Item','Principal','Auction Date'));

            
            $fpdf->SetX(20);
            $fpdf->SetFont('Arial','',11);
            $fpdf->SetAligns(array('C','C','C','C'));

            $fpdf->Row(array($data->ticket_number,$item_display,number_format($data->principal,2),date('M d, Y', strtotime($data->auction_date))));

            $fpdf->Ln(2);
            $fpdf->setX(20);
            $fpdf->Cell(20,20, 'In case of any other Pledge Contract not mentioned above, please check your receipts and make the');

            $fpdf->Ln(5);
            $fpdf->setX(20);
            $fpdf->Cell(20,20, 'necessary renewal or redemption');
            $fpdf->Ln(10);
            $fpdf->setX(20);
            $fpdf->Cell(20,20, 'Thank you');
            $fpdf->SetFont('Arial','B',11);
            $fpdf->Ln(20);
            $fpdf->setX(30);
            $fpdf->Cell(20,20, 'NOTE : DISREGARD THIS NOTICE IF ARTICLE/S HAS BEEN REDEEMED / RENEWED.');
            $fpdf->SetFont('Arial','',11);
            $fpdf->Ln(20);
            $fpdf->setX(80);
            $fpdf->Cell(20,20, 'HERMOSURA, GIORA');
            $fpdf->Ln(5);
            $fpdf->setX(80);
            $fpdf->Cell(20,20, 'Sto. Nino St.');
            $fpdf->Ln(5);
            $fpdf->setX(80);
            $fpdf->Cell(20,20, 'Brgy Poblacion Norte, Paracale, Camarines Norte 4605');


        $pdfContent = $fpdf->Output('', "S");
    
        return response($pdfContent, 200,
        [
           'Content-Type'        => 'application/pdf',
           'Content-Length'      =>  strlen($pdfContent),
           'Cache-Control'       => 'private, max-age=0, must-revalidate',
           'Pragma'              => 'public'
        ]);

    }

    public function single_print(Request $request){
        // $pdf = \PDF::make('pdf.test');
        $data = \DB::table('inventories')
        ->select(\DB::raw('notices.notice_date, tickets.ticket_number, tickets.principal, inventories.auction_date'))
        ->join('tickets', 'tickets.inventory_id', 'inventories.id')
        ->join('notices' , 'notices.ticket_id', 'tickets.id')
        ->where('tickets.id', '=', $request->id)
        ->first();
        $data_items = \DB::table('ticket_items')
        ->select(\DB::raw('item_types.item_type, inventory_items.item_name, inventory_items.item_karat, inventory_items.item_type_weight,
        inventory_items.description'))
        ->join('inventory_items', 'inventory_items.id', 'ticket_items.inventory_item_id')
        ->join('item_types', 'item_types.id', 'inventory_items.item_type_id')
        ->where('ticket_items.ticket_id', '=', $request->id)
        ->get();
        $item_display = '';
        foreach($data_items as $item){
            $item_display .= ucwords(strtolower($item->item_type)). " " .$item->item_name. " " . $item->item_karat."K " . $item->item_type_weight ."(g) (" .$item->description.") <br/>";
        }
        $pdf = \PDF::loadView('pdf.single_notice', array('data' => $data, 'item' => $item_display));
        return $pdf->inline();
    }

    public function submitCreateSearch(Request $request){
        // dd($request);
        $date = $request['notice_month_year'];
        $branch = $request['branch'];
        return redirect()->route('notice_listing.index', ['date' => $date, 'branch' => $branch]);
    }

    public function print_test(Request $request){
        $notice_listing = \DB::table('notices')
        ->select(\DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as customer, customers.present_address, tickets.ticket_number'))
        ->join('tickets', 'tickets.id', 'notices.ticket_id')
        ->join('inventories', 'inventories.id', 'tickets.inventory_id')
        ->join('customers', 'inventories.customer_id', 'customers.id')
        ->where('notices.notice_yr', '=', $request->notice_yr)
        ->where('notices.notice_ctrl', '=', $request->notice_ctrl)
        ->get();
        /*
        $jewelry_auction = \DB::table('inventories')
        ->select(\DB::raw('inventories.auction_date, inventories.item_category_id'))
        ->join('tickets', 'tickets.inventory_id', 'inventories.id')
        ->join('notices', 'notices.ticket_id', 'tickets.id')
        ->where('notices.notice_yr', '=', $request->notice_yr)
        ->where('notices.notice_ctrl', '=', $request->notice_ctrl)
        ->groupBy('item_category_id')
        ->get();
        */
        $pdf = \PDF::loadView('pdf.notice', array('data' => $notice_listing));
        return $pdf->inline();
    }
}