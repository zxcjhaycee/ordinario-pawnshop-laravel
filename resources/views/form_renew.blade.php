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

                    <h4 class="card-title">Renew Pawn</h4>

                </div>
    
                 <div class="card-body">
                    @include('alert')
                    <form enctype="multipart/form-data" id="renewForm" onSubmit="renewForm(event, this)" method="POST">
                    @csrf
                    <div class="card">
                                <div class="card-header card-header-text card-header-primary">
                                
                                    <div class="card-text">
                                    <h4 class="card-title">Pawn Ticket : {{ $inventory->pawnTickets->ticket_number }}</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                <div class="row">
                                
                                  <div class="col-xl-6">
                                      <!-- <h3 class="display-4">Details</h3> -->
                                      <div class="col-xl-12 mt-4">
                                        <ul style="list-style-type:none;padding-left: 5px;">
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Inventory #</label> : <span class="font-weight-normal col-xl-5">{{ $inventory->inventory_number }}</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">PT #</label> : <span class="font-weight-normal col-xl-5">{{ $ticket_number }}</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">OR #</label> : <span class="font-weight-normal col-xl-5"></span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Transaction Type</label> : <span class="font-weight-normal col-xl-5">Renew</span></li>
                                            <li class="form-inline" style="height:40px"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Transaction Date </label> : <input type="text" class="form-control transaction_picker" style="margin-left:20px" onblur="transaction_dates(this)" name="transaction_date" autocomplete="off"></li>
                                            <li class="form-inline" style="height:40px"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Maturity Date </label> : <input type="text" class="form-control" style="margin-left:20px" id="maturity_date" readonly name="maturity_date"></li>
                                            <li class="form-inline" style="height:40px"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Expiration Date </label> : <input type="text" class="form-control" style="margin-left:20px" id="expiration_date" readonly name="expiration_date"></li>
                                            <li class="form-inline"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Auction Date </label> : <input type="text" class="form-control" style="margin-left:20px" id="auction_date" readonly name="auction_date"></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Interbranch Transaction </label> : <input type="checkbox" style="width:50px;box-shadow:none" value="1" name="interbranch"></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Interbranch Renewal </label> : <input type="checkbox" style="width:50px;box-shadow:none" name="interbranch_renewal" value="1"></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Branch </label> : <span class="font-weight-normal col-xl-5">{{ $inventory->branch->branch }}</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Processed By</label> : <span class="font-weight-normal col-xl-5">{{ $inventory->pawnTickets->encoder->first_name." ".$inventory->pawnTickets->encoder->last_name }}</span></li>
                                        </ul>
                                        <!-- <div class="form-inline">

                                        </div> -->
                                      </div>

                                  </div>
                                    <div class="col-xl-6">
                                      <!-- <h3 class="display-4">Customer</h3> -->
                                            <ul style="list-style-type:none;padding-left: 5px;">
                                                <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Original PT #</label> : <span class="font-weight-normal col-xl-5">{{ $inventory->pawnTickets->ticket_number }}</span></li>
                                                <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Transaction Date</label> : <span class="font-weight-normal col-xl-5">{{ date('M d, Y', strtotime($inventory->pawnTickets->transaction_date)) }}</span></li>
                                                <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Maturity Date</label> : <span class="font-weight-normal col-xl-5">{{ date('M d, Y', strtotime($inventory->pawnTickets->maturity_date)) }}</span></li>
                                                <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Expiration Date</label> : <span class="font-weight-normal col-xl-5">{{ date('M d, Y', strtotime($inventory->pawnTickets->expiration_date)) }}</span></li>
                                                <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Auction Date</label> : <span class="font-weight-normal col-xl-5">{{ date('M d, Y', strtotime($inventory->pawnTickets->auction_date)) }}</span></li>
                                            </ul>
                                      

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
                                
                                  <div class="col-xl-6">
                                      <h3 class="display-4">Customer</h3>
                                      <div class="col-xl-12 mt-4">
                                        <ul style="list-style-type:none;padding-left: 5px;">
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Name </label> : <span class="font-weight-normal col-xl-5">{{ $inventory->customer->first_name." ".$inventory->customer->last_name." ".$inventory->customer->suffix }}</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Customer Image</label> : <img src="http://getdrawings.com/free-icon-bw/facebook-avatar-icon-25.png" class="rounded" alt="..." style="width:150px"></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Authorized Representative</label> : <input type="checkbox" style="width:50px;box-shadow:none" name="authorized_representative" value="1"></li>
                                            <li class="form-inline"  style="height:40px"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">ID Presented</label> : 
                                            <select name="attachment_id" id="attachment_id" class="form-control attachment_select" style="width:200px;margin-left:20px" onChange="getAttachmentNumber(this.options[this.selectedIndex])" name="attachment_id">
                                                <!-- <option>Hello!</option> -->
                                                <option disabled selected>Please select attachment...</option>
                                                @foreach($inventory->customer->attachments as $key => $value)
                                                    <option value="{{ $value->id }}" data-number="{{ $value->pivot->number }}">{{ $value->type }}</option>
                                                @endforeach
                                            </select>
                                            </li>
                                            <li class="form-inline"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">ID Number</label> : <input type="text" class="form-control" style="margin-left:20px;width:200px" id="attachment_number" readonly name="attachment_number"></li>
                                            <!-- <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Image</label> : <span class="font-weight-normal col-xl-5">Renew</span></li> -->
                                        </ul>
                                        <!-- <div class="form-inline">

                                        </div> -->
                                      </div>

                                  </div>
                                    <div class="col-xl-6">
                                      <h3 class="display-4">Item Details</h3>
                                      <div class="table-responsive material-datatables" style="overflow-y: hidden;">

                                      <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                  <th style="width:30%">{{ $inventory->item_category->item_category }}</th>
                                                  <th>Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($inventory->item as $key => $value)
                                                    <tr>
                                                    <td><img src="https://i.pinimg.com/originals/81/b8/ec/81b8ec9c3450c00ef1d9dbfc1d6de9d6.png" alt="" class="rounded" style="width:130px"></td>
                                                    <td>{{ $value->item_type->item_type." (".$value->item_karat."K P ".number_format($value->item_type_appraised_value, 2).")" }}<br/>
                                                    {{ number_format($value->item_type_weight,2)."g / ".number_format($value->item_name_weight,2)."g / ". number_format($value->item_karat_weight,2)."g" }}
                                                    <br/>
                                                    {{ 'P '. number_format($value->item_type_appraised_value * $value->item_type_weight,2) }} 
                                                    <br/>
                                                    {{ $value->description }}
                                                    <!-- babaguhin pa -->
                                                    </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                       </table>

                                  </div>
                                      

                                    </div>
                                    <div class="col-xl-6">
                                    <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                  <th>Start Date</th>
                                                  <th>Date</th>
                                                  <th>Deadline</th>
                                                  <th>Interest</th>
                                                  <th>Penalty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tickets as $key => $value)
                                                <tr>
                                                    <td>{{ date('M d, Y', strtotime($value->transaction_date))  }}</td>
                                                    <td>{{ date('M d, Y', strtotime($value->maturity_date))  }}</td>
                                                    <td>{{ date('M d, Y', strtotime($value->maturity_date .'+ 1 day'))  }}</td>
                                                    <td>{{ number_format($inventory->principal * 0.03, 2)  }}</td>
                                                    <td>{{ number_format($value->penalty, 2)  }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                       </table>

                                       <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                <th style="width:50%">Other Charges</th>
                                                <th style="width:30%">Amount</th>
                                                <th style="width:10%"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="other_charges_body">
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
                                            </tbody>
                                            <tfoot>
                                        <tr>
                                            <td colspan="3">
                                                <button type="button" class="btn btn-info btn-sm float-right" onClick="add_other_charges(this)" style="margin:1px">Add</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                       </table>

                                       <ul style="list-style-type:none;padding-left: 5px;">
                                            <li class="form-inline" style="height:40px;">
                                                <label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Discounts <i>(if any)</i> </label> : 
                                                <input type="text" name="remarks" class="form-control" style="margin-left:20px;width:180px" id="remarks" placeholder="Remarks" onChange="enableDiscount(this)">
                                                <input type="number" name="discount" class="form-control" style="margin-left:20px;width:100px" id="discount" value="0" readonly step=".01" onChange="setNetProceeds()">
                                            </li>
                                            <li class="form-inline" style="height:40px;">
                                                <label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Interest</label> : 
                                                <input type="number" name="interest_text" class="form-control" style="margin-left:20px;width:100px" id="interest_text" value="0" step=".01" onChange="setNetProceeds()">
                                            </li>
                                            <li class="form-inline" style="height:40px;">
                                                <label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Penalty</label> : 
                                                <input type="number" name="penalty_text" class="form-control" style="margin-left:20px;width:100px" id="penalty_text" value="0" step=".01" onChange="setNetProceeds()">
                                            </li>
                                        </ul>

                                        
                                    </div>
                                    <div class="col-xl-6">
                                    <h3 class="display-4">Computation</h3>

                                    <ul style="list-style-type:none;padding-left: 5px;">
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Principal </label> : <span class="font-weight-normal col-xl-5">{{ number_format($inventory->principal,2) }}</span></li>
                                            <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Interest</label> : <input type="text" name="interest" class="form-control" style="margin-left:20px;width:200px" id="interest" value="{{ round(($inventory->principal * 0.03) * $tickets->count(),2) }}" readonly></li>
                                            <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Advance Interest</label> : <input type="text" name="advance_interest" class="form-control" style="margin-left:20px;width:200px" id="advance_interest" value="{{ isset($inventory->advance_interest) ? $inventory->advance_interest : '0'}}" readonly></li>
                                            <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Penalty</label> : <input type="text" class="form-control" name="penalty" style="margin-left:20px;width:200px" id="penalty" value="{{ isset($inventory->penalty) ? $inventory->penalty : '0'}}" readonly ></li>
                                            <li class="form-inline" style="height:40px;"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Other Charges</label> : <input type="text" class="form-control" name="other_charges" style="margin-left:20px;width:200px" id="other_charges" value="0" readonly></li>
                                            <li class="form-inline"><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Net Proceeds</label> : <input type="text" name="net" class="form-control" style="margin-left:20px;width:200px" id="net_proceeds" readonly></li>
                                        </ul>
                                    <div class="jumbotron" style="padding:0">
                                    <ul style="list-style-type:none;">
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Adv Int. Months </label> : <span class="font-weight-normal">0 month/s</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Interest Rate </label> : <span class="font-weight-normal">{{ $inventory->interest_percentage. "%" }}</span></li>
                                            <li><label class="font-weight-bold" style="display:inline-block;width:120px;text-align:right">Penalty Rate </label> : <span class="font-weight-normal">{{ $inventory->penalty_percentage."%" }}</span></li>
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
                        <input type="hidden" name="ticket_number" value="{{ $ticket_number }}">
                        <input type="hidden" name="processed_by"  value="{{ Auth::user()->id }}">
                        <input type="hidden" name="transaction_type"  value="renew">
                        <input type="submit" value="Submit" class="btn btn-success">

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
    const table_other_charges = 'other_charges';
    select2Initialized(other_charges,other_charges_route, table_other_charges);

    $(document).on('select2:select', '.other_charges_select', function (e) {
        const data = e.params.data;
        $(this).closest('tr').find('.other_charges_amount').val(data['data-amount']);
        setOtherCharges();
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
        // const principal = document.getElementById('principal').value;
        const other_charges = document.getElementById('other_charges').value;
        const penalty = document.getElementById('penalty').value;
        const interest = document.getElementById('interest').value;
        const penalty_text = document.getElementById('penalty_text').value;
        const interest_text = document.getElementById('interest_text').value;
        const advance_interest = document.getElementById('advance_interest').value;
        const discount = document.getElementById('discount').value;

        const net_proceeds = document.getElementById('net_proceeds');
        net_proceeds.value = ((parseFloat(other_charges) + parseFloat(penalty) + parseFloat(interest) + parseFloat(advance_interest) + parseFloat(interest_text) + parseFloat(penalty_text)) - parseFloat(discount)).toFixed(2);

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

</script>


@endpush