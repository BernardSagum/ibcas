$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
    $('[data-toggle="tooltip"]').tooltip();
    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);

    // $(document).on('change', '#ftr_selby', function() {
    //     if ($(this).val() == 'blpdNumber') {
    //         $('.ftr_apptype').attr('hidden', true);
    //         $('.ftr_val').attr('hidden', false);
    //     } else if ($(this).val() == 'stats') {
    //         $('.ftr_apptype').attr('hidden', false);
    //         $('.ftr_val').attr('hidden', true);
    //     }
    // });
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
    
            // Loop over forms and prevent submission while adding validation styles
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
    
            // Get the Extract button
            var extractButton = document.getElementById('exportBusinessMasterlist');
    
            // Event listener for the Extract button
            extractButton.addEventListener('click', function(event) {
                // Check if the form is valid
                var form = document.getElementById('ftr-business'); // Assuming this is the ID of your form
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    form.classList.add('was-validated');
                } else {
                    // If valid, you can proceed with the extraction process
                    // Trigger your extraction logic here
                    console.log("Extraction process starts");
                }
            });
    
            // Additional code to handle enabling/disabling of the extract button based on other form interactions
            // For example, enabling the button only when certain conditions are met
            var selectElement = document.getElementById('ftr_selby');
            selectElement.addEventListener('change', function(event) {
                // Enable the extract button if a certain option is selected
                if (this.value === "stats") {  // example condition
                    extractButton.disabled = false;
                } else {
                    extractButton.disabled = true;
                }
            });
        }, false);
    })();
    
    function enableselVal(){
        $('#ftr_val').attr('required',true);
        $('#ftr_val').attr('disabled',false);
        $('#ftr_val').inputmask('9999-9999-9999');
    }
    function disableselVal(){
        $('#ftr_val').attr('disabled',true);
        $('#ftr_val').inputmask('');
        $('#ftr_val').val('');
        $('#ftr_val').attr('required',false);
    }

    $(document).on('change','#ftr_selby',function(){
        var selBy = $(this).val();
        $('#exportBusinessMasterlist').attr('disabled',true);
        if (selBy == 'blpdNumber') {
            enableselVal();
            $('.ftr_apptype').attr('hidden', true);
            $('.ftr_val').attr('hidden', false);
            $('#ftr_sel').attr('required', false);
        } else if ($(this).val() == 'stats') {
            $('.ftr_apptype').attr('hidden', false);
            $('.ftr_val').attr('hidden', true);
            $('#ftr_sel').attr('required', true);
        } else {
           disableselVal();
           $('.ftr_apptype').attr('hidden', true);
           $('.ftr_val').attr('hidden', false);
           $('#ftr_sel').attr('required', false);
        }

    });

    $('#exportBusinessMasterlist').attr('disabled',true);
    var tblBusinessMasterlist = $('#tbl_BusinessMasterlist').DataTable({
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




    $(document).on('submit','#ftr-business',function(e){
        
        e.preventDefault();
        var arr = $(this).serialize();
        showOverlay('Loading Masterlist');
        axios.post(base_url + "/ibcas/report/filter-Business", arr)
        .then(function(response) {
          
            var data = response.data.TableContent;

            $('#tbl_BusinessMasterlist').dataTable().fnDestroy();

            var BUSINESSMASTERLIST = $('#tbl_BusinessMasterlist').DataTable({
                data: data,
                fnCreatedRow: function(nRow, data, iDisplayIndex) {
                    $(nRow).attr('data-id', data.id);
                //     $(nRow).attr('data-uniqueid', data.uniqueid);
                },
                columns: [
                    { data: 'blpdno' },
                    { data: 'business_name' },
                    { data: 'tax_payer_name' },
                    { data: 'taxpayer_address' },
                  
                    
                    {
                        "data": function(data, type, row, meta) {
                            // if(data._stat == 'Paid'){
                            //     return `<a href="${base_url}/ibcas/reprint/Official-Receipt/${data.id}" data-placement="top" data-toggle="tooltip" title="Reprint Official Receipt" target="_blank" class="btn btn-sm btn-soft-warning btn-view-details">Reprint OR
                            //     </a>`;
                            // } else {
                            return `
                            <a href="javascript:void(0);"  data-placement="top" data-id="${data.id}" data-blpdNum="${data.blpdno}" data-toggle="tooltip" title="View Business Ledger" class="btn btn-sm btn-soft-success btn-business-ledger"><i class='fas fa-book'></i></a>
                            <a href="javascript:void(0);"  data-placement="top" data-id="${data.id}" data-blpdNum="${data.blpdno}" data-toggle="tooltip" title="View Assessment SLip" class="btn btn-sm btn-soft-info btn-view-assessment"><i class='fas fa-calculator'></i></a>

                             `;
                            // }
                            // return ``;
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
                   'info': true,
                   'searching': false,
                   'paging': true,
                   "pageLength": 10,
            });

            $('[data-toggle="tooltip"]').tooltip();


            hideOverlay();
            $('#exportBusinessMasterlist').attr('disabled',false);
            // console.log(data);
        })
        .catch(function(error) {
            console.log(error);
        });
    })

    $(document).on('click','.btn-business-ledger',function(){
        var blpdNUm = $(this).attr('data-blpdNum')
        openPromptWindow(base_url+'/ibcas/reports/business-legder/'+blpdNUm);
    });
    $(document).on('click','.btn-view-assessment',function(){
        var blpdNUm = $(this).attr('data-blpdNum')
        openPromptWindow(base_url+'/ibcas/reports/business-legder/'+blpdNUm);
    });




    function openPromptWindow(url) {
        var width = 2000; // Width of the window
        var height = 5000; // Height of the window
    
        // Calculate the position to open the window in the center of the screen
        var left = (screen.width / 2) - (width / 2);
        var top = (screen.height / 2) - (height / 2);
    
        // Specify features of the window
        var features = 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left;
        features += ',menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes';
    
        // Open the window
        var newWindow = window.open(url, 'PromptWindow', features);
    
        // Focus on the new window in case it opens in the background
        if (window.focus) {
            newWindow.focus();
        }
    }
    







    $(document).on('click','#exportBusinessMasterlist',function(){
        var id = $(this).attr('data-id');
        // var year = $(this).attr('data-year');
        Swal.fire({
            title: `Extract List of Establishments?`,
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
                        var arr = $('#ftr-business').serialize();
                        axios.post(base_url + "/ibcas/report/filter-Business", arr)
                        .then(function (response) {
                            var data = response.data.TableContent;
                            // Call the function to download the Excel file
                            exportToExcel('BusinessReport', data, 'BusinessData');
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                       


                        toastr.success('Extracted', 'Success...', { "timeOut": "2000" });
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