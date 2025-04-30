@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Settings Point of Inspections - Category
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Point of Inspections - Category</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('settings.qcf.pointof.tabs')
    <div class="tab-content mt-3" id="myTabContent">
        <div class="tab-pane fade {{$mode=='cat'?'show active':''}}" id="cat" role="tabpanel" aria-labelledby="cat-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <button class="btn btn-success btn-sm" onclick="show_edit('{{route('qcf.settings.pointof.category.edit','0')}}')"><i class="ti-plus"></i> Add New</button>
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
                                            <th scope="col">CATEGORY</th>
                                            <th scope="col">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($category as $key=>$item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$item->category}}</td>
                                                <td>
                                                    <button onclick="show_edit('{{route('qcf.settings.pointof.category.edit',$item->id)}}')" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</button>
                                                    <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
                                                    <form id="form_{{$item->id}}" hidden action="{{route('qcf.settings.pointof.category.delete')}}" method="post">
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Settings Point of Inspections - Category</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div id="modal_body" class="modal-body" style="min-height: 150px">
                </div>
                <div class="modal-footer">
                    <button onclick="save_fuel()" type="button" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

    <script>
        function save_fuel() {
            if($("#category").val() === '') {
                $("#alert").show(300);
            }
            else {
                $("#alert").hide(200);
                $("form").submit();
            }
        }
        function show_edit(url){
            $.get(url, function (data) {
                $("#modal_body").html(data);
                $("#input_modal").modal('show');
            });
        }

    </script>
@stop
