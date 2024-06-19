<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Cash Ticket 
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
                        <h4 class="mb-sm-0 font-size-18">Cash Ticket Masterlist</h4>
                        <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item">Cash Ticket</li>
                                            <li class="breadcrumb-item active"></li>
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
                            <form id="ftr-accform">
                                <div class="row">

                                    <label for="horizontal-firstname-input" class="col-sm-1 col-form-label">Search
                                        By:</label>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="ftr_selby" name="ftr_selby">
                                            <option value="" selected>All</option>
                                            <option value="formnum">Form number</option>
                                            <option value="fund">Type of fund</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select class="form-control" id="ftr_val" name="ftr_val">
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" id="frm_submit" data-placement="top" data-toggle="tooltip" title="Filter forms" class="btn btn-primary">Filter</button>
                                        <button type="button" id="frm_loading"
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

                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <a href="<?php echo base_url('/ibcas/acc-forms/create')?>" data-placement="top" data-toggle="tooltip" title="Add new accountable form" target="_blank"
                                        class="btn btn-secondary">Add New Accountable Form</a>
                                </div>
                            </div>
                            <br>
                            <!-- <h4 class="card-title mb-3">Inspection Report</h4> -->
                            <table id="acc-forms" class="table table-bordered table-striped" width="100%"
                                style="text-align: center; vertical-align: middle;">
                                <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                                    <tr>
                                        <td style="width: 13% !important;">Form No</td>
                                        <td style="width: 13% !important;">Fund</td>
                                        <td style="width: 12% !important;">Stub No</td>
                                        <td style="width: 20% !important;">Receipt Series</td>
                                        <td style="width: 12% !important;">Quantity</td>
                                        <td style="width: 15% !important;">Remaining</td>
                                        <td style="width: 15% !important;">Action</td>
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