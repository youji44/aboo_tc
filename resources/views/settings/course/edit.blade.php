<form class="needs-validation"  novalidate="" action="{{ route('settings.course.save') }}" method="POST">
    @csrf
    <input title="id" hidden name="id" value="{{isset($course)?$course->id:'0'}}">
    <div class="form-group">
        <label for="form_name" class="col-form-label mr-3"> Course Title </label>
        <input required class="form-control" value="{{isset($course)?$course->course_title:''}}" name="course_title" id="course_title" placeholder="Please input a course title">
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
