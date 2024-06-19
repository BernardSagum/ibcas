$(document).ready(function(){

     $('[data-toggle="tooltip"]').tooltip();
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  

    // getParticularType();
    hideOverlay();
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
                $('#tstwarning strong').text('Please fill all required fields...');
                toastwarning.show();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
      $(":input").inputmask();
      $("#effectivity_year").inputmask({
          "mask": "9999"
      });  
      $("#code").inputmask({
          "mask": "9-99-99-999"
      });  


      $(document).on('submit','#frm-addAccount',function(e){
        e.preventDefault();
        
        var arr = $(this).serialize();

        Swal.fire({
          title: 'Save account?',
        text: "",
        icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Save'
          }).then((result) => {
            if (result.isConfirmed) {
              showOverlay('Saving Account......')
        axios.post(base_url + "/ibcas/cAccounts/post-accounts", arr)
        .then(function(response) {
          hideOverlay();
          if (response.data.status == 200) {
            $('#tstsuccess strong').text(response.data.message);
          toastsuccess.show();
          setTimeout(function(){
            toastsuccess.hide();
            var url = base_url + "/ibcas/cAccounts/";
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
  })
      });



      // function getParticularType() {
      //   showOverlay('Loading resources....');
      //   axios.get(base_url + "/ibcas/paticulars/get-partType")
      //   .then(function(response) {
      //     var data = response.data.selectOptions;
      //     const select = document.getElementById('particular_type_id');

      //     for (let i = 0; i < data.length; i++) {
      //         // console.log(data[i]);
      //         const option = document.createElement('option');
      //                 option.value = data[i].id;
                     
      //                 option.textContent = data[i].name;
      //                 select.appendChild(option);
      //         }

      //         hideOverlay();
      //         $('#tstsuccess strong').text(response.data.message);
      //         toastsuccess.show(2000);
      //   })
        
      //   .catch(function(error) {
      //       console.log(error);
      //   });
        
      // }




});