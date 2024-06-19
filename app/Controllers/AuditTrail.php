<?php


namespace App\Controllers;

use App\Models\AuditTrailModel;
use App\Models\UserManagementModel;

class AuditTrail extends BaseController
{
    public function index()
    {   
        // $session = session();
        // if ($session->get('isLoggedIn')) {
        //     return view('logs/v_logs');
        // } else {
        //     return redirect()->to(base_url('ibcas/login'));
        // }
    
        
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
            return true;
        } else {
            return false;
        } 
    }




}