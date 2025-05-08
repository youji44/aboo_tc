@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Courses
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Courses </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    @include('notifications')
                    <h4 class="header-title">Topic Course</h4>
                    <ul class="list-group">
                        @foreach($courses as $item)
                            <li class="list-group-item d-flex justify-content-between">
                                <a href="{{ route('course.topic') }}?cid={{$item->id}}" class="text-decoration-none">
                                    <span class="font-weight-bold font-14 text-info">{{$item->course_title}}</span>
                                </a>
                                <span class="badge badge-info ml-auto mr-1" style="font-size: 100%">{{$item->topic_count}}</span>
                                <span class="font-14">Topics</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop
{{-- page level scripts --}}
@section('footer_scripts')
@stop
