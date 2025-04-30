@extends('layouts.layout')
{{-- Page title --}}
@section('title')
    Grading Result
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Grading Result > Add New</h4>
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
                    <h4 class="header-title">Add a New Grading Result</h4>
                    @include('notifications')
                    <form action="{{ route('settings.grading.save') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="grade" class="col-form-label">Grade</label>
                            <input class="form-control" name="grade" id="grade">
                        </div>
                        <div class="form-group">
                            <label for="result" class="col-form-label">Result</label>
                            <input class="form-control" name="result" id="result">
                        </div>
                        <div class="form-group">
                            <label for="grading_type" class="col-form-label">Grading Type</label>
                            <select name="grading_type" id="grading_type" class="custom-select">
                                <option value="percentage">Percentage</option>
                                <option value="rating">Rating</option>
                                <option value="condition">Condition</option>
                                <option value="level">Level</option>
                                <option value="leaking">Leaking</option>
                                <option value="operation">Operation</option>
                                <option value="certificate">Certificate</option>
                                <option value="auditor">Auditor</option>
                                <option value="dryrating">Dry Rating</option>
                                <option value="wetrating">Wet Rating</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="color" class="col-form-label">Color</label>
                            <select onchange="select_color(this.value)" name="color" id="color" class="custom-select alert-{{$colors[0]->name}}">
                                @foreach($colors as $item)
                                    <option class="alert-{{$item->name}}" value="{{$item->name}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="is_comments" id="is_comments">
                            <label class="custom-control-label" for="is_comments">REQUIRED COMMENTS</label>
                        </div>
                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Save</button>
                        <a href="{{ route('settings.grading') }}" class="btn btn-outline-danger mt-4 pr-4 pl-4"><i class="ti-reload"> </i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
@stop
