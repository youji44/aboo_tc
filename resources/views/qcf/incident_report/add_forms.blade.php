<script>
    let images2 = null;
</script>
<script src="{{ asset('assets/select2/dist/js/select2.full.min.js') }}"></script>
@foreach($form_details as $group)
    <div class="big-group">
        <h6 class="mt-2">{{ $group['form_name'] }}</h6>
        @foreach($group['rows'] as $row)
            @if($row->input_type == '0')
                <div class="form-group">
                    <label for="datetime_{{$row->id}}" class="col-form-label">{{$row->item}}</label>
                    <input {{$row->required?'required':''}} id="datetime_{{$row->id}}" class="form-control" type="datetime-local" value="{{isset($row->date_time)?$row->date_time:date('Y-m-d H:i')}}" name="datetime_{{$row->id}}">
                </div>
            @endif
            @if($row->input_type == '1')
                <div class="form-group">
                    <label for="number_field_{{$row->id}}" class="col-form-label">{{$row->item}}</label>
                    <input {{$row->required?'required':''}} id="number_field_{{$row->id}}" class="form-control" type="number" step="0.01" value="{{isset($row->number_field)?$row->number_field:''}}" name="number_field_{{$row->id}}">
                </div>
            @endif
            @if($row->input_type == '2')
                <div class="form-group">
                    <label for="text_field_{{$row->id}}" class="col-form-label">{{$row->item}}</label>
                    <input {{$row->required?'required':''}} id="text_field_{{$row->id}}" class="form-control" type="text" value="{{isset($row->text_field)?$row->text_field:''}}" name="text_field_{{$row->id}}">
                </div>
            @endif
            @if($row->input_type == '3')
                <div class="form-group">
                    <label for="textarea_field_{{$row->id}}" class="col-form-label">{{$row->item}}</label>
                    <textarea {{$row->required?'required':''}} rows="2" name="textarea_field_{{$row->id}}" class="form-control" id="textarea_field_{{$row->id}}">{{isset($row->textarea_field)?$row->textarea_field:''}}</textarea>
                </div>
                <script>
                    var tid = '#textarea_field_'+'{{$row->id}}';
                    if($('textarea').length > 0 && $(tid).length){
                        ClassicEditor
                            .create( document.querySelector( tid ) )
                            .then( function(editor) {
                                ck_editor = editor;
                                editor.ui.view.editable.element.style.height = '150px';
                            } )
                            .catch( function(error) {
                                console.error( error );
                            } );
                    }
                </script>
            @endif
            @if($row->input_type == '4')
                <div class="form-group mt-3 mb-1">
                    <label class="col-form-label">{{$row->item}}</label>
                </div>
                @foreach($row->form_details_options as $key=>$op)
                <div class="custom-control custom-radio">
                    <input {{isset($row->selection_field) && $row->selection_field == $op->value?'checked':''}} {{!isset($row->selection_field) && $key==0?'checked':''}} id="multiple_option_{{$op->form_detail_id}}_{{$op->value}}" type="radio" name="multiple_option_{{$op->form_detail_id}}" value="{{$op->value}}" class="custom-control-input">
                    <label class="custom-control-label" for="multiple_option_{{$op->form_detail_id}}_{{$op->value}}">{{$op->name}}</label>
                </div>
                @endforeach
            @endif
            @if($row->input_type == '5')
                <div class="form-group">
                    <div class="panel-body">
                        <p class="text-muted">{{$row->item}}</p>
                        <div class="dropzone mb-3" id="uploader_images_{{$row->id}}">
                            @if(isset($row->image_field))
                                @if($images = json_decode($row->image_field))
                                    @foreach($images as $img)
                                        <div class="dz-preview dz-image-preview" data-img="{{$img}}">
                                            <div class="dz-image">
                                                <img alt="" src="{{asset('uploads/'.$img)}}" style="width: 120px;height: 120px" />
                                            </div>
                                            <div class="dz-details">
                                                <div class="dz-filename"><span data-dz-name="">{{$img}}</span></div>
                                            </div>
                                            <a class="dz-remove" href="javascript:" onclick="remove_files('{{$img}}','uploader_images_{{$row->id}}')" data-dz-remove="">Remove Image</a>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="dz-default dz-message"><i class='ti-cloud-up text-secondary' style='font-size:48px'></i><p>Drop images here to upload or click</p></div>
                            @endif
                        </div>
                    </div>
                </div>
                <script>
                    images2 = '{!!isset($row->image_field)?$row->image_field:'' !!}';
                    if(isValidJson(images2)) images2 = JSON.parse(images2);
                    else images2 = [images2];
                    console.log(images2)
                    function isValidJson(json) {
                        try {
                            JSON.parse(json);
                            return true;
                        } catch (e) {
                            return false;
                        }
                    }
                    if($("div#uploader_images_{{$row->id}}").length > 0){
                        let uploaded = {};
                        Dropzone.autoDiscover = false;
                        new Dropzone(document.querySelector("#uploader_images_{{$row->id}}"), {
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
                                $('form').append('<input type="hidden" name="uploader_images_{{$row->id}}[]" value="' + response.name + '">');
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
                                $('form').find('input[name="uploader_images_{{$row->id}}[]"][value="' + name + '"]').remove()
                            },
                            init: function () {
                                if(images2) {
                                    if(Array.isArray(images2)) {
                                        images2.forEach(function (img) {
                                            if(img !== "")
                                                $('#uploader_images_{{$row->id}}').append('<input type="hidden" name="uploader_images_{{$row->id}}[]" value="' + img + '">')
                                        })
                                    }
                                }
                            }
                        });
                    }
                </script>
            @endif
            @if($row->input_type == '6')
                <div class="form-group">
                    <label for="condition_field_{{$row->id}}" class="col-form-label">{{$row->item}}</label>
                    <select {{$row->required?'required':''}} id="condition_field_{{$row->id}}" name="condition_field_{{$row->id}}" class="custom-select">
                        @foreach($grading_condition as $item)
                            <option {{isset($row->condition_field) && $row->condition_field==$item->id?'selected':''}} value="{{$item->id}}">{{$item->result}}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        @endforeach
    </div>

@endforeach


