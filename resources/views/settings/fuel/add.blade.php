@extends('layouts.layout')
{{-- Page title --}}
@section('title')
    Manage Fleet
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
{{-- Page content --}}
@section('content')
    <div class="header-area">
        <div class="row align-items-center">
            <!-- nav and search button -->
            <div class="col-md-12 col-sm-12 clearfix">
                <div class="nav-btn pull-left">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="search-box pull-left">
                    <div class="page-title-area">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="breadcrumbs-area clearfix">
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Manage Fleet > Add New</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12 mt-2">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Add a New Manage Fleet</h4>
                    @include('notifications')
                    <form action="{{ route('settings.fuel.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="pid" class="col-form-label">SELECT PRIMARY LOCATION</label>
                            <select id="pid" name="pid" class="custom-select">
                                @foreach($primary_locations as $item)
                                    <option value="{{$item->id}}">{{$item->location}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="unit" class="col-form-label">Unit #</label>
                            <input class="form-control" type="text" name="unit" id="unit" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="unit_type" class="col-form-label">Unit Type</label>
                            <select required id="unit_type" name="unit_type" class="custom-select">
                                <option></option>
                                @foreach(\App\Http\Controllers\Utils::unit_types() as $key=>$item)
                                    <option value="{{$key}}">{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="vin_number" class="col-form-label">VIN NUMBER</label>
                            <input class="form-control" type="text" name="vin_number" id="vin_number" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="manu_year" class="col-form-label">EQUIPMENT MANUFACTURE YEAR </label>
                            <input class="form-control" type="text" name="manu_year" id="manu_year" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="make_model" class="col-form-label">EQUIPMENT MAKE - MODEL</label>
                            <input class="form-control" type="text" name="make_model" id="make_model" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="equip_type" class="col-form-label">EQUIPMENT TYPE</label>
                            <select required id="equip_type" name="equip_type" class="custom-select">
                                <option></option>
                                @foreach(\App\Http\Controllers\Utils::equip_types() as $key=>$item)
                                    <option value="{{$key}}">{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="model_type" class="col-form-label">ELEMENT MODEL / TYPE </label>
                            <input class="form-control" type="text" name="model_type" id="model_type" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="serial_number" class="col-form-label">ELEMENT SERIAL NUMBER </label>
                            <input class="form-control" type="text" name="serial_number" id="serial_number" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="qty_installed" class="col-form-label">QTY OF ELEMENT INSTALLED </label>
                            <input class="form-control" type="number" name="qty_installed" id="qty_installed" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="max_flow_rate" class="col-form-label">MAXIMUM OPERATING FLOW RATE </label>
                            <input class="form-control" type="number" step="0.01" name="max_flow_rate" id="max_flow_rate" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="max_dp" class="col-form-label">MAXIMUM CHANGE OUT DP </label>
                            <input class="form-control" type="number" name="max_dp" id="max_dp" style="text-transform:uppercase">
                        </div>
                        <div class="form-group">
                            <label for="last_inspected" class="col-form-label">ELEMENT / VESSEL LAST INSPECTED </label>
                            <input class="form-control" type="date" name="last_inspected" id="last_inspected" value="{{date('Y-m-d')}}">
                        </div>
                        <div class="form-group">
                            <label for="fire_ext_id" class="col-form-label">FIRE EXTINGUISHER TYPE</label>
                            <select id="fire_ext_id" name="fire_ext_id" class="custom-select">
                                @foreach($fire_ext as $item)
                                    <option value="{{$item->id}}">{{$item->fire_extinguisher_type}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="size" class="col-form-label">SIZE(LBS) </label>
                            <input class="form-control" type="number" name="size" id="size">
                        </div>
                        <div class="form-group">
                            <div class="panel-body">
                                <p class="text-muted">Images</p>
                                <div class="dropzone mb-3" id="settings_images"></div>
                            </div>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="hydrant_filter_sump" id="hydrant_filter_sump">
                            <label class="custom-control-label" for="hydrant_filter_sump">Hydrant Filter Sump</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="tanker_filter_sump" id="tanker_filter_sump">
                            <label class="custom-control-label" for="tanker_filter_sump">Tanker Sump</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="eye_wash_inspection" id="eye_wash_inspection">
                            <label class="custom-control-label" for="eye_wash_inspection">Eye Wash Inspection</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="visi_jar_cleaning" id="visi_jar_cleaning">
                            <label class="custom-control-label" for="visi_jar_cleaning">Visi Jar cleaning</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="filter_membrane_test" id="filter_membrane_test">
                            <label class="custom-control-label" for="filter_membrane_test">Filter Membrane Test</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="fuel_equipment_weekly" id="fuel_equipment_weekly">
                            <label class="custom-control-label" for="fuel_equipment_weekly">Fuel Equipment - Weekly</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="fuel_equipment_monthly" id="fuel_equipment_monthly">
                            <label class="custom-control-label" for="fuel_equipment_monthly">Fuel Equipment - Monthly</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="fuel_equipment_quarterly" id="fuel_equipment_quarterly">
                            <label class="custom-control-label" for="fuel_equipment_quarterly">Fuel Equipment - Quarterly</label>
                        </div>

                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Save</button>
                        <a href="{{ route('settings.fuel') }}" class="btn btn-outline-danger mt-4 pr-4 pl-4"><i class="ti-reload"> </i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script>
        if($("div#settings_images").length > 0){
            let uploaded = {};
            Dropzone.autoDiscover = false;
            new Dropzone(document.querySelector("#settings_images"), {
                url: "{{ route('images.settings.upload') }}",
                maxFilesize: 24, // MB
                maxFiles: 4,
                addRemoveLinks: true,
                dictRemoveFile:"Remove Image",
                dictDefaultMessage:"<i class='ti-cloud-up text-secondary' style='font-size:48px'></i><p>Drag and drop a file here or click</p>",
                capture: "camera",
                acceptedFiles:"image/*",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (file, response) {
                    $('form').append('<input type="hidden" name="images[]" value="' + response.name + '">');
                    uploaded[file.name] = response.name
                },
                error: function(file, message) {
                    console.log(message);
                },
                removedfile: function (file) {
                    file.previewElement.remove();
                    let name = '';
                    if (typeof file.file_name !== 'undefined') {
                        name = file.file_name
                    } else {
                        name = uploaded[file.name]
                    }
                    $('form').find('input[name="images[]"][value="' + name + '"]').remove()
                },
                init: function () {
                    if(images) {
                        if(Array.isArray(images)) {
                            images.forEach(function (img) {
                                if(img !== "")
                                    $('form').append('<input type="hidden" name="images[]" value="' + img + '">')
                            })
                        }
                    }
                }
            });
        }
    </script>
@stop
