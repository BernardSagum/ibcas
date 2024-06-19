<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Update Sub-Account
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 

?>
<div class="main-content">
    <style>

    </style>
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

            <div style="width: 45%; background-color: #f8f8f8" class="offcanvas offcanvas-end" tabindex="-1"
                id="offCanvasParentCode" aria-labelledby="offCanvasParentCodeLabel" aria-modal="true" role="dialog">
                <div class="offcanvas-header">
                    <h5 id="offCanvasParentCodeLabel">Search business</h5>
                    <button type="button" id="closeoffCanvasParentCode" class="btn-close text-reset"
                        data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <form id="ftr-cAccounts">
                                        <div class="row">

                                            <label for="horizontal-firstname-input"
                                                class="col-sm-2 col-form-label">Search
                                                By:</label>
                                            <div class="col-sm-4">
                                                <select class="form-select" id="ftr_selby" name="ftr_selby" required>
                                                    <option value="" disabled selected>Select</option>
                                                    <option value="acTitle">Account Title</option>
                                                    <option value="acType">Account Type</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <input class="form-control" id="ftr_val" name="ftr_val" type="text">
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="submit" id="frm_submit" data-placement="top"
                                                    data-toggle="tooltip" title="Filter rates"
                                                    class="btn btn-primary">Filter</button>

                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- end card -->

                        </div>
                    </div>


                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="chartAccounts-table" class="table table-bordered table-striped" width="100%"
                                    style="text-align: center; vertical-align: middle;">
                                    <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                                        <tr>
                                            <td style="width: 15% !important;">Effectivity Year</td>
                                            <td style="width: 20% !important;">Account Code</td>

                                            <td style="width: 30% !important;">Accoount Title</td>
                                            <td style="width: 20% !important;">Account Type</td>

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
            </div>

                 <!-- Offcanvas for Search Particular -->
                 <div style="width: 45%; background-color: #f8f8f8" class="offcanvas offcanvas-end" tabindex="-1"
                id="searchParticularOffcanvas" aria-labelledby="searchParticularOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="searchParticularOffcanvasLabel">Search Particular</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">

                    <!-- Content for Search Particular Offcanvas -->
                    <div class="row">

                        <div class="col-12">
                            <form id="ftr-particulars" class="needs-validation2" novalidate>
                                <div class="row">

                                    <label for="horizontal-firstname-input" class="col-sm-2 col-form-label">Search
                                        By:</label>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="ftr_selby" name="ftr_selby" required>
                                            <option value="" disabled selected>Select</option>
                                            <option value="parName">Particular Name</option>
                                            <option value="parType">Particular type</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4" id="ftrValDiv">
                                        <input class="form-control" id="ftr_val" name="ftr_val" required>

                                    </div>

                                    <div class="col-sm-4" id="selTypeDiv" hidden>
                                        <select class="form-select" id="ftr_type" name="ftr_type">
                                            <option value="" disabled selected>Select</option>
                                            <!-- <option value="parName">Particular Name</option>
                                            <option value="parType">Particular type</option> -->
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="submit" id="frm_submit" data-placement="top" data-toggle="tooltip"
                                            title="Filter forms" class="btn btn-primary">Filter</button>

                                    </div>

                                </div>
                            </form>
                        </div>


                    </div>
                    <br>
                    <div class="row">

                        <div class="12">
                            <table id="particulars-table" class="table table-bordered table-striped"
                                style="background-color: #ffe7e7 !important;" width="100%"
                                style="text-align: center; vertical-align: middle;">
                                <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                                    <tr>
                                        <td style="min-width: 55% !important;">Particular Name</td>

                                        <td style="min-width: 45% !important;">Particular Type</td>

                                        <!-- <td style="width: 15% !important;">Action</td> -->
                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Offcanvas for Search Classification -->
            <div style="width: 45%; background-color: #f8f8f8" class="offcanvas offcanvas-end" tabindex="-1"
                id="searchClassificationOffcanvas" aria-labelledby="searchClassificationOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="searchClassificationOffcanvasLabel">Search Classification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <!-- Content for Search Classification Offcanvas -->
                    <div class="row">

