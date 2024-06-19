(function () {
    'use strict';

    // Retrieve form elements for validation
    var forms = document.querySelectorAll('.needs-validation');

    // Function to check if the new and confirm passwords match
    function checkPasswordsMatch() {
        var newPassword = document.getElementById('newPass').value;
        var confirmPassword = document.getElementById('confimPass').value;
        var confirmPasswordInput = document.getElementById('confimPass');
        if (newPassword !== confirmPassword) {
            confirmPasswordInput.setCustomValidity('Passwords do not match');
            confirmPasswordInput.nextElementSibling.textContent = 'Passwords do not match';
        } else {
            confirmPasswordInput.setCustomValidity('');
            confirmPasswordInput.nextElementSibling.textContent = 'Password must be at least 8 characters long and match the new password.';
        }
    }

    // Function to check if the current password is correct
    function checkCurrentPassword() {
        var currentPasswordInput = document.getElementById('curPass');
        var hiddenPassword = document.getElementById('hidPassword').value; // Fixed typo from 'hidAuthenticity' to 'hidPassword'
        if (currentPasswordInput.value.length >= 8) {
            if (currentPasswordInput.value !== hiddenPassword) {
                currentPasswordInput.setCustomValidity('Current password is incorrect');
                currentPasswordInput.nextElementSibling.textContent = 'Current password is incorrect';
            } else {
                currentPasswordInput.setCustomValidity('');
                currentPasswordInput.nextElementSibling.textContent = 'Password must be at least 8 characters long.';
            }
        }
    }

    // Add event listeners to each form
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            checkCurrentPassword(); // First check current password
            checkPasswordsMatch(); // Then check if new and confirm passwords match
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                // Trigger the validation UI for each input
                Array.from(form.getElementsByClassName('pass')).forEach(input => {
                    if (!input.checkValidity()) {
                        input.reportValidity();
                    }
                });
            }
            form.classList.add('was-validated');
        }, false);
    });
})();



$(document).ready(function(){

    $('[data-toggle="tooltip"]').tooltip();
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));


    $(document).on('click','#btn-changePassword',function(){
        $('#offCanvasChangePassword').offcanvas('show');
    });


    $(document).on('submit','#frmChangePassword',function(e){
        e.preventDefault();
         var arr = $(this).serialize();
         Swal.fire({
            title: 'Update Password?',
            text: "",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Update it'
        }).then((result) => {
            if (result.isConfirmed) {
                showOverlay('Updating password.')
         axios.post(base_url + "/ibcas/users/changePassword", arr)
            .then(function(response) {
                if(response.data.status == 200){
                    $('#tstsuccess strong').text(response.data.message);
                    toastsuccess.show();
                    axios.get(base_url + "/ibcas/logout")
                    .then(function(response) {
                        // console.log(response);
                        if(response.data.status = 201){
                            showLogoutAlert();   
                    } 
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
                } else {
                    $('#tsterror strong').text(response.data.message);
                    toasterror.show();
                }

            hideOverlay();
            $('#offCanvasChangePassword').offcanvas('hide');
            // console.log(response);
            })
            .catch(function(error) {
                console.log(error);
            });
        } else {
        
        }
    });
    });

    $(document).on('click','#showPassword',function(){
        if($(this).text() == 'Show Password'){
            $('.pass').attr('type','text');
            $(this).text('Hide Password');
        }else{
            $('.pass').attr('type','password');
            $(this).text('Show Password');
        }
    });


});