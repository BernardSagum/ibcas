$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
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


      function enableselVal(){
        $('#ftr_val').attr('required',true);
        $('#ftr_val').attr('disabled',false);
        $('#ftr_val').inputmask('9999-9999-9999');
    }
    function disableselVal(){
        $('#ftr_val').attr('disabled',true);
        $('#ftr_val').inputmask('');
        $('#ftr_val').val('');
        $('#ftr_val').attr('required',false);
    }

    $(document).on('change','#ftr_selby',function(){
        var selBy = $(this).val();

        if (selBy == 'blpdNumber') {
            enableselVal();
        } else {
           disableselVal();
        }

    });


    $(document).on('submit','#ftr-business',function(e){
        e.preventDefault();
        var arr = $(this).serialize();
        axios.post(base_url + "/ibcas/report/ftr-delinquencies_2", arr)
        .then(function (response) {
            var data = response.data.TableContent;
            // Call the function to download the Excel file
            // exportToExcel('BusinessReport', data, 'BusinessData');

            $('#tbl_BusinessMasterlist').dataTable().fnDestroy();

            var BUSINESSMASTERLIST = $('#tbl_BusinessMasterlist').DataTable({
                data: data,
                fnCreatedRow: function(nRow, data, iDisplayIndex) {
                    $(nRow).attr('data-id', data.id);
                //     $(nRow).attr('data-uniqueid', data.uniqueid);
                },
                columns: [
                    { data: 'blpdno' },
                    { data: 'business_name' },
                    { data: 'OWNER' },
                    { data: 'ADDRESS' },
                  
                    
                    {
                        "data": function(data, type, row, meta) {
                            if(data.series_stats === null){ // Corrected NULL to null
                                return `<a  data-blpdno="${data.blpdno}" data-appId="${data.application_id}" data-placement="top" data-toggle="tooltip" title="Issue Notice" target="_blank" class="btn btn-sm btn-soft-success btn-issue-notice">Issue</a>`;
                            } else {
                                return `<a data-blpdno="${data.blpdno}" data-appId="${data.application_id}" data-placement="top" data-toggle="tooltip" title="Reissue Notice" target="_blank" class="btn btn-sm btn-soft-warning btn-reissue-notice">Reissue</a>`;
                            }
                        }
                        
                    },
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

            $('[data-toggle="tooltip"]').tooltip();


            hideOverlay();




        })
        .catch(function (error) {
            console.log(error);
        });
       


        // toastr.success('Extracted', 'Success...', { "timeOut": "2000" });
    });

    $(document).on('click','.btn-issue-notice',function(){
        var appId = $(this).attr('data-appId');
        var blpdno = $(this).attr('data-blpdno');
        
        axios.get(base_url + "/ibcas/reports/issue-notice/"+appId)
        .then(function (response) {
            if (response.data.status == 200) {

                    // console.log(response);
                    $('#tstsuccess strong').text(response.data.message);
                    toastsuccess.show();

                    // // $('#tstsuccess strong').text(response.data.message);
                    // // toastsuccess.show();
                    // setTimeout(() => {
                    //     $('#ftr-business').trigger('submit');
                        setTimeout(() => {
                            window.open(base_url + "/ibcas/reports/generateNotice", '_blank');
                                // $('#ftr-business').trigger('submit');
                                setTimeout(() => {
                                    $('#ftr-business').trigger('submit');
                                }, 3000);
                            // 
                            }, 3000);
                    
                    // }, 3000);


                    // generatePDFPrintNotice(response.data.OrSeries,blpdno);
                } else {
                    $('#tsterror strong').text('Failed to generate series');
                 
                    toasterror.show({delay:100});
                }
        })
        .catch(function (error) {
            console.log(error);
        });


    })

    $(document).on('click','.btn-reissue-notice',function(){
        var appId = $(this).attr('data-appId');
        var blpdno = $(this).attr('data-blpdno');
        axios.get(base_url + "/ibcas/reports/reissue-notice/"+appId)
        .then(function (response) {
          
            if (response.data.status == 200) {
                    $('#tstsuccess strong').text(response.data.message);
                    toastsuccess.show();
                    setTimeout(() => {
                        $('#ftr-business').trigger('submit');
                        setTimeout(() => {
                            window.open(base_url + "/ibcas/reports/generateNotice", '_blank');
                            }, 3000);
                    
                    }, 3000);
                } else {
                    $('#tsterror strong').text('Failed to generate series');
                 
                    toasterror.show({delay:100});
                }
        })
        .catch(function (error) {
            console.log(error);
        });


    })


    var fromDateInput = document.getElementById('DateFrom');
    var toDateInput = document.getElementById('DateTo');

    // Function to format date to YYYY-MM-DD
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year, month, day].join('-');
    }

    // Set max attribute to today's date for both inputs
    var today = new Date();
    var maxDate = formatDate(today);
    fromDateInput.setAttribute('max', maxDate);
    toDateInput.setAttribute('max', maxDate);

    // Listen for 'input' or 'change' events on the 'From' date
    fromDateInput.addEventListener('input', function(event) {
        // Set the 'To' date's min attribute to the selected 'From' date
        toDateInput.setAttribute('min', event.target.value);

        // Log the change (for demonstration purposes)
        // console.log('From date changed (input event): ', event.target.value);
    });

    // Optional: Listen for 'change' event if you want to perform actions when the selection is finalized
    fromDateInput.addEventListener('change', function(event) {
        // Log the finalized selection (for demonstration purposes)
        toDateInput.setAttribute('min', event.target.value);
    });

});