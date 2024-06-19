<?php

namespace App\Models;

use CodeIgniter\Model;

class UserManagementModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['username','password','prefix','lastname','firstname','middlename','suffix','photo'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function mChangePassword($data,$id)
    {   
        $builder = $this->db->table('users');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function findByUsername($username)
    {   
       // Assuming you are using CodeIgniter's query builder
        $query = $this->db->table('users as u')
        ->select('u.id, u.lastname, u.firstname, u.middlename, u.password, u.username, dept.name as department')
        ->join('departments as dept', 'dept.id = u.department_id', 'left')
        ->where('u.username', $username)  // Assuming $username is a variable holding the username to filter by
        ->get();

        $result = $query->getResult();

        if (!empty($result)) {
        return $result;
        } else {
        return false;
        }
    }

    public function getAllUser(){
        $query = $this->db->table('users')
        ->select('users.id,users.lastname,users.firstname,users.middlename,users.suffix,users.username')
        // ->join('forms', 'forms.id = accountable_forms.form_id', 'left')
        ->where('users.department_id', '5')
        ->get();

    $result = $query->getResult();

    if ($result) {
        return $result;
    } else {
        return false;
    }
    }

}
