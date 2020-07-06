<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!-- <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" /> -->
    <link rel="stylesheet" type="text/css" href="/css/googlefont.css" />
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="/css/font-awesome.min.css"> 
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css"> -->
    <!-- <link rel="stylesheet" href="/css/material/material-dashboard.min.css"> -->
    <link rel="stylesheet" href="/css/material/material-dashboard-pro.min.css">
    <!-- <link rel="stylesheet" href="/css/material/material.min.css"> -->
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/datepicker.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <!-- <link rel="stylesheet" href="/css/material/datatables-material.min.css"> -->

    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"> -->
    
    <title>Ordinario Pawnshop</title>
</head>
<body>
<!-- sidebar -->
<div class="wrapper">
    @include('sidebar')
    <div class="main-panel">
   @include('nav')

<!-- end sidebar -->
@yield('content')

</body>
<script src="/js/material/core/jquery-3.3.1.js"></script>
<script src="/js/material/core/popper.min.js"></script>
<script src="/js/material/core/bootstrap-material-design.min.js"></script>
<script src="/js/material/plugins/perfect-scrollbar.jquery.min.js"></script>
<script src="/js/material/material-dashboard.js"></script>
<script src="/js/material/plugins/bootstrap-selectpicker.js"></script>
<script src="/js/material/plugins/moment.min.js"></script>
<script src="/js/material/plugins/jasny-bootstrap.min.js"></script>
<script src="/js/material/plugins/arrive.min.js"></script>
<script src="/js/form.js"></script>
<script src="/js/datepicker.js"></script>
<script src="/js/datepicker.en.js"></script>
<script src="/js/select2.min.js"></script>
<!-- <script src="/js/material/plugins/dataTables.material.min.js"></script> -->
<script src="/js/material/plugins/jquery.dataTables-2.min.js"></script>
<script src="/js/function.js"></script>
@stack('scripts')

<script>
 

 let counter = 1;
 const addAttachment = () => {
    $('.attachment_section:last').after('<section class="attachment_section">'+
                        '<div class="card">'+
                            '<div class="card-header card-header-text card-header-primary">'+
                                '<div class="card-text">'+
                                '<h4 class="card-title">Attachment</h4>'+
                                '</div>'+
                                '<button type="button" class="btn btn-sm btn-danger pull-right remove_section" data-id="'+counter+'" onClick="remove_section(this)"><span class="material-icons">remove_circle</span></button>'+
                            '</div>'+
                            '<div class="card-body card-body-form">'+
                            '<div class="row d-flex justify-content-center">'+
                                '<label for="attachment_type" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Type: </label>'+
                                '<div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 attachment_type_'+counter+'_error"  style="top:-20px;">'+
                                    '<div class="form-group input @error("attachment_type") has-error is-focused @enderror">'+
                                        '<select name="attachment_type['+counter+']" id="attachment_type'+counter+'" class="form-control attachment_type_select'+counter+'">'+
                                          '<option></option>'+
                                        '</select>'+                                     
                                    '</div>'+
                                    '@error("attachment_type")'+
                                     '<label class="text-danger">{{ $message }}</label>'+
                                    '@enderror'+
                                '</div>'+
                            '</div>'+
                            '<div class="row d-flex justify-content-center">'+
                                '<label for="attachment_number" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Number: </label>'+
                                '<div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 attachment_number_'+counter+'_error" style="top:-20px;">'+
                                    '<div class="form-group input @error("attachment_number") has-error is-focused @enderror">'+
                                    '<input type="text" id="attachment_number" name="attachment_number['+counter+']" class="form-control" value="{{ isset($data->attachment_number) && $errors->isEmpty() ? $data->attachment_number : old("attachment_number") }}"/>'+
                       
                                        '<span class="material-icons form-control-feedback">clear</span>'+
                                    '</div>'+
                                    '@error("attachment_number")'+
                                     '<label class="text-danger">{{ $message }}</label>'+
                                    '@enderror'+
                                '</div>'+
                            '</div>'+
                            '<div class="row d-flex justify-content-center">'+
                                '<label for="attachment" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">File: </label>'+
                                '<div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 attachment_'+counter+'_error" style="top:-20px;">'+
                                    '<div class="form-group input @error("attachment") has-error is-focused @enderror">'+
                                                '<input type="file" name="attachment['+counter+']">'+
                                            '<div class="input-group">'+
                                                '<input type="text" name="attachment['+counter+']" readonly="" class="form-control" placeholder="Add Attachment">'+
                                                '<button type="button" class="btn btn-fab btn-fab-mini">'+
                                                     '<i class="material-icons">attach_file</i>'+
                                                '</button>'+
                                            '</div>'+
                                        
                                    '</div>'+
                                    '@error("attachment")'+
                                     '<label class="text-danger">{{ $message }}</label>'+
                                    '@enderror'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+     
                    '</section>');

      const attachment = document.querySelector('.attachment_type_select'+counter);
      const route = '{{ route('attachment.search') }}';
      select2Initialized(attachment, route);    
      $(".attachment_type_select"+counter).on("select2:select", function (e) {
      if(e.params.data.text == 'Can\'t find? Add Attachment'){
        window.open('{{ route('attachment.create') }}');
        $(this).val(null).trigger("change");

      }

    });
      counter++;


  }

  $(document).on('click', '.remove', function(){
    const id = $(this).attr('id');
    const name = $(this).data('name');
    let url = name+'/'+id;
    // console.log("hello!");
    const r = confirm("Do you want to continue?");
      if(r == true){
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          type: "POST",
          url : url,
          data: {
            "_method": 'DELETE'
          },
          success: (data) => {
            console.log(data);
            // location.reload();
            alert_message('Success', data.status);
            if(data.reload === true){
              location.reload();
            }

            if(data['inventory_item'] === true){
              $(this).closest('table').remove();
              setAppraisedComputation();
            }
            if(data['other_charges'] === true){
              removeTr(this);
            }
            tableFunction();
            
          }

        })
      }
  });  

  
</script>
</html>