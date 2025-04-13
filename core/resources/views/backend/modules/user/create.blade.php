<div class="page-wrapper-new p-0">
    <div class="content">
        <div class="modal-header border-0 custom-modal-header">
            <div class="page-title">
                <h4>Add New User</h4>
            </div>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body custom-modal-body">
            <form action="{{ route('user.create.ajax') }}" id="bintelForm" enctype="multipart/form-data" method="post">
                @csrf
                <div class="row">

                    <div class="col-lg-12 pe-0 d-flex justify-content-center">
                        <div class="mb-3 required">
                            
                            <div class="new-employee-field">
                                <div class="profile-pic-upload">
                                    <div class="profile-pic">
                                        
                                        <img  id="img-holder" src="{{asset('theme/common/uplaod.jpg')}}" alt=""  onclick="triggerFileInput()">
                                        <input type="file" class="d-none" id="file-input" name='image' accept="image/*"   onchange="previewImage(event)">
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-12 pe-0">
                        <div class="mb-3 required">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-12 pe-0">
                        <div class="mb-3 required">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-lg-6 pe-0">
                        <div class="mb-3 required">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-6 pe-0">
                        <div class="mb-3 required">
                            <label class="form-label">Phone</label>
                            <input type="number" name="phone" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-lg-6 pe-0">
                        <div class="mb-3 required">
                            <label class="form-label">Gender</label>
                            <select class="selectSimple" name="gender" required>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-lg-6 pe-0">
                        <div class="mb-3 required">
                            <label class="form-label">User Role</label>
                            <select class="selectSimple" name="user_role" required>
                                <option>Choose</option>
                                @foreach ($role as $single)
                                    <option value="{{ $single->id }}">{{ Str::title($single->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12 pe-0">
                        <div class="mb-3 required">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" autocomplete="new-password"
                                required>
                        </div>
                    </div>


                </div>
                <div class="modal-footer-btn">
                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-submit">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>
