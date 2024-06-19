$(document).ready(function(){
    $(document).on('click','#btnPrint',function(){
        window.open(base_url + "/ibcas/print/Official-Receipt-pdf", '_blank');
    });
    var id = document.getElementById('assessment_slip_id').value;
    var OldTotal = '';
    var Newtotal = '';
    var TaxCreds = '';
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
    $('[data-toggle="tooltip"]').tooltip();
    $('#tstsuccess strong').text('Success');
    $('#tstsuccess label').text('Records Found..');

    $('#frm_submit').attr('hidden',false);
    $('#frm_loading').attr('hidden',true);
    $('#frm_submit2').attr('hidden',false);
    $('#frm_loading2').attr('hidden',true);
    addPaymentDetail("cash", "1");
    // init js
    loadmodeofpayment();
    loadPaymentInfo(id);
    curr();
    getbankNames();
    getpaymentType();
    getfees_default(id);
    getAssessmentInstallment(id);
    getTaxCredit(id);
    function getTaxCredit(id){

        axios.get(base_url + `/ibcas/payment/getTaxCreditValue/${id}` )
        .then(function(response) {
                // console.log(response.data.taxcred);
                if (response.data.taxcred <= 0) {
                    $('#taxCredDiv').attr('hidden',true);
                }
                $('#taxcredVal').val(response.data.taxcred);


        })
        .catch(function(error) {
            console.log(error);
        });
    }


    $(document).on('change', '#UseCredit', function() {
        if ($(this).is(':checked')) {
            OldTotal = converToNumValue($('#totalAmountDue').val());
            TaxCreds = converToNumValue($('#taxcredVal').val());

            Newtotal = parseFloat(OldTotal) - parseFloat(TaxCreds);

            // console.log(Newtotal);


            document.getElementById('totalAmountDue').value = parseFloat(Newtotal);
        } else {

            document.getElementById('totalAmountDue').value = parseFloat(OldTotal);

        }
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


    // mode of payment on change
    $(document).on('change','#mode_of_payment',function(){
      
       
        var params = {
            mop: $(this).val(),
            assessment_slip_id: id
        };
        // console.log(params);
        var queryString = new URLSearchParams(params).toString();

        axios.get(base_url + `/ibcas/payment/recomputeInstallment/?${queryString}` )
            .then(function(response) {
                var data = response.data.installmentInfo;
                var dataInstallmentSequence = response.data.installmentsSequence;

               let table = document.getElementById('installment');
               var html ="";
               html +=`
               
               <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                                                   <tr>
                                                       <td>Year</td>
                                                       <td>Installment</td>
                                                       <td>Amount</td>
                                                   </tr>
               </thead>
               `;
               const tableinst = document.getElementById("installment");
               for (let i = 0; i < data.length; i++) {
                       // console.log(data[i]);
                       
                       html += `
                       <tr data-id="${data[i].id}" id="rowid${data[i].id}">
                           <td width="50%">${data[i].year}<input type="hidden" name="taxyr_${data[i].installment}" value="${data[i].tax_year}"></td>
                           <td width="25%">${data[i].installment}<input type="hidden" name="inst_${data[i].installment}" value="${data[i].installment}"></td>
                           <td width="25%">${data[i].amount}<input type="hidden" id="amount_${data[i].installment}" name="amount_${data[i].installment}" value="${data[i].amount}"></td>
                       </tr>
                       `;
                       // totalAmountDue =  parseFloat(totalAmountDue) + parseFloat(data[i].amount);
           
                   }

                   tableinst.innerHTML = html;
                   

                  
                //    const select = document.getElementsByClassName('instto');
                //    select.innerHTML = '';
                //    for (let i = 0; i < dataInstallmentSequence.length; i++) {
                //        // console.log(data[i]);
                //        const option = document.createElement('option');
                //                option.value = dataInstallmentSequence[i].installment;
                //                option.dataset.numinst = dataInstallmentSequence[i].installment;
                //                option.textContent = dataInstallmentSequence[i].installment;
                //                select.appendChild(option);
                //        }
                       populSelto(dataInstallmentSequence.length);
            })
            .catch(function(error) {
                console.log(error);
            });


    });

    // on submit next

    // $(document).on('submit','#payment-details-init',function(e){
    // e.preventDefault();
    // showOverlay('Loading Payment Details');
    // var inst = $('#instfr').val();
    
    // var amountToPay = $('#amount_' + inst).val();
    // // console.log("amountToPay");


    // var arr = $(this).serialize();
    // // console.log(arr);
    // axios.post(base_url + "/ibcas/payment/save-assessment-slip-details", arr)
    // .then(function(response) {
    //     // console.log(response);
    //     if(response.data.status == 200){
    //         hideOverlay();
           
    //     }
    // })
    // .catch(function(error) {
    //     console.log(error);
    // });
    // });

    // onclick next 

    $(document).on('click','#btn_enabled_next',function(){
        var inst = $('#instfr').val();
        $('#installment_input').val($('#instfr').val());
        var amountToPay = $('#amount_' + inst).val();
        $('#offcanvasUser').offcanvas('show');
        
        loadInfoNewPayment(amountToPay);
    });




    // add Payment Details

    $(document).on('click','#addPaymentDetailButton',function(){
        $('#paymentTypeModal').modal('show');
    });
    
    document.getElementById('confirmPaymentTypeButton').addEventListener('click', function() {
        const paymentType = document.getElementById('paymentTypeSelector').value == '1'?"cash":"check";
        const typeValue =  document.getElementById('paymentTypeSelector').value;
        if (paymentType) {
          addPaymentDetail(paymentType,typeValue);
          // Using jQuery to hide the modal, as Bootstrap's modal functionality is often tied to jQuery
          $('#paymentTypeModal').modal('hide');
        } else {
          alert('Please select a payment type.');
        }
    });

    // auto computer
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

    // submit post payment
    $(document).on('submit','#form-post-payment',function(e){
        e.preventDefault();
        var tamount = converToNumValue($('#totalAmountDue').val());
        var amountTend = converToNumValue($('#amountTendered').val());
        var installment_input = $('#installment_input').val();
        var mop = $('#mode_of_payment').val();
       
        if (tamount > amountTend) {
            Swal.fire({
                title: "Posting Failed",
                text: "Amount tendered is less than the amount to be paid",
                icon: "warning"
              });
        } else {
            
        Swal.fire({
            title: 'Post Transaction?',
            text: "You won't be able to revert this",
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true,
            
            confirmButtonText: 'post',
            cancelButtonText: 'cancel',
            reverseButtons: true, // This option will switch the buttons' positions
            confirmButtonColor: '#34c38f', // Green color for the confirm button
            cancelButtonColor: '#dc3545', // Red color for the cancel button
            buttonsStyling: true
          }).then((result) => {
            if (result.isConfirmed) {
                        
        // $('#payment-details-init').trigger('submit');
        if (!$('#under_protest').is(':checked')) {
            // If unchecked, append 'under_protest=N' to the form data
            $('<input>').attr({
                type: 'hidden',
                name: 'under_protest',
                value: 'N'
            }).appendTo('#form-post-payment');
        }
        if (!$('#UseCredit').is(':checked')) {
            // If unchecked, append 'under_protest=N' to the form data
            $('<input>').attr({
                type: 'hidden',
                name: 'UseCredit',
                value: 'Y'
            }).appendTo('#form-post-payment');
        } else {
            $('<input>').attr({
                type: 'hidden',
                name: 'UseCredit',
                value: 'N'
            }).appendTo('#form-post-payment');
        }   




        var arr =  $(this).serialize()+'&mode_of_payment='+ mop;
        
        axios.post(base_url + "/ibcas/payment/post-payment", arr)
        .then(function(response) {
            console.log(response);
            if(response.data.status == 200){
                
                $('#tstsuccess strong').text(response.data.message);
                toastsuccess.show();
                showOverlay('Loading Print OR...');
                setTimeout(function(){
                hideOverlay();
                var params = {
                    assessment_slip_id: response.data.assessment_slip_id,
                    installment_id: installment_input
                };
              // Create a URLSearchParams object from the params object
                var queryString = new URLSearchParams(params).toString();

                // Construct the URL with the query string
                var url = `${base_url}/ibcas/print/Official-Receipt/?${queryString}`;

                // Opening the new tab with the constructed URL
                var newTab = window.open(url, '_blank');
              if (newTab) {
                // Close the current window
                window.close();
            } else {
                alert('The new tab could not be opened.');
            }
                  }, 1000);

            } else {
                $('#tsterror strong').text('Error Posting Payment');
                    $('#offcanvasUser').offcanvas('hide');
                    toasterror.show();
                    hideOverlay();
            }

        })
        .catch(function(error) {
            console.log(error);
        });
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
              swalWithBootstrapButtons.fire({
                title: "Posting Cancelled",
                text: "",
                icon: "error"
              });
            }
          });
        

        }

    });

    // Change Date

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

    $(document).on('click','#btnChangeOrNumber',function(){
        $('#offcanvasUser').offcanvas('hide');
        Swal.fire({
            title: `Change Official Receipt Number?`,
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
                    title: 'Official Receipt Number:',
                    text: '',
                    input: 'text',
                    icon: '',
                    inputAttributes: {
                        autocapitalize: 'off',
                        maxlength: '6'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Confirm O.R Number Change',
                    showLoaderOnConfirm: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(result);
                        
                        var ornum = result.value;
                        axios.post(base_url + "/ibcas/payment/check-or-avail", {ornum})
                        .then(function(response) {
                            // console.log(response.data.content);
                            if(response.data.status == 200){
                                $('#tstsuccess strong').text("Official Receipt Number Changed");
                                    // $('#tstwarning label').text('');
                                    toastsuccess.show({ delay: 100 });
                                $('#ornumber_id').val(response.data.content.id);
                                $('#ornumberlabel').val(response.data.content.ornum);
                                $('#offcanvasUser').offcanvas('show');
                            } else {
                            //     $('#tstwarning strong').text(response.data.message);
                            // // $('#tstwarning label').text('');
                            //     toastwarning.show({delay:100});
                                Swal.fire({
                                    title: ornum,
                                    text: response.data.message,
                                    icon: "warning"
                                  });
                                $('#offcanvasUser').offcanvas('show');
                            }

                        })
                        .catch(function(error) {
                            console.log(error);
                        });


                        // $('#ornumber_id').val(result.value);
                        // $('#ornumberlabel').val(result.value);
                        // $('#offcanvasUser').offcanvas('show');




                        // $('#ordate').val(result.value);
                    }
                });
                
            }
        });
    });



// functions

    function getAssessmentInstallment(id){
        axios.get(base_url + "/ibcas/payment/getAssessmentInstallment/" + id)
        .then(function(response) {
                 var data = response.data.installmentInfo;
                 
                 var dataInstallmentSequence = response.data.installmentsSequence;

                let table = document.getElementById('installment');
                var html ="";
                html +=`
                
                <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                                                    <tr>
                                                        <td>Year</td>
                                                        <td>Installment</td>
                                                        <td>Amount</td>
                                                    </tr>
                </thead>
                `;
                const tableinst = document.getElementById("installment");
                for (let index = 0; index < data.length; index++) {
                        
                        
                        html += `
                        <tr data-id="${data[index].id}" id="rowid${data[index].id}">
                            <td width="50%">${data[index].year}<input type="hidden" name="taxyr_${data[index].installment}" value="${data[index].tax_year}"></td>
                            <td width="25%">${data[index].installment}<input type="hidden" name="inst_${data[index].installment}" value="${data[index].installment}"></td>
                            <td width="25%">${data[index].amount}<input type="hidden" id="amount_${data[index].installment}" name="amount_${data[index].installment}" value="${data[index].amount}"></td>
                        </tr>
                        `;
                        // totalAmountDue =  parseFloat(totalAmountDue) + parseFloat(data[i].amount);
            
                    }

                    tableinst.innerHTML = html;
                    

                   
                    var selectinstfr = document.getElementById('instfr');

                    for (let i = 0; i < dataInstallmentSequence.length; i++) {
                        // console.log(dataInstallmentSequence[i].installment);
                        var option = document.createElement('option');
                                option.value = dataInstallmentSequence[i].installment;
                                option.dataset.numinst = dataInstallmentSequence[i].installment;
                                option.textContent = dataInstallmentSequence[i].installment;
                                selectinstfr.appendChild(option);

                                // populSelto(dataInstallmentSequence[i].installment);
                        }
                        selectinstfr.setAttribute('disabled',true);

                        


        })
        .catch(function(error) {
            console.log(error);
        });
    }



    function converToNumValue(stringWithCommas){
        // Original string with commas

    // Remove commas from the string
    var stringWithoutCommas = stringWithCommas.replace(/,/g, '');

    // Convert the string to a floating-point number
    var numericValue = parseFloat(stringWithoutCommas);

    // console.log(numericValue); // Output: 1000
    return numericValue;
    }



    function loadPaymentInfo(id){
        axios.get(base_url + "/ibcas/payment/getpaymentinfo/" + id)
        .then(function(response) {
            var data = response.data.paymentInfo[0];
            var datainstallment = response.data.installmentInfo;
            var dataNextOrNumber = response.data.nextOrNumber;
            // console.log(response.data.nextOrNumber);
           if (response.data.status == 200) {
            $('#blpdno').val(data.blpdno);
            $('#tax_payer_name').val(data.tax_payer_name);
            $('#business_name').val(data.business_name);
            $('#mode_of_payment').val(data.modeofpayment);
            $('#mode_of_payment').trigger('change');
            
            if (dataNextOrNumber != null) {
                $('#nxtORnum').text(dataNextOrNumber.next_ornum);
                $('#btn_enabled_next').attr('hidden',false);
                $('#btn_disabled_next').attr('hidden',true);

            } else {
                $('#nxtORnum').text("000000");
                $('#btn_enabled_next').attr('hidden',true);
                $('#btn_disabled_next').attr('hidden',false);
                
            }
        
           }

        })
        .catch(function(error) {
            console.log(error);
        });
    }

    function loadmodeofpayment(){
        axios.get(base_url + "/ibcas/payment/getmodeofpayment")
        .then(function(response) {
            var data = response.data.TableContent;
            const select = document.getElementById('mode_of_payment');

            for (let i = 0; i < data.length; i++) {
                // console.log(data[i]);
                const option = document.createElement('option');
                        option.value = data[i].id;
                        option.dataset.numinst = data[i].numinst;
                        option.textContent = data[i].description;
                        select.appendChild(option);
                }

                
        })
        .catch(function(error) {
            console.log(error);
        });
    }
    


    function curr(){
        $('.curr').inputmask('decimal', {
            rightAlign: true,
            radixPoint: '.',         // Decimal separator
            groupSeparator: ',',     // Thousands separator
            autoGroup: true,         // Automatically group thousands
            digits: 2,               // <-- Set to 2 for two decimal places
            digitsOptional: false,   // Require the specified number of digits
            placeholder: '0',
            clearMaskOnLostFocus: false // Keep the mask always on
        });
    }
    

    function addPaymentDetail(type, typeValue) {

        if (document.querySelector(`input[name='${type}Amount']`)) {
            alert(`${type.charAt(0).toUpperCase() + type.slice(1)} is already added. You cannot add it again.`);
            return;
          }   
      const container = document.getElementById('paymentDetailsContainer');
      const detailDiv = document.createElement('div');
      detailDiv.classList.add('payment-detail');
      
      let innerHTML = `
        <div class="row">
          <div class="col-12">
            <label>Type of Payment: ${type.charAt(0).toUpperCase() + type.slice(1)}</label>
          </div>
         `;
    
      if (type === 'check') {
        innerHTML += `
          <div class="col-6" style="margin-bottom: 10px;">
            <input type="hidden"  name="assessment_payment_id_check" value="${typeValue}"/>
            <input type="text" class="form-control" required name="checkNumber" placeholder="Check Number" />
          </div>
          <div class="col-6" style="margin-bottom: 10px;">
            <select class="form-control" name="bankName" required id="bankName">
            <option value="" selected >Select Bank</option>
            </select>
          </div>
          <div class="col-6" style="margin-bottom: 10px;">
            <input type="date" class="form-control" required name="checkDate" />
          </div>
          <div class="col-6" style="margin-bottom: 10px;">
            <input type="text" class="form-control paytype" required name="checkAmount" placeholder="Amount" />
          </div>`;
      } else {
        innerHTML += 
        `<div class="col-6" style="margin-bottom: 10px;">
        <input type="hidden" name="assessment_payment_id_cash" value="${typeValue}" />
        <input type="text" class="form-control paytype" name="${type}Amount" placeholder="Enter amount" required />
      </div>`;
      }
      getbankNames();
      // Append the remove button HTML before the <hr>
      innerHTML += `
        <div class="col-12" style="margin-bottom: 10px;">
          <button class="btn btn-sm btn-danger remove-button" style="float:right">Remove</button>
        </div>
        </div>
        <hr style="width: 100%; border:solid 1px">`;
    
      // Set the innerHTML to the detailDiv
      detailDiv.innerHTML = innerHTML;
    
      // Append the new detailDiv to the container
      container.appendChild(detailDiv);
       // Attach the event listener to all elements with the class 'paytype'
       var paytypeInputs = document.getElementsByClassName('paytype');
       paytypeInputs.forEach(function(input) {
           input.addEventListener('input',updateTotal);
       });
      // Add remove functionality to the newly added remove button
      const removeButtons = detailDiv.getElementsByClassName('remove-button');
      Array.from(removeButtons).forEach(button => {
        button.addEventListener('click', function() {
          removeDetail(this.closest('.payment-detail'));
          updateTotal();
        });
      });

      curr();
      function updateTotal() {
        var paytypeInputs = document.querySelectorAll('.paytype');
    
        var total = 0;
        paytypeInputs.forEach(function(input) {
            var value = parseFloat(input.value) || 0;
            total += value;
        });
        
        // Update the amountTendered input
        var amountTenderedInput = document.getElementById('amountTendered');
        amountTenderedInput.value = total.toFixed(2); // Formats the total to 2 decimal places
        $('#amountTendered').trigger('input');
    }
      
    }
    
    function removeDetail(detailDiv) {
      // This function will remove the payment detail div
      detailDiv.remove();
    }
    
    function getbankNames(){
        axios.get(base_url + "/ibcas/payment/BankNames/")
        .then(function(response) {
            var data = response.data.BankNames;
            // console.log(data);
            const select = document.getElementById('bankName');

            for (let i = 0; i < data.length; i++) {
                // console.log(data[i]);
                const option = document.createElement('option');
                        option.value = data[i].id;
                        option.textContent = `${data[i].name} -(${data[i].shortname})`;
                        select.appendChild(option);
                }
        })
        .catch(function(error) {
            console.log(error);
        });
    }

    function getpaymentType(){
        axios.get(base_url + "/ibcas/payment/getPaymentType/")
        .then(function(response) {
            var data = response.data.PaymentTypes;
            // console.log(data);
            const select = document.getElementById('paymentTypeSelector');

            for (let i = 0; i < data.length; i++) {
                // console.log(data[i]);
                const option = document.createElement('option');
                        option.value = data[i].id;
                        option.textContent = `${data[i].name}`;
                        select.appendChild(option);
                }
        })
        .catch(function(error) {
            console.log(error);
        });
    }

    function loadInfoNewPayment(amountToPay) {
        axios.get(base_url + "/ibcas/payment/getpaymentinfo/" + id)
        .then(function(response) {
            var data = response.data.paymentInfo[0];
            // console.log(response.data.nextOrNumber);

            $('#ornumber_id').val(response.data.nextOrNumber.id);
            $('#ornumberlabel').val(response.data.nextOrNumber.next_ornum);
            $('#application_id').val(data.application_id);
            $('#blpdNumber').val(data.blpdno);
            $('#taxPayer').val(data.tax_payer_name);
            $('#busName').val(data.business_name);
            // console.log(amountToPay);
            document.getElementById('totalAmountDue').value = parseFloat(amountToPay);
        })
        .catch(function(error) {
            console.log(error);
        });
    }

    function getfees_default(id){
        var totalAmountDue = 0.00;
        axios.get(base_url + "/ibcas/payment/get-fees-list/"+id)
            .then(function(response) {
                // console.log(response);
                if (response.data.status == 200){
                    toastwarning.hide();
                    var html ="";
                    html +=`
                    
                    <tr>
                        <td width="65%"></td>
                        <td width="35%"></td>
                        
                    </tr>
                    `;
                    // console.log(response.data);
                    var data = response.data.TableContent;
                    // console.log(data)
                    const table = document.getElementById("tbl_new_payment_fees");
                    for (let i = 0; i < data.length; i++) {
                        // console.log(data[i]);
                        
                        html += `
                        <tr data-id="${data[i].id}" id="rowid${data[i].id}">
                            <td width="50%">${data[i].remarks}</td>
                            <td width="25%"><input type="number" readonly class="form-control currency" data-rowid="${data[i].id}" id="feesamount${i+1}" value="${data[i].amount}"></td>
                             </tr>
                        `;
                        // totalAmountDue =  parseFloat(totalAmountDue) + parseFloat(data[i].amount);

                    }
                    // console.log(totalAmountDue);
                    table.innerHTML = html;
                    
                    // $('#').val(totalAmountDue);
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
                $('.currency').inputmask('₱9{+}.99', {
                    clearMaskOnLostFocus: true,
                    rightAlign: true,
                    radixPoint: '.',
                  });
              }, 3000);
    }

    // function populSelto(num){
    //     const select = document.getElementById('instto');
        
    //     // Clear existing options and set to read-only
    //     select.innerHTML = '';
    //     // select.setAttribute('disabled', true);
    
    //     // Append only the option from the last loop
    //     if (num > 0) {
    //         const option = document.createElement('option');
    //         option.value = num;
    //         option.textContent = num;
    //         select.appendChild(option);
    //     }
    // }
    function populSelto(num) {
        const select = document.getElementById('instto');
        
        // Clear existing options
        select.innerHTML = '';
    
        // Dynamically create and append options based on num
        for (let i = 1; i <= num; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            select.appendChild(option);
        }
    }
    
    
});