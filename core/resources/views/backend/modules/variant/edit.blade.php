<form  method="POST" id="updateForm" enctype="multipart/form-data">
    @csrf



    <div class="row">
        <input type="hidden" name="id" value="{{$variant->id}}">
        <div class="col-lg-6 col-sm-12 col-12">
            <div class="input-blocks">
                <label>Variant Type <span class="text-danger fs-4">*</span></label>
                <select name="type" id="type" class="form-control">
                    <option value="edition" {{ $variant->type == 'edition' ? 'selected' : '' }}>Edition</option>
                    <option value="paper_quality" {{ $variant->type == 'paper_quality' ? 'selected' : '' }}>Paper Quality</option>
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 col-12">
            <div class="input-blocks">
                <label>Variant Name <span class="text-danger fs-4">*</span></label>
                <input type="text" name="name" class="form-control" value="{{$variant->name}}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>

</form>
