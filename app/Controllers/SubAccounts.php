<?php


namespace App\Controllers;

use App\Models\SubAccountsModel;
use App\Models\ParticularsModel;
use App\Models\UserManagementModel;

class SubAccounts extends BaseController
{



    
    public function index()
    {   
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Chart of Accounts Page ',$session->get('id'));
            return view('sub-accounts/v_sAccounts');
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
            return view('sub-accounts/v_saccounts_create');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    public function getParticularsList(){
        $model = new ParticularsModel();
        // $data = $model->getParticularsList();
        if($data = $model->mGetParticularsList()){
            $result = array('status' => 200,'message'=> "Records Found","particularsList"=>$data);
        } else {
            $result = array('status' => 401,'message'=> "Records not found");
        }
        
        return $this->response->setJSON($result);

    }

    public function postSubAccounts(){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new SubAccountsModel();
        $postedData = $this->request->getPost();
        $subId = $postedData['sub_ID'];
        $particularClassificationArray = json_decode($postedData['particularClassification'], true);

        if ( $subId == '') {
            
            $SaveArray = array(
                'effectivity_year'    => strtoupper($postedData['effectivity_year']),
                'account_id'                  => strtoupper($postedData['account_id']),
                'code'                  => strtoupper($postedData['subcode']),
                'description'                  => strtoupper($postedData['subcodedesc']),
                'type'                  => $postedData['type'],
                // 'account_nature'                  => strtoupper($postedData['account_nature']),                
                'remarks'               => strtoupper($postedData['remarks']),
                'created_by'               => $session->get('id'),
            );

            if ($id = $model->saveSubAccount($SaveArray)) {
                $allSaved = true; // Flag to check if all save operations are successful
            
                for ($i = 0; $i <= count($particularClassificationArray) - 1; $i++) {
                    $partClassArray = array(
                        'account_subcode_id' => $id,
                        'particular_id' => $particularClassificationArray[$i]['part'],
                        'classifications' => $particularClassificationArray[$i]['class'],
                        'new' => $particularClassificationArray[$i]['N'] == 'checked' ? 'Y' : 'N',
                        'renewal' => $particularClassificationArray[$i]['R'] == 'checked' ? 'Y' : 'N',
                        'closure' => $particularClassificationArray[$i]['C'] == 'checked' ? 'Y' : 'N',
                        'created_by' => $session->get('id'),
                    );
            
                    if (!$model->savePartClass($partClassArray)) {
                        $allSaved = false; // If any save operation fails, set the flag to false
                        break; // Optional: Exit the loop if save operation fails
                    }
                }
            
                if ($allSaved) {
                    $AuditTrail->save_logs('[save] : Add new sub account ', $session->get('id'));
                    $result = array('status' => 200, 'message' => "Sub Account Saved");
                } else {
                    $result = array('status' => 500, 'message' => "Failed to save some classifications");
                }
            } else {
                $result = array('status' => 401,'message'=> "Sub Account Saving Failed");    
            }

        } else {
            $UpdateArray = array(
                'effectivity_year'    => strtoupper($postedData['effectivity_year']),
                'account_id'                  => strtoupper($postedData['account_id']),
                'code'                  => strtoupper($postedData['subcode']),
                'description'                  => strtoupper($postedData['subcodedesc']),
                'type'                  => $postedData['type'],
                // 'account_nature'                  => strtoupper($postedData['account_nature']),                
                'remarks'               => strtoupper($postedData['remarks']),
                'updated_by'               => $session->get('id'),
            );

            if ($model->updateSubAccount($subId,$UpdateArray)) {
                $allSaved = true; // Flag to check if all save operations are successful
            
                for ($i = 0; $i <= count($particularClassificationArray) - 1; $i++) {
                    $partClassArray = array(
                        'account_subcode_id' => $subId,
                        'particular_id' => $particularClassificationArray[$i]['part'],
                        'classifications' => $particularClassificationArray[$i]['class'],
                        'new' => $particularClassificationArray[$i]['N'] == 'checked' ? 'Y' : 'N',
                        'renewal' => $particularClassificationArray[$i]['R'] == 'checked' ? 'Y' : 'N',
                        'closure' => $particularClassificationArray[$i]['C'] == 'checked' ? 'Y' : 'N',
                        'created_by' => $session->get('id'),
                    );
                    // print_r($partClassArray);
                    // die();
                    if (!$model->savePartClass($partClassArray)) {
                        $allSaved = false; // If any save operation fails, set the flag to false
                        break; // Optional: Exit the loop if save operation fails
                    }
                }
            
                if ($allSaved) {
                    $AuditTrail->save_logs('[save] : Update sub account ', $session->get('id'));
                    $result = array('status' => 200, 'message' => "Sub Account Updated");
                } else {
                    $result = array('status' => 500, 'message' => "Failed to save some classifications");
                }
               
            } else {
                $result = array('status' => 401,'message'=> "Sub Account Updating Failed");    
            }
         
        }
        return $this->response->setJSON($result);
    }

    public function ftrsAccounts(){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new SubAccountsModel();
        $ftr_selby = $this->request->getPost("ftr_selby");
        $ftr_val = $this->request->getPost("ftr_val");
        // $ftr_selVal = $this->request->getPost("ftr_type");

        if ($ftr_selby == "effYear") {
            $ftrdata["ftr_by"] = "effYear";
            $ftrdata["ftr_val"] = $ftr_val;
        } elseif ($ftr_selby == "accCodeDesc") {
            $ftrdata["ftr_by"] = "ac.`title`";
            $ftrdata["ftr_val"] = $ftr_val;
        } else {
            $ftrdata["ftr_by"] = "ALL";
            $ftrdata["ftr_val"] = "";
        }

        $data = $model->filterSubAccounts($ftrdata);
        $AuditTrail->save_logs('[filter] : Sub Accounts ',$session->get('id'));
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "TableContent" => $data,
        ];
        return $this->response->setJSON($result);


    }
    public function ftrClassifications(){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new SubAccountsModel();
        $ftr_selby = $this->request->getPost("ftr_selbyC");
        $ftr_val = $this->request->getPost("ftr_valC");
        // $ftr_selVal = $this->request->getPost("ftr_type");

        if ($ftr_selby == "ALL") {
            $ftrdata["ftr_by"] = "ALL`";
            $ftrdata["ftr_val"] = '';
        } 
        // elseif ($ftr_selby == "accCodeDesc") {
        //     $ftrdata["ftr_by"] = "ac.`title`";
        //     $ftrdata["ftr_val"] = $ftr_val;
        // } else {
        //     $ftrdata["ftr_by"] = "ALL";
        //     $ftrdata["ftr_val"] = "";
        // }

        $data = $model->filterClassifications($ftrdata);
        $AuditTrail->save_logs('[filter] : Classifications ',$session->get('id'));
        $result = [
            "status" => 200,
            "message" => "Records Found",
            "TableContent" => $data,
        ];
        return $this->response->setJSON($result);


    }

