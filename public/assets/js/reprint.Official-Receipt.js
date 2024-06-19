function printDivContent() {
    // Create an iframe element
    var iframe = document.createElement('iframe');
    iframe.style.position = 'absolute';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);

    // Write the div content to the iframe document
    var content = document.getElementById('printDiv').innerHTML;
    iframe.contentDocument.open();
    iframe.contentDocument.write('<html><head><title>Print Claim Stub</title>');
    // Add any required styles for printing here
    iframe.contentDocument.write(`
    <style>
        @media print 
            { 
                body { 
                width: 85mm; 
                height: auto;
                visibility: visible;
                margin: 10px
                font-family: Arial, Helvetica, sans-serif;
                } 
                .lbl1{
                    font-size: 7px;
                }
                .lbl2{
                    font-size: 10px;
                }
                .lbl3{
                    font-size: 12px !important;
                }
                #margTop {
                    margin-top: 190px;
                }
                .leftalign{
                    text-align : left;
                }
                .rightalign{
                    text-align : right;
                }
            }

        </style>`);
    iframe.contentDocument.write('</head><body>');
    iframe.contentDocument.write(content);
    iframe.contentDocument.write('</body></html>');
    iframe.contentDocument.close();

    // Wait for the iframe content to load and trigger the print dialog
    iframe.onload = function() {
        iframe.contentWindow.focus(); // Required for IE
        iframe.contentWindow.print();
        document.body.removeChild(iframe); // Remove the iframe after printing
    };
}
$(document).ready(function(){
    var assessment_slip_id = document.getElementById('assessment_slip_id').value;
    $(document).on('click','#PrintOr',function(){
        
        
        printDivContent();
           
        setTimeout(function(){
            Swal.fire({
                title: 'Print Claim Stub?',
                // text: "You won't be able to revert this",
                icon: 'question',
                showConfirmButton: true,
                // showCancelButton: true,
                
                confirmButtonText: 'Print',
                cancelButtonText: 'cancel',
                reverseButtons: true, // This option will switch the buttons' positions
                confirmButtonColor: '#34c38f', // Green color for the confirm button
                cancelButtonColor: '#dc3545', // Red color for the cancel button
                buttonsStyling: true
              }).then((result) => {
                if (result.isConfirmed) {
                    var newTab = window.open(base_url + '/ibcas/print/claimStub/' + assessment_slip_id, '_blank');
                    if (newTab) {
                      // Close the current window
                      window.close();
                  } else {
                      alert('The new tab could not be opened.');
                  }
                } else if (
                  /* Read more about handling dismissals below */
                  result.dismiss === Swal.DismissReason.cancel
                ) {
                  swalWithBootstrapButtons.fire({
                    title: "Claimstub Printing Cancelled",
                    text: "",
                    icon: "error"
                  });
                
                  var newTab = window.open(base_url + '/ibcas/payment/');
                  if (newTab) {
                    // Close the current window
                    window.close();
                } else {
                    alert('The new tab could not be opened.');
                }
                }
              });
          
        }, 5000);
       
    });



    

    getApplicationDetails(assessment_slip_id);
    getFeesDetails(assessment_slip_id);

    function getApplicationDetails(assessment_slip_id){

        axios.get(base_url + "/ibcas/print/get-payment-details/"+assessment_slip_id)
        .then(function(response) {
            
            if (response.data.status == 200) {

                console.log(response.data.busTax);
                $('#lbl_busname').text(response.data.applicationDetails.business_name)
                $('#blpd_no').text(response.data.applicationDetails.blpdno)
                $('#moPayment').text(response.data.applicationDetails.modeofpayment)
                $('#taxYear').text(response.data.busTax[0].tax_year)
                $('#busTaxAmount').text(response.data.busTax[0].tamount)
                $('#mayorsFeeAmount').text(response.data.MayorsFee[0].tamount)
            //     $('#businessName').text(response.data.applicationDetails.business_name)
                $('#lbl_taxpayer').text(response.data.applicationDetails.tax_payer_name)
                $('#date_paid').text(response.data.applicationDetails.date_paid)
                $('#ornumpaid').text(response.data.applicationDetails.orNumber)
                $('#paidTotal').text(response.data.applicationDetails.PaidAmount)
            //     $('#DueDate').text(response.data.duedate)
                $('#amountToWords').text(numberToWords(response.data.applicationDetails.PaidAmount));
                $('#tsterror strong').text('Error Loading Stub Details');
                toasterror.show({ delay: 100 });
            }
        })
        .catch(function(error) {
            console.log(error);
        });

    }

    function getFeesDetails(assessment_slip_id){

        axios.get(base_url + "/ibcas/print/get-fees-details/"+assessment_slip_id)
        .then(function(response) {
            
            if (response.data.status == 200) {

                console.log(response.data.feesList);
                var data = response.data.feesList;

                populateTable(data);

                
                
            
            } else {
                $('#tsterror strong').text('Error Loading Stub Details');
                toasterror.show({ delay: 100 });
            }
        })
        .catch(function(error) {
            console.log(error);
        });

    }

    
// function populateTable(data) {
//     const tableBody = document.getElementById('feesTable').getElementsByTagName('tbody')[0];
  
//     // Process two items at a time
//     for (let i = 0; i < data.length; i += 2) {
//       const row = document.createElement('tr');
      
//       // Column for the remark of the first item
//       const remarkCell1 = document.createElement('td');
//       remarkCell1.textContent = data[i].remarks;
//       row.appendChild(remarkCell1);
  
//       // Column for the amount of the first item
//       const amountCell1 = document.createElement('td');
//       amountCell1.textContent = data[i].amount;
//       row.appendChild(amountCell1);
  
//       // Check if the second item exists
//       if (data[i + 1]) {
//         // Column for the remark of the second item
//         const remarkCell2 = document.createElement('td');
//         remarkCell2.textContent = data[i + 1].remarks;
//         row.appendChild(remarkCell2);
  
//         // Column for the amount of the second item
//         const amountCell2 = document.createElement('td');
//         amountCell2.textContent = data[i + 1].amount;
//         row.appendChild(amountCell2);
//       } else {
//         // If there is no second item, add empty cells to complete the row
//         const emptyCell1 = document.createElement('td');
//         const emptyCell2 = document.createElement('td');
//         row.appendChild(emptyCell1);
//         row.appendChild(emptyCell2);
//       }
  
//       tableBody.appendChild(row);
//     }
//   }


function populateTable(data) {
    const tableBody = document.getElementById('feesTable').getElementsByTagName('tbody')[0];
  
    // Function to truncate remarks with more than 20 characters
    function truncateRemark(remark) {
      return remark.length > 20 ? remark.substring(0, 17) + "..." : remark;
    }
  
    // Process two items at a time
    for (let i = 0; i < data.length; i += 2) {
      const row = document.createElement('tr');
      
      // Column for the truncated remark of the first item
      const remarkCell1 = document.createElement('td');
      remarkCell1.textContent = truncateRemark(data[i].remarks);
      remarkCell1.className = 'lbl2 leftalign'; // Add class lbl1
      row.appendChild(remarkCell1);
  
      // Column for the amount of the first item
      const amountCell1 = document.createElement('td');
      amountCell1.textContent = data[i].amount;
      amountCell1.className = 'lbl2 rightalign';
      row.appendChild(amountCell1);
  
      // Check if the second item exists
      if (data[i + 1]) {
        // Column for the truncated remark of the second item
        const remarkCell2 = document.createElement('td');
        remarkCell2.textContent = truncateRemark(data[i + 1].remarks);
        remarkCell2.className = 'lbl2 leftalign'; // Add class lbl1
        row.appendChild(remarkCell2);
  
        // Column for the amount of the second item
        const amountCell2 = document.createElement('td');
        amountCell2.textContent = data[i + 1].amount;
        amountCell2.className = 'lbl2 rightalign';
        row.appendChild(amountCell2);
      } else {
        // If there is no second item, add empty cells to complete the row
        const emptyCell1 = document.createElement('td');
        emptyCell1.className = 'lbl2'; // Add class lbl1
        const emptyCell2 = document.createElement('td');
        row.appendChild(emptyCell1);
        row.appendChild(emptyCell2);
      }
  
      tableBody.appendChild(row);
    }
  }

  function numberToWords(num) {
    const ones = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
    const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

    function convertHundreds(n) {
        let words = '';

        if (n > 99) {
            words += ones[Math.floor(n / 100)] + ' Hundred ';
            n %= 100;
        }
        if (n > 19) {
            words += tens[Math.floor(n / 10)] + ' ';
            n %= 10;
        }
        if (n > 0) {
            words += (words !== '' ? '' : '') + (n < 10 ? ones[n] : teens[n - 10]) + ' ';
        }

        return words;
    }

    function convert(num) {
        if (num === 0) return 'Zero';
        let words = '';
        let thousands = Math.floor(num / 1000);
        let remainder = num % 1000;

        if (thousands > 0) words += convertHundreds(thousands) + 'Thousand ';
        if (remainder > 0) words += convertHundreds(remainder);

        return words.trim();
    }

    let integerPart = Math.floor(num);
    let decimalPart = Math.round((num - integerPart) * 100);
    let words = convert(integerPart) + ' Pesos';

    if (decimalPart > 0) {
        words += ' and ' + convert(decimalPart) + ' Centavos';
    }

    return words;
}


});