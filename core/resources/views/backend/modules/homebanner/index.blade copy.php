@extends('backend.layouts.master')
@section('meta')
    <title>Home cart - {{ get_option('title') }}</title>
@endsection

@section('content')
    <div id="app">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Home cart</h4>
                    <h6>Manage your Home cart</h6>
                </div>
            </div>
            <ul class="table-top-head">
                @include('backend.include.buttons')
                <li>
                    <a href="{{ route('orderstatuses.all.delete') }}" class="delete-btn-group" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Delete Selected">
                        <img src="{{ asset('theme/admin/assets/img/icons/delete.svg') }}" alt="img">
                    </a>
                </li>
            </ul>
            <div class="page-btn">
                <a href="javascript:void(0)" class="btn btn-primary me-2" data-bs-toggle="modal"
                    data-bs-target="#statusModal">
                    <i data-feather="plus-circle"></i>Add New
                </a>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Home Cart</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitForm" enctype="multipart/form-data">
                            <div class="card">
                                <div class="card-body add-product pb-0">
                                    <div class="accordion-card-one accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <div class="accordion-header" id="headingOne">
                                                <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-controls="collapseOne">
                                                    <div class="addproduct-icon">
                                                        <h5><i data-feather="info" class="add-info"></i><span>Basic Information</span></h5>
                                                        <a href="javascript:void(0);"><i data-feather="chevron-down" class="chevron-down-add"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-sm-12 col-12">
                                                            <div class="mb-3 add-product required">
                                                                <label class="form-label">Name</label>
                                                                <input type="text" class="form-control" v-model="formData.name" placeholder="Enter text here">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-sm-12 col-12 required">
                                                            <label class="form-label">Link</label>
                                                            <input type="text" class="form-control" v-model="formData.link" placeholder="Enter link here">
                                                        </div>
                                                        <div class="col-lg-12 col-sm-12 col-12 required">
                                                            <label class="form-label">Status</label>
                                                            <select class="form-select" v-model="formData.status" width="100%">
                                                                <option value="1">Active</option>
                                                                <option value="0">Inactive</option>
                                                            </select>

                                                        </div>
                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                            <div class="mb-3 add-product">
                                                                <label class="form-label">Icon Image</label>
                                                                <div class="form-group" id="icon">
                                                                    <input type="file" id="image" name="image" class="form-control" ref="image">
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
                        <h5 class="modal-title" id="exampleModalLabel">Home cart</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="updateOrderstatus">
                        <form   @submit.prevent="updateForm" enctype="multipart/form-data">
                            <div class="card">
                                <div class="card-body add-product pb-0">
                                    <div class="accordion-card-one accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <div class="accordion-header" id="headingOne">
                                                <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-controls="collapseOne">
                                                    <div class="addproduct-icon">
                                                        <h5><i data-feather="info" class="add-info"></i><span>Basic Information</span></h5>
                                                        <a href="javascript:void(0);"><i data-feather="chevron-down" class="chevron-down-add"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-sm-12 col-12">
                                                            <div class="mb-3 add-product required">
                                                                <label class="form-label">Name</label>
                                                                <!-- Hidden input for ID -->
                                                                <input type="hidden" v-model="formData.id">
                                                                <!-- Text input for Name -->
                                                                <input type="text" class="form-control" v-model="formData.name" placeholder="Enter text here">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-sm-12 col-12 required">
                                                            <label class="form-label">Link</label>
                                                            <!-- Text input for Link -->
                                                            <input type="text" class="form-control" v-model="formData.link" placeholder="Enter link here">
                                                        </div>
                                                        <div class="col-lg-12 col-sm-12 col-12 required">
                                                            <label class="form-label">Status</label>
                                                            <!-- Select input for Status -->
                                                            <select class="form-select" v-model="formData.status" width="100%">
                                                                <option value="1">Active</option>
                                                                <option value="0">Inactive</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                            <div class="mb-3 add-product">
                                                                <label class="form-label">Icon Image</label>
                                                                <div class="form-group" id="icon">
                                                                    <!-- Display image thumbnail -->
                                                                    <img :src="getImageUrl(imageUrl)" alt="Icon Image" class="img-thumbnail mb-2" width="100">
                                                                    <!-- Input for uploading new image -->
                                                                    <input type="file" id="image" name="image" class="form-control" ref="image">
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

        <!-- Data Table -->
        {{-- <div class="card table-list-card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-input">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <input type="text" v-model="searchQuery" placeholder="Search...">
                    <table class="table AjaxDataTable" style="width:100%;">
                        <!-- Table Header -->
                        <thead>
                            <tr>
                                <!-- Checkboxes -->
                                <th class="no-sort" data-orderable="false">
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all" data-value="0">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th>Sr</th>
                                <th class="no-sort">Name</th>
                                <th class="no-sort">Image</th>
                                <th class="no-sort">Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <!-- Table Body -->
                        <tbody>
                            <tr v-for="(item, index) in paginatedData" :key="index">
                                <!-- Checkboxes -->
                                <td>
                                    <label class="checkboxs">
                                        <input type="checkbox" :data-value="index">
                                        <span class="checkmarks"></span>
                                    </label>
                                </td>
                                <!-- Serial Number -->
                                <td>@{{ index + 1 }}</td>
                                <!-- Name -->
                                <td>@{{ item.name }}</td>
                                <td>
                                    <img style="width: 200px; height: 100px; border-radius: 5px; object-fit: cover;" :src="getImageUrl(item.image)" alt="Image">
                                </td>


                                <!-- Status -->
                                <td>
                                    <span v-if="item.status === 1" class="badge badge-success">Active</span>
                                    <span v-else class="badge badge-danger">Inactive</span>
                                </td>

                                <!-- Action Buttons -->
                                <td>
                                    <div class="action-table-data">
                                        <div class="edit-delete-action">

                                            <a class="btn btn-info me-2 p-2">
                                                <i class="fa fa-edit text-white" data-bs-toggle="modal"
                                                    data-bs-target="#statusUpdateModal" @click="showData(item.id)"
                                                    data-bs-toggle="modal" data-bs-target="#statusUpdateModal"></i>
                                            </a>
                                            <a class="btn btn-danger delete-btn p-2" @click="confirmDelete(item.id)">
                                                <i class="fa fa-trash text-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                                <a class="page-link" href="#" aria-label="Previous" @click.prevent="prevPage">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item" v-for="page in totalPages" :key="page"
                                :class="{ 'active': currentPage === page }">
                                <a class="page-link" href="#"
                                    @click.prevent="setPage(page)">@{{ page }}</a>
                            </li>
                            <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
                                <a class="page-link" href="#" aria-label="Next" @click.prevent="nextPage">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div> --}}


        <div class="row">
            <div class="col-md-3" v-for="(item, index) in paginatedData" :key="index">
                <div class="card">
                    <div class="card-header">
                      <h3> (Banner @{{ index+1 }})</h3>
                    </div>
                    <div class="card-body">

                            <!-- Loop through your data and display each item -->

                                <div class="card mb-3">
                                    <img :src="getImageUrl(item.image)" class="card-img-top" alt="Image" style="width: 100%; height: 200px;">
                                    <div class="card-body">
                                        <h5 class="card-title">@{{ item.name }}</h5>
                                        <p class="card-text">
                                            <span v-if="item.status === 1" class="badge badge-success">Active</span>
                                            <span v-else class="badge badge-danger">Inactive</span>
                                        </p>
                                        <div class="action-buttons">
                                            <button class="btn btn-info me-2" @click="showData(item.id)" data-bs-toggle="modal" data-bs-target="#statusUpdateModal">
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
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-router@3.5.2/dist/vue-router.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


    <script>
       var domain_path = 'http://localhost/english_moja_new/';

