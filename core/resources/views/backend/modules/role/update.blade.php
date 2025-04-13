<div class="page-wrapper-new p-0">
    <div class="content">
        <div class="modal-header border-0 custom-modal-header">
            <div class="page-title">
                <h4>Update Role</h4>
            </div>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ route('role.update.ajax',$role->id) }}" id="bintelForm" enctype="multipart/form-data" method="post">
            <div class="modal-body custom-modal-body">

                @csrf
                <div class="col-lg-12 pe-0">
                    <div class="mb-3 required">
                        <label class="form-label">Role Name</label>
                        <input type="text" name="name" class="form-control" style="width:400px"
                            value="{{ $role->name }}" required>
                    </div>
                </div>

                <div class="modal-content" style="max-height: 50vh;overflow-y:scroll;border:none;overflow-x: hidden;">
                    <div class="row justify-content-center">

                        <div class="col-lg-12 pe-0">
                            <div class="table-responsive">
                                <table class="table  datanew">
                                    <thead>
                                        <tr>
                                            <th class="no-sort" style="width:50px">

                                            </th>
                                            <th>Module</th>
                                            <th style="width:50%">Group Permissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @php
                                            $old = [];
                                            $oldPermissions = $role->permissions;

                                            if (!empty($oldPermissions)) {
                                                $old = explode(',', $oldPermissions);
                                            }

                                            //print_r($old);

                                        @endphp


                                        @foreach ($permissions as $module => $modulePermissions)
                                            @php
                                                $flag = '';
                                            @endphp
                                            @foreach ($modulePermissions as $permission)
                                                @if (in_array($permission->id, $old))
                                                    @php
                                                        $flag = 'checked';
                                                    @endphp
                                                @endif
                                            @endforeach
                                            <tr>
                                                <td>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" {{ $flag }}>
                                                        <span class="checkmarks ckall"></span>
                                                    </label>
                                                </td>
                                                <td><strong>{{ $module }}</strong></td>
                                                <td class="d-flex">
                                                    @foreach ($modulePermissions as $permission)
                                                        <label class="checkboxs mx-2">
                                                            {{ $permission->name }}
                                                            <input type="checkbox" class="custom-check"
                                                                name="permissions[]" value="{{ $permission->id }}"
                                                                @if (in_array($permission->id, $old)) checked @endif>
                                                            <span class="checkmarks"></span>
                                                        </label>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="modal-footer-btn">
                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-submit">Update</button>
                </div>

            </div>

        </form>
    </div>
</div>
