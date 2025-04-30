@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Airline Management
@stop
{{-- page level styles --}}
@section('header_styles')
<style>

</style>
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Airline Management</h4>
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
            <a class="btn btn-success btn-sm" href="{{ route('settings.airline.add') }}"><i class="ti-plus"></i> Add New</a>
            <a class="btn btn-info btn-sm" href="{{route('settings.regulations','airline')}}"><i class="ti-plus"></i> Regulations</a>
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
                                    <th scope="col">AIRLINE NAME</th>
                                    <th scope="col">LOGO</th>
                                    <th scope="col">ICAO CODE</th>
                                    <th scope="col">IATA CODE</th>
                                    <th scope="col">Water Detector Test</th>
                                    <th scope="col">TRUCK - BOL</th>
                                    <th scope="col">PIPELINE - BOL</th>
                                    <th scope="col">ACTION</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $no = 1 ?>
                                @foreach($airline as $item)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$item->airline_name}}</td>
                                    <td><img class="thumb" src="{{asset('/uploads'.'/settings/'.$item->logo)}}"></td>
                                    <td>{{$item->icao_code}}</td>
                                    <td>{{$item->iata_code}}</td>
                                    <td><input type="checkbox" {{$item->airline_water_test==1?'checked':''}} disabled></td>
                                    <td><input type="checkbox" {{$item->bol==1?'checked':''}} disabled></td>
                                    <td><input type="checkbox" {{$item->pipeline_bol==1?'checked':''}} disabled></td>
                                    <td>
                                        <a href="{{ route('settings.airline.edit',$item->id) }}" type="button" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</a>
                                        <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
                                        <form id="form_{{$item->id}}" hidden action="{{route('settings.airline.delete')}}" method="post">
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
