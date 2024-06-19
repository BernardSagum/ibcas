<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('UserManagement');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);
$routes->set404Override(function() {
    header('Location: '. base_url().'/404');
});
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// Home Controller
$routes->get('ibcas/decrypt', 'collectors::seedecrypt');
$routes->get('/home', 'Home::index');

// UserManagement Controller
$routes->get('ibcas/login', 'UserManagement::login');
$routes->get('ibcas/logout', 'UserManagement::logout');
$routes->get('ibcas/userman', 'UserManagement::index');
$routes->get('ibcas/users/profile', 'UserManagement::UserProfile');
$routes->post('/ibcas/userlogin', 'UserManagement::user_authentication');
$routes->post('/ibcas/userman/checkpassword', 'UserManagement::ConfimPassword');
$routes->post('/ibcas/users/changePassword', 'UserManagement::ChangePassword');

// ClaimStub Controller
$routes->get('/ibcas/claim-stub', 'ClaimStub::index');
$routes->get('/ibcas/claim-stub/view/(:num)', 'ClaimStub::viewing/$1');
$routes->get('/ibcas/claim-stub/edit/(:num)', 'ClaimStub::edit/$1');
$routes->get('/ibcas/claim-stub/create', 'ClaimStub::create');
$routes->post('/ibcas/claim-stub/filter', 'ClaimStub::claim_stub_filter');
$routes->post('/ibcas/claim-stub/save', 'ClaimStub::save_claimstub');
$routes->get('/ibcas/claim-stub/delete-claimstub-schedule/(:num)', 'ClaimStub::delete_cs/$1');
$routes->get('/ibcas/claim-stub/get-view-info/(:num)', 'ClaimStub::get_edit_info/$1');
$routes->get('/ibcas/claim-stub/get-apptype', 'ClaimStub::getapptype');

// AccForms Controller

$routes->get('/ibcas/acc-forms', 'AccForms::index');
$routes->get('/ibcas/acc-forms/view/(:num)', 'AccForms::viewing/$1');
$routes->get('/ibcas/acc-forms/edit/(:num)', 'AccForms::edit/$1');
$routes->get('/ibcas/acc-forms/void/(:num)', 'AccForms::void/$1');
$routes->get('/ibcas/acc-forms/create', 'AccForms::create');
$routes->get('/ibcas/acc-forms/assign/(:num)', 'AccForms::assign/$1');
$routes->get('/ibcas/acc-forms/reassign/(:num)', 'AccForms::reassign/$1');
$routes->get('/ibcas/acc-forms/get-all-forms/', 'AccForms::getforms');
$routes->get('/ibcas/acc-forms/get-all-funds/', 'AccForms::getfunds');
$routes->post('/ibcas/acc-form/filter', 'AccForms::acc_forms_filter');
$routes->post('/ibcas/acc-forms/save', 'AccForms::save_accform');
$routes->post('/ibcas/acc-forms/assign-form', 'AccForms::save_assign_form');
$routes->post('/ibcas/acc-forms/void-form', 'AccForms::save_void_form');
$routes->post('/ibcas/acc-forms/reassign-form', 'AccForms::save_reassign_form');
$routes->post('/ibcas/acc-forms/gen-series', 'AccForms::generate_series');
$routes->post('/ibcas/acc-forms/reassign-series', 'AccForms::reassign_series');
$routes->get('/ibcas/acc-forms/delete-acc-form/(:num)', 'AccForms::delete_accform/$1');
$routes->get('/ibcas/acc-forms/getUsers', 'AccForms::getUsers');
$routes->get('/ibcas/acc-officers', 'AccForms::acc_officer');
$routes->get('/ibcas/acc-forms/get_acc_data/(:num)', 'AccForms::get_edit_info/$1');

