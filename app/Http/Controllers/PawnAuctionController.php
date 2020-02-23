<?php

namespace App\Http\Controllers;

use App\PawnAuction;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

class PawnAuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function pawnView()
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
        // return redirect()->route('pawn_print');
    }
    public function pawnPrint(Fpdf $fpdf){
        $formatter = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
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
        $fpdf->SetXY(5,45);
        $fpdf->Write(0,'Date Loan Granted: '.date('M d, Y', strtotime($_GET['transaction_date'])));
        
        $fpdf->SetXY(156,45);
        $fpdf->Write(0,'Maturity Date: '.date('M d, Y', strtotime($_GET['maturity_date'])));
        $fpdf->SetXY(135,50);
        $fpdf->Write(0,'Loan Redemption Expiry Date: '.date('M d, Y', strtotime($_GET['expiration_date'])));
        
        $fpdf->SetXY(10,55);
        $fpdf->Write(5,'Pawnee '.$_GET['customer'].', residing at Brgy. VI, Daet Camarines NOrte for a loan of '.strtoupper($formatter->format($_GET['appraised_value'])).' (P '.number_format($_GET['appraised_value']).'), with three percent (3.00%) interest per month pledged in security for the loan as described and appraised at SIX THOUSAND PESOS (P 6,000.00), subject to the terms and conditions of the pawn.');
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
