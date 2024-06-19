<?php

namespace App\Models;

use CodeIgniter\Model;

class MiscellaneousModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'miscellaneous';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name','amount'];

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
     
        $query = $this->db->table('miscellaneous')
            ->select('id,name,amount')
            ->where('deleted_by', null);
    
        if ($data['ftr_by'] != '') {
            $query->where($data['ftr_by'], $data['ftr_val']);
        }
    
        $result = $query->get()->getResult();
        return $result;
    }


    public function getapplicationtype(){
        $sql = "SELECT  `application_type`.`id`,
        `application_type`.`name` 
        FROM `application_type`
        WHERE `application_type`.`deleted_by` IS NULL";
        $query = $this->db->query($sql);

        $result = $query->getResult();
        return $result;
    }
    public function save_fee($data){
        $builder = $this->db->table('miscellaneous');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            return true;
        }
    }
    public function update_msc($data,$id)
    {
      
        $builder = $this->db->table('miscellaneous');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function get_mscFee_info($id) {
        // $db = \Config\Database::connect();
    
        $query = $this->db->table('miscellaneous AS msc')
            ->select('msc.id,msc.name,msc.amount,
            msc.created_at,
            msc.updated_at,
            created.username AS created_by,updated.username AS updated_by
               ')
            
            ->join('users AS created', 'msc.created_by = created.id', 'left')
            ->join('users AS updated', 'msc.updated_by = updated.id', 'left')
            ->where('msc.deleted_by', null)
            ->where('msc.id', $id)
            ->get();
    
        $result = $query->getResult();
    
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    
    public function check_penalty_rates_year($year){
        $sql = "SELECT `id` FROM penalty_rates WHERE `deleted_by` IS NULL AND `year`='$year'";

        $query = $this->db->query($sql);
        $result = $query->getResult();
        if ($result) {
            return $result;
        } else {
            return false;
        }
        // $result = $query->getResult();
        // return $result;
    }



}
