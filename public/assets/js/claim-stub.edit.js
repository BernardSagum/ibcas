$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
   $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);
     $('[data-toggle="tooltip"]').tooltip();
    showOverlay('Loading schedule data....')
    var c_id = $('#c_id').val();
    loadapptypesel();
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
    function loadapptypesel(){
        axios.get(base_url + "/ibcas/claim-stub/get-apptype")
        .then(function(response) {
            var data = response.data.TableContent;
            const select = document.getElementById('application_type_id');

            

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
    setTimeout(function() {
    getClaimstubData(c_id);
    }, 1000); // 5000 milliseconds = 5 seconds  



    function getClaimstubData(c_id){
        axios.get(base_url + "/ibcas/claim-stub/get-view-info/"+c_id)
        .then(function(response) {
            // console.log(response.data.dtClaim);
            if(response.data.status == 'yes'){
                $('#application_type_id').val(response.data.dtClaim[0].application_type_id);
                $('#tax_effectivity_year').val(response.data.dtClaim[0].tax_effectivity_year);
                $('#first_quarter_date').val(response.data.dtClaim[0].first_quarter_date);
                $('#first_quarter_peak_days').val(response.data.dtClaim[0].first_quarter_peak_days);
                $('#second_quarter_date').val(response.data.dtClaim[0].second_quarter_date);
                $('#second_quarter_peak_days').val(response.data.dtClaim[0].second_quarter_peak_days);
                $('#third_quarter_date').val(response.data.dtClaim[0].third_quarter_date);
                $('#third_quarter_peak_days').val(response.data.dtClaim[0].third_quarter_peak_days);
                $('#fourth_quarter_date').val(response.data.dtClaim[0].fourth_quarter_date);
                $('#fourth_quarter_peak_days').val(response.data.dtClaim[0].fourth_quarter_peak_days);
                $('#nonpeak_days').val(response.data.dtClaim[0].nonpeak_days);
                $('#remarks').val(response.data.dtClaim[0].remarks);
                // $('#created_by').text(response.data.dtClaim[0].username);
                // $('#created_at').text(response.data.dtClaim[0].created_at);
                // $('#updated_by').text(response.data.dtClaim[0].updated_by);
                // $('#updated_at').text(response.data.dtClaim[0].updated_at);

                hideOverlay();
                $('#tstsuccess strong').text('Claimstub schedule loaded');
                toastsuccess.show();
            } else {
                hideOverlay();
            }
        })
        .catch(function(error) {
            console.log(error);
        });
    }
    
    $(document).on('submit','#ftr_edit_claimstub',function(e){
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

                axios.post(base_url + "/ibcas/claim-stub/save", formData)
                    .then(function(response) {
                        console.log(response.data);
                        
                          if(response.data.status == 'yes'){
                            hideOverlay();
                                $('#tstsuccess strong').text('Claimsub schedule updated');
                                toastsuccess.show();
                                $('#frm_submit').attr('hidden',false);
                                $('#frm_loading').attr('hidden',true);
                            $('#ftr_edit_claimstub')[0].reset();
                            setTimeout(function(){
                                toastsuccess.hide();
                                var url = base_url + "/ibcas/claim-stub/";
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




});