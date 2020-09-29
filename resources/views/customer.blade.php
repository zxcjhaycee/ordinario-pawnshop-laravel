@extends('layout')
@section('title', 'Customer')
@section('content')
<style>

    @media(max-width: 370px){
      .card-title{
        /* font-size : 13px; */
        display:none;
        /* font-weight: bold!important; */
      }
      
      .card [class*=card-header-] .card-icon, .card [class*=card-header-] .card-text {
          padding:10px;
      }
    }
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
                    <i class="material-icons">group</i>
                  </div>
                    <a href="{{ route('customer.create') }}" class="btn float-right btn-responsive">Add Customer</a>
                    <h4 class="card-title">Customer</h4>

                </div>
    
                 <div class="card-body">
                        @include('alert')
                        <div class="alert_message"></div>
                  <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                      <table class="table table-hover customer_table">
                            <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Full Name</th>
                                  <th>Birthday</th>
                                  <th>Sex</th>
                                  <th>Email</th>
                                  <th>Contact #</th>
                                  <th>Actions</th>
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
    
    let table = $('.customer_table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true, // statesaving of datatable
        bDestroy: true, // for re-initialized 
        ajax: "{{ route('customer.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'full_name', name: 'full_name'},
            {data: 'birthdate', name: 'birthdate'},
            {data: 'sex', name: 'sex'},
            {data: 'email', name: 'email'},
            {data: 'contact_number', name: 'contact_number'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    // console.log(table.state());


  });
</script>
@endpush