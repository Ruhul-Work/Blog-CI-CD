@extends('backend.layouts.master')

@section('meta')
    <title>My Profile</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h4>Profile</h4>
            <h6>User Profile</h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="bintelForm" action="{{route('my.profile.ajax')}}" enctype="multipart/form-data" method="post">
                <div class="profile-set">
                    <div class="profile-head">
                    </div>
                    <div class="profile-top">
                        <div class="profile-content">
                            <div class="profile-contentimg">
                                <img id="img-holder"
                                    src="@if (empty(Auth::user()->image)) {{ asset('theme/common/uplaod.jpg') }} @else {{ asset(Auth::user()->image) }} @endif"
                                    alt="" onclick="triggerFileInput()">
                                <input type="file" class="d-none" id="file-input" name='image' accept="image/*"
                                    onchange="previewImage(event)">
                            </div>
                            <div class="profile-contentname">
                                <h2>{{ Auth::user()->name }}</h2>
                                <h4 class="text-capitalize">{{ Auth::user()->role->name }}</h4>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks required">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" name="name"
                                required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks required">
                            <label class="form-label">Mobile</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->phone }}" name="phone"
                                required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks required">
                            <label>Email</label>
                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" name="email"
                                required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks required">
                            <label class="form-label">Username</label>
                            <input type="text" value="{{ Auth::user()->username }}" name="username" required>
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks required">
                            <label class="form-label">Password</label>
                            <div class="pass-group">
                                <input type="password" class="pass-input form-control" name="password"
                                    autocomplete="new-password" >
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12">
                        <div class="input-blocks">
                            <label class="form-label">Address</label>
                            <textarea name="address" id="" cols="30" rows="10">{{Auth::user()->address}}</textarea>
                        </div>
                    </div>


                    <div class="col-12">
                        <button type="submit" class="btn btn-submit me-2">Update</button>
                        <a href="javascript:void(0);" class="btn btn-cancel">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
@endsection
