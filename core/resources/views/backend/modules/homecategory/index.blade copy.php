@extends('backend.layouts.master')
@section('meta')
    <title>Home Category - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div id="app">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Home Category</h4>
                    <h6>Manage Home Category</h6>
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
                        <h5 class="modal-title" id="exampleModalLabel">Home Category</h5>
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
                                                        <div class="col-lg-12 col-sm-12 col-12">
                                                            <div class="mb-3 add-product required">
                                                                <label class="form-label">Name</label>
                                                                {{-- <input type="text" class="form-control"
                                                                    v-model="formData.name" placeholder="Enter text here"> --}}
                                                                <select class="form-select form-control" v-model="formData.name"
                                                                    width="100%">
                                                                    <option value="author">Author</option>
                                                                    <option value="publisher">Publisher</option>
                                                                    <option value="category">Category</option>
                                                                    <option value="review">Review</option>
                                                                </select>

                                                            </div>
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
                        <h5 class="modal-title" id="exampleModalLabel">Home Banner</h5>
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
                                                        <div class="col-lg-12 col-sm-12 col-12">
                                                            <div class="mb-3 add-product required">
                                                                <label class="form-label">Name</label>
                                                                <!-- Hidden input for ID -->
                                                                <input type="hidden" v-model="formData.id">
                                                                <!-- Text input for Name -->
                                                                <input type="text" class="form-control"
                                                                    v-model="formData.name" placeholder="Enter text here">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-sm-12 col-12 required">
                                                            <label class="form-label">Link</label>
                                                            <!-- Text input for Link -->
                                                            <input type="text" class="form-control"
                                                                v-model="formData.link" placeholder="Enter link here">
                                                        </div>
                                                        <div class="col-lg-12 col-sm-12 col-12 required">
                                                            <label class="form-label">Status</label>
                                                            <!-- Select input for Status -->
                                                            <select class="form-select" v-model="formData.status"
                                                                width="100%">
                                                                <option value="1">Active</option>
                                                                <option value="0">Inactive</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                            <div class="mb-3 add-product">
                                                                <label class="form-label">Icon Image</label>
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
            {{-- <div class="col-md-3" v-for="(item, index) in data" :key="index">
                <div class="card">
                    <div class="card-header">
                        <h3>(Home Category @{{ index + 1 }})</h3>
                    </div>
                    <div class="card-body">
                        <div class="card mb-3">
                            <div class="card-body">
                                <a :href="generateUrl(item.id)">
                                    <h5 class="card-title">@{{ item.name }}</h5>
                                </a>

                                <button v-if="item.has_category_section==false" class="btn btn-info me-2 mb-2" @click="showData(item.id)" data-bs-toggle="modal"
                                data-bs-target="#statusUpdateModal">
                                <i class="fa fa-edit text-white"></i> Add
                            </button>
                                <button v-if="item.has_category_section==true" class="btn btn-info me-2 mb-2" @click="showData(item.id)" data-bs-toggle="modal"
                                data-bs-target="#statusUpdateModal">
                                <i class="fa fa-edit text-white"></i> view
                            </button>

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
                    </div>
                </div>
            </div> --}}
            <div class="col-md-3" v-for="(item, index) in data" :key="index">
                <div class="card">
                    <div class="card-header">
                        <h3>@{{ item.name }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="card mb-3">
                            <div class="card-body">

{{-- category --}}
                                <a v-if="item.name==category" :href="generateUrlAdd(item.id)">
                                    <h5 class="card-title">@{{ item.name }}</h5>
                                </a>
                                <!-- Button to add or view based on has_category_section -->
                                <a  :href="generateUrlAdd(item.id)" hr v-if="!item.has_category_section" v-if="item.name==category" class="btn btn-info me-2 mb-2" >
                                    <i class="fa fa-plus text-white"></i> Add
                                </a>

                                <a v-if="item.has_category_section" :href="generateUrlView(item.id)" v-else class="btn btn-info me-2 mb-2">
                                    <i class="fa fa-eye text-white"></i> View
                                </a>

{{-- authors --}}
                                <a v-if="item.name==authors" :href="homeAuthorUrl(item.id)">
                                    <h5 class="card-title">@{{ item.name }}</h5>
                                </a>
                                <!-- Button to add or view based on has_category_section -->
                                <a  v-if="item.name==authors" :href="generateUrlAdd(item.id)" hr v-if="!item.has_authors_section"  class="btn btn-info me-2 mb-2" >
                                    <i class="fa fa-plus text-white"></i> Add
                                </a>

                                <a v-if="item.has_authors_section" :href="generateUrlView(item.id)" v-else class="btn btn-info me-2 mb-2">
                                    <i class="fa fa-eye text-white"></i> View
                                </a>

                                {{-- publishers --}}
                                <a v-if="item.name==publisher" :href="homePublisherUrl(item.id)">
                                    <h5 class="card-title">@{{ item.name }}</h5>
                                </a>
                                <!-- Button to add or view based on has_category_section -->
                                <a  v-if="item.name==publisher" :href="homePublisherUrl(item.id)" hr v-if="!item.has_publishers_section"  class="btn btn-info me-2 mb-2" >
                                    <i class="fa fa-plus text-white"></i> Add
                                </a>

                                <a v-if="item.has_publishers_section" :href="generateUrlView(item.id)" v-else class="btn btn-info me-2 mb-2">
                                    <i class="fa fa-eye text-white"></i> View
                                </a>

                                {{-- reviews --}}
                                <a v-if="item.name==review" :href="homeReviewUrl(item.id)">
                                    <h5 class="card-title">@{{ item.name }}</h5>
                                </a>
                                <!-- Button to add or view based on has_category_section -->
                                <a  v-if="item.name==review" :href="homeReviewUrl(item.id)" hr v-if="!item.has_review_section"  class="btn btn-info me-2 mb-2" >
                                    <i class="fa fa-plus text-white"></i> Add
                                </a>

                                <a v-if="item.has_review_section" :href="generateUrlView(item.id)" v-else class="btn btn-info me-2 mb-2">
                                    <i class="fa fa-eye text-white"></i> View
                                </a>





                                <!-- Edit and Delete buttons -->
                                <div class="action-buttons">
                                    <button class="btn btn-danger" @click="confirmDelete(item.id)">
                                        <i class="fa fa-trash text-white"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Image View Modal -->

    </div>
@endsection

@section('script')
    {{-- vue js cdn --}}
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    {{-- vue router if need --}}
    <script src="https://cdn.jsdelivr.net/npm/vue-router@3.5.2/dist/vue-router.js"></script>
    {{-- axios cdn --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    {{-- this page vue scripts --}}
    @include('backend.modules.homecategory.vue-script')
@endsection
