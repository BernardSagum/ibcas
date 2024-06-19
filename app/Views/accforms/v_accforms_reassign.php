<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Reassign Receipt 
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
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" aria-modal="true" role="dialog">
                                            <div class="offcanvas-header">
                                              <h5 id="offcanvasRightLabel">Search accountable person</h5>
                                              <button type="button" id="closeoffcanvasRight" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body">
                                              <table id="tbl_search_accountable" class="table table-bordered" width="100%" style="vertical-align: middle;">
                                              <thead style="font-weight: bold; text-align: left; vertical-align: middle;">
                                                <tr>
                                                    <td>NAME</td>
                                                </tr>
                                                    
                                                </thead>
                                                <tbody></tbody>
                                              </table>
                                            </div>
                                        </div>
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
            <div class="col-1"></div>
                <div class="col-10">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Reasign Receipt</h4>
                        <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Receipt</li>
                                            <li class="breadcrumb-item active">Reassign</li>
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
                        <form id="ftr_assign_form">
                        <div class="row">
                            <div class="col-12" style="text-align: right;">
                                <label><span style="color:red;"> * Required fields</span></label>
                                <input id="assign_id" name="assign_id" type="hidden" value="">
                                <input id="accform_id" name="accform_id" type="hidden" value="">
                                <input id="assigned_receipts_id" name="assigned_receipts_id" type="hidden" value="">
                                <input id="acc_id" name="acc_id" type="hidden" value="<?php echo $p_id ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label>Form Number : </label>
                                <select class="form-control" id="form_id" name="form_id" disabled>

                                </select>
                            </div>
                            <div class="col-6">
                                <label>Form Description : </label>
                                <select class="form-control" id="form_des" name="form_des" disabled>

                                </select>
                            </div>
                            <div class="col-12">
                                <label>Fund : <span style="color:red;">*</span></label>
                                <select class="form-control" type="text" id="fund_id" name="fund_id">
                                </select>
                            </div>
                            <div class="col-12">
                                <label>Booklet :</label>
                                <input class="form-control" type="text" id="stub_no" name="stub_no" disabled>
                            </div>
                            <div class="col-12">
                                <label>Accountable Officer :</label>
                                <!-- <input class="form-control" type="text" id="acc_officer" name="acc_officer"> -->
                                <div class="input-group">
                                <input type="text" class="form-control" id="acc_officer_label" name="acc_officer_label" disabled>
                                <input type="hidden" class="form-control" id="acc_officer" name="acc_officer">
                                <!-- <button class="btn btn-primary" type="button" id="btn-search-assign" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">Search</button> -->
                                </div>
                            </div>
                            <div class="col-12">
                                <label>Assigned : <span style="color:red;">*</span></label>
                                <!-- <input class="form-control" type="text" id="assign" name="assign" required> -->
                                <div class="input-group">
                                <input type="text" class="form-control" id="assign_label" name="assignlabel" disabled>
                                <input type="hidden" class="form-control" id="assign" name="assign">
                                <button class="btn btn-primary" type="button" id="btn-search-assign" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">Search</button>
                                </div>
                            </div>
                            <div class="col-6">
                                <label>Accountable series form from : </label>
                                <input class="form-control" type="text" id="from" name="from" readonly>
                            </div>
                            <div class="col-6">
                                <label>Accountable series form to : <span style="color:red;"></span></label>
                                <input class="form-control" type="text" id="to" name="to" readonly>
                            </div>
                            <div class="col-12">
                                <label>Date Issued : <span style="color:red;"></span></label>
                                <input class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('1900-01-01'); ?>" max="<?php echo date('Y-m-d'); ?>" id="date_issued" name="date_issued">
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-success" id="frm_submit" style="width: 100%;">Reassign</button>
                                <button class="btn btn-info" id="frm_loading" style="width: 100%;" disabled="disabled"><span
                                                class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"> </span> Loading</button>
                            </div>
                        </div>
                        </form>
                        </div>
                    </div>
                    <!-- end card -->

                </div>
                <div class="col-xl-1"></div>



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