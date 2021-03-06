@php
 $routeName = substr(Route::currentRouteName(), strpos(Route::currentRouteName(), ".") + 1); // to identify if add or update
@endphp

@extends('layout')
@if(isset($data))
    @section('title', 'Update User : '. $data->first_name." ".$data->last_name)

@else
    @section('title', 'Create User')
@endif

@section('content')
<style>

    form .form-group select.form-control {
        position: static;
        top: -5px;
    }
    .alert .close{
        line-height : 1.5;
    }
    /* #user_auth_code{
        bottom: 120px;
    } */


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
                                        <select name="access" id="access" class="form-control" onChange="checkAccess()">
                                                <option></option>
                                                <option value="Administrator" {{ old('access') == 'Administrator' || (isset($data) && $data->access == 'Administrator')  ? 'selected' : '' }}>
                                                Administrator</option>
                                                <option value="Manager" {{ old('access') == 'Manager' || (isset($data) && $data->access == 'Manager') ? 'selected' : '' }}>
                                                Manager</option>
                                                <option value="Staff" {{ old('access') == 'Staff' || (isset($data) && $data->access == 'Staff') ? 'selected' : '' }}>
                                                Staff</option>
                                        </select>                                        
                                            <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('access')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row justify-content-center" id="branches_element" style="display:{{ old('access') == 'Manager' || isset($data) && $data->access == 'Manager' ? 'flex' : 'none' }}">
                                <label for="branches" class="col-xl-3 col-lg-2 col-md-2 col-sm-2 mb-4 ">Branches: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('branches') has-error is-focused @enderror">
                                        <select name="branches[]" id="branches" class="form-control branch_select" multiple>

                                                    @if(old('branches'))
                                                        @foreach($branch->find(old('branches')) as $branchs)
                                                         <option value="{{ $branchs->id }}" selected>{{ $branchs->branch }}</option>
                                                        @endforeach
                                                    @elseif(isset($data) && isset($branches))
                                                        @foreach($branches as $branchs)
                                                         <option value="{{ $branchs->id }}" selected>{{ $branchs->branch }}</option>
                                                        @endforeach                                                    
                                                    @endif
                                        </select>                                        
                                            <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('branches')
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

                        </div>

                    </div>
                    <div class="col-xl-12">

                                <div class="d-flex justify-content-center">
                                        <input type="text" id="user_auth_code" class="form-control" style="margin-top:16px;width:130px" name="user_auth_code"  placeholder="Auth Code"/>
                                         &nbsp;
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
    const branch = document.querySelector('.branch_select');
    const route = '{{ route('branch.search') }}';
    select2Initialized(branch, route);    
    /*
    $(".attachment_type_select").on("select2:select", function (e) {
    //   console.log(e.params);
      if(e.params.data.text == 'Can\'t find? Add Attachment'){
        window.open('{{ route('attachment.create') }}');
        $(this).val(null).trigger("change");

      }
      
    });
    */

   function checkAccess(){
    const access = document.getElementById('access');
    const branch = document.getElementById('branch_id');
    const branches_element = document.getElementById('branches_element');
    branches_element.style.display = 'none';
    if(access.value == 'Manager'){
        branches_element.style.display = 'flex';
        // $('.branch_select').select2().val(['1']).trigger("change")

    }
    //    console.log(access.value);
   }
</script>
@endpush