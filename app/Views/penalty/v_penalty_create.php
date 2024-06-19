<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Penalty Rates
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
                <div class="col-1"></div>
                <div class="col-10">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Add New Penalty Rate</h4>
                        <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Penalty</li>
                                            <li class="breadcrumb-item active">New Rate</li>
                                        </ol>
                                    </div>
                    </div>
                </div>
                <div class="col-1"></div>
            </div>
            <!-- end page title -->

            <div class="row">
            <div class="col-1"></div>
            <div class="col-xl-10">
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                        <form id="ftr_new_penalty" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-4">
                                <label>Taxable Year <span style="color:red;">*</span></label>
                                <select class="form-control" id="taxyr" name="taxyr" required>
                                    <option value="" disabled selected>Select</option>
                                </select>
                            </div>
                            <div class="col-8" style="text-align: right;">
                                <label><span style="color:red;"> * Required fields</span></label>
                                
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12">
                           <table class="table table-striped" id="tbl_new_fees" width="100%" style="text-align: center; vertical-align: middle;">
                            <tr>
                                <td width="50%">FEES</td>
                                <td width="25%">PENALTY (%)</td>
                                <td width="25%">SURCHARGE (%)</td>
                            </tr>
                            <tr class="placeholder-glow">
                            <td><span class="placeholder col-12"></span></td>
                                <td><span class="placeholder col-3"></span></td>
                                <td><span class="placeholder col-3"></span></td>
                            </tr>
                           </table>     
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label>Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks"></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-success" data-toggle="tooltip" id="frm_submit" title="Save rates" style="width: 100%;">Save</button>
                                <button type="button" id="frm_loading" style="width: 100%;"
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
                <div class="col-xl-1">
                    <!-- end card -->

                    <!-- <div class="card">
                        <div class="card-body">
                      
                        </div>
                    </div> -->
                    <!-- end card -->

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