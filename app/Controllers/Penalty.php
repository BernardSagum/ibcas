<?php


namespace App\Controllers;
use App\Controllers\AuditTrail;

// $otherController = new \App\Controllers\UserManagement();
use App\Models\PenaltyRatesModel;
use App\Models\FeesDefaultModel;
use App\Models\PenaltyRateListModel;
use App\Models\AuditTrailModel;

class Penalty extends BaseController
{
    public function index()
    {   
        $session = session();
        if ($session->get('isLoggedIn')) {
            
            return view('penalty/v_penalty_rates');
        } else {
            return view('v_userman');
        }
    
        
    }



    public function rates(){
        $AuditTrail = new AuditTrail();
        // return view('penalty/v_penalty_rates');
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Penalty Rates Page',$session->get('id'));
            return view('penalty/v_penalty_rates');
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function create(){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Create Penalty Rates Page',$session->get('id'));
        return view('penalty/v_penalty_create');
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function ConfimPassword(){
        $encodedPassword = $this->request->getPost('password');
      
        $defaultpassword = password_hash('C1ct05468',PASSWORD_DEFAULT);

       if (password_verify($encodedPassword, $defaultpassword)) {
           
            $result = array('status' => 'yes','message'=> "Password Correct");
            return $this->response->setJSON($result);
        } else {
            $result = array('status' => 'no','message'=> "Password Incorrect");
            return $this->response->setJSON($result);
        }

    }
    public function delete_pen($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        // $data['p_id'] = $id;
        $data_penalty_rates = [
            // 'id' => $p_id,

            'deleted_by' =>'1',
            'deleted_at' => date('Y-m-d H:i:s'),
        ];
        $penaltyRatesModel = new PenaltyRatesModel();
        
        if ($penaltyRatesModel->updatePenRates($id, $data_penalty_rates)){
            $PenaltyRateListModel =  new PenaltyRateListModel();
            if($PenaltyRateListModel->updatePenRateslist($id, $data_penalty_rates)){
                $AuditTrail->save_logs('[delete] : Penalty Rate [id] = '.$id  ,$session->get('id'));
                $result = array('status' => 'yes','message'=> "DELETED");
                return $this->response->setJSON($result);
            }else {
                $result = array('status' => 'no','message'=> "Deletion failed in list");
                return $this->response->setJSON($result);
            }
        } else {
            $result = array('status' => 'no','message'=> "Deletion failed rates");
            return $this->response->setJSON($result);
        }
    }
    public function edit($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : Edit Penalty Rates Page [id] = '.$id,$session->get('id'));
        return view('penalty/v_penalty_edit',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function viewing($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : View Penalty Rates Page [id] = '.$id,$session->get('id'));
        return view('penalty/v_penalty_view',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function get_edit_info($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        $penaltyrateModel = new PenaltyRatesModel();
        if($datayr = $penaltyrateModel->get_penalty_rates_info($id)) {
            $dtyr = $datayr[0]->year;
            $dtremarks = $datayr[0]->remarks;
            
            $PenaltyRateListModel =  new PenaltyRateListModel();

            if($datafees = $PenaltyRateListModel->get_penalty_list_info($id)){
                $AuditTrail->save_logs('[fetch] : Penalty Rate details [id] = '.$id,$session->get('id'));
                $result = array('status' => 'yes','message'=> "Penalty rates loaded",'year'=>$dtyr,'fees'=>$datafees,'remarks'=>$dtremarks);
            } else{
                $result = array('status' => 'no','message'=> "No data found");
            }
        } else{
            $result = array('status' => 'no','message'=> "No data found");
        }
        

        

        
        return $this->response->setJSON($result);

        // return view('penalty/v_penalty_edit',$data);
    }

    public function getTaxYear(){
        $model = new PenaltyRatesModel();
        $data = $model->m_getTaxYear();
        // dd($data);
        $result = [
            "status" => "yes",
            "message" => "Records Found",
            "TableContent" => $data,
        ];
        return $this->response->setJSON($result);
    }

    public function penalty_rates_filter(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $ftr_selby = $this->request->getPost('ftr_selby');
        $ftr_val = $this->request->getPost('ftr_val');
        
        if ($ftr_selby == 'taxyear') {
            $ftrdata['ftr_by'] = 'year';
            $ftrdata['ftr_val'] = $ftr_val;
        } else {
            $ftrdata['ftr_by'] = '';
            $ftrdata['ftr_val'] = '';
        }
       
        $model = new PenaltyRatesModel();
        $data = $model->filterData($ftrdata);
        $AuditTrail->save_logs('[filter] : Penalty Rate Table ',$session->get('id'));
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();

    }
    public function save_penalty_rate(){
        $AuditTrail = new AuditTrail();
        $session = session();

        $p_id = $this->request->getPost('p_id');

        if ($p_id == '') {

            $penaltyrateModel = new PenaltyRatesModel();
            $year = $this->request->getPost('year');
            if ($data = $penaltyrateModel->check_penalty_rates_year($year)) {
                
                foreach ($data as $r){
                    $yearId = $r->id;
                }
                $result = array('status' => 'dup','message'=> "Penalties for year ".$year." already exist",'p_id'=>$yearId,'year'=>$year);
                $AuditTrail->save_logs('[saving] : Saving failed, because'.$year.'already exist ',$session->get('id'));
                return $this->response->setJSON($result);
            } else {
                // $result = array('status' => 'yes','message'=> "Saved");
                // return $this->response->setJSON($result);
            $remarks = $this->request->getPost('remarks');
            $looparray = $this->request->getPost('looparray');
            
            $data_penalty_rates = [
                        'year' => $year,
                        'remarks' => $remarks,
                        'created_by' =>$session->get('id'),
    
            ];
            
            if($id = $penaltyrateModel->save_penalty_rate($data_penalty_rates)){
                $array = explode(",", $looparray);
                $arrcount = count($array);
            $data_insert = [];
            for ($i=0; $i < $arrcount; $i++) { 
                // echo $array[$i];
                $penaltypercent = $this->request->getPost('penaltypercent'.$array[$i]);
                $penaltysurcharge = $this->request->getPost('surchargepercent'.$array[$i]);
    
                $arrdata = [
                    'penalty_rate_id' => $id,
                    'fees_default_id' => $array[$i],
                    'percent' => $penaltypercent,
                    'surcharge' => $penaltysurcharge,
                    'created_by' => '1',
                ];
                
               array_push($data_insert,$arrdata);
            }
    
            $model = new PenaltyRateListModel();
            // $table = '';
            if($result = $model->save_penalty_list('penalty_rate_list',$data_insert)){
                $AuditTrail->save_logs('[saving] : Penalty rate saved ',$session->get('id'));
                $result = array('status' => 'yes','message'=> "Records Saved");
                return $this->response->setJSON($result);
            } else {
                $result = array('status' => 'no','message'=> "Error Saving Rates List");
                return $this->response->setJSON($result);
            }
            } else {
                $result = array('status' => 'no','message'=> "Error Saving Rates");
                return $this->response->setJSON($result);
            }
            }
        } else {

            $remarks = $this->request->getPost('remarks');
            $looparr = $this->request->getPost('looparray');
            $data_penalty_rates = [
                // 'id' => $p_id,
                'remarks' => $remarks,
                'updated_by' =>'1',
            ];
            $penaltyRatesModel = new PenaltyRatesModel();
            $updateRemarkResult = $penaltyRatesModel->updatePenRates($p_id, $data_penalty_rates);
            if($updateRemarkResult){
                $array = explode(",", $looparr);
                $arrcount = count($array);
                $data_update = [];
                $inputArray = [];
                for ($i=0; $i < $arrcount; $i++) { 
                    // echo $array[$i];
                
                    $f_id = $this->request->getPost('f_id'.$array[$i]);
                    $penaltypercent = $this->request->getPost('penaltypercent'.$array[$i]);
                    $penaltysurcharge = $this->request->getPost('surchargepercent'.$array[$i]);
        
                    $arrdata = [
                        'id' => $f_id,
                        'percent' => $penaltypercent,
                        'surcharge' => $penaltysurcharge,
                        'updated_by' => '1',
                    ];
                    
                   array_push($data_update,$arrdata);
                }


                foreach ($data_update as $item) {
                    $inputArray[] = [
                        'id' => $item['id'],
                        'percent' => $item['percent'],
                        'surcharge' => $item['surcharge'],
                        'updated_by' => $item['updated_by'],
                    ];
                }
                // print_r($inputArray);
                // die();
                $PenaltyRateListModel = new PenaltyRateListModel();
                if ($PenaltyRateListModel->updateBatchpen($inputArray, 'id')){
                    $AuditTrail->save_logs('[saving] : Penalty rate updated ',$session->get('id'));
                    $result = array('status' => 'yes','message'=> "Records updated");
                    return $this->response->setJSON($result);
                } else{
                    $result = array('status' => 'no','message'=> "Error Updating Batch");
                    return $this->response->setJSON($result);
                }

                // if ($PenaltyRateListModel->updatePenRateslist($inputArray, 'id')) {
                //     $result = array('status' => 'yes','message'=> "Records updated");
                //     return $this->response->setJSON($result);
                // } else {
                //     $result = array('status' => 'no','message'=> "Error Updating Batch");
                //     return $this->response->setJSON($result);
                // }
                
            } else {
                $result = array('status' => 'no','message'=> "Error Updating");
                return $this->response->setJSON($result);
            }

           
        }

       
        




    }


    public function fees_default(){
     
        $feeDefault = new FeesDefaultModel();
        // $data = $model->filterData($ftrdata);
        $data['penalties'] = $feeDefault->where('group', 'r')->orderBy('order', 'ASC')->findAll();
        
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();

    }
}