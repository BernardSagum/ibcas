<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Encryption;
class ReportManagementModal extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'applications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;


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

    public function filterData($ftrdata){
        $query = $this->db->table('applications AS apps')
        ->select('apps.id, COALESCE(apps.blpdno, "") AS blpdno, apps.business_name, 
                    FORMAT_FULLNAME("", tp.lastname, tp.firstname, tp.middlename, tp.suffix, "") AS tax_payer_name,
                    FORMAT_ADDRESS(apps.brgy_id, apps.building_no, apps.unit_no, apps.street, apps.subdivision, apps.block, apps.lot, apps.phase) AS taxpayer_address')
        ->join('tax_payers AS tp', 'tp.id = apps.tax_payer_id', 'left')
        // Adding the new WHERE clause
        ->where('apps.deleted_by', null);
    
    // Assuming you might still want the filtering condition from the old function
        if ($ftrdata['ftr_by'] == 'apps.blpdno') {
            $query->where($ftrdata['ftr_by'], $ftrdata['ftr_val']);
        }
        else if ($ftrdata['ftr_by'] == 'apps.application_type_id'){
            $query->where($ftrdata['ftr_by'], $ftrdata['ftr_val']);
            // $query->like($ftrdata['ftr_by'], $ftrdata['ftr_val']);
        } else if ($ftrdata['ftr_by'] == 'status'){
            $query->where('apps.active', $ftrdata['ftr_val']);
        }
        // else if ($data['ftr_by'] == 'tax_payer_name'){
        //     $query->like(
        //         'CONCAT(COALESCE(tp.firstname, ""), " ", COALESCE(tp.middlename, ""), " ", COALESCE(tp.lastname, ""), " ", COALESCE(tp.suffix, ""))',
        //         $data['ftr_val']
        //     );
        // } 
        $query->groupBy('apps.blpdno');
        $query->orderBy('apps.id','ASC');
        $result = $query->get()->getResult();
        return $result;
    
 
    }
    public function filterDataORLogbook($ftrdata) {
        // print_r($ftrdata);
        $sql = "
            (
                SELECT 
                apps.blpdno AS 'BUSINESS CONTROL NO',
                FORMAT_FULLNAME('', tp.lastname, tp.firstname, tp.middlename, tp.suffix, '') AS 'OWNER',
                apps.business_name AS 'NAME OF BUSINESS',
                cls.name AS NATURE,
                FORMAT_ADDRESS(apps.brgy_id, apps.building_no, apps.unit_no, apps.street, apps.subdivision, apps.block, apps.lot, apps.phase) AS ADDRESS,
                atype.code AS 'APPLICATION STATUS',
                asdet.year AS 'TAX YEAR',
                '' AS 'APPLICATION PAYMENT',
                mop.description AS 'MODE OF PAYMENT',
                lob.capitalization AS 'GROSS RECEIPTS/ CAPITAL',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '1' AND tax_year = asdet.year) AS 'BUSINESS TAX',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '30' AND tax_year = asdet.year) AS 'BUSINESS CLOSURE',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '2' AND tax_year = asdet.year) AS 'MAYORS PERMIT',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '3' AND tax_year = asdet.year) AS 'SANITARY INSPECTION FEE',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '4' AND tax_year = asdet.year) AS 'ENV. MGMT. FEE',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '5' AND tax_year = asdet.year) AS 'HEALTH CERTIFICATE',
                'FIRE SAFETY INSP FEE' AS 'FIRE SAFETY INSP. FEE',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '27' AND tax_year = asdet.year) AS 'FIRE CODE FEE',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '7' AND tax_year = asdet.year)+
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '8' AND tax_year = asdet.year) AS 'METAL PLATE/ STICKER',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '9' AND tax_year = asdet.year) AS 'LBOD CLEARANCE FEE',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '10' AND tax_year = asdet.year) AS EIF,
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '11' AND tax_year = asdet.year) AS 'ZONING CLEARANCE',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '12' AND tax_year = asdet.year) AS 'SIGNAGE PERMIT FEE',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '20' AND tax_year = asdet.year)+
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '21' AND tax_year = asdet.year) AS 'SURCHARGE & INTEREST',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '23' AND tax_year = asdet.year) AS 'URBAN GREENING DEV. FEE',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '15' AND tax_year = asdet.year) AS 'DELIVERY TRUCK/ VAN',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '16' AND tax_year = asdet.year) AS 'SERVICE MOTOR VEHICLE',
                 (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '13' AND tax_year = asdet.year) AS 'OCC. FEE',
                 (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '24' AND tax_year = asdet.year) AS 'MACH/HEAVY EQUIP. PERMIT FEE',
                'PRINTING/ PUBLICATION FEE (B.T.)' AS 'PRINTING/ PUBLICATION FEE (B.T.)' ,
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '14' AND tax_year = asdet.year) + 
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '29' AND tax_year = asdet.year) AS 'STORAGE FEE',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '25' AND tax_year = asdet.year) AS 'CONVENIENCE FEE',
                (SELECT amount FROM fees WHERE application_id = apps.id AND fees_default_id = '26' AND tax_year = asdet.year) AS OTHERS,
                'OTHER DEFERRED CREDITS - BUSINESS TAX' AS 'OTHER DEFERRED CREDITS - BUSINESS TAX',
                ap.amount AS 'TOTAL AMOUNT',
                CONCAT(ap.time_in, '-', ap.time_out) AS 'TIME IN - OUT',
                orser.ornum AS 'OR. NO.',
                ap.date_paid AS 'DATE'
                FROM applications apps
                LEFT JOIN assessment_slip aslip ON aslip.application_id = apps.id
                LEFT JOIN mode_of_payments mop ON mop.id = aslip.mode_of_payment_id
                LEFT JOIN assessment_slip_details asdet ON asdet.assessment_slip_id = aslip.id
                LEFT JOIN application_type atype ON atype.id  = apps.application_type_id
                LEFT JOIN applications_line_of_business lob ON apps.id = lob.application_id
                LEFT JOIN classifications cls ON lob.classification_id = cls.id
                LEFT JOIN tax_payers tp ON tp.id = apps.tax_payer_id
                LEFT JOIN assessment_payments ap ON apps.id = ap.application_id
                LEFT JOIN accountable_form_or_series orser ON ap.or_series_id = orser.id
                LEFT JOIN accountable_forms af ON orser.accountable_form_id = af.id
                LEFT JOIN accountable_form_assigned_receipts afar ON afar.accountable_form_id = af.id
                LEFT JOIN forms fms ON fms.id = af.form_id
                WHERE ap.deleted_at IS NULL 
                AND afar.assigned = ?
                AND ap.date_paid BETWEEN ? AND ?
            )
            UNION
            (
                SELECT   
                '' AS 'BUSINESS CONTROL NO',
                msc.payors_name AS OWNER,
                '' AS'NAME OF BUSINESS',
                '' AS NATURE,
                NULL AS ADDRESS,
                'O' AS 'APPLICATION STATUS',
                '' AS 'TAX YEAR',
                'Application Payment' AS 'APPLICATION PAYMENT',
                '' AS 'MODE OF PAYMENT',
                '' AS 'GROSS RECEIPTS/ CAPITAL',
                '' AS 'BUSINESS TAX',
                '' AS 'BUSINESS CLOSURE',
                '' AS 'MAYORS PERMIT',
                '' AS 'SANITARY INSPECTION FEE',  
                '' AS 'ENV. MGMT. FEE',
                '' AS 'HEALTH CERTIFICATE',
                '' AS 'FIRE SAFETY INSP. FEE',
                '' AS 'FIRE CODE FEE',
                '' AS 'METAL PLATE/ STICKER',
                '' AS 'LBOD CLEARANCE FEE',
                '' AS EIF,
                '' AS 'ZONING CLEARANCE',
                '' AS 'SIGNAGE PERMIT FEE',
                '' AS 'SURCHARGE & INTEREST',
                '' AS 'URBAN GREENING DEV. FEE',
                '' AS 'DELIVERY TRUCK/ VAN',
                '' AS 'SERVICE MOTOR VEHICLE',
                '' AS 'OCC. FEE',
                '' AS 'MACH/HEAVY EQUIP. PERMIT FEE',
                '' AS 'PRINTING/ PUBLICATION FEE (B.T.)' ,
                '' AS 'STORAGE FEE',
                '' AS 'CONVENIENCE FEE',
                msc.amount AS OTHERS,
                '' AS 'OTHER DEFERRED CREDITS - BUSINESS TAX',
                msc.amount AS 'TOTAL AMOUNT',
                CONCAT( msc.time_in,'-', msc.time_out) 'TIME IN - OUT',
                orser2.ornum AS 'OR. NO.',
                msc.date_paid AS 'DATE'
                FROM miscellaneous_payments msc
                
                LEFT JOIN accountable_form_or_series orser2 ON msc.orno_id = orser2.id
                LEFT JOIN accountable_forms af2 ON orser2.accountable_form_id = af2.id
                LEFT JOIN forms fms2 ON fms2.id = af2.form_id
                LEFT JOIN accountable_form_assigned_receipts afar2 ON afar2.accountable_form_id = af2.id
                WHERE msc.deleted_at IS NULL 
               
                AND afar2.assigned = ?
                AND msc.date_paid BETWEEN ? AND ?
            )
            ORDER BY 'OR. NO.'
            ";
    
        // Execute the query
        $query = $this->db->query($sql,[$ftrdata['collector_id'],$ftrdata['DateFrom'], $ftrdata['DateTo'],$ftrdata['collector_id'],$ftrdata['DateFrom'], $ftrdata['DateTo']]);
    
        // Return the result set as an array
        return $query->getResultArray();
    }
    
    public function filterDataOR($ftrdata){
                // The main ledger query
                $sql = "
                (
                    SELECT orser.ornum, ap.amount
                    FROM assessment_payments ap
                    LEFT JOIN accountable_form_or_series orser ON ap.or_series_id = orser.id
                    LEFT JOIN accountable_forms af ON orser.accountable_form_id = af.id
                    LEFT JOIN forms fms ON fms.id = af.form_id
                    WHERE ap.deleted_at IS NULL 
                    AND af.form_id = ?
                    AND ap.date_paid = ?
                    AND orser.ornum BETWEEN ? AND ?
                )
                UNION
                (
                    SELECT orser2.ornum, msc.amount
                    FROM miscellaneous_payments msc
                    LEFT JOIN accountable_form_or_series orser2 ON msc.orno_id = orser2.id
                    LEFT JOIN accountable_forms af2 ON orser2.accountable_form_id = af2.id
                    LEFT JOIN forms fms2 ON fms2.id = af2.form_id
                    WHERE msc.deleted_at IS NULL 
                    AND af2.form_id = ?
                    AND msc.date_paid = ?
                    AND orser2.ornum BETWEEN ? AND ?
                )
                ORDER BY ornum;
                ";
        
                // Execute the main query
                $query = $this->db->query($sql, [$ftrdata['form_id'], $ftrdata['OrDate'],$ftrdata['from'],$ftrdata['to'],$ftrdata['form_id'], $ftrdata['OrDate'],$ftrdata['from'],$ftrdata['to']]);
        
                // Return the result set
                return $query->getResultArray();
        // return $ftrdata;
    
 
    }
    public function filterDataDelinquents($ftrdata){
                // The main ledger query
                $sql = "
                SELECT 
  aslip.`id`,
  apps.`blpdno`,
  apps.`business_name`,
  -- cls.name AS NATURE,
  asdet.`installment`,
  FORMAT_ADDRESS(apps.brgy_id, apps.building_no, apps.unit_no, apps.street, apps.subdivision, apps.block, apps.lot, apps.phase) AS ADDRESS,
  (SELECT CASE
    WHEN mop1.`number_of_installment` = '1' THEN 
      CASE
        WHEN asdet1.`installment` IS NULL THEN 'NO LAST PAYMENT'
        ELSE CAST(asdet1.`year` AS CHAR)
      END
    WHEN mop1.`number_of_installment` = '2' THEN 
      CASE
        WHEN asdet1.`installment` IS NULL THEN 'NO LAST PAYMENT'
        WHEN asdet1.`installment` = '1' THEN '1ST SEMI-ANNUAL'
        WHEN asdet1.`installment` = '2' THEN '2ND SEMI-ANNUAL'
        ELSE 'UNKNOWN SEMI-ANNUAL'
      END
    WHEN mop1.`number_of_installment` = '4' THEN 
      CASE
        WHEN asdet1.`installment` IS NULL THEN 'NO LAST PAYMENT'
        WHEN asdet1.`installment` = '1' THEN '1ST QUARTER'
        WHEN asdet1.`installment` = '2' THEN '2ND QUARTER'
        WHEN asdet1.`installment` = '3' THEN '3RD QUARTER'
        WHEN asdet1.`installment` = '4' THEN '4TH QUARTER'
        ELSE 'UNKNOWN QUARTER'
      END
    ELSE 'UNKNOWN PAYMENT SCHEDULE'
  END
  FROM assessment_slip aslip1
  LEFT JOIN mode_of_payments mop1 ON mop1.`id` = aslip1.`mode_of_payment_id`
  LEFT JOIN assessment_payments apay1 ON apay1.`assessment_slip_id` = aslip1.`id`
  LEFT JOIN assessment_slip_details asdet1 ON asdet1.`assessment_slip_id` = aslip1.`id`
  WHERE aslip1.`id` = aslip.`id`
  ORDER BY apay1.`date_paid` DESC
  LIMIT 1) AS 'LAST PAYMENT',
 (SELECT SUM(fs.`amount`) FROM fees fs 
  LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
 WHERE fs.`application_id` = apps.`id`) AS 'TOTAL ASSESSMENT',

(SELECT SUM(fs.`amount`)/4 FROM fees fs 
   LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
   WHERE fs.`application_id` = apps.`id` AND fd.group != 'r') AS 'BUSINESS TAX Q1',
  (SELECT SUM(fs.`amount`)/4 FROM fees fs 
   LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
   WHERE fs.`application_id` = apps.`id` AND fd.group != 'r') AS 'BUSINESS TAX Q2',
  (SELECT SUM(fs.`amount`)/4 FROM fees fs 
   LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
   WHERE fs.`application_id` = apps.`id` AND fd.group != 'r') AS 'BUSINESS TAX Q3',
  (SELECT SUM(fs.`amount`)/4 FROM fees fs 
   LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
   WHERE fs.`application_id` = apps.`id` AND fd.group != 'r') AS 'BUSINESS TAX Q4',
    (SELECT SUM(fs.`amount`) FROM fees fs 
  LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
 WHERE fs.`application_id` = apps.`id` AND fd.group != 'r') AS 'TOTAL BUSINESS TAX',
  (SELECT SUM(fs.`amount`) FROM fees fs 
  LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
 WHERE fs.`application_id` = apps.`id` AND fd.group != 'r') AS 'REGULATORY AMOUNT'
FROM assessment_slip aslip
LEFT JOIN applications apps ON apps.`id` = aslip.`application_id`
-- LEFT JOIN fees fs ON fs.`application_id` = apps.`id`
-- LEFT JOIN applications_line_of_business lob ON apps.id = lob.application_id
-- LEFT JOIN classifications cls ON lob.classification_id = cls.id
LEFT JOIN assessment_slip_details asdet ON asdet.`assessment_slip_id` = aslip.`id`
LEFT JOIN mode_of_payments mop ON mop.`id` = aslip.`mode_of_payment_id`
WHERE aslip.`deleted_at` IS NULL
AND apps.`date_applied` BETWEEN ? AND ?
;
                ";
        
                // Execute the main query
                $query = $this->db->query($sql, [$ftrdata['DateFrom'],$ftrdata['DateTo']]);
        
                // Return the result set
                return $query->getResultArray();
        // return $ftrdata;
    
 
    }
    public function mFilterNoticeReports($from,$to){
                // The main ledger query
                $sql = "SELECT  
                ntc.`series` AS 'No',
                apps.`blpdno` AS 'Business Control Number',
                apps.`business_name` AS 'NAME OF TAXPAYER',
                apps.`business_name` AS 'NAME OF BUSINESS',
                FORMAT_ADDRESS(apps.brgy_id, apps.building_no, apps.unit_no, apps.street, apps.subdivision, apps.block, apps.lot, apps.phase) AS 'BUSINESS ADDRESS',
                FORMAT_ADDRESS(tp.`barangay_id`,tp.`building_no`,tp.`unit_no`,tp.`street`,tp.`subdivision`,tp.`block`,tp.`lot`,tp.`phase`) AS 'BUSINESS ADDRESS',
                CASE
                    WHEN (
                        SELECT YEAR(a.`date_applied`)
                        FROM applications a
                        WHERE a.`id` = (
                            SELECT MAX(a2.`id`)
                            FROM applications a2
                            WHERE a2.`deleted_at` IS NULL
                            AND a2.`blpdno` = apps.`blpdno`
                            AND (a2.`application_type_id` = 1 OR a2.`application_type_id` = 2)
                        )
                        AND a.`deleted_at` IS NULL
                    ) = YEAR(CURDATE()) THEN CAST(YEAR(CURDATE()) AS CHAR)
                    ELSE CONCAT(
                        (
                            SELECT YEAR(a.`date_applied`)
                            FROM applications a
                            WHERE a.`id` = (
                                SELECT MAX(a2.`id`)
                                FROM applications a2
                                WHERE a2.`deleted_at` IS NULL
                                AND a2.`blpdno` = apps.`blpdno`
                                AND (a2.`application_type_id` = 1 OR a2.`application_type_id` = 2)
                            )
                            AND a.`deleted_at` IS NULL
                        ),
                        '-',
                        YEAR(CURDATE())
                    )
                END AS 'YEAR DEL.'
            
            FROM notice_to_comply ntc
            LEFT JOIN applications apps ON ntc.`application_id` = apps.id
            LEFT JOIN tax_payers tp ON apps.`tax_payer_id` = tp.`id`
            
            WHERE ntc.`series` BETWEEN ?  AND ?
            ";
        
                // Execute the main query
                $query = $this->db->query($sql, [$from,$to]);
        
                // Return the result set
                return $query->getResultArray();
        // return $ftrdata;
    
 
    }
    public function filterDataDelinquents2($ftrdata){
                // The main ledger query
             
                $sql = "
                SELECT 
  aslip.`id`,
  apps.`id` AS application_id,
  apps.`blpdno`,
  apps.`business_name`,
  FORMAT_FULLNAME('', tp.lastname, tp.firstname, tp.middlename, tp.suffix, '') AS 'OWNER',
  asdet.`installment`,
  FORMAT_ADDRESS(apps.brgy_id, apps.building_no, apps.unit_no, apps.street, apps.subdivision, apps.block, apps.lot, apps.phase) AS ADDRESS,
  (SELECT CASE
    WHEN mop1.`number_of_installment` = '1' THEN 
      CASE
        WHEN asdet1.`installment` IS NULL THEN 'NO LAST PAYMENT'
        ELSE CAST(asdet1.`year` AS CHAR)
      END
    WHEN mop1.`number_of_installment` = '2' THEN 
      CASE
        WHEN asdet1.`installment` IS NULL THEN 'NO LAST PAYMENT'
        WHEN asdet1.`installment` = '1' THEN '1ST SEMI-ANNUAL'
        WHEN asdet1.`installment` = '2' THEN '2ND SEMI-ANNUAL'
        ELSE 'UNKNOWN SEMI-ANNUAL'
      END
    WHEN mop1.`number_of_installment` = '4' THEN 
      CASE
        WHEN asdet1.`installment` IS NULL THEN 'NO LAST PAYMENT'
        WHEN asdet1.`installment` = '1' THEN '1ST QUARTER'
        WHEN asdet1.`installment` = '2' THEN '2ND QUARTER'
        WHEN asdet1.`installment` = '3' THEN '3RD QUARTER'
        WHEN asdet1.`installment` = '4' THEN '4TH QUARTER'
        ELSE 'UNKNOWN QUARTER'
      END
    ELSE 'UNKNOWN PAYMENT SCHEDULE'
  END
  FROM assessment_slip aslip1
  LEFT JOIN mode_of_payments mop1 ON mop1.`id` = aslip1.`mode_of_payment_id`
  LEFT JOIN assessment_payments apay1 ON apay1.`assessment_slip_id` = aslip1.`id`
  LEFT JOIN assessment_slip_details asdet1 ON asdet1.`assessment_slip_id` = aslip1.`id`
  
  WHERE aslip1.`id` = aslip.`id`
  ORDER BY apay1.`date_paid` DESC
  LIMIT 1) AS 'LAST PAYMENT',
 (SELECT SUM(fs.`amount`) FROM fees fs 
  LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
 WHERE fs.`application_id` = apps.`id`) AS 'TOTAL ASSESSMENT',

(SELECT SUM(fs.`amount`)/4 FROM fees fs 
   LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
   WHERE fs.`application_id` = apps.`id` AND fd.group != 'r') AS 'BUSINESS TAX Q1',
  (SELECT SUM(fs.`amount`)/4 FROM fees fs 
   LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
   WHERE fs.`application_id` = apps.`id` AND fd.group != 'r') AS 'BUSINESS TAX Q2',
  (SELECT SUM(fs.`amount`)/4 FROM fees fs 
   LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
   WHERE fs.`application_id` = apps.`id` AND fd.group != 'r') AS 'BUSINESS TAX Q3',
  (SELECT SUM(fs.`amount`)/4 FROM fees fs 
   LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
   WHERE fs.`application_id` = apps.`id` AND fd.group != 'r') AS 'BUSINESS TAX Q4',
    (SELECT SUM(fs.`amount`) FROM fees fs 
  LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
 WHERE fs.`application_id` = apps.`id` AND fd.group != 'r') AS 'TOTAL BUSINESS TAX',
  (SELECT SUM(fs.`amount`) FROM fees fs 
  LEFT JOIN fees_default fd ON fd.`id` = fs.`fees_default_id`
 WHERE fs.`application_id` = apps.`id` AND fd.group != 'r') AS 'REGULATORY AMOUNT',
 nc.remarks as series_stats
FROM assessment_slip aslip
LEFT JOIN applications apps ON apps.`id` = aslip.`application_id`
LEFT JOIN tax_payers tp ON tp.id = apps.tax_payer_id
-- LEFT JOIN fees fs ON fs.`application_id` = apps.`id`
-- LEFT JOIN applications_line_of_business lob ON apps.id = lob.application_id
-- LEFT JOIN classifications cls ON lob.classification_id = cls.id
LEFT JOIN assessment_slip_details asdet ON asdet.`assessment_slip_id` = aslip.`id`
LEFT JOIN mode_of_payments mop ON mop.`id` = aslip.`mode_of_payment_id`
LEFT JOIN notice_to_comply nc ON nc.`application_id` = apps.`id`
WHERE aslip.`deleted_at` IS NULL";

if ($ftrdata['ftr_selby'] == 'blpdNumber') {
    $sql .= " AND apps.`blpdno` = '".$ftrdata['ftr_val']."' ";
}


$sql .= " GROUP BY blpdno";        
                // Execute the main query
                $query = $this->db->query($sql);
        
                // Return the result set
                return $query->getResultArray();
        // return $ftrdata;
    
 
    }

    public function mGetPaymentDetails($id){
    return $this->db->table('applications as apps')
            ->select('apps.id, apps.blpdno, apps.business_name')
            ->select('FORMAT_FULLNAME("", tp.lastname, tp.firstname, tp.middlename, tp.suffix, "") AS tax_payer_name')
            ->select('FORMAT_ADDRESS(apps.brgy_id, apps.building_no, apps.unit_no, apps.street, apps.subdivision, apps.block, apps.lot, apps.phase) AS taxpayer_address')
            
            ->join('tax_payers as tp', 'tp.id = apps.tax_payer_id', 'left')
            ->where('apps.blpdno', $id)
            ->get()
            ->getRowArray();
    }
    public function mgetBusinessDetails($id){
      return $this->db->table('applications as apps')
              ->select('apps.id, apps.blpdno, apps.business_name')
              ->select("FORMAT_FULLNAME('', tp.lastname, tp.firstname, tp.middlename, tp.suffix, '') AS tax_payer_name,
                         FORMAT_ADDRESS(apps.brgy_id, apps.building_no, apps.unit_no, apps.street, apps.subdivision, apps.block, apps.lot, apps.phase) AS taxpayer_address,
                         (SELECT YEAR(a.`date_applied`)
                          FROM applications a
                          WHERE a.`id` = (SELECT MAX(a2.`id`)
                                          FROM applications a2
                                          WHERE a2.`deleted_at` IS NULL
                                            AND a2.`blpdno` = apps.blpdno
                                            AND (a2.`application_type_id` = 1 OR a2.`application_type_id` = 2)
                                         ) 
                          AND a.`deleted_at` IS NULL) AS last_applied_year")
              ->join('tax_payers as tp', 'tp.id = apps.tax_payer_id', 'left')
              ->where('apps.id', $id)
              ->get()
              ->getRowArray();
  }
  
    public function mGetCollectors(){
        // $config = new Encryption();
        // $secretKey = $config->secretKey;
        $sql = "SELECT 
        collectors.id, collectors.user_id, collectors.accountable_officer_id, 
        CONCAT_WS(' ', accoff.firstname, COALESCE(accoff.middlename, ''), COALESCE(accoff.lastname, ''), COALESCE(accoff.suffix, '')) AS accountable_person, 
        CONCAT_WS(' ', colname.firstname, COALESCE(colname.middlename, ''), COALESCE(colname.lastname, ''), COALESCE(colname.suffix, '')) AS collectorname 
        FROM collectors
        LEFT JOIN accountable_form_officers AS accofficer ON accofficer.id = collectors.accountable_officer_id
        
        LEFT JOIN users AS accoff ON accoff.id = accofficer.user_id
        LEFT JOIN users AS colname ON colname.id = collectors.user_id
        WHERE collectors.deleted_by IS NULL";
    
       
        // Execute the SQL query using your database connection
        $result = $this->db->query($sql)->getResult();
    
        return $result;
    }
    public function mSaveNotice($data){
      $builder = $this->db->table('notice_to_comply');
      $builder->insert($data);
      $insertID = $this->db->insertID();
      if (!$insertID){
          return false;
      }else {
          return true;
      }
  }
    public function mGetBusinessHistory($blpdno){
        // Initialize the running balance
        $this->db->query("SET @running_balance := 0;");

        // The main ledger query
        $sql = "
        SELECT 
        TransactionDate,
        TaxYear,
        ModeOfPayment,
        TransactionType,
        TransactionNo,
        Assessment,
        Interest,
        Payment,
        (@running_balance := @running_balance + (Assessment - Payment)) AS Balance
    FROM (
        SELECT 
            asl.assessment_date AS TransactionDate,
            asd.year AS TaxYear,
            'Annual' AS ModeOfPayment,
             CONCAT('ASSESSMENT-', apt.`name`) AS TransactionType,
            CONCAT('ASSESSMENT-', asl.id) AS TransactionNo, -- Concatenate with actual ID or reference number
            asd.amount AS Assessment,
            0 AS Interest,
            0 AS Payment
        FROM 
            applications a
        JOIN 
            application_type apt ON apt.id = a.`application_type_id`
        JOIN 
            assessment_slip asl ON a.id = asl.application_id
        JOIN 
            assessment_slip_details asd ON asl.id = asd.assessment_slip_id
        WHERE 
            a.blpdno = ?
            
    
        UNION ALL
    
        SELECT 
            ap.date_paid AS TransactionDate,
            YEAR(ap.date_paid) AS TaxYear,
            'Annual' AS ModeOfPayment,
            CONCAT('PAYMENT-', apt.`name`) AS TransactionType,
            CONCAT('OR-', ors.ornum) AS TransactionNo,
            0 AS Assessment,
            0 AS Interest,
            ap.amount AS Payment
        FROM 
            applications a
        JOIN 
            application_type apt ON apt.id = a.application_type_id
        JOIN 
            assessment_payments ap ON a.id = ap.application_id
        JOIN
        accountable_form_or_series ors ON ors.id = ap.or_series_id
        JOIN 
            assessment_payment_details apd ON ap.id = apd.assessment_payment_id
        WHERE 
            a.blpdno = ?
            
    ) AS combined
    ORDER BY 
        TransactionDate;";

        // Execute the main query
        $query = $this->db->query($sql, [$blpdno, $blpdno]);

        // Return the result set
        return $query->getResultArray();
    }

    public function mCheckNoticeSeries(){
      // Get the current year
      $currentYear = date('Y');
      
      // Prepare the query to find the last series number for the current year
      $lastSeries = $this->db->table('notice_to_comply')
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
  


}
