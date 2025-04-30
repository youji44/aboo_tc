<style>
    .dropify-wrapper{
        height: 80px;
    }
</style>
@if(count($audit_questions)>0)
<h6>AUDIT TASK</h6>
    <script>
        let uploadedDocumentMap = {};
    </script>
@endif
@php($no=0)
@foreach($audit_questions as $item)
    @php($no++)
    <div class="form-group p-2" style="background-color: #e9ecef">
        <h6 for="audit_question_{{$item->id}}" class="col-form-label font-weight-bold"> {{ $no.'. '.$item->question }}</h6>
        <select id="condition_{{$item->id}}" name="condition_{{$item->id}}" class="custom-select">
            @foreach($grading_audit as $item1)
                <option value="{{$item1->id}}">{{$item1->result}}</option>
            @endforeach
        </select>
        <div class="row">
            <div class="col-md-12">
                <label for="comment_{{$item->id}}" class="col-form-label-sm">Comment</label>
                <input name="comment_{{$item->id}}" class="form-control" id="comment_{{$item->id}}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="panel-body">
                        <label class="text-muted-sm">Images</label>
                        <div class="mt-40">
                            <div class="dropzone mb-3" id="files_{{$item->id}}"></div>
                            {{--<input type="file" name="files_{{$item->id}}" id="files_{{$item->id}}" accept="image/*" capture="camera" class="dropify" />--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        Dropzone.autoDiscover = false;
        new Dropzone(document.querySelector("#files_{{$item->id}}"),{
            url: "{{ route('audit.upload') }}",
            maxFilesize: 24, // MB
            maxFiles: 4,
            addRemoveLinks: true,
            dictRemoveFile:"Remove Image",
            dictDefaultMessage:"<i class='ti-cloud-up text-secondary' style='font-size:48px'></i><p>Drag and drop a file here or click</p>",
            capture: "camera",
            acceptedFiles:"image/*",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="files_{{$item->id}}[]" value="' + response.name + '">');
                uploadedDocumentMap[file.name] = response.name
            },
            removedfile: function (file) {
                file.previewElement.remove();
                let name = '';
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $('form').find('input[name="files_{{$item->id}}[]"][value="' + name + '"]').remove()
            },
            init: function () {

            }
        });
    </script>
@endforeach
<script>
    /* Basic Init*/
    $('.dropify').dropify();
</script>


