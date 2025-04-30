@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Internal Audit
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Internal Audit </h4>
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
            <button class="btn btn-success btn-sm" onclick="show_modal('{{route('settings.audit.add')}}')"><i class="ti-plus"></i> Add New</button>
            <a class="btn btn-info btn-sm" href="{{route('settings.regulations','audit')}}"><i class="ti-plus"></i> Regulations</a>
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
                                    <th scope="col">PRIMARY LOCATION</th>
                                    <th scope="col">AUDIT TITLE</th>
                                    <th scope="col">TOTAL AUDIT QUESTIONS</th>
                                    <th scope="col">ACTION</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $no = 1 ?>
                                @foreach($audit as $item)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$item->location}}</td>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->questions}}</td>
                                    <td>
                                        <a href="{{route('settings.audit.manage',$item->id)}}" class="btn btn-primary btn-sm"><i class="ti-plus"></i> Manage Task</a>
                                        <button  onclick="show_modal('{{route('settings.audit.edit',$item->id)}}')" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</button>
                                        <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
                                        <form id="form_{{$item->id}}" hidden action="{{route('settings.audit.delete')}}" method="post">
                                            @csrf <input hidden name="id" value="{{$item->id}}">
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
    <div class="modal fade" id="add_inspect_task">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="title_body" class="modal-title">Modal Title</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div id="add_body_1" class="modal-body" style="min-height: 240px">
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script>
        function delete_item(id){
            $("#form_"+id).submit();
        }

        function show_modal(url) {
            $.get(url, function (data,status) {
                $("#title_body").html($(".page-title").html());
                $("#add_body_1").html(data);
                $("#add_inspect_task").modal('show');
            })
        }

    </script>
@stop
