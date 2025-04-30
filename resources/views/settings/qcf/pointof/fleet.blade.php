@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Settings Point of Inspections - Assign Fleet
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Point of Inspections - Assign Fleet</h4>
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
        <div class="tab-pane fade {{$mode=='fleet'?'show active':''}}" id="task" role="tabpanel" aria-labelledby="task-tab">
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
                                            <th scope="col">UNIT#</th>
                                            <th scope="col">UNIT TYPE</th>
                                            @foreach($prevent_category as $item)
                                                <th scope="col">{{$item->category}}</th>
                                            @endforeach
                                            <th scope="col">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($fuel_equipment as $key=>$item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$item->unit}}</td>
                                                <td>{{$item->unit_type}}</td>
                                                @if(count($item->prevent_fleet) > 0)
                                                    @foreach($item->prevent_fleet as $fleet)
                                                        <td><input title="Select" type="checkbox" {{$fleet->selected==1?'checked':''}} disabled></td>
                                                    @endforeach
                                                @else
                                                    @foreach($prevent_category as $fleet)
                                                        <td><input title="Select" type="checkbox" disabled></td>
                                                    @endforeach
                                                @endif
                                                <td>
                                                    <button onclick="show_edit('{{route('qcf.settings.pointof.fleet.edit',$item->id)}}')" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</button>
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
                    <h5 class="modal-title">Edit Settings Point of Inspections - Assign Fleet</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div id="modal_body" class="modal-body" style="min-height: 240px">
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="save_btn()" class="btn btn-success">Save</button>
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
        function save_btn(){
            $("#fleet_form").submit()
        }
    </script>
@stop
