$(document).ready(function(){
 const par_id = document.getElementById('p_id').value;

 const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
 const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
 const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
 axios.get(base_url + "/ibcas/paticulars/getParticularInfo/"+par_id)
    .then(function(response) {
        if (response.data.status == 200) {
            document.getElementById('par_id').innerHTML = response.data.ParticularInfo[0].id;
            document.getElementById('description').innerHTML = response.data.ParticularInfo[0].particular_name;
            document.getElementById('particular_type').innerHTML = response.data.ParticularInfo[0].parTypeName;
            document.getElementById('code').innerHTML = response.data.ParticularInfo[0].code;
            document.getElementById('print_order').innerHTML = response.data.ParticularInfo[0].print_order;
            document.getElementById('remarks').innerHTML = response.data.ParticularInfo[0].remarks;
            document.getElementById('created_by').innerHTML = response.data.ParticularInfo[0].createdby;
            document.getElementById('created_at').innerHTML = response.data.ParticularInfo[0].created_at;
            document.getElementById('updated_by').innerHTML = response.data.ParticularInfo[0].updatedby;
            document.getElementById('updated_at').innerHTML = response.data.ParticularInfo[0].updated_at;
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