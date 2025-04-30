@extends('layouts.layout')
{{-- Page title --}}
@section('title')
    Vessel
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Vessel > Add New</h4>
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
                    <h4 class="header-title">Add a New Vessel</h4>
                    @include('notifications')
                    <form action="{{ route('settings.vessel.save') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="col-form-label">Primary Location</label>
                            <select id="plocation_id" name="plocation_id" class="custom-select">
                                @foreach($locations as $item)
                                    <option value="{{$item->id}}">{{$item->location}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="vessel" class="col-form-label">Vessel</label>
                            <input class="form-control" type="text" name="vessel" id="vessel">
                        </div>
                        <div class="form-group">
                            <label for="location" class="col-form-label">Location Name</label>
                            <input class="form-control" type="text" name="location" id="location">
                        </div>
                        <div class="form-group" id="color-group">
                            <label for="location_code" class="col-form-label">Location Code</label>
                            <input class="form-control" type="text" name="location_code" id="location_code">
                        </div>
                        <div class="form-group">
                            <label for="location_latitude" class="col-form-label">Location Latitude</label>
                            <input class="form-control" type="text" name="location_latitude" id="location_latitude" >
                        </div>
                        <div class="form-group">
                            <label for="location_longitude" class="col-form-label">Location Longitude</label>
                            <input class="form-control" type="text" name="location_longitude" id="location_longitude" >
                        </div>
                        <div class="form-group">
                            <label for="location_longitude" class="col-form-label">Google Map</label>
                            <div id="map" style="height: 300px;width: auto"></div>
                        </div>
                        <div class="form-group">
                            <label for="last_inspected" class="col-form-label">LAST INSPECTED</label>
                            <input id="last_inspected" class="form-control" type="date" value="{{date('Y-m-d')}}" name="last_inspected">
                        </div>
                        <div class="form-group">
                            <label for="vessel_rate" class="col-form-label">VESSEL MAX RATED FLOW RATE LITERS/MIN</label>
                            <input class="form-control" type="number" name="vessel_rate" id="vessel_rate" >
                        </div>
                        <div class="form-group">
                            <label for="filter_type" class="col-form-label">FILTER TYPE/MANUFACTURE</label>
                            <input class="form-control" type="text" name="filter_type" id="filter_type" >
                        </div>

                        <div class="form-group">
                            <label for="filter_serial" class="col-form-label">ELEMENT TYPE</label>
                            <input class="form-control" type="text" name="filter_serial" id="filter_serial" >
                        </div>

                        <div class="form-group">
                            <label for="qty" class="col-form-label">QTY OF FILTERS INSTALLED</label>
                            <input class="form-control" type="number" name="qty" id="qty" >
                        </div>

                        <div class="form-group">
                            <div class="panel-body">
                                <p class="text-muted">Images</p>
                                <div class="dropzone mb-3" id="settings_images"></div>
                            </div>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="water_defense" id="water_defense">
                            <label class="custom-control-label" for="water_defense">Water Defense System</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="vessel_filter" id="vessel_filter">
                            <label class="custom-control-label" for="vessel_filter">Vessel Filter Sump</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="bonding_cable" id="bonding_cable">
                            <label class="custom-control-label" for="bonding_cable">Bonding Cable, Scully System Continuity Test Inspection</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="differential_pressure" id="differential_pressure">
                            <label class="custom-control-label" for="differential_pressure">Differential Pressure Gauge Position Full Movement Check</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="filter_membrane" id="filter_membrane">
                            <label class="custom-control-label" for="filter_membrane">Filter Membrane Test(Millipore)</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="deadman_control" id="deadman_control">
                            <label class="custom-control-label" for="deadman_control">Deadman Control Check</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="hoses_pumps_screens" id="hoses_pumps_screens">
                            <label class="custom-control-label" for="hoses_pumps_screens">Hoses, Pumps and Screens</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="bol" id="bol">
                            <label class="custom-control-label" for="bol">Truck - Bill of lading</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="bol_pipeline" id="bol_pipeline">
                            <label class="custom-control-label" for="bol_pipeline">Pipeline - Bill of lading</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="eye_wash_inspection" id="eye_wash_inspection">
                            <label class="custom-control-label" for="eye_wash_inspection">Eye Wash Inspection</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="bulk_sump" id="bulk_sump">
                            <label class="custom-control-label" for="bulk_sump">Bulk Air Eliminators Sump</label>
                        </div>
                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Save</button>
                        <a href="{{ route('settings.vessel') }}" class="btn btn-outline-danger mt-4 pr-4 pl-4"><i class="ti-reload"> </i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrPmrGVt96gp4gQSRmBYdLYw05jdg4KnM&callback=initMap&v=weekly" async defer></script>
    <script>
        // Initialize and add the map
        function initMap() {
            const center_loc = { lat: 49.1968531, lng: -123.1751411 };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 16,
                center: center_loc,
                streetViewControl: false,
                linksControl: false,
                panControl: false,
                addressControl: false,
                zoomControl: false,
                fullScreenControl: false,
                enableCloseButton: false,
                disableDefaultUI: true,
                mapTypeId: 'satellite'
            });
            const marker = new google.maps.Marker({
                position: center_loc,
                map: map,
            });
            map.addListener("click", (mapsMouseEvent) => {
                let latLng = mapsMouseEvent.latLng.toJSON();
                document.getElementById("location_latitude").value = latLng.lat;
                document.getElementById("location_longitude").value = latLng.lng;
                marker.setPosition(mapsMouseEvent.latLng)
            });
        }
        window.initMap = initMap;
    </script>
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
