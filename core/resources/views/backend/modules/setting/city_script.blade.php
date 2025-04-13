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
                    country_id: '',
                    own_name: '',
                },
            };
        },
        computed: {
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
                return Math.ceil(this.filteredCityData.length / this.cityItemsPerPage);
            }
        },
        methods: {
            loadCountryData() {
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
            changeCityPage(page) {
                if (page > 0 && page <= this.totalCityPages) {
                    this.currentCityPage = page;
                }
            },
            calculateSerialNumber(index) {
                return (this.currentCityPage - 1) * this.cityItemsPerPage + index + 1;
            },
            submitForm() {
                const formData = new FormData();
                formData.append('name', this.formData.name);
                formData.append('country_id', this.formData.country_id);
                formData.append('own_name', this.formData.own_name);

                axios.post(`${domainPath}api/store-city`, formData)
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
                axios.get(`${domainPath}api/single-city-edit/${itemId}`)
                    .then(response => {
                        const itemData = response.data.data;
                        this.formData.id = itemData.id;
                        this.formData.name = itemData.name;
                        this.formData.country_id = itemData.country_id;
                        this.formData.own_name = itemData.own_name;
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
                formData.append('country_id', this.formData.country_id);
                formData.append('own_name', this.formData.own_name);

                axios.post(`${domainPath}api/city-update/${this.formData.id}`, formData)
                    .then(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Country updated successfully.',
                        }).then(() => {
                            $('#statusUpdateModal').modal('hide');
                            this.resetForm();
                            this.loadCityData();
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
                    country_id: '',
                    own_name: '',
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
                axios.get(`${domainPath}api/city-destroy/${id}`)
                    .then(response => {
                        this.cityData = this.cityData.filter(item => item.id !== id);
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
        },
        mounted() {
            this.loadCountryData();
            this.loadCityData();
        }
    });
</script>
