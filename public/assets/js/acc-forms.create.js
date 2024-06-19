$(document).ready(function(){
    // let timerInterval
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));

    // $('#tstsuccess strong').text('Success');
    // $('#tstsuccess label').text('Records Found..');
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

    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);
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

    getfunds();
    function getfunds(){

        axios.get(base_url + "/ibcas/acc-forms/get-all-funds/")
        .then(function(response) {
            console.log(response.data);
            if(response.data.status == 'yes'){
                var data = response.data.TableContent;
                var select = document.getElementById('fund_id');
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
                    option.text = data[i].name;
                    
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
    

    $(document).on('submit','#ftr_new_acform',function(e){
        e.preventDefault();
        $('#frm_submit').attr('hidden',true);
        $('#frm_loading').attr('hidden',false);    
        var arr = $(this).serialize();
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
                $('#frm_submit').attr('hidden',false);
                $('#frm_loading').attr('hidden',true);  

                axios.post(base_url + "/ibcas/acc-forms/save", arr)
                    .then(function(response) {
                        console.log(response.data);
                        if (response.data.status == 200){
                            $('#tstsuccess strong').text(response.data.message);
                            toastsuccess.show();
                            $('#ftr_new_acform')[0].reset();
                            setTimeout(function(){
                                toastsuccess.hide();
                                var url = base_url + "/ibcas/acc-forms/";
                                window.location.href = url;
                              }, 2000);
                        } else if(response.data.status == 409){
                            showAlertWithDuplicates(response.data.duplicate_entries)
                          }else {
                            $('#tsterro strong').text(response.data.message);
                            toasterror.show();
                        }

                    })
                    .catch(function(error) {
                        console.log(error);
                    });

            }
        })

    });
    function showAlertWithDuplicates(duplicates) {
        // Construct the message to display
        let message = '<p>The following O.R. number series are already encoded:</p><ul>';
        duplicates.forEach(function(entry) {
            // Assuming you want to display the 'from' and 'to' values, but you can adjust this to show whatever you need
            message += `<li>${entry.from} - ${entry.to}</li>`;
        });
        message += '</ul>';
    
        // Display the SweetAlert
        Swal.fire({
            title: 'Duplicate Entries Found!',
            html: message, // Use `html` to render HTML content
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    }

});
