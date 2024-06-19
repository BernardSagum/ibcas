<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Encryption;

class CollectorsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'collectors';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id','accountable_form_id','barangay_id','tin_no','deputized_collector','field_collector'];

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


    public function saveCollector($collsaveArr)
    {
        // Encrypt the 'tin_no' field using AES_ENCRYPT
        $config = new Encryption();
        $secretKey = $config->secretKey;

        $sql = "INSERT INTO collectors (user_id, barangay_id, accountable_officer_id, address, tin_no, contact_number, email, position_id, deputized_collector, field_collector,created_by) ";
        $sql .= "VALUES (
            '{$collsaveArr['user_id']}', 
            '{$collsaveArr['barangay_id']}', 
            '{$collsaveArr['accountable_officer_id']}', 
            '{$collsaveArr['address']}', 
            AES_ENCRYPT('{$collsaveArr['tin_no']}', '{$secretKey}'),
            '{$collsaveArr['contact_number']}', 
            '{$collsaveArr['email']}', 
            '{$collsaveArr['position_id']}',
            '{$collsaveArr['deputized_collector']}',
            '{$collsaveArr['field_collector']}',
            '{$collsaveArr['created_by']}'
        )";


        // Execute the custom SQL query
        $query = $this->db->query($sql);

        if ($query === false) {
            // Handle query execution error here
            return false;
        }

        return true;
    }

    public function updateCollector($collsaveArr, $collectorId)
{
    // Encrypt the 'tin_no' field using AES_ENCRYPT
    $config = new Encryption();
    $secretKey = $config->secretKey;

    // Build the SQL query for updating the collector record
    $sql = "UPDATE collectors SET
        user_id = '{$collsaveArr['user_id']}', 
        barangay_id = '{$collsaveArr['barangay_id']}', 
        accountable_officer_id = '{$collsaveArr['accountable_officer_id']}', 
        address = '{$collsaveArr['address']}', 
        tin_no = AES_ENCRYPT('{$collsaveArr['tin_no']}', '{$secretKey}'),
        contact_number = '{$collsaveArr['contact_number']}', 
        email = '{$collsaveArr['email']}', 
        position_id = '{$collsaveArr['position_id']}',
        deputized_collector = '{$collsaveArr['deputized_collector']}',
        field_collector = '{$collsaveArr['field_collector']}',
        updated_by = '{$collsaveArr['updated_by']}'
        WHERE id = {$collectorId}";

    // Execute the custom SQL query for updating
    $query = $this->db->query($sql);

    if ($query === false) {
        // Handle query execution error here
        return false;
    }

    return true;
}


    public function m_getCollectorFunds($user_id){
        $query = $this->db->table('funds fds')
          ->select('fds.id, fds.name, af.id AS accountable_form_id, afar.assigned')
          ->join('accountable_forms af', 'af.fund_id = fds.id', 'left')
          ->join('accountable_form_assigned_receipts afar', 'afar.accountable_form_id = af.id', 'left')
          ->where('afar.assigned', $user_id); // replace $assigned with the variable holding the assigned ID

$result = $query->get()->getResult();
// Uncomment the next lines to debug the output
// print_r($result);
// die();
return $result;

    }
    
    public function mGetLastORBasedonFunds($user_id,$fund_id){
            
        $query = $this->db->table('accountable_form_assigned_receipts afar')
        ->select('afos.id, afos.ornum AS next_ornum')
        ->join('accountable_forms af', 'af.id = afar.accountable_form_id', 'left')
        ->join('accountable_form_or_series afos', 'afos.accountable_form_id = af.id', 'left')
        ->join('assessment_payments ap', 'ap.or_series_id = afos.id', 'left')
        ->where('afar.assigned', $user_id) // replace $user_id with your variable
        ->where('af.fund_id', $fund_id) // replace $fund_id with your variable
        ->where('ap.id', null, false) // Generates "IS NULL"
        ->where('afos.status', 'AVAIL')
        ->limit(1);

        $result = $query->get()->getRowArray();
        return $result;
    }
    

    // public function save_collector_form($data){

    //     $config = new Encryption();
    //     $secretKey = $config->secretKey;
    //     // dd($secretKey);

    //     $builder = $this->db->table('collectors');
    //     $data['tin_no'] = openssl_encrypt($data['tin_no'], 'des-ecb', $secretKey);

    //     $builder->insert($data);
    //     $insertID = $this->db->insertID();
    //     if (!$insertID){
    //         return false;
    //     }else {
    //         return true;
    //     }
    // }

    public function decrypt($encryptedData) {
        $config = new Encryption();
        $secretKey = $config->secretKey;
    
        $decryptedTin = openssl_decrypt($encryptedData, 'des-ecb', $secretKey);
        
        return $decryptedTin;
    }
    
    public function filterData($data) {
        $config = new Encryption();
        $secretKey = $config->secretKey;
        $sql = "
            SELECT 
                collectors.id, collectors.user_id, collectors.accountable_officer_id, 
                collectors.barangay_id, collectors.email,collectors.contact_number,
                AES_DECRYPT(tin_no, '{$secretKey}') AS tin_no,
                collectors.deputized_collector, collectors.field_collector, 
                CONCAT_WS(' ', accoff.firstname, COALESCE(accoff.middlename, ''), COALESCE(accoff.lastname, ''), COALESCE(accoff.suffix, '')) as accountable_person, 
                CONCAT_WS(' ', colname.firstname, COALESCE(colname.middlename, ''), COALESCE(colname.lastname, ''), COALESCE(colname.suffix, '')) as collectorname 
            FROM collectors
            LEFT JOIN accountable_form_officers AS accofficer ON accofficer.id = collectors.accountable_officer_id

            LEFT JOIN users AS accoff ON accoff.id = accofficer.user_id
            LEFT JOIN users as colname ON colname.id = collectors.user_id
            WHERE collectors.deleted_by IS NULL";
    
        if (!empty($data['ftr_by']) && !empty($data['ftr_val'])) {
            if ($data['ftr_by'] == 'collector_name') {
                $sql .= " HAVING collectorname  LIKE '%" . $data['ftr_val'] . "%'";
            } else if ($data['ftr_by'] == 'officer_name'){
                $sql .= " HAVING accountable_person LIKE '%" . $data['ftr_val'] . "%'";
            }
        }
    
        // Execute the SQL query using your database connection
        $result = $this->db->query($sql)->getResult();
    
        return $result;
    }
    
    public function m_get_collectorInfo($id) {
        $config = new Encryption();
        $secretKey = $config->secretKey;
        $sql = "
            SELECT 
                collectors.id, collectors.user_id, collectors.accountable_officer_id, 
                collectors.barangay_id,collectors.address, collectors.email,
                collectors.contact_number,`collectors`.`position_id`,`positions`.`name` AS `position`,
                collectors.created_at,collectors.updated_at,collectors.created_by,collectors.updated_by,
                AES_DECRYPT(tin_no, '{$secretKey}') AS tin_no,
                collectors.deputized_collector, collectors.field_collector, 
                CONCAT_WS(' ', accoff.firstname, COALESCE(accoff.middlename, ''), COALESCE(accoff.lastname, ''), COALESCE(accoff.suffix, '')) AS accountable_person, 
                CONCAT_WS(' ', colname.firstname, COALESCE(colname.middlename, ''), COALESCE(colname.lastname, ''), COALESCE(colname.suffix, '')) AS collectorname,
                CONCAT_WS(' ', created.firstname, COALESCE(created.middlename, ''), COALESCE(created.lastname, ''), COALESCE(created.suffix, '')) AS createdby,
                 CONCAT_WS(' ', updated.firstname, COALESCE(updated.middlename, ''), COALESCE(updated.lastname, ''), COALESCE(updated.suffix, '')) AS updatedby 
            FROM collectors
            LEFT JOIN accountable_form_officers AS accofficer ON accofficer.id = collectors.accountable_officer_id

            LEFT JOIN users AS accoff ON accoff.id = accofficer.user_id
            LEFT JOIN users AS colname ON colname.id = collectors.user_id
            LEFT JOIN users AS created ON created.id = collectors.created_by
            LEFT JOIN users AS updated ON updated.id = collectors.updated_by
            LEFT JOIN `positions` ON collectors.`position_id` = positions.`id`
            WHERE collectors.deleted_by IS NULL AND collectors.id = ".$id;
    
       
    
        // Execute the SQL query using your database connection
        $result = $this->db->query($sql)->getResult();
    
        return $result;
    }

    public function deleteColl($data,$id){
        
        $builder = $this->db->table('collectors');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }




    public function getAllOfficers(){
        $query = $this->db->table('accountable_form_officers')
        ->select('accountable_form_officers.id,users.id as UserId,users.lastname,users.firstname,users.middlename,users.suffix')
        ->join('users', 'users.id = accountable_form_officers.user_id', 'left')
        ->where('users.department_id', '5')
        ->get();

    $result = $query->getResult();

    if ($result) {
        return $result;
    } else {
        return false;
    }
    }
    public function m_getAllpositions(){
        $query = $this->db->table('positions')
        ->select('positions.id,positions.name,positions.remarks')
        ->where('positions.deleted_by', null)
        ->get();

    $result = $query->getResult();

    if ($result) {
        return $result;
    } else {
        return false;
    }
    }
    public function m_getAllbarangay(){
        $query = $this->db->table('barangays')
        ->select('barangays.id,barangays.name')
        ->where('barangays.deleted_by', null)
        ->get();

    $result = $query->getResult();

    if ($result) {
        return $result;
    } else {
        return false;
    }
    }



}
