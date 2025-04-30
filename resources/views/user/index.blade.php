 @extends('layouts.layout')

{{-- Page title --}}
@section('title')
    User Management
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
                                    <h4 class="page-title pull-left">Settings > User Management</h4>
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
            <a class="btn btn-success btn-sm" href="{{ route('settings.user.add') }}"><i class="ti-plus"></i> Add New</a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl mt-2">
            <div class="card">
                @include('notifications')
                <div class="card-body">
                    <div class="single-table">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                <thead class="text-uppercase">
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">FULL NAME</th>
                                    <th scope="col">USERNAME</th>
                                    <th scope="col">POSITION</th>
                                    <th scope="col">USER ROLE</th>
                                    <th scope="col">PRIMARY LOCATION</th>
                                    <th scope="col">PORTAL ACCESS</th>
                                    <th scope="col">ACTION</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($no=1)
                                @foreach($users  as $item)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->username}}</td>
                                    <td>{{$item->staff_position}}</td>
                                    <td>{{$item->role_name}}</td>
                                    <td>
                                        @foreach($locations as $location)
                                            @if(json_decode($item->location_ids)!=null && in_array($location->id,json_decode($item->location_ids)))
                                            <div class="alert p-1 m-1" style="background-color: {{$location->location_color}};color:#ffffff">{{$location->location}}</div>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        {!! $item->qc==0?'<div class="alert alert-success">QC Access</div>':''!!}
                                        {!! $item->fm==0?'<div class="alert alert-info">FM Access</div>':''!!}
                                    </td>
                                    <td>
                                        <a href="{{ route('settings.user.edit',$item->id) }}" class="btn btn-success btn-sm"><i class="ti-pencil-alt"></i> Edit</a>
                                        <form hidden id="{{"reset_".$item->id}}" action="{{ route('settings.user.reset') }}" method="POST">
                                            @csrf
                                            <input hidden value="{{$item->id}}" name="uid">
                                        </form>
                                        <button onclick="reset({{$item->id}})" class="btn btn-info btn-sm"><i class="ti-lock"></i> Reset Password</button>
                                        <form hidden id="{{"deny_".$item->id}}" action="{{ route('settings.user.delete') }}" method="POST">
                                            @csrf
                                            <input hidden value="{{$item->id}}" name="uid">
                                        </form>
                                        <button onclick="deny({{$item->id}})" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
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
        function reset(id) {
            $("#reset_"+id).submit()
        }
        function deny(id) {
            $("#deny_"+id).submit()
        }
    </script>
@stop
