@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Topic
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > <a class="text-dark" href="{{route('course')}}"> Courses </a> > {{$course->course_title}} </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl mt-2">
            <a class="btn btn-secondary btn-sm" href="{{route('course')}}"><i class="ti-arrow-left"></i> Back </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl mt-2">
            <div class="card">
                <div class="card-body">
                    @include('notifications')
                    <div class="single-table">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                <thead class="text-uppercase">
                                <tr class="bg-light">
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th class="text-left" scope="col" style="width: 80%;">TOPIC</th>
                                    <th scope="col" style="width: 15%;">ACTION</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($topic as $key=>$item)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td class="text-left">{{$item->topic_title}}</td>
                                        <td>
                                            @if($item->reviewed == 1)
                                                <a style="min-width: 80px" href="{{route('course.topic.detail')}}?tid={{$item->id}}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="ti-eye mr-1"></i>Review</a>
                                            @else
                                                <a style="min-width: 80px" href="{{route('course.topic.detail')}}?tid={{$item->id}}" class="btn btn-success btn-sm">
                                                    <i class="ti-arrow-circle-right mr-1"></i>Start</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
        if ($('table').length) {
            $('table').DataTable({
                "destroy": true,
                "responsive": true,
                "pageLength": 100,
                "info": false,
                "order": [],
                "columnDefs": [{
                    "targets":[0],
                    "searchable":false,
                    "orderable":false
                }],
                dom: 'Bfrtip',
            });
            $('.dt-buttons').hide();
        }
    </script>
@stop
