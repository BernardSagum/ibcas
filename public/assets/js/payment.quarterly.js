$(document).ready(function(){
    // let timerInterval
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
    $('[data-toggle="tooltip"]').tooltip();
    $('#tstsuccess strong').text('Success');
    $('#tstsuccess label').text('Records Found..');


    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);

    loadmodeofpayment();
    function loadmodeofpayment(){
        axios.get(base_url + "/ibcas/payment/getmodeofpayment")
        .then(function(response) {
            var data = response.data.TableContent;
            const select = document.getElementById('mode_of_payment');

            

            for (let i = 0; i < data.length; i++) {
                // console.log(data[i]);
                const option = document.createElement('option');
                        option.value = data[i].id;
                        option.textContent = data[i].description;
                        select.appendChild(option);
                }


        })
        .catch(function(error) {
            console.log(error);
        });
    }



    

});
