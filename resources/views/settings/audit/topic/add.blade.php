<div class="card">
    <div class="card-body">
        <h4 class="header-title">Add New Audit Question</h4>
        @include('notifications')
        <form action="{{ route('settings.audit.topic.save') }}" method="POST">
            @csrf
            <input hidden name="audit_id" value="{{$aid}}">
            <div class="form-group">
                <label for="question" class="col-form-label">NEW AUDIT QUESTION</label>
                <textarea required name="question" class="form-control form-control-lg" id="question"></textarea>
            </div>
            <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Save</button>
        </form>
    </div>
</div>
