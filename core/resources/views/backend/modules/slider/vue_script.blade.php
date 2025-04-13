// <script>
//   var domainPath = '{{ env('APP_URL') }}';
//     new Vue({
//         el: '#app',
//         data: {
//             formData: {
//                 id: null,
//                 name: '',
//                 url: '',
//                 status: '',
//                 image: null
//             },
//             selectedImage: null,
//             imageUrl: '',
//             data: []
//         },
//         mounted() {
//             this.loadData();
//         },
//         methods: {
//             loadData() {
//                 axios.get(`${domainPath}api/view-sub-slider/`)
//                     .then(response => {
//                         this.data = response.data.data;
//                     })
//                     .catch(error => {
//                         console.error('An error occurred:', error);
//                     });
//             },

//             submitForm() {
//                 const formData = new FormData();
//                 const imageInput = document.getElementById('image');
               
//                 if (imageInput.files.length > 0) {
//                     formData.append('image', imageInput.files[0]);
//                 }
                
//                  formData.append('url', this.formData.url);
                
//                 axios.post(`${domainPath}api/sub-slider`, formData)
//                     .then(response => {
//                         if (response.data && response.data.message) {
//                             Swal.fire({
//                                 title: 'Success',
//                                 text: response.data.message,
//                                 icon: 'success',
//                                 position: 'top-end',
//                                 timer: 1500
//                             });
//                             this.resetForm();
//                             $('#statusModal').modal('hide');
//                             this.loadData();
//                         }
//                     })
//                     .catch(error => {
//                         this.handleError(error);
//                     });
//             },

//             getImageUrl(relativePath) {
//                 return domainPath + relativePath;
//             },

//             showData(itemId) {
//                 axios.get(`${domainPath}api/sub-slider-edit/${itemId}`)
//                     .then(response => {
//                         const itemData = response.data.data;
//                         this.formData.id = itemData.id;
//                         this.formData.url = itemData.url;
//                         this.imageUrl = itemData.image;
//                     })
//                     .catch(error => {
//                         console.error('An error occurred while fetching item data:', error);
//                         Swal.fire({
//                             icon: 'error',
//                             title: 'Oops!',
//                             text: 'Failed to fetch item data.',
//                         });
//                     });
//             },

//             updateForm() {
//                 const formData = new FormData();
//                 formData.append('id', this.formData.id);
//                 const imageInput = this.$refs.image;
//                 if (imageInput.files && imageInput.files[0]) {
//                     formData.append('image', imageInput.files[0]);
//                 }

//                 axios.post(`${domainPath}api/sub-slider-update/${this.formData.id}`, formData)
//                     .then(response => {
//                         this.loadData();
//                         Swal.fire({
//                             icon: 'success',
//                             title: 'Success',
//                             text: 'Item has been updated successfully!',
//                         });
//                         $('#statusUpdateModal').modal('hide');
//                         this.resetForm();
//                     })
//                     .catch(error => {
//                         this.handleError(error);
//                     });
//             },

//             confirmDelete(id) {
//                 Swal.fire({
//                     title: "Are you sure?",
//                     text: "Once deleted, you will not be able to recover this item!",
//                     icon: "warning",
//                     showCancelButton: true,
//                     confirmButtonColor: '#3085d6',
//                     cancelButtonColor: '#d33',
//                     confirmButtonText: 'Yes, delete it!',
//                     cancelButtonText: 'No, cancel!'
//                 }).then((result) => {
//                     if (result.isConfirmed) {
//                         this.deleteItem(id);
//                     } else {
//                         Swal.fire("Your item is safe!");
//                     }
//                 });
//             },

//             deleteItem(id) {
//                 axios.get(`${domainPath}api/sub-slider-destroy/${id}`)
//                     .then(response => {
//                         this.data = this.data.filter(item => item.id !== id);
//                         Swal.fire({
//                             icon: "success",
//                             title: "Success",
//                             text: "Your item has been deleted successfully!",
//                         });
//                     })
//                     .catch(error => {
//                         console.error('An error occurred:', error);
//                         Swal.fire({
//                             icon: "error",
//                             title: "Oops!",
//                             text: "Something went wrong while deleting the item.",
//                         });
//                     });
//             },

//             onFileChange(event) {
//                 this.formData.image = event.target.files[0];
//             },

