$(document).ready(function(){

    getcollectors();

    function getcollectors(){
        axios.get(base_url + "/ibcas/reports/get-collectors")
                    .then(function(response) {
                        // console.log(response.data);
                        if(response.data.status == 200){
                            var data = response.data.TableContent;
                            console.log(data);
                            var select = document.getElementById('collector_id');
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
                                option.value = data[i].user_id;
                                option.text = data[i].collectorname;
                                
                                // Append the option to the select element
                                select.appendChild(option);
                            }
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
    }

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




    $(document).on('submit','#ftr-OrLogbook',function(e){
        e.preventDefault();

        var arr = $(this).serialize();
        var DateFrom = $('#DateFrom').val().replace(/-/g, '');
        var DateTo = $('#DateFrom').val().replace(/-/g, '');
        Swal.fire({
            title: `Generate OR Logbook?`,
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
                        axios.post(base_url + "/ibcas/report/filter-orLogbook", arr)
                        .then(function(response) {
                            hideOverlay();
                            var data = response.data.TableContent;
                            exportToExcel('Official Receipt LogBook_' + DateFrom +'-'+DateTo, data, 'OR Logbook');
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

    });
    


});