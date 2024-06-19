<?php

namespace App\Models;

use CodeIgniter\Model;

class ClaimStubModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'claimstub_schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['application_type_id','tax_effectivity_year'];

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
     
        $query = $this->db->table('claimstub_schedules')
            ->select('claimstub_schedules.id, application_type_id, tax_effectivity_year, first_quarter_date,
                second_quarter_date, third_quarter_date, fourth_quarter_date, application_type.name')
            ->join('application_type', 'claimstub_schedules.application_type_id = application_type.id', 'inner')
            ->where('claimstub_schedules.deleted_by', null);
    
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
    public function save_claimstub($data){
        $builder = $this->db->table('claimstub_schedules');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            return true;
        }
    }
    public function update_claimstub($data,$id)
    {
      
        $builder = $this->db->table('claimstub_schedules');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function get_claimstub_info($id) {
        // $db = \Config\Database::connect();
    
        $query = $this->db->table('claimstub_schedules')
            ->select('claimstub_schedules.id, application_type_id, tax_effectivity_year, first_quarter_date,
                first_quarter_peak_days, second_quarter_date, second_quarter_peak_days,
                third_quarter_date, third_quarter_peak_days, fourth_quarter_date,
                fourth_quarter_peak_days, nonpeak_days, claimstub_schedules.remarks,
                claimstub_schedules.created_at,
                claimstub_schedules.updated_at,
                application_type.name AS app_type_name, created.username AS created_by,updated.username AS updated_by
               ')
            ->join('application_type', 'claimstub_schedules.application_type_id = application_type.id', 'left')
            ->join('users AS created', 'claimstub_schedules.created_by = created.id', 'left')
            ->join('users AS updated', 'claimstub_schedules.updated_by = updated.id', 'left')
            ->where('claimstub_schedules.deleted_by', null)
            ->where('claimstub_schedules.id', $id)
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
