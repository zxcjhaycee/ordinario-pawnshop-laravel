<div class="card">
    <div class="card-header card-header-warning">
        <h4 class="card-title">Add New Rate Form</h4>
        <p class="card-category">Add Karat Rates</p>
    </div>
    <div class="card-body" style="min-height: auto;">
        <form action="{{ route('rates.store') }}"
            method="POST">
            @csrf
            <input type="hidden" name="branch_id" value="{{ $branch_id }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="item_type_id">Item Type</label>
                            <select class="selectpicker form-control" name="item_type_id" id="item_type_id" data-style="btn btn-primary" title="Item Type">
                                @foreach($item_types as $item_type)
                                    <option value="{{ $item_type->id }}">
                                        {{ $item_type->item_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @if ($item_category_id == 1)
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Karat">Karat</label>
                            <input type="number" class="form-control" name="karat" id="karat" value="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gram">Gram</label>
                            <input type="number" class="form-control" name="gram" id="gram" step="any" value="0">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="regular_rate">Regular Rate</label>
                            <input type="number" class="form-control" name="regular_rate" id="regular_rate" value="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="special_rate">Special Rate</label>
                            <input type="number" class="form-control" name="special_rate" id="special_rate" value="0">
                        </div>
                    </div>
                </div>
            @else
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Item Name</label>
                            <input type="text" class="form-control" name="description" id="description">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="regular_rate">Appraised Value</label>
                            <input type="number" class="form-control" name="regular_rate" id="regular_rate" value="0">
                        </div>
                    </div>
                </div>
            @endif
            <button type="submit" class="btn btn-primary pull-right">Submit</button>
        </form>
    </div>
</div>
