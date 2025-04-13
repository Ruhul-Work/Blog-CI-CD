@extends('backend.layouts.master')
@section('meta')
    <title>All Countries- {{ get_option('title') }}</title>
@endsection

@section('content')
    <div id="app">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>All Countries</h4>
                    <h6>Manage All Countries</h6>
                </div>
            </div>

            {{-- <div class="page-btn">
                <a href="javascript:void(0)" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#statusModal">
                    <i data-feather="plus-circle"></i>Add New
                </a>
            </div> --}}
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
                        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
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

                <div class="page-btn" style="padding-bottom: 15px;
        text-align: end;">
                    <a href="javascript:void(0)" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i data-feather="plus-circle"></i>Add Country
                    </a>
                </div>

                <div class="card table-list-card">

                    <div class="card-body p-4">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-input">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">

                            <div  style="position: relative; display: inline-block; margin-top: 10px">
                                <input type="text" v-model="search" placeholder="Search by name" style="width: 100%"
                                    class="form-control mb-3">
                                <i v-if="search" @click="clearSearch()" class="fas fa-times"
                                    style="position: absolute; right: 10px; top: 40%; transform: translateY(-50%); cursor: pointer;"></i>
                            </div>
                            <table class="table table-hover" style="width:100%; border: 1px solid #ddd;">
                                <thead>
                                    <tr>
                                        <th style="font-size: 18px; font-weight:600px; width:100px">SN</th>
                                        <th style="font-size: 18px; font-weight:600px; width:150px">Name</th>
                                        <th style="font-size: 18px; font-weight:600px; width:10px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(country, index) in paginatedData" :key="country.id">

                                        <td>@{{ calculateSerialNumber(index) }}</td>
                                        <td>@{{ country.name }}</td>

                                        <td>
                                            <div class="action-table-data">
                                                <div class="edit-delete-action">
                                                    <a class="btn btn-info me-2 p-2" @click="showData(country.id)"
                                                        data-bs-toggle="modal" data-bs-target="#statusUpdateModal">
                                                        <i class="fa fa-edit text-white"></i>
                                                    </a>
                                                    {{-- <a class="btn btn-danger p-2" @click="confirmDelete(country.id)">
                                                        <i class="fa fa-trash text-white"></i>
                                                    </a> --}}
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
    </div>
@endsection
@section('script')
    {{-- vue js cdn --}}
    <script src="{{asset('theme/admin/assets/vue/vue.js')}}" type="text/javascript"></script>
    <script src="{{asset('theme/admin/assets/vue/axios.min.js')}}" type="text/javascript"></script>
    {{-- this page vue scripts --}}
    @include('backend.modules.setting.vue_script')
@endsection
