<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Claim Stub
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
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Claim Stub</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">Claimstub</li>
                                <li class="breadcrumb-item active">List</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-12">
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <form id="ftr_claimstub">
                                <div class="row">

                                    <label for="horizontal-firstname-input" class="col-sm-1 col-form-label">Search
                                        By:</label>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="ftr_selby" name="ftr_selby">
                                            <option value="" selected>All</option>
                                            <option value="apptype">Application type</option>
                                            <option value="taxyear">Tax year effectivity</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 ftr_taxyr" hidden>
                                        <input class="form-control" id="ftr_val" name="ftr_val" type="text">
                                    </div>
                                    <div class="col-sm-4 ftr_apptype" hidden>
                                    <select class="form-select" id="ftr_sel" name="ftr_sel">
                                            <option value="" selected disabled>Select</option>
                                            <option value="1">New</option>
                                            <option value="2">Renewal</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" id="frm_submit"  data-toggle="tooltip" title="Filter schedules" class="btn btn-primary">Filter</button>
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

                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <a href="<?php echo base_url('/ibcas/claim-stub/create')?>" target="_blank"
                                        class="btn btn-secondary">Add New Claimstub Schedule</a>
                                </div>
                            </div>
                            <br>
                            <!-- <h4 class="card-title mb-3">Inspection Report</h4> -->
                            <table id="claim-stub" class="table table-bordered table-striped" width="100%"
                                style="text-align: center; vertical-align: middle;">
                                <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                                    <tr>
                                        <td width="14%">Application Type</td>
                                        <td width="15%">Tax Effectivity Year</td>
                                        <td width="14%">1st Quarter Date</td>
                                        <td width="14%">2nd Quarter Date</td>
                                        <td width="14%">3rd Quarter Date</td>
                                        <td width="14%">4th Quarter Date</td>
                                        <td width="15%">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                    <!-- <tr>
                            <td>2006</td>
                            <td>
                                <a class="btn btn-sm btn-info">SHOW</a>
                                <a class="btn btn-sm btn-warning">EDIT</a>
                                <a class="btn btn-sm btn-danger">DELETE</a>
                            </td>
                        </tr>
                        <tr>
                            <td>2018</td>
                            <td>
                                <a class="btn btn-sm btn-info">SHOW</a>
                                <a class="btn btn-sm btn-warning">EDIT</a>
                                <a class="btn btn-sm btn-danger">DELETE</a>
                            </td>
                        </tr> -->
                                </tbody>
                            </table>
                        </div>
                        <!-- end card body -->
                    </div>

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