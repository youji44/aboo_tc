@extends('layouts.layout')
{{-- Page title --}}
@section('title')
    Internal Audit
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Internal Audit > Add New</h4>
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
                    <h4 class="header-title">Add a new Internal Audit</h4>
                    @include('notifications')
                    <form id="save_form" action="{{ route('audit.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="date" class="col-form-label">Date</label>
                            <input {{\Sentinel::inRole('admin') || \Sentinel::inRole('superadmin')?'':'readonly'}} id="date" class="form-control" type="date" onchange="set_date(this.value)" value="{{isset($date)?$date:date('Y-m-d')}}" placeholder="2022-12-05" name="date">
                        </div>
                        <div class="form-group">
                            <label for="time" class="col-form-label">Time</label>
                            <input class="form-control" type="time" value="{{date('H:i')}}" placeholder="00:00" id="time" name="time">
                        </div>

                        <div class="form-group">
                            <label for="airline" class="col-form-label">SELECT AIRLINE/CUSTOMER</label>
                            <select id="airline" name="airline" class="custom-select select2">
                                <option></option>
                                @foreach($settings_airline as $item)
                                    <option value="{{$item->id}}">{{$item->airline_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="refuelled_id" class="col-form-label">SELECT A TYPE OF AIRCRAFT</label>
                            <select id="refuelled_id" name="refuelled" class="custom-select select2">
                                <option></option>
                                @foreach($settings_refuelled as $item)
                                    <option value="{{$item->id}}">{{$item->refuelled}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="flight_number" class="col-form-label">FLIGHT NUMBER OR AIRCRAFT REGISTRATION</label>
                            <input class="form-control" type="text" id="flight_number" name="flight_number">
                        </div>

                        <div class="form-group">
                            <label for="unit" class="col-form-label">SELECT FUEL EQUIPMENT UNIT#</label>
                            <select id="unit" name="unit" class="custom-select select2">
                                <option></option>
                                @foreach($fuel_equipment as $item)
                                    <option value="{{$item->id}}">{{$item->unit.' - '.$item->unit_type.' - Last Inspected Date '.$item->last_inspected}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="operator" class="col-form-label">FUELER FULL NAME</label>
                            <input required class="form-control" type="text" id="operator" name="operator">
{{--                            <select required id="operator" name="operator" class="custom-select select2">--}}
{{--                                <option></option>--}}
{{--                                @foreach($operators as $item)--}}
{{--                                    <option value="{{$item->id}}">{{$item->operator}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                        </div>

                        <div class="form-group">
                            <label for="location_gate" class="col-form-label">LOCATION, GATE</label>
                            <input class="form-control" type="text" id="location_gate" name="location_gate">
                        </div>

                        <div class="form-group">
                            <label for="audit_type" class="col-form-label">SELECT A TYPE OF AUDIT</label>
                            <select required id="audit_type" name="audit_type" onchange="select_audit(this.value)" class="custom-select select2">
                                <option selected></option>
                                @foreach($audits as $item)
                                    <option value="{{$item->id}}">{{$item->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="audit_question" class="form-group">
                        </div>

                        <div class="form-group">
                            <label for="overall_result" class="col-form-label">OVERALL RESULT</label>
                            <select id="overall_result" name="overall_result" class="custom-select">
                                @foreach($grading_condition as $item1)
                                    <option value="{{$item1->id}}">{{$item1->result}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="signature-pad" class="col-form-label">Sign Signature</label>
                            <div class="form-group mb-0">
                                <canvas id="signature-pad" class="border border-dark bg-light"></canvas>
                            </div>
                            <button id="clear" type="button" class="btn btn-outline-dark">Clear</button>
                            <input hidden type="text" id="signature" name="signature">
                        </div>

                        <div class="form-group">
                            <label for="comments" class="col-form-label">COMMENTS</label>
                            <textarea name="comments" class="form-control form-control-lg" type="text"  id="comments"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="panel-body">
                                <p class="text-muted">Images</p>
                                <div class="dropzone mb-3" id="images"></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Save</button>
                        <a href="{{ route('audit') }}" class="btn btn-outline-danger mt-4 pr-4 pl-4"><i class="ti-reload"> </i> Cancel</a>
                    </form>
                    <div class="form-group">
                        <label for="map" class="col-form-label">You Location</label>
                        <div id="map" style="height: 200px;width: auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/signature_pad/dist/signature_pad.umd.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrPmrGVt96gp4gQSRmBYdLYw05jdg4KnM&callback=initMap&v=weekly" async defer></script>
    <script>
        let signaturePad = new SignaturePad(document.getElementById('signature-pad'));
        document.getElementById('clear').addEventListener('click', function () {
            signaturePad.clear();
        });

        function set_date(date) {
            location.href = '{{route('audit.add')}}'+'?date='+date;
        }
        function select_audit(id) {
            $.get('{{route('audit.change')}}?id='+id, function (data,status) {
                $("#audit_question").html(data);
            });
        }

        $(document).ready(function() {
            $('#save_form').on('submit', function(e) {
                e.preventDefault();
                $('<input>').attr({
                    type: 'hidden',
                    name: 'geo_lat',
                    id: 'location_latitude',
                    value: geo_position.lat
                }).appendTo('#save_form');

                $('<input>').attr({
                    type: 'hidden',
                    name: 'geo_lng',
                    id: 'location_longitude',
                    value: geo_position.lng
                }).appendTo('#save_form');

                if (!signaturePad.isEmpty()) {
                    let data = signaturePad.toDataURL('image/png');
                    $("#signature").val(data);
                }

                this.submit();
            });
        });
        var geo_position = {lat: 49.1968531, lng: -123.1751411};
        function initMap() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    geo_position.lat = position.coords.latitude;
                    geo_position.lng = position.coords.longitude;
                    get_map();
                }, function () {
                    get_map();
                });
            } else {
                get_map();
            }
        }
        function get_map(){
            var center_loc = { lat: parseFloat(geo_position.lat), lng: parseFloat(geo_position.lng) };
            var map = new google.maps.Map(document.getElementById("map"), {
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
            var marker = new google.maps.Marker({
                position: center_loc,
                map: map,
            });

            map.addListener("click", (mapsMouseEvent) => {
                let latLng = mapsMouseEvent.latLng.toJSON();
                marker.setPosition(mapsMouseEvent.latLng)
                geo_position.lat = latLng.lat;
                geo_position.lng = latLng.lng;
            });
        }
    </script>
@stop
