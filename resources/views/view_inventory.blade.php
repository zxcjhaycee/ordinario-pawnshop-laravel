@php
 $routeName = substr(Route::currentRouteName(), strpos(Route::currentRouteName(), ".") + 1); // to identify if add or update
@endphp

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

                  @if($inventory->status == 0)
                  <a href="{{ route('inventory.renew', $id) }}" class="btn btn-success float-right btn-responsive">Renew</a>
                  <a href="{{ route('inventory.redeem', $id) }}" class="btn btn-success float-right btn-responsive">Redeem</a>
                    <form action="{{ route('inventory.auction') }}" method="post">
                      @csrf
                        <input type="hidden" name="inventory_id" value="{{ $id }}">
                      <input type="submit" value="Auction" class="btn btn-success float-right btn-responsive">
                    </form>
                  @endif

                    <h4 class="card-title"> {{ ucwords($routeName) }} Inventory</h4>

                </div>
    
                 <div class="card-body">
                    @include('alert')

                    <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                      <table class="table table-hover branch_table">
                            <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Ticket #</th>
                                  <th>Transaction Date<br/>Maturity Date<br/>Expiration Date<br/>Auction Date</th>
                                  <th>Transaction Type</th>
                                  <th>Net Proceeds</th>
                                  <th>Attachment</th>
                                  <th>Processed By</th>
                                  <th style="width:15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($ticket)
                                  @php
                                    $count = 1;
                                  @endphp
                                  @foreach($ticket as $key => $value)
                                    <tr>
                                      <td>{{ $count++ }}</td>
                                      <td>{{ $value->ticket_number }}</td>
                                      <td>{{ date('m-d-Y', strtotime($value->transaction_date)) }} <br/>
                                          {{ date('m-d-Y', strtotime($value->maturity_date))  }} <br/>
                                          {{ date('m-d-Y', strtotime($value->expiration_date )) }} <br/>
                                          {{ date('m-d-Y', strtotime($value->auction_date)) }}
                                      </td>
                                      <td>{{ strtoupper($value->transaction_type) }}</td>
                                      <td>{{ number_format($value->net, 2) }}</td>
                                      <td>{{ $value->attachment->type }}<br/>{{ $value->attachment_number }}</td>

                                      <td>{{ ucwords($value->encoder->first_name)." ".ucwords($value->encoder->last_name) }}</td>
                                      <td>
                                          <a href="{{ route('pawn_print', ['id' => $id, 'ticket_id' => $value->id ]) }}" target="_blank" class="btn ordinario-button btn-sm btn-responsive">Print</a>
                                          @if($value->transaction_type == 'renew')
                                             <a href="{{ route('renew_update', ['ticket_id' => $value->id, 'id' => $id ]) }}" target="_blank" class="btn btn-success btn-sm btn-responsive"><span class="material-icons">edit</span></a>
                                          @endif                                      
                                      </td>
                                    </tr>
                                  @endforeach
                                @endisset
                            </tbody>
                        </table>

                    </div>
                    <div class="card">
                                <div class="card-header card-header-text card-header-primary">
                                
                                    <div class="card-text">
                                    <h4 class="card-title">Inventory Details</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                <div class="row">
                                
                                  <div class="col-xl-6">
                                      <h3 class="display-4">Details</h3>
                                        <div class="col-xl-12 mt-4">

                                          <ul style="list-style-type:none;padding-left: 5px;">
                                            <li class="font-weight-bold" style="text-indent:11px">Inventory # : <span class="font-weight-normal">{{ $inventory->inventory_number }}</span></li>
                                            <li class="font-weight-bold mt-2" style="text-indent:8px">Item Category : <span class="font-weight-normal">{{ $inventory->item_category->item_category }}</span> </li>
                                            <li class="font-weight-bold mt-2" style="text-indent:8px">Loan Status : <span class="font-weight-normal">Outstanding</span> </li>
                                            <li class="font-weight-bold mt-2" style="text-indent:40px">Branch : <span class="font-weight-normal">{{ $inventory->branch->branch }}</span> </li>
                                          </ul>

                                        </div>
                                  </div>
                                    <div class="col-xl-6">
                                      <h3 class="display-4">Customer</h3>
                                      <div class="row">
                                        <div class="col-xl-4">
                                          <img src="http://getdrawings.com/free-icon-bw/facebook-avatar-icon-25.png" class="rounded float-left" alt="..." style="width:150px">
                                        </div>
                                        <div class="col-xl-8">
                                          <ul style="padding-left:5px;list-style-type:none">
                                            <li class="font-weight-bold">Full Name : <span class="font-weight-normal"> {{ $inventory->customer->first_name." ".$inventory->customer->middle_name." ".$inventory->customer->last_name }} </span> </li>
                                            <li class="font-weight-bold">Address : <span class="font-weight-normal"> {{ $inventory->customer->present_address }}</span>  </li>
                                            <li class="font-weight-bold">Birthday : <span class="font-weight-normal"> {{ date('F d, Y', strtotime($inventory->customer->birthdate)) }}</span>  </li>
                                            <li class="font-weight-bold">Sex : <span class="font-weight-normal"> {{ ucwords($inventory->customer->sex) }}</span>  </li>
                                            <li class="font-weight-bold">Civil Status : <span class="font-weight-normal"> {{ ucwords($inventory->customer->civil_status) }}</span>  </li>
                                            <li class="font-weight-bold">Contact # : <span class="font-weight-normal"> {{ $inventory->customer->contact_number }}</span>  </li>
                                            <li class="font-weight-bold">Email : <span class="font-weight-normal"> {{ $inventory->customer->email }}</span>  </li>

                                          </ul>
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
                                                  <th style="width:15%">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @isset($inventory->item)
                                                  @php
                                                    $count = 1;
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
                                                      <td>{{ number_format($value->item_type_appraised_value,2) }}</td>

                                                      <td></td>
                                                    </tr>
                                                  @endforeach
                                                @endisset
                                            </tbody>
                                       </table>

                                  </div>

                                </div>
    
                            </div>
                        </div>


                <!-- end content-->
              </div>

              <!--  end card  -->
            </div>

</div>

@endsection