<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css">
    <link rel="stylesheet" href="/css/material/material-dashboard-pro.min.css">
    <link rel="stylesheet" href="/css/material/material.min.css">
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/datepicker.css">
    <title>Ordinario Pawnshop</title>
</head>
<body>
<!-- sidebar -->
<div class="wrapper">
    <div class="sidebar" data-color="danger" data-background-color="white">
      <!-- data-color="purple | azure | green | orange | danger" data-image="../assets/img/ordinario.jpg" -->
      <div class="logo">
        <a class="simple-text logo-normal">
          <img style="width:90%;" src="/img/Ordinario_Pawnshop_Logo.png">
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('dashboard') }}">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ class_basename(Route::current()->controller) == 'PawnAuctionController' ? '' : 'collapsed'  }}" data-toggle="collapse" href="#pagesExamples" 
              aria-expanded="{{ class_basename(Route::current()->controller) == 'PawnAuctionController' ? 'true' : 'false' }}">
              <i class="material-icons">star</i>
              <p> Pawn & Auction
                <b class="caret"></b>
              </p>   

            </a>
            <div class="collapse {{ class_basename(Route::current()->controller) == 'PawnAuctionController' ? 'show' : ''  }} " id="pagesExamples">
              <ul class="nav">
                <li class="nav-item {{ Route::currentRouteName() == 'pawn' ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('pawn') }}">
                    <span class="sidebar-mini"> <i class="material-icons">star</i> </span>
                    <span class="sidebar-normal"> Sangla </span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="">
                    <span class="sidebar-mini"> <i class="material-icons">star</i> </span>
                    <span class="sidebar-normal"> Renew & Redeem </span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="">
                    <span class="sidebar-mini"> <i class="material-icons">star</i> </span>
                    <span class="sidebar-normal"> Auction </span>
                  </a>
                </li>
              </ul>
            </div>
          </li>

          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#reports" 
              aria-expanded="">
              <i class="material-icons">build</i>
              <p> Reports
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse" id="reports" style="">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="">
                    <span class="sidebar-mini"> <i class="material-icons">book</i> </span>
                    <span class="sidebar-normal"> Notice Listing </span>
                  </a>
                </li>


              </ul>
            </div>
          </li>

          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#settings" 
              aria-expanded="false">
              <i class="material-icons">build</i>
              <p> Settings
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse" id="settings" style="">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="">
                    <span class="sidebar-mini"> <i class="material-icons">book</i> </span>
                    <span class="sidebar-normal"> Karat Rates </span>
                  </a>
                </li>


              </ul>
            </div>
          </li>

        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Start of Navigation -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="#pablo">
                
            </a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="#pablo">
                  <i class="material-icons">dashboard</i>
                  <p class="d-lg-none d-md-block">
                    Stats
                  </p>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">notifications</i>
                  <span class="notification">0</span>
                  <p class="d-lg-none d-md-block">Notifications</p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="#">Notification</a>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="#">Profile</a>
                  <a class="dropdown-item" href="#">Settings</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="../../">Log out</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>

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
<script>
    $(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function() {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function() {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function() {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });

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
    /*
  $(document).ready(function(){
    var sampleTable = $('#dataTableExample').DataTable({
      columnDefs: [
            {
                targets: [ 0, 1, 2,3,4,5 ],
                className: 'mdl-data-table__cell--non-numeric'
            }
        ]
    });
    $('#datatbl tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input class="form-control" type="text" placeholder="Search '+title+'" />' );
    } );
    var table = $('#datatbl').DataTable({
      "lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
      "processing": true,
      "serverSide": true,
      "ajax":"../pages/scripts/datatables.php?data_type=<?=isset($_GET['sub']) ? $_GET['sub']:'' ;?>",
      "columnDefs": [{
        "data":null,
        "render":function(data, type, row, meta){
            return data[-1];
        }},
        {
          targets: [ -1, 0, 1, 2 ],
          className: 'mdl-data-table__cell--non-numeric'
        },
        {
          "targets":-1,
          "data":null,
            "render":function(data, type, row, meta){
            <?php 
              // include("actions.php");
               ?>
        }
        }]
    });
    table.columns().every( function () {
      var that = this;

      $( 'input', this.footer() ).on( 'keyup change clear', function () {
        if ( that.search() !== this.value ) {
            that
                .search( this.value )
                .draw();
        }
      } );
    } );
    $('#datatbl tbody').on( 'click', 'button', function () {
      var data = table.row( $(this).parents('tr') ).data();
    });
  });
      */
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

 });
</script>
</html>