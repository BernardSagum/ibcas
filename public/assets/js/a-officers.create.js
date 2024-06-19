$(document).ready(function(){
  // let timerInterval
  const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
  const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
  const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));

  // $('#tstsuccess strong').text('Success');
  // $('#tstsuccess label').text('Records Found..');
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


  $('#frm_submit').attr('hidden',false);
  $('#frm_loading').attr('hidden',true);

  $(document).on('click','#btn-search-users',function(){
      getUsersTable();
      $('#offcanvasUserLabel').text('Search User');

  });

 
  function getUsersTable(){
      axios.get(base_url + "/ibcas/a-officer/getUsers")
      .then(function(response) {
          console.log(response.data);
          var data = response.data.TableContent;
          $('#tbl_search_users').dataTable().fnDestroy();

          var tblsearchusers = $('#tbl_search_users').DataTable({
              data: data,
              fnCreatedRow: function(nRow, data, iDisplayIndex) {
                  $(nRow).attr('data-username', data.username);
                  $(nRow).attr('data-id', data.id);
                  $(nRow).attr('data-name', `${(data.firstname = null ? '': data.firstname)} ${(data.middlename = null ? '': data.middlename)} ${(data.lastname = null ? '': data.lastname)} ${(data.suffix = 'null' ? '':data.suffix)}`);
              //     $(nRow).attr('data-uniqueid', data.uniqueid);
              },
              columns: [
                 
                  { "data": function(data, type, row, meta) {
                      return `${(data.firstname = null ? '': data.firstname)} ${(data.middlename = null ? '': data.middlename)} ${(data.lastname = null ? '': data.lastname)} ${(data.suffix = 'null' ? '':data.suffix)}`;
                  } },
                  
              ],                
              'dom': '<"wrapper"Bfritp>',
                 'order': [
                     [0, "desc"]
                 ],
          
                 "language": {
                     "emptyTable": " No Record Found"
                 },
                 'paging': false,
                 'ordering': true,
                 'info': false,
                 'searching': false,
                 'paging': true,
                 "pageLength": 10,
          });
      })
      .catch(function(error) {
          console.log(error);
      });
  }

      // Attach a click event handler to the tbody of the table with the id 'tbl_search_accountable'
  $('#tbl_search_users tbody').on('click', 'tr', function () {
  // Check if the clicked row has the class 'selected'
  if ($(this).hasClass('selected')) {
      // If it has the class, remove the class 'selected'
      $(this).removeClass('selected');
  } else {
      $('#tbl_search_users tr.selected').removeClass('selected'); 
      $(this).addClass('selected');
      var id = $(this).attr('data-id');
      var tblName = $(this).attr('data-tblName');
  
          $('#user_id').val(id);
          $('#user_label').val($(this).attr('data-name'));
          $('#o_username').val($(this).attr('data-username'));
      // Trigger a click event on an element with id 'closeoffcanvasRight'
      $('#closeoffcanvasUser').trigger('click');
  }
  });


  $(document).on('submit','#frm_new_officer',function(e){
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
              axios.post(base_url + "/ibcas/a-officers/save-officer", arr)
                  .then(function(response) {
                      console.log(response.data);
                      if (response.data.status == 200){
                          // $('#tstsuccess strong').text(response.data.message);
                          // toastsuccess.show();
                        
                                  $('#tstsuccess strong').text(response.data.message);
                                  toastsuccess.show();
                                  hideOverlay();
                                  setTimeout(function(){
                                          toastsuccess.hide();
                                          var url = base_url + "/ibcas/a-officers/";
                                          window.location.href = url;
                                        }, 2000);
                            
                         
                      } else {
                          $('#tsterro strong').text(response.data.message);
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
