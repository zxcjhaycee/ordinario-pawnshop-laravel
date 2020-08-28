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

                    <h4 class="card-title">Redeem Pawn</h4>

                </div>
    
                 <div class="card-body">
                    @include('alert')
                    <div class="alert_message"></div>
                    <form enctype="multipart/form-data" id="redeemForm" onSubmit="redeemForm(event, this)" method="POST">
                    @csrf
                    @isset($ticket_id)
                        @method('PUT')
                    @endisset
                    <div class="card">
                                <div class="card-header card-header-text card-header-primary">
                                
                                    <div class="card-text">
                                    <h4 class="card-title">Pawn Ticket : {{ isset($tickets_current) ? $tickets_current->pawnTickets->ticket_number : $ticket_number }}</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                <div class="row">
                                
                                  <div class="col-xl-6">
                                      <!-- <h3 class="display-4">Details</h3> -->
                                      <div class="col-xl-12 mt-4">
                                        <ul style="list-style-type:none;padding-left: 5px;">
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Inventory #</label> : <span class="font-weight-normal col-xl-5">{{ $inventory->inventory_number }}</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">OR #</label> : <span class="font-weight-normal col-xl-5">{{ isset($tickets_current) ? $tickets_current->pawnTickets->payment->or_number : $or_number }}</span></li>
                                            <li class="form-inline"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Transaction Date </label> : <input type="text" class="form-control transaction_picker" style="margin-left:20px"  name="transaction_date" autocomplete="off" value="{{ isset($tickets_current) ? date('m/d/Y' ,strtotime($tickets_current->pawnTickets->transaction_date)) : '' }}"></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Transaction Type </label> : <span class="font-weight-normal col-xl-5">Redeem</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Interbranch Transaction </label> : <input type="checkbox" style="width:50px;box-shadow:none" value="1" name="interbranch" {{ isset($tickets_current) && $tickets_current->pawnTickets->interbranch == 1 ? 'checked' : ''  }}></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Original PT #</label> : <span class="font-weight-normal col-xl-5">{{ $inventory->pawnTickets->ticket_number }}</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Date Loaned</label> : <span class="font-weight-normal col-xl-5">{{ date('M d, Y', strtotime($inventory->pawnTickets->transaction_date)) }}</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Maturity Date</label> : <span class="font-weight-normal col-xl-5">{{ date('M d, Y', strtotime($inventory->pawnTickets->maturity_date)) }}</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Branch </label> : <span class="font-weight-normal col-xl-5">{{ $inventory->branch->branch }}</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Item Category </label> : <span class="font-weight-normal col-xl-5">{{ $inventory->item_category->item_category }}</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Processed By</label> : <span class="font-weight-normal col-xl-5">{{ $inventory->pawnTickets->encoder->first_name." ".$inventory->pawnTickets->encoder->last_name }}</span></li>
                                        </ul>
                                        <!-- <div class="form-inline">

                                        </div> -->
                                      </div>

                                  </div>
                                  <div class="col-xl-6">
                                      <h3 class="display-4">Customer</h3>
                                      <div class="col-xl-12 mt-4">
                                        <ul style="list-style-type:none;padding-left: 5px;">
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Name </label> : <span class="font-weight-normal col-xl-5">{{ $inventory->customer->first_name." ".$inventory->customer->last_name." ".$inventory->customer->suffix }}</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Customer Image</label> : <img src="http://getdrawings.com/free-icon-bw/facebook-avatar-icon-25.png" class="rounded" alt="..." style="width:150px"></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Authorized Representative</label> : <input type="checkbox" style="width:50px;box-shadow:none" name="authorized_representative" value="1" {{ isset($tickets_current) && $tickets_current->pawnTickets->authorized_representative == 1 ? 'checked' : ''  }}></li>
                                            <li class="form-inline"  style="height:40px"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">ID Presented</label> : 
                                            <select name="attachment_id" id="attachment_id" class="form-control attachment_select" style="width:200px;margin-left:20px" onChange="getAttachmentNumber(this.options[this.selectedIndex])" name="attachment_id">
                                                <!-- <option>Hello!</option> -->
                                                <option disabled selected>Please select attachment...</option>
                                                @foreach($inventory->customer->attachments as $key => $value)
                                                    @if(isset($tickets_current) && $value->id == $tickets_current->pawnTickets->attachment_id)
                                                        <option value="{{ $value->id }}" data-number="{{ $value->pivot->number }}" selected>{{ $value->type }}</option>
                                                    @else
                                                        <option value="{{ $value->id }}" data-number="{{ $value->pivot->number }}">{{ $value->type }}</option>
                                                    @endif      
                                                @endforeach
                                            </select>
                                            </li>
                                            <li class="form-inline"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">ID Number</label> : <input type="text" class="form-control" style="margin-left:20px;width:200px" id="attachment_number" readonly name="attachment_number" value="{{ isset($tickets_current) ? $tickets_current->pawnTickets->attachment_number : '' }}"></li>
                                            <!-- <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Image</label> : <span class="font-weight-normal col-xl-5">Renew</span></li> -->
                                        </ul>
                                        <!-- <div class="form-inline">

                                        </div> -->
                                      </div>

                                  </div>



                                </div>
    
                            </div>
                        </div>



                        <div class="card">
                                <div class="card-header">
                                
                                    <div class="card-text">
                                    </div>
                                </div>
                                <div class="card-body">
                                <div class="row">
                                
                                    <div class="col-xl-12">
                                      <h3 class="display-4">Item Details</h3>
                                      <div class="table-responsive material-datatables" style="overflow-y: hidden;">

                                      <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                  <!-- <th><input type="checkbox" name="all" id="all" onClick="toggle(this)"></th> -->
                                                  <th style="width:30%">{{ $inventory->item_category->item_category }}</th>
                                                  <th>Details</th>
                                                  <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($inventory->pawnTickets->item_tickets as $key => $value)
                                                    @if($inventory->item_category_id == 1)
                                                    <tr>
                                                        <!-- <td><input type="checkbox" class="item" name="item[]" id="item" value='{{ $value->id }}' onClick="toggleState()"></td> -->
                                                        <td><img src="https://i.pinimg.com/originals/81/b8/ec/81b8ec9c3450c00ef1d9dbfc1d6de9d6.png" alt="" class="rounded" style="width:130px"></td>
                                                        <td>{{ $value->inventory_items->item_type->item_type." (".$value->inventory_items->item_karat."K P ".number_format($value->item_type_appraised_value, 2).")" }}<br/>
                                                        {{ number_format($value->inventory_items->item_type_weight,2)."g / ".number_format($value->inventory_items->item_name_weight,2)."g / ". number_format($value->inventory_items->item_karat_weight,2)."g" }}
                                                        <br/>

                                                        {{-- 'P '. number_format($value->item_type_appraised_value ,2) --}} 
                                                        <!-- <br/> -->
                                                        <!-- babaguhin pa -->
                                                        </td>
                                                        <td> {{ $value->inventory_items->description }}</td>
                                                    </tr>
                                                    @else
                                                    <tr>
                                                        <td><img src="https://i.pinimg.com/originals/81/b8/ec/81b8ec9c3450c00ef1d9dbfc1d6de9d6.png" alt="" class="rounded" style="width:130px"></td>
                                                        <td>{{ $value->inventory_items->item_type->item_type }}<br/>
                                                        {{ $value->inventory_items->item_name }}
                                                        <br/>
                                                        {{ 'P '. number_format($value->item_type_appraised_value,2) }} 

                                                        <!-- babaguhin pa -->
                                                        </td>
                                                        <td> {{ $value->inventory_items->description }}</td>

                                                    </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                       </table>

                                  </div>
                                      

                                    </div>

                                    <div class="col-xl-12">
                                        <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                    <th>Start Date</th>
                                                    <th>Date</th>
                                                    <th>Deadline</th>
                                                    <th>Interest</th>
                                                    <th>Penalty</th>
                                                    <th>Net</th>
                                                    <th>Payment</th>
                                                    <th>Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $interest_total = 0;
                                                        $penalty_total = 0;
                                                        $net_total = 0;
                                                        $payment_total = 0;
                                                        $balance_total = 0;
                                                    @endphp
                                                    @foreach($tickets->pawn_parent_many as $key => $value)
                                                    @isset($value->ticket_child)
                                                    <tr>
                                                        <td>{{ date('M d, Y', strtotime($value->ticket_child->transaction_date))  }}</td>
                                                        <td>{{ date('M d, Y', strtotime($value->ticket_child->maturity_date))  }}</td>
                                                        <td>{{ date('M d, Y', strtotime($value->ticket_child->maturity_date .'+ 1 day'))  }}</td>
                                                        <td>{{ number_format($value->ticket_child->interest, 2)  }}</td>
                                                        <td>{{ number_format($value->ticket_child->penalty, 2)  }}</td>
                                                        <td>{{ number_format($value->ticket_child->net,2) }}</td>
                                                        <td>{{ isset($value->ticket_child) ? number_format($value->ticket_child->payment->amount, 2) : 0  }}</td>
                                                        <td>{{ isset($value->ticket_child) ? number_format($value->ticket_child->net - $value->ticket_child->payment->amount, 2) : 0  }}</td>
                                                    </tr>
                                                        @php
                                                            $interest_total += $value->ticket_child->interest;
                                                            $penalty_total += $value->ticket_child->penalty;
                                                            $net_total += $value->ticket_child->net;
                                                            $payment_total += $value->ticket_child->payment->amount;
                                                            $balance_total += $value->ticket_child->net - $value->ticket_child->payment->amount;
                                                        @endphp
                                                    @endisset
                                                    @endforeach
                                                    <tr>
                                                        <th colspan="3" style="text-align:right"><b>Total : </b></td>
                                                        <th>{{ number_format($interest_total, 2) }}</td>
                                                        <th>{{ number_format($penalty_total, 2) }}</td>
                                                        <th>{{ number_format($net_total, 2) }}</td>
                                                        <th>{{ number_format($payment_total, 2) }}</td>
                                                        <th>{{ number_format($balance_total, 2) }}</td>
                                                    </tr>
                                                </tbody>
                                        </table>

                                        
                                    </div>
                                    <div class="col-xl-6">
                                    
                                    <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                    <th style="width:50%">Other Charges</th>
                                                    <th style="width:30%">Amount</th>
                                                    <th style="width:10%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="other_charges_body">
                                                @if(isset($tickets_current))
                                                        @foreach($tickets_current->pawnTickets->other_charges as $key => $value)
                                                            @if($value->inventory_other_charges->charge_type == 'charges')
                                                            <tr>
                                                                <td>
                                                                    <select name="other_charges_id[]" class="form-control select2 other_charges_select">
                                                                        <option value="{{ $value->inventory_other_charges->id }}">{{ $value->inventory_other_charges->charge_name }}</option>
                                                                        <option></option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="other_charges_amount[]" id="other_charges_amount" class="form-control other_charges_amount" value="{{ $value->amount }}">
                                                                    <input type="hidden" name="inventory_other_charges_id[]" id="inventory_other_charges_id" class="form-control inventory_other_charges_id" value="{{ $value->id }}">
                                                                </td>
                                                                <td>
                                                                     <button type="button" class="btn btn-danger btn-sm" onClick="removeTr(this)"><i class="material-icons">close</i></button>
                                                                </td>
                                                        </tr>
                                                        @endif
                                                        @endforeach
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <select name="other_charges_id[]" class="form-control select2 other_charges_select">
                                                                        <option></option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="other_charges_amount[]" id="other_charges_amount" class="form-control other_charges_amount">
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-sm" onClick="removeTr(this)"><i class="material-icons">close</i></button>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                </tbody>
                                                <tfoot>
                                            <tr>
                                                <td colspan="3">
                                                    <button type="button" class="btn btn-info btn-sm float-right" onClick="add_other_charges(this, 'other_charges')" style="margin:1px">Add</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                        </table>

                                        <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                    <th style="width:50%">Discount</th>
                                                    <th style="width:30%">Amount</th>
                                                    <th style="width:10%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="discount_body">
                                                    @if(isset($tickets_current))
                                                        @foreach($tickets_current->pawnTickets->other_charges as $key => $value)
                                                            @if($value->inventory_other_charges->charge_type == 'discount')
                                                            <tr>
                                                                <td>
                                                                    <select name="other_charges_id[]" class="form-control select2 discount_select">
                                                                        <option value="{{ $value->inventory_other_charges->id }}">{{ $value->inventory_other_charges->charge_name }}</option>
                                                                        <option></option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="other_charges_amount[]" id="discount_amount" class="form-control discount_amount" value="{{ $value->amount }}">
                                                                    <input type="hidden" name="inventory_other_charges_id[]" id="inventory_other_charges_id" class="form-control discount_id" value="{{ $value->id }}">
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-sm" onClick="removeTr(this)"><i class="material-icons">close</i></button>
                                                                </td>
                                                        </tr>
                                                        @endif
                                                        @endforeach
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <select name="other_charges_id[]" class="form-control select2 discount_select">
                                                                        <option></option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="other_charges_amount[]" id="discount_amount" class="form-control discount_amount">
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-sm" onClick="removeTr(this)"><i class="material-icons">close</i></button>
                                                                </td>
                                                            </tr>
                                                        @endif

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3">
                                                            <button type="button" class="btn btn-info btn-sm float-right" onClick="add_other_charges(this, 'discount')" style="margin:1px">Add</button>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                        </table>
                                       <!-- <ul style="list-style-type:none;padding-left: 5px;">
                                            <li class="form-inline" style="height:40px;">
                                                <label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Interest</label> : 
                                                <input type="number" name="interest_text" class="form-control" style="margin-left:20px;width:100px" id="interest_text" value="0" step=".01" onChange="setNetProceeds()">
                                            </li>
                                            <li class="form-inline" style="height:40px;">
                                                <label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Penalty</label> : 
                                                <input type="number" name="penalty_text" class="form-control" style="margin-left:20px;width:100px" id="penalty_text" value="0" step=".01" onChange="setNetProceeds()">
                                            </li>
                                        </ul> -->

                                    </div>
                                    <div class="col-xl-6">
                                    <h3 class="display-4">Computation</h3>


                                        <ul style="list-style-type:none;padding-left: 5px;">
                                            <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Principal</label> : <input type="text" name="principal" class="form-control" style="margin-left:20px;width:200px" id="principal" value="{{ $inventory->pawnTickets->principal }}" readonly></li>
                                                <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Interest</label> : <input type="text" name="interest" class="form-control" style="margin-left:20px;width:200px" id="interest" value="{{ round(($inventory->pawnTickets->principal * 0.03),2) }}" readonly></li>
                                                <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Advance Interest</label> : <input type="text" name="advance_interest" class="form-control" style="margin-left:20px;width:200px" id="advance_interest" value="{{ isset($inventory->advance_interest) ? $inventory->advance_interest : '0'}}" readonly></li>
                                                <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Penalty</label> : <input type="text" class="form-control" name="penalty" style="margin-left:20px;width:200px" id="penalty" value="{{  round($inventory->pawnTickets->principal * 0.02,2)}}" readonly ></li>
                                                <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Other Charges</label> : <input type="text" class="form-control" name="other_charges" style="margin-left:20px;width:200px" id="other_charges" value="{{ isset($tickets_current) ? $tickets_current->pawnTickets->charges : 0 }}" readonly></li>
                                                <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Discount</label> : <input type="text" class="form-control" name="discount" style="margin-left:20px;width:200px" id="discount" value="{{ isset($tickets_current) && $tickets_current->pawnTickets->discount ? $tickets_current->pawnTickets->discount : 0 }}" readonly></li>
                                                <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Net Proceeds</label> : <input type="text" name="net" class="form-control" style="margin-left:20px;width:200px" id="net_proceeds" readonly></li>
                                                @if(isset($prev_balance) && $prev_balance != 0)
                                                    <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Prev. Balance</label> : <input type="text" name="prev_balance" class="form-control" style="margin-left:20px;width:200px" id="prev_balance" readonly value="{{ isset($tickets_current) ? $prev_balance : $prev_balance  }}"></li>
                                                <!-- <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Total</label> : <input type="text" name="total" class="form-control" style="margin-left:20px;width:200px" id="total" readonly value="{{ isset($tickets_current) ? 0 : 0  }}"></li> -->
                                                @endisset

                                                <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Balance</label> : <input type="text" name="balance" class="form-control" style="margin-left:20px;width:200px" id="balance" value="{{ isset($tickets_current) ?  '' : 0  }}" readonly></li>
                                                <li class="form-inline"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Payment</label> : <input type="number" name="payment" class="form-control" style="margin-left:20px;width:200px" id="payment" value="{{ isset($tickets_current) ?  $tickets_current->pawnTickets->payment->amount : 0  }}"  step="0.01"></li>

                                        </ul>
                                        <div class="jumbotron" style="padding:0">
                                            <ul style="list-style-type:none;">
                                                <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Adv Int. Months </label> : <span class="font-weight-normal">0 month/s</span></li>
                                                <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Interest Rate </label> : <span class="font-weight-normal">{{ $inventory->pawnTickets->interest_percentage. "%" }}</span></li>
                                                <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Penalty Rate </label> : <span class="font-weight-normal">{{ $inventory->pawnTickets->penalty_percentage."%" }}</span></li>
                                                <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Grace Period </label> : <span class="font-weight-normal">1 day/s</span></li>
                                            </ul>
                                        </div>

                                    </div>

                                </div>
    
                            </div>
                        </div>
                <!-- end content-->
                <div class="text-center">
                        <input type="hidden" name="inventory_id" value="{{ $id }}">
                        <input type="hidden" name="ticket_number" value="{{ isset($tickets_current) ? $tickets_current->pawnTickets->ticket_number : $ticket_number }}">
                        <input type="hidden" name="processed_by"  value="{{ Auth::user()->id }}">
                        <input type="hidden" name="transaction_type"  value="redeem">
                        <input type="hidden" name="or_number"  value="{{ isset($tickets_current) ? $tickets_current->pawnTickets->payment->or_number : $or_number }}">
                        @isset($pawn_id)
                            <input type="hidden" name="pawn_id"  value="{{ $pawn_id }}">
                        @endisset
                        @isset($ticket_id)
                            <input type="hidden" name="id"  value="{{ $ticket_id }}">
                            <input type="hidden" name="payment_id"  value="{{ $tickets_current->pawnTickets->payment->id }}">
                        @endisset

                        <div class="d-flex justify-content-center">
                                 <input type="text" id="user_auth_code" class="form-control" style="margin-top:16px;width:130px" name="user_auth_code"  placeholder="Auth Code"/>
                                <button type="submit" class="btn btn-success" style="height:100%">Submit</button>
                        </div>
                </div>
                </form>
              </div>

              <!--  end card  -->
            </div>