// Penalty Controller
$routes->get('/ibcas/penalty-rates', 'Penalty::rates');
$routes->get('/ibcas/penalty-rates/create', 'Penalty::create');
$routes->get('/ibcas/penalty-rates/edit/(:num)', 'Penalty::edit/$1');
$routes->get('/ibcas/penalty-rates/view/(:num)', 'Penalty::viewing/$1');
$routes->post('/ibcas/penalty-rates/filter', 'Penalty::penalty_rates_filter');
$routes->post('/ibcas/penalty-rates/save', 'Penalty::save_penalty_rate');
$routes->get('/ibcas/penalty-rates/get-fees-default', 'Penalty::fees_default');
$routes->post('/ibcas/penalty-rates/get-confirm-password', 'Penalty::ConfimPassword');
$routes->get('/ibcas/penalty-rates/delete-penalty-rate/(:num)', 'Penalty::delete_pen/$1');
$routes->get('/ibcas/penalty-rates/get-edit-info/(:num)', 'Penalty::get_edit_info/$1');
$routes->get('/ibcas/penalty/getTaxYear', 'Penalty::getTaxYear');

// Collectors Controller
$routes->get('/ibcas/collectors', 'Collectors::index');
$routes->get('/ibcas/collectors/getofficers', 'Collectors::getOfficers');
$routes->get('ibcas/collectors/get-view-data/(:num)', 'Collectors::viewDetails/$1');
$routes->get('/ibcas/collectors/view/(:num)', 'Collectors::ViewPage/$1');
$routes->get('/ibcas/collectors/edit/(:num)', 'Collectors::EditPage/$1');
$routes->get('/ibcas/collectors/positions', 'Collectors::getAllPositions');
$routes->get('/ibcas/collectors/barangay', 'Collectors::getAllBarangay');
$routes->get('/ibcas/collectors/create', 'Collectors::create');
$routes->post('/ibcas/collectors/filter', 'Collectors::collectors_filter');
$routes->post('/ibcas/collectors/save-collector', 'Collectors::save_collector');
$routes->get('/ibcas/collectors/delete-collector/(:num)', 'Collectors::delete_col/$1');
// AuditTrail Controller
$routes->get('/ibcas/logs/save', 'AuditTrail::save_logs');
$routes->add('audittrail/save_logs', 'AuditTrail::save_logs');

// Accountable Officers Controller
$routes->get('/ibcas/a-officers', 'AccountableOfficers::index');
$routes->get('/ibcas/a-officers/create', 'AccountableOfficers::createPage');
$routes->get('/ibcas/a-officers/view/(:num)', 'AccountableOfficers::ViewPage/$1');
$routes->get('/ibcas/a-officers/get-officer-data/(:num)', 'AccountableOfficers::GetOfficerInfo/$1');
$routes->post('/ibcas/a-officers/filtertable', 'AccountableOfficers::acc_officer_filter');
$routes->post('/ibcas/a-officers/save-officer', 'AccountableOfficers::saveaAccountableOfficer');
$routes->get('/ibcas/a-officers/edit/(:num)', 'AccountableOfficers::editPage/$1');
$routes->get('/ibcas/a-officer/delete-acc-officer/(:num)', 'AccountableOfficers::deleteOfficer/$1');
$routes->get('/ibcas/a-officer/getUsers', 'AccountableOfficers::getUsers');

// Payment Controller

$routes->get('/ibcas/payment', 'Payment::index');
$routes->get('ibcas/payment/BankNames', 'Payment::getBankNames');
$routes->get('ibcas/payment/getPaymentType', 'Payment::getPaymentTypes');
$routes->get('/ibcas/payment/details/(:num)', 'Payment::pAnnual/$1');
$routes->get('/ibcas/payment/business/(:num)', 'Payment::pBusiness/$1');
$routes->post('/ibcas/payment/check-or-avail/', 'Payment::checkOrAvail');
$routes->get('/ibcas/payment/miscellaneous', 'Payment::pMiscellaneous/');
$routes->get('/ibcas/payment/installment-mop', 'Payment::installmentMop/');
$routes->get('/ibcas/payment/get-fees-list/(:num)', 'Payment::getFeesInfo/$1');
$routes->get('/ibcas/payment/getpaymentinfo/(:num)', 'Payment::getPaymentInfo/$1');
$routes->get('/ibcas/payment/getAssessmentInstallment/(:num)', 'PaymentBusiness::getAssessmentInstallment/$1');

