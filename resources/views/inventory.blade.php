@extends('layout')
@section('title', 'Inventory')
@section('content')
<style>
    @media(max-width: 576px){
      .text-center-jc {
       text-align:center
      }
      .btn-sm-jc{
        padding: 12px 20px;
        font-size: 12px;
      }
    }

    @media(min-width: 576px){
      .btn-sm-jc{
        padding: 6px 20px;
        font-size: 11px;
      }
    }
    form .form-group select.form-control {
        position: static;
        top: -5px;
    }
    .alert .close{
        line-height : 1.5;
    }
    .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
    width: 100%;
    width: -moz-available;          /* WebKit-based browsers will ignore this. */
    width: -webkit-fill-available;
    }
    .filter-option {
        font-size: 14px; /* selectpicker */
    }
/* 


@media (min-width: 768px) {
  .btn {
    font-size:12px;
    padding:6px 12px;
  }
}

@media (min-width: 992px) {
  .btn {
    font-size:14px;
    padding:8px 12px;
  }
}
*/

@media (max-width: 576px) {
  .btn-responsive {
    font-size:10px;
    padding:4px 4px;
  }
}

@media (min-width: 1200px) {
  .btn-responsive {
    padding:12px 30px;
    font-size:12px;
  }
} 
.pagination{
  flex-wrap:wrap;
}
</style>
<div class="content">
    <div class="col-xl-12">
              <div class="card">
                <div class="card-header card-header-icon">
                  <div class="card-icon" style="background: linear-gradient(60deg,#702230,#702230)">
                    <i class="material-icons">star</i>
                  </div>
                    <!-- <a href="{{ route('pawn.create') }}" class="btn float-right btn-responsive">Add Pawn</a> -->
                    <h4 class="card-title">Inventory</h4>

                </div>
    
                 <div class="card-body">
                    <div class="row">
                      <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                        <form action="{{ route('inventory.submit') }}" method="post" class="row">
                          @csrf
                          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                            <div class="dropdown bootstrap-select show-tick">
                              <select class="selectpicker" data-size="7" data-style="select-with-transition" title="Branch" tabindex="-98" name="branch_id">
                                  @foreach($branch as $branches)
                                    <option value="{{ $branches->id }}" {{ $branch_selected == $branches->id ? 'selected' : '' }}>{{ $branches->branch }}</option>
                                  @endforeach
                            </select>
                            </div>
                          </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                              <input type="text" name="date" id="date"  class="form-control inventory_picker"  value="{{ isset($date) ? date('m/d/Y', strtotime($date)) : date('m/d/Y') }}" autocomplete="off">
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                              <div class="dropdown bootstrap-select show-tick">
                                <select class="selectpicker" data-size="7" data-style="select-with-transition" title="Loan Type" tabindex="-98" name="loan_type">
                                <option  {{ isset($loan_type) && $loan_type == 'ALL' ? 'selected' : '' }} value="ALL">All Loans</option>
                                  <option {{ isset($loan_type) && $loan_type == 'Active' ? 'selected' : '' }}>Active</option>
                                  <option {{ isset($loan_type) && $loan_type == 'Matured' ? 'selected' : '' }}>Matured</option>
                                  <option {{ isset($loan_type) && $loan_type == 'Expired' ? 'selected' : '' }}>Expired</option>
                              </select>
                              </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 mt-2 text-center-jc">
                              <button type="submit" class="btn btn-default btn-sm-jc"><i class="material-icons">search</i></button>
                            </div>
                            </form>
                        </div>
                    </div>
                    <h4>As of {{ date('M d, Y', strtotime($date)) }}</h4>
                    <h5>Total Principal :  {{ number_format($principal_total,2) }}</h5>
                        @include('alert')
                    <div class="alert_message"></div>
                    <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                      <table class="table table-hover inventory_table">
                            <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Inventory #</th>
                                  <th>PT #</th>
                                  <th>Loan Date</th>
                                  <th>Maturity<br/>Expiry<br/>Auction</th>
                                  <th>Customer</th>
                                  <th>Principal</th>
                                  {{-- <th>Type<br/>Status</th> --}}
                                  <th style="width:15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>


                <!-- end content-->
              </div>

              <!--  end card  -->
            </div>

</div>

@endsection
@push('scripts')

    <script type="text/javascript">
      const inventory_picker = $('.inventory_picker').datepicker({
    language: 'en',
    autoClose : true,
    position: "bottom center",
    // inline : true,
    // minDate: new Date() // Now can select only dates, which goes after today
  });
  inventory_picker.data('datepicker').selectDate(new Date($('.inventory_picker').val()));
  $(tableFunction = () => {
    
    let table = $('.inventory_table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true, // statesaving of datatable
        bDestroy: true, // for re-initialized 
        ajax: "{{ route('inventory.index', ['branch' => $branch_selected, 'date' => $date, 'loan_type' => $loan_type]) }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'inventory_number', name: 'inventory_number'},
            {data: 'ticket_number', name: 'ticket_number'},
            {data: 'transaction_date', name: 'transaction_date'},
            {data: 'pawn_dates', name: 'pawn_dates'},
            {data: 'customer', name: 'customer'},
            {data: 'principal', name: 'principal'},
            // {data: 'transaction_status', name: 'transaction_status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    // console.log(table.state());


  });
</script>
@endpush