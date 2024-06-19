<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Official Receipt
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 

?>
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
                        <h4 class="mb-sm-0 font-size-18">OFFICIAL RECEIPT</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">Business</li>
                                <li class="breadcrumb-item active">Print</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            <!-- end page title -->
            <style>
        /* Styles for on-screen display (not for print) */
        #printDiv {
            
            margin: 20px auto;
            padding: 20px;
            padding-left: 22px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Hide everything except the print container for printing */
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
                width: 95mm; /* Half the width of A4 */
                height: 297mm; /* Full height of A4 */
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }
    </style>
    
            <div class="row">
                <input type="hidden" id="assessment_slip_id" name="assessment_slip_id" value="<?php echo $assessment_id;?>">  
                <input type="hidden" id="installment_id" name="installment_id" value="<?php echo $installment_id;?>">  
                <div class="col-xl-2"></div>
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-secondary" id="PrintOr" ><?php echo $action ?></button>
                                </div>
                            </div>
                            <div id="printDiv" style="font-family: Arial, Helvetica, sans-serif;">
                            <div style="font-family: Arial, Helvetica, sans-serif;">
                                <div class="row" id="margTop"  style="text-align: left; ">
                                    <div class="col-5"><label id="date_paid"></label></div>
                                    <div class="col-7"></div>
                                </div>
                
                                <div class="row" style="text-align: right; margin-top: 17px">
                                    <div class="col-8"><label></label></div>
                                    <div class="col-4"><label class="lbl1" id="ornumpaid"></label><br><label class="lbl1" id="blpd_no"></label></div>
                                </div>
                                <div class="row" style="text-align: center;">
                                    <div class="col-12" style="margin-bottom: 0;"><label id="lbl_taxpayer" class="lbl2"></label></div>
                                    <div class="col-12" style="margin-top: 0;"><label id="lbl_busname" style="font-size: 20px; font-weight:bold" class="lbl3">LAKESHORE, S.F. FOOD PHILS. INC</label></div>
                                </div>
                                <div class="row" >
                                    <div class="col-12">
                                        <label class="lbl2"></label>
                                        <label class="lbl2" style="float: right !important;"></label>
                                    </div>
                                </div>
                                <div class="row" style="text-align: center; margin-top: 40px;">
                                <table style="width:98%; font-weight: bold;" >
                                    <tr>
                                        <td style="width:53%"><label class="lbl3" style="font-size: 15px;">Business Tax</label></td>
                                        <td style="width:20%"><label id="taxYear" class="lbl3">2023</label></td>
                                        <td style="width:27%; text-align:right; margin-right:20px;"><label id="busTaxAmount" class="lbl3">50,000.00</label></td>
                                    </tr>
                                    <tr>
                                        <td style="width:53%"><label class="lbl3" style="font-size: 15px;">Mayor's Fee</label></td>
                                        <td style="width:30%"><label style="font-size: 10px !important; font-weight:bold !important;" class="lbl4" id="moPayment">ANNUAL</label></td>
                                        <td style="width:20%; text-align:right; margin-right:20px;"><label id="mayorsFeeAmount" class="lbl3">2,684.62</label></td>
                                    </tr>
                                </table>
                                <div style="min-height: 14%; max-height: 14%;">
                                <table id="feesTable" style="text-align:center; width:95%" hidden>
                                <tbody>
                                    <!-- Rows will be added here by JavaScript -->
                                </tbody>
                                </table>
                                </div>
                                <div class="row" style="margin-top:3%">
                                    <div class="col-12" style="text-align: right;">
                                        <label class="lbl3" style="font-weight:bold !important;" id="paidTotal">0.00</label>
                                    </div>
                                  
                                </div>
                                <div class="row" style="margin-top:3%">
                                    <div class="col-12" style="text-align: left;">
                                        <label class="lbl3" style="font-weight:bold !important;" id="amountToWords"></label>
                                    </div>
                                  
                                </div>




                                </div>

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