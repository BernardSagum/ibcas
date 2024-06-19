<?php


namespace App\Controllers;

use App\Models\AccOfficersModel;
use App\Models\UserManagementModel;

class AccountableOfficers extends BaseController
{
    public function index()
    {   
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Accountable Officer Page ',$session->get('id'));
            return view('accofficer/v_acc_officer');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    public function getUsers(){

        $session = session();
        if ($session->get('isLoggedIn')) {
            $Model = new UserManagementModel();
           if ($data = $Model->getAllUser()) {
            $result = array('status' => 200,'message'=> "Users found",'TableContent' => $data);
           } else {
            $result = array('status' => 404,'message'=> "No data found");
           }
           return $this->response->setJSON($result);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }

    
    public function acc_officer_filter(){

        $AuditTrail = new AuditTrail();
        $session = session();
        $ftr_selby = $this->request->getPost('ftr_selby');
        $ftr_val = $this->request->getPost('ftr_val');
        
        if ($ftr_selby == 'offname') {
            $ftrdata['ftr_by'] = 'offname';
            $ftrdata['ftr_val'] = $ftr_val;
        } else {
            $ftrdata['ftr_by'] = '';
            $ftrdata['ftr_val'] = '';
        }
       
        $model = new AccOfficersModel();
        $data = $model->filterData($ftrdata);
        $AuditTrail->save_logs('[filter] : Accountable Officers Table ',$session->get('id'));
        
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();

    }



    public function createPage(){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Create Accountable Officer Page ',$session->get('id'));
        return view('accofficer/v_aofficers_create');
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
   

    public function void($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $data['p_id'] = $id;
            $AuditTrail->save_logs('[visit] : Void Accountable Form Page [id] = '.$id,$session->get('id'));
        return view('accforms/v_accforms_void',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
   
    public function deleteOfficer($id){
        $AuditTrail = new AuditTrail();
        $Model = new AccOfficersModel();
        $session = session();
        $data_acc_form =array(
            'deleted_by' =>$session->get('id'),
            'deleted_at' => date('Y-m-d H:i:s'),
        );
        
        
        if($Model->updateAccOfficer($data_acc_form,$id)){
            $AuditTrail->save_logs('[delete] : Delete Accountable Officer Page [id] = '.$id,$session->get('id'));
            $result = array('status' => 200,'message'=> "Record Deleted");
            return $this->response->setJSON($result);
        } else {
            $result = array('status' => 404,'message'=> "Records deletion error");
            return $this->response->setJSON($result);
        }
    }
    public function editPage($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : Edit Accountable Officer Page [id] = '.$id,$session->get('id'));
        return view('accofficer/v_aofficers_edit',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
 
    public function ViewPage($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : View Accountable Officer Page [id] = '.$id,$session->get('id'));
        return view('accofficer/v_accofficer_view',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function GetOfficerInfo($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        $model = new AccOfficersModel();
        if($datayr = $model->m_get_accofficer_info($id)) {
            $AuditTrail->save_logs('[fetch] : Accountable Officer info [id] = '.$id,$session->get('id'));
            $result = array('status' => 200,'message'=> "Record Found",'recdata' => $datayr);
        } else{
            $result = array('status' => 404,'message'=> "No data found");
        }
        

        

        
        return $this->response->setJSON($result);

        // return view('penalty/v_penalty_edit',$data);
    }


    public function saveaAccountableOfficer(){
        $AuditTrail = new AuditTrail();
        $Model = new AccOfficersModel();
        $session = session();
        $off_id = $this->request->getPost('off_id');
        


        if ($off_id == '') {

                   
                $saveArr = array(
                    'user_id' => $this->request->getPost('user_id'),
                    'type' => $this->request->getPost('type'),
                    // 'stub_no' => $this->request->getPost('stub_no'),
                    'created_by' => $session->get('id'),
                );
              
                    if($Model->saveAccOfficer($saveArr)){
                        $result = array('status' => 200,'message'=> "Record saved");
                        $AuditTrail->save_logs('[saving] : Accountable Officer Saved ',$session->get('id'));
                                return $this->response->setJSON($result);
                    } else {
                            $result = array('status' => 404,'message'=> "Records saving error");
                            return $this->response->setJSON($result);
                    }
                    
              
          
            
           
        } else {

            $updateArr = array(
                'user_id' => $this->request->getPost('user_id'),
                'type' => $this->request->getPost('type'),
                'updated_by' => $session->get('id'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if($Model->updateAccOfficer($updateArr,$off_id)){
                $AuditTrail->save_logs('[saving] : Accountable Officer Updated ',$session->get('id'));
                $result = array('status' => 200,'message'=> "Record updated");
                return $this->response->setJSON($result);
            } else {
                $result = array('status' => 404,'message'=> "Records saving error");
                return $this->response->setJSON($result);
            }
            // $result = array('status' => 200,'message'=> "Record updated");
            // return $this->response->setJSON($result);
      
            
        }

    }
   
}