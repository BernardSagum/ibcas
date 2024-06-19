$(document).ready(function(){
    // let timerInterval
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));

    // $('#tstsuccess strong').text('Success');
    // $('#tstsuccess label').text('Records Found..');


    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);
    
    getfunds();
    function getfunds(){

        axios.get(base_url + "/ibcas/acc-forms/get-all-funds/")
        .then(function(response) {
            console.log(response.data);
            if(response.data.status == 'yes'){
                var data = response.data.TableContent;
                var select = document.getElementById('fund_id');
                select.innerHTML = '';
                var opt = document.createElement('option');
                opt.value = '';
                opt.text = 'Select';
                opt.selected = true;
                select.appendChild(opt);
                // Iterate over the data array
                for (var i = 0; i < data.length; i++) {
                    // Create an option element
                    var option = document.createElement('option');
                    
                    // Set the value and text of the option
                    option.value = data[i].id;
                    option.text = data[i].name;
                    
                    // Append the option to the select element
                    select.appendChild(option);
                }
            }
        })
        .catch(function(error) {
            console.log(error);
        });


        // var select = document.getElementById('form_id');
    }





    var id = document.getElementById('acc_id').value;
    loadViewData(id);

    function loadViewData(id){
        axios.get(base_url + "/ibcas/acc-forms/get_acc_data/"+id)
        .then(function(response) {
            // console.log(response.data.recdata[0].name);
            document.getElementById('frmtype').innerHTML = response.data.recdata[0].form_name;
            document.getElementById('fund_id').value = response.data.recdata[0].fund_id;
            document.getElementById('stub_no').value = response.data.recdata[0].stub_no;
            document.getElementById('from').value = response.data.recdata[0].from;
            document.getElementById('to').value = response.data.recdata[0].to;
            document.getElementById('vfrom').value = response.data.recdata[0].from;
            document.getElementById('vto').value = response.data.recdata[0].to;
        

        })
        .catch(function(error) {
            console.log(error);
        });
    }


    $(document).on('submit','#frm_void',function(e){
        e.preventDefault();
        $('#frm_submit').attr('hidden',true);
        $('#frm_loading').attr('hidden',false);    
        var arr = $(this).serialize();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Save'
          }).then((result) => {
            if (result.isConfirmed) {
                $('#frm_submit').attr('hidden',false);
                $('#frm_loading').attr('hidden',true);  
                showOverlay('Saving....');
                axios.post(base_url + "/ibcas/acc-forms/void-form", arr)
                    .then(function(response) {
                        console.log(response.data);
                        if (response.data.status == 200){
                            setTimeout(function(){
                           hideOverlay();
                           $('#tstsuccess strong').text(response.data.message);
                           toastsuccess.show();
                           $('#frm_void')[0].reset();
                        }, 5000);
                        } else {
                            $('#tsterror strong').text(response.data.message);
                            toasterror.show();
                        }

                    })
                    .catch(function(error) {
                        console.log(error);
                    });

            } else if (result.dismiss)  {
                $('#frm_submit').attr('hidden',false);
                $('#frm_loading').attr('hidden',true);
            }
        })

    });





});
