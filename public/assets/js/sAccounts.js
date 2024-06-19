$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
    // getParticularType();
    $(":input").inputmask();
    $("#ftr_val").inputmask({
        "mask": "9999"
    });
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

      $(document).on('change','#ftr_selby',function(){
        var ftrBy = $(this).val();
        if (ftrBy == 'effYear') {
            $('#ftrValDiv').attr('hidden',false);
            // $('#ftr_val').attr('hidden',false);
            $("#ftr_val").inputmask({
                "mask": "9999"
            });  



        }else if(ftrBy == 'accCodeDesc') {
            $('#ftrValDiv').attr('hidden',false);
            $("#ftr_val").inputmask("remove");
        } else {
            
        }
      });



      $(document).on('submit','#ftr-sAccounts',function(e){
        e.preventDefault();

        var arr = $(this).serialize();
        
        showOverlay('Searching...');
        // Show processing indicator  
        var table = $('#chartSubAccounts-table').DataTable({
            processing: true,
            serverSide: false,  // Disable server-side mode since we're handling it manually
            'dom': '<"wrapper"Bfritp>',
            'order': [[0, "desc"]],
            'language': {
                'emptyTable': "Loading..."
            },
            'paging': false,
            'ordering': true,
            'info': false,
            'searching': false,
            'pageLength': 10,
            destroy: true,  // Destroy the table if it exists
        });

        axios.post(base_url + "/ibcas/sAccounts/ftr_subAccounts", arr)
            .then(function(response) {
                var data = response.data.TableContent;
                hideOverlay();
                // Destroy the existing DataTable instance
                table.destroy();
    
                // Initialize DataTable with the fetched data
                table = $('#chartSubAccounts-table').DataTable({
                    data: data,
                    fnCreatedRow: function(nRow, data, iDisplayIndex) {
                        $(nRow).attr('data-id', data.sub_id);
                        $(nRow).attr('data-acc_id', data.acc_id);
                        // $(nRow).attr('data-particular_type_id', data.particular_type_id);
                    },
                    columns: [
                        { data: 'effectivity_year' },
                        { data: 'acc_code' },
                        { data: 'acc_desc' },
                        { data: 'sub_code' },
                        { data: 'sub_desc' },
                        {
                            "data": function(data, type, row, meta) {
                                return `
                                    <a data-toggle="tooltip" title="View sub-account" href="${base_url}/ibcas/sAccounts/view/${data.sub_id}" target="_blank" class="btn btn-sm btn-soft-primary btn-view-details"><i class="mdi mdi-eye-outline"></i></a>
                                    <a data-toggle="tooltip" title="Update sub-account" href="${base_url}/ibcas/sAccounts/edit/${data.sub_id}" target="_blank" class="btn btn-sm btn-soft-info btn-edit"><i class="mdi mdi-pencil-outline"></i></a>
                                    <a data-toggle="tooltip" title="Delete account" href="javascript:void(0);" class="btn btn-sm btn-soft-danger btn-delete delete" data-id="${data.sub_id}"><i class="mdi mdi-delete-outline"></i></a>
                                `;
                            }
                        }
                    ],
                    'dom': '<"wrapper"Bfritp>',
                    'order': [
                        [0, "desc"]
                    ],
                    "language": {
                        "emptyTable": "No Record Found"
                    },
                    'paging': true,
                    'ordering': true,
                    'info': false,
                    'searching': false,
                    "pageLength": 10,
                });
                
                
                $('[data-toggle="tooltip"]').tooltip();
            })
            .catch(function(error) {
                console.log(error);
            });


      });


      $(document).on('click','.btn-delete',function(){
        var id  = $(this).attr('data-id');
        Swal.fire({
            title: 'Delete Account?',
          text: "",
          icon: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Delete it'
            }).then((result) => {
              if (result.isConfirmed) {
                showOverlay('Deleting...')
                axios.get(base_url + "/ibcas/sAccounts/deleteSubAccount/"+id)
                .then(function(response) {
                    
                    if (response.data.status == 200) {
                      $('#tstsuccess strong').text(response.data.message);
                    toastsuccess.show();
                    $('#ftr-cAccounts').trigger('submit');
                    setTimeout(function(){
                      toastsuccess.hide();
                      
                    }, 2000);
                    } else {
                      $('#tsterror strong').text(response.data.message);
                    toasterror.show();
                    }
                    hideOverlay();
                })
                .catch(function(error) {
                    console.log(error);
                });
        } else {
          // $('#frm_submit').attr('hidden',false);
          // $('#frm_loading').attr('hidden',true);
      }
    })
       
    
    
    
    
        
      
       });




});