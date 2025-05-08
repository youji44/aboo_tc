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
                        <li class="list-group-item"><a href="{{route('settings.location')}}">Primary Location</a></li>
                        <li class="list-group-item"><a href="{{route('settings.course')}}">Courses</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
@stop