<div class="col-12">
    <form id="ftr-classifications" class="needs-validation3" novalidate>
        <div class="row">

            <label for="horizontal-firstname-input" class="col-sm-2 col-form-label">Search
                By:</label>
            <div class="col-sm-4">
                <select class="form-select" id="ftr_selbyC" name="ftr_selbyC" required>
                    <option value="" disabled selected>Select</option>
                    <option value="ALL">All</option>
                    <!-- <option value="parType">Particular type</option> -->
                </select>
            </div>
            <div class="col-sm-4" id="ftrValDiv" hidden>
                <input class="form-control" id="ftr_valC" name="ftr_valC" required>

            </div>

        
            <div class="col-sm-1">
                <button type="submit" id="frm_submit" data-placement="top" data-toggle="tooltip"
                    title="Filter forms" class="btn btn-primary">Filter</button>

            </div>

        </div>
    </form>
</div>


</div>
<br>
<div class="row">

<div class="12">
    <table id="classifications-table" class="table table-bordered table-striped"
        style="background-color: #ffe7e7 !important;" width="100%"
        style="text-align: center; vertical-align: middle;">
        <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
            <tr>
                <td style="min-width: 55% !important;">Classification Code</td>

                <td style="min-width: 45% !important;">Classification Name</td>

                <!-- <td style="width: 15% !important;">Action</td> -->
            </tr>
        </thead>
        <tbody>


        </tbody>
    </table>
</div>

</div>





                </div>
            </div>

            <!-- start page title -->
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Update Sub-Account</h4>
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
                            <form id="frm-addSubAccount" class="needs-validation" novalidate>
                                <input type="hidden" id="sub_ID" name="sub_ID" value="<?php echo $p_id;?>">
                                <div class="row">
                                    <div class="col-12" style="text-align: right;">
                                        <label><span style="color:red;"> * Required fields</span></label>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label>Effectivity year<span style="color:red;">*</span></label>
                                        <input class="form-control" id="effectivity_year" name="effectivity_year"
                                            required>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-6" style="margin-bottom: 10px;">
                                        <label> Parent code : </label>
                                        <!-- <input class="form-control" type="text" id="assign" name="assign" required> -->
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="parentCodelabel"
                                                name="parentCodelabel" value="" require disabled>
                                            <input type="hidden" class="form-control" id="account_id" name="account_id"
                                                value="" required>
                                            <button class="btn btn-primary" type="button" id="btSearchAccount">Search
                                                Parent Code</button>
                                        </div>
                                    </div>

                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-5">
                                        <label>Account sub-code<span style="color:red;">*</span></label>
                                        <input class="form-control" id="subcode" name="subcode" required disabled=true>
                                    </div>
                                    <div class="col-5">
                                        <label>Account sub-code description<span style="color:red;">*</span></label>
                                        <input class="form-control" id="subcodedesc" name="subcodedesc" required>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-check form-check-primary mb-3">
                                            <input class="form-check-input optcbx" data-val="NoParticular"  type="checkbox" name="options[]"
                                                id="noParticular" value="NoParticular" required>
                                            <label class="form-check-label" for="noParticular">
                                                No particular
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-check form-check-success mb-3">
                                            <input class="form-check-input optcbx" data-val="ForCashTicket"  name="options[]" type="checkbox"
                                                id="forCashTicket" value="ForCashTicket" required>
                                            <label class="form-check-label" for="forCashTicket">
                                                For cash ticket
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-check form-check-info mb-3">
                                            <input class="form-check-input optcbx" data-val="ForCTC"  name="options[]" type="checkbox" id="forCTC"
                                                value="ForCTC" required>
                                            <label class="form-check-label" for="forCTC">
                                                For CTC
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-check form-check-danger">
                                            <input class="form-check-input optcbx" data-val="forTaxCredit" name="options[]" type="checkbox"
                                                id="forTaxCredit" value="ForTaxCredit" required>
                                            <label class="form-check-label" for="forTaxCredit">
                                                For Tax Credit
                                            </label>
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
                                <div id="particularDiv">
                                    <div class="container mt-5">
                                        <div class="card custom-card-bg">
                                            <div class="card-body">
                                                <h5 class="card-title">Particular And Classification</h5>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <span class="legend-text">N - New</span>
                                                        <span class="legend-text">R - Renewal</span>
                                                        <span class="legend-text">C - Closure</span>
                                                    </div>
                                                </div>
                                                <div id="dynamic-content">
                                                   
                                                </div>
                                                <button type="button" class="btn btn-secondary mt-3"
                                                    id="addSectionBtn">Add Particular and Classification</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-success" data-toggle="tooltip" id="frm_submit"
                                            title="Save Account" style="width: 100%;">Save Account</button>

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