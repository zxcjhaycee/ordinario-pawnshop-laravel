@extends('layout')

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
                    <div class="col-xl-7 col-lg-10 col-md-10 col-sm-12 col-12 mb-4 row">
                          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <input type="text" name="notice_month_year" data-id=""  data-min-view="months" data-view="months" data-date-format="MM yyyy" class="form-control air_date_picker" placeholder="Month / Year">
                          </div>
                          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="dropdown bootstrap-select show-tick">
                              <select class="selectpicker" data-size="7" data-style="select-with-transition" title="Branch" tabindex="-98">
                                <option>Manila</option>
                                <option>Daet</option>
                            </select>
                            </div>
                          </div>
                          <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mt-2 text-center-jc">
                            <button type="button" class="btn btn-default btn-sm-jc"><i class="material-icons">search</i></button>
                          </div>
                      </div>

                      <div class="col-xl-5 col-lg-10 col-md-10 col-sm-12 col-12 mb-4">
                        <form action="{{ route('notice_listing_print') }}" method="GET" target="_blank" class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <input type="text" name="jewelry_date" data-id="" class="form-control air_date_picker" placeholder="Jewelry">
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <input type="text" name="non_jewelry_date" data-id="" class="form-control air_date_picker" placeholder="Non-Jewelry">
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mt-2 text-center-jc">
                                <!-- <a href="reports/notice_listing_print" target="_blank" class="btn btn-default btn-warning btn-sm-jc"><i class="material-icons">archive</i></a> -->
                                <button type="submit" class="btn btn-default btn-warning btn-sm-jc"><i class="material-icons">archive</i></button>
                                <!-- change to button if finalized -->
                            </div>
                        </form>


                      </div>
                </div>

                <div class="row">
                  <div class="col-xl-12 table-responsive">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pawn Ticket</th>
                                <th>Customer</th>
                                <th>Last Transaction Date</th>
                                <th>Address</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Tiger Nixon</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td>
                                <td>2011/04/25</td>
                                <td>$320,800</td>
                            </tr>
                            <tr>
                                <td>Garrett Winters</td>
                                <td>Accountant</td>
                                <td>Tokyo</td>
                                <td>63</td>
                                <td>2011/07/25</td>
                                <td>$170,750</td>
                            </tr>
                            <tr>
                                <td>Ashton Cox</td>
                                <td>Junior Technical Author</td>
                                <td>San Francisco</td>
                                <td>66</td>
                                <td>2009/01/12</td>
                                <td>$86,000</td>
                            </tr>
                            <tr>
                                <td>Cedric Kelly</td>
                                <td>Senior Javascript Developer</td>
                                <td>Edinburgh</td>
                                <td>22</td>
                                <td>2012/03/29</td>
                                <td>$433,060</td>
                            </tr>
                            <tr>
                                <td>Airi Satou</td>
                                <td>Accountant</td>
                                <td>Tokyo</td>
                                <td>33</td>
                                <td>2008/11/28</td>
                                <td>$162,700</td>
                            </tr>
                            <tr>
                                <td>Brielle Williamson</td>
                                <td>Integration Specialist</td>
                                <td>New York</td>
                                <td>61</td>
                                <td>2012/12/02</td>
                                <td>$372,000</td>
                            </tr>

                        </tbody>
                    </table>
                  </div>
                </div>
                <!-- end content-->
              </div>
              <!--  end card  -->
            </div>
    <!-- </div> -->
  <!-- </div> -->
</div>
@endsection