$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
    $('[data-toggle="tooltip"]').tooltip();
    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);
    $(":input").inputmask();
    

    $(document).on('change','#ftr_selby',function(){
        var ftr_selby = $(this).val();

        if (ftr_selby == 'msc_name') {
            $('.ftr_taxyr').attr('hidden',false);
        }
    });

    var tblclaimstub = $('#tbl-msc').DataTable({
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


    $(document).on('submit','#ftr_msc',function(e){
        e.preventDefault();
        $('#frm_submit').attr('hidden',true);
        $('#frm_loading').attr('hidden',false);
        var arr = $(this).serialize();
      
        axios.post(base_url + "/ibcas/miscellaneous/filter", arr)
        .then(function(response) {
          
            var data = response.data.TableContent;
            console.log(data);
            $('#tbl-msc').dataTable().fnDestroy();

            var TBLMSC = $('#tbl-msc').DataTable({
                data: data,
                fnCreatedRow: function(nRow, data, iDisplayIndex) {
                    $(nRow).attr('data-id', data.id);
                },
                columns: [
                    { data: 'name' },
                    { data: 'amount' },
                   
                    {
                        "data": function(data, type, row, meta) {
                            return `
                            <a data-toggle="tooltip" title="View fee" href="${base_url}/ibcas/miscellaneous/view/${data.id}" target="_blank" class="btn btn-sm btn-soft-primary btn-view-details"><i class="mdi mdi-eye-outline"></i></a>
                            <a data-toggle="tooltip" title="Update fee" href="${base_url}/ibcas/miscellaneous/edit/${data.id}" target="_blank" class="btn btn-sm btn-soft-info btn-edit"><i class="mdi mdi-pencil-outline"></i></a>
                            <a  data-toggle="tooltip" title="Delete fee" href="javascript:void(0);" class="btn btn-sm btn-soft-danger btn-delete delete"  data-year="${data.year}" data-id="${data.id}"><i class="mdi mdi-delete-outline"></i></a>
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
       
        })
        .catch(function(error) {
            console.log(error);
        });
      
        
        setTimeout(function() {
            
            $('#frm_submit').attr('hidden',false);
            $('#frm_loading').attr('hidden',true);
 
        },1000); // 5000 milliseconds = 5 seconds
        // 
    });

    $(document).on('click', '.delete', function () {
        var id = $(this).attr('data-id');
        
        Swal.fire({
            title: `Delete Miscellaneous Fee?`,
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
                showOverlay('Deleting');
                axios.get(base_url + "/ibcas/miscellaneous/delete-miscellaneous-fee/"+id)
                    .then(function (response) {
                        if (response.data.status == 200) {
                            hideOverlay();
                            $('#tstsuccess strong').text('Deleted');
                          
                            toastsuccess.show({delay:100});
                            // toastr.success('Deleted', 'Success...', { "timeOut": "2000" });
                            $('#ftr_msc').submit();
                        } else {
                            hideOverlay();
                            $('#tsterror strong').text('Deletion failed');
                         
                            toasterror.show({delay:100});
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
    //         title: `Delete Claimstub Schedule?`,
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
    //                 if (response.data.status == '200') {
    
    //                     axios.get(base_url + "/ibcas/claim-stub/delete-claimstub-schedule/"+id)
    //                     .then(function(response) {
    //                         // console.log(response);
                            // if (response.data.status == 200) {
                            //     $('#tstsuccess strong').text('Deleted');
                              
                            //     toastsuccess.show({delay:100});
                            //     // toastr.success('Deleted', 'Success...', { "timeOut": "2000" });
                            //     $('#ftr_claimstub').submit();
                            // } else {
                            //     $('#tsterror strong').text('Deletion failed');
                             
                            //     toasterror.show({delay:100});
                            // }
    //                     })
    //                     .catch(function(error) {
    //                         console.log(error);
    //                     });
    
    
    //                     // toastr.success('Deleted', 'Success...', { "timeOut": "2000" });
    //                 } else {
    //                     $('#tsterror strong').text('Invalid Password');
    //                     toasterror.show();
    //                     setTimeout(function(){
    //                         toasterror.hide();
    //                       }, 2000);
    //                 }
    
    
    //                 // setTimeout(function() {
    //                 //     toastsuccess.hide();
    //                 //     toasterror.hide();
    //                 //     toastwarning.hide();
    //                 // }, 3000); // 5000 milliseconds = 5 seconds
    //             })
    //             .catch(function(error) {
    //                 console.log(error);
    //             });
    //         }
    //       });
          
    // });

});












function dateformat(bdate){
    // var bdate = "1994-01-12";
    if(bdate == null){
        return "-";
    } else {
    // var tdate = bdate.split(' ');
    // // console.log(tdate);
    // var fdate = tdate[0];
    // var ftime = tdate[1];
    // console.log(fdate.split('-'))
    var spldate = bdate.split('-');
    // var spltime = ftime.split(':');

    // console.log(spltime)
    var yr = spldate[0];
    var mnt = spldate[1];
    var dy = spldate[2];

    // var hr = (spltime[0] > 12 ? spltime[0] - 12 : spltime[0]);
    // var min = spltime[1];
    // var ampm = (spltime[0] > 12 ? 'PM' : 'AM');
    return mnt+"/"+dy+"/"+yr;
    // return mnt+"/"+dy+"/"+yr+" "+hr+":"+min+" "+ampm;
    // // return "111111";
    }
    
}