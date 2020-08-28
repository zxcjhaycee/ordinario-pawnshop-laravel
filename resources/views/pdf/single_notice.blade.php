<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice</title>
</head>
    <style>
        *{
            margin: 0
            font-family: Arial;
        }
        .header{
            text-align:center;
 
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
    </style>
<body>

   <div class="header">
        <img style="vertical-align:middle" class="logo" src="{{ asset('img/Logo Only.png' )}}">
        <span style="font-size:50px;vertical-align:middle;font-weight:bold;">ORDINARIO PAWNSHOP</span><br/>
        <span style="font-size:23px;font-weight:bold;position:relative;top:-15px">Daet, Camarines Norte 4600</span><br/>
        <span style="font-size:20px;font-weight:bold;position:relative;top:-15px">Tel No: 0544403834</span>
    </div>
    <div class="content">
        <p>{{ date('M d, Y', strtotime($data->notice_date)) }}</p>
        <p>Dear Sir/Madam</p>
        <p>This is to remind you that the following articles are expired. To avoid foreclosure, this management hereby
        advices you to renew or redeem your pawned article(s) before the date below otherwise this office shall
        acquire possession as provided by Law and will sell the foreclosed property/ies on the following dates.
        </p>
    <table style="width:100%;">
        <tr>
            <th style="width:18%">PT</th>
            <th style="width:40%">Item</th>
            <th style="width:20%">Principal</th>
            <th style="width:20%">Auction Date</th>
        </tr>
        <tr>
            <td style="text-align:center">{{ $data->ticket_number }}</td>
            <td>{!! $item !!}</td>
            <td style="text-align:center">{{ number_format($data->principal,2) }}</td>
            <td style="text-align:center">{{ date('M d, Y', strtotime($data->auction_date)) }}</td>
        </tr>
    </table>
    <p>In case of any other Pledge Contract not mentioned above, please check your receipts and make the
        necessary renewal or redemption
    </p>
    <p>Thank you</p>
    <h5 style="margin-left:0px;text-align:center;margin-top:80px">NOTE : DISREGARD THIS NOTICE IF ARTICLE/S HAS BEEN REDEEMED / RENEWED.</h5>
    <p style="text-align:center;margin-top:70px">HERMOSURA, GIORA<br/>
        Sto. Nino St.<br/>
        Brgy Poblacion Norte, Paracale, Camarines Norte 4605
    </p>
    </div>

</body>
</html>