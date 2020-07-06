<?php

namespace App\Http\Controllers;

use App\Pawn;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use \NumberFormatter;
use App\Inventory;
use App\Ticket;
use App\Http\Controllers\MC_TableFpdf;

// use Carbon\Carbon; 

class PawnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('sangla');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // dd($request);
        $print_value = array(
            'transaction_date' => $request->transaction_date,
            'maturity_date' => $request->maturity_date,
            'expiration_date' => $request->expiration_date,
            'auction_date' => $request->auction_date,
            'customer' => $request->customer,
            'appraised_value' => $request->appraised_value 
        );
        // dd($print_value);
        return redirect()->route('pawn_print', $print_value);
        // return redirect()->route('pawn_print');
    }
    public function pawnPrint(MC_TableFpdf $fpdf, Request $request){
        // dd($request->id);
        $data = Inventory::with(['pawnTickets' => function($query){
            $query->where('pawnTickets.transaction_type', '=', 'pawn');
        }])
        ->with(['pawnTickets.encoder', 'customer', 'inventoryItems', 'pawnTickets.other_charges', 'pawnTickets.attachment', 'inventoryItems.item_type'])
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
        $fpdf->SetXY(98,28);
        $fpdf->Write(0,'Daet A. Branch');
        
        $fpdf->SetXY(94,32);
        $fpdf->Write(0,'Daet, Camarines Norte');
        
        $fpdf->SetXY(90,36);
        $fpdf->Write(0,'FOR INQUIRY Call/Text 1234');
        
        $fpdf->SetXY(94,40);
        $fpdf->Write(0,'TIN 11111111 Non-Vat');
        
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
        
        // $fpdf->Write(0, date('M d, Y', strtotime($_GET['auction_date'])));
        
        $fpdf->AddPage();
        /*
        $fpdf->SetXY(20,20);
        $fpdf->SetFont('Arial','',8);
        $fpdf->MultiCell(35, 7, 'PT# : ',1,'C');
        $fpdf->SetXY(20,27);
        $fpdf->MultiCell(35, 7, 'Date of Loan : ',1,'C');
        $fpdf->SetXY(20,34);
        $fpdf->MultiCell(35, 7, 'Maturity Date : ',1,'C');
        $fpdf->SetXY(20,41);
        $fpdf->MultiCell(35, 7, 'Name : ',1,'C');
        $fpdf->SetXY(20,48);
        $fpdf->MultiCell(35, 7, 'Birthdate : ',1,'C');
        $fpdf->SetXY(20,55);
        $fpdf->MultiCell(35, 7, 'Address : ',1,'C');
        $fpdf->SetXY(20,62);
        $fpdf->MultiCell(35, 7, 'Description of Pawn : ',1,'C');
        $fpdf->SetXY(20,69);
        $fpdf->MultiCell(35, 7, 'Pledge Loan : ',1,'C');
        $fpdf->SetXY(20,76);
        $fpdf->MultiCell(35, 7, 'Signature  : ',1,'C');
        $fpdf->SetXY(20,83);
        $fpdf->MultiCell(35, 7, 'Contact Number : ',1,'C');
        $fpdf->SetXY(20,90);
        $fpdf->MultiCell(35, 7, 'Appraiser : ',1,'C');
        $fpdf->SetXY(20,97);
        $fpdf->MultiCell(35, 7, 'CPG : ',1,'C');
        
        
        $fpdf->SetXY(55,20);
        $fpdf->SetFont('Arial','',8);
        $fpdf->MultiCell(130, 7, $data->pawnTickets->ticket_number,1,'L');
        $fpdf->SetXY(55,27);
        $fpdf->MultiCell(130, 7, date('M d, Y', strtotime($data->pawnTickets->transaction_date)) ,1,'L');
        $fpdf->SetXY(55,34);
        $fpdf->MultiCell(130, 7, date('M d, Y', strtotime($data->pawnTickets->maturity_date)),1,'L');
        $fpdf->SetXY(55,41);
        $fpdf->MultiCell(130, 7, strtoupper($data->customer->first_name). " " .strtoupper($data->customer->last_name). " " .strtoupper($data->customer->suffix),1,'L');
        $fpdf->SetXY(55,48);
        $fpdf->MultiCell(130, 7, date('M d, Y', strtotime($data->customer->birthdate)),1,'L');
        $fpdf->SetXY(55,55);
        $fpdf->MultiCell(130, 7, $data->customer->present_address,1,'L');
        $fpdf->SetXY(55,62);
        $fpdf->MultiCell(130, 7, " Hello",1,'L');
        $fpdf->SetXY(55,69);
        $fpdf->MultiCell(130, 7, number_format($data->pawnTickets->net,2),1,'L');
        $fpdf->SetXY(55,76);
        $fpdf->MultiCell(130, 7, ' ',1,'L');
        $fpdf->SetXY(55,83);
        $fpdf->MultiCell(130, 7,  $data->customer->contact_number,1,'L');
        $fpdf->SetXY(55,90);
        $fpdf->MultiCell(130, 7, 'XXX, '. $data->pawnTickets->encoder->first_name,1,'L');
        $fpdf->SetXY(55,97);
        $fpdf->MultiCell(130, 7, '20,000.00 ',1,'L');
        // $fpdf->Output();
        */
        // $fpdf->SetXY(20,110);
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

    /**
     * Display the specified resource.
     *
     * @param  \App\PawnAuction  $pawnAuction
     * @return \Illuminate\Http\Response
     */
    public function show(PawnAuction $pawnAuction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PawnAuction  $pawnAuction
     * @return \Illuminate\Http\Response
     */
    public function edit(Pawn $pawnAuction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PawnAuction  $pawnAuction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pawn $pawnAuction)
    {
        //
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
}
