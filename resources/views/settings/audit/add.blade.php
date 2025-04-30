<div class="card">
    <div class="card-body">
        <h4 class="header-title">Add New Audit</h4>
        @include('notifications')
        <form action="{{ route('settings.audit.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="plocation_id" class="col-form-label">PRIMARY LOCATION</label>
                <select id="plocation_id" name="plocation_id" class="custom-select">
                    @foreach($locations as $item)
                        <option value="{{$item->id}}">{{$item->location}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="title" class="col-form-label">NEW AUDIT TITLE</label>
                <input required class="form-control" type="text" name="title" id="title">
            </div>

            <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Save</button>
        </form>
    </div>
</div>