<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MC_TableFpdf;

class NoticeListingController extends Controller
{
    //

    public function index(){
        
        return view('notice_listing');
    }

    public function print(MC_TableFpdf $fpdf, Request $request){
        // dd($request->jewelry_date);
        $fpdf->AddPage();
        // Set font
        $fpdf->SetFont('Arial','B',11);
        // Move to 8 cm to the right
        $fpdf->SetXY(20, 35);

        $fpdf->Cell(20,20,'NOTICE LISTING');

        $fpdf->SetFont('Arial','',12);
        $fpdf->SetXY(20, 45);
        $fpdf->Cell(20,20,'Auction Date (Jewelry) : '. $request->jewelry_date);
        $fpdf->SetXY(20, 50);
        $fpdf->Cell(20,20,'Auction Date (Non-Jewelry) : '. $request->non_jewelry_date);


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

        $fpdf->SetWidths(array(20,60,70,30));
        $fpdf->SetAligns(array('C','C','C','C'));

        $fpdf->Row(array('No','Customer','Address','PT #'));

        // $fpdf->SetXY(50, 65);

        // $fpdf->SetXY(110, 65);

        // $fpdf->SetXY(175, 65);
        $number = 1;
        while($number <= 30){
            $fpdf->SetX(15);
            $fpdf->SetFont('Arial','',11);
            $customer = 'The quick brown fox jumps over the lazy dog near the riverbanks';
            $fpdf->SetAligns(array('C','L','L','C'));

            $fpdf->Row(array($number,$customer,$customer,str_pad($number, 6, '0', STR_PAD_LEFT)));

            // $fpdf->MultiCell( 20, 10, $customer, 1);
            // $fpdf->MultiCell( 60, 10, "Hello", 1);
            // $fpdf->Cell(60,10,$customer, 1,0,'C');
            // $fpdf->Cell(80,10,'Address', 1,0,'C');
            // $fpdf->Cell(25,10,'PT #', 1,0,'C');
            // $fpdf->Ln();


            $number++;
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
}
