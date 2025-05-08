@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Quiz
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > <a class="text-dark" href="{{route('course.quiz')}}"> Courses </a> > {{$course->course_title}} </h4>
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

        </div>
    </div>
    <div class="row">
        <div class="col-xl mt-2">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Time Remaining: <span id="quiz-timer">00:00</span></h5>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @include('notifications')
                    <div class="single-table">
                        <div class="table-responsive">
                            <form id="quiz_form" class="needs-validation" novalidate="" action="{{ route('course.quiz.submit') }}" method="POST">
                                @csrf
                                <input hidden="hidden" name="cid" value="{{$course->id}}">
                                <table class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                    <thead class="text-uppercase">
                                    <tr class="bg-light">
                                        <th scope="col" style="width: 5%;">#</th>
                                        <th class="text-left" scope="col" style="width: 60%;">QUESTION</th>
                                        <th scope="col" style="width: 35%;">ANSWERS</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($quiz as $key=>$item)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td class="text-left">{{$item->question}}</td>
                                            <td class="text-left">
                                                @foreach($item->answers as $kk=>$option)
                                                    <div class="custom-control custom-radio">
                                                        <input required {{$option->answer==$option->value?'checked':''}} type="radio" id="option_{{$item->id}}_{{$option->value}}" name="option_{{$item->id}}" value="{{$option->value}}" class="custom-control-input">
                                                        <label class="custom-control-label text-{{($option->answer!=null)? (($option->answer==$option->correct)?'info':'danger'):''}}" for="option_{{$item->id}}_{{$option->value}}">{{$option->name}}</label>
                                                    </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="form-group m-3">
                                    <button class="btn btn-success btn-sm"><i class="ti-arrow-up"></i> Finish </button>
                                </div>
                            </form>
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
                "responsive": true,
                "paging": false,
                "info": false,
                "filter":false,
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

        $(document).ready(function () {
            const timerElement = $('#quiz-timer');
            const quizForm = $('#quiz_form')[0];
            let timerInterval = null;
            let secondsElapsed = 0;

            const savedTime = "{{ $course->timing ?? 0 }}";
            if (savedTime && parseInt(savedTime) > 0) {
                secondsElapsed = parseInt(savedTime);
            } else {
                localStorage.setItem('quizStartTime', Date.now().toString());
            }

            function startTimer() {
                if (timerInterval) {
                    clearInterval(timerInterval);
                }

                const startTime = localStorage.getItem('quizStartTime');
                if (startTime && (!savedTime || parseInt(savedTime) === 0)) {
                    secondsElapsed = Math.floor((Date.now() - parseInt(startTime)) / 1000);
                }
                if('{{$course->quiz_count}}' > 0){
                    updateTimer();
                    timerInterval = setInterval(updateTimer, 1000);
                }
            }

            function updateTimer() {
                secondsElapsed++;
                localStorage.setItem('quizElapsedTime', secondsElapsed);

                const hours = Math.floor(secondsElapsed / 3600);
                const minutes = Math.floor((secondsElapsed % 3600) / 60);
                const seconds = secondsElapsed % 60;

                const formattedTime = hours > 0
                    ? `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
                    : `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                timerElement.text(formattedTime);

                if (secondsElapsed % 1 === 0) {
                    $.post("{{ route('course.quiz.timing.save') }}", {
                        _token: "{{ csrf_token() }}",
                        cid: "{{ $course->id }}",
                        timing: secondsElapsed
                    });
                }
            }

            $('.needs-validation').on('submit', function(event) {
                let form = $(this);
                if (form[0].checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }else{
                    $(":submit", this).attr("disabled", "disabled");
                    clearInterval(timerInterval);
                    localStorage.removeItem('quizElapsedTime');
                    localStorage.removeItem('quizStartTime');
                }
                form[0].classList.add('was-validated');
            });

            // quizForm.addEventListener('submit', function () {
            //
            //     clearInterval(timerInterval);
            //     localStorage.removeItem('quizElapsedTime');
            //     localStorage.removeItem('quizStartTime');
            // });

            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'visible') {
                    startTimer();
                } else if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
            });

            startTimer();
        });

    </script>
@stop
