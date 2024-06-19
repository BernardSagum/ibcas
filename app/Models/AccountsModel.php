<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Encryption;

class AccountsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'accounts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['effectivity_year','code','title','acronym','account_type','account_nature','remarks','created_by','updated_by','deleted_by'];

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


    public function getParticularTypes()
    {
        $query = $this->db->table('particular_types AS pt')
        ->select('pt.id, pt.name')
        ->where('pt.deleteddt', NULL);
        $result = $query->get()->getResult();
        return $result;
    }


    public function saveAccount($data)
    {
        return $this->save($data);
    }
    public function updateAccount($id, $data)
    {
        return $this->update($id, $data);
    }

    public function filterChartofAccounts($ftrData){

        $sql = "
        SELECT 	acc.`id`,
                acc.`effectivity_year`,
                acc.`code`,
                acc.`title`,
                acc.`account_type`
        FROM accounts acc
        
        WHERE deleted_by IS NULL
        ";
        $sql.= " AND ".$ftrData['ftr_by']." LIKE '%".$ftrData['ftr_val']."%'";
        // if ($ftrData['ftr_by'] == 'name') {
        //     $sql.= " AND prls.`name` LIKE '%".$ftrData['ftr_val']."%'";
        // } elseif ($ftrData['ftr_by'] == 'particular_type_id') {
        //     $sql.= " AND prls.`particular_type_id` = '".$ftrData['ftr_val']."'";
        // }

        $query = $this->db->query($sql);
        return $query->getResult();
    }

    public function mGetAccountDetails($id){
        $sql = "
        SELECT a.id,a.`effectivity_year`,a.`code`,a.`title`,a.`acronym`,a.`account_nature`,a.`account_type`,a.`remarks`,
		CONCAT_WS(' ', created.firstname, COALESCE(created.middlename, ''), COALESCE(created.lastname, ''), COALESCE(created.suffix, '')) AS createdby,
		CONCAT_WS(' ', updated.firstname, COALESCE(updated.middlename, ''), COALESCE(updated.lastname, ''), COALESCE(updated.suffix, '')) AS updatedby,
		a.`created_at`,a.`updated_at`
            FROM accounts a
            LEFT JOIN users AS created ON created.id = a.`created_by`
            LEFT JOIN users AS updated ON updated.id = a.`updated_by`
            WHERE a.`deleted_by` IS NULL
            AND a.`id`= '$id'";

            $query = $this->db->query($sql);
            return $query->getResult();


    }


    public function deleteAccount($id,$data)
    {
      
        $builder = $this->db->table('Accounts');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
}
