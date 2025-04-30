<div class="form-group1">
    <label class="col-form-label mr-3">Date:</label>
    <label class="col-form-label">{{ date('Y-m-d',strtotime($incident_reporting->date)) }}</label>
</div>
<div class="form-group1">
    <label class="col-form-label mr-3">Time:</label>
    <label class="col-form-label">{{ date('H:i',strtotime($incident_reporting->time)) }}</label>
</div>
<div class="form-group1">
    <label class="col-form-label mr-3">LOCATION:</label>
    <label class="col-form-label">{{$incident_reporting->location_name}}-{{$incident_reporting->location_code}}</label>
</div>
<div class="form-group1">
    <label class="col-form-label mr-3">DEPARTMENT:</label>
    <label class="col-form-label">{{$incident_reporting->department_name}}</label>
</div>
<div class="form-group1">
    <label class="col-form-label mr-3">INCIDENT TITLE OR DESCRIPTION:</label>
    <label class="col-form-label-sm">{{$incident_reporting->incident_title}}</label>
</div>
<div class="form-group1">
    <label class="col-form-label mr-3">INCIDENT NOTIFICATIONS:</label>
    <label class="col-form-label-sm">{{$incident_reporting->notifications}}</label>
</div>
<div class="form-group1">
    <label class="col-form-label mr-3">TYPE OF INCIDENT:</label>
    <label class="col-form-label" style="color:{{$incident_reporting->color}}">{{$incident_reporting->type}}</label>
</div>

@foreach($form_details as $group)
    <div class="big-group">
        <h6 class="col-form-label mt-2">{{ $group['form_name'] }}</h6>
        @foreach($group['rows'] as $row)
            @if($row->input_type == '0')
                <div class="form-group1">
                    <label class="col-form-label mr-3">{{$row->item}}:</label>
                    <label class="col-form-label">{{$row->date_time}}</label>
                </div>
            @endif
            @if($row->input_type == '1')
                    <div class="form-group1">
                        <label class="col-form-label mr-3">{{$row->item}}:</label>
                        <label class="col-form-label">{{$row->number_field}}</label>
                    </div>
            @endif
            @if($row->input_type == '2')
                    <div class="form-group1">
                        <label class="col-form-label mr-3">{{$row->item}}:</label>
                        <label class="col-form-label">{{$row->text_field}}</label>
                    </div>
            @endif
            @if($row->input_type == '3')
                    <div class="form-group1">
                        <label class="col-form-label mr-3">{{$row->item}}:</label>
                        <label class="col-form-label" >{!! $row->textarea_field !!}</label>
                    </div>
            @endif
            @if($row->input_type == '4')
                <div class="form-group1">
                    <label class="col-form-label mr-3">{{$row->item}}:</label>
                    <label class="col-form-label">{{$row->selection_field}}</label>
                </div>
            @endif
            @if($row->input_type == '5')
                <div class="form-group1">
                    @if($row->image_field != null)
                        @if(json_decode($row->image_field))
                            <div class="row">
                                <label class="col-2 col-form-label">{{$row->item}}:</label>
                                <label class="col-10 col-form-label">
                                    @foreach(json_decode($row->image_field) as $image)
                                        <a class="gallery" data-fancybox="gallery" href="{{asset('/uploads/'.$image)}}">
                                            <img alt="Img" style="height:80px;padding: 4px" src="{{asset('/uploads/'.$image)}}"></a>
                                    @endforeach
                                </label>
                            </div>
                        @endif
                    @endif
                </div>
            @endif
            @if($row->input_type == '6')
                <div class="form-group1">
                    <label class="col-form-label mr-3">{{$row->item}}:</label>
                    <label class="col-form-label text-{{$row->gr_color}}">{{$row->gr_result}}</label>
                </div>
            @endif
        @endforeach
    </div>
@endforeach

<div class="row"><label class="col-4 control-label">MECHANIC:</label>
    <label class="col-8 control-label">
        <a href="https://www.google.com/maps/search/{{$incident_reporting->geo_latitude}},{{$incident_reporting->geo_longitude}}" target="_blank">{{$incident_reporting->user_name}}
            <i class="ti-location-pin"></i>
        </a>
    </label></div>

<div class="row"><label class="col-4 control-label">STATUS:</label>
    <label class="col-8 control-label">
        <span class="status-p bg-{{$incident_reporting->w_status==1?'success':'warning'}}">{{$incident_reporting->w_status==1?'Checked':'Pending'}}</span>
    </label>
</div>
@if($incident_reporting->images != null)
    @if(json_decode($incident_reporting->images))
        <div class="row">
            <label class="col-2 col-form-label">Images:</label>
            <label class="col-10 col-form-label">
                @foreach(json_decode($incident_reporting->images) as $image)
                    <a class="gallery" data-fancybox="gallery" href="{{asset('/uploads/'.$image)}}">
                        <img alt="Img" style="height:80px;padding: 4px" src="{{asset('/uploads/'.$image)}}"></a>
                @endforeach
            </label>
        </div>
    @else
        <div class="row"><label class="col-4 control-label">Images:</label>
            <a class="gallery" data-fancybox="gallery" href="{{asset('/uploads/'.$incident_reporting->images)}}">
                <img alt="Img" style="height:80px" src="{{asset('/uploads/'.$incident_reporting->images)}}"></a>
        </div>
    @endif
@endif
