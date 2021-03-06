@php
 $routeName = substr(Route::currentRouteName(), strpos(Route::currentRouteName(), ".") + 1); // to identify if add or update
@endphp
@if(isset($data->inventory->inventory_number))
    @section('title', 'Update Ticket #: '. $data->ticket_number)
@else
    @section('title', 'Create Ticket')
@endif
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
                    <a href="{{ route('pawn.index') }}" class="btn float-right btn-responsive">View All</a>
                    <h4 class="card-title"> {{ ucwords($routeName) }} Pawn</h4>

                </div>
    
                 <div class="card-body">
                    @include('alert')
                    <div class="alert_message"></div>

                 <form enctype="multipart/form-data" id="inventoryForm" onSubmit="inventoryForm(event, this)" method="POST">
                        @if(isset($data->id))
                            @method('PUT')
                        @endif
                        @csrf
                    <div class="mt-5">
                    <!-- Inventory Number -->
                    <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                <h4 class="card-title">Inventory</h4>
                                </div>
                            </div>
                            <div class="card-body card-body-form">

                                <div class="row d-flex justify-content-center mt-3">
                                    <label for="inventory_number" class="col-xl-2 col-lg-2 col-md-2 col-sm-4 ">Number: </label>
                                    <div class="col-xl-5 col-lg-6 col-md-5 col-sm-7 inventory_number_error" style="top:-20px;">
                                        <div class="form-group input @error('inventory_number') has-error is-focused @enderror">
                                        <!-- <input type="number" readonly id="inventory_number" name="inventory_number" class="form-control" value="{{ isset($data->inventory_number) && $errors->isEmpty() ? $data->inventory_number : old('inventory_number') }}"/> -->
                                            <input type="text" id="inventory_number" name="inventory_number" class="form-control" value="{{ isset($data->inventory->inventory_number) && $errors->isEmpty() ? $data->inventory->inventory_number : '' }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('inventory_number')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                         </div>

                        <!-- Ticket Details -->
                         <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                <h4 class="card-title">Ticket Details</h4>
                                </div>
                            </div>
                            <div class="card-body card-body-form row">

                                <div class="col-xl-6">

                                    <div class="col-xl-12 row mt-3">
                                        <label for="ticket_number" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">PT #: </label>
                                        <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 ticket_number_error" style="top:-17px;">
                                            <div class="form-group input @error('ticket_number') has-error is-focused @enderror">
                                                <input type="number" id="ticket_number" name="ticket_number" class="form-control" value="{{ isset($data->ticket_number) && $errors->isEmpty() ? $data->ticket_number : $ticket_number }}"/>
                                                <span class="material-icons form-control-feedback">clear</span>
                                            </div>
                                            @error('ticket_number')
                                            <label class="text-danger">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-12 row mt-3">
                                        <label for="transaction_status" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Transaction Status: </label>
                                        <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 transaction_status_error" style="top:-17px;">
                                            <div class="form-group input @error('transaction_status') has-error is-focused @enderror">
                                            <select name="transaction_status" id="transaction_status" class="form-control">
                                                <option {{ isset($data) && $data->transaction_status == 'New' ? 'selected' : '' }}>New</option>
                                                <option {{ isset($data) && $data->transaction_status == 'Old' ? 'selected' : '' }}>Old</option>
                                            </select>                                                
                                           <span class="material-icons form-control-feedback">clear</span>
                                            </div>
                                            @error('transaction_status')
                                            <label class="text-danger">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-xl-12 row mt-3">
                                        <label for="processed_by" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Processed By: </label>
                                        <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 processed_by_error" style="top:-17px;">
                                            <div class="form-group input @error('processed_by') has-error is-focused @enderror">
                                                <input type="text" readonly  class="form-control" value="{{ Auth::user()->first_name. ' ' .Auth::user()->last_name }}"/>
                                                <span class="material-icons form-control-feedback">clear</span>
                                            </div>
                                            @error('processed_by')
                                            <label class="text-danger">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-12 row mt-3">
                                        <label for="branch_id" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Branch: </label>
                                        <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 branch_id_error" style="top:-17px;">
                                            <div class="form-group input @error('branch_id') has-error is-focused @enderror">
                                                <select class="form-control" id="branch_id" name="branch_id" {{ (!Auth::user()->isAdmin()) || isset($data) ? 'disabled' : '' }} onChange="setItemBranch();setItemType()">
                                                    <option></option>
                                                    @foreach($branch as $branches)
                                                        <option value="{{ $branches->id }}" {{ (Auth::user()->branch_id == $branches->id) || (isset($data) && $data->inventory->branch_id == $branches->id) ? 'selected' : '' }}>{{ $branches->branch }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="material-icons form-control-feedback">clear</span>
                                            </div>
                                            @error('branch_id')
                                            <label class="text-danger">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="col-xl-12 row mt-3">
                                            <label for="transaction_date" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Transaction Date: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 transaction_date_error" style="top:-17px;">
                                                <div class="form-group input @error('transaction_date') has-error is-focused @enderror">
                                                    <input type="text" name="transaction_date"  class="form-control transaction_picker" id="transaction_date" onblur="transaction_dates(this)" value="{{ isset($data->transaction_date) && $errors->isEmpty() ? date('m/d/Y', strtotime($data->transaction_date)) : date('m/d/Y') }}" autocomplete="off">
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('transaction_date')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                    </div>

                                    <div class="col-xl-12 row mt-3">
                                            <label for="maturity_date" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Maturity Date: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 maturity_date_error" style="top:-17px;">
                                                <div class="form-group input @error('maturity_date') has-error is-focused @enderror">
                                                    <input type="text" name="maturity_date" id="maturity_date" class="form-control" readonly value="{{ isset($data->maturity_date) && $errors->isEmpty() ? date('m/d/Y', strtotime($data->maturity_date)) : date('m/d/Y', strtotime('+1 month')) }}">
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('maturity_date')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                    </div>

                                    <div class="col-xl-12 row mt-3">
                                            <label for="expiration_date" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Expiration Date: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 expiration_date_error" style="top:-17px;">
                                                <div class="form-group input @error('expiration_date') has-error is-focused @enderror">
                                                <input type="text" name="expiration_date" id="expiration_date" class="form-control"  readonly value="{{ isset($data->expiration_date) && $errors->isEmpty() ? date('m/d/Y', strtotime($data->expiration_date)) : date('m/d/Y', strtotime('+4 month')) }}">
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('expiration_date')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                    </div>

                                    <div class="col-xl-12 row mt-3">
                                            <label for="auction_date" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Auction Date: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 auction_date_error" style="top:-17px;">
                                                <div class="form-group input @error('auction_date') has-error is-focused @enderror">
                                                <input type="text" name="auction_date" id="auction_date" class="form-control" readonly value="{{ isset($data->auction_date) && $errors->isEmpty() ? date('m/d/Y', strtotime($data->auction_date)) : date('m/d/Y', strtotime('+6 month')) }}">
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('auction_date')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                    </div>
                                </div>

                            </div>
                         </div>


                         <!-- Customer -->
                    <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                <h4 class="card-title">Customer</h4>
                                </div>
                            </div>
                            <div class="card-body card-body-form">
                                <div class="row d-flex justify-content-center">
                                    <label for="customer_id" class="col-xl-2 col-lg-2 col-md-2 col-sm-4 ">Customer: </label>
                                    <div class="col-xl-5 col-lg-6 col-md-5 col-sm-7 customer_id customer_id_error" style="top:-10px;">
                                        <div class="form-group input @error('customer_id') has-error is-focused @enderror">
                                            <select name="customer_id" id="customer_id" class="form-control customer_id select2">
                                                <!-- <option></option> -->
                                                <option value="{{ isset($data) ? $data->inventory->customer_id : '' }}" selected="selected">{{ isset($data) ? $data->inventory->customer->first_name." ".$data->inventory->customer->last_name." ".$data->inventory->customer->suffix : '' }}</option>

                                            </select>     
                                        </div>
                                        @error('customer_id')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center mt-3">
                                    <label for="attachment_id" class="col-xl-2 col-lg-2 col-md-2 col-sm-4 ">Attachment: </label>
                                    <div class="col-xl-5 col-lg-6 col-md-5 col-sm-7 attachment_id_error" style="top:-10px;">
                                        <div class="form-group input @error('attachment_id') has-error is-focused @enderror">
                                            <select name="attachment_id" id="attachment_id" class="form-control attachment_id" readonly onChange="getAttachmentNumber(this.options[this.selectedIndex])">
                                                @isset($data->inventory->customer->attachments)
                                                    @foreach($data->inventory->customer->attachments as $key => $value)
                                                            <option value="{{ $value->id }}" data-number="{{ $value->pivot->number }}" 
                                                            {{ $data->attachment_id == $value->id ? 'selected' : '' }}>
                                                             {{ $value->type }}
                                                            </option>
                                                    @endforeach
                                                @endisset
                                                <!-- <option value = {{ isset($data->pawnTickets->attachment_id) ? $data->pawnTickets->attachment_id : '' }}>{{ isset($data->pawnTickets->attachment->type) ?  $data->pawnTickets->attachment->type : '' }}</option> -->
                                            </select>                                     
                                        </div>
                                        @error('attachment_id')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center mt-3">
                                    <label for="attachment_number" class="col-xl-2 col-lg-2 col-md-2 col-sm-4 ">Attachment Number: </label>
                                    <div class="col-xl-5 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-20px;">
                                        <div class="form-group input @error('attachment_number') has-error is-focused @enderror">
                                            <input type="number" readonly id="attachment_number" name="attachment_number" class="form-control" value="{{ isset($data->attachment_number) && $errors->isEmpty() ? $data->attachment_number : old('attachment_number') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('attachment_number')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                         </div>
                        <!-- Items -->
                         <div class="card" id="items">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                <h4 class="card-title">Items</h4>
                                </div>
                            </div>
                            <div class="card-body card-body-form">
                                <div class="col-xl-12 row">
                                    <div class="col-xl-3 item_category_id_error" id="item_category" {{-- (!Auth::user()->isAdmin()) || isset($data) ? "" :  "style=display:none" --}}>
                                        <div class="form-group input @error('item_category_id') has-error is-focused @enderror">
                                            <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" id="category_jewelry" name="item_category_id" value="1" onclick="getSuki(this);addItem('checkbox')" {{ isset($data->inventory->item_category_id) && $data->inventory->item_category_id == 1 ? 'checked' : ''  }}> Jewelry
                                                <span class="circle">
                                                <span class="check"></span>
                                                </span>
                                            </label>
                                            </div>
                                            <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" id="category_non-jewelry" name="item_category_id" value="2" onclick="getSuki(this);addItem('checkbox')" {{ isset($data->inventory->item_category_id) && $data->inventory->item_category_id == 2 ? 'checked' : ''  }}> Non-Jewelry
                                                <span class="circle">
                                                <span class="check"></span>
                                                </span>
                                            </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div id="suki" class="form-check"  {{ isset($data) && $data->inventory->item_category_id == 1 ? '' : 'style=display:none'  }}>
                                        <label class="form-check-label">
                                            <input id="suki_check" class="form-check-input" type="checkbox" value="1" name="is_special_rate" onClick="checkRate(this)" {{ isset($data->is_special_rate) && $data->is_special_rate == 1 ? 'checked' : ''  }}> Suki Rate? (For Jewelry Items)
                                            <span class="form-check-sign">
                                            <span class="check"></span>
                                            </span>
                                        </label>
                                        </div>
                                    </div>
                                    </div>
                                    <!-- <div class="col-xl-12">
                                        <div class="alert alert-danger" role="alert">
                                            <ul style="list-style-type: none;" class="text-center">
                                                <li>Material field is required!</li>
                                                <li>Item type field is required!</li>
                                                <li>Karat field is required!</li>
                                                <li>Description field is required!</li>
                                                <li>Image field is required!</li>
                                            </ul>
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 mx-auto">
                                        <div class="alert alert-danger alert-dismissible fade show text-center" style="font-size:15px" role="alert">
                                            <strong>Error!</strong> The material field is required!
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                        </div>
                                    </div>                                    -->
                                    <div class="table-responsive" id="itemTable">
                            @isset($data->item_tickets)
                                @foreach($data->item_tickets as $key => $value)
                                    @if($data->inventory->item_category_id == 1)
                                    <table class="table table-bordered mt-3 jewelry_table table_{{ $key }}_error" width="100%">
                                        <thead>
                                            <tr>
                                                <td style="width:10%">Material <br> Item Type <br> Karat</td>
                                                <td>Weight (g)</td>
                                                <td>Rate Appraised Value</td>
                                                <td>Description</td>
                                                <td>Image</td>
                                                <td></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-group input item_type_id_{{ $key }}_error">
                                                        <select name="item_type_id[{{ $key }}]" class="form-control item_type" onChange="setAppraisedValue(this);getKarat(this.value, this)">
                                                            <option selected disabled></option>
                                                            @foreach($item_type_data as $item_type)
                                                                <option value="{{ $item_type->id }}" {{ $item_type->id == $value->inventory_items->item_type_id ? 'selected' : '' }}>{{ $item_type->item_type}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group input item_type_weight_{{ $key }}_error ">
                                                        <input class="form-control item_type_weight" type="number" name="item_type_weight[{{ $key }}]" value="{{ $value->inventory_items->item_type_weight }}" step=".01" onKeyup="setKaratWeight(this);setAppraisedValue(this)">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group input item_type_appraised_value_{{ $key }}_error">
                                                        <input class="form-control item_type_appraised_value" type="number" name="item_type_appraised_value[{{ $key }}]" value="{{ $value->item_type_appraised_value }}" readonly >
                                                    </div>
                                                </td>
                                                <td rowspan=3 class="text-center">
                                                    <div class="form-group input description_{{ $key }}_error">
                                                     <textarea class="form-control" rows="5" name="description[{{ $key }}]" id="description">{{ $value->inventory_items->description }}</textarea> <br>
                                                    </div>
                                                    <button id="addDiamond" class="btn btn-warning btn-sm" type="button">ADD DIAMOND</button>
                                                </td>
                                                <td rowspan=3 class="text-center">
                                                    <div class="form-group input image_{{ $key }}_error">
                                                        <input type="file" name="image[{{ $key }}]" class="image" onChange="checkUpload(this)">
                                                        <button type="button" class="btn btn-success btn-sm uploadButton">Change Image</button>
                                                        <div class="input-group">
                                                            <input type="text" name="image[{{ $key }}]" readonly="" class="form-control imageText" value="{{ $value->inventory_items->image }}">
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm removeImage" style="display:none" onClick="removeImage(this)">Remove Image</button>
                                                </td>
                                                <td rowspan=3>
                                                    <!-- <i class="material-icons remove" style="cursor: pointer;" data-id="{{ $value->id }}" id="{{ $value->id }}" data-name="/pawn_auction/inventory_item">cancel</i> -->
                                                    <i class="material-icons remove_table" style="cursor: pointer;">cancel</i>

                                                </td>
                                             </tr>
                                        
                                                <tr>
                                                    <td>
                                                        <div class="form-group input item_name_{{ $key }}_error">
                                                            <input type="text" name="item_name[{{ $key }}]" class="form-control" value="{{ $value->inventory_items->item_name }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input class="form-control item_name_weight" type="number" name="item_name_weight[{{ $key }}]" value="{{ $value->inventory_items->item_name_weight }}" onKeyup="setKaratWeight(this);setAppraisedValue(this)" step=".01">
                                                    </td>
                                                    <td>
                                                        <input class="form-control item_name_appraised_value" type="number" name="item_name_appraised_value[{{ $key }}]" value="{{ $value->item_name_appraised_value }}"  readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="form-group input item_karat_{{ $key }}_error">
                                                            <select name="item_karat[{{ $key }}]" class="form-control item_karat" onChange="setAppraisedValue(this)">
                                                                <option></option>
                                                                @foreach($rate_data[$key] as $rate)
                                                                    <option value="{{ $rate->karat }}" data-gram="{{ $rate->gram }}" data-regular_rate="{{ $rate->regular_rate }}" data-special_rate="{{ $rate->special_rate }}" {{ $rate->karat == $value->inventory_items->item_karat ? 'selected' : '' }} >
                                                                        {{ $rate->karat }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input class="form-control karat_weight" type="number" name="item_karat_weight[{{ $key }}]" value="{{ $value->inventory_items->item_karat_weight }}" step=".01" readonly>
                                                          <input type="hidden" class="count" value="{{ $key }}">
                                                          <input type="hidden" name="inventory_item_id[{{ $key }}]" id="inventory_item_id" value="{{ $value->inventory_item_id }}"  class="inventory_item_id">
                                                          <input type="hidden" name="ticket_item_id[{{ $key }}]" id="ticket_item_id" value="{{ $value->id }}"  class="ticket_item_id">

                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @else
                                        <table class="table table-bordered mt-3 non_jewelry_table table_{{ $key }}_error" width="100%">
                                            <thead>
                                                <tr>
                                                <td style="width:20%">Item Type <br/> Item Name</td>
                                                <td>Rate Appraised Value</td>
                                                <td>Description</td>
                                                <td>Image</td>
                                                <td></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                            <td>
                                                <div class="form-group input item_type_id_{{ $key }}_error">
                                                <select name="item_type_id[{{ $key }}]" class="form-control item_type" onChange="setNonJewelryAppraisedValue(this);getItemName(this.value, this)">
                                                    @foreach($item_type_data as $item_type)
                                                                <option value="{{ $item_type->id }}" {{ $item_type->id == $value->inventory_items->item_type_id ? 'selected' : '' }}>{{ $item_type->item_type}}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                            </td>
                                            <td rowspan="2">
                                                <div class="form-group input item_name_appraised_value{{ $key }}_error">
                                                <input class="form-control item_name_appraised_value" type="number" name="item_name_appraised_value[{{ $key }}]" value="{{ $value->item_name_appraised_value }}" readonly >
                                                </div>
                                            </td>
                                            <td rowspan=2 class="text-center">
                                                <div class="form-group input description_{{ $key }}_error">
                                                <textarea class="form-control" rows="5" name="description[{{ $key }}]" id="description">{{ $value->inventory_items->description }}</textarea> <br>
                                                </div>
                                            </td>
                                            <td rowspan=2 class="text-center">
                                                <div class="form-group input image_{{ $key }}_error">
                                                <input type="file" name="image[{{ $key }}]" class="image" onChange="checkUpload(this)">
                                                <button type="button" class="btn btn-success btn-sm uploadButton">Upload Image</button>
                                                <div class="input-group">
                                                    <input type="text" name="image[{{ $key }}]" readonly="" class="form-control imageText" value="{{ $value->inventory_items->image }}">
                                                </div>
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm removeImage" style="display:none" onClick="removeImage(this)">Remove Image</button>

                                            </td>
                                            <td rowspan=2>
                                                <i class="material-icons remove_table" style="cursor: pointer;">cancel</i>
                                            </td>
                                            </tr>
                                                <tr>
                                                    <td>
                                                    <div class="form-group input item_name_{{ $key }}_error">
                                                        <select name="item_name[{{ $key }}]" class="form-control item_name" onChange="setNonJewelryAppraisedValue(this)">
                                                            @foreach($rate_data[$key] as $rate)
                                                                <option value="{{ $rate->description }}"  data-regular_rate="{{ $rate->regular_rate }}"  {{ $rate->description == $value->inventory_items->item_name ? 'selected' : '' }} >
                                                                    {{ $rate->description }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" value="{{ $key }}" class="count">
                                                          <input type="hidden" name="inventory_item_id[{{ $key }}]" id="inventory_item_id" value="{{ $value->inventory_item_id }}"  class="inventory_item_id">
                                                          <input type="hidden" name="ticket_item_id[{{ $key }}]" id="ticket_item_id" value="{{ $value->id }}"  class="ticket_item_id">

                                                    </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            </table>
                                        @endif
                              @endforeach
                            @endisset
                                    </div>
                                      <button id="item_button" class="btn btn-warning btn-sm" type="button" onClick="addItem();"  {{ isset($data->item_tickets) ? '' :  'style=display:none' }}>ADD</button>
                            
                            </div>
                         </div>
                        <!-- Computation -->
                         <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                <h4 class="card-title">Computation</h4>
                                </div>
                            </div>
                            <div class="card-body card-body-form row">
                                <div class="col-xl-6">
                                        <div class="col-xl-12 row mt-3">
                                            <label for="appraised_value" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Appraised Value </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 appraised_value_error" style="top:-17px;">
                                                <div class="form-group input @error('appraised_value') has-error is-focused @enderror">
                                                    <input type="number" id="appraised_value" name="appraised_value" class="form-control" value="{{ isset($data->appraised_value) && $errors->isEmpty() ? $data->appraised_value : 0 }}" readonly/>
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('appraised_value')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-12 row mt-3">
                                            <label for="principal" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Principal: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 principal_error" style="top:-17px;">
                                                <div class="form-group input @error('principal') has-error is-focused @enderror">
                                                    <input type="number"  id="principal" name="principal" class="form-control" onChange="setAppraisedByPrincipal(this.value)" value="{{ isset($data->principal) && $errors->isEmpty() ? $data->principal : 0 }}"/>
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('principal')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-12 row mt-3">
                                            <label for="other_charges" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Other Charges: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 other_charges_error" style="top:-17px;">
                                                <div class="form-group input @error('other_charges') has-error is-focused @enderror">
                                                    <input type="number"  id="other_charges" name="other_charges" class="form-control" value="{{ isset($other_charges['charges']) && $errors->isEmpty() ? $other_charges['charges'] : 0 }}" readonly/>
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('other_charges')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-12 row mt-3">
                                            <label for="discount" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Discount: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 discount_error" style="top:-17px;">
                                                <div class="form-group input @error('discount') has-error is-focused @enderror">
                                                    <input type="number"  id="discount" name="discount" class="form-control" value="{{ isset($other_charges['discount']) && $errors->isEmpty() ? $other_charges['discount'] : 0 }}" readonly/>
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('discount')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-12 row mt-3">
                                            <label for="net_proceeds" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Net Proceeds: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 net_proceeds_error" style="top:-17px;">
                                                <div class="form-group input @error('net_proceeds') has-error is-focused @enderror">
                                                    <input type="number"  id="net_proceeds" name="net_proceeds" class="form-control" value="{{ isset($data->net) && $errors->isEmpty() ? $data->net : 0 }}" readonly/>
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('net_proceeds')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                </div>
                            <div class="col-xl-6">
                            <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:50%">Other Charges</th>
                                            <th style="width:30%">Amount</th>
                                            <th style="width:10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="other_charges_body">
                                    @if(isset($data->other_charges))
                                        @foreach($data->other_charges as $key => $value)
                                            @if($value->inventory_other_charges->charge_type == 'charges')
                                                <tr>
                                                    <td>
                                                        <select name="other_charges_id[]" class="form-control select2 other_charges_select">
                                                            <!-- <option></option> -->
                                                            <option value="{{ $value->other_charges_id }}">{{ $value->inventory_other_charges->charge_name }}</option>

                                                        </select>
                                                    </td>
                                                    <td>
                                                    <input type="text" name="other_charges_amount[]" id="other_charges_amount" class="form-control other_charges_amount" value="{{ $value->amount }}">
                                                    <input type="hidden" name="inventory_other_charges_id[]" id="inventory_other_charges_id" class="form-control inventory_other_charges_id" value="{{ $value->id }}">
                                                    </td>
                                                    <td>
                                                        <!-- <button type="button" class="btn btn-danger btn-sm remove" id="{{ $value->id }}" data-name="/pawn_auction/inventory_other_charges"><i class="material-icons">close</i></button> -->
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
                                </div>
                                <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:50%">Discount</th>
                                            <th style="width:30%">Amount</th>
                                            <th style="width:10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="discount_body">
                                    @if(isset($data->other_charges))
                                        @foreach($data->other_charges as $key => $value)
                                            @if($value->inventory_other_charges->charge_type == 'discount')
                                                <tr>
                                                    <td>
                                                        <select name="other_charges_id[]" class="form-control select2 discount_select">
                                                            <!-- <option></option> -->
                                                            <option value="{{ $value->other_charges_id }}">{{ $value->inventory_other_charges->charge_name }}</option>

                                                        </select>
                                                    </td>
                                                    <td>
                                                    <input type="text" name="other_charges_amount[]" id="discount_amount" class="form-control discount_amount" value="{{ $value->amount }}">
                                                    <input type="hidden" name="inventory_other_charges_id[]" id="discount_id" class="form-control discount_id" value="{{ $value->id }}">
                                                    </td>
                                                    <td>
                                                        <!-- <button type="button" class="btn btn-danger btn-sm remove" id="{{ $value->id }}" data-name="/pawn_auction/inventory_other_charges"><i class="material-icons">close</i></button> -->
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
                                </div>

                            </div>



                            </div>
                         </div>
                         @if(!(Auth::user()->isAdmin()))
                         <input type="hidden" name="branch_id" id="branch_id" class="form-control" value="{{ Auth::user()->branch_id }}">
                         @endif
                         <input type="hidden" name="processed_by" id="processed_by" class="form-control" value="{{ Auth::user()->id }}">
                         <input type="hidden" name="transaction_type" id="transaction_type" class="form-control" value="pawn">
                        @isset($data->id)
                            <input type="hidden" name="inventory_id" id="inventory_id" class="form-control" value="{{ $data->inventory->id }}">
                            <input type="hidden" name="id" id="id" class="form-control" value="{{ $data->id }}">
                        @endisset
                        <div class="d-flex justify-content-center">
                                 <input type="text" name="user_auth_code" id="user_auth_code" class="form-control" style="margin-top:16px;width:130px"   placeholder="Auth Code"/>
                                <button type="submit" class="btn btn-success" style="height:100%">Submit</button>
                        </div>

                    </div>

                  </form>
                 

                <!-- end content-->
              </div>

              <!--  end card  -->
            </div>

</div>

@endsection
@push('scripts')

<script>
    // nonJewelryTable(0);
    // jewelryTable(0);
    // $('.item_type').tooltip();
    // $('.item_name').tooltip();
    // $('.item_karat').tooltip();
    /*
    window.onbeforeunload = function(){  // back to top when the page reloads	
        window.scrollTo(0,0); 
    }
    */


    
    const customer = document.querySelector('#customer_id');
    const route_customer = '/settings/customer/search';
    const table_customer = 'customer';
    const customer_placeholder = 'Select Customer...';
    select2Initialized(customer,route_customer, table_customer, customer_placeholder);

    const other_charges = '.other_charges_select';
    const other_charges_route =  '{{ route("other_charges.search") }}' 
    const table_other_charges = 'charges';
    const other_charges_placeholder = 'Select Charges...';
    // console.log(table_other_charges);
    select2Initialized(other_charges,other_charges_route, table_other_charges, other_charges_placeholder);

    const discount = '.discount_select';
    const discount_route =  '{{ route("other_charges.search") }}' 
    const table_discount = 'discount';
    const discount_placeholder = 'Select Discount...';
    // console.log(table_other_charges);
    select2Initialized(discount,discount_route,table_discount, discount_placeholder);
    $(document).on('select2:select', '#customer_id, .other_charges_select, .discount_select', function (e) {
        const data = e.params.data;
        $('.attachment_id').attr('readonly', true);
        switch(data.action){
            case 'attachment':
                customer_attachment(data.id);
            break;
            case 'amount':
                $(this).closest('tr').find('.other_charges_amount').val(data['data-amount']);
               setOtherCharges();
            break;
            case 'discount':
                $(this).closest('tr').find('.discount_amount').val(data['data-amount']);
                setDiscount();
            break;
            default:
                window.open(data.link);
                $(this).val(null).trigger("change");       


        }
    });

    const transaction_picker = $('.transaction_picker').datepicker({
    language: 'en',
    todayButton: new Date(),
    autoClose : true,
    position: "bottom center"
    });
    transaction_picker.data('datepicker').selectDate(new Date($('.transaction_picker').val()));

    $(document).on('change', '.transaction_picker', function(){
        const transaction_date = new Date($(this).val());
        if(transaction_date != 'Invalid Date'){
            transaction_picker.data('datepicker').selectDate(transaction_date);
            return;
        }
        $(this).val('');
    });
    function customer_attachment(customer_id){
        $.ajax({
            type : 'GET',
            url : '{{ route('customer.search_attachment') }}',
            data : {
            customer_id : customer_id
            },
        success:(data) => {
            $('.attachment_id').removeAttr('readonly');
            $('#attachment_number').val('');
            let option = '<option></optionn>';
            if(typeof data.type !== 'undefined'){
                data.type.forEach((element, key) => {
                option += '<option value="'+data.id[key]+'" data-number="'+data['attachment_number'][key]+'">'+element+'</option>';
               })
            }
            $('.attachment_id').html(option);
        }
        });
    }


    function setAppraisedComputation(){
        const item_type_appraised_value = document.querySelectorAll('.item_type_appraised_value');
        const item_name_appraised_value = document.querySelectorAll('.item_name_appraised_value');
        const compute_appraised_value = document.getElementById('appraised_value');
        const principal = document.getElementById('principal');
        let total = 0;
        for(let i = 0; i < item_name_appraised_value.length; i++){

            total += parseFloat(item_name_appraised_value[i].value);

        }
        compute_appraised_value.value = total.toFixed(2);
        principal.value = total.toFixed(2);
        setNetProceeds();
    }


    function setNetProceeds(){
        const principal = document.getElementById('principal').value;
        const other_charges = document.getElementById('other_charges').value;
        const discount = document.getElementById('discount').value;
        const net_proceeds = document.getElementById('net_proceeds');

        net_proceeds.value = ( (parseFloat(principal) + parseFloat(other_charges)) - parseFloat(discount)).toFixed(2);

    }

    function addItem(type = null){
    // console.log("Hello!");
    const category_jewelry = document.getElementById('category_jewelry');
    const add_item_button = document.getElementById('item_button');
    const jewelry_table  = document.querySelector('.jewelry_table');
    const non_jewelry_table  = document.querySelector('.non_jewelry_table');
    if(category_jewelry.checked){
      const count = document.querySelectorAll('.count');
      const length = count.length;
      // console.log(length);
      
      add_item_button.removeAttribute('style');
        // console.log(non_jewelry_table);
        non_jewelry_table != null ? $('.non_jewelry_table').remove() : '';
        jewelry_table == null ? $('.alert_error').remove() : '';
        if((type == 'checkbox' && jewelry_table == null) || type == null){
            // $('.alert_error').remove();
            let jewelry_counter = length == 0 ? 0 : parseInt(count[length - 1].value) + 1;
            jewelryTable(jewelry_counter);
        }
    }else{
         const count = document.querySelectorAll('.count');
         const length = count.length;
        //  jewelry_table.innerHTML = '';
        jewelry_table != null ? $('.jewelry_table').remove()  : '';
        non_jewelry_table == null ? $('.alert_error').remove() : '';

         add_item_button.removeAttribute('style');
            // console.log(jewelry_table);
         if((type == 'checkbox' && non_jewelry_table == null) || type == null){
            // $('.alert_error').remove();
            let non_jewelry_counter = length == 0 ? 0 : parseInt(count[length - 1].value) + 1;
            nonJewelryTable(non_jewelry_counter);
         }
    }
  }

function setAppraisedByPrincipal(amount){
    const net_proceeds = document.getElementById('net_proceeds');

    net_proceeds.value = amount;
}


</script>
@endpush