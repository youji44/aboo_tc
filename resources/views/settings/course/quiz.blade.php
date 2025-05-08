
<h6 class="card-title">{{isset($quiz)?'Edit a ':'Add new'}} Question</h6>
<form class="needs-validation"  novalidate="" action="{{ route('settings.course.quiz.save') }}" method="POST">
    @csrf
    <input title="id" hidden name="id" value="{{isset($quiz)?$quiz->id:''}}">
    <input title="course_id" hidden name="cid" value="{{$course->id}}">
    <div class="form-group">
        <label for="question" class="col-form-label mr-3">Question</label>
        <textarea class="form-control" required name="question" id="description">{{isset($quiz)?$quiz->question:''}}</textarea>
{{--        <div class="editor-container editor-container_classic-editor editor-container_include-style" id="editor-container">--}}
{{--            <div class="editor-container__editor">--}}
{{--                <textarea required name="question" id="description">{{isset($quiz)?$quiz->question:''}}</textarea>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
    <div class="form-group p-2" id="option_body">
        <label class="col-form-label-sm">OPTIONS:</label>
        <div class="table-responsive">
            <table id="optionTable" class="table align-middle">
                <thead>
                <tr>
                    <th><button onclick="addToOption()" type="button" class="btn btn-success btn-sm">+</button></th>
                    <th scope="col" style="width: 5%">#</th>
                    <th scope="col">Option Fields</th>
                    <th scope="col">Correct Answer</th>
                </tr>
                @if(isset($course_quiz_options))
                    @foreach($course_quiz_options as $key=>$item)
                        <tr>
                            <td><button onclick="remove_cart('{{$key+1}}')" type="button" class="btn btn-danger" style="width: 34px">-</button></td>
                            <td>{{$key+1}}</td>
                            <td><input title="" required style="min-width: 100px;" class="form-control" name="options[]" value="{{$item->name}}"></td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input {{$item->correct==$item->value?'checked':''}} type="radio" id="answers_{{$item->id}}" name="answer" value="{{$item->value}}" class="custom-control-input">
                                    <label class="custom-control-label" for="answers_{{$item->id}}"> </label>
                                </div>
                            </td>
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
    <div class="form-group float-right">
        <button type="submit" class="btn btn-success"> <i class="ti-save"></i> &nbsp;Save </button>
    </div>
</form>

{{--<script type="module" src="{{asset('assets/ckeditor5/main.js')}}"></script>--}}
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

    window.addToOption = function () {
        let row = optionTable.insertRow(-1);
        let btn = row.insertCell(0);
        let num = row.insertCell(1);
        let sku = row.insertCell(2);
        let ans = row.insertCell(3);
        btn.innerHTML = remove_btn(row.rowIndex);
        num.innerHTML = no;
        sku.innerHTML = '<input required style="min-width: 100px;" class="form-control" name="options[]" placeholder="Please input a field name...">';
        ans.innerHTML = '<div class="custom-control custom-radio"> <input type="radio" id="answer_'+no+'" name="answer" value="'+(row.rowIndex-1)+'" class="custom-control-input"> <label class="custom-control-label" for="answer_'+no+'"> </label></div>';
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
