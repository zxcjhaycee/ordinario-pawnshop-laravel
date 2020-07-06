@php
 $routeName = substr(Route::currentRouteName(), strpos(Route::currentRouteName(), ".") + 1); // to identify if add or update
@endphp

@extends('layout')
@section('content')
<style>

    form .form-group select.form-control {
        position: static;
        /* top: -10px; */
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
                    <i class="material-icons">list</i>
                  </div>
                    <a href="{{ route('customer.index') }}" class="btn float-right btn-responsive">View All</a>
                    @if(isset($data))
                    <a href="{{ route('customer.show', $data->id) }}" class="btn float-right ordinario-button btn-responsive"><span class="material-icons">view_list</span></a>
                    @endif
                    <h4 class="card-title"> {{ ucwords($routeName) }} Customer</h4>

                </div>
    
                 <div class="card-body">
                    <!-- @include('alert') -->
                    <div class="alert_message"></div>
                    
                 <form method="POST" enctype="multipart/form-data" id="customerForm" onSubmit="customerForm(event, this)">
                        @if(isset($data->id))
                            @method('PUT')
                            <input type="hidden" id="id" name="id" value="{{ $data->id }}">
                        @endif
                        @csrf
                    <div class="mt-5 row">
                        <div class="col-xl-6 col-lg-12 col-12">

                            <div class="card">
                                <div class="card-header card-header-text card-header-primary">
                                    <div class="card-text">
                                    <h4 class="card-title">Details</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <label for="first_name" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">First Name: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 first_name_error" style="top:-20px;">
                                        <div class="form-group input @error('first_name') has-error is-focused @enderror">
                                            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ isset($data->first_name) && $errors->isEmpty() ? $data->first_name : old('first_name') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        <!-- <label class="text-danger first_name_error" style="display:none"></label> -->
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="middle_name" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Middle Name: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 middle_name_error" style="top:-20px;">
                                        <div class="form-group input @error('middle_name') has-error is-focused @enderror">
                                            <input type="text" id="middle_name" name="middle_name" class="form-control" value="{{ isset($data->middle_name) && $errors->isEmpty() ? $data->middle_name : old('middle_name') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('middle_name')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="last_name" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Last Name: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 last_name_error" style="top:-20px;">
                                        <div class="form-group input @error('last_name') has-error is-focused @enderror">
                                            <input type="text" id="last_name" name="last_name" class="form-control" value="{{ isset($data->last_name) && $errors->isEmpty() ? $data->last_name : old('last_name') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('last_name')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="suffix" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Suffix: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7" style="top:-20px;">
                                        <div class="form-group @error('suffix') has-error is-focused @enderror">
                                            <input type="text" id="suffix" name="suffix" class="form-control" value="{{ isset($data->suffix) && $errors->isEmpty() ? $data->suffix : old('suffix') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('suffix')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="birthdate" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Birthdate: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 birthdate_error" style="top:-20px;">
                                        <div class="form-group input @error('birthdate') has-error is-focused @enderror">
                                            <input type="text" id="birthdate" name="birthdate" class="form-control air_date_picker" value="{{ isset($data->birthdate) && $errors->isEmpty() ? date('m/d/Y', strtotime($data->birthdate)) : old('birthdate') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('birthdate')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="sex" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Sex: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 sex_error" style="top:-20px;">
                                        <div class="form-group input @error('sex') has-error is-focused @enderror">

                                            <div class="form-check form-check-radio">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="sex" id="exampleRadios1" value="male" {{ isset($data) && $data->sex == 'male' ? 'checked' : '' }} >
                                                    Male
                                                    <span class="circle" for="exampleRadios1">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="sex" id="exampleRadios2" value="female" {{ isset($data) && $data->sex == 'female' ? 'checked' : '' }}  >
                                                    Female
                                                    <span class="circle" for="exampleRadios2">
                                                        <span class="check"></span>
                                                    </span>
                                                </label>
                                            </div>

                                                <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('sex')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="civil_status" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Civil Status: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 civil_status_error" style="top:-20px;">
                                        <div class="form-group input @error('civil_status') has-error is-focused @enderror">
                                            <select name="civil_status" id="civil_status" class="form-control">
                                                    <option></option>
                                                    <option value="single" {{ old('civil_status') == 'single' || (isset($data) && $data->civil_status == 'single')  ? 'selected' : '' }}>
                                                        Single
                                                    </option>
                                                    <option value="married" {{ old('civil_status') == 'married' || (isset($data) && $data->civil_status == 'married') ? 'selected' : '' }}>
                                                        Married
                                                    </option>
                                                    <option value="seperated" {{ old('civil_status') == 'seperated' || (isset($data) && $data->civil_status == 'seperated') ? 'selected' : '' }}>
                                                        Separated
                                                    </option>
                                                    <option value="divorced" {{ old('civil_status') == 'divorced' || (isset($data) && $data->civil_status == 'divorced') ? 'selected' : '' }}>
                                                        Divorced
                                                    </option>
                                                    <option value="widowed" {{ old('civil_status') == 'widowed' || (isset($data) && $data->civil_status == 'widowed') ? 'selected' : '' }}>
                                                        Widowed
                                                    </option>
                                            </select>                                        
                                                <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('civil_status')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="email" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Email: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 email_error" style="top:-20px;">
                                        <div class="form-group input @error('email') has-error is-focused @enderror">
                                            <input type="text" id="email" name="email" class="form-control" value="{{ isset($data->email) && $errors->isEmpty() ? $data->email : old('email') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('email')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-center">
                                    <label for="contact_number" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Contact Number: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 contact_number_error" style="top:-20px;">
                                        <div class="form-group input @error('contact_number') has-error is-focused @enderror">
                                            <input type="number" id="contact_number" name="contact_number" class="form-control" value="{{ isset($data->contact_number) && $errors->isEmpty() ? $data->contact_number : old('contact_number') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('contact_number')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-center">
                                    <label for="alternate_number" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Alternate Number: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 alternate_number_error" style="top:-20px;">
                                        <div class="form-group input @error('alternate_number') has-error is-focused @enderror">
                                            <input type="number" id="alternate_number" name="alternate_number" class="form-control" value="{{ isset($data->alternate_number) && $errors->isEmpty() ? $data->alternate_number : old('alternate_number') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('alternate_number')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                <h4 class="card-title">Present Address</h4>
                                </div>
                            </div>
                            <div class="card-body card-body-form">
                                <div class="row d-flex justify-content-center">
                                    <label for="present_address" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Address Line: </label>
                                    <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 present_address_error" style="top:-20px;">
                                        <div class="form-group input @error('present_address') has-error is-focused @enderror">
                                            <input type="text" id="present_address" name="present_address" class="form-control" value="{{ isset($data->present_address) && $errors->isEmpty() ? $data->present_address : old('present_address') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('present_address')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="present_address_two" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Address Line 2: </label>
                                    <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                        <div class="form-group @error('present_address_two') has-error is-focused @enderror">
                                            <input type="text" id="present_address_two" name="present_address_two" class="form-control" value="{{ isset($data->present_address_two) && $errors->isEmpty() ? $data->present_address_two : old('present_address_two') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('present_address_two')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="present_area" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Area: </label>
                                    <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 present_area_error" style="top:-20px;">
                                        <div class="form-group input @error('present_area') has-error is-focused @enderror">
                                            <input type="text" id="present_area" name="present_area" class="form-control" value="{{ isset($data->present_area) && $errors->isEmpty() ? $data->present_area : old('present_area') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('present_area')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="present_city" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">City: </label>
                                    <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 present_city_error" style="top:-20px;">
                                        <div class="form-group input @error('present_city') has-error is-focused @enderror">
                                            <input type="text" id="present_city" name="present_city" class="form-control" value="{{ isset($data->present_city) && $errors->isEmpty() ? $data->present_city : old('present_city') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('present_city')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="present_zip_code" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Zip Code: </label>
                                    <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 present_zip_code_error" style="top:-20px;">
                                        <div class="form-group input @error('present_zip_code') has-error is-focused @enderror">
                                            <input type="number" id="present_zip_code" name="present_zip_code" class="form-control" value="{{ isset($data->present_zip_code) && $errors->isEmpty() ? $data->present_zip_code : old('present_zip_code') }}"/>
                                            <span class="material-icons form-control-feedback">clear</span>
                                        </div>
                                        @error('present_zip_code')
                                        <label class="text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                         </div>

                        </div>



                        <div class="col-xl-6">



                        <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                    <h4 class="card-title">Permanent Address</h4>
                                </div>
                            </div>
                            <div class="card-body card-body-form">
                            <div class="row d-flex justify-content-center">
                                <label for="permanent_address" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Address Line: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 permanent_address_error" style="top:-20px;">
                                    <div class="form-group input @error('permanent_address') has-error is-focused @enderror">
		                                <input type="text" id="permanent_address" name="permanent_address" class="form-control" value="{{ isset($data->permanent_address) && $errors->isEmpty() ? $data->permanent_address : old('permanent_address') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('permanent_address')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="permanent_address_two" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Address Line 2: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-20px;">
                                    <div class="form-group @error('permanent_address_two') has-error is-focused @enderror">
		                                <input type="text" id="permanent_address_two" name="permanent_address_two" class="form-control" value="{{ isset($data->permanent_address_two) && $errors->isEmpty() ? $data->permanent_address_two : old('permanent_address_two') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('permanent_address_two')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="permanent_area" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Area: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 permanent_area_error" style="top:-20px;">
                                    <div class="form-group input @error('permanent_area') has-error is-focused @enderror">
		                                <input type="text" id="permanent_area" name="permanent_area" class="form-control" value="{{ isset($data->permanent_area) && $errors->isEmpty() ? $data->permanent_area : old('permanent_area') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('permanent_area')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="permanent_city" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">City: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 permanent_city_error" style="top:-20px;">
                                    <div class="form-group input @error('permanent_city') has-error is-focused @enderror">
		                                <input type="text" id="permanent_city" name="permanent_city" class="form-control" value="{{ isset($data->permanent_city) && $errors->isEmpty() ? $data->permanent_city : old('permanent_city') }}"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('permanent_city')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="permanent_zip_code" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Zip Code: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 permanent_zip_code_error" style="top:-20px;">
                                    <div class="form-group input @error('permanent_zip_code') has-error is-focused @enderror">
		                                <input type="number" id="permanent_zip_code" name="permanent_zip_code" class="form-control" value="{{ isset($data->permanent_zip_code) && $errors->isEmpty() ? $data->permanent_zip_code : old('permanent_zip_code') }}"/>
                                         <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error('permanent_zip_code')
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            </div>
                        </div>
                        <section class="attachment_section">
                            @if(!isset($data))

                        <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                <h4 class="card-title">Attachment</h4>
                                </div>

                            </div>
                            <div class="card-body card-body-form">
                            <div class="row d-flex justify-content-center">
                                <label for="attachment_type" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Type: </label>
                                <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 attachment_type_0_error"  style="top:-20px;">
                                    <div class="form-group input @error("attachment_type") has-error is-focused @enderror">
                                        <select name="attachment_type[0]" id="attachment_type" class="form-control attachment_type_select">
                                        <option></option>
                                        </select>                                     
                                    </div>
                                    @error("attachment_type")
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="attachment_number" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Number: </label>
                                <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 attachment_number_0_error" style="top:-20px;">
                                    <div class="form-group input @error("attachment_number") has-error is-focused @enderror">
                                    <input type="text" id="attachment_number" name="attachment_number[0]" class="form-control"/>
                                        <span class="material-icons form-control-feedback">clear</span>
                                    </div>
                                    @error("attachment_number")
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="attachment" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">File: </label>
                                <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 attachment_0_error" style="top:-20px;">
                                    <div class="form-group input @error("attachment") has-error is-focused @enderror">
                                                <input type="file" name="attachment[0]">
                                            <div class="input-group">
                                                <input type="text" name="attachment[0]" readonly="" class="form-control" placeholder="Add Attachment">
                                                <button type="button" class="btn btn-fab btn-fab-mini">
                                                     <i class="material-icons">attach_file</i>
                                                </button>
                                            </div>
                                        
                                    </div>
                                    @error("attachment")
                                     <label class="text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                        </section>
                    <!-- end of card attachment -->
                           
                            <button type="button" class="btn btn-warning btn-sm add_section" onClick="addAttachment()"> Add Another Attachment </button>
                        </div>
                            <div class="text-center mx-auto">
                                            <button type="submit" class="btn btn-success">Submit</button>
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
    const attachment = document.querySelector('.attachment_type_select');
    const route = '{{ route('attachment.search') }}';
    select2Initialized(attachment, route);    
    $(".attachment_type_select").on("select2:select", function (e) {
    //   console.log(e.params);
      if(e.params.data.text == 'Can\'t find? Add Attachment'){
        window.open('{{ route('attachment.create') }}');
        $(this).val(null).trigger("change");

      }

    });
</script>
@endpush