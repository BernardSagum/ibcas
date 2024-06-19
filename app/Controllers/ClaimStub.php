<?php


namespace App\Controllers;
use App\Controllers\AuditTrail;

// $otherController = new \App\Controllers\UserManagement();
use App\Models\ClaimStubModel;
use App\Models\FeesDefaultModel;
use App\Models\PenaltyRateListModel;

class ClaimStub extends BaseController
{
    public function index()
    {   
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Claim Stub Page',$session->get('id'));
            return view('claimstub/v_claimstub');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    
    public function claim_stub_filter(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $ftr_selby = $this->request->getPost('ftr_selby');
        $ftr_val = $this->request->getPost('ftr_val');
        $ftr_sel = $this->request->getPost('ftr_sel');
        
        if ($ftr_selby == 'taxyear') {
            $ftrdata['ftr_by'] = 'tax_effectivity_year';
            $ftrdata['ftr_val'] = $ftr_val;
        } else  if ($ftr_selby == 'apptype') {
            $ftrdata['ftr_by'] = 'application_type_id';
            $ftrdata['ftr_val'] = $ftr_sel;
        } else {
            $ftrdata['ftr_by'] = '';
            $ftrdata['ftr_val'] = '';
        }
       
        $model = new ClaimStubModel();
        $data = $model->filterData($ftrdata);
        $AuditTrail->save_logs('[filter] : Claim Stub Table',$session->get('id'));
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();

    }
    public function edit($id){
        $session = session();
        $AuditTrail = new AuditTrail();

        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : Edit Claim Stub Info [id] = '.$id,$session->get('id'));

        return view('claimstub/v_claimstub_edit',$data);
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
            $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : View Claim Stub Info [id] = '.$id,$session->get('id'));
            
        return view('claimstub/v_claimstub_viewing',$data);
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
            $AuditTrail->save_logs('[visit] : Create Claim Stub Page',$session->get('id'));
        return view('claimstub/v_claimstub_create');
       
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }

    public function getapptype(){
        $model = new ClaimStubModel();
        $data = $model->getapplicationtype();
        // dd($data);
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
    }

    public function save_claimstub(){
        $session = session();
        $AuditTrail = new AuditTrail();
        $c_id = $this->request->getPost('c_id');

        if ($c_id != '') {
            $data_claimstub = [
                'application_type_id' => $this->request->getPost('application_type_id'),
                'tax_effectivity_year' => $this->request->getPost('tax_effectivity_year'),
                'first_quarter_date' => $this->request->getPost('first_quarter_date'),
                'first_quarter_peak_days' => $this->request->getPost('first_quarter_peak_days'),
                'second_quarter_date' => $this->request->getPost('second_quarter_date'),
                'second_quarter_peak_days' => $this->request->getPost('second_quarter_peak_days'),
                'third_quarter_date' => $this->request->getPost('third_quarter_date'),
                'third_quarter_peak_days' => $this->request->getPost('third_quarter_peak_days'),
                'fourth_quarter_date' => $this->request->getPost('fourth_quarter_date'),
                'fourth_quarter_peak_days' => $this->request->getPost('fourth_quarter_peak_days'),
                'remarks' => $this->request->getPost('remarks'),
                'nonpeak_days' => $this->request->getPost('nonpeak_days'),
                'updated_by' => $session->get('id'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $model = new ClaimStubModel();
            if($model->update_claimstub($data_claimstub,$c_id)){
                $AuditTrail->save_logs('[saving] : Update Claim Stub',$session->get('id'));
                $result = array('status' => 'yes','message'=> "Record Updated");
                return $this->response->setJSON($result);
            } else {
                $result = array('status' => 'no','message'=> "Error Saving Record");
                return $this->response->setJSON($result);
            }



        } else {

            $data_claimstub = [
                'application_type_id' => $this->request->getPost('apptype'),
                'tax_effectivity_year' => $this->request->getPost('taxyreffect'),
                'first_quarter_date' => $this->request->getPost('first_quarter_date'),
                'first_quarter_peak_days' => $this->request->getPost('first_quarter_peak_days'),
                'second_quarter_date' => $this->request->getPost('second_quarter_date'),
                'second_quarter_peak_days' => $this->request->getPost('second_quarter_peak_days'),
                'third_quarter_date' => $this->request->getPost('third_quarter_date'),
                'third_quarter_peak_days' => $this->request->getPost('third_quarter_peak_days'),
                'fourth_quarter_date' => $this->request->getPost('fourth_quarter_date'),
                'fourth_quarter_peak_days' => $this->request->getPost('fourth_quarter_peak_days'),
                'remarks' => $this->request->getPost('remarks'),
                'nonpeak_days' => $this->request->getPost('nonpeak_days'),
                'created_by' => $session->get('id'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $model = new ClaimStubModel();
            if($model->save_claimstub($data_claimstub)){
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
        $model = new ClaimStubModel();
       if($dataclaimstub = $model->get_claimstub_info($id)){
                $AuditTrail->save_logs('[fetch] : Claim Stub Info [id] = '.$id,$session->get('id'));
                $result = array('status' => 'yes','message'=> "Claimstub schedule loaded",'dtClaim' => $dataclaimstub);
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
        $model = new ClaimStubModel();
        if($model->update_claimstub($data_claimstub,$id)){
            $AuditTrail->save_logs('[delete] : Claim Stub Info [id] = '.$id,$session->get('id'));
            $result = array('status' => 200,'message'=> "Record Deleted");
            return $this->response->setJSON($result);
        } else {
            $result = array('status' => 404,'message'=> "Error deleting Record");
            return $this->response->setJSON($result);
        }
        // return $this->response->setJSON($result);
       
    }


}