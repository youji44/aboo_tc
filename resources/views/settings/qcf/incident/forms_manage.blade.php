<form class="needs-validation"  novalidate="" action="{{ route('qcf.settings.incident.forms.manage.save') }}" method="POST">
    @csrf
    <input title="id" hidden name="id" value="{{$forms_detail?$forms_detail->id:''}}">
    <input title="id" hidden name="form_id" value="{{$form_id}}">
    <div class="form-group">
        <label for="form_item" class="col-form-label mr-3">Form Item</label>
        <input required class="form-control" value="{{$forms_detail?$forms_detail->item:''}}" name="form_item" id="form_item">
    </div>
    <div class="form-group">
        <label for="description" class="col-form-label mr-3">Form Description</label>
        <textarea class="form-control" name="description" id="description">{{$forms_detail?$forms_detail->description:''}}</textarea>
    </div>
    <div class="form-group">
        @foreach(Utils::form_items() as $key=>$item)
            <div class="custom-control custom-radio">
                <input onclick="select_option('{{$key}}')" {{$forms_detail && $forms_detail->input_type==$key?'checked':''}}{{!$forms_detail && $key==0?'checked':''}} id="input_type_{{$key}}" type="radio" name="input_type" value="{{$key}}" class="custom-control-input">
                <label class="custom-control-label" for="input_type_{{$key}}">{{$item}}</label>
            </div>
        @endforeach
    </div>
    <div class="form-group p-2" id="option_body" {{$forms_detail && $forms_detail->input_type == '4'?'':'hidden'}}>
        <label class="col-form-label-sm">Options:</label>
        <div class="table-responsive">
            <table id="optionTable" class="table align-middle">
                <thead>
                <tr>
                    <th><button onclick="addToOption()" type="button" class="btn btn-success btn-sm">+</button></th>
                    <th scope="col" style="width: 5%">#</th>
                    <th scope="col">Option Fields</th>
                </tr>
                @if(isset($form_details_options))
                    @foreach($form_details_options as $key=>$item)
                        <tr>
                            <td><button onclick="remove_cart('{{$key+1}}')" type="button" class="btn btn-danger waves-effect waves-light shadow-none" style="width: 34px">-</button></td>
                            <td>{{$key+1}}</td>
                            <td><input title="" required style="min-width: 100px;" class="form-control" name="options[]" value="{{$item->name}}"></td>
                        </tr>
                    @endforeach
                @endif
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" {{($forms_detail && $forms_detail->required==1)?'checked="checked"':''}} class="custom-control-input" name="required" id="required">
            <label class="custom-control-label" for="required">Make Mandatory Field</label>
        </div>
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

    function select_option(val){
        if(val === '4'){
            $("#option_body").removeAttr('hidden');
        }else{
            $("#option_body").attr('hidden','hidden');
        }
    }

    function addOption() {
        const optionsDiv = document.getElementById('options');
        const newInput = document.createElement('input');
        newInput.class = 'form-control';
        newInput.type = 'text';
        newInput.name = 'options[]';
        optionsDiv.appendChild(newInput);
    }

    var optionTable = document.getElementById('optionTable');
    var no = '{{isset($form_details_options)?count($form_details_options)+1:1}}';
    // function to add a new item to the shopping cart
    window.addToOption = function () {
        let row = optionTable.insertRow(-1);
        let btn = row.insertCell(0);
        let num = row.insertCell(1);
        let sku = row.insertCell(2);
        btn.innerHTML = remove_btn(row.rowIndex);
        num.innerHTML = no;
        sku.innerHTML = '<input required style="min-width: 100px;" class="form-control" name="options[]" placeholder="Please input a field name...">';
        no ++ ;
    }
    window.remove_cart = function(rowNumber){
        let rows = optionTable.rows;
        if (rowNumber > 0 && rowNumber < rows.length && rows.length > 1) {
            optionTable.deleteRow(rowNumber); // Remove the row from the table
            // Update the row numbers in the table
            for (let i = 1; i < rows.length; i++) {
                rows[i].cells[1].innerHTML = i;
                rows[i].cells[0].innerHTML = remove_btn(i);
            }
            no = rows.length;
        }
    }

    function remove_btn(rowNumber){
        return '<button onclick="remove_cart('+rowNumber+')" type="button" class="btn btn-danger btn-sm" style="width: 34px">-</button>';
    }

</script>
