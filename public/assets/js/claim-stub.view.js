$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
   
    var p_id = $('#p_id').val();
    setTimeout(function() {
    getClaimstubData(p_id);
    }, 1000); // 5000 milliseconds = 5 seconds  



    function getClaimstubData(p_id){
        axios.get(base_url + "/ibcas/claim-stub/get-view-info/"+p_id)
        .then(function(response) {
            // console.log(response.data.dtClaim);
            if(response.data.status == 'yes'){
                $('#application_type_id').text(response.data.dtClaim[0].app_type_name);
                $('#tax_effectivity_year').text(response.data.dtClaim[0].tax_effectivity_year);
                $('#first_quarter_date').text(response.data.dtClaim[0].first_quarter_date);
                $('#first_quarter_peak_days').text(response.data.dtClaim[0].first_quarter_peak_days);
                $('#second_quarter_date').text(response.data.dtClaim[0].second_quarter_date);
                $('#second_quarter_peak_days').text(response.data.dtClaim[0].second_quarter_peak_days);
                $('#third_quarter_date').text(response.data.dtClaim[0].third_quarter_date);
                $('#third_quarter_peak_days').text(response.data.dtClaim[0].third_quarter_peak_days);
                $('#fourth_quarter_date').text(response.data.dtClaim[0].fourth_quarter_date);
                $('#fourth_quarter_peak_days').text(response.data.dtClaim[0].fourth_quarter_peak_days);
                $('#nonpeak_days').text(response.data.dtClaim[0].nonpeak_days);
                $('#remarks').text(response.data.dtClaim[0].remarks);
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