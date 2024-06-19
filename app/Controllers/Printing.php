<?php

namespace App\Controllers;
use App\Controllers\AuditTrail;
use CodeIgniter\I18n\Time;
// $otherController = new \App\Controllers\UserManagement();
use App\Models\PrintingModel;
use App\Models\PaymentModel;
// use App\Models\FeesDefaultModel;
// use App\Models\PenaltyRateListModel;
use App\Libraries\PdfLibrary;
class Printing extends BaseController
{
    public function index()
    {   
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Print Claimstub',$session->get('id'));
            return view('printables/p_claimstub');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    public function OfficialReceipt(){
        $session = session();
        $assessmentSlipId = $this->request->getVar('assessment_slip_id');
        $installmentId = $this->request->getVar('installment_id');

        // $data['assessment_id'] = $assessmentSlipId;
        // $data['installment_id'] = $installmentId;
        // print_r($data);
        // die();


        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Print Official Receipt',$session->get('id'));
            $data['assessment_id'] = $assessmentSlipId;
            $data['installment_id'] = $installmentId;
            // $data['assessment_id'] = '212';
            // $data['installment_id'] = '2';
            $data['action'] = 'PRINT OFFICIAL RECEIPT';
            return view('printables/p_officialReceipt',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }
    public function OfficialReceiptReprint($id){
        $session = session();
        
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Rerint Official Receipt',$session->get('id'));
            $data['assessment_id'] = $id;
            $data['action'] = 'REPRINT OFFICIAL RECEIPT';
            return view('printables/p_officialReceipt',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }
    public function OfficialReceiptMsc($id){
        $session = session();
        
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Print Official Receipt',$session->get('id'));
            $data['msc_payment_id'] = $id;
            return view('printables/p_officialReceiptMsc',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }

    public function claimStub(){
        $session = session();
         $assessmentSlipId = $this->request->getVar('assessment_slip_id');
        $installmentId = $this->request->getVar('installment_id');
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Print Claimstub',$session->get('id'));
            $data['assessment_slip_id'] = $assessmentSlipId;
            $data['installment_id'] = $installmentId;
            return view('printables/p_claimstub',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }

    public function getClaimStubDetails($id){
        $session = session();
        $model = new PrintingModel();
        // $AuditTrail = new AuditTrail();
        // $AuditTrail->save_logs('[fetch] : Claim Stub Details',$session->get('id'));

        $appliationDetails = $model->mGetPaymentDetailsGeneral($id);
        $appId = $appliationDetails['id'];

        // print_r($appliationDetails);
        // die();
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "applicationDetails" => $appliationDetails,
            "duedate" => $this->calculateDueDate($appId),
        ];

        return $this->response->setJSON($result);

    }
    public function getPaymentDetails(){
        $session = session();
        $model = new PrintingModel();
        $assessmentSlipId = $this->request->getVar('assessment_slip_id');
        $installmentId = $this->request->getVar('installment_id');
        
        
        $PaymentModel = new PaymentModel();
        // $AuditTrail = new AuditTrail();
        // $AuditTrail->save_logs('[fetch] : Claim Stub Details',$session->get('id'));

        $appliationDetails = $model->mGetPaymentDetails($assessmentSlipId,$installmentId);
        $busTax = $PaymentModel->m_getFeesTotalMOP_semi($assessmentSlipId);
        $MayorsFee = $model->m_getFeesTotal_Mayors_fee($assessmentSlipId);
        // $OrDetails = $model->m_get_OrDetails($id);
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "applicationDetails" => $appliationDetails,
            "busTax" => $busTax,
            "MayorsFee" => $MayorsFee,
        ];

        return $this->response->setJSON($result);

    }
    public function getPaymentDetailsMsc($id){
        $session = session();
        $model = new PrintingModel();
        $PaymentModel = new PaymentModel();
        // $AuditTrail = new AuditTrail();
        // $AuditTrail->save_logs('[fetch] : Claim Stub Details',$session->get('id'));

        $PaymentDetails = $model->mGetPaymentDetailsMsc($id);
        // $busTax = $PaymentModel->m_getFeesTotalMOP_semi($id);
        // $MayorsFee = $model->m_getFeesTotal_Mayors_fee($id);
        // $OrDetails = $model->m_get_OrDetails($id);
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "PaymentDetails" => $PaymentDetails,
            // "busTax" => $busTax,
            // "MayorsFee" => $MayorsFee,
        ];

        return $this->response->setJSON($result);

    }
    public function getFeesDetails($id){
        $session = session();
        $model = new PrintingModel();
        // $PaymentModel = new PaymentModel();
        // $AuditTrail = new AuditTrail();
        // $AuditTrail->save_logs('[fetch] : Claim Stub Details',$session->get('id'));

        $feesList =  $model->m_getListOfFees($id);
        // $busTax = $PaymentModel->m_getFeesTotalMOP_semi($id);
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "feesList" => $feesList,
            // "busTax" => $busTax,
        ];

        return $this->response->setJSON($result);

    }
    public function getFeesDetailsMsc($id){
        $session = session();
        $model = new PrintingModel();
        // $PaymentModel = new PaymentModel();
        // $AuditTrail = new AuditTrail();
        // $AuditTrail->save_logs('[fetch] : Claim Stub Details',$session->get('id'));

        $feesList =  $model->m_getListOfFeesmsc($id);
        // $busTax = $PaymentModel->m_getFeesTotalMOP_semi($id);
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "feesList" => $feesList,
            // "busTax" => $busTax,
        ];

        return $this->response->setJSON($result);

    }


