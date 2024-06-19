<?php


namespace App\Controllers;
use App\Controllers\AuditTrail;
use App\Models\OfficialReceiptModel;
// use App\Models\UserManagementModel;
use App\Models\PaymentModel;
use App\Models\PrintingModel;


class OrMasterlist extends BaseController
{



    
    public function index()
    {   
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : OR Masterlist ',$session->get('id'));
            return view('OfficialReceipt/OrMasterlist');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    public function SearchOrNumberDetails(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $PaymentModel = new PaymentModel();
        $printingModel =  new PrintingModel();
        $data['ftr_selby'] = $this->request->getPost('ftr_selby');
        $data['ftr_val'] = $this->request->getPost('ftr_val');
        $orSeriesID = '';
        $orSeriesStatus = '';
        $orSeriesVoid = '';
        $assessment_slip_id = '';
        $model = new OfficialReceiptModel();
        $orSeries = $model->mGetOrSeries($data);
        // $orDetails = $model->mGetOrDetails($data);

        foreach ($orSeries as $r) {
            # code...
            $orSeriesStatus = $r->status;
            $orSeriesID = $r->id;
            $orSeriesVoid = $r->void;
            $assessment_slip_id = $r->assessment_slip_id;

        }

        if ($orSeriesStatus != 'USED') {
            $result = [
                "status" => 401,
                "message" => "Searching for Unused OR Number",
            ];
        } else {

            if ($orSeriesVoid != 'Y') {



                $orDetails = $model->mGetOrDetails($orSeriesID);
                $busTax = $PaymentModel->m_getFeesTotalMOP_semi($assessment_slip_id);
                $MayorsFee = $printingModel->m_getFeesTotal_Mayors_fee($assessment_slip_id);
                $feesList =  $printingModel->m_getListOfFees($assessment_slip_id);



                $result = [
                    "status" => 200,
                    "message" => "OR Number Details Found",
                    "applicationDetails" => $orDetails,
                    "busTax" => $busTax,
                    "MayorsFee" => $MayorsFee,
                    "feesList" => $feesList,
                ];
            } else {
                $result = [
                    "status" => 402,
                    "message" => "Searching for a voided OR Number",
                ];
            }




           
        }

        return $this->response->setJSON($result);

        // print_r($orSeries);
        // die();



    }
    
}