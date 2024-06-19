<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Create new collector
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
                        <h4 class="mb-sm-0 font-size-18">Add new Collector</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">Collectors</li>
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
                        <form id="frm_new_collector" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-12" style="text-align: right;">
                                <label><span style="color:red;"> * Required fields</span></label>
                                <!-- <input id="col_id" name="col_id" type="hidden"> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label>ID Number : <span style="color:red;"></span></label>
                                <input class="form-control" id="col_id" name="col_id" readonly>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-12">
                                <label>Fullname : <span style="color:red;">*</span></label>
                                <!-- <input class="form-control" type="text" id="assign" name="assign" required> -->
                                <div class="input-group">
                                <input type="text" class="form-control" id="user_label" name="user_label" disabled>
                                <input type="hidden" class="form-control" id="user_id" name="user_id" required>
                                <button class="btn btn-primary" type="button"  id="btn-search-users" data-bs-toggle="offcanvas" data-bs-target="#offcanvasUser">Search user</button>
                                </div>
                            </div>
                            <div class="col-8">
                                <label>Address : <span style="color:red;" >*</span></label>
                                <input class="form-control" type="text" id="address" name="address" required>
                            </div>
                            <div class="col-4">
                                <label>Barangay : <span style="color:red;" >*</span></label>
                                <select class="form-control" type="text" id="barangay_id" name="barangay_id" required></select>
                            </div>
                            <div class="col-6">
                                <label>Contact number : <span style="color:red;"></span></label>
                                <input class="form-control" type="text" id="contact_number" name="contact_number">
                            </div>
                            <div class="col-6">
                                <label>Email : <span style="color:red;"></span></label>
                                <input class="form-control" type="text" id="email" name="email">
                            </div>
                            <div class="col-6">
                                <label>TIN No. : <span style="color:red;"></span></label>
                                <input class="form-control" type="text" id="tin_no" name="tin_no">
                            </div>
                            <div class="col-6">
                                <label>Position : <span style="color:red;">*</span></label>
                                <select class="form-control" type="text" id="position_id" name="position_id" required></select>
                            </div>
                            <div class="col-12">
                                <label>Accountable Officer : <span style="color:red;">*</span></label>
                                <!-- <input class="form-control" type="text" id="assign" name="assign" required> -->
                                <div class="input-group">
                                <input type="text" class="form-control" id="officerlabel" name="officer_label" disabled>
                                <input type="hidden" class="form-control" id="accountable_officer_id" name="accountable_officer_id" required>
                                <button class="btn btn-primary" type="button"  id="btn-search-officers" data-bs-toggle="offcanvas" data-bs-target="#offcanvasUser">Search officer</button>
                                </div>
                            </div>

                            <div class="col-6" style="margin-top:10px">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Y" name="deputized_collector" id="deputized_collector">
                                <label class="form-check-label" for="deputized_collector">
                                Deputized collector
                                </label>
                                </div>
                            </div>
                            <div class="col-6" style="margin-top:10px">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Y" name="field_collector" id="field_collector">
                                <label class="form-check-label" for="field_collector">
                                Field collector
                                </label>
                                </div>
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