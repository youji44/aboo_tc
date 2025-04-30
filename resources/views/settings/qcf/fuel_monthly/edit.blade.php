<form id="fuel_form" action="{{ route('qcf.settings.fuel_monthly.save') }}" method="POST">
    @csrf
    <input title="uid" hidden name="unit_id" value="{{$fuel_monthly->fe_id}}">
    <div class="form-group1">
        <h4>
        <label class="col-form-label mr-3">UNIT#:</label>
        <label class="col-form-label">{{$fuel_monthly->fe_unit}}</label>
        </h4>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" {{$fuel_monthly->button1==1?'checked="checked"':''}} class="custom-control-input" name="button1" id="button1">
        <label class="custom-control-label" for="button1">BUTTON 1</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" {{$fuel_monthly->button2==1?'checked="checked"':''}} class="custom-control-input" name="button2" id="button2">
        <label class="custom-control-label" for="button2">BUTTON 2</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" {{$fuel_monthly->button3==1?'checked="checked"':''}} class="custom-control-input" name="button3" id="button3">
        <label class="custom-control-label" for="button3">BUTTON 3</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" {{$fuel_monthly->hose_deadman==1?'checked="checked"':''}} class="custom-control-input" name="hose_deadman" id="hose_deadman">
        <label class="custom-control-label" for="hose_deadman">HOSE REEL DEADMAN</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" {{$fuel_monthly->lift_deadman==1?'checked="checked"':''}} class="custom-control-input" name="lift_deadman" id="lift_deadman">
        <label class="custom-control-label" for="lift_deadman">LIFT DECK DEADMAN</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" {{$fuel_monthly->lift_platforms==1?'checked="checked"':''}} class="custom-control-input" name="lift_platforms" id="lift_platforms">
        <label class="custom-control-label" for="lift_platforms">LIFT PLATFORMS</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" {{$fuel_monthly->water_sensor==1?'checked="checked"':''}} class="custom-control-input" name="water_sensor" id="water_sensor">
        <label class="custom-control-label" for="water_sensor">WATER SENSOR SYSTEM</label>
    </div>
</form>
