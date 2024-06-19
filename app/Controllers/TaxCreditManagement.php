<?php

namespace App\Controllers;
use App\Controllers\AuditTrail;
use CodeIgniter\I18n\Time;
use App\Libraries\PdfLibrary;

// $otherController = new \App\Controllers\UserManagement();
use App\Models\PrintingModel;
use App\Models\ReportManagementModal;
// use App\Models\FeesDefaultModel;
use App\Models\AssessmentSlipModel;

class TaxCreditManagement extends BaseController
{
    // public function index(){   
    //     $session = session();
    //     if ($session->get('isLoggedIn')) {
    //         $AuditTrail = new AuditTrail();
    //         $AuditTrail->save_logs('[visit] : Issuance TAXBILL',$session->get('id'));
    //         return view('reports/issueTaxBill');
    //     } else {
    //         return redirect()->to(base_url('ibcas/login'));
    //     }
    
        
    // }
    public function ViewTaxCredit(){   
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Issuance TaxCredit',$session->get('id'));
            return view('reports/issueTaxCredit');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }
    public function ViewTaxBill(){   
        $session = session();
        if ($session->get('isLoggedIn')) {
            $AuditTrail = new AuditTrail();
            $AuditTrail->save_logs('[visit] : Issuance TaxBill',$session->get('id'));
            return view('reports/issueTaxBill');
        } else {
            return redirect()->to(base_url('ibcas/login'));
        }
    
        
    }

    public function getTaxCreditValue($assessmentId){
        $session = session();
        $model = new AssessmentSlipModel();
        $blpdNumber = '';
        $blpdData = $model->m_getBlpdNumber($assessmentId);       
        $blpdNumber = $blpdData['id'];
        $taxCredAmount = '';
      
        if($taxCredData = $model->m_getTaxCreditValue($blpdNumber)){
            foreach ($taxCredData as $r) {
                $taxCredAmount = $r->credit_amount;
            }
    
    
            
            $result = array('status' => 200,'message'=> "Records Found","taxcred"=>$taxCredAmount);
            return $this->response->setJSON($result);
        } else {
            $result = array('status' => 200,'message'=> "Records Found","taxcred"=>'0');
            return $this->response->setJSON($result);
        }

       


      



    }

    public function FilterTaxCredit(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $DataPosted = $this->request->getPost();
        $model = new AssessmentSlipModel();

        if ($DataPosted['ftr_selby'] != 'blpdNumber') {
            $data['ftr_by'] = 'ALL';
            $data['ftr_value'] = '';

        } else {
            $data['ftr_by'] = 'blpdno';
            $data['ftr_value'] = $DataPosted['ftr_val'];
           
        }

        if($data = $model->mFilterTaxCredit($data)){
            $AuditTrail->save_logs('[filter] : Tax Credits',$session->get('id'));
            $result = array('status' => 200,'message'=> "Records Found","TableContent"=>$data);
            return $this->response->setJSON($result);
        } else {
           
            $result = array('status' => 401,'message'=> "Records Filter Failed","TableContent"=>'');
            return $this->response->setJSON($result);
        }

        




    }
    public function FilterTaxBill(){
        $AuditTrail = new AuditTrail();
        $session = session();
        $DataPosted = $this->request->getPost();
        $model = new AssessmentSlipModel();
        // print_r($DataPosted);
        // die();
        if ($DataPosted['ftr_selby'] == 'blpdNumber') {
            $data['ftr_by'] = 'blpdno';
            $data['ftr_value'] = $DataPosted['ftr_val'];

        } else if ($DataPosted['ftr_selby'] == 'business_name'){
            $data['ftr_by'] = 'business_name';
            $data['ftr_value'] = $DataPosted['ftr_val'];
        } 

        
        if($data = $model->mFilterTaxBill($data)){
            $AuditTrail->save_logs('[filter] : Tax Bill',$session->get('id'));
            $result = array('status' => 200,'message'=> "Records Found","TableContent"=>$data);
            return $this->response->setJSON($result);
        } else {
           
            $result = array('status' => 401,'message'=> "Records Filter Failed","TableContent"=>'');
            return $this->response->setJSON($result);
        }

        




    }

