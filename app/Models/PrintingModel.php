<?php

namespace App\Models;

use CodeIgniter\Model;

class PrintingModel extends Model
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

    public function getApplicationDetails($appId){
        return $this->db->table('applications as apps')
            ->select('apps.id AS app_id, apps.blpdno, apps.business_name,apps.date_applied')
            ->select('FORMAT_FULLNAME("", tp.lastname, tp.firstname, tp.middlename, tp.suffix, "") AS tax_payer_name')
            ->join('tax_payers as tp', 'tp.id = apps.tax_payer_id', 'left')
            ->join('assessment_slip as aslip', 'apps.id = aslip.application_id', 'left')
            ->where('aslip.id', $appId)
            ->get()
            ->getRowArray();
    }
    
    public function mGetPaymentDetails($appId,$installment_id){
        $sql = "
        SELECT apps.id,apps.`blpdno`,apps.`business_name`, 
        CONCAT(mop.`description`,'-',asdet.`installment`) AS modeofpayment,
        (SELECT aspay.`date_paid` FROM assessment_payments aspay WHERE aspay.assessment_slip_id = ? AND aspay.installment = ?) AS date_paid,
        (SELECT aspay.`amount` FROM assessment_payments aspay WHERE aspay.assessment_slip_id = ? AND aspay.installment = ?) AS PaidAmount,
        (SELECT orseries.`ornum` AS orNumber FROM assessment_payments aspay
            LEFT JOIN accountable_form_or_series orseries ON aspay.`or_series_id` = orseries.`id`
         WHERE aspay.assessment_slip_id = ? AND aspay.installment = ?) AS orNumber,
        FORMAT_FULLNAME('', tp.lastname, tp.firstname, tp.middlename, tp.suffix, '') AS tax_payer_name 
         
        FROM applications apps
        LEFT JOIN assessment_slip aslip ON aslip.`application_id` = apps.`id`
        LEFT JOIN mode_of_payments mop ON mop.id = aslip.`mode_of_payment_id`
        LEFT JOIN assessment_slip_details asdet ON asdet.`assessment_slip_id` = aslip.`id`
        LEFT JOIN tax_payers tp ON tp.id = apps.`tax_payer_id`
        
        WHERE aslip.id = ?
        AND asdet.`installment` = ?
        
        ";
        $db = \Config\Database::connect();
        $result = $db->query($sql, [$appId, $installment_id,$appId, $installment_id,$appId, $installment_id,$appId, $installment_id])->getResult();
        
        return $result;




    }
    public function mGetPaymentDetailsGeneral($appId){
        return $this->db->table('applications as apps')
            ->select('apps.id, apps.blpdno, apps.business_name,mop.description AS modeofpayment,asspay.date_paid,orseries.ornum AS orNumber, asspay.amount as PaidAmount')
            ->select('FORMAT_FULLNAME("", tp.lastname, tp.firstname, tp.middlename, tp.suffix, "") AS tax_payer_name')
            ->join('tax_payers as tp', 'tp.id = apps.tax_payer_id', 'left')
            ->join('assessment_slip as aslip', 'apps.id = aslip.application_id', 'left')
            ->join('mode_of_payments as mop', 'aslip.mode_of_payment_id = mop.id', 'left')
            ->join('assessment_payments as asspay', 'asspay.assessment_slip_id = aslip.id', 'left')
            ->join('accountable_form_or_series as orseries', 'asspay.or_series_id = orseries.id', 'left')
            ->where('aslip.id', $appId)
            ->get()
            ->getRowArray();
    }
    public function mGetPaymentDetailsMsc($appId){
        return $this->db->table('miscellaneous_payments as pays')
            ->select('pays.id, pays.form_id, pays.orno_id,pays.date_paid, pays.payors_name,orseries.ornum AS orNumber, pays.amount as PaidAmount, apps.blpdno')
            // ->select('FORMAT_FULLNAME("", tp.lastname, tp.firstname, tp.middlename, tp.suffix, "") AS tax_payer_name')
            // ->join('tax_payers as tp', 'tp.id = apps.tax_payer_id', 'left')
            // ->join('assessment_slip as aslip', 'apps.id = aslip.application_id', 'left')
            // ->join('mode_of_payments as mop', 'aslip.mode_of_payment_id = mop.id', 'left')
            ->join('applications as apps', 'apps.id = pays.application_id', 'left')
            ->join('accountable_form_or_series as orseries', 'pays.orno_id = orseries.id', 'left')
            ->where('pays.id', $appId)
            ->get()
            ->getRowArray();
    }
    public function getApplicationData($applicationId) {
        $query = $this->db->table($this->table)
            ->select('date_applied, application_type_id')
            ->where('id', $applicationId)
            ->get();
    
        $result = $query->getResult(); // Use getResult() instead of get()
    
        if (!empty($result)) {
            $row = $result[0]; // Assuming there's only one result
            return [
                'date_applied' => $row->date_applied,
                'application_type_id' => $row->application_type_id,
            ];
        } else {
            return null;
        }
    }
    public function calculateDueDate($applicationTypeId, $dateApplied) {
        // Get the current year
        $currentYear = date('Y');
    
        // Determine the quarter start dates for the current year
        $firstQuarterStart = $currentYear . '-01-01';
        $secondQuarterStart = $currentYear . '-04-01';
        $thirdQuarterStart = $currentYear . '-07-01';
        $fourthQuarterStart = $currentYear . '-10-01';
    
        // Default values for peak and non-peak days
        $peakDays = 3;
        $nonPeakDays = 1; // Default value for non-peak days
    
        // Get peak days and non-peak days based on application type and year
        $claimstubSchedulesQuery = $this->db->table('claimstub_schedules')
            ->select('first_quarter_peak_days, second_quarter_peak_days, third_quarter_peak_days, fourth_quarter_peak_days, nonpeak_days')
            ->where('application_type_id', $applicationTypeId)
            ->where('tax_effectivity_year', $currentYear)
            ->get();
    
        $result = $claimstubSchedulesQuery->getResult(); // Use getResult() instead of get()
    
        if (!empty($result)) {
            $row = $result[0]; // Assuming there's only one result
            $firstQuarterPeakDays = $row->first_quarter_peak_days;
            $secondQuarterPeakDays = $row->second_quarter_peak_days;
            $thirdQuarterPeakDays = $row->third_quarter_peak_days;
            $fourthQuarterPeakDays = $row->fourth_quarter_peak_days;
            $nonPeakDays = $row->nonpeak_days;
        }
    
        // Determine the quarter based on date_applied
        if ($dateApplied >= $firstQuarterStart && $dateApplied < $secondQuarterStart) {
            // First quarter logic
            $dueDate = date('Y-m-d', strtotime($dateApplied . ' +' . $firstQuarterPeakDays . ' days'));
        } elseif ($dateApplied >= $secondQuarterStart && $dateApplied < $thirdQuarterStart) {
            // Second quarter logic
            $dueDate = date('Y-m-d', strtotime($dateApplied . ' +' . $secondQuarterPeakDays . ' days'));
        } elseif ($dateApplied >= $thirdQuarterStart && $dateApplied < $fourthQuarterStart) {
            // Third quarter logic
            $dueDate = date('Y-m-d', strtotime($dateApplied . ' +' . $thirdQuarterPeakDays . ' days'));
        } elseif ($dateApplied >= $fourthQuarterStart) {
            // Fourth quarter logic
            $dueDate = date('Y-m-d', strtotime($dateApplied . ' +' . $fourthQuarterPeakDays . ' days'));
        } else {
            // Handle the case when date_applied is not within any quarter
            return null;
        }
    
        return $dueDate;
    }
    
    public function m_getListOfFees($id){
        $sql = "SELECT fs.application_id, fs.tax_year, fs.fees_default_id, fs.amount, fs.remarks
        FROM fees AS fs
        LEFT JOIN assessment_slip AS aslip ON aslip.application_id = fs.application_id
        WHERE aslip.id = '$id' AND aslip.deleted_by IS NULL AND fs.fees_default_id NOT IN (1, 2) AND fs.amount != '0.00'";
        
        $query = $this->db->query($sql);

        $result = $query->getResult();
        return $result;
    }
    public function m_getListOfFeesmsc($id){
        $sql = "SELECT fs.id, fs.amount, msc.name AS remarks
        FROM miscellaneous_payment_list AS fs
        LEFT JOIN miscellaneous AS msc ON msc.id = fs.miscellaneous_id
        WHERE fs.miscellaneous_payment_id = '$id' AND fs.deleted_by IS NULL";
        
        $query = $this->db->query($sql);

        $result = $query->getResult();
        return $result;
    }
    public function m_getFeesTotal_Mayors_fee($id) {
        $query = $this->db->table('assessment_slip AS aslip')
          ->select('aslip.id, aslip.application_id, SUM(fs.amount) AS tamount,fs.tax_year')
          ->join('fees AS fs', 'fs.application_id = aslip.application_id', 'left');
        //   ->join('tax_payers AS tp', 'tp.id = apps.tax_payer_id', 'left');
          $query->where('aslip.id', $id);
          $query->where('fs.tax_year', date('Y'));
          $query->where('fs.fees_default_id','2');


        $result = $query->get()->getResult();
        return $result;
    }


}
