<?php


namespace App\Controllers;

use App\Models\BanksModel;
use App\Models\UserManagementModel;

class Banks extends BaseController
{
    
    public function index(){   
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('Visit Bank Masterlist ',$session->get('id'));
            return view('banks/v_banks');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    public function ftrBanks(){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new BanksModel();
        $ftr_selby = $this->request->getPost("ftr_selby");
        $ftr_val = $this->request->getPost("ftr_val");
        // $ftr_selVal = $this->request->getPost("ftr_type");

        if ($ftr_selby == "bankName") {
            $ftrdata["ftr_by"] = "bks.`name`";
            $ftrdata["ftr_val"] = $ftr_val;
        } elseif ($ftr_selby == "branch") {
            $ftrdata["ftr_by"] = "bks.`branch`";
            $ftrdata["ftr_val"] = $ftr_val;
        } else {
            $ftrdata["ftr_by"] = "ALL";
            $ftrdata["ftr_val"] = "";
        }

        $data = $model->filterBankList($ftrdata);
        $AuditTrail->save_logs('Filter list of banks ',$session->get('id'));
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "TableContent" => $data,
        ];
        return $this->response->setJSON($result);
    }

    public function create(){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('Visit New Bank ',$session->get('id'));
            return view('banks/v_banks_create');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }
    public function edit($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('Visit Edit Bank ',$session->get('id'));
            $data['bankId'] = $id;
            return view('banks/v_banks_edit',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }
    public function view($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('Visit Bank Details ',$session->get('id'));
            $data['bankId'] = $id;
            return view('banks/v_banks_view',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    }

    public function SaveBank(){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new BanksModel();
        $postedData = $this->request->getPost();
        $bankId = $postedData['bankId'];

        if ($bankId == '') {
            $SaveBankArray = array(
                'name'              =>  strtoupper($postedData['name']),
                'shortname'         =>  strtoupper($postedData['shortname']),
                'branch'            =>  strtoupper($postedData['branch']),
                'contact_person'    =>  strtoupper($postedData['contact_person']),
                'contactno'         =>  $postedData['contactno'],
                'email'             =>  $postedData['email'],
                'remarks'           =>  strtoupper($postedData['remarks']),
                'created_by'           =>$session->get('id'),
            );

            if ($model->InsertBank($SaveBankArray)) {
                $AuditTrail->save_logs('Create New Bank Record',$session->get('id'));
                $result = array('status' => 200,'message'=> "Bank Saved");    
            } else {
                $result = array('status' => 401,'message'=> "Bank Saving Failed");    
            }
    

        } else {
            $UpdateBankArray = array(
                'name'              =>  strtoupper($postedData['name']),
                'shortname'         =>  strtoupper($postedData['shortname']),
                'branch'            =>  strtoupper($postedData['branch']),
                'contact_person'    =>  strtoupper($postedData['contact_person']),
                'contactno'         =>  $postedData['contactno'],
                'email'             =>  $postedData['email'],
                'remarks'           =>  strtoupper($postedData['remarks']),
                'updated_by'           =>$session->get('id'),
            );

            if ($model->m_UpdateBankRecord($UpdateBankArray,$bankId)) {
                $AuditTrail->save_logs('Update Bank Record',$session->get('id'));
                $result = array('status' => 200,'message'=> "Bank Details Updated");    
            } else {
                $result = array('status' => 401,'message'=> "Bank Updating Failed");    
            }
        }

        return $this->response->setJSON($result);





    }


    public function getBankDetails($id){
        $model = new BanksModel();
        if($data = $model->m_getBankDetails($id)){
            $result = [
                "status" => 200,
                "message" => "Bank Details Loaded",
                "BankInfo" => $data,
            ];
        } else {
            $result = [
                "status" => 401,
                "message" => "Request Error",
                "BankInfo" => '',
            ];
        }
        return $this->response->setJSON($result);
    }

    public function deleteBankDetails($id){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new BanksModel();
        $UpdateBankArray = array(
            'deleted_at'                =>  date('Y-m-d'),
            'deleted_by'                =>  $session->get('id'),
        );

        if ($model->m_UpdateBankRecord($UpdateBankArray,$id)) {
            $AuditTrail->save_logs('Delete Bank Record',$session->get('id'));
            $result = array('status' => 200,'message'=> "Bank Details Deleted");    
        } else {
            $result = array('status' => 401,'message'=> "Bank Deletion Failed");    
        }


        return $this->response->setJSON($result);
    }






}