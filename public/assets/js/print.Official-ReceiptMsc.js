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
                    font-size: 10px;
                }
                .lbl2{
                    font-size: 12px;
                }
                .lbl3{
                    font-size: 15px !important;
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
    var msc_payment_id = document.getElementById('msc_payment_id').value;

    getPaymentDetails(msc_payment_id);
    getFeesDetails(msc_payment_id);

    function getPaymentDetails(msc_payment_id){

        axios.get(base_url + "/ibcas/print/get-payment-detailsmsc/"+msc_payment_id)
        .then(function(response) {
            
            if (response.data.status == 200) {

                // console.log(response.data.busTax);
            //     $('#lbl_busname').text(response.data.applicationDetails.business_name)
                $('#blpd_no').text(response.data.PaymentDetails.blpdno === null ? "  " : response.data.PaymentDetails.blpdno);
                
            //     $('#moPayment').text(response.data.applicationDetails.modeofpayment)
            //     $('#taxYear').text(response.data.busTax[0].tax_year)
            //     $('#busTaxAmount').text(response.data.busTax[0].tamount)
            //     $('#mayorsFeeAmount').text(response.data.MayorsFee[0].tamount)
                $('#lbl_busname').text(response.data.PaymentDetails.payors_name)
                $('#lbl_taxpayer').text('');
                $('#date_paid').text(response.data.PaymentDetails.date_paid)
                $('#ornumpaid').text(response.data.PaymentDetails.orNumber)
                $('#paidTotal').text(response.data.PaymentDetails.PaidAmount)
            //     $('#DueDate').text(response.data.duedate)
                $('#amountToWords').text(numberToWords(response.data.PaymentDetails.PaidAmount));
                // $('#tsterror strong').text('Error Loading Stub Details');
                // toasterror.show({ delay: 100 });
            }
        })
        .catch(function(error) {
            console.log(error);
        });

    }

    function getFeesDetails(msc_payment_id){

        axios.get(base_url + "/ibcas/print/get-fees-detailsmsc/"+msc_payment_id)
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
  
    // Function to truncate remarks with more than 20 characters and add line breaks
    function truncateRemark(remark) {
        let result = '';
        for (let i = 0; i < remark.length; i += 25) {
            result += remark.substring(i, i + 25) + "<br>";
        }
        return result;
    }
    
    // Process each item individually
    data.forEach(item => {
        const row = document.createElement('tr');
      
        // Column for the truncated remark
        const remarkCell = document.createElement('td');
        remarkCell.innerHTML = truncateRemark(item.remarks); // Use innerHTML to handle line breaks
        remarkCell.className = 'lbl2 leftalign'; // Add class lbl2
        row.appendChild(remarkCell);
  
        // Column for the amount
        const amountCell = document.createElement('td');
        amountCell.textContent = item.amount;
        amountCell.className = 'lbl2 rightalign';
        row.appendChild(amountCell);
  
        tableBody.appendChild(row);
    });
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