<?php


namespace App\Controllers;

use App\Models\AccountsModel;
use App\Models\UserManagementModel;

class Accounts extends BaseController
{



    
    public function index()
    {   
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Chart of Accounts Page ',$session->get('id'));
            return view('accounts/v_cAccounts');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    public function create()
    {   
       
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Accountable Form Page ',$session->get('id'));
            return view('accounts/v_accounts_create');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    // public function getPartTypes(){
    //     $model = new ParticularsModel();
    //     $data = $model->getParticularTypes();
    //     if($data = $model->getParticularTypes()){
    //         $result = array('status' => 200,'message'=> "Resources Loaded","selectOptions"=>$data);
    //     } else {
    //         $result = array('status' => 401,'message'=> "Resources loading failed");
    //     }
        
    //     return $this->response->setJSON($result);

    // }

    public function postAccounts(){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new AccountsModel();
        $postedData = $this->request->getPost();
        
        if ($postedData['accID'] == '') {
            
            $SaveArray = array(
                'effectivity_year'    => strtoupper($postedData['effectivity_year']),
                'code'                  => strtoupper($postedData['code']),
                'title'                  => strtoupper($postedData['title']),
                'acronym'                  => strtoupper($postedData['acronym']),
                'account_type'                  => strtoupper($postedData['account_type']),
                'account_nature'                  => strtoupper($postedData['account_nature']),                
                'remarks'               => strtoupper($postedData['remarks']),
                'created_by'               => $session->get('id'),
            );

            if ($model->saveAccount($SaveArray)) {
                $AuditTrail->save_logs('[save] : Add new account ',$session->get('id'));
                $result = array('status' => 200,'message'=> "Account Saved");    
            } else {
                $result = array('status' => 401,'message'=> "Account Saving Failed");    
            }

        } else {
            $UpdateArray = array(
                'effectivity_year'    => strtoupper($postedData['effectivity_year']),
                'code'                  => strtoupper($postedData['code']),
                'title'                  => strtoupper($postedData['title']),
                'acronym'                  => strtoupper($postedData['acronym']),
                'account_type'                  => strtoupper($postedData['account_type']),
                'account_nature'                  => strtoupper($postedData['account_nature']),                
                'remarks'               => strtoupper($postedData['remarks']),
                'updated_by'               => $session->get('id'),
            );

            if ($model->updateAccount($postedData['accID'],$UpdateArray)) {
                $AuditTrail->save_logs('[update] : Update Account info ',$session->get('id'));
                $result = array('status' => 200,'message'=> "Account Updated");    
            } else {
                $result = array('status' => 401,'message'=> "Account updating Failed");    
            }

        }
        return $this->response->setJSON($result);
    }

    public function ftrcAccounts(){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new AccountsModel();
        $ftr_selby = $this->request->getPost("ftr_selby");
        $ftr_val = $this->request->getPost("ftr_val");
        // $ftr_selVal = $this->request->getPost("ftr_type");

        if ($ftr_selby == "acTitle") {
            $ftrdata["ftr_by"] = "acc.title";
            $ftrdata["ftr_val"] = $ftr_val;
        } elseif ($ftr_selby == "acType") {
            $ftrdata["ftr_by"] = "acc.account_type";
            $ftrdata["ftr_val"] = $ftr_val;
        } else {
            $ftrdata["ftr_by"] = "ALL";
            $ftrdata["ftr_val"] = "";
        }

        $data = $model->filterChartofAccounts($ftrdata);
        $AuditTrail->save_logs('[filter] : Chart of Accounts ',$session->get('id'));
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "TableContent" => $data,
        ];
        return $this->response->setJSON($result);


    }

    public function viewAccounts($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : View Particular Details Page [id] = '.$id,$session->get('id'));
        return view('accounts/v_cAccounts_view',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function editAccounts($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : Edit Account Details Page [id] = '.$id,$session->get('id'));
        return view('accounts/v_cAccounts_edit',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }

    public function getAccountsInfo($id){
        $model = new AccountsModel();
        if($data = $model->mGetAccountDetails($id)){
            $result = [
                "status" => 200,
                "message" => "Particular Details Loaded",
                "AccountInfo" => $data,
            ];
        } else {
            $result = [
                "status" => 401,
                "message" => "Request Error",
                "AccountInfo" => '',
            ];
        }
        return $this->response->setJSON($result);
    }

    public function deleteAccount($id){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new AccountsModel();

        $UpdateArray = array(
            'deleted_by'    => $session->get('id'),
            'deleted_at'    => date('Y-m-d H:i:s'),
           
        );
        // print_r($UpdateArray);
        // die();
        if ($model->deleteAccount($id,$UpdateArray)) {
            $AuditTrail->save_logs('[delete] : Delete Account info ',$session->get('id'));
            $result = array('status' => 200,'message'=> "Account Deleted");    
        } else {
            $result = array('status' => 401,'message'=> "Account Deletion Failed");    
        }

        return $this->response->setJSON($result);
    }



}