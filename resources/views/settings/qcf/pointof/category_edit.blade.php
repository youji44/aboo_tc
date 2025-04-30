<form class="needs-validation"  novalidate="" action="{{ route('qcf.settings.pointof.category.update') }}" method="POST">
    @csrf
    <input title="id" hidden name="id" value="{{$category?$category->id:''}}">
    <div class="form-group">
        <label for="category" class="col-form-label mr-3">Category</label>
        <input required class="form-control" value="{{$category?$category->category:''}}" name="category" id="category">
    </div>
</form>

