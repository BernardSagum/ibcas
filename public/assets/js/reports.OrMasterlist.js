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
$(document).ready(function (){
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));

    $('#frm_loading').attr('hidden',true);
    // toastr.success('Generated and Downloaded', 'Success...', { "timeOut": "2000" });
    $('#ftr_val').inputmask('9999999');

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

      $(document).on('click','#PrintOr',function(){
        printDivContent();
      });
      $(document).on('submit','#frm-searchOrNum',function(e){
        e.preventDefault();
        var arr = $(this).serialize()
        $('#frm_submit').attr('hidden',true);
        $('#frm_loading').attr('hidden',false);   
        
        Swal.fire({
            title: 'Search Official Receipt Number?',
            text: "",
            icon: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Search'
            }).then((result) => {
              if (result.isConfirmed) {
                  $('#frm_submit').attr('hidden',false);
                  $('#frm_loading').attr('hidden',true);  
                  showOverlay('Searching....');
                  axios.post(base_url + "/ibcas/search-or-details", arr)
                      .then(function(response) {
                        hideOverlay();
                          
                          if (response.data.status == 200){
                            showOverlay('Loading Official Receipt..')
                            // console.log(response.data.PaymentDetails[0].blpdno);
                           // console.log(response.data.applicationDetails);
                        $('#lbl_busname').text(response.data.applicationDetails[0].business_name)
                        $('#blpd_no').text(response.data.applicationDetails[0].blpdno)
                        $('#moPayment').text(response.data.applicationDetails[0].modeofpayment)
                        $('#taxYear').text(response.data.busTax[0].tax_year)
                        $('#busTaxAmount').text(response.data.busTax[0].tamount)
                        $('#mayorsFeeAmount').text(response.data.MayorsFee[0].tamount)
                 
                        $('#lbl_taxpayer').text(response.data.applicationDetails[0].tax_payer_name)
                        $('#date_paid').text(response.data.applicationDetails[0].date_paid)
                        $('#ornumpaid').text(response.data.applicationDetails[0].orNumber)
                        $('#paidTotal').text(response.data.applicationDetails[0].PaidAmount)
                    //     $('#DueDate').text(response.data.duedate)
                        $('#amountToWords').text(numberToWords(response.data.applicationDetails[0].PaidAmount));
                           
                             
                              
                                      // $('#tstsuccess strong').text(response.data.message);
                                      // toastsuccess.show();
                                      var dataFees = response.data.feesList;

                                      populateTable(dataFees); 
                                      $('#tstsuccess strong').text(response.data.message);
                                      toastsuccess.show();    
                                      hideOverlay() 
                             $('#Pdiv').attr('hidden',false);
                          } else {
                              $('#tsterror strong').text(response.data.message);
                              toasterror.show();
                              $('#Pdiv').attr('hidden',true);
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

      function populateTable(data) {
        const tableBody = document.getElementById('feesTable').getElementsByTagName('tbody')[0];
        
        // Clear existing rows from the table
        tableBody.innerHTML = '';
    
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
    







})