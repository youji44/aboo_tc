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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > <a class="text-dark" href="{{route('course')}}"> Courses </a> > {{$topic->topic_title}} </h4>
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
            <a class="btn btn-secondary btn-sm" href="{{ route('course.topic') }}?cid={{$topic->course_id}}&tid={{$topic->id}}&exit=1"><i class="ti-arrow-left"></i> Back </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 mt-2">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-uppercase">{{$topic->topic_title}}</h3>
                    <hr>
                    <div>
                        {!! $topic->description !!}
                    </div>
                    <hr>
                    @if($topic->attach_files && is_array($files = json_decode($topic->attach_files)))
                        <div class="form-group">
                            <h6 class="col-form-label font-weight-bold">ATTACHMENT</h6>
                            <ul class="list-unstyled">
                                @foreach($files as $key=>$file)
                                    <li class="mb-1">
                                        <a class="text-info" href="{{route('file.download')}}?file={{$file}}">
                                            <i class="ti-file mr-2"></i>
                                            Attached File {{$key+1}}
                                            <small class="text-muted ml-2">
                                                (.{{ pathinfo($file, PATHINFO_EXTENSION) }})
                                            </small>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
{{-- page level scripts --}}
@section('footer_scripts')
    <script>
        $(document).ready(function() {
            $('figure.media').has('[data-oembed-url*="youtu"]').each(function() {
                var $iframeContainer = $(this).find('[style*="padding-bottom"]');
                var $iframe = $iframeContainer.find('iframe');
                var $responsiveContainer = $('<div>').css({
                    'position': 'relative',
                    'padding-bottom': '56.25%',  // 16:9 aspect ratio (9/16 = 0.5625)
                    'height': '0',
                    'overflow': 'hidden',
                    'width': '100%'
                });
                // Move iframe into the new container
                $iframe.appendTo($responsiveContainer).css({
                    'position': 'absolute',
                    'top': '0',
                    'left': '0',
                    'width': '100%',
                    'height': '100%'
                });
                // Replace original figure with the new container
                $(this).replaceWith($responsiveContainer);
            });
        });

    </script>
@stop
