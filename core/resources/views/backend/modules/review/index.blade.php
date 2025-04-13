@extends('backend.layouts.master')
@section('meta')
    <title>All Reviews- {{ get_option('title') }}</title>
@endsection

@section('content')
    <div id="app">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Reviews</h4>
                    <h6>Manage Reviews</h6>
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
                        <h5 class="modal-title" id="exampleModalLabel">Reviews</h5>
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
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter text here" v-model="formData.name">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-sm-12 col-12">
                                                            <div class="mb-3 add-product required">
                                                                <label class="form-label">Comment</label>
                                                                <textarea class="form-control" placeholder="Leave a comment here" v-model="formData.comment" id="comment"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                            <div class="mb-3 add-product">
                                                                <label class="form-label">Image (128x128)</label>
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
                        <h5 class="modal-title" id="exampleModalLabel">Reviews</h5>
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
                                                                <input type="hidden" class="form-control"
                                                                    placeholder="Enter text here" v-model="formData.id">
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter text here" v-model="formData.name">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-sm-12 col-12">
                                                            <div class="mb-3 add-product required">
                                                                <label class="form-label">Comment</label>
                                                                <textarea class="form-control" placeholder="Leave a comment here" v-model="formData.comment" id="comment"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                            <div class="mb-3 add-product">
                                                                <label class="form-label">Review Image</label>
                                                                <div class="form-group" id="icon">

                                                                    <img :src="getImageUrl(imageUrl)" alt="Image not found"
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
            <div class="col-md-12">
                <div class="card table-list-card">
                    <div class="card-body p-4">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-input">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">

                            <div style="position: relative; display: inline-block; margin-top:10px;">
                                <input type="text" v-model="search" placeholder="Search by name" style="width: 100%"
                                    class="form-control mb-3">
                                <i v-if="search" @click="clearSearch()" class="fas fa-times"
                                    style="position: absolute; right: 10px; top: 40%; transform: translateY(-50%); cursor: pointer;"></i>
                            </div>


                            <table class="table table-hover" style="width:100%; border: 1px solid #ddd;">
                                <thead>
                                    <tr style="border-bottom: 1px solid #ddd;">
                                        <th style="font-size: 18px; font-weight:600; width:100px; border-right: 1px solid #ddd;">SN</th>
                                        <th style="font-size: 18px; font-weight:600; width:150px; border-right: 1px solid #ddd;">Name</th>
                                        <th style="font-size: 18px; font-weight:600; width: 200px; border-right: 1px solid #ddd;">Comment</th>
                                        <th style="font-size: 18px; font-weight:600; width:10px; border-right: 1px solid #ddd;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(review, index) in paginatedData" :key="review.id" style="border-bottom: 1px solid #ddd;">
                                        <td style="border-right: 1px solid #ddd;">@{{ calculateSerialNumber(index) }}</td>
                                        <td style="border-right: 1px solid #ddd;">@{{ review.name }}</td>
                                        <td style="border-right: 1px solid #ddd;">
                                            <span v-if="isTruncated(review.comment)">
                                                @{{ truncatedComment(review.comment) }}
                                                <button class="btn btn-link" @click="showFullComment(review.comment)">
                                                    See more
                                                </button>
                                            </span>
                                            <span v-else>
                                                @{{ review.comment }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-table-data">
                                                <div class="edit-delete-action">
                                                    <a class="btn btn-info me-2 p-2" @click="showData(review.id)" data-bs-toggle="modal" data-bs-target="#statusUpdateModal">
                                                        <i class="fa fa-edit text-white"></i>
                                                    </a>
                                                    <a class="btn btn-danger p-2" @click="confirmDelete(review.id)">
                                                        <i class="fa fa-trash text-white"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <nav class="mt-4">
                                <ul class="pagination">
                                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                        <a class="page-link" @click.prevent="changePage(currentPage - 1)" href="#"
                                            style="cursor: pointer;">Previous</a>
                                    </li>
                                    <li class="page-item" v-for="page in totalPages" :key="page"
                                        :class="{ active: page === currentPage }">
                                        <a class="page-link" @click.prevent="changePage(page)"
                                            href="#">@{{ page }}</a>
                                    </li>
                                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                        <a class="page-link" @click.prevent="changePage(currentPage + 1)" href="#"
                                            style="cursor: pointer;">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- full comment modal --}}
        <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentModalLabel">Full Comment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @{{ fullComment }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- full comment modal --}}
    </div>
@endsection
@section('script')
    <script src="{{ asset('theme/admin/assets/vue/vue.js') }}" type="text/javascript"></script>
    <script src="{{ asset('theme/admin/assets/vue/axios.min.js') }}" type="text/javascript"></script>
    {{-- this page vue scripts --}}
    @include('backend.modules.review.vue_script')
@endsection
