@extends('layout')

@section('content')
<style>
    @media(min-width: 576px){
      .float-left-jc{
        float: left;
      }
      .float-right-mey{
        float: right;
      }
      .col-sm-7-jc-amount{
        flex:0 0 61.478%;
        margin-left:18.5%;
      }
      .col-sm-7-jc{
        flex:0 0 62.77777%;
        margin-left:21%;
      }
            


    }
    @media(max-width: 576px){
      .btn-small {
        padding: .25rem .5rem;
        font-size: .875rem;
        line-height: 1.5;
        border-radius: .2rem;
      }
      .margin-small{
        margin: 1rem 0;
      }
      .float-right-mey{
        float: right;
      }

      .col-7-jc-amount{
        flex:0 0 67.2%;
        margin-left:5%;
      }

      .col-7-jc{
        flex:0 0 84.77777%;
        margin-left:11.2%;
      }
      
      .col-charges{
        margin-left:-15px;
      }
      
    }
    
    @media (min-width: 768px){
      .col-md-7-jc{
        flex:0 0 61.77777%;
        margin-left:0.2rem;
      }
      .col-md-7-jc-amount{
        flex:0 0 61.478%;
        margin-left:2.3rem;
      }

    }


    @media (max-width: 768px) {
    .text-md-center-jc {
      text-align: center !important;
    }
    .col-charges{
        margin-left:-15px;
      }
    /* .mx-auto-jc {
      margin-right: auto !important;
      margin-left: auto !important;
    } */
    .mx-auto-jc:not(.col-sm-7-jc-amount):not(.col-sm-7-jc){
      margin-right: auto !important;
      margin-left: auto !important;
      }
}
@media (min-width: 992px){
      .col-lg-7-jc{
        flex:0 0 61.22222%;
        margin-left:0.3rem;
      }
      .col-lg-7-jc-amount{
        flex:0 0 54.878%;
        margin-left:2.291rem;
      }
  }
    @media(min-width: 1200px){
      .col-xl-12-jc{
        flex:0 0 62.22222%;
        margin-left:0.9%;
      }
      .col-xl-12-jc-amount{
        flex:0 0 42.878%;
        margin-left:0.6rem;
      }

    }


  table{
    text-align: center;
    font-size: 11pt !important;
    padding: 0px !important;
  }
  table td{
    border:1px solid #D2D2D2
  }
  .form-control-jc{
    margin-top : -1.3rem;
  }


