<div class="card">
    <div class="card-header card-header-warning">
        <h4 class="card-title">Add New Rate Form</h4>
        <p class="card-category">Add Karat Rates</p>
    </div>
    <div class="card-body" style="min-height: auto;">
        <form action="{{ route('rates.store') }}"
            method="POST">
            @include('alert')
            @csrf
            <input type="hidden" name="branch_id" value="{{ $branch_id }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="item_type_id">Item Type</label>
                            <select class="selectpicker form-control" name="item_type_id" id="item_type_id" data-style="btn btn-primary" title="Item Type">
                                @foreach($item_types as $item_type)
                                    <option value="{{ $item_type->id }}" {{ old('item_type_id') == $item_type->id ? 'selected' : ''  }}>
                                        {{ $item_type->item_type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('item_type_id')
                                    <label class="text-danger mt-3">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                </div>
                @if ($item_category_id == 1)
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group @error('karat') has-error is-focused @enderror">
                            <label for="Karat">Karat</label>
                            <input type="number" class="form-control" name="karat" id="karat" value="{{ $errors->isEmpty() ? 0 : old('karat') }}">
                        </div>
                        @error('karat')
                                    <label class="text-danger">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @error('gram') has-error is-focused @enderror">
                            <label for="gram">Gram</label>
                            <input type="number" class="form-control" name="gram" id="gram" step="any" value="{{ $errors->isEmpty() ? 0 : old('gram') }}">
                        </div>
                        @error('gram')
                                    <label class="text-danger">{{ $message }}</label>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @error('regular_rate') has-error is-focused @enderror">
                            <label for="regular_rate">Regular Rate</label>
                            <input type="number" class="form-control" name="regular_rate" id="regular_rate" value="{{ $errors->isEmpty() ? 0 : old('regular_rate') }}">
                        </div>
                        @error('regular_rate')
                                    <label class="text-danger">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @error('special_rate') has-error is-focused @enderror">
                            <label for="special_rate">Special Rate</label>
                            <input type="number" class="form-control" name="special_rate" id="special_rate" value="{{ $errors->isEmpty() ? 0 : old('special_rate') }}">
                        </div>
                        @error('special_rate')
                                    <label class="text-danger">{{ $message }}</label>
                        @enderror
                    </div>
                </div>
            @else
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group @error('description') has-error is-focused @enderror">
                            <label for="description">Item Name</label>
                            <input type="text" class="form-control" name="description" id="description" value="{{ $errors->isEmpty() ? '' : old('description') }}"> 
                        </div>
                        @error('description')
                                    <label class="text-danger">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @error('regular_rate') has-error is-focused @enderror" >
                            <label for="regular_rate">Appraised Value</label>
                            <input type="number" class="form-control" name="regular_rate" id="regular_rate" value="{{ $errors->isEmpty() ? 0 : old('regular_rate') }}">
                        </div>
                        @error('regular_rate')
                                    <label class="text-danger">{{ $message }}</label>
                        @enderror
                    </div>
                </div>
            @endif
            <div class="d-flex justify-content-end">
                <input type="text" id="user_auth_code" class="form-control" style="margin-top:16px;width:130px" name="user_auth_code"  placeholder="Auth Code"/>
                <button type="submit" class="btn btn-primary pull-right" style="height:100%">Submit</button>
            </div>

        </form>
    </div>
</div>
