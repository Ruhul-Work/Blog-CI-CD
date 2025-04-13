<script>
    var domainPath = '{{ env('APP_URL') }}';
    new Vue({
        el: '#app',
        data() {
            return {
                data: [],
                cityData: [],
                search: '',
                currentCityPage: 1,
                cityItemsPerPage: 10,
                formData: {
                    id: null,
                    name: '',
                    city_id: '',
                },
            };
        },
        computed: {
            filteredData() {
                return this.data.filter(city =>
                    city.name.toLowerCase().includes(this.search.toLowerCase())
                );
            },
            paginatedData() {
                const start = (this.currentCityPage - 1) * this.cityItemsPerPage;
                const end = start + this.cityItemsPerPage;
                return this.filteredData.slice(start, end);
            },
            totalPages() {
                return Math.ceil(this.filteredData.length / this.cityItemsPerPage);
            }
        },
        methods: {
            loadUpazilaData() {
                axios.get(`${domainPath}api/upazila-list/`)
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
            changeCityPage(page) {
                if (page > 0 && page <= this.totalPages) {
                    this.currentCityPage = page;
                }
            },
            calculateSerialNumber(index) {
                return (this.currentCityPage - 1) * this.cityItemsPerPage + index + 1;
            },
            submitForm() {
                const formData = new FormData();
                formData.append('name', this.formData.name);
                formData.append('city_id', this.formData.city_id);

                axios.post(`${domainPath}api/store-upazila`, formData)
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
                            this.loadCityData();
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
                axios.get(`${domainPath}api/single-upazila-edit/${itemId}`)
                    .then(response => {
                        const itemData = response.data.data;
                        this.formData.id = itemData.id;
                        this.formData.name = itemData.name;
                        this.formData.city_id = itemData.city_id;

                        // Use $nextTick to ensure Vue updates the DOM before setting select2 value
                        this.$nextTick(() => {
                            // Ensure select2 is initialized
                            this.initializeSelect2();

                            // Set the selected value in select2
                            $(this.$el).find('.selectSimple').val(itemData.city_id).trigger(
                                'change');
                        });
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

            initializeSelect2() {
                // Initialize select2 if it's not already initialized
                if ($.fn.select2) {
                    $(this.$el).find('.selectSimple').select2({
                        dropdownParent: $('#statusUpdateModal')
                    });
                }
            },

            updateCityId(value) {
                this.formData.city_id = value;
            },
            updateForm() {
                const formData = new FormData();
                formData.append('id', this.formData.id);
                formData.append('name', this.formData.name);
                formData.append('city_id', this.formData.city_id);

                axios.post(`${domainPath}api/upazila-update/${this.formData.id}`, formData)
                    .then(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Upazila updated successfully.',
                        }).then(() => {
                            $('#statusUpdateModal').modal('hide');
                            this.resetForm();
                            this.loadUpazilaData();
                        });
                    })
                    .catch(error => {
                        console.error('An error occurred while updating the upazila:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Failed to update the upazila.',
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
                    city_id: '',
                };
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
                axios.get(`${domainPath}api/upazila-destroy/${id}`)
                    .then(response => {
                        this.cityData = this.cityData.filter(item => item.id !== id);
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: "Your item has been deleted successfully!",
                        });
                        this.loadUpazilaData(); // Reload data after successful deletion
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

        },

        mounted() {
            this.loadUpazilaData();
            this.loadCityData();
            // Initialize select2
            $(this.$el).find('.selectSimple').select2();
            // Listen for changes
            $(this.$el).find('.selectSimple').on('change', (event) => {
                this.updateCityId(event.target.value);
            });
            this.initializeSelect2();
        }
    });
</script>
