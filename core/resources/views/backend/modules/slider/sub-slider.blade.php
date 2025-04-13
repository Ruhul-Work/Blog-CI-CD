@extends('backend.layouts.master')
@section('meta')
    <title>Sub Slider - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div id="app">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Sub Slider</h4>
                    <h6>Manage your Sub Slider</h6>
                </div>
            </div>

            <div class="page-btn">
                <a href="javascript:void(0)" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#statusModal">
                    <i data-feather="plus-circle"></i>Add New
                </a>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Sub Slider</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitForm" enctype="multipart/form-data">
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
                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                            <div class="mb-3 add-product">
                                                                <label class="form-label">Slider Image</label>
                                                                <div class="form-group" id="icon">
                                                                    <input type="file" id="image" name="image"
                                                                        class="form-control" ref="image"
                                                                        @change="previewImage">
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <img :src="imageUrl" v-if="imageUrl"
                                                                    style="max-width: 200px; max-height: 200px;">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                             <label class="form-label">Url</label>
                                                             <input type="text" class="form-control" id="link" name="link"
                                                             placeholder="Enter url here" v-model="formData.url">
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
                                    <button type="submit" class="btn btn-submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Update Modal -->
        <div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Sub Slider</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="updateOrderstatus">
                        <form @submit.prevent="updateForm" enctype="multipart/form-data">
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

                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                            <div class="mb-3 add-product">
                                                                <label class="form-label">Slider</label>
                                                                <div class="form-group" id="icon">
                                                                    <!-- Display image thumbnail -->
                                                                    <img :src="getImageUrl(imageUrl)" alt="Icon Image"
                                                                        class="img-thumbnail mb-2" width="100">
                                                                    <!-- Input for uploading new image -->
                                                                    <input type="file" id="image" name="image"
                                                                        class="form-control" ref="image">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                             <label class="form-label">Url</label>
                                                             <input type="text" class="form-control" id="link" name="link"
                                                             placeholder="Enter url here" v-model="formData.url">
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
                                    <!-- Submit button for updating -->
                                    <button type="submit" class="btn btn-submit">Update</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3" v-for="(item, index) in data" :key="index">
                <div class="card">
                    <div class="card-header">
                        <h3> (Sub Slider @{{ index + 1 }})</h3>
                    </div>
                    <div class="card-body">
                        <div class="card mb-3">
                            <img :src="getImageUrl(item.image)" class="card-img-top" alt="Image"
                                style="width: 100%; height: auto; object-fit: cover;">

                            <div class="card-body">
                                <div class="action-buttons">
                                    <button class="btn btn-info me-2" @click="showData(item.id)" data-bs-toggle="modal"
                                        data-bs-target="#statusUpdateModal">
                                        <i class="fa fa-edit text-white"></i> Edit
                                    </button>
                                    <button class="btn btn-danger" @click="confirmDelete(item.id)">
                                        <i class="fa fa-trash text-white"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Pagination -->
                    </div>
                </div>

            </div>
        </div>

        <!-- Image View Modal -->
        <div class="modal fade" id="imageViewModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Image Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="" id="modalImage" class="img-fluid" alt="Image Preview">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('theme/admin/assets/vue/vue.js') }}" type="text/javascript"></script>
    <script src="{{ asset('theme/admin/assets/vue/vue-router.js') }}" type="text/javascript"></script>
    <script src="{{ asset('theme/admin/assets/vue/axios.min.js') }}" type="text/javascript"></script>
    {{-- this page vue scripts --}}
    @include('backend.modules.slider.vue_script')
@endsection
