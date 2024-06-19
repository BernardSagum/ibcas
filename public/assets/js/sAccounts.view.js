$(document).ready(function(){

    $('[data-toggle="tooltip"]').tooltip();
  const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
  const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
  const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
  var id = document.getElementById('sub_ID').value;

  getSubAccountDetails(id);


  function getSubAccountDetails(id){
    showOverlay('Loading Sub-account information');
    axios.get(base_url + "/ibcas/SAccounts/getSubAccountInfo/"+id)
    .then(function(response) {
        if (response.data.status == 200) {
     
            // console.log(response.data);
            document.getElementById('effectivity_year').innerHTML = response.data.SubAccountInfo[0].effectivity_year;
            document.getElementById('pcode').innerHTML = response.data.SubAccountInfo[0].acc_code;
            document.getElementById('pdesc').innerHTML = response.data.SubAccountInfo[0].acc_desc;
            document.getElementById('sub_code').innerHTML = response.data.SubAccountInfo[0].sub_code;
            document.getElementById('sub_desc').innerHTML = response.data.SubAccountInfo[0].sub_desc;
            document.getElementById('particular_types').innerHTML = response.data.SubAccountInfo[0].sub_type;
            document.getElementById('remarks').innerHTML = response.data.SubAccountInfo[0].remarks;
            document.getElementById('created_by').innerHTML = response.data.SubAccountInfo[0].createdby;
            document.getElementById('created_at').innerHTML = response.data.SubAccountInfo[0].created_at;
            document.getElementById('updated_by').innerHTML = response.data.SubAccountInfo[0].updatedby;
            document.getElementById('updated_at').innerHTML = response.data.SubAccountInfo[0].updated_at;
                axios.get(base_url + "/ibcas/SAccounts/getSubAccountParticulars/"+id)
                .then(function(response) {
                    if (response.data.status == 200) {
                        // console.log(response.data.SubAccountPartClass);
                        var arr = response.data.SubAccountPartClass
                            
                        var $tableBody = $('#dynamic-table');

                        if (arr.length === 0) {
                            $tableBody.append('<tr><td colspan="5">No Particular</td></tr>');
                        } else {
                            arr.forEach(function(item, index) {
                                var status = '';
                                    if (item.new === 'Y') {
                                        status = 'New';
                                    } else if (item.renewal === 'Y') {
                                        status = 'Renewal';
                                    } else if (item.closure === 'Y') {
                                        status = 'Closure';
                                    }
                                var row = `
                                    <tr>
                                        <td>${item.particular_id}</td>
                                        <td>${item.classifications}</td>
                                        <td>${status}</td>
                                    </tr>
                                `;
                                $tableBody.append(row);
                            });
                        }
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
            hideOverlay();
            $('#tstsuccess strong').text(response.data.message);
            toastsuccess.show(2000);
        }
    })
    .catch(function(error) {
        console.log(error);
    });
}



















});
