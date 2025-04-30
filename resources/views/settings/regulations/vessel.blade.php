@extends('layouts.layout')
{{-- Page title --}}
@section('title')
    Regulations
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > {{\Utils::get_title($regulation->type)}} > Add Regulations</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 mt-2">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Add Regulations</h4>
                    @include('notifications')
                    <form action="{{ route('settings.regulations.save') }}" method="POST">
                        @csrf
                        <input hidden name="id" value="{{$regulation->id}}">
                        <input hidden name="type" value="{{$regulation->type}}">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="water_defense-tab" data-toggle="tab" href="#water_defense" role="tab" aria-controls="water_defense" aria-selected="true">Water Defense System</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="vessel_filter-tab" data-toggle="tab" href="#vessel_filter" role="tab" aria-controls="vessel_filter" aria-selected="false">Vessel Filter Sump</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="bonding_cable-tab" data-toggle="tab" href="#bonding_cable" role="tab" aria-controls="bonding_cable" aria-selected="false">Bonding Cable, Scully System Continuity Test Inspection</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="differential_pressure-tab" data-toggle="tab" href="#differential_pressure" role="tab" aria-controls="differential_pressure" aria-selected="false">Differential Pressure Gauge Position Full Movement Check</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="filter_membrane-tab" data-toggle="tab" href="#filter_membrane" role="tab" aria-controls="filter_membrane" aria-selected="false">Filter Membrane Test(Millipore)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="deadman_control-tab" data-toggle="tab" href="#deadman_control" role="tab" aria-controls="deadman_control" aria-selected="false">Deadman Control Check</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="hoses_pumps_screens-tab" data-toggle="tab" href="#hoses_pumps_screens" role="tab" aria-controls="hoses_pumps_screens" aria-selected="false">Hoses, Pumps and Screens</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="myTabContent">
                            <div class="tab-pane fade show active" id="water_defense" role="tabpanel" aria-labelledby="water_defense-tab">
                                <div class="form-group">
                                    <textarea class="form-control" name="regulations1" id="regulations1">{!! $regulation->water_defense !!}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="vessel_filter" role="tabpanel" aria-labelledby="vessel_filter-tab">
                                <div class="form-group">
                                    <textarea class="form-control" name="regulations2" id="regulations2">{!! $regulation->vessel_filter !!}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="bonding_cable" role="tabpanel" aria-labelledby="bonding_cable-tab">
                                <div class="form-group">
                                    <textarea class="form-control" name="regulations3" id="regulations3">{!! $regulation->bonding_cable !!}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="differential_pressure" role="tabpanel" aria-labelledby="differential_pressure-tab">
                                <div class="form-group">
                                    <textarea class="form-control" name="regulations4" id="regulations4">{!! $regulation->differential_pressure !!}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="filter_membrane" role="tabpanel" aria-labelledby="filter_membrane-tab">
                                <div class="form-group">
                                    <textarea class="form-control" name="regulations5" id="regulations5">{!! $regulation->filter_membrane !!}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="deadman_control" role="tabpanel" aria-labelledby="deadman_control-tab">
                                <div class="form-group">
                                    <textarea class="form-control" name="regulations6" id="regulations6">{!! $regulation->deadman_control !!}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="hoses_pumps_screens" role="tabpanel" aria-labelledby="hoses_pumps_screens-tab">
                                <div class="form-group">
                                    <textarea class="form-control" name="regulations7" id="regulations7">{!! $regulation->hoses_pumps_screens !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Save</button>
                        <a href="{{ route(\Utils::get_indexRoute($regulation->type)) }}" class="btn btn-outline-danger mt-4 pr-4 pl-4"><i class="ti-reload"> </i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script>
        ClassicEditor
            .create( document.querySelector( '#regulations1' ) )
            .then( function(editor) {
                editor.ui.view.editable.element.style.height = '200px';
            } )
            .catch( function(error) {
                console.error( error );
            } );

        ClassicEditor
            .create( document.querySelector( '#regulations2' ) )
            .then( function(editor) {
                editor.ui.view.editable.element.style.height = '200px';
            } )
            .catch( function(error) {
                console.error( error );
            } );

        ClassicEditor
            .create( document.querySelector( '#regulations3' ) )
            .then( function(editor) {
                editor.ui.view.editable.element.style.height = '200px';
            } )
            .catch( function(error) {
                console.error( error );
            } );

        ClassicEditor
            .create( document.querySelector( '#regulations4' ) )
            .then( function(editor) {
                editor.ui.view.editable.element.style.height = '200px';
            } )
            .catch( function(error) {
                console.error( error );
            } );

        ClassicEditor
            .create( document.querySelector( '#regulations5' ) )
            .then( function(editor) {
                editor.ui.view.editable.element.style.height = '200px';
            } )
            .catch( function(error) {
                console.error( error );
            } );

        ClassicEditor
            .create( document.querySelector( '#regulations6' ) )
            .then( function(editor) {
                editor.ui.view.editable.element.style.height = '200px';
            } )
            .catch( function(error) {
                console.error( error );
            } );
        ClassicEditor
            .create( document.querySelector( '#regulations7' ) )
            .then( function(editor) {
                editor.ui.view.editable.element.style.height = '200px';
            } )
            .catch( function(error) {
                console.error( error );
            } );
    </script>
@stop
