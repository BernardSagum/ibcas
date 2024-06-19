$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));  
    getParticularType();
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



      $(document).on('submit', '#ftr-cAccounts', function(e) {
        e.preventDefault();
        var arr = $(this).serialize();
        showOverlay('Searching...');
        // Show processing indicator  
        var table = $('#chartAccounts-table').DataTable({
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
    
        axios.post(base_url + "/ibcas/cAccounts/ftr-accounts", arr)
            .then(function(response) {
              
                var data = response.data.TableContent;
                hideOverlay();
                // Destroy the existing DataTable instance
                table.destroy();
    
                // Initialize DataTable with the fetched data
                table = $('#chartAccounts-table').DataTable({
                    data: data,
                    fnCreatedRow: function(nRow, data, iDisplayIndex) {
                        $(nRow).attr('data-id', data.id);
                        // $(nRow).attr('data-particular_type_id', data.particular_type_id);
                    },
                    columns: [
                        { data: 'effectivity_year' },
                        { data: 'code' },
                        { data: 'title' },
                        { data: 'account_type' },
                        {
                            "data": function(data, type, row, meta) {
                                return `
                                    <a data-toggle="tooltip" title="View account" href="${base_url}/ibcas/cAccounts/view/${data.id}" target="_blank" class="btn btn-sm btn-soft-primary btn-view-details"><i class="mdi mdi-eye-outline"></i></a>
                                    <a data-toggle="tooltip" title="Update account" href="${base_url}/ibcas/cAccounts/edit/${data.id}" target="_blank" class="btn btn-sm btn-soft-info btn-edit"><i class="mdi mdi-pencil-outline"></i></a>
                                    <a data-toggle="tooltip" title="Delete account" href="javascript:void(0);" class="btn btn-sm btn-soft-danger btn-delete delete" data-id="${data.id}"><i class="mdi mdi-delete-outline"></i></a>
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
    

   $(document).on('change','#ftr_selby',function(){
    var selBy = $(this).val();

    if (selBy == 'parName') {
        $('#selTypeDiv').attr('hidden',true);
        // $('#ftrValDiv').removeAttr('hidden');
        $('#ftrValDiv').attr('hidden',false);
        $('#ftr_type').val('');
        $('#ftr_val').attr('required',true);
        $('#ftr_type').removeAttr('required');
    } else if (selBy == 'parType') {
        $('#selTypeDiv').removeAttr('hidden');
        $('#ftr_type').attr('required',true);
        $('#ftrValDiv').attr('hidden',true);
        $('#ftr_val').val('');
        $('#ftr_val').removeAttr('required');
    }
       


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
            axios.get(base_url + "/ibcas/cAccounts/deleteAccount/"+id)
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



   function getParticularType() {

    axios.get(base_url + "/ibcas/paticulars/get-partType")
    .then(function(response) {
      var data = response.data.selectOptions;
      const select = document.getElementById('ftr_type');

      for (let i = 0; i < data.length; i++) {
          // console.log(data[i]);
          const option = document.createElement('option');
                  option.value = data[i].id;
                 
                  option.textContent = data[i].name;
                  select.appendChild(option);
          }
    })
    .catch(function(error) {
        console.log(error);
    });
    
  }



});