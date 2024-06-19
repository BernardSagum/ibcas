$(document).ready(function () {
    var FeesArrayChecker = [];
    // var id = document.getElementById('assessment_slip_id').value;
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
    $('[data-toggle="tooltip"]').tooltip();
    $('#tstsuccess strong').text('Success');
    $('#tstsuccess label').text('Records Found..');

    $('#frm_submit_post').attr('hidden', false);
    $('#frm_loading_post').attr('hidden', true);
    $('#frm_loading').attr('hidden', true);
    addPaymentDetail("cash", "1");
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
  

    // init js

    // loadPaymentInfo(id);
    // getNextOrNumber();
    getMiscType();
    curr();
    getforms();
   
    // getbankNames();
    getpaymentType();
    // getfees_default(id);


      $(document).on('change','#fund_id',function(){
        getNextOrNumber($(this).val());
      });







    // getNextOrNumber
    function getNextOrNumber(fund_id) {
        axios.get(base_url + "/ibcas/payment/getNextOrNumber_FundID/" + fund_id)
            .then(function (response) {
                $('#ornumberlabel').val(response.data.nextOrNumber.next_ornum);
                $('#ornumber_id').val(response.data.nextOrNumber.id);
            })
            .catch(function (error) {
                console.log(error);
            });
    }


    $(document).on('click','#AddNewMsc',function(){
        $('#AddNewMsc').attr('hidden',true);    
        $('#CancelNew').attr('hidden',false);    
        $('#MainMsc').attr('hidden',false);
        
        $('#form-post-payment')[0].reset();
        getNextOrNumber();
        GetFunds();
    });

    $(document).on('click','#CancelNew',function(){
        $('#AddNewMsc').attr('hidden',false);   
        $('#CancelNew').attr('hidden',true);   
        $('#MainMsc').attr('hidden',true);
        $('#form-post-payment')[0].reset();


    });
    function getMiscType() {
        axios.get(base_url + "/ibcas/payment/getMiscType")
            .then(function (response) {
                var data = response.data.miscType;
                const select = document.getElementById('MiscType');

                for (let i = 0; i < data.length; i++) {
                    // console.log(data[i]);
                    const option = document.createElement('option');
                    option.value = data[i].id;
                    option.dataset.mscVal = data[i].amount;
                    option.textContent = data[i].name;
                    select.appendChild(option);
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }
    function GetFunds(){

        axios.get(base_url + "/ibcas/funds/get-list-funds/")
        .then(function(response) {
            // console.log(response.data);
            if(response.data.status == 200){
                var data = response.data.FundList;
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
    function getforms(){

        axios.get(base_url + "/ibcas/acc-forms/get-all-forms/")
        .then(function(response) {
            // console.log(response.data);
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
    // ON CLICK SEARCH BUSINESS

    $(document).on('click','#searchBusiness',function(){
        $('#offcanvasUser').offcanvas('show');
    });
    // //  SEARCH BUSINESS SECTION

    var tbl_payment = $('#payment-masterlist').DataTable({
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


    $(document).on('submit','#ftr_paymentlist',function(e){
        e.preventDefault();
        $('#frm_submit').attr('hidden',true);
        $('#frm_loading').attr('hidden',false);
        var arr = $(this).serialize();
        axios.post(base_url + "/ibcas/payment/filterBusiness", arr)
        .then(function(response) {
            // console.log(response.data);
            var data = response.data.TableContent;
            $('#payment-masterlist').dataTable().fnDestroy();

            var PAYMENTMASTERLIST = $('#payment-masterlist').DataTable({
                data: data,
                fnCreatedRow: function(nRow, data, iDisplayIndex) {
                    $(nRow).attr('data-id', data.id);
                    // Add a class to make rows clickable
                    $(nRow).addClass('clickable-row');
                },
                columns: [
                    { data: 'blpdno' },
                    { data: 'business_name' },
                    { data: 'tax_payer_name' },
                    { data: 'taxpayer_address' },
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
        // toastsuccess.show({delay: 100});
        // toastsuccess.show();
        
        setTimeout(function() {
            
            $('#frm_submit').attr('hidden',false);
            $('#frm_loading').attr('hidden',true);
            // toastsuccess.hide();
        },1000); // 5000 milliseconds = 5 seconds
        // 
    });

    $(document).on('change','#ftr_selby',function(){
        var ftr_selby = $(this).val();

       
            $('#ftr_val').val('');
     
    });

        // Add a click event handler for clickable rows
        $('#payment-masterlist tbody').on('click', 'tr.clickable-row', function() {
            // Get the data-id attribute of the clicked row
            var dataId = $(this).data('id');
            getBusinessDetails(dataId);
            // Use dataId as needed (e.g., display it in an alert)
            // alert('Clicked row with data-id: ' + dataId);
        });
        
        function getBusinessDetails(id){
            axios.get(base_url + "/ibcas/payment/getBusnessDetails/"+id)
        .then(function(response) {
            console.log(response.data);

            if (response.data.status == 200) {
                $('#tstsuccess strong').text('Business Data loaded');

                    toastsuccess.show();

                    $('#app_id').val(response.data.BusinessData[0].id)
                    $('#blpdNumber').val(response.data.BusinessData[0].blpdno)
                    $('#busName').val(response.data.BusinessData[0].business_name)
                    $('#payorName').val(response.data.BusinessData[0].tax_payer_name)
                    $('#offcanvasUser').offcanvas('hide');

            }else {
                $('#tsterror strong').text('Error loading Business Data');

                    toasterror.show();
                    $('#offcanvasUser').offcanvas('hide');
            }

        })
        .catch(function(error) {
            console.log(error);
        });
        }



    // END OF SEARCH BUSINESS SECTION


    // add Payment Details

    $(document).on('click', '#addPaymentDetailButton', function () {
        $('#paymentTypeModal').modal('show');
    });
    // add MSC Details

    $(document).on('click', '#addMiscellaneousPaymentButton', function () {
        $('#MiscellaneousPaymentModal').modal('show');
    });

    document.getElementById('confirmPaymentTypeButton').addEventListener('click', function () {
        const paymentType = document.getElementById('paymentTypeSelector').value == '1' ? "cash" : "check";
        const typeValue = document.getElementById('paymentTypeSelector').value;
        if (paymentType) {
            addPaymentDetail(paymentType, typeValue);
            
            // Using jQuery to hide the modal, as Bootstrap's modal functionality is often tied to jQuery
            $('#paymentTypeModal').modal('hide');
        } else {
            alert('Please select a payment type.');
        }
    });
    document.getElementById('confirmMscButton').addEventListener('click', function () {
        const MiscTypeId = document.getElementById('MiscType').value;

        axios.get(base_url + "/ibcas/payment/getMiscTypeSpec/" + MiscTypeId)
            .then(function (response) {
                //    console.log(response.data.miscType);
                if (FeesArrayChecker.includes(response.data.miscType[0].id)) {
                    Swal.fire({
                        title: "Duplicate Fee, you cannot add it again.",
                        text: `${response.data.miscType[0].name.toUpperCase()} is already added.`,
                        icon: "warning"
                      });

                    return;
                }
                const container = document.getElementById('MiscellaneousPaymentDetails');
                const detailDiv = document.createElement('div');
                detailDiv.classList.add('msc-detail');

                let innerHTML = `
           <div class="row">
                <input type="hidden" class="form-control " name="mscFeeid[]" value="${response.data.miscType[0].id}"/>
               
            <div class="col-5" style="margin-bottom: 10px;">
               <input type="text" class="form-control " readonly name="mscFeeName[]" value="${response.data.miscType[0].name}"/>
               </div>
               <div class="col-5" style="margin-bottom: 10px;">
               <input type="text" class="form-control fees curr" name="mscFeeAmount[]" value="${response.data.miscType[0].amount}"/>
             </div>
             <div class="col-2" style="margin-bottom: 10px;">
             <button class="btn btn-sm btn-danger remove-button-fees" data-id="${response.data.miscType[0].id}" style="float:right">Remove</button>
           </div>
             </div>
             `;



                // Set the innerHTML to the detailDiv
                detailDiv.innerHTML = innerHTML;

                // Append the new detailDiv to the container
                container.appendChild(detailDiv);

                FeesArrayChecker.push(response.data.miscType[0].id);
                // console.log(FeesArrayChecker);
                curr();
                calculateTotalFees();

                        // Add remove functionality to the newly added remove button
                        const removeButtonsFees = detailDiv.getElementsByClassName('remove-button-fees');
                        Array.from(removeButtonsFees).forEach(button => {
                            button.addEventListener('click', function() {
                                var dataId = this.getAttribute('data-id'); // Retrieve the data-id attribute
                                removeDetail(this.closest('.msc-detail')); // Remove the closest .msc-detail element
                        
                                // Filter the FeesArrayChecker array to remove the element with the dataId
                                FeesArrayChecker = FeesArrayChecker.filter(element => element !== dataId);
                                // console.log(FeesArrayChecker);
                                // Optionally, you can uncomment the following line to update the total after removing an item
                                // updateTotal();
                                calculateTotalFees();
                            });
                        });
                        
                //    document.getElementById('totalAmountDue').value = parseFloat(response.data.miscType[0].amount);
                //    $('#totalAmountDue').val(response.data.miscType.amount);

            })
            .catch(function (error) {
                console.log(error);
            });
        $('#MiscellaneousPaymentModal').modal('hide');
        //    console.log(FeesArrayChecker);
        
    });

    // auto computer
    $('#amountTendered').on('input', function () {
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
    $(document).on('submit', '#form-post-payment', function (e) {
        e.preventDefault();
         
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
        var arr = $(this).serialize();

        axios.post(base_url + "/ibcas/payment/post-payment-msc", arr)
            .then(function (response) {
                console.log(response);
                if (response.data.status == 200) {

                    $('#tstsuccess strong').text(response.data.message);
                    toastsuccess.show();
                        showOverlay('Loading Print OR...');
                        setTimeout(function(){
                                hideOverlay();
                      // Open a new tab with a specified URL
                      var newTab = window.open(base_url + '/ibcas/print/Official-ReceiptMsc/' + response.data.miscellaneous_paymentsId, '_blank');

                      if (newTab) {
                          // Close the current tab after a delay (e.g., 2 seconds)
                          setTimeout(function() {
                              window.location.reload();
                          }, 2000); // Adjust the delay time as needed
                      } else {
                          alert('The new tab could not be opened.');
                      }


                          }, 1000);




                } else {
                    $('#tsterror strong').text('Error Posting Payment');

                    toasterror.show();
                }

            })
            .catch(function (error) {
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
    });

    // Change Date

    $(document).on('click', '#btnChangeDate', function () {
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

    $(document).on('click', '#btnChangeOrNumber', function () {
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
                        axios.post(base_url + "/ibcas/payment/check-or-avail", { ornum })
                            .then(function (response) {
                                // console.log(response.data.content);
                                if (response.data.status == 200) {
                                    $('#tstsuccess strong').text("Official Receipt Number Changed");
                                    // $('#tstwarning label').text('');
                                    toastsuccess.show({ delay: 100 });
                                    $('#ornumber_id').val(response.data.content.id);
                                    $('#ornumberlabel').val(response.data.content.ornum);
                                    // $('#offcanvasUser').offcanvas('show');
                                } else {
                                    // $('#tstwarning strong').text(response.data.message);
                                    // // $('#tstwarning label').text('');
                                    // toastwarning.show({ delay: 100 });
                                    Swal.fire({
                                        title: ornum,
                                        text: response.data.message,
                                        icon: "warning"
                                      });
                                    // $('#offcanvasUser').offcanvas('show');
                                }

                            })
                            .catch(function (error) {
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
    //         $('#mode_of_payment').trigger('change');

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
    // }

    function calculateTotalFees() {
        // Select all input elements with the class 'fees'
        var feesInputs = document.querySelectorAll('.fees');
    
        var total = 0;
        feesInputs.forEach(function (input) {
            // Remove any commas from the input value
            var valueWithoutCommas = input.value.replace(/,/g, '');
            // Parse the cleaned value as a float and add it to the total
            total += parseFloat(valueWithoutCommas) || 0;
        });
    
        console.log(total);
        document.getElementById('totalAmountDue').value = total.toFixed(2); // ensuring the result is a float with two decimal places
        // return total;
    }
    

    function curr() {
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
            <input type="number" class="form-control paytype" required name="checkAmount" placeholder="Amount" />
          </div>`;
            getbankNames();
        } else {
            innerHTML +=
                `<div class="col-6" style="margin-bottom: 10px;">
        <input type="hidden" name="assessment_payment_id_cash" value="${typeValue}"/>
        <input type="number" class="form-control paytype" name="${type}Amount" placeholder="Enter amount" required/>
      </div>`;
        }

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
        //   
        // Attach the event listener to all elements with the class 'paytype'
        var paytypeInputs = document.getElementsByClassName('paytype');
        paytypeInputs.forEach(function (input) {
            input.addEventListener('input', updateTotal);
        });


        // Add remove functionality to the newly added remove button
        const removeButtons = detailDiv.getElementsByClassName('remove-button');
        Array.from(removeButtons).forEach(button => {
            button.addEventListener('click', function () {
                removeDetail(this.closest('.payment-detail'));
                updateTotal();
            });
        });

        curr();

    }
    // Function to compute the total and update the amountTendered input
    function updateTotal() {
        var paytypeInputs = document.querySelectorAll('.paytype');

        var total = 0;
        paytypeInputs.forEach(function (input) {
            var value = parseFloat(input.value) || 0;
            total += value;
        });

        // Update the amountTendered input
        var amountTenderedInput = document.getElementById('amountTendered');
        amountTenderedInput.value = total.toFixed(2); // Formats the total to 2 decimal places
        $('#amountTendered').trigger('input');
    }
    function removeDetail(detailDiv) {
        // This function will remove the payment detail div
        detailDiv.remove();
    }

    function getbankNames() {
        axios.get(base_url + "/ibcas/payment/BankNames/")
            .then(function (response) {
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
            .catch(function (error) {
                console.log(error);
            });
    }

    function getpaymentType() {
        axios.get(base_url + "/ibcas/payment/getPaymentType/")
            .then(function (response) {
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
            .catch(function (error) {
                console.log(error);
            });
    }

    // function loadInfoNewPayment(amountToPay) {
    //     axios.get(base_url + "/ibcas/payment/getpaymentinfo/" + id)
    //     .then(function(response) {
    //         var data = response.data.paymentInfo[0];
    //         // console.log(response.data.nextOrNumber);

    //         $('#ornumber_id').val(response.data.nextOrNumber.id);
    //         $('#ornumberlabel').val(response.data.nextOrNumber.next_ornum);
    //         $('#application_id').val(data.application_id);
    //         $('#blpdNumber').val(data.blpdno);
    //         $('#taxPayer').val(data.tax_payer_name);
    //         $('#busName').val(data.business_name);
    //         // console.log(amountToPay);
    //         document.getElementById('totalAmountDue').value = parseFloat(amountToPay);
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
    //                 // console.log(data)
    //                 const table = document.getElementById("tbl_new_payment_fees");
    //                 for (let i = 0; i < data.length; i++) {
    //                     // console.log(data[i]);

    //                     html += `
    //                     <tr data-id="${data[i].id}" id="rowid${data[i].id}">
    //                         <td width="50%">${data[i].remarks}</td>
    //                         <td width="25%"><input type="number" readonly class="form-control currency" data-rowid="${data[i].id}" id="feesamount${i+1}" value="${data[i].amount}"></td>
    //                          </tr>
    //                     `;
    //                     // totalAmountDue =  parseFloat(totalAmountDue) + parseFloat(data[i].amount);

    //                 }
    //                 // console.log(totalAmountDue);
    //                 table.innerHTML = html;

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

    // function populSelFrom(num){
    //     const select = document.getElementById('instfr');
    //     select.innerHTML = '';
    //     for (let i = 0; i < num; i++) {
    //         // console.log(data[i]);
    //         const option = document.createElement('option');
    //                 option.value = i+1;
    //                 option.textContent = i+1;
    //                 select.appendChild(option);
    //         }
    // }
    // function populSelto(num){
    //     const select = document.getElementById('instto');
    //     select.innerHTML = '';
    //     for (let i = 0; i < num; i++) {
    //         // console.log(data[i]);
    //         const option = document.createElement('option');
    //                 option.value = i+1;
    //                 option.textContent = i+1;
    //                 select.appendChild(option);
    //         }
    // }

    // function populSelto(num){
    //     const select = document.getElementById('instto');

    //     // Clear existing options and set to read-only
    //     select.innerHTML = '';
    //     select.setAttribute('disabled', true);

    //     // Append only the option from the last loop
    //     if (num > 0) {
    //         const option = document.createElement('option');
    //         option.value = num;
    //         option.textContent = num;
    //         select.appendChild(option);
    //     }
    // }


});