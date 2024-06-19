<?php

namespace App\Controllers;
use App\Controllers\AuditTrail;
use CodeIgniter\I18n\Time;
use App\Libraries\PdfLibrary;

// $otherController = new \App\Controllers\UserManagement();
use App\Models\PrintingModel;
use App\Models\ReportManagementModal;
// use App\Models\FeesDefaultModel;
// use App\Models\PenaltyRateListModel;

class ReportsManagement extends BaseController
{
    public function index(){   
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Print Claimstub',$session->get('id'));
            return view('printables/p_claimstub');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }
    public function FilterNoticeReports(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $serFrom = $this->request->getPost('serFrom');
        $serTo = $this->request->getPost('serTo');
        $model = new ReportManagementModal();
        if($data = $model->mFilterNoticeReports($serFrom,$serTo)){
            $AuditTrail->save_logs('[generate-report] : Notice to Comply',$session->get('id'));
            $result = array('status' => 200,'message'=> "Report Generated","TableContent"=>$data);
            return $this->response->setJSON($result);
        } else {
           
            $result = array('status' => 401,'message'=> "Report Generation Failed","TableContent"=>'');
            return $this->response->setJSON($result);
        }

    }
    public function noticeToComply(){
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Notice to Comply',$session->get('id'));
            return view('printables/p_noticeToComply');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }
    public function noticeToComplyReport(){
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Notice to Comply',$session->get('id'));
            return view('reports/rNoticeToComply');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }
    public function orsummary(){
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : OR Summary',$session->get('id'));
            return view('reports/r_orSummaryReport');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }
    public function BusinessMasterlist(){
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Business Masterlist',$session->get('id'));
            return view('reports/r_BusinessMasterlist');
            // return view('reports/sample');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }
    public function BusinessLedger($id){
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Business Ledger',$session->get('id'));
            $data['blpdNumber'] = $id;
            return view('reports/r_BusinessLegder', $data);
            // return view('reports/sample');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }
    public function OrLogbookBusiness(){
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : OR Logbook for Business',$session->get('id'));
            return view('reports/r_BusinessLogbook');
            // return view('reports/sample');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }
    public function ListOfDelinquencies(){
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : List of Delinquencies',$session->get('id'));
            return view('reports/r_ListOfDelinquencies');
            // return view('reports/sample');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }
    public function FilterBusinessMasterlist(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $ftr_selby = $this->request->getPost('ftr_selby');
        $ftr_val = $this->request->getPost('ftr_val');
        $ftr_sel = $this->request->getPost('ftr_sel');
        
        if ($ftr_selby == 'blpdNumber') {
            $ftrdata['ftr_by'] = 'apps.blpdno';
            $ftrdata['ftr_val'] = $ftr_val;
        } else  if ($ftr_selby == 'stats') {
            if ($ftr_sel != 'Y') {
                $ftrdata['ftr_by'] = 'apps.application_type_id';
                $ftrdata['ftr_val'] = $ftr_sel;
            } else {
                $ftrdata['ftr_by'] = 'status';
                $ftrdata['ftr_val'] = $ftr_sel;
            }
           
        } else {
            $ftrdata['ftr_by'] = '';
            $ftrdata['ftr_val'] = '';
        }
       
        $model = new ReportManagementModal();
        $data = $model->filterData($ftrdata);
        $AuditTrail->save_logs('[filter] : Business Establisments',$session->get('id'));
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();
    }
    public function FilterOrSummary(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $DataPosted = $this->request->getPost();
        $model = new ReportManagementModal();
        $data = $model->filterDataOR($DataPosted);
        $AuditTrail->save_logs('[filter] : OR Summary',$session->get('id'));
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();
    }
    public function FilterDelinquents(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $DataPosted = $this->request->getPost();
        $model = new ReportManagementModal();
        $data = $model->filterDataDelinquents($DataPosted);
        $AuditTrail->save_logs('[filter] : OR Summary',$session->get('id'));
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();
    }
    public function FilterDelinquents2(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $DataPosted = $this->request->getPost();
        $model = new ReportManagementModal();
        $data = $model->filterDataDelinquents2($DataPosted);
        $AuditTrail->save_logs('[filter] : OR Summary',$session->get('id'));
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();
    }
    public function FilterOrLogbook(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $DataPosted = $this->request->getPost();
        $model = new ReportManagementModal();
        $data = $model->filterDataORLogbook($DataPosted);
        $AuditTrail->save_logs('[filter] : OR Logbook Business',$session->get('id'));
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();
    }
    public function getBusinessDetails($id){
        $model = new ReportManagementModal();
        $appliationDetails = $model->mGetPaymentDetails($id);
        $result = array('status' => 200,'message'=> "Records Found","appliationDetails"=>$appliationDetails);
        return $this->response->setJSON($result);
    }
    public function getBusinessHistory($id){
        $model = new ReportManagementModal();
        $tableContent = $model->mGetBusinessHistory($id);
        $result = array('status' => 200,'message'=> "Records Found","tableContent"=>$tableContent);
        return $this->response->setJSON($result);
    }
    public function getCollectors(){
        $model = new ReportManagementModal();
        $tableContent = $model->mGetCollectors();
        $result = array('status' => 200,'message'=> "Records Found","TableContent"=>$tableContent);
        return $this->response->setJSON($result);
    }
    public function issueNotice($id){
        $session = session();
        $model = new ReportManagementModal();
        $OrSeries = $model->mCheckNoticeSeries();

        $noticeToComplyArray = array(
            'application_id'    =>  $id,
            'series'            =>  $OrSeries,
            'remarks'            =>  'ISSUED',
            'issued_at' => date('Y-m-d H:i:s'),
            'issued_by' => $session->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $session->get('id'),
        );

        if($model->mSaveNotice($noticeToComplyArray)){

            $busDetails = $model->mgetBusinessDetails($id);
            $result = array('status' => 200,'message'=> "Series Generated","OrSeries"=>$OrSeries,'busDetails'=>$busDetails);
            // 
            // Set the data as flashdata
            foreach ($result as $key => $value) {
                $session->setFlashdata($key, $value);
            }
            return $this->response->setJSON($result);
        }



       
    }
    public function reissueNotice($id){
        $session = session();
        $model = new ReportManagementModal();
        $OrSeries = $model->mCheckNoticeSeries();

        $noticeToComplyArray = array(
            'application_id'    =>  $id,
            'series'            =>  $OrSeries,
            'remarks'            =>  'REISSUED',
            'issued_at' => date('Y-m-d H:i:s'),
            'issued_by' => $session->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $session->get('id'),
        );

        if($model->mSaveNotice($noticeToComplyArray)){
            $busDetails = $model->mgetBusinessDetails($id);
            $result = array('status' => 200,'message'=> "Series Generated","OrSeries"=>$OrSeries,'busDetails'=>$busDetails);
            // 
            // Set the data as flashdata
            foreach ($result as $key => $value) {
                $session->setFlashdata($key, $value);
            }
            return $this->response->setJSON($result);

            // return redirect()->to('/ibcas/reports/generateNotice');

            // $this->generate_pdf($SeriesNo = "2023-00513", $blpdNumber = "7001-2013-0041",$busName="SAMPLE BUSINESS NAME",$dateIssued='2024-03-21',$busAddress="Townhomes San Fernando, Panipuan, CSFP");

        }



       
    }
public function generate_pdf() {
       // create new PDF document
       $session = session();
      
       $SeriesNo = $session->getFlashdata('OrSeries');
       $blpdNumber= $session->getFlashdata('busDetails')['blpdno'];
       $busName=$session->getFlashdata('busDetails')['business_name'];
       $dateIssued=date('M-d-YY');
       $busAddress=$session->getFlashdata('busDetails')['taxpayer_address'];;
       $lengthOfDelinquency=$session->getFlashdata('busDetails')['last_applied_year'];;


    $pdf = new PdfLibrary('P', 'mm', 'LEGAL', true, 'UTF-8', false);

// set document information
// $pdf->SetCreator('KENNETH');
// $pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Notice to comply');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
$pdf->SetMargins(10,1, 10);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// add a page
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table

// add a page
$pdf->AddPage();

$html = '
<style>
  .custom-height {
    line-height: 1.0;
  }
  .custom-height2{
    line-height: 1.1;
  }
</style>
<div style="text-align:center">
<table style="width:100%; border-collapse:collapse;text-align:left;font-size: 11px;">
    <tr>
        <td style="width:30%"></td>
        <td style="width:30%"></td>
        <td style="width:10%"></td>
        <td style="width:12%"></td>
        <td style="width:18%"></td>
    </tr>
    <tr >
        <td></td>
        <td></td>
        
        <td colspan="2" style="text-align: right">Control No: </td>
        <td><span style="text-decoration: underline; font-weight:bold">'.$SeriesNo.'</span><br></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:center">
        <label>Republic of the Philippines</label><br>
        <label>Province of Pampanga </label><br>
        <label>CITY OF SAN FERNANDO</label><br>
        <label>OFFICE OF THE CITY TREASURER </label><br><br>
        
        <label style="font-size: 12px; font-weight: bold;">NOTICE TO COMPLY</label>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="3" style="text-align: center"><label>Business Control No.: </label><span style="text-decoration: underline; font-weight:bold">'.$blpdNumber.'</span><br>
                        <label>Business Address : ______</label>
        </td>
       
    </tr>
    <tr>
        <td style="text-align: left;" colspan="2">
        <label>'.$this->formatDate($dateIssued).'</label><br>
        <label>Owner :</label><br>
        <label>Owner Address :</label><br>
        <label>Business Name: '.$busName.'</label>

        </td>
        <td></td>
        <td></td>
    </tr>
    <tr >
        <td colspan="5">
        <p>Sir/Madam:</p>
        <p style="text-align:justify; text-indent: 40px;">Upon verification of your Business Permit/s Business Tax Payment Certificates on file with this Office, the undersigned personnel noted the following deficiency/ies and or violations of the existing Local Revenue Code of the City Of San Fernando (P) viz: </p>
        <br>
        </td>
    </tr>
    <tr>
        <td colspan="5" style="padding:30px">
        
        <table  style="width:100%; text-align:left;font-size: 12px;">
        <tr>
            <td style="width:10%;">_________</td>
            <td style="width:90%;">Operating a business without Registration and a valid Mayor'."'".'s Business Permit with the City Of San Fernando (P)</td>
        </tr>
        <tr>
            <td style="width:10%;">_________</td>
            <td style="width:90%;">Operating a business without the approved additional requirements</td>
        </tr>
        <tr>
            <td style="width:10%;">&nbsp;&nbsp;&nbsp;&nbsp;[X]&nbsp;&nbsp;</td>
            <td style="width:90%;">Failure to renew the Mayor'."'".'s Business Permit for the CY <b>'.$lengthOfDelinquency.'-'.date('Y').'</b>.</td>
        </tr>
        <tr>
            <td style="width:10%;">_________</td>
            <td style="width:90%;">Failure to pay the business tax/es and/or fees for the _______________ qtr/s or CY _______________.</td>
        </tr>
        <tr>
            <td style="width:10%;">_________</td>
            <td style="width:90%;">Non-display of Mayor'."'".'s Permit and other mandated requirements of goods and services. </td>
        </tr>
        <tr>
            <td style="width:10%;">_________</td>
            <td style="width:90%;">Non-issuance of sales invoices, officials receipts and other receipts acknowleding the sales.</td>
        </tr>
        <tr>
            <td style="width:10%;">_________</td>
            <td style="width:90%;">Commission of Specific Violations enumerated under the City Tax Ordinance to wit: </td>
        </tr>
        <tr>
            <td style="width:10%;"></td>
            <td style="width:90%;">
        <ol type="a">
        <li>Using delivery receipts, order slips, etc., other than BIR Registered Invoices.</li>
        <li>Using sales invoices of official receipts of the branch or principal office of the business establishments located outside the City Of San Fernando (P) even if the said invoices or receipts are registered with the BIR; (New)</li>
        <li>Misdeclaration or Undervaluation of Goods Sold or Services Rendered.</li>
        <li>Other related violations.</li>
        </ol>
            </td>
        </tr>

        <tr>
            <td style="width:10%;">_________</td>
            <td style="width:90%;">Employing a person who has not paid his occupational or professional tax and community tax prior to employment</td>
        </tr>
        <tr>
            <td style="width:10%;">_________</td>
            <td style="width:90%;">Using weighing scales and/or other instruments of weights and measures without the required Annual Testing and Sealing</td>
        </tr>
        <tr>
            <td style="width:10%;">_________</td>
            <td style="width:90%;">Non-payment of amusement tax</td>
        </tr>
        <tr>
            <td style="width:10%;">_________</td>
            <td style="width:90%;">Non-payment of other regulatory fees, licenses charges and clearances</td>
        </tr>
        <tr>
            <td style="width:10%;">_________</td>
            <td style="width:90%;">Non-declaration of other businesses, related or incidental to business operations </td>
        </tr>
        <tr>
            <td style="width:10%;">&nbsp;&nbsp;&nbsp;&nbsp;[X]&nbsp;&nbsp;</td>
            <td style="width:90%;">Non-application of the termination, transfer and retirement of business </td>
        </tr>
        <tr>
            <td style="width:10%;">_________</td>
            <td style="width:90%;">Non-submission of financial documents </td>
        </tr>



        </table>
        </td>
    </tr>
    <tr>
        <td colspan="5">
        <p style="text-align:justify; text-indent: 40px;">
        In view hereof, you are requested to visit the Permits and Licenses or the undersigned regarding the above stated violation and tax deficiencies within three (3) days from receipt. 
        </p>
        </td>
    </tr>
    <tr class="custom-height">
        <td colspan="5">
        <p style="text-align:justify; text-indent: 40px;">
        Please give this matter your preferential attention to avoid future inconvenience. 
        </p>
        </td>
    </tr>
    <tr class="custom-height">
        <td colspan="5">
        <p style="text-align:justify; text-indent: 40px;">
        Your valued cooperation will be highly appreciated.
        </p>
        </td>
    </tr>

    <tr class="custom-height2">
        <td colspan="5">
        <table style="width:100%; text-align:left;font-size: 11px;">
        <tr style="padding-top: 2px">
            <td style="width:5%;"></td>
            <td style="width:30%;"></td>
            <td style="width:30%;"></td>
            <td style="width:35%;"></td>
        </tr>
        <tr>
            <td ></td>
            <td >Prepared by:</td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
     
        <tr>
        <td></td>
        <td style="text-align:center;">
       __________________________________<br>
        <label style="font-weight: bold;">USER</label>
        <br><label>LTOO IV</label><br>
        </td>
        <td colspan="2"></td>
        </tr>

        <tr>
            <td ></td>
            <td >Verified by:</td>
            <td></td>
             <td >Noted by:</td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
     
        <tr>
        <td></td>
        <td style="text-align:center;">
       __________________________________<br>
        <label style="font-weight: bold;">AGNES BERNABE</label>
        
        </td>
        <td></td>
        <td>
        <br><br>
        <label style="font-weight: bold;">MS. MARY ANN P. BAUTISTA</label>
        </td>
        </tr>

        </table>
        </td>
        
    </tr>
   
</table>
</div>
';
$pdf->writeHTML($html, true, false, true, false, '');



// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table

$this->response->setHeader("Content-Type", "application/pdf");
$pdf->Output('Notice to .pdf', 'I');
        
    }
    
    public function formatDate($dateString) {
        // Convert date string to timestamp
        $timestamp = strtotime($dateString);
        
        // Format the timestamp into 'Month Day, Year' format
        $formattedDate = date('F d, Y', $timestamp);
        
        return $formattedDate;
    }  

}
