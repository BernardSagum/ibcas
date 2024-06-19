$(document).ready(function(){
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
    $(document).on('submit','#ftr-delinquencies',function(e){
        e.preventDefault();
        var arr = $(this).serialize();
        axios.post(base_url + "/ibcas/report/ftr-delinquencies", arr)
        .then(function (response) {
            var data = response.data.TableContent;
            // Call the function to download the Excel file
            exportToExcel('List Of Delinquencies', data, 'Delinquents');
            toastr.success('Extracted', 'Success...', { "timeOut": "2000" });
        })
        .catch(function (error) {
            console.log(error);
        });
       


        
    });





    var fromDateInput = document.getElementById('DateFrom');
    var toDateInput = document.getElementById('DateTo');

    // Function to format date to YYYY-MM-DD
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year, month, day].join('-');
    }

    // Set max attribute to today's date for both inputs
    var today = new Date();
    var maxDate = formatDate(today);
    fromDateInput.setAttribute('max', maxDate);
    toDateInput.setAttribute('max', maxDate);

    // Listen for 'input' or 'change' events on the 'From' date
    fromDateInput.addEventListener('input', function(event) {
        // Set the 'To' date's min attribute to the selected 'From' date
        toDateInput.setAttribute('min', event.target.value);

        // Log the change (for demonstration purposes)
        // console.log('From date changed (input event): ', event.target.value);
    });

    // Optional: Listen for 'change' event if you want to perform actions when the selection is finalized
    fromDateInput.addEventListener('change', function(event) {
        // Log the finalized selection (for demonstration purposes)
        toDateInput.setAttribute('min', event.target.value);
    });

});