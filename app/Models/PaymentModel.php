<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'payment_schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['year','quarter_no'];

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


    public function saveAssessmentPayment($data){
        $builder = $this->db->table('assessment_payments');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            $dataOr = array(
                'status' => 'USED',
            );
            $builder = $this->db->table('accountable_form_or_series');
            $builder->where('id', $data['or_series_id']);
            
            if ($builder->update($dataOr)) {

                return $insertID;
                
            } else {
                return false;
            }
        }
    }

    public function SaveKPM($data,$id){
        $this->db->table('kpms')
            ->set($data)
            ->where('application_id', $id)
            ->where('kpm_default_id', '14')
            ->update();
    }


    public function savePaymentDetails($data){
        $builder = $this->db->table('assessment_payment_details');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            return $insertID;
        }
    }
    public function savePaymentListsmsc($data){
        $builder = $this->db->table('miscellaneous_payment_list');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            return $insertID;
        }
    }
    public function savePaymentDetailsmsc($data){
        $builder = $this->db->table('miscellaneous_payment_details');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            return $insertID;
        }
    }
    public function m_save_msc_payments($data){
        $builder = $this->db->table('miscellaneous_payments');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            $dataOr = array(
                'status' => 'USED',
            );
            $builder = $this->db->table('accountable_form_or_series');
            $builder->where('id', $data['orno_id']);
            
            if ($builder->update($dataOr)) {
                return $insertID;
            } else {
                return false;
            }
        }
    }
    public function saveAssessmentSlipDetails($data){
        $builder = $this->db->table('assessment_slip_details');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if (!$insertID){
            return false;
        }else {
            return $insertID;
        }
    }

    public function m_getBankNames(){
        $query = $this->db->table('banks AS bnk')
          ->select('bnk.id,bnk.name,bnk.shortname');
          $result = $query->get()->getResult();
        return $result;
    }
    public function getBusinessDetails($id){
        $query = $this->db->table('applications AS apps')
              ->select('apps.id, apps.blpdno, apps.business_name, ' .
                       'FORMAT_FULLNAME("", tp.lastname, tp.firstname, tp.middlename, tp.suffix, "") AS tax_payer_name')
              ->join('tax_payers tp', 'apps.tax_payer_id = tp.id', 'left') // Join with the tax_payers table
              ->where('apps.deleted_by', NULL)
              ->where('apps.id', $id);
    
        $result = $query->get()->getResult();
        return $result;
    }
    
    public function m_getMiscType(){
        $query = $this->db->table('miscellaneous AS msc')
          ->select('msc.id,msc.name,msc.amount')
          ->where('msc.deleted_by', NULL);
          $result = $query->get()->getResult();
        return $result;
    }
    public function m_getMiscTypeSpec($id){
        $query = $this->db->table('miscellaneous AS msc')
          ->select('msc.id,msc.name,msc.amount')
          ->where('msc.deleted_by', NULL)
          ->where('msc.id', $id);
          $result = $query->get()->getResult();
        return $result;
    }
    public function m_getPaymentType(){
        $query = $this->db->table('payment_type AS pt')
          ->select('pt.id,pt.name');
          $result = $query->get()->getResult();
        return $result;
    }
   

    public function m_getListOfFees($id){
        $sql = "SELECT `fs`.`application_id`,`fs`.`tax_year`,`fs`.`fees_default_id`,`fs`.`amount`,fs.`remarks`
        FROM `fees` AS fs
        LEFT JOIN `assessment_slip` AS `aslip` ON `aslip`.`application_id` = `fs`.`application_id`
        WHERE `aslip`.`id` = '$id' AND `aslip`.`deleted_by` IS NULL";
        
        $query = $this->db->query($sql);

        $result = $query->getResult();
        return $result;
    }
    public function m_getModeOfPayment(){
        $sql = "SELECT  `mode_of_payments`.`id`,
        `mode_of_payments`.`description`,`mode_of_payments`.`number_of_installment` AS `numinst`
        FROM `mode_of_payments`
        WHERE `mode_of_payments`.`deleted_by` IS NULL";
        
        $query = $this->db->query($sql);

        $result = $query->getResult();
        return $result;
    }
   
    public function filterData($data)
    {
        $sql = "
        SELECT
            aslip.`id`,
            apps.`blpdno`,
            apptype.`name` AS application_type,
            apps.`business_name`,
            FORMAT_FULLNAME('', tp.`lastname`, tp.`firstname`, tp.`middlename`, tp.`suffix`, '') AS tax_payer_name,
            FORMAT_ADDRESS(apps.brgy_id, apps.building_no, apps.unit_no, apps.street, apps.subdivision, apps.block, apps.lot, apps.phase) AS taxpayer_address,
            CASE
                WHEN aslip.`isfullypaid` = 'F' THEN '_fullyPaid'
                WHEN aslip.`isfullypaid` = 'P' THEN
                    CASE
                        WHEN aslip.`mode_of_payment_id` = '2' THEN
                            CASE
                                WHEN (SELECT ps.`extention_date` FROM payment_schedules ps WHERE CURDATE() BETWEEN ps.`from` AND ps.`to`) >= CURDATE() THEN '_topay'
                                WHEN (SELECT ps.`extention_date` FROM payment_schedules ps WHERE CURDATE() BETWEEN ps.`from` AND ps.`to`) < CURDATE() THEN '_paidpatially'
                            END
                        WHEN aslip.`mode_of_payment_id` = '3' THEN
                            CASE
                                WHEN (SELECT 
                                    CASE
                                        WHEN CURDATE() BETWEEN CONCAT(YEAR(CURDATE()), '-01-01') AND CONCAT(YEAR(CURDATE()), '-06-30') THEN
                                            (SELECT ps.`extention_date` FROM payment_schedules ps WHERE ps.`quarter_no` = 1 AND YEAR(ps.`from`) = YEAR(CURDATE()) ORDER BY ps.`from` ASC LIMIT 1)
                                        WHEN CURDATE() BETWEEN CONCAT(YEAR(CURDATE()), '-07-01') AND CONCAT(YEAR(CURDATE()), '-12-31') THEN
                                            (SELECT ps.`extention_date` FROM payment_schedules ps WHERE ps.`quarter_no` = 3 AND YEAR(ps.`from`) = YEAR(CURDATE()) ORDER BY ps.`from` ASC LIMIT 1)
                                    END
                                ) >= CURDATE() THEN '_topay'
                                WHEN (SELECT 
                                    CASE
                                        WHEN CURDATE() BETWEEN CONCAT(YEAR(CURDATE()), '-01-01') AND CONCAT(YEAR(CURDATE()), '-06-30') THEN
                                            (SELECT ps.`extention_date` FROM payment_schedules ps WHERE ps.`quarter_no` = 1 AND YEAR(ps.`from`) = YEAR(CURDATE()) ORDER BY ps.`from` ASC LIMIT 1)
                                        WHEN CURDATE() BETWEEN CONCAT(YEAR(CURDATE()), '-07-01') AND CONCAT(YEAR(CURDATE()), '-12-31') THEN
                                            (SELECT ps.`extention_date` FROM payment_schedules ps WHERE ps.`quarter_no` = 3 AND YEAR(ps.`from`) = YEAR(CURDATE()) ORDER BY ps.`from` ASC LIMIT 1)
                                    END
                                ) < CURDATE() THEN '_paidpatially'
                            END
                        ELSE 'OTHER PAYMENT STATUS'
                    END
                ELSE '_topay'
            END AS 'PaymentStatus'
        FROM assessment_slip aslip
        LEFT JOIN assessment_slip_details asdet ON asdet.`assessment_slip_id` = aslip.`id`
        LEFT JOIN applications apps ON apps.`id` = aslip.`application_id`
        LEFT JOIN tax_payers tp ON apps.`tax_payer_id` = tp.`id` 
        LEFT JOIN payment_schedules paysched ON paysched.`year` = asdet.`year`
        LEFT JOIN application_type apptype ON apptype.`id` = apps.`application_type_id`
        
        ";


        if (isset($data['ftr_by']) && $data['ftr_by'] != 'mop') {
            $sql .= "WHERE aslip.`isfullypaid` IS NULL";
            if ($data['ftr_by'] == 'apps.blpdno') {
                        $sql .= " AND ".$data['ftr_by']." = '".$data['ftr_val']."'";
                    } else if ($data['ftr_by'] == 'apps.business_name') {
                        $sql .= " AND ".$data['ftr_by']." LIKE '%".$data['ftr_val']."%'";
                    } else if ($data['ftr_by'] == 'tax_payer_name') {
                        $sql .= " AND 
                                CONCAT(COALESCE(tp.firstname, ''), ' ', COALESCE(tp.middlename, ''), ' ', COALESCE(tp.lastname, ''), ' ', COALESCE(tp.suffix, ''))               
                         LIKE '%".$data['ftr_val']."%'";
                    } else {
                        
                    }
           

        } else {
            
            $sql .= "WHERE aslip.`mode_of_payment_id` ='".$data["ftr_val"]."'";
            // if ($data['ftr_val'] == '_topay') {
            //     $sql .= "WHERE aslip.`isfullypaid` IS NULL";
            // } elseif ($data['ftr_val'] == '_partiallyPaid') {
            //     $sql .= "WHERE aslip.`isfullypaid` = 'P'";
            // } elseif ($data['ftr_val'] == '_fullyPaid') {
            //     $sql .= "WHERE aslip.`isfullypaid` = 'F'";
            // }
        }

 

        $sql .= " GROUP BY aslip.`id`";

        $query = $this->db->query($sql);
        return $query->getResult();
    }
    

