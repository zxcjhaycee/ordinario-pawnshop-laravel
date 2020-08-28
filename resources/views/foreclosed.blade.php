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
                    <h4 class="card-title">Expired</h4>

                </div>
    
                 <div class="card-body">
                        @include('alert')
                    <div class="alert_message"></div>
                    <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                      <table class="table table-hover foreclosed_table">
                            <thead>
                                <tr>
                                    <th>PT #</th>
                                    <th>Loan</th>
                                    <th>Customer</th>
                                    <th>Principal</th>
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

    let table = $('.foreclosed_table').DataTable({
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
        ajax: "{{ route('foreclosed.index') }}",
        columns: [
            {data: 'ticket_number', name: 'ticket_number'},
            {data: 'transaction_date', name: 'transaction_date', className: 'desktop tablet-l tablet-p'},
            {data: 'customer', name: 'customer', responsivePriority: 0, className: 'all'},
            {data: 'principal', name: 'principal', responsivePriority: 0, className: 'all'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        drawCallback: function( settings ) {
          console.log("Test");
            $('.foreclosed').popover({
                    html: true,
                    title : 'Warning',
                    trigger: 'manual',
                    container: 'body',

                    content: function () {
                        return '<h6>Are you sure you want to foreclose this item?</h6><div><button class="btn btn-sm float-right btn-danger send_foreclose" id="'+$(this).attr('id')+'">Foreclose</button></div>';
                    }
                }).click(function(e) {
                    $(this).popover('toggle');
                    $('.foreclosed').not(this).popover('hide');

                    e.stopPropagation();
                });
                $('.auction').popover({
                                  html: true,
                                  title : 'Auction',
                                  trigger: 'manual',
                                  container: 'body',
                                  content: function () {
                                      return '<form method="POST" onSubmit="auctionForm(event, this)" ><input type="text" name="inventory_auction_number" class="form-control" placeholder="Auction Inventory Code" required/>'+
                                      '@csrf'+
                                      '<input type="number" name="price" class="form-control" placeholder="Cost" required/>'+
                                      '<input type="hidden" name="id" class="form-control" value="'+$(this).attr('id')+'"/>'+
                                      '<div><button type="submit" class="btn btn-sm float-right btn-danger">Auction</button></div>'+
                                      '</form>';
                                  }
                              }).click(function(e) {
                                  $(this).popover('toggle');
                                  $('.auction').not(this).popover('hide');
                                  e.stopPropagation();
                              });


                $('body').on('click', function (e) {
                    $('.foreclosed, .auction').each(function () {
                        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                            $(this).popover('hide');
                        }
                    });
                });

        },
        'createdRow': function( row, data, dataIndex ) {
             $(row).addClass('table_row');
          }

    });

    // console.log(table.state());
    $(document).on('click', '.send_foreclose', function(){
        const pawn_id = $(this).attr('id');
        const url =  '/pawn_auction/foreclosed/'+pawn_id; 
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          type: "POST",
          url : url,
          data: {
            "_method": 'PUT'
          },
          success: (data) => {
            // console.log(data);
            if(data['status'] == 'success'){
            //   location.reload();
                 $('.foreclosed').popover('hide');
                 tableFunction();

            }
            
          }
        })
    });

    function format ( d ) {
            return '<table cellpadding="1" cellspacing="0" border="0">'+
                '<tr class="child_row">'+
                    '<td>Maturity:</td>'+
                    '<td>'+d.maturity_date+'</td>'+
                    '<td rowspan="3">Details:</td>'+
                    '<td rowspan="3">'+d.item+'</td>'+
                  '</tr>'+
                  '<tr  class="child_row">'+
                    '<td>Expiration:</td>'+
                    '<td>'+d.expiration_date+'</td>'+
                  '</tr>'+
                  '<tr  class="child_row">'+
                    '<td>Auction:</td>'+
                    '<td>'+d.auction_date+'</td>'+
                  '</tr>'+
         
            '</table>';
      }
      $(document).on('click', '.foreclosed_table .table_row', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    });
    

      });



     
</script>
@endpush