<form id="fuel_form" action="{{ route('qcf.settings.fuel_quarterly.save') }}" method="POST">
    @csrf
    <input title="" hidden name="unit_id" value="{{$fuel_quarterly->fe_id}}">
    <div class="form-group1">
        <h4>
        <label class="col-form-label mr-3">UNIT#:</label>
        <label class="col-form-label">{{$fuel_quarterly->fe_unit}}</label>
        </h4>
    </div>
    <div class="custom-control custom-checkbox">
        <input title="" type="checkbox" {{$fuel_quarterly->water_check==1?'checked="checked"':''}} class="custom-control-input" name="water_check" id="water_check">
        <label class="custom-control-label" for="water_check">WATER DEFENCE SYSTEM - EXTERNAL CHECK</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input title="" type="checkbox" {{$fuel_quarterly->valve_check==1?'checked="checked"':''}} class="custom-control-input" name="valve_check" id="valve_check">
        <label class="custom-control-label" for="valve_check">INTERNAL VALVE CHECK</label>
    </div>
</form>
