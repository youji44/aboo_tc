@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Settings
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
                                    <h4 class="page-title pull-left"> Settings </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Administrative Rights</h4>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{ route('settings.user') }}">User Management</a></li>
                        <li class="list-group-item"><a href="{{route('settings.airline')}}">Airline/Customers</a></li>
                        <li class="list-group-item"><a href="{{ route('settings.inspect') }}">Inspection Management</a></li>
                        <li class="list-group-item"><a href="{{ route('settings.fuel') }}">Manage Fleet</a></li>
                        <li class="list-group-item"><a href="{{route('settings.location')}}">Primary Location</a></li>
                        <li class="list-group-item"><a href="{{route('settings.grading')}}">Grading Result</a></li>
                        <li class="list-group-item"><a href="{{route('settings.vessel')}}">Vessel</a></li>
                        <li class="list-group-item"><a href="{{route('tf1.settings.tanksump')}}">Tanks</a></li>
                        <li class="list-group-item"><a href="{{route('settings.tanks.mv')}}">Tanks - Measurement, Volume</a></li>
                        <li class="list-group-item"><a href="{{route('settings.operator')}}">Manage Operators(Fuelers)</a></li>
                        <li class="list-group-item"><a href="{{route('settings.audit')}}">Internal Audit</a></li>
                        <li class="list-group-item"><a href="{{route('settings.delays')}}">Fuel Delays</a></li>
                        <li class="list-group-item"><a href="{{route('settings.incident')}}">Incident Reporting</a></li>
                        <li class="list-group-item"><a href="{{route('settings.oil')}}">Oil Water Seperator Locaitons</a></li>
                        <li class="list-group-item"><a href="{{route('settings.tanks.omv')}}">Oil Water Seperator Tanks - Measurement, Volume</a></li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Maintenance Inspections</h4>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{ route('settings.maintenance.hose') }}">Hose Inspection, Change Out Certificate</a></li>
                        <li class="list-group-item"><a href="{{ route('settings.maintenance.vessel_filter') }}">Vessel Inspection, Filter Change Certificate</a></li>
                        <li class="list-group-item"><a href="{{ route('settings.maintenance.fuel_weekly') }}">Fuel Equipment - Weekly</a></li>
                        <li class="list-group-item"><a href="{{ route('settings.maintenance.fuel_monthly') }}">Fuel Equipment - Monthly</a></li>
                        <li class="list-group-item"><a href="{{ route('settings.maintenance.fuel_quarterly') }}">Fuel Equipment - Quarterly</a></li>
                        <li class="list-group-item"><a href="{{ route('settings.prevent.task') }}">Preventative Maintenance - Task</a></li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Daily Inspections</h4>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{route('settings.hydrant')}}">Hydrant Pit</a></li>
                        <li class="list-group-item"><a href="{{route('settings.gasbar')}}">Gas Bar Area</a></li>
                        <li class="list-group-item"><a href="{{route('settings.pit')}}">Fuel Depot-Walk Around </a></li>
                        <li class="list-group-item"><a href="{{route('tf1.settings.facility')}}">Facility General Condition </a></li>
                        <li class="list-group-item"><a href="{{route('tf1.settings.sloptank')}}">Slop Tank</a></li>
                        <li class="list-group-item"><a href="{{route('tf1.settings.walk')}}">Walk Around</a></li>
                        <li class="list-group-item"><a href="{{route('settings.inspect_task','d')}}">Fuel Equipment</a></li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Weekly Inspections</h4>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{route('settings.dbb')}}">Double Block and Bleed</a></li>
                        <li class="list-group-item"><a href="{{route('settings.gasbarw')}}">Gas Bar Area</a></li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Monthly Inspections</h4>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{route('settings.drain')}}">Low Point Drain</a></li>
                        <li class="list-group-item"><a href="{{route('settings.monitor')}}">Monitoring Well</a></li>
                        <li class="list-group-item"><a href="{{route('settings.chamber')}}">Valve Chamber</a></li>
                        <li class="list-group-item"><a href="{{route('settings.recycle')}}">Recycle, Upkeep, Misc</a></li>
                        <li class="list-group-item"><a href="{{route('settings.fire')}}">Fire Extinguisher</a></li>
                        <li class="list-group-item"><a href="{{route('settings.firetype')}}">Fire Extinguisher Type</a></li>
                        <li class="list-group-item"><a href="{{route('settings.hazard')}}">Hazardous Material Task</a></li>
                        <li class="list-group-item"><a href="{{route('settings.gasbarm')}}">Gas Bar Area</a></li>
                        <li class="list-group-item"><a href="{{route('settings.tfesd')}}">Tank Farm Emergency Shut Down(ESD)</a></li>
                        <li class="list-group-item"><a href="{{route('settings.leak')}}">Leak Detection</a></li>
                        <li class="list-group-item"><a href="{{route('settings.signs')}}">Signs & Placards</a></li>
                        <li class="list-group-item"><a href="{{route('settings.truck')}}">Fuel Depot - Truck Rack</a></li>
                        <li class="list-group-item"><a href="{{route('settings.cathodic')}}">Cathodic Protection</a></li>
                        <li class="list-group-item"><a href="{{route('qcf.settings.fuel_monthly')}}">Monthly Inspection</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <h4 class="header-title">Quarterly Inspections</h4>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{route('settings.hpd')}}">High Point Inspections</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <h4 class="header-title">Annual Inspections</h4>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{route('settings.esd')}}">Hydrant System ESD</a></li>
                        <li class="list-group-item"><a href="{{route('settings.owsc')}}">Oil Water Separator Cleaning</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <h4 class="header-title">Close Out</h4>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{route('settings.pipline')}}">Pipline</a></li>
                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="{{route('settings.totalizer')}}">Totalizer</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
@stop
