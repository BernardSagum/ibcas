$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
    $('[data-toggle="tooltip"]').tooltip();
    (function() {
        'use strict';
        window.addEventListener('load', function() {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');
          
          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();


    getforms();
    function getforms(){

        axios.get(base_url + "/ibcas/acc-forms/get-all-forms/")
        .then(function(response) {
            console.log(response.data);
            if(response.data.status == 'yes'){
                var data = response.data.TableContent;
                var select = document.getElementById('form_id');
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




    // $('#frm_submit').attr('hidden',false);
    // $('#frm_loading').attr('hidden',true);

    // var tblORSummaryReport = $('#tblORSummaryReport').DataTable({
    //     'dom': '<"wrapper"Bfritp>',
    //     'order': [
    //         [0, "desc"]
    //     ],
 
    //     "language": {
    //         "emptyTable": " No Record Found"
    //     },
    //     'paging': false,
    //     'ordering': false,
    //     'info': false,
    //     'searching': false,
    //     'paging': true,
    //     "pageLength": 10,
    // });

    $(document).on('submit','#ftr-orSummary',function(e){
        e.preventDefault();
        var arr = $(this).serialize();
        var OrDate = $('#OrDate').val().replace(/-/g, '');
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
                        
                        
                        showOverlay('Generating Summary');
                        axios.post(base_url + "/ibcas/report/filter-orSummary", arr)
                        .then(function(response) {
                            hideOverlay();
                            var data = response.data.TableContent;
                            exportToExcel('Official Receipt Summary-' + OrDate, data, 'OR Summary');
                            toastr.success('Generated and Downloaded', 'Success...', { "timeOut": "2000" });
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                        
                        
                        
                        // var arr = $('#ftr-orSummary').serialize();
                        // axios.post(base_url + "/ibcas/report/filter-orSummary", arr)
                        // .then(function (response) {
                        //     var data = response.data.TableContent;
                        //     // Call the function to download the Excel file
                        //     exportToExcel('Official Receipt Summary', data, 'OR Summary');
                        // })
                        // .catch(function (error) {
                        //     console.log(error);
                        // });
                       


                        
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
                        var arr = $('#ftr-orSummary').serialize();
                        axios.post(base_url + "/ibcas/report/filter-orSummary", arr)
                        .then(function (response) {
                            var data = response.data.TableContent;
                            // Call the function to download the Excel file
                            exportToExcel('Official Receipt Summary', data, 'OR Summary');
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