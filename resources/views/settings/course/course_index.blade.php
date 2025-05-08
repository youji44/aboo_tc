@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Settings Course Managing
@stop
{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" href="{{asset('assets/ckeditor5/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/ckeditor5/ckeditor5/ckeditor5.css')}}">
    <style>
        .editor-container_classic-editor .editor-container__editor {
            min-width: 500px;
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > <a class="text-dark" href="{{ route('settings.course') }}"> Courses </a> > {{$course->course_title}} </h4>
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
            <a class="btn btn-warning btn-sm" href="{{route('settings.course')}}"><i class="ti-arrow-left"></i> Back </a>
        </div>
    </div>
    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="topic-tab" data-toggle="tab" href="#topic" role="tab" aria-controls="topic" aria-selected="true">Topic</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="quiz-tab" data-toggle="tab" href="#quiz" role="tab" aria-controls="quiz" aria-selected="true">Quiz</a>
        </li>
    </ul>
    <div class="tab-content mt-3" id="myTabContent">
        <div class="tab-pane active show" id="topic" role="tabpanel" aria-labelledby="topic-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <a class="btn btn-success btn-sm" href="{{route('settings.course.topic.edit','0')}}?cid={{$course->id}}"><i class="ti-plus"></i> Add New </a>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            @include('notifications')
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">#</th>
                                            <th scope="col">TOPICS</th>
                                            <th scope="col">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($topics as $key=>$item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$item->topic_title}}</td>
                                                <td>
                                                    <a data-tip="tooltip" title="Edit" href="{{route('settings.course.topic.edit',$item->id)}}?cid={{$course->id}}" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i></a>
                                                    <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i></button>
                                                    <form id="form_{{$item->id}}" hidden action="{{route('settings.course.topic.delete')}}" method="post">
                                                        @csrf <input title="id" hidden name="id" value="{{$item->id}}">
                                                    </form>
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
        </div>
        <div class="tab-pane" id="quiz" role="tabpanel" aria-labelledby="quiz-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <button class="btn btn-success btn-sm" onclick="show_edit('{{route('settings.course.quiz.edit',0)}}?cid={{$course->id}}')"><i class="ti-plus"></i> Add New </button>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            @include('notifications')
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">#</th>
                                            <th scope="col">QUESTION</th>
                                            <th scope="col">CORRECT CHOICE</th>
                                            <th scope="col">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($quiz as $key=>$item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td class="text-left">{!! $item->question !!}</td>
                                                <td class="text-left">
                                                    @foreach($item->choices as $kk=>$option)
                                                        <div class="custom-control custom-radio">
                                                            <input disabled {{$option->correct==$option->value?'checked':''}} type="radio" id="option_{{$item->id}}_{{$kk}}" name="option_{{$item->id}}" value="{{$kk}}" class="custom-control-input">
                                                            <label class="custom-control-label" for="option_{{$item->id}}_{{$kk}}">{{$option->name}}</label>
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <button data-tip="tooltip" title="Edit" onclick="show_edit('{{route('settings.course.quiz.edit',$item->id)}}?cid={{$course->id}}')" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i></button>
                                                    <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i></button>
                                                    <form id="form_{{$item->id}}" hidden action="{{route('settings.course.quiz.delete')}}" method="post">
                                                        @csrf <input title="id" hidden name="id" value="{{$item->id}}">
                                                    </form>
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
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="input_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title_modal">Manage Course Quiz</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div id="modal_body" class="modal-body" style="min-height: 240px">
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "{{asset('assets/ckeditor5/ckeditor5/ckeditor5.js')}}",
            "ckeditor5/": "./ckeditor5/ckeditor5/"
        }
    }
    </script>
    <script>

        function show_edit(url){
            $.get(url, function (data) {
                $('#title_modal').html($('.page-title').html() + '> Quiz ');
                $("#modal_body").html(data);
                $("#input_modal").modal('show');
            });
        }

        var activeTab = localStorage.getItem('tc_activeTab');
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('active');
        if (activeTab && $(activeTab).length > 0) {
            $('a[href="' + activeTab + '"]').addClass('active');
            $(activeTab).addClass('active');
        } else {
            const tabLink = $('#myTab .nav-link').eq(0);
            tabLink.addClass('active');
            const activeDiv = $('#myTabContent .tab-pane').eq(0);
            activeDiv.addClass('active');
        }
        $('.nav-link').on('click', function(evt) {
            const tabId = $(this).attr('href');
            localStorage.setItem('tc_activeTab', tabId);
        });

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