//             resetForm() {
//                 this.formData = {
//                     id: null,
//                     image: null
//                 };
//                 const imageInput = document.getElementById('image');
//                 if (imageInput) {
//                     imageInput.value = '';
//                 }
//                 this.imageUrl = '';
//             },

//             previewImage(event) {
//                 this.selectedImage = event.target.files[0];
//                 this.imageUrl = URL.createObjectURL(this.selectedImage);
//             },

//             handleError(error) {
//                 console.error('An error occurred:', error);
//                 if (error.response) {
//                     if (error.response.status === 422) {
//                         const errorMessages = Object.values(error.response.data.errors).flat().join('<br>');
//                         Swal.fire({
//                             title: 'Validation Error',
//                             html: errorMessages,
//                             icon: 'error',
//                         });
//                     } else {
//                         Swal.fire({
//                             title: 'Error',
//                             text: 'An error occurred while submitting data.',
//                             icon: 'error',
//                         });
//                     }
//                 } else {
//                     Swal.fire({
//                         title: 'Error',
//                         text: 'An error occurred while submitting data.',
//                         icon: 'error',
//                     });
//                 }
//             }
//         }
//     });
// </script>

<script>
    var domainPath = '{{ env('APP_URL') }}';
    
    new Vue({
      el: '#app',
      data: {
        formData: {
          id: null,
          name: '',
          url: '',
          status: '',
          image: null
        },
        selectedImage: null,
        imageUrl: '',
        data: []
      },
      mounted() {
        this.loadData();
      },
      methods: {
        loadData() {
          axios.get(`${domainPath}api/view-sub-slider/`)
            .then(response => {
              this.data = response.data.data;
            })
            .catch(error => {
              console.error('An error occurred:', error);
            });
        },
  
        submitForm() {
          const formData = new FormData();
          formData.append('url', this.formData.url);
  
          const imageInput = document.getElementById('image');
          if (imageInput.files.length > 0) {
            formData.append('image', imageInput.files[0]);
          }
  
          axios.post(`${domainPath}api/sub-slider`, formData)
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
  
        updateForm() {
          const formData = new FormData();
          formData.append('id', this.formData.id);
          formData.append('url', this.formData.url);
  
          const imageInput = this.$refs.image;
          if (imageInput.files && imageInput.files[0]) {
            formData.append('image', imageInput.files[0]);
          }
  
          axios.post(`${domainPath}api/sub-slider-update/${this.formData.id}`, formData)
            .then(response => {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Item has been updated successfully!',
              });
              $('#statusUpdateModal').modal('hide');
              this.resetForm();
              this.loadData();
            })
            .catch(error => {
              this.handleError(error);
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
            cancelButtonText: 'No, cancel!'
          }).then((result) => {
            if (result.isConfirmed) {
              this.deleteItem(id);
            } else {
              Swal.fire("Your item is safe!");
            }
          });
        },
  
        deleteItem(id) {
          axios.get(`${domainPath}api/sub-slider-destroy/${id}`)
            .then(response => {
              this.data = this.data.filter(item => item.id !== id);
              Swal.fire({
                icon: "success",
                title: "Success",
                text: "Your item has been deleted successfully!",
              });
            })
            .catch(error => {
              Swal.fire({
                icon: "error",
                title: "Oops!",
                text: "Something went wrong while deleting the item.",
              });
            });
        },
  
        showData(itemId) {
          axios.get(`${domainPath}api/sub-slider-edit/${itemId}`)
            .then(response => {
              const itemData = response.data.data;
              this.formData.id = itemData.id;
              this.formData.url = itemData.url;
              this.imageUrl = itemData.image;
            })
            .catch(error => {
              Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Failed to fetch item data.',
              });
            });
        },
  
        resetForm() {
          this.formData = { id: null, image: null };
          document.getElementById('image').value = '';
          this.imageUrl = '';
        },
  
        previewImage(event) {
          this.selectedImage = event.target.files[0];
          this.imageUrl = URL.createObjectURL(this.selectedImage);
        },
        getImageUrl(relativePath) {
                return domainPath + relativePath;
            },
  
        handleError(error) {
          if (error.response && error.response.status === 422) {
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
        }
      }
    });
  </script>
  
