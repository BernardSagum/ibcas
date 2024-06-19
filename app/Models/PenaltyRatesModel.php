<?php

namespace App\Models;

use CodeIgniter\Model;

class PenaltyRatesModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'penalty_rates';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['year','remarks'];

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


    public function filterData($data){
        $sql = "SELECT `id`,`year` FROM penalty_rates WHERE `deleted_by` IS NULL";

        if ($data['ftr_by'] != '') {
            $sql .= " AND `".$data['ftr_by']."` = '".$data['ftr_val']."'";
        }

        $query = $this->db->query($sql);

        $result = $query->getResult();
        return $result;
    }

    public function save_penalty_rate($data){
        $builder = $this->db->table('penalty_rates');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        return $insertID;
    }
    public function updatePenRates($id, $data)
    {
      
        $builder = $this->db->table('penalty_rates');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_penalty_rates_info($id){
        $sql = "SELECT `id`,
                        `year`,
                        `remarks`
                FROM `penalty_rates` 
                WHERE `deleted_by` IS NULL AND `penalty_rates`.`id` = '$id'";

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

    public function m_getTaxYear(){
        $sql = "SELECT `id`,`year` FROM `tax_year` WHERE `deleted_by` IS NULL";

        $query = $this->db->query($sql);

        $result = $query->getResult();
        return $result;
    }

}
