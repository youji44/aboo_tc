<div class="card">
    <div class="card-body">
        <h4 class="header-title">Edit Audit</h4>
        @include('notifications')
        <form action="{{ route('settings.audit.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input hidden name="id" value="{{$audit->id}}">
            <div class="form-group">
                <label for="plocation_id" class="col-form-label">PRIMARY LOCATION</label>
                <select id="plocation_id" name="plocation_id" class="custom-select">
                    @foreach($locations as $item)
                        <option {{$item->id==$audit->plocation_id?'selected':''}} value="{{$item->id}}">{{$item->location}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="title" class="col-form-label">NEW AUDIT TITLE</label>
                <input required class="form-control" value="{{$audit->title}}" name="title" id="title">
            </div>

            <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Update</button>
        </form>
    </div>
</div>