$(document).ready(function(){
    // let timerInterval

    $(":input").inputmask();
    $("#ftr_val").inputmask({
        "mask": "9999"
    });
    $('[data-toggle="tooltip"]').tooltip();

    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));

    $('#tstsuccess strong').text('Success');
    $('#tstsuccess label').text('Records Found..');

    // toastsuccess.show({delay: 3000});
    // toasterror.show();
    // toastwarning.show({delay: 3000});


    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);
    // toastr.warning('Page will load in a few seconds', 'Loading...', { "timeOut": "2000" });
    // setTimeout(function() {
    //     toastr.success('Page loaded successfully', 'Success', { "timeOut": "2000" });
    //   }, 1000); // 5000 milliseconds = 5 seconds
    var tblpenalty = $('#penalty-rates').DataTable({
        'dom': '<"wrapper"Bfritp>',
        'order': [
            [0, "desc"]
        ],
 
        "language": {
            "emptyTable": " No Record Found"
        },
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false,
        'paging': true,
        "pageLength": 10,
    });
    
    $(document).on('submit','#ftr_penalty',function(e){
        e.preventDefault();
        $('#frm_submit').attr('hidden',true);
        $('#frm_loading').attr('hidden',false);
        showOverlay('Loading penalty rates');
        var arr = $(this).serialize();
        // console.log(base_url);
        // toastr.warning('Table will be loaded in few seconds', 'Loading...', { "timeOut": "2000" });
        axios.post(base_url + "/ibcas/penalty-rates/filter", arr)
        .then(function(response) {
            // console.log(response.data);
            // setTimeout(function() {
            //     toastr.success('Table loaded successfully!', 'Success', { "timeOut": "2000" });
              
            var data = response.data.TableContent;
            $('#penalty-rates').dataTable().fnDestroy();

            var PENALTYRATES = $('#penalty-rates').DataTable({
                data: data,
                fnCreatedRow: function(nRow, data, iDisplayIndex) {
                    $(nRow).attr('data-id', data.id);
                //     $(nRow).attr('data-uniqueid', data.uniqueid);
                },
                columns: [
                    { data: 'year' },
                    {
                        "data": function(data, type, row, meta) {
                            return `
                            <a href="${base_url}/ibcas/penalty-rates/view/${data.id}" data-toggle="tooltip" title="View details" target="_blank" class="btn btn-sm btn-soft-primary btn-view-details"><i class="mdi mdi-eye-outline"></i></a>
                            <a href="${base_url}/ibcas/penalty-rates/edit/${data.id}" data-toggle="tooltip" title="Update details" target="_blank" class="btn btn-sm btn-soft-info btn-edit"><i class="mdi mdi-pencil-outline"></i></a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-soft-danger btn-delete delete" data-toggle="tooltip" title="Delete rate"  data-year="${data.year}" data-id="${data.id}"><i class="mdi mdi-delete-outline"></i></a>
                          `
                        }
                    },
                ],                
                'dom': '<"wrapper"Bfritp>',
                   'order': [
                       [0, "desc"]
                   ],
            
                   "language": {
                       "emptyTable": " No Record Found"
                   },
                   'paging': false,
                   'ordering': true,
                   'info': false,
                   'searching': false,
                   'paging': true,
                   "pageLength": 10,
            });
            $('[data-toggle="tooltip"]').tooltip();
        // }, 500); // 5000 milliseconds = 5 seconds

            // console.log(data);
        })
        .catch(function(error) {
            console.log(error);
        });
        // toastsuccess.show({delay: 100});
        // toastsuccess.show();
        
        setTimeout(function() {
            
            $('#frm_submit').attr('hidden',false);
            $('#frm_loading').attr('hidden',true);
            // toastsuccess.hide();
            hideOverlay();

        },1000); // 5000 milliseconds = 5 seconds
        // 
        

    });

    $(document).on('click', '.delete', function () {
    var id = $(this).attr('data-id');
    var year = $(this).attr('data-year');
    Swal.fire({
        title: `Delete rates from taxable year ${year}?`,
        text: 'Please enter password to confirm',
        input: 'password',
        icon: 'question',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Confirm Password',
        showLoaderOnConfirm: true,
        preConfirm: (password) => {
            return axios.post(base_url + "/ibcas/userman/checkpassword/", { password })
                .then(response => {
                    if (response.data.status == 200) {
                        return true; // Password is correct, allow deletion
                    } else {
                        Swal.showValidationMessage('Wrong password'); // Show error message
                        return false; // Prevent closing the dialog
                    }
                })
                .catch(error => {
                    console.log(error);
                    Swal.showValidationMessage('Error checking password'); // Show error message
                    return false; // Prevent closing the dialog
                });
        },
    }).then((result) => {
        if (result.isConfirmed) {
            axios.get(base_url + "/ibcas/penalty-rates/delete-penalty-rate/" + id)
                .then(function (response) {
                    if (response.data.status == 'yes') {
                        $('#tstsuccess strong').text('Records Deleted');
                        toastsuccess.show({ delay: 100 });
                        $('#ftr_penalty').submit();
                    } else {
                        $('#tsterror strong').text('Record deletion failed');
                        toasterror.show({ delay: 100 });
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    });

});


    // $(document).on('click','.delete',function(){
    //     var id = $(this).attr('data-id');
    //     var year = $(this).attr('data-year');
    //     Swal.fire({
    //         title: `Delete rates from taxable year ${year}?`,
    //         text:'Please enter password to confirm',
    //         input: 'password',
    //         icon: 'question',
    //         inputAttributes: {
    //           autocapitalize: 'off'
    //         },
    //         showCancelButton: true,
    //         confirmButtonText: 'Confirm Password',
    //         showLoaderOnConfirm: true,
    //         preConfirm: (login) => {
                
    //         },
            
    //       }).then((result) => {
    //         if (result.isConfirmed) {
    //             var password = result.value;
    //             var frmData = new FormData();
    //             frmData.append('password', password);
    //             axios.post(base_url + "/ibcas/userman/checkpassword/",frmData)
    //             .then(function(response) {
    //                 // toastr.warning('Password Confirming', 'Processing...', { "timeOut": "2000" });
    //                 if (response.data.status == 200) {

    //                     axios.get(base_url + "/ibcas/penalty-rates/delete-penalty-rate/"+id)
    //                     .then(function(response) {
    //                         // console.log(response);
    //                         if (response.data.status == 'yes') {
    //                             $('#tstsuccess strong').text('Records Deleted');
    //                             // $('#tstsuccess label').text('Records Deleted');
    //                             toastsuccess.show({delay:100});
    //                             // toastr.success('Deleted', 'Success...', { "timeOut": "2000" });
    //                             $('#ftr_penalty').submit();
    //                         } else {
    //                             $('#tsterror strong').text('Record deletion failed');
    //                             // $('#tsterror label').text('Record deletion failed');
    //                             toasterror.show({delay:100});
    //                         }
    //                     })
    //                     .catch(function(error) {
    //                         console.log(error);
    //                     });


    //                     // toastr.success('Deleted', 'Success...', { "timeOut": "2000" });
    //                 } else {
    //                     $('#tstwarning strong').text('Wrong password');
    //                     // $('#tstwarning label').text('');
    //                     toastwarning.show({delay:100});
    //                 }


    //                 setTimeout(function() {
    //                     toastsuccess.hide();
    //                     toasterror.hide();
    //                     toastwarning.hide();
    //                 }, 3000); // 5000 milliseconds = 5 seconds
    //             })
    //             .catch(function(error) {
    //                 console.log(error);
    //             });
    //         }
    //       });
          
    // });
    
    

});
