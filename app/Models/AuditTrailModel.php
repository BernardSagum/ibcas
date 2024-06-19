<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditTrailModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'audittrails';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['action','userid'];

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
        $query = $this->db->table('collectors')
            ->select('collectors.id, collectors.user_id, collectors.accountable_officer_id, collectors.barangay_id, collectors.email, collectors.tin_no, collectors.deputized_collector, collectors.field_collector, CONCAT_WS(" ", accoff.firstname, COALESCE(accoff.middlename, ""), COALESCE(accoff.lastname, ""), COALESCE(accoff.suffix, "")) as accountable_person, CONCAT_WS(" ", colname.firstname, COALESCE(colname.middlename, ""), COALESCE(colname.lastname, ""), COALESCE(colname.suffix, "")) as colname')
            ->join('accountable_form_officers', 'accountable_form_officers.id = collectors.accountable_officer_id', 'left')
            ->join('users as accoff', 'accoff.id = collectors.accountable_officer_id', 'left')
            ->join('users as colname', 'colname.id = collectors.user_id', 'left')
            ->where('collectors.deleted_by', null);
        
        if (!empty($data['ftr_by']) && !empty($data['ftr_val'])) {
            $query->where($data['ftr_by'], $data['ftr_val']);
        }
        
        $result = $query->get()->getResult();
        return $result;
    }

    public function m_save_logs($data){
        $builder = $this->db->table('audittrails');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            return true;
        }
    }


}
