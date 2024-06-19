$(document).ready(function(){
    const bankId = document.getElementById('bankId').value;
   
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  

    axios.get(base_url + "/ibcas/banks/getBankDetails/"+bankId)
    .then(function(response) {
        if (response.data.status == 200) {
            document.getElementById('bankName').innerHTML = response.data.BankInfo[0].name + "("+response.data.BankInfo[0].shortname+")";
            // document.getElementById('alias').innerHTML = response.data.BankInfo[0].shortname;
            document.getElementById('branch').innerHTML = response.data.BankInfo[0].branch;
            document.getElementById('email').innerHTML = response.data.BankInfo[0].email;
            document.getElementById('contact_person').innerHTML = response.data.BankInfo[0].contact_person;
            document.getElementById('contact_number').innerHTML = response.data.BankInfo[0].contactno;
            document.getElementById('remarks').innerHTML = response.data.BankInfo[0].remarks;
            document.getElementById('created_by').innerHTML = response.data.BankInfo[0].createdby;
            document.getElementById('created_at').innerHTML = response.data.BankInfo[0].created_at;
            document.getElementById('updated_by').innerHTML = response.data.BankInfo[0].updatedby;
            document.getElementById('updated_at').innerHTML = response.data.BankInfo[0].updated_at;
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