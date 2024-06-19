<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | OR Masterlist
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 

?>
<div class="main-content">
    <div class="page-content">

        <div class="container-fluid">
        <?= $this->include('layout/content_toastAlert') ?>
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Official Receipt</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">Reprint</li>
                                <li class="breadcrumb-item active">Official Receipt</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-3">
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <form id="frm-searchOrNum" class="needs-validation" novalidate>
                                <div class="row">

                                    <label for="horizontal-firstname-input" class="col-sm-12 col-form-label">Search
                                        By:</label>
                                    <div class="col-sm-12">
                                        <select class="form-select" id="ftr_selby" name="ftr_selby">
                                            <!-- <option value="" selected>All</option> -->
                                            <option value="ornum">Official Receipt Number</option>
                                            <!-- <option value="busname">Business Name</option>
                                            <option value="taxpayer">Tax Payer</option>
                                            <option value="transtatus">Transaction Status</option> -->
                                            <!-- <option value="">Payment Reference No.</option> -->
                                        </select>
                                        <br>
                                    </div>
                                   
                                    <div class="col-sm-12" id="ftr_valDiv">
                                        <input class="form-control" id="ftr_val" name="ftr_val" type="text" required>
                                    </div>
                                    <!-- <div class="col-sm-12" id="ftr_selValDiv" hidden>
                                    <select class="form-select" id="ftr_selVal" name="ftr_selVal">
                                             <option value="A" selected>All</option>
                                            <option value="_topay">Official Receipt Number</option>
                                             <option value="_partiallyPaid">Partially Paid</option>
                                            <option value="_fullyPaid">Fully Paid</option> 
                                            <option value="transtatus">Transaction Status</option> 
                                             <option value="">Payment Reference No.</option>
                                        </select>
                                    </div> -->
                                    <div class="col-sm-12">
                                    <br>
                                        <button type="submit" style="width: 100%;" id="frm_submit" data-placement="top" data-toggle="tooltip" title="Search" class="btn btn-primary">Search</button>
                                        <button type="button" id="frm_loading" style="width: 100%;"
                                            class="btn btn-sm btn-primary waves-effect waves-light" disabled="disabled"><span
                                                class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span> Loading...</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end card -->

                </div>

                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body">
                    <div id="Pdiv" hidden='true'>

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


<div class="row" >
                                <div class="col-12">
                                    <button class="btn  btn-secondary" id="PrintOr" >RePrint</button>
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
                                <table style="width:95%; font-weight: bold;">
                                    <tr>
                                        <td style="width:53%"><label class="lbl3" style="font-size: 15px;">Business Tax</label></td>
                                        <td style="width:20%"><label id="taxYear" class="lbl3">2023</label></td>
                                        <td style="width:27%; text-align:right; margin-right:20px;"><label id="busTaxAmount" class="lbl3">50,000.00</label></td>
                                    </tr>
                                    <tr>
                                        <td style="width:53%"><label class="lbl3" style="font-size: 15px;">Mayor's Fee</label></td>
                                        <td style="width:20%"><label class="lbl3" id="moPayment">ANNUAL</label></td>
                                        <td style="width:27%; text-align:right; margin-right:20px;"><label id="mayorsFeeAmount" class="lbl3">2,684.62</label></td>
                                    </tr>
                                </table>
                                <div style="min-height: 14%; max-height: 14%;">
                                <table id="feesTable" style="text-align:center; width:95%">
                                <tbody>
                                    <!-- Rows will be added here by JavaScript -->
                                </tbody>
                                </table>
                                </div>
                                <div class="row" style="margin-top:3%">
                                    <div class="col-12" style="text-align: right;">
                                        <label class="lbl3" id="paidTotal">0.00</label>
                                    </div>
                                  
                                </div>
                                <div class="row" style="margin-top:3%">
                                    <div class="col-12" style="text-align: left;">
                                        <label class="lbl3" id="amountToWords"></label>
                                    </div>
                                  
                                </div>




                                </div>

                            </div>
                                
                            </div>
                        </div>
                        </div>
                        <!-- end card body -->
                    </div>

                </div>
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