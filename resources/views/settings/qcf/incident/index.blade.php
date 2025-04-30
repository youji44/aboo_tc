@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Settings Incident Reporting
@stop
{{-- page level styles --}}
@section('header_styles')

@stop
{{-- Page content --}}
@section('content')
    <div class="header-area">
        <div class="row align-items-center">
            <!-- nav and search button -->
            <div class="col-md-12 col-sm-12 clearfix">
                <div class="nav-btn pull-left">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="search-box pull-left">
                    <div class="page-title-area">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="breadcrumbs-area clearfix">
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Incident Reporting</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="type-tab" data-toggle="tab" href="#type" role="tab" aria-controls="type" aria-selected="true">Types of Incident</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="notification-tab" data-toggle="tab" href="#notification" role="tab" aria-controls="notification" aria-selected="true">Incident Notification</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="locations-tab" data-toggle="tab" href="#locations" role="tab" aria-controls="locations" aria-selected="true">Locations</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="departments-tab" data-toggle="tab" href="#departments" role="tab" aria-controls="departments" aria-selected="true">Departments</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="forms-tab" data-toggle="tab" href="#forms" role="tab" aria-controls="forms" aria-selected="true">Forms</a>
        </li>
    </ul>
    <div class="tab-content mt-3" id="myTabContent">
        <div class="tab-pane active" id="type" role="tabpanel" aria-labelledby="type-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <button class="btn btn-success btn-sm" onclick="show_edit('{{route('qcf.settings.incident.type.edit',0)}}')"><i class="ti-plus"></i> Add New</button>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            @include('notifications')
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">#</th>
                                            <th scope="col">TYPE OF INCIDENT</th>
                                            <th scope="col">COLOR</th>
                                            <th scope="col">FORMS</th>
                                            <th scope="col">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($incident_types as $key=>$item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$item->type}}</td>
                                                <td style="background:{{$item->color}}">{{$item->color}}</td>
                                                <td>{{$item->forms}}</td>
                                                <td>
                                                    <button onclick="show_edit('{{route('qcf.settings.incident.type.edit',$item->id)}}')" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</button>
                                                    <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
                                                    <form id="form_{{$item->id}}" hidden action="{{route('qcf.settings.incident.type.delete')}}" method="post">
                                                        @csrf <input title="id" hidden name="id" value="{{$item->id}}">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="notification" role="tabpanel" aria-labelledby="notification-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <button class="btn btn-success btn-sm" onclick="show_edit('{{route('qcf.settings.incident.notification.edit',0)}}')"><i class="ti-plus"></i> Add New</button>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            @include('notifications')
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table id="dataTable1" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">#</th>
                                            <th scope="col">INCIDENT NOTIFICATION</th>
                                            <th scope="col">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($incident_notifications as $key=>$item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$item->notification}}</td>
                                                <td>
                                                    <button onclick="show_edit('{{route('qcf.settings.incident.notification.edit',$item->id)}}')" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</button>
                                                    <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
                                                    <form id="form_{{$item->id}}" hidden action="{{route('qcf.settings.incident.notification.delete')}}" method="post">
                                                        @csrf <input title="id" hidden name="id" value="{{$item->id}}">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="locations" role="tabpanel" aria-labelledby="locations-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <button class="btn btn-success btn-sm" onclick="show_edit('{{route('qcf.settings.incident.location.edit',0)}}')"><i class="ti-plus"></i> Add New</button>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            @include('notifications')
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table id="dataTable1" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">#</th>
                                            <th scope="col">PRIMARY LOCATION</th>
                                            <th scope="col">LOCATION NAME</th>
                                            <th scope="col">LOCATION CODE</th>
                                            <th scope="col">LOCATION LATITUDE</th>
                                            <th scope="col">LOCATION LONGITUDE</th>
                                            <th scope="col">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($incident_locations as $key=>$item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$item->p_location_name}}</td>
                                                <td>{{$item->location_name}}</td>
                                                <td>{{$item->location_code}}</td>
                                                <td>{{$item->location_latitude}}</td>
                                                <td>{{$item->location_longitude}}</td>
                                                <td>
                                                    <button onclick="show_edit('{{route('qcf.settings.incident.location.edit',$item->id)}}')" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</button>
                                                    <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
                                                    <form id="form_{{$item->id}}" hidden action="{{route('qcf.settings.incident.location.delete')}}" method="post">
                                                        @csrf <input title="id" hidden name="id" value="{{$item->id}}">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="departments" role="tabpanel" aria-labelledby="departments-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <button class="btn btn-success btn-sm" onclick="show_edit('{{route('qcf.settings.incident.department.edit',0)}}')"><i class="ti-plus"></i> Add New</button>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            @include('notifications')
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table id="dataTable1" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">#</th>
                                            <th scope="col">DEPARTMENT NAME</th>
                                            <th scope="col">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($incident_departments as $key=>$item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$item->department_name}}</td>
                                                <td>
                                                    <button onclick="show_edit('{{route('qcf.settings.incident.department.edit',$item->id)}}')" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</button>
                                                    <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
                                                    <form id="form_{{$item->id}}" hidden action="{{route('qcf.settings.incident.department.delete')}}" method="post">
                                                        @csrf <input title="id" hidden name="id" value="{{$item->id}}">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="forms" role="tabpanel" aria-labelledby="forms-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <button class="btn btn-success btn-sm" onclick="show_edit('{{route('qcf.settings.incident.forms.edit',0)}}')"><i class="ti-plus"></i> Add New</button>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            @include('notifications')
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table id="dataTable1" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">#</th>
                                            <th scope="col">FORM NAME</th>
                                            <th scope="col">FORM ITEMS COUNT</th>
                                            <th scope="col">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($incident_forms as $key=>$item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$item->form_name}}</td>
                                                <td>{{$item->count}}</td>
                                                <td>
                                                    <a href="{{route('qcf.settings.incident.forms.details',$item->id)}}" class="btn btn-primary btn-sm"><i class="ti-settings"></i> Manage </a>
                                                    <button onclick="show_edit('{{route('qcf.settings.incident.forms.edit',$item->id)}}')" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</button>
                                                    <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
                                                    <form id="form_{{$item->id}}" hidden action="{{route('qcf.settings.incident.forms.delete')}}" method="post">
                                                        @csrf <input title="id" hidden name="id" value="{{$item->id}}">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="input_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title_modal">Edit Settings Incident Reporting</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div id="modal_body" class="modal-body" style="min-height: 240px">
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script>
        // Add event listener to the tab links
        $('.nav-link').on('click', function(evt){
            const tabId = $(this).attr('href');
            localStorage.setItem('qc_activeTab', tabId);
        });
        let activeTab = localStorage.getItem('qc_activeTab');
        if(activeTab) {
            $('.nav-link').removeClass('active');
            $('.tab-pane').removeClass('active');
            if($(activeTab).length < 1) activeTab = "#type";
            $(activeTab).addClass('active');
            const tabLink = $('a[href="'+activeTab+'"]');
            tabLink.addClass('active');
        }else{
            const tabLink = $('a[href="#type"]');
            tabLink.addClass('active');
            $("#type").addClass('active');
        }
        function show_edit(url){
            $.get(url, function (data) {
                let title = $('.nav-link.active').text().trim();
                $("#title_modal").text(title);
                $("#modal_body").html(data);
                $("#input_modal").modal('show');
            });
        }

        if ($('table').length) {
            $('table').DataTable({
                "destroy": true,
                "responsive": true,
                "pageLength": 100,
                "info": false,
                "order": [],
                "columnDefs": [{
                    "targets":[0],
                    "searchable":false,
                    "orderable":false
                }],
                dom: 'Bfrtip',
            });
            $('.dt-buttons').hide();
        }
    </script>
@stop
