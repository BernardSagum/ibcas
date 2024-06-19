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

$(document).ready(function(){
    $(":input").inputmask();
    $("#contactno").inputmask({
        "mask": "999-999-9999"
    });
    const bankId = document.getElementById('bankId').value;
   
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  

    axios.get(base_url + "/ibcas/banks/getBankDetails/"+bankId)
    .then(function(response) {
        if (response.data.status == 200) {
            document.getElementById('name').value = response.data.BankInfo[0].name;
            document.getElementById('shortname').value = response.data.BankInfo[0].shortname;
            document.getElementById('branch').value = response.data.BankInfo[0].branch;
            document.getElementById('email').value = response.data.BankInfo[0].email;
            document.getElementById('contact_person').value = response.data.BankInfo[0].contact_person;
            document.getElementById('contactno').value = response.data.BankInfo[0].contactno;
            document.getElementById('remarks').value = response.data.BankInfo[0].remarks;
         
            $('#tstsuccess strong').text(response.data.message);
            toastsuccess.show();
        } else{
            $('#tsterror strong').text(response.data.message);
          toasterror.show();
        }
        
    })
    .catch(function(error) {
        console.log(error);
    });

    $(document).on('submit','#frm-Bank',function(e){
        e.preventDefault();

        var arr = $(this).serialize();
        Swal.fire({
            title: 'Update Bank Details?',
            text: "",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Save'
        }).then((result) => {
            if (result.isConfirmed) {
                showOverlay('Updating Bank Details');
                axios.post(base_url + "/ibcas/banks/save-bank", arr)
                .then(function(response) {
                    hideOverlay();
                    if (response.data.status == 200) {
                        $('#tstsuccess strong').text(response.data.message);
                        toastsuccess.show();
                        setTimeout(function() {
                            toastsuccess.hide();
                            var url = base_url + "/ibcas/banks/";
                            window.location.href = url;
                        }, 2000);
                    } else {
                        $('#tsterror strong').text(response.data.message);
                        toasterror.show();
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
            } else {
                // $('#frm_submit').attr('hidden',false);
                // $('#frm_loading').attr('hidden',true);
            }
        });

    });







});