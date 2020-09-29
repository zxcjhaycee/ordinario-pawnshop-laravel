
<div class="card">
    <div class="card-header card-header-danger">
        <h4 class="card-title">ITEM RATES</h4>
        <p class="card-category">View / Update item Rates</p>
    </div>
    <div class="card-body" style="min-height: auto;">
        <div class="row">
            @foreach($item_types as $item_type)
                @if ($item_category_id == 1)
                    <div class="col-md-6">
                        <h2 class="text-center">{{ $item_type->item_type }}</h2>
                        <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                        <table class="table">
                            <thead>
                                <tr>			
                                    <th class="text-center">Karat</th>
                                    <th class="text-center">Gram %</th>
                                    <th class="text-right">Regular Rate</th>
                                    <th class="text-right">Special Rate</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rates->getItem($branch_id, $item_type->id) as $item)
                                    <form id="update-rate-{{ $item->id }}"
                                        action="{{ route('rates.update', $item->id) }}"
                                        method="POST" style="display: none;">
                                        @method('PUT')
                                        @csrf
                                        <tr>
                                            <td class="text-center">{{ $item->karat }}</td>
                                            <td class="text-center">
                                                <input type="number" name="gram" id="gram" step="any"
                                                    value="{{ old('gram', $item->gram) }}"
                                                    class="table-input">
                                            </td>
                                            <td class="text-right">
                                                <input type="number" name="regular_rate" id="regular_rate"
                                                    value="{{ old('regular_rate', $item->regular_rate) }}"
                                                    class="table-input">
                                            </td>
                                            <td class="text-right" style="width: 120px;">
                                                <input type="number" name="special_rate" id="special_rate"
                                                    value="{{ old('special_rate', $item->special_rate) }}"
                                                    class="table-input">
                                            </td>
                                            <td class="td-actions text-right">
                                                <button type="submit" class="btn btn-success"
                                                    onclick="event.preventDefault(); document.getElementById('update-rate-{{ $item->id }}').submit();">
                                                    <i class="material-icons">edit</i>
                                                </button>
                                                <button type="button" class="btn btn-danger"
                                                    onclick="event.preventDefault(); document.getElementById('delete-rate-{{ $item->id }}').submit();">
                                                    <i class="material-icons">close</i>
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                    <form id="delete-rate-{{ $item->id }}" method="POST" action="{{ route('rates.destroy', $item->id) }}" style="display: none;">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                @else
                    <div class="col-md-12">
                        <h2 class="text-center">{{ $item_type->item_type }}</h2>
                        <div class="table-responsive material-datatables" style="overflow-y: hidden;">
                        <table class="table">
                            <thead>
                                <tr>			
                                    <th class="text-center">Item Name</th>
                                    <th class="text-center">Appraised Value</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rates->getItem($branch_id, $item_type->id) as $item)
                                    <form id="update-rate-{{ $item->id }}"
                                        action="{{ route('rates.update', $item->id) }}"
                                        method="POST" style="display: none;">
                                        @method('PUT')
                                        @csrf
                                        <tr>
                                            <td class="text-center">
                                                <input type="text" name="description" id="description"
                                                    value="{{ old('description', $item->description) }}">
                                            </td>
                                            <td class="text-right">
                                                <input type="number" name="regular_rate" id="regular_rate"
                                                    value="{{ old('regular_rate', $item->regular_rate) }}">
                                            </td>
                                            <td class="td-actions text-right">
                                                <button type="submit" class="btn btn-success"
                                                    onclick="event.preventDefault(); document.getElementById('update-rate-{{ $item->id }}').submit();">
                                                    <i class="material-icons">edit</i>
                                                </button>
                                                <button type="button" class="btn btn-danger"
                                                    onclick="event.preventDefault(); document.getElementById('delete-rate-{{ $item->id }}').submit();">
                                                    <i class="material-icons">close</i>
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                    <form id="delete-rate-{{ $item->id }}" method="POST" action="{{ route('rates.destroy', $item->id) }}" style="display: none;">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
