$(document).ready(function(){
    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);
     $('[data-toggle="tooltip"]').tooltip();
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
    $(":input").inputmask();
    $(".yrclass").inputmask({
        "mask": "9999"
    });

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




    loadapptypesel();
    function loadapptypesel(){
        axios.get(base_url + "/ibcas/claim-stub/get-apptype")
        .then(function(response) {
            var data = response.data.TableContent;
            const select = document.getElementById('apptype');

            

            for (let i = 0; i < data.length; i++) {
                // console.log(data[i]);
                const option = document.createElement('option');
                        option.value = data[i].id;
                        option.textContent = data[i].name;
                        select.appendChild(option);
                }


        })
        .catch(function(error) {
            console.log(error);
        });
    }

    $(document).on('submit','#ftr_new_claimstub',function(e){
        e.preventDefault();
        $('#frm_submit').attr('hidden',true);
        $('#frm_loading').attr('hidden',false);
        Swal.fire({
          title: 'Are you sure?',
        text: "You won't be able to revert this",
        icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Save'
          }).then((result) => {
            if (result.isConfirmed) {
                
                // toastr.warning('Saving in progress', 'Saving...', { "timeOut": "1000" })
                //  console.log(arr);
                var formData = new FormData(this);
                // formData.append('application_type_id', year);
                // formData.append('looparray', arr);
            
                // var arr = $(this).serialize();
                axios.post(base_url + "/ibcas/claim-stub/save", formData)
                    .then(function(response) {
                        console.log(response.data);
            
                          if(response.data.status == 'yes'){
                                $('#tstsuccess strong').text('New claimsub schedule saved');
                                toastsuccess.show();
                                $('#frm_submit').attr('hidden',false);
                                $('#frm_loading').attr('hidden',true);
                            $('#ftr_new_claimstub')[0].reset();
                            setTimeout(function(){
                                toastsuccess.hide();
                                var url = base_url + "/ibcas/claim-stub/";
                                window.location.href = url;
                              }, 2000);
                        } else {
                            $('#tsterror strong').text('Error saving');
                            toasterror.show();
                            $('#frm_submit').attr('hidden',false);
                            $('#frm_loading').attr('hidden',true);
                            setTimeout(function(){
                                toasterror.hide();
                              }, 2000);
                        }
            
            
            
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
                // console.log(arr);
    
    
            } else {
                $('#frm_submit').attr('hidden',false);
                $('#frm_loading').attr('hidden',true);
            }
          })
    
    
    
    
    
    
    });





});