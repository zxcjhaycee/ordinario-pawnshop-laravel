@php
 $routeName = substr(Route::currentRouteName(), strpos(Route::currentRouteName(), ".") + 1); // to identify if add or update
@endphp
@section('title', 'Pawn Ticket #: '.$ticket->ticket_number )
@extends('layout')
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

    .table_display tr td:first-child{
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
                  {{--
                  @if($inventory->status == 1)
                      <div><span class="badge badge-danger float-right" style="font-size:30px!important">Auctioned</span></div>
                  @endif
                 --}}
                  @if($ticket->status == 0)
                  <a href="{{ route('pawn.renew', ['id' => $ticket->inventory_id, 'pawn_id' => $ticket->id]) }}" class="btn btn-success float-right btn-responsive">Renew</a>
                  <a href="{{ route('pawn.redeem', ['id' => $ticket->inventory_id, 'pawn_id' => $ticket->id]) }}" class="btn btn-success float-right btn-responsive">Redeem</a>
                    {{--
                    <form action="{{ route('inventory.auction') }}" method="post">
                      @csrf
                        <input type="hidden" name="inventory_id" value="{{ $ticket->inventory_id }}">
                      <input type="submit" value="Auction" class="btn btn-success float-right btn-responsive">
                    </form>
                    --}}
                  @else
                    @if($ticket->repawn == 0)
                     <a href="{{ route('pawn.repawn', ['id' => $ticket->inventory_id]) }}" class="btn btn-success float-right btn-responsive">Repawn</a>
                     @endif
                  @endif
                    <h4 class="card-title"> {{ ucwords($routeName) }} Pawn</h4>

                </div>
    
                 <div class="card-body">
                    @include('alert')

                    <div class="card">
                                <div class="card-header card-header-text card-header-primary">
                                
                                    <div class="card-text">
                                    <h4 class="card-title">Ticket Details</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                <div class="row">
                                
                                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                      <h3 class="display-4">Details</h3>
                                        <div class="mt-4">
                                        <div class="table-responsive material-datatables" style="overflow-y: hidden;">

                                        <table class="table table-borderless table_display" style="margin:0 auto">
                                            <tr>
                                              <td style="text-align:right;font-weight:bold">Inventory # :</td>
                                              <td>{{ $ticket->inventory->inventory_number }}</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:right;font-weight:bold">Ticket # :</td>
                                              <td>{{ $ticket->ticket_number }}</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:right;font-weight:bold">Type :</td>
                                              <td>{{ ucwords($ticket->transaction_type) }}</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:right;font-weight:bold">Category :</td>
                                              <td>{{ $ticket->inventory->item_category->item_category }}</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:right;font-weight:bold">Loan Status :</td>
                                              <td>{{ 'Outstanding' }}</td>
                                            </tr>
                                            <tr>
                                              <td style="text-align:right;font-weight:bold">Branch :</td>
                                              <td>{{ $ticket->inventory->branch->branch }}</td>
                                            </tr>
                                        </table>
                                        </div>
                                          <!-- 
                                          <ul style="list-style-type:none;padding-left: 5px;">
                                          <li class="font-weight-bold" style="text-indent:11px">Inventory # : <span class="font-weight-normal">{{ $ticket->inventory->inventory_number }}</span></li>
                                            <li class="font-weight-bold mt-2" style="text-indent:30px">Ticket # : <span class="font-weight-normal">{{ $ticket->ticket_number }}</span></li>
                                            <li class="font-weight-bold mt-2" style="text-indent:8px">Transaction Type: <span class="font-weight-normal">{{ ucwords($ticket->transaction_type) }}</span></li>
                                            <li class="font-weight-bold mt-2" style="text-indent:8px">Item Category : <span class="font-weight-normal">{{ $ticket->inventory->item_category->item_category }}</span> </li>
                                            <li class="font-weight-bold mt-2" style="text-indent:8px">Loan Status : <span class="font-weight-normal">Outstanding</span> </li>
                                            <li class="font-weight-bold mt-2" style="text-indent:40px">Branch : <span class="font-weight-normal">{{ $ticket->inventory->branch->branch }}</span> </li>
                                          </ul>
                                           -->
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

                                            <table class="table table-borderless table_display" style="margin:0 auto">
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Full Name :</td>
                                                  <td>{{ $ticket->inventory->customer->first_name." ".$ticket->inventory->customer->middle_name." ".$ticket->inventory->customer->last_name }}</td>
                                                </tr>
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Address :</td>
                                                  <td>{{ $ticket->inventory->customer->present_address }}</td>
                                                </tr>
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Birthday :</td>
                                                  <td>{{ date('F d, Y', strtotime($ticket->inventory->customer->birthdate)) }}</td>
                                                </tr>
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Sex :</td>
                                                  <td>{{ ucwords($ticket->inventory->customer->sex) }}</td>
                                                </tr>
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Civil Status :</td>
                                                  <td>{{ ucwords($ticket->inventory->customer->civil_status) }}</td>
                                                </tr>
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Contact # :</td>
                                                  <td>{{ $ticket->inventory->customer->contact_number }}</td>
                                                </tr>
                                                <tr>
                                                  <td style="text-align:center;font-weight:bold">Email :</td>
                                                  <td>{{ $ticket->inventory->customer->email }}</td>
                                                </tr>
                                            </table>
                                          </div>
                                          <!-- <ul style="padding-left:5px;list-style-type:none">
                                            <li class="font-weight-bold">Full Name : <span class="font-weight-normal"> {{ $ticket->inventory->customer->first_name." ".$ticket->inventory->customer->middle_name." ".$ticket->inventory->customer->last_name }} </span> </li>
                                            <li class="font-weight-bold">Address : <span class="font-weight-normal"> {{ $ticket->inventory->customer->present_address }}</span>  </li>
                                            <li class="font-weight-bold">Birthday : <span class="font-weight-normal"> {{ date('F d, Y', strtotime($ticket->inventory->customer->birthdate)) }}</span>  </li>
                                            <li class="font-weight-bold">Sex : <span class="font-weight-normal"> {{ ucwords($ticket->inventory->customer->sex) }}</span>  </li>
                                            <li class="font-weight-bold">Civil Status : <span class="font-weight-normal"> {{ ucwords($ticket->inventory->customer->civil_status) }}</span>  </li>
                                            <li class="font-weight-bold">Contact # : <span class="font-weight-normal"> {{ $ticket->inventory->customer->contact_number }}</span>  </li>
                                            <li class="font-weight-bold">Email : <span class="font-weight-normal"> {{ $ticket->inventory->customer->email }}</span>  </li>
                                          </ul>
                                           -->
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
                                                @php
                                                    $count = 1;
                                                @endphp
                                                @foreach($ticket->item_tickets as $key => $value)
                                                    <tr>
                                                        <td>{{ $count++ }}</td>
                                                        <td>{{ $value->inventory_items->item_type->item_type }}</td>
                                                        <td>{{ $value->inventory_items->item_name }}</td>
                                                        <td>{{ $value->inventory_items->item_karat }}</td>
                                                        <td>{{ $value->inventory_items->item_karat_weight }}</td>
                                                        <td>{{ $value->inventory_items->description }}</td>
                                                        <td>{{ number_format($value->item_name_appraised_value,2) }}</td>
                                                    
                                                    </tr>
                                                @endforeach
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
                                  <table class="table table-hover">
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
                                            @php
                                                $ticket_count = 1;
                                                $net_proceeds = 0;
                                                $payment = 0;

                                            @endphp
                                            @foreach($ticket->pawn_parent_many as $key => $value)
                                            @php
                                                  $transaction_date = isset($value->ticket_child->transaction_date) ?  date('m-d-Y', strtotime($value->ticket_child->transaction_date)) : ""; 
                                                  $maturity_date = isset($value->ticket_child->maturity_date) ?  date('m-d-Y', strtotime($value->ticket_child->maturity_date)) : ""; 
                                                  $expiration_date = isset($value->ticket_child->expiration_date) ?  date('m-d-Y', strtotime($value->ticket_child->expiration_date)) : ""; 
                                                  $auction_date = isset($value->ticket_child->auction_date) ?  date('m-d-Y', strtotime($value->ticket_child->auction_date)) : ""; 
                                                  $net_proceeds += $value->ticket_child->status == 0 ?  $value->ticket_child->net : 0;
                                                  $payment += isset($value->ticket_child->payment) ? $value->ticket_child->payment->amount : 0;

                                                @endphp
                                            <tr>
                                                  <td>{{ $ticket_count++ }}</td>
                                                  <td>{{ $value->ticket_child->ticket_number }}</td>
                                                  <td>{{ $transaction_date  }} <br/>
                                                  <td>{{ $maturity_date  }} <br/>
                                                      {{ $expiration_date}} <br/>
                                                      {{ $auction_date }}
                                                  </td>
                                                  <td>{{ strtoupper($value->ticket_child->transaction_type) }}</td>
                                                  <td>{{ number_format($value->ticket_child->net, 2) }}</td>
                                                  <td>{{ isset($value->ticket_child->payment) ?  number_format($value->ticket_child->payment['amount'],2) : "" }}</td>
                                                  <td>{{ $value->ticket_child->attachment->type }}<br/>{{ $value->ticket_child->attachment_number }}</td>

                                                  <td>{{ ucwords($value->ticket_child->encoder->first_name)." ".ucwords($value->ticket_child->encoder->last_name) }}</td>
                                                  <td>
                                                      <a href="{{ route('pawn_print', ['id' => $value->ticket_child->inventory_id, 'ticket_id' => $value->ticket_child->id ]) }}" target="_blank" class="btn ordinario-button btn-sm btn-responsive">Print</a>
                                                      @if($value->ticket_child->transaction_type == 'renew' && $ticket_update->pawn_parent->ticket_child->transaction_type == 'renew')
                                                          @if(Auth::user()->isStaff())
                                                            @if($ticket_update->pawn_parent->ticket_id == $value->ticket_child->id)
                                                            <a href="{{ route('renew_update', ['ticket_id' => $value->ticket_child->id, 'id' => $value->ticket_child->inventory_id ]) }}" target="_blank" class="btn btn-success btn-sm btn-responsive"><span class="material-icons">edit</span></a>
                                                            @endif
                                                          @else
                                                            <a href="{{ route('renew_update', ['ticket_id' => $value->ticket_child->id, 'id' => $value->ticket_child->inventory_id ]) }}" target="_blank" class="btn btn-success btn-sm btn-responsive"><span class="material-icons">edit</span></a>
                                                          @endif
                                                      @endif   
                                                      @if($value->ticket_child->transaction_type == 'redeem' && $ticket_update->pawn_parent->ticket_id == $value->ticket_child->id && $ticket->repawn == 0)
                                                          <a href="{{ route('redeem_update', ['ticket_id' => $value->ticket_child->id, 'id' => $value->ticket_child->inventory_id ]) }}" target="_blank" class="btn btn-success btn-sm btn-responsive"><span class="material-icons">edit</span></a>
                                                      @endif       

                                            
                                                  </td>
                                                </tr>
                                            @endforeach
                                                    <tr class="table-success">
                                                          <th colspan="5" style="text-align:right">Total : </th>
                                                          <th>{{ number_format($net_proceeds,2) }}</th>
                                                          <th>{{ number_format($payment,2) }}</th>
                                                          <th colspan="3">Balance : {{ number_format(round($net_proceeds, 2) - round($payment, 2),2) }}</th>
                                                      </tr>
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