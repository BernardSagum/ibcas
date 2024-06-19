$(document).ready(function(){
    // let timerInterval
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));

    // $('#tstsuccess strong').text('Success');
    // $('#tstsuccess label').text('Records Found..');

    var id = document.getElementById('p_id').value;
    loadViewData(id);

    function loadViewData(id){
        axios.get(base_url + "/ibcas/collectors/get-view-data/"+id)
        .then(function(response) {
            // console.log(response.data.TableContent[0].user_id);
            document.getElementById('id_num').innerHTML = response.data.TableContent[0].id;
            document.getElementById('collectorname').innerHTML = response.data.TableContent[0].collectorname;
            document.getElementById('contact_number').innerHTML = response.data.TableContent[0].contact_number;
            document.getElementById('email').innerHTML = response.data.TableContent[0].email;
            document.getElementById('tin_no').innerHTML = response.data.TableContent[0].tin_no;
            document.getElementById('accountable_person').innerHTML = response.data.TableContent[0].accountable_person;
            document.getElementById('position').innerHTML = response.data.TableContent[0].position;
            document.getElementById('created_by').innerHTML = response.data.TableContent[0].createdby;
            document.getElementById('created_at').innerHTML = response.data.TableContent[0].created_at;
            document.getElementById('updated_by').innerHTML = response.data.TableContent[0].updatedby;
            document.getElementById('updated_at').innerHTML = response.data.TableContent[0].updated_at;
        })
        .catch(function(error) {
            console.log(error);
        });
    }

    function dateformat(bdate){
        // var bdate = "1994-01-12";
        if(bdate == null){
            return "-";
        } else {
      
        var spldate = bdate.split('-');
    
        var yr = spldate[0];
        var mnt = spldate[1];
        var dy = spldate[2];
    
        return mnt+"/"+dy+"/"+yr;
       
        }
        
    }


});
