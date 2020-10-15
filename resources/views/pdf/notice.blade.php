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
        }
        body{
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
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }    
         th,td {
            padding : 10px 10px;
        }
    </style>
<body>

   <div class="header">
        <img style="vertical-align:middle" class="logo" src="{{ asset('img/Logo Only.png' )}}">
        <span style="font-size:35px;vertical-align:middle;font-weight:bold;">ORDINARIO PAWNSHOP AND JEWELRY</span><br/>
        <span style="font-size:23px;font-weight:bold;position:relative;top:-15px">Daet, Camarines Norte 4600</span><br/>
        <span style="font-size:20px;font-weight:bold;position:relative;top:-15px">Tel No: 0544403834</span>
    </div>
    <div class="content">
        <div class="ctrl">
            <h4>NOTICE LISTING:</h4>
        </div>
        <table style="width:100%">
            <tr>
                <th>No</th>
                <th>Customer</th>
                <th>Address</th>
                <th>PT #</th>
            </tr>
            @php
                $count = 1;
            @endphp
            @foreach($data as $notice)
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ $notice->customer }}</td>
                    <td>{{ $notice->present_address }}</td>
                    <td>{{ $notice->ticket_number }}</td>
                </tr>
            @endforeach
        </table>
    </div>

</body>
</html>