@extends('layout')
@section('title', 'Auction')

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
      .btn-responsive {
        font-size:10px;
        padding:4px 4px;
      }
    }

    @media(min-width: 576px){
      .btn-sm-jc{
        padding: 6px 20px;
        font-size: 11px;
      }
    }
    @media screen and (max-width: 767px) {
    table.dataTable>tbody>tr>td:first-child{
        padding-left:5px!important;
    }
}
@media (min-width: 1200px) {
  .btn-responsive {
    padding:12px 30px;
    font-size:12px;
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
.pagination{
  flex-wrap:wrap;
}

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
                    <i class="material-icons">star</i>
                  </div>
                    <h4 class="card-title">Auction</h4>

                </div>
                
                 <div class="card-body">
                        @include('alert')
                    <div class="alert_message"></div>

                        <form  method="POST" class="row" onSubmit="auctionForm(event, this)">
                          @csrf
                          {{--
                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4 col-12">
                                <input type="text" name="inventory_auction_number" data-id="" class="form-control" placeholder="Inventory Auction Number" autocomplete="off" required>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
                                <input type="number" name="price" data-id="" class="form-control" placeholder="Price" autocomplete="off" step=".01" required>
                            </div>
                            --}}
                            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12">
                                <input type="text" name="auction_date" data-id="" class="form-control air_date_picker" placeholder="Auction Date" autocomplete="off" required>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4 col-12 mt-2 text-center-jc">
                                <!-- <a href="reports/notice_listing_print" target="_blank" class="btn btn-default btn-warning btn-sm-jc"><i class="material-icons">archive</i></a> -->
                                <button type="submit" class="btn btn-default btn-success btn-sm-jc"><i class="material-icons">save</i></button>
                                <!-- change to button if finalized -->
                            </div>
                          </form>



                    <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                      <table class="table table-hover auction_table">
                            <thead>
                                <tr>
                                    <th style="width:45px">
                                        <input id="checkbox_all" type="checkbox" value="1" name="checkbox_all" onClick="toggle(this)" >
                                    </th>
                                    <th>PT #</th>
                                    <th>Last Transaction</th>
                                    <th>Customer</th>
                                    <th>Principal</th>
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

  $(tableFunction = () => {

    let table = $('.auction_table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true, // statesaving of datatable
        bDestroy: true, // for re-initialized 
        // responsive : {
        //   details : {
        //       type : 'column',
        //       target : 'tr',
        //   }
        // },
        // responsive : true,
        ajax: "{{ route('auction.index') }}",
        columns: [
            {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-left'},
            {data: 'ticket_number', name: 'ticket_number'},
            {data: 'transaction_date', name: 'transaction_date'},
            {data: 'customer', name: 'customer'},
            {data: 'principal', name: 'principal'},
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