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
		                                <input type="text" id="branch" name="branch" class="form-control" value="{{ isset($data->branch) && $errors->isEmpty() ? $data->branch : '' }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('branch')
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