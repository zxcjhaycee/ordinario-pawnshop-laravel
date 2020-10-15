@extends('layout')
@section('title', 'Cashflow Statement')

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
#cash_count td{
  padding-top: 3px;
  padding-bottom: 3px;
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
                  <h4 class="card-title">Cash Flow Statement</h4>
                </div>
                <div class="card-body">
                <div class="row">
                    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                      <form action="{{ route('cash_flow.submit') }}" method="post" class="row">
                        @csrf
                          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                            <input type="text" name="date" id="date"  class="form-control cashflow_picker"  value="{{ $date }}" autocomplete="off">
                          </div>
                          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                            <div class="dropdown bootstrap-select show-tick">
                              <select class="selectpicker" data-size="7" data-style="select-with-transition" title="Branch" tabindex="-98" name="branch_id">
                                  @foreach($branch as $branches)
                                    <option value="{{ $branches->id }}" {{ $branch_selected == $branches->id ? 'selected' : '' }}>{{ $branches->branch }}</option>
                                  @endforeach
                            </select>
                            </div>
                          </div>
                          <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12 mt-2 text-center-jc">
                            <button type="submit" class="btn btn-default btn-sm-jc"><i class="material-icons">search</i></button>
                          </div>
                          </form>
                      </div>

                </div>
                @include('alert')

                <div class="container row">
                  <div class="col-xl-6">
                      <h3>Inflows</h3>
                      <table class="table table-striped">
                          <tr>
                            <th>Fund Tranfer IN</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Redeemed Loans (Jewelry)</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Redeemed Loans (Non-Jewelry)</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Loan Penalty</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Interests</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Service Charge</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Service Charge</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Other Charges</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Auction Sales</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Total Inflows</th>
                            <td></td>
                          </tr>
                      </table>
                      <h3>Outflows</h3>
                      <table class="table table-striped">
                          <tr>
                            <th>Fund Transfer OUT</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>New Loans (Jewelry)</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>New Loans (Non-Jewelry)</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Expenses</th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Total Outflows</th>
                            <td></td>
                          </tr>
                      </table>
                      <table class="table table-striped">
                        <tr>
                          <th>ENDING CASH BALANCE</th>
                          <td></td>
                        </tr>
                        <tr>
                          <th>Cash Count</th>
                          <td></td>
                        </tr>
                        <tr>
                          <th>Overage</th>
                          <td></td>
                        </tr>
                      </table>
                  </div>
                  <div class="col-xl-6">
                  <form method="POST" action="{{ isset($cashFlow) ? route('cash_flow.update', ['cashFlow' => $cashFlow->id]) : route('cash_flow.store') }}">
                      @csrf
                      @isset($cashFlow)
                          @method('PUT')
                      @endisset
                    <h3>Cash Count</h3>
                    <table class="table table-striped" id="cash_count">
                      <tr>
                        <thead>
                         <th>Denomination</th>
                         <th>Count</th>
                         <th>Subtotal</th>
                       </thead>
                     </tr>
                     <tbody>
                      <tr>
                        <td>1000</td>
                         <td style="width:20%"><input class="form-control" id="1000" name="denomination[one_thousand][count]" onChange="calculateAmount(this)" value="{{ isset($cashFlow->cashFlowsDetails) ? $cashFlow->cashFlowsDetails->where('denomination', 'one_thousand')->first()->count ?? 0 : 0 }}"></td>
                        <td><input class="form-control subtotal" name="denomination[one_thousand][subtotal]" readonly value="{{ isset($cashFlow->cashFlowsDetails) ? $cashFlow->cashFlowsDetails->where('denomination', 'one_thousand')->first()->subtotal ?? 0 : 0 }}"> </td>
                      </tr>
                      <tr>
                        <td>500</td>
                        <td style="width:20%"><input class="form-control" id="500" name="denomination[five_hundred][count]" onChange="calculateAmount(this)" value="{{ isset($cashFlow->cashFlowsDetails) ? $cashFlow->cashFlowsDetails->where('denomination', 'five_hundred')->first()->count ?? 0 : 0 }}"></td>
                        <td><input class="form-control subtotal" name="denomination[five_hundred][subtotal]" readonly value="{{ isset($cashFlow->cashFlowsDetails) ? $cashFlow->cashFlowsDetails->where('denomination', 'five_hundred')->first()->subtotal ?? 0 : 0 }}"> </td>
                      </tr>
                      <tr>
                        <td>200</td>
                        <td style="width:20%"><input class="form-control" id="200" name="denomination[two_hundred][count]" onChange="calculateAmount(this)" value="{{ isset($cashFlow->cashFlowsDetails) ? $cashFlow->cashFlowsDetails->where('denomination', 'two_hundred')->first()->count ?? 0 : 0 }}"></td>
                        <td><input class="form-control subtotal" name="denomination[two_hundred][subtotal]" readonly value="{{ isset($cashFlow->cashFlowsDetails) ? $cashFlow->cashFlowsDetails->where('denomination', 'two_hundred')->first()->subtotal ?? 0 : 0 }}"> </td>
                      </tr>
                      <tr>
                        <td>100</td>
                        <td style="width:20%"><input class="form-control" id="100" name="denomination[one_hundred][count]" onChange="calculateAmount(this)" value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'one_hundred')->first()->count ?? 0 : 0 }}"></td>
                        <td><input class="form-control subtotal" name="denomination[one_hundred][subtotal]" readonly value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'one_hundred')->first()->subtotal ?? 0 : 0 }}"> </td>
                      </tr>
                      <tr>
                        <td>50</td>
                        <td style="width:20%"><input class="form-control" id="50" name="denomination[fifty][count]" onChange="calculateAmount(this)" value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'fifty')->first()->count ?? 0 : 0 }}"></td>
                        <td><input class="form-control subtotal" name="denomination[fifty][subtotal]" readonly value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'fifty')->first()->subtotal ?? 0 : 0 }}"> </td>
                      </tr>
                      <tr>
                        <td>20</td>
                        <td style="width:20%"><input class="form-control" id="20" name="denomination[twenty][count]" onChange="calculateAmount(this)" value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'twenty')->first()->count ?? 0 : 0 }}"></td>
                        <td><input class="form-control subtotal" name="denomination[twenty][subtotal]" readonly value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'twenty')->first()->subtotal ?? 0 : 0 }}"> </td>
                      </tr>
                      <tr>
                        <td>10</td>
                        <td style="width:20%"><input class="form-control" id="10" name="denomination[ten][count]" onChange="calculateAmount(this)" value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'ten')->first()->count ?? 0 : 0 }}"></td>
                        <td><input class="form-control subtotal" name="denomination[ten][subtotal]" readonly  value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'ten')->first()->subtotal ?? 0 : 0 }}"> </td>
                      </tr>
                      <tr>
                        <td>5</td>
                        <td style="width:20%"><input class="form-control" id="5" name="denomination[five][count]" onChange="calculateAmount(this)" value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'five')->first()->count ?? 0 : 0 }}"></td>
                        <td><input class="form-control subtotal" name="denomination[five][subtotal]" readonly value="{{  isset($cashFlow->cashFlowsDetails) ? $cashFlow->cashFlowsDetails->where('denomination', 'five')->first()->subtotal ?? 0 : 0 }}"> </td>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td style="width:20%"><input class="form-control" id="1" name="denomination[one][count]" onChange="calculateAmount(this)" value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'one')->first()->count ?? 0 : 0 }}"></td>
                        <td><input class="form-control subtotal" name="denomination[one][subtotal]" readonly value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'one')->first()->subtotal ?? 0 : 0 }}"> </td>
                      </tr>
                      <tr>
                        <td>0.25</td>
                        <td style="width:20%"><input class="form-control" id="0.25" name="denomination[twenty_five_cents][count]" onChange="calculateAmount(this)" value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'twenty_five_cents')->first()->count ?? 0 : 0 }}"></td>
                        <td><input class="form-control subtotal" name="denomination[twenty_five_cents][subtotal]" readonly value="{{ isset($cashFlow->cashFlowsDetails) ?  $cashFlow->cashFlowsDetails->where('denomination', 'twenty_five_cents')->first()->subtotal ?? 0 : 0 }}"> </td>
                      </tr>
                      <tr>
                        <th colspan="2">Total</th>
                        <td><input class="form-control" name="grand_total" id="grand_total" readonly value="{{ isset($cashFlow) ? $cashFlow->grand_total : 0 }}"> </td>
                      </tr>
                      <tr>
                        <th colspan="2"></th>
                        <td class="form-inline"><input class="form-control" name="auth_code" placeholder="Auth Code" required style="width:15em">
                          <input type="hidden" name="date" value="{{ $date }}">
                          <input type="hidden" name="branch_id" value="{{ $branch_selected }}">
                          <button type="submit" class="btn btn-success btn-sm">Save</button> </td>
                      </tr>
                    </tbody>

                    </table>
                    </form>
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

@push('scripts')

<script type="text/javascript">
  const cashflow_picker = $('.cashflow_picker').datepicker({
    language: 'en',
    autoClose : true,
    position: "bottom center",
    // inline : true,
    // minDate: new Date() // Now can select only dates, which goes after today
  });
  cashflow_picker.data('datepicker').selectDate(new Date($('.cashflow_picker').val()));

function calculateAmount(element){
  const value = element.id;
  const count = element.value;
  const subtotal = element.closest('td').nextElementSibling.querySelectorAll('.subtotal')[0];
  subtotal.value = parseFloat(count * value);
  const subtotal_all = document.querySelectorAll('.subtotal');
  const grand_total = document.getElementById('grand_total');
  let grand_total_init = 0;
  // console.log(subtotal_all);
  for(let i = 0; i < subtotal_all.length; i++){
    grand_total_init += parseFloat(subtotal_all[i].value);
    // console.log(subtotal_all[i].value);
  }
  grand_total.value = parseFloat(grand_total_init).toFixed(2);

}
</script>
@endpush