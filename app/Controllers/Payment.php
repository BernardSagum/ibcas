<?php

namespace App\Controllers;

use App\Models\PaymentModel;
use App\Models\UserManagementModel;
use App\Models\AssessmentSlipModel;

date_default_timezone_set("Asia/Singapore");
class Payment extends BaseController
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

    public function checkOrAvail(){

        $session = session();
        $model = new PaymentModel();
        $newOrNumber = $this->request->getVar('ornum');



        if ($data = $model->m_checkOrAvail($newOrNumber,$session->get("id"))){
            $result = [
                "status" => 200,
                "message" => "O.R. Number Available",
                "content" => $data,
            ];
        } else {
            $result = [
                "status" => 401,
                "message" => "Invalid O.R. Number",
            ];
        }
        
        return $this->response->setJSON($result);
    }
    
    public function getNextOrNumber(){
        $session = session();
        $model = new PaymentModel();
        $dataLastOrNumber = $model->m_getLastOrNumber($session->get("id"));

        
        $result = [
            "status" => 200,
            "nextOrNumber" => $dataLastOrNumber,
        ];
        return $this->response->setJSON($result);
    }
    public function getMiscType(){
        $session = session();
        $model = new PaymentModel();
        $miscType = $model->m_getMiscType();

        
        $result = [
            "status" => 200,
            "miscType" => $miscType,
        ];
        return $this->response->setJSON($result);
    }
    public function getMiscTypeSpec($id){
        $session = session();
        $model = new PaymentModel();
        $miscType = $model->m_getMiscTypeSpec($id);

        
        $result = [
            "status" => 200,
            "miscType" => $miscType,
        ];
        return $this->response->setJSON($result);
    }


    public function installmentMop(){
        $request = \Config\Services::request();
        $AuditTrail = new AuditTrail();
        $session = session();
        $model = new PaymentModel();

        $ModeOfPayment = $request->getVar("value");
        $assessmentSlipId = $request->getVar("id");
        if ($ModeOfPayment == "1") {
            $data = $model->m_getPaymentInfo($assessmentSlipId);
            $datafees = $model->m_getFeesTotal($assessmentSlipId);
            $dataLastOrNumber = $model->m_getLastOrNumber($session->get("id"));
            // print_r($dataLastOrNumber);
            // die();
            $result = [
                "status" => 200,
                "message" => "Records Found",
                "paymentInfo" => $data,
                "installmentInfo" => $datafees,
                "nextOrNumber" => $dataLastOrNumber,
            ];
            return $this->response->setJSON($result);
        } elseif ($ModeOfPayment == "3") {
            $data = $model->m_getPaymentInfo($assessmentSlipId);

            $dataLastOrNumber = $model->m_getLastOrNumber($session->get("id"));
            $datafees = $model->m_getFeesTotalMOP_semi($assessmentSlipId);
            $datafees = array_merge(
                $datafees,
                $model->m_getFeesTotalMOP_others($assessmentSlipId)
            );

            $result = [
                "status" => 200,
                "message" => "Records Found",
                "paymentInfo" => $data,
                "installmentInfo" => $datafees,
                "nextOrNumber" => $dataLastOrNumber,
            ];
            return $this->response->setJSON($result);
        } else if ($ModeOfPayment == "2"){
            $data = $model->m_getPaymentInfo($assessmentSlipId);

            $dataLastOrNumber = $model->m_getLastOrNumber($session->get("id"));
            $datafees = $model->m_getFeesTotalMOP_semi($assessmentSlipId);
            $datafees = array_merge(
                $datafees,
                $model->m_getFeesTotalMOP_quarterly($assessmentSlipId)
            );

            $result = [
                "status" => 200,
                "message" => "Records Found",
                "paymentInfo" => $data,
                "installmentInfo" => $datafees,
                "nextOrNumber" => $dataLastOrNumber,
            ];
            return $this->response->setJSON($result);
        }
    }

    public function pAnnual($id){
        $AuditTrail = new AuditTrail();
        $model = new PaymentModel();
        $session = session();
        if ($session->get("isLoggedIn")) {
            if ($data = $model->checkIfPaid($id)) {
                print_r($data);
            } else {
                $data["p_id"] = $id;
                $AuditTrail->save_logs(
                    "[visit] : Payment Details [id] = " . $id,
                    $session->get("id")
                );
                return view("payment/v_payment_annual", $data);
            }
           
        } else {
            return redirect()->to(base_url("ibcas/login"));
        }
    }
    public function pBusiness($id)
{
    $session = session();
    if (!$session->get("isLoggedIn")) {
        return redirect()->to(base_url("ibcas/login"));
    }

    $model = new PaymentModel();
    $AuditTrail = new AuditTrail();
    $toPayStatus = '_topay'; // Default status
    $ModelResult = $model->checkIfPaid($id);
    
    // Early return if payment details are not found, assuming payment needed
    if (empty($ModelResult)) {
        return $this->redirectToPayment($id, $AuditTrail, $session);
    }

    // Check payment status
    foreach ($ModelResult as $r) {
        $toPayStatus = $r->PaymentStatus;
    }

    if ($toPayStatus != '_fullyPaid') {
        return $this->redirectToPayment($id, $AuditTrail, $session);
    }
    
    // Handle already paid case
    $data['message'] = "Business Application Already Paid";
    return view("errors/html/error_201", $data);
}

