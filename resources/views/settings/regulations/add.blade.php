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
                        <div class="form-group">
                            <textarea class="form-control" name="regulations" id="regulations">{!! $regulation->regulations !!}</textarea>
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
            .create( document.querySelector( '#regulations' ) )
            .then( function(editor) {
                editor.ui.view.editable.element.style.height = '200px';
            } )
            .catch( function(error) {
                console.error( error );
            } );
    </script>
@stop