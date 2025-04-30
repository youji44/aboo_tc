@extends('layouts.layout')
{{-- Page title --}}
@section('title')
    Airline/Customer Management
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Airline/Customer Management > Add New</h4>
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
                    <h4 class="header-title">Add a New Airline/Customer Management</h4>
                    @include('notifications')
                    <form action="{{ route('settings.airline.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="airline_name" class="col-form-label">Airline/Customer Name</label>
                            <input class="form-control" type="text" name="airline_name" id="airline_name">
                        </div>
                        <div class="form-group">
                            <label for="icao_code" class="col-form-label">ICAO Code</label>
                            <input class="form-control" type="text" name="icao_code" id="icao_code">
                        </div>
                        <div class="form-group">
                            <label for="iata_code" class="col-form-label">IATA Code</label>
                            <input class="form-control" type="text" name="iata_code" id="iata_code">
                        </div>
                        <div class="form-group">
                            <div class="panel-body">
                                <p class="text-muted">Logo Image</p>
                                <div class="mt-40">
                                    <input type="file" name="images" id="images" accept="image/*" capture="camera" class="dropify" />
                                </div>
                            </div>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="airline_water_test" id="airline_water_test">
                            <label class="custom-control-label" for="airline_water_test">Water Detector Test</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="bol" id="bol">
                            <label class="custom-control-label" for="bol">Truck - Bill of lading</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" checked="checked" class="custom-control-input" name="pipeline_bol" id="pipeline_bol">
                            <label class="custom-control-label" for="pipeline_bol">Pipeline - Bill of lading</label>
                        </div>
                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Save</button>
                        <a href="{{ route('settings.airline') }}" class="btn btn-outline-danger mt-4 pr-4 pl-4"><i class="ti-reload"> </i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
@stop