    public function IssueTaxCert($id){
        $session = session();
        $model = new AssessmentSlipModel();
        $OrSeries = $model->mCheckTaxCredSeries();
        $CityTreasurer = $model->mGetCityTreasurer();
        $taxCreditResult = $model->mgetTaxCreditAmount($id);
        $taxCreditAmount = '';

        foreach($taxCreditResult as $k) {
            $taxCreditAmount = $k->taxCredit;
        }



        $TaxCreditSaveArray = array(
            'application_id'    =>  $id,
            'series'            =>  $OrSeries,
            'credit_amount'     =>  $taxCreditAmount,
            'city_treasurer'            =>  $CityTreasurer,
            'remarks'            =>  'ISSUED',
            'issued_at' => date('Y-m-d H:i:s'),
            'issued_by' => $session->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $session->get('id'),
        );

        if($model->mSaveTaxCredit($TaxCreditSaveArray)){

            $busDetails = $model->mgetBusinessDetails($id);
            $result = array('status' => 200,'message'=> "Series Generated","OrSeries"=>$OrSeries,'busDetails'=>$busDetails);
            // 
            // Set the data as flashdata
            foreach ($result as $key => $value) {
                $session->setFlashdata($key, $value);
            }

            // print_r($session->getFlashdata());
            // die();
            return $this->response->setJSON($result);
        }



       
    }

