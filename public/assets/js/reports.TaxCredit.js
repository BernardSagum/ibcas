$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);
    validate();
    disableselVal();
    function validate(){
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
    }
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
    var tbl_BusinessMasterlist = $('#tbl_BusinessMasterlist').DataTable({
        'dom': '<"wrapper"Bfritp>',
        'order': [
            [0, "desc"]
        ],
 
        "language": {
            "emptyTable": " No Record Found"
        },
        'paging': false,
        'ordering': false,
        'info': false,
        'searching': false,
        'paging': true,
        "pageLength": 10,
    });
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

        // console.log(arr);
        axios.post(base_url + "/ibcas/reports/ftr_taxCredit",arr)
                .then(function(response) {
                    var data = response.data.TableContent;

                    $('#tbl_BusinessMasterlist').dataTable().fnDestroy();
        
                    var BUSINESSMASTERLIST = $('#tbl_BusinessMasterlist').DataTable({
                        data: data,
                        fnCreatedRow: function(nRow, data, iDisplayIndex) {
                            $(nRow).attr('data-id', data.id);
                        //     $(nRow).attr('data-uniqueid', data.uniqueid);
                        },
                        columns: [
                            { data: 'application_type' },
                            { data: 'blpdno' },
                            { data: 'business_name' },
                            { data: 'amountPaidCheck' },
                            { data: 'taxCredit' },
                          
                            
                            {
                                "data": function(data, type, row, meta) {
                                    if (data._status === null) {
                                        return `
                                            <a href="javascript:void(0);" 
                                               data-placement="top" 
                                               data-id="${data.appId}" 
                                               data-blpdNum="${data.blpdno}" 
                                               data-toggle="tooltip" 
                                               title="Issue Tax Credit" 
                                               class="btn btn-sm btn-soft-info issueTaxCred">
                                                ISSUE TAX CREDIT
                                            </a> `;
                                    } else {
                                        return ``;
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
        
                })
                .catch(function(error) {
                    console.log(error);
                });

    });




    $(document).on('click','.issueTaxCred',function(){
        var appId = $(this).attr('data-id');
        var blpdno = $(this).attr('data-blpdno');
        
        axios.get(base_url + "/ibcas/reports/issue-TaxCreditCertificate/"+appId)
        .then(function (response) {
            if (response.data.status == 200) {
                    $('#tstsuccess strong').text(response.data.message);
                    toastsuccess.show();

                    $('#tstsuccess strong').text(response.data.message);
                    toastsuccess.show();
                    window.open(base_url + "/ibcas/reports/generateTaxCredCert", '_blank');



                    // generatePDFPrintNotice(response.data.OrSeries,blpdno);
                } else {
                    $('#tsterror strong').text('Failed to generate series');
                 
                    toasterror.show({delay:100});
                }
        })
        .catch(function (error) {
            console.log(error);
        });
    });

    
});

// $(document).on('submit','#ftr-business',function(e){
//     e.preventDefault();

//     var arr = $(this).serialize();

//       axios.post(base_url + 'ibcas/reports/ftr_taxCredit',arr)
//       .then(function(response) {
//         console.log(response);
//       })
//       .catch(function(error) {
//           console.log(error);
//       });

// });

