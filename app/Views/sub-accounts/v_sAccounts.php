<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Sub-Accounts 
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
                        <h4 class="mb-sm-0 font-size-18">Sub-Accounts</h4>
                        <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"></li>
                                            <li class="breadcrumb-item active">list</li>
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
                            <form id="ftr-sAccounts" class="needs-validation" novalidate>
                                <div class="row">

                                    <label for="horizontal-firstname-input" class="col-sm-1 col-form-label">Search
                                        By:</label>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="ftr_selby" name="ftr_selby" required>
                                            <!-- <option value="" disabled selected>Select</option> -->
                                            <option value="effYear">Effectivity Year</option>
                                            <option value="accCodeDesc">Account Description</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4" id="ftrValDiv">
                                        <input class="form-control" id="ftr_val" name="ftr_val" required>
                                        
                                    </div>

                                    <!-- <div class="col-sm-4" id="selTypeDiv" hidden>
                                        <select class="form-select" id="ftr_type" name="ftr_type">
                                            <option value="" disabled selected>Select</option>
                                        
                                        </select>
                                    </div> -->
                                    <div class="col-sm-2">
                                        <button type="submit" id="frm_submit" data-placement="top" data-toggle="tooltip" title="Filter forms" class="btn btn-primary">Filter</button>
                                        <!-- <button type="button" id="frm_loading"
                                            class="btn btn-secondary waves-effect waves-light" disabled="disabled"><span
                                                class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span> Loading...</button> -->
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
                                    <a href="<?php echo base_url('/ibcas/sAccounts/create')?>" data-placement="top" data-toggle="tooltip" title="Add new accountable form" target="_blank"
                                        class="btn btn-secondary">Add New Sub-account</a>
                                </div>
                            </div>
                            <br>
                            <!-- <h4 class="card-title mb-3">Inspection Report</h4> -->
                            <table id="chartSubAccounts-table" class="table table-bordered table-striped" width="100%"
                                style="text-align: center; vertical-align: middle;">
                                <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                                    <tr>
                                        <td style="min-width: 12% !important;">Effectivity Year</td>
                                        <td style="min-width: 14% !important;">Account Code</td>
                                       
                                        <td style="min-width: 20% !important;">Account Code Description</td>
                                        <td style="min-width: 15% !important;">Account Sub-Code</td>
                                        <td style="min-width: 25% !important;">Account Sub-Code Description</td>
                             
                                        <td style="min-width: 14% !important;"></td>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                 
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