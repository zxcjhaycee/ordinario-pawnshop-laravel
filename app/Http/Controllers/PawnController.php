<?php

namespace App\Http\Controllers;

use App\Pawn;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use \NumberFormatter;
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
    public function pawnPrint(Fpdf $fpdf, Request $request){
        // dd($request);
        // dd(Carbon::now());
        $formatter = new NumberFormatter("en", \NumberFormatter::SPELLOUT);

        $fpdf->AddPage();

        $fpdf->SetFont('Arial','',8.5);
        $fpdf->SetX(10);
        $fpdf->Write(0,'Processed By: XXX');
        
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
        $fpdf->Write(0,'PT 00001');
        
        $fpdf->SetFont('Arial','',8);
        $fpdf->SetXY(10,45);
        $fpdf->Write(0,'Date Loan Granted: ');
        $fpdf->SetFont('Arial','I',8);
        $fpdf->Write(0, date('M d, Y', strtotime($request->transaction_date)));
        
        $fpdf->SetFont('Arial','',8);
        $fpdf->SetXY(156,45);
        $fpdf->Write(0,'Maturity Date: ');
        $fpdf->SetFont('Arial','I',8);
        $fpdf->Write(0, date('M d, Y', strtotime($request->maturity_date)));
        $fpdf->SetXY(135,50);
        $fpdf->SetFont('Arial','',8);
        $fpdf->Write(0,'Loan Redemption Expiry Date: ');
        $fpdf->SetFont('Arial','I',8);
        $fpdf->Write(0, date('M d, Y', strtotime($request->expiration_date)));
        $fpdf->SetFont('Arial','',8);
        $fpdf->SetXY(10,55);
        $fpdf->Write(5,'Pawnee ');
        $fpdf->SetFont('Arial','B',8);
        $fpdf->Write(5, $request->customer);
        $fpdf->SetFont('Arial','',8);
        $fpdf->Write(5,', residing at ');
        $fpdf->SetFont('Arial','I',8);
        $fpdf->Write(5,'Brgy. VI, Daet Camarines Norte ');
        $fpdf->SetFont('Arial','',8);
        $fpdf->Write(5,'for a loan of ');
        $fpdf->SetFont('Arial','B',8);
        $fpdf->Write(5, strtoupper($formatter->format($request->appraised_value)).' (P '.number_format($request->appraised_value,2 ).'),');

        $fpdf->SetFont('Arial','',8);
        $fpdf->Write(5,' with ');
        $fpdf->SetFont('Arial','I',8);
        $fpdf->Write(5,'three ');
        $fpdf->SetFont('Arial','',8);
        $fpdf->Write(5,'percent (3.00%) interest per month, pledged in security for the loan as described and appraised at SIX THOUSAND PESOS (P 6,000.00), subject to the terms and conditions of the pawn.');
        
        
        $fpdf->SetXY(30,75);
        $fpdf->SetFont('Arial','B',8);
        $fpdf->Write(5,' (Description of Pawn) ');
        
        $fpdf->SetXY(10,82);
        $fpdf->SetFont('Arial','',8);
        $i = 0;
        while($i <= 1){
            // $fpdf->Write(5,'Gold Ring 24K 5g (The quick brown fox jumps over the lazy dog near the riverbanks) ');
            $fpdf->MultiCell(85, 3, 'Gold Ring 24K 5g (Gold with damage) ');
            $fpdf->Ln(2);
            $i++;
        }
        $fpdf->Write(5,' ID Presented :  SSS');
        $fpdf->Ln();
        $fpdf->Write(5,' ID Number : 12312312 ');
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
        $fpdf->MultiCell(45, 7, 'P 250,000.00',1,'C');
        $fpdf->SetXY(155,87);
        $fpdf->MultiCell(45, 7, 'P 150,000.00',1,'C');
        $fpdf->SetXY(155,94);
        $fpdf->MultiCell(45, 7, 'P 50,000.00',1,'C');
        
        $fpdf->SetXY(118,103);
        
        $fpdf->Write(5,' Monthly Effective Interest Rate :  2%');
        $fpdf->Ln();
        $fpdf->SetXY(118,108);
        
        $fpdf->Write(5,' Contact Number : 0912345678 ');
        $fpdf->SetXY(118,142);
        
        $fpdf->Write(10,' ________________________________________________ ');
        $fpdf->SetXY(130,147);
        $fpdf->Write(10,' (Pawnshop Authorized Representative) ');
        
        
        $fpdf->SetXY(10,170);
        // $fpdf->Write(0,' REMINDER TO THE PAWNEE: THE PLEDGED ARTICLE(S) COVERED BY THIS CERTIFICATE WILL BE SOLD IN A PUBLIC AUCTION ON ');
        $fpdf->MultiCell(190, 4, 'REMINDER TO THE PAWNEE: THE PLEDGED ARTICLE(S) COVERED BY THIS CERTIFICATE WILL BE SOLD IN A PUBLIC AUCTION ON',0,'C');
        // $fpdf->SetFont('Arial','I',8);
        // $fpdf->MultiCell(190, 4,date('M d, Y', strtotime($_GET['auction_date'])) ,0,'C');
        
        $fpdf->MultiCell(190, 4, date('M d, Y', strtotime($request->auction_date)). ' AT ABOVE ADDRESS IF NOT REDEEMED OR RENEWED WITHIN THE REDEMPTION PERIOD',0,'C');
        
        $fpdf->Ln();
        $fpdf->SetXY(45,185);
        
        $fpdf->MultiCell(125, 4, 'Pawner is advised to read and understand the Terms and Conditions on the reverse side hereof 100 Bkits (50 x 2) 001-5000 BIR Permit OCN-4AU0000377499*1-27-2006',0,'C');
        
        // $fpdf->Write(0, date('M d, Y', strtotime($_GET['auction_date'])));
        
        $fpdf->AddPage();
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
        $fpdf->MultiCell(130, 7, '00001',1,'L');
        $fpdf->SetXY(55,27);
        $fpdf->MultiCell(130, 7, date('M d, Y', strtotime($request->transaction_date)) ,1,'L');
        $fpdf->SetXY(55,34);
        $fpdf->MultiCell(130, 7, date('M d, Y', strtotime($request->maturity_date)),1,'L');
        $fpdf->SetXY(55,41);
        $fpdf->MultiCell(130, 7, strtoupper($request->customer),1,'L');
        $fpdf->SetXY(55,48);
        $fpdf->MultiCell(130, 7, date('M d, Y'),1,'L');
        $fpdf->SetXY(55,55);
        $fpdf->MultiCell(130, 7, 'The quick brown fox jumps over the lazy dog near the riverbanks ',1,'L');
        $fpdf->SetXY(55,62);
        $fpdf->MultiCell(130, 7, ' ',1,'L');
        $fpdf->SetXY(55,69);
        $fpdf->MultiCell(130, 7, ' ',1,'L');
        $fpdf->SetXY(55,76);
        $fpdf->MultiCell(130, 7, '',1,'L');
        $fpdf->SetXY(55,83);
        $fpdf->MultiCell(130, 7, '0912345678 ',1,'L');
        $fpdf->SetXY(55,90);
        $fpdf->MultiCell(130, 7, 'XXX',1,'L');
        $fpdf->SetXY(55,97);
        $fpdf->MultiCell(130, 7, '20,000.00 ',1,'L');
        
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
    public function edit(PawnAuction $pawnAuction)
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
    public function update(Request $request, PawnAuction $pawnAuction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PawnAuction  $pawnAuction
     * @return \Illuminate\Http\Response
     */
    public function destroy(PawnAuction $pawnAuction)
    {
        //
    }
}
