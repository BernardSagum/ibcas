<?php


namespace App\Controllers;

use App\Models\AccountableFormsModel;
use App\Models\UserManagementModel;

class CashTicket extends BaseController
{



    
    public function index()
    {   
        $AuditTrail = new AuditTrail();
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail->save_logs('[visit] : Accountable Form Page ',$session->get('id'));
            return view('cashticket/cashTicketView');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

}