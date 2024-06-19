<?php

namespace App\Models;

use CodeIgniter\Model;

class AssessmentSlipModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'assessment_slip_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['assessment_slip_id','year','installment','amount'];

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


    public function mCheckReferenceSeries(){
        // Get the current year
        $currentYear = date('Y');
        
        // Prepare the query to find the last series number for the current year
        $lastSeries = $this->db->table('applications')
                                ->select('MAX(reference_no) AS last_series')
                                ->like('reference_no', $currentYear, 'after')
                                ->get()
                                ->getRowArray();
        
        // Extract the numeric part of the last series and increment it
        $nextNumber = 1; // Default if no series is found for the current year
        if ($lastSeries && !empty($lastSeries['last_series'])) {
            $numberPart = (int)substr($lastSeries['last_series'], strpos($lastSeries['last_series'], '-') + 1);
            $nextNumber = $numberPart + 1;
        }
        
        // Format the next series number
        $nextSeries = $currentYear . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        
        return $nextSeries;
    }



    public function m_getBlpdNumber($id){
        return $this->select('applications.id')
                    ->from('applications')
                    ->join('assessment_slip', 'assessment_slip.application_id = applications.id', 'left')
                    ->where('assessment_slip.id', $id) // Apply the WHERE clause based on the assessment slip ID
                    ->groupBy('applications.blpdno') // Group the results by BLPD number to avoid duplicates
                    ->limit(1) // Limit the results to only one entry
                    ->first(); // Get the
    }

    public function mgetTaxCreditAmount($id) {
        $sql = "
        SELECT
            (aspdet.amount - aspay.amount) AS taxCredit
        FROM
            applications AS apps
        LEFT JOIN tax_payers AS tp ON tp.id = apps.tax_payer_id
        LEFT JOIN assessment_payments AS aspay ON aspay.application_id = apps.id
        LEFT JOIN assessment_payment_details AS aspdet ON aspdet.assessment_payment_id = aspay.id
        WHERE
            aspdet.type_of_payment_id = '2'
            AND apps.id = ?
        ";
        $db = \Config\Database::connect();
        $result = $db->query($sql, [$id])->getResult();
        
        return $result;
    }

    public function m_getTaxCreditValue($id) {
        $sql = "
        SELECT tc.`credit_amount`
        FROM tax_credit tc
        WHERE tc.`application_id` =  ?
        ";
        $db = \Config\Database::connect();
        $result = $db->query($sql, [$id])->getResult();
        
        return $result;
    }
    
    


    public function getAssessmentMOP($id){
        
            $query = $this->db->table('assessment_slip AS aslip')
              ->select('aslip.mode_of_payment_id,apps.tax_year')
              ->join('applications as apps' ,'apps.id = aslip.application_id','left')
              ->where('aslip.id',$id);
    
            $result = $query->get()->getResult();
            return $result;
    }
    public function UpdateAssessmentSlip($data,$id){
        
        $builder = $this->db->table('assessment_slip');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function UpdateApplication($data,$id){
        
        $builder = $this->db->table('applications');
        $builder->where('id', $id);
    
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function getInstallmentsLeft($id) {
      // echo $taxYear;
      // die();
      $sql = "
      SELECT asdet.`installment`
      FROM assessment_slip_details asdet
      WHERE asdet.`assessment_slip_id` = ?
      AND asdet.`installment` NOT IN (
        SELECT aspay.`installment`
        FROM assessment_payments aspay
        WHERE aspay.`assessment_slip_id` = ?
      )
      
      ";
      $db = \Config\Database::connect();
      $result = $db->query($sql, [$id,$id])->getResult();
      
      return $result;
  }
    public function m_getFeesTotal($id,$taxYear) {
      // echo $taxYear;
      // die();
      $sql = "
      SELECT SUM(fs.amount) AS tamount
      FROM assessment_slip aslip
      LEFT JOIN applications apps ON apps.`id` = aslip.`application_id`
      LEFT JOIN fees fs ON aslip.`application_id` = fs.`application_id`

      WHERE aslip.`id` = ?
      AND fs.`tax_year` = ?
      ";
      $db = \Config\Database::connect();
      $result = $db->query($sql, [$id, $taxYear])->getResult();
      
      return $result;
  }
    public function m_getFeesTotalSemiAnnual($id,$taxYear) {
      // echo $taxYear;
      // die();
      $sql = "
      SELECT SUM(fs.amount) AS tamount
      FROM assessment_slip aslip
      LEFT JOIN applications apps ON apps.`id` = aslip.`application_id`
      LEFT JOIN fees fs ON aslip.`application_id` = fs.`application_id`
      
      WHERE aslip.`id` = ?
      AND fs.`tax_year` = ?
      AND fs.fees_default_id ='1'
      
      
      
      UNION
      
      SELECT SUM(fs.amount) AS tamount
      FROM assessment_slip aslip
      LEFT JOIN applications apps ON apps.`id` = aslip.`application_id`
      LEFT JOIN fees fs ON aslip.`application_id` = fs.`application_id`
      
      WHERE aslip.`id` = ?
      AND fs.`tax_year` = ?
      AND fs.fees_default_id !='1'
      ";
      $db = \Config\Database::connect();
      $result = $db->query($sql, [$id, $taxYear,$id, $taxYear])->getResult();
      
      return $result;
  }
  public function m_getFeesTotalQuarterly($id,$taxYear) {
    // echo $taxYear;
    // die();
    $sql = "
    SELECT 
    aslip.id AS aslip_id,
    aslip.application_id AS app_id,
    fs.tax_year,
    SUM(fs.amount) AS tamount
FROM assessment_slip aslip
LEFT JOIN applications apps ON apps.id = aslip.application_id
LEFT JOIN fees fs ON aslip.application_id = fs.application_id
WHERE aslip.`id` = '212'
AND fs.`tax_year` = '2024'
AND fs.fees_default_id = '1'
GROUP BY aslip.id, aslip.application_id, fs.tax_year

UNION ALL

SELECT 
    TA.aslip_id,
    TA.app_id,
    TA.tax_year,
    CASE
        WHEN parts.n = 1 THEN FLOOR(TA.total_amount / 3)
        WHEN parts.n = 2 THEN FLOOR(TA.total_amount / 3)
        WHEN parts.n = 3 THEN TA.total_amount - 2 * FLOOR(TA.total_amount / 3)
    END AS tamount
FROM (
    SELECT 
        aslip.id AS aslip_id,
        aslip.application_id AS app_id,
        SUM(fs.amount) AS total_amount,
        fs.tax_year
    FROM assessment_slip AS aslip
    LEFT JOIN fees AS fs ON fs.application_id = aslip.application_id
   WHERE aslip.`id` = '212'
	AND fs.`tax_year` = '2024'
    AND fs.fees_default_id != '1'
    GROUP BY aslip.id, aslip.application_id, fs.tax_year
) AS TA
CROSS JOIN (SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3) AS parts;

    ";
    $db = \Config\Database::connect();
    $result = $db->query($sql, [$id, $taxYear,$id, $taxYear])->getResult();
    
    return $result;
}
  public function mFilterTaxCredit($data) {
    // print_r($data);
    // die();
    $sql = "
    SELECT 
    apps.`id` AS appId,
    aspay.`assessment_slip_id`,
    aspdet.`amount` AS amountPaidCheck,
    apps.`blpdno`,
    apptype.`name` AS application_type,
    apps.`business_name`,
    (aspdet.`amount` - aspay.`amount`) AS taxCredit,
    tc.`remarks` AS _status

FROM 
    assessment_payment_details aspdet
    LEFT JOIN assessment_payments aspay ON aspdet.`assessment_payment_id` = aspay.`id`
    LEFT JOIN applications apps ON apps.`id` = aspay.`application_id`
    LEFT JOIN tax_payers tp ON tp.id = apps.tax_payer_id
    LEFT JOIN application_type apptype ON apptype.id = apps.application_type_id
    LEFT JOIN tax_credit tc ON tc.`application_id` = apps.id

WHERE 
    aspdet.`type_of_payment_id` = '2' AND
    (aspdet.`amount` - aspay.`amount`) >= 0;

 ";

    if ($data['ftr_by'] != 'ALL') {
     $sql .= " AND ".$data['ftr_by']."='" .$data['ftr_value']."'" ;
    }
    


    $db = \Config\Database::connect();
    $result = $db->query($sql)->getResult();
    
    return $result;
}
  public function mFilterTaxBill($data) {
    // print_r($data);
    // die();
    $sql = "SELECT apps.`id`, apps.`business_name`, apps.`blpdno`, apptype.`name` AS applicationType,
            FORMAT_FULLNAME('', tp.`lastname`, tp.`firstname`, tp.`middlename`, tp.`suffix`, '') AS tax_payer_name

            FROM applications apps
            LEFT JOIN application_type apptype ON apptype.`id` = apps.`application_type_id`
            LEFT JOIN tax_payers tp ON tp.`id` = apps.`tax_payer_id`

            WHERE apps.`deleted_by` IS NULL";

            if ($data['ftr_by'] == 'blpdnum') {

                $sql .= " AND ".$data['ftr_by']."='" .$data['ftr_value']."'" ;

            } else if($data['ftr_by'] == 'business_name'){

                $sql .= " AND apps.`business_name` LIKE '%".$data['ftr_value']."%'";
            } else {
                
            }
    


    $db = \Config\Database::connect();
    $result = $db->query($sql)->getResult();
    
    return $result;
}
public function mGetCityTreasurer() {
  $sql = "
  SELECT CONCAT(u.`firstname`,' ',u.`middlename`,' ',u.`lastname`) AS cityTreas
  FROM users u
  LEFT JOIN positions pos ON pos.`id` = u.`position_id`
  LEFT JOIN departments depts ON depts.`id` = u.`department_id`
  WHERE depts.`deleted_at` IS NULL AND pos.`deleted_at` IS NULL AND u.`deleted_at` IS NULL
  AND depts.`name` = 'CTO' AND pos.`name` = 'City Treasurer'";
 
  $db = \Config\Database::connect();

  try {
      $result = $db->query($sql)->getResult();
      if (!empty($result)) {
          // Return the name of the City Treasurer directly as a string
          return $result[0]->cityTreas;
      } else {
          // Return an appropriate message or value if no City Treasurer is found
          return "No City Treasurer found.";
      }
  } catch (\Throwable $e) {
      // Handle error, log it, or return a default value/error message
      log_message('error', 'Error retrieving City Treasurer: '.$e->getMessage());
      return "Error retrieving City Treasurer.";
  }
}
public function mgetBusinessDetails($id){
  // Start by selecting from the applications table
  $query = $this->db->table('applications as apps')
                    ->select('apps.id, apps.blpdno, apps.business_name')
                    ->select("FORMAT_FULLNAME('', tp.lastname, tp.firstname, tp.middlename, tp.suffix, '') AS tax_payer_name,
                              FORMAT_ADDRESS(apps.brgy_id, apps.building_no, apps.unit_no, apps.street, apps.subdivision, apps.block, apps.lot, apps.phase) AS taxpayer_address")
                    ->join('tax_payers as tp', 'tp.id = apps.tax_payer_id', 'left');

  // Here you would join with assessment_payment_details and assessment_payments
  // Note: This is conceptual. You'd need to adjust field names and conditions based on your actual database schema.
  $query = $query->join('assessment_payments as aspay', 'aspay.application_id = apps.id', 'left')
                 ->join('assessment_payment_details as aspdet', 'aspdet.assessment_payment_id = aspay.id', 'left')
                 ->select('aspay.assessment_slip_id, aspdet.amount AS amountPaidCheck,
                           CONCAT(aspdet.amount - aspay.amount) AS taxCredit')
                 ->where('aspdet.type_of_payment_id', '2');

  // Filter by the specific application ID
  $query = $query->where('apps.id', $id);

  // Execute the query and return the result
  return $query->get()->getRowArray();
}



public function mCheckTaxCredSeries(){
  // Get the current year
  $currentYear = substr(date('y'),-2);
  
  // Prepare the query to find the last series number for the current year
  $lastSeries = $this->db->table('tax_credit')
                          ->select('MAX(series) AS last_series')
                          ->like('series', $currentYear, 'after')
                          ->get()
                          ->getRowArray();
  
  // Extract the numeric part of the last series and increment it
  $nextNumber = 1; // Default if no series is found for the current year
  if ($lastSeries && !empty($lastSeries['last_series'])) {
      $numberPart = (int)substr($lastSeries['last_series'], strpos($lastSeries['last_series'], '-') + 1);
      $nextNumber = $numberPart + 1;
  }
  
  // Format the next series number
  $nextSeries = $currentYear . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
  
  return $nextSeries;
}
public function mSaveTaxCredit($data){
  $builder = $this->db->table('tax_credit');
  $builder->insert($data);
  $insertID = $this->db->insertID();
  if (!$insertID){
      return false;
  }else {
      return true;
  }
}
}