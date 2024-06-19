$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
   
    var msc_id = $('#msc_id').val();
    setTimeout(function() {
    getMscFeeDetails(msc_id);
    }, 1000); // 5000 milliseconds = 5 seconds  



    function getMscFeeDetails(msc_id){
        axios.get(base_url + "/ibcas/miscellaneous/get-view-info/"+msc_id)
        .then(function(response) {
            // console.log(response.data.dtClaim);
            if(response.data.status == 'yes'){
                $('#msc_name').text(response.data.dtClaim[0].name);
                $('#msc_amount').text(response.data.dtClaim[0].amount);

                // $('#remarks').text(response.data.dtClaim[0].remarks);
                $('#created_by').text(response.data.dtClaim[0].created_by);
                $('#created_at').text(response.data.dtClaim[0].created_at);
                $('#updated_by').text(response.data.dtClaim[0].updated_by);
                $('#updated_at').text(response.data.dtClaim[0].updated_at);


                $('#tstsuccess strong').text('Claimstub schedule loaded');
                toastsuccess.show();
            } else {

            }
        })
        .catch(function(error) {
            console.log(error);
        });
    }
    




});