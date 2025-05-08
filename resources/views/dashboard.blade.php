@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Welcome
@stop
{{-- page level styles --}}
@section('header_styles')
<style>
    .badge-left {
        min-width: 20px;
        border-radius: 50rem;
        display: inline-block;
        padding: .25em .4em .25em .4em;
        font-size: 90%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
    }
    .colx-3 {
        width: 20%;
        float: left;
    }
    .colx-3 {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }
    @media screen and (max-width: 768px) {
        .colx-3 { width: 100%; }
    }
</style>
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
                                <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Dashboard </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sales-report-area mt-5 mb-5">
    @include('notifications')
    <div class="row">
        <div class="col-6">
            <div class="single-report mb-xs-30">
                <div class="s-report-inner pr--20 pt--30 mb-3">
                    <div class="icon"><i class="fa calendar-check-o fa-calendar-check-o"></i></div>
                    <div class="s-report-title d-flex justify-content-between">
                        <h4 class="header-title mb-0">AAA</h4>
                    </div>
                    <div class="d-flex justify-content-between pb-2">
                        <h2>0</h2>
                        <p>Pending</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="single-report mb-xs-30">
                <div class="s-report-inner pr--20 pt--30 mb-3">
                    <div class="icon"><i class="fa calendar-check-o fa-calendar-check-o"></i></div>
                    <div class="s-report-title d-flex justify-content-between">
                        <h4 class="header-title mb-0">BBB</h4>
                    </div>
                    <div class="d-flex justify-content-between pb-2">
                        <h2>0</h2>
                        <p>Pending</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script>
        $(document).ready(function(){

        });
    </script>
@stop