</div>

@endsection
@push('scripts')
<script>
setNetProceeds();
    const other_charges = '.other_charges_select';
    const other_charges_route =  '{{ route("other_charges.search") }}' 
    const table_other_charges = 'charges';
    select2Initialized(other_charges,other_charges_route, table_other_charges);

    const discount = '.discount_select';
    const discount_route =  '{{ route("other_charges.search") }}' 
    const table_discount = 'discount';
    select2Initialized(discount,discount_route,table_discount);

    $(document).on('select2:select', '.other_charges_select, .discount_select', function (e) {
        const data = e.params.data;

        switch(data.action){
            case 'discount':
                $(this).closest('tr').find('.discount_amount').val(data['data-amount']);
                setDiscount();
            break;
            case 'amount':
                $(this).closest('tr').find('.other_charges_amount').val(data['data-amount']);
                setOtherCharges();
            break;

        }

        // console.log(data.id);
        if(data.id == 'link'){
            window.open(data.link);
            $(this).val(null).trigger("change");  
        }



    });

    $('.transaction_picker').datepicker({
    language: 'en',
    todayButton: new Date(),
    autoClose : true,
    position: "bottom center",
    minDate: new Date('{{ date("m-d-Y", strtotime($tickets_latest->transaction_date. "+1 day")) }}')
    // inline : true,
    // minDate: new Date() // Now can select only dates, which goes after today
  });

  function setNetProceeds(){
        const principal = document.getElementById('principal').value;
        const other_charges = document.getElementById('other_charges').value;
        const penalty = document.getElementById('penalty').value;
        const interest = document.getElementById('interest').value;
        // const penalty_text = document.getElementById('penalty_text').value;
        // const interest_text = document.getElementById('interest_text').value;
        const advance_interest = document.getElementById('advance_interest').value;
        const discount = document.getElementById('discount').value;

        const net_proceeds = document.getElementById('net_proceeds');
        // net_proceeds.value = (( parseFloat(principal) + parseFloat(other_charges) + parseFloat(penalty) + parseFloat(interest) + parseFloat(advance_interest) + parseFloat(interest_text) + parseFloat(penalty_text)) - parseFloat(discount)).toFixed(2);
        net_proceeds.value = (( parseFloat(principal) + parseFloat(other_charges) + parseFloat(penalty) + parseFloat(interest) + parseFloat(advance_interest)) - parseFloat(discount)).toFixed(2);
    setBalance();
    }

    function enableDiscount(element){
        const discount = document.getElementById('discount');
        discount.setAttribute('readonly', 'true');
        if(element.value != ''){
            discount.removeAttribute('readonly');
        }

    }

    function setInterest(element){
        const interest_text = element;
        const interest = document.getElementById('interest');
        const total = parseFloat(interest_text.value) + parseFloat(interest.value);

        setNetProceeds();
        // setInterest2(total);
    }


    function setPenalty(element){
        const penalty_text = element
        const penalty = document.getElementById('penalty');
        penalty.value = parseFloat(penalty_text.value) + parseFloat(penalty.value);
        setNetProceeds();
    }

    function toggle(source) {
        checkboxes = document.querySelectorAll('.item');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleState(){
        checkbox = document.getElementById('all');
        checkbox.checked = false;
        if(AreAnyCheckboxesChecked()){
            checkbox.checked = true;
        }
    }

    function toggleCheck(element){
        checkboxes = element.querySelector('td .item');
        if(checkboxes.checked === false){
            checkboxes.checked = true;
        }else{
            checkboxes.checked = false;

        }
        toggleState();
        // console.log(checkboxes.checked);

    }

    function AreAnyCheckboxesChecked () {
        if($('.item').not(':checked').length > 0){
            return false;
        }
        return true;
    }

    function setBalance(){
        const payment = document.getElementById('payment').value;
        const net_proceeds = document.getElementById('net_proceeds').value;
        const prev_balance = document.getElementById('prev_balance');
        const balance = document.getElementById('balance');
        // console.log(prev_balance);
        balance.value = prev_balance !== null ? (parseFloat(prev_balance.value) + parseFloat(net_proceeds)).toFixed(2) : (parseFloat(net_proceeds)).toFixed(2);
        const computation = prev_balance !== null ? (parseFloat(net_proceeds) + parseFloat(prev_balance.value)).toFixed(2) : (parseFloat(net_proceeds)).toFixed(2);

        // console.log("hello!");
        // if(typeof prev_balance != 'undefined'){
        //     balance.value = computation;
        // }
        if(payment != '' && payment != 0){
            balance.value = computation;
        }

    }
</script>
@endpush