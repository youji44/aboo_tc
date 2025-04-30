<form class="needs-validation" novalidate="" action="{{route('incident.reporting.check')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <input title="" hidden value="{{isset($incident_approve)?$incident_approve->id:''}}" name="id">
    <div class="form-group">
        <label for="root_cause" class="col-form-label">ROOT CAUSE</label>
        <textarea name="root_cause" class="form-control" id="root_cause">{{isset($incident_approve)?$incident_approve->root_cause:''}}</textarea>
    </div>
    <div class="form-group">
        <label for="corrective_actions" class="col-form-label">CORRECTIVE ACTIONS</label>
        <textarea name="corrective_actions" class="form-control" id="corrective_actions">{{isset($incident_approve)?$incident_approve->corrective_actions:''}}</textarea>
    </div>
    <div class="form-group">
        <label for="preventive_actions" class="col-form-label">PREVENTIVE ACTIONS</label>
        <textarea name="preventive_actions" class="form-control" id="preventive_actions">{{isset($incident_approve)?$incident_approve->preventive_actions:''}}</textarea>
    </div>
    <div class="form-group">
        <div class="panel-body">
            <p class="text-muted">ADDITIONAL IMAGES</p>
            <div class="dropzone mb-3" id="additional_images">

                @if(isset($incident_approve) && $incident_approve->additional_images)
                    @if($images = json_decode($incident_approve->additional_images))
                        @foreach($images as $img)
                            <div class="dz-preview dz-image-preview" data-img="{{$img}}">
                                <div class="dz-image">
                                    <img alt="" src="{{asset('uploads/'.$img)}}" style="width: 120px;height: 120px" />
                                </div>
                                <div class="dz-details">
                                    <div class="dz-filename"><span data-dz-name="">{{$img}}</span></div>
                                </div>
                                <a class="dz-remove" href="javascript:" onclick="remove_files('{{$img}}','images')" data-dz-remove="">Remove Image</a>
                            </div>
                        @endforeach
                    @endif
                    <div class="dz-default dz-message"><i class='ti-cloud-up text-secondary' style='font-size:48px'></i><p>Drop images here to upload or click</p></div>
                @endif
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-success mt-4 pr-4 pl-4 float-right"><i class="ti-save"> </i> Save</button>
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

    let images = '{!! isset($incident_reporting)?$incident_reporting->additional_images:'' !!}';
    if(isValidJson(images)) images = JSON.parse(images);
    else images = [images];
    function isValidJson(json) {
        try {
            JSON.parse(json);
            return true;
        } catch (e) {
            return false;
        }
    }

    if($("div#additional_images").length > 0){
        Dropzone.autoDiscover = false;
        new Dropzone(document.querySelector("#additional_images"), {
            url: "{{ route('images.upload') }}",
            maxFilesize: 24, // MB
            maxFiles: 24,
            addRemoveLinks: true,
            dictRemoveFile:"Remove Image",
            dictDefaultMessage:"<i class='ti-cloud-up text-secondary' style='font-size:48px'></i><p>Drag and drop a file here or click</p>",
            capture: "camera",
            acceptedFiles:"image/*",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="additional_images[]" value="' + response.name + '">');
                uploaded[file.name] = response.name
            },
            error: function(file, message) {
                console.log(message);
            },
            removedfile: function (file) {
                file.previewElement.remove();
                let name = '';
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploaded[file.name]
                }
                $('form').find('input[name="additional_images[]"][value="' + name + '"]').remove()
            },
            init: function () {
                if(images) {
                    if(Array.isArray(images)) {
                        images.forEach(function (img) {
                            if(img !== "")
                                $('form').append('<input type="hidden" name="additional_images[]" value="' + img + '">')
                        })
                    }
                }
            }
        });
    }
</script>
