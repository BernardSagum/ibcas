<?php

namespace App\Controllers;
use App\Models\AuditTrailModel;

class Home extends BaseController
{
    public function index()
    {
        $session = session();
        $action = 'Home page';
        $user_id = $session->get('id');
        $this->save_logs($action, $user_id);
        return view('welcome_message');
    }
    
    public function save_logs($action,$user_id)
    {   
        $session = session();
        $model = new AuditTrailModel();
        $logsArray = array(
            'action' => $action,
            'userid' => $user_id,
        );
        if($model->m_save_logs($logsArray)){
            $result = array('status' => 200,'message'=> "Logs saved");
            return $this->response->setJSON($result);
        } else {
            $result = array('status' => 404,'message'=> "Error saving logs");
            return $this->response->setJSON($result);
        }

    
        
    }

}
