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

    var tbl_payment = $('#payment-masterlist').DataTable({
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
    

   
    $(document).on('submit','#ftr_paymentlist',function(e){
        e.preventDefault();
        $('#frm_submit').attr('hidden',true);
        $('#frm_loading').attr('hidden',false);
        var arr = $(this).serialize();
        axios.post(base_url + "/ibcas/payment/filter", arr)
        .then(function(response) {
            // console.log(response.data);
            var data = response.data.TableContent;
            $('#payment-masterlist').dataTable().fnDestroy();

            var PAYMENTMASTERLIST = $('#payment-masterlist').DataTable({
                data: data,
                fnCreatedRow: function(nRow, data, iDisplayIndex) {
                    $(nRow).attr('data-id', data.id);
                //     $(nRow).attr('data-uniqueid', data.uniqueid);
                },
                columns: [
                    { data: 'application_type' },
                    { data: 'blpdno' },
                    { data: 'business_name' },
                    { data: 'tax_payer_name' },
                    { data: 'taxpayer_address' },
                  
                    
                    {
                        "data": function(data, type, row, meta) {
                            if(data.PaymentStatus != '_fullyPaid' ){
                                return `
                                <a href="${base_url}/ibcas/payment/business/${data.id}" data-placement="top" data-toggle="tooltip" title="Proceed payment" target="_blank" class="btn btn-sm btn-soft-success btn-view-details">Proceed
                                </a>
                                 `;
                            } else {
                                return `Paid`;


                              
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
                   'searching': false,
                   'paging': true,
                   "pageLength": 10,
            });

            $('[data-toggle="tooltip"]').tooltip();
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
            title: `Delete accountable officer?`,
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

                        axios.get(base_url + "/ibcas/a-officer/delete-acc-officer/"+id)
                        .then(function(response) {
                            // console.log(response);
                            if (response.data.status == 200) {
                                $('#tstsuccess strong').text(response.data.message);
                                // $('#tstsuccess label').text('Records Deleted');
                                toastsuccess.show({delay:100});
                                // toastr.success('Deleted', 'Success...', { "timeOut": "2000" });
                                $('#ftr-accofficer').submit();
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
    
    $(document).on('change','#ftr_selby',function(){
        // console.log();
        var ftrby = $(this).val();

        if (ftrby == 'mop') {
            $('#ftr_valDiv').attr('hidden',true);
            $('#ftr_selValDiv').attr('hidden',false);
            loadmodeofpayment();
        } else {
            $('#ftr_valDiv').attr('hidden',false);
            $('#ftr_selValDiv').attr('hidden',true);
        }




    });



    function loadmodeofpayment(){
        axios.get(base_url + "/ibcas/payment/getmodeofpayment")
        .then(function(response) {
            var data = response.data.TableContent;
            const select = document.getElementById('ftr_selVal');

            for (let i = 0; i < data.length; i++) {
                // console.log(data[i]);
                const option = document.createElement('option');
                        option.value = data[i].id;
                        option.dataset.numinst = data[i].numinst;
                        option.textContent = data[i].description;
                        select.appendChild(option);
                }

                
        })
        .catch(function(error) {
            console.log(error);
        });
    }
});
