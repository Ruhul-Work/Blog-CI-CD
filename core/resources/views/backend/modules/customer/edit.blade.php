<form  method="POST" id="updateForm" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <input type="hidden" name="id" value="{{ $user->id }}">

        {{-- <div class="col-12 col-sm-6">

            <div class="input-block">
                <label>User Type<span class="star-sign">*</span></label>
                <select name="user_type" id="user_type" class="form-control">
                    <option value="">Select User Type</option>
                    @foreach($userTypes as $userType)
                        <option value="{{ $userType }}" {{ $user->user_type == $userType ? 'selected' : '' }}>{{ $userType }}</option>
                    @endforeach
                </select>
            </div>

        </div> --}}

        <div class="col-lg-6 col-sm-12 col-12">
            <div class="input-blocks">
                <label>Customer Name <span class="star-sign">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}">
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-12">
            <div class="input-blocks">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}">
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-12">
            <div class="input-blocks">
                <label>Phone <span class="star-sign">*</span></label>
                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-12">
            <div class="input-blocks">
                <label>Alternative Phone</label>
                <input type="text" name="phone_alt" class="form-control" value="{{ $user->phone_alt }}">
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-12 pe-0">
            <div class="mb-3">
                <label class="form-label">Gender</label>
                <select  name="gender" required class="form-control" >
                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
        </div>
{{-- 
        <div class="col-lg-6 col-sm-12 col-12 pe-0">
            <div class="mb-3 required">
                <label class="form-label">User Role</label>
                <select class="form-control" name="user_role" required>
                    <option>Choose</option>
                    @foreach($role as $single)
                        <option value="{{ $single->id }}" {{ $user->user_role == $single->id ? 'selected' : '' }}>{{ Str::title($single->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div> --}}

        <div class="col-lg-6 col-sm-12 col-12 pe-0">
            <div class="mb-3 required">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" autocomplete="new-password" placeholder="Enter new password if you want to change">
            </div>
        </div>

        <div class="col-lg-6 col-sm-12 col-12">
            <div class="mb-3 add-product">
                <label class="form-label">Image</label>
                <div class="form-group">
                    <div class="row" >
                        @if($user->image)
                            <img src="{{ asset($user->image) }}" alt="User Image" width="100">
                        @endif
                        <input type="file" name="image" class="form-control mt-2">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-submit">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="text">Update</span>
                </button>
            </div>
        </div>
    </div>

</form>






