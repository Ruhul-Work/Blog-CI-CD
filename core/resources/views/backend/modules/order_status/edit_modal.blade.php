<form action="#" id="StatuseditForm" method="post" enctype="multipart/form-data">
    @csrf
 
    <div class="card">
        <div class="card-body add-product pb-0">
            <div class="accordion-card-one accordion" id="accordionExample">
                <div class="accordion-item">
                    <div class="accordion-header" id="headingOne">
                        <div class="accordion-button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-controls="collapseOne">
                            <div class="addproduct-icon">
                                <h5><i data-feather="info" class="add-info"></i><span>Basic
                                        Information</span></h5>
                                <a href="javascript:void(0);"><i data-feather="chevron-down"
                                        class="chevron-down-add"></i></a>
                            </div>
                        </div>
                    </div>
                    <div id="collapseOne" class="accordion-collapse collapse show"
                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">

                                <div class="col-lg-12 col-sm-12 col-12">
                                    <div class="mb-3 add-product required">
                                        <label class="form-label">Name</label>

                                        <input type="hidden" class="form-control" id="name"
                                            name="id" value="{{ $statuses->id }}">

                                        <input type="text" class="form-control" id="name"
                                            name="name" placeholder="Enter text here"  value="{{ $statuses->name }}">
                                    </div>
                                </div>

                                <div class="col-lg-12 col-sm-12 col-12 required">
                                    <label class="form-label">Status</label>
                                    <select class="form-select select" name="status" width="100%">
                                        <option value="1" {{ $statuses->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $statuses->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-12">
        <div class="btn-addproduct mb-4">
            <button type="submit" class="btn btn-submit">Update Status</button>
        </div>
    </div>

</form>
