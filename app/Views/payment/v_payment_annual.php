<?= $this->extend('layout/default') ?>
<?= $this->section('title') ?>
iBCAS | Payment - Business
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php 
   ?>
<div class="main-content">
    <div class="page-content">
        <!-- <button class="btn btn-info" id="btnPrint">testPrint</button> -->
        <div class="position-fixed p-3"
            style="z-index: 1005; left:50%; transform:translate(-50%,-80%); color:aliceblue;">
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
        <div class="position-fixed p-3"
            style="z-index: 1005; left:50%; transform:translate(-50%,-65%);color:aliceblue;">
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
        <div class="position-fixed p-3"
            style="z-index: 1005; left:50%; transform:translate(-50%,-65%);color:aliceblue;">
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
        <!-- Hidden dropdown for selecting payment type -->
<!-- Modal Structure -->
<!-- <div id="paymentTypeModal" class="modal fade" tabindex="-1" role="dialog"> -->
<div class="modal fade" id="paymentTypeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Payment Type</h5>
      </div>
      <div class="modal-body">
        <select id="paymentTypeSelector" class="form-control">
          <option value="">Select Payment Type</option>
          <!-- <option value="cash">Cash</option>
          <option value="check">Check</option> -->
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="confirmPaymentTypeButton" class="btn btn-primary">Confirm</button>
      </div>
    </div>
  </div>
