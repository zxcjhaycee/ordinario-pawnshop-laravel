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
                  <div class="table-responsive">
                      <table class="table table-hover">
                            <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Branch</th>
                                  <th>Status</th>
                                  <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $count = 1;
                                @endphp
                                @foreach($branch as $key => $value)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $value->branch }}</td>
                                        <td class="text-{{ !isset($value->deleted_at) ? 'success' : 'danger' }}" style="width:20%">
                                            {!! !isset($value->deleted_at) ? 'Active' : 'Inactive <i class="text-muted">('.date('F d, Y', strtotime($value->deleted_at)).')</i> ' !!}
                                        </td>
                                        <td style="width:15%">
                                            <a href="{{ route('branch.edit', $value->id) }}" class="btn btn-sm ordinario-button"><span class="material-icons">edit</span></a>
                                            <form action="{{ route('branch.destroy', $value->id) }}" method="POST" style="display:inline">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning"><span class="material-icons">{{ !isset($value->deleted_at) ? 'delete' : 'restore' }}</span></button>
                                            </form>
                                            <!-- <a href="branch/delete/{{ $value->id }}" class="btn btn-sm btn-warning"><span class="material-icons">delete</span></a> -->
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>


                <!-- end content-->
              </div>

              <!--  end card  -->
            </div>

</div>

@endsection