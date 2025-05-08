@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Primary Location
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} >  <a class="text-dark" href="{{route('settings')}}">Settings</a> > Primary Location</h4>
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
            <a class="btn btn-success btn-sm" href="{{ route('settings.location.add') }}"><i class="ti-plus"></i> Add New</a>
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
                                    <th scope="col">LOCATION NAME</th>
                                    <th scope="col">COLOR</th>
                                    <th scope="col">LATITUDE</th>
                                    <th scope="col">LONGITUDE</th>
                                    <th scope="col">ADDRESS</th>
                                    <th scope="col">ACTION</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $no = 1 ?>
                                @foreach($location as $item)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$item->location}}</td>
                                    <td style="background-color:{{$item->location_color}}">{{$item->location_color}}</td>
                                    <td>{{$item->location_latitude}}</td>
                                    <td>{{$item->location_longitude}}</td>
                                    <td>{{$item->location_address}}</td>
                                    <td>
                                        <a data-tip="tooltip" title="Edit" href="{{ route('settings.location.edit',$item->id) }}" type="button" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> </a>
                                        <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> </button>
                                        <form id="form_{{$item->id}}" hidden action="{{route('settings.location.delete')}}" method="post">
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
