$(document).ready(function(){

    var blpdNumber = document.getElementById('blpdNum').value;

    getBusinessDetails(blpdNumber);

    getBusinessHistory(blpdNumber);

    function getBusinessDetails(blpdNumber){
        axios.get(base_url + "/ibcas/reports/getBusinessDetails/"+blpdNumber)
        .then(function(response) {
            
            if (response.data.status == 200) {

            
                $('#blpd_no').text(response.data.appliationDetails.blpdno);
                $('#lbl_taxpayer').text(response.data.appliationDetails.tax_payer_name);
                $('#lbl_busname').text(response.data.appliationDetails.business_name);
                $('#lbl_busAddress').text(response.data.appliationDetails.taxpayer_address);

            }
        })
        .catch(function(error) {
            console.log(error);
        });
    }

    function getBusinessHistory(blpdNumber){
        axios.get(base_url + "/ibcas/reports/getBusinessHistory/"+blpdNumber)
        .then(function(response) {
            
            if (response.data.status == 200) {

                var data = response.data.tableContent;

            // $('#tbl_BusinessMasterlist').dataTable().fnDestroy();

            var BUSINESSLEDGER = $('#tbl_businessLedger').DataTable({
                data: data,
                fnCreatedRow: function(nRow, data, iDisplayIndex) {
                    $(nRow).attr('data-id', data.id);
                //     $(nRow).attr('data-uniqueid', data.uniqueid);
                },
                columns: [
                    { data: 'TransactionDate' },
                    { data: 'TaxYear' },
                    { data: 'ModeOfPayment' },
                    { data: 'TransactionType' },
                    { data: 'TransactionNo' },
                    { data: 'Assessment' },
                    { data: 'Interest' },
                    { data: 'Payment' },
                    { data: 'Balance' },
                  
                    
                    
                ],                
                'dom': '<"wrapper"Bfritp>',
                   'order': [
                       [0, "asc"]
                   ],
            
                   "language": {
                       "emptyTable": " No Record Found"
                   },
                   'paging': false,
                   'ordering': true,
                   'info': false,
                   'searching': false,
                   'paging': false,
                //    "pageLength": 10,
            });
            }
        })
        .catch(function(error) {
            console.log(error);
        });
    }



});