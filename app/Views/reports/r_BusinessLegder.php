<!DOCTYPE html>
<html>

<head>
    <title>
iBCAS | Business Ledger
</title>
    <?= $this->include('layout/header') ?>

</head>


<body data-topbar="dark" data-layout="horizontal" data-layout-size="boxed">

<div class="main-content">
    <div class="page-content">
    
        <div class="container-fluid">
            
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Business Ledger</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">View</li>
                                <li class="breadcrumb-item active">Report</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                        <input type="hidden" name="blpdNum" id="blpdNum" value="<?php echo $blpdNumber ?>" >
                            <div class="row">
                                <div class="col-3"><label>Business Account Number : </label></div><div class="col-9"><label id="blpd_no"></label></div>
                                <div class="col-3"><label>Tax Payer : </label></div><div class="col-9"><label id="lbl_taxpayer"></label></div>
                                <div class="col-3"><label>Business Name : </label></div><div class="col-9"><label id="lbl_busname"></label></div>
                                <div class="col-3"><label>Business Address : </label></div><div class="col-9"><label id="lbl_busAddress"></label></div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                <table id="tbl_businessLedger" class="table table-bordered table-striped" width="100%"
                                style="text-align: center; vertical-align: middle;">
                                <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                                    <tr>
                                        <td style="width: 20% !important;">Transaction Date</td>
                                        <td style="width: 15% !important;">Tax Year</td>
                                        <td style="width: 15% !important;">Mode of Payment</td>
                                        <td style="width: 15% !important;">Transaction Type</td>
                                        <td style="width: 20% !important;">Transaction Number</td>
                                        <td style="width: 15% !important;">Assessment</td>
                                        <td style="width: 15% !important;">Interest</td>
                                        <td style="width: 15% !important;">Payment</td>
                                        <td style="width: 15% !important;">Balance</td>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                   
                            
                                </tbody>
                            </table>
                                </div>
                            
                            </div>
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
 

    <!-- JAVASCRIPT -->
<?= $this->include('layout/footer') ?>
</body>

</html>