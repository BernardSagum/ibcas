let p_id = '';
$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));

    // $('#tstwarning strong').text('Page loading...');
    // // $('#tstwarning label').text('Page Loading');

    // toastwarning.show();
    
    // toastr.warning('Page will load in a few seconds', 'Loading...', { "timeOut": "1000" });
    showOverlay('Loading data');
    setTimeout(function() {

        p_id = $('#p_id').val();

        axios.get(base_url + "/ibcas/penalty-rates/get-edit-info/"+p_id)
        .then(function(response) {
            console.log(response);
            if(response.data.status == 'yes'){
                hideOverlay();
                $('#taxyr').text(response.data.year);
                $('#remarks').text(response.data.remarks);
                var fees = response.data.fees;
    
                for (let i = 0; i < fees.length; i++) {
                    $('#penaltypercent'+fees[i].fees_default_id).text(`${fees[i].percent}`);
                    $('#surchargepercent'+fees[i].fees_default_id).text(`${fees[i].surcharge}`);
                    $('#f_id'+fees[i].fees_default_id).val(`${fees[i].id}`);
                    // console.log(`${fees[i].fees_default_id}`);
                }
                toastwarning.hide();
                $('#tstsuccess strong').text('Page loaded successfuly');
                // $('#tstsuccess label').text('');
                toastsuccess.show();
                setTimeout(function() {
                toastsuccess.hide();
                 }, 800); // 5000 milliseconds = 5 seconds
            } else {
                $('#tstwarning strong').text('No data found');
                // $('#tsterror label').text('');
            
                toastwarning.show();
                hideOverlay()
            }

          // console.log(response.data.fees);
        })
        .catch(function(error) {
            console.log(error);
        });
        


      }, 1000); // 5000 milliseconds = 5 seconds

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
                <tr  data-id="${data[i].id}" id="rowid${data[i].id}">
                    <td width="50%">${data[i].remarks}
                   </td>
                    <td width="25%" style="font-weight : bold;" id="penaltypercent${data[i].id}"><span class="placeholder col-3"></span></td>
                    <td width="25%" style="font-weight : bold;" id="surchargepercent${data[i].id}"><span class="placeholder col-3"></span></td>
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
    Swal.fire({
        title: 'Please Confirm Updating?',
        text: "Are you sure?",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!'
      }).then((result) => {
        if (result.isConfirmed) {
            toastr.warning('Updating in progress', 'Updating...', { "timeOut": "1000" });

            var year = $('#taxyr').val();
            var remarks = $('#remarks').val();
            var p_id = $('#p_id').val();
            var table1 = document.getElementById('tbl_edit_fees');
            var arr = [];
            for (let i = 1; i < table1.rows.length; i++) {
                console.log($('.f_id'+i).attr('data-rowid'));
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
            })
            .catch(function(error) {
                console.log(error);
            });
            console.log(formData);
        }
    })

});



});