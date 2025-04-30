<form class="needs-validation"  novalidate="" id="fleet_form" action="{{ route('qcf.settings.pointof.fleet.update') }}" method="POST">
    @csrf
    <input title="id" hidden name="id" value="{{$fuel_equipment->id}}">
    <div>
        <label class="col-form-label mr-3">Unit#: </label>
        <label class="col-form-label mr-3">{{$fuel_equipment->unit}}</label>
    </div>
    <div class="mb-3">
        <label class="col-form-label mr-3">Unit Type: </label>
        <label class="col-form-label mr-3">{{$fuel_equipment->unit_type}}</label>
    </div>
    @if(count($fuel_equipment->prevent_fleet) > 0)
        @foreach($fuel_equipment->prevent_fleet as $item)
        <div class="custom-control custom-checkbox">
            <input type="checkbox" {{$item->selected==1?'checked':''}} class="custom-control-input" name="cat_{{$item->id}}" id="cat_{{$item->id}}">
            <label class="custom-control-label" for="cat_{{$item->id}}">{{$item->category}}</label>
        </div>
        @endforeach
    @else
        @foreach($prevent_category as $item)
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="cat_{{$item->id}}" id="cat_{{$item->id}}">
                <label class="custom-control-label" for="cat_{{$item->id}}">{{$item->category}}</label>
            </div>
        @endforeach
    @endif
</form>

