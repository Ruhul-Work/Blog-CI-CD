<script>
    var domain_path = '{{env("APP_URL")}}';
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
                image: null
            },
            selectedImage: null,
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
            this.initializeImagePicker();
        },
        methods: {
            loadData() {
                axios.get(`${domain_path}api/home-banner/`)
                    .then(response => {
                        this.data = response.data.data;
                    })
                    .catch(error => {
                        console.error('An error occurred:', error);
                    });
            },


            submitForm() {

                const formData = new FormData();


                formData.append('name', this.formData.name);
                formData.append('link', this.formData.link);
                formData.append('status', this.formData.status);


                const imageInput = document.getElementById('image');


                if (imageInput.files.length > 0) {
                    formData.append('image', imageInput.files[0]);
                }


                axios.post(`${domain_path}api/home-banner-store`, formData)
                    .then(response => {

                        console.log('Response received:', response);
                        if (response.data && response.data.message) {

                            Swal.fire({
                                title: 'Success',
                                text: response.data.message,
                                icon: 'success',
                                position: 'top-end',
                                timer: 1500
                            });


                            this.formData = {
                                name: '',
                                link: '',
                                status: '1',
                                image: null
                            };


                            imageInput.value = '';


                            $('#statusModal').modal('hide');

                            this.loadData();
                        }
                    })
                    .catch(error => {

                        console.error('An error occurred:', error);
                        if (error.response) {
                            console.error('Response data:', error.response.data);
                            if (error.response.status === 422) {

                                const errorMessages = Object.values(error.response.data.errors)
                                    .flat().join(
                                        '<br>');
                                Swal.fire({
                                    title: 'Validation Error',
                                    html: errorMessages,
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

                return domain_path + '/' + relativePath;
            },

            showData(itemId) {
                axios.get(`${domain_path}api/home-banner-edit/${itemId}`)
                    .then(response => {
                        const itemData = response.data.data;

                        this.formData.id = itemData.id;
                        this.formData.name = itemData.name;
                        this.formData.link = itemData.link;
                        this.formData.status = itemData.status;

                        this.imageUrl = itemData.image;
                    })
                    .catch(error => {
                        console.error('An error occurred while fetching item data:', error);

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Failed to fetch item data.',
                        });
                    });
            },
            updateForm() {
                const formData = new FormData();
                formData.append('id', this.formData.id);
             
                formData.append('name', this.formData.name);
                formData.append('link', this.formData.link);
                formData.append('status', this.formData.status);

                const imageInput = this.$refs.image;

                if (imageInput.files && imageInput.files[0]) {
                    formData.append('image', imageInput.files[0]);
                }

                axios.post(`${domain_path}api/home-banner-update/${this.formData.id}`, formData)
                    .then(response => {
                        this.loadData();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Item has been updated successfully!',
                        });
                        $('#statusUpdateModal').modal('hide');

                        // Clear the form data after successful update
                        this.formData.id = '';
                        this.formData.name = '';
                        this.formData.link = '';
                        this.formData.status = '';
                        if (imageInput) {
                            imageInput.value = '';
                        }
                    })
                    .catch(error => {
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
                axios.get(`${domain_path}api/home-banner-destroy/${id}`)
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
            previewImage(event) {
                this.selectedImage = event.target.files[0];
                this.imageUrl = URL.createObjectURL(this.selectedImage);
            },
            setPage(page) {
                this.currentPage = page;
            },
            onFileChange(event) {
                this.formData.image = event.target.files[0];
            }
        }

    });
</script>


