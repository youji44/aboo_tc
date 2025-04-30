@extends('layouts.layout')
{{-- Page title --}}
@section('title')
    Primary Location
@stop
{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Primary Location > Add New</h4>
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
                    <h4 class="header-title">Add a New Primary Location</h4>
                    @include('notifications')
                    <form action="{{ route('settings.location.save') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="location" class="col-form-label">Location Name</label>
                            <input class="form-control" type="text" name="location" id="location">
                        </div>
                        <div class="form-group" id="color-group">
                            <label for="location_color" class="col-form-label">Location Color</label>
                            <input class="form-control" type="text" name="location_color" id="location_color">
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
                            <label for="location_address" class="col-form-label">Location Address</label>
                            <input class="form-control" type="text" name="location_address" id="location_address">
                        </div>
                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Save</button>
                        <a href="{{ route('settings.location') }}" class="btn btn-outline-danger mt-4 pr-4 pl-4"><i class="ti-reload"> </i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

    <script src="{{ asset('assets/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrPmrGVt96gp4gQSRmBYdLYw05jdg4KnM&callback=initMap&v=weekly" async defer></script>
    <script>
        $('#location_color').colorpicker();
        // Example using an event, to change the color of the #demo div background:
        $('#location_color').on('colorpickerChange', function(event) {
            $('#location_color').css('color', event.color.toString());
        });

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
@stop