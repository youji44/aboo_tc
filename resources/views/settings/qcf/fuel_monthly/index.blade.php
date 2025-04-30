@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Monthly Inspection
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Monthly Inspection</h4>
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
            <a class="btn btn-info btn-sm" href="{{route('settings.regulations','fuel')}}"><i class="ti-plus"></i> Regulations</a>
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
                                    <th scope="col">UNIT #</th>
                                    <th scope="col">UNIT Type</th>
                                    <th scope="col">BUTTON 1</th>
                                    <th scope="col">BUTTON 2</th>
                                    <th scope="col">BUTTON 3</th>
                                    <th scope="col">HOSE REEL DEADMAN</th>
                                    <th scope="col">LIFT DECK DEADMAN</th>
                                    <th scope="col">LIFT PLATFORMS</th>
                                    <th scope="col">WATER SENSOR SYSTEM</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($fuel_monthly as $key=>$item)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$item->fe_unit}}</td>
                                        <td>{{$item->unit_type}}</td>
                                        <td><input title="button" type="checkbox" {{$item->button1==1?'checked':''}} disabled></td>
                                        <td><input title="button" type="checkbox" {{$item->button2==1?'checked':''}} disabled></td>
                                        <td><input title="button" type="checkbox" {{$item->button3==1?'checked':''}} disabled></td>
                                        <td><input title="" type="checkbox" {{$item->hose_deadman==1?'checked':''}} disabled></td>
                                        <td><input title="" type="checkbox" {{$item->lift_deadman==1?'checked':''}} disabled></td>
                                        <td><input title="" type="checkbox" {{$item->lift_platforms==1?'checked':''}} disabled></td>
                                        <td><input title="" type="checkbox" {{$item->water_sensor==1?'checked':''}} disabled></td>
                                        <td>
                                            <button onclick="show_edit('{{route('qcf.settings.fuel_monthly.edit',$item->fe_id)}}')" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</button>
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
    <div class="modal fade" id="fuel_monthly">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Settings Monthly Inspection</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div id="fuel_body" class="modal-body" style="min-height: 240px">
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
            $("#fuel_form").submit();
        }
        function show_edit(url){
            $.get(url, function (data) {
                $("#fuel_body").html(data);
                $("#fuel_monthly").modal('show');
            });
        }
    </script>
@stop
