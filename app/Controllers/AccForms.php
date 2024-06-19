<?php


namespace App\Controllers;

use App\Models\AccountableFormsModel;
use App\Models\UserManagementModel;

class AccForms extends BaseController
{



    
    public function index()
    {   
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Accountable Form Page ',$session->get('id'));
            return view('accforms/v_accforms');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    public function acc_officer(){
        $session = session();
        if ($session->get('isLoggedIn')) {
        return view('accforms/v_acc_officer');
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
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


    public function create(){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Create Accountable Form Page ',$session->get('id'));
        return view('accforms/v_accforms_create');
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
   
    public function delete_accform($id){
        $AuditTrail = new AuditTrail();
        $Model = new AccountableFormsModel();
        $acc_id = $id;
        $session = session();
        $data_acc_form =array(
            'deleted_by' =>$session->get('id'),
            'deleted_at' => date('Y-m-d H:i:s'),
        );
        
        
        if($Model->update_acc_form($data_acc_form,$acc_id)){
            $AuditTrail->save_logs('[delete] : Delete Accountable Form Page [id] = '.$id,$session->get('id'));
            $result = array('status' => 200,'message'=> "Record Deleted");
            return $this->response->setJSON($result);
        } else {
            $result = array('status' => 404,'message'=> "Records deletion error");
            return $this->response->setJSON($result);
        }
    }
    public function edit($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : Edit Accountable Form Page [id] = '.$id,$session->get('id'));
        return view('accforms/v_accforms_edit',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function reassign($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : Reasign Accountable Form Page [id] = '.$id,$session->get('id'));
        return view('accforms/v_accforms_reassign',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function assign($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
        $data['p_id'] = $id;
        $AuditTrail->save_logs('[visit] : Assign Accountable Form Page [id] = '.$id,$session->get('id'));
        return view('accforms/v_accforms_assign',$data);
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
        $AuditTrail->save_logs('[visit] : View Accountable Form Page [id] = '.$id,$session->get('id'));
        return view('accforms/v_accforms_view',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));

            // return redirect('');
            // return view('v_userman');
        }
    }
    public function get_edit_info($id){
        $AuditTrail = new AuditTrail();
        $session = session();
        $model = new AccountableFormsModel();
        if($datayr = $model->get_accform_info($id)) {
            $dataFrTo = $model->mGetFromTo($id);
            $AuditTrail->save_logs('[fetch] : Accountable Form info [id] = '.$id,$session->get('id'));
            $result = array('status' => 200,'message'=> "Record Found",'recdata' => $datayr,'FromToData'=>$dataFrTo);
        } else{
            $result = array('status' => 404,'message'=> "No data found");
        }
        

        

        
        return $this->response->setJSON($result);

        // return view('penalty/v_penalty_edit',$data);
    }


    public function acc_forms_filter(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $ftr_selby = $this->request->getPost('ftr_selby');
        $ftr_val = $this->request->getPost('ftr_val');
        
        if ($ftr_selby == 'formnum') {
            $ftrdata['ftr_by'] = 'form_id';
            $ftrdata['ftr_val'] = $ftr_val;
        } else if ($ftr_selby == 'fund') {
            $ftrdata['ftr_by'] = 'fund_id';
            $ftrdata['ftr_val'] = $ftr_val;
        } else {
            $ftrdata['ftr_by'] = '';
            $ftrdata['ftr_val'] = '';
        }
       
        $model = new AccountableFormsModel();
        $data = $model->filterData($ftrdata);
        $AuditTrail->save_logs('[filter] : Accountable Form Table ',$session->get('id'));
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();

    }
    public function save_accform(){
        $AuditTrail = new AuditTrail();
        $Model = new AccountableFormsModel();
        $session = session();
        $acc_id = $this->request->getPost('acc_id');
        


        if ($acc_id == '') {

            $acc_from = $this->request->getPost('from');
            $acc_to = $this->request->getPost('to');
            
            // Ensure acc_from is less than or equal to acc_to
            if ($acc_from > $acc_to) {
                // Swap values if acc_from is greater
                $temp = $acc_from;
                $acc_from = $acc_to;
                $acc_to = $temp;
            }
            
            $rangeSize = 50;
            $numRanges = ceil(($acc_to - $acc_from + 1) / $rangeSize);
            $successCount = 0;
            
           // Assuming the rest of your setup is the same as before

           $duplicates = [];
           $entriesToProcess = [];
           $successCount = 0;
           
           for ($i = 0; $i < $numRanges; $i++) {
               $start = $acc_from + ($i * $rangeSize);
               $end = min($start + $rangeSize - 1, $acc_to);
           
               $entry = [
                   'form_id' => $this->request->getPost('form_id'),
                   'fund_id' => $this->request->getPost('fund_id'),
                   'stub_no' => $this->request->getPost('stub_no'),
                   'from' => $start,
                   'to' => $end,
                   'date_delivered' => $this->request->getPost('date_delivered'),
                   'created_by' => $session->get('id'),
               ];
           
               $entriesToProcess[] = $entry;
           }
           
           // Check for duplicates in all entries
           foreach ($entriesToProcess as $entry) {
               if ($Model->check_duplicate($entry)) {
                   $duplicates[] = $entry; // Add to duplicates array if duplicate
               }
           }
           
           // Handling based on duplicates
           if (!empty($duplicates)) {
               // Return all duplicate entries to be fixed
               $result = [
                   'status' => 409, 
                   'message' => "Duplicate entries were found.", 
                   'duplicate_entries' => $duplicates
               ];
           } else {
               // If no duplicates, proceed with saving all entries
               foreach ($entriesToProcess as $entry) {
                   if ($Model->save_acc_form($entry)) {
                       $successCount++;
                       $AuditTrail->save_logs('[saving] : Accountable Form saved ', $session->get('id'));
                   }
               }
           
               if ($successCount == count($entriesToProcess)) {
                   $result = ['status' => 200, 'message' => "All records saved successfully."];
               } else {
                   // This else block might not be necessary if you're ensuring all saves are successful,
                   // but it's here to handle any unforeseen errors during save operations.
                   $result = ['status' => 404, 'message' => "An error occurred while saving some records."];
               }
           }
           
           return $this->response->setJSON($result);
           
           
            
           
        } else {

            $updateArr = array(
                'form_id' => $this->request->getPost('form_id'),
                'fund_id' => $this->request->getPost('fund_id'),
                'stub_no' => $this->request->getPost('stub_no'),
                'from' => $this->request->getPost('from'),
                'to' => $this->request->getPost('to'),
                'date_delivered' => $this->request->getPost('date_delivered'),
                'updated_by' => $session->get('id'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if($Model->update_acc_form($updateArr,$acc_id)){
                $AuditTrail->save_logs('[saving] : Accountable Form Updated ',$session->get('id'));
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
    public function save_assign_form(){
        $AuditTrail = new AuditTrail();
        $Model = new AccountableFormsModel();
        $session = session();
        $from = $this->request->getPost('from');
        $assign_id = $this->request->getPost('assign_id');
       
        if($assign_id == ''){
            $resData = $Model->check_if_assigned($from);
            if (!$resData) {
                $AcfId = "";
                $saveassign = array(
                    'accountable_form_id' => $this->request->getPost('acc_id'),
                    'accountable_form_officer_id' => $this->request->getPost('acc_officer'),
                    'assigned' => $this->request->getPost('assign'),
                    'date_issued' => $this->request->getPost('date_issued'),
                    'created_by' => $session->get('id'),
                );

                if($Model->save_acc_assign($saveassign)){
                    $AuditTrail->save_logs('[saving] : Accountable Form Assigned ',$session->get('id'));
                    $result = array('status' => 200,'message'=> "Record Assigned");
                    return $this->response->setJSON($result);
                } else {
                    $result = array('status' => 404,'message'=> "Records saving error");
                    return $this->response->setJSON($result);
                }
            } else {
                foreach ($resData as $r) {
                    $AcfId = $r->id;
                }
                $result = array('status' => 402,'message'=> "Already assigned",'accFormid'=>$AcfId);
                return $this->response->setJSON($result);
            }
        }

    

            // $result = array('status' => 200,'message'=> "Record updated");
            // return $this->response->setJSON($result);
      
            
        

    }
    public function save_void_form(){
        $AuditTrail = new AuditTrail();
        $Model = new AccountableFormsModel();
        $session = session();
        $acc_id = $this->request->getPost('acc_id');
        $from = $this->request->getPost('vfrom');
        $to = $this->request->getPost('vto');
        $sertrigger = 0;
        if ($acc_id != '') {
            for ($i=$from; $i <= $to; $i++) { 

                $voidArr = array(
                    'status' => 'VOID',
                    'void' => 'Y',
                    'date_void' => date('Y-m-d H:i:s'),
                );

                if($Model->void_series($voidArr,intval($i))){
                    $sertrigger += 1;
                } else {
                    $result = array('status' => 404,'message'=> "Void saving error");
                    return $this->response->setJSON($result);
                }

                if($sertrigger == 50){
                    $AuditTrail->save_logs('[saving] : Accountable Form voided ',$session->get('id'));
                    $result = array('status' => 200,'message'=> 'Series voided');
                    return $this->response->setJSON($result);
                }

                
            }
        }


        




    }

    public function save_reassign_form(){
        $AuditTrail = new AuditTrail();
        $Model = new AccountableFormsModel();
        $session = session();
        $from = $this->request->getPost('from');
        $to = $this->request->getPost('to');
        $assign_id = $this->request->getPost('assign_id');
        $assigned_receipts_id = $this->request->getPost('assigned_receipts_id');
        $AcfId = "";
                $saveassign = array(
                    'accountable_form_id' => $this->request->getPost('acc_id'),
                    'accountable_form_officer_id' => $this->request->getPost('acc_officer'),
                    'assigned' => $this->request->getPost('assign'),
                    'date_issued' => $this->request->getPost('date_issued'),
                    'updated_by' => $session->get('id'),
                );

                if($Model->update_acc_assign($saveassign,$assigned_receipts_id)){
                    $AuditTrail->save_logs('[saving] : Accountable Form Reassigned ',$session->get('id'));
                    $result = array('status' => 200,'message'=> "Record Reassigned");
                    return $this->response->setJSON($result);
                } else {
                    $result = array('status' => 404,'message'=> "Records saving error");
                    return $this->response->setJSON($result);
                }
    }

    public function reassign_series(){
        $AuditTrail = new AuditTrail();
        $Model = new AccountableFormsModel();
        $session = session();
        $from = $this->request->getPost('from');
        $to = $this->request->getPost('to');
        $assignTo = $this->request->getPost('assign');

        $saveassign = array(
            'assigned_to' => $assignTo,
            'updated_by' => $session->get('id'),
        );

        if($Model->update_afor($saveassign,$from,$to)){
            $AuditTrail->save_logs('Updated OR Series Reassigned ',$session->get('id'));
            $result = array('status' => 200,'message'=> "Records Reassigned");
            return $this->response->setJSON($result);
        } else {
            $result = array('status' => 404,'message'=> "Records saving error");
            return $this->response->setJSON($result);
        }


    }





    public function generate_series(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $Model = new AccountableFormsModel();
        $from = $this->request->getPost('from');
        $to = $this->request->getPost('to');
        $result = [];
        $counter = 0;
        if (!$Model->check_if_assigned($from)) {
        for ($i=$from; $i <= $to ; $i++) { 
           $arr_ser = array(
            'accountable_form_id' => $this->request->getPost('acc_id'),
            'assigned_to' => $this->request->getPost('assign'),
            'ornum' => $i,
            'created_by' => $session->get('id'),
           );

           $counter += 1;
           $Model->save_series($arr_ser);
           if($counter == 50){
            $AuditTrail->save_logs('[saving] : Series Generated ',$session->get('id'));
            $result = array('status' => 200,'message'=> "Series generated");
            return $this->response->setJSON($result);
           }

        }
    } else {
        $result = array('status' => 402,'message'=> "Already assigned");
        return $this->response->setJSON($result);
    }



    }

    public function getforms(){
     
        $Model = new AccountableFormsModel();
        // $data = $model->filterData($ftrdata);
        $data = $Model->getAllForms();
        
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();

    }
    public function getfunds(){
     
        $Model = new AccountableFormsModel();
        // $data = $model->filterData($ftrdata);
        $data = $Model->getAllFunds();
        
        $result = array('status' => 'yes','message'=> "Records Found","TableContent"=>$data);
        return $this->response->setJSON($result);
        // end();

    }
}