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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Quiz </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-xs-12 mt-4">
            <div class="card">
                <div class="card-body">
                    @include('notifications')
                    <h4 class="header-title">Quiz Course</h4>
                    <ul class="list-group">
                        @foreach($courses as $item)
                            <li class="list-group-item  pt-1 pb-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('course.quiz.list') }}?cid={{$item->id}}" class="text-decoration-none">
                                        <span class="font-weight-bold font-14 text-info">{{$item->course_title}}</span>
                                    </a>
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-info mr-2" style="font-size: 100%">{{$item->quiz_count}}</span>
                                        <span class="font-14">Questions</span>
                                        @if(empty($item->passed) || $item->passed == 1)
                                            <a href="{{ route('course.quiz.list') }}?cid={{$item->id}}" style="min-width: 160px" class="btn btn-light btn-sm ml-3">
                                                Not Started </a>
                                        @elseif($item->passed == 2)
                                            <a href="{{ route('course.quiz.list') }}?cid={{$item->id}}" style="min-width: 160px" class="btn btn-warning btn-sm ml-3">
                                                <i class="ti-close"></i> Failed <span>(Score: {{$item->grade}})</span></a>
                                        @else
                                            <a href="{{ route('course.quiz.list') }}?cid={{$item->id}}" style="min-width: 160px" class="btn btn-success btn-sm ml-3">
                                                <i class="ti-check"></i> Passed <span>(Score: {{$item->grade}})</span></a>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop
@section('footer_scripts')
@stop
