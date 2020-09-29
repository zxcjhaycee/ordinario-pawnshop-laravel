@php
 $routeName = substr(Route::currentRouteName(), strpos(Route::currentRouteName(), ".") + 1); // to identify if add or update
@endphp

@extends('layout')
@section('title', 'Inventory # : '. $inventory->inventory_number) 
@section('content')
<style>

    form .form-group select.form-control {
        position: static;
        margin-top: -5px;
    }
    .alert .close{
        line-height : 1.5;
    }
    .card-body-form{
        min-height : 0;
    }

    .card .form-check{
        margin-top : 20px;
    }
    .has-error .select2-selection {
         border-color: rgb(185, 74, 72) !important;
    }
    tr td:first-child{
      width:1%;
      white-space:nowrap;
    }
    .table-borderless td, .table-borderless th {
       border: none;
    }
</style>
<div class="content">
    <div class="col-xl-12">
              <div class="card">
                <div class="card-header card-header-icon">
                  <div class="card-icon" style="background: linear-gradient(60deg,#702230,#702230)">
                    <i class="material-icons">star</i>
                  </div>
                  <!-- <span class="">Closed</span> -->
                  @if($inventory->status == 1)
                      <div><span class="badge badge-danger float-right" style="font-size:30px!important">Auctioned</span></div>
                  @endif
                  {{--
                  @if(isset($current_pawn))
                  <a href="{{ route('inventory.renew', ['id' => $id, 'pawn_id' => $current_pawn->id]) }}" class="btn btn-success float-right btn-responsive">Renew</a>
                  <a href="{{ route('inventory.redeem', ['id' => $id, 'pawn_id' => $current_pawn->id]) }}" class="btn btn-success float-right btn-responsive">Redeem</a>
                    <form action="{{ route('inventory.auction') }}" method="post">
                      @csrf
                        <input type="hidden" name="inventory_id" value="{{ $id }}">
                      <input type="submit" value="Auction" class="btn btn-success float-right btn-responsive">
                    </form>
                  @else
                     <a href="{{ route('inventory.repawn', ['id' => $id]) }}" class="btn btn-success float-right btn-responsive">Repawn</a>
                  @endif
                  --}}
                    <h4 class="card-title"> {{ ucwords($routeName) }} Inventory</h4>

                </div>
    
                 <div class="card-body">
                    @include('alert')

                    <div class="card">
                                <div class="card-header card-header-text card-header-primary">
                                
                                    <div class="card-text">
                                    <h4 class="card-title">Inventory Details</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                <div class="row">
                                
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                      <h3 class="display-4">Details</h3>
                                        <div class="mt-4">
                                        <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                                          <table class="table table-borderless" style="margin:0 auto">
                                              <tr>
                                                <td style="text-align:right;font-weight:bold">Inventory # :</td>
                                                <td>{{ $inventory->inventory_number }}</td>
                                              </tr>
                                              <tr>
                                                <td style="text-align:right;font-weight:bold">Category :</td>
                                                <td>{{ $inventory->item_category->item_category }}</td>
                                              </tr>
                                              <tr>
                                                <td style="text-align:right;font-weight:bold">Loan Status :</td>
                                                <td>{{ 'Outstanding' }}</td>
                                              </tr>
                                              <tr>
                                                <td style="text-align:right;font-weight:bold">Branch :</td>
                                                <td>{{ $inventory->branch->branch }}</td>
                                              </tr>
                                          </table>
                                        </div>
                                          <!-- 
                                          <ul style="list-style-type:none;padding-left: 5px;">
                                            <li class="font-weight-bold" style="text-indent:11px">Inventory # : <span class="font-weight-normal">{{ $inventory->inventory_number }}</span></li>
                                            <li class="font-weight-bold mt-2" style="text-indent:8px">Item Category : <span class="font-weight-normal">{{ $inventory->item_category->item_category }}</span> </li>
                                            <li class="font-weight-bold mt-2" style="text-indent:8px">Loan Status : <span class="font-weight-normal">Outstanding</span> </li>
                                            <li class="font-weight-bold mt-2" style="text-indent:40px">Branch : <span class="font-weight-normal">{{ $inventory->branch->branch }}</span> </li>
                                          </ul> -->

                                        </div>
                                  </div>
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                      <h3 class="display-4">Customer</h3>
                                      <div class="row">
                                        <div class="col-xl-4">
                                          <img src="http://getdrawings.com/free-icon-bw/facebook-avatar-icon-25.png" class="rounded float-left" alt="..." style="width:150px">
                                        </div>
                                        <div class="col-xl-8">
                                        <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                                            <table class="table table-borderless" style="margin:0 auto">
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Full Name :</td>
                                                  <td>{{ $inventory->customer->first_name." ".$inventory->customer->middle_name." ".$inventory->customer->last_name }}</td>
                                                </tr>
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Address :</td>
                                                  <td>{{ $inventory->customer->present_address }}</td>
                                                </tr>
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Birthday :</td>
                                                  <td>{{ date('F d, Y', strtotime($inventory->customer->birthdate)) }}</td>
                                                </tr>
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Sex :</td>
                                                  <td>{{ ucwords($inventory->customer->sex) }}</td>
                                                </tr>
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Civil Status :</td>
                                                  <td>{{ ucwords($inventory->customer->civil_status) }}</td>
                                                </tr>
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Contact # :</td>
                                                  <td>{{ $inventory->customer->contact_number }}</td>
                                                </tr>
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Email :</td>
                                                  <td>{{ $inventory->customer->email }}</td>
                                                </tr>
                                            </table>
                                          </div>
                                          <!-- <ul style="padding-left:5px;list-style-type:none">
                                            <li class="font-weight-bold">Full Name : <span class="font-weight-normal"> {{ $inventory->customer->first_name." ".$inventory->customer->middle_name." ".$inventory->customer->last_name }} </span> </li>
                                            <li class="font-weight-bold">Address : <span class="font-weight-normal"> {{ $inventory->customer->present_address }}</span>  </li>
                                            <li class="font-weight-bold">Birthday : <span class="font-weight-normal"> {{ date('F d, Y', strtotime($inventory->customer->birthdate)) }}</span>  </li>
                                            <li class="font-weight-bold">Sex : <span class="font-weight-normal"> {{ ucwords($inventory->customer->sex) }}</span>  </li>
                                            <li class="font-weight-bold">Civil Status : <span class="font-weight-normal"> {{ ucwords($inventory->customer->civil_status) }}</span>  </li>
                                            <li class="font-weight-bold">Contact # : <span class="font-weight-normal"> {{ $inventory->customer->contact_number }}</span>  </li>
                                            <li class="font-weight-bold">Email : <span class="font-weight-normal"> {{ $inventory->customer->email }}</span>  </li>
                                          </ul> -->

                                        </div>
                                        <!-- <div class="col-xl-6">Birthday:</div> -->
                                        <!-- <div class="col-xl-6">Sex:</div> -->
                                        <!-- <div class="col-xl-6">Contact #:</div> -->
                                      </div>


                                    </div>

                                <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                                <h3 class="display-4 text-center">Item List</h3>

                                      <table class="table table-hover branch_table">
                                            <thead>
                                                <tr>
                                                  <th>#</th>
                                                  <th>Item Type</th>
                                                  <th>Item Name</th>
                                                  <th>Karat</th>
                                                  <th>Weight (g)</th>
                                                  <th>Description</th>
                                                  <th>Appraised Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @isset($inventory->item)
                                                  @php
                                                    $count = 1;
                                                    $appraised_value = 0;
                                                  @endphp
                                                  @foreach($inventory->item as $key => $value)
                                                    @switch($value->status)
                                                      @case('1')
                                                         <tr style="background:linear-gradient(60deg, #f55a4e, #f32c1e)">
                                                      @break
                                                      @case('2')
                                                        <tr style="background:linear-gradient(60deg, #8cc352, #72a83a)">
                                                      @break
                                                      @default
                                                        <tr>
                                                    @endswitch
                                                      <td>{{ $count++ }}</td>
                                                      <td>{{ $value->item_type->item_type }}</td>
                                                      <td>{{ $value->item_name }}</td>
                                                      <td>{{ $value->item_karat }}</td>
                                                      <td>{{ $value->item_karat_weight }}</td>
                                                      <td>{{ $value->description }}</td>
                                                      <td>{{ number_format($value->ticket_item->item_name_appraised_value,2) }}</td>
                                                    </tr>
                                                    @php
                                                      $appraised_value += $value->ticket_item->item_name_appraised_value;
                                                    @endphp
                                                  @endforeach
                                                    <tr class="table-success">
                                                        <th colspan="6" style="text-align:right">Total : </th>
                                                        <th>{{ number_format($appraised_value,2) }}</th>
                                                    </tr>
                                                @endisset
                                            </tbody>
                                       </table>

                                  </div>

                                </div>
    
                            </div>
                        </div>





                    <div class="card">
                          <div class="card-header card-header-text card-header-primary">
                          
                              <div class="card-text">
                              <h4 class="card-title">Transaction Details</h4>
                              </div>
                          </div>
                            <div class="card-body card-body-form">
                              <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                                  <table class="table table-hover branch_table">
                                        <thead>
                                            <tr>
                                              <th>#</th>
                                              <th>Ticket #</th>
                                              <th>Transaction Date</th>
                                              <th>Maturity Date<br/>Expiration Date<br/>Auction Date</th>
                                              <th>Transaction Type</th>
                                              <th>Net Proceeds</th>
                                              <th>Payment</th>
                                              <th>Attachment</th>
                                              <th>Processed By</th>
                                              <th style="width:15%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @isset($ticket)
                                              @php
                                                $count = 1;
                                                $net_proceeds = 0;
                                                $payment = 0;
                                              @endphp
                                              @foreach($ticket as $key => $value)
                                                @php
                                                  $transaction_date = isset($value->transaction_date) ?  date('m-d-Y', strtotime($value->transaction_date)) : ""; 
                                                  $maturity_date = isset($value->maturity_date) ?  date('m-d-Y', strtotime($value->maturity_date)) : ""; 
                                                  $expiration_date = isset($value->expiration_date) ?  date('m-d-Y', strtotime($value->expiration_date)) : ""; 
                                                  $auction_date = isset($value->auction_date) ?  date('m-d-Y', strtotime($value->auction_date)) : ""; 
                                                  $net_proceeds += isset($value->status) && $value->status == 0  ?  $value->net : 0;
                                                  $payment += isset($value->payment) ? $value->payment->amount : 0;

                                                @endphp
                                                <tr>
                                                  <td>{{ $count++ }}</td>
                                                  <td>{{ $value->ticket_number }}</td>
                                                  <td>{{ $transaction_date  }} <br/>
                                                  <td>{{ $maturity_date  }} <br/>
                                                      {{ $expiration_date}} <br/>
                                                      {{ $auction_date }}
                                                  </td>
                                                  <td>{{ strtoupper($value->transaction_type) }}</td>
                                                  <td>{{ number_format($value->net, 2) }}</td>
                                                  <td>{{ isset($value->payment) ?  number_format($value->payment['amount'],2) : "" }}</td>
                                                  <td>{{ $value->attachment->type }}<br/>{{ $value->attachment_number }}</td>

                                                  <td>{{ ucwords($value->encoder->first_name)." ".ucwords($value->encoder->last_name) }}</td>
                                                  <td>
                                                      <a href="{{ route('pawn_print', ['id' => $id, 'ticket_id' => $value->id ]) }}" target="_blank" class="btn ordinario-button btn-sm btn-responsive">Print</a>
                                                      {{--
                                                      @if($value->transaction_type == 'renew' && $ticket_update->id == $value->id)
                                                        <a href="{{ route('renew_update', ['ticket_id' => $value->id, 'id' => $id ]) }}" target="_blank" class="btn btn-success btn-sm btn-responsive"><span class="material-icons">edit</span></a>
                                                      @endif   
                                                      --}}
                                                      {{--
                                                      @if($value->transaction_type == 'redeem' && $ticket_update->id == $value->id)
                                                        <a href="{{ route('redeem_update', ['ticket_id' => $value->id, 'id' => $id ]) }}" target="_blank" class="btn btn-success btn-sm btn-responsive"><span class="material-icons">edit</span></a>
                                                      @endif       
                                                      @if($value->transaction_type == 'repawn' && $ticket_update->id == $value->id)
                                                        <a href="{{ route('repawn_update', ['ticket_id' => $value->id, 'id' => $id ]) }}" target="_blank" class="btn btn-success btn-sm btn-responsive"><span class="material-icons">edit</span></a>
                                                      @endif        
                                                      --}}                                
                                            
                                                  </td>
                                                </tr>
                                              @endforeach
                                                      <tr class="table-success">
                                                          <th colspan="5" style="text-align:right">Total : </th>
                                                          <th>{{ number_format($net_proceeds,2) }}</th>
                                                          <th>{{ number_format($payment,2) }}</th>
                                                          <th colspan="3">Balance : {{ number_format(round($net_proceeds, 2) - round($payment, 2),2) }}</th>
                                                      </tr>
                                            @endisset
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                    </div>
                <!-- end content-->
              </div>

              <!--  end card  -->
            </div>

</div>

@endsection