$routes->get('/ibcas/payment/semi-annual', 'Payment::pSemiAnnual');
$routes->get('/ibcas/payment/quarterly', 'Payment::pQuarterly');
$routes->get('/ibcas/payment/getmodeofpayment', 'Payment::getModeOfPayment');
$routes->post('/ibcas/payment/filter', 'Payment::FilterPayment');
$routes->post('/ibcas/payment/post-payment', 'PaymentBusiness::SavePayment');
// $routes->post('/ibcas/payment/post-payment', 'Payment::SavePayment');
$routes->post('/ibcas/payment/post-payment-msc', 'Payment::SavePaymentMsc');
$routes->post('/ibcas/payment/save-assessment-slip-details', 'Payment::SaveAssessmentSlipSetails');
$routes->get('/ibcas/payment/getNextOrNumber', 'Payment::getNextOrNumber');

$routes->get('/ibcas/payment/getMiscType', 'Payment::getMiscType');
$routes->get('/ibcas/payment/getMiscTypeSpec/(:num)', 'Payment::getMiscTypeSpec/$1');
$routes->get('/ibcas/payment/recomputeInstallment', 'PaymentBusiness::recompInstallments/$1');
$routes->get('/ibcas/payment/getBusnessDetails/(:num)', 'Payment::getBusinessDetails/$1');
$routes->post('/ibcas/payment/filterBusiness', 'Payment::filterBusiness');



$routes->get('/ibcas/funds/get-list-funds', 'Collectors::getCollectorFunds');
$routes->get('/ibcas/payment/getNextOrNumber_FundID/(:any)', 'Collectors::getNextOrNumberFundId/$1');


// Printing Controller

// $routes->get('/ibcas/print', 'Printing::index');

$routes->get('/ibcas/print/claimStub', 'Printing::claimStub/$1');
$routes->get('/ibcas/print/Official-Receipt', 'Printing::OfficialReceipt/$1');
$routes->get('/ibcas/reprint/Official-Receipt/(:num)', 'Printing::OfficialReceiptReprint/$1');
$routes->get('/ibcas/print/Official-ReceiptMsc/(:num)', 'Printing::OfficialReceiptMsc/$1');
// $routes->get('/ibcas/print/dueDate', 'Printing::calculateDueDate');
$routes->get('/ibcas/print/get-claimstub-details/(:num)', 'Printing::getClaimStubDetails/$1');
$routes->get('/ibcas/print/get-payment-details', 'Printing::getPaymentDetails/$1');
$routes->get('/ibcas/print/get-payment-detailsmsc/(:num)', 'Printing::getPaymentDetailsMsc/$1');
$routes->get('/ibcas/print/get-fees-details/(:num)', 'Printing::getFeesDetails/$1');
$routes->get('/ibcas/print/get-fees-detailsmsc/(:num)', 'Printing::getFeesDetailsMsc/$1');
$routes->get('/ibcas/print/Official-Receipt-pdf', 'Printing::printOrPdf');


// Miscellaneous Controller
$routes->get('/ibcas/miscellaneous', 'Miscellaneous::index');
$routes->post('/ibcas/miscellaneous/filter', 'Miscellaneous::msc_filter');
$routes->get('/ibcas/miscellaneous/create', 'Miscellaneous::create');
$routes->post('/ibcas/miscellaneous/save', 'Miscellaneous::save_fee');
$routes->get('/ibcas/miscellaneous/delete-miscellaneous-fee/(:num)', 'Miscellaneous::delete_cs/$1');
$routes->get('/ibcas/miscellaneous/view/(:num)', 'Miscellaneous::viewing/$1');
$routes->get('/ibcas/miscellaneous/edit/(:num)', 'Miscellaneous::edit/$1');
$routes->get('/ibcas/miscellaneous/get-view-info/(:num)', 'Miscellaneous::get_edit_info/$1');




