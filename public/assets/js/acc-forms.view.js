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
        axios.get(base_url + "/ibcas/acc-forms/get_acc_data/"+id)
        .then(function(response) {
            // console.log(response.data.recdata[0].name);
            document.getElementById('fund_name').innerHTML = response.data.recdata[0].name;
            document.getElementById('Form_num').innerHTML = `${response.data.recdata[0].print_label}  (${response.data.recdata[0].form_no})`;
            document.getElementById('stub_num').innerHTML = response.data.recdata[0].stub_no;
            document.getElementById('recipt_ser').innerHTML = `${response.data.recdata[0].from} - ${response.data.recdata[0].to}`;
            document.getElementById('total_recipt').innerHTML = `${(response.data.recdata[0].to - response.data.recdata[0].from) + 1}`;
            document.getElementById('dt_delivered').innerHTML = `${dateformat(response.data.recdata[0].date_delivered)}`;
            document.getElementById('created_by').innerHTML = `${response.data.recdata[0].created_by}`;
            document.getElementById('created_at').innerHTML = `${response.data.recdata[0].created_at}`;
            document.getElementById('updated_by').innerHTML = `${response.data.recdata[0].updated_by == null ? '':response.data.recdata[0].updated_by}`;
            document.getElementById('updated_at').innerHTML = `${response.data.recdata[0].updated_at}`;
            document.getElementById('assigned_to').innerHTML = `${response.data.recdata[0].assigned_to == null ? 'Unassigned': response.data.recdata[0].assigned_to}`;
            document.getElementById('date_assigned').innerHTML = `${response.data.recdata[0].assigned_to == null ? 'Unassigned': dateformat(response.data.recdata[0].date_issued)}`;
            if (response.data.recdata[0].assigned_to != null) {
                document.getElementById('receipt_series').innerHTML = `${response.data.recdata[0].from} - ${response.data.recdata[0].to}`;
            } else {
                document.getElementById('receipt_series').innerHTML = '';
            }
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
