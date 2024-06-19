<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | New Account
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
                    <div class="me-0 m-auto ms-2"><i class="mdi mdi-check text-white fs-4"></i></div>
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
                        <h4 class="mb-sm-0 font-size-18">Add New Account</h4>
                        <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Chart of Accounts</li>
                                            <li class="breadcrumb-item active"></li>
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
                        <form id="frm-addAccount" class="needs-validation" novalidate>
                            <input type="hidden" id="accID" name="accID">
                            <div class="row">
                            <div class="col-12" style="text-align: right;">
                                <label><span style="color:red;"> * Required fields</span></label>
                                
                            </div>
                            </div>
                        <div class="row">
                            <div class="col-4">
                                <label>Tax year effectivity <span style="color:red;">*</span></label>
                               <input class="form-control" id="effectivity_year" name="effectivity_year" required>
                            </div>
                            <div class="col-5">
                                <label>Account code <span style="color:red;">*</span></label>
                               <input class="form-control" id="code" name="code" required>
                            </div>
                           
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-5">
                                <label>Account title <span style="color:red;">*</span></label>
                                <input class="form-control" id="title" name="title" maxlength="70" required>
                            </div>
                            <div class="col-3">
                                <label>Acronym <span style="color:red;"></span></label>
                               <input class="form-control" id="acronym" name="acronym" >
                            </div>
                            <div class="col-4">
                                <label>Account type <span style="color:red;"></span></label>
                                <input class="form-control" id="account_type" name="account_type" >
                            </div>
                        </div>
                        <br>
                        <div class="row">
                           
                            <div class="col-5">
                            <label>Account nature <span style="color:red;"></span></label>
                                <div class="row">
                                <div class="col-3">
                                <label> <input type="radio" class="form-check-input" id="account_nature1" name="account_nature" value="1">  Debit </label>
                                </div>
                                <div class="col-9">
                                <label>s <input type="radio" class="form-check-input" id="account_nature2" name="account_nature" value="2">  Credit </label>
                                </div>
                                </div>
                                
                               
                            </div>
                           
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <label>Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks"></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-success" data-toggle="tooltip" id="frm_submit" title="Save Account" style="width: 100%;">Save Account</button>
                              
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