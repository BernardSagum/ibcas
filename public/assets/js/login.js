$(document).ready(function(){
// alert('test')
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
    
    
    // toastsuccess.show();
    // toasterror.show();



    function displayAlert() {
        const alertElement = document.getElementById('loginAlert');
        alertElement.classList.remove('d-none');
    
        // Hide the alert after 5 seconds
        setTimeout(() => {
            alertElement.classList.add('d-none');
            setTimeout(() => {
               
                var url = base_url + "/ibcas/users/profile";
                    window.location.href = url;
            }, 900);
          
        }, 3000); // 5000 milliseconds = 5 seconds
    }
    function displayFailAlert() {
        const failAlert = document.getElementById('loginFailAlert');
        failAlert.classList.remove('d-none');
    
        setTimeout(() => {
            failAlert.classList.add('d-none');
        }, 2000);
    }
    
    // Call this function when you want to display the alert, such as after a login attempt
    // displayAlert();
    
$(document).on('submit','#frm_login',function(e){
    e.preventDefault();
    $('#btnsubmit').attr('hidden',true);
    $('#frm_loading').attr('hidden',false);
    var arr = $(this).serialize();
    // console.log(arr);
    axios.post(base_url + "/ibcas/userlogin", arr)
    .then(function(response) {
        console.log(response.data);

         if(response.data.status == '200'){
                // $('#tstsuccess strong').text(response.data.message);
                // toastsuccess.show();

            
                displayAlert();
                
              
        } else {
            // $('#tsterror strong').text(response.data.message);
            // toasterror.show();
            displayFailAlert();
            // setTimeout(function(){
            //     toasterror.hide();
            //   }, 2000);
        }

        $('#btnsubmit').attr('hidden',false);
        $('#frm_loading').attr('hidden',true);

    })
    .catch(function(error) {
        console.log(error);
    });

});




});