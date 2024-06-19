<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Void Form 
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
                        <h4 class="mb-sm-0 font-size-18">Void Accountable Form</h4>
                        <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Forms</li>
                                            <li class="breadcrumb-item active">Void</li>
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
                        <form id="frm_void">
                        <div class="row">
                            <div class="col-12" style="text-align: right;">
                                <!-- <label><span style="color:red;"> * Required fields</span></label> -->
                                <!-- <input id="assign_id" name="assign_id" type="hidden" value=""> -->
                                <input id="acc_id" name="acc_id" type="hidden" value="<?php echo $p_id ?>">
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-4">
                                <label>Fund type: <span style="color:red;"></span></label>
                                <select class="form-control" type="text" id="fund_id" name="fund_id" disabled>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div><div class="col-12"><label id="frmtype">Form type</label></div></div>
                        <div class="row" style="margin:1px; border: solid 1px; border-radius: 10px; padding:10px; max-height:fit-content;">
                            <!-- <div class="col-12"><label id="frmtype">Form type</label></div> -->
                            <div class="col-4">
                                <label>Stub :</label>
                                <input class="txtright form-control" type="text" id="stub_no" name="stub_no" disabled>
                            </div>
                            <div class="col-4">
                                <label>From : </label>
                                <input class="txtright form-control" type="text" id="from" name="from" disabled>
                            </div>
                            <div class="col-4">
                                <label>To : <span style="color:red;"></span></label>
                                <input class="txtright form-control" type="text" id="to" name="to" disabled>
                            </div>
                            <div class="col-12" style="text-align: center; padding-top:20px;">
                                <a href="#">View Series Status</a>
                            </div>
                        </div>
                        <br>
                        <div><div class="col-12"><label >Void Series</label></div></div>
                        <div class="row" style="margin:1px; border: solid 1px; border-radius: 10px; padding:10px; max-height:fit-content;">
                            <!-- <div class="col-12"><label id="frmtype">Form type</label></div> -->
                            
                            <!-- <div class="col-1"></div> -->
                            <div class="col-6">
                                <label>From : </label>
                                <input class="form-control txtright" type="text" id="vfrom" name="vfrom" readonly>
                            </div>
                            <div class="col-6">
                                <label>To : <span style="color:red;"></span></label>
                                <input class="form-control txtright" type="text" id="vto" name="vto" readonly>
                            </div>
                            <!-- <div class="col-1"></div> -->
                            <div class="col-12" style="padding-top:10px;">
                            <label>Reason : </label>
                                <textarea name="vreason" id="vreason" class="form-control"></textarea>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-success" id="frm_submit" style="width: 100%;">Void</button>
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