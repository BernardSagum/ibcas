$(document).ready(function(){
    // Initialize toasts for displaying messages
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));

    // Enable tooltips throughout the document
    $('[data-toggle="tooltip"]').tooltip();

    // Set default text for success toast
    $('#tstsuccess strong').text('Success');
    $('#tstsuccess label').text('Records Found..');

    // Event handler for form submission
    $(document).on('submit', '#ftr-banks', function(e){
        e.preventDefault(); // Prevent the default form submission
        var arr = $(this).serialize(); // Serialize form data

        showOverlay('Searching...'); // Show a loading overlay

        // Initialize or re-initialize the DataTable
        var table = $('#tbl-banks').DataTable({
            processing: true,    // Enable processing indicator
            serverSide: false,   // Disable server-side processing
            'dom': '<"wrapper"Bfritp>', // Custom DOM layout
            'order': [[0, "desc"]], // Default sorting
            'language': {
                'emptyTable': "Loading..." // Message for empty table
            },
            'paging': false,  // Disable pagination
            'ordering': true, // Enable column ordering
            'info': false,    // Disable info display
            'searching': false, // Disable searching
            'pageLength': 10,
            destroy: true,  // Destroy any existing DataTable instance
        });

        // AJAX post request to server-side handler
        axios.post(base_url + "/ibcas/banks/ftr-banks", arr)
            .then(function(response) {
                var data = response.data.TableContent;
                hideOverlay(); // Hide the loading overlay
                table.destroy(); // Destroy the DataTable instance

                // Re-initialize the DataTable with new data
                table = $('#tbl-banks').DataTable({
                    data: data,
                    fnCreatedRow: function(nRow, data, iDisplayIndex) {
                        $(nRow).attr('data-id', data.sub_id); // Add data attributes to rows
                    },
                    columns: [
                        { data: 'name' },
                        { data: 'shortname' },
                        { data: 'branch' },
                        { data: 'email' },
                        {
                            "data": function(data, type, row, meta) {
                                return `
                                    <a data-toggle="tooltip" title="View Bank Details" href="${base_url}/ibcas/banks/view/${data.id}" target="_blank" classator="btn btn-sm btn-soft-primary btn-view-details"><i class="mdi mdi-eye-outline"></i></a>
                                    <a data-toggle="tooltip" title="Update Bank Details" href="${base_url}/ibcas/banks/edit/${data.id}" target="_blank" class="btn btn-sm btn-soft-info btn-edit"><i class="mdi mdi-pencil-outline"></i></a>
                                    <a data-toggle="tooltip" title="Delete Bank " href="javascript:void(0);" class="btn btn-sm btn-soft-danger btn-delete delete" data-id="${data.id}"><i class="mdi mdi-delete-outline"></i></a>
                                `;
                            }
                        }
                    ],
                    'dom': '<"wrapper"Bfritp>',
                    'order': [[0, "desc"]],
                    "language": {
                        "emptyTable": "No Record Found"
                    },
                    'paging': true,
                    'ordering': true,
                    'info': false,
                    'searching': false,
                    "pageLength": 10,
                });

                $('[data-toggle="tooltip"]').tooltip(); // Reinitialize tooltips for new elements
            })
            .catch(function(error) {
                console.log(error);
            });
    });

    // Event handler for delete button clicks
    $(document).on('click','.btn-delete',function(){
        var id  = $(this).attr('data-id'); // Get the data-id attribute
        Swal.fire({
            title: 'Delete Bank Details?',
            text: "",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                showOverlay('Deleting...') // Show deletion overlay
                axios.get(base_url + "/ibcas/banks/deleteBankDetails/"+id)
                .then(function(response) {
                    if (response.data.status == 200) {
                        $('#tstsuccess strong').text(response.data.message);
                        toastsuccess.show(); // Show success toast
                        $('#ftr-banks').trigger('submit'); // Re-trigger form submission
                        setTimeout(function(){
                            toastsuccess.hide(); // Hide toast after delay
                        }, 2000);
                    } else {
                        $('#tsterror strong').text(response.data.message);
                        toasterror.show(); // Show error toast
                    }
                    hideOverlay(); // Hide the overlay
                })
                .catch(function(error) {
                    console.log(error);
                });
            }
        });
    });
});
