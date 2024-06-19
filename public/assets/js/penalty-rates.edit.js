let p_id = '';
$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
    showOverlay('Loading data');
    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);


    (function() {
        'use strict';
        window.addEventListener('load', function() {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');
          
          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
    // toastr.warning('Page will load in a few seconds', 'Loading...', { "timeOut": "1000" });
    setTimeout(function() {

        p_id = $('#p_id').val();

        axios.get(base_url + "/ibcas/penalty-rates/get-edit-info/"+p_id)
        .then(function(response) {
            hideOverlay();
            // console.log(response);
            if(response.data.status == 'yes'){
                $('#taxyr').val(response.data.year);
                $('#remarks').val(response.data.remarks);
                var fees = response.data.fees;
    
                for (let i = 0; i < fees.length; i++) {
                    $('#penaltypercent'+fees[i].fees_default_id).val(`${fees[i].percent}`);
                    $('#surchargepercent'+fees[i].fees_default_id).val(`${fees[i].surcharge}`);
                    $('#f_id'+fees[i].fees_default_id).val(`${fees[i].id}`);
                    // console.log(`${fees[i].fees_default_id}`);
                }
    
                $('#tstsuccess').text('Page loaded successfuly');
                // $('#tstsuccess label').text('');
                toastsuccess.show();
                setTimeout(function() {
                    toastsuccess.hide();
                     }, 3000);
            } else{
                $('#tstwarning').text('No data found');
                // $('#tstsuccess label').text('');
                toastwarning.show();
                setTimeout(function() {
                    toastwarning.hide();
                     }, 3000);
            }

          // console.log(response.data.fees);
        })
        .catch(function(error) {
            console.log(error);
        });
        // toastr.success('Page loaded successfully', 'Success', { "timeOut": "1000" });
      }, 1500); // 5000 milliseconds = 5 seconds

    $(":input").inputmask();
    $("#taxyr").inputmask({
        "mask": "9999"
    });
    
    getfees_default();

function getfees_default(){
    axios.get(base_url + "/ibcas/penalty-rates/get-fees-default")
        .then(function(response) {
            var html ="";
            html +=`
            <tr>
                <td width="50%">FEES</td>
                <td width="25%">PENALTY (%)</td>
                <td width="25%">SURCHARGE (%)</td>
            </tr>
            `;
            // console.log(response.data);
            var data = response.data.TableContent.penalties;
            // console.log(data)
            const table = document.getElementById("tbl_edit_fees");
            for (let i = 0; i < data.length; i++) {
                // console.log(data[i]);
                
                html += `
                <tr data-id="${data[i].id}" id="rowid${data[i].id}">
                    <td width="50%">${data[i].remarks}
                    <input type="hidden" class="form-control f_id${i+1}" data-rowid="${data[i].id}" id="f_id${data[i].id}" name="f_id${data[i].id}">
                    </td>
                    <td width="25%"><input type="number" min="0" value="0" max="100" class="form-control percent penaltypercent${i+1}" data-rowid="${data[i].id}" id="penaltypercent${data[i].id}" name="penaltypercent${data[i].id}"></td>
                    <td width="25%"><input type="number" min="0" value="0" max="100" class="form-control percent surchargepercent${i+1}" data-rowid="${data[i].id}" id="surchargepercent${data[i].id}" name="surchargepercent${data[i].id}"></td>
                </tr>
                `;
                
            }
            table.innerHTML = html;
        })
        .catch(function(error) {
            console.log(error);
        });
}

$(document).on('submit','#ftr_edit_penalty',function(e){
    e.preventDefault();
    $('#frm_submit').attr('hidden',true);
    $('#frm_loading').attr('hidden',false);
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this",
        icon: 'question',
       
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Update'
      }).then((result) => {
        if (result.isConfirmed) {
            // toastr.warning('Updating in progress', 'Updating...', { "timeOut": "1000" });

            var year = $('#taxyr').val();
            var remarks = $('#remarks').val();
            var p_id = $('#p_id').val();
            var table1 = document.getElementById('tbl_edit_fees');
            var arr = [];
            for (let i = 1; i < table1.rows.length; i++) {
                // console.log($('.f_id'+i).attr('data-rowid'));
                var datarowid = $('.f_id'+i).attr('data-rowid')
                arr.push(datarowid);
            }

            var formData = new FormData(this)
            
            formData.append('p_id', p_id);
            formData.append('year', year);
            formData.append('remarks', remarks);
            formData.append('looparray', arr);
            axios.post(base_url + "/ibcas/penalty-rates/save", formData)
            .then(function(response) {
                console.log(response);
                if (response.data.status == 'yes') {
                    $('#frm_submit').attr('hidden',false);
                    $('#frm_loading').attr('hidden',true);
                    $('#tstsuccess').text('Updated');
                    // $('#tstsuccess label').text('');
                    toastsuccess.show();
                    setTimeout(function() {
                        toastsuccess.hide();
                        var url = base_url + "/ibcas/penalty-rates/";
                        window.location.href = url;
                         }, 3000);
                } else {
                    $('#frm_submit').attr('hidden',false);
                    $('#frm_loading').attr('hidden',true);
                    $('#tstwarning').text('Error saving');
                    // $('#tstsuccess label').text('');
                    toastwarning.show();
                    setTimeout(function() {
                        toastwarning.hide();
                         }, 3000);
                }


            })
            .catch(function(error) {
                console.log(error);
            });
            // console.log(formData);
        } else {
            $('#frm_submit').attr('hidden',false);
            $('#frm_loading').attr('hidden',true);
        }
    })

});



});