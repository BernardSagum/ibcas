$(document).ready(function(){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
    $('#ftr_val').inputmask('9999-9999-9999');
    (function () {
      'use strict'
    
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.querySelectorAll('.needs-validation')
    
      // Loop over them and prevent submission
      Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }
    
            form.classList.add('was-validated')
          }, false)
        })
    })()
    

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
            $('#ftr_val').inputmask('9999-9999-9999');
            // $('#ftr_val').attr('required',true);
        } else if (selBy == 'business_name') {
            $('#ftr_val').inputmask('');
            // $('#ftr_val').attr('required',true);
        } else {
        //    disableselVal();
        }

    });



    $(document).on('submit','#ftr-business',function(e){
      e.preventDefault();

      var arr = $(this).serialize();

      // console.log(arr);
      axios.post(base_url + "/ibcas/reports/filter-taxbill",arr)
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
                          { data: 'applicationType' },
                          { data: 'blpdno' },
                          { data: 'business_name' },
                          { data: 'tax_payer_name' },
                          {
                            "data": function(data, type, row, meta) {
                                if(data.PaymentStatus != '_fullyPaid' ){
                                  return `<a  data-blpdno="${data.blpdno}" data-appId="${data.application_id}" data-placement="top" data-toggle="tooltip" title="Show Tax Bill" target="_blank" class="btn btn-sm btn-soft-success btn-show-TaxBIll">Show</a>`;
                                } else {
                                    return `Paid`;
    
    
                                  
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


  $(document).on('click','.btn-show-TaxBIll',function(){
        var appId = $(this).attr('data-appId');
        var blpdno = $(this).attr('data-blpdno');
        
        axios.get(base_url + "/ibcas/reports/get-taxBill-info/"+appId)
        .then(function (response) {


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

