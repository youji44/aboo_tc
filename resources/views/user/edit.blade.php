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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} >Settings > User management > Edit</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12 mt-2">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Add A User</h4>
                    @include('notifications')
                    <form action="{{route('settings.user.update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input hidden name="uid" value="{{$user->id}}">
                        <div class="form-group">
                            <label class="col-form-label" for="username">Username</label>
                            <input value="{{$user->username}}" class="form-control" type="text" id="username" name="username">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="name">Full Name</label>
                            <input value="{{$user->name}}" class="form-control" type="text" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="email">Email address</label>
                            <input value="{{$user->email}}" class="form-control" type="email" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="password">Password</label>
                            <input class="form-control" type="password" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="passwordconfirm">Confirm Password</label>
                            <input class="form-control" type="password" id="passwordconfirm" name="passwordconfirm">
                        </div>
                        <div class="form-group">
                            <label for="rid" class="col-form-label">Roles</label>
                            <select id="rid" name="rid" class="custom-select">
                                @foreach($roles as $item)
                                    <option {{$item->id==$user->role_id?'selected':''}} value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <b class="text-muted mb-3 d-block">PRIMARY LOCATIONS</b>
                            @foreach($locations as $item)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" {{ in_array($item->id,$location_ids)?'checked="checked"':''}} name="location_{{$item->id}}" id="location_{{$item->id}}">
                                    <label class="custom-control-label" for="location_{{$item->id}}">{{$item->location}}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <b class="text-muted mb-3 d-block">USER ACCESS</b>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" {{ $user->qc==0?'checked="checked"':''}} name="qc" id="qc">
                                <label class="custom-control-label" for="qc">Quality Control</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" {{ $user->fm==0?'checked="checked"':''}} name="fm" id="fm">
                                <label class="custom-control-label" for="fm">Fleet Management</label>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label class="col-form-label" for="staff_position">Staff Position</label>
                            <input class="form-control" value="{{$staff_position}}" type="text" id="staff_position" name="staff_position">
                        </div>
                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Update</button>
                        <a href="{{ route('settings.user') }}" class="btn btn-outline-danger mt-4 pr-4 pl-4"><i class="ti-reload"> </i> Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
@stop
