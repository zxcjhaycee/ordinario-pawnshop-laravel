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
<script src="/js/material/plugins/dataTables.material.min.js"></script>
<script src="/js/material/plugins/jquery.dataTables-2.min.js"></script>
@stack('scripts')

<script>
 
    $(document).ready(function(){
      $(document).on('click', '#showPassword', function(){
        let state = $(this).attr('data-id');
        if(state == 'off'){
          // $(this).data('id', 'on');
          $(this).attr('data-id', 'on');
          $('#password').attr('type', 'text');
          $(this).html('<i class="fas fa-eye-slash"></i>');
        }else{
          // $(this).data('id', 'off');
          $(this).attr('data-id', 'off');
          $('#password').attr('type', 'password');
          $(this).html('<i class="fas fa-eye"></i>');
        }
      });
    });

    $(document).ready(function(){
      $('#next1').on('click', function(){
          $('.batch1').toggle("slide");
          $('.batch2').toggle("slide");
          $('.progress-bar').css('width', '25%');
          $('.progress-bar').text('25%')
      });
      $('#back1').on('click', function(){
          $('.batch2').toggle("slide");
          $('.batch1').toggle("slide");
          $('.progress-bar').css('width', '0%');
          $('.progress-bar').text('0%')
      });
      $('#next2').on('click', function(){
          // $('.batch1').toggle("slide");
          $('.batch2').toggle("slide");
          $('.batch3').toggle("slide");
          $('.progress-bar').css('width', '50%');
          $('.progress-bar').text('50%')
      });
      $('#back2').on('click', function(){
          // $('.batch1').toggle("slide");
          $('.batch3').toggle("slide");
          $('.batch2').toggle("slide");
          $('.progress-bar').css('width', '25%');
          $('.progress-bar').text('25%')
      });
      $('#next3').on('click', function(){
          $('.batch4').toggle("slide");
          $('.batch3').toggle("slide");
          $('.progress-bar').css('width', '75%');
          $('.progress-bar').text('75%')
      });
      $('#back3').on('click', function(){
          $('.batch3').toggle("slide");
          $('.batch4').toggle("slide");
          $('.progress-bar').css('width', '50%');
          $('.progress-bar').text('50%')
      });
      $('#add_pawn').on('click', function(){
          // $('.batch3').toggle("slide");
          // $('.batch4').toggle("slide");
          $('.progress-bar').css('width', '100%');
          $('.progress-bar').text('100%')
      });
    });

    jQuery(document).ready(function($) {
      var alterClass = function() {
        var ww = document.body.clientWidth;
        if (ww < 700) {
          $('.mobile_resize').removeClass('row');
          $('.mobile_resize').addClass('container');
          $('.dt-mobile_resize3').removeClass('mdl-cell--3-col');
          $('.dt-mobile_resize3').addClass('mdl-cell--3-col-phone');
          $('.dt-mobile_resize4').removeClass('mdl-cell--4-col');
          $('.dt-mobile_resize4').addClass('mdl-cell--4-col-phone');
          $('.dt-mobile_resize6').removeClass('mdl-cell--6-col');
          $('.dt-mobile_resize6').addClass('mdl-cell--6-col-phone');
          $('.dt-mobile_resize8').removeClass('mdl-cell--8-col');
          $('.dt-mobile_resize8').addClass('mdl-cell--8-col-phone');
        } else if (ww >= 701) {
          $('.mobile_resize').removeClass('container');
          $('.mobile_resize').addClass('row');
          $('.dt-mobile_resize3').addClass('mdl-cell--3-col');
          $('.dt-mobile_resize3').removeClass('mdl-cell--3-col-phone');
          $('.dt-mobile_resize4').addClass('mdl-cell--4-col');
          $('.dt-mobile_resize4').removeClass('mdl-cell--4-col-phone');
          $('.dt-mobile_resize6').addClass('mdl-cell--6-col');
          $('.dt-mobile_resize6').removeClass('mdl-cell--6-col-phone');
          $('.dt-mobile_resize8').addClass('mdl-cell--8-col');
          $('.dt-mobile_resize8').removeClass('mdl-cell--8-col-phone');
        };
      };
      $(window).resize(function(){
        alterClass();
      });
      //Fire it when the page first loads:
      alterClass();
    });

    
    function transaction_dates(element){
      // var date = document.getElementById("transaction_date").value;
      var date = element.value;
      // alert(date.length);
      var days = parseInt(30);
      var newdate = new Date(date);
      newdate.setDate(newdate.getDate() + days);
      
      var dd = newdate.getDate();
      var mm = newdate.getMonth() + 1;
      var y = newdate.getFullYear();
      dd = dd.toString().length == 1 ? "0"+dd : dd;
      mm = mm.toString().length == 1 ? "0"+mm : mm;
      var someFormattedDate = mm + '/' + dd + '/' + y;
    
      // expiration date
      var days_2 = parseInt(120);
      var newdate_2 = new Date(date);
      newdate_2.setDate(newdate_2.getDate() + days_2);
      
      var dd_2 = newdate_2.getDate();
      var mm_2 = newdate_2.getMonth() + 1;
      var y_2 = newdate_2.getFullYear();
      dd_2 = dd_2.toString().length == 1 ? "0"+dd_2 : dd_2;
      mm_2 = mm_2.toString().length == 1 ? "0"+mm_2 : mm_2;
      var someFormattedDate_2 = mm_2 + '/' + dd_2 + '/' + y_2;

      // auction date
      var days_3 = parseInt(180);
      var newdate_3 = new Date(date);
      newdate_3.setDate(newdate_3.getDate() + days_3);
      
      var dd_3 = newdate_3.getDate();
      var mm_3 = newdate_3.getMonth() + 1;
      var y_3 = newdate_3.getFullYear();
      dd_3 = dd_3.toString().length == 1 ? "0"+dd_3 : dd_3;
      mm_3 = mm_3.toString().length == 1 ? "0"+mm_3 : mm_3;
      var someFormattedDate_3 = mm_3 + '/' + dd_3 + '/' + y_3;

      if(someFormattedDate != 'NaN/NaN/NaN'){
        document.getElementById("maturity_date").value = someFormattedDate;
        document.getElementById("expiration_date").value = someFormattedDate_2;
        document.getElementById("auction_date").value = someFormattedDate_3;
      } else {
        document.getElementById("maturity_date").value = '';
        document.getElementById("expiration_date").value = '';
        document.getElementById("auction_date").value = '';
      }
    }

    $(document).on('click', '#addTable', function() {
      $("#itemTable").append('<table class="table table-striped" width="100%">'+
        '<thead>'+
          '<tr>'+
            '<td>Material <br> Item Type <br> Karat</td>'+
            '<td>Weight (g)</td>'+
            '<td>Rate Appraised Value</td>'+
            '<td>Description</td>'+
            '<td>Image</td>'+
            '<td></td>'+
          '</tr>'+
        '</thead>'+
        '<tbody>'+
        '<tr>'+
          '<td>'+
            '<select name="item_type" class="form-control">'+
              '<option></option>'+
              '<option value="Gold">Gold</option>'+
              '<option value="Silver">Silver</option>'+
            '</select>'+
          '</td>'+
          '<td>'+
            '<input class="form-control" type="number" name="weight" value="0.00">'+
          '</td>'+
          '<td>'+
            '<input class="form-control" type="number" name="rate_appraised" value="0">'+
          '</td>'+
          '<td rowspan=3>'+
            '<textarea class="form-control" rows="5" name="description" id="description"></textarea> <br>'+
            '<button id="addDiamond" class="btn btn-warning btn-sm" type="button">ADD DIAMOND</button>'+
          '</td>'+
          '<td rowspan=3>'+
            '<label class="btn btn-success btn-sm float-left-jc">'+
              '<i class="material-icons">arrow_upward</i> Upload Image <input type="file" hidden>'+
            '</label>'+
          '</td>'+
          '<td rowspan=3>'+
            '<i class="material-icons remove" style="cursor: pointer;">cancel</i>'+
          '</td>'+
        '</tr>'+
        
        '<tr>'+
          '<td>'+
            '<select name="item_type" class="form-control">'+
                '<option></option>'+
                '<option value="Ring">Ring</option>'+
                '<option value="Necklace">Necklace</option>'+
                '<option value="Bracelet">Bracelet</option>'+
              '</select>'+
          '</td>'+
          '<td>'+
            '<input class="form-control" type="number" name="weight" value="0.00">'+
          '</td>'+
          '<td>'+
            '<input class="form-control" type="number" name="rate_appraised_value" value="0">'+
          '</td>'+
        '</tr>'+
        
        '<tr>'+
          '<td>'+
            '<select name="item_type" class="form-control">'+
                '<option></option>'+
                '<option value="20">20</option>'+
                '<option value="21">21</option>'+
                '<option value="22">22</option>'+
                '<option value="23">23</option>'+
                '<option value="24">24</option>'+
              '</select>'+
          '</td>'+
          '<td>'+
            '<input class="form-control" type="number" name="weight" value="0.00">'+
          '</td>'+
          '<td>'+
          '</td>'+
        '</tr>'+
        '</tbody>'+
        '</table>');
    });
    $('#itemTable').on('click','.remove',function() {
      $(this).closest('table').remove();
    });
  $(document).ready(function(){
    $('.air_date_picker').datepicker({
    language: 'en',
    todayButton: new Date(),
    autoClose : true,
    position: "bottom center"
    // inline : true,
    // minDate: new Date() // Now can select only dates, which goes after today
});
// $("#selectpicker").selectpicker();

  $(document).on('click', '.addButton', function(){
    // console.log($(this).attr('data-id'));
    let counter = parseInt($(this).attr('data-id')) + 1;
    // console.log($(this).closest('section').attr('id'));
    $(this).css('display', 'none');
    $(this).parent().parent().parent().append('<section id="'+counter+'">'+
                '<div class="form-group row">'+
                  '<label class="col-xl-3 mt-3">Other Charges</label>'+
                    '<div class="col-xl-6 col-lg-8 col-md-9 col-sm-8 col-12 mt-3 pb-3 mx-auto-jc">'+ 
                      '<select class="form-control col-charges">'+
                        '<option></option>'+
                      '</select>'+
                    '</div>'+
                '</div>'+
                '<div class="row">'+
                '<label class="col-xl-3 mt-3 ml-3">Amount</label>'+
                '<div class="col-xl-12-jc-amount col-lg-7-jc-amount col-md-7-jc-amount col-sm-7-jc-amount col-7-jc-amount mx-auto-jc" style="height:0px">'+
                    '<input class="form-control" type="number" value="0">'+
                  '</div>'+
                    '<button type="button" class="btn btn-success btn-sm px-1 py-2 addButton" data-id="'+counter+'"><i class="material-icons">add</i></button>'+
                    '<button type="button" class="btn btn-danger btn-sm px-1 py-2 removeButton" data-id="'+counter+'"><i class="material-icons">remove</i></button>'+
                '</div>'+                
                '</section>');
  });

 $(document).on('click', '.removeButton', function(){
   let counter = $(this).attr('data-id');
   const prev = $(this).closest('section').prev().attr('id');
  if($('.addButton[data-id='+counter+']').css('display') == 'block'){
    $('.addButton[data-id='+prev+']').css('display', 'block');
  }
  $(this).closest('section').remove();

 });

 let counter = 1;
  $(document).on('click', '.add_section', function(){
    // let counter = 0;
    // let counterTwo = ++counter;    
    $('.attachment_section:last').after('<section class="attachment_section">'+
                        '<div class="card">'+
                            '<div class="card-header card-header-text card-header-primary">'+
                                '<div class="card-text">'+
                                '<h4 class="card-title">Attachment</h4>'+
                                '</div>'+
                                '<button type="button" class="btn btn-sm btn-danger pull-right remove_section" data-id="'+counter+'"><span class="material-icons">remove_circle</span></button>'+

                            '</div>'+
                            '<div class="card-body card-body-form">'+
                            '<div class="row d-flex justify-content-center">'+
                                '<label for="attachment_type" class="col-xl-3 col-lg-3 col-md-2 col-sm-4 ">Type: </label>'+
                                '<div class="col-xl-8 col-lg-5 col-md-5 col-sm-7 attachment_type_'+counter+'_error"  style="top:-20px;">'+
                                    '<div class="form-group input @error("attachment_type") has-error is-focused @enderror">'+
                                        '<select name="attachment_type['+counter+']" id="attachment_type'+counter+'" class="form-control attachment_type_select">'+
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
                                     '<label class="text-danger">{{ $message }}</label>'
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

                    $(".attachment_type_select").select2({
                      width: '100%',
                      ajax: {
                        url: '{{ route('attachment.search') }}',
                        data: function (params) {
                          return {
                            search: params.term,
                          }
                        // Query parameters will be ?search=[term]&type=public
                        },
                        processResults: function (data) {
                          return {
                            results : data
                          }
                        },
                       }
                    });


          $(".attachment_type_select").on("select2:select", function (e) {
            // console.log(e.params);
            if(e.params.data.text == 'Can\'t find? Add Attachment'){
              window.open('{{ route('attachment.create') }}');
              // console.log("Hello!");
              // console.log($(this).text());
              $(this).val(null).trigger("change");

            }

          });

    counter++;

  });

  $(document).on('click', '.remove_section', function(){
      // let counter = $(this).data('id');
      $(this).parent().closest('section').remove();
      // counter--;

  });

    $(document).on('submit', 'form#customerForm', function(e){
      const checkValue = $('input[name="_method"]').val();
      // const method = checkValue == null ? 'POST' : 'PUT';
      const url = checkValue == null ? '{{ route('customer.store') }}' : '{{ route('customer.update', $data->id ?? '') }}'; 
      $('.error_message').css('display', 'none');
      $('.input').removeClass('has-error is-focused');
      e.preventDefault();
      var form_data = new FormData(this);
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          url : url,
          type : "POST",
          data : form_data,
          cache: false,
          contentType : false,
          processData : false,
          success: (data) => {
            // console.log(data.success);
            if(data.success === true){
              alert_message('Success', data.status);
             location.reload();
            }else{
              alert_message('Danger', 'Error occured. Pleasse contact administrator');

            }

          },
          error: (data) => {
            const error = data.responseJSON.errors;
            for(let errorValue in error){
              if (!error.hasOwnProperty(errorValue)) continue;
                 $('.'+errorValue.replace(/\./g, '_')+'_error').append('<label class="text-danger error_message">'+error[errorValue][0].replace(/\.|[0-9]/g, '')+'</label>');
                  $('.'+errorValue.replace(/\./g, '_')+'_error').find('.input').addClass('has-error is-focused');
                  // console.log('.'+errorValue+'_error');
                  // console.log(errorValue.replace(/\D/g,''));

            }
            // $('.first_name_error').html(error.first_name).css('display','block');
          }
        });
        
    });

    $('.attachment_type_select').select2({
      width: '100%',
      ajax: {
    url: '{{ route('attachment.search') }}',
    data: function (params) {
      return {
        search: params.term,
      }

      // Query parameters will be ?search=[term]&type=public
    },
    processResults: function (data) {
        return {
          results : data
        }
    },

  }
    });
    $(".attachment_type_select").on("select2:select", function (e) {
      // console.log(e.params);
      if(e.params.data.text == 'Can\'t find? Add Attachment'){
        window.open('{{ route('attachment.create') }}');
        // console.log("Hello!");
        // console.log($(this).text());
        $(this).val(null).trigger("change");

      }

    });

    $(document).on('click', '.remove_attachment', function(){
      const id = $(this).data('id');
      let url = '{{ route('customer.delete_attachment', ":id") }}';
      url = url.replace(':id', id);
      const r = confirm("Do you want to continue?");
        if(r == true){
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            type: "DELETE",
            url : url,

            success: (data) => {
              // console.log(data);
              location.reload();
              alert_message('Success', data.status);
              
            }

          })
        }
    });
 });
 
 function alert_message(status, message){
   $('.alert_message').html('<div class="col-xl-7 col-lg-9 col-md-8 col-sm-12 col-12 mx-auto">'+
        '<div class="alert alert-'+status.toLowerCase()+' alert-dismissible fade show text-center" style="font-size:15px" role="alert">'+
            '<strong>'+status+'!</strong> '+message+ 
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                    '<span aria-hidden="true">&times;</span>'+
                '</button>'+
        '</div>'+
    '</div>');
  
 }
</script>
</html>