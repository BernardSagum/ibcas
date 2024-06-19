<?php


namespace App\Controllers;

use App\Models\CollectorsModel;
use App\Models\UserManagementModel;

class Collectors extends BaseController
{
    public function index()
    {    $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Collector Page',$session->get('id'));
            return view('collectors/v_col_list');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }


    public function getCollectorFunds(){
        $session = session();
        $Model = new CollectorsModel();
        $dataLastOrNumber = $Model->m_getCollectorFunds($session->get('id'));
        $result = [
            "status" => 200,
            "FundList" => $dataLastOrNumber,
        ];
        return $this->response->setJSON($result);
    }

    public function getNextOrNumberFundId($fund_id){
        $session = session();
        $model = new CollectorsModel();
        $dataLastOrNumber = $model->mGetLastORBasedonFunds($session->get("id"),$fund_id);

        
        $result = [
            "status" => 200,
            "nextOrNumber" => $dataLastOrNumber,
        ];
        return $this->response->setJSON($result);
    }









    public function create()
    {   
        $session = session();
        if ($session->get('isLoggedIn')) {
            return view('collectors/v_create_new');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
        
        
    }
    
    public function ViewPage($id)
    {
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : View Collector Details Page [id] = '.$id,$session->get('id'));
        return view('collectors/v_view_details',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    
    public function EditPage($id)
    {
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : Edit Collector Page [id] = '.$id,$session->get('id'));
        return view('collectors/v_edit_collector',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function seedecrypt($encryptedData){
        $Model = new CollectorsModel();
        $data = $Model->decrypt_tin_no($encryptedData);
        dd($data);
    }
    public function save_collector(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $Model = new CollectorsModel();
        $coll_id = $this->request->getPost('col_id');
        $deputized_collector = $this->request->getPost('deputized_collector');
        $field_collector = $this->request->getPost('field_collector');
       
        if ($coll_id == '') {
            $collsaveArr = array(
                'user_id'           => $this->request->getPost('user_id'),
                'barangay_id'       => $this->request->getPost('barangay_id'),
                'accountable_officer_id'       => $this->request->getPost('accountable_officer_id'),
                'address'           => $this->request->getPost('address'),
                'tin_no'            => $this->request->getPost('tin_no'),
                'contact_number'    => $this->request->getPost('contact_number'),
                'email'             => $this->request->getPost('email'),
                'position_id'       => $this->request->getPost('position_id'),
                'field_collector'       => ($field_collector != '') ? $field_collector : 'N',
                'deputized_collector'       => ($deputized_collector != '') ? $deputized_collector : 'N',
                'created_by'                => $session->get('id'),
            );

                if ($Model->saveCollector($collsaveArr)) {
                    $AuditTrail->save_logs('[saving] : New collector saving ',$session->get('id'));
                    $result = array('status' => 200,'message'=> "Record saved");
                    return $this->response->setJSON($result);
                } else {
                    $result = array('status' => 404,'message'=> "Error saving");
                    return $this->response->setJSON($result);
                }

        } else {

            $collsaveArr = array(
                'user_id'                   => $this->request->getPost('user_id'),
                'barangay_id'               => $this->request->getPost('barangay_id'),
                'accountable_officer_id'    => $this->request->getPost('accountable_officer_id'),
                'address'                   => $this->request->getPost('address'),
                'tin_no'                    => $this->request->getPost('tin_no'),
                'contact_number'            => $this->request->getPost('contact_number'),
                'email'                     => $this->request->getPost('email'),
                'position_id'               => $this->request->getPost('position_id'),
                'field_collector'           => ($field_collector != '') ? $field_collector : 'N',
                'deputized_collector'       => ($deputized_collector != '') ? $deputized_collector : 'N',
                'updated_by'                => $session->get('id'),
            );

            if ($Model->updateCollector($collsaveArr,$coll_id)) {
                $AuditTrail->save_logs('[saving] : Collector update saving ',$session->get('id'));
                $result = array('status' => 200,'message'=> "Record Updated");
                return $this->response->setJSON($result);
            } else {
                $result = array('status' => 404,'message'=> "Error updating");
                return $this->response->setJSON($result);
            }

        }




       

    }

    public function delete_col($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        $Model = new CollectorsModel();
        $colldeleteArr = array(
          
            'deleted_by'                => $session->get('id'),
            'deleted_at'                => date('Y-m-d H:i:s'),
        );

        if ($Model->deleteColl($colldeleteArr,$id)) {
            $AuditTrail->save_logs('[Delete] : Delete Collector ',$session->get('id'));
            $result = array('status' => 200,'message'=> "Record deleted");
            return $this->response->setJSON($result);
        } else {
            $result = array('status' => 404,'message'=> "Error deleting");
            return $this->response->setJSON($result);
        }
    }

    
    public function viewDetails($id){

        $session = session();
        if ($session->get('isLoggedIn')) {
            $Model = new CollectorsModel();
           if ($data = $Model->m_get_collectorInfo($id)) {
            $result = array('status' => 200,'message'=> "Collector found",'TableContent' => $data);
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
    public function getAllPositions(){

        $session = session();
        if ($session->get('isLoggedIn')) {
            $Model = new CollectorsModel();
           if ($data = $Model->m_getAllpositions()) {
            $result = array('status' => 200,'message'=> "Positions found",'TableContent' => $data);
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
    
    public function getAllBarangay(){

        $session = session();
        if ($session->get('isLoggedIn')) {
            $Model = new CollectorsModel();
           if ($data = $Model->m_getAllbarangay()) {
            $result = array('status' => 200,'message'=> "Positions found",'TableContent' => $data);
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
    public function getOfficers(){

        $session = session();
        if ($session->get('isLoggedIn')) {
            $Model = new CollectorsModel();
           if ($data = $Model->getAllOfficers()) {
            $result = array('status' => 200,'message'=> "Officers found",'TableContent' => $data);
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
    public function collectors_filter(){
        $AuditTrail = new AuditTrail();
        $session = session();

        $ftr_selby = $this->request->getPost('ftr_selby');
        $ftr_val = $this->request->getPost('ftr_val');
        
        if ($ftr_selby == 'colname') {
            $ftrdata['ftr_by'] = 'collector_name';
            $ftrdata['ftr_val'] = $ftr_val;
        } else if ($ftr_selby == 'officer') {
            $ftrdata['ftr_by'] = 'officer_name';
            $ftrdata['ftr_val'] = $ftr_val;
        } else {
            $ftrdata['ftr_by'] = '';
            $ftrdata['ftr_val'] = '';
        }
       
        $model = new CollectorsModel();
        $data = $model->filterData($ftrdata);
        $AuditTrail->save_logs('[Filter] : Collector Table',$session->get('id'));
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();
    }


}