    public function viewSubAccount($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : View Particular Details Page [id] = '.$id,$session->get('id'));
        return view('sub-accounts/v_saccounts_view',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function editSubAccount($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : Edit Sub-Account Details Page [id] = '.$id,$session->get('id'));
        return view('sub-accounts/v_saccounts_update',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }

    public function getSubAccountInfo($id){
        $model = new SubAccountsModel();
        if($data = $model->mGetSubAccountDetails($id)){
            $result = [
                "status" => 200,
                "message" => "Sub-Account Details Loaded",
                "SubAccountInfo" => $data,
            ];
        } else {
            $result = [
                "status" => 401,
                "message" => "Request Error",
                "SubAccountInfo" => '',
            ];
        }
        return $this->response->setJSON($result);
    }
    public function getSubAccountPartClass($id){
        
        $model = new SubAccountsModel();
        if($data = $model->mGetSubAccountParticulars($id)){
            $result = [
                "status" => 200,
                "message" => "Sub-Account Details Loaded",
                "SubAccountPartClass" => $data,
            ];
        } else {
            $result = [
                "status" => 401,
                "message" => "Request Error",
                "SubAccountPartClass" => '',
            ];
        }
        return $this->response->setJSON($result);
    }

    public function deleteSubAccount($id){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new SubAccountsModel();

        $UpdateArray = array(
            'deleted_by'    => $session->get('id'),
            'deleted_at'    => date('Y-m-d H:i:s'),
           
        );
        // print_r($UpdateArray);
        // die();
        if ($model->deleteAccount($id,$UpdateArray)) {
            $AuditTrail->save_logs('[delete] : Delete Sub Account info ',$session->get('id'));
            $result = array('status' => 200,'message'=> "Account Deleted");    
        } else {
            $result = array('status' => 401,'message'=> "Account Deletion Failed");    
        }

        return $this->response->setJSON($result);
    }
    public function deletePartClass($id){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new SubAccountsModel();

        $UpdateArray = array(
            'deleted_by'    => $session->get('id'),
            'deleted_at'    => date('Y-m-d H:i:s'),
           
        );
        // print_r($UpdateArray);
        // die();
        if ($model->mdeletePartClass($id,$UpdateArray)) {
            $AuditTrail->save_logs('[delete] : Delete Particular and Classification ',$session->get('id'));
            $result = array('status' => 200,'message'=> "Particular and Classification Deleted");    
        } else {
            $result = array('status' => 401,'message'=> "Particular and Classification Deletion Failed");    
        }

        return $this->response->setJSON($result);
    }



}