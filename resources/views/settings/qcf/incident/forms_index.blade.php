@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Settings Incident Reporting Forms
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Incident Reporting > <a href="{{ route('qcf.settings.incident') }}">Forms</a> > {{$form->form_name}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl mt-2">
            <button class="btn btn-success btn-sm" onclick="show_edit('{{route('qcf.settings.incident.forms.manage.edit',0)}}?fid={{$form->id}}')"><i class="ti-plus"></i> Add New</button>
            <a class="btn btn-outline-warning btn-sm" href="{{route('qcf.settings.incident')}}"><i class="ti-close"></i> Back</a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl mt-2">
            <div class="card">
                <div class="card-body">
                    @include('notifications')
                    <div class="single-table">
                        <div class="table-responsive">
                            <table class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                <thead class="text-uppercase">
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">FORM ITEM</th>
                                    <th scope="col">DESCRIPTION</th>
                                    <th scope="col">INPUT FIELD</th>
                                    <th scope="col">MANDATORY FIELD</th>
                                    <th scope="col">ACTION</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($forms_details as $key=>$item)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$item->item}}</td>
                                        <td>{{$item->description}}</td>
                                        <td>{{Utils::form_item($item->input_type)}}</td>
                                        <td>{{$item->required==1?'YES':'NO'}}</td>
                                        <td>
                                            <button onclick="show_edit('{{route('qcf.settings.incident.forms.manage.edit',$item->id)}}?fid={{$form->id}}')" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</button>
                                            <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
                                            <form id="form_{{$item->id}}" hidden action="{{route('qcf.settings.incident.forms.manage.delete')}}" method="post">
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

    <!-- Modal -->
    <div class="modal fade" id="input_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title_modal">Manage Form Details</h5>
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

        function show_edit(url){
            $.get(url, function (data) {
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
