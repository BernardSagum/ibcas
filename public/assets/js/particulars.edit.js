$(document).ready(function(){
  
    // $('#frm_submit').attr('hidden',false);
    // $('#frm_loading').attr('hidden',true);
     $('[data-toggle="tooltip"]').tooltip();
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
    const par_id = document.getElementById('PartId').value;
    getParticularType();
    getParticularInfo(par_id);
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


      function getParticularInfo(par_id){
        showOverlay('Loading resources...');
        axios.get(base_url + "/ibcas/paticulars/getParticularInfo/"+par_id)
        .then(function(response) {
            if (response.data.status == 200) {
         
                // document.getElementById('par_id').innerHTML = response.data.ParticularInfo[0].id;
                document.getElementById('name').value = response.data.ParticularInfo[0].particular_name;
                document.getElementById('particular_type_id').value = response.data.ParticularInfo[0].particular_type_id;
                document.getElementById('code').value = response.data.ParticularInfo[0].code;
                document.getElementById('print_order').value = response.data.ParticularInfo[0].print_order;
                document.getElementById('remarks').value = response.data.ParticularInfo[0].remarks;
                // document.getElementById('frm_submit').innerHTML = 'UPDATE PARTICULAR';
                // document.getElementById('created_at').innerHTML = response.data.ParticularInfo[0].created_at;
                // document.getElementById('updated_by').innerHTML = response.data.ParticularInfo[0].updatedby;
                // document.getElementById('updated_at').innerHTML = response.data.ParticularInfo[0].updated_at;
                hideOverlay();
                $('#tstsuccess strong').text(response.data.message);
                toastsuccess.show(2000);
            }
        })
        .catch(function(error) {
            console.log(error);
        });
      }









      $(document).on('submit','#frm-addParticular',function(e){
        e.preventDefault();
        var arr = $(this).serialize();
      
        Swal.fire({
          title: 'Update particular?',
        text: "",
        icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Update it'
          }).then((result) => {
            if (result.isConfirmed) {
              showOverlay('Updating particular.....')
        axios.post(base_url + "/ibcas/particular/post-particular", arr)
        .then(function(response) {
          hideOverlay();
          if (response.data.status == 200) {
            $('#tstsuccess strong').text(response.data.message);
          toastsuccess.show();
          setTimeout(function(){
            toastsuccess.hide();
            var url = base_url + "/ibcas/particulars/";
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



      function getParticularType() {

        axios.get(base_url + "/ibcas/paticulars/get-partType")
        .then(function(response) {
          var data = response.data.selectOptions;
          const select = document.getElementById('particular_type_id');

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




});