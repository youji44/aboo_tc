<form class="needs-validation"  novalidate="" action="{{ route('qcf.settings.incident.location.save') }}" method="POST">
    @csrf
    <input title="id" hidden name="id" value="{{$incident_location?$incident_location->id:''}}">
    <div class="form-group">
        <label class="col-form-label">Primary Location</label>
        <select id="pid" name="pid" class="custom-select">
            @foreach($locations as $item)
                <option {{$incident_location?($incident_location->pid==$item->id?'selected':''):''}} value="{{$item->id}}">{{$item->location}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="location_name" class="col-form-label mr-3">Location</label>
        <input required class="form-control" value="{{$incident_location?$incident_location->location_name:''}}" name="location_name" id="location_name">
    </div>
    <div class="form-group">
        <label for="location_code" class="col-form-label mr-3">Location Code</label>
        <input class="form-control" value="{{$incident_location?$incident_location->location_code:''}}" name="location_code" id="location_code">
    </div>
    <div class="form-group">
        <label for="location_latitude" class="col-form-label mr-3">Location Latitude</label>
        <input required class="form-control" value="{{$incident_location?$incident_location->location_latitude:''}}" name="location_latitude" id="location_latitude">
    </div>
    <div class="form-group">
        <label for="location_longitude" class="col-form-label mr-3">Location Longitude</label>
        <input required class="form-control" value="{{$incident_location?$incident_location->location_longitude:''}}" name="location_longitude" id="location_longitude">
    </div>
    <div class="form-group">
        <label for="map" class="col-form-label">Google Map</label>
        <div id="map" style="height: 300px;width: auto"></div>
    </div>
    <div class="form-group float-right">
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrPmrGVt96gp4gQSRmBYdLYw05jdg4KnM&callback=initMap&v=weekly" async defer></script>
<script>
    // Initialize and add the map
    const lat = "{{$incident_location?$incident_location->location_latitude:49.1968531}}";
    const lng = "{{$incident_location?$incident_location->location_longitude:-123.1751411}}";

    function initMap() {
        const center_loc = { lat: parseFloat(lat), lng: parseFloat(lng) };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 16,
            center: center_loc,
            streetViewControl: false,
            linksControl: false,
            panControl: false,
            addressControl: false,
            zoomControl: false,
            fullScreenControl: false,
            enableCloseButton: false,
            disableDefaultUI: true,
            mapTypeId: 'satellite'
        });
        const marker = new google.maps.Marker({
            position: center_loc,
            map: map,
        });
        map.addListener("click", (mapsMouseEvent) => {
            let latLng = mapsMouseEvent.latLng.toJSON();
            $("#location_latitude").val(latLng.lat);
            $("#location_longitude").val(latLng.lng);
            marker.setPosition(mapsMouseEvent.latLng)
        });
    }
    window.initMap = initMap;

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
</script>
