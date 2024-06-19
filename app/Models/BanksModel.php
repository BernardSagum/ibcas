<?php

namespace App\Models;

use CodeIgniter\Model;

class BanksModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'banks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name','shortname','branch','contact_person','contactno','email','remarks','created_by','updated_by','deleted_by','deleted_at'];

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


    public function filterBankList($data) {
        $sql = "
        SELECT 
            bks.`id`,
            bks.`name`,
            bks.`shortname`,
            bks.`branch`,
            bks.`contact_person`,
            bks.`contactno`,
            bks.`email`
        FROM banks bks
        WHERE bks.`deleted_at` IS NULL ";


        if ($data['ftr_by'] != 'ALL') {
            $sql .= " AND ".$data['ftr_by']." LIKE '%".$data['ftr_val']."%' ";
        } 
            $query = $this->db->query($sql);
            return $query->getResult();

    }

    public function m_getBankDetails($id){
        $sql = "
        SELECT 
            bks.`id`,
            bks.`name`,
            bks.`shortname`,
            bks.`branch`,
            bks.`contact_person`,
            bks.`contactno`,
            bks.`email`,
            CONCAT_WS(' ', created.firstname, COALESCE(created.middlename, ''), COALESCE(created.lastname, ''), COALESCE(created.suffix, '')) AS createdby,
		    CONCAT_WS(' ', updated.firstname, COALESCE(updated.middlename, ''), COALESCE(updated.lastname, ''), COALESCE(updated.suffix, '')) AS updatedby,
		    bks.`created_at`,bks.`updated_at`,
            bks.`remarks`
        FROM banks bks
        LEFT JOIN users AS created ON created.id = bks.`created_by`
        LEFT JOIN users AS updated ON updated.id = bks.`updated_by`
        WHERE bks.`deleted_at` IS NULL AND bks.`id` ='".$id."'";

        $query = $this->db->query($sql);
        return $query->getResult();


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



    public function InsertBank($data){
        $this->save($data);

        // Get the ID of the newly inserted record
        $insertedID = $this->db->insertID();
    
        return $insertedID;
    }
    public function m_UpdateBankRecord(array $data, int $id): bool {
        return $this->update($id, $data);
    }
}
