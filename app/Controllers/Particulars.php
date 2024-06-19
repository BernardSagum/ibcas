<?php


namespace App\Controllers;

use App\Models\ParticularsModel;
use App\Models\UserManagementModel;

class Particulars extends BaseController
{



    
    public function index()
    {   
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Particulars Page ',$session->get('id'));
            return view('particulars/v_particulars');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    public function create()
    {   
       
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Create Particular Page ',$session->get('id'));
            return view('particulars/v_particular_create');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    public function getPartTypes(){
        $model = new ParticularsModel();
        $data = $model->getParticularTypes();
        if($data = $model->getParticularTypes()){
            $result = array('status' => 200,'message'=> "Resources Loaded","selectOptions"=>$data);
        } else {
            $result = array('status' => 401,'message'=> "Resources loading failed");
        }
        
        return $this->response->setJSON($result);

    }

    public function postParticular(){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new ParticularsModel();
        $postedData = $this->request->getPost();
        
        if ($postedData['PartId'] == '') {
            
            $SaveArray = array(
                'particular_type_id'    => $postedData['particular_type_id'],
                'code'                  => $postedData['code'],
                'name'                  => $postedData['name'],
                'print_order'           => $postedData['print_order'],
                'remarks'               => $postedData['remarks'],
                'created_by'               => $session->get('id'),
            );

            if ($model->saveParticular($SaveArray)) {
                $AuditTrail->save_logs('[save] : Add new particular ',$session->get('id'));
                $result = array('status' => 200,'message'=> "Particular Saved");    
            } else {
                $result = array('status' => 401,'message'=> "Particular Saving Failed");    
            }

        } else {
            $UpdateArray = array(
                'particular_type_id'    => $postedData['particular_type_id'],
                'code'                  => $postedData['code'],
                'name'                  => $postedData['name'],
                'print_order'           => $postedData['print_order'],
                'remarks'               => $postedData['remarks'],
                'updated_by'               => $session->get('id'),
            );

            if ($model->updateParticular($postedData['PartId'],$UpdateArray)) {
                $AuditTrail->save_logs('[update] : Update Particular info ',$session->get('id'));
                $result = array('status' => 200,'message'=> "Particular Updated");    
            } else {
                $result = array('status' => 401,'message'=> "Particular updating Failed");    
            }

        }
        return $this->response->setJSON($result);
    }

    public function ftrParticulars(){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new ParticularsModel();
        $ftr_selby = $this->request->getPost("ftr_selby");
        $ftr_val = $this->request->getPost("ftr_val");
        $ftr_selVal = $this->request->getPost("ftr_type");

        if ($ftr_selby == "parName") {
            $ftrdata["ftr_by"] = "name";
            $ftrdata["ftr_val"] = $ftr_val;
        } elseif ($ftr_selby == "parType") {
            $ftrdata["ftr_by"] = "particular_type_id";
            $ftrdata["ftr_val"] = $ftr_selVal;
        } else {
            $ftrdata["ftr_by"] = "ALL";
            $ftrdata["ftr_val"] = "";
        }

        $data = $model->filterParticulars($ftrdata);
        $AuditTrail->save_logs('[filter] : Particulars ',$session->get('id'));
        $result = [
            "status" => "yes",
            "message" => "Records Found",
            "TableContent" => $data,
        ];
        return $this->response->setJSON($result);


    }

    public function viewParticulars($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : View Particular Details Page [id] = '.$id,$session->get('id'));
        return view('particulars/v_particular_view',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function editParticulars($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : Edit Particular Details Page [id] = '.$id,$session->get('id'));
        return view('particulars/v_particular_edit',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }

    public function getParticularInfo($id){
        $model = new ParticularsModel();
        if($data = $model->mGetParticularInfo($id)){
            $result = [
                "status" => 200,
                "message" => "Particular Details Loaded",
                "ParticularInfo" => $data,
            ];
        } else {
            $result = [
                "status" => 401,
                "message" => "Request Error",
                "ParticularInfo" => '',
            ];
        }
        return $this->response->setJSON($result);
    }

    public function deleteParticular($id){
        $session = session();
        $AuditTrail = new AuditTrail();
        $model = new ParticularsModel();

        $UpdateArray = array(
            'deleted_by'    => $session->get('id'),
            'deleted_at'    => date('Y-m-d H:i:s'),
           
        );
        // print_r($UpdateArray);
        // die();
        if ($model->deleteParticular($id,$UpdateArray)) {
            $AuditTrail->save_logs('[delete] : Delete Particular info ',$session->get('id'));
            $result = array('status' => 200,'message'=> "Particular Deleted");    
        } else {
            $result = array('status' => 401,'message'=> "Particular Deletion Failed");    
        }

        return $this->response->setJSON($result);
    }



}