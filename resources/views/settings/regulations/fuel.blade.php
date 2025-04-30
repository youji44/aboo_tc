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
                        <input title="" hidden name="id" value="{{$regulation->id}}">
                        <input title="" hidden name="type" value="{{$regulation->type}}">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="fuel_equipment_daily-tab" data-toggle="tab" href="#fuel_equipment_daily" role="tab" aria-controls="fuel_equipment_daily" aria-selected="true">Fuel Equipment - Daily</a>
                            </li>
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link active" id="hydrant_filter_sump-tab" data-toggle="tab" href="#hydrant_filter_sump" role="tab" aria-controls="hydrant_filter_sump" aria-selected="true">Hydrant Filter Sump</a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" id="tanker_filter_sump-tab" data-toggle="tab" href="#tanker_filter_sump" role="tab" aria-controls="tanker_filter_sump" aria-selected="false">Tanker Filter Sump</a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" id="eye_wash_inspection-tab" data-toggle="tab" href="#eye_wash_inspection" role="tab" aria-controls="eye_wash_inspection" aria-selected="false">Eye Wash Inspection</a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" id="visi_jar_cleaning-tab" data-toggle="tab" href="#visi_jar_cleaning" role="tab" aria-controls="visi_jar_cleaning" aria-selected="false">Visi Jar Cleaning</a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" id="filter_membrane_test-tab" data-toggle="tab" href="#filter_membrane_test" role="tab" aria-controls="filter_membrane_test" aria-selected="false">Filter Membrane Test</a>--}}
{{--                            </li>--}}
                            <li class="nav-item">
                                <a class="nav-link" id="fuel_equipment_weekly-tab" data-toggle="tab" href="#fuel_equipment_weekly" role="tab" aria-controls="fuel_equipment_weekly" aria-selected="false">Fuel Equipment - Weekly</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="fuel_equipment_monthly-tab" data-toggle="tab" href="#fuel_equipment_monthly" role="tab" aria-controls="fuel_equipment_monthly" aria-selected="false">Fuel Equipment - Monthly</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="fuel_equipment_quarterly-tab" data-toggle="tab" href="#fuel_equipment_quarterly" role="tab" aria-controls="fuel_equipment_quarterly" aria-selected="false">Fuel Equipment - Quarterly</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="myTabContent">
                            <div class="tab-pane fade show active" id="fuel_equipment_daily" role="tabpanel" aria-labelledby="fuel_equipment_daily-tab">
                                <div class="form-group">
                                    <textarea class="form-control regulations" name="regulations9" id="regulations9">{!! $regulation->fuel_equipment_daily !!}</textarea>
                                </div>
                            </div>
{{--                            <div class="tab-pane fade show active" id="hydrant_filter_sump" role="tabpanel" aria-labelledby="hydrant_filter_sump-tab">--}}
{{--                                <div class="form-group">--}}
{{--                                    <textarea class="form-control regulations" name="regulations1" id="regulations1">{!! $regulation->hydrant_filter_sump !!}</textarea>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="tab-pane fade" id="tanker_filter_sump" role="tabpanel" aria-labelledby="tanker_filter_sump-tab">--}}
{{--                                <div class="form-group">--}}
{{--                                    <textarea class="form-control regulations" name="regulations2" id="regulations2">{!! $regulation->tanker_filter_sump !!}</textarea>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="tab-pane fade" id="eye_wash_inspection" role="tabpanel" aria-labelledby="eye_wash_inspection-tab">--}}
{{--                                <div class="form-group">--}}
{{--                                    <textarea class="form-control regulations" name="regulations3" id="regulations3">{!! $regulation->eye_wash_inspection !!}</textarea>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="tab-pane fade" id="visi_jar_cleaning" role="tabpanel" aria-labelledby="visi_jar_cleaning-tab">--}}
{{--                                <div class="form-group">--}}
{{--                                    <textarea class="form-control regulations" name="regulations4" id="regulations4">{!! $regulation->visi_jar_cleaning !!}</textarea>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="tab-pane fade" id="filter_membrane_test" role="tabpanel" aria-labelledby="filter_membrane_test-tab">--}}
{{--                                <div class="form-group">--}}
{{--                                    <textarea class="form-control regulations" name="regulations5" id="regulations5">{!! $regulation->filter_membrane_test !!}</textarea>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="tab-pane fade" id="fuel_equipment_weekly" role="tabpanel" aria-labelledby="fuel_equipment_weekly-tab">
                                <div class="form-group">
                                    <textarea class="form-control regulations" name="regulations6" id="regulations6">{!! $regulation->fuel_equipment_weekly !!}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="fuel_equipment_monthly" role="tabpanel" aria-labelledby="fuel_equipment_monthly-tab">
                                <div class="form-group">
                                    <textarea class="form-control regulations" name="regulations7" id="regulations7">{!! $regulation->fuel_equipment_monthly !!}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="fuel_equipment_quarterly" role="tabpanel" aria-labelledby="fuel_equipment_quarterly-tab">
                                <div class="form-group">
                                    <textarea class="form-control regulations" name="regulations8" id="regulations8">{!! $regulation->fuel_equipment_quarterly !!}</textarea>
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
            .create( document.querySelector('#regulations9') )
            .then( function(editor) {
                editor.ui.view.editable.element.style.height = '200px';
            } )
            .catch( function(error) {
                console.error( error );
            } );

        // ClassicEditor
        //     .create( document.querySelector( '#regulations2' ) )
        //     .then( function(editor) {
        //         editor.ui.view.editable.element.style.height = '200px';
        //     } )
        //     .catch( function(error) {
        //         console.error( error );
        //     } );
        //
        // ClassicEditor
        //     .create( document.querySelector( '#regulations3' ) )
        //     .then( function(editor) {
        //         editor.ui.view.editable.element.style.height = '200px';
        //     } )
        //     .catch( function(error) {
        //         console.error( error );
        //     } );
        //
        // ClassicEditor
        //     .create( document.querySelector( '#regulations4' ) )
        //     .then( function(editor) {
        //         editor.ui.view.editable.element.style.height = '200px';
        //     } )
        //     .catch( function(error) {
        //         console.error( error );
        //     } );
        //
        // ClassicEditor
        //     .create( document.querySelector( '#regulations5' ) )
        //     .then( function(editor) {
        //         editor.ui.view.editable.element.style.height = '200px';
        //     } )
        //     .catch( function(error) {
        //         console.error( error );
        //     } );

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
        ClassicEditor
            .create( document.querySelector( '#regulations8' ) )
            .then( function(editor) {
                editor.ui.view.editable.element.style.height = '200px';
            } )
            .catch( function(error) {
                console.error( error );
            } );
    </script>
@stop
