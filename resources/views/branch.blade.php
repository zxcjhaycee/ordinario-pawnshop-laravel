@extends('layout')
@section('title', 'Branch')
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
                    <a href="{{ route('branch.create') }}" class="btn float-right btn-responsive">Add Branch</a>
                    <h4 class="card-title">Branch</h4>

                </div>
    
                 <div class="card-body">
                        @include('alert')
                    <div class="alert_message"></div>
                    <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                      <table class="table table-hover branch_table">
                            <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Branch</th>
                                  <th>Address</th>
                                  <th>Contact Number</th>
                                  <th>Tin</th>
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
    
    let table = $('.branch_table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true, // statesaving of datatable
        bDestroy: true, // for re-initialized 
        ajax: "{{ route('branch.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'branch', name: 'branch'},
            {data: 'address', name: 'address'},
            {data: 'contact_number', name: 'contact_number'},
            {data: 'tin', name: 'tin'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    // console.log(table.state());


  });
</script>
@endpush