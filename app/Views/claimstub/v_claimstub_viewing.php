<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Claimstub View Schedule
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
                        <h4 class="mb-sm-0 font-size-18">View Claimstub Details</h4>
                        <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Claimstub</li>
                                            <li class="breadcrumb-item active">Details</li>
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

                    <div class="card placeholder-glow">
                        <div class="card-body">
                        <form id="ftr_view_claimstub">

                        <input type="hidden" id="p_id" name="p_id" value="<?php echo $p_id ?>">
                        <div class="row">
                            <div class="col-6">
                                <label>Application Type : </label><br>
                                    <p id="application_type_id" class="pbld"><span class="placeholder col-12"></span></p>
                                    <!-- <label id="application_type_id"></label> -->
                            </div>
                            <div class="col-6">
                                <label>Tax Year Effectivity : </label><br>
                                    <p id="tax_effectivity_year" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <label>First Quarter Peak Date : </label><br>
                                    <p id="first_quarter_date" class="pbld"><span  class="placeholder col-12"></span> </p>
                            </div>
                            <div class="col-6">
                                <label>First Quarter Peak Days : </label><br>
                                    <p id="first_quarter_peak_days" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <label>Second Quarter Peak Date : </label><br>
                                    <p id="second_quarter_date" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                            <div class="col-6">
                                <label>Second Quarter Peak Days : </label><br>
                                    <p id="second_quarter_peak_days" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <label>Third Quarter Peak Date : </label><br>
                                    <p id="third_quarter_date" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                            <div class="col-6">
                                <label>Third Quarter Peak Days : </label><br>
                                    <p id="third_quarter_peak_days" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <label>Forth Quarter Peak Date : </label><br>
                                    <p id="fourth_quarter_date" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                            <div class="col-6">
                                <label>Forth Quarter Peak Days : </label><br>
                                    <p id="fourth_quarter_peak_days" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <label>Non Peak Days : </label><br>
                                    <p id="nonpeak_days" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                            <div class="col-6">
                                <label>Remarks : </label><br>
                                    <p id="remarks" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label>Created by : </label><br>
                                    <p id="created_by" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                            <div class="col-6">
                                <label>Updated by : </label><br>
                                    <p id="updated_by" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-6">
                                <label>Created at : </label><br>
                                    <p id="created_at" class="pbld"><span class="placeholder col-12"></span></p>
                            </div>
                           
                            <div class="col-6">
                                <label>Updated at : </label><br>
                                    <p id="updated_at" class="pbld"><span class="placeholder col-12"></span></p>
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