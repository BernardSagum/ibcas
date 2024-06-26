$(document).ready(function(){
    // let timerInterval
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
    $('[data-toggle="tooltip"]').tooltip();
    $('#tstsuccess strong').text('Success');
    $('#tstsuccess label').text('Records Found..');


    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);

    var tblcollectors = $('#tblcollectors').DataTable({
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
    

    $(document).on('change','#ftr_selby',function(){
        var val = $(this).val();

       if (val == 'formnum') {
         getforms();
       } else if (val == 'fund') {
        getfunds();
       } else {

       }




    });

   

    $(document).on('submit','#ftr-collectors',function(e){
        e.preventDefault();
        $('#frm_submit').attr('hidden',true);
        $('#frm_loading').attr('hidden',false);
        var arr = $(this).serialize();
        // console.log(base_url);
        // toastr.warning('Table will be loaded in few seconds', 'Loading...', { "timeOut": "2000" });
        axios.post(base_url + "/ibcas/collectors/filter", arr)
        .then(function(response) {
            console.log(response.data);
            // setTimeout(function() {
            //     toastr.success('Table loaded successfully!', 'Success', { "timeOut": "2000" });
              
            var data = response.data.TableContent;
            $('#tblcollectors').dataTable().fnDestroy();

            var TBLCOLLECTORS = $('#tblcollectors').DataTable({
                data: data,
                fnCreatedRow: function(nRow, data, iDisplayIndex) {
                    $(nRow).attr('data-id', data.id);
                //     $(nRow).attr('data-uniqueid', data.uniqueid);
                },
                columns: [
                    { data: 'id' },
                    { data: 'collectorname' },
                    { data: 'accountable_person' },
                    { data: 'contact_number' },
                   
                    {
                        "data": function(data, type, row, meta) {
                      
                            return `
                            <a href="${base_url}/ibcas/collectors/view/${data.id}" data-placement="top" data-toggle="tooltip" title="View collector information" target="_blank" class="btn btn-sm btn-soft-primary btn-view-details"><i class="mdi mdi-eye-outline"></i></a>
                            <a href="${base_url}/ibcas/collectors/edit/${data.id}" data-placement="top" data-toggle="tooltip" title="Update collector information" target="_blank" class="btn btn-sm btn-soft-info btn-edit"><i class="mdi mdi-pencil-outline"></i></a>
                            <a href="javascript:void(0);" data-placement="top" data-toggle="tooltip" title="Delete collector information" class="btn btn-sm btn-soft-danger btn-delete delete" data-id="${data.id}"><i class="mdi mdi-delete-outline"></i></a>
                             `;
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
        },1000); // 5000 milliseconds = 5 seconds
        // 
    });


    $(document).on('click','.delete',function(){
        var id = $(this).attr('data-id');
        // var year = $(this).attr('data-year');
        Swal.fire({
            title: `Delete Collector from list?`,
            text:'Please enter password to confirm',
            input: 'password',
            icon: 'question',
            inputAttributes: {
              autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Confirm Password',
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                
            },
            
          }).then((result) => {
            if (result.isConfirmed) {
                var password = result.value;
                var frmData = new FormData();
                frmData.append('password', password);
                axios.post(base_url + "/ibcas/userman/checkpassword/",frmData)
                .then(function(response) {
                    // toastr.warning('Password Confirming', 'Processing...', { "timeOut": "2000" });
                    if (response.data.status == 200) {

                        axios.get(base_url + "/ibcas/collectors/delete-collector/"+id)
                        .then(function(response) {
                            // console.log(response);
                            if (response.data.status == 200) {
                                $('#tstsuccess strong').text(response.data.message);
                                // $('#tstsuccess label').text('Records Deleted');
                                toastsuccess.show({delay:100});
                                // toastr.success('Deleted', 'Success...', { "timeOut": "2000" });
                                $('#ftr-collectors').submit();
                            } else {
                                $('#tsterror strong').text(response.data.message);
                                // $('#tsterror label').text('Record deletion failed');
                                toasterror.show({delay:100});
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                        });


                        // toastr.success('Deleted', 'Success...', { "timeOut": "2000" });
                    } else {
                        $('#tstwarning strong').text('Wrong password');
                        // $('#tstwarning label').text('');
                        toastwarning.show({delay:100});
                    }


                    setTimeout(function() {
                        toastsuccess.hide();
                        toasterror.hide();
                        toastwarning.hide();
                    }, 3000); // 5000 milliseconds = 5 seconds
                })
                .catch(function(error) {
                    console.log(error);
                });
            }
          });
          
    });
    
    

});
