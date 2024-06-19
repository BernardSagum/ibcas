<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Encryption;

class AccOfficersModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'accountable_form_officers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id','type','is_active'];

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


    public function filterData($data) {
        $config = new Encryption();
        $secretKey = $config->secretKey;
        $sql = "
            SELECT 
            accountable_form_officers.id, accountable_form_officers.user_id, accountable_form_officers.type, 
            accountable_form_officers.is_active,accoff.username,
            CONCAT_WS(' ', accoff.firstname, COALESCE(accoff.middlename, ''), COALESCE(accoff.lastname, ''), COALESCE(accoff.suffix, '')) as accountable_person    
            FROM accountable_form_officers
            LEFT JOIN users as accoff ON accoff.id = accountable_form_officers.user_id
            WHERE accountable_form_officers.deleted_by IS NULL";
    
        if (!empty($data['ftr_by']) && !empty($data['ftr_val'])) {
            if ($data['ftr_by'] == 'officer_name') {
                $sql .= " HAVING accountable_person  LIKE '%" . $data['ftr_val'] . "%'";
            }
        }
    
        // Execute the SQL query using your database connection
        $result = $this->db->query($sql)->getResult();
    
        return $result;
    }
    
    public function m_get_accofficer_info($id) {
        $config = new Encryption();
        $secretKey = $config->secretKey;
        $sql = "
            SELECT 
            accountable_form_officers.id, accountable_form_officers.user_id, accountable_form_officers.type,accountable_form_officers.created_at,accountable_form_officers.updated_at, 
            accountable_form_officers.is_active,accoff.username,
            CONCAT_WS(' ', accoff.firstname, COALESCE(accoff.middlename, ''), COALESCE(accoff.lastname, ''), COALESCE(accoff.suffix, '')) as accountable_person,   
            CONCAT_WS(' ', created.firstname, COALESCE(created.middlename, ''), COALESCE(created.lastname, ''), COALESCE(created.suffix, '')) AS createdby,
            CONCAT_WS(' ', updated.firstname, COALESCE(updated.middlename, ''), COALESCE(updated.lastname, ''), COALESCE(updated.suffix, '')) AS updatedby 
      
            FROM accountable_form_officers
            LEFT JOIN users as accoff ON accoff.id = accountable_form_officers.user_id
            LEFT JOIN users as created ON created.id = accountable_form_officers.created_by
            LEFT JOIN users as updated ON updated.id = accountable_form_officers.updated_by
            WHERE accountable_form_officers.deleted_by IS NULL AND accountable_form_officers.id ='" . $id."'" ;
    
        
    
        // Execute the SQL query using your database connection
        $result = $this->db->query($sql)->getResult();
    
        return $result;
    }
    
   
    public function saveAccOfficer($data){
        $builder = $this->db->table('accountable_form_officers');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            return true;
        }
    }

    public function updateAccOfficer($data,$id)
    {
      
        $builder = $this->db->table('accountable_form_officers');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }


}
