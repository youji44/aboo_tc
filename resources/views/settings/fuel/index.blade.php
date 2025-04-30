@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Manage Fleet
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Manage Fleet</h4>
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
            <a class="btn btn-success btn-sm" href="{{ route('settings.fuel.add') }}"><i class="ti-plus"></i> Add New</a>
            <a class="btn btn-info btn-sm" href="{{route('settings.regulations','fuel')}}"><i class="ti-plus"></i> Regulations</a>
            <a class="btn btn-info btn-sm" onclick="excel()" href="javascript:;"><i class="ti-download"></i> Excel</a>
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
                                    <th scope="col">UNIT #</th>
                                    <th scope="col">UNIT Type</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($fuel as $key=>$item)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$item->location}}</td>
                                    <td>{{$item->unit}}</td>
                                    <td>{{$item->unit_type}}</td>
                                    <td>
                                        <a href="{{ route('settings.fuel.edit',$item->id) }}" type="button" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</a>
                                        <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
                                        <form id="form_{{$item->id}}" hidden action="{{route('settings.fuel.delete')}}" method="post">
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
    <div class="export_table_body" style="display: none">
        <table id="exportDataTable" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
            <thead class="text-uppercase">
            <tr class="bg-light">
                <th scope="col">#</th>
                <th scope="col">PRIMARY LOCATION</th>
                <th scope="col">UNIT #</th>
                <th scope="col">UNIT Type</th>
                <th scope="col">VIN NUMBER</th>
                <th scope="col">EQUIPMENT MANUFACTURE YEAR</th>
                <th scope="col">EQUIPMENT MAKE - MODEL</th>
                <th scope="col">EQUIPMENT TYPE</th>
                <th scope="col">ELEMENT MODEL/TYPE</th>
                <th scope="col">ELEMENT SERIAL NUMBER</th>
                <th scope="col">QTY OF ELEMENT INSTALLED</th>
                <th scope="col">MAXIMUM OPERATING FLOW RATE</th>
                <th scope="col">MAXIMUM CHANGE OUT DP</th>
                <th scope="col">ELEMENT / VESSEL LAST INSPECTED</th>
                <th scope="col">FIRE EXTINGUISHER TYPE</th>
                <th scope="col">SIZE(LBS)</th>
                <th scope="col">Hydrant Filter Sump</th>
                <th scope="col">Tanker Sump</th>
                <th scope="col">Eye Wash Inspection</th>
                <th scope="col">Visi jar Cleaning</th>
                <th scope="col">Filter Membrane Test</th>
                <th scope="col">Fuel Equipment - Weekly</th>
                <th scope="col">Fuel Equipment - Monthly</th>
                <th scope="col">Fuel Equipment - Quarterly</th>
            </tr>
            </thead>
            <tbody>
            @foreach($fuel as $key=>$item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item->location}}</td>
                    <td>{{$item->unit}}</td>
                    <td>{{$item->unit_type}}</td>
                    <td>{{$item->vin_number}}</td>
                    <td>{{$item->manu_year}}</td>
                    <td>{{$item->make_model}}</td>
                    <td>{{$item->equip_type}}</td>
                    <td>{{$item->model_type}}</td>
                    <td>{{$item->serial_number}}</td>
                    <td>{{$item->qty_installed}}</td>
                    <td>{{$item->max_flow_rate}}</td>
                    <td>{{$item->max_dp}}</td>
                    <td>{{$item->last_inspected}}</td>
                    <td>{{$item->fire_extinguisher_type}}</td>
                    <td>{{$item->size}}</td>
                    <td>{{$item->hydrant_filter_sump==1?'Yes':'No'}}</td>
                    <td>{{$item->tanker_filter_sump==1?'Yes':'No'}} </td>
                    <td>{{$item->eye_wash_inspection==1?'Yes':'No'}} </td>
                    <td>{{$item->visi_jar_cleaning==1?'Yes':'No'}} </td>
                    <td>{{$item->filter_membrane_test==1?'Yes':'No'}} </td>
                    <td>{{$item->fuel_equipment_weekly==1?'Yes':'No'}} </td>
                    <td>{{$item->fuel_equipment_monthly==1?'Yes':'No'}} </td>
                    <td>{{$item->fuel_equipment_quarterly==1?'Yes':'No'}} </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script>
        function delete_item(id){
            $("#form_"+id).submit();
        }
        $(document).ready(function () {
            exportPDF(
                'SETTINGS \nMANAGE FLEET',
                'QC DASHBOARD > SETTINGS > MANAGE FLEET',
                [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10,11,12,13,14,15,16,17,18,19,20,21,22,23],
            );
        });

    </script>
@stop
