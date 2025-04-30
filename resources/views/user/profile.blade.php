@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    User Profile
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
                                    <h4 class="page-title pull-left"> User Profile > Edit</h4>
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

                    @include('notifications')
                    <form action="{{route('user.profile.update')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input hidden name="uid" value="{{$user->id}}">
                        <div class="form-gp">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="{{ $user->username }}" readonly>
                            <i class="ti-check-box"></i>
                            <div class="text-success"></div>
                        </div>
                        <div class="form-gp">
                            <label for="name">User Name</label>
                            <input type="text" id="name" name="name" value="{{ $user->name }}" required>
                            <i class="ti-user"></i>
                            <div class="text-success"></div>
                        </div>
                        <div class="form-gp">
                            <label for="email">Email address</label>
                            <input type="email" id="email" name="email" value="{{$user->email}}">
                            <i class="ti-email"></i>
                            <div class="text-success"></div>
                        </div>
                        <div class="form-gp">
                            <label for="oldpassword">Old Password</label>
                            <input type="password" id="oldpassword" name="oldpassword">
                            <i class="ti-lock"></i>
                            <div class="text-success"></div>
                        </div>
                        <div class="form-gp">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password">
                            <i class="ti-lock"></i>
                            <div class="text-success"></div>
                        </div>
                        <div class="form-gp">
                            <label for="passwordconfirm">Confirm Password</label>
                            <input type="password" id="passwordconfirm" name="passwordconfirm">
                            <i class="ti-lock"></i>
                            <div class="text-success"></div>
                        </div>
                        <button type="submit" class="btn btn-success mt-4 pr-4 pl-4"><i class="ti-save"> </i> Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
@stop