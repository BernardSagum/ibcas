$(document).ready(function(){
 const acc_id = document.getElementById('p_id').value;

 const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
 const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
 const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  



 axios.get(base_url + "/ibcas/cAccounts/getAccountInfo/"+acc_id)
    .then(function(response) {
        if (response.data.status == 200) {
            document.getElementById('effectivity_year').innerHTML = response.data.AccountInfo[0].effectivity_year;
            document.getElementById('code').innerHTML = response.data.AccountInfo[0].code;
            document.getElementById('title').innerHTML = response.data.AccountInfo[0].title;
            document.getElementById('acronym').innerHTML = response.data.AccountInfo[0].acronym;
            document.getElementById('account_type').innerHTML = response.data.AccountInfo[0].account_type;
            document.getElementById('account_nature').innerHTML = (response.data.AccountInfo[0].account_nature == '1') ?  'DEBIT' : 'CREDIT';
            document.getElementById('remarks').innerHTML = response.data.AccountInfo[0].remarks;
            document.getElementById('created_by').innerHTML = response.data.AccountInfo[0].createdby;
            document.getElementById('created_at').innerHTML = response.data.AccountInfo[0].created_at;
            document.getElementById('updated_by').innerHTML = response.data.AccountInfo[0].updatedby;
            document.getElementById('updated_at').innerHTML = response.data.AccountInfo[0].updated_at;
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


});