</div>
        <div class="container-fluid">
            <div style="width: 40%; background-color: #f8f8f8" class="offcanvas offcanvas-end" tabindex="-1"
                id="offcanvasUser" aria-labelledby="offcanvasUserLabel" aria-modal="true" role="dialog">
                <div class="offcanvas-header">
                    <h5 id="offcanvasUserLabel"></h5>
                    <button type="button" id="closeoffcanvasUser" class="btn-close text-reset"
                        data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="row">

                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">New Payment</h4>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <form id="form-post-payment" class="needs-validation" novalidate>
                            <input type="hidden" id="application_id" name="application_id">
                            <input type="hidden" id="assessment_slip_id" name="assessment_slip_id" value="<?php echo $p_id; ?>">
                            <!-- <input type="hidden" id="assessment_payment_id_check" name="assessment_payment_id_check" value="">
                            <input type="hidden" id="assessment_payment_id_cash" name="assessment_payment_id_cash" value=""> -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6" style="margin-bottom: 10px;">
                                            <label>O.R. Number as of <?php   date_default_timezone_set('Asia/Singapore');
                                                            echo date('M. d, Y h:i A'); ?> : </label>
                                            <!-- <input class="form-control" type="text" id="assign" name="assign" required> -->
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="ornumberlabel"
                                                    name="ornumberlabel" value="" disabled>
                                                <input type="hidden" class="form-control" id="ornumber_id" name="ornumber_id"
                                                    value="" required>
                                                <button class="btn btn-primary" type="button"
                                                    id="btnChangeOrNumber">Change O.R. Number</button>
                                            </div>
                                        </div>

                                        <div class="col-6" style="margin-bottom: 10px;">
                                            <label>Business Control Number : <span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" id="blpdNumber" name="blpdNumber"
                                                readonly required>
                                        </div>
                                        <div class="col-5" style="margin-bottom: 10px;">
                                            <label>Tax Payer : <span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" id="taxPayer" readonly name="taxPayer"
                                                required>
                                        </div>
                                        <div class="col-7" style="margin-bottom: 10px;">
                                            <label>Business Name : <span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" id="busName" readonly name="busName"
                                                required>
                                        </div>
                                        <div class="col-9"></div>
                                        <div class="col-3" style="margin-bottom: 10px;">
                                            <label><input type="checkbox" id="under_protest" name="under_protest" value="Y"> Paid Under
                                                Protest</label>

                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-12">
                                    <table class="table table-striped" id="tbl_new_payment_fees" width="90%" style="text-align: left; vertical-align: middle;">
                                    <tr>
                                       <td width="65%"></td>
                                       <td width="35%"></td>
                                     
                                    </tr>
                                    <tr class="placeholder-glow">
                                    <td><span class="placeholder col-12"></span></td>
                                       <td><span class="placeholder col-6"></span></td>
                                    </tr>
                                    </table>     
                                    </div>
                                 </div>
                                    <!-- <div class="row">
                                        <div class="col-6" style="margin-bottom: 10px;">
                                            <label>Business tax : <span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" id="assign" name="assign" required>
                                        </div>
                                        <div class="col-6" style="margin-bottom: 10px;">
                                            <label>Other Service Income Fees - Closure : <span
                                                    style="color:red;">*</span></label>
                                            <input class="form-control" type="text" id="assign" name="assign" required>
                                        </div>
                                    </div> -->
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-9">

                            

                                        </div>
                                        <div class="col-3">
                                            <label>Amount Due : </label>
                                            <input id="installment_input" name="installment_input" type="hidden">
                                            <input id="totalAmountDue" name="totalAmountDue" type="text" style="text-align: right;" class="form-control curr">
                                        </div>
                                    </div>
                                    <hr style="width: 100%; border:solid 1px">
                                    <div id="paymentDetailsContainer">
                                    <!-- This is where your payment forms will be appended -->
                                    <!-- <select class="form-control" name="bankName" id="bankName">
                                    <option selected value="" disabled>Select Bank</option>
                                    </select> -->
                                    </div>
                                   <button id="addPaymentDetailButton" class="btn btn-primary" type="button">Add Payment Detail</button>
                                    
                                   <hr style="width: 100%; border:solid 1px">
                                    <div class="row">
                                        <div class="col-6">
                                            <div id="taxCredDiv">

                                            <div class="row">
                                                <div class="col-12">
                                                    <label>Tax Credit : </label>
                                                    <input id="taxcredVal" name="taxcredVal" type="text" style="text-align: right;" class="form-control curr" readonly>
                                            
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                            <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="Y" id="UseCredit" name="UseCredit">
                                                <label class="form-check-label" for="UseCredit">
                                                    Use tax credits?
                                                </label>
                                                </div>
                                            </div>
                                            </div>

                                            </div>

                                        



                                        </div>
                                        <div class="col-6">
                                            <div class="row">
                                            <div class="col-12">
                                            <label>Amount Tendered : </label>
                                            
                                            <input id="amountTendered" name="amountTendered" type="text" style="text-align: right;" class="form-control curr" readonly>
                                            </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                            <div class="col-12">
                                            <label>Change : </label>
                                            <input id="change" name="change" type="text" style="text-align: right;" class="form-control" readonly>
                                            </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                            <div class="col-6">
                                            <label>Time-in : </label>
                                            <input id="time_in" name="time_in" type="text" readonly class="form-control" value="<?php echo date('h:i A');?>">
                                            </div>
                                            <div class="col-6">
                                            <label>Time-out : </label>
                                            <input id="time_out" name="time_out" type="time" class="form-control" readonly>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-success" id="frm_submit2" style="width: 100%;">POST</button>
                                            <button class="btn btn-info" id="frm_loading2" style="width: 100%;"
                                                disabled="disabled"><span class="spinner-border spinner-border-sm"
                                                    role="status" aria-hidden="true"> </span> Loading</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                        </div>
                        </form>
                    </div>

                </div>
            </div>
            <!-- start page title -->
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Transaction Details</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">Payment</li>
                                <li class="breadcrumb-item active">Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-1"></div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <div class="card">
                        <div class="card-body">
                            <!-- <form id="payment-details-init" class="needs-validation" novalidate> -->
                            <form id="payment-details-init" >
                            <div class="row">
                                <div class="col-12" style="margin-bottom: 10px;">
                                <input type="hidden" id="assessment_slip_id_det" name="assessment_slip_id_det" value="<?php echo $p_id; ?>">
                                <input type="hidden" id="installm_count" name="installm_count" value="1">
                                    <label>Business Control Number : <span style="color:red;">*</span></label>
                                    
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="blpdno" name="blpdno" required
                                            readonly>
                                        <!-- <button class="btn btn-primary" type="button"  id="btn-search-users" data-bs-toggle="offcanvas" data-bs-target="#">Search for Business</button> -->
                                    </div>
                                </div>
                                <div class="col-12" style="margin-bottom: 10px;">
                                    <label>Owner's Name : <span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" id="tax_payer_name" name="tax_payer_name"
                                        required>
                                </div>
                                <div class="col-9" style="margin-bottom: 10px;">
                                    <label>Business Name : <span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" id="business_name" name="business_name"
                                        required>
                                </div>
                                <div class="col-3" style="margin-bottom: 10px;">
                                    <label>O.R. Date : <span style="color:red;">*</span></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" id="or_date_label" name="or_date_label"
                                            value="<?php echo date('Y-m-d') ?>" disabled>
                                        <input type="hidden" class="form-control" id="ordate" name="ordate"
                                            value="<?php echo date('Y-m-d') ?>" required>
                                        <button class="btn btn-primary" type="button" id="btnChangeDate">Change
                                            Date</button>
                                    </div>
                                    <!-- <input class="form-control" type="date" id="ordate" name="ordate" readonly required value="<?php echo date('Y-m-d'); ?>"> -->
                                </div>
                                <div class="col-5" style="margin-bottom: 10px;">
                                    <label>Mode of Payment : <span style="color:red;">*</span></label>
                                    <select class="form-control" type="text" id="mode_of_payment" name="mode_of_payment"
                                        required>
                                        <option selected value="" disabled>Select</option>
                                    </select>
                                </div>
                                <div class="col-12" style="margin-bottom: 10px;">
                                    <label>Computed as of Date: <?=date('m-d-Y')?></label>
                                    <table id="installment" class="table table-bordered table-striped" width="100%"
                                        style="text-align: center; vertical-align: middle;">
                                        
                                       
                                    </table>
                                </div>
                                <div class="col-9" style="margin-bottom: 10px;"></div>
                            <div class="col-3" style="margin-bottom: 10px;">
                            <label>Select installment :</label>    
                            <div class="row">
                            <div class="col-6">
                            <label>From :</label>
                            <select class="form-control instfr" type="text" id="instfr" name="instfr" required>
                              
                            </select>
                            </div>
                            <div class="col-6">
                            <label>To :</label>
                            <select class="form-control" type="text" id="instto" name="instto" required>
                                <option selected value="" disabled>Select</option>
                                
                            </select>
                            </div>
                            </div>
                            
                            </div>
                                <div class="col-12">
                                    <label>Next O.R. is </label> <label id="nxtORnum"></label> <label>as of July 7,
                                        2023</label>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-success" id="btn_enabled_next" style="width: 100%;"
                                        data-bs-toggle="offcanvas" type="button">Next</button>
                                    <button class="btn btn-info" id="btn_disabled_next" style="width: 100%;"
                                        disabled="disabled"> No Official Receipt Assigned to User</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <!-- end card body -->
                    </div>
                </div>
                <div class="col-1"></div>
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