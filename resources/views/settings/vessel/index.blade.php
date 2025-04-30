@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Vessel
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Vessel</h4>
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
            <a class="btn btn-success btn-sm" href="{{ route('settings.vessel.add') }}"><i class="ti-plus"></i> Add New</a>
            <a class="btn btn-info btn-sm" href="{{route('settings.regulations','vessel')}}"><i class="ti-plus"></i> Regulations</a>
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
                                    <th scope="col">VESSEL</th>
                                    <th scope="col">LOCATION NAME</th>
                                    <th scope="col">LOCATION LATITUDE</th>
                                    <th scope="col">LOCATION LONGITUDE</th>
                                    <th scope="col">TRUCK - BOL</th>
                                    <th scope="col">PIPELINE - BOL</th>
                                    <th scope="col">Water Defense System</th>
                                    <th scope="col">Vessel Filter Sump</th>
                                    <th scope="col">Bonding Cable, Scully System Continuity Test Inspection</th>
                                    <th scope="col">Differential Pressure Gauge<br>Position Full Movement Check</th>
                                    <th scope="col">Filter Membrane Test(Millipore)</th>
                                    <th scope="col">Deadman Control Check</th>
                                    <th scope="col">Hoses, Pumps and Screens</th>
                                    <th scope="col">Eye Wash Inspection</th>
                                    <th scope="col">Bulk Air Eliminator Sump</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $no = 1 ?>
                                @foreach($vessel as $item)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$item->pl_location}}</td>
                                    <td>{{$item->vessel}}</td>
                                    <td>{{$item->location_name}}</td>
                                    <td>{{$item->location_latitude}}</td>
                                    <td>{{$item->location_longitude}}</td>
                                    <td><input type="checkbox" {{$item->bol==1?'checked':''}} disabled></td>
                                    <td><input type="checkbox" {{$item->bol_pipeline==1?'checked':''}} disabled></td>
                                    <td><input type="checkbox" {{$item->water_defense==1?'checked':''}} disabled></td>
                                    <td><input type="checkbox" {{$item->vessel_filter==1?'checked':''}} disabled></td>
                                    <td><input type="checkbox" {{$item->bonding_cable==1?'checked':''}} disabled></td>
                                    <td><input type="checkbox" {{$item->differential_pressure==1?'checked':''}} disabled></td>
                                    <td><input type="checkbox" {{$item->filter_membrane==1?'checked':''}} disabled></td>
                                    <td><input type="checkbox" {{$item->deadman_control==1?'checked':''}} disabled></td>
                                    <td><input type="checkbox" {{$item->hoses_pumps_screens==1?'checked':''}} disabled></td>
                                    <td><input type="checkbox" {{$item->eye_wash_inspection==1?'checked':''}} disabled></td>
                                    <td><input type="checkbox" {{$item->bulk_sump==1?'checked':''}} disabled></td>
                                    <td>
                                        <a href="{{ route('settings.vessel.edit',$item->id) }}" type="button" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</a>
                                        <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
                                        <form id="form_{{$item->id}}" hidden action="{{route('settings.vessel.delete')}}" method="post">
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
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script>
        function delete_item(id){
            $("#form_"+id).submit();
        }
    </script>
@stop
