<?= $this->extend('layout/default') ?>
<?php $session = session(); ?>
<?= $this->section('title') ?>
iBCAS | User Profile
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="main-content">
    <div class="page-content">
    <div class="position-fixed p-3"
            style="z-index: 1005; left:50%; transform:translate(-50%,-80%); color:aliceblue;">
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

    
        <div class="container-fluid">

            
        <div style="width: 25%; background-color: #f8f8f8" class="offcanvas offcanvas-end" tabindex="-1"
                id="offCanvasChangePassword" aria-labelledby="offCanvasParentCodeLabel" aria-modal="true" role="dialog">
                <form id="frmChangePassword" class="needs-validation" novalidate>
                <div class="offcanvas-header">
                    <h5 id="offCanvasParentCodeLabel">Change Password</h5>
                    <button type="button" id="closeoffCanvasParentCode" class="btn-close text-reset"
                        data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" id="hidPassword" value="<?php echo $Userpass;?>">
                        <label for="curPass">Current Password: <span style="color: red;">*</span></label>
                        <input type="password" class="form-control pass needs-validation" id="curPass" name="curPass" required minlength="8">
                        <div class="invalid-feedback">Password must be at least 8 characters long.</div>
                    </div>
                    <div class="col-12">
                        <label for="newPass">New Password: <span style="color: red;">*</span></label>
                        <input type="password" class="form-control pass needs-validation" id="newPass" name="newPass" required minlength="8">
                        <div class="invalid-feedback">Password must be at least 8 characters long.</div>
                    </div>
                    <div class="col-12">
                        <label for="confimPass">Confirm Password: <span style="color: red;">*</span></label>
                        <input type="password" class="form-control pass needs-validation" id="confimPass" name="confimPass" required minlength="8">
                        <div class="invalid-feedback">Password must be at least 8 characters long and match the new password.</div>
                    </div>
                </div>





                </div>
                <div class="offcanvas-footer footer" style="padding-top: 10px; text-align:right; background-color:#cfd2db;">
                    <div class="row">
                        <div class="col-12">
                            <!-- <button type="button" id="hidePassword" class="btn btn-warning" hidden >hide Password</button> -->
                            <button type="button" id="showPassword" class="btn btn-warning">Show Password</button>
                            <button type="submit" class="btn btn-info">Update Password</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>




            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 font-size-18">User Profile</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">User</li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="row mb-3">
                                    <label for="name" class="col-sm-2 col-form-label">Name:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" value="<?php echo  $session->get('fullName'); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="username" class="col-sm-2 col-form-label">Username:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="username" value="<?php echo  $session->get('username'); ?>">
                                    </div>
                                </div>

                                <!-- <div class="row mb-3">
                                    <label for="address" class="col-sm-2 col-form-label">Address:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="address" placeholder="Enter your address">
                                    </div>
                                </div> -->

                                <div class="row mb-3">
                                    <label for="department" class="col-sm-2 col-form-label">Department:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="department" value="<?php echo  $session->get('department'); ?>">
                                    </div>
                                </div>

                                <!-- <div class="row mb-3">
                                    <label for="designation" class="col-sm-2 col-form-label">Designation:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="designation" placeholder="e.g., Collector, Supervisor">
                                    </div>
                                </div> -->

                                <div class="row mb-3">
                                    <div class="col-sm-12 text-end">
                                        <button type="button" id="btn-changePassword" class="btn btn-secondary">Change Password</button>
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

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
