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
                    <a href="{{ route('branch.index') }}" class="btn float-right btn-responsive">View All</a>
                    <h4 class="card-title"> {{ ucwords($routeName) }} Branch</h4>

                </div>
    
                 <div class="card-body">
                    @include('alert')
                 <form action="{{ isset($data->id) ? route('branch.update', $data->id) : route('branch.store') }}" method="POST">
                        @if(isset($data->id))
                            @method('PUT')
                        @endif
                        @csrf
                    <div class="col-xl-12 mt-5 d-flex justify-content-center">
                        <div class="col-xl-6">
                            <div class="row d-flex justify-content-center">
                                <label for="branch" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">Branch: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('branch') has-error is-focused @enderror">
		                                <input type="text" id="branch" name="branch" class="form-control" value="{{ isset($data->branch) && $errors->isEmpty() ? $data->branch : old('branch') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('branch')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="address" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">Address: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('address') has-error is-focused @enderror">
		                                <input type="text" id="address" name="address" class="form-control" value="{{ isset($data->address) && $errors->isEmpty() ? $data->address : old('address') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('address')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="contact_number" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">Contact Number: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('contact_number') has-error is-focused @enderror">
		                                <input type="text" id="contact_number" name="contact_number" class="form-control" value="{{ isset($data->contact_number) && $errors->isEmpty() ? $data->contact_number : old('contact_number') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('contact_number')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="tin" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">TIN: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('tin') has-error is-focused @enderror">
		                                <input type="text" id="tin" name="tin" class="form-control" value="{{ isset($data->tin) && $errors->isEmpty() ? $data->tin : old('tin') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('tin')
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