<form class="needs-validation"  novalidate="" action="{{ route('qcf.settings.incident.type.save') }}" method="POST">
    @csrf
    <input title="id" hidden name="id" value="{{$incident_type?$incident_type->id:''}}">
    <div class="form-group">
        <label for="type" class="col-form-label mr-3">Incident Type</label>
        <input required class="form-control" value="{{$incident_type?$incident_type->type:''}}" name="type" id="type">
    </div>
    <div class="form-group">
        <label for="color" class="col-form-label mr-3">Color</label>
        <input required class="form-control" value="{{$incident_type?$incident_type->color:'#e3524a'}}" name="color" id="color" type="color">
    </div>
    <div class="form-group">
        <label class="col-form-label" for="forms">Select Forms</label>
        @foreach($forms as $item1)
            <div class="custom-control custom-checkbox">
                <input type="checkbox" {{$incident_type && in_array($item1->id, json_decode($incident_type->forms)??[])?'checked="checked"':''}} class="custom-control-input" value="{{$item1->id}}" name="forms[]" id="forms_{{$item1->id}}">
                <label class="custom-control-label" for="forms_{{$item1->id}}">{{$item1->form_name}}</label>
            </div>
        @endforeach
    </div>
    <div class="form-group float-right">
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>
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
</script>
