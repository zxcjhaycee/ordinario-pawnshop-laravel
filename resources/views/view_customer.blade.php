@php
 $routeName = substr(Route::currentRouteName(), strpos(Route::currentRouteName(), ".") + 1); // to identify if add or update
@endphp

@extends('layout')
@section('title', 'Customer : ' .$customer->first_name." ".$customer->middle_name." ".$customer->last_name)

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
                    <a href="{{ route('customer.edit', $customer->id) }}" class="btn float-right btn-info btn-responsive"><span class="material-icons">edit</span></a>
                    <h4 class="card-title"> {{ ucwords($routeName) }} Customer</h4>

                </div>
    
                 <div class="card-body">
                    <!-- @include('alert') -->
                    <div class="alert_message"></div>
                    
                 <form method="POST" enctype="multipart/form-data" id="customerForm">
                        @if(isset($data->id))
                            @method('PUT')
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
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 " style="top:-10px;">
                                        <div class="form-group">
                                        <h5 >{{ $customer->first_name }}</h5> 
                                        </div>
                                        <!-- <label class="text-danger first_name_error" style="display:none"></label> -->
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="middle_name" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Middle Name: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7" style="top:-10px;">
                                        <div class="form-group" style="min-height:38px">
                                            <h5 >{{ $customer->middle_name  }}</h5> 
                                        </div>

                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="last_name" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Last Name: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 " style="top:-10px;">
                                     <div class="form-group" style="min-height:38px">
                                        <h5 >{{ $customer->last_name }}</h5> 

                                        </div>

                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="suffix" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Suffix: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7" style="top:-10px;">
                                        <div class="form-group">
                                        <h5 >{{ $customer->suffix }}</h5> 

                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="birthdate" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Birthdate: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7" style="top:-10px;">
                                        <div class="form-group">
                                        <h5 >{{ date('F d, Y', strtotime($customer->birthdate)) }}</h5> 

                                        </div>

                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="sex" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Sex: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 sex_error" style="top:-10px;">
                                        <div class="form-group">
                                        <h5 >{{ ucwords($customer->sex) }}</h5> 

                                    </div>
                                </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="civil_status" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Civil Status: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7" style="top:-10px;">
                                        <div class="form-group">
                                        <h5 >{{ ucwords($customer->civil_status) }}</h5> 

                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="email" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Email: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 email_error" style="top:-10px;">
                                        <div class="form-group">
                                        <h5 >{{ $customer->email }}</h5> 

                                        </div>

                                    </div>
                                </div>

                                <div class="row d-flex justify-content-center">
                                    <label for="contact_number" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Contact Number: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 contact_number_error" style="top:-10px;">
                                        <div class="form-group">
                                        <h5 >{{ $customer->contact_number }}</h5> 

                                        </div>
  
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-center">
                                    <label for="alternate_number" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Alternate Number: </label>
                                    <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7" style="top:-10px;">
                                        <div class="form-group">
                                        <h5 >{{ $customer->alternate_number }}</h5> 

                                        </div>

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
                                    <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-10px;">
                                        <div class="form-group">
                                            <h5 >{{ $customer->present_address }}</h5> 
                                            <!-- <h5 >The quick brown fox jumps over the lazy dog near the riverbanks. The quick brown fox jumps over the lazy dog near the riverbanks</h5>  -->

                                        </div>

                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="present_address_two" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Address Line 2: </label>
                                    <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-10px;">
                                        <div class="form-group">
                                        <h5 >{{ $customer->present_address_two }}</h5> 

                                        </div>

                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="present_area" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Area: </label>
                                    <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-10px;">
                                        <h5 >{{ $customer->present_area }}</h5> 

                                        <div class="form-group">
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="present_city" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">City: </label>
                                    <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-10px;">
                                        <div class="form-group">
                                        <h5 >{{ $customer->present_city }}</h5> 

                                        </div>

                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <label for="present_zip_code" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Zip Code: </label>
                                    <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-10px;">
                                        <div class="form-group">
                                        <h5 >{{ $customer->present_zip_code }}</h5> 

                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>

                        </div>



                        <div class="col-xl-6">



                        <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                    <h5 class="card-title">Permanent Address</h5>
                                </div>
                            </div>
                            <div class="card-body card-body-form">
                            <div class="row d-flex justify-content-center">
                                <label for="permanent_address" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Address Line: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-10px;">
                                    <div class="form-group">
                                    <h5 >{{ $customer->permanent_address }}</h5> 

                                    </div>

                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="permanent_address_two" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Address Line 2: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-10px;">
                                    <div class="form-group">
                                    <h5 >{{ $customer->permanent_address_two }}</h5> 

                                    </div>

                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="permanent_area" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Area: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-10px;">
                                    <div class="form-group">
                                    <h5 >{{ $customer->permanent_area }}</h5> 

                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="permanent_city" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">City: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7" style="top:-10px;">
                                    <div class="form-group">
                                    <h5>{{ $customer->permanent_city }}</h5> 

                                    </div>

                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="permanent_zip_code" class="col-xl-3 col-lg-2 col-md-2 col-sm-4 ">Zip Code: </label>
                                <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 permanent_zip_code_error" style="top:-10px;">
                                    <div class="form-group">
                                    <h5>{{ $customer->permanent_zip_code }}</h5> 

                                    </div>

                                </div>
                            </div>
                            </div>
                        </div>
                        @foreach($customer->attachments as $key => $value)
                        <section class="attachment_section">
                        <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                <h4 class="card-title">Attachment</h4>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger pull-right remove" data-name="delete_attachment" id="{{ $value->pivot->id }}"><span class="material-icons">remove_circle</span></button>
                            </div>
                            <div class="card-body card-body-form">
                            <div class="row d-flex justify-content-center">
                                <label for="attachment_type" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Type: </label>
                                <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7" style="top:-10px;">
                                    <div class="form-group">
                                        <h5>{{ $value->type }}</h5>
    
                                        </select>                                     
                                    </div>

                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="attachment_number" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Number: </label>
                                <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7" style="top:-10px;">
                                    <div class="form-group">
                                    <h5>{{ $value->pivot->number }}</h5>
                       
                                    </div>

                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <label for="attachment" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">File: </label>
                                <div class="col-xl-8 col-lg-5 col-md-5 col-sm-7" style="top:-10px;">
                                    <div class="form-group">
                                    <h5>{{ $value->pivot->path }}</h5>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>     
                    </section>
                    @endforeach
                    <!-- end of card attachment -->
                           
                        </div>

                    </div>

                  </form>
                 

                <!-- end content-->
              </div>

              <!--  end card  -->
            </div>

</div>

@endsection