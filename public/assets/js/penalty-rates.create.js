$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);
    // $('#tstwarning strong').text('Resources loading');
    $('[data-toggle="tooltip"]').tooltip();
    // toastwarning.show();
    showOverlay('Loading resources');
    // toastr.warning('Page will load in a few seconds', 'Loading...', { "timeOut": "500" });
    // setTimeout(function() {
    //     toastr.success('Page loaded successfully', 'Success', { "timeOut": "1000" });
    //   }, 500); // 5000 milliseconds = 5 seconds
    $(":input").inputmask();
    $("#taxyr").inputmask({
        "mask": "9999"
    });
    getTaxYear();
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

    getfees_default();
    // $(".percent").inputmask({
    //     "mask": "99"
    // });


function getTaxYear(){
    axios.get(base_url + "/ibcas/penalty/getTaxYear")
        .then(function(response) {
            var data = response.data.TableContent;
            const select = document.getElementById('taxyr');

            for (let i = 0; i < data.length; i++) {
                // console.log(data[i]);
                const option = document.createElement('option');
                        option.value = data[i].year;
                        // option.dataset.numinst = data[i].numinst;
                        option.textContent = data[i].year;
                        select.appendChild(option);
                }

                
        })
        .catch(function(error) {
            console.log(error);
        });
}


function getfees_default(){
    axios.get(base_url + "/ibcas/penalty-rates/get-fees-default")
        .then(function(response) {
            // console.log(response);
            if (response.data.status == 'yes'){
                toastwarning.hide();
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
                const table = document.getElementById("tbl_new_fees");
                for (let i = 0; i < data.length; i++) {
                    // console.log(data[i]);
                    
                    html += `
                    <tr data-id="${data[i].id}" id="rowid${data[i].id}">
                        <td width="50%">${data[i].remarks}</td>
                        <td width="25%"><input type="number" min="0" value="0" max="100" class="form-control percent penaltypercent${i+1}" data-rowid="${data[i].id}" id="penaltypercent${i+1}" name="penaltypercent${data[i].id}"></td>
                        <td width="25%"><input type="number" min="0" value="0" max="100" class="form-control percent surchargepercent${i+1}" data-rowid="${data[i].id}" id="surchargepercent${i+1}" name="surchargepercent${data[i].id}"></td>
                    </tr>
                    `;
                    
                }
                table.innerHTML = html;
                hideOverlay();
                $('#tstsuccess strong').text('Resources loaded successfuly');
         
                toastsuccess.show();
            } else {
                // toastwarning.hide();
                hideOverlay();
                $('#tsterror strong').text('Resource loading error');
                toasterror.show();
            
            }

        })
        .catch(function(error) {
            console.log(error);
        });
        setTimeout(function(){
            toastsuccess.hide();
            toasterror.hide();
          }, 3000);
}

$(document).on('submit','#ftr_new_penalty',function(e){
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
        confirmButtonText: 'Save'
      }).then((result) => {
        if (result.isConfirmed) {
            
            // toastr.warning('Saving in progress', 'Saving...', { "timeOut": "1000" });
            
            var year = $('#taxyr').val();
            var remarks = $('#remarks').val();
            var table = document.getElementById('tbl_new_fees');
            var arr = [];
            for (let i = 1; i < table.rows.length; i++) {
                var datarowid = $('#penaltypercent'+i).attr('data-rowid');
                arr.push(datarowid);
            }
            //  console.log(arr);
            var formData = new FormData(this);
            formData.append('p_id', '');
            formData.append('year', year);
            formData.append('remarks', remarks);
            formData.append('looparray', arr);
        
            // var arr = $(this).serialize();
            axios.post(base_url + "/ibcas/penalty-rates/save", formData)
                .then(function(response) {
                    // console.log(response.data);
        
                    if (response.data.status == 'dup') {
                        var yrId = response.data.p_id;
                        setTimeout(function() {
                            Swal.fire({
                                title: `${response.data.message}`,
                                text: `Do you want to edit penalties for year ${response.data.year}?`,
                                icon: 'info',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Proceed'
                              }).then((result) => {
                                if (result.isConfirmed) {
                                    var url = base_url + "/ibcas/penalty-rates/edit/"+yrId;
                                      window.open(url, '_blank');
                                }
                              }) 
                          }, 1000);
                          $('#ftr_new_penalty')[0].reset();
                          $('#frm_submit').attr('hidden',false);
                          $('#frm_loading').attr('hidden',true);
                    } else if(response.data.status == 'yes'){
                            $('#tstsuccess strong').text('Penalty rate saved');
                            toastsuccess.show();
                        
                        $('#ftr_new_penalty')[0].reset();
                        setTimeout(function(){
                            toastsuccess.hide();
                            var url = base_url + "/ibcas/penalty-rates/";
                            window.location.href = url;
                          }, 2000);
                          $('#frm_submit').attr('hidden',false);
                          $('#frm_loading').attr('hidden',true);
                    } else {
                        $('#tsterror strong').text('Error saving');
                        toasterror.show();
                        setTimeout(function(){
                            toasterror.hide();
                          }, 2000);
                          $('#frm_submit').attr('hidden',false);
                          $('#frm_loading').attr('hidden',true);
                    }
        
        
        
                })
                .catch(function(error) {
                    console.log(error);
                });
            // console.log(arr);


        } else {
            $('#frm_submit').attr('hidden',false);
                          $('#frm_loading').attr('hidden',true);
        }
      })






});

});