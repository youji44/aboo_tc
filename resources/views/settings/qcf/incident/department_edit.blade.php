<form class="needs-validation"  novalidate="" action="{{ route('qcf.settings.incident.department.save') }}" method="POST">
    @csrf
    <input title="id" hidden name="id" value="{{$incident_department?$incident_department->id:''}}">
    <div class="form-group">
        <label for="department_name" class="col-form-label mr-3">Department Name</label>
        <input required class="form-control" value="{{$incident_department?$incident_department->department_name:''}}" name="department_name" id="department_name">
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
