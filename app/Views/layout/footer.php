<script src="<?php echo base_url('assets/libs/jquery/jquery.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/metismenu/metisMenu.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/simplebar/simplebar.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/node-waves/waves.min.js');?>"></script>
<!-- Required datatable js -->
<script src="<?php echo base_url('assets/libs/datatables.net/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo base_url('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js');?>"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script> -->

<!-- Buttons examples -->
<!-- <script src="<?php echo base_url('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js');?>"></script> -->
<!-- <script src="<?php echo base_url('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js');?>"></script> -->
<script src="<?php echo base_url('assets/libs/toastr/build/toastr.min.js')?>"></script>
<!-- apexcharts -->
<script src="<?php echo base_url('assets/libs/apexcharts/apexcharts.min.js');?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo base_url('assets/js/pages/dashboard.init.js');?>"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> -->
<script src="<?php echo base_url('assets/js/pages/axios.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/pages/popper.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/pages/jquery.inputmask.bundle.js');?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
<script>
 var base_url = "<?php echo base_url(); ?>";

                // Function to show the overlay
                function showOverlay(loadingmessage) {
                document.getElementById("overlay").style.display = "flex";
                document.getElementById("loading-text").innerHTML = loadingmessage;
              }

              // Function to hide the overlay
              function hideOverlay() {
                document.getElementById("overlay").style.display = "none";
                document.getElementById("loading-text").innerHTML = "Loading...";
              }
              hideOverlay();

var fromDateInput = document.getElementsByClassName('DateFrom');
var toDateInput = document.getElementsByClassName('DateTo');




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
    function formatDateToLongForm(inputDate) {
    // Parse the input date string
    const dateParts = inputDate.split('-');
    const year = parseInt(dateParts[0], 10);
    const month = parseInt(dateParts[1], 10) - 1; // Month is 0-indexed in JavaScript Date
    const day = parseInt(dateParts[2], 10);

    // Create a new Date object
    const date = new Date(year, month, day);

    // Array of month names to convert month number to month name
    const monthNames = ["January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"];

    // Format the date in 'Month day, Year' format
    const formattedDate = `${monthNames[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;

    return formattedDate;
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
        console.log('From date changed (input event): ', event.target.value);
    });

    // Optional: Listen for 'change' event if you want to perform actions when the selection is finalized
    fromDateInput.addEventListener('change', function(event) {
        // Log the finalized selection (for demonstration purposes)
        console.log('From date selection finalized (change event): ', event.target.value);
    });



function exportToExcel(fileName, fileContent, sheetName) {
        // Create a new workbook and add a worksheet
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.json_to_sheet(fileContent);
    
        // Add the worksheet to the workbook
        XLSX.utils.book_append_sheet(wb, ws, sheetName);
    
        // Generate an Excel file (default type is 'xlsx')
        var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });
    
        // Convert string to ArrayBuffer
        function s2ab(s) {
            var buffer = new ArrayBuffer(s.length);
            var view = new Uint8Array(buffer);
            for (var i = 0; i < s.length; i++) {
                view[i] = s.charCodeAt(i) & 0xFF;
            }
            return buffer;
        }
    
        // Create a Blob for the Excel File
        var blob = new Blob([s2ab(wbout)], {
            type: 'application/octet-stream'
        });
    
        // Create an anchor element and trigger a download
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = `${fileName}.xlsx`;
        document.body.appendChild(link); // Append to html link element page
        link.click(); // Start download
        document.body.removeChild(link); // Clean up and remove the link
    }

    function openSmallWindow(url) {
    // Specify the width and height of the new window
    var width = 600;
    var height = 400;

    // Calculate the position to center the window on the screen
    var left = (screen.width / 2) - (width / 2);
    var top = (screen.height / 2) - (height / 2);

    // Specify features of the new window
    var features = 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left;

    // Open the new window
    window.open(url, 'newWindow', features);
}



function showLogoutAlert() {
    const logoutAlert = document.getElementById('logoutAlert');
    logoutAlert.classList.remove('d-none'); // Show the alert

    setTimeout(() => {
        logoutAlert.classList.add('d-none'); // Hide the alert after 5 seconds
        var url = base_url + "/ibcas/login/";
                window.location.href = url;
    }, 2000);
}
    
function btnLogout(){
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, log me out!'
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, proceed with the logout
            axios.get(base_url + "/ibcas/logout")
            .then(function(response) {
                // console.log(response);
                if(response.data.status = 201){
                    showLogoutAlert();

                    
            } 
            })
            .catch(function(error) {
                console.log(error);
            });
        }
    });
   
}


</script>
<!-- <script src="<?php //echo base_url('assets/js/logout.js');?>"></script> -->

<?php $uri = current_url(true); 
// echo $uri->getSegment(3);
?>

<!-- Custom JS -->
<?php if ($uri->getSegment(4) == '') {
    $urlseg3 = $uri->getSegment(3);
?>
    <script src="<?php echo base_url('assets/js/'.$urlseg3.'.js'); ?>"></script>
<?php } else { 
    $urlseg3 = $uri->getSegment(3);    
    $urlseg4 = $uri->getSegment(4);    
?>
    <script src="<?php echo base_url('assets/js/'.$urlseg3.'.'.$urlseg4.'.js'); ?>"></script>
<?php } ?>