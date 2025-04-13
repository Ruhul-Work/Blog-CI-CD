<script>
    var domainPath = '{{ env('APP_URL') }}';

    const routeUrls = {
        homeCategoryAdd: '{{ route('home-category.add', ['id' => ':id']) }}',
        homeAuthorAdd: '{{ route('home-category.author-add', ['id' => ':id']) }}',
        homePublisherAdd: '{{ route('home-category.publisher-add', ['id' => ':id']) }}',
        homeAddReviewAdd: '{{ route('home-category.review-add', ['id' => ':id']) }}',
        homeCategoryView: '{{ route('home-category.view', ['id' => ':id']) }}',
        homeAuthorView: '{{ route('home-category.author-view', ['id' => ':id']) }}',
        homePublisherView: '{{ route('home-category.publisher-view', ['id' => ':id']) }}',
        homereviewView: '{{ route('home-category.review-view', ['id' => ':id']) }}',
        homeReload: '{{ route('home-category.index') }}'
    };

    new Vue({
        el: '#app',
        data: {
            formData: {
                id: null,
                name: '',
            },
            selectedImage: null,
            imageUrl: '',
            data: [],
            category: 'category',
            author: 'author',
            publisher: 'publisher',
            review: 'review',
        },
        mounted() {
            this.loadData();
            this.resetForm();
        },
        methods: {
            loadData() {
                axios.get(`${domainPath}api/category-all`)
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

                axios.post(`${domainPath}api/home-category-store`, formData)
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
                            // window.location.href = routeUrls.homeReload;
                        }
                    })
                    .catch(error => {
                        this.handleError(error);
                    });
            },
            resetForm() {
                this.formData.id = null;
                this.formData.name = '';
            },
            handleError(error) {
                let message = 'An error occurred';
                if (error.response) {
                    message = error.response.data.message || message;
                } else if (error.request) {
                    message = 'No response received from the server';
                } else {
                    message = error.message;
                }

                Swal.fire({
                    title: 'Error',
                    text: message,
                    icon: 'error',
                    position: 'top-end',
                    timer: 1500
                });
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
                        Swal.fire("Cancelled", "Your item is safe :)", "info");
                    }
                });
            },
            deleteItem(id) {
                axios.get(`${domainPath}api/category-destroy/${id}`)
                    .then(response => {
                        this.data = this.data.filter(item => item.id !== id);
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: "Your item has been deleted successfully!",
                        });
                        window.location.href = routeUrls.homeReload;
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
            generateUrlAdd(id) {
                return routeUrls.homeCategoryAdd.replace(':id', id);
            },
            homeAuthorUrl(id) {
                return routeUrls.homeAuthorAdd.replace(':id', id);
            },
            homePublisherUrl(id) {
                return routeUrls.homePublisherAdd.replace(':id', id);
            },
            homeReviewUrl(id) {
                return routeUrls.homeAddReviewAdd.replace(':id', id);
            },
            generateUrlView(id) {
                return routeUrls.homeCategoryView.replace(':id', id);
            },
            AuthorView(id) {
                return routeUrls.homeAuthorView.replace(':id', id);
            },
            generateUrlPublisherView(id) {
                return routeUrls.homePublisherView.replace(':id', id);
            },
            generateUrlreviewView(id) {
                return routeUrls.homereviewView.replace(':id', id);
            },
        }
    });
</script>
