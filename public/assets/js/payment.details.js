$(document).ready(function(){
    // let timerInterval
    getpaymentType();
    var id = document.getElementById('assessment_slip_id').value;
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
    $('[data-toggle="tooltip"]').tooltip();
    $('#tstsuccess strong').text('Success');
    $('#tstsuccess label').text('Records Found..');
    curr();

    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);
    $('#frm_submit2').attr('hidden',false);
    $('#frm_loading2').attr('hidden',true);

    getfees_default(id);
    // $(document).on('click','#addPaymentDetailButton',function(){
    //    $('#paymentTypeModal').modal('show');
    // });

    // document.getElementById('confirmPaymentTypeButton').addEventListener('click', function() {
    //     const paymentType = document.getElementById('paymentTypeSelector').value == '1'?"cash":"check";
    //     const typeValue =  document.getElementById('paymentTypeSelector').value;
    //     if (paymentType) {
    //       addPaymentDetail(paymentType,typeValue);
    //       // Using jQuery to hide the modal, as Bootstrap's modal functionality is often tied to jQuery
    //       $('#paymentTypeModal').modal('hide');
    //     } else {
    //       alert('Please select a payment type.');
    //     }
    //   });
 
 
    // function addPaymentDetail(type, typeValue) {

    //     if (document.querySelector(`input[name='${type}Amount']`)) {
    //         alert(`${type.charAt(0).toUpperCase() + type.slice(1)} is already added. You cannot add it again.`);
    //         return;
    //       }   
    //   const container = document.getElementById('paymentDetailsContainer');
    //   const detailDiv = document.createElement('div');
    //   detailDiv.classList.add('payment-detail');
      
    //   let innerHTML = `
    //     <div class="row">
    //       <div class="col-12">
    //         <label>Type of Payment: ${type.charAt(0).toUpperCase() + type.slice(1)}</label>
    //       </div>
    //      `;
    
    //   if (type === 'check') {
    //     innerHTML += `
    //       <div class="col-6" style="margin-bottom: 10px;">
    //         <input type="hidden"  name="assessment_payment_id_check" value="${typeValue}"/>
    //         <input type="text" class="form-control" name="checkNumber" placeholder="Check Number" />
    //       </div>
    //       <div class="col-6" style="margin-bottom: 10px;">
    //         <select class="form-control" name="bankName" id="bankName">
    //         <option value="" selected >Select Bank</option>
    //         </select>
    //       </div>
    //       <div class="col-6" style="margin-bottom: 10px;">
    //         <input type="date" class="form-control" name="checkDate" />
    //       </div>
    //       <div class="col-6" style="margin-bottom: 10px;">
    //         <input type="text" class="form-control curr" name="checkAmount" placeholder="Amount" />
    //       </div>`;
    //   } else {
    //     innerHTML += 
    //     `<div class="col-6" style="margin-bottom: 10px;">
    //     <input type="hidden" name="assessment_payment_id_cash" value="${typeValue}"/>
    //     <input type="text" class="form-control curr" name="${type}Amount" placeholder="Enter amount" />
    //   </div>`;
    //   }
    
    //   // Append the remove button HTML before the <hr>
    //   innerHTML += `
    //     <div class="col-12" style="margin-bottom: 10px;">
    //       <button class="btn btn-sm btn-danger remove-button" style="float:right">Remove</button>
    //     </div>
    //     </div>
    //     <hr style="width: 100%; border:solid 1px">`;
    
    //   // Set the innerHTML to the detailDiv
    //   detailDiv.innerHTML = innerHTML;
    
    //   // Append the new detailDiv to the container
    //   container.appendChild(detailDiv);
    
    //   // Add remove functionality to the newly added remove button
    //   const removeButtons = detailDiv.getElementsByClassName('remove-button');
    //   Array.from(removeButtons).forEach(button => {
    //     button.addEventListener('click', function() {
    //       removeDetail(this.closest('.payment-detail'));
    //     });
    //   });

    //   curr();
    //   getbankNames();
    // }
    
    // function removeDetail(detailDiv) {
    //   // This function will remove the payment detail div
    //   detailDiv.remove();
    // }
    
    // function getbankNames(){
    //     axios.get(base_url + "/ibcas/payment/BankNames/")
    //     .then(function(response) {
    //         var data = response.data.BankNames;
    //         console.log(data);
    //         const select = document.getElementById('bankName');

    //         for (let i = 0; i < data.length; i++) {
    //             // console.log(data[i]);
    //             const option = document.createElement('option');
    //                     option.value = data[i].id;
    //                     option.textContent = `${data[i].name} -(${data[i].shortname})`;
    //                     select.appendChild(option);
    //             }
    //     })
    //     .catch(function(error) {
    //         console.log(error);
    //     });
    // }
    // function getpaymentType(){
    //     axios.get(base_url + "/ibcas/payment/getPaymentType/")
    //     .then(function(response) {
    //         var data = response.data.PaymentTypes;
    //         console.log(data);
    //         const select = document.getElementById('paymentTypeSelector');

    //         for (let i = 0; i < data.length; i++) {
    //             // console.log(data[i]);
    //             const option = document.createElement('option');
    //                     option.value = data[i].id;
    //                     option.textContent = `${data[i].name}`;
    //                     select.appendChild(option);
    //             }
    //     })
    //     .catch(function(error) {
    //         console.log(error);
    //     });
    // }

    
    // $('#amountTendered').on('input', function() {
    //     var amountDue = $('#totalAmountDue').val();
    //     var amountTendered = $('#amountTendered').inputmask('unmaskedvalue');

    //     amountDue = parseFloat(amountDue);
    //     amountTendered = parseFloat(amountTendered);

    //     if (!isNaN(amountTendered) && !isNaN(amountDue)) {
    //         var change = amountTendered - amountDue;
    //         $('#change').val(change >= 0 ? change.toFixed(2) : '0.00');
    //     } else {
    //         $('#change').val('0.00');
    //     }
    // });

    $('#amountTendered').on('input', function() {
        // Get the numeric values from the inputs
        var amountDue = $('#totalAmountDue').inputmask('unmaskedvalue'); // Assuming this is the ID for the Amount Due input
        var amountTendered = $(this).inputmask('unmaskedvalue');

        // Convert string to float
        amountDue = parseFloat(amountDue);
        amountTendered = parseFloat(amountTendered);

        // Calculate the change
        var change = amountTendered - amountDue;

        // Set the calculated change into the Change input
        // Ensuring that we don't display negative change
        $('#change').val(change >= 0 ? change.toFixed(2) : '0.00');
    });



    // $(document).on('submit','#payment-details-init',function(e){
    //     e.preventDefault();
    //     showOverlay('Loading Payment Details');
    //     var arr = $(this).serialize();
    //     // console.log(arr);
    //     axios.post(base_url + "/ibcas/payment/save-assessment-slip-details", arr)
    //     .then(function(response) {
    //         console.log(response);
    //         if(response.data.status == 200){
    //             hideOverlay();
    //             $('#offcanvasUser').offcanvas('show');
    //             loadInfoNewPayment();
    //         }
    //     })
    //     .catch(function(error) {
    //         console.log(error);
    //     });
    // });

    // $(document).on('click','#btn_enabled_next',function(){
    //     loadInfoNewPayment();
    // });
    
    // function loadInfoNewPayment() {
    //     axios.get(base_url + "/ibcas/payment/getpaymentinfo/" + id)
    //     .then(function(response) {
    //         var data = response.data.paymentInfo[0];
    //         console.log(response.data.nextOrNumber);

    //         $('#ornumber_id').val(response.data.nextOrNumber.id);
    //         $('#ornumberlabel').val(response.data.nextOrNumber.next_ornum);
    //         $('#application_id').val(data.application_id);
    //         $('#blpdNumber').val(data.blpdno);
    //         $('#taxPayer').val(data.tax_payer_name);
    //         $('#busName').val(data.business_name);
    //     })
    //     .catch(function(error) {
    //         console.log(error);
    //     });
    // }


    // function getfees_default(id){
    //     var totalAmountDue = 0.00;
    //     axios.get(base_url + "/ibcas/payment/get-fees-list/"+id)
    //         .then(function(response) {
    //             // console.log(response);
    //             if (response.data.status == 200){
    //                 toastwarning.hide();
    //                 var html ="";
    //                 html +=`
                    
    //                 <tr>
    //                     <td width="65%"></td>
    //                     <td width="35%"></td>
                        
    //                 </tr>
    //                 `;
    //                 // console.log(response.data);
    //                 var data = response.data.TableContent;
    //                 console.log(data)
    //                 const table = document.getElementById("tbl_new_payment_fees");
    //                 for (let i = 0; i < data.length; i++) {
    //                     // console.log(data[i]);
                        
    //                     html += `
    //                     <tr data-id="${data[i].id}" id="rowid${data[i].id}">
    //                         <td width="50%">${data[i].remarks}</td>
    //                         <td width="25%"><input type="number" readonly class="form-control currency" data-rowid="${data[i].id}" id="feesamount${i+1}" value="${data[i].amount}"></td>
    //                          </tr>
    //                     `;
    //                     totalAmountDue =  parseFloat(totalAmountDue) + parseFloat(data[i].amount);

    //                 }
    //                 // console.log(totalAmountDue);
    //                 table.innerHTML = html;
    //                 document.getElementById('totalAmountDue').value = parseFloat(totalAmountDue);
    //                 // $('#').val(totalAmountDue);
    //                 hideOverlay();
    //                 $('#tstsuccess strong').text('Resources loaded successfuly');
             
    //                 toastsuccess.show();
    //             } else {
    //                 // toastwarning.hide();
    //                 hideOverlay();
    //                 $('#tsterror strong').text('Resource loading error');
    //                 toasterror.show();
                
    //             }
    
    //         })
    //         .catch(function(error) {
    //             console.log(error);
    //         });
    //         setTimeout(function(){
    //             toastsuccess.hide();
    //             toasterror.hide();
    //             $('.currency').inputmask('₱9{+}.99', {
    //                 clearMaskOnLostFocus: true,
    //                 rightAlign: true,
    //                 radixPoint: '.',
    //               });
    //           }, 3000);
    // }

    $(document).on('submit','#form-post-payment',function(e){
        e.preventDefault();
        var arr =  $(this).serialize();
        
        axios.post(base_url + "/ibcas/payment/post-payment", arr)
        .then(function(response) {
            console.log(response);
        })
        .catch(function(error) {
            console.log(error);
        });


    });


    $(document).on('click','#btnChangeDate',function(){
        Swal.fire({
            title: `Change Official Receipt date?`,
            text: 'Please enter password to confirm',
            input: 'password',
            icon: 'question',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Confirm Password',
            showLoaderOnConfirm: true,
            preConfirm: (password) => {
                return axios.post(base_url + "/ibcas/userman/checkpassword/", { password })
                    .then(response => {
                        if (response.data.status == 200) {
                            return true; // Password is correct, allow deletion
                        } else {
                            Swal.showValidationMessage('Wrong password'); // Show error message
                            return false; // Prevent closing the dialog
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        Swal.showValidationMessage('Error checking password'); // Show error message
                        return false; // Prevent closing the dialog
                    });
            },
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Official Receipt Date:',
                    text: '',
                    input: 'date',
                    icon: '',
                    inputAttributes: {
                        autocapitalize: 'off',
                        max: new Date().toISOString().split('T')[0] // Set max date to today
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Confirm Date Change',
                    showLoaderOnConfirm: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(result);
                        $('#or_date_label').val(result.value);
                        $('#ordate').val(result.value);
                    }
                });
                
            }
        });
    });












    loadmodeofpayment();
    loadPaymentInfo(id);
   
   
    // function loadPaymentInfo(id){
    //     axios.get(base_url + "/ibcas/payment/getpaymentinfo/" + id)
    //     .then(function(response) {
    //         var data = response.data.paymentInfo[0];
    //         var datainstallment = response.data.installmentInfo;
    //         var dataNextOrNumber = response.data.nextOrNumber;
    //         // console.log(response.data.nextOrNumber);
    //        if (response.data.status == 200) {
    //         $('#blpdno').val(data.blpdno);
    //         $('#tax_payer_name').val(data.tax_payer_name);
    //         $('#business_name').val(data.business_name);
    //         $('#mode_of_payment').val(data.modeofpayment);
           
            
    //         if (dataNextOrNumber != null) {
    //             $('#nxtORnum').text(dataNextOrNumber.next_ornum);
    //             $('#btn_enabled_next').attr('hidden',false);
    //             $('#btn_disabled_next').attr('hidden',true);

    //         } else {
    //             $('#nxtORnum').text("000000");
    //             $('#btn_enabled_next').attr('hidden',true);
    //             $('#btn_disabled_next').attr('hidden',false);
                
    //         }
    //        loadintallentTable(datainstallment);
        
    //        }


    //     })
    //     .catch(function(error) {
    //         console.log(error);
    //     });
    //     }

    // function loadintallentTable(datainstallment){
    //     let table = document.getElementById('installment');
    //     var html ="";
    //     html +=`
        
    //     <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
    //                                         <tr>
    //                                             <td>Year</td>
    //                                             <td>Installment</td>
    //                                             <td>Amount</td>
    //                                         </tr>
    //     </thead>
    //     `;
    //     const tableinst = document.getElementById("installment");
    //     for (let i = 0; i < datainstallment.length; i++) {
    //         console.log(datainstallment[i]);
            
    //         html += `
    //         <tr data-id="${datainstallment[i].id}" id="rowid${datainstallment[i].id}">
    //             <td width="50%">${datainstallment[i].tax_year}<input type="hidden" name="taxyr_${i+1}" value="${datainstallment[i].tax_year}"></td>
    //             <td width="25%">${i+1}<input type="hidden" name="inst_${i+1}" value="${i+1}"></td>
    //             <td width="25%">${datainstallment[i].tamount}<input type="hidden" name="amount_${i+1}" value="${datainstallment[i].tamount}"></td>
    //         </tr>
    //         `;
    //         // totalAmountDue =  parseFloat(totalAmountDue) + parseFloat(data[i].amount);

    //     }

    //     tableinst.innerHTML = html;
    
    // }


    // function loadmodeofpayment(){
    //     axios.get(base_url + "/ibcas/payment/getmodeofpayment")
    //     .then(function(response) {
    //         var data = response.data.TableContent;
    //         const select = document.getElementById('mode_of_payment');

    //         for (let i = 0; i < data.length; i++) {
    //             // console.log(data[i]);
    //             const option = document.createElement('option');
    //                     option.value = data[i].id;
    //                     option.dataset.numinst = data[i].numinst;
    //                     option.textContent = data[i].description;
    //                     select.appendChild(option);
    //             }


    //     })
    //     .catch(function(error) {
    //         console.log(error);
    //     });
    // }
    
    // $(document).on('change','#mode_of_payment',function(){
    //     var selectedOption = $(this).find('option:selected'); // Find the selected option
    //     var value = selectedOption.val(); // Value of the selected option
    //     var num_of_installment = selectedOption.data('numinst'); // Correct way to access data-* attributes
    //     var id = $('#assessment_slip_id').val(); // Value of another element (if needed)
    //     $('#installm_count').val(num_of_installment);
    //     // console.log(num_of_installment); // Log the number of installments
    //     axios.get(base_url + "/ibcas/payment/installment-mop", {
    //         params: {
    //             value: value,
    //             id: id
    //         }
    //     })
    //     .then(function(response) {
    //         console.log(response);
    //         var datainstallment = response.data.installmentInfo;
    //         loadintallentTable(datainstallment);
    //     })
    //     .catch(function(error) {
    //         console.log(error);
    //     });


    // });


    // $('.currency').inputmask('₱9{+}.99', {
    //     clearMaskOnLostFocus: true,
    //     rightAlign: true,
    //     radixPoint: '.',
    //   });
    //   $('.curr').inputmask('9{+}.99', {
    //     clearMaskOnLostFocus: true,
    //     rightAlign: true,
    //     radixPoint: '.',
    //   });

    // function curr(){
    //     $('.curr').inputmask('decimal', {
    //         rightAlign: true,
    //         radixPoint: '.',
    //         groupSeparator: ',',
    //         autoGroup: true,
    //         digitsOptional: false,
    //         placeholder: '0',
    //         clearMaskOnLostFocus: false // Keep the mask always on
    //     });
    // }

});


