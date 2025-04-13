<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        var table;
        if ($('.AjaxDataTable').length > 0) {
            table = $('.AjaxDataTable').DataTable({
                "bFilter": true,

                "sDom": 'fBtlpi',
                "ordering": true,
                "responsive": true,
                "stateSave": true,
                'order': [
                    [0, 'desc']
                ],
                // "scrollX": true,
                // "scrollY": "60vh",
                // "scrollCollapse": true,
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': AJAX_URL
                },

                'aLengthMenu': [
                    [10, 50, 100, 200, 500, 1000000000000],
                    [10, 50, 100, 200, 500, "ALL"]
                ],
                "language": {
                    search: '',
                    sLengthMenu: '_MENU_',
                    searchPlaceholder: "Search",
                    info: "_START_ - _END_ of _TOTAL_ items",
                    paginate: {
                        next: ' <i class=" fa fa-angle-right"></i>',
                        previous: '<i class="fa fa-angle-left"></i> '
                    },
                },
                'buttons': [{
                        extend: 'copy',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'

                ],
                initComplete: (settings, json) => {
                    $('.dataTables_filter').appendTo('#tableSearch');
                    $('.dataTables_filter').appendTo('.search-input');

                    $(document).on('click', '.export-excel', function() {
                        $('.dt-buttons .buttons-excel').click();
                    });

                    $(document).on('click', '.export-print', function() {
                        $('.dt-buttons .buttons-print').click();
                    });

                    $(document).on('click', '.export-copy', function() {
                        $('.dt-buttons .buttons-copy').click();
                        Swal.fire({
                            title: "Success",
                            text: "Successfully copied",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });

                    $(document).on('click', '.export-refresh', function() {
                        $('.AjaxDataTable').DataTable().ajax.reload();
                        //$('.DataTable').DataTable().draw();
                        Swal.fire({
                            title: "Success",
                            text: "Successfully Reloaded",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });


                    // Custom function to toggle column visibility
                    function toggleColumn(index) {
                        table.column(index).visible(!table.column(index).visible());
                    }

                    // Event listener for column hide/unhide button
                    $(document).on('click', '.export-hide-column', function() {
                        var columnCheckboxes = '';
                        table.columns().every(function() {
                            var column = this;
                            var columnTitle = $(column.header()).text().trim();

                            var columnIndex = column.index();
                            columnCheckboxes +=
                                `<div style="text-align:left;"><input type="checkbox" id="chk_${columnIndex}" class="column-checkbox" value="${columnIndex}" ${column.visible() ? 'checked' : ''}><label for="chk_${columnIndex}">${columnTitle}</label></div>`;
                        });

                        Swal.fire({
                            title: 'Hide/Unhide Columns',
                            html: columnCheckboxes,
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Apply',
                            cancelButtonText: 'Cancel',
                            preConfirm: () => {
                                $('.column-checkbox').each(function() {
                                    var columnIndex = $(this).val();
                                    var isChecked = $(this).prop(
                                        'checked');
                                    if (isChecked !== table.column(
                                            columnIndex).visible()) {
                                        toggleColumn(columnIndex);
                                    }
                                });
                            }
                        });
                    });



                },
            });

        }

        if ($('.DataTable').length > 0) {

            table = $('.DataTable').DataTable({
                "bFilter": true,
                "sDom": 'fBtlpi',
                "ordering": true,
                "responsive": true,
                "scrollX": true,
                "scrollY": "60vh",
                "scrollCollapse": true,

                'aLengthMenu': [
                    [10, 50, 100, 200, 500, -1],
                    [10, 50, 100, 200, 500, "ALL"]
                ],
                "language": {
                    search: '',
                    sLengthMenu: '_MENU_',
                    searchPlaceholder: "Search",
                    info: "_START_ - _END_ of _TOTAL_ items",
                    paginate: {
                        next: ' <i class=" fa fa-angle-right"></i>',
                        previous: '<i class="fa fa-angle-left"></i> '
                    },
                },
                'buttons': [{
                        extend: 'copy',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'

                ],
                initComplete: (settings, json) => {
                    $('.dataTables_filter').appendTo('#tableSearch');
                    $('.dataTables_filter').appendTo('.search-input');

                    $(document).on('click', '.export-excel', function() {
                        $('.dt-buttons .buttons-excel').click();
                    });

                    $(document).on('click', '.export-print', function() {
                        $('.dt-buttons .buttons-print').click();
                    });

                    $(document).on('click', '.export-copy', function() {
                        $('.dt-buttons .buttons-copy').click();
                        Swal.fire({
                            title: "Success",
                            text: "Successfully copied",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });

                    $(document).on('click', '.export-refresh', function() {
                        //$('.DataTable').DataTable().ajax.reload();
                        $('.DataTable').DataTable().draw();
                        Swal.fire({
                            title: "Success",
                            text: "Successfully Reloaded",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });


                    // Custom function to toggle column visibility
                    function toggleColumn(index) {
                        table.column(index).visible(!table.column(index).visible());
                    }

                    // Event listener for column hide/unhide button
                    $(document).on('click', '.export-hide-column', function() {
                        var columnCheckboxes = '';
                        table.columns().every(function() {
                            var column = this;
                            var columnTitle = $(column.header()).text().trim();

                            var columnIndex = column.index();
                            columnCheckboxes +=
                                `<div style="text-align:left;"><input type="checkbox" id="chk_${columnIndex}" class="column-checkbox" value="${columnIndex}" ${column.visible() ? 'checked' : ''}><label for="chk_${columnIndex}">${columnTitle}</label></div>`;
                        });

                        Swal.fire({
                            title: 'Hide/Unhide Columns',
                            html: columnCheckboxes,
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Apply',
                            cancelButtonText: 'Cancel',
                            preConfirm: () => {
                                $('.column-checkbox').each(function() {
                                    var columnIndex = $(this).val();
                                    var isChecked = $(this).prop(
                                        'checked');
                                    if (isChecked !== table.column(
                                            columnIndex).visible()) {
                                        toggleColumn(columnIndex);
                                    }
                                });
                            }
                        });
                    });



                },
            });
        }

        //delete button group
        $(document).on("click", '.delete-btn-group', function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                confirmButtonClass: "btn btn-primary",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: !1
            }).then(function(t) {
                if (t.isConfirmed) {
                    var url = get_checked();

                    var link = href + "?token=" + url;
                    $.ajax({
                        url: link,
                        type: 'GET',
                        success: function(response) {
                            // Show success message or handle response as needed
                            Swal.fire(
                                'Deleted!',
                                'Your item has been deleted.',
                                'success'
                            );
                            // Check if .datatable is initialized and reload it
                            if ($.fn.DataTable.isDataTable('.AjaxDataTable')) {
                                $('.AjaxDataTable').DataTable().ajax.reload(null,
                                    false);
                            }
                        },
                        error: function(xhr, status, error) {
                            // Show error message or handle error as needed
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the item.',
                                'error'
                            );
                        }
                    });
                }
            })
        })

        //get checked
        function get_checked() {
            var selected = [];
            $('input[type=checkbox]').each(function() {
                if ($(this).is(":checked")) {
                    var num = $(this).attr('data-value');
                    //console.log(num);
                    if (num != '0')
                        selected.push($(this).attr('data-value'));
                }
            });

            var url = (btoa(JSON.stringify(selected)));
            return url;

        }


        //delete-action
        $(document).on("click", '.delete-btn', function(e) {

            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
                confirmButtonClass: "btn btn-primary",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: !1
            }).then(function(t) {
                if (t.isConfirmed) {
                    //window.location.href = href;

                    $.ajax({
                        url: href,
                        type: 'GET',
                        success: function(response) {
                            // Show success message or handle response as needed

                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.success,
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }


                            // Check if .datatable is initialized and reload it
                            if ($.fn.DataTable.isDataTable('.AjaxDataTable')) {
                                $('.AjaxDataTable').DataTable().ajax.reload(null,
                                    false);
                            }
                        },
                        error: function(xhr, status, error) {
                            // Show error message or handle error as needed
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the item.',
                                'error'
                            );
                        }
                    });
                }
            })
        });


        // $(document).on("click", '.delete-btn', function(e) {

        //     e.preventDefault();
        //     var href = $(this).attr('href');
        //     Swal.fire({
        //         title: "Are you sure?",
        //         text: "You won't be able to revert this!",
        //         type: "warning",
        //         showCancelButton: !0,
        //         confirmButtonColor: "#3085d6",
        //         cancelButtonColor: "#d33",
        //         confirmButtonText: "Yes",
        //         confirmButtonClass: "btn btn-primary",
        //         cancelButtonClass: "btn btn-danger ml-1",
        //         buttonsStyling: !1
        //     }).then(function(t) {
        //         if (t.isConfirmed) {
        //             //window.location.href = href;

        //             $.ajax({
        //                 url: href,
        //                 type: 'GET',
        //                 success: function(response) {
        //                     // Show success message or handle response as needed
        //                     Swal.fire(
        //                         'Deleted!',
        //                         'Your item has been deleted.',
        //                         'success'
        //                     );
        //                     // Check if .datatable is initialized and reload it
        //                     if ($.fn.DataTable.isDataTable('.AjaxDataTable')) {
        //                         $('.AjaxDataTable').DataTable().ajax.reload();
        //                     }
        //                 },
        //                 error: function(xhr, status, error) {
        //                     // Show error message or handle error as needed
        //                     Swal.fire(
        //                         'Error!',
        //                         'An error occurred while deleting the item.',
        //                         'error'
        //                     );
        //                 }
        //             });
        //         }
        //     })
        // });

        //focus select2
        $(document).on('select2:open', (e) => {
            const selectId = e.target.id

            $(".select2-search__field[aria-controls='select2-" + selectId + "-results']").each(function(
                key,
                value,
            ) {
                value.focus();
            })
        })

        //hide empty menu
        // $('.submenu-open > ul > .submenu > ul.bintel-menu').each(function() {
        //     if ($(this).children().length === 0) {
        //         $(this).closest('.submenu-open').hide();
        //     }
        // });

        $('.submenu > .bintel-menu').each(function() {
            if ($(this).children().length === 0) {
                $(this).closest('.submenu').hide();
            }
        });


        // //hide empty menu
        // $('.submenu-open > ul').each(function() {
        //     if ($(this).children().length === 0) {
        //         $(this).closest('.submenu-open').hide();
        //     }
        // });

        $('.submenu-open > ul').each(function() {
            if ($(this).find('li:visible').length === 0) {
                $(this).closest('.submenu-open').hide();
            }
        });



        $(document).on('click', '.AjaxModal', function(e) {
            e.preventDefault(); // Prevent default link behavior

            var modalTarget = '#AjaxModal'; // Get the modal target
            var ajaxUrl = $(this).data('ajax-modal'); // Get the URL for AJAX call
            var modalSize = $(this).data('size'); // Get the modal size
            var isSelect2Enabled = $(this).data('select2'); // Check if Select2 is enabled
            var select2DropdownParent = modalTarget; // Get the dropdown parent for Select2

            // Make AJAX call
            $.ajax({
                url: ajaxUrl,
                type: 'GET',
                success: function(response) {

                    // Set modal size
                    $(modalTarget + ' .modal-dialog').addClass('modal-' +
                        modalSize);

                    // Set AJAX response as modal content
                    $(modalTarget + ' .modal-content').html(response);

                    // Show modal
                    $(modalTarget).modal('show');

                    // Initialize Select2 if enabled
                    if (isSelect2Enabled) {
                        $(modalTarget + ' .selectSimple').select2({
                            dropdownParent: $(select2DropdownParent),
                            minimumResultsForSearch: -1,
                            width: '100%'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(error);
                    message("403 Forbiden", 'warning')
                }
            });
        });



        //call scroll to


        function scrollToActive() {
            // Find the active li element
            const activeLi = document.querySelector('li.active');

            // Check if the active li element exists
            if (activeLi) {
                // Scroll to the active li element using smooth behavior
                activeLi.scrollIntoView({
                    behavior: 'smooth'
                });
            }

            console.log("scroll to called");
        }
        // setTimeout(function() {
        //   scrollToActive();
        // }, 2000);








        //search

        // Get references to the input, links, and the results list
        const searchInput = document.getElementById('qs');
        const links = document.querySelectorAll('.sidebar-menu a');
        const resultsList = document.getElementById('search-results');

        // Function to handle the search and display the result
        function handleSearch() {
            // Get the search input value
            const searchValue = searchInput.value.toLowerCase();

            // Clear any existing results
            resultsList.innerHTML = '';

            // Loop through all links to find matches
            links.forEach(link => {
                const hrefValue = link.getAttribute('href');

                // Check if the link's href is not '#' and the text includes the search value
                if (hrefValue !== '#' && hrefValue !== 'javascript:void(0);' && link.textContent
                    .toLowerCase().includes(searchValue)) {
                    // Create a new li element
                    const li = document.createElement('li');
                    li.className = 'search-info';

                    // Create a new a element manually
                    const newLink = document.createElement('a');
                    newLink.href = hrefValue;
                    newLink.textContent = link.textContent;

                    // Append the new link to the li
                    li.appendChild(newLink);

                    // Append the new li element to the results list
                    resultsList.appendChild(li);
                }
            });
        }

        // Add an event listener to the input to call handleSearch on input
        searchInput.addEventListener('input', handleSearch);

        //end search
    });

    function message(text, type) {

        if (type === "success") {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: text,
                showConfirmButton: false,
                timer: 2500
            });
        } else if (type === "warning") {

            Swal.fire({
                position: "top-end",
                icon: "warning",
                title: text,
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: message,
                showConfirmButton: false,
                timer: 2500
            });
        }
    }

    if ($('.editor').length > 0) {

        /*ClassicEditor.create(document.querySelector('.editor'), {
                ckfinder: {
                    uploadUrl: '{{ route('ck.upload', ['_token' => csrf_token()]) }}',
                    //filebrowserUploadUrl: "{{ route('ck.upload', ['_token' => csrf_token()]) }}",
                    filebrowserUploadMethod: 'form'
                },
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'link', 'imageUpload', 'mediaEmbed', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'blockQuote', 'insertTable', '|','highlight', '|', // Add the highlight button
                    'undo', 'redo', '|',
                ],

                mediaEmbed: {
                    previewsInData: true
                },

                //filebrowserUploadUrl: '{{ route('ck.upload') }}'
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(err => {
                console.error(err.stack);
            });*/
            
            //cknew
            
            
            const {
	ClassicEditor,
	Alignment,
	Autoformat,
	AutoImage,
	AutoLink,
	Autosave,
	BlockQuote,
	Bold,
	Bookmark,
	Code,
	CodeBlock,
	Essentials,
	FindAndReplace,
	FontBackgroundColor,
	FontColor,
	FontFamily,
	FontSize,
	GeneralHtmlSupport,
	Heading,
	Highlight,
	HorizontalLine,
	HtmlEmbed,
	ImageBlock,
	ImageCaption,
	ImageInline,
	ImageInsert,
	ImageInsertViaUrl,
	ImageResize,
	ImageStyle,
	ImageTextAlternative,
	ImageToolbar,
	ImageUpload,
	Indent,
	IndentBlock,
	Italic,
	Link,
	LinkImage,
	List,
	ListProperties,
	Markdown,
	MediaEmbed,
	Mention,
	PageBreak,
	Paragraph,
	PasteFromMarkdownExperimental,
	PasteFromOffice,
	RemoveFormat,
	SimpleUploadAdapter,
	SpecialCharacters,
	SpecialCharactersArrows,
	SpecialCharactersCurrency,
	SpecialCharactersEssentials,
	SpecialCharactersLatin,
	SpecialCharactersMathematical,
	SpecialCharactersText,
	Strikethrough,
	Style,
	Subscript,
	Superscript,
	Table,
	TableCaption,
	TableCellProperties,
	TableColumnResize,
	TableProperties,
	TableToolbar,
	TextTransformation,
	TodoList,
	Underline
} = window.CKEDITOR;

const LICENSE_KEY =
	'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3Njk0NzE5OTksImp0aSI6IjNiZmU3NmRlLWM0MjEtNDI0ZC1iZjA3LWNhYzU4NTRmYTVmOCIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiXSwiZmVhdHVyZXMiOlsiRFJVUCJdLCJ2YyI6ImE0ZWEzNzE2In0.b2JDGshg594h_cCHbFA6andIsicOXG1NDVtNXzbph2yL3CNNEATGX5oCEL-AhhrphnmK-wB-m9uO0UncXJaoJQ';

const editorConfig = {
	toolbar: {
		items: [
			'findAndReplace',
			'|',
			'heading',
			'style',
			'|',
			'fontSize',
			'fontFamily',
			'fontColor',
			'fontBackgroundColor',
			'|',
			'bold',
			'italic',
			'underline',
			'strikethrough',
			'subscript',
			'superscript',
			'code',
			'removeFormat',
			'|',
			'specialCharacters',
			'horizontalLine',
			'pageBreak',
			'link',
			'bookmark',
			'insertImage',
			'insertImageViaUrl',
			'mediaEmbed',
			'insertTable',
			'highlight',
			'blockQuote',
			'codeBlock',
			'htmlEmbed',
			'|',
			'alignment',
			'|',
			'bulletedList',
			'numberedList',
			'todoList',
			'outdent',
			'indent',
			'imageTextAlternative',
                            'imageStyle:inline',
                            'imageStyle:wrapText',
                            'imageStyle:breakText',
                            'imageStyle:side',
		],
		styles: [
                            'inline', 'wrapText', 'breakText', 'side'
                        ],
		shouldNotGroupWhenFull: false
	},
	plugins: [
		Alignment,
		Autoformat,
		AutoImage,
		AutoLink,
		Autosave,
		BlockQuote,
		Bold,
		Bookmark,
		Code,
		CodeBlock,
		Essentials,
		FindAndReplace,
		FontBackgroundColor,
		FontColor,
		FontFamily,
		FontSize,
		GeneralHtmlSupport,
		Heading,
		Highlight,
		HorizontalLine,
		HtmlEmbed,
		ImageBlock,
		ImageCaption,
		ImageInline,
		ImageInsert,
		ImageInsertViaUrl,
		ImageResize,
		ImageStyle,
		ImageTextAlternative,
		ImageToolbar,
		ImageUpload,
		Indent,
		IndentBlock,
		Italic,
		Link,
		LinkImage,
		List,
		ListProperties,
		//Markdown,
		MediaEmbed,
		Mention,
		PageBreak,
		Paragraph,
		//PasteFromMarkdownExperimental,
		PasteFromOffice,
		RemoveFormat,
		SimpleUploadAdapter,
		SpecialCharacters,
		SpecialCharactersArrows,
		SpecialCharactersCurrency,
		SpecialCharactersEssentials,
		SpecialCharactersLatin,
		SpecialCharactersMathematical,
		SpecialCharactersText,
		Strikethrough,
		Style,
		Subscript,
		Superscript,
		Table,
		TableCaption,
		TableCellProperties,
		TableColumnResize,
		TableProperties,
		TableToolbar,
		TextTransformation,
		TodoList,
		Underline
	],
	fontFamily: {
		supportAllValues: true
	},
	simpleUpload: {
		uploadUrl: '{{ route("ck.upload", ["_token" => csrf_token()]) }}',
		headers: {
			'X-CSRF-TOKEN': '{{ csrf_token() }}'
		}
	},
	fontSize: {
		options: [10, 12, 14, 'default', 18, 20, 22],
		supportAllValues: true
	},
	heading: {
		options: [
			{
				model: 'paragraph',
				title: 'Paragraph',
				class: 'ck-heading_paragraph'
			},
			{
				model: 'heading1',
				view: 'h1',
				title: 'Heading 1',
				class: 'ck-heading_heading1'
			},
			{
				model: 'heading2',
				view: 'h2',
				title: 'Heading 2',
				class: 'ck-heading_heading2'
			},
			{
				model: 'heading3',
				view: 'h3',
				title: 'Heading 3',
				class: 'ck-heading_heading3'
			},
			{
				model: 'heading4',
				view: 'h4',
				title: 'Heading 4',
				class: 'ck-heading_heading4'
			},
			{
				model: 'heading5',
				view: 'h5',
				title: 'Heading 5',
				class: 'ck-heading_heading5'
			},
			{
				model: 'heading6',
				view: 'h6',
				title: 'Heading 6',
				class: 'ck-heading_heading6'
			}
		]
	},
	htmlSupport: {
		allow: [
			{
				name: /^.*$/,
				styles: true,
				attributes: true,
				classes: true
			}
		]
	},
	image: {
		toolbar: [
			'toggleImageCaption',
			'imageTextAlternative',
			'|',
			'imageStyle:inline',
			'imageStyle:wrapText',
			'imageStyle:breakText',
			'|',
			'resizeImage'
		]
	},
	
	licenseKey: LICENSE_KEY,
	link: {
		addTargetToExternalLinks: true,
		defaultProtocol: 'https://',
		decorators: {
			toggleDownloadable: {
				mode: 'manual',
				label: 'Downloadable',
				attributes: {
					download: 'file'
				}
			}
		}
	},
	list: {
		properties: {
			styles: true,
			startIndex: true,
			reversed: true
		}
	},
	mention: {
		feeds: [
			{
				marker: '@',
				feed: [
					/* See: https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html */
				]
			}
		]
	},
	menuBar: {
		isVisible: true
	},
	placeholder: 'Type or paste your content here!',
	style: {
		definitions: [
			{
				name: 'Article category',
				element: 'h3',
				classes: ['category']
			},
			{
				name: 'Title',
				element: 'h2',
				classes: ['document-title']
			},
			{
				name: 'Subtitle',
				element: 'h3',
				classes: ['document-subtitle']
			},
			{
				name: 'Info box',
				element: 'p',
				classes: ['info-box']
			},
			{
				name: 'Side quote',
				element: 'blockquote',
				classes: ['side-quote']
			},
			{
				name: 'Marker',
				element: 'span',
				classes: ['marker']
			},
			{
				name: 'Spoiler',
				element: 'span',
				classes: ['spoiler']
			},
			{
				name: 'Code (dark)',
				element: 'pre',
				classes: ['fancy-code', 'fancy-code-dark']
			},
			{
				name: 'Code (bright)',
				element: 'pre',
				classes: ['fancy-code', 'fancy-code-bright']
			}
		]
	},
	table: {
		contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
	}
};

ClassicEditor.create(document.querySelector('.editor'), editorConfig);
            //cknew

    }

    if ($('.editorBasic').length > 0) {

        ClassicEditor.create(document.querySelector('.editorBasic'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link']
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(err => {
                console.error(err.stack);
            });

    }

    if ($('.editorBasic2').length > 0) {

        ClassicEditor.create(document.querySelector('.editorBasic2'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link']
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(err => {
                console.error(err.stack);
            });

    }
    if ($('.editorBasic3').length > 0) {

        ClassicEditor.create(document.querySelector('.editorBasic3'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link']
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(err => {
                console.error(err.stack);
            });

    }
    if ($('.dateRangePredifined').length > 0) {

        $(function() {
            $('.dateRangePredifined').daterangepicker({
                opens: 'right',
                startDate: '01/01/2024', // Set initial start date to null
                locale: {
                    format: 'YYYY-MM-DD'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                },
            }, function(start, end, label) {

            });
        });
    }

    $(document).ready(function() {
        if ($('.dateranges').length > 0) {
            $('.dateranges').daterangepicker({
                opens: 'right',
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' +
                    end.format('YYYY-MM-DD'));
            });
        }

    });

    if ($('.daterange').length > 0) {

        $(function() {
            $('.daterange').daterangepicker({
                opens: 'right',
                singleDatePicker: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },

            }, function(start, end, label) {

            });
        });
    }


    function updateStatus(id) {
        $.ajax({
            url: '{{ route('user.status') }}?id=' + id,
            type: 'GET',
            success: function(response) {
                // Show success message or handle response as needed
                Swal.fire(
                    'Updated',
                    'Status updated',
                    'success'
                );
                // Check if .datatable is initialized and reload it
                if ($.fn.DataTable.isDataTable('.AjaxDataTable')) {
                    $('.AjaxDataTable').DataTable().ajax.reload();
                }
            },
            error: function(xhr, status, error) {
                // Show error message or handle error as needed
                Swal.fire(
                    'Error!',
                    'An error occurred while deleting the item.',
                    'error'
                );
            }
        });
    }
</script>

{{-- ajax Form --}}
<script>
    $(document).on('submit', '#bintelForm', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Create FormData object
        var formData = new FormData(this);

        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            processData: false, // Prevent jQuery from automatically processing the FormData
            contentType: false, // Prevent jQuery from automatically setting the content type
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        onClose: function() {
                            $('#AjaxModal').modal('hide'); // Close the Ajax modal
                            // Check if .datatable is initialized and reload it
                            if ($.fn.DataTable.isDataTable('.AjaxDataTable')) {
                                $('.AjaxDataTable').DataTable().ajax.reload();
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseText
                });
            }
        });
    });
</script>
@if (session()->has('message'))
    <script>
        Swal.fire({
            position: 'top-end',
            icon: '{{ empty(session()->get('type')) ? 'success' : 'error' }}',
            title: '{{ session()->get('message') }}',
            showConfirmButton: false,
            timer: 2500
        })
    </script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ $errors->first() }}',
        })
    </script>
@endif

<script>
    function triggerFileInput() {
        document.getElementById('file-input').click();
    }

    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('img-holder').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>
