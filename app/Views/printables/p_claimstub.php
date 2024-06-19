<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Claim Stub
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 

?>
<style>
    /* Styles for on-screen display (not for print) */
    #printDiv {
        width: 105mm;
        margin: 20px auto;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Print styles - applied only when printing */
    @media print {
        body * {
            visibility: hidden;
        }
        #printDiv, #printDiv * {
            visibility: visible;
        }
        #printDiv {
            position: absolute;
            left: 0;
            top: 0;
            width: 105mm; /* Half the width of A4 */
            height: auto; /* Let the content determine the height */
            margin: 0;
            padding: 0;
            box-shadow: none;
            overflow: visible; /* Ensure all content is printed */
            page-break-after: always; /* Add a page break after the element */
            page-break-inside: avoid; /* Avoid breaking the content inside */
        }
    }
</style>
<div class="main-content">
    <div class="page-content">
    <div class="position-fixed p-3" style="z-index: 1005; left:50%; transform:translate(-50%,-80%); color:aliceblue;">
            <div role="alert" aria-live="assertive" aria-atomic="true" id="toastsuccess"
                class="toast align-items-center fade bg-success border-0">
                <div class="d-flex">
                    <div class="me-0 m-auto ms-2"><i class="mdi mdi-information text-white fs-4"></i></div>
                    <div class="toast-body text-white" id="tstsuccess">
                        <strong>Sucessfully saved. </strong> 
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div class="position-fixed p-3" style="z-index: 1005; left:50%; transform:translate(-50%,-65%);color:aliceblue;">
            <div role="alert" aria-live="assertive" aria-atomic="true" id="toasterror"
                class="toast align-items-center fade  bg-danger border-0 mb-4">
                <div class="d-flex">
                    <div class="me-0 m-auto ms-2"><i class="mdi mdi-close-circle text-white  fs-4"></i></div>
                    <div class="toast-body text-white " id="tsterror">
                        <strong>Error</strong> 
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div class="position-fixed p-3" style="z-index: 1005; left:50%; transform:translate(-50%,-65%);color:aliceblue;">
            <div role="alert" aria-live="assertive" aria-atomic="true" id="toastwarning"
                class="toast align-items-center fade  bg-warning  border-0 mb-4">
                <div class="d-flex">
                    <div class="me-0 m-auto ms-2"><i class="mdi mdi-information text-white fs-4"></i></div>
                    <div class="toast-body text-white" id="tstwarning">
                        <strong>Unable to save changes.</strong>
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Claim Stub</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">Claimstub</li>
                                <li class="breadcrumb-item active">Print</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            <!-- end page title -->

            <div class="row">
                <input type="hidden" id="assessment_slip_id" name="assessment_slip_id" value="<?php echo $assessment_slip_id;?>">  
                <input type="hidden" id="installment_id" name="installment_id" value="<?php echo $installment_id;?>">  
                <div class="col-xl-2"></div>
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-secondary" onclick="printDivContent()">Print Claimstub</button>
                                </div>
                            </div>
                            <div id="printDiv">
                            
                                <div class="row" style="text-align: center; font-family: Arial, Helvetica, sans-serif;">
                                    <div class="col-12" >
                                        <label style="font-weight: bold; font-size:15px">Business License and Permit Division (BLPD)</label><br>
                                        <label>City of San Fernando (P)</label><br>
                                        <label style="font-size:13px">Tel.No. (045) 961-6640 loc.127; (045) 455-0397</label>
                                    </div>
                                    
                                    <div class="col-12" style="margin-top: 15px;">
                                    <label style="font-weight: bold; font-size:16px">ACKNOWLEDGEMENT RECEIPT</label><br>
                                    <label style="font-size:14px; font-weight:bold">(CLAIM STUB)</label><br>
                                    
                                    </div>
                                    <div class="col-1"></div>
                                    <div class="col-10">
                                    <p style="text-align: justify; font-size:13px">Your application filled on this office is hereby acknowledged. If it is compliant, the permit will be issued on the Due Date indicated below</p>
                                    </div>
                                    <div class="col-1"></div>
                                </div>
                                <div class="row" style="text-align: center; font-family: Arial, Helvetica, sans-serif;">
                                    <div class="col-1"></div>
                                    <div class="col-10">
                                       <table  style="width: 100%; text-align:left;font-size:13px;">
                                        <tr class="placeholder-glow">
                                            <td style="width: 40%;">Date:</td>
                                            <td style="width: 60%;"><label style="font-weight: bold;"><?php echo date('M d, Y')?></label></td>
                                        </tr>
                                        <tr class="placeholder-glow">
                                            <td style="width: 40%;">Business Acct No:</td>
                                            <td style="width: 60%;"><label style="font-weight: bold;" id="blpdNumber"><span class="placeholder col-12"></span></label></td>
                                        </tr>
                                        <tr class="placeholder-glow">
                                            <td style="width: 40%;">Business Name:</td>
                                            <td style="width: 60%;"><label id="businessName" style="font-weight: bold;"><span class="placeholder col-12"></span></label></td>
                                        </tr>
                                        <tr class="placeholder-glow">
                                            <td style="width: 40%;">Owner's Name:</td>
                                            <td style="width: 60%;"><label id="TaxPayerName" style="font-weight: bold;"><span class="placeholder col-12"></span></label></td>
                                        </tr>
                                        <tr class="placeholder-glow">
                                            <td style="width: 40%;">Due Date:</td>
                                            <td style="width: 60%;"><label id="DueDate" style="font-weight: bold;"><span class="placeholder col-12"></span><?php echo date('M d, Y') ?></label></td>
                                        </tr>
                                       </table>
                                    </div>
                                    <div class="col-1"></div>
                                </div>
                                <br>
                                <div class="row" style="text-align: center; font-family: Arial, Helvetica, sans-serif; font-size:13px;">
                                    <div class="col-1"></div>
                                    <div class="col-10"><p>Mayor's Permit will not be Released without the corresponding Claim Stub</p></div>
                                    <div class="col-1"></div>
                                    </div>
                                    <div class="row" style="text-align: left; font-family: Arial, Helvetica, sans-serif; font-size:13px;">
                                    <div class="col-4">Released by: </div>
                                    <div class="col-8"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>

                </div>
                <div class="col-xl-2"></div>
            </div>

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    <!-- end modal -->

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>
                    document.write(new Date().getFullYear());
                    </script>
                    © City of San Fernando, Pampanga.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Designed &amp; Developed by City Information and Communication Technology Office
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
<?= $this->endSection() ?>