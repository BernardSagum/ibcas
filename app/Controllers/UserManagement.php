<?php


namespace App\Controllers;

use App\Models\UserManagementModel;


class UserManagement extends BaseController
{
    public function index()
    {
        $session = session();
        if ($session->get('isLoggedIn')) {
             return redirect()->to('/ibcas/users/profile');
        } else {
            return view('users/v_userlogin');
            // return view('v_userman');
        }
        // redirect()
    }
    public function loginRedirect()
    {   
        $session = session();
        if ($session->get('isLoggedIn')) {
            return redirect()->to(base_url('ibcas/users/profile'));
        } else {
            return redirect()->to('/ibcas/login');
            // return view('v_userman');
        }
       
    }
    public function login(){
        $session = session();
        if ($session->get('isLoggedIn')) {
            return redirect()->to(base_url('ibcas/users/profile'));
        } else {
            return view('users/v_userlogin');
            // return view('v_userman');
        }
        
    }
    public function UserProfile(){
        $session = session();
        if ($session->get('isLoggedIn')) {
            $data['Userpass'] = $session->get('userPass');
            return view('users/v_UserProfile',$data);
        } else {
            return redirect()->to(base_url('ibcas/login'));
            // return view('v_userman');
        }
    }
    public function user_authentication(){
        $AuditTrail = new AuditTrail();
        date_default_timezone_set('Asia/Manila');

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // echo $hashedPassword;

        $UserManagementModel = new UserManagementModel();


        $user = $UserManagementModel->findByUsername($username);
        foreach ($user as $ur) {
            if (!$user || !password_verify($password, $ur->password)) {
                return $this->response->setJSON(['message' => 'Invalid username or password','status'=>'404']);
            }
        
        

        // Set session variables
            $session = session();
            $sessionData = [
                'id' => $ur->id,
                'username' => $ur->username,
                'fullName' => $ur->firstname." ".$ur->middlename." ".$ur->lastname,
                'firstName' =>$ur->firstname,
                'lastName' =>$ur->lastname,
                'middleName' =>$ur->middlename,
                'department' =>$ur->department,
                'userPass'  => $password,
                'isLoggedIn' => true,
               
            ];
            // print_r($sessionData);
            // die();
            $session->set($sessionData);
            $AuditTrail->save_logs('[Logged in] : User:'.$session->get('username').' date and time :'.date('M-d-Y H:i:s'),$session->get('id'));
            return $this->response->setJSON(['message' => 'Login successful','status'=>'200']);
            // return $this->response->setJSON($user);
        }
    }

    public function ConfimPassword(){
        $session = session();
        $username =  $session->get('username');
        $password = $this->request->getVar('password');
        $UserManagementModel = new UserManagementModel();


        $user = $UserManagementModel->findByUsername($username);
        // echo $user['password'];
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->response->setJSON(['message' => 'Invalid Password','status'=>'404']);
        } else {
            return $this->response->setJSON(['message' => 'Password Authentication success','status'=>'200']);
        }

    }
    public function ChangePassword(){
        $session = session();
        $AuditTrail = new AuditTrail();
        $UserManagementModel = new UserManagementModel();
        $userid = $session->get('id');
        $postedData = $this->request->getPost();
       
        $newPasswordArray = array(
            'password'   => password_hash($postedData['newPass'], PASSWORD_DEFAULT),
            'updated_by' => $session->get('id'),
        );
        
        // print_r($newPasswordArray);
        // die();
        if ($UserManagementModel->mChangePassword($newPasswordArray,$userid)) {
            $AuditTrail->save_logs('Change password User : '.$session->get('username'),$session->get('id'));
            $result = array('status' => 200, 'message' => "Password changed");
        } else {
            $result = array('status' => 201, 'message' => "Password changing failed");
        }

        return $this->response->setJSON($result);
       

    }



    public function logout()
    {   
        date_default_timezone_set('Asia/Manila');

        $AuditTrail = new AuditTrail();
        $session = session();
        $AuditTrail->save_logs('[Logged out] : User: '.$session->get('username').' date and time :'.date('M-d-Y H:i:s'),$session->get('id'));
        $session->destroy();
        
        $result = array('message' => 'Logout successful','status'=>201);
        return $this->response->setJSON($result);
        // if(empty($isLoggedIn)){
        //     $result = array('message' => 'Logout successful','status'=>201);
        //     return $this->response->setJSON($result);
        // } else {
        //     $result = array('message' => 'Logout Error','status'=>200);
        //     return $this->response->setJSON($result);
        // }



        
        // return $this->response->setJSON(['message' => 'Logout successful','status'=>201]);
    }

}
