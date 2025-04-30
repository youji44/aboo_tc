@extends('layouts.layout')
{{-- Page title --}}
@section('title')
    Tank Sump
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
                                    <h4 class="page-title pull-left">Settings > Tank Sump > Add New</h4>
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
                    <h4 class="header-title">Add a New Tank Sump</h4>
                    @include('notifications')
                    <form action="{{ route('tf1.settings.tanksump.save') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="tank_no" class="col-form-label">Tank No</label>
                            <input class="form-control" type="text" name="tank_no" id="tank_no">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Primary Location</label>
                            <select id="plocation_id" name="plocation_id" class="custom-select" onchange="select_location(this.value, {{json_encode($locations)}})">
                                @foreach($locations as $item)
                                    <option value="{{$item->id}}">{{$item->location}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="location_name" class="col-form-label">Location Name</label>
                            <input class="form-control" type="text" name="location_name" id="location_name">
                        </div>
                        <div class="form-group">
                            <label for="location_code" class="col-form-label">LOCATION CODE</label>
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
                            <label for="ec_no" class="col-form-label">EC NO#</label>
                            <input class="form-control" type="text" name="ec_no" id="ec_no" >
                        </div>
                        <div class="form-group">
                            <label for="storage_product_type" class="col-form-label">Storage Product Type</label>
                            <input class="form-control" type="text" name="storage_product_type" id="storage_product_type" >
                        </div>
                        <div class="form-group">
                            <label for="location_longitude" class="col-form-label">Google Map</label>
                            <div id="map" style="height: 300px;width: auto"></div>
                        </div>
                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Save</button>
                        <a href="{{ route('tf1.settings.tanksump') }}" class="btn btn-outline-danger mt-4 pr-4 pl-4"><i class="ti-reload"> </i> Cancel</a>
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
@stop