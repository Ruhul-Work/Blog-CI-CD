<script>
    var domainPath = '{{ env('APP_URL') }}';
    new Vue({
        el: '#app',
        data() {
            return {
                data: [],
                search: '',
                currentPage: 1,
                itemsPerPage: 10,
                formData: {
                    id: null,
                    name: '',
                    comment: '',
                    image: null
                },
                imageUrl: '',
                fullComment: ''
            };

        },
        computed: {
            filteredData() {
                return this.data.filter(review => review.name.toLowerCase().includes(this.search
                    .toLowerCase()));
            },
            paginatedData() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredData.slice(start, end);
            },
            totalPages() {
                return Math.ceil(this.filteredData.length / this.itemsPerPage);
            },
        },
        methods: {
            loadData() {
                axios.get(`${domainPath}api/all-review/`)
                    .then(response => {
                        this.data = response.data.data;
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
            calculateSerialNumber(index) {
                return (this.currentPage - 1) * this.itemsPerPage + index + 1;
            },
            showData(itemId) {
                axios.get(`${domainPath}api/single-review-edit/${itemId}`)
                    .then(response => {
                        const itemData = response.data.data;
                        this.formData.id = itemData.id;
                        this.formData.name = itemData.name;
                        this.formData.comment = itemData.comment;
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
                formData.append('comment', this.formData.comment);

                if (this.$refs.image.files[0]) {
                    formData.append('image', this.$refs.image.files[0]);
                }

                axios.post(`${domainPath}api/review-update/${this.formData.id}`, formData)
                    .then(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Review updated successfully.',
                        }).then(() => {
                            $('#statusUpdateModal').modal('hide');
                            this.resetForm();
                            this.loadData();

                        });
                    })
                    .catch(error => {
                        console.error('An error occurred while updating the review:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Failed to update the review.',
                        });
                    });
            },
            submitForm() {
                const formData = new FormData();
                const imageInput = document.getElementById('image');
                formData.append('name', this.formData.name);
                formData.append('comment', this.formData.comment);

                if (imageInput && imageInput.files.length > 0) {
                    formData.append('image', imageInput.files[0]);
                }

                axios.post(`${domainPath}api/store-review`, formData)
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
                axios.get(`${domainPath}api/review-destroy/${id}`)
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
            },


            isTruncated(comment) {
                return comment.split(' ').length > 30;
            },
            truncatedComment(comment) {
                return comment.split(' ').slice(0, 30).join(' ') + '...';
            },
            showFullComment(comment) {
                this.fullComment = comment;
                var commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
                commentModal.show();
            }
        },

        mounted() {
            this.loadData();
        },

    });
</script>
