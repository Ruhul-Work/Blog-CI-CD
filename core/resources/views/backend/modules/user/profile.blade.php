<div class="page-wrapper-new p-0">
    <div class="content">
        <div class="modal-header border-0 custom-modal-header">
            <div class="page-title">
                <h4>Profile</h4>
            </div>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body custom-modal-body">

            <div class="profile-set">
                <div class="profile-head">
                </div>
                <div class="profile-top">
                    <div class="profile-content">
                        <div class="profile-contentimg">
                            <img src="{{ image($user->image) }}" alt="img">

                        </div>
                        <div class="profile-contentname">
                            <h2>{{ $user->name }}</h2>
                            <h4>{{ $user->email }}</h4>
                        </div>
                    </div>

                </div>
            </div>
            <p class="card-text">
            <div class="row">
                <div class="col-6">
                    <strong>Username:</strong> {{ $user->username }}<br>
                    <strong>Phone:</strong> {{ $user->phone }}<br>
                    <strong>Gender:</strong> {{ $user->gender }}<br>

                </div>

                <div class="col-6">
                    <strong>Type:</strong> ADMIN<br>
                    <strong>Role:</strong> {{ $user->role->name }}<br>
                    <strong>Lastlogin:</strong> {{ $user->last_login }}
                </div>

            </div>

            </p>
            <a href="#" class="btn btn-outline-danger w-100"> <i class="fas fa-edit"></i> UPDATE</a>

        </div>
    </div>
</div>
