@extends('layouts.layout')
{{-- Page title --}}
@section('title')
    Internal Audit
@stop
{{-- page level styles --}}
@section('header_styles')
    <style>
        .dropify-wrapper{
            height: 80px;
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Internal Audit > Edit</h4>
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
                    <h4 class="header-title">Edit Internal Audit</h4>
                    @include('notifications')
                    <form id="save_form" action="{{ route('audit.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input hidden name="id" value="{{ $audit->id }}">
                        <div class="form-group">
                            <label for="date" class="col-form-label">Date</label>
                            <input readonly value="{{ date('Y-m-d',strtotime($audit->date)) }}" class="form-control" type="date" onchange="set_date(this.value)" name="date" id="date">
                        </div>
                        <div class="form-group">
                            <label for="time" class="col-form-label">Time</label>
                            <input value="{{ date('H:i',strtotime($audit->time)) }}"  readonly type="time" class="form-control" placeholder="10:00 AM" id="time" name="time">
                        </div>

                        <div class="form-group">
                            <label for="airline" class="col-form-label">SELECT AIRLINE/CUSTOMER</label>
                            <select id="airline" name="airline" class="custom-select select2">
                                <option></option>
                                @foreach($settings_airline as $item)
                                    <option {{$audit->airline==$item->id?'selected':''}} value="{{$item->id}}">{{$item->airline_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="refuelled_id" class="col-form-label">SELECT A TYPE OF AIRCRAFT</label>
                            <select id="refuelled_id" name="refuelled" class="custom-select select2">
                                <option></option>
                                @foreach($settings_refuelled as $item)
                                    <option {{$audit->refuelled_id==$item->id?'selected':''}} value="{{$item->id}}">{{$item->refuelled}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="flight_number" class="col-form-label">FLIGHT NUMBER OR AIRCRAFT REGISTRATION</label>
                            <input class="form-control" type="text" id="flight_number" name="flight_number" value="{{$audit->flight_number}}">
                        </div>

                        <div class="form-group">
                            <label for="unit" class="col-form-label">SELECT FUEL EQUIPMENT UNIT#</label>
                            <select id="unit" name="unit" class="custom-select select2">
                                <option></option>
                                @foreach($fuel_equipment as $item)
                                    <option {{$audit->unit==$item->id?'selected':''}} value="{{$item->id}}">{{$item->unit.' - '.$item->unit_type.' - Last Inspected Date '.$item->last_inspected}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="operator" class="col-form-label">SELECT AN OPERATOR</label>
                            <input required class="form-control" type="text" id="operator" name="operator" value="{{$audit->operator_name}}">
{{--                            <select required id="operator" name="operator" class="custom-select select2">--}}
{{--                                <option></option>--}}
{{--                                @foreach($operators as $item)--}}
{{--                                    <option {{$audit->operator_id==$item->id?"selected":":"}} value="{{$item->id}}">{{$item->operator}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                        </div>

                        <div class="form-group">
                            <label for="location_gate" class="col-form-label">LOCATION, GATE</label>
                            <input class="form-control" type="text" id="location_gate" name="location_gate" value="{{$audit->location_gate}}">
                        </div>

                        <div class="form-group">
                            <label for="audit_type" class="col-form-label">SELECT A TYPE OF AUDIT</label>
                            <select required id="audit_type" name="audit_type" onchange="select_audit(this.value)" class="custom-select select2">
                                <option selected></option>
                                @foreach($audits as $item)
                                    <option {{$audit->audit_type==$item->id?"selected":":"}} value="{{$item->id}}">{{$item->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="audit_question" class="form-group">
                            @if(count($audit_questions)>0)
                                <h6>AUDIT TASK</h6>
                            @endif
                            @php($no=0)
                                <script>
                                    let uploadedDocumentMap = {};
                                </script>
                            @foreach($audit_questions as $item)
                                @php($no++)
                                <div class="form-group p-2" style="background-color: #ffeed3">
                                    <h6 for="audit_question_{{$item->id}}" class="col-form-label font-weight-bold"> {{ $no.'. '.$item->question }}</h6>
                                    <select id="condition_{{$item->id}}" name="condition_{{$item->id}}" class="custom-select">
                                        @foreach($grading_audit as $item1)
                                            <option {{$item1->id==$item->gr_id?'selected':''}} value="{{$item1->id}}">{{$item1->result}}</option>
                                        @endforeach
                                    </select>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="comment_{{$item->id}}" class="col-form-label-sm">Comment</label>
                                            <input name="comment_{{$item->id}}" value="{{$item->comment}}" class="form-control" id="comment_{{$item->id}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="panel-body">
                                                    <label class="text-muted-sm">Images</label>
                                                    <div class="dropzone mb-3" id="files_{{$item->id}}">
                                                        @php($images = json_decode($item->files))
                                                        @if($images)
                                                            @foreach(json_decode($item->files) as $img)
                                                                <div class="dz-preview dz-image-preview" data-img="{{$img}}">
                                                                    <div class="dz-image">
                                                                        <img src="{{asset('uploads/audit/'.$img)}}" style="width: 120px;height: 120px" />
                                                                    </div>
                                                                    <div class="dz-details">
                                                                        <div class="dz-filename"><span data-dz-name="">{{$img}}</span></div>
                                                                    </div>
                                                                    <a class="dz-remove" href="javascript:;" onclick="remove_files('{{$img}}','{{'files_'.$item->id}}')" data-dz-remove="">Remove Image</a>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        <div class="dz-default dz-message"><i class='ti-cloud-up text-secondary' style='font-size:48px'></i><p>Drag and drop a file here or click</p></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    Dropzone.autoDiscover = false;
                                    new Dropzone(document.querySelector("#files_{{$item->id}}"), {
                                        url: "{{ route('audit.upload') }}",
                                        maxFilesize: 24, // MB
                                        maxFiles: 8,
                                        addRemoveLinks: true,
                                        dictRemoveFile:"Remove Image",
                                        dictDefaultMessage:"<i class='ti-cloud-up text-secondary' style='font-size:48px'></i><p>Drag and drop a file here or click</p>",
                                        capture: "camera",
                                        acceptedFiles:"image/*",
                                        headers: {
                                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                        },
                                        success: function (file, response) {
                                            $('form').append('<input type="hidden" name="files_{{$item->id}}[]" value="' + response.name + '">');
                                            uploadedDocumentMap[file.name] = response.name
                                        },
                                        removedfile: function (file) {
                                            file.previewElement.remove();
                                            let name = '';
                                            if (typeof file.file_name !== 'undefined') {
                                                name = file.file_name
                                            } else {
                                                name = uploadedDocumentMap[file.name]
                                            }
                                            $('form').find('input[name="files[]"][value="' + name + '"]').remove()
                                        },
                                        init: function ()
                                        {
                                                    @if(isset($item) && $item->files)
                                            let files = JSON.parse('{!! $item->files !!}');
                                            for (let i in files) {
                                                let file = files[i];
                                                $('form').append('<input type="hidden" name="files_{{$item->id}}[]" value="' + file + '">')
                                            }
                                            @endif
                                        }
                                    });
                                </script>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <label for="overall_result" class="col-form-label">OVERALL RESULT</label>
                            <select id="overall_result" name="overall_result" class="custom-select">
                                @foreach($grading_condition as $item1)
                                    <option {{$item1->id==$audit->overall_result?'selected':''}} value="{{$item1->id}}">{{$item1->result}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="signature-pad" class="col-form-label">Sign Signature</label>
                            <div class="form-group mb-0">
                                <canvas id="signature-pad" class="border border-dark bg-light"></canvas>
                            </div>
                            <button id="clear" type="button" class="btn btn-outline-dark">Clear</button>
                            <input hidden type="text" id="signature" name="signature" value="{{$audit->signature}}">
                        </div>

                        <div class="form-group">
                            <label for="comments" class="col-form-label">COMMENTS</label>
                            <textarea name="comments" class="form-control form-control-lg" type="text"  id="comments">{{ $audit->comments }}</textarea>
                        </div>
                        <div class="form-group">
                            <div class="panel-body">
                                <p class="text-muted">IMAGES</p>
                                <div class="dropzone mb-3" id="images">
                                    @php($images = json_decode($audit->images))
                                    @if($images)
                                        @foreach(json_decode($audit->images) as $img)
                                            <div class="dz-preview dz-image-preview" data-img="{{$img}}">
                                                <div class="dz-image">
                                                    <img src="{{asset('uploads/'.$img)}}" style="width: 120px;height: 120px" />
                                                </div>
                                                <div class="dz-details">
                                                    <div class="dz-filename"><span data-dz-name="">{{$img}}</span></div>
                                                </div>
                                                <a class="dz-remove" href="javascript:;" onclick="remove_files('{{$img}}','images')" data-dz-remove="">Remove Image</a>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="dz-default dz-message"><i class='ti-cloud-up text-secondary' style='font-size:48px'></i><p>Drop images here to upload or click</p></div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Update</button>
                        <a href="{{ route('audit') }}" class="btn btn-outline-danger mt-4 pr-4 pl-4"><i class="ti-reload"> </i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/signature_pad/dist/signature_pad.umd.js') }}"></script>
    <script>
        let signaturePad = new SignaturePad(document.getElementById('signature-pad'));
        signaturePad.fromDataURL('{{$audit->signature}}')
        document.getElementById('clear').addEventListener('click', function () {
            signaturePad.clear();
        });

        $('#save_form').submit(function (event) {
            if (!signaturePad.isEmpty()) {
                let data = signaturePad.toDataURL('image/png');
                $("#signature").val(data);
            }
        });

        function set_date(date) {
            location.href = '{{route('audit.edit',$audit->id)}}'+'?date='+date;
        }
        function select_audit(id) {
            $.get('{{route('audit.change')}}?id='+id, function (data,status) {
                $("#audit_question").html(data);
            });
        }

        let images = '{!! $audit->images !!}';
        if(isValidJson(images)) images = JSON.parse(images);
        else images = [images];
        function isValidJson(json) {
            try {
                JSON.parse(json);
                return true;
            } catch (e) {
                return false;
            }
        }
    </script>
@stop
