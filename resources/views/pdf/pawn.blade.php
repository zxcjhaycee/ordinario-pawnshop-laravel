<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawn Print</title>
</head>
    <style>
        *{
            margin: 0
        }
        body{
            font-family: Arial;
        }
        .header{
            /* text-align:center; */
 
        }
        .time{
            text-align:center;
            margin-top:-19px;
            font-size : 15px;
        }
        .date{
            text-align:right;
            margin-top:-19px;
        }
        .logo{
            height : 80px;
            /* margin-left:120px; */
            /* position:relative; */
            object-fit : fill
        }
        .content{
            font-size: 20px;
            margin-top:100px;
            margin-left: 80px
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }    
        .amount th,
        .amount td {
            padding : 10px 55px;
        }
        .details th,
        .details td {
            padding : 5px 25px;
        }
        /* .center{
            position:relative;
        } */
        div.page{
        page-break-after: always;
        page-break-inside: avoid;
        }
    </style>
<body>
        <div class="page">
        <span style="font-size:15px">Processed By : {{ $data->pawnTickets->encoder->first_name." ".$data->pawnTickets->encoder->last_name }} </span>
        <div class="time">
            <span>OPEN Monday - Sunday 08:00 AM to 05:00 PM</span>
        </div>
        <div class="date">
            <span style="font-size:15px; text-align:right">{{ date('F d, Y') }}</span>

        </div>
        <h2 style="text-align:center;margin-top:20px">Ordinario Pawnshop and Jewelry</h2>
        <p style="text-align:center">TRUSTED SINCE 1988</p>
        <div style="margin-top:30px">
            <p style="font-size:17px;margin-left:5px">Inventory #: {{ $data->inventory_number }}</p>

            <!-- <span style="font-size:20px;margin-top:-25px;margin-right:70px;text-align:right">PT00001</span> -->
        </div>
        <div style="margin-top:-30px;text-align:center">
            <p>{{ ucwords($data->branch->branch) }} Branch</p>
            <p style="width:300px;margin:auto">{{ $data->branch->address }}</p>
            <p>TIN {{ $data->branch->tin }}</p>
            <p>Tel No: {{ $data->branch->contact_number }} / Email: ordinario.pawnshop@gmail.com</p>
        </div>
        <div style="position:fixed;right:0;top:107px">
            <p style="font-size:17px;margin-right:5px">ORIGINAL</p>

            <!-- <span style="font-size:20px;margin-top:-25px;margin-right:70px;text-align:right">PT00001</span> -->
        </div>
        <br/>
        <div>
            <div style="float:left">
                <p>Date Loan Granted : {{ date('M d, Y', strtotime($data->pawnTickets->transaction_date)) }} </p>
            </div>
            <div style="text-align:right">
                <p>Maturity Date : {{ date('M d, Y', strtotime($data->pawnTickets->maturity_date)) }} </p>
                <p>Loan Redemption Expiry Date : {{ date('M d, Y', strtotime($data->pawnTickets->expiration_date)) }} </p>
            </div>
        </div>
        <br/>
        <br/>
        <br/>
        <div>

        <p>Pawnee <b>{{ $data->customer->first_name." ".$data->customer->last_name." ".$data->customer->suffix }}</b>, residing at {{ $data->customer->present_address }} for a loan of <b>{{ strtoupper($formatter->format($data->pawnTickets->pawn_tickets->ticket_parent->principal)) }}</b> (P{{ number_format($data->pawnTickets->pawn_tickets->ticket_parent->principal) }}), 
        with <i>{{ $formatter->format($data->pawnTickets->interest_percentage) }}</i> percent ({{ number_format($data->pawnTickets->interest_percentage,2) }}%) interest per month, pledged in security for the loan as described        
                and appraised at {{ strtoupper($formatter->format($data->pawnTickets->pawn_tickets->ticket_items->sum('item_name_appraised_value'))) }} (P{{ number_format($data->pawnTickets->pawn_tickets->ticket_items->sum('item_name_appraised_value'),2) }}), subject to the terms and conditions of the pawn.</p>
        </div>
        <div style="margin-top: 50px">
            <div style="float:left">
                <b style="margin-left:80px">(Description of the Pawn)</b>
                <ul style="margin-top:10px">
                    @foreach($data->pawnTickets->pawn_tickets->ticket_items as $item)
                         <li style="width:400px">{{ ucwords($item->inventory_items->item_type->item_type). " " .$item->inventory_items->item_name. " " . $item->inventory_items->item_karat."K " . $item->inventory_items->item_type_weight ."(g) (" .$item->inventory_items->description.") " }}</li>
                    @endforeach
                    <!-- <li style="width:400px">GOLD Ring 20K 5(g) (The quick brown fox jumps over the lazy dog near the riverbanks)</li> -->


                </ul>
                <div style="margin-top:30px">
                    <p style="width:400px">ID Presented / ID No. : <u> {{ $data->pawnTickets->attachment->type." / ".$data->pawnTickets->attachment_number }}</u></p>      
                </div>
            </div>
            <div style="float:right">
                    <table style="width:100%" class="amount">
                        <tr>
                            <td>
                                Principal
                            </td>
                            <td style="width:80px">
                                P {{ number_format($data->pawnTickets->pawn_tickets->ticket_parent->principal,2) }}
                            </td>

                        </tr>
                        @if($data->pawnTickets->transaction_type == 'renew')
                        <tr>
                            <td>
                                Interest
                            </td>
                            <td style="width:80px">
                                P {{ number_format($data->pawnTickets->interest,2) }}
                            </td>

                        </tr>
                            @if($data->pawnTickets->penalty != 0)
                            <tr>
                                <td>
                                    Penalty
                                </td>
                                <td style="width:80px">
                                    P {{ number_format($data->pawnTickets->penalty,2) }}
                                </td>
                            </tr>
                            @endif
                        @endif
                        <tr>
                            <td>
                                Service Charge
                            </td>
                            <td style="width:80px">
                                P {{ number_format($data->pawnTickets->charges, 2)}}
                            </td>

                        </tr>
                        <tr>
                            <td>
                                Net Proceeds
                            </td>
                            <td style="width:80px">
                                P {{ number_format($data->pawnTickets->net, 2)}}
                            </td>

                        </tr>
                        <tr>
                            <td>
                                Effective Interest<br/> Rate in Percent	
                            </td>
                            <td style="width:80px">
                               
                            </td>

                        </tr>
                    </table> 

                    <div style="margin-top:10px">
                        {{--<p>Monthly Effective Interest Rate : {{ $data->pawnTickets->interest_percentage }}%</p>--}}      
                        <p>Contact Number : <u>{{ $data->customer->contact_number }}</u></p>      

                    </div>     
            </div>
        </div>

        <br/>
        <div class="signature" style="padding-top:500px">
            <div style="float:left">
                <p style="margin-left:50px">
                _______________________________________<br/>
                    <i style="margin-left:35px">(Signature or Thumb Mark of Pawnee)</i>
                 </p>

            </div>
            <div style="float:right">
                <p style="margin-right:50px">
                    _______________________________________<br/>
                        <i style="margin-left:35px"> (Pawnshop Authorized Representative)</i>
                </p>
            </div>
        </div>
        <!-- <br/>
        <br/>
        <br/>
        <br/> -->
        <div class="announcement" style="margin-top:80px;text-align:center">
            REMINDER TO THE PAWNEE: THE PLEDGED ARTICLE(S) COVERED BY THIS CERTIFICATE WILL BE SOLD IN A PUBLIC AUCTION ON
            {{ date('M d, Y', strtotime($data->pawnTickets->auction_date)) }} AT ABOVE ADDRESS IF NOT REDEEMED OR RENEWED WITHIN THE REDEMPTION PERIOD

        </div>
        <div class="terms_condition" style="margin-top:50px;text-align:center">
        Pawner is advised to read and understand the Terms and Conditions on the reverse side hereof
        100 Bkits (50 x 2) 001-5000 BIR Permit OCN-4AU0000377499*1-27-2006
        </div>
        </div>
        <div class="page">

            <div>
                <table class="details" style="width:80%;margin-left:auto;margin-right:auto">
                    <tr>
                        <td style="width:25%">PT# : </td>
                        <td>{{ $data->pawnTickets->ticket_number }}</td>
                    </tr>
                    <tr>
                        <td style="width:25%">Date of Loan : </td>
                        <td>{{ date('M d, Y', strtotime($data->pawnTickets->transaction_date)) }}</td>
                    </tr>
                    <tr>
                        <td style="width:25%">Maturity Date : </td>
                        <td>{{ date('M d, Y', strtotime($data->pawnTickets->maturity_date)) }}</td>
                    </tr>
                    <tr>
                        <td style="width:25%">Name : </td>
                        <td>{{ $data->customer->first_name." ".$data->customer->last_name." ".$data->customer->suffix }}</td>
                    </tr>
                    <tr>
                        <td style="width:25%">Birthdate : </td>
                        <td>{{ date('M d, Y', strtotime($data->customer->birthdate)) }}</td>
                    </tr>
                    <tr>
                        <td style="width:25%">Address : </td>
                        <td>{{ $data->customer->present_address }}</td>
                    </tr>
                    <tr>
                        <td style="width:25%">Description of Pawn : </td>
                        <td>
                            @foreach($data->pawnTickets->pawn_tickets->ticket_items as $description)
                                {{ $description->inventory_items->item_type->item_type." ".$description->inventory_items->item_name." ".$description->inventory_items->item_karat."K ".$description->inventory_items->item_type_weight."(g) (".$description->inventory_items->description.")" }}
                                <br/>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td style="width:25%">Pledge Loan : </td>
                        <td>{{ number_format($data->pawnTickets->pawn_tickets->ticket_parent->principal,2) }}</td>
                    </tr>
                    <tr>
                        <td style="width:25%">Signature : </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="width:25%">Contact Number : </td>
                        <td>{{ $data->customer->contact_number }}</td>
                    </tr>
                    <tr>
                        <td style="width:25%">Appraiser : </td>
                        <td>XXX, {{ $data->pawnTickets->encoder->first_name }}</td>
                    </tr>
                    <tr>
                        <td style="width:25%">CPG : </td>
                        <td></td>
                    </tr>

                </table>
            </div>
        </div>
        @if($data->pawnTickets->transaction_type == 'renew')
        <div class="page">
            <div class="center">
                <h3 style="text-align:center">ORDINARIO PAWNSHOP AND JEWELRY</h3>
                <h4 style="text-align:center">Daet, Camarines Norte</h4>
                <h4 style="text-align:center">Non-VAT Reg. TIN: 915-198-482-000</h4>
                <h4 style="text-align:center">ORDINARIO PAWNSHOP AND JEWELRY</h4>
            </div>
            <div class="title">
            <h4 style="float:left">OFFICIAL RECEIPT</h4>
            <h4 style="text-align:right;color:red">Date : {{ date('M d, Y')}}</h4>

            </div>
            <div class="content_or" style="margin-top:5px">
            <p>
                 <b>RECEIVED</b> from: {{ $data->customer->first_name." ".$data->customer->last_name." ".$data->customer->suffix }} w/ TIN_____________ address , 
                 {{ $data->customer->present_address }} engaged in 
                 the business style of __________________________________________________ the sum of (₱ {{ $data->pawnTickets->net }}) 
                 as full/partial payment for {{ $data->pawnTickets->ticket_number }}

            </p>

            </div>
            <div class="table" style="margin-top:10px">
                <table style="width:100%" class="details">
                    <tr>
                        <th colspan="2">In settlement of the following</th>
                    </tr>
                    <tr>
                        <th>Particulars</th>
                        <th>Amount</th>
                    </tr>
                    <tr>
                        <td>PT #</td>
                        <td>{{ $data->pawnTickets->ticket_number }}</td>
                    </tr>
                    <tr>
                        <td>Principal</td>
                        <td>{{ number_format($data->pawnTickets->pawn_tickets->ticket_parent->principal,2) }}</td>
                    </tr>
                    <tr>
                        <td>Advance Interest</td>
                        <td>0.00</td>
                    </tr>
                    <tr>
                        <td>Interest</td>
                        <td>{{ number_format($data->pawnTickets->interest,2) }}</td>
                    </tr>
                    <tr>
                        <td>Penalty</td>
                        <td>{{ number_format($data->pawnTickets->penalty,2) }}</td>
                    </tr>
                    <tr>
                        <td>DST</td>
                        <td>0.00</td>
                    </tr>
                    <tr>
                        <td>Others</td>
                        <td> {{ number_format($data->pawnTickets->charges,2) }} </td>
                    </tr>
                    <tr>
                        <th style="text-align:left">Total</th>
                        <th style="text-align:left">{{ number_format($data->pawnTickets->pawn_tickets->ticket_parent->principal + $data->pawnTickets->interest + $data->pawnTickets->penalty + $data->pawnTickets->charges,2) }}</th>
                    </tr>
                </table>
                
            </div>
            <div class="important" style="text-align:center">
                <i>Important: This is your official receipt when machine validated</i>
            </div>
            <div class="info">
                <ul>
                    <li>Date :  <u>{{ date('M d, Y')}}</u></li>
                    <li>Transaction No : </li>
                    <li>Teller :  <u>{{ $data->pawnTickets->encoder->first_name." ".$data->pawnTickets->encoder->last_name }}</u> </li>
                    <li>Amount Received : <u>{{ number_format($data->pawnTickets->payment->amount,2) }}</u> </li>
                </ul>
            </div>
        </div>
        @endif
</body>
</html>