// Reports
$routes->get('/ibcas/reports/OrSummaryReport', 'ReportsManagement::orsummary');
$routes->get('/ibcas/reports/busMasterlist', 'ReportsManagement::BusinessMasterlist');
$routes->get('/ibcas/reports/OrLogbookBusiness', 'ReportsManagement::OrLogbookBusiness');
$routes->get('/ibcas/reports/List-Delinquencies', 'ReportsManagement::ListOfDelinquencies');
$routes->get('/ibcas/reports/get-collectors', 'ReportsManagement::getCollectors');
$routes->get('/ibcas/reports/business-legder/(:any)', 'ReportsManagement::BusinessLedger/$1');
$routes->get('/ibcas/reports/getBusinessDetails/(:any)', 'ReportsManagement::getBusinessDetails/$1');
$routes->get('/ibcas/reports/getBusinessHistory/(:any)', 'ReportsManagement::getBusinessHistory/$1');
$routes->post('/ibcas/report/filter-Business/', 'ReportsManagement::FilterBusinessMasterlist');
$routes->post('/ibcas/report/filter-orSummary/', 'ReportsManagement::FilterOrSummary');
$routes->post('/ibcas/report/filter-orLogbook/', 'ReportsManagement::FilterOrLogbook');
$routes->post('/ibcas/report/ftr-delinquencies_2/', 'ReportsManagement::FilterDelinquents2');
$routes->post('/ibcas/report/ftr-delinquencies/', 'ReportsManagement::FilterDelinquents');
// $routes->get('/ibcas/reports/ListOfDelinquencies/', 'ReportsManagement::ListOfDelinquencies');
// $routes->get('/ibcas/report/filter-orLogbook/', 'ReportsManagement::FilterOrLogbook');


// Notice to comply
$routes->get('/ibcas/reports/issuance-notice-to-comply', 'ReportsManagement::noticeToComply');
$routes->get('/ibcas/reports/issue-notice/(:any)', 'ReportsManagement::issueNotice/$1');
$routes->get('/ibcas/reports/reissue-notice/(:any)', 'ReportsManagement::reissueNotice/$1');
$routes->get('/ibcas/reports/generateNotice', 'ReportsManagement::generate_pdf');
$routes->get('/ibcas/reports/noticeToComplyReport', 'ReportsManagement::noticeToComplyReport');
$routes->post('/ibcas/reports/filterNoticeReport', 'ReportsManagement::FilterNoticeReports');


// TAX BILL   
$routes->get('/ibcas/reports/TaxCredit', 'TaxCreditManagement::ViewTaxCredit');
$routes->post('/ibcas/reports/ftr_taxCredit', 'TaxCreditManagement::FilterTaxCredit');
$routes->get('/ibcas/reports/issue-TaxCreditCertificate/(:any)', 'TaxCreditManagement::IssueTaxCert/$1');
$routes->get('/ibcas/reports/generateTaxCredCert', 'TaxCreditManagement::generate_pdf');
$routes->get('/ibcas/payment/getTaxCreditValue/(:any)', 'TaxCreditManagement::getTaxCreditValue/$1');
$routes->get('/ibcas/reports/TaxBill', 'TaxCreditManagement::ViewTaxBill');
$routes->post('/ibcas/reports/filter-taxbill', 'TaxCreditManagement::FilterTaxBill');
$routes->get('//ibcas/reports/get-taxBill-info/(:any)', 'TaxCreditManagement::GetTaxbillInformation/$1');

// Offical Receipt Masterlist

$routes->get('/ibcas/reports/OrMasterlist', 'OrMasterlist::index');
$routes->post('/ibcas/search-or-details', 'OrMasterlist::SearchOrNumberDetails');

// Cash Ticket
$routes->get('ibcas/cashTicket', 'CashTicket::index');


