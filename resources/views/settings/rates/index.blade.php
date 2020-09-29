@extends('layout')
@section('title', 'Rates')

@section('content')
<style>
    .table-input {
        width: 70px;
        text-align: right;
    }
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header card-header-success">
                        <h4 class="card-title">Branch & Item Category</h4>
                        <p class="card-category">Select to proceed</p>
                    </div>
                    <div class="card-body" style="min-height: auto;">
                        <form method="GET" action="{{ route('rates.getBranchItem') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="branch_id">Branch {{ $branch_id }}</label>
                                        <select class="selectpicker form-control" name="branch_id" id="branch_id" data-style="btn btn-primary" title="Branch">
                                            @foreach($branch as $key => $value)
                                                <option value="{{ $value->id }}"
                                                    {{ $branch_id == $value->id ? 'selected':''}}>
                                                    {{ $value->branch }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="item_category_id">Item Category {{ $item_category_id }}</label>
                                        <select class="selectpicker form-control" name="item_category_id" id="item_category_id" data-style="btn btn-primary" title="Item Category">
                                            @foreach($item_category as $key => $value)
                                                <option value="{{ $value->id }}"
                                                    {{ $item_category_id == $value->id ? 'selected':''}}>
                                                    {{ $value->item_category }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary pull-right">Submit</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                @isset($item_category_id)
                @include('settings.rates._create')
                @endisset
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('settings.rates._edit')
            </div>
        </div>
    </div>
</div>
@endsection