    public function generate_pdf() {
        // create new PDF document
        $session = session();
        helper('number'); 
        // echo numberToWords(1578.54);
        // die();
        $SeriesNo = $session->getFlashdata('OrSeries');
        $blpdNumber= $session->getFlashdata('busDetails')['blpdno'];
        $busName=$session->getFlashdata('busDetails')['business_name'];
        $taxCred=$session->getFlashdata('busDetails')['taxCredit'];
        $taxCredAmount=strtoupper(numberToWords($taxCred));
        $dateIssued=date('M-d-YY');
        $busAddress=$session->getFlashdata('busDetails')['taxpayer_address'];;
  
 
 
     $pdf = new PdfLibrary('P', 'mm', 'A4', true, 'UTF-8', false);
 
 // set document information
 // $pdf->SetCreator('KENNETH');
 // $pdf->SetAuthor('Nicola Asuni');
 $pdf->SetTitle('CERTIFICATE OF TAX CREDIT (CoTC)');
 $pdf->SetSubject('TCPDF Tutorial');
 $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
 
 // set default monospaced font
 $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 // set margins
 $pdf->SetMargins(10,1, 10);
 // set auto page breaks
 $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
 
 // set image scale factor
 $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
 
 // ---------------------------------------------------------
 
 // set font
 $pdf->SetFont('dejavusans', '', 10);
 $pdf->setPrintHeader(false);
 $pdf->setPrintFooter(false);
 // add a page
 // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 // Print a table
 
 // add a page
 $pdf->AddPage();
 
 $html = '
 <style>
   .custom-height {
     line-height: 1.0;
   }
   .custom-height2{
     line-height: 1.5;
   }
 </style>
 <div style="text-align:center">
 <table style="width:100%; border-collapse:collapse;text-align:left;font-size: 11px;">
     <tr>
         <td style="width:5%"></td>
         <td style="width:15%"></td>
         <td style="width:20%"></td>
         <td style="width:20%"></td>
         <td style="width:20%"></td>
         <td style="width:15%"></td>
         <td style="width:5%"></td>
     </tr>
    
     <tr >
         
         <td colspan="7" style="text-align:center">
        
         <b><label>Province of Pampanga </label><br><br>
         <label>City of San Fernando</label><br><br><br></b>
       
         <label style="font-size: 15px; font-weight: bold;">CERTIFICATE OF TAX CREDIT (CoTC)</label><br>
         </td>
         
     </tr>
    <tr class="custom-height2">
    <td colspan="4"></td>
        <td colspan="3" style="border-bottom: 1px solid #000000;">
        CoTC NO.: '.$SeriesNo.'
        </td>
    </tr>
    <tr class="custom-height2">
    <td colspan="4"></td>
        <td colspan="3" style="border-bottom: 1px solid #000000;">
        Date : '.date('M d, Y').'
        </td>
    </tr>
    <tr class="custom-height2">
    <td colspan="4" style="border-bottom: 1px solid #000000;">
    '.$busName.'
    </td>
        <td colspan="3"></td>
    </tr>
    ';
    if (strlen($busAddress) > 60) {
        // Kung higit sa 40 na karakter ang haba ng $busAddress, hatiin ito sa dalawang bahagi
        $firstPart = substr($busAddress, 0, 60);
        $secondPart = substr($busAddress, 60);
        
        // Simulan ang HTML para sa unang <tr>
        $html .= '<tr class="custom-height2">';
        $html .= '<td colspan="4" style="border-bottom: 1px solid #000000; font-size: 12px;">' . $firstPart . '</td>';
        $html .= '<td colspan="3"></td>';
        $html .= '</tr>';
        
        // Simulan ang HTML para sa pangalawang <tr>
        $html .= '<tr class="custom-height2">';
        $html .= '<td colspan="4" style="border-bottom: 1px solid #000000; font-size: 12px;">' . $secondPart . ', City of San Fernando, Pampanga</td>';
        $html .= '<td colspan="3"></td>';
        $html .= '</tr>';
    } else {
        // Kung hindi higit sa 40 ang karakter ng $busAddress, ilagay ito sa isang <tr> lang
        $html .= '<tr class="custom-height2">';
        $html .= '<td colspan="4" style="border-bottom: 1px solid #000000; font-size: 12px;">' . $busAddress . ',</td>';
        $html .= '<td colspan="3"></td>';
        $html .= '</tr>';

         // Simulan ang HTML para sa pangalawang <tr>
         $html .= '<tr class="custom-height2">';
         $html .= '<td colspan="4" style="border-bottom: 1px solid #000000; font-size: 12px;">City of San Fernando, Pampanga</td>';
         $html .= '<td colspan="3"></td>';
         $html .= '</tr>';
    }
    $html .='


     <tr class="custom-height2">
         <td colspan="7">
         <p style="font-size: 12px;">Sir/Madam:</p>
         <p style="text-align:justify; text-indent: 40px; font-size: 12px;">Please be informed that this Certificate of Tax Credit No. <u><b>'.$SeriesNo.'</b></u> in the amount of PESOS:
            <u><b>'.$taxCredAmount.' ('.$taxCred.')'.'</b></u>, is issued on even
            date in your favor, covering the tax year 2022 representing overpayment made on the business tax
            of year 2023.<br><br>
            It is further informed that this tax credit may be applied to future payments of the business taxes
            due to the city on the registered business described hereunder.</p>
         <br>
         </td>
     </tr>

     <tr class="custom-height2">
     <td></td>
     <td colspan="2" style="text-align:left; font-style: 12px; padding-left:40px">Business Control Number : </td>
         <td colspan="4" style="border-bottom: 1px solid #000000;font-weight:bold;">
         '.$blpdNumber.'
         </td>
     </tr>

     <tr class="custom-height2">
     <td></td>
     <td colspan="2" style="text-align:left; font-style: 12px; padding-left:40px">Name of Owner : </td>
         <td colspan="4" style="border-bottom: 1px solid #000000;font-weight:bold;">
         '.$busName.'
         </td>
     </tr>

     <tr class="custom-height2">
     <td></td>
     <td colspan="2" style="text-align:left; font-style: 12px; padding-left:40px">Name of Business : </td>
         <td colspan="4" style="border-bottom: 1px solid #000000;font-weight:bold;">
         '.$busName.'
         </td>
     </tr>

     <tr class="custom-height2">
     <td></td>
     <td colspan="2" style="text-align:left; font-style: 12px; padding-left:40px">Nature of Business : </td>
         <td colspan="4" style="border-bottom: 1px solid #000000;font-weight:bold;">
         '.$busName.'
         </td>
     </tr>';
    

     if (strlen($busAddress) > 60) {
        // Kung higit sa 40 na karakter ang haba ng $busAddress, hatiin ito sa dalawang bahagi
        $firstPart = substr($busAddress, 0, 60);
        $secondPart = substr($busAddress, 60);
        
        // Simulan ang HTML para sa unang <tr>
        $html .= '<tr class="custom-height2"> <td></td>';
        
        $html .= '<td colspan="2" style="text-align:left; font-style: 11px; padding-left:40px">Address : </td>';
        $html .= '<td colspan="4" style="border-bottom: 1px solid #000000; font-weight:bold;">' . $firstPart . '</td>';
        $html .= '</tr>';
        
        // Simulan ang HTML para sa pangalawang <tr>
        $html .= '<tr class="custom-height2"> <td></td>';
        $html .= '<td colspan="2"></td>';
        $html .= '<td colspan="4" style="border-bottom: 1px solid #000000; font-weight:bold;">' . $secondPart . ', City of San Fernando, Pampanga</td>';
        $html .= '</tr>';
    } else {
        // Kung hindi higit sa 40 ang karakter ng $busAddress, ilagay ito sa isang <tr> lang
        $html .= '<tr class="custom-height2"> <td></td>';
        $html .= '<td colspan="2" style="text-align:left; font-style: 12px; padding-left:40px">Address : </td>';
        $html .= '<td colspan="4" style="border-bottom: 1px solid #000000; font-weight:bold;">' . $busAddress . ',</td>';
       
        $html .= '</tr>';

         // Simulan ang HTML para sa pangalawang <tr>
         $html .= '<tr class="custom-height2"> <td></td>';
         $html .= '<td colspan="2"></td>';
         $html .= '<td colspan="4" style="border-bottom: 1px solid #000000; font-weight:bold;">City of San Fernando, Pampanga</td>';
         
         $html .= '</tr>';
    }

    





     $html .='
     <tr class="custom-height2">
     <td colspan="7">
    
     <p style="text-align:justify; text-indent: 40px; font-size: 12px;">This certification is issued upon the request of above company, for record purposes. Given this
     20th day of October Year 2023 at the City of San Fernando, Pampanga.</p>
     <br>
     </td>
 </tr>

    
 <tr class="custom-height2">
 <td></td>
 <td colspan="3"></td>
 <td colspan="2" style="font-weight:bold;text-align:left">Very truly yours,
 <br><br><br>
 </td>
 <td></td>
 </tr>
    
 <tr class="custom-height2">
 <td></td>
 <td colspan="3"></td>
 <td colspan="2" style="border-bottom: 1px solid #000000; font-weight:bold;text-align:center">Ms. Mary Ann P. Bautista</td>
 <td></td>
 </tr>

 <tr class="custom-height2">
 <td></td>
 <td colspan="3"></td>
 <td colspan="2" style="text-align:center">City Treasurer</td>
 <td></td>
 </tr>

 <tr class="custom-height2">
 <td></td>
 <td colspan="2" style="border-bottom: 1px solid #000000; font-weight:bold;text-align:left">
 <br><br>
 Received by : </td>
 <td colspan="4" ></td>
 <td></td>
 </tr>

 <tr class="custom-height2">
 <td></td>
 <td colspan="2" style="border-bottom: 1px solid #000000; font-weight:bold;text-align:left">
 
 Date : </td>
 <td colspan="4" ></td>
 <td></td>
 </tr>
 </table>
 </div>
 ';
 $pdf->writeHTML($html, true, false, true, false, '');
 
 
 
 // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 // Print a table
 
 
 // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 // Print a table
 
 $this->response->setHeader("Content-Type", "application/pdf");
 $pdf->Output('TaxCredit.pdf', 'I');
         
     }

     public function formatDate($dateString) {
        // Convert date string to timestamp
        $timestamp = strtotime($dateString);
        
        // Format the timestamp into 'Month Day, Year' format
        $formattedDate = date('F d, Y', $timestamp);
        
        return $formattedDate;
    }  


    

}
//    