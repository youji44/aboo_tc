@extends('layouts.layout')
{{-- Page title --}}
@section('title')
    Incident Report
@stop
{{-- page level styles --}}
@section('header_styles')
    <style>
        .big-group{
            padding:.5rem !important;
            margin-bottom: 0.5rem;
            background-color: #f0f0f0;
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Incident Report > Add New</h4>
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
                    <h4 class="header-title">Add a new Incident Report</h4>
                    @include('notifications')
                    <form class="needs-validation" novalidate="" action="{{route('incident.reporting.save')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input title="" hidden value="{{isset($incident_reporting)?$incident_reporting->id:''}}" name="id">
                        <div class="form-group">
                            <label for="date" class="col-form-label">Date</label>
                            <input id="date" class="form-control" type="date" value="{{isset($incident_reporting)?$incident_reporting->date:date('Y-m-d')}}" placeholder="2022-12-05" name="date">
                        </div>
                        <div class="form-group">
                            <label for="time" class="col-form-label">Time</label>
                            <input class="form-control" type="time" value="{{isset($incident_reporting)?$incident_reporting->time:date('H:i')}}" placeholder="00:00" id="time" name="time">
                        </div>
                        <div class="form-group">
                            <label for="type" class="col-form-label">SELECT A LOCATION</label>
                            <select required id="location_id" name="location_id" class="custom-select select2">
                                <option></option>
                                @foreach($settings_incident_locations as $item)
                                    <option {{isset($incident_reporting) && $incident_reporting->location_id==$item->id?'selected':''}} value="{{$item->id}}">{{$item->location_name}} - {{$item->location_code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type" class="col-form-label">SELECT A DEPARTMENT</label>
                            <select required id="department_id" name="department_id" class="custom-select select2">
                                <option></option>
                                @foreach($settings_incident_departments as $item)
                                    <option {{isset($incident_reporting) && $incident_reporting->department_id==$item->id?'selected':''}}  value="{{$item->id}}">{{$item->department_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                                <label for="incident_title" class="col-form-label">Incident Title or Description</label>
                            <textarea rows="2" name="incident_title" class="form-control" id="incident_title">{{isset($incident_reporting)?$incident_reporting->incident_title:''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="notifications">Select Notifications</label>
                            <select required id="notifications" name="notifications[]" class="form-control select2 select2-multiple" multiple="multiple" data-placeholder="Choose...">
                                @foreach($settings_incident_notification as $item)
                                    <option value="{{$item->id}}">{{$item->notification}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="incident_type" class="col-form-label">TYPE OF INCIDENT</label>
                            <select required id="incident_type" name="incident_type" class="custom-select">
                                <option></option>
                                @foreach($settings_incident_type as $item)
                                    <option {{isset($incident_reporting) && $incident_reporting->incident_type==$item->id?'selected':''}} value="{{$item->id}}">{{$item->type}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="forms-body">
                            @if(isset($incident_reporting))
                                @include('qcf.incident_report.add_forms')
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="comments" class="col-form-label">Comments</label>
                            <textarea name="comments" class="form-control" id="comments">{{isset($incident_reporting)?$incident_reporting->comments:''}}</textarea>
                        </div>
                        <div class="form-group">
                            <div class="panel-body">
                                <p class="text-muted">IMAGES</p>
                                <div class="dropzone mb-3" id="images">
                                    @if(isset($incident_reporting) && $incident_reporting->images)
                                        @if($images = json_decode($incident_reporting->images))
                                            @foreach($images as $img)
                                                <div class="dz-preview dz-image-preview" data-img="{{$img}}">
                                                    <div class="dz-image">
                                                        <img alt="" src="{{asset('uploads/'.$img)}}" style="width: 120px;height: 120px" />
                                                    </div>
                                                    <div class="dz-details">
                                                        <div class="dz-filename"><span data-dz-name="">{{$img}}</span></div>
                                                    </div>
                                                    <a class="dz-remove" href="javascript:" onclick="remove_files('{{$img}}','images')" data-dz-remove="">Remove Image</a>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="dz-default dz-message"><i class='ti-cloud-up text-secondary' style='font-size:48px'></i><p>Drop images here to upload or click</p></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Save</button>
                        <a href="{{ route('incident.reporting') }}" class="btn btn-outline-danger mt-4 pr-4 pl-4"><i class="ti-reload"> </i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="inspect_detail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="inspect_title" class="modal-title">Modal Title</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div id="inspect_body" class="modal-body" style="min-height: 240px">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script>
        $(document).ready(function() {

        });

        let images = '{!! isset($incident_reporting)?$incident_reporting->images:'' !!}';
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

        $('#incident_type').change(function() {
            let url = '{{route('incident.reporting.add_forms')}}'+'?tid='+this.value+'&rid='+'{{isset($incident_reporting)?$incident_reporting->id:''}}'
            $.get(url, function (data) {
                $("#forms-body").html(data);
            });
        });

        @if(isset($incident_reporting->notifications))
        $("#notifications").select2().val(JSON.parse('{!! $incident_reporting->notifications !!}')).trigger("change");
        @else
        $("#notifications").select2().val('No notifications').trigger("change");
        @endif

    </script>
@stop
