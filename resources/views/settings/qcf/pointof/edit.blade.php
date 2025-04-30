<form class="needs-validation"  novalidate="" id="fuel_form" action="{{ route('qcf.settings.pointof.task.save') }}" method="POST">
    @csrf
    <input title="id" hidden name="id" value="{{isset($prevent->id)?$prevent->id:''}}">
    <div id="alert" class="alert alert-warning alert-dismissible fade show" role="alert" style="display: none">
        <strong>Warning:</strong> Please input the Task Item.
    </div>
    <div class="form-group">
        <label for="pid" class="col-form-label mr-3">Primary Location</label>
        <select class="custom-select" name="pid" id="pid">
            @foreach($locations as $item)
                <option {{ isset($prevent->plocation_id) && ($prevent->plocation_id == $item->id)?'selected':''}} value="{{$item->id}}">{{$item->location}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="pid" class="col-form-label mr-3">Category</label>
        <select class="custom-select select2" name="category" id="category">
            @foreach($category as $item)
                <option {{ isset($prevent->category_id) && ($prevent->category_id == $item->id)?'selected':''}} value="{{$item->id}}">{{$item->category}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="task" class="col-form-label mr-3">Task</label>
        <textarea required class="form-control" name="task" id="prevent_task">{{isset($prevent->task)?$prevent->task:''}}</textarea>
    </div>
</form>
<script>
    $(".select2").select2();
    function save_fuel() {
        if($("#prevent_task").val() === '') {
            $("#alert").show(300);
        }
        else {
            $("#alert").hide(200);
            $("#fuel_form").submit();
        }
    }
</script>
