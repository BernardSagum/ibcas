<div class="topnav">
    
                <div class="container-fluid">
                    <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                        <div class="collapse navbar-collapse" id="topnav-menu-content">
                            <ul class="navbar-nav">

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                                    >
                                        <i class="bx bx-cog bx-spin"></i> <span key="t-dashboards"> Maintenance</span> <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">

                                        <a href="<?php echo base_url('ibcas/penalty-rates') ?>" class="dropdown-item" key="t-default">Penalty Rates</a>
                                        <a href="<?php echo base_url('ibcas/claim-stub') ?>" class="dropdown-item" key="t-default">Claim Stub</a>
                                         <a href="<?php echo base_url('ibcas/acc-forms') ?>" class="dropdown-item" key="t-saas">Accountable Forms</a>
                                         <a href="<?php echo base_url('ibcas/a-officers') ?>" class="dropdown-item" key="t-saas">Accountable Officers</a>
                                         <a href="<?php echo base_url('ibcas/collectors') ?>" class="dropdown-item" key="t-saas">Collectors</a>
                                         <a href="<?php echo base_url('ibcas/miscellaneous') ?>" class="dropdown-item" key="t-saas">Miscellaneous Fees</a>
                                        <a href="<?php echo base_url('ibcas/reports/issuance-notice-to-comply') ?>" class="dropdown-item" key="t-crypto">Issuance of Notice to Comply</a>
                                        <a href="<?php echo base_url('ibcas/reports/TaxCredit') ?>" class="dropdown-item" key="t-default">Issuance of Tax Credit</a>
                                        <a href="<?php echo base_url('ibcas/cashTicket') ?>" class="dropdown-item" key="t-inbox">Cash Ticket</a>
                                        <a href="<?php echo base_url('ibcas/particulars') ?>" class="dropdown-item" key="t-inbox">Particulars</a>
                                        <a href="<?php echo base_url('ibcas/cAccounts') ?>" class="dropdown-item" key="t-inbox">Chart of Accounts</a>
                                        <a href="<?php echo base_url('ibcas/sAccounts') ?>" class="dropdown-item" key="t-inbox">Sub-Accounts</a>
                                        <a href="<?php echo base_url('ibcas/banks') ?>" class="dropdown-item" key="t-inbox">Banks Masterlist</a>
                                        <a href="<?php echo base_url('ibcas/profs') ?>" class="dropdown-item" key="t-inbox">Professions Masterlist</a>
                                        <!-- <a href="dashboard-blog.html" class="dropdown-item" key="t-blog">Blog</a>
                                        <a href="dashboard-job.html" class="dropdown-item" key="t-Jobs">Jobs</a> -->
                                    </div>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-pages" role="button">
                                        <i class="bx bx-receipt bx-tada me-2"></i><span key="t-apps">Transactions</span> <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-pages">

                                        <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-email" role="button">
                                                <span key="t-calendar">Payment</span> <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="topnav-email">
                                                <a href="<?php echo base_url('ibcas/payment') ?>" class="dropdown-item" key="t-tui-calendar">Business tax</a>
                                                <a href="<?php echo base_url('ibcas/payment/miscellaneous') ?>" class="dropdown-item" key="t-tui-calendar">Miscellaneous</a>
                                              
                                            </div>
                                            
                                        </div>

                                        <!-- <div class="dropdown">
                                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="cashTicket" role="button">
                                                <span key="t-email">Cash Ticket</span> <div class="arrow-down"></div>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="cashTicket">
                                                <a href="<?php echo base_url('ibcas/cashTicket/') ?>" class="dropdown-item" key="t-inbox">OnGoing</a>
                                                <a href="email-read.html" class="dropdown-item" key="t-read-email">Read Email</a> -->

                                                <!-- <div class="dropdown">
                                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-cashTicket" role="button">
                                                        <span key="t-email-templates">Templates</span> <div class="arrow-down"></div>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="topnav-cashTicket">
                                                        <a href="email-template-basic.html" class="dropdown-item" key="t-basic-action">OnGoing</a>
                                                        <a href="email-template-alert.html" class="dropdown-item" key="t-alert-email">Alert Email</a>
                                                        <a href="email-template-billing.html" class="dropdown-item" key="t-bill-email">Billing Email</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->


                                    
                                </div></li>
                                
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                                    ><i class="bx bx-receipt bx-tada"></i> <span key="t-dashboards"> Reports</span> <div class="arrow-down"></div>
                                    </a>
                                    
                                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">

                                        <a href="<?php echo base_url('ibcas/reports/busMasterlist') ?>" class="dropdown-item" key="t-default">Business Masterlist</a>
                                        <!-- <a href="<?php //echo base_url('ibcas/payment') ?>" class="dropdown-item" key="t-default">Abstract of Collection Report</a> -->
                                        <!-- <a href="<?php //echo base_url('ibcas/payment') ?>" class="dropdown-item" key="t-default">Collection Report</a> -->
                                        <!-- <a href="<?php //echo base_url('ibcas/payment') ?>" class="dropdown-item" key="t-default">Collection Report by Type of Payment</a> -->
                                        <!-- <a href="<?php //echo base_url('ibcas/payment') ?>" class="dropdown-item" key="t-default">Delinquent Masterlist (BLPD)</a> -->
                                        <!-- <a href="<?php //echo base_url('ibcas/payment') ?>" class="dropdown-item" key="t-default">Key Performance Measures Monitoring</a> -->
                                        <!-- <a href="<?php //echo base_url('ibcas/payment') ?>" class="dropdown-item" key="t-default">List of Business Establishments</a> -->
                                        <a href="<?php echo base_url('ibcas/reports/List-Delinquencies') ?>" class="dropdown-item" key="t-default">List of Delinquencies (Cashier)</a>
                                        <!-- <a href="<?php //echo base_url('ibcas/payment') ?>" class="dropdown-item" key="t-default">Logbook on Collections for Business Tax and Other Regulatories</a> -->
                                        <!-- <a href="<?php //echo base_url('ibcas/payment') ?>" class="dropdown-item" key="t-default">Masterlist of Retired Businesses (Cashier)</a> -->
                                        <a href="<?php echo base_url('ibcas/reports/noticeToComplyReport') ?>" class="dropdown-item" key="t-default">Notice to Comply Report</a>
                                        <a href="<?php echo base_url('ibcas/reports/OrLogbookBusiness'); ?>" class="dropdown-item" key="t-default">OR Logbook Business</a>
                                        <a href="<?php echo base_url('ibcas/reports/OrSummaryReport'); ?>" class="dropdown-item" key="t-default">OR Summary Report</a>
                                        <a href="<?php //echo base_url('ibcas/payment') ?>" class="dropdown-item" key="t-default">Summary of Collection Report</a>
                                        <a href="<?php echo base_url('ibcas/reports/TaxBill') ?>" class="dropdown-item" key="t-default">Tax Bill</a>
                                        <a href="<?php //echo base_url('ibcas/payment') ?>" class="dropdown-item" key="t-default">Business Ledger</a>
                                       
                                    </div>
                                </li>




                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
