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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Grading Result > Edit</h4>
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
                    <h4 class="header-title">Edit a Grading Result</h4>
                    @include('notifications')
                    <form action="{{ route('settings.grading.update') }}" method="POST">
                        @csrf
                        <input hidden value="{{$grading->id}}" name="id">
                        <div class="form-group">
                            <label for="grade" class="col-form-label">Grade</label>
                            <input value="{{$grading->grade}}" class="form-control" name="grade" id="grade">
                        </div>
                        <div class="form-group">
                            <label for="result" class="col-form-label">Result</label>
                            <input value="{{$grading->result}}" class="form-control" name="result" id="result">
                        </div>
                        <div class="form-group">
                            <label for="grading_type" class="col-form-label">Grading Type</label>
                            <select name="grading_type" id="grading_type" class="custom-select">
                                <option {{$grading->grading_type=='percentage'?'selected':''}} value="percentage">Percentage</option>
                                <option {{$grading->grading_type=='rating'?'selected':''}} value="rating">Rating</option>
                                <option {{$grading->grading_type=='condition'?'selected':''}} value="condition">Condition</option>
                                <option {{$grading->grading_type=='level'?'selected':''}} value="level">Level</option>
                                <option {{$grading->grading_type=='leaking'?'selected':''}} value="leaking">Leaking</option>
                                <option {{$grading->grading_type=='operation'?'selected':''}} value="operation">Operation</option>
                                <option {{$grading->grading_type=='certificate'?'selected':''}} value="certificate">Certificate</option>
                                <option {{$grading->grading_type=='auditor'?'selected':''}} value="auditor">Auditor</option>
                                <option {{$grading->grading_type=='dryrating'?'selected':''}} value="dryrating">Dry Rating</option>
                                <option {{$grading->grading_type=='wetrating'?'selected':''}} value="wetrating">Wet Rating</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="color" class="col-form-label">Color</label>
                            <select onchange="select_color(this.value)" name="color" id="color" class="custom-select alert-{{$grading->color}}">
                                @foreach($colors as $item)
                                    <option {{$grading->color==$item->name?'selected':''}} class="alert-{{$item->name}}" value="{{$item->name}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(\Sentinel::inRole('superadmin'))
                        <div class="form-group">
                            <label for="value" class="col-form-label">Value</label>
                            <input value="{{$grading->value}}" class="form-control" name="value" id="value">
                        </div>
                        @endif
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{$grading->status==1?'checked':''}} class="custom-control-input" name="is_comments" id="is_comments">
                            <label class="custom-control-label" for="is_comments">REQUIRED COMMENTS</label>
                        </div>
                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Update</button>
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
