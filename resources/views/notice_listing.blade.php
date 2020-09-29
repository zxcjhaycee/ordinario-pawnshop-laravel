@extends('layout')
@section('title', 'Create Notice Listing')

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
.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
    width: 100%;
    width: -moz-available;          /* WebKit-based browsers will ignore this. */
    width: -webkit-fill-available;
}
.filter-option {
    font-size: 14px; /* selectpicker */
}
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
  <!-- <div class="container-fluid"> -->
    
    <!-- <a href="" data-modal="#modal" class="modal__trigger">Modal 1</a>
    <div id="modal" class="modal modal__bg" role="dialog" aria-hidden="true">
      <div class="modal__dialog">
        <div class="modal__content">
          <div class="col-xl-12 row mobile_resize">
            <div class="col-xl-6">
              <div class="form-group label-floating">
                <label class="control-label">Inventory Number</label>
                  <input class="form-control" type="number" name="appraised_value" id="appraised_value" value="0">
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div> -->
    
    <!-- <div class="col-xl-12 container table-responsive" id="itemTable"> -->
    <div class="col-xl-12">
              <div class="card">
                <div class="card-header card-header-icon">
                  <div class="card-icon" style="background: linear-gradient(60deg,#702230,#702230)">
                    <i class="material-icons">assignment</i>
                  </div>
                  <h4 class="card-title">Notice Listing</h4>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-xl-7 col-lg-10 col-md-10 col-sm-12 col-12 mb-4">
                      <form action="{{ route('notice_listing.create.search.submit') }}" method="post" class="row">
                        @csrf
                          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <input type="text" name="notice_month_year" data-id=""  data-min-view="months" data-view="months" data-date-format="MM yyyy" class="form-control air_date_picker" placeholder="Month / Year" value="{{ isset($date) ? $date : '' }}">
                          </div>
                          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="dropdown bootstrap-select show-tick">
                              <select class="selectpicker" data-size="7" data-style="select-with-transition" title="Branch" tabindex="-98" name="branch">
                                  @foreach($branch as $branches)
                                    <option value="{{ $branches->id }}" {{ isset($branch_value) && $branch_value == $branches->id ? 'selected' : '' }}>{{ $branches->branch }}</option>
                                  @endforeach
                            </select>
                            </div>
                          </div>
                          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mt-2 text-center-jc">
                            <button type="submit" class="btn btn-default btn-sm-jc"><i class="material-icons">search</i></button>
                          </div>
                          </form>
                      </div>
                    {{--
                      <div class="col-xl-5 col-lg-10 col-md-10 col-sm-12 col-12 mb-4">
                        <form  method="POST" class="row" onSubmit="noticeListingForm(event, this)">
                          @csrf
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <input type="text" name="jewelry_date" data-id="" class="form-control air_date_picker" placeholder="Jewelry" autocomplete="off" required>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <input type="text" name="non_jewelry_date" data-id="" class="form-control air_date_picker" placeholder="Non-Jewelry" autocomplete="off" required>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mt-2 text-center-jc">
                                <!-- <a href="reports/notice_listing_print" target="_blank" class="btn btn-default btn-warning btn-sm-jc"><i class="material-icons">archive</i></a> -->
                                <button type="submit" class="btn btn-default btn-warning btn-sm-jc"><i class="material-icons">archive</i></button>
                                <!-- change to button if finalized -->
                            </div>
                          </form>


                      </div>
                      --}}
                </div>
                    <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                      <table class="table table-hover notice_listing_table">
                            <thead>
                                <tr>
                                  <th style="width:45px">
                                      <input id="checkbox_all" type="checkbox" value="1" name="checkbox_all" onClick="toggle(this)" >
                                  </th>
                                  <th>Pawn Ticket</th>
                                  <th>Customer</th>
                                  <th>Last Transaction</th>
                                  <th>Address</th>
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
    <!-- </div> -->
  <!-- </div> -->
</div>

@endsection

@push('scripts')

    <script type="text/javascript">
  $(tableFunction = () => {
    
    let table = $('.notice_listing_table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true, // statesaving of datatable
        bDestroy: true, // for re-initialized 
        ajax: "{{ route('notice_listing.index', ['date' => $date, 'branch' => $branch_value]) }}",
        columns: [
            {data: 'checkbox', name: 'checkbox', orderable: false, targets: 0, searchable: false},
            {data: 'ticket_number', name: 'ticket_number'},
            {data: 'customer', name: 'customer'},
            {data: 'transaction_date', name: 'transaction_date'},
            {data: 'present_address', name: 'present_address'},
        ],
        order: [[1, 'asc']] 

    });


  });
  function toggle(source) {
        checkboxes = document.querySelectorAll('.item');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }
    function toggleState(){
        checkbox = document.getElementById('checkbox_all');
        checkbox.checked = false;
        if(AreAnyCheckboxesChecked()){
            checkbox.checked = true;
        }
    }

    function AreAnyCheckboxesChecked () {
        if($('.item').not(':checked').length > 0){
            return false;
        }
        return true;
    }

</script>
@endpush