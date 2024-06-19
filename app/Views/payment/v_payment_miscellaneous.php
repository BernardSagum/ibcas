<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Payment
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
        <!-- Modal Structure -->
<!-- <div id="paymentTypeModal" class="modal fade" tabindex="-1" role="dialog"> -->
<div class="modal fade" id="MiscellaneousPaymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Payment Type</h5>
      </div>
      <div class="modal-body">
      <label>Type of Misc. Payment :</label>
                                <select class="form-control" id="MiscType" name="MiscType">
                                    <option value="" selected disabled>Select</option>
                                </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="confirmMscButton" class="btn btn-primary">Confirm</button>
      </div>
    </div>
  </div>
</div>

        <div class="container-fluid">
        <div style="width: 45%; background-color: #f8f8f8" class="offcanvas offcanvas-end" tabindex="-1"
                id="offcanvasUser" aria-labelledby="offcanvasUserLabel" aria-modal="true" role="dialog">
                <div class="offcanvas-header">
                    <h5 id="offcanvasUserLabel">Search business</h5>
                    <button type="button" id="closeoffcanvasUser" class="btn-close text-reset"
                        data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="row">
                    <div class="col-xl-12">
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <form id="ftr_paymentlist">
                                <div class="row">

                                    <label for="horizontal-firstname-input" class="col-sm-2 col-form-label">Search
                                        By:</label>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="ftr_selby" name="ftr_selby">
                                            <!-- <option value="" selected>All</option> -->
                                            <option value="blpdno">Business Control Number</option>
                                            <option value="busname">Business Name</option>
                                            <option value="taxpayer">Tax Payer</option>
                                            <!-- <option value="">Payment Reference No.</option> -->
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input class="form-control" id="ftr_val" name="ftr_val" type="text">
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" id="frm_submit" data-placement="top" data-toggle="tooltip" title="Filter rates" class="btn btn-primary">Filter</button>
                                        <button type="button" id="frm_loading"
                                            class="btn btn-primary waves-effect waves-light" disabled="disabled"><span
                                                class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span> Loading...</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end card -->

                </div>
            </div>


                    <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- <div class="row">
                                <div class="col-12">
                                    <a href="<?php echo base_url('/ibcas/penalty-rates/create')?>" target="_blank"
                                        class="btn btn-secondary">Add New Penalty Rate</a>
                                </div>
                            </div>
                            <br> -->
                            <!-- <h4 class="card-title mb-3">Inspection Report</h4> -->
                            <table id="payment-masterlist" class="table table-bordered" width="100%" style="vertical-align: middle;">
                                              
                            <!-- <table id="payment-masterlist" class="table table-bordered" width="100%" -->
                                <!-- style="text-align: center; vertical-align: middle;"> -->
                                <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                                    <tr>
                                        <td>Business Control Number</td>
                                        <td>Business Name</td>
                                        <td>Tax Payer Name</td>
                                        <td>Address</td>
                                        <!-- <td>Action</td> -->
                                    </tr>
                                </thead>
                                <tbody>
                                   

                                </tbody>
                            </table>
                        </div>
                        <!-- end card body -->
                    </div>

                </div>




                </div>
                </div>
            <div id="PreMainMsc">
                <div class="row">

                <div class="col-xl-1"></div>
                <div class="col-xl-10">
                  

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <!-- <input type="hidden" id="formStat" value="0"> -->
                                    <button id="AddNewMsc" class="btn btn-info">New Miscellaneous Payment</button>
                                    <button id="CancelNew" class="btn btn-warning" hidden>Cancel Miscellaneous Payment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card -->

                </div>
                <div class="col-xl-1"></div>
                </div>
            </div>
            <div id="MainMsc" hidden>    
            <!-- start page title -->
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">TRANSACTIONS</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">Miscellaneous</li>
                                <li class="breadcrumb-item active">Payment</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-1"></div>
            </div>
            <!-- end page title -->
        
            <div class="row">
                <div class="col-xl-1"></div>
                <div class="col-xl-10">
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <form id="form-post-payment" class="needs-validation" novalidate>
                            <div class="row" style="margin-bottom: 10px;">
                            <div class="col-6" >
                            <label>Fund type : <span style="color:red;">*</span></label>
                                <select class="form-control" id="fund_id" name="fund_id" >
                                    <!-- <option value="" selected disabled>Select</option> -->
                                </select>
                            </div>
                            <div class="col-6" >
                            <label>Type of Form : <span style="color:red;">*</span></label>
                                <select class="form-control" id="form_id" name="form_id" required>
                                    <option value="" selected disabled>Select</option>
                                </select>
                            </div>
                            </div>
                            <div class="row" style="margin-bottom: 10px;">
                            <div class="col-6" >
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
                                        <div class="col-3" style="padding-top: 26px;"><button id="searchBusiness" class="btn btn-secondary" type="button">Search business</button></div>
                                        <div class="col-3"></div>
                                        <!-- <div class="col-6" style="margin-bottom: 10px;">
                                            <label>Business Control Number : <span style="color:red;"></span></label>
                                            <input class="form-control" type="text" id="blpdNumber" name="blpdNumber"
                                                readonly required>
                                        </div> -->
                            </div>
                            <div class="row" id="business_pannel">
                                <div class="col-4">
                                <label>Business Control Number : <span style="color:red;"></span></label>
                                            <input class="form-control" type="text" id="blpdNumber" name="blpdNumber" disabled >
                                            <input class="form-control" type="hidden" id="app_id" name="app_id" >
                                </div>
                                <div class="col-6">
                                <label>Business Name : <span style="color:red;"></span></label>
                                            <input class="form-control" type="text" id="busName" name="busName" disabled>
                                </div>
                           
                            </div>
                            <hr style="width: 100%; border:solid 1px">
                            <div class="row">
                                <div class="col-12">
                                    <label>Payor's Name </label>
                                </div>
                                <div class="col-8" id="mscPayorName">
                                    <!-- <label>First Name : <span style="color:red;">*</span></label> -->
                                    <input type="text" class="form-control" id="payorName" name="payorName" required>
                                </div>
                          
                            </div>

                            <hr style="width: 100%; border:solid 1px">
                                    <div id="MiscellaneousPaymentDetails">
                                    <div class="row">
                                    <div class="col-12">
                                        <label>Fees: </label>
                                    </div></div>
                                    <!-- This is where your payment forms will be appended -->
                                    <!-- <select class="form-control" name="bankName" id="bankName">
                                    <option selected value="" disabled>Select Bank</option>
                                    </select> -->
                                    </div>
                                   <button id="addMiscellaneousPaymentButton" class="btn btn-primary" type="button">Add Miscellaneous</button>
                                    


                            <!-- <div class="row">
                                <div class="col-5">
                                <label>Type of Misc. Payment :</label>
                                <select class="form-control" id="MiscType" name="MiscType">
                                    <option value="" selected disabled>Select</option>
                                </select>
                                </div>
                            </div> -->
                            <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-9"></div>
                                        <div class="col-3">
                                            <label>Amount Due : </label>
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
                                        <div class="col-6"></div>
                                        <div class="col-6">
                                            <div class="row">
                                            <div class="col-12">
                                            <label>Amount Tendered : </label>
                                            <input id="amountTendered" name="amountTendered" required readonly type="text" style="text-align: right;" class="form-control curr">
                                            </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                            <div class="col-12">
                                            <label>Change : </label>
                                            <input id="change" name="change" type="text" style="text-align: right;" required readonly class="form-control">
                                            </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                            <div class="col-6">
                                            <label>Time-in : </label>
                                            <input id="time_in" name="time_in" type="text" readonly class="form-control" value="<?php echo date('H:i');?>">
                                            </div>
                                            <div class="col-6">
                                            <label>Time-out : </label>
                                            <input id="time_out" name="time_out" type="time" readonly class="form-control">
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-success" id="frm_submit_post" style="width: 100%;">POST</button>
                                            <button class="btn btn-info" id="frm_loading_post" style="width: 100%;"
                                                disabled="disabled"><span class="spinner-border spinner-border-sm"
                                                    role="status" aria-hidden="true"></span> Loading</button>
                                        </div>
                                    </div>

                                    </form>
                        </div>
                    </div>
                    <!-- end card -->

                </div>
                <div class="col-xl-1"></div>
            
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