private function redirectToPayment($id, $AuditTrail, $session)
{
    $data["p_id"] = $id;
    $AuditTrail->save_logs(
        "[visit] : Payment Business [id] = " . $id,
        $session->get("id")
    );

    return view("payment/v_payment_annual", $data);
}

    public function pMiscellaneous(){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get("isLoggedIn")) {
            // $data['p_id'] = $id;
            $AuditTrail->save_logs(
                "[visit] : Payment Miscellaneous ",
                $session->get("id")
            );
            return view("payment/v_payment_miscellaneous");
        } else {
            return redirect()->to(base_url("ibcas/login"));
        }
    }
    public function pSemiAnnual(){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get("isLoggedIn")) {
            $AuditTrail->save_logs(
                "[visit] : Payment Semi Anual ",
                $session->get("id")
            );
            return view("payment/v_payment_semi_annual");
        } else {
            return redirect()->to(base_url("ibcas/login"));
        }
    }
    public function pQuarterly(){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get("isLoggedIn")) {
            $AuditTrail->save_logs(
                "[visit] : Payment Quarterly ",
                $session->get("id")
            );
            return view("payment/v_payment_quarterly");
        } else {
            return redirect()->to(base_url("ibcas/login"));
        }
    }

    public function FilterPayment(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $ftr_selby = $this->request->getPost("ftr_selby");
        $ftr_val = $this->request->getPost("ftr_val");
        $ftr_selVal = $this->request->getPost("ftr_selVal");

        if ($ftr_selby == "blpdno") {
            $ftrdata["ftr_by"] = "apps.blpdno";
            $ftrdata["ftr_val"] = $ftr_val;
        } elseif ($ftr_selby == "busname") {
            $ftrdata["ftr_by"] = "apps.business_name";
            $ftrdata["ftr_val"] = $ftr_val;
        } elseif ($ftr_selby == "taxpayer") {
            $ftrdata["ftr_by"] = "tax_payer_name";
            $ftrdata["ftr_val"] = $ftr_val;
        } elseif ($ftr_selby == "mop") {
            $ftrdata["ftr_by"] = "mop";
            $ftrdata["ftr_val"] = $ftr_selVal;
        } else {
            $ftrdata["ftr_by"] = "";
            $ftrdata["ftr_val"] = "";
        }

        $model = new PaymentModel();
        $data = $model->filterData($ftrdata);
        $AuditTrail->save_logs('[filter] : Accountable Form Table ',$session->get('id'));
        $result = [
            "status" => "yes",
            "message" => "Records Found",
            "TableContent" => $data,
        ];
        return $this->response->setJSON($result);
        // end();
    }

    public function filterBusiness(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $ftr_selby = $this->request->getPost("ftr_selby");
        $ftr_val = $this->request->getPost("ftr_val");

        if ($ftr_selby == "blpdno") {
            $ftrdata["ftr_by"] = "apps.blpdno";
            $ftrdata["ftr_val"] = $ftr_val;
        } elseif ($ftr_selby == "busname") {
            $ftrdata["ftr_by"] = "apps.business_name";
            $ftrdata["ftr_val"] = $ftr_val;
        } elseif ($ftr_selby == "taxpayer") {
            $ftrdata["ftr_by"] = "tax_payer_name";
            $ftrdata["ftr_val"] = $ftr_val;
        } else {
            $ftrdata["ftr_by"] = "";
            $ftrdata["ftr_val"] = "";
        }

        $model = new PaymentModel();
        $data = $model->filterDataBusiness($ftrdata);
        // $AuditTrail->save_logs('[filter] : Accountable Form Table ',$session->get('id'));
        $result = [
            "status" => "yes",
            "message" => "Records Found",
            "TableContent" => $data,
        ];
        return $this->response->setJSON($result);
        // end();
    }

    // public function SaveAssessmentSlipSetails(){
    //     $AuditTrail = new AuditTrail();
    //     $session = session();
    //     $model = new PaymentModel();
    //     $postedData = $this->request->getPost();
    //     if (isset($postedData)) {
    //         $installmentnum = $postedData['installm_count'];
    //         $countSaved = 0;
    //         for($i = 1; $i <= $installmentnum ; $i++){
    //             $assessmentSlipDetailsArrar = array(
    //                 'assessment_slip_id' => $postedData['assessment_slip_id_det'],
    //                 'year' => $postedData['taxyr_'.$i],
    //                 'installment' => $postedData['inst_'.$i],
    //                 'amount' => $postedData['amount_'.$i],
    //                 'created_at' => date('Y-m-d H:i:s'),
    //                 'created_by' =>  $session->get("id"),
    //             );
    //             $data = $model->saveAssessmentSlipDetails($assessmentSlipDetailsArrar);
    //             $countSaved = $countSaved + 1;

    //             if ($countSaved == $installmentnum) {
    //                 $result = [
    //                     "status" => 200,
    //                     "message" => "Records Saved",
    //                 ];
    //                 return $this->response->setJSON($result);
    //             }
    //         }

    //     }


    // }



    public function SavePayment(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $model = new PaymentModel();
        $postedData = $this->request->getPost();
        // print_r($postedData);
        // die();
        $AssessmentPayment = [
            "application_id" => $postedData["application_id"],
            "date_paid" => date("Y-m-d"),
            "assessment_slip_id" => $postedData["assessment_slip_id"],
            "installment" => $postedData["installment_input"],
            "under_protest" => $postedData["under_protest"],
            "or_series_id" => $postedData["ornumber_id"],
            "amount" => str_replace(
                ",",
                "",
                $postedData["totalAmountDue"]
            ),
            "change" => str_replace(
                ",",
                "",
                $postedData["change"]
            ),
            "time_in" => $this->convertTo24HourFormat($postedData["time_in"]),
            "time_out" => date("H:i:s"),
            "created_by" => $session->get("id"),
        ];

        // Check if either 'assessment_payment_id_cash' or 'assessment_payment_id_check' is set and not empty
        if (
            (isset($postedData["assessment_payment_id_cash"]) &&
                !empty($postedData["assessment_payment_id_cash"])) ||
            (isset($postedData["assessment_payment_id_check"]) &&
                !empty($postedData["assessment_payment_id_check"]))
        ) {
            if (
                $assessmentPaymentId = $model->saveAssessmentPayment(
                    $AssessmentPayment
                )
            ) {
                $AuditTrail->save_logs(
                    "[transact] : Saved Assessment Payment [application_id] = " .
                        $postedData["application_id"],
                    $session->get("id")
                );
                
                if (isset($postedData["assessment_payment_id_cash"])) {
                    $PaymentCashAmount = [
                        "assessment_payment_id" => $assessmentPaymentId,
                        "type_of_payment_id" =>
                            $postedData["assessment_payment_id_cash"],
                        "amount" => str_replace(
                            ",",
                            "",
                            $postedData["cashAmount"]
                        ),
                        "created_by" => $session->get("id"),
                    ];
                    $model->savePaymentDetails($PaymentCashAmount);
                }

                if (isset($postedData["assessment_payment_id_check"])) {
                    $PaymentCashAmount = [
                        "assessment_payment_id" => $assessmentPaymentId,
                        "type_of_payment_id" =>
                            $postedData["assessment_payment_id_check"],
                        "reference_number" => $postedData["checkNumber"],
                        "bank_id" => $postedData["bankName"],
                        "date" => $postedData["checkDate"],
                        "amount" => str_replace(
                            ",",
                            "",
                            $postedData["checkAmount"]
                        ),
                        "created_by" => $session->get("id"),
                    ];
                    $model->savePaymentDetails($PaymentCashAmount);
                }

                $result = [
                    "status" => 200,
                    "message" => "Assessment Payment Saved",
                    "assessment_slip_id" => $postedData["assessment_slip_id"],
                ];
                return $this->response->setJSON($result);
            } else {
                $result = [
                    "status" => 404,
                    "message" => "Error Saving Assessment Save",
                ];
                return $this->response->setJSON($result);
            }
        } else {
            $result = [
                "status" => 402,
                "message" => "Please, Add type of Payment",
            ];
            return $this->response->setJSON($result);
        }
    }
    public function SavePaymentMsc(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $model = new PaymentModel();
        $postedData = $this->request->getPost();
        $miscellaneous_paymentsArr = array(
            'form_id' => $this->request->getPost('form_id'),
            'orno_id' => $this->request->getPost('ornumber_id'),
            'date_paid' => date('Y-m-d'),
            'payors_name' => $this->request->getPost('payorName'),
            "amount" => str_replace(
                ",",
                "",
                $postedData["totalAmountDue"]
            ),
            'application_id'    => $this->request->getPost('app_id'),
            "change" => str_replace(
                ",",
                "",
                $postedData["change"]
            ),
            'time_in'    => $this->request->getPost('time_in'),
            'time_out'    => date('H:i'),
            'created_by' => $session->get('id'),
        );

       
        // print_r($miscellaneous_paymentsArr);
        // die();




       if (
            (isset($postedData["assessment_payment_id_cash"]) &&
                !empty($postedData["assessment_payment_id_cash"])) ||
            (isset($postedData["assessment_payment_id_check"]) &&
                !empty($postedData["assessment_payment_id_check"]))
        ) {
        if ($insertId = $model->m_save_msc_payments($miscellaneous_paymentsArr)) {

            $AuditTrail->save_logs(
                "[transact] : Saved Miscellaneous Payment [payment id] = " .
                    $insertId,
                $session->get("id")
            );

            if (isset($postedData["assessment_payment_id_cash"])) {
                $PaymentCashAmount = [
                    "miscellaneous_payment_id" => $insertId,
                    "type_of_payment_id" =>
                        $postedData["assessment_payment_id_cash"],
                    "amount" => str_replace(
                        ",",
                        "",
                        $postedData["cashAmount"]
                    ),
                    "created_by" => $session->get("id"),
                ];
                $model->savePaymentDetailsmsc($PaymentCashAmount);
            }

            if (isset($postedData["assessment_payment_id_check"])) {
                $PaymentCashAmount = [
                    "miscellaneous_payment_id" => $insertId,
                    "type_of_payment_id" =>
                        $postedData["assessment_payment_id_check"],
                    "reference_number" => $postedData["checkNumber"],
                    "bank_id" => $postedData["bankName"],
                    "date" => $postedData["checkDate"],
                    "amount" => str_replace(
                        ",",
                        "",
                        $postedData["checkAmount"]
                    ),
                    "created_by" => $session->get("id"),
                ];
                $model->savePaymentDetailsmsc($PaymentCashAmount);
            }

            for ($i=0; $i < count($postedData['mscFeeid']); $i++) { 
                $PaymentListMsc = [
                    // "miscellaneous_payment_id" => '1',
                    "miscellaneous_payment_id" => $insertId,
                    "miscellaneous_id" => $postedData["mscFeeid"][$i],
                    "amount" => str_replace(
                        ",",
                        "",
                        $postedData["mscFeeAmount"][$i]
                    ),
                    "created_by" => $session->get("id"),
                ];
                // print_r($PaymentListMsc);
                $model->savePaymentListsmsc($PaymentListMsc);
            }


            $result = [
                "status" => 200,
                "message" => "Msc Payments Saved",
                "miscellaneous_paymentsId" => $insertId,
            ];
            return $this->response->setJSON($result);
        } else {
            $result = [
                "status" => 401,
                "message" => "Error Saving Assessment Save",
            ];
            return $this->response->setJSON($result);
        }

    } else {
        $result = [
            "status" => 402,
            "message" => "Please, Add type of Payment",
        ];
        return $this->response->setJSON($result);
    }


    }


    public function getBusinessDetails($id){
        $model = new PaymentModel();
        $dataBusiness = $model->getBusinessDetails($id);
        $result = [
            "status" => 200,
            "BusinessData" => $dataBusiness,
        ];
        return $this->response->setJSON($result);
    }






    public function getBankNames(){
        $model = new PaymentModel();
        $data = $model->m_getBankNames();
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "BankNames" => $data,
        ];
        return $this->response->setJSON($result);
    }

    public function getPaymentTypes(){
        $model = new PaymentModel();
        $data = $model->m_getPaymentType();
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "PaymentTypes" => $data,
        ];
        return $this->response->setJSON($result);
    }

    public function getFeesInfo($id)
    {
        $model = new PaymentModel();
        $data = $model->m_getListOfFees($id);
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "TableContent" => $data,
        ];
        return $this->response->setJSON($result);
    }

    public function getModeOfPayment()
    {
        $model = new PaymentModel();
        $data = $model->m_getModeOfPayment();
        // dd($data);
        $result = [
            "status" => "yes",
            "message" => "Records Found",
            "TableContent" => $data,
        ];
        return $this->response->setJSON($result);
    }

    public function getAssessmentInstallment($id)
    {

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
                    'created_at'            =>          date('Y-m-d H:i:s'),
                    'created_by'            =>          $session->get("id"),
                );


                if($model->insert($saveArray)){
                    $getInstallmentFromDetails = $model->where('assessment_slip_id',$id)->find();
                    $getIstallmentsNotInAssessmentPayment = $model->getInstallmentsLeft($id);
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
                        'created_at'         => date('Y-m-d H:i:s'),
                        'created_by'         => $session->get("id"),
                    ];

                    if ($model->insert($saveArray)) {
                        $dataCount++;
                    }
                }

                if ($dataCount >= count($dataFees)) {
                    $getInstallmentFromDetails = $model->where('assessment_slip_id', $id)->find();
                    $getIstallmentsNotInAssessmentPayment = $model->getInstallmentsLeft($id);
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
                        'created_at'         => date('Y-m-d H:i:s'),
                        'created_by'         => $session->get("id"),
                    ];

                    if ($model->insert($saveArray)) {
                        $dataCount++;
                    }
                }

                if ($dataCount >= count($dataFees)) {
                    $getInstallmentFromDetails = $model->where('assessment_slip_id', $id)->find();
                    $getIstallmentsNotInAssessmentPayment = $model->getInstallmentsLeft($id);
                }
            }




        }
       
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "installmentInfo" => $getInstallmentFromDetails,
            "installmentsSequence" => $getIstallmentsNotInAssessmentPayment,

        ];
        return $this->response->setJSON($result);
    }








    public function getPaymentInfo($id)
    {
        $session = session();

        $model = new PaymentModel();
        $data = $model->m_getPaymentInfo($id);
        $dataLastOrNumber = $model->m_getLastOrNumber($session->get("id"));
       
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "paymentInfo" => $data,
            "nextOrNumber" => $dataLastOrNumber,
        ];
        return $this->response->setJSON($result);
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