    public function calculateDueDate($applicationId) {
        // Application details
        // $applicationId = 10406;
        $model = new PrintingModel();
    
        // Retrieve application details from the model
        $applicationData = $model->getApplicationData($applicationId);
    
        // Check if application data was retrieved successfully
        if ($applicationData !== null) {
            $dateApplied = date('Y-m-d');
            // $dateApplied = $applicationData['date_applied'];
            $applicationTypeId = $applicationData['application_type_id'];
    
            // Calculate the due date
            $dueDate = $model->calculateDueDate($applicationTypeId, $dateApplied);
            
            if ($dueDate !== null) {
                // $dateString = "2023-12-19";
                // $dateTime = new DateTime($dueDate);
                $convertedDate = Time::createFromFormat('Y-m-d', $dueDate)->format('F j, Y');
                return $convertedDate;
            } else {
                return 'Due Date cannot be calculated.';
            }
        } else {
            return 'Application data not found.';
        }
    }
    
    public function printOrPdf(){
        // create new PDF document
        $session = session();
        helper('number'); 
    
        $pdf = new PdfLibrary('L', 'mm', array(279.4, 215.9 / 2), true, 'UTF-8', false); // Custom dimensions, landscape mode
    
        $pdf->SetTitle('OFFICIAL RECEIPT');
        $pdf->SetMargins(10, 1, 10);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
    
        $pdf->AddPage();
    
        $html = "<div>

                    <div id='printDiv' style='font-family: Arial, Helvetica, sans-serif;'>
                        <div style='font-family: Arial, Helvetica, sans-serif;'>
                            <div class='row' id='margTop'  style='text-align: left;'>
                                <div class='col-5'><label id='date_paid'></label></div>
                        <div class='col-7'></div>
                    </div>
    
                    <div class='row' style='text-align: right; margin-top: 17px'>
                        <div class='col-8'><label></label></div>
                        <div class='col-4'><label class='lbl1' id='ornumpaid'></label><br><label class='lbl1' id='blpd_no'></label></div>
                    </div>
                    <div class='row' style='text-align: center;'>
                        <div class='col-12' style='margin-bottom: 0;'><label id='lbl_taxpayer' class='lbl2'></label></div>
                        <div class='col-12' style='margin-top: 0;'><label id='lbl_busname' style='font-size: 20px; font-weight:bold' class='lbl3'>LAKESHORE, S.F. FOOD PHILS. INC</label></div>
                    </div>
                    <div class='row' >
                        <div class='col-12'>
                            <label class='lbl2'></label>
                            <label class='lbl2' style='float: right !important;'></label>
                        </div>
                    </div>
                    <div class='row' style='text-align: center; margin-top: 40px;'>
                    <table style='width:95%; font-weight: bold;''>
                        <tr>
                            <td style='width:53%'><label class='lbl3' style='font-size: 15px;'>Business Tax</label></td>
                            <td style='width:20%'><label id='taxYear' class='lbl3'>2023</label></td>
                            <td style='width:27%; text-align:right; margin-right:20px;'><label id='busTaxAmount' class='lbl3'>50,000.00</label></td>
                        </tr>
                        <tr>
                            <td style='width:53%'><label class='lbl3' style='font-size: 15px;'>Mayor"."'"."s Fee</label></td>
                            <td style='width:20%'><label class='lbl3' id='moPayment'>ANNUAL</label></td>
                            <td style='width:27%; text-align:right; margin-right:20px;'><label id='mayorsFeeAmount' class='lbl3'>2,684.62</label></td>
                        </tr>
                    </table>
                    <div style='min-height: 14%; max-height: 14%;'>
                    <table id='feesTable' style='text-align:center; width:95%'>
                    <tbody>
                        <!-- Rows will be added here by JavaScript -->
                    </tbody>
                    </table>
                    </div>
                    <div class='row' style='margin-top:3%'>
                        <div class='col-12' style='text-align: right;'>
                            <label class='lbl3' id='paidTotal'>0.00</label>
                        </div>
                      
                    </div>
                    <div class='row' style='margin-top:3%'>
                        <div class='col-12' style='text-align: left;'>
                            <label class='lbl3' id='amountToWords'></label>
                        </div>
                      
                    </div>




                    </div>

                </div>
                   
                    </div>
                </div>

             ";
    
        $pdf->writeHTML($html, true, false, true, false, '');
    
        $this->response->setHeader("Content-Type", "application/pdf");
        $pdf->Output('OfficialReceiptBusiness', 'I');
    }
    


}
