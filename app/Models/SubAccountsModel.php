<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Encryption;

class SubAccountsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'account_subcode';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['effectivity_year','code','account_id','description','type','remarks','created_by','updated_by','deleted_by'];

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


    public function saveSubAccount($data)
    {
        $this->save($data);

        // Get the ID of the newly inserted record
        $insertedID = $this->db->insertID();
    
        return $insertedID;
    }
    public function savePartClass($data)
    {
        // Assuming you have a table name defined
        $tableName = 'sub_account_particulars';
        
        // Insert data into the specific table
        $this->db->table($tableName)->insert($data);
    
        // Get the ID of the newly inserted record
        $insertedID = $this->db->insertID();
        
        return $insertedID;
    }
    public function updateSubAccount($id, $data)
    {
        return $this->update($id, $data);
    }

    public function filterSubAccounts($ftrData){
        // print_r($ftrData);
        // die();
        $sql = "
        SELECT asub.`id` AS sub_id,  asub.`effectivity_year` ,asub.`code` AS sub_code,asub.`description` AS sub_desc,ac.`id` AS acc_id, ac.`code` AS acc_code, ac.`title` AS acc_desc
            FROM account_subcode asub
            LEFT JOIN accounts ac ON ac.`id` = asub.`account_id`


            WHERE asub.`deleted_at` IS NULL";
        
        if ($ftrData['ftr_by'] == 'effYear') {
            $sql.= " AND asub.`effectivity_year` = '".$ftrData['ftr_val']."'";
        } elseif ($ftrData['ftr_by'] == 'ac.`title`') {
            $sql.= " AND ".$ftrData['ftr_by']." LIKE '%".$ftrData['ftr_val']."%'";
        }

        $query = $this->db->query($sql);
        return $query->getResult();
    }

    public function mGetSubAccountDetails($id){
        $sql = "
        SELECT 	asub.id AS `sub_id`,
        asub.`effectivity_year`, 
        asub.`code` AS sub_code,
        asub.`description` AS sub_desc,
        ac.`id` AS acc_id,
        ac.`code` AS acc_code,
        ac.`title` AS acc_desc,
        asub.`type` AS sub_type,
        asub.`remarks`,
		CONCAT_WS(' ', created.firstname, COALESCE(created.middlename, ''), COALESCE(created.lastname, ''), COALESCE(created.suffix, '')) AS createdby,
		CONCAT_WS(' ', updated.firstname, COALESCE(updated.middlename, ''), COALESCE(updated.lastname, ''), COALESCE(updated.suffix, '')) AS updatedby,
		asub.`created_at`,asub.`updated_at`
        FROM account_subcode asub
        LEFT JOIN accounts ac ON ac.`id` = asub.`account_id`
            LEFT JOIN users AS created ON created.id = asub.`created_by`
            LEFT JOIN users AS updated ON updated.id = asub.`updated_by`
            WHERE asub.`deleted_by` IS NULL
            AND asub.`id`= '$id'";

            $query = $this->db->query($sql);
            return $query->getResult();


    }
    public function mGetSubAccountParticulars($id){
        $sql = "
        SELECT sap.`id`, sap.`account_subcode_id`, sap.`particular_id`,pars.`name` AS par_name,sap.`classifications`,cls.`name` AS className,sap.`new`, sap.`renewal`, sap.`closure`
        FROM sub_account_particulars sap
        LEFT JOIN particulars pars ON pars.`id` = sap.`particular_id`
        LEFT JOIN classifications cls ON cls.`id` = sap.`classifications`

        WHERE sap.`deleted_by` IS NULL AND sap.`account_subcode_id` = '$id'";

            $query = $this->db->query($sql);
            return $query->getResult();


    }

    public function filterClassifications($ftrData){

        $sql = "
        SELECT cfs.`id`, cfs.`class_code` AS `class_code`,cfs.`name` AS `class_name`
        FROM classifications cfs
        WHERE cfs.`deleted_at` IS NULL
        ";

        if ($ftrData['ftr_by'] == 'ALL') {
            // $sql.= " AND prls.`name` LIKE '%".$ftrData['ftr_val']."%'";
         }
         // elseif ($ftrData['ftr_by'] == 'particular_type_id') {
        //     $sql.= " AND prls.`particular_type_id` = '".$ftrData['ftr_val']."'";
        // }

        $query = $this->db->query($sql);
        return $query->getResult();
    }

    public function deleteAccount($id,$data)
    {
      
        $builder = $this->db->table('account_subcode');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function mdeletePartClass($id,$data)
    {
      
        $builder = $this->db->table('sub_account_particulars');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
}