</style>
<div class="content">
  <div class="container-fluid">
    <!-- <div class="col-xl-12"> -->
    <!-- <div class="col-xl-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Pawn</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add Pawn</li>
        </ol>
      </nav>
    </div> -->
    <div class="progress-div">
      <div class="progress mb-4" style="height: 15px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated" 
          role="progressbar" style="width: 0%;background-color:#feb81c" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
            0%
        </div>
      </div>
      <div class="card">
        <form action="{{ route('pawn_store') }}" method="POST">
        {{ csrf_field() }}
        <section class="batch1" style="display:none">
          <div class="card-header card-header-primary text-center">
            TICKET DETAILS
          </div> <br>
          <div class="card-body">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 row mobile_resize">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 text-md-center-jc">
                <div class="form-group row pt-2">
                  <label class="col-xl-5 col-lg-12 col-md-12 col-sm-12 col-12">PT #:</label>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12" style="font-size:14px">
                    00001
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-xl-5 pt-2">Transaction Type:</label>
                  <div class="col-xl-6 col-lg-10 col-md-10 col-sm-8 col-12 pb-3 mx-auto-jc">
                    <select name="transaction_type" id="transaction_type" class="form-control" data-style="btn-info">
                      <option disabled="" selected=""></option>
                      <option value="Old">Old</option>
                      <option value="New">New</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row pt-3">
                  <label class="col-xl-5">Processed By:</label>
                  <div class="col-xl-6" style="font-size:14px">
                    Chris Domingo
                  </div>
                </div>
              </div>
              <!-- second column -->
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 text-md-center-jc mt-3">
              <div class="row">
                <label class="col-xl-5">Transaction Date:</label>
                <div class="col-xl-6 col-lg-10 col-md-10 col-sm-8 col-11 mx-auto-jc">

                  <input type="text" name="transaction_date" data-id="" class="form-control form-control-jc air_date_picker" onblur="transaction_dates(this)">
                </div>
              </div>
                <div class="row mt-3">
                  <label class="col-xl-5">Maturity Date:</label>
                  <div class="col-xl-6 col-lg-10 col-md-10 col-sm-8 col-11 mx-auto-jc">
                    <input type="text" name="maturity_date" id="maturity_date" class="form-control form-control-jc"  readonly>
                  </div>
                </div>
                <div class="row mt-3">
                  <label class="col-xl-5">Expiration Date:</label>
                  <div class="col-xl-6 col-lg-10 col-md-10 col-sm-8 col-11 mx-auto-jc">
                    <input type="text" name="expiration_date" id="expiration_date" class="form-control form-control-jc"  readonly>
                  </div>
                </div>
                <div class="row mt-3">
                  <label class="col-xl-5">Auction Date:</label>
                  <div class="col-xl-6 col-lg-10 col-md-10 col-sm-8 col-11 mx-auto-jc">
                    <input type="text" name="auction_date" id="auction_date" class="form-control form-control-jc" readonly>
                  </div>
                </div>
              </div>
            </div>
            <button type="button" class="btn btn-primary float-right-mey" id="next1"><i class="material-icons">arrow_forward</i></button>
          </div>
        </section>
        <!-- Customer Tab -->
        <section class="batch2" style="display:none">
          <div class="card-header card-header-primary text-center">
            CUSTOMER
          </div> <br>
          <div class="card-body">
             <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 row mobile_resize">
             <div class="mx-auto col-xl-6 col-lg-7 col-md-7 col-sm-12 text-md-center-jc ">
                <div class="form-group row">
                <label class="col-xl-4 col-lg-4 col-md-4 mt-2">Name</label>
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-11 pb-4 mx-auto-jc">
                    <select name="customer" id="customer" class="form-control">
                      <option></option>
                      <option value="REGAN INDUSTRIAL SALES INC.">REGAN INDUSTRIAL SALES INC.</option>
                      <option value="SUPREME STEEL PIPE CORP.">SUPREME STEEL PIPE CORP.</option>
                      <option value="KIRIN RESOURCES INC.">KIRIN RESOURCES INC.</option>
                    </select>
                </div>
                </div>
                <div class="form-group row">
                <label class="col-xl-4 col-lg-4 col-md-4 mt-2">ID Presented</label>
                  <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-11 pb-4 mx-auto-jc">
                      <select name="id_type" id="id_type" class="form-control">
                        <option></option>
                        <option value="SSS">SSS</option>
                        <option value="Driver's License">Driver's License</option>
                        <option value="Pag-ibig">Pag-ibig</option>
                        <option value="Postal ID">Postal ID</option>
                        <option value="Passport">Passport</option>
                      </select>
                  </div>
                </div>
                <div class="form-group row">
                <label class="col-xl-4 col-lg-4 col-md-4 mt-2">Country</label>
                  <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-11 pb-4 mx-auto-jc">
                    <select name="country_id" id="country_id" class="form-control">
                      <option disabled="" selected=""></option>
                      <option value="Afghanistan"> Afghanistan </option>
                      <option value="Albania"> Albania </option>
                      <option value="Algeria"> Algeria </option>
                      <option value="American Samoa"> American Samoa </option>
                      <option value="Andorra"> Andorra </option>
                      <option value="Angola"> Angola </option>
                      <option value="Anguilla"> Anguilla </option>
                      <option value="Antarctica"> Antarctica </option>
                    </select>
                  </div>
                </div>
                <div class="row">
                <label class="col-xl-4 col-lg-4 col-md-4 mt-3 ml-3">ID Number:</label>
                  <div class="col-xl-12-jc col-lg-7-jc col-md-7-jc col-sm-7-jc col-7-jc mx-auto-jc">
                     <input type="text" class="form-control" name="id_number" id="id_number">
                  </div>
                </div>
                <div class="form-group row">
                <label class="col-xl-4 col-lg-4 col-md-4 mt-2">ID Image:</label>
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12 mx-auto-jc">
                    <label class="btn btn-success btn-sm">  
                      <i class="material-icons">arrow_upward</i> Upload Image <input type="file" hidden>
                    </label>  
                  </div>
                </div>
              </div>
            </div>
            <button type="button" class="btn btn-primary float-left-jc" id="back1"><i class="material-icons">arrow_back</i></button>
            <button type="button" class="btn btn-primary float-right-mey" id="next2"><i class="material-icons">arrow_forward</i></button>
          </div>
        </section>
        <!-- Item Tab -->
        <section class="batch3" style="display:none">
          <div class="card-header card-header-primary text-center">
            ITEM
          </div> <br>
          <div class="card-body">
            <div class="col-xl-12 row mobile_resize">
              <div class="col-xl-3">
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" id="category_jewelry" name="category" value="jewelry" onclick="getSuki(this)"> Jewelry
                    <span class="circle">
                      <span class="check"></span>
                    </span>
                  </label>
                </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" id="category_non-jewelry" name="category" value="non-jewelry" onclick="getSuki(this)"> Non-Jewelry
                    <span class="circle">
                      <span class="check"></span>
                    </span>
                  </label>
                </div>
              </div>
              <div class="col-xl-6">
                <div id="suki" class="form-check" style="display:none;">
                  <label class="form-check-label">
                    <input id="suki_check" class="form-check-input" type="checkbox" value=""> Suki Rate? (For Jewelry Items)
                    <span class="form-check-sign">
                      <span class="check"></span>
                    </span>
                  </label>
                </div>
              </div>
              <div class="col-xl-12 col-sm-12 container table-responsive" id="itemTable">
              </div>
                     <button id="addTable" class="btn btn-warning btn-sm" type="button">ADD</button>

              <!-- <button type="button" class="btn btn-primary btn-sm mx-auto">Submit</button> -->
            </div>
            <button type="button" class="btn btn-primary float-left-jc" id="back2"><i class="material-icons">arrow_back</i></button>
            <button type="button" class="btn btn-primary float-right-mey" id="next3"><i class="material-icons">arrow_forward</i></button>
          </div>
        </section>
        <!-- Computation Tab -->
        <section class="batch4">
          <div class="card-header card-header-primary text-center">
            COMPUTATION
          </div> <br>
          <div class="card-body">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 row mobile_resize ">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 text-md-center-jc">
                <div class="row">
                <label class="col-xl-4 mt-3">Appraised Value</label>
                    <div class="col-xl-7 col-lg-10 col-md-10 col-sm-8 col-12  mx-auto-jc"> 
                        <input class="form-control" type="number" name="appraised_value" id="appraised_value" value="0">
                    </div>
                </div>
                <div class="row">
                <label class="col-xl-4 mt-3">Principal</label>
                  <div class="col-xl-7 col-lg-10 col-md-10 col-sm-8 col-12 mx-auto-jc"> 
                      <input class="form-control" type="number" name="principal" id="principal" value="0">
                  </div>
                </div>
                <div class="row">
                <label class="col-xl-4 mt-3">Other Charges</label>
                  <div class="col-xl-7 col-lg-10 col-md-10 col-sm-8 col-12 mx-auto-jc"> 
                      <input class="form-control" type="number" name="other_charges" id="other_charges" value="0">
                  </div>
                </div>
                <div class="row">
                 <label class="col-xl-4 mt-3">Net Proceeds</label>
                  <div class="col-xl-7 col-lg-10 col-md-10 col-sm-8 col-12 mx-auto-jc"> 
                    <input class="form-control" type="number" name="net_proceeds" id="net_proceeds" value="0">
                  </div>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 text-md-center-jc">
                <section id="1">
                <div class="form-group row">
                  <label class="col-xl-3 mt-3">Other Charges</label>
                    <div class="col-xl-6 col-lg-8 col-md-9 col-sm-8 col-12 mt-3 pb-3 mx-auto-jc"> 
                      <select class="form-control col-charges">
                        <option></option>
                      </select>
                    </div>
                </div>
                <div class="row">
                <label class="col-xl-3 mt-3 ml-3">Amount</label>
                <div class="col-xl-12-jc-amount col-lg-7-jc-amount col-md-7-jc-amount col-sm-7-jc-amount col-7-jc-amount mx-auto-jc" style="height:0px">
                    <input class="form-control" type="number" value="0">
                  </div>
                    <button type="button" class="btn btn-success btn-sm px-1 py-2 addButton" data-id="1"><i class="material-icons">add</i></button>
                    <!-- <button type="button" class="btn btn-danger btn-sm px-1 py-2"><i class="material-icons">remove</i></button> -->
                </div>
                
                </section>

                
              </div>
            </div>
            <button type="button" class="btn btn-primary float-left-jc" id="back3"><i class="material-icons">arrow_back</i></button>
            <button type="submit" class="btn btn-primary float-right-mey" id="add_pawn"><i class="material-icons">check</i></button>
          </div>
        </section>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  function getSuki(radio){
    if(document.getElementById("category_jewelry").checked){
        document.getElementById("suki").style.display = "block";
        document.getElementById("suki_check").disabled = false;
    }else {
        document.getElementById("suki").style.display = "none";
        document.getElementById("suki_check").disabled = true;
    }
  }


</script>
@endsection