<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('title')| IR - Dashboard</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="description" content="aboo qc dashboard" />
        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
        <link rel="icon" href="{{ asset('favico.ico') }}" type="image/x-icon">
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/themify-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/metisMenu.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/slicknav.min.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/css/jquery.fancybox.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/typography.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/default-css.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/flatpickr/dist/flatpickr.min.css') }}">
        <link rel="stylesheet" href="{{asset('assets/dropify/dist/css/dropify.min.css')}}"/>
        <link rel="stylesheet" href="{{asset('assets/select2/dist/css/select2.min.css')}}"/>
        <link rel="stylesheet" href="{{asset('assets/dropzone/dist/dropzone.css')}}"/>
        <link rel="stylesheet" href="{{asset('assets/jquery-toast/jquery.toast.min.css')}}"/>

        <link rel="stylesheet" href="{{asset('assets/datatables/datatables/css/dataTables.bootstrap4.min.css')}}"/>
        <link rel="stylesheet" href="{{asset('assets/datatables/datatables.net-fixedheader-dt/css/fixedHeader.dataTables.css')}}"/>
        <link rel="stylesheet" href="{{ asset('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/custom1.css') }}">
        @yield('header_styles')
        <style>
            .single-report .icon {
                background:{{\Session::get('p_loc_color')==''?'#f2114c':\Session::get('p_loc_color')}};
            }
            .page-title-area:before{
                background:{{\Session::get('p_loc_color')==''?'#f2114c':\Session::get('p_loc_color')}};
            }
            .metismenu li.active>a {
                color: {{\Session::get('p_loc_color')==''?'#f2114c':\Session::get('p_loc_color')}};
            }
            .metismenu li:hover>a, .metismenu li.active>a {
                color: {{\Session::get('p_loc_color')==''?'#f2114c':\Session::get('p_loc_color')}};
            }
            .ck-editor__editable {
                min-height: 150px !important;
            }
            .select2-container{
                width: 100% !important;
            }
            .select2-container--default .select2-selection--single{
                height: 38px;
                min-width: 190px;
                border: 1px solid #cdcdcd;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered{
                line-height: 36px;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow{
                height: 36px;
            }
            table.dataTable{
                margin-top: 0px !important;
            }
            .btn-sm{
                margin-bottom: 2px;
            }
            .btn-outline-primary.dropdown-toggle{
                padding: 9px 10px;
            }

            .ows_one{
                background-color: #f0f0f0;
            }

        </style>
        <!-- Google tag (gtag.js) -->
        {{--<script async src="https://www.googletagmanager.com/gtag/js?id=G-CTPNKNTW81"></script>--}}
        {{--<script>--}}
            {{--window.dataLayer = window.dataLayer || [];--}}
            {{--function gtag(){dataLayer.push(arguments);}--}}
            {{--gtag('js', new Date());--}}
            {{--gtag('config', 'G-CTPNKNTW81');--}}
        {{--</script>--}}
        <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('assets/dropzone/dist/dropzone.js') }}"></script>
        <script>
            let checked_list = [];
            let unchecked_list = [];
        </script>
    </head>

    <body>
        <div class="page-container">
                @include('partials.menu_ir')
            <div class="main-content">
                @yield('content')
            </div>
            @include('partials.footer')
        </div>
        <!-- Modal -->
        <div class="modal fade" id="detail">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="title_body" class="modal-title">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div id="detail_body" class="modal-body" style="min-height: 240px">
                    </div>
                    <div class="modal-footer">
                        <button hidden id="deficiency" onclick="create_deficiency()" type="button" class="btn btn-warning">Create Deficiency Report</button>
                        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="confirm_form">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div id="confirm_body" class="modal-body">
                        Would you confirm this entry? Are you sure?
                    </div>
                    <div class="modal-footer">
                        <input hidden id="confirm_id">
                        <button onclick="check_yes()" type="button" class="btn btn-success">Yes</button>
                        <button type="button" class="btn btn-info" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="delete_form">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div id="delete_body" class="modal-body">
                        Would you delete this entry?
                    </div>
                    <div class="modal-footer">
                        <input hidden id="delete_id">
                        <button onclick="delete_yes()" type="button" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-info" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <script>
        let username = '{{\Sentinel::getUser()->name}}'
    </script>


    <script src="{{ asset('assets/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slicknav.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.fancybox.js') }}"></script>
    <script src="{{ asset('assets/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/flatpickr/dist/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/jquery-toast/jquery.toast.min.js') }}"></script>

    <script src="{{ asset('assets/datatables/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/datatables/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
     <script src="{{ asset('assets/ckeditor/ckeditor5-build-classic/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/scripts1.js') }}"></script>
    <!-- JavaScript -->
    @yield('footer_scripts')
    <script>
        let ck_editor = null;
        if($('textarea').length > 0 && $('#comments').length){
            ClassicEditor
                .create( document.querySelector( '#comments' ) )
                .then( function(editor) {
                    ck_editor = editor;
                    editor.ui.view.editable.element.style.height = '150px';
                } )
                .catch( function(error) {
                    console.error( error );
                } );
        }
        $(document).ready(function(){
            // file upload dropify
            /* Basic Init*/
            $('.dropify').dropify();
            $('.dropify-clear').click(function(e){
                e.preventDefault();
                $('form').find('input[name="old_images"]').remove();
            });

            /* Select2 Init*/
            $(".select2").select2();

            $('form').submit(function(){
                let isWasValidated = this.classList.contains("was-validated");
                if(!isWasValidated)
                    $(":submit", this).attr("disabled", "disabled");
            });

            $('[data-tip="tooltip"]').tooltip();
            if ($('#dataTable').length) {
                $('#dataTable').DataTable({
                    bDestroy: true,
                    responsive: true,
                    pageLength: 100,
                    info: false,
                    order: [],
                    "columnDefs": [{
                        "targets":[0],
                        "searchable":false,
                        "orderable":false
                    }],
                    dom: 'Bfrtip',
                    buttons: ['excel','pdfHtml5']
                });
                $('.dt-buttons').hide();
            }
            if ($('#inspectDataTable').length) {
                $('#inspectDataTable').DataTable({
                    bDestroy: true,
                    responsive: true,
                    pageLength: 100,
                    info: false,
                    order: [],
                    "columnDefs": [{
                        "targets":[0],
                        "searchable":false,
                        "orderable":false
                    }],
                    dom: 'Bfrtip',
                    buttons: ['excel','pdfHtml5']
                });
                $('.dt-buttons').hide();
            }
        });

        function unableToInspect() {
            $("#unable").val('unable');
            $('.card-body').find("form:first").submit();
        }

        function excel() {
            $('#exportDataTable_wrapper .buttons-excel').click()
        }
        function pdf(){
            $('#exportDataTable_wrapper .buttons-pdf').click()
        }

        function check_yes() {
            const id = $("#confirm_id").val();
            $("#form_check_" + id).submit();
        }
        function delete_yes() {
            const id = $("#delete_id").val();
            $("#form_"+id).submit();
        }
        function delete_id(id){
            $("#delete_id").val(id);
        }

        function approve_item() {
            if(checked_list.length > 0){
                let form = document.getElementById('form_check_');
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'checked';
                input.value = checked_list;
                form.appendChild(input);
                form.submit();
            }else{
                document.getElementById('form_check_').submit();
            }
        }

        if(document.getElementById('checkAll1')){
            document.getElementById('checkAll1').addEventListener('change', function(event) {
                if (event.target.checked) {
                    checked_list = unchecked_list;
                    $('table input:checkbox').not(this).prop('checked', 'checked');
                } else {
                    checked_list = [];
                    $('table input:checkbox').not(this).prop('checked','');
                }
                count_checked(checked_list.length);
            });
        }

        function count_checked(count){
            if(count > 0)
                $("#approve_all").html("<i class='ti-check-box'></i> Approve ("+count+")")
            else
                $("#approve_all").html("<i class='ti-check-box'></i> Approve All")
        }

        $('#menu li a').on('click', function(evt){localStorage.removeItem('qc_activeTab');});

        function delete_item(obj, id, url){
            $.ajax({
                url: url,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: { id: id},
                success: function(response) {
                    if($("#inspectDataTable").length > 0) $('#inspectDataTable').DataTable().row($(obj).parents('tr')).remove().draw();
                    if($("#dataTable").length > 0) $('#dataTable').DataTable().row($(obj).parents('tr')).remove().draw();
                    if($("#verttable").length > 0) $('#verttable').DataTable().row($(obj).parents('tr')).remove().draw();

                    const activeListItem = document.querySelector('.metismenu .active .active');
                    if (activeListItem) {
                        const badgeElement = activeListItem.querySelector('.badge');
                        if (badgeElement) {
                            // Retrieve the badge count value
                            const badgeCount = badgeElement.textContent.trim();
                            let newBadgeCount = parseInt(badgeCount) - 1;
                            badgeElement.textContent = newBadgeCount > 0?newBadgeCount.toString():'';
                            const cur_count = document.querySelector('.card-body .text-success').textContent;
                            let i = cur_count.indexOf("/")
                            if( i > 0){
                                let new_count = cur_count.slice(0,i).replaceAll('Total:','').trim();
                                document.querySelector('.card-body .text-success').textContent = "Total: " + (parseInt(new_count)-1).toString() + cur_count.slice(i);
                            }else{
                                document.querySelector('.card-body .text-success').textContent = "Total: " + newBadgeCount;
                            }

                        } else {
                            console.log('Active list item does not contain a badge count.');
                        }
                    } else {
                        console.log('No active list item found.');
                    }

                    const inspectBadge = document.querySelector('.nav-link .badge');
                    if (inspectBadge) {
                        inspectBadge.textContent = (parseInt(inspectBadge.textContent.trim()) - 1) > 0?(parseInt(inspectBadge.textContent.trim()) - 1).toString():'';
                    }

                    $.toast().reset('all');
                    $("body").removeAttr('class');
                    $.toast({
                        heading: 'Success',
                        text: 'You deleted an inspection.',
                        position: 'top-right',
                        loaderBg:'#3e93ff',
                        icon: 'success',
                        hideAfter: 2000,
                        stack: 6
                    });
                    return false;
                },
                error: function(error) {
                    $.toast({
                        heading: 'Error',
                        text: 'You have an error.',
                        position: 'top-right',
                        loaderBg:'#3e93ff',
                        icon: 'danger',
                        hideAfter: 2000,
                        stack: 6
                    });
                    return false;
                }
            });
        }

        function check_item(obj, id, url, undo) {
            if(id == null)
            {
                $("#form_check_").submit();
                return;
            }
            $.ajax({
                url: url,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: { id: id, undo:undo},
                success: function(response) {
                    if($("#inspectDataTable").length > 0) $('#inspectDataTable').DataTable().row($(obj).parents('tr')).remove().draw();
                    else if($("#verttable").length > 0) $('#verttable').DataTable().row($(obj).parents('tr')).remove().draw();
                    else $('table').DataTable().row($(obj).parents('tr')).remove().draw();
                    let msg = "You approved an inspection.";
                    if(undo === 'undo'){
                        if($("#dataTable").length > 0) $('#dataTable').DataTable().row($(obj).parents('tr')).remove().draw();
                        else $('table').DataTable().row($(obj).parents('tr')).remove().draw();
                        const cur_count = document.querySelector('.card-body .text-success').textContent;
                        let new_count = parseInt(cur_count.replace('Total:','').trim()) - 1;
                        document.querySelector('.card-body .text-success').textContent = "Total: " + new_count.toString();
                        msg = "You undo an inspection."
                    }else{
                        const inspectBadge = document.querySelector('.nav-link .badge');
                        if (inspectBadge) {
                            inspectBadge.textContent = (parseInt(inspectBadge.textContent.trim()) - 1) > 0?(parseInt(inspectBadge.textContent.trim()) - 1).toString():'';
                        }

                        const activeListItem = document.querySelector('.metismenu .active .active');
                        if (activeListItem) {
                            const badgeElement = activeListItem.querySelector('.badge');
                            if (badgeElement) {
                                // Retrieve the badge count value
                                const badgeCount = badgeElement.textContent.trim();
                                let newBadgeCount = parseInt(badgeCount) - 1;
                                badgeElement.textContent = newBadgeCount > 0?newBadgeCount.toString():'';
                            } else {
                                console.log('Active list item does not contain a badge count.');
                            }
                        }
                    }

                    $.toast().reset('all');
                    $("body").removeAttr('class');
                    $.toast({
                        heading: 'Success',
                        text: msg,
                        position: 'top-right',
                        loaderBg:'#3e93ff',
                        icon: 'success',
                        hideAfter: 2000,
                        stack: 6
                    });
                    return false;
                },
                error: function(error) {
                    $.toast({
                        heading: 'Error',
                        text: 'You have an error.',
                        position: 'top-right',
                        loaderBg:'#3e93ff',
                        icon: 'danger',
                        hideAfter: 2000,
                        stack: 6
                    });
                    return false;
                }
            });
        }

        function create_deficiency() {

        }

        function convert(date) {
            // const options = {
            //     year: "numeric",
            //     month: "2-digit",
            //     day: "numeric",
            // };
            // const formattedDate = new Date(date).toLocaleDateString("en-US", options);
            // const [month, day, year] = formattedDate.split('/');
            // return `${year}-${month}-${day}`;
            return date;
        }

        function clean(data) {
            if (data == null) return '-';
            else return data;
        }

        function select_color(color) {
            $("#color").attr('class','custom-select alert-'+color);
        }

        let load_data = function (isdate) {
            if(isdate === true){
                $("#date").val('');
            }
            $("#form_date").submit();
        };

        let set_plocation = function () {
            $("#form_plocation").submit();
        };

        function colored(data) {
            if (data == 'Satisfied')
                return '<span class="text-success">'+data+'</span>';
            else
                return '<span class="text-danger">'+data+'</span>'
        }

        function get_color(data) {
            if (data == null) return 'secondary';
            else return data;
        }
        function get_other(data) {
            if (data == null)return 'Other';
            else return data;
        }

        function maplink(name, lat,lng) {
            let map = 'https://www.google.com/maps/search/'+lat+','+lng;
            return '<a href="'+map+'" target="_blank">'+name+' <i class="ti-location-pin"></i></a>'
        }
        function set_geo(){
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    $("#geo_latitude").val(position.coords.latitude);
                    $("#geo_longitude").val(position.coords.longitude);
                });
            }
        }
        let regulation = function (data) {
            let regulations = data.regulations;
            if(regulations == undefined)regulations = 'No specification';
            $("#title_body").html($(".page-title").html()+' Regulations');
            let va = '<div>'+regulations+'</div>';
            $("#detail_body").html(va);
            $("#deficiency").attr('hidden','hidden');
            $("#detail").show();
        };

        function add_flight_count(url) {
            let ckeditor;
            $.get(url, function (data,status) {
                $("#title_body1").html('Add Daily Flight Counts');
                $("#add_body").html(data);
                $("#add_modal").modal('show');
            })
        }
        function cancel() {
            $("#add_modal").modal('hide');
        }

        function exportPDF(title, fTitle, columns,pageType,imageFlag,widthFlag,align,id) {
            /*================================
                datatable active
            ==================================*/
            let loc_name = '{{\Session::get('p_loc_name')}}';
            if (id === undefined) id = "#exportDataTable";

            if ($(id).length) {
                let today = new Date();
                if (!pageType) pageType = 'LETTER';
                if (!align) align = 'center';
                $(id).DataTable({
                    bDestroy: true,
                    responsive: true,
                    pageLength: 100,
                    info:false,
                    dom: 'Bfrtip',
                    order: [],
                    columnDefs: [ {
                        "targets": [0],
                        "orderable": false,
                    }],
                    buttons: [
                    {
                        extend:'excelHtml5',
                        exportOptions:{
                            columns:columns
                        },
                        customize: function (doc) {
                            //console.log(doc);
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: pageType,
                        messageTop:' ',
                        exportOptions:{
                            columns:columns
                        },
                        title:loc_name.toUpperCase() +' '+title,
                        customize: function (doc) {
                            doc.styles.title = {
                                alignment: 'right',
                                fontSize:16,
                                bold:true
                            };
                            doc.defaultStyle = {
                                fontSize:8
                            };
                            // doc.content[2].margin = [ 20, 0, 20, 0 ];
                            let table = doc.content[2].table.body;

                            for (let i = 1; i < table.length; i++) // skip table header row (i = 0)
                            {
                                for(let j = 0; j < table[i].length;j++){
                                    table[i][j].text = table[i][j].text
                                        .replaceAll("<br>","\n")
                                        .replaceAll("<span class=\"text-success\">","")
                                        .replaceAll("<span class=\"text-danger\">","")
                                        .replaceAll("<span class=\"text-secondary\">","")
                                        .replaceAll("</span>","")
                                        .replaceAll("<p>","")
                                        .replaceAll("</p>","\n");
                                }
                            }

                            doc.content[2].layout = {
                                border: "borders",
                                hLineColor:'#cdcdcd',
                                vLineColor:'#cdcdcd'
                            };
                            doc.styles.tableHeader = {fillColor:'#ebebeb',alignment: 'center'};
                            doc.styles.tableBodyOdd = {alignment: align};
                            doc.styles.tableBodyEven = {alignment: align};
                            if(widthFlag) {
                                doc.content[2].table.widths = Array(doc.content[2].table.body[0].length + 1).join('*').split('');
                            }

                            doc.pageMargins = [50,20,50,50];
                            if(imageFlag){
                                const idd = id+' .thumb';
                                let arr2 = $(idd).map(function(){
                                    return this.src;
                                }).get();
                                for (let i = 0, c = 1; i < arr2.length; i++, c++) {
                                    let col = 0;
                                    if(id==="#dataTable1" || id==="#dataTable2") col = 1;
                                    if(id==="#dataTable3" || id==="#dataTable4") col = 1;
                                    if(id==="#dataTable5") col = 5;
                                    if(id==="#dataTable22") col = 2;
                                    doc.content[2].table.body[c][col] = {
                                        image: arr2[i],
                                        maxHeight: 30,
                                        maxWidth: 120,
                                        alignment:'center'
                                    }
                                }
                            }
                            doc.content.splice( 1, 0, {
                                margin: [ -20, -50, 0, 30 ],
                                alignment: 'left',
                                width: 120,
                                image:'{{\Utils::logo()}}'
                            } );
                            doc.content.splice( 2, 0, {
                                margin: [ 90, -64, 0, 30 ],
                                text:'Report Generated By '+username+' \non '+today.toLocaleDateString("en-US", { year: 'numeric', month: 'long', day: 'numeric',hour:'numeric',minute:'numeric'})
                            } );

                            doc['footer']=(function(page, pages) {
                                return {
                                    columns: [
                                        {
                                            text:fTitle,
                                            fontSize:8
                                        },
                                        {
                                            alignment: 'right',
                                            text: 'Page:'+ page.toString()+'/'+pages.toString(),
                                            fontSize: 8
                                        }
                                    ],
                                    margin: [50, 0, 50]
                                }
                            });
                            if ($('#exportRegulation').length) {
                                let table1 = $('#exportRegulation').DataTable({
                                    bDestroy: true,
                                    bPaginate: false,
                                    info:false,
                                    bFilter:false
                                });
                                let headings = table1.columns().header().to$().map(function(i,e) { return e.innerHTML;}).get();
                                let data = table1.rows().data();
                                let tbl1_rows = []; // the data from the first table

                                // PDF header row for the first table:
                                tbl1_rows.push( $.map( headings, function ( d ) {
                                    return {
                                        text: typeof d === 'string' ? d : d+'',
                                        style: 'tableHeader',
                                        alignment:'left'
                                    };
                                } ) );

                                // PDF body rows for the first table:
                                for ( let i = 0, ien = data.length ; i < ien ; i++ ) {
                                    tbl1_rows.push( $.map( data[i], function ( d ) {
                                        if ( d === null || d === undefined ) {
                                            d = '';
                                        }
                                        let txt = typeof d === 'string'?d:d+'';

                                        txt = txt.replaceAll("&lt;p&gt;","")
                                        .replaceAll("&amp;nbsp;","\n")
                                        .replaceAll("&lt;/p&gt;","\n")
                                        .replaceAll("&lt;h2&gt;","")
                                        .replaceAll("&lt;/h2&gt;","\n")
                                        .replaceAll("&lt;h3&gt;","")
                                        .replaceAll("&lt;/h3&gt;","\n")
                                        .replaceAll("&lt;h4&gt;","")
                                        .replaceAll("&lt;/h4&gt;","\n");

                                        return {
                                            text: txt,
                                            style: i % 2 ? 'tableBodyEven' : 'tableBodyOdd',
                                            alignment:'left'
                                        };
                                    } ) );
                                }

                                let clone = structuredClone(doc.content[4]);
                                clone.table.body = tbl1_rows;
                                clone.margin = [ 0, 20, 0, 0 ];
                                clone.layout = {
                                    border: "borders",
                                    hLineColor:'#cdcdcd',
                                    vLineColor:'#cdcdcd'
                                };
                                clone.table.widths = Array(clone.table.body[0].length + 1).join('*').split('');
                                doc.content.splice(5, 1, clone);
                            }
                        }
                    }]
                });
                $('.dt-buttons').hide();
            }
        }

        if($("div#images").length > 0){
            let uploaded = {};
            Dropzone.autoDiscover = false;
            new Dropzone(document.querySelector("#images"), {
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
                    $('form').append('<input type="hidden" name="images[]" value="' + response.name + '">');
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
                    $('form').find('input[name="images[]"][value="' + name + '"]').remove()
                },
                init: function () {
                    if(images) {
                        if(Array.isArray(images)) {
                            images.forEach(function (img) {
                                if(img !== "")
                                    $('form').append('<input type="hidden" name="images[]" value="' + img + '">')
                            })
                        }
                    }
                }
            });
        }
        function remove_files(file_name, name) {
            $('form').find('div[class="dz-preview dz-image-preview"][data-img="' + file_name + '"]').remove();
            $('form').find('div[class="dz-preview dz-file-preview"][data-pdf="' + file_name + '"]').remove();
            $('form').find('input[name="'+name+'[]"][value="' + file_name + '"]').remove()
        }

        function isValidJson(json) {
            try {
                JSON.parse(json);
                return true;
            } catch (e) {
                return false;
            }
        }

    </script>

</html>
