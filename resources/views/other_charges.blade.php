@extends('layout')
@section('title', 'Charges')
@section('content')
<style>

    form .form-group select.form-control {
        position: static;
        top: -5px;
    }
    .alert .close{
        line-height : 1.5;
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
                    <i class="material-icons">list</i>
                  </div>
                    <a href="{{ route('other_charges.create') }}" class="btn float-right btn-responsive">Add Charges</a>
                    <h4 class="card-title">Charges</h4>

                </div>
    
                 <div class="card-body">
                        @include('alert')
                    <div class="alert_message"></div>
                    <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                      <table class="table table-hover other_charges_table">
                            <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Type</th>
                                  <th>Name</th>
                                  <th>Amount</th>
                                  <th>Status</th>
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
  $(tableFunction = () => {
    
    let table = $('.other_charges_table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true, // statesaving of datatable
        bDestroy: true, // for re-initialized 
        ajax: "{{ route('other_charges.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'charge_type', name: 'type'},
            {data: 'charge_name', name: 'name'},
            {data: 'amount', name: 'amount'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    // console.log(table.state());


  });
</script>
@endpush