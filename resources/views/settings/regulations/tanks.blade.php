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
                                <a class="nav-link active" id="tank_sump_results-tab" data-toggle="tab" href="#tank_sump_results" role="tab" aria-controls="tank_sump_results" aria-selected="true">Tank Sump Results</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tank_level_alarm_test-tab" data-toggle="tab" href="#tank_level_alarm_test" role="tab" aria-controls="tank_level_alarm_test" aria-selected="false">Tank Level Alarm Test</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="myTabContent">
                            <div class="tab-pane fade show active" id="tank_sump_results" role="tabpanel" aria-labelledby="tank_sump_results-tab">
                                <div class="form-group">
                                    <textarea class="form-control" name="regulations1" id="regulations1">{!! $regulation->tank_sump_results !!}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tank_level_alarm_test" role="tabpanel" aria-labelledby="tank_level_alarm_test-tab">
                                <div class="form-group">
                                    <textarea class="form-control" name="regulations2" id="regulations2">{!! $regulation->tank_level_alarm_test !!}</textarea>
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
    </script>
@stop