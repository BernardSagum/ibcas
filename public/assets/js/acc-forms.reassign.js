$(document).ready(function(){
    // let timerInterval
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));

    // $('#tstsuccess strong').text('Success');
    // $('#tstsuccess label').text('Records Found..');


    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);
    getforms();
    function getforms(){

        axios.get(base_url + "/ibcas/acc-forms/get-all-forms/")
        .then(function(response) {
            console.log(response.data);
            if(response.data.status == 'yes'){
                var data = response.data.TableContent;
                var select = document.getElementById('form_id');
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
                    option.text = data[i].form_no;
                    
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
            console.log(response.data.recdata[0]);
            document.getElementById('form_id').value = response.data.recdata[0].form_id;
            document.getElementById('fund_id').value = response.data.recdata[0].fund_id;
            document.getElementById('stub_no').value = response.data.recdata[0].stub_no;
            document.getElementById('from').value = response.data.FromToData[0].from;
            document.getElementById('to').value = response.data.FromToData[0].to;
            document.getElementById('assign_id').value = response.data.recdata[0].id;
            document.getElementById('assigned_receipts_id').value = response.data.recdata[0].assigned_receipts_id;
            // document.getElementById('assign_id').value = response.data.recdata[0].id;
            document.getElementById('acc_officer_label').value = response.data.recdata[0].accountable_officer_name;
            document.getElementById('acc_officer').value = response.data.recdata[0].accountable_form_officer_id;
            document.getElementById('assign').value = response.data.recdata[0].assigned_to_id;
            document.getElementById('assign_label').value = response.data.recdata[0].assigned_to;
        

        })
        .catch(function(error) {
            console.log(error);
        });
    }
    
    // $(document).on('#btn-search-assign','click',function(){
       
    // });
    loadUsersdataTable();
    
    $('#tbl_search_accountable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
           
        } else {
            $('#tbl_search_accountable tr.selected').removeClass('selected');
            $(this).addClass('selected');
            var id  = $(this).attr('data-id');
            $('#assign').val($(this).attr('data-id'));
            $('#assign_label').val($(this).attr('data-name'));
            // console.log(id);
            $('#closeoffcanvasRight').trigger('click');
        }

    });




    function loadUsersdataTable(){

        axios.get(base_url + "/ibcas/acc-forms/getUsers")
        .then(function(response) {
            console.log(response.data);
            var data = response.data.TableContent;
            $('#acc-tbl_search_accountable').dataTable().fnDestroy();

            var tbl_search_accountable = $('#tbl_search_accountable').DataTable({
                data: data,
                fnCreatedRow: function(nRow, data, iDisplayIndex) {
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

        // $('#tbl_search_accountable').DataTable({
        //     // 'url':base_url + "/ibcas/acc-forms/getUsers",
        //     'ajax': {
        //         "type": "GET",
        //         "url": base_url + "/ibcas/acc-forms/getUsers",
        //         "dataSrc": ""
        //       },
        //     fnCreatedRow: function(nRow, data, iDisplayIndex) {
        //         $(nRow).attr('data-id', data.id);
        //     //     $(nRow).attr('data-uniqueid', data.uniqueid);
        //     },
        //     columns: [
               
        //         {
        //             "data": function(data, type, row, meta) {
        //                 return `${data.from} - ${data.to}`;
        //             }
        //         },
             
        //     ],                
        //     'dom': '<"wrapper"Bfritp>',
        //        'order': [
        //            [0, "desc"]
        //        ],
        
        //        "language": {
        //            "emptyTable": " No Record Found"
        //        },
        //        'paging': false,
        //        'ordering': true,
        //        'info': false,
        //        'searching': false,
        //        'paging': true,
        //        "pageLength": 10,
        // });
    }




    $(document).on('submit','#ftr_assign_form',function(e){
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

                axios.post(base_url + "/ibcas/acc-forms/reassign-form", arr)
                    .then(function(response) {
                        // console.log(response.data);
                        if (response.data.status == 200){
                            showOverlay('Reassigning OR numbers....')
                            axios.post(base_url + "/ibcas/acc-forms/reassign-series", arr)
                            .then(function(response) {
                                if (response.data.status == 200){
                                    $('#tstsuccess strong').text(response.data.message);
                                    toastsuccess.show();
                                    hideOverlay();
                                    setTimeout(function(){
                                            toastsuccess.hide();
                                            var url = base_url + "/ibcas/acc-forms/";
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
                            // $('#tstsuccess strong').text(response.data.message);
                            // toastsuccess.show();
                            // $('#ftr_assign_form')[0].reset();
                            // setTimeout(function(){
                            //     toastsuccess.hide();
                            //     var url = base_url + "/ibcas/acc-forms/";
                            //     window.location.href = url;
                            //   }, 2000);
                        } else {
                            $('#tsterro strong').text(response.data.message);
                            toasterror.show();
                        }

                    })
                    .catch(function(error) {
                        console.log(error);
                    });

            }
        })

    });
    

});
