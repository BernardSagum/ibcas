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

    var tbl_accform = $('#acc-forms').DataTable({
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
        'searching': true,
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

    function getforms(){

        axios.get(base_url + "/ibcas/acc-forms/get-all-forms/")
        .then(function(response) {
            console.log(response.data);
            if(response.data.status == 'yes'){
                var data = response.data.TableContent;
                var select = document.getElementById('ftr_val');
                select.innerHTML = '';
                var opt = document.createElement('option');
                opt.value = '';
                opt.text = 'Select';
                opt.selected = true;
                select.appendChild(opt);


                // Iterate over the data array
                for (var i = 0; i < data.length; i++) {
                    // Create an option element
                    var option = document.createElement('option');
                    
                    // Set the value and text of the option
                    option.value = data[i].id;
                    option.text = data[i].form_no;
                    
                    // Append the option to the select element
                    select.appendChild(option);
                }
            }
        })
        .catch(function(error) {
            console.log(error);
        });


        // var select = document.getElementById('form_id');
    }

    function getfunds(){

        axios.get(base_url + "/ibcas/acc-forms/get-all-funds/")
        .then(function(response) {
            console.log(response.data);
            if(response.data.status == 'yes'){
                var data = response.data.TableContent;
                var select = document.getElementById('ftr_val');
                select.innerHTML = '';
                var opt = document.createElement('option');
                opt.value = '';
                opt.text = 'Select';
                opt.selected = true;
                select.appendChild(opt);

                // Iterate over the data array
                for (var i = 0; i < data.length; i++) {
                    // Create an option element
                    var option = document.createElement('option');
                    
                    // Set the value and text of the option
                    option.value = data[i].id;
                    option.text = data[i].name;
                    
                    // Append the option to the select element
                    select.appendChild(option);
                }
            }
        })
        .catch(function(error) {
            console.log(error);
        });


        // var select = document.getElementById('form_id');
    }

    $(document).on('submit','#ftr-accform',function(e){
        e.preventDefault();
        $('#frm_submit').attr('hidden',true);
        $('#frm_loading').attr('hidden',false);
        var arr = $(this).serialize();
        // console.log(base_url);
        // toastr.warning('Table will be loaded in few seconds', 'Loading...', { "timeOut": "2000" });
        axios.post(base_url + "/ibcas/acc-form/filter", arr)
        .then(function(response) {
            // console.log(response.data);
            // setTimeout(function() {
            //     toastr.success('Table loaded successfully!', 'Success', { "timeOut": "2000" });
              
            var data = response.data.TableContent;
            $('#acc-forms').dataTable().fnDestroy();

            var ACCFORMS = $('#acc-forms').DataTable({
                data: data,
                fnCreatedRow: function(nRow, data, iDisplayIndex) {
                    $(nRow).attr('data-id', data.id);
                //     $(nRow).attr('data-uniqueid', data.uniqueid);
                },
                columns: [
                    { data: 'form_no' },
                    { data: 'fundname' },
                    { data: 'stub_no' },
                    {
                        "data": function(data, type, row, meta) {
                            return `${data.from} - ${data.to}`;
                        }
                    },
                    { "data": function(data, type, row, meta) {
                        return `${data.to - data.from + 1}`;
                    } },
                    { "data": function(data, type, row, meta) {
                        return `${data.to - data.from + 1}`;
                    } },
                    
                    {
                        "data": function(data, type, row, meta) {
                        if (data.date_issued == null) {
                            return `
                            <a href="${base_url}/ibcas/acc-forms/view/${data.id}" data-placement="top" data-toggle="tooltip" title="View form" target="_blank" class="btn btn-sm btn-soft-primary btn-view-details"><i class="mdi mdi-eye-outline"></i></a>
                            <a href="${base_url}/ibcas/acc-forms/edit/${data.id}" data-placement="top" data-toggle="tooltip" title="Update form" target="_blank" class="btn btn-sm btn-soft-info btn-edit"><i class="mdi mdi-pencil-outline"></i></a>
                            <a href="javascript:void(0);" data-placement="top" data-toggle="tooltip" title="Delete form" class="btn btn-sm btn-soft-danger btn-delete delete" data-id="${data.id}"><i class="mdi mdi-delete-outline"></i></a>
                            <a target="_blank" href="${base_url}/ibcas/acc-forms/assign/${data.id}" data-placement="top" data-toggle="tooltip" title="Assign form" class="btn btn-sm btn-soft-warning btn-asign asign" data-id="${data.id}"><i class="mdi mdi-account-check-outline"></i></a>
                          `
                        } else {
                            return `
                            <a href="${base_url}/ibcas/acc-forms/view/${data.id}" data-placement="top" data-toggle="tooltip" title="View form" target="_blank" class="btn btn-sm btn-soft-primary btn-view-details"><i class="mdi mdi-eye-outline"></i></a>
                            <a target="_blank" href="${base_url}/ibcas/acc-forms/reassign/${data.id}" data-placement="top" data-toggle="tooltip" title="Reassign form" class="btn btn-sm btn-soft-warning btn-asign asign" data-id="${data.id}"><i class="mdi mdi-account-check-outline"></i></a>
                            <a target="_blank" href="${base_url}/ibcas/acc-forms/void/${data.id}" data-placement="top" data-toggle="tooltip" title="Void form" class="btn btn-sm btn-soft-danger btn-asign asign" data-id="${data.id}"><i class="mdi mdi-cancel"></i></a>
                         `
                        }

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
                   'searching': true,
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
            title: `Delete accountable form?`,
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

                        axios.get(base_url + "/ibcas/acc-forms/delete-acc-form/"+id)
                        .then(function(response) {
                            // console.log(response);
                            if (response.data.status == 200) {
                                $('#tstsuccess strong').text(response.data.message);
                                // $('#tstsuccess label').text('Records Deleted');
                                toastsuccess.show({delay:100});
                                // toastr.success('Deleted', 'Success...', { "timeOut": "2000" });
                                $('#ftr-accform').submit();
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
