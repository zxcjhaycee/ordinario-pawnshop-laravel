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
                    <a href="{{ route('user.index') }}" class="btn float-right btn-responsive">View All</a>
                    <h4 class="card-title"> {{ ucwords($routeName) }} User</h4>

                </div>
    
                 <div class="card-body">
                    @include('alert')
                 <form action="{{ isset($data->id) ? route('user.update', $data->id) : route('user.store') }}" method="POST">
                        @if(isset($data->id))
                            @method('PUT')
                        @endif
                        @csrf
                    <div class="col-xl-12 mt-5 d-flex justify-content-center">
                        <div class="col-xl-6">
                            <div class="row d-flex justify-content-center">
                                <label for="first_name" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">First Name: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('first_name') has-error is-focused @enderror">
		                                <input type="text" id="first_name" name="first_name" class="form-control" value="{{ isset($data->first_name) && $errors->isEmpty() ? $data->first_name : old('first_name') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('first_name')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="last_name" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">Last Name: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('last_name') has-error is-focused @enderror">
		                                <input type="text" id="last_name" name="last_name" class="form-control" value="{{ isset($data->last_name) && $errors->isEmpty() ? $data->last_name : old('last_name') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('last_name')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="username" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">Username: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('username') has-error is-focused @enderror">
		                                <input type="text" id="username" name="username" class="form-control" value="{{ isset($data->username) && $errors->isEmpty() ? $data->username : old('username') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('username')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="password" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">Password: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('password') has-error is-focused @enderror">
		                                <input type="password" id="password" name="password" class="form-control" value=""/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('password')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="branch_id" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">Branch: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('branch_id') has-error is-focused @enderror">
		                                <!-- <input type="password" id="password" name="password" class="form-control" value=""/> -->
                                        <select name="branch_id" id="branch_id" class="form-control">
                                            <option></option>
                                            @foreach($branch as $key => $value)
                                                @php
                                                    $selected = '';
                                                    if(old('branch_id') == $value->id){
                                                        $selected = 'selected';
                                                    }elseif(isset($data->branch_id) && $data->branch_id == $value->id){
                                                        $selected = 'selected';
                                                    }
                                                @endphp
                                                <option value="{{ $value->id }}" {{ $selected }} >{{ $value->branch }}</option>
                                            @endforeach
                                        </select>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('branch_id')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="access" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">Access: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('access') has-error is-focused @enderror">
                                        <select name="access" id="access" class="form-control">
                                                <option></option>
                                                <option value="admin" {{ old('access') == 'admin' || (isset($data) && $data->access == 'admin')  ? 'selected' : '' }}>
                                                Admin</option>
                                                <option value="user" {{ old('access') == 'user' || (isset($data) && $data->access == 'user') ? 'selected' : '' }}>
                                                End-User</option>
                                        </select>                                        
                                            <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('access')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="auth_code" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 ">Auth Code: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('auth_code') has-error is-focused @enderror">
		                                <input type="text" id="auth_code" name="auth_code" class="form-control" value="{{ isset($data->auth_code) && $errors->isEmpty() ? $data->auth_code : old('auth_code') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('auth_code')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                                <div class="text-center">
                                         <button type="submit" class="btn btn-success">Submit</button>
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