<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
iBCAS | Payment
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 

?>
<div class="main-content">
    <div class="page-content">
    <?= $this->renderSection('content_toastAlert') ?>
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Payment</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">Payment</li>
                                <li class="breadcrumb-item active">Masterlist</li>
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
                            <form id="ftr_paymentlist">
                                <div class="row">

                                    <label for="horizontal-firstname-input" class="col-sm-1 col-form-label">Search
                                        By:</label>
                                    <div class="col-sm-4">
                                        <select class="form-select" id="ftr_selby" name="ftr_selby">
                                            <!-- <option value="" selected>All</option> -->
                                            <option value="blpdno">Business Control Number</option>
                                            <option value="busname">Business Name</option>
                                            <option value="taxpayer">Tax Payer</option>
                                            <!-- <option value="transtatus">Transaction Status</option> -->
                                            <option value="mop">Mode of Payment</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4" id="ftr_valDiv">
                                        <input class="form-control" id="ftr_val" name="ftr_val" type="text">
                                    </div>
                                    <div class="col-sm-4" id="ftr_selValDiv" hidden>
                                    <!-- <select class="form-select" id="ftr_selVal" name="ftr_selVal">
                                            
                                            <option value="_topay">For Payment</option>
                                            <option value="_partiallyPaid">Partially Paid</option>
                                            <option value="_fullyPaid">Fully Paid</option>
                                            <option value="transtatus">Transaction Status</option>
                                            
                                        </select> -->
                                    <select class="form-select" id="ftr_selVal" name="ftr_selVal">
                                            <option value="" selected disabled>Select</option>
                                           
                                            <!-- <option value="transtatus">Transaction Status</option> -->
                                            <!-- <option value="">Payment Reference No.</option> -->
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" id="frm_submit" data-placement="top" data-toggle="tooltip" title="Filter rates" class="btn btn-primary">Filter</button>
                                        <button type="button" id="frm_loading"
                                            class="btn btn-sm btn-primary waves-effect waves-light" disabled="disabled"><span
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
                            <!-- <div class="row">
                                <div class="col-12">
                                    <a href="<?php echo base_url('/ibcas/penalty-rates/create')?>" target="_blank"
                                        class="btn btn-secondary">Add New Penalty Rate</a>
                                </div>
                            </div>
                            <br> -->
                            <!-- <h4 class="card-title mb-3">Inspection Report</h4> -->
                            <table id="payment-masterlist" class="table table-bordered table-striped" width="100%"
                                style="text-align: center; vertical-align: middle;">
                                <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                                <tr>
                                    <td style="max-width: 10% !important;">Application Type</td>
                                    <td style="max-width: 15% !important;">Business Control Number</td>
                                    <td style="max-width: 20% !important;">Business Name</td>
                                    <td style="max-width: 20% !important;">Tax Payer Name</td>
                                    <td style="max-width: 20% !important;">Address</td>
                                    <td style="max-width: 15% !important;"></td>
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