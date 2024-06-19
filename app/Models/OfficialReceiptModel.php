<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Encryption;

class OfficialReceiptModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'accountable_form_or_series';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['accountable_form_id','ornum','status','void','date_void'];

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

    public function mGetOrSeries($data) {
        $builder = $this->db->table('accountable_form_or_series orser');
        
        $builder->select('aspay.assessment_slip_id, orser.id, orser.accountable_form_id, orser.ornum, orser.status, orser.void');
        $builder->join('assessment_payments aspay', 'orser.id = aspay.or_series_id', 'left');
        $builder->where('orser.status', 'used');
        $builder->where('orser.void !=', 'Y');
        $builder->where('orser.ornum', $data['ftr_val']);
        
        $query = $builder->get();
    
        return $query->getResult();
    }
    

public function mGetOrDetails($orNnumberSeriesID){
   
        $sql = "SELECT 	orseries.`id`,
        orseries.`ornum` AS orNumber,
        aspay.`application_id`,
        apps.`blpdno`,
        apps.`business_name`,
        CONCAT((SELECT mop.`description` FROM mode_of_payments mop WHERE mop.`id` = aslip.`mode_of_payment_id`),
        '-',
        aspay.`installment`) AS modeofpayment,
        
        
        
        aspay.`amount` AS PaidAmount,
        aspay.`date_paid` AS date_paid,
        
        FORMAT_FULLNAME('', tp.lastname, tp.firstname, tp.middlename, tp.suffix, '') AS tax_payer_name 
        FROM accountable_form_or_series orseries
        LEFT JOIN assessment_payments aspay ON aspay.`or_series_id` = orseries.`id`
        LEFT JOIN applications apps ON apps.`id` = aspay.`application_id`
        LEFT JOIN tax_payers tp ON tp.`id` = apps.`tax_payer_id`
        LEFT JOIN assessment_slip aslip ON aslip.`application_id` = apps.`id`
        
        
        WHERE orseries.`id` = '$orNnumberSeriesID'
        ";

         
        $query = $this->db->query($sql);

        $result = $query->getResult();
        return $result;
        



    
}




}
