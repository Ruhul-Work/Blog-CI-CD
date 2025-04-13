<div class="page-wrapper-new p-0">
    <div class="content">
        <div class="modal-header border-0 custom-modal-header">
            <div class="page-title">
                <h4>Add New Rule</h4>
            </div>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body custom-modal-body">
            <form action="{{ route('firewall.new.ajax') }}" id="bintelForm" enctype="multipart/form-data" method="post">
                @csrf
                <div class="row">

                    <div class="col-lg-12 pe-0">
                        <div class="mb-3 required">
                            <label class="form-label">IP Address</label>
                            <input type="text" name="ip_address" class="form-control" placeholder="10.10.10.103" required>
                        </div>
                    </div>
                    <div class="col-lg-12 pe-0">
                        <div class="mb-3 required">
                            <label class="form-label">Type</label>
                            <select class="selectSimple" name="type" required>
                                <option value="White_listed">White List</option>
                                <option value="Black_listed">Black List</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12 pe-0">
                        <div class="mb-3 required">
                            <label class="form-label">Note</label>
                            <textarea name="comments" class="form-control" placeholder="note....."></textarea>
                        </div>
                    </div>


                </div>
                <div class="modal-footer-btn">
                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-submit">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
