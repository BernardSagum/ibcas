<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Encryption;

class ParticularsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'particulars';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['particular_type_id','code','name','print_order','remarks','created_by','updated_by','deleted_by'];

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
    public function mGetParticularsList()
    {
        $query = $this->db->table('particulars AS prt')
        ->select('prt.id, prt.name,prt.code')
        ->where('prt.deleted_at', NULL);
        $result = $query->get()->getResult();
        return $result;
    }


    public function saveParticular($data)
    {
        return $this->save($data);
    }
    public function updateParticular($id, $data)
    {
        return $this->update($id, $data);
    }

    public function filterParticulars($ftrData){

        $sql = "
        SELECT prls.`id`,prls.`particular_type_id`,prls.`code`,prls.`name` AS particular_name,pt.`name` AS parTypeName
            FROM particulars prls
            LEFT JOIN particular_types pt ON pt.`id` = prls.`particular_type_id`
            WHERE deleted_by IS NULL 
        ";

        if ($ftrData['ftr_by'] == 'name') {
            $sql.= " AND prls.`name` LIKE '%".$ftrData['ftr_val']."%'";
        } elseif ($ftrData['ftr_by'] == 'particular_type_id') {
            $sql.= " AND prls.`particular_type_id` = '".$ftrData['ftr_val']."'";
        }

        $query = $this->db->query($sql);
        return $query->getResult();
    }
    
   
    public function mGetParticularInfo($id){
        $sql = "
        SELECT 
        prls.`id`,
        prls.`particular_type_id`,
        prls.`code`,
        prls.`name` AS particular_name,
        pt.`name` AS parTypeName,
        prls.`print_order`,
        prls.remarks,
        CONCAT_WS(' ', created.firstname, COALESCE(created.middlename, ''), COALESCE(created.lastname, ''), COALESCE(created.suffix, '')) AS createdby,
        CONCAT_WS(' ', updated.firstname, COALESCE(updated.middlename, ''), COALESCE(updated.lastname, ''), COALESCE(updated.suffix, '')) AS updatedby,
        prls.`created_at`,prls.`updated_at`

            FROM particulars prls
            LEFT JOIN particular_types pt ON pt.`id` = prls.`particular_type_id`
            LEFT JOIN users AS created ON created.id = prls.`created_by`
            LEFT JOIN users AS updated ON updated.id = prls.`updated_by`
            WHERE prls.`deleted_by` IS NULL 
            AND prls.`id`= '$id'";

            $query = $this->db->query($sql);
            return $query->getResult();


    }


    public function deleteParticular($id,$data)
    {
      
        $builder = $this->db->table('particulars');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
}
