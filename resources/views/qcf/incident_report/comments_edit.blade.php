<style>
    .comments-body{
        max-height: 360px;
        overflow-y: auto;
    }
</style>
@if($mode == 'view')
    <div class="comments-body">
        @foreach($comments as $item)
            <div class="form-group">
                <label for="root_cause" class="col-form-label-sm">{{$item->comments}}</label>
                <p>Commented by {{$item->user_name}} on {{date('Y-m-d',strtotime($item->date))}} at {{date('H:i', strtotime($item->time))}}</p>
            </div>
            <hr>
        @endforeach
    </div>
@endif

@if($mode == 'add')
    <div class="comments-body">
        @foreach($comments as $item)
            <div class="form-group">
                <label class="col-form-label-sm">{{$item->comments}}</label>
                <p>Commented by {{$item->user_name}} on {{date('Y-m-d',strtotime($item->date))}} at {{date('H:i', strtotime($item->time))}}</p>
            </div>
            <hr>
        @endforeach
    </div>
    <form class="needs-validation" novalidate="" action="{{route('incident.reporting.comments.save')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input title="" hidden value="{{$id}}" name="rid">
        <div class="form-group">
            <label for="additional_comments" class="col-form-label">Comments</label>
            <textarea required name="additional_comments" class="form-control" id="additional_comments"></textarea>
        </div>
        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4 float-right"><i class="ti-save"> </i> Save</button>
    </form>
@endif

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
