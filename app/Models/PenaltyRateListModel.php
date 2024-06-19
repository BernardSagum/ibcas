<?php

namespace App\Models;

use CodeIgniter\Model;

class PenaltyRateListModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'penalty_rate_list';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id','penalty_rate_id','fees_default_id','percent','surcharge','created_by','created_at','updated_by','updated_at','deleted_by','deleted_at'];

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


    public function save_penalty_list($table,$data){

        if($this->db->table($table)->insertBatch($data)){
            return true;
        } else {
            return false;
        }
    
    }
        public function updatePenRateslist($id, $data)
    {
      
        $builder = $this->db->table('penalty_rate_list');
        $builder->where('penalty_rate_id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }


    public function updateBatchpen($dataToUpdate, $index = null)
    {
        $builder = $this->db->table('penalty_rate_list');

        // Attempt to update
        $result = $builder->updateBatch($dataToUpdate, $index);
        
        // Check for a false result, indicating an error
        if ($result === false) {
            // An error occurred
            return false;
        }
        
        // At this point, $result is either 0 (no rows affected) or the number of rows affected.
        // Both of these cases can be considered a "successful" operation, so we return true.
        return true;
        
        
    }


    public function get_penalty_list_info($id){
        $sql = "SELECT  `id`,`penalty_rate_id`,
                        `fees_default_id`,
                        `percent`,
                        `surcharge`
                FROM `penalty_rate_list`
                WHERE `penalty_rate_id` = '$id'";
          $query = $this->db->query($sql);
          $result = $query->getResult();
          if ($result) {
              return $result;
          } else {
              return false;
          }
    }


}
