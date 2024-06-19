<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountableFormsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'accountable_forms';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['form_id','fund_id','stub_no','from','to','date_delivered'];

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
     
        $query = $this->db->table('accountable_forms')
            ->select('accountable_forms.id, accountable_forms.form_id, forms.form_no,`funds`.`name` AS fundname,
                        accountable_forms.fund_id, accountable_forms.stub_no,
                        accountable_forms.from, accountable_forms.to, accountable_forms.date_delivered, accountable_forms.created_by,accountable_form_assigned_receipts.date_issued')
            ->join('forms', 'accountable_forms.form_id = forms.id', 'left')
            ->join('funds', 'accountable_forms.fund_id = funds.id', 'left')
            ->join('accountable_form_assigned_receipts', 'accountable_forms.id = accountable_form_assigned_receipts.accountable_form_id', 'left')
            ->where('accountable_forms.deleted_by', null);
    
        if ($data['ftr_by'] != '') {
            $query->where($data['ftr_by'], $data['ftr_val']);
        }
    
        $result = $query->get()->getResult();
        return $result;
    }


public function check_duplicate($data) {
    // Assuming you're using a database abstraction layer or ORM
    $query = $this->db->table('accountable_forms')
                      ->select('COUNT(*) as count')
                      ->where('form_id', $data['form_id'])
                      ->where('fund_id', $data['fund_id'])
                      ->where('stub_no', $data['stub_no'])
                      ->where('from', $data['from'])
                      ->where('to', $data['to'])
                      ->get();

    // Fetch the result
    $result = $query->getRow(); // Adjust this method based on your DB access layer

    // Check if count is greater than 0, indicating a duplicate exists
    return $result->count > 0;
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
    public function save_acc_form($data){
        $builder = $this->db->table('accountable_forms');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            return true;
        }
    }
    public function save_acc_assign($data){
        $builder = $this->db->table('accountable_form_assigned_receipts');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            return true;
        }
    }
    public function save_series($data){
        $builder = $this->db->table('accountable_form_or_series');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            return true;
        }
    }
    public function update_acc_form($data,$id)
    {
      
        $builder = $this->db->table('accountable_forms');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function check_if_assigned($id) {
        // $db = \Config\Database::connect();
    
        $query = $this->db->table('accountable_forms')
            ->select('accountable_forms.id,CONCAT(assigned.firstname," ",assigned.middlename," ",assigned.lastname," ",assigned.suffix) AS assigned_to')
            ->join('accountable_form_or_series', 'accountable_forms.id = accountable_form_or_series.accountable_form_id', 'left')
            ->join('accountable_form_assigned_receipts', 'accountable_forms.id = accountable_form_assigned_receipts.accountable_form_id', 'left')
            ->join('users AS assigned', 'assigned.id = accountable_form_assigned_receipts.assigned', 'left')
            ->where('accountable_form_or_series.deleted_by', null)
            ->where('accountable_form_or_series.void', 'N')
            ->where('accountable_form_or_series.ornum', $id)
            ->get();
    
        $result = $query->getResult();
    
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    public function mGetFromTo($id){
        $sql = "
            SELECT MIN(afor.`ornum`) AS `from`, MAX(afor.`ornum`) AS `to` 
            FROM accountable_form_or_series afor
            WHERE afor.`deleted_by` IS NULL
            AND afor.`accountable_form_id` = '$id'
            AND afor.`status` = 'AVAIL'
            ";

            $query = $this->db->query($sql);
            return $query->getResult();


    }
    public function get_accform_info($id) {
        // $db = \Config\Database::connect();
    
        $query = $this->db->table('accountable_forms')
            ->select('forms.form_no, accountable_forms.form_id, accountable_forms.fund_id, forms.print_label, funds.name,
            accountable_forms.stub_no,
            accountable_forms.id,
            accountable_forms.from,
            accountable_forms.to,
            accountable_forms.date_delivered,
            forms.description AS form_name,
            acformassigned.accountable_form_officer_id,
            acformassigned.assigned AS assigned_to_id,
            CONCAT(COALESCE(created.firstname, \'\'), \' \', COALESCE(created.middlename, \'\'), \' \', COALESCE(created.lastname, \'\'), \' \', COALESCE(created.suffix, \'\')) AS created_by,
            CONCAT(COALESCE(updated.firstname, \'\'), \' \', COALESCE(updated.middlename, \'\'), \' \', COALESCE(updated.lastname, \'\'), \' \', COALESCE(updated.suffix, \'\')) AS updated_by,
            CONCAT(COALESCE(assigned.firstname, \'\'), \' \', COALESCE(assigned.middlename, \'\'), \' \', COALESCE(assigned.lastname, \'\'), \' \', COALESCE(assigned.suffix, \'\')) AS assigned_to,
            acformassigned.date_issued,
            acformassigned.id AS assigned_receipts_id,
            accountable_forms.created_at,
            accountable_forms.updated_at,
            CONCAT(COALESCE(acc_officer.firstname, \'\'), \' \', COALESCE(acc_officer.middlename, \'\'), \' \', COALESCE(acc_officer.lastname, \'\'), \' \', COALESCE(acc_officer.suffix, \'\')) AS accountable_officer_name')
            ->join('forms', 'forms.id = accountable_forms.form_id', 'left')
            ->join('funds', 'accountable_forms.fund_id = funds.id', 'left')
            ->join('accountable_form_assigned_receipts AS acformassigned', 'accountable_forms.id = acformassigned.accountable_form_id', 'left')
            ->join('users AS created', 'accountable_forms.created_by = created.id', 'left')
            ->join('users AS updated', 'accountable_forms.updated_by = updated.id', 'left')
            ->join('users AS assigned', 'assigned.id = acformassigned.assigned', 'left')

            ->join('accountable_form_officers AS accofficer', 'accofficer.id = acformassigned.accountable_form_officer_id', 'left')

            ->join('users AS acc_officer', 'acc_officer.id = accofficer.user_id', 'left')
            ->where('accountable_forms.deleted_by', null)
            ->where('accountable_forms.id', $id)
            ->get();
    
        $result = $query->getResult();
    
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    
    
    public function getAllForms(){
        $sql = "SELECT `id`,`form_no` FROM `forms` WHERE `deleted_by` IS NULL";

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
    
    public function getAllFunds(){
        $sql = "SELECT `id`,`name` FROM `funds` WHERE `deleted_by` IS NULL";

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
    public function update_acc_assign($data,$id)
    {
      
        $builder = $this->db->table('accountable_form_assigned_receipts');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function void_series($data,$id)
    {
      
        $builder = $this->db->table('accountable_form_or_series');
        $builder->where('ornum', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_afor($data, $from, $to)
    {
        $builder = $this->db->table('accountable_form_or_series');
    
        // Set the condition to update rows where ornum is between $from and $to
        $builder->where('ornum >=', $from);
        $builder->where('ornum <=', $to);
    
        // Perform the update
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    


}
