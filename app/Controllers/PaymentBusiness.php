<?php

namespace App\Controllers;

use App\Models\PaymentModel;
use App\Models\UserManagementModel;
use App\Models\AssessmentSlipModel;

date_default_timezone_set("Asia/Singapore");
class PaymentBusiness extends BaseController
{
    public function index(){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get("isLoggedIn")) {
            $AuditTrail->save_logs(
                "[visit] : Payment Master Page ",
                $session->get("id")
            );
            return view("payment/v_payment");
        } else {
            return redirect()->to(base_url("ibcas/login"));
        }
    }

    public function recompInstallments(){
        $assessmentSlipId = $this->request->getVar('assessment_slip_id');
        $mop = $this->request->getVar('mop');

        $session = session();
        $model = new AssessmentSlipModel();
       
            $data = $model->getAssessmentMOP($assessmentSlipId);
            foreach ($data as $r) {
            
              $taxYear = $r->tax_year;
            }
            $result = [
                "status" => 200,
                "message" => "Records Found",
                "installmentInfo" => $this->generateInstallmentTable($mop,$taxYear,$assessmentSlipId),
                "installmentsSequence" => $this->getInstallmentSequence($mop),
    
            ];
            return $this->response->setJSON($result);




        
    }

    public function getAssessmentInstallment($id){
        // echo $this->generateInstallmentTable();

        $session = session();
        $model = new AssessmentSlipModel();
        $mop = '';
        $taxYear = '';
        $amountSave = '';
        $getInstallmentFromDetails = '';
        if($model->select('id')->where('assessment_slip_id',$id)->first()){
            $getInstallmentFromDetails = $model->where('assessment_slip_id',$id)->find();
            $getIstallmentsNotInAssessmentPayment = $model->getInstallmentsLeft($id);
        } else {
            $data = $model->getAssessmentMOP($id);
            foreach ($data as $r) {
              $mop = $r->mode_of_payment_id;
              $taxYear = $r->tax_year;
            }
            $getInstallmentFromDetails = $this->generateInstallmentTable($mop,$taxYear,$id);
            $getIstallmentsNotInAssessmentPayment = $this->getInstallmentSequence($mop);
            
        //    print_r($InstallmentTableData);
        //    die();
            
        }
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "installmentInfo" => $getInstallmentFromDetails,
            "installmentsSequence" => $getIstallmentsNotInAssessmentPayment,

        ];
        return $this->response->setJSON($result);





    }

    function generateInstallmentTable($mop,$taxYear,$id){
        $session = session();
        $model = new AssessmentSlipModel();
         if ($mop == '1') {
                $dataFees = $model->m_getFeesTotal($id,$taxYear);
                foreach ($dataFees as $r1) {
                    $amountSave = $r1->tamount;
                }
                $saveArray[] = array(
                    'assessment_slip_id'    =>          $id,
                    'year'                  =>          $taxYear,
                    'installment'           =>          '1',
                    'amount'                =>          $amountSave,
                    'created_at'            =>          date('Y-m-d H:i:s'),
                    'created_by'            =>          $session->get("id"),
                );

                return $saveArray;
            } elseif ($mop == '2') {
                $dataFees = $model->m_getFeesTotalQuarterly($id, $taxYear);
                $dataCount = 0;
                $TableData = [];

                foreach ($dataFees as $i => $feeObject) {
                    $amountSave2 = $feeObject->tamount;

                    $saveArray = [
                        'assessment_slip_id' => $id,
                        'year'               => $taxYear,
                        'installment'        => $i + 1,
                        'amount'             => $amountSave2,
                        'created_at'         => date('Y-m-d H:i:s'),
                        'created_by'         => $session->get("id"),
                    ];
                    $TableData[]= $saveArray;
                }

                return $TableData;
            } elseif ($mop == '3') {
                $dataFees = $model->m_getFeesTotalSemiAnnual($id, $taxYear);
                $dataCount = 0;
                $TableData = [];
                foreach ($dataFees as $i => $feeObject) {
                    $amountSave2 = $feeObject->tamount;

                    $saveArray = [
                        'assessment_slip_id' => $id,
                        'year'               => $taxYear,
                        'installment'        => $i + 1,
                        'amount'             => $amountSave2,
                        'created_at'         => date('Y-m-d H:i:s'),
                        'created_by'         => $session->get("id"),
                    ];

                    $TableData[]= $saveArray;
                }
                return $TableData;
            } 
    }

    function getInstallmentSequence($mop) {
        switch ($mop) {
            case 1:
                $numInstallments = 1;
                break;
            case 2:
                $numInstallments = 4;
                break;
            case 3:
                $numInstallments = 2;
                break;
            default:
                $numInstallments = 0;  // Default case if $mop is not recognized
        }
    
        $installmentSequence = [];
        for ($i = 1; $i <= $numInstallments; $i++) {
            $installmentSequence[] = ['installment' => $i];
        }
        return $installmentSequence;
    }

    public function SavePayment(){
        $session = session();
        $model = new AssessmentSlipModel();
        $assessmentSlipId = $this->request->getPost("assessment_slip_id");
        $mop = $this->request->getPost("mode_of_payment");
        $postedData = $this->request->getPost();
        $OrSeries = $model->mCheckReferenceSeries();
        // echo $OrSeries;
        // die();

        $SaveOrSeries = array(
                        'reference_no' => $OrSeries,
        );

        $model->UpdateApplication($SaveOrSeries,$postedData['application_id']);
        
       if($model->select('id')->where('assessment_slip_id',$assessmentSlipId)->first()){
        if($SavingResult = $this->SaveTransaction($postedData,$mop)){
            if ($SavingResult['status'] == '200') {
              $result = [
                      "status" => 200,
                      "message" => $SavingResult['message'],
                      "assessment_slip_id" => $assessmentSlipId,
                  ];
            } else{
               $result = [
                  "status" => 401,
                  "message" => $SavingResult['message'],
              ];
            }
          }
        
        } else {

            
            $asDetResult =  $this->SaveAssessmentSlipDetails($assessmentSlipId,$mop);  // Save to Assessment Slip Details  

           
            if($asDetResult == true){ // If Saving Assessment Slip Details Success
                 
               
                // Update MOP in Assessment Slip
               $dataAssessmentSlip = array(
                        'mode_of_payment_id'   =>  $mop
               );
              
               if ($model->UpdateAssessmentSlip($dataAssessmentSlip,$assessmentSlipId)) {



                    if($SavingResult = $this->SaveTransaction($postedData,$mop)){
                      if ($SavingResult['status'] == '200') {
                        $result = [
                                "status" => 200,
                                "message" => $SavingResult['message'],
                                "assessment_slip_id" => $assessmentSlipId,
                            ];
                      } else{
                         $result = [
                            "status" => 401,
                            "message" => $SavingResult['message'],
                        ];
                      }
                    }

                // $result = [
                //     "status" => 200,
                //     "message" => "Records Saving Success - Assessment_slip_details",
                // ];
               }


            } else {
                 // If Saving Assessment Slip Details Failed
                    $result = [
                        "status" => 401,
                        "message" => "Record Saving Failed",
                    
                    ];
           


        }
       }

      

        return $this->response->setJSON($result);


    }

    function SaveTransaction($postedData,$mop) {
        $AuditTrail = new AuditTrail();
        $session = session();
        $model = new PaymentModel();
        $Assessmentmodel = new AssessmentSlipModel();
        $AssessmentPayment = [
            "application_id" => $postedData["application_id"],
            "date_paid" => date("Y-m-d"),
            "assessment_slip_id" => $postedData["assessment_slip_id"],
            "installment" => $postedData["installment_input"],
            "under_protest" => $postedData["under_protest"],
            "or_series_id" => $postedData["ornumber_id"],
            "amount" => str_replace(",", "", $postedData["totalAmountDue"]),
            "change" => str_replace(",", "", $postedData["change"]),
            "time_in" => $this->convertTo24HourFormat($postedData["time_in"]),
            "time_out" => date("H:i:s"),
            "created_by" => $session->get("id"),
        ];
        
        if ($mop == '1') {
            $instLimit = '1';
            
            $AssessmentSlipData = [
                'isfullypaid' => 'F'
            ];
        
        } elseif ($mop == '2') {
            $instLimit = '4';
            if($postedData["installment_input"] != $instLimit){
                $AssessmentSlipData = [
                    'isfullypaid' => 'P'
                ];
            } else {
                $AssessmentSlipData = [
                    'isfullypaid' => 'F'
                ];
            }

        } elseif ($mop == '3') {
            $instLimit = '2';
            if($postedData["installment_input"] != $instLimit){
                $AssessmentSlipData = [
                    'isfullypaid' => 'P'
                ];
            } else {
                $AssessmentSlipData = [
                    'isfullypaid' => 'F'
                ];
            }

        }

        if ($Assessmentmodel->UpdateAssessmentSlip($AssessmentSlipData,$postedData["assessment_slip_id"])) {

// save KPMS
            if ($postedData["installment_input"] == '1') {
                $appId = $postedData['application_id'];
                $kmpDadta = array(
                    'date_in' => date('Y-m-d'),
                    'time_in' => date("H:i", strtotime($postedData['time_in'])),
                    'date_out' => date('Y-m-d'),
                    'time_out' => date('H:i:s'),
                );
    
                $model->SaveKPM($kmpDadta,$appId);
            }
           
// END KPMS saving
        // Check if either 'assessment_payment_id_cash' or 'assessment_payment_id_check' is set and not empty
        if (!empty($postedData["assessment_payment_id_cash"]) || !empty($postedData["assessment_payment_id_check"])) {
            if ($assessmentPaymentId = $model->saveAssessmentPayment($AssessmentPayment)) {
                $AuditTrail->save_logs(
                    "[transact] : Saved Assessment Payment [application_id] = " . $postedData["application_id"],
                    $session->get("id")
                );
    
                if (isset($postedData["assessment_payment_id_cash"])) {
                    $PaymentCashAmount = [
                        "assessment_payment_id" => $assessmentPaymentId,
                        "type_of_payment_id" => $postedData["assessment_payment_id_cash"],
                        "amount" => str_replace(",", "", $postedData["cashAmount"]),
                        "created_by" => $session->get("id"),
                    ];
                    $model->savePaymentDetails($PaymentCashAmount);
                }
    
                if (isset($postedData["assessment_payment_id_check"])) {
                    $PaymentCashAmount = [
                        "assessment_payment_id" => $assessmentPaymentId,
                        "type_of_payment_id" => $postedData["assessment_payment_id_check"],
                        "reference_number" => $postedData["checkNumber"],
                        "bank_id" => $postedData["bankName"],
                        "date" => $postedData["checkDate"],
                        "amount" => str_replace(",", "", $postedData["checkAmount"]),
                        "created_by" => $session->get("id"),
                    ];
                    $model->savePaymentDetails($PaymentCashAmount);
                }
                

              





                $result = [
                    "status" => 200,
                    "message" => "Payment Saved",
                    "assessment_slip_id" => $postedData["assessment_slip_id"],
                ];
                return $result;
            } else {
                $result = [
                    "status" => 404,
                    "message" => "Error Saving Assessment Payment",
                ];
                return $result;
            }
        } else {
            $result = [
                "status" => 402,
                "message" => "Please, Add type of Payment",
            ];
            return $result;
        }
    }
    }
    



    function SaveAssessmentSlipDetails($id,$mop){
        $session = session();
        $model = new AssessmentSlipModel();
        // $mop = '';
        $taxYear = '';
        $amountSave = '';
        $SavingAssDet = [];

        $data = $model->getAssessmentMOP($id);
            foreach ($data as $r) {
            //   $mop = $r->mode_of_payment_id;
              $taxYear = $r->tax_year;
            }
        

            if ($mop == '1') {
                $dataFees = $model->m_getFeesTotal($id,$taxYear);
                foreach ($dataFees as $r1) {
                    $amountSave = $r1->tamount;
                }
                $saveArray = array(
                    'assessment_slip_id'    =>          $id,
                    'year'                  =>          $taxYear,
                    'installment'           =>          '1',
                    'amount'                =>          $amountSave,
                    
                    'created_by'            =>          $session->get("id"),
                );


                if($model->insert($saveArray)){
                    $SavingAssDet['status'] = true;
                }



            } elseif ($mop == '2') {
                $dataFees = $model->m_getFeesTotalQuarterly($id, $taxYear);
                $dataCount = 0;

                foreach ($dataFees as $i => $feeObject) {
                    $amountSave2 = $feeObject->tamount;

                    $saveArray = [
                        'assessment_slip_id' => $id,
                        'year'               => $taxYear,
                        'installment'        => $i + 1,
                        'amount'             => $amountSave2,
                       
                        'created_by'         => $session->get("id"),
                    ];

                    if ($model->insert($saveArray)) {
                        $dataCount++;
                    }
                }

                if ($dataCount >= count($dataFees)) {
                    $SavingAssDet['status'] = true;
                }

               
               

            } elseif ($mop == '3') {
                $dataFees = $model->m_getFeesTotalSemiAnnual($id, $taxYear);
                $dataCount = 0;

                foreach ($dataFees as $i => $feeObject) {
                    $amountSave2 = $feeObject->tamount;

                    $saveArray = [
                        'assessment_slip_id' => $id,
                        'year'               => $taxYear,
                        'installment'        => $i + 1,
                        'amount'             => $amountSave2,
                      
                        'created_by'         => $session->get("id"),
                    ];

                    if ($model->insert($saveArray)) {
                        $dataCount++;
                    }
                }

                if ($dataCount >= count($dataFees)) {
                    $SavingAssDet['status'] = true;
                }
            }
        // $SavingAssDet['mop'] = $mop;
            return  $SavingAssDet;



    }

  

    function convertTo24HourFormat($time)
    {
        // Create a DateTime object from the input time
        // Use a backslash before DateTime to indicate the global namespace
        $date = \DateTime::createFromFormat("h:i A", $time);

        // Format the time in 24-hour format with seconds
        return $date->format("H:i:s");
    }


}