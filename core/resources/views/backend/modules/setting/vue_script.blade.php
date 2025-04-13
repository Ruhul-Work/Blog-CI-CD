<script>
    var domainPath = '{{ env('APP_URL') }}';
    new Vue({
        el: '#app',
        data() {
            return {
                data: [],
                cityData: [],
                search: '',
                currentPage: 1,
                itemsPerPage: 10,
                currentCityPage: 1,
                cityItemsPerPage: 10,
                formData: {
                    id: null,
                    name: '',
                    comment: '',
                    image: null
                },
                imageUrl: '',
            };
        },
        computed: {
            filteredData() {
                return this.data.filter(review =>
                    review.name.toLowerCase().includes(this.search.toLowerCase())
                );
            },
            paginatedData() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredData.slice(start, end);
            },
            totalPages() {
                return Math.ceil(this.filteredData.length / this.itemsPerPage);
            },
            filteredCityData() {
                return this.cityData.filter(city =>
                    city.name.toLowerCase().includes(this.search.toLowerCase())
                );
            },
            paginatedCityData() {
                const start = (this.currentCityPage - 1) * this.cityItemsPerPage;
                const end = start + this.cityItemsPerPage;
                return this.filteredCityData.slice(start, end);
            },
            totalCityPages() {
                return Math.ceil(this.filteredCityData.length / this.itemsPerPage);
            }
        },
        methods: {
            loadData() {
                axios.get(`${domainPath}api/country-list/`)
                    .then(response => {
                        this.data = response.data.data;
                    })
                    .catch(error => {
                        console.error('An error occurred:', error);
                    });
            },
            loadCityData() {
                axios.get(`${domainPath}api/city-list/`)
                    .then(response => {
                        this.cityData = response.data.data;
                    })
                    .catch(error => {
                        console.error('An error occurred:', error);
                    });
            },
            changePage(page) {
                if (page > 0 && page <= this.totalPages) {
                    this.currentPage = page;
                }
            },
            changeCityPage(page) {
                if (page > 0 && page <= this.totalCityPages) {
                    this.currentPage = page;
                }
            },
            calculateSerialNumber(index) {
                return (this.currentPage - 1) * this.itemsPerPage + index + 1;
            },
            submitForm() {
                const formData = new FormData();
                formData.append('name', this.formData.name);

                axios.post(`${domainPath}api/store-country`, formData)
                    .then(response => {
                        if (response.data && response.data.message) {
                            Swal.fire({
                                title: 'Success',
                                text: response.data.message,
                                icon: 'success',
                                position: 'top-end',
                                timer: 1500
                            });
                            this.resetForm();
                            $('#statusModal').modal('hide');
                            this.loadData();
                        }
                    })
                    .catch(error => {
                        this.handleError(error);
                    });
            },
            handleError(error) {
                console.error('An error occurred:', error);
                if (error.response) {
                    if (error.response.status === 422) {
                        const errorMessages = Object.values(error.response.data.errors).flat().join('<br>');
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
            },
            showData(itemId) {
                axios.get(`${domainPath}api/single-country-edit/${itemId}`)
                    .then(response => {
                        const itemData = response.data.data;
                        this.formData.id = itemData.id;
                        this.formData.name = itemData.name;
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

                axios.post(`${domainPath}api/country-update/${this.formData.id}`, formData)
                    .then(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Country updated successfully.',
                        }).then(() => {
                            $('#statusUpdateModal').modal('hide');
                            this.resetForm();
                            this.loadData();
                        });
                    })
                    .catch(error => {
                        console.error('An error occurred while updating the country:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Failed to update the country.',
                        });
                    });
            },
            onFileChange(event) {
                this.formData.image = event.target.files[0];
            },
            clearSearch() {
                this.search = '';
            },
            resetForm() {
                this.formData = {
                    id: null,
                    name: '',
                    comment: '',
                    image: null
                };
                const imageInput = document.getElementById('image');
                if (imageInput) {
                    imageInput.value = '';
                }
                this.imageUrl = '';
            },
            confirmDelete(id) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this item!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.deleteItem(id);
                        
                    } else {
                        Swal.fire("Your item is safe!");
                    }
                });
            },
            deleteItem(id) {
                axios.get(`${domainPath}api/country-destroy/${id}`)
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
            getImageUrl(relativePath) {
                return `${domainPath}${relativePath}`;
            },
            previewImage(event) {
                this.selectedImage = event.target.files[0];
                this.imageUrl = URL.createObjectURL(this.selectedImage);
            }
        },
        mounted() {
            this.loadData();
            this.loadCityData();
        }
    });
</script>
