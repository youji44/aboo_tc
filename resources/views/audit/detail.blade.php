<div class="form-group">
    <label class="col-form-label mr-3">AIRLINE/CUSTOMER:</label>
    <label class="col-form-label">
            @if(isset($audit->logo))<img alt="{{$audit->airline_name}}" style="max-height:80px;max-width:240px; padding: 4px" src="{{asset('/uploads/settings/'.$audit->logo)}}">@endif</label>
</div>
<div class="form-group">
    <label class="col-form-label mr-3">TYPE OF AIRCRAFT:</label>
    <label class="col-form-label">{{$audit->refuelled}}</label>
</div>
<div class="form-group">
    <label class="col-form-label mr-3">FLIGHT NUMBER OR AIRCRAFT REGISTRATION:</label>
    <label class="col-form-label">{{$audit->flight_number}}</label>
</div>

<div class="form-group">
    <label class="col-form-label mr-3">LOCATION, GATE:</label>
    <label class="col-form-label">{{$audit->location_gate}}</label>
</div>

<div class="form-group">
    <label class="col-form-label mr-3">FUEL EQUIPMENT UNIT#:</label>
    <label class="col-form-label">{{$audit->fe_unit.' - '.$audit->unit_type}}</label>
</div>

<div id="audit_question">
    <div class="sub-group form-group">
        <h6>AUDIT TASK</h6>
        @php($no=0)
        @foreach($audit_questions as $item)
            @php($no++)
            <div class="form-group p-2" style="background-color: #ffeed3">
                <h6 class="col-form-label font-weight-bold"> {{ $no.'. '.$item->question }}</h6>
                <p class="col-form-label text-{{$item->gr_color}}"> {{ $item->gr_result}}</p>
                <label for="comment_{{$item->id}}" class="col-form-label-sm">Comment:</label>
                <label class="col-form-label">{{$item->comment?$item->comment:' -'}}</label>
                @if($item->files != null && json_decode($item->files))
                    <div class="row">
                        <label class="col-2 col-form-label-sm">Images:</label>
                        <label class="col-10 col-form-label">
                            @foreach(json_decode($item->files) as $image)
                                <a class="gallery" data-fancybox="gallery" href="{{asset('/uploads/audit/'.$image)}}">
                                    <img alt="Image" style="height:80px;padding: 4px" src="{{asset('/uploads/audit/'.$image)}}"></a>
                            @endforeach
                        </label>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
<div class="form-group">
    <label class="col-form-label mr-3">OVERALL RESULT:</label>
    <label class="col-form-label text-{{$audit->gr_color}}">{{$audit->gr_result}}</label>
</div>
<div class="form-group">
    <label class="col-form-label mr-3">COMMENTS:</label>
    <label>{!! $audit->comments !!}</label>
</div>
<div class="form-group">
    <label class="col-form-label mr-3">OPERATOR NAME:</label>
    <label>{{ $audit->o_operator }}</label>
</div>
<div class="form-group">
    <label class="col-form-label mr-3">AUDITOR NAME:</label>
    <label class="col-form-label">
        <a href="https://www.google.com/maps/search/{{$audit->geo_latitude}},{{$audit->geo_longitude}}" target="_blank">{{$audit->user_name}}
            <i class="ti-location-pin"></i>
        </a>
    </label>
</div>
<div class="form-group">
    <label class="col-form-label mr-3">SIGNATURE:</label>
    <label class="col-form-label">
        @if($audit->signature)
        <a class="gallery" data-fancybox="gallery" href="{{$audit->signature}}">
            <img alt="Signature" style="height:50px;padding: 4px" src="{{$audit->signature}}"></a>
        @endif
    </label>
</div>

@if($audit->images != null && json_decode($audit->images))
    <div class="row">
        <label class="col-2 col-form-label">Images:</label>
        <label class="col-10 col-form-label">
            @foreach(json_decode($audit->images) as $image)
                <a class="gallery" data-fancybox="gallery" href="{{asset('/uploads/'.$image)}}">
                    <img alt="Img" style="height:80px;padding: 4px" src="{{asset('/uploads/'.$image)}}"></a>
            @endforeach
        </label>
    </div>
@endif
