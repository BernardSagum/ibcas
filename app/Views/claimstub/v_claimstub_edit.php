<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Claimstub Update Schedule
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
                        <h4 class="mb-sm-0 font-size-18">Update Claimstub Details</h4>
                        <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Claimstub</li>
                                            <li class="breadcrumb-item active">Schedule</li>
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
                        <form id="ftr_edit_claimstub" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-6">
                                <!-- <label>Taxable Year <span style="color:red;">*</span></label>
                                <input class="form-control" id="taxyr" name="taxyr" required> -->
                            </div>
                            <div class="col-6" style="text-align: right;">
                                <label><span style="color:red;"> * Required fields</span></label>
                                <input type="hidden" id="c_id" name="c_id" value="<?php echo $p_id ?>">
                               
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-6">
                                <label>Application Type <span style="color:red;">*</span></label>
                                <select class="form-select" id="application_type_id" name="application_type_id" required> 
                                    <option value="" selected disabled>Select type</option>
                                    <!-- <option value="1" >NEW</option>
                                    <option value="2" >RENEWAL</option> -->
                                    <!-- <option value="1" >NEW</option> -->
                                </select>
                            </div>
                            <div class="col-6">
                                <label>Tax Year Effectivity <span style="color:red;">*</span></label>
                                <input class="form-control yrclass" id="tax_effectivity_year" name="tax_effectivity_year" type="text" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <label>First Quarter Peak Date<span style="color:red;">*</span></label>
                                <input class="form-control" id="first_quarter_date" name="first_quarter_date" type="date" required>
                            </div>
                            <div class="col-6">
                                <label>First Quarter Peak Days<span style="color:red;">*</span></label>
                                <input class="form-control" id="first_quarter_peak_days" name="first_quarter_peak_days" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <label>Second Quarter Peak Date<span style="color:red;">*</span></label>
                                <input class="form-control" id="second_quarter_date" name="second_quarter_date" type="date" required>
                            </div>
                            <div class="col-6">
                                <label>Second Quarter Peak Days<span style="color:red;">*</span></label>
                                <input class="form-control" id="second_quarter_peak_days" name="second_quarter_peak_days" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <label>Third Quarter Peak Date<span style="color:red;">*</span></label>
                                <input class="form-control" id="third_quarter_date" name="third_quarter_date" type="date" required>
                            </div>
                            <div class="col-6">
                                <label>Third Quarter Peak Days<span style="color:red;">*</span></label>
                                <input class="form-control" id="third_quarter_peak_days" name="third_quarter_peak_days" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <label>Forth Quarter Peak Date<span style="color:red;">*</span></label>
                                <input class="form-control" id="fourth_quarter_date" name="fourth_quarter_date" type="date" required>
                            </div>
                            <div class="col-6">
                                <label>Forth Quarter Peak Days<span style="color:red;">*</span></label>
                                <input class="form-control" id="fourth_quarter_peak_days" name="fourth_quarter_peak_days" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <label>Non Peak Days<span style="color:red;">*</span></label>
                                <input class="form-control" id="nonpeak_days" name="nonpeak_days" required>
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
                            <button class="btn btn-success" id="frm_submit" data-placement="top" data-toggle="tooltip" title="Update schedule" style="width: 100%;">Update schedule</button>
                                <button type="button" id="frm_loading" style="width: 100%;"
                                            class="btn btn-secondary waves-effect waves-light" disabled="disabled"><span
                                                class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span> Loading...</button>
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