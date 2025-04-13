@extends('backend.layouts.master')

@section('meta')
    <title>Update User | {{ get_option('title') }}</title>
@endsection

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Update User</h4>
                <h6>User Management</h6>
            </div>


        </div>
        <div class="page-btn">
            <a href="{{ route('user.list') }}" class="btn btn-added">
                <i data-feather="arrow-left" class="me-2"></i>Back to user</a>
        </div>

    </div>

    <div class="card table-list-card">
        <div class="card-body">

            <div class="page-wrapper-new p-0">
                <div class="content px-5 py-5">
                    <form action="{{ route('user.profile.update.ajax') }}" id="bintelForm" enctype="multipart/form-data"
                        method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <div class="row justify-content-center">

                            <div class="col-lg-12 pe-0 d-flex justify-content-center">
                                <div class="mb-3 required">

                                    <div class="new-employee-field">
                                        <div class="profile-pic-upload">
                                            <div class="profile-pic">

                                                <img id="img-holder" src="@if(empty($user->image)){{asset('theme/common/uplaod.jpg')}} @else {{ asset($user->image) }} @endif" alt=""
                                                    onclick="triggerFileInput()">
                                                <input type="file" class="d-none" id="file-input" name='image'
                                                    accept="image/*" onchange="previewImage(event)">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-4 pe-0">
                                <div class="mb-3 required">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" required
                                        value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="col-lg-4 pe-0">
                                <div class="mb-3 required">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required
                                        value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="col-lg-4 pe-0">
                                <div class="mb-3 required">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required
                                        value="{{ $user->username }}">
                                </div>
                            </div>
                            <div class="col-lg-4 pe-0">
                                <div class="mb-3 required">
                                    <label class="form-label">Phone</label>
                                    <input type="number" name="phone" class="form-control" required
                                        value="{{ $user->phone }}">
                                </div>
                            </div>

                            <div class="col-lg-4 pe-0">
                                <div class="mb-3 required">
                                    <label class="form-label">Gender</label>
                                    <select class="select" name="gender" required>
                                        <option value="male" @if ($user->gender == 'male') selected @endif>Male
                                        </option>
                                        <option value="female" @if ($user->gender == 'female') selected @endif>Female
                                        </option>
                                    </select>
                                </div>
                            </div>



                            <div class="col-lg-4 pe-0">
                                <div class="mb-3 required">
                                    <label class="form-label">User Role</label>
                                    <select class="select" name="user_role" required>
                                        <option>Choose</option>
                                        @foreach ($roles as $single)
                                            <option value="{{ $single->id }}" @if($user->user_role==$single->id) selected @endif>{{ Str::title($single->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 pe-0">
                                <div class="mb-3 required">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" autocomplete="new-password">
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer-btn">
                            <button type="submit" class="btn btn-submit">Update User</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
@endsection
