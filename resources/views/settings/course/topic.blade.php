@extends('layouts.layout')
{{-- Page title --}}
@section('title')
    Topic
@stop
{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" href="{{asset('assets/ckeditor5/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/ckeditor5/ckeditor5/ckeditor5.css')}}">
    <style>
        figure.media iframe {
            position: static !important;
            width: 100%;
            height: auto !important;
            max-width: 100%;
            aspect-ratio: 16 / 9 !important;
            display: block;
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Courses > {{$course->course_title}} > Add New </h4>
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
                    <h4 class="header-title">{{isset($topic)?'Edit a ':'Add a New'}} Topic</h4>
                    @include('notifications')
                    <form class="needs-validation" novalidate="" action="{{ route('settings.course.topic.save') }}" method="POST">
                        @csrf
                        <input hidden="hidden" name="id" value="{{isset($topic)?$topic->id:''}}">
                        <input hidden="hidden" name="cid" value="{{$course->id}}">
                        <div class="form-group">
                            <label for="location" class="col-form-label">TOPIC TITLE: </label>
                            <input required class="form-control" type="text" name="topic_title" id="topic_title" value="{{isset($topic)?$topic->topic_title:''}}">
                        </div>
                        <div class="editor-container editor-container_classic-editor editor-container_include-style" id="editor-container">
                            <div class="editor-container__editor"><textarea name="description" id="description">{{isset($topic)?$topic->description:''}}</textarea></div>
                        </div>
                        <div class="form-group">
                            <div class="panel-body">
                                <p class="text-muted">ATTACHMENT: </p>
                                <div class="dropzone mb-3" id="attach_files">
                                    @if(isset($topic) && $topic->attach_files)
                                        @if($attach_files = json_decode($topic->attach_files))
                                            @foreach($attach_files as $file)
                                                <div class="dz-preview dz-file-preview dz-processing dz-complete" data-file="{{$file}}">
                                                    <div class="dz-image"><img data-dz-thumbnail=""></div>
                                                    <div class="dz-details">
                                                        <div class="dz-filename"><span data-dz-name="">{{$file}}</span></div>
                                                    </div>
                                                    <a class="dz-remove" href="javascript:" onclick="remove_files('{{$file}}')" data-dz-remove="">Remove File</a>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="dz-default dz-message"><i class='ti-cloud-up text-secondary' style='font-size:48px'></i><p>Drag and drop documents here or click</p></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Save</button>
                        <a href="{{route('settings.course.detail')}}?cid={{$course->id}}" class="btn btn-outline-danger mt-4 pr-4 pl-4"><i class="ti-reload"> </i> Cancel</a>
                    </form>
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
    <script type="module" src="{{asset('assets/ckeditor5/main.js')}}"></script>
    <script>

        $('.needs-validation').on('submit', function(event) {
            let form = $(this);
            if (form[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }else{
                $(":submit", this).attr("disabled", "disabled");
            }
            form[0].classList.add('was-validated');
        });

        var attach_files = '{!! isset($topic)?$topic->attach_files:'[]' !!}';
        if(isValidJson(attach_files)) attach_files = JSON.parse(attach_files);
        else attach_files = [attach_files];

        function isValidJson(json) {
            try {
                JSON.parse(json);
                return true;
            } catch (e) {
                return false;
            }
        }

        var uploaded = {};
        if($("div#attach_files").length > 0){
            Dropzone.autoDiscover = false;
            new Dropzone("#attach_files", {
                url: "{{ route('settings.files.upload') }}",
                maxFilesize: 24, // MB
                maxFiles: 8,
                uploadMultiple: false,
                parallelUploads: 5,
                addRemoveLinks: true,
                dictRemoveFile: "Remove File",
                dictDefaultMessage: "<i class='ti-cloud-up text-secondary' style='font-size:48px'></i><p>Drag and drop documents here or click</p>",
                acceptedFiles: ".pdf,.doc,.docx,.xls,.xlsx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                success: function (file, response) {
                    $('form').append(`<input type="hidden" name="attach_files[]" value="${response.name}">`);
                    uploaded[file.name] = response.name;
                },
                error: function(file, message) {
                    console.error("Upload error:", message);
                },
                removedfile: function (file) {
                    file.previewElement.remove();
                    var filename = file.file_name || uploaded[file.name];
                    $(`form input[name="attach_files[]"][value="${filename}"]`).remove();
                    $('form').find(`div[class="dz-preview dz-file-preview dz-processing dz-complete"][data-file="${file_name}"]`).remove();
                },
                init: function () {
                    if (typeof attach_files !== 'undefined' && Array.isArray(attach_files)) {
                        attach_files.forEach(function (f) {
                            if (f !== "") {
                                $('form').append(`<input type="hidden" name="attach_files[]" value="${f}">`);
                            }
                        });
                    }
                }
            });
        }

        function remove_certs(file_name) {
            $(`form input[name="attach_files[]"][value="${file_name}"]`).remove();
            $('form').find(`div[class="dz-preview dz-file-preview dz-processing dz-complete"][data-file="${file_name}"]`).remove();
        }

    </script>
@stop
