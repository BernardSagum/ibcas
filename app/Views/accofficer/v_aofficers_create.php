<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Create new accountable officer
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
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUser" aria-labelledby="offcanvasUserLabel" aria-modal="true" role="dialog">
                                            <div class="offcanvas-header">
                                              <h5 id="offcanvasUserLabel"></h5>
                                              <button type="button" id="closeoffcanvasUser" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body">
                                              <table id="tbl_search_users" class="table table-bordered" width="100%" style="vertical-align: middle;">
                                              <thead style="font-weight: bold; text-align: left; vertical-align: middle;">
                                                <tr>
                                                    <td>NAME</td>
                                                </tr>
                                                    
                                                </thead>
                                                <tbody></tbody>
                                              </table>
                                            </div>
                                        </div>
            <!-- start page title -->
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Add new accountable officer</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">Accountable officers</li>
                                <li class="breadcrumb-item active">Add new</li>
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
                        <form id="frm_new_officer" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-12" style="text-align: right;">
                                <label><span style="color:red;"> * Required fields</span></label>
                                <input id="off_id" name="off_id" type="hidden">
                            </div>
                        </div>

                        <div class="row">
                        <div class="col-12">
                                <label>Fullname : <span style="color:red;">*</span></label>
                                <!-- <input class="form-control" type="text" id="assign" name="assign" required> -->
                                <div class="input-group">
                                <input type="text" class="form-control" id="user_label" name="user_label" readonly required>
                                <input type="hidden" class="form-control" id="user_id" name="user_id" readonly required>
                                <button class="btn btn-primary" type="button"  id="btn-search-users" data-bs-toggle="offcanvas" data-bs-target="#offcanvasUser">Search user</button>
                                </div>
                            </div>
                            <div class="col-6">
                                <label>Username : <span style="color:red;" ></span></label>
                                <input class="form-control" type="text" id="o_username" name="o_username" required readonly>
                            </div>
                            <div class="col-6">
                                <label>Type : <span style="color:red;" ></span></label>
                                <select class="form-control" type="text" id="type" name="type" required>
                                    <option value="" selected disabled>Select</option>
                                    <option value="1">Custodian</option>
                                    <option value="2">Accountable Officer</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-success" id="frm_submit" style="width: 100%;">Save</button>
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
                <div class="col-xl-1">
                
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