public function checkIfPaid($id) {
    $sql = "
    SELECT aspay.id,  CASE
    WHEN aslip.`isfullypaid` = 'F' THEN '_fullyPaid'
    WHEN aslip.`isfullypaid` = 'P' THEN
        CASE
            WHEN aslip.`mode_of_payment_id` = '2' THEN
                CASE
                    WHEN (SELECT ps.`extention_date` FROM payment_schedules ps WHERE CURDATE() BETWEEN ps.`from` AND ps.`to`) >= CURDATE() THEN '_topay'
                    WHEN (SELECT ps.`extention_date` FROM payment_schedules ps WHERE CURDATE() BETWEEN ps.`from` AND ps.`to`) < CURDATE() THEN '_paidpatially'
                END
            WHEN aslip.`mode_of_payment_id` = '3' THEN
                CASE
                    WHEN (SELECT 
                        CASE
                            WHEN CURDATE() BETWEEN CONCAT(YEAR(CURDATE()), '-01-01') AND CONCAT(YEAR(CURDATE()), '-06-30') THEN
                                (SELECT ps.`extention_date` FROM payment_schedules ps WHERE ps.`quarter_no` = 1 AND YEAR(ps.`from`) = YEAR(CURDATE()) ORDER BY ps.`from` ASC LIMIT 1)
                            WHEN CURDATE() BETWEEN CONCAT(YEAR(CURDATE()), '-07-01') AND CONCAT(YEAR(CURDATE()), '-12-31') THEN
                                (SELECT ps.`extention_date` FROM payment_schedules ps WHERE ps.`quarter_no` = 3 AND YEAR(ps.`from`) = YEAR(CURDATE()) ORDER BY ps.`from` ASC LIMIT 1)
                        END
                    ) >= CURDATE() THEN '_topay'
                    WHEN (SELECT 
                        CASE
                            WHEN CURDATE() BETWEEN CONCAT(YEAR(CURDATE()), '-01-01') AND CONCAT(YEAR(CURDATE()), '-06-30') THEN
                                (SELECT ps.`extention_date` FROM payment_schedules ps WHERE ps.`quarter_no` = 1 AND YEAR(ps.`from`) = YEAR(CURDATE()) ORDER BY ps.`from` ASC LIMIT 1)
                            WHEN CURDATE() BETWEEN CONCAT(YEAR(CURDATE()), '-07-01') AND CONCAT(YEAR(CURDATE()), '-12-31') THEN
                                (SELECT ps.`extention_date` FROM payment_schedules ps WHERE ps.`quarter_no` = 3 AND YEAR(ps.`from`) = YEAR(CURDATE()) ORDER BY ps.`from` ASC LIMIT 1)
                        END
                    ) < CURDATE() THEN '_paidpatially'
                END
            ELSE 'OTHER PAYMENT STATUS'
        END
    ELSE '_topay'
END AS 'PaymentStatus'
FROM assessment_payments aspay
LEFT JOIN assessment_slip aslip ON aslip.`id` = aspay.`assessment_slip_id`

WHERE aspay.`assessment_slip_id` = '".$id."'

ORDER BY aspay.id DESC LIMIT 1
 ";

    
    


    $db = \Config\Database::connect();
    $result = $db->query($sql)->getResult();
    
    return $result;
}
public function mCheckForInstallment($id) {
    $query = $this->db->table('assessment_slip_details AS asdet')
    ->select('asdet.id')
    ->where('asdet.assessment_slip_id',$id);
    
    $result = $query->get()->getResult();
    return $result;
}



    public function filterDataBusiness($data) {
        $query = $this->db->table('applications AS apps')
    ->select('apps.id, , COALESCE(apps.blpdno, "") AS blpdno, apps.business_name, 
                FORMAT_FULLNAME("", tp.lastname, tp.firstname, tp.middlename, tp.suffix, "") AS tax_payer_name,
                FORMAT_ADDRESS(apps.brgy_id, apps.building_no, apps.unit_no, apps.street, apps.subdivision, apps.block, apps.lot, apps.phase) AS taxpayer_address')
    ->join('tax_payers AS tp', 'tp.id = apps.tax_payer_id', 'left')
    // Adding the new WHERE clause
    ->where('apps.deleted_by', null);

// Assuming you might still want the filtering condition from the old function
if ($data['ftr_by'] == 'apps.blpdno') {
    $query->where($data['ftr_by'], $data['ftr_val']);
} else if ($data['ftr_by'] == 'apps.business_name'){
    $query->like($data['ftr_by'], $data['ftr_val']);
} else if ($data['ftr_by'] == 'tax_payer_name'){
    $query->like(
        'CONCAT(COALESCE(tp.firstname, ""), " ", COALESCE(tp.middlename, ""), " ", COALESCE(tp.lastname, ""), " ", COALESCE(tp.suffix, ""))',
        $data['ftr_val']
    );
} 
$query->groupBy('apps.blpdno');
$result = $query->get()->getResult();
return $result;

}
    public function m_getPaymentInfo($id) {
        $query = $this->db->table('assessment_slip AS aslip')
          ->select('aslip.id, aslip.application_id, COALESCE(apps.blpdno, "") AS blpdno, apps.business_name, 
                    FORMAT_FULLNAME("", tp.lastname, tp.firstname, tp.middlename, tp.suffix, "") AS tax_payer_name,
                    FORMAT_ADDRESS(apps.brgy_id, apps.building_no, apps.unit_no, apps.street, apps.subdivision, apps.block, apps.lot, apps.phase) AS taxpayer_address,
                    aslip.mode_of_payment_id AS modeofpayment')
          ->join('applications AS apps', 'apps.id = aslip.application_id', 'left')
          ->join('tax_payers AS tp', 'tp.id = apps.tax_payer_id', 'left');
          $query->where('aslip.id', $id);

        $result = $query->get()->getResult();
        // print_r($result);
        // die();
        return $result;
    }


    public function m_checkOrAvail($ornumber,$user_id){
       
   
        $assignedQuery = $this->db->table('accountable_form_assigned_receipts afar')
            ->select('afos.id, afos.ornum')
            ->join('accountable_forms af', 'af.id = afar.accountable_form_id', 'left')
            ->join('accountable_form_or_series afos', 'afos.accountable_form_id = af.id', 'left')
            ->where('afar.assigned', $user_id)
            ->where('afos.ornum', $ornumber)
           
            ->limit(1);
        
        $assignedResult = $assignedQuery->get()->getRowArray();
        
        if (empty($assignedResult)) {
            return false;
        } else {
        // Query to check if the OR number is available (not void and not used)
        $availabilityQuery = $this->db->table('accountable_form_or_series afos')
            ->select('afos.id, afos.ornum')
            ->join('assessment_payments ap', 'ap.or_series_id = afos.id', 'left')
            ->where('afos.id', $assignedResult['id'])
            ->where('afos.void', 'N')
            ->where('afos.status', 'AVAIL')
            ->where('ap.id', null, false) // To check if the OR number is not used in any payment
            ->limit(1);
        
        $availabilityResult = $availabilityQuery->get()->getRowArray();
        
        if (!empty($availabilityResult)) {
            return $availabilityResult;
        } else {
            return false;
        }
    }
        
    }
    public function m_getLastOrNumber($user_id){
        
        $query = $this->db->table('accountable_form_assigned_receipts afar')
        ->select('afos.id, afos.ornum AS next_ornum')
        ->join('accountable_forms af', 'af.id = afar.accountable_form_id', 'left')
        ->join('accountable_form_or_series afos', 'afos.accountable_form_id = af.id', 'left')
        ->join('assessment_payments ap', 'ap.or_series_id = afos.id', 'left')
        ->where('afar.assigned', $user_id)
        ->where('ap.id', null, false) // Use NULL as the second parameter and set the third parameter to false to generate "IS NULL"
        ->where('afos.status', 'AVAIL')
        ->limit(1);
        $result = $query->get()->getRowArray();
        return $result;

    }
    
    public function m_getFeesTotal($id) {
        $query = $this->db->table('assessment_slip AS aslip')
          ->select('aslip.id, aslip.application_id, SUM(fs.amount) AS tamount,fs.tax_year')
          ->join('fees AS fs', 'fs.application_id = aslip.application_id', 'left');
        //   ->join('tax_payers AS tp', 'tp.id = apps.tax_payer_id', 'left');
          $query->where('aslip.id', $id);
          $query->where('fs.tax_year', date('Y'));

        $result = $query->get()->getResult();
        return $result;
    }
    public function m_getFeesTotalMOP_semi($id) {
        $query = $this->db->table('assessment_slip AS aslip')
          ->select('aslip.id, aslip.application_id, SUM(fs.amount) AS tamount,fs.tax_year')
          ->join('fees AS fs', 'fs.application_id = aslip.application_id', 'left');
        //   ->join('tax_payers AS tp', 'tp.id = apps.tax_payer_id', 'left');
          $query->where('aslip.id', $id);
          $query->where('fs.tax_year', date('Y'));
          $query->where('fs.fees_default_id','1');


        $result = $query->get()->getResult();
        return $result;
    }
    public function m_getFeesTotalMOP_others($id) {
        $query = $this->db->table('assessment_slip AS aslip')
          ->select('aslip.id, aslip.application_id, SUM(fs.amount) AS tamount,fs.tax_year')
          ->join('fees AS fs', 'fs.application_id = aslip.application_id', 'left');
        //   ->join('tax_payers AS tp', 'tp.id = apps.tax_payer_id', 'left');
          $query->where('aslip.id', $id);
          $query->where('fs.tax_year', date('Y'));
          $query->where('fs.fees_default_id !=','1');


        $result = $query->get()->getResult();
        return $result;
    }
public function m_getFeesTotalMOP_quarterly($id) {
       // Prepare the SQL query with CTE and CROSS JOIN
$sql = "
WITH TotalAmount AS (
    SELECT 
        aslip.id AS aslip_id,
        aslip.application_id AS app_id,
        SUM(fs.amount) AS total_amount,
        fs.tax_year
    FROM assessment_slip AS aslip
    LEFT JOIN fees AS fs ON fs.application_id = aslip.application_id
    WHERE aslip.id = ?
    AND fs.tax_year = ?
    AND fs.fees_default_id != '1'
    GROUP BY aslip.id, aslip.application_id, fs.tax_year
)

SELECT 
    aslip_id,
    app_id,
    tax_year,
    CASE
        WHEN parts.n = 1 THEN FLOOR(total_amount / 3)
        WHEN parts.n = 2 THEN FLOOR(total_amount / 3)
        WHEN parts.n = 3 THEN total_amount - 2 * FLOOR(total_amount / 3)
    END AS tamount
FROM TotalAmount
CROSS JOIN (SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3) AS parts
";
$db = \Config\Database::connect();
$result = $db->query($sql, [$id, date('Y')])->getResult();

return $result;
    }
    


}