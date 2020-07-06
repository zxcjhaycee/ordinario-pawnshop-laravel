
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
                    <a href="{{ route('inventory.show', $id) }}" class="btn float-right btn-responsive">View Details</a>
                    <h4 class="card-title">Add Pawn</h4>

                </div>
    
                 <div class="card-body">
                    @include('alert')
                 <form action="{{ isset($data->id) ? route('inventory.update', $data->id) : route('inventory.store') }}" method="POST">
                        @if(isset($data->id))
                            @method('PUT')
                        @endif
                        @csrf
                    <div class="mt-5">
                    <!-- Inventory Number -->
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
                                        <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-17px;">
                                            <div class="form-group input @error('ticket_number') has-error is-focused @enderror">
                                                <input type="number" readonly id="ticket_number" name="ticket_number" class="form-control" value="00001"/>
                                                <span class="material-icons form-control-feedback">clear</span>
                                            </div>
                                            @error('ticket_number')
                                            <label class="text-danger">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-12 row mt-3">
                                        <label for="transaction_type" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Transaction Type: </label>
                                        <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-17px;">
                                            <div class="form-group input @error('transaction_type') has-error is-focused @enderror">
                                            <select name="transaction_type" id="transaction_type" class="form-control">
                                                <option>New</option>
                                                <option>Old</option>
                                            </select>                                                
                                           <span class="material-icons form-control-feedback">clear</span>
                                            </div>
                                            @error('transaction_type')
                                            <label class="text-danger">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-xl-12 row mt-3">
                                        <label for="ticket_number" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Processed By: </label>
                                        <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-17px;">
                                            <div class="form-group input @error('ticket_number') has-error is-focused @enderror">
                                                <input type="text" readonly id="ticket_number" name="ticket_number" class="form-control" value="Administrator"/>
                                                <span class="material-icons form-control-feedback">clear</span>
                                            </div>
                                            @error('ticket_number')
                                            <label class="text-danger">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="col-xl-12 row mt-3">
                                            <label for="transaction_date" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Transaction Date: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-17px;">
                                                <div class="form-group input @error('transaction_date') has-error is-focused @enderror">
                                                    <input type="text" name="transaction_date"  class="form-control air_date_picker" onblur="transaction_dates(this)">
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('transaction_date')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                    </div>

                                    <div class="col-xl-12 row mt-3">
                                            <label for="maturity_date" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Maturity Date: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-17px;">
                                                <div class="form-group input @error('maturity_date') has-error is-focused @enderror">
                                                    <input type="text" name="maturity_date" id="maturity_date" class="form-control" readonly>
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('maturity_date')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                    </div>

                                    <div class="col-xl-12 row mt-3">
                                            <label for="expiration_date" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Expiration Date: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-17px;">
                                                <div class="form-group input @error('expiration_date') has-error is-focused @enderror">
                                                <input type="text" name="expiration_date" id="expiration_date" class="form-control"  readonly>
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('expiration_date')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                    </div>

                                    <div class="col-xl-12 row mt-3">
                                            <label for="auction_date" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">Auction Date: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-17px;">
                                                <div class="form-group input @error('auction_date') has-error is-focused @enderror">
                                                <input type="text" name="auction_date" id="auction_date" class="form-control" readonly>
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

                         <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                <h4 class="card-title">Customer</h4>
                                </div>
                            </div>
                            <div class="card-body card-body-form">


                                    <div class="row d-flex justify-content-center mt-3">
                                        <label for="customer_id" class="col-xl-2 col-lg-2 col-md-2 col-sm-4 ">Customer: </label>
                                        <div class="col-xl-4 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-17px;">
                                            <div class="form-group input @error('customer_id') has-error is-focused @enderror">
                                                <input type="text" readonly id="customer_id" name="customer_id" class="form-control" value="Ashley Bauch PhD"/>
                                                <span class="material-icons form-control-feedback">clear</span>
                                            </div>
                                            @error('customer_id')
                                            <label class="text-danger">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row d-flex justify-content-center mt-3">
                                        <label for="attachment_id" class="col-xl-2 col-lg-2 col-md-2 col-sm-4 ">Attachment: </label>
                                        <div class="col-xl-4 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-20px;">
                                            <div class="form-group input @error('attachment_id') has-error is-focused @enderror">
                                                <select name="attachment_id" id="attachment_id" class="form-control attachment_id"></select>
                                                <span class="material-icons form-control-feedback">clear</span>
                                            </div>
                                            @error('attachment_id')
                                            <label class="text-danger">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row d-flex justify-content-center mt-3">
                                        <label for="attachment_number" class="col-xl-2 col-lg-2 col-md-2 col-sm-4 ">Attachment Number: </label>
                                        <div class="col-xl-4 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-20px;">
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


                         <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                <h4 class="card-title">Items</h4>
                                </div>
                            </div>
                            <div class="card-body card-body-form">




                            </div>
                         </div>




                         <div class="card">
                            <div class="card-header card-header-text card-header-primary">
                                <div class="card-text">
                                <h4 class="card-title">Computation</h4>
                                </div>
                            </div>
                            <div class="card-body card-body-form row">
                                <div class="col-xl-6">
                                        <div class="col-xl-12 row mt-3">
                                            <label for="ticket_number" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">PT #: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-17px;">
                                                <div class="form-group input @error('ticket_number') has-error is-focused @enderror">
                                                    <input type="number" readonly id="ticket_number" name="ticket_number" class="form-control" value="00001"/>
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('ticket_number')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-12 row mt-3">
                                            <label for="ticket_number" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">PT #: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-17px;">
                                                <div class="form-group input @error('ticket_number') has-error is-focused @enderror">
                                                    <input type="number" readonly id="ticket_number" name="ticket_number" class="form-control" value="00001"/>
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('ticket_number')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-12 row mt-3">
                                            <label for="ticket_number" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">PT #: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-17px;">
                                                <div class="form-group input @error('ticket_number') has-error is-focused @enderror">
                                                    <input type="number" readonly id="ticket_number" name="ticket_number" class="form-control" value="00001"/>
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('ticket_number')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-12 row mt-3">
                                            <label for="ticket_number" class="col-xl-4 col-lg-2 col-md-2 col-sm-4 ">PT #: </label>
                                            <div class="col-xl-8 col-lg-6 col-md-5 col-sm-7 attachment_number_error" style="top:-17px;">
                                                <div class="form-group input @error('ticket_number') has-error is-focused @enderror">
                                                    <input type="number" readonly id="ticket_number" name="ticket_number" class="form-control" value="00001"/>
                                                    <span class="material-icons form-control-feedback">clear</span>
                                                </div>
                                                @error('ticket_number')
                                                <label class="text-danger">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                </div>
                            <div class="col-xl-6"></div>



                            </div>
                         </div>
               
                        <div class="mx-auto">
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
    $(document).ready(function(){
        customer_attachment(3);
    });
    </script>
@endpush