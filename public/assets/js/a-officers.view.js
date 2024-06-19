$(document).ready(function(){
    // let timerInterval
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));

    // $('#tstsuccess strong').text('Success');
    // $('#tstsuccess label').text('Records Found..');

    var id = document.getElementById('off_id').value;
    loadViewData(id);

    function loadViewData(id){
        axios.get(base_url + "/ibcas/a-officers/get-officer-data/"+id)
        .then(function(response) {
            // console.log(response.data.recdata[0].name);
            document.getElementById('accountable_person').innerHTML = response.data.recdata[0].accountable_person;
            document.getElementById('type').innerHTML = response.data.recdata[0].type;
            document.getElementById('username').innerHTML = response.data.recdata[0].username;
          
            document.getElementById('created_by').innerHTML = `${response.data.recdata[0].createdby}`;
            document.getElementById('created_at').innerHTML = `${response.data.recdata[0].created_at}`;
            document.getElementById('updated_by').innerHTML = `${response.data.recdata[0].updatedby == null ? '':response.data.recdata[0].updatedby}`;
            document.getElementById('updated_at').innerHTML = `${response.data.recdata[0].updated_at}`;
          
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
