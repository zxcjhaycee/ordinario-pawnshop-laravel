@php
 $routeName = substr(Route::currentRouteName(), strpos(Route::currentRouteName(), ".") + 1); // to identify if add or update
@endphp
@extends('layout')
@section('content')
<style>

    form .form-group select.form-control {
        position: static;
        top: -5px;
    }
    .alert .close{
        line-height : 1.5;
    }

</style>
<div class="content">
    <div class="col-xl-12">
              <div class="card">
                <div class="card-header card-header-icon">
                  <div class="card-icon" style="background: linear-gradient(60deg,#702230,#702230)">
                    <i class="material-icons">list</i>
                  </div>
                    <a href="{{ route('other_charges.index') }}" class="btn float-right btn-responsive">View All</a>
                    <h4 class="card-title"> {{ ucwords($routeName) }} Charges</h4>

                </div>
    
                 <div class="card-body">
                    @include('alert')
                 <form action="{{ isset($data->id) ? route('other_charges.update', $data->id) : route('other_charges.store') }}" method="POST">
                        @if(isset($data->id))
                            @method('PUT')
                        @endif
                        @csrf
                    <div class="col-xl-12 mt-5 d-flex justify-content-center">
                        <div class="col-xl-6">
                            <div class="row d-flex justify-content-center">
                                <label for="charge_type" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">Type: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('charge_type') has-error is-focused @enderror">
                                        <select class="form-control" name="charge_type" id="charge_type">
                                            <option></option>
                                            <option value="discount"
                                                @if((isset($data->charge_type) && $data->charge_type == 'discount'))
                                                    selected 
                                                @elseif(!isset($data->charge_type) && old('charge_type') == 'discount')
                                                    selected
                                                @endif
                                            >Discount</option>
                                            <option value="charges" 
                                            @if((isset($data->charge_type) && $data->charge_type == 'charges'))
                                                    selected 
                                                @elseif(!isset($data->charge_type) && old('charge_type') == 'charges')
                                                    selected
                                                @endif
                                            >Charges</option>
                                        </select>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('charge_type')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="charge_name" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">Name: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('charge_name') has-error is-focused @enderror">
		                                <input type="text" id="charge_name" name="charge_name" class="form-control" value="{{ isset($data->charge_name) && $errors->isEmpty() ? $data->charge_name : old('charge_name') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('charge_name')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="amount" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">Amount: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('amount') has-error is-focused @enderror">
		                                <input type="number" step="0.01" id="amount" name="amount" class="form-control" value="{{ isset($data->amount) && $errors->isEmpty() ? $data->amount : old('amount') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('amount')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                 <input type="text" id="user_auth_code" class="form-control" style="margin-top:16px;width:130px" name="user_auth_code"  placeholder="Auth Code"/>
                                <button type="submit" class="btn btn-success" style="height:100%">Submit</button>
                            </div>
                        </div>


                    </div>

                  </form>
                 

                <!-- end content-->
              </div>

              <!--  end card  -->
            </div>

</div>

@endsection