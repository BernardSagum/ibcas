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
                width: 95mm; 
                height: auto;
                visibility: visible;
                margin: 10px;
                transform: scale(0.9);
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
    var installment_id = document.getElementById('installment_id').value;

    getApplicationDetails(assessment_slip_id,installment_id);



    function getApplicationDetails(assessment_slip_id,installment_id){

        if (installment_id == '1') {
            axios.get(base_url + "/ibcas/print/get-claimstub-details/"+assessment_slip_id)
            .then(function(response) {
                
                if (response.data.status == 200) {
                    // console.log(response.data.applicationDetails);
                    $('#blpdNumber').text(response.data.applicationDetails.blpdno)
                    $('#businessName').text(response.data.applicationDetails.business_name)
                    $('#TaxPayerName').text(response.data.applicationDetails.tax_payer_name)
                    $('#DueDate').text(response.data.duedate)
                } else {
                    $('#tsterror strong').text('Error Loading Stub Details');
                    toasterror.show({ delay: 100 });
                }
            })
            .catch(function(error) {
                console.log(error);
            });
        } else {
            
        }


    }




});