<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Payment - Quarterly
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
                        <h4 class="mb-sm-0 font-size-18">Transaction Details (Quarterly)</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">Payment</li>
                                <li class="breadcrumb-item active">Quarterly</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-1"></div>
            </div>
            <!-- end page title -->

            <div class="row">

            <div class="col-1"></div>
                <div class="col-10">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-12" style="margin-bottom: 10px;">
                                <label>Business Control Number : <span style="color:red;">*</span></label>
                                <!-- <input class="form-control" type="text" id="assign" name="assign" required> -->
                                <div class="input-group">
                                <input type="text" class="form-control" id="user_label" name="user_label" disabled>
                                <input type="hidden" class="form-control" id="user_id" name="user_id" required>
                                <button class="btn btn-primary" type="button"  id="btn-search-users" data-bs-toggle="offcanvas" data-bs-target="#offcanvasUser">Search for Business</button>
                                </div>
                            </div>
                            <div class="col-12" style="margin-bottom: 10px;">
                            <label>Owner's Name : <span style="color:red;">*</span></label>    
                            <input class="form-control" type="text" id="address" name="address" required>
                            </div>
                            <div class="col-9" style="margin-bottom: 10px;">
                            <label>Business Name : <span style="color:red;">*</span></label>    
                            <input class="form-control" type="text" id="address" name="address" required>
                            </div>
                            <div class="col-3" style="margin-bottom: 10px;">
                            <label>O.R. Date : <span style="color:red;">*</span></label>    
                            <input class="form-control" type="date" id="address" name="address" required>
                            </div>
                            <div class="col-5" style="margin-bottom: 10px;">
                            <label>Mode of Payment : <span style="color:red;">*</span></label>    
                            <select class="form-control" type="text" id="mode_of_payment" name="mode_of_payment" required>
                                <option selected value="" disabled>Select</option>
                            </select>
                            </div>
                            <div class="col-12" style="margin-bottom: 10px;">
                            <label>Computed as of Date: 07-07-2023</label>
                            <table id="penalty-rates" class="table table-bordered table-striped" width="100%"
                                style="text-align: center; vertical-align: middle;">
                                <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                                    <tr>
                                        <td>Year</td>
                                        <td>Installment</td>
                                        <td>Ammount</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                        <td>2023</td>
                                        <td>1</td>
                                        <td>10,445.60</td>
                                    </tr>
                                <tr>
                                        <td>2023</td>
                                        <td>2</td>
                                        <td>4,743.75</td>
                                    </tr>
                                <tr>
                                        <td>2023</td>
                                        <td>3</td>
                                        <td>4,743.75</td>
                                    </tr>
                                <tr>
                                        <td>2023</td>
                                        <td>4</td>
                                        <td>7,743.75</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                            <div class="col-9" style="margin-bottom: 10px;"></div>
                            <div class="col-3" style="margin-bottom: 10px;">
                            <label>Select installment :</label>    
                            <div class="row">
                            <div class="col-6">
                            <label>From :</label>
                            <select class="form-control" type="text" id="" name="" required>
                                <option selected value="" disabled>Select</option>
                                <option value="">1</option>
                                <option value="">2</option>
                                <option value="">3</option>
                                <option value="">4</option>
                            </select>
                            </div>
                            <div class="col-6">
                            <label>To :</label>
                            <select class="form-control" type="text" id="" name="" required>
                                <option selected value="" disabled>Select</option>
                                <option value="">1</option>
                                <option value="">2</option>
                                <option value="">3</option>
                                <option value="">4</option>
                            </select>
                            </div>
                            </div>
                            
                            </div>
                            <div class="col-12">
                                <label>Next O.R. is 4121885 as of February 21, 2023</label>
                            </div>
                            </div>



                            <br>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-success" id="frm_submit" style="width: 100%;">Next</button>
                                <button class="btn btn-info" id="frm_loading" style="width: 100%;" disabled="disabled"><span
                                                class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"> </span> Loading</button>
                            </div>
                        </div>
                        </div>
                        <!-- end card body -->
                    </div>

                </div>
                <div class="col-1"></div>
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