// Particulars
$routes->get('ibcas/particulars', 'Particulars::index');
$routes->get('ibcas/particulars/create', 'Particulars::create');
$routes->get('ibcas/paticulars/get-partType', 'Particulars::getPartTypes');
$routes->post('ibcas/particular/post-particular', 'Particulars::postParticular');
$routes->post('ibcas/particular/ftr-particulars', 'Particulars::ftrParticulars');
$routes->get('ibcas/particulars/view/(:any)', 'Particulars::viewParticulars/$1');
$routes->get('ibcas/particulars/edit/(:any)', 'Particulars::editParticulars/$1');
$routes->get('ibcas/paticulars/deleteParticular/(:any)', 'Particulars::deleteParticular/$1');
$routes->get('ibcas/paticulars/getParticularInfo/(:any)', 'Particulars::getParticularInfo/$1');

// // Accounts
$routes->get('ibcas/cAccounts', 'Accounts::index');
$routes->get('ibcas/cAccounts/create', 'Accounts::create');


$routes->post('ibcas/cAccounts/post-accounts', 'Accounts::postAccounts');

$routes->post('ibcas/cAccounts/ftr-accounts', 'Accounts::ftrcAccounts');

$routes->get('ibcas/cAccounts/view/(:any)', 'Accounts::viewAccounts/$1');
$routes->get('ibcas/cAccounts/edit/(:any)', 'Accounts::editAccounts/$1');
$routes->get('ibcas/cAccounts/deleteAccount/(:any)', 'Accounts::deleteAccount/$1');
$routes->get('ibcas/cAccounts/getAccountInfo/(:any)', 'Accounts::getAccountsInfo/$1');






// Sub-Accounts

$routes->get('ibcas/sAccounts', 'SubAccounts::index');
$routes->get('ibcas/sAccounts/deleteSubAccount/(:any)', 'SubAccounts::deleteSubAccount/$1');
$routes->get('ibcas/sAccounts/deleteClassPart/(:any)', 'SubAccounts::deletePartClass/$1');
$routes->get('ibcas/sAccounts/edit/(:any)', 'SubAccounts::editSubAccount/$1');
$routes->get('ibcas/sAccounts/view/(:any)', 'SubAccounts::viewSubAccount/$1');
$routes->get('ibcas/SAccounts/getSubAccountInfo/(:any)', 'SubAccounts::getSubAccountInfo/$1');
$routes->get('ibcas/SAccounts/getSubAccountParticulars/(:any)', 'SubAccounts::getSubAccountPartClass/$1');
$routes->post('ibcas/sAccounts/ftr_subAccounts', 'SubAccounts::ftrsAccounts');
$routes->get('ibcas/sAccounts/create', 'SubAccounts::create');
$routes->get('ibcas/sAccounts/getParticularsList', 'SubAccounts::getParticularsList');
$routes->post('ibcas/sAccounts/post-accounts', 'SubAccounts::postSubAccounts');
$routes->post('ibcas/particular/ftr-classifications', 'SubAccounts::ftrClassifications');

// BANK MODULE

$routes->get('ibcas/banks', 'Banks::index');
$routes->get('ibcas/banks/create', 'Banks::create');
$routes->get('ibcas/banks/view/(:any)', 'Banks::view/$1');
$routes->get('ibcas/banks/edit/(:any)', 'Banks::edit/$1');
$routes->get('ibcas/banks/getBankDetails/(:any)', 'Banks::getBankDetails/$1');
$routes->get('ibcas/banks/deleteBankDetails/(:any)', 'Banks::deleteBankDetails/$1');
$routes->post('ibcas/banks/ftr-banks', 'Banks::ftrBanks');
$routes->post('ibcas/banks/save-bank', 'Banks::SaveBank');


// PROFESSION MODULE

$routes->get('ibcas/profs', 'Professions::index');
$routes->post('ibcas/profs/ftr-profs', 'Professions::ftrProfs');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}



// if(file_exists((ROOTPATH. 'modules'))) {
//     $modulesPath = ROOTPATH. 'modules/';
//     $modules = scandir($modulesPath);

//     foreach ($modules as $module) {
//         if ($module === '.' || $module === '..') continue;
//         if (is_dir($modulesPath) . '/' . $module) {
//             $routesPath = $modulesPath . $module . '/Config/Routes.php';
//             if(file_exists($routesPath)) {
//                 require($routesPath);
//             }else{
//                 continue;
//             }
//         }
//     }
// }