new Vue({
    el: '#app',
    data: {
        data: [],
        currentPage: 1,
        itemsPerPage: 5,
        searchQuery: '',
        formData: {

            name: '',
            link: '',
            status: '',
            icon: null,
            image: null // Add image property here
        },
        imageUrl: ''
    },
    computed: {
        paginatedData() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredData.slice(start, end);
        },
        filteredData() {
            if (!this.searchQuery) {
                return this.data;
            } else {
                return this.data.filter(item => {
                    return item.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                });
            }
        },
        totalPages() {
            return Math.ceil(this.filteredData.length / this.itemsPerPage);
        }
    },
    mounted() {
        this.formData.status = '';
        this.loadData();
        $('.select2').select2();
    },
    methods: {
        loadData() {
            axios.get(`${domain_path}api/home-cart/`)
                .then(response => {
                    this.data = response.data.data;
                })
                .catch(error => {
                    console.error('An error occurred:', error);
                });
        },

        submitForm() {
    // Create FormData object to collect form data
    const formData = new FormData();

    // Append form fields to FormData object
    formData.append('name', this.formData.name);
    formData.append('link', this.formData.link);
    formData.append('status', this.formData.status);

    // Capture the file input element
    const imageInput = document.getElementById('image');

    // Check if a new image file is selected
    if (imageInput.files.length > 0) {
        formData.append('image', imageInput.files[0]);
    }

    // Make POST request to the API endpoint
    axios.post(`${domain_path}api/store`, formData)
        .then(response => {
            // Handle success response
            console.log('Response received:', response);
            if (response.data && response.data.message) {
                // Data saved successfully
                Swal.fire({
                    title: 'Success',
                    text: response.data.message,
                    icon: 'success',
                    position: 'top-end',
                    timer: 1500
                });

                // Optionally, reset form fields
                this.formData = {
                    name: '',
                    link: '',
                    status: '1',
                    image: null
                };

                // Reset file input field
                imageInput.value = '';

                // Hide the modal
                $('#statusModal').modal('hide');

                // Load data after hiding modal
                this.loadData();
            }
        })
        .catch(error => {
            // Handle error
            console.error('An error occurred:', error);
            if (error.response) {
                console.error('Response data:', error.response.data);
                if (error.response.status === 422) {
                    // Extract validation error messages
                    const errorMessages = Object.values(error.response.data.errors).flat().join('<br>');
                    Swal.fire({
                        title: 'Validation Error',
                        html: errorMessages, // Use html to display line breaks
                        icon: 'error',
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while submitting data.',
                        icon: 'error',
                    });
                }
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred while submitting data.',
                    icon: 'error',
                });
            }
        });
},




        getImageUrl(relativePath) {
        const domainPath = 'http://localhost/english_moja_new';
        return domainPath + '/' + relativePath;
    },

    showData(itemId) {

        // Make a GET request to fetch existing data for the given item ID
        axios.get(`${domain_path}api/home-cart-edit/${itemId}`)
            .then(response => {
                const itemData = response.data.data;

                this.formData.id = itemData.id;
                this.formData.name = itemData.name;
                this.formData.link = itemData.link;
                this.formData.status = itemData.status;
                // Assuming there's a property 'imageUrl' for displaying image thumbnail
                this.imageUrl = itemData.image;
            })
            .catch(error => {
                console.error('An error occurred while fetching item data:', error);
                // Handle error, e.g., show an alert
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Failed to fetch item data.',
                });
            });
    },
    updateForm() {
    // Create FormData object to collect form data
    const formData = new FormData();

    // Append form fields to FormData object
    formData.append('id', this.formData.id);
    formData.append('name', this.formData.name);
    formData.append('link', this.formData.link);
    formData.append('status', this.formData.status);

    // Access the file input using Vue's ref
    const imageInput = this.$refs.image;

    // Check if a new image file is selected
    if (imageInput.files && imageInput.files[0]) {
        formData.append('image', imageInput.files[0]);
    }

    // Make POST request to the API endpoint
    axios.post(`${domain_path}api/home-cart-update/${this.formData.id}`, formData)
        .then(response => {
            // Handle success response
            this.loadData();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Item has been updated successfully!',
            });
            $('#statusUpdateModal').modal('hide');
        })
        .catch(error => {
            // Handle error
            console.error('An error occurred:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Something went wrong while updating the item.',
            });
        });
},



        confirmDelete(id) {
            const vm = this;
            Swal.fire({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this item!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    vm.deleteItem(id);
                } else {
                    Swal.fire("Your item is safe!");
                }
            });
        },
        deleteItem(id) {
            axios.get(`${domain_path}api/home-cart-destroy/${id}`)
                .then(response => {
                    this.data = this.data.filter(item => item.id !== id);
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: "Your item has been deleted successfully!",
                    });
                })
                .catch(error => {
                    console.error('An error occurred:', error);
                    Swal.fire({
                        icon: "error",
                        title: "Oops!",
                        text: "Something went wrong while deleting the item.",
                    });
                });
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        setPage(page) {
            this.currentPage = page;
        },
        onFileChange(event) {
            this.formData.image = event.target.files[0];
        }
    }
});




        $(document).on("click", '.changeStatus', function(e) {
            e.preventDefault();
            var authorId = $(this).data('author-id');
            $.ajax({
                url: '{{ route('orderstatuses.status') }}',
                type: 'POST',
                data: {
                    id: authorId,
                },
                success: function(response) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        type: "success",
                        confirmButtonClass: "btn btn-success"
                    }).then(function() {
                        $('.AjaxDataTable').DataTable().ajax.reload();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Error!",
                        text: "An error occurred while updating the Author status.",
                        type: "error",
                        confirmButtonClass: "btn btn-danger"
                    });
                }
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.view-image-btn', function() {
                var imageUrl = $(this).data('cover-url');
                if (imageUrl) {
                    $('#modalImage').attr('src', imageUrl);
                    $('#imageViewModal').modal('show');
                } else {
                    console.error('Image URL not found.');
                }
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.edit_status', function(event) {
                event.preventDefault();
                var statusId = $(this).data('id');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{ route('orderstatuses.edit') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: statusId,
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#updateOrderstatus').html(response.statData);
                            $('#statusUpdateModal').modal('show');
                        } else {
                            console.error('Error: ', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: ', error);
                    }
                });
            });
        });

        $("#icon").spartanMultiImagePicker({
            fieldName: 'icon',
            maxCount: 1,
            rowHeight: '200px',
            groupClassName: 'col',
            maxFileSize: '',
            dropFileLabel: "Drop Here",
            onExtensionErr: function(index, file) {
                console.log(index, file, 'extension err');
                alert('Please only input png or jpg type file')
            },
            onSizeErr: function(index, file) {
                console.log(index, file, 'file size too big');
                alert('File size too big max:250KB');
            }
        });
    </script>
@endsection
