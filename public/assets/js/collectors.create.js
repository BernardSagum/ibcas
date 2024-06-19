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
    $(document).on('click','#btn-search-officers',function(){
        getOfficersTable();
        $('#offcanvasUserLabel').text('Search Accountable Officer');

    });
    function getOfficersTable(){
        axios.get(base_url + "/ibcas/collectors/getofficers")
        .then(function(response) {
            console.log(response.data);
            var data = response.data.TableContent;
            $('#tbl_search_users').dataTable().fnDestroy();

            var tblsearchusers = $('#tbl_search_users').DataTable({
                data: data,
                fnCreatedRow: function(nRow, data, iDisplayIndex) {
                    $(nRow).attr('data-tblName', 'tblsearchofficers');
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
    function getUsersTable(){
        axios.get(base_url + "/ibcas/acc-forms/getUsers")
        .then(function(response) {
            console.log(response.data);
            var data = response.data.TableContent;
            $('#tbl_search_users').dataTable().fnDestroy();

            var tblsearchusers = $('#tbl_search_users').DataTable({
                data: data,
                fnCreatedRow: function(nRow, data, iDisplayIndex) {
                    $(nRow).attr('data-tblName', 'tblsearchusers');
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
        if(tblName == 'tblsearchusers'){
            $('#user_id').val(id);
            $('#user_label').val($(this).attr('data-name'));
        } else if (tblName == 'tblsearchofficers'){
            $('#accountable_officer_id').val(id);
            $('#officerlabel').val($(this).attr('data-name'));
        }
      

        
        // Trigger a click event on an element with id 'closeoffcanvasRight'
        $('#closeoffcanvasUser').trigger('click');
    }
    });


    $(document).on('submit','#frm_new_collector',function(e){
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
                axios.post(base_url + "/ibcas/collectors/save-collector", arr)
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
                                            var url = base_url + "/ibcas/collectors/";
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
    getbarangay();
    function getbarangay() {
        axios.get(base_url + "/ibcas/collectors/barangay")
        .then(function(response) {
            console.log(response.data);
            var data = response.data.TableContent;
            var select = document.getElementById('barangay_id');
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
        })
        .catch(function(error) {
            console.log(error);
        });
    }






    getpositions();
    function getpositions(){
        axios.get(base_url + "/ibcas/collectors/positions")
        .then(function(response) {
            console.log(response.data);
            var data = response.data.TableContent;
            var select = document.getElementById('position_id');
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
        })
        .catch(function(error) {
            console.log(error);
        });
    }

});
