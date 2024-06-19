$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
   $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);
     $('[data-toggle="tooltip"]').tooltip();
    showOverlay('Loading data....')
    var msc_id = $('#msc_id').val();
    // loadapptypesel();
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

    setTimeout(function() {
    getMscDetails(msc_id);
    }, 1000); // 5000 milliseconds = 5 seconds  



    function getMscDetails(msc_id){
        axios.get(base_url + "/ibcas/miscellaneous/get-view-info/"+msc_id)
        .then(function(response) {
            // console.log(response.data.dtClaim);
            if(response.data.status == 'yes'){
                $('#msc_name').val(response.data.dtClaim[0].name);
                $('#msc_amount').val(response.data.dtClaim[0].amount);
                curr();
                hideOverlay();
                $('#tstsuccess strong').text('Miscellaneous Fee Loaded');
                toastsuccess.show();
            } else {
                hideOverlay();
            }
        })
        .catch(function(error) {
            console.log(error);
        });
    }
    
    $(document).on('submit','#frm_editMsc',function(e){
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
            confirmButtonText: 'Update'
          }).then((result) => {
            if (result.isConfirmed) {
                showOverlay('Updating...');
                var formData = new FormData(this);

                axios.post(base_url + "/ibcas/miscellaneous/save", formData)
                    .then(function(response) {
                        console.log(response.data);
                        
                          if(response.data.status == 'yes'){
                            hideOverlay();
                                $('#tstsuccess strong').text('Miscellaneous Fee updated');
                                toastsuccess.show();
                                $('#frm_submit').attr('hidden',false);
                                $('#frm_loading').attr('hidden',true);
                            $('#frm_editMsc')[0].reset();
                            setTimeout(function(){
                                toastsuccess.hide();
                                var url = base_url + "/ibcas/miscellaneous/";
                                window.location.href = url;
                              }, 2000);
                        } else {
                            hideOverlay
                            $('#tsterror strong').text('Error updating');
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


    function curr() {
        $('.curr').inputmask('decimal', {
            rightAlign: true,
            radixPoint: '.',         // Decimal separator
            groupSeparator: ',',     // Thousands separator
            autoGroup: true,         // Automatically group thousands
            digits: 2,               // <-- Set to 2 for two decimal places
            digitsOptional: false,   // Require the specified number of digits
            placeholder: '0',
            clearMaskOnLostFocus: false // Keep the mask always on
        });
    }

});