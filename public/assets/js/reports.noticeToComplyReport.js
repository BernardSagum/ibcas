$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
    $(".serMask").inputmask("9999-99999", { "placeholder": "yyyy-#####", clearIncomplete: true });


    
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
   
      $(document).on('submit','#ftr-notice-report',function(e){
        e.preventDefault();

        var arr = $(this).serialize();
        var serFrom = $('#serFrom').val();
        var serTo = $('#serTo').val();
        Swal.fire({
            title: `Generate Report?`,
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
                        
                        
                        showOverlay('Generating Report');
                        axios.post(base_url + "/ibcas/reports/filterNoticeReport/", arr)
                        .then(function(response) {
                            hideOverlay();
                            var data = response.data.TableContent;
                            exportToExcel('Notice to Comply' + serFrom +'-'+serTo, data, 'OR Logbook');
                            toastr.success('Generated and Downloaded', 'Success...', { "timeOut": "2000" });
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                        

                        
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





        // axios.post(base_url + "/ibcas/reports/filterNoticeReport/", arr)
        // .then(function(response) {
        
        // if(response.data.status == 200){
        //     console.log(response.data.message);
        // } else {
        //     console.log(response.data.message);
        // }

        // })
        // .catch(function(error) {
        //     console.log(error);
        // });




      });






});