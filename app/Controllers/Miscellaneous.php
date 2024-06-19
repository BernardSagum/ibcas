<?php


namespace App\Controllers;
use App\Controllers\AuditTrail;

// $otherController = new \App\Controllers\UserManagement();
// use App\Models\ClaimStubModel;
// use App\Models\FeesDefaultModel;
use App\Models\MiscellaneousModel;

class Miscellaneous extends BaseController
{
    public function index()
    {   
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Miscellaneous Page',$session->get('id'));
            return view('msc/msc_list');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    
    public function msc_filter(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $ftr_selby = $this->request->getPost('ftr_selby');
        $ftr_val = $this->request->getPost('ftr_val');
        // $ftr_sel = $this->request->getPost('ftr_sel');
        
        if ($ftr_selby == 'msc_name') {
            $ftrdata['ftr_by'] = 'name';
            $ftrdata['ftr_val'] = $ftr_val;
        } else {
            $ftrdata['ftr_by'] = '';
            $ftrdata['ftr_val'] = '';
        }

        $model = new MiscellaneousModel();
        
        $data = $model->filterData($ftrdata);
        $AuditTrail->save_logs('[filter] : Miscellaneous Table',$session->get('id'));
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();

    }
    public function edit($id){
        $session = session();
        $AuditTrail = new AuditTrail();

        if ($session->get('isLoggedIn')) {
        $data['msc_id'] = $id;
        $AuditTrail->save_logs('[visit] : Edit Miscellaneous Fee Info [id] = '.$id,$session->get('id'));

        return view('msc/msc_edit',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function viewing($id){
        $session = session();
        $AuditTrail = new AuditTrail();

        // $session = session();
        if ($session->get('isLoggedIn')) {
            $data['msc_id'] = $id;
        $AuditTrail->save_logs('[visit] : View Miscellaneous Fee Info [id] = '.$id,$session->get('id'));
            
        return view('msc/msc_view',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }

    public function create(){
        $session = session();
        $AuditTrail = new AuditTrail();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Create New Miscellaneous Fee Page',$session->get('id'));
        return view('msc/msc_create_new');
       
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }

    // public function getapptype(){
    //     $model = new ClaimStubModel();
    //     $data = $model->getapplicationtype();
    //     // dd($data);
    //     $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
    //     return $this->response->setJSON($result);
    // }

    public function save_fee(){
        $session = session();
        $AuditTrail = new AuditTrail();
        $msc_id = $this->request->getPost('msc_id');

        if ($msc_id != '') {
            $data_claimstub = [
                'name' => strtoupper($this->request->getPost('msc_name')),
                "amount" => str_replace(",","",$this->request->getPost('msc_amount')),
                'updated_by' => $session->get('id'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $model = new MiscellaneousModel();
            if($model->update_msc($data_claimstub,$msc_id)){
                $AuditTrail->save_logs('[saving] : Update Miscellaneous Fee',$session->get('id'));
                $result = array('status' => 'yes','message'=> "Record Updated");
                return $this->response->setJSON($result);
            } else {
                $result = array('status' => 'no','message'=> "Error Saving Record");
                return $this->response->setJSON($result);
            }



        } else {

            $data_claimstub = [
                'name' => strtoupper($this->request->getPost('msc_name')),
                "amount" => str_replace(",","",$this->request->getPost('msc_amount')),
                
                'created_by' => $session->get('id'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $model = new MiscellaneousModel();
            if($model->save_fee($data_claimstub)){
                $AuditTrail->save_logs('[saving] : Saving Claim Stub',$session->get('id'));
                $result = array('status' => 'yes','message'=> "Record Saved");
                return $this->response->setJSON($result);
            } else {
                $result = array('status' => 'no','message'=> "Error Saving Record");
                return $this->response->setJSON($result);
            }

           
        }


    }

    public function get_edit_info($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        $model = new MiscellaneousModel();
       if($dataclaimstub = $model->get_mscFee_info($id)){
                $AuditTrail->save_logs('[fetch] : Miscellaneous Fee Info [id] = '.$id,$session->get('id'));
                $result = array('status' => 'yes','message'=> "Miscellaneous Fee Loaded",'dtClaim' => $dataclaimstub);
            } else{
                $result = array('status' => 'no','message'=> "No data found");
            }
        return $this->response->setJSON($result);

        // return view('penalty/v_penalty_edit',$data);
    }

    public function delete_cs($id){
        // $data['p_id'] = $id;
        $AuditTrail = new AuditTrail();
        $session = session();
        $data_claimstub = [
            // 'id' => $p_id,

            'deleted_by' =>$session->get('id'),
            'deleted_at' => date('Y-m-d H:i:s'),
        ];
        $model = new MiscellaneousModel();
        if($model->update_msc($data_claimstub,$id)){
            $AuditTrail->save_logs('[delete] : Miscellaneous Fee Info [id] = '.$id,$session->get('id'));
            $result = array('status' => 200,'message'=> "Record Deleted");
            return $this->response->setJSON($result);
        } else {
            $result = array('status' => 404,'message'=> "Error deleting Record");
            return $this->response->setJSON($result);
        }
        // return $this->response->setJSON($result);
       
    }


}