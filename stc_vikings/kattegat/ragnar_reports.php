<?php
include "../../MCU/obdb.php";
session_start();
// reports merchant ledger
class ragnarReportsViewMerchantLedger extends tesseract{
   // call merchant
   public function stc_call_merchants(){
      $ivar='<option value="NA">Please Select Merchant</option>';
      $ivarmerchantsqry=mysqli_query($this->stc_dbs, "
         SELECT 
            `stc_merchant_id`,
            `stc_merchant_name` 
         FROM `stc_merchant` 
         ORDER BY `stc_merchant_name` ASC
      ");
      foreach($ivarmerchantsqry as $ivarrow){
         $ivar.='
            <option value="'.$ivarrow['stc_merchant_id'].'">'.$ivarrow['stc_merchant_name'].'</option>
         ';
      }
      return $ivar;
   }
    
   // call ledger
   public function stc_call_ledger($bjornebegdate, $bjorneenddate, $bjornemerchantid){
      $ivar='';
      $invoicetotal=0;
      $paidtotal=0;
      $duetotal=0;
      // get merchant qeury
      $merchantget=mysqli_query($this->stc_dbs, "
         SELECT 
            `stc_merchant_name` 
         FROM 
            `stc_merchant` 
         WHERE `stc_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $bjornemerchantid)."'
      ");
      $mername='';
      // get merchant loop
      foreach($merchantget as $merchantrow){
         $mername=$merchantrow['stc_merchant_name'];
      }

      // merchant name row
      $ivar.='
         <tr>
            <td colspan="2" align="Right"><h3><b>Merchant Name :</b></h3></td>
            <td colspan="2"><h3><b>'.$mername.'</b></h3></td>
         </tr>
      ';

      // grn query & grn loop
      $ivarledgerqry=mysqli_query($this->stc_dbs, "
         SELECT 
            `stc_product_grn_id`,
            `stc_product_grn_date`,
            `stc_product_grn_purchase_order_id`,
            `stc_product_grn_purchase_order_date`,
            `stc_product_grn_invoice_number`,
            `stc_product_grn_invoice_date`,
            `stc_merchant_name`
         FROM 
            `stc_product_grn` 
         INNER JOIN 
            `stc_merchant` 
         ON 
            `stc_merchant_id`=`stc_product_grn_merchant_id` 
         WHERE 
            `stc_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $bjornemerchantid)."'
         AND
            (DATE(`stc_product_grn_invoice_date`) 
         BETWEEN
            '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."'
         AND 
            '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."')
         ORDER BY DATE(`stc_product_grn_invoice_date`) ASC 
      ");

      if(mysqli_num_rows($ivarledgerqry)>0){
         foreach($ivarledgerqry as $ledgerrow){
            if(isset($_SESSION["stc_merchant_invoice_sort"])) {  
               $is_available = 0;
               foreach($_SESSION["stc_merchant_invoice_sort"] as $keys => $values){  
               }  
               if($is_available < 1) {  
                  $item_array = array(  
                     'ledger_poid'        => $ledgerrow['stc_product_grn_purchase_order_id'],
                     'ledger_podate'      => $ledgerrow['stc_product_grn_purchase_order_date'],
                     'ledger_id'          => $ledgerrow['stc_product_grn_id'],
                     'ledger_date'        => $ledgerrow['stc_product_grn_date'],
                     'ledger_invoice_no'  => $ledgerrow['stc_product_grn_invoice_number'],
                     'ledger_invoice_date'   => $ledgerrow['stc_product_grn_invoice_date'],
                     'ledger_type'        => 'grn'
                  );  
                  $_SESSION["stc_merchant_invoice_sort"][] = $item_array;  
               }
            }else{  
               $item_array = array(  
                  'ledger_poid'        => $ledgerrow['stc_product_grn_purchase_order_id'],
                  'ledger_podate'      => $ledgerrow['stc_product_grn_purchase_order_date'],
                  'ledger_id'          => $ledgerrow['stc_product_grn_id'],
                  'ledger_date'        => $ledgerrow['stc_product_grn_date'],
                  'ledger_invoice_no'  => $ledgerrow['stc_product_grn_invoice_number'],
                  'ledger_invoice_date'   => $ledgerrow['stc_product_grn_invoice_date'],
                  'ledger_type'        => 'grn'
               );  
               $_SESSION["stc_merchant_invoice_sort"][] = $item_array;  
            }  
         }
      }

      // direct challan query
      $ivarledgerqry2=mysqli_query($this->stc_dbs, "
         SELECT
             `stc_sale_product_id`,
             `stc_sale_product_date`,
             `stc_sale_product_dc_invo_no`,
             `stc_sale_product_dc_invo_date`,
             `stc_merchant_name`
         FROM
             `stc_sale_product`
         INNER JOIN `stc_merchant` ON `stc_merchant_id` = `stc_sale_product_dc_merchant`
         WHERE
            `stc_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $bjornemerchantid)."'
         AND
            (DATE(`stc_sale_product_dc_invo_date`) 
         BETWEEN
            '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."'
         AND 
            '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."')
         ORDER BY DATE(`stc_sale_product_dc_invo_date`) ASC 
      ");

      if(mysqli_num_rows($ivarledgerqry2)>0){
         foreach($ivarledgerqry2 as $ledgerrow){
            if(isset($_SESSION["stc_merchant_invoice_sort"])) {  
               $is_available = 0;
               foreach($_SESSION["stc_merchant_invoice_sort"] as $keys => $values){  
                  if($_SESSION["stc_merchant_invoice_sort"][$keys]['ledger_id'] == $ledgerrow['stc_sale_product_id']){  
                     $is_available++; 
                  }  
               }  
               if($is_available < 1) {  
                  $item_array = array(  
                     'ledger_poid'        => '',
                     'ledger_podate'      => '',
                     'ledger_id'          => $ledgerrow['stc_sale_product_id'],
                     'ledger_date'        => $ledgerrow['stc_sale_product_date'],
                     'ledger_invoice_no'  => $ledgerrow['stc_sale_product_dc_invo_no'],
                     'ledger_invoice_date'   => $ledgerrow['stc_sale_product_dc_invo_date'],
                     'ledger_type'        => 'dc'
                  );  
                  $_SESSION["stc_merchant_invoice_sort"][] = $item_array;  
               }
            }else{  
               $item_array = array(  
                  'ledger_poid'        => '',
                  'ledger_podate'      => '',
                  'ledger_id'          => $ledgerrow['stc_sale_product_id'],
                  'ledger_date'        => $ledgerrow['stc_sale_product_date'],
                  'ledger_invoice_no'  => $ledgerrow['stc_sale_product_dc_invo_no'],
                  'ledger_invoice_date'   => $ledgerrow['stc_sale_product_dc_invo_date'],
                  'ledger_type'        => 'dc'
               );  
               $_SESSION["stc_merchant_invoice_sort"][] = $item_array;  
            }  
         }
      }

      // po query & loops
      $ivarledgerqry3=mysqli_query($this->stc_dbs, "
         SELECT
            `stc_purchase_product_id`,
            `stc_purchase_product_order_date`,
            `stc_merchant_name`
         FROM
            `stc_purchase_product`
         INNER JOIN 
            `stc_merchant_advance_payment` 
         ON 
            `stc_merchant_advance_payment_purhcase_product_id` = `stc_purchase_product_id`
         INNER JOIN 
            `stc_merchant` 
         ON 
            `stc_merchant_id` = `stc_purchase_product_merchant_id`
         WHERE
            `stc_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $bjornemerchantid)."'
         AND
            (DATE(`stc_purchase_product_order_date`) 
         BETWEEN
            '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."'
         AND 
            '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."')
         ORDER BY DATE(`stc_purchase_product_order_date`) ASC 
      ");

      if(mysqli_num_rows($ivarledgerqry3)>0){
         foreach($ivarledgerqry3 as $ledgerrow){
            if(isset($_SESSION["stc_merchant_invoice_sort"])) {  
               $is_available = 0;
               foreach($_SESSION["stc_merchant_invoice_sort"] as $keys => $values){  
                  if($_SESSION["stc_merchant_invoice_sort"][$keys]['ledger_id'] == $ledgerrow['stc_purchase_product_id']){  
                     $is_available++; 
                  }  
               }  
               if($is_available < 1) {  
                  $item_array = array(  
                     'ledger_poid'        => $ledgerrow['stc_purchase_product_id'],
                     'ledger_podate'      => $ledgerrow['stc_purchase_product_order_date'],
                     'ledger_id'          => '',
                     'ledger_date'        => '',
                     'ledger_invoice_no'  => '',
                     'ledger_invoice_date'   => '',
                     'ledger_type'        => 'po'
                  );  
                  $_SESSION["stc_merchant_invoice_sort"][] = $item_array;  
               }
            }else{  
               $item_array = array(  
                  'ledger_poid'        => $ledgerrow['stc_purchase_product_id'],
                  'ledger_podate'      => $ledgerrow['stc_purchase_product_order_date'],
                  'ledger_id'          => '',
                  'ledger_date'        => '',
                  'ledger_invoice_no'  => '',
                  'ledger_invoice_date'   => '',
                  'ledger_type'        => 'po'
               );  
               $_SESSION["stc_merchant_invoice_sort"][] = $item_array;  
            }  
         }
      }

      // loop for get grn or direct challan records from session
      foreach($_SESSION['stc_merchant_invoice_sort'] as $reportsessrow){
         $basicamount=0;
         $totalamount=0;
         $total=0;
         $refr_id='';
         $po_id='';
         $pv='';
         $paidamount='';
         if($reportsessrow['ledger_type']=='grn'){
            $po_id='STC/'.substr("0000{$reportsessrow["ledger_poid"]}", -5);
            $refr_id='GRN/'.substr("0000{$reportsessrow["ledger_id"]}", -5);
         }elseif($reportsessrow['ledger_type']=='dc'){
            $refr_id='STC/DC/'.substr("0000{$reportsessrow["ledger_id"]}", -5);
         }else{
            $po_id='STC/'.substr("0000{$reportsessrow["ledger_poid"]}", -5);
            $pv='';
         }

         // check ledger type means grn or direct challan
         if($reportsessrow['ledger_type']=='grn'){
            // invoice amount get query & loops
            $invoicerateqry=mysqli_query($this->stc_dbs, "
               SELECT
                  `stc_product_grn_items_qty`,
                  `stc_product_grn_items_rate`,
                  `stc_product_gst`
               FROM 
                  `stc_product_grn_items`
               INNER JOIN 
                  `stc_product` 
               ON 
                  `stc_product_id` = `stc_product_grn_items_product_id`
               WHERE
                   `stc_product_grn_items_grn_order_id`='".mysqli_real_escape_string($this->stc_dbs, $reportsessrow['ledger_id'])."'
            ");

            foreach($invoicerateqry as $itemsraterow){
               $amount=0;
               $gstamount=0;
               $bqrty=$itemsraterow['stc_product_grn_items_qty'];
               $bqrate=$itemsraterow['stc_product_grn_items_rate'];
               $amount=$bqrty * $bqrate;
               $gstamount=($amount * $itemsraterow["stc_product_gst"])/100;
               $basicamount+=$amount;
               $totalamount+=$gstamount;
            }

            // regular payment query & loops
            $rppaymentqry=mysqli_query($this->stc_dbs, "
               SELECT
                  `stc_merchant_regular_payment_id`,
                  `stc_merchant_regular_payment_date`,
                  `stc_merchant_regular_payment_amount`,
                  `stc_merchant_regular_payment_payment_type`
               FROM
                  `stc_merchant_regular_payment`
               WHERE
                  `stc_merchant_regular_payment_grn_number`='".mysqli_real_escape_string($this->stc_dbs, $reportsessrow['ledger_id'])."'
            ");

            foreach($rppaymentqry as $rppaymentrow){
               $pv.=
                  'RP/'.substr("0000{$rppaymentrow['stc_merchant_regular_payment_id']}", -5).'<br>'
                  .date('d-m-Y', strtotime($rppaymentrow['stc_merchant_regular_payment_date'])).'<br>'
                  .$rppaymentrow['stc_merchant_regular_payment_payment_type']
               ;
               $paidamount.=number_format($rppaymentrow['stc_merchant_regular_payment_amount'], 2).'<br>';
               $paidtotal += $rppaymentrow['stc_merchant_regular_payment_amount'];
            }
         }elseif($reportsessrow['ledger_type']=='dc'){
            // invoice amount query
            $invoicerateqry=mysqli_query($this->stc_dbs, "
               SELECT
                  `stc_sale_product_dc_items_product_qty`,
                  `stc_sale_product_dc_items_product_rate`,
                  `stc_product_gst`
               FROM 
                  `stc_sale_product_dc_items`
               INNER JOIN 
                  `stc_product` 
               ON 
                  `stc_product_id` = `stc_sale_product_dc_items_product_id`
               WHERE
                   `stc_sale_product_dc_items_sale_product_id`='".mysqli_real_escape_string($this->stc_dbs, $reportsessrow['ledger_id'])."'
            ");

            foreach($invoicerateqry as $itemsraterow){
               $amount=0;
               $gstamount=0;
               $bqrty=$itemsraterow['stc_sale_product_dc_items_product_qty'];
               $bqrate=$itemsraterow['stc_sale_product_dc_items_product_rate'];
               $amount=$bqrty * $bqrate;
               $gstamount=($amount * $itemsraterow["stc_product_gst"])/100;
               $basicamount+=$amount;
               $totalamount+=$gstamount;
            }

            // dc payment founder qry & loop
            $dcpaymentqry=mysqli_query($this->stc_dbs, "
               SELECT
                  `stc_sale_product_dc_payment_id`,
                   `stc_sale_product_dc_payment_date`,
                  `stc_sale_product_dc_payment_value`,
                  `stc_sale_product_dc_payment_pay_type`
               FROM 
                  `stc_sale_product_dc_payment` 
               WHERE 
                  `stc_sale_product_dc_payment_order_id`='".mysqli_real_escape_string($this->stc_dbs, $reportsessrow['ledger_id'])."'
            ");

            foreach($dcpaymentqry as $dcpaymentrow){
               $pv.=
                  'STC/DC/PV/'.substr("0000{$dcpaymentrow['stc_sale_product_dc_payment_id']}", -5).'<br>'
                  .date('d-m-Y', strtotime($dcpaymentrow['stc_sale_product_dc_payment_date'])).'<br>'
                  .$dcpaymentrow['stc_sale_product_dc_payment_pay_type']
               ;
               $paidamount.=number_format($dcpaymentrow['stc_sale_product_dc_payment_value'], 2).'<br>';
               $paidtotal += $dcpaymentrow['stc_sale_product_dc_payment_value'];
            }
         }else{
            // advance payment founder qry & loop
            $appaymentqry=mysqli_query($this->stc_dbs, "
               SELECT
                  `stc_merchant_advance_payment_id`,
                  `stc_merchant_advance_payment_date`,
                  `stc_merchant_advance_payment_advance_value`,
                  `stc_merchant_advance_payment_payment_type`
               FROM
                  `stc_merchant_advance_payment`
               WHERE
                  `stc_merchant_advance_payment_purhcase_product_id`='".mysqli_real_escape_string($this->stc_dbs, $reportsessrow['ledger_poid'])."'
            ");

            foreach($appaymentqry as $popaymentrow){
               $pv.=
                  'AP/'.substr("0000{$popaymentrow['stc_merchant_advance_payment_id']}", -5).'<br>'
                  .date('d-m-Y', strtotime($popaymentrow['stc_merchant_advance_payment_date'])).'<br>'
                  .$popaymentrow['stc_merchant_advance_payment_payment_type']
               ;
               $paidamount.=number_format($popaymentrow['stc_merchant_advance_payment_advance_value'], 2).'<br>';
               $paidtotal += $popaymentrow['stc_merchant_advance_payment_advance_value'];
            }
         }
         // calculate total for plus minus in total section
         $total=$basicamount + $totalamount;
         
         $invoicetotal += $total;
         // middle grn or direct challan section row
         $podate=(!empty($reportsessrow['ledger_podate']) ? date('d-m-Y', strtotime($reportsessrow['ledger_podate'])) : '');

         if(!empty($basicamount)){
            $ratesec=
               number_format($basicamount, 2).'<br>
               '.number_format($totalamount, 2).'<br>
               -------------'.'<br>
               '.number_format($total, 2)
            ;
         }else{
            $ratesec='';
         }
         $ivar.='
            <tr>
               <td class="text-center">
                  '.$po_id.'<br>
                  '.$podate.'
               </td>             
               <td class="text-center">
                  '.$refr_id.'<br>
                  '.(!empty($reportsessrow['ledger_date']) ? date('d-m-Y', strtotime($reportsessrow['ledger_date'])) : '').'
               </td>
               <td class="text-center">
                  '.$reportsessrow['ledger_invoice_no'].'<br>
                  '.(!empty($reportsessrow['ledger_invoice_date']) ? date('d-m-Y', strtotime($reportsessrow['ledger_invoice_date'])) : '').'
               </td>
               <td align="right">
                  '.$ratesec.'
               </td>
               <td align="right"></td>
            </tr>
         ';

         // middle & payment voucher section row
         $ivar.='
            <tr>
               <td></td>
               <td>
                  '.$pv.'
               </td>
               <td></td>
               <td></td>
               <td align="right">'.$paidamount.'</td>
            </tr>
         ';
      }

      // perform whether number is not in minus, greater than or smaller then
      if($invoicetotal>=$paidtotal){
         $duetotal=$invoicetotal-$paidtotal;
      }else{
         $duetotal=$paidtotal-$invoicetotal;
      }

      // if due is in .or something than show yes
      if($duetotal<=1){
         $duetotal=0;
      }
      // total section row
      $ivar.='
         <tr>
            <td colspan="3" align="right">
               <h4><b>Total Invoice Amount :</b></h4>
            </td>
            <td align="right">
               '.number_format($invoicetotal, 2).'
            </td>
            <td align="right"></td>
         </tr>
         <tr>
            <td colspan="3" align="right">
               <h4><b>Total Paid Amount :</b></h4>
            </td>
            <td align="right"></td>
            <td align="right">
               '.number_format($paidtotal, 2).'
            </td>
         </tr>
         <tr>
            <td colspan="3" align="right">
               <h4><b>Total Due Amount :</b></h4>
            </td>
            <td align="right"></td>
            <td align="right">
               '.number_format($duetotal, 2).'
            </td>
         </tr>
      '; 
      return $ivar;
   }
}

class ragnarReportsViewRequiReports extends tesseract{
   // call departments
   public function stc_call_departments($location){
      $ivar='';
      $ivar_query=mysqli_query($this->stc_dbs, "
         SELECT * FROM `stc_status_down_list_department`
         WHERE `stc_status_down_list_department_location`='".mysqli_real_escape_string($this->stc_dbs, $location)."'
         ORDER BY `stc_status_down_list_department_dept` ASC
      ");
      if(mysqli_num_rows($ivar_query)>0){
         $ivar='<option value="NA">SELECT</option>';
         foreach($ivar_query as $ivar_row){
            $ivar.='<option>'.$ivar_row['stc_status_down_list_department_dept'].'</option>';
         }
      }else{
         $ivar='<option value="NA">NO DEPARTMENT FOUND</option>';
      }
      return $ivar;
   }

   // call std
   public function stc_call_std($datefrom, $dateto, $location, $department, $typeofjob, $status, $pagination, $filter, $type){
      $ivar='';
      $paginationfilter="LIMIT ".$pagination.", 20";
      $exportbtnname='stc-sdl-exportexcel-hit';
      if($type=='print'){$exportbtnname='stc-sdl-exportexcel-hit2';$paginationfilter='';}
      $query_filter = '';
      if($filter == 1){
         $datefilter='';
         if($datefrom!=""){
            $query_filter.="
               AND DATE(`stc_status_down_list_date`) BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $datefrom)."' 
               AND '".mysqli_real_escape_string($this->stc_dbs, $dateto)."'
            ";
         }
         if($location!="NA"){
            $query_filter.="AND `stc_status_down_list_plocation`='".mysqli_real_escape_string($this->stc_dbs, $location)."'";
         }
         if($department!="NA"){
            $query_filter.="AND `stc_status_down_list_sub_location`='".mysqli_real_escape_string($this->stc_dbs, $department)."'";
         }
         if($typeofjob!="NA"){
            $query_filter.="AND `stc_status_down_list_jobtype`='".mysqli_real_escape_string($this->stc_dbs, $typeofjob)."'";
         }
         if($status!="NA"){
            $query_filter.="AND `stc_status_down_list_status`='".mysqli_real_escape_string($this->stc_dbs, $status)."'";
         }
      }else{
         $query_filter='AND `stc_status_down_list_date`> NOW() - INTERVAL 48 HOUR';
      }
      $query ="
         SELECT 
            `stc_status_down_list_id`,
            `stc_status_down_list_date`,
            `stc_status_down_list_plocation`,
            `stc_cust_project_title`,
            `stc_status_down_list_sub_location`,
            `stc_status_down_list_area`,
            `stc_status_down_list_equipment_type`,
            `stc_status_down_list_equipment_number`,
            `stc_status_down_list_equipment_status`,
            `stc_status_down_list_reason`,
            `stc_status_down_list_manpower_req`,
            `stc_status_down_list_material_desc`,
            `stc_status_down_list_from_date`,
            `stc_status_down_list_rect_date`,
            `stc_status_down_list_remarks`,
            `stc_status_down_list_jobdone_details`,
            `stc_status_down_list_jobpending_details`,
            `stc_status_down_list_jobtype`,
            `stc_status_down_list_qty`,
            `stc_status_down_list_capacity`,
            `stc_status_down_list_reasonattribute`,
            `stc_status_down_list_created_by_select`,
            `stc_status_down_list_permit_no`,
            `stc_status_down_list_creator_details`,
            `stc_status_down_list_responsive_person`,
            `stc_status_down_list_target_date`,
            `stc_status_down_list_fremarks`,
            `stc_status_down_list_ftarget_date`,
            `stc_status_down_list_status`,
            `stc_status_down_list_failurerootcost`,
            `stc_status_down_list_created_by`,
            `stc_cust_pro_supervisor_created_by`,
				`stc_status_down_list_wipstatus`
         FROM `stc_status_down_list` 
         LEFT JOIN `stc_cust_project` 
         ON `stc_cust_project_id`=`stc_status_down_list_location` 
         LEFT JOIN `stc_cust_pro_supervisor` 
         ON `stc_status_down_list_created_by`=`stc_cust_pro_supervisor_id` 
         WHERE `stc_status_down_list_equipment_type`<>'' AND `stc_status_down_list_date`<>'' ".$query_filter."         
         ORDER BY TIMESTAMP(`stc_status_down_list_date`) DESC ".$paginationfilter."
      ";
      $ivarqry=mysqli_query($this->stc_dbs, $query);

      $ivarpreqry=mysqli_query($this->stc_dbs, "
         SELECT 
             `stc_cust_project_title`,
             `stc_status_down_list_status`,
             `stc_status_down_list_date`,
             `stc_status_down_list_rect_date`,
             `stc_status_down_list_jobtype`
         FROM `stc_status_down_list` 
         LEFT JOIN `stc_cust_project` 
         ON `stc_cust_project_id`=`stc_status_down_list_location` 
         WHERE `stc_status_down_list_equipment_type`<>'' AND `stc_status_down_list_date`<>'' 
         ORDER BY TIMESTAMP(`stc_status_down_list_date`) DESC
      ");
      $sitename="";
      $callattend=0;
      $callattend48=0;

      // new calculation from summary of sdl
      $totalp=$totalwp=$totalwd=$totalpending=0;
      $atotalp=$atotalwp=$atotalwd=$atotalpending=0;

      $cplanning=$cjobdone=$cprogress=$cpendingjon=0;
      $bmplanning=$bmjobdone=$bmprogress=$bmpendingjon=0;
      $djaplanning=$djajobdone=$djaprogress=$djapendingjon=0;
      $pmplanning=$pmjobdone=$pmprogress=$pmpendingjon=0;
      $whplanning=$whjobdone=$whprogress=$whpendingjon=0;

      $cplanning48=$cjobdone48=$cprogress48=$cpendingjon48=0;
      $bmplanning48=$bmjobdone48=$bmprogress48=$bmpendingjon48=0;
      $djaplanning48=$djajobdone48=$djaprogress48=$djapendingjon48=0;
      $pmplanning48=$pmjobdone48=$pmprogress48=$pmpendingjon48=0;
      $whplanning48=$whjobdone48=$whprogress48=$whpendingjon48=0;
      
      $planning=0;
      $jobdone=0;
      $progress=0;
      $pendingjon=0;

      $planning48=0;
      $jobdone48=0;
      $progress48=0;
      $pendingjon48=0;

      $outputcheck = '';
      if(mysqli_num_rows($ivarpreqry)>0){
         $currenthr=date("Y/m/d");
         foreach($ivarpreqry as $prerow){
            $today = date("Y/m/d") ; 
            $startTimeStamp = strtotime(date('Y/m/d', strtotime($prerow['stc_status_down_list_date'])));
            $endTimeStamp = strtotime($today);

            $timeDiff = abs($endTimeStamp - $startTimeStamp);

            $dperiod = $timeDiff/86400;
            
            if($prerow['stc_status_down_list_jobtype']=="BREAKDOWN MAINTENANCE"){
               if($prerow['stc_status_down_list_status']==1){
                  $bmplanning++;
                  if ($dperiod<2) $bmplanning48++;
               }else if($prerow['stc_status_down_list_status']==2){
                  $bmpendingjon++;
                  if ($dperiod<2) $bmpendingjon48++;
               }else if($prerow['stc_status_down_list_status']==3){
                  $bmprogress++;
                  if ($dperiod<2) $bmprogress48++;
               }else if($prerow['stc_status_down_list_status']==4){
                  $bmjobdone++;
                  if ($dperiod<2) $bmjobdone48++;
               }
            }else if($prerow['stc_status_down_list_jobtype']=="CALL ATTEND"){
               if($prerow['stc_status_down_list_status']==1){
                  $cplanning++;
                  if ($dperiod<2) $cplanning48++;
               }else if($prerow['stc_status_down_list_status']==2){
                  $cpendingjon++;
                  if ($dperiod<2) $cpendingjon48++;
               }else if($prerow['stc_status_down_list_status']==3){
                  $cprogress++;
                  if ($dperiod<2) $cprogress48++;
               }else if($prerow['stc_status_down_list_status']==4){
                  $cjobdone++;
                  if ($dperiod<2) $cjobdone48++;
               }
            }else if($prerow['stc_status_down_list_jobtype']=="DAILY JOB ACTIVITY"){
               if($prerow['stc_status_down_list_status']==1){
                  $djaplanning++;
                  if ($dperiod<2) $djaplanning48++;
               }else if($prerow['stc_status_down_list_status']==2){
                  $djapendingjon++;
                  if ($dperiod<2) $djapendingjon48++;
               }else if($prerow['stc_status_down_list_status']==3){
                  $djaprogress++;
                  if ($dperiod<2) $djaprogress48++;
               }else if($prerow['stc_status_down_list_status']==4){
                  $djajobdone++;
                  if ($dperiod<2) $djajobdone48++;
               }
            }else if($prerow['stc_status_down_list_jobtype']=="PREVENTIVE MAINTENANCE"){
               if($prerow['stc_status_down_list_status']==1){
                  $pmplanning++;
                  if ($dperiod<2) $pmplanning48++;
               }else if($prerow['stc_status_down_list_status']==2){
                  $pmpendingjon++;
                  if ($dperiod<2) $pmpendingjon48++;
               }else if($prerow['stc_status_down_list_status']==3){
                  $pmprogress++;
                  if ($dperiod<2) $pmprogress48++;
               }else if($prerow['stc_status_down_list_status']==4){
                  $pmjobdone++;
                  if ($dperiod<2) $pmjobdone48++;
               }
            }else if($prerow['stc_status_down_list_jobtype']=="WINTER OVERHAULING"){
               if($prerow['stc_status_down_list_status']==1){
                  $whplanning++;
                  if ($dperiod<2) $whplanning48++;
               }else if($prerow['stc_status_down_list_status']==2){
                  $whpendingjon++;
                  if ($dperiod<2) $whpendingjon48++;
               }else if($prerow['stc_status_down_list_status']==3){
                  $whprogress++;
                  if ($dperiod<2) $whprogress48++;
               }else if($prerow['stc_status_down_list_status']==4){
                  $whjobdone++;
                  if ($dperiod<2) $whjobdone48++;
               }
            }
         }

         // new calculation from summary of sdl
         $bmplanningday = $bmplanning48 + $bmprogress48 + $bmjobdone48;
         $bmpendingday = $bmplanningday - $bmjobdone48;
         $cplanningday = $cplanning48 + $cprogress48 + $cjobdone48;
         $cpendingday = $cplanningday - $cjobdone48;
         $djaplanningday = $djaplanning48 + $djaprogress48 + $djajobdone48;
         $djapendingday = $djaplanningday - $djajobdone48;
         $pmplanningday = $pmplanning48 + $pmprogress48 + $pmjobdone48;
         $pmpendingday = $pmplanningday - $pmjobdone48;
         $whplanningday = $whplanning48 + $whprogress48 + $whjobdone48;
         $whpendingday = $whplanningday - $whjobdone48;
         
         $totalp+=$bmplanningday + $cplanningday + $djaplanningday + $pmplanningday + $whplanning48;
         $totalwp+=$bmprogress48 + $cprogress48 + $djaprogress48 + $pmprogress48 + $whprogress48;
         $totalwd+=$bmjobdone48 + $cjobdone48 + $djajobdone48 + $pmjobdone48 + $whjobdone48;
         $totalpending+=$bmpendingday + $cpendingday + $djapendingday + $pmpendingday + $whpendingday;
         
         $abmplanningday = $bmplanning + $bmprogress + $bmjobdone;
         $abmpendingday = $abmplanningday - $bmjobdone;
         $acplanningday = $cplanning + $cprogress + $cjobdone;
         $acpendingday = $acplanningday - $cjobdone;
         $adjaplanningday = $djaplanning + $djaprogress + $djajobdone;
         $adjapendingday = $adjaplanningday - $djajobdone;
         $apmplanningday = $pmplanning + $pmprogress + $pmjobdone;
         $apmpendingday = $apmplanningday - $pmjobdone;
         $awhplanningday = $whplanning + $whprogress + $whjobdone;
         $awhpendingday = $awhplanningday - $whjobdone;
         
         $atotalp+=$abmplanningday + $acplanningday + $adjaplanningday + $apmplanningday;
         $atotalwp+=$bmprogress + $cprogress + $djaprogress + $pmprogress;
         $atotalwd+=$bmjobdone + $cjobdone + $djajobdone + $pmjobdone;
         $atotalpending+=$abmpendingday + $acpendingday + $adjapendingday + $apmpendingday;

         $data_filter = array(
            '1' => 'SL NO',
            '2' => 'DATE',
            '3' => 'LOCATION',
            '6' => 'EQUIPMENT DETAILS',
            '11' => 'REASON ATTRIBUTE',
            '13' => 'STATUS',
            '14' => 'DELAY',
            '15' => 'PENDING DETAILS',
            '16' => 'FRC REPORTS',
            '17' => 'JOB DONE DETAILS',
            '18' => 'ANY COMMENT',
            '19' => 'TARGET DATE',
            '20' => 'SAFETY',
            '21' => 'REMARKS',
            '22' => 'ACTION'
         );
         
         $data_fields="";
         foreach($data_filter as $data_filter_key=>$data_filter_row){
            $data_fields.='<li class="mt-2"><input type="checkbox" class="stc-datatable-filter" style="display:block;float:left;position:relative;top:3px" id="'.$data_filter_key.'" value="'.$data_filter_key.'"><span class="filter-span">'.$data_filter_row.'</span></li>';
         }
         $ivar.='
            <table class="table table-bordered table-responsive" id="stc-show-std-detailspre-table">
               <tr>
                  <td class="text-center">DURATION</td>
                  <td class="text-center">LABEL</td>
                  <td class="text-center">PLANNING</td>
                  <td class="text-center">WORK-IN-PROGRESS</td>
                  <td class="text-center">WORK DONE</td>
                  <td class="text-center">PENDING</td>                  
                  <td class="text-center">FILTER</td>
               </tr>
         ';
         if($filter==1){
            $filteredqry="
               SELECT 
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'BREAKDOWN MAINTENANCE' AND `stc_status_down_list_status` = 1 THEN 1 ELSE 0 END) AS `bmplanning48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'BREAKDOWN MAINTENANCE' AND `stc_status_down_list_status` = 2 THEN 1 ELSE 0 END) AS `bmpendingjon48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'BREAKDOWN MAINTENANCE' AND `stc_status_down_list_status` = 3 THEN 1 ELSE 0 END) AS `bmprogress48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'BREAKDOWN MAINTENANCE' AND `stc_status_down_list_status` = 4 THEN 1 ELSE 0 END) AS `bmjobdone48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'CALL ATTEND' AND `stc_status_down_list_status` = 1 THEN 1 ELSE 0 END) AS `cplanning48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'CALL ATTEND' AND `stc_status_down_list_status` = 2 THEN 1 ELSE 0 END) AS `cpendingjon48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'CALL ATTEND' AND `stc_status_down_list_status` = 3 THEN 1 ELSE 0 END) AS `cprogress48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'CALL ATTEND' AND `stc_status_down_list_status` = 4 THEN 1 ELSE 0 END) AS `cjobdone48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'DAILY JOB ACTIVITY' AND `stc_status_down_list_status` = 1 THEN 1 ELSE 0 END) AS `djaplanning48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'DAILY JOB ACTIVITY' AND `stc_status_down_list_status` = 2 THEN 1 ELSE 0 END) AS `djapendingjon48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'DAILY JOB ACTIVITY' AND `stc_status_down_list_status` = 3 THEN 1 ELSE 0 END) AS `djaprogress48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'DAILY JOB ACTIVITY' AND `stc_status_down_list_status` = 4 THEN 1 ELSE 0 END) AS `djajobdone48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'PREVENTIVE MAINTENANCE' AND `stc_status_down_list_status` = 1 THEN 1 ELSE 0 END) AS `pmplanning48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'PREVENTIVE MAINTENANCE' AND `stc_status_down_list_status` = 2 THEN 1 ELSE 0 END) AS `pmpendingjon48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'PREVENTIVE MAINTENANCE' AND `stc_status_down_list_status` = 3 THEN 1 ELSE 0 END) AS `pmprogress48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'PREVENTIVE MAINTENANCE' AND `stc_status_down_list_status` = 4 THEN 1 ELSE 0 END) AS `pmjobdone48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'WINTER OVERHAULING' AND `stc_status_down_list_status` = 1 THEN 1 ELSE 0 END) AS `whplanning48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'WINTER OVERHAULING' AND `stc_status_down_list_status` = 2 THEN 1 ELSE 0 END) AS `whpendingjon48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'WINTER OVERHAULING' AND `stc_status_down_list_status` = 3 THEN 1 ELSE 0 END) AS `whprogress48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'WINTER OVERHAULING' AND `stc_status_down_list_status` = 4 THEN 1 ELSE 0 END) AS `whjobdone48`
                  FROM `stc_status_down_list` 
                  LEFT JOIN `stc_cust_project` 
                  ON `stc_cust_project_id`=`stc_status_down_list_location` 
                  WHERE `stc_status_down_list_equipment_type`<>'' AND `stc_status_down_list_date`<>'' ".$query_filter."
                  ORDER BY TIMESTAMP(`stc_status_down_list_date`) DESC
            ";
            $ivarpreqry=mysqli_query($this->stc_dbs, $filteredqry);
            if(mysqli_num_rows($ivarpreqry)>0){
               $result=mysqli_fetch_assoc($ivarpreqry);
               $totalp48 = $result['bmplanning48'] + $result['cplanning48'] + $result['djaplanning48'] + $result['pmplanning48'];
               $totalpending48 = $result['bmpendingjon48'] + $result['cpendingjon48'] + $result['djapendingjon48'] + $result['pmpendingjon48'];
               $totalwp48 = $result['bmprogress48'] + $result['cprogress48'] + $result['djaprogress48'] + $result['pmprogress48'];
               $totalwd48 = $result['bmjobdone48'] + $result['cjobdone48'] + $result['djajobdone48'] + $result['pmjobdone48'];
               $ivar.='
                     <tr style="background-color:white;">
                        <td class="text-center" rowspan="5">Filtered Result</td>
                        <td class="text-center">BREAKDOWN MAINTENANCE</td>
                        <td class="text-right" style="background-color: #00f9b4;">'.$result['bmplanning48'].'</td>
                        <td class="text-right" style="background-color: #f6f900;">'.$result['bmprogress48'].'</td>
                        <td class="text-right" style="background-color: #82f900;">'.$result['bmjobdone48'].'</td>
                        <td class="text-right" style="background-color: #ff4545;">'.$result['bmpendingjon48'].'</td>
                        <td class="text-center" rowspan="11">
                           <a href="#" class="btn btn-primary" data-toggle="modal" data-target=".bd-stdfilter-modal-lg">FILTER</a>
                           <ul class="btn btn-default stc-datatable-filter-ul"><a href="javascript:void(0)" class="data-fields-display btn btn-success">FIELDS</a>
                           '.$data_fields.'
                           </ul>
      
                        </td>
                     </tr>
                     <tr style="background-color:white;">
                        <td class="text-center">CALL ATTEND</td>
                        <td class="text-right" style="background-color: #00f9b4;">'.$result['cplanning48'].'</td>
                        <td class="text-right" style="background-color: #f6f900;">'.$result['cprogress48'].'</td>
                        <td class="text-right" style="background-color: #82f900;">'.$result['cjobdone48'].'</td>
                        <td class="text-right" style="background-color: #ff4545;">'.$result['cpendingjon48'].'</td>
                     </tr>
                     <tr style="background-color:white;">
                        <td class="text-center">DAILY JOB ACTIVITY</td>
                        <td class="text-right" style="background-color: #00f9b4;">'.$result['djaplanning48'].'</td>
                        <td class="text-right" style="background-color: #f6f900;">'.$result['djaprogress48'].'</td>
                        <td class="text-right" style="background-color: #82f900;">'.$result['djajobdone48'].'</td>
                        <td class="text-right" style="background-color: #ff4545;">'.$result['djapendingjon48'].'</td>
                     </tr>
                     <tr style="background-color:white;">
                        <td class="text-center">PREVENTIVE MAINTENANCE</td>
                        <td class="text-right" style="background-color: #00f9b4;">'.$result['pmplanning48'].'</td>
                        <td class="text-right" style="background-color: #f6f900;">'.$result['pmprogress48'].'</td>
                        <td class="text-right" style="background-color: #82f900;">'.$result['pmjobdone48'].'</td>
                        <td class="text-right" style="background-color: #ff4545;">'.$result['pmpendingjon48'].'</td>
                     </tr>
                     <tr style="background-color:white;border-bottom: 3px solid black;">
                        <td class="text-center">WINTER OVERHAULING</td>
                        <td class="text-right" style="background-color: #00f9b4;">'.$result['whplanning48'].'</td>
                        <td class="text-right" style="background-color: #f6f900;">'.$result['whprogress48'].'</td>
                        <td class="text-right" style="background-color: #82f900;">'.$result['whjobdone48'].'</td>
                        <td class="text-right" style="background-color: #ff4545;">'.$result['whpendingjon48'].'</td>
                     </tr>
                     <tr style="background-color:white;border-bottom: 3px solid black;">
                        <td class="text-center" colspan="2">TOTAL</td>
                        <td class="text-right"><b>'.$totalp48.'</b></td>
                        <td class="text-right"><b>'.$totalwp48.'</b></td>
                        <td class="text-right"><b>'.$totalwd48.'</b></td>
                        <td class="text-right"><b>'.$totalpending48.'</b></td>
                     </tr>
               ';
            }
         }
         if($filter==0){
            $filteredqry="
               SELECT 
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'BREAKDOWN MAINTENANCE' AND `stc_status_down_list_status` = 1 THEN 1 ELSE 0 END) AS `bmplanning48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'BREAKDOWN MAINTENANCE' AND `stc_status_down_list_status` = 2 THEN 1 ELSE 0 END) AS `bmpendingjon48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'BREAKDOWN MAINTENANCE' AND `stc_status_down_list_status` = 3 THEN 1 ELSE 0 END) AS `bmprogress48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'BREAKDOWN MAINTENANCE' AND `stc_status_down_list_status` = 4 THEN 1 ELSE 0 END) AS `bmjobdone48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'CALL ATTEND' AND `stc_status_down_list_status` = 1 THEN 1 ELSE 0 END) AS `cplanning48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'CALL ATTEND' AND `stc_status_down_list_status` = 2 THEN 1 ELSE 0 END) AS `cpendingjon48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'CALL ATTEND' AND `stc_status_down_list_status` = 3 THEN 1 ELSE 0 END) AS `cprogress48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'CALL ATTEND' AND `stc_status_down_list_status` = 4 THEN 1 ELSE 0 END) AS `cjobdone48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'DAILY JOB ACTIVITY' AND `stc_status_down_list_status` = 1 THEN 1 ELSE 0 END) AS `djaplanning48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'DAILY JOB ACTIVITY' AND `stc_status_down_list_status` = 2 THEN 1 ELSE 0 END) AS `djapendingjon48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'DAILY JOB ACTIVITY' AND `stc_status_down_list_status` = 3 THEN 1 ELSE 0 END) AS `djaprogress48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'DAILY JOB ACTIVITY' AND `stc_status_down_list_status` = 4 THEN 1 ELSE 0 END) AS `djajobdone48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'PREVENTIVE MAINTENANCE' AND `stc_status_down_list_status` = 1 THEN 1 ELSE 0 END) AS `pmplanning48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'PREVENTIVE MAINTENANCE' AND `stc_status_down_list_status` = 2 THEN 1 ELSE 0 END) AS `pmpendingjon48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'PREVENTIVE MAINTENANCE' AND `stc_status_down_list_status` = 3 THEN 1 ELSE 0 END) AS `pmprogress48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'PREVENTIVE MAINTENANCE' AND `stc_status_down_list_status` = 4 THEN 1 ELSE 0 END) AS `pmjobdone48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'WINTER OVERHAULING' AND `stc_status_down_list_status` = 1 THEN 1 ELSE 0 END) AS `whplanning48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'WINTER OVERHAULING' AND `stc_status_down_list_status` = 2 THEN 1 ELSE 0 END) AS `whpendingjon48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'WINTER OVERHAULING' AND `stc_status_down_list_status` = 3 THEN 1 ELSE 0 END) AS `whprogress48`,
                  SUM(CASE WHEN `stc_status_down_list_jobtype` = 'WINTER OVERHAULING' AND `stc_status_down_list_status` = 4 THEN 1 ELSE 0 END) AS `whjobdone48`
               FROM `stc_status_down_list` 
               LEFT JOIN `stc_cust_project` ON `stc_cust_project_id`=`stc_status_down_list_location` 
               WHERE `stc_status_down_list_equipment_type`<>'' AND `stc_status_down_list_date`<>'' AND `stc_status_down_list_date`> NOW() - INTERVAL 48 HOUR ORDER BY TIMESTAMP(`stc_status_down_list_date`) DESC
            ";
            $ivarpreqry=mysqli_query($this->stc_dbs, $filteredqry);
            if(mysqli_num_rows($ivarpreqry)>0){
               $result=mysqli_fetch_assoc($ivarpreqry);
               $totalp48 = $result['bmplanning48'] + $result['cplanning48'] + $result['djaplanning48'] + $result['pmplanning48'];
               $totalwp48 = $result['bmprogress48'] + $result['cprogress48'] + $result['djaprogress48'] + $result['pmprogress48'];
               $totalwd48 = $result['bmjobdone48'] + $result['cjobdone48'] + $result['djajobdone48'] + $result['pmjobdone48'];
               $totalpending48 = $result['bmpendingjon48'] + $result['cpendingjon48'] + $result['djapendingjon48'] + $result['pmpendingjon48'];
               $ivar.='
                     <tr style="background-color:white;">
                        <td class="text-center" rowspan="5">DAILY DCP ACTIVITY(within 48hr)</td>
                        <td class="text-center">BREAKDOWN MAINTENANCE</td>
                        <td class="text-right" style="background-color: #00f9b4;">'.$result['bmplanning48'].'</td>
                        <td class="text-right" style="background-color: #f6f900;">'.$result['bmprogress48'].'</td>
                        <td class="text-right" style="background-color: #82f900;">'.$result['bmjobdone48'].'</td>
                        <td class="text-right" style="background-color: #ff4545;">'.$result['bmpendingjon48'].'</td>
                        <td class="text-center" rowspan="11">
                           <a href="#" class="btn btn-primary" data-toggle="modal" data-target=".bd-stdfilter-modal-lg">FILTER</a>
                           <ul class="btn btn-default stc-datatable-filter-ul"><a href="javascript:void(0)" class="data-fields-display btn btn-success">FIELDS</a>
                           '.$data_fields.'
                           </ul>
      
                        </td>
                     </tr>
                     <tr style="background-color:white;">
                        <td class="text-center">CALL ATTEND</td>
                        <td class="text-right" style="background-color: #00f9b4;">'.$result['cplanning48'].'</td>
                        <td class="text-right" style="background-color: #f6f900;">'.$result['cprogress48'].'</td>
                        <td class="text-right" style="background-color: #82f900;">'.$result['cjobdone48'].'</td>
                        <td class="text-right" style="background-color: #ff4545;">'.$result['cpendingjon48'].'</td>
                     </tr>
                     <tr style="background-color:white;">
                        <td class="text-center">DAILY JOB ACTIVITY</td>
                        <td class="text-right" style="background-color: #00f9b4;">'.$result['djaplanning48'].'</td>
                        <td class="text-right" style="background-color: #f6f900;">'.$result['djaprogress48'].'</td>
                        <td class="text-right" style="background-color: #82f900;">'.$result['djajobdone48'].'</td>
                        <td class="text-right" style="background-color: #ff4545;">'.$result['djapendingjon48'].'</td>
                     </tr>
                     <tr style="background-color:white;">
                        <td class="text-center">PREVENTIVE MAINTENANCE</td>
                        <td class="text-right" style="background-color: #00f9b4;">'.$result['pmplanning48'].'</td>
                        <td class="text-right" style="background-color: #f6f900;">'.$result['pmprogress48'].'</td>
                        <td class="text-right" style="background-color: #82f900;">'.$result['pmjobdone48'].'</td>
                        <td class="text-right" style="background-color: #ff4545;">'.$result['pmpendingjon48'].'</td>
                     </tr>
                     <tr style="background-color:white;border-bottom: 3px solid black;">
                        <td class="text-center">WINTER OVERHAULING</td>
                        <td class="text-right" style="background-color: #00f9b4;">'.$result['whplanning48'].'</td>
                        <td class="text-right" style="background-color: #f6f900;">'.$result['whprogress48'].'</td>
                        <td class="text-right" style="background-color: #82f900;">'.$result['whjobdone48'].'</td>
                        <td class="text-right" style="background-color: #ff4545;">'.$result['whpendingjon48'].'</td>
                     </tr>
                     <tr style="background-color:white;border-bottom: 3px solid black;">
                        <td class="text-center" colspan="2">TOTAL</td>
                        <td class="text-right"><b>'.$totalp48.'</b></td>
                        <td class="text-right"><b>'.$totalwp48.'</b></td>
                        <td class="text-right"><b>'.$totalwd48.'</b></td>
                        <td class="text-right"><b>'.$totalpending48.'</b></td>
                     </tr>
               ';
            }
         }
         $ivar.='
               <tr style="background-color:white;">
                  <td class="text-center" rowspan="5">TOTAL DCP ACTIVITY (All time)</td>
                  <td class="text-center">BREAKDOWN MAINTENANCE</td>
                  <td class="text-right" style="background-color: #00f9b4;">'.$abmplanningday.'</td>
                  <td class="text-right" style="background-color: #f6f900;">'.$bmprogress.'</td>
                  <td class="text-right" style="background-color: #82f900;">'.$bmjobdone.'</td>
                  <td class="text-right" style="background-color: #ff4545;">'.$abmpendingday.'</td>
               </tr>
               <tr style="background-color:white;">
                  <td class="text-center">CALL ATTEND</td>
                  <td class="text-right" style="background-color: #00f9b4;">'.$acplanningday.'</td>
                  <td class="text-right" style="background-color: #f6f900;">'.$cprogress.'</td>
                  <td class="text-right" style="background-color: #82f900;">'.$cjobdone.'</td>
                  <td class="text-right" style="background-color: #ff4545;">'.$acpendingday.'</td>
               </tr>
               <tr style="background-color:white;">
                  <td class="text-center">DAILY JOB ACTIVITY</td>
                  <td class="text-right" style="background-color: #00f9b4;">'.$adjaplanningday.'</td>
                  <td class="text-right" style="background-color: #f6f900;">'.$djaprogress.'</td>
                  <td class="text-right" style="background-color: #82f900;">'.$djajobdone.'</td>
                  <td class="text-right" style="background-color: #ff4545;">'.$adjapendingday.'</td>
               </tr>
               <tr style="background-color:white;">
                  <td class="text-center">PREVENTIVE MAINTENANCE</td>
                  <td class="text-right" style="background-color: #00f9b4;">'.$apmplanningday.'</td>
                  <td class="text-right" style="background-color: #f6f900;">'.$pmprogress.'</td>
                  <td class="text-right" style="background-color: #82f900;">'.$pmjobdone.'</td>
                  <td class="text-right" style="background-color: #ff4545;">'.$apmpendingday.'</td>
               </tr>
               <tr style="background-color:white;border-bottom: 3px solid black;">
                  <td class="text-center">WINTER OVERHAULING</td>
                  <td class="text-right" style="background-color: #00f9b4;">'.$awhplanningday.'</td>
                  <td class="text-right" style="background-color: #f6f900;">'.$whprogress.'</td>
                  <td class="text-right" style="background-color: #82f900;">'.$whjobdone.'</td>
                  <td class="text-right" style="background-color: #ff4545;">'.$awhpendingday.'</td>
               </tr>
               <tr style="background-color:white;border-bottom: 3px solid black;">
                  <td class="text-center" colspan="2">TOTAL</td>
                  <td class="text-right"><strong>'.$atotalp.'</strong></td>
                  <td class="text-right"><strong>'.$atotalwp.'</strong></td>
                  <td class="text-right"><strong>'.$atotalwd.'</strong></td>
                  <td class="text-right"><strong>'.$atotalpending.'</strong></td>
               </tr>
         ';
         $ivar.='
            </table>
         ';
      }

      if(mysqli_num_rows($ivarqry)>0){
         $ivar.='
                     
            <button class="mb-2 mr-2 btn btn-success btn-block '.$exportbtnname.'" data-type="excel">
            <i class="fa fa-file-excel-o"></i> Export Excel
            </button>
            <table class="table table-bordered" id="stc-show-std-details-table">
               <thead>
                  <tr>
                     <th style="width:2%" class="text-center SLNO sl-hide">SL NO</th>
                     <th style="width:3%" class="text-center">DATE</th>
                     <th style="width:3%" class="text-center">LOCATION</th>
                     <th style="width:1%" class="text-center">DEPARTMENT</th>
                     <th style="width:2%" class="text-center">AREA</th>
                     <th style="width:3%" class="text-center">EQUIPMENT DETAILS</th>
                     <th style="width:3%" class="text-center">TYPE OF JOB</th>
                     <th style="width:1%" class="text-center">QTY</th>
                     <th style="width:1%" class="text-center">CAPACITY</th>
                     <th style="width:4%" class="text-center">REASON</th>
                     <th style="width:2%" class="text-center sl-hide">REASON ATTRIBUTE</th>
                     <th style="width:1%" class="text-center">PERMIT NO</th>
                     <th style="width:4%" class="text-center">MATERIAL</th>
                     <th style="width:1%" class="text-center">STATUS</th>
                     <th style="width:1%" class="text-center">DELAY</th>
                     <th style="width:1%" class="text-center">PENDING DETAILS</th>
                     <th style="width:1%" class="text-center">PROGRESS REPORT</th>
                     <th style="width:3%" class="text-center">FRC REPORTS</th>
                     <th style="width:3%" class="text-center">JOB DONE DETAILS</th>
                     <th style="width:3%" class="text-center">ANY COMMENTS</th>
                     <th style="width:3%" class="text-center">TARGET DATE</th>
                     <th style="width:3%" class="text-center">SAFETY</th>
                     <th style="width:3%" class="text-center">REMARKS</th>
                     <th style="width:3%" class="text-center sl-hide excel-hide">ACTION</th>
                  </tr>
               </thead>
               <tbody>
         ';
         $rowcount=$pagination;
         foreach($ivarqry as $row){
            $rowcount++;
            
            $list_date=(date('Y', strtotime($row['stc_status_down_list_date']))>1970) ? date('d-m-Y', strtotime($row['stc_status_down_list_date'])) : 'NA';
            $rec_date=(date('Y', strtotime($row['stc_status_down_list_rect_date']))>1970) ? date('d-m-Y', strtotime($row['stc_status_down_list_rect_date'])) : 'NA';
            $tar_date=(date('Y', strtotime($row['stc_status_down_list_target_date']))>1970) ? date('d-m-Y', strtotime($row['stc_status_down_list_target_date'])) : 'NA';

            $status='';
            $status2color="";
            if($row['stc_status_down_list_status']==1){
               $status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000;">PLANNING</span></b>';
               $status2color="#00f9b4";
               $pendingjon++;
            }elseif($row['stc_status_down_list_status']==2){
               $status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000;">DOWN</span></b>';
               $status2color="#e91919";
            }elseif($row['stc_status_down_list_status']==3){
               $status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000;">WORK-IN-PROGRESS</span></b>';
               $status2color="#f6f900";
               $jobdone++;
            }elseif($row['stc_status_down_list_status']==4){
               $status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000;">WORK-DONE</span></b>';
               $status2color="#60f900";
            }elseif($row['stc_status_down_list_status']==5){
               $status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000;">WORK-COMPLETE</span></b>';
               $status2color="#38be7a";
            }else{
               $status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000;">CLOSE</span></b>';
            }
            
            $eqstatus='
                  <td class="text-center" style="font-weight:bold;background: #5cd25c;border-radius: 5px;">'.$row['stc_status_down_list_equipment_status'].'</td>
            ';

            $dperiod='0';

            if($row['stc_status_down_list_status']==1){
               $eqstatus='
                     <td class="text-center" style="font-weight:bold;background: #e91919;border-radius: 5px;">'.$row['stc_status_down_list_jobtype'].'</td>
               ';
            }
               
            $today = !empty($row['stc_status_down_list_rect_date']) ? date("Y/m/d", strtotime($row['stc_status_down_list_rect_date'])) : date("Y/m/d") ; 
				$startTimeStamp = strtotime(date('Y-m-d', strtotime($row['stc_status_down_list_date'])));
		      $endTimeStamp = strtotime($today);

		      $timeDiff = abs($endTimeStamp - $startTimeStamp);

		      $dperiod = $timeDiff/86400;

            $actionsec='';
            if($row['stc_status_down_list_status']==3){
               $actionsec='
                  <a href="#" class="stc-set-to-complete" style="font-size:20px" id="'.$row['stc_status_down_list_id'].'"><i class="fas fa-thumbs-up"></i></a>
               ';
            }elseif($row['stc_status_down_list_status']==4){
               $actionsec='
                  <a href="#" class="stc-set-to-close" style="font-size:20px" id="'.$row['stc_status_down_list_id'].'"><i class="fas fa-thumbs-up"></i></a>
               ';
            }else{
               $actionsec='#';
            }

            $eq_type='';
            $eq_number='';
            $sup_det='';
            // $stc_call_eqtypeqry=mysqli_query($this->stc_dbs, "
            //    SELECT
            //        `stc_cpumpd_equipment_type`
            //    FROM
            //        `stc_customer_pump_details`
            //    WHERE
            //        `stc_cpumpd_id`='".$row['stc_status_down_list_equipment_type']."'
            // ");
            // if(mysqli_num_rows($stc_call_eqtypeqry)>0){
            //    foreach($stc_call_eqtypeqry as $stc_call_eqtyperow){
            //       $eq_type=$stc_call_eqtyperow['stc_cpumpd_equipment_type'];
            //    }
            // }

            $stc_call_eqnumberqry=mysqli_query($this->stc_dbs, "
               SELECT
                   `stc_cpumpd_equipment_number`
               FROM
                   `stc_customer_pump_details`
               WHERE
                   `stc_cpumpd_id`='".$row['stc_status_down_list_equipment_number']."'
            ");
            if(mysqli_num_rows($stc_call_eqnumberqry)>0){
               foreach($stc_call_eqnumberqry as $stc_call_eqnumberrow){
                  $eq_number=$stc_call_eqnumberrow['stc_cpumpd_equipment_number'];
               }
            }

            $stc_call_supqry=mysqli_query($this->stc_dbs, "
               SELECT
                   `stc_cust_pro_supervisor_fullname`,
                   `stc_cust_pro_supervisor_contact`
               FROM
                   `stc_cust_pro_supervisor`
               WHERE
                   `stc_cust_pro_supervisor_id`='".$row['stc_status_down_list_created_by']."'
            ");
            if(mysqli_num_rows($stc_call_supqry)>0){
               foreach($stc_call_supqry as $stc_call_suprow){
                  $sup_det=$stc_call_suprow['stc_cust_pro_supervisor_fullname'].'<br>'.$stc_call_suprow['stc_cust_pro_supervisor_contact'];
               }
            }
            $p_title=$row['stc_cust_project_title'];
            if((strpos($p_title,"(GTO")>0) || (strpos($p_title,"(WOG")>0) || (strpos($p_title,"(STO")>0)){
               $strpos=strpos($p_title,"(");
               $p_title=substr($p_title,0, $strpos);
            }

            $eq_type=$row['stc_status_down_list_equipment_type'];
				if(ctype_digit($eq_type)){
					$stc_call_eqtypeqry=mysqli_query($this->stc_dbs, "
						SELECT
							`stc_cpumpd_equipment_type`
						FROM
							`stc_customer_pump_details`
						WHERE
							`stc_cpumpd_id`='".$row['stc_status_down_list_equipment_type']."'
					");
					foreach($stc_call_eqtypeqry as $stc_call_eqtyperow){
						$eq_type=$stc_call_eqtyperow['stc_cpumpd_equipment_type'];
					}

					$stc_call_eqnumberqry=mysqli_query($this->stc_dbs, "
						SELECT
							`stc_cpumpd_equipment_number`
						FROM
							`stc_customer_pump_details`
						WHERE
							`stc_cpumpd_id`='".$row['stc_status_down_list_equipment_number']."'
					");
					foreach($stc_call_eqnumberqry as $stc_call_eqnumberrow){
						$eq_number=$stc_call_eqnumberrow['stc_cpumpd_equipment_number'];
					}
				}
            $material_view='<a href="javascript:void(0)" class="btn btn-primary stc-sdl-material-show-req" id="'.$row['stc_status_down_list_id'].'" data-toggle="modal" data-target=".bd-stdmaterial-modal-lg">View</a>';
            $sdl_id=$row['stc_status_down_list_id'];
            $stc_materquery=mysqli_query($this->stc_dbs, "
               SELECT `stc_cust_super_requisition_list_id` 
               FROM `stc_cust_super_requisition_list` 
               WHERE `stc_cust_super_requisition_list_sdlid`='".$sdl_id."'
				");
            if(mysqli_num_rows($stc_materquery)==0){
               $material_view='#';
            }
            $safety_inc="";
            $safetygetmgrqry=mysqli_query($this->stc_dbs, "SELECT `stc_safetytbm_id` FROM `stc_safetytbm` WHERE `stc_safetytbm_std_id`='".$row['stc_status_down_list_id']."'");
            if(mysqli_num_rows($safetygetmgrqry)>0){
               foreach($safetygetmgrqry as $safetygetmgrrow){
                  $tbtout='<a class="btn btn-success" target="_blank" title="Click to view TBM" href="../stc_agent47/safety-tbm-print-preview.php?tbm_no='.$safetygetmgrrow['stc_safetytbm_id'].'">'.$safetygetmgrrow['stc_safetytbm_id'].'</a>';
                  $safety_inc.=$safety_inc=='' ? $tbtout : ', '.$tbtout;
               }
            }

            $penddet_value = $row['stc_status_down_list_jobpending_details'];
            $penddet = strlen($penddet_value)>25 ? substr($penddet_value, 0, 25).'...<a href="javascript:void(0)" class="show-jobdonedetails" label="Pending Details" data="'.$penddet_value.'">Read more</a>' : $penddet_value;
            $progrdet_value = $row['stc_status_down_list_wipstatus'];
            $progrdet = strlen($progrdet_value)>25 ? substr($progrdet_value, 0, 25).'...<a href="javascript:void(0)" class="show-jobdonedetails" label="Progress Report" data="'.$progrdet_value.'">Read more</a>' : $progrdet_value;
            $frcrdet_value = $row['stc_status_down_list_failurerootcost'];
            $frcrdet = strlen($frcrdet_value)>25 ? substr($frcrdet_value, 0, 25).'...<a href="javascript:void(0)" class="show-jobdonedetails" label="Failure Root Cause" data="'.$frcrdet_value.'">Read more</a>' : $frcrdet_value;
            $ftargetdate=$row['stc_status_down_list_ftarget_date']=="" ? "" : date('d-m-Y H:i a', strtotime($row['stc_status_down_list_ftarget_date']));
            $anycomm_value = $row['stc_status_down_list_remarks'];
            $anycomdet = strlen($anycomm_value)>25 ? substr($anycomm_value, 0, 25).'...<a href="javascript:void(0)" class="show-jobdonedetails" label="Any Comment" data="'.$anycomm_value.'">Read more</a>' : $anycomm_value;
            $reason_value = $row['stc_status_down_list_reason'];
            $reasondet = strlen($reason_value)>25 ? substr($reason_value, 0, 25).'...<a href="javascript:void(0)" class="show-jobdonedetails" label="Reason" data="'.$reason_value.'">Read more</a>' : $reason_value;
            $jobdonedet_value = $row['stc_status_down_list_jobdone_details'];
            $jobdonedet = strlen($jobdonedet_value)>25 ? substr($jobdonedet_value, 0, 25).'...<a href="javascript:void(0)" class="show-jobdonedetails" label="Job Done Details" data="'.$jobdonedet_value.'">Read more</a>' : $jobdonedet_value;
            $finalremarks_value = $row['stc_status_down_list_fremarks'];
            $finalremarks = strlen($finalremarks_value)>25 ? substr($finalremarks_value, 0, 25).'...<a href="javascript:void(0)" class="show-jobdonedetails" label="Remarks" data="'.$finalremarks_value.'">Read more</a>' : $finalremarks_value;
            $finalremarks_value = $row['stc_status_down_list_fremarks'];
            $ivar.='
               <tr>
                  <td class="text-center SLNO sl-hide">'.$row['stc_status_down_list_id'].'</td>
                  <td>'.date('d-m-Y h:i a', strtotime($row['stc_status_down_list_date'])).'</td>
                  <td>'.$row['stc_status_down_list_plocation'].'</td>
                  <td>'.$row['stc_status_down_list_sub_location'].'</td>
                  <td>'.$row['stc_status_down_list_area'].'</td>
                  <td>'.$eq_type.' '.$eq_number.'</td>
                  <td class="text-center">'.$row['stc_status_down_list_jobtype'].'</td>
                  <td class="text-right">'.$row['stc_status_down_list_qty'].'</td>
                  <td class="text-right">'.$row['stc_status_down_list_capacity'].'</td>
                  <td>
                     <span class="jobdonedet-view">'.$reasondet.'</span>
                     <span class="jobdonedet-print" style="display:none;">'.$reason_value.'</span>
                  </td>
                  <td class="sl-hide">'.$row['stc_status_down_list_reasonattribute'].'</td>
                  <td>'.$row['stc_status_down_list_permit_no'].'</td>
                  <td class="sl-hide text-center">'.$material_view.'</td>
                  <td class="text-center" style="background-color:'.$status2color.'">'.$status.'</td>
                  <td class="text-right">'.$dperiod.' Days</td>
                  <td>
                     <span class="jobdonedet-view">'.$penddet.'</span>
                     <span class="jobdonedet-print" style="display:none;">'.$penddet_value.'</span>
                  </td>
                  <td>
                     <span class="jobdonedet-view">'.$progrdet.'</span>
                     <span class="jobdonedet-print" style="display:none;">'.$progrdet_value.'</span>
                  </td>
                  <td>
                     <span class="jobdonedet-view">'.$frcrdet.'</span>
                     <span class="jobdonedet-print" style="display:none;">'.$frcrdet_value.'</span>
                  </td>
                  <td>
                     <span class="jobdonedet-view">'.$jobdonedet.'</span>
                     <span class="jobdonedet-print" style="display:none;">'.$jobdonedet_value.'</span>
                  </td>
                  <td>
                     <span class="jobdonedet-view">'.$anycomdet.'</span>
                     <span class="jobdonedet-print" style="display:none;">'.$anycomm_value.'</span>
                  </td>
                  <td class="text-center">'.$ftargetdate.'</td>
                  <td class="text-center">'.$safety_inc.'</td>
                  <td>
                     <span class="jobdonedet-view">'.$finalremarks.'</span>
                     <span class="jobdonedet-print" style="display:none;">'.$finalremarks_value.'</span>
                  </td>
                  <td class="excel-hide"><a href="javascript:void(0)" class="btn btn-primary stc-edit-report" id="'.$row['stc_status_down_list_id'].'">Update</a></td>
               </tr>
            ';
         }
         $pagination+=20;
         $begpag=$pagination-20;
         $querycount ="
            SELECT 
               `stc_status_down_list_id`
            FROM `stc_status_down_list` 
            LEFT JOIN `stc_cust_project` 
            ON `stc_cust_project_id`=`stc_status_down_list_location` 
            WHERE `stc_status_down_list_equipment_type`<>'' AND `stc_status_down_list_date`<>'' ".$query_filter."         
            ORDER BY TIMESTAMP(`stc_status_down_list_date`) DESC
         ";
         $ivarqrycount=mysqli_query($this->stc_dbs, $querycount);
         $ivar.='
            <tr class="std-filter-find-pagination-tr">
               <td colspan="4">Showing result '.$begpag.'-'.$rowcount.' out of '.mysqli_num_rows($ivarqrycount).'</td>
               <td colspan="2">
                  <a class="btn btn-success std-filter-find-paginationprev" data="'.$begpag.'" >Prev</a>
                  <a class="btn btn-success std-filter-find-pagination" data="'.$pagination.'" >Next</a>
               </td>
            </tr>
         ';
      }else{
         $ivar.='
            <tr>
               <td colspan="14">No Record Found.</td>
            </tr>
         ';
      }

      $ivar.='
            </tbody>
         </table>
      ';
      return $ivar;
      
   }

   // update remarks and target datae from admin
   public function stc_call_std_update($sdl_id, $target_date, $remarks){
      $ivar='';
      $date=Date('d-m-Y h:i a');
      $remarks = ", ".$date." - ".$remarks;
      $ivarquery=mysqli_query($this->stc_dbs, "
         UPDATE `stc_status_down_list` 
         SET `stc_status_down_list_fremarks` = CONCAT(`stc_status_down_list_fremarks`,'".mysqli_real_escape_string($this->stc_dbs, $remarks)."'),
            `stc_status_down_list_ftarget_date` = '".mysqli_real_escape_string($this->stc_dbs, $target_date)."' 
         WHERE `stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sdl_id)."';
      ");
      if($ivarquery){
         $ivar="updated";
      }
      return $ivar;
   }

   // /call material list
   public function stc_call_std_material($sdl_id){
      $ivar='';
      $slno=0;
      $reqno=0;
      $reqdate='';
      $downlistdate='';
      $sublocation='';
      $ivarquery=mysqli_query($this->stc_dbs, "
         SELECT DISTINCT
            `stc_cust_super_requisition_list_items`.`stc_cust_super_requisition_list_id` as reqlistid,
            DATE(`stc_cust_super_requisition_list_date`) as stc_req_date,
            `stc_status_down_list_sub_location`,
            `stc_status_down_list_date`,
            `stc_cust_super_requisition_list_items_req_id`,
            `stc_cust_super_requisition_list_items_title`,
            `stc_cust_super_requisition_list_items_unit`,
            `stc_cust_super_requisition_list_items_reqqty`,
            `stc_cust_super_requisition_list_items_approved_qty`,
            `stc_cust_super_requisition_items_finalqty`,
            `stc_cust_super_requisition_list_items_status`,
            `stc_cust_super_requisition_items_priority`,
            `stc_cust_pro_supervisor_fullname`,
            `stc_cust_pro_supervisor_contact`
         FROM `stc_cust_super_requisition_list_items`
         LEFT JOIN `stc_cust_super_requisition_list` 
         ON `stc_cust_super_requisition_list_items_req_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id`
         LEFT JOIN `stc_status_down_list` 
         ON `stc_status_down_list_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_sdlid`
         LEFT JOIN `stc_cust_pro_supervisor` 
         ON `stc_cust_pro_supervisor_id`=`stc_cust_super_requisition_list_super_id`
         WHERE 
            `stc_cust_super_requisition_list_sdlid`='".mysqli_real_escape_string($this->stc_dbs, $sdl_id)."'
         ORDER BY DATE(`stc_cust_super_requisition_list_date`) DESC
      ");
      if(mysqli_num_rows($ivarquery)>0){
			foreach($ivarquery as$requisitionrow){
				$slno++;
				$stcdispatchedqty=0;
				$stcrecievedqty=0;
				$stcpendingqty=0;      
            $downlistdate=date('d-m-Y', strtotime($requisitionrow['stc_status_down_list_date']));
            $reqno=$requisitionrow['stc_cust_super_requisition_list_items_req_id'];
            $sublocation=$requisitionrow['stc_status_down_list_sub_location'];            
            $reqdate=date('d-m-Y', strtotime($requisitionrow['stc_req_date']));
            $SupName=$requisitionrow['stc_cust_pro_supervisor_fullname'];            
            $SupContact=$requisitionrow['stc_cust_pro_supervisor_contact']; 
            $rqitemstts='';
				if($requisitionrow['stc_cust_super_requisition_list_items_status']==1){
					$rqitemstts='<span style="background-color: #3498db; color: white; padding: 2px 6px; border-radius: 3px;">Ordered</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==2){
					$rqitemstts='<span style="background-color: #2ecc71; color: white; padding: 2px 6px; border-radius: 3px;">Approved</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==3){
					$rqitemstts='<span style="background-color: #27ae60; color: white; padding: 2px 6px; border-radius: 3px;">Accepted</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==4){
					$rqitemstts='<span style="background-color: #f39c12; color: white; padding: 2px 6px; border-radius: 3px;">Dispatched</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==5){
					$rqitemstts='<span style="background-color: #16a085; color: white; padding: 2px 6px; border-radius: 3px;">Received</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==6){
					$rqitemstts='<span style="background-color: #e74c3c; color: white; padding: 2px 6px; border-radius: 3px;">Rejected</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==7){
					$rqitemstts='<span style="background-color: #95a5a6; color: white; padding: 2px 6px; border-radius: 3px;">Canceled</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==8){
					$rqitemstts='<span style="background-color: #9b59b6; color: white; padding: 2px 6px; border-radius: 3px;">Returned</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==9){
					$rqitemstts='<span style="background-color:rgb(255, 47, 47); color: white; padding: 2px 6px; border-radius: 3px;">Pending</span>';
				}else{
					$rqitemstts='<span style="background-color: #34495e; color: white; padding: 2px 6px; border-radius: 3px;">Closed</span>';
				}
				$stcdecqtyqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_cust_super_requisition_list_items_rec_recqty`
					FROM `stc_cust_super_requisition_list_items_rec` 
					WHERE 
						`stc_cust_super_requisition_list_items_rec_list_id`='".$requisitionrow['stc_cust_super_requisition_list_items_req_id']."' 
					AND `stc_cust_super_requisition_list_items_rec_list_item_id`='".$requisitionrow['reqlistid']."'  
				");
				foreach($stcdecqtyqry as $dispatchedrow){
					$stcdispatchedqty+=$dispatchedrow['stc_cust_super_requisition_list_items_rec_recqty'];
				}

				$stcrecqtyqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty`
					FROM `stc_cust_super_requisition_rec_items_fr_supervisor` 
					WHERE `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`='".$requisitionrow['reqlistid']."'  
				");
				foreach($stcrecqtyqry as $recievedrow){
					$stcrecievedqty+=$recievedrow['stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty'];
				}

				$stcconsumedqty=0;
				$stcconsrecqtyqry=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_cust_super_list_items_consumption_items_qty`) AS consumable_qty
					FROM `stc_cust_super_list_items_consumption_items` 
					WHERE `stc_cust_super_list_items_consumption_items_name`='".$requisitionrow['reqlistid']."'  
				");
				foreach($stcconsrecqtyqry as $consumedrow){
					$stcconsumedqty+=$consumedrow['consumable_qty'];
				}

				$stcpendingqty=$requisitionrow['stc_cust_super_requisition_items_finalqty'] - $stcdispatchedqty;
				if($stcpendingqty>0){
					$stcpendingqty='
						<p class="form-control" style="
						    background: #ffd81a;
						    color: red;
						">
							'.number_format($stcpendingqty, 2).'
						</p>
					';
				}else{
					$stcpendingqty=number_format($stcpendingqty, 2);
				}
            $priority=$requisitionrow['stc_cust_super_requisition_items_priority']==2 ? "Urgent" : "Normal";
				$ivar.='
						<tr>
							<td>'.$slno.'</td>
							<td>'.$requisitionrow['stc_req_date'].'</td>
							<td>'.$requisitionrow['stc_cust_super_requisition_list_items_title'].'</td>
							<td>'.$requisitionrow['stc_cust_super_requisition_list_items_unit'].'</td>
							<td align="right">'.number_format($requisitionrow['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
							<td align="right">'.number_format($requisitionrow['stc_cust_super_requisition_list_items_approved_qty'], 2).'</td>
							<td align="right">'.number_format($requisitionrow['stc_cust_super_requisition_items_finalqty'], 2).'</td>
							<td align="right">'.number_format($stcdispatchedqty, 2).'</td>
							<td align="right">'.number_format($stcrecievedqty, 2).'</td>
							<td align="right">'.$stcpendingqty.'</td>
							<td align="right">'.number_format($stcconsumedqty, 2).'</td>
							<td>'.$priority.'</td>
							<td>'.$rqitemstts.'</td>
						</tr>
				';
			}
		}else{
			$ivar.='
					<tr>
						<td colspan="10">No requisition found!!!</td>
					</tr>
			';
		}
      $out_ivar = array(
         'data'=> $ivar,
         'Downlist'=> $sdl_id,
         'downlistdate'=>$downlistdate,
         'reqno'=> $reqno,
         'reqdate'=> $reqdate,
         'sublocation'=> $sublocation,
         'SupName'=> $SupName,
         'SupContact'=>$SupContact,
      );
      return $out_ivar;
   }

   // call customer
   public function stc_call_customer(){
      $ivar='<option value="NA">Please Select Customer</option>';
      $ivarcustomerqry=mysqli_query($this->stc_dbs, "
         SELECT DISTINCT 
            `stc_customer_id`,
            `stc_customer_name` 
         FROM `stc_customer` 
         INNER JOIN `stc_agent_requested_customer`
         ON `stc_customer_id`=`stc_agent_requested_customer_cust_id`
         ORDER BY `stc_customer_name` ASC
      ");
      foreach($ivarcustomerqry as $ivarrow){
         $ivar.='
            <option value="'.$ivarrow['stc_customer_id'].'">'.$ivarrow['stc_customer_name'].'</option>
         ';
      }
      return $ivar;
   }

   // call agent on change customer
   public function stc_call_agent_on_customer($custid){
      $ivar='<option value="NA">Please Select Agent</option>';
      $ivarcustomerqry=mysqli_query($this->stc_dbs, "
         SELECT DISTINCT
            `stc_agents_id`,
            `stc_agents_name`
         FROM `stc_agents` 
         INNER JOIN `stc_agent_requested_customer` 
         ON `stc_agent_requested_customer_agent_id`=`stc_agents_id`
         INNER JOIN `stc_customer` 
         ON `stc_customer_id`=`stc_agent_requested_customer_cust_id` 
         WHERE `stc_customer_id`='".mysqli_real_escape_string($this->stc_dbs, $custid)."'
         ORDER BY `stc_agents_name` ASC
      ");
      foreach($ivarcustomerqry as $ivarrow){
         $ivar.='
            <option value="'.$ivarrow['stc_agents_id'].'">'.strtoupper($ivarrow['stc_agents_name']).'</option>
         ';
      }
      return $ivar;
   }

   // call project on change agent
   public function stc_call_project_on_agent($agentid, $custid){
      $ivar='<option value="NA">Please Select Project</option>';
      $ivarcustomerqry=mysqli_query($this->stc_dbs, "
         SELECT 
            `stc_cust_project_id`,
             `stc_cust_project_title`
         FROM `stc_cust_project` 
         INNER JOIN `stc_customer` 
         ON `stc_customer_id`=`stc_cust_project_cust_id` 
         WHERE `stc_cust_project_createdby`='".mysqli_real_escape_string($this->stc_dbs, $agentid)."'
         AND `stc_customer_id`='".mysqli_real_escape_string($this->stc_dbs, $custid)."'
         ORDER BY `stc_cust_project_title` ASC
      ");
      foreach($ivarcustomerqry as $ivarrow){
         $ivar.='
            <option value="'.$ivarrow['stc_cust_project_id'].'">'.strtoupper($ivarrow['stc_cust_project_title']).'</option>
         ';
      }
      return $ivar;
   }

   // get supervisors order & requisitions 
   public function stc_get_supervisors_requisition_records($stc_begdate, $stc_enddate, $stc_custid, $stc_agentid, $stc_projeid, $stc_count_id){
      $optimusprime='';
      // $stc_count_id+=$stc_count_id;
      $slno=$stc_count_id;
      $loopcount=0;
      $countgetrequisitionsqry=mysqli_query($this->stc_dbs, "
         SELECT DISTINCT
            `stc_requisition_combiner_id`,
            DATE(`stc_requisition_combiner_date`) as stc_req_comb_date,
            `stc_cust_super_requisition_list_items`.`stc_cust_super_requisition_list_id` as reqlistid,
            DATE(`stc_cust_super_requisition_list_date`) as stc_req_date,
            `stc_cust_super_requisition_list_items_req_id`,
            `stc_cust_project_title`,
            `stc_cust_super_requisition_list_items_title`,
            `stc_cust_super_requisition_list_items_unit`,
            `stc_cust_super_requisition_list_items_reqqty`,
            `stc_cust_super_requisition_list_items_approved_qty`,
            `stc_cust_super_requisition_items_finalqty`,
            `stc_cust_super_requisition_list_items_status`
         FROM `stc_cust_super_requisition_list_items`
         INNER JOIN `stc_cust_super_requisition_list` 
         ON `stc_cust_super_requisition_list_items_req_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id`
         INNER JOIN `stc_cust_project` 
         ON `stc_cust_super_requisition_list_project_id`=`stc_cust_project_id`
            INNER JOIN `stc_requisition_combiner_req` 
            ON `stc_requisition_combiner_req_requisition_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id` 
            INNER JOIN `stc_requisition_combiner` 
            ON `stc_requisition_combiner_id`=`stc_requisition_combiner_req_comb_id` 
         WHERE 
            `stc_cust_super_requisition_items_finalqty`!=0
         AND 
            `stc_cust_super_requisition_list_project_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_projeid)."'
         AND (
            DATE(`stc_cust_super_requisition_list_date`) 
            BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $stc_begdate)."'
            AND '".mysqli_real_escape_string($this->stc_dbs, $stc_enddate)."' 
         ) ORDER BY DATE(`stc_cust_super_requisition_list_date`) DESC
      ");
      $getrequisitionsqry=mysqli_query($this->stc_dbs, "
         SELECT DISTINCT
            `stc_requisition_combiner_id`,
            DATE(`stc_requisition_combiner_date`) as stc_req_comb_date,
            `stc_cust_super_requisition_list_items`.`stc_cust_super_requisition_list_id` as reqlistid,
            DATE(`stc_cust_super_requisition_list_date`) as stc_req_date,
            `stc_cust_project_title`,
            `stc_cust_super_requisition_list_items_req_id`,
            `stc_cust_super_requisition_list_items_title`,
            `stc_cust_super_requisition_list_items_unit`,
            `stc_cust_super_requisition_list_items_reqqty`,
            `stc_cust_super_requisition_list_items_approved_qty`,
            `stc_cust_super_requisition_items_finalqty`,
            `stc_cust_super_requisition_list_items_status`
         FROM `stc_cust_super_requisition_list_items`
         INNER JOIN `stc_cust_super_requisition_list` 
         ON `stc_cust_super_requisition_list_items_req_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id`
         INNER JOIN `stc_cust_project` 
         ON `stc_cust_super_requisition_list_project_id`=`stc_cust_project_id`
            INNER JOIN `stc_requisition_combiner_req` 
            ON `stc_requisition_combiner_req_requisition_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id` 
            INNER JOIN `stc_requisition_combiner` 
            ON `stc_requisition_combiner_id`=`stc_requisition_combiner_req_comb_id` 
         WHERE 
            `stc_cust_super_requisition_items_finalqty`!=0
         AND 
            `stc_cust_super_requisition_list_project_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_projeid)."'
         AND (
            DATE(`stc_cust_super_requisition_list_date`) 
            BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $stc_begdate)."'
            AND '".mysqli_real_escape_string($this->stc_dbs, $stc_enddate)."' 
         ) ORDER BY DATE(`stc_cust_super_requisition_list_date`) DESC
         LIMIT ".$stc_count_id.",25
      ");
      if(mysqli_num_rows($getrequisitionsqry)>0){
         foreach($getrequisitionsqry as$requisitionrow){
            $loopcount++;
            $slno++;
            $stcdispatchedqty=0;
            $stcrecievedqty=0;
            $stcpendingqty=0;
            
            $rqitemstts='';
				if($requisitionrow['stc_cust_super_requisition_list_items_status']==1){
					$rqitemstts='<span style="background-color: #3498db; color: white; padding: 2px 6px; border-radius: 3px;">Ordered</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==2){
					$rqitemstts='<span style="background-color: #2ecc71; color: white; padding: 2px 6px; border-radius: 3px;">Approved</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==3){
					$rqitemstts='<span style="background-color: #27ae60; color: white; padding: 2px 6px; border-radius: 3px;">Accepted</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==4){
					$rqitemstts='<span style="background-color: #f39c12; color: white; padding: 2px 6px; border-radius: 3px;">Dispatched</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==5){
					$rqitemstts='<span style="background-color: #16a085; color: white; padding: 2px 6px; border-radius: 3px;">Received</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==6){
					$rqitemstts='<span style="background-color: #e74c3c; color: white; padding: 2px 6px; border-radius: 3px;">Rejected</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==7){
					$rqitemstts='<span style="background-color: #95a5a6; color: white; padding: 2px 6px; border-radius: 3px;">Canceled</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==8){
					$rqitemstts='<span style="background-color: #9b59b6; color: white; padding: 2px 6px; border-radius: 3px;">Returned</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==9){
					$rqitemstts='<span style="background-color:rgb(255, 47, 47); color: white; padding: 2px 6px; border-radius: 3px;">Pending</span>';
				}else{
					$rqitemstts='<span style="background-color: #34495e; color: white; padding: 2px 6px; border-radius: 3px;">Closed</span>';
				}
            $stcdecqtyqry=mysqli_query($this->stc_dbs, "
               SELECT 
                  `stc_cust_super_requisition_list_items_rec_recqty`
               FROM `stc_cust_super_requisition_list_items_rec` 
               WHERE 
                  `stc_cust_super_requisition_list_items_rec_list_id`='".$requisitionrow['stc_cust_super_requisition_list_items_req_id']."' 
               AND `stc_cust_super_requisition_list_items_rec_list_item_id`='".$requisitionrow['reqlistid']."'  
            ");
            foreach($stcdecqtyqry as $dispatchedrow){
               $stcdispatchedqty+=$dispatchedrow['stc_cust_super_requisition_list_items_rec_recqty'];
            }

            $stcrecqtyqry=mysqli_query($this->stc_dbs, "
               SELECT 
                  `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty`
               FROM `stc_cust_super_requisition_rec_items_fr_supervisor` 
               WHERE `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`='".$requisitionrow['reqlistid']."'  
            ");
            foreach($stcrecqtyqry as $recievedrow){
               $stcrecievedqty+=$recievedrow['stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty'];
            }

            $stcconsumedqty=0;
            $stcconsrecqtyqry=mysqli_query($this->stc_dbs, "
               SELECT 
                  SUM(`stc_cust_super_list_items_consumption_items_qty`) AS consumable_qty
               FROM `stc_cust_super_list_items_consumption_items` 
               WHERE `stc_cust_super_list_items_consumption_items_name`='".$requisitionrow['reqlistid']."'  
            ");
            foreach($stcconsrecqtyqry as $consumedrow){
               $stcconsumedqty+=$consumedrow['consumable_qty'];
            }

            $challanqry=mysqli_query($this->stc_dbs, "
               SELECT DISTINCT
                  `stc_sale_product_id`,
                  `stc_sale_product_date`
               FROM
                  `stc_sale_product`
               INNER JOIN 
                  `stc_sale_product_items` 
               ON 
                  `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
               INNER JOIN 
                  `stc_cust_super_requisition_list_items` 
               ON 
                  `stc_cust_super_requisition_list_items_req_id`=`stc_sale_product_order_id`
               INNER JOIN 
                  `stc_cust_super_requisition_list_items_rec` 
               ON 
                  `stc_cust_super_requisition_list_items_rec_list_item_id`=`stc_cust_super_requisition_list_id`
               WHERE
                  `stc_sale_product_order_id`='".mysqli_real_escape_string($this->stc_dbs, $requisitionrow['stc_cust_super_requisition_list_items_req_id'])."'
               AND 
                  `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $requisitionrow['reqlistid'])."'
            ");
            $challaninfo='';
            foreach($challanqry as $challanrow){
               $challaninfo.=
                   '<a href="challan-preview.php?pid='.$challanrow['stc_sale_product_id'].'">'.
                        date('d-m-Y',strtotime($challanrow['stc_sale_product_date'])).
                     '<br>'.
                        $challanrow['stc_sale_product_id'].
                     '<br>
                   </a>
                  '
               ;
            }
            $stcpendingqty=$requisitionrow['stc_cust_super_requisition_items_finalqty'] - $stcdispatchedqty;
            if($stcpendingqty>0){
               $stcpendingqty='
                  <p class="form-control" style="
                      background: #ffd81a;
                      color: red;
                  ">
                     '.number_format($stcpendingqty, 2).'
                  </b>
               ';
            }else{
               $stcpendingqty=number_format($stcpendingqty, 2);
            }
            $cosump_bal_qty = $stcrecievedqty - $stcconsumedqty;
            $stcbalqtymark = '';
            if($stcpendingqty>0){
               $stcbalqtymark='
                  <p class="form-control" style="
                      background: #ffd81a;
                      color: red;
                  ">
                     '.number_format($cosump_bal_qty, 2).'
                  </b>
               ';
            }else{
               $stcbalqtymark=$stcpendingqty;
            }
            $optimusprime.='
                  <tr>
                     <td>'.$slno.'</td>
                     <td>
                         <a href="stc-requisition-combiner-fshow.php?requi_id='.$requisitionrow['stc_requisition_combiner_id'].'">
                           '.$requisitionrow['stc_req_comb_date'].'<br>
                           '.$requisitionrow['stc_requisition_combiner_id'].'
                        </a>
                     </td>
                     <td>
                        '.$requisitionrow['stc_req_date'].'<br>
                        '.$requisitionrow['stc_cust_super_requisition_list_items_req_id'].'
                     </td>
                     <td>
                        '.$challaninfo.'
                     </td>
                     <td>'.$requisitionrow['stc_cust_project_title'].'</td>
                     <td>'.$requisitionrow['stc_cust_super_requisition_list_items_title'].'</td>
                     <td>'.$requisitionrow['stc_cust_super_requisition_list_items_unit'].'</td>
                     <td align="right">'.number_format($requisitionrow['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
                     <td align="right">'.number_format($requisitionrow['stc_cust_super_requisition_list_items_approved_qty'], 2).'</td>
                     <td align="right">'.number_format($requisitionrow['stc_cust_super_requisition_items_finalqty'], 2).'</td>
                     <td align="right">'.number_format($stcdispatchedqty, 2).'</td>
                     <td align="right">'.$stcpendingqty.'</td>
                     <td align="right">'.number_format($stcrecievedqty, 2).'</td>
                     <td align="right">'.number_format($stcconsumedqty, 2).'</td>
                     <td align="right">'.$stcbalqtymark.'</td>
                     <td>'.$rqitemstts.'</td>
                  </tr>
            ';
         }
         $totalcount=mysqli_num_rows($countgetrequisitionsqry);
         $showmorecol='
               <td colspan="5" class="text-center">
                  <a href="#" class="show_more_requisition" data-val="'.$slno.'">Show More</a>
               </td>
         ';
         if($slno>=$totalcount){
            $showmorecol='';
         }
         $optimusprime.='
            <tr class="show_more_requisition_row">
               <td colspan="2" class="text-center">Result Shown '.$slno.' out of '.$totalcount.'</td>
               '.$showmorecol.'
            </tr>
         ';
      }else{
         $optimusprime.='
               <tr>
                  <td colspan="10">No requisition found!!!</td>
               </tr>
         ';
      }
      return $optimusprime;
   }
    
   // get supervisors pending requisitions 
   public function stc_get_supervisors_pending_records($stc_begdate, $stc_enddate, $stc_custid, $stc_agentid, $stc_projeid){
      $optimusprime='';
      $slno=0;
      $loopcount=0;
      $query="
         SELECT DISTINCT
            `stc_requisition_combiner_id`,
            DATE(`stc_requisition_combiner_date`) as stc_req_comb_date,
            `stc_cust_super_requisition_list_items`.`stc_cust_super_requisition_list_id` as reqlistid,
            DATE(`stc_cust_super_requisition_list_date`) as stc_req_date,
            `stc_cust_super_requisition_list_items_req_id`,
            `stc_cust_project_title`,
            `stc_cust_super_requisition_list_items_title`,
            `stc_cust_super_requisition_list_items_unit`,
            `stc_cust_super_requisition_list_items_reqqty`,
            `stc_cust_super_requisition_list_items_approved_qty`,
            `stc_cust_super_requisition_items_finalqty`,
            `stc_cust_super_requisition_list_items_status`
         FROM `stc_cust_super_requisition_list_items`
         INNER JOIN `stc_cust_super_requisition_list` 
         ON `stc_cust_super_requisition_list_items_req_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id`
         INNER JOIN `stc_cust_project` 
         ON `stc_cust_super_requisition_list_project_id`=`stc_cust_project_id`
         INNER JOIN `stc_requisition_combiner_req` 
         ON `stc_requisition_combiner_req_requisition_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id` 
         INNER JOIN `stc_requisition_combiner` 
         ON `stc_requisition_combiner_id`=`stc_requisition_combiner_req_comb_id` 
         WHERE `stc_cust_super_requisition_items_finalqty`!=0
         AND `stc_cust_project_cust_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_custid)."' 
         AND `stc_cust_super_requisition_list_project_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_projeid)."'
         AND (
            DATE(`stc_cust_super_requisition_list_date`) 
            BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $stc_begdate)."'
            AND '".mysqli_real_escape_string($this->stc_dbs, $stc_enddate)."' 
         ) ORDER BY DATE(`stc_cust_super_requisition_list_date`) DESC
      ";
      $getrequisitionsqry=mysqli_query($this->stc_dbs, $query);
      if(mysqli_num_rows($getrequisitionsqry)>0){
         foreach($getrequisitionsqry as$requisitionrow){
            $loopcount++;
            $slno++;
            $rqitemstts='';
            $stcdispatchedqty=0;
            $stcrecievedqty=0;
            $stcpendingqty=0;
            $rqitemstts='';
				if($requisitionrow['stc_cust_super_requisition_list_items_status']==1){
					$rqitemstts='<span style="background-color: #3498db; color: white; padding: 2px 6px; border-radius: 3px;">Ordered</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==2){
					$rqitemstts='<span style="background-color: #2ecc71; color: white; padding: 2px 6px; border-radius: 3px;">Approved</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==3){
					$rqitemstts='<span style="background-color: #27ae60; color: white; padding: 2px 6px; border-radius: 3px;">Accepted</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==4){
					$rqitemstts='<span style="background-color: #f39c12; color: white; padding: 2px 6px; border-radius: 3px;">Dispatched</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==5){
					$rqitemstts='<span style="background-color: #16a085; color: white; padding: 2px 6px; border-radius: 3px;">Received</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==6){
					$rqitemstts='<span style="background-color: #e74c3c; color: white; padding: 2px 6px; border-radius: 3px;">Rejected</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==7){
					$rqitemstts='<span style="background-color: #95a5a6; color: white; padding: 2px 6px; border-radius: 3px;">Canceled</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==8){
					$rqitemstts='<span style="background-color: #9b59b6; color: white; padding: 2px 6px; border-radius: 3px;">Returned</span>';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==9){
					$rqitemstts='<span style="background-color:rgb(255, 47, 47); color: white; padding: 2px 6px; border-radius: 3px;">Pending</span>';
				}else{
					$rqitemstts='<span style="background-color: #34495e; color: white; padding: 2px 6px; border-radius: 3px;">Closed</span>';
				}
            $stcdecqtyqry=mysqli_query($this->stc_dbs, "
               SELECT 
                  `stc_cust_super_requisition_list_items_rec_recqty`
               FROM `stc_cust_super_requisition_list_items_rec` 
               WHERE 
                  `stc_cust_super_requisition_list_items_rec_list_id`='".$requisitionrow['stc_cust_super_requisition_list_items_req_id']."' 
               AND `stc_cust_super_requisition_list_items_rec_list_item_id`='".$requisitionrow['reqlistid']."'  
            ");
            foreach($stcdecqtyqry as $dispatchedrow){
               $stcdispatchedqty+=$dispatchedrow['stc_cust_super_requisition_list_items_rec_recqty'];
            }

            $stcrecqtyqry=mysqli_query($this->stc_dbs, "
               SELECT 
                  `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty`
               FROM `stc_cust_super_requisition_rec_items_fr_supervisor` 
               WHERE `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`='".$requisitionrow['reqlistid']."'  
            ");
            foreach($stcrecqtyqry as $recievedrow){
               $stcrecievedqty+=$recievedrow['stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty'];
            }

            $stcconsumedqty=0;
            $stcconsrecqtyqry=mysqli_query($this->stc_dbs, "
               SELECT 
                  SUM(`stc_cust_super_list_items_consumption_items_qty`) AS consumable_qty
               FROM `stc_cust_super_list_items_consumption_items` 
               WHERE `stc_cust_super_list_items_consumption_items_name`='".$requisitionrow['reqlistid']."'  
            ");
            foreach($stcconsrecqtyqry as $consumedrow){
               $stcconsumedqty+=$consumedrow['consumable_qty'];
            }

            $challanqry=mysqli_query($this->stc_dbs, "
               SELECT DISTINCT
                  `stc_sale_product_id`,
                  `stc_sale_product_date`
               FROM
                  `stc_sale_product`
               INNER JOIN 
                  `stc_sale_product_items` 
               ON 
                  `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
               INNER JOIN 
                  `stc_cust_super_requisition_list_items` 
               ON 
                  `stc_cust_super_requisition_list_items_req_id`=`stc_sale_product_order_id`
               INNER JOIN 
                  `stc_cust_super_requisition_list_items_rec` 
               ON 
                  `stc_cust_super_requisition_list_items_rec_list_item_id`=`stc_cust_super_requisition_list_id`
               WHERE
                  `stc_sale_product_order_id`='".mysqli_real_escape_string($this->stc_dbs, $requisitionrow['stc_cust_super_requisition_list_items_req_id'])."'
               AND 
                  `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $requisitionrow['reqlistid'])."'
            ");
            $challaninfo='';
            foreach($challanqry as $challanrow){
               $challaninfo.=
                   '<a href="challan-preview.php?pid='.$challanrow['stc_sale_product_id'].'">'.
                        date('d-m-Y',strtotime($challanrow['stc_sale_product_date'])).
                     '<br>'.
                        $challanrow['stc_sale_product_id'].
                     '<br>
                   </a>
                  '
               ;
            }
            $stcpendingqty=$requisitionrow['stc_cust_super_requisition_items_finalqty'] - $stcdispatchedqty;
                
                $cosump_bal_qty = $stcrecievedqty - $stcconsumedqty;
                $stcbalqtymark = '';
                if($cosump_bal_qty>0){
               $stcbalqtymark='
                  <p class="form-control" style="
                      background: #ffd81a;
                      color: red;
                  ">
                     '.number_format($cosump_bal_qty, 2).'
                  </b>
               ';
            }else{
               $stcbalqtymark=number_format($cosump_bal_qty, 2);
            }
            if($stcpendingqty>0){
               $stcpendingqty='
                  <p class="form-control" style="
                      background: #ffd81a;
                      color: red;
                  ">
                     '.number_format($stcpendingqty, 2).'
                  </b>
               ';
               $pendingreason='<a href="stc-requisition-combiner-fsale.php?requi_id='.$requisitionrow['stc_requisition_combiner_id'].'" target="__blank">';
               $qry=mysqli_query($this->stc_dbs, "
                  SELECT `message` FROM `stc_cust_super_requisition_list_items_log` WHERE title='Pending' AND `item_id`='".$requisitionrow['reqlistid']."'
               ");
               foreach($qry as $result){
                  $pendingreason.=$result['message'].'<br>';
               }
               $pendingreason.='</a>';
                    $optimusprime.='
                            <tr>
                                <td>'.$slno.'</td>
                                <td>
                                    <a href="stc-requisition-combiner-fshow.php?requi_id='.$requisitionrow['stc_requisition_combiner_id'].'">
                                        '.$requisitionrow['stc_req_comb_date'].'<br>
                                        '.$requisitionrow['stc_requisition_combiner_id'].'
                                    </a>
                                </td>
                                <td>
                                    '.$requisitionrow['stc_req_date'].'<br>
                                    '.$requisitionrow['stc_cust_super_requisition_list_items_req_id'].'
                                </td>
                                <td>
                                    '.$challaninfo.'
                                </td>
                                <td>'.$requisitionrow['stc_cust_project_title'].'</td>
                                <td>'.$requisitionrow['stc_cust_super_requisition_list_items_title'].'</td>
                                <td>'.$requisitionrow['stc_cust_super_requisition_list_items_unit'].'</td>
                                <td align="right">'.number_format($requisitionrow['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
                                <td align="right">'.number_format($requisitionrow['stc_cust_super_requisition_list_items_approved_qty'], 2).'</td>
                                <td align="right">'.number_format($requisitionrow['stc_cust_super_requisition_items_finalqty'], 2).'</td>
                                <td align="right">'.number_format($stcdispatchedqty, 2).'</td>
                                <td align="right">'.$stcpendingqty.'</td>
                                <td align="right">'.number_format($stcrecievedqty, 2).'</td>
                                <td align="right">'.number_format($stcconsumedqty, 2).'</td>
                                <td align="right">'.$stcbalqtymark.'</td>
                                <td>'.$rqitemstts.'</td>
                                <td>'.$pendingreason.'</td>
                            </tr>
                    ';
            }
                    
         }
      }else{
         $optimusprime.='
               <tr>
                  <td colspan="10">No requisition found!!!</td>
               </tr>
         ';
      }
      return $optimusprime;
   }
}

class ragnarReportsViewProjReports extends tesseract{
   // call project details
   public function stc_get_project_details($stc_custid, $stc_agentid){
      $optimusprime='';
      $slno=0;
      $loopcount=0;
      $getrequisitionsqry=mysqli_query($this->stc_dbs, "
         SELECT 
                `stc_cust_project_id`,
                `stc_cust_project_date`,
                `stc_cust_project_title`,
                `stc_cust_project_address`,
                `stc_cust_project_responsive_person`,
                `stc_cust_project_status`
            FROM 
                `stc_cust_project` 
            WHERE 
                `stc_cust_project_createdby`='".mysqli_real_escape_string($this->stc_dbs, $stc_agentid)."'
         ORDER BY `stc_cust_project_title` ASC
      ");
      if(mysqli_num_rows($getrequisitionsqry)>0){
         foreach($getrequisitionsqry as$requisitionrow){
            $loopcount++;
            $status='';
            $actionattr='';
               if($requisitionrow['stc_cust_project_status']==1){
                   $status="Live";
               }else{
                   $status="Closed";
               }
               $checkact_qry=mysqli_query($this->stc_dbs, "
                  SELECT 
                     `stc_cust_project_details_id` 
                  FROM 
                     `stc_cust_project_details` 
                  WHERE 
                     `stc_cust_project_details_pro_title_id`='".mysqli_real_escape_string($this->stc_dbs, $requisitionrow['stc_cust_project_id'])."'
               ");
               if(mysqli_num_rows($checkact_qry)>0){
                     $actionattr='
                        <a href="#" id="'.$requisitionrow['stc_cust_project_id'].'" class="stc-project-show-ret" style="font-size: 25px;color: #cc7676;">
                            <i class="fas fa-eye"></i>
                        </a>
                     ';
               }else{
                     $actionattr='#';
               }
            $optimusprime.='
                  <tr>
                         <td>'.$requisitionrow['stc_cust_project_id'].'<br>'.date('d-m-Y', strtotime($requisitionrow['stc_cust_project_date'])).'</td>
                         <td>'.$requisitionrow['stc_cust_project_title'].'</td>
                         <td>'.$requisitionrow['stc_cust_project_address'].'</td>
                         <td>'.$requisitionrow['stc_cust_project_responsive_person'].'</td>
                         <td>'.$status.'</td>
                         <td align="center">'.$actionattr.'</td>
                     </tr>
            ';
         }
      }else{
         $optimusprime.='
               <tr>
                  <td colspan="10">No requisition found!!!</td>
               </tr>
         ';
      }
      $bjornebegval=0;
      $bjorneendval=0;
      $optimusprime.= '
         <tr>
            <td colspan="2">
               '.$bjornebegval.' to '.$bjorneendval.'
               <button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
                  <i class="fas fa-arrow-left"></i>
               </button>
               <input type="hidden" class="begvalueinput" value="0">
               <input type="hidden" class="begvalueinputsearch" value="'.$bjornebegval.'">
            </td>
            <td colspan="11">
               <button type="button" class="btn btn-primary endbuttoninvsearch">
                  <i class="fas fa-arrow-right"></i>
               </button>
               <input type="hidden" class="endvalueinput" value="20">
               <input type="hidden" class="endvalueinputsearch" value="'.$bjorneendval.'">
            </td>
         </tr>
      ';
      return $optimusprime;
   }

   // call project details
   public function stc_call_project_details($project_id){
      $blackpearl='';
      $blackpearl_pd_qry=mysqli_query($this->stc_dbs, "
         SELECT
            `stc_cust_project_title`,
            `stc_cust_project_details_cust_name`,
            `stc_cust_project_details_email_id`,
            `stc_cust_project_details_cont_no`,
            `stc_cust_project_details_purpose`,
            `stc_cust_project_details_ref_contact`,
            `stc_cust_project_details_job_details`,
            `stc_cust_project_details_quote_number`,
            `stc_cust_project_details_quote_date`,
            `stc_cust_project_details_basic_val`,
            `stc_cust_project_details_gst_val`,
            `stc_cust_project_details_quotation_by`,
            `stc_cust_project_details_mode_of_quote`,
            `stc_cust_project_details_tar_price`,
            `stc_cust_project_details_status`,
            `stc_cust_project_details_remarks`,
            `stc_cust_project_details_po_number`,
            `stc_cust_project_details_po_value`
         FROM
            `stc_cust_project_details`
         INNER JOIN 
            `stc_cust_project` ON `stc_cust_project_id` = `stc_cust_project_details_pro_title_id`
         WHERE
            `stc_cust_project_details_pro_title_id`='".mysqli_real_escape_string($this->stc_dbs, $project_id)."'
      ");
      $blackpearl=((mysqli_num_rows($blackpearl_pd_qry)>0) ? mysqli_fetch_assoc($blackpearl_pd_qry) : 'NA');
      return $blackpearl;
   }
}

class ragnarReportsViewElectronicsPurchaseSaleReports extends tesseract{
   // call purchase
   public function stc_electronics_call_purchase($bjornebegdate, $bjorneenddate){
      $grandtotal=0;
      $totaldues=0;
      $check_loki=mysqli_query($this->stc_dbs, "
         SELECT
            `stc_daily_purchase_id`,
            `stc_daily_purchase_party_name`,
            `stc_daily_purchase_items_material_desc`,
            `stc_daily_purchase_refr_no`,
            `stc_daily_purchase_refr_date`,
            `stc_daily_purchase_items_id`,
            `stc_daily_purchase_items_qty`,
            `stc_daily_purchase_items_rate`,
            `stc_daily_purchase_items_unit`,
            `stc_daily_purchase_items_tax`,
            `stc_daily_purchase_remarks`,
            `stc_electronics_user_fullName`
         FROM `stc_daily_purchase_items`
         INNER JOIN `stc_daily_purchase` 
         ON `stc_daily_purchase_id` = `stc_daily_purchase_items_order_id`
         LEFT JOIN `stc_electronics_user` 
         ON  `stc_electronics_user_id` = `stc_daily_purchase_createdby`
         WHERE DATE(`stc_daily_purchase_refr_date`) 
         BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."' 
         AND '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."'
         ORDER BY DATE(`stc_daily_purchase_refr_date`) DESC
      ");
      $odin='
         <table class="mb-0 table table-bordered table-responsive" id="stc-reports-electronics-pending-view">
            <thead>
              <tr>
                <th scope="col">Party Name</th>
                <th scope="col">Material Details</th>
                <th scope="col">Invoice/ <br>Challan No</th>
                <th scope="col">Invoice/ <br>Challan Date</th>
                <th scope="col">Material Quantity</th>
                <th scope="col">Material Rate</th>
                <th scope="col">Tax</th>
                <th scope="col">Amount</th>
                <th scope="col">Payment Mode</th>
                <th scope="col">Payment Date</th>     
                <th scope="col">Due Amount</th>                         
                <th scope="col">Remarks</th>                          
                <th scope="col">Created By</th>  
              </tr>
            </thead>
            <tbody>  
       ';
      $do_action=mysqli_num_rows($check_loki);
      if($do_action == 0){
         $odin .= "<tr><td colspan='13' align='center'>No Record Found!!!</td></tr>";
      }else{
         foreach ($check_loki as $row) {
            $check_loki_nest=mysqli_query($this->stc_dbs, "
                  SELECT
                     `stc_daily_purchase_payments_date`,
                     `stc_daily_purchase_payments_type`,
                     `stc_daily_purchase_payments_amount`
                  FROM
                     `stc_daily_purchase_payments`
                  WHERE
                     `stc_daily_purchase_payments_order_no`='".$row["stc_daily_purchase_items_id"]."'
            ");
            $paydate='';
            $paytype='';
            $payamount=0;
            foreach ($check_loki_nest as $row2nd) {
               $paydate=date('d-m-Y', strtotime($row2nd['stc_daily_purchase_payments_date']));
               $paytype=$row2nd['stc_daily_purchase_payments_type'];
               $payamount+=$row2nd['stc_daily_purchase_payments_amount'];
            }

            if($paytype==1){
               $paytype='Account';
            }elseif($paytype==2){
               $paytype='Advance';
            }elseif($paytype==3){
               $paytype='Cash';
            }else{
               $paytype='Credit';
            }
            $basic=$row['stc_daily_purchase_items_qty'] * $row['stc_daily_purchase_items_rate'];
            $gstamount=$basic * ($row['stc_daily_purchase_items_tax']/100);
            $total=$basic + $gstamount;

            $dues=$total - $payamount;
            $grandtotal+=$total;
            $totaldues+=$dues;

            $coldues='';
            if($dues>10){
               $coldues='<h6 style="color:red;">'.number_format($dues, 2).'</h6>';
            }else{
               $coldues='<h6>'.number_format($dues, 2).'</h6>';
            }
            $odin.='
               <tr>
                     <td class="text-center">
                       <b>'.$row["stc_daily_purchase_party_name"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_daily_purchase_items_material_desc"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.$row["stc_daily_purchase_refr_no"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.date('d-m-y', strtotime($row["stc_daily_purchase_refr_date"])).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($row["stc_daily_purchase_items_qty"], 2).'/'.$row["stc_daily_purchase_items_unit"].'</b>
                     </td>          
                     <td class="text-right">
                       <b>'.number_format($row["stc_daily_purchase_items_rate"], 2).'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_daily_purchase_items_tax"].'%</b>
                     </td>
                     <td class="text-right">
                       <h6>'.number_format($total, 2).'</h6>
                     </td>
                     <td class="text-center">
                       <b>'.$paytype.'.</b>
                     </td>
                     <td>
                       <b>'.$paydate.'</b>
                     </td>
                     <td class="text-right">
                       '.$coldues.'
                     </td>
                     <td>
                       <b>'.$row["stc_daily_purchase_remarks"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_electronics_user_fullName"].'</b>
                     </td>
                  </tr>          
            ';          
         }
         
      }

      if($totaldues>10){
         $coltotaldues='<b style="color: red;">'.number_format($totaldues, 2).'</b>';
      }else{
         $coltotaldues='<b>'.number_format($totaldues, 2).'</b>';
      }
      $odin.='
               <tr>
                  <td class="text-center" colspan="7">Total Purchased</td>
                  <td class="text-right"><b>'.$grandtotal.'</b></td>
                  <td class="text-center" colspan="2">Total Dues</td>
                  <td class="text-right">'.$coltotaldues.'</td>
                  <td colspan="2"></td>
               </tr>
            </tbody>
            </table>
      ';
      return $odin;
   }

   // call sale
   public function stc_electronics_call_sale($bjornebegdate, $bjorneenddate){
      $grandtotal=0;
      $totaldues=0;
      $check_loki=mysqli_query($this->stc_dbs, "
         SELECT
            `stc_daily_sale_id`,
            `stc_daily_sale_party_name`,
            `stc_daily_sale_items_material_desc`,
            `stc_daily_sale_refr_no`,
            `stc_daily_sale_refr_date`,
            `stc_daily_sale_items_id`,
            `stc_daily_sale_items_qty`,
            `stc_daily_sale_items_rate`,
            `stc_daily_sale_items_unit`,
            `stc_daily_sale_items_tax`,
            `stc_daily_sale_remarks`,
            `stc_daily_sale_by`,
            `stc_electronics_user_fullName`
         FROM
            `stc_daily_sale_items`
         INNER JOIN 
            `stc_daily_sale` 
         ON 
            `stc_daily_sale_id` = `stc_daily_sale_items_order_id`
         INNER JOIN 
            `stc_electronics_user` 
         ON 
            `stc_electronics_user_id` = `stc_daily_sale_createdby`
         WHERE
            DATE(`stc_daily_sale_refr_date`) 
            BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."' 
            AND '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."'
         ORDER BY DATE(`stc_daily_sale_refr_date`) DESC
      ");
      $odin='
         <table class="mb-0 table table-bordered table-responsive" id="stc-reports-electronics-pending-view">
            <thead>
              <tr>
               <th scope="col">Party Name</th>
               <th scope="col">Material Details</th>
               <th scope="col">Invoice/ <br>Challan No</th>
               <th scope="col">Invoice/ <br>Challan Date</th>
               <th scope="col">Material Quantity</th>
               <th scope="col">Material Rate</th>
               <th scope="col">Tax</th>
               <th scope="col">Amount</th>
               <th scope="col">Payment Mode</th>
               <th scope="col">Payment Date</th> 
               <th scope="col">Due Amount</th> 
               <th scope="col">Remarks</th> 
               <th scope="col">Order By</th> 
               <th scope="col">Created By</th> 
              </tr>
            </thead>
            <tbody>  
       ';
      $do_action=mysqli_num_rows($check_loki);
      if($do_action == 0){
         $odin .= "<tr><td colspan='14' align='center'>No Record Found!!!</td></tr>";
      }else{
         foreach ($check_loki as $row) {
            $check_loki_nest=mysqli_query($this->stc_dbs, "
                  SELECT
                     `stc_daily_sale_payments_date`,
                     `stc_daily_sale_payments_type`,
                     `stc_daily_sale_payments_amount`
                  FROM
                     `stc_daily_sale_payments`
                  WHERE
                     `stc_daily_sale_payments_order_no`='".$row["stc_daily_sale_items_id"]."'
            ");
            $paydate='';
            $paytype='';
            $payamount=0;
            foreach ($check_loki_nest as $row2nd) {
               $paydate=date('d-m-Y', strtotime($row2nd['stc_daily_sale_payments_date']));
               $paytype=$row2nd['stc_daily_sale_payments_type'];
               $payamount+=$row2nd['stc_daily_sale_payments_amount'];
            }

            if($paytype==1){
               $paytype='Account';
            }elseif($paytype==2){
               $paytype='Advance';
            }elseif($paytype==3){
               $paytype='Cash';
            }else{
               $paytype='Credit';
            }
            $basic=$row['stc_daily_sale_items_qty'] * $row['stc_daily_sale_items_rate'];
            $gstamount=$basic * ($row['stc_daily_sale_items_tax']/100);
            $total=$basic + $gstamount;

            $dues=$total - $payamount;
            $grandtotal+=$total;
            $totaldues+=$dues;

            $coldues='';
            if($dues>10){
               $coldues='<h6 style="color:red;">'.number_format($dues, 2).'</h6>';
            }else{
               $coldues='<h6>'.number_format($dues, 2).'</h6>';
            }
            $odin.='
               <tr>
                     <td class="text-center">
                       <b>'.$row["stc_daily_sale_party_name"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_daily_sale_items_material_desc"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.$row["stc_daily_sale_refr_no"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.date('d-m-y', strtotime($row["stc_daily_sale_refr_date"])).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($row["stc_daily_sale_items_qty"], 2).'/'.$row["stc_daily_sale_items_unit"].'</b>
                     </td>          
                     <td class="text-right">
                       <b>'.number_format($row["stc_daily_sale_items_rate"], 2).'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_daily_sale_items_tax"].'%</b>
                     </td>
                     <td class="text-right">
                       <h6>'.number_format($total, 2).'</h6>
                     </td>
                     <td class="text-center">
                       <b>'.$paytype.'.</b>
                     </td>
                     <td>
                       <b>'.$paydate.'</b>
                     </td>
                     <td class="text-right">
                       '.$coldues.'
                     </td>
                     <td>
                       <b>'.$row["stc_daily_sale_remarks"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_daily_sale_by"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_electronics_user_fullName"].'</b>
                     </td>
                  </tr>          
            ';          
         }
      }

      if($totaldues>10){
         $coltotaldues='<b style="color: red;">'.number_format($totaldues, 2).'</b>';
      }else{
         $coltotaldues='<b>'.number_format($totaldues, 2).'</b>';
      }
      $odin.='
               <tr>
                  <td class="text-center" colspan="7">Total Sale</td>
                  <td class="text-right"><b>'.number_format($grandtotal, 2).'</b></td>
                  <td class="text-center" colspan="2">Total Dues</td>
                  <td class="text-right">'.$coltotaldues.'</td>
               </tr>
            </tbody>
            </table>
      ';
      return $odin;
   }
}

class ragnarReportsViewTradingPurchaseSaleReports extends tesseract{
   // trading purchase sale
   // call purchase
   public function stc_trading_call_purchase($bjornebegdate, $bjorneenddate){
      $grandtotal=0;
      $totaldues=0;
      $check_loki=mysqli_query($this->stc_dbs, "
         SELECT
            `stc_trading_purchase_id`,
            `stc_trading_purchase_date`,
            `stc_trading_product_title`,
            `stc_merchant_name`,
            `stc_trading_purchase_refrence_no`,
            `stc_trading_purchase_refrence_date`,
            `stc_trading_purchase_items_qty`,
            `stc_trading_purchase_items_price`,
            `stc_trading_purchase_items_unit`,
            `stc_trading_purchase_items_gst`,
            `stc_trading_purchase_remarks`,
            `stc_trading_user_name`
         FROM
            `stc_trading_purchase_items`
         INNER JOIN 
            `stc_trading_purchase` 
         ON 
            `stc_trading_purchase_items_purchase_id` = `stc_trading_purchase_id` 
         INNER JOIN 
            `stc_merchant` 
         ON 
            `stc_trading_purchase_purchaser_id` = `stc_merchant_id` 
         INNER JOIN 
            `stc_trading_product` 
         ON 
            `stc_trading_purchase_items_item_id` = `stc_trading_product_id`
         INNER JOIN 
            `stc_trading_user` 
         ON 
            `stc_trading_purchase_createdby` = `stc_trading_user_id`
         WHERE
             DATE(`stc_trading_purchase_refrence_date`) 
            BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."' 
            AND '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."'
         ORDER BY DATE(`stc_trading_purchase_date`) DESC
      ");
      $odin='
         <table class="mb-0 table table-bordered table-responsive stc-trading-purchasesale-table" id="stc-reports-trading-pending-view">
            <thead>
              <tr>
                <th class="text-center" scope="col">Party Name</th>
                <th class="text-center" scope="col">Material Details</th>
                <th class="text-center" scope="col">Invoice/ <br>Challan No</th>
                <th class="text-center" scope="col">Invoice/ <br>Challan Date</th>
                <th class="text-center" scope="col">Material Quantity</th>
                <th class="text-center" scope="col">Material Rate</th>
                <th class="text-center" scope="col">Tax</th>
                <th class="text-center" scope="col">Amount</th>
                <th class="text-center" scope="col">Payment Mode</th>
                <th class="text-center" scope="col">Payment Date</th>     
                <th class="text-center" scope="col">Due Amount</th>                         
                <th class="text-center" scope="col">Remarks</th>                          
                <th class="text-center" scope="col">Created By</th>  
              </tr>
            </thead>
            <tbody>  
       ';
      $do_action=mysqli_num_rows($check_loki);
      if($do_action == 0){
         $odin .= "<tr><td colspan='13' align='center'>No Record Found!!!</td></tr>";
      }else{
         foreach ($check_loki as $row) {
            $check_loki_nest=mysqli_query($this->stc_dbs, "
               SELECT
                   `stc_trading_purchase_payment_date`,
                   `stc_trading_purchase_payment_type`,
                   `stc_trading_purchase_payment_value`
               FROM
                   `stc_trading_purchase_payment`
               WHERE
                   `stc_trading_purchase_payment_purchase_id`='".$row["stc_trading_purchase_id"]."'
            ");
            $paydate='';
            $paytype='';
            $payamount=0;
            foreach ($check_loki_nest as $row2nd) {
               $paydate=date('d-m-Y', strtotime($row2nd['stc_trading_purchase_payment_date']));
               $paytype=$row2nd['stc_trading_purchase_payment_type'];
               $payamount+=$row2nd['stc_trading_purchase_payment_value'];
            }

            if($paytype=='Cash'){
               $paytype='Cash';
            }elseif($paytype=='Account'){
               $paytype='Account';
            }else{
               $paytype='Credit';
            }
            $basic=$row['stc_trading_purchase_items_qty'] * $row['stc_trading_purchase_items_price'];
            $gstamount=$basic * ($row['stc_trading_purchase_items_gst']/100);
            $total=$basic + $gstamount;

            $dues=$total - $payamount;

            if(mysqli_num_rows($check_loki_nest)>0){
               $dues=0;
            }
            
            $grandtotal+=$total;
            $totaldues+=$dues;
            $coldues='';
            if($dues>10){
               $coldues='<h6 style="color:red;">'.number_format($dues, 2).'</h6>';
            }else{
               $coldues='<h6>'.number_format($dues, 2).'</h6>';
            }
            $odin.='
               <tr>
                     <td class="text-center">
                       <b>'.$row["stc_merchant_name"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_trading_product_title"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.$row["stc_trading_purchase_refrence_no"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.date('d-m-y', strtotime($row["stc_trading_purchase_refrence_date"])).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($row["stc_trading_purchase_items_qty"], 2).'/'.$row["stc_trading_purchase_items_unit"].'</b>
                     </td>          
                     <td class="text-right">
                       <b>'.number_format($row["stc_trading_purchase_items_price"], 2).'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_trading_purchase_items_gst"].'%</b>
                     </td>
                     <td class="text-right">
                       <h6>'.number_format($total, 2).'</h6>
                     </td>
                     <td class="text-center">
                       <b>'.$paytype.'.</b>
                     </td>
                     <td>
                       <b>'.$paydate.'</b>
                     </td>
                     <td class="text-right">
                       '.$coldues.'
                     </td>
                     <td>
                       <b>'.$row["stc_trading_purchase_remarks"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_trading_user_name"].'</b>
                     </td>
                  </tr>          
            ';          
         }
      }

      if($totaldues>10){
         $coltotaldues='<b style="color: red;">'.number_format($totaldues, 2).'</b>';
      }else{
         $coltotaldues='<b>'.number_format($totaldues, 2).'</b>';
      }
      $odin.='
               <tr>
                  <td class="text-center" colspan="7">Total Purchased</td>
                  <td class="text-right"><b>'.number_format($grandtotal, 2).'</b></td>
                  <td class="text-center" colspan="2">Total Dues</td>
                  <td class="text-right">'.$coltotaldues.'</td>
                  <td colspan="2"></td>
               </tr>
            </tbody>
            </table>
      ';
      return $odin;
   }

   // call sale
   public function stc_trading_call_sale($bjornebegdate, $bjorneenddate){
      $grandtotal=0;
      $totaldues=0;
      $check_loki=mysqli_query($this->stc_dbs, "
         SELECT
            `stc_trading_sale_id`,
            `stc_trading_sale_date`,
            `stc_trading_product_title`,
            `stc_trading_customer_title`,
            `stc_trading_sale_items_item_qty`,
            `stc_trading_sale_items_item_price`,
            `stc_trading_sale_items_item_unit`,
            `stc_trading_sale_items_item_gst`,
            `stc_trading_sale_remarks`,
            `stc_trading_user_name`,
            `stc_trading_sale_order_by`
         FROM
            `stc_trading_sale`
         INNER JOIN 
            `stc_trading_customer` 
         ON 
            `stc_trading_sale_cust_id` = `stc_trading_customer_id` 
         INNER JOIN 
            `stc_trading_sale_items` 
         ON 
            `stc_trading_sale_id` = `stc_trading_sale_items_sale_id` 
         INNER JOIN 
            `stc_trading_product` 
         ON 
            `stc_trading_sale_items_item_id` = `stc_trading_product_id` 
         INNER JOIN 
            `stc_trading_user` 
         ON 
            `stc_trading_sale_created_by` = `stc_trading_user_id` 
         WHERE
            DATE(`stc_trading_sale_date`) 
            BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."' 
            AND '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."'
         ORDER BY DATE(`stc_trading_sale_date`) DESC
      ");
      $odin='
         <table class="mb-0 table table-bordered table-hover table-responsive stc-trading-purchasesale-table" id="stc-reports-trading-pending-view">
            <thead>
              <tr>
               <th class="text-center" scope="col">Party Name</th>
               <th class="text-center" scope="col">Material Details</th>
               <th class="text-center" scope="col">Invoice/ <br>Challan No</th>
               <th class="text-center" scope="col">Invoice/ <br>Challan Date</th>
               <th class="text-center" scope="col">Quantity</th>
               <th class="text-center" scope="col">Unit</th>
               <th class="text-center" scope="col">Rate</th>
               <th class="text-center" scope="col">28%</th>
               <th class="text-center" scope="col">18%</th>
               <th class="text-center" scope="col">Amount</th>
               <th class="text-center" scope="col">Payment Mode</th>
               <th class="text-center" scope="col">Payment Trans</th> 
               <th class="text-center" scope="col">Due Amount</th> 
               <th class="text-center" scope="col">Due (In Days)</th> 
               <th class="text-center" scope="col">Remarks</th> 
               <th class="text-center" scope="col">Order By</th> 
               <th class="text-center" scope="col">Created By</th> 
              </tr>
            </thead>
            <tbody>  
       ';
      $do_action=mysqli_num_rows($check_loki);
      if($do_action == 0){
         $odin .= "<tr><td colspan='14' align='center'>No Record Found!!!</td></tr>";
      }else{
         foreach ($check_loki as $row) {
            $check_loki_nest=mysqli_query($this->stc_dbs, "
               SELECT
                  `stc_trading_sale_payment_date`,
                  `stc_trading_sale_payment_type`,
                  `stc_trading_sale_payment_value`
               FROM
                  `stc_trading_sale_payment`
               WHERE
                  `stc_trading_sale_payment_sale_id`='".$row["stc_trading_sale_id"]."'
            ");
            $paydate='';
            $paytype='';
            $payamount=0;
            $paytrans='';
            foreach ($check_loki_nest as $row2nd) {
               $paydate=date('d-m-Y', strtotime($row2nd['stc_trading_sale_payment_date']));
               $paytype=$row2nd['stc_trading_sale_payment_type'];
               $payamount+=$row2nd['stc_trading_sale_payment_value'];
               $paytrans.='<span>'.number_format($row2nd['stc_trading_sale_payment_value'], 2).' on '.$paydate.'</span>.<br>';
            }
            if($paytype=='Cash'){
               $paytype='Cash';
            }elseif($paytype=='Account'){
               $paytype='Account';
            }else{
               $paytype='Credit';
            }
            $basic=$row['stc_trading_sale_items_item_qty'] * $row['stc_trading_sale_items_item_price'];
            $gstamount=$basic * ($row['stc_trading_sale_items_item_gst']/100);
            $total=$basic + $gstamount;

            $dues=$total - $payamount;

            if(mysqli_num_rows($check_loki_nest)>0){
               $dues=0;
            }

            $price=$row["stc_trading_sale_items_item_price"] + ($row["stc_trading_sale_items_item_price"] * $row["stc_trading_sale_items_item_gst"]/100);
            $gst18=0;
            $gst28=0;
            if($row["stc_trading_sale_items_item_gst"]==18){
               $gst18=$basic * $row["stc_trading_sale_items_item_gst"]/100;
            }else{
               $gst28=$basic * $row["stc_trading_sale_items_item_gst"]/100;
            }

            $grandtotal+=$total;
            $totaldues+=$dues;

            $coldues='';
            $numberDays=''; 

            $today = date("Y/m/d"); 
            $startTimeStamp = strtotime($row["stc_trading_sale_date"]);
            $endTimeStamp = strtotime($today);

            $timeDiff = abs($endTimeStamp - $startTimeStamp);

            $numberDays = $timeDiff/86400;  // 86400 seconds in one day

            if($dues>10){
               $coldues='<h6 style="color:red;">'.number_format($dues, 2).'</h6>';
               // and you might want to convert to integer
               $numberDays = '<h6 style="color:red;">'.intval($numberDays).'</h6>';
            }else{
               $coldues='<h6>'.number_format($dues, 2).'</h6>';
               $numberDays = '<h6">0 </h6>';
            }

            $odin.='
               <tr>
                     <td class="text-center">
                       <b>'.$row["stc_trading_customer_title"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_trading_product_title"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.$row["stc_trading_sale_id"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.date('d-m-y', strtotime($row["stc_trading_sale_date"])).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($row["stc_trading_sale_items_item_qty"], 2).'</b>
                     </td>          
                     <td class="text-right">
                       <b>'.$row["stc_trading_sale_items_item_unit"].'</b>
                     </td>          
                     <td class="text-right">
                       <b>'.number_format($price, 2).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($gst18, 2).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($gst28, 2).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($total, 2).'</b>
                     </td>
                     <td class="text-center">
                       <b>'.$paytype.'.</b>
                     </td>
                     <td>
                       <b>'.$paytrans.'</b>
                     </td>
                     <td class="text-right">
                        '.$coldues.'
                     </td>
                     <td class="text-center">
                        '.$numberDays.'
                     </td>
                     <td>
                       <b>'.$row["stc_trading_sale_remarks"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_trading_sale_order_by"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_trading_user_name"].'</b>
                     </td>
                  </tr>          
            ';          
         }
      }
      if($totaldues>10){
         $coltotaldues='<b style="color: red;">'.number_format($totaldues, 2).'</b>';
      }else{
         $coltotaldues='<b>'.number_format($totaldues, 2).'</b>';
      }
      $odin.='
               <tr>
                  <td class="text-right" colspan="9">Total Sale :</td>
                  <td class="text-right"><b>'.number_format($grandtotal, 2).'</b></td>
                  <td class="text-center" colspan="7"></td>
               </tr>
               <tr>
                  <td class="text-right" colspan="9">Total Recieved :</td>
                  <td class="text-right"><b>'.number_format(($grandtotal - $totaldues), 2).'</b></td>
                  <td class="text-center" colspan="7"></td>
               </tr>
               <tr>
                  <td class="text-right" colspan="9">Total Dues :</td>
                  <td class="text-right"><b>'.$coltotaldues.'</b></td>
                  <td class="text-center" colspan="7"></td>
               </tr>
            </tbody>
            </table>
      ';
      return $odin;
   }

   public function stc_gld_call_sale($bjornebegdate, $bjorneenddate, $branch){
      $grandtotal=0;
      $totaldues=0;
      $totaldiscounted=0;
      $totaldiscountedamt=0;
      
      $filter='';
      if($branch>0){
         if($branch==1){
            $branch='Dhatkidih';
         }elseif($branch==2){
            $branch='Sehrabazar';
         }elseif($branch==3){
            $branch='Kolkata';
         }
         $filter="`stc_trading_user_location`='".$branch."' AND ";
      }
      $query="
         SELECT `id`, `stc_product_name`, `stc_sub_cat_name`, `stc_product_unit`, `gld_customer_title`, `challan_number`, `bill_number`, `qty`, `rate`, `paid_amount`, `discount`, `payment_status`, `agent_id`, `status`, `created_date`, `stc_trading_user_name` 
         FROM `gld_challan`
         INNER JOIN `gld_customer` 
         ON `cust_id` = `gld_customer_id` 
         INNER JOIN `stc_product` 
         ON `product_id` = `stc_product_id` 
         INNER JOIN `stc_sub_category` 
         ON `stc_product_sub_cat_id` = `stc_sub_cat_id` 
         INNER JOIN `stc_trading_user` 
         ON `created_by` = `stc_trading_user_id`
         WHERE ".$filter."`status`<>0 AND DATE(`created_date`) BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."' 
         AND '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."' ORDER BY TIMESTAMP(`created_date`) DESC
      ";
      $check_loki=mysqli_query($this->stc_dbs, $query);
      $odin='
         <table class="mb-0 table table-bordered table-hover table-responsive" id="stc-reports-trading-pending-view">
            <thead>
              <tr>
               <th class="text-center" scope="col">Party Name</th>
               <th class="text-center" scope="col">Challan No</th>
               <th class="text-center" scope="col">Challan Date</th>
               <th class="text-center" scope="col">Material Details</th>
               <th class="text-center" scope="col">Quantity</th>
               <th class="text-center" scope="col">Unit</th>
               <th class="text-center" scope="col">Rate</th>
               <th class="text-center" scope="col">Amount</th>
               <th class="text-center" scope="col">Discount</th>
               <th class="text-center" scope="col">Total Amount</th>
               <th class="text-center" scope="col">Due Amount</th> 
               <th class="text-center" scope="col">Created By</th> 
              </tr>
            </thead>
            <tbody>  
       ';
      $do_action=mysqli_num_rows($check_loki);
      if($do_action == 0){
         $odin .= "<tr><td colspan='14' align='center'>No Record Found!!!</td></tr>";
      }else{
         foreach ($check_loki as $row) {
            $total=$row['qty'] * $row['rate'];
            $due=($total - $row['discount']) - $row['paid_amount'];
            $totaldiscounted+=$total - $row['discount'];
            $totaldiscountedamt+=$row['discount'];
            $product_name=$row["stc_product_name"];
            if($row["stc_sub_cat_name"]!="OTHERS"){
               $product_name=$row["stc_sub_cat_name"].' '.$row["stc_product_name"];
            }
            $odin.='
               <tr>
                     <td class="text-center">
                       <b>'.$row["gld_customer_title"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.$row["challan_number"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.date('d-m-y', strtotime($row["created_date"])).'</b>
                     </td>
                     <td>
                       <b>'.$product_name.'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($row["qty"], 2).'</b>
                     </td>          
                     <td class="text-right">
                       <b>'.$row["stc_product_unit"].'</b>
                     </td>          
                     <td class="text-right">
                       <b>'.number_format($row['rate'], 2).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($total, 2).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($row['discount'], 2).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($total - $row['discount'], 2).'</b>
                     </td>
                     <td class="text-right">
                        '.number_format($due, 2).'
                     </td>
                     <td>
                       <b>'.$row["stc_trading_user_name"].'</b>
                     </td>
                  </tr>          
            ';          
            $totaldues+=$due;
            $grandtotal+=$total;
         }
      }
      if($totaldues>10){
         $coltotaldues='<b style="color: red;">'.number_format($totaldues, 2).'</b>';
      }else{
         $coltotaldues='<b>'.number_format($totaldues, 2).'</b>';
      }
      $odin.='
               <tr>
                  <td class="text-right" colspan="9">Total Sale :</td>
                  <td class="text-right"><b>'.number_format($grandtotal, 2).'</b></td>
                  <td class="text-center" colspan="7"></td>
               </tr>
               <tr>
                  <td class="text-right" colspan="9">Total Discount :</td>
                  <td class="text-right"><b>'.number_format(($totaldiscountedamt), 2).'</b></td>
                  <td class="text-center" colspan="7"></td>
               </tr>
               <tr>
                  <td class="text-right" colspan="9">Total Amount :</td>
                  <td class="text-right"><b>'.number_format(($totaldiscounted), 2).'</b></td>
                  <td class="text-center" colspan="7"></td>
               </tr>
               <tr>
                  <td class="text-right" colspan="9">Total Recieved :</td>
                  <td class="text-right"><b>'.number_format(($totaldiscounted - $totaldues), 2).'</b></td>
                  <td class="text-center" colspan="7"></td>
               </tr>
               <tr>
                  <td class="text-right" colspan="9">Total Dues :</td>
                  <td class="text-right"><b>'.$coltotaldues.'</b></td>
                  <td class="text-center" colspan="7"></td>
               </tr>
            </tbody>
            </table>
      ';
      return $odin;
   }
}

class ragnarReportsViewgroceriesPurchaseSaleReports extends tesseract{
   // groceries purchase sale
   // call purchase
   public function stc_groceries_call_purchase($bjornebegdate, $bjorneenddate){
      $grandtotal=0;
      $totaldues=0;
      $check_loki=mysqli_query($this->stc_dbs, "
         SELECT
            `stc_groceries_purchase_id`,
            `stc_groceries_purchase_date`,
            `stc_groceries_product_title`,
            `stc_groceries_merchant_title`,
            `stc_groceries_purchase_refrence_no`,
            `stc_groceries_purchase_refrence_date`,
            `stc_groceries_purchase_items_qty`,
            `stc_groceries_purchase_items_price`,
            `stc_groceries_purchase_items_unit`,
            `stc_groceries_purchase_items_gst`,
            `stc_groceries_purchase_remarks`,
            `stc_groceries_user_name`
         FROM
            `stc_groceries_purchase_items`
         INNER JOIN 
            `stc_groceries_purchase` 
         ON 
            `stc_groceries_purchase_items_purchase_id` = `stc_groceries_purchase_id` 
         INNER JOIN 
            `stc_groceries_merchant` 
         ON 
            `stc_groceries_purchase_purchaser_id` = `stc_groceries_merchant_id` 
         INNER JOIN 
            `stc_groceries_product` 
         ON 
            `stc_groceries_purchase_items_item_id` = `stc_groceries_product_id`
         INNER JOIN 
            `stc_groceries_user` 
         ON 
            `stc_groceries_purchase_createdby` = `stc_groceries_user_id`
         WHERE
             DATE(`stc_groceries_purchase_refrence_date`) 
            BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."' 
            AND '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."'
         ORDER BY DATE(`stc_groceries_purchase_date`) DESC
      ");
      $odin='
         <table class="mb-0 table table-bordered table-responsive" id="stc-reports-groceries-pending-view">
            <thead>
              <tr>
                <th class="text-center" scope="col">Party Name</th>
                <th class="text-center" scope="col">Material Details</th>
                <th class="text-center" scope="col">Invoice/ <br>Challan No</th>
                <th class="text-center" scope="col">Invoice/ <br>Challan Date</th>
                <th class="text-center" scope="col">Material Quantity</th>
                <th class="text-center" scope="col">Material Rate</th>
                <th class="text-center" scope="col">Tax</th>
                <th class="text-center" scope="col">Amount</th>
                <th class="text-center" scope="col">Payment Mode</th>
                <th class="text-center" scope="col">Payment Date</th>     
                <th class="text-center" scope="col">Due Amount</th>                         
                <th class="text-center" scope="col">Remarks</th>                          
                <th class="text-center" scope="col">Created By</th>  
              </tr>
            </thead>
            <tbody>  
       ';
      $do_action=mysqli_num_rows($check_loki);
      if($do_action == 0){
         $odin .= "<tr><td colspan='13' align='center'>No Record Found!!!</td></tr>";
      }else{
         foreach ($check_loki as $row) {
            $check_loki_nest=mysqli_query($this->stc_dbs, "
               SELECT
                   `stc_groceries_purchase_payment_date`,
                   `stc_groceries_purchase_payment_type`,
                   `stc_groceries_purchase_payment_value`
               FROM
                   `stc_groceries_purchase_payment`
               WHERE
                   `stc_groceries_purchase_payment_purchase_id`='".$row["stc_groceries_purchase_id"]."'
            ");
            $paydate='';
            $paytype='';
            $payamount=0;
            foreach ($check_loki_nest as $row2nd) {
               $paydate=date('d-m-Y', strtotime($row2nd['stc_groceries_purchase_payment_date']));
               $paytype=$row2nd['stc_groceries_purchase_payment_type'];
               $payamount+=$row2nd['stc_groceries_purchase_payment_value'];
            }

            if($paytype=='Cash'){
               $paytype='Cash';
            }elseif($paytype=='Account'){
               $paytype='Account';
            }else{
               $paytype='Credit';
            }
            $basic=$row['stc_groceries_purchase_items_qty'] * $row['stc_groceries_purchase_items_price'];
            $gstamount=$basic * ($row['stc_groceries_purchase_items_gst']/100);
            $total=$basic + $gstamount;

            $dues=$total - $payamount;

            if(mysqli_num_rows($check_loki_nest)>0){
               $dues=0;
            }
            
            $grandtotal+=$total;
            $totaldues+=$dues;
            $coldues='';
            if($dues>10){
               $coldues='<h6 style="color:red;">'.number_format($dues, 2).'</h6>';
            }else{
               $coldues='<h6>'.number_format($dues, 2).'</h6>';
            }
            $odin.='
               <tr>
                     <td class="text-center">
                       <b>'.$row["stc_groceries_merchant_title"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_groceries_product_title"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.$row["stc_groceries_purchase_refrence_no"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.date('d-m-y', strtotime($row["stc_groceries_purchase_refrence_date"])).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($row["stc_groceries_purchase_items_qty"], 2).'/'.$row["stc_groceries_purchase_items_unit"].'</b>
                     </td>          
                     <td class="text-right">
                       <b>'.number_format($row["stc_groceries_purchase_items_price"], 2).'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_groceries_purchase_items_gst"].'%</b>
                     </td>
                     <td class="text-right">
                       <h6>'.number_format($total, 2).'</h6>
                     </td>
                     <td class="text-center">
                       <b>'.$paytype.'.</b>
                     </td>
                     <td>
                       <b>'.$paydate.'</b>
                     </td>
                     <td class="text-right">
                       '.$coldues.'
                     </td>
                     <td>
                       <b>'.$row["stc_groceries_purchase_remarks"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_groceries_user_name"].'</b>
                     </td>
                  </tr>          
            ';          
         }
      }

      if($totaldues>10){
         $coltotaldues='<b style="color: red;">'.number_format($totaldues, 2).'</b>';
      }else{
         $coltotaldues='<b>'.number_format($totaldues, 2).'</b>';
      }
      $odin.='
               <tr>
                  <td class="text-center" colspan="7">Total Purchased</td>
                  <td class="text-right"><b>'.number_format($grandtotal, 2).'</b></td>
                  <td class="text-center" colspan="2">Total Dues</td>
                  <td class="text-right">'.$coltotaldues.'</td>
                  <td colspan="2"></td>
               </tr>
            </tbody>
            </table>
      ';
      return $odin;
   }

   // call sale
   public function stc_groceries_call_sale($bjornebegdate, $bjorneenddate){
      $grandtotal=0;
      $totaldues=0;
      $check_loki=mysqli_query($this->stc_dbs, "
         SELECT
            `stc_groceries_sale_id`,
            `stc_groceries_sale_date`,
            `stc_groceries_product_title`,
            `stc_groceries_customer_title`,
            `stc_groceries_sale_items_item_qty`,
            `stc_groceries_sale_items_item_price`,
            `stc_groceries_sale_items_item_unit`,
            `stc_groceries_sale_items_item_gst`,
            `stc_groceries_sale_remarks`,
            `stc_groceries_user_name`,
            `stc_groceries_sale_order_by`
         FROM
            `stc_groceries_sale`
         INNER JOIN 
            `stc_groceries_customer` 
         ON 
            `stc_groceries_sale_cust_id` = `stc_groceries_customer_id` 
         INNER JOIN 
            `stc_groceries_sale_items` 
         ON 
            `stc_groceries_sale_id` = `stc_groceries_sale_items_sale_id` 
         INNER JOIN 
            `stc_groceries_product` 
         ON 
            `stc_groceries_sale_items_item_id` = `stc_groceries_product_id` 
         INNER JOIN 
            `stc_groceries_user` 
         ON 
            `stc_groceries_sale_created_by` = `stc_groceries_user_id` 
         WHERE
            DATE(`stc_groceries_sale_date`) 
            BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."' 
            AND '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."'
         ORDER BY DATE(`stc_groceries_sale_date`) DESC
      ");
      $odin='
         <table class="mb-0 table table-bordered table-hover table-responsive" id="stc-reports-groceries-pending-view">
            <thead>
              <tr>
               <th class="text-center" scope="col">Party Name</th>
               <th class="text-center" scope="col">Material Details</th>
               <th class="text-center" scope="col">Invoice/ <br>Challan No</th>
               <th class="text-center" scope="col">Invoice/ <br>Challan Date</th>
               <th class="text-center" scope="col">Quantity</th>
               <th class="text-center" scope="col">Unit</th>
               <th class="text-center" scope="col">Rate</th>
               <th class="text-center" scope="col">28%</th>
               <th class="text-center" scope="col">18%</th>
               <th class="text-center" scope="col">Amount</th>
               <th class="text-center" scope="col">Payment Mode</th>
               <th class="text-center" scope="col">Payment Trans</th> 
               <th class="text-center" scope="col">Due Amount</th> 
               <th class="text-center" scope="col">Due (In Days)</th> 
               <th class="text-center" scope="col">Remarks</th> 
               <th class="text-center" scope="col">Order By</th> 
               <th class="text-center" scope="col">Created By</th> 
              </tr>
            </thead>
            <tbody>  
       ';
      $do_action=mysqli_num_rows($check_loki);
      if($do_action == 0){
         $odin .= "<tr><td colspan='14' align='center'>No Record Found!!!</td></tr>";
      }else{
         foreach ($check_loki as $row) {
            $check_loki_nest=mysqli_query($this->stc_dbs, "
               SELECT
                  `stc_groceries_sale_payment_date`,
                  `stc_groceries_sale_payment_type`,
                  `stc_groceries_sale_payment_value`
               FROM
                  `stc_groceries_sale_payment`
               WHERE
                  `stc_groceries_sale_payment_sale_id`='".$row["stc_groceries_sale_id"]."'
            ");
            $paydate='';
            $paytype='';
            $payamount=0;
            $paytrans='';
            foreach ($check_loki_nest as $row2nd) {
               $paydate=date('d-m-Y', strtotime($row2nd['stc_groceries_sale_payment_date']));
               $paytype=$row2nd['stc_groceries_sale_payment_type'];
               $payamount+=$row2nd['stc_groceries_sale_payment_value'];
               $paytrans.='<span>'.number_format($row2nd['stc_groceries_sale_payment_value'], 2).' on '.$paydate.'</span>.<br>';
            }
            if($paytype=='Cash'){
               $paytype='Cash';
            }elseif($paytype=='Account'){
               $paytype='Account';
            }else{
               $paytype='Credit';
            }
            $basic=$row['stc_groceries_sale_items_item_qty'] * $row['stc_groceries_sale_items_item_price'];
            $gstamount=$basic * ($row['stc_groceries_sale_items_item_gst']/100);
            $total=$basic + $gstamount;

            $dues=$total - $payamount;

            if(mysqli_num_rows($check_loki_nest)>0){
               $dues=0;
            }

            $price=$row["stc_groceries_sale_items_item_price"] + ($row["stc_groceries_sale_items_item_price"] * $row["stc_groceries_sale_items_item_gst"]/100);
            $gst18=0;
            $gst28=0;
            if($row["stc_groceries_sale_items_item_gst"]==18){
               $gst18=$basic * $row["stc_groceries_sale_items_item_gst"]/100;
            }else{
               $gst28=$basic * $row["stc_groceries_sale_items_item_gst"]/100;
            }

            $grandtotal+=$total;
            $totaldues+=$dues;

            $coldues='';
            $numberDays=''; 

            $today = date("Y/m/d"); 
            $startTimeStamp = strtotime($row["stc_groceries_sale_date"]);
            $endTimeStamp = strtotime($today);

            $timeDiff = abs($endTimeStamp - $startTimeStamp);

            $numberDays = $timeDiff/86400;  // 86400 seconds in one day

            if($dues>10){
               $coldues='<h6 style="color:red;">'.number_format($dues, 2).'</h6>';
               // and you might want to convert to integer
               $numberDays = '<h6 style="color:red;">'.intval($numberDays).'</h6>';
            }else{
               $coldues='<h6>'.number_format($dues, 2).'</h6>';
               $numberDays = '<h6">0 </h6>';
            }

            $odin.='
               <tr>
                     <td class="text-center">
                       <b>'.$row["stc_groceries_customer_title"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_groceries_product_title"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.$row["stc_groceries_sale_id"].'</b>
                     </td>
                     <td class="text-center">
                       <b>'.date('d-m-y', strtotime($row["stc_groceries_sale_date"])).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($row["stc_groceries_sale_items_item_qty"], 2).'</b>
                     </td>          
                     <td class="text-right">
                       <b>'.$row["stc_groceries_sale_items_item_unit"].'</b>
                     </td>          
                     <td class="text-right">
                       <b>'.number_format($price, 2).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($gst18, 2).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($gst28, 2).'</b>
                     </td>
                     <td class="text-right">
                       <b>'.number_format($total, 2).'</b>
                     </td>
                     <td class="text-center">
                       <b>'.$paytype.'.</b>
                     </td>
                     <td>
                       <b>'.$paytrans.'</b>
                     </td>
                     <td class="text-right">
                        '.$coldues.'
                     </td>
                     <td class="text-center">
                        '.$numberDays.'
                     </td>
                     <td>
                       <b>'.$row["stc_groceries_sale_remarks"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_groceries_sale_order_by"].'</b>
                     </td>
                     <td>
                       <b>'.$row["stc_groceries_user_name"].'</b>
                     </td>
                  </tr>          
            ';          
         }
      }
      if($totaldues>10){
         $coltotaldues='<b style="color: red;">'.number_format($totaldues, 2).'</b>';
      }else{
         $coltotaldues='<b>'.number_format($totaldues, 2).'</b>';
      }
      $odin.='
               <tr>
                  <td class="text-right" colspan="9">Total Sale :</td>
                  <td class="text-right"><b>'.number_format($grandtotal, 2).'</b></td>
                  <td class="text-center" colspan="7"></td>
               </tr>
               <tr>
                  <td class="text-right" colspan="9">Total Recieved :</td>
                  <td class="text-right"><b>'.number_format(($grandtotal - $totaldues), 2).'</b></td>
                  <td class="text-center" colspan="7"></td>
               </tr>
               <tr>
                  <td class="text-right" colspan="9">Total Dues :</td>
                  <td class="text-right"><b>'.$coltotaldues.'</b></td>
                  <td class="text-center" colspan="7"></td>
               </tr>
            </tbody>
            </table>
      ';
      return $odin;
   }
}

class ragnarReportsViewSchoolCanteenReports extends tesseract{
   // call canteen
   public function stc_school_call_canteen($bjornebegdate, $bjorneenddate){
      $odin='';
      $odin_get_req_qry=mysqli_query($this->stc_dbs, "
         SELECT DISTINCT
             `stc_school_canteen_date`
         FROM
             `stc_school_canteen`
         WHERE 
            DATE(`stc_school_canteen_date`) 
            BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."'
            AND '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."'
         ORDER BY DATE(`stc_school_canteen_date`) DESC
      ");
      if(mysqli_num_rows($odin_get_req_qry)>0){
         $maxtotal=0;
         $maxstudent=0;
         $maxteacher=0;
         $maxstaff=0;
         $maxguest=0;

         $studtotbreakfast=0;
         $studtotlunch=0;
         $studtoteveningsnacks=0;
         $studtotdinner=0;

         $teachertotbreakfast=0;
         $teachertotlunch=0;
         $teachertoteveningsnacks=0;
         $teachertotdinner=0;

         $stafftotbreakfast=0;
         $stafftotlunch=0;
         $stafftoteveningsnacks=0;
         $stafftotdinner=0;

         $guesttotbreakfast=0;
         $guesttotlunch=0;
         $guesttoteveningsnacks=0;
         $guesttotdinner=0;
         foreach($odin_get_req_qry as $req_row){
            $student=0;
            $teacher=0;
            $staff=0;
            $guest=0;
            $total=0;

            $odin_getstudentqry=mysqli_query($this->stc_dbs, "
               SELECT
                   `stc_school_canteen_serve_quantity`
               FROM
                   `stc_school_canteen`
               WHERE 
                  DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
               AND 
                  `stc_school_canteen_serve_type`='student'
               ORDER BY DATE(`stc_school_canteen_date`) DESC
            ");

            foreach($odin_getstudentqry as $odin_getstudentrow){
               $student+=$odin_getstudentrow['stc_school_canteen_serve_quantity'];
            }

            $odin_getteacherqry=mysqli_query($this->stc_dbs, "
               SELECT
                   `stc_school_canteen_serve_quantity`
               FROM
                   `stc_school_canteen`
               WHERE 
                  DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
               AND 
                  `stc_school_canteen_serve_type`='teacher'
               ORDER BY DATE(`stc_school_canteen_date`) DESC
            ");

            foreach($odin_getteacherqry as $odin_getteacherrow){
               $teacher+=$odin_getteacherrow['stc_school_canteen_serve_quantity'];
            }

            $odin_getstaffqry=mysqli_query($this->stc_dbs, "
               SELECT
                   `stc_school_canteen_serve_quantity`
               FROM
                   `stc_school_canteen`
               WHERE 
                  DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
               AND 
                  `stc_school_canteen_serve_type`='staff'
               ORDER BY DATE(`stc_school_canteen_date`) DESC
            ");

            foreach($odin_getstaffqry as $odin_getstaffrow){
               $staff+=$odin_getstaffrow['stc_school_canteen_serve_quantity'];
            }

            $odin_getguestqry=mysqli_query($this->stc_dbs, "
               SELECT
                   `stc_school_canteen_serve_quantity`
               FROM
                   `stc_school_canteen`
               WHERE 
                  DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
               AND 
                  `stc_school_canteen_serve_type`='guest'
               ORDER BY DATE(`stc_school_canteen_date`) DESC
            ");

            foreach($odin_getguestqry as $odin_getguestrow){
               $guest+=$odin_getguestrow['stc_school_canteen_serve_quantity'];
            }

            $total= $student + $teacher + $staff + $guest;
            $maxtotal+=$total;
            $maxstudent+=$student;
            $maxteacher+=$teacher;
            $maxstaff+=$staff;
            $maxguest+=$guest;
            $odin.='
               <tr>
                  <td class="text-center">'.date('d-m-Y', strtotime($req_row['stc_school_canteen_date'])).'</td>
                  <td class="text-right">'.number_format($student, 2).'</td>
                  <td class="text-right">'.number_format($teacher, 2).'</td>
                  <td class="text-right">'.number_format($staff, 2).'</td>
                  <td class="text-right">'.number_format($guest, 2).'</td>
                  <td class="text-right"><a href="#" class="stc-school-showdeep-req" id="'.$req_row['stc_school_canteen_date'].'">'.number_format($total, 2).'</a></td>
               </tr>
            ';

            // for total serving time qry
            $odin_getqry=mysqli_query($this->stc_dbs, "
               SELECT
                   `stc_school_canteen_serve_type`,
                   `stc_school_canteen_serve_time`,
                   `stc_school_canteen_serve_quantity`
               FROM
                   `stc_school_canteen`
               WHERE
                   DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_canteen_date'])."'
            ");
            foreach($odin_getqry as $odin_getrow){
               if($odin_getrow['stc_school_canteen_serve_type']=="student" && $odin_getrow['stc_school_canteen_serve_time']=="breakfast"){
                  $studtotbreakfast+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="student" && $odin_getrow['stc_school_canteen_serve_time']=="lunch"){
                  $studtotlunch+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="student" && $odin_getrow['stc_school_canteen_serve_time']=="evening snacks"){
                  $studtoteveningsnacks+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="student" && $odin_getrow['stc_school_canteen_serve_time']=="dinner"){
                  $studtotdinner+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="teacher" && $odin_getrow['stc_school_canteen_serve_time']=="breakfast"){
                  $teachertotbreakfast+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="teacher" && $odin_getrow['stc_school_canteen_serve_time']=="lunch"){
                  $teachertotlunch+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="teacher" && $odin_getrow['stc_school_canteen_serve_time']=="evening snacks"){
                  $teachertoteveningsnacks+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="teacher" && $odin_getrow['stc_school_canteen_serve_time']=="dinner"){
                  $teachertotdinner+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="staff" && $odin_getrow['stc_school_canteen_serve_time']=="breakfast"){
                  $stafftotbreakfast+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="staff" && $odin_getrow['stc_school_canteen_serve_time']=="lunch"){
                  $stafftotlunch+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="staff" && $odin_getrow['stc_school_canteen_serve_time']=="evening snacks"){
                  $stafftoteveningsnacks+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="staff" && $odin_getrow['stc_school_canteen_serve_time']=="dinner"){
                  $stafftotdinner+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="guest" && $odin_getrow['stc_school_canteen_serve_time']=="breakfast"){
                  $guesttotbreakfast+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="guest" && $odin_getrow['stc_school_canteen_serve_time']=="lunch"){
                  $guesttotlunch+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="guest" && $odin_getrow['stc_school_canteen_serve_time']=="evening snacks"){
                  $guesttoteveningsnacks+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }elseif($odin_getrow['stc_school_canteen_serve_type']=="guest" && $odin_getrow['stc_school_canteen_serve_time']=="dinner"){
                  $guesttotdinner+=$odin_getrow['stc_school_canteen_serve_quantity'];
               }
            }  
         }
         $odin.='
            <tr style="font-size: 20px;font-weight: bold;">
               <td class="text-center">Total</td>
               <td class="text-right">'.number_format($maxstudent, 2).' NOS</td>
               <td class="text-right">'.number_format($maxteacher, 2).' NOS</td>
               <td class="text-right">'.number_format($maxstaff, 2).' NOS</td>
               <td class="text-right">'.number_format($maxguest, 2).' NOS</td>
               <td class="text-right">'.number_format($maxtotal, 2).' NOS</td>
            </tr>
         ';

         $odin.='
            <tr style="font-size: 20px;font-weight: bold;">
               <td class="text-center">Total Breakfast</td>
               <td class="text-right">'.number_format($studtotbreakfast, 2).' NOS</td>
               <td class="text-right">'.number_format($teachertotbreakfast, 2).' NOS</td>
               <td class="text-right">'.number_format($stafftotbreakfast, 2).' NOS</td>
               <td class="text-right">'.number_format($guesttotbreakfast, 2).' NOS</td>
               <td class="text-right">'.number_format($studtotbreakfast + $teachertotbreakfast + $stafftotbreakfast + $guesttotbreakfast, 2).' NOS</td>
            </tr>
         ';
         $odin.='
            <tr style="font-size: 20px;font-weight: bold;">
               <td class="text-center">Total Lunch</td>
               <td class="text-right">'.number_format($studtotlunch, 2).' NOS</td>
               <td class="text-right">'.number_format($teachertotlunch, 2).' NOS</td>
               <td class="text-right">'.number_format($stafftotlunch, 2).' NOS</td>
               <td class="text-right">'.number_format($guesttotlunch, 2).' NOS</td>
               <td class="text-right">'.number_format($studtotlunch + $teachertotlunch + $stafftotlunch + $guesttotlunch, 2).' NOS</td>
            </tr>
         ';
         $odin.='
            <tr style="font-size: 20px;font-weight: bold;">
               <td class="text-center">Total Evening Snacks</td>
               <td class="text-right">'.number_format($studtoteveningsnacks, 2).' NOS</td>
               <td class="text-right">'.number_format($teachertoteveningsnacks, 2).' NOS</td>
               <td class="text-right">'.number_format($stafftoteveningsnacks, 2).' NOS</td>
               <td class="text-right">'.number_format($guesttoteveningsnacks, 2).' NOS</td>
               <td class="text-right">'.number_format($studtoteveningsnacks + $teachertoteveningsnacks + $stafftoteveningsnacks + $guesttoteveningsnacks, 2).' NOS</td>
            </tr>
         ';
         $odin.='
            <tr style="font-size: 20px;font-weight: bold;">
               <td class="text-center">Total Dinner</td>
               <td class="text-right">'.number_format($studtotdinner, 2).' NOS</td>
               <td class="text-right">'.number_format($teachertotdinner, 2).' NOS</td>
               <td class="text-right">'.number_format($stafftotdinner, 2).' NOS</td>
               <td class="text-right">'.number_format($guesttotdinner, 2).' NOS</td>
               <td class="text-right">'.number_format($studtotdinner + $teachertotdinner + $stafftotdinner + $guesttotdinner, 2).' NOS</td>
            </tr>
         ';
      }else{
         $odin.='
            <tr>
               <td colspan="6" class="text-center">No Records Found!!!</td>
            </tr>
         ';
      }
      return $odin;
   }

   // search by date
   public function stc_search_by_date($search){
      $odin='';
      $stbf=0;
      $stlunch=0;
      $stes=0;
      $stdinner=0;

      $tebf=0;
      $telunch=0;
      $tees=0;
      $tedinner=0;

      $sfbf=0;
      $sflunch=0;
      $sfes=0;
      $sfdinner=0;

      $gsbf=0;
      $gslunch=0;
      $gses=0;
      $gsdinner=0;

      $odin_getqry=mysqli_query($this->stc_dbs, "
         SELECT
             `stc_school_canteen_serve_type`,
             `stc_school_canteen_serve_time`,
             `stc_school_canteen_serve_quantity`
         FROM
             `stc_school_canteen`
         WHERE
             DATE(`stc_school_canteen_date`)='".mysqli_real_escape_string($this->stc_dbs, $search)."'
      ");
      foreach($odin_getqry as $odin_getrow){
         if($odin_getrow['stc_school_canteen_serve_type']=="student" && $odin_getrow['stc_school_canteen_serve_time']=="breakfast"){
            $stbf+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="student" && $odin_getrow['stc_school_canteen_serve_time']=="lunch"){
            $stlunch+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="student" && $odin_getrow['stc_school_canteen_serve_time']=="evening snacks"){
            $stes+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="student" && $odin_getrow['stc_school_canteen_serve_time']=="dinner"){
            $stdinner+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="teacher" && $odin_getrow['stc_school_canteen_serve_time']=="breakfast"){
            $tebf+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="teacher" && $odin_getrow['stc_school_canteen_serve_time']=="lunch"){
            $telunch+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="teacher" && $odin_getrow['stc_school_canteen_serve_time']=="evening snacks"){
            $tees+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="teacher" && $odin_getrow['stc_school_canteen_serve_time']=="dinner"){
            $tedinner+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="staff" && $odin_getrow['stc_school_canteen_serve_time']=="breakfast"){
            $sfbf+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="staff" && $odin_getrow['stc_school_canteen_serve_time']=="lunch"){
            $sflunch+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="staff" && $odin_getrow['stc_school_canteen_serve_time']=="evening snacks"){
            $sfes+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="staff" && $odin_getrow['stc_school_canteen_serve_time']=="dinner"){
            $sfdinner+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="guest" && $odin_getrow['stc_school_canteen_serve_time']=="breakfast"){
            $gsbf+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="guest" && $odin_getrow['stc_school_canteen_serve_time']=="lunch"){
            $gslunch+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="guest" && $odin_getrow['stc_school_canteen_serve_time']=="evening snacks"){
            $gses+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }elseif($odin_getrow['stc_school_canteen_serve_type']=="guest" && $odin_getrow['stc_school_canteen_serve_time']=="dinner"){
            $gsdinner+=$odin_getrow['stc_school_canteen_serve_quantity'];
         }
      }
      $odin='
            <tr>
                    <td class="text-center">Breakfast</td>
                    <td class="text-right">'.number_format($stbf, 2).'</td>
                    <td class="text-right">'.number_format($tebf, 2).'</td>
                    <td class="text-right">'.number_format($sfbf, 2).'</td>
                    <td class="text-right">'.number_format($gsbf, 2).'</td>
                </tr>
                <tr>
                    <td class="text-center">Lunch</td>
                    <td class="text-right">'.number_format($stlunch, 2).'</td>
                    <td class="text-right">'.number_format($telunch, 2).'</td>
                    <td class="text-right">'.number_format($sflunch, 2).'</td>
                    <td class="text-right">'.number_format($gslunch, 2).'</td>
                </tr>
                <tr>
                    <td class="text-center">Evening Snacks</td>
                    <td class="text-right">'.number_format($stes, 2).'</td>
                    <td class="text-right">'.number_format($tees, 2).'</td>
                    <td class="text-right">'.number_format($sfes, 2).'</td>
                    <td class="text-right">'.number_format($gses, 2).'</td>
                </tr>
                <tr>
                    <td class="text-center">Dinner</td>
                    <td class="text-right">'.number_format($stdinner, 2).'</td>
                    <td class="text-right">'.number_format($tedinner, 2).'</td>
                    <td class="text-right">'.number_format($sfdinner, 2).'</td>
                    <td class="text-right">'.number_format($gsdinner, 2).'</td>
                </tr>
                <tr>
                    <td class="text-center">Total</td>
                    <td class="text-right">'.number_format(($stbf + $stlunch + $stes + $stdinner), 2).' NOS</td>
                    <td class="text-right">'.number_format(($tebf + $telunch + $tees + $tedinner), 2).' NOS</td>
                    <td class="text-right">'.number_format(($sfbf + $sflunch + $sfes + $sfdinner), 2).' NOS</td>
                    <td class="text-right">'.number_format(($gsbf + $gslunch + $gses + $gsdinner), 2).' NOS</td>
                </tr>
      ';
      return $odin;
   }
}

class ragnarReportsViewSchoolFeeReports extends tesseract{
   // call fee
   public function stc_school_call_fee($bjornebegdate, $bjorneenddate, $bjorneschool) {
    // Initialize variables
    $list_table = '';
    $summary_table = '';
    $school_attr = '';
    
    // Build school filter condition
    if (!empty($bjorneschool)) {
        $school_conditions = array_map(function($school) {
            return "`stc_school_fee_which_school`='".mysqli_real_escape_string($this->stc_dbs, $school)."'";
        }, $bjorneschool);
        
        $school_attr = '('.implode(' OR ', $school_conditions).') AND ';
    }
    
    // Get current period records
    $current_query = "
        SELECT 
            `stc_school_fee_id`,
            `stc_school_fee_which_school`,
            `stc_school_fee_date`
        FROM `stc_school_fee`
        WHERE {$school_attr}(
            DATE(`stc_school_fee_date`) BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."'
            AND '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."'
        )
        ORDER BY DATE(`stc_school_fee_date`) DESC
    ";
    
    $odin_get_req_qry = mysqli_query($this->stc_dbs, $current_query);
    
    if (mysqli_num_rows($odin_get_req_qry) > 0) {
        // Get previous month closing balance
        $calculatedate = date('Y-m-d', strtotime("-1 months", strtotime($bjornebegdate)));
        $year = date("Y", strtotime($calculatedate));
        $month = date("m", strtotime($calculatedate));
        $effectiveDate = date('Y-m-d', strtotime("01-{$month}-{$year}"));
        
        $prev_query = "
            SELECT 
                `stc_school_month_closing_value`
            FROM `stc_school_month_closing`
            WHERE {$school_attr}(
                DATE(`stc_school_month_closing_date`) 
                BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $effectiveDate)."'
                AND '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."'
            )
            ORDER BY DATE(`stc_school_month_closing_date`) DESC
        ";
        
        $odin_get_prevreq_qry = mysqli_query($this->stc_dbs, $prev_query);
        
        $totalprevmonthincome = 0;
        while ($prev_row = mysqli_fetch_assoc($odin_get_prevreq_qry)) {
            $totalprevmonthincome += $prev_row['stc_school_month_closing_value'];
        }
        
        // Initialize totals
        $totals = [
            'monthfee' => 0, 'admfee' => 0, 'readmfee' => 0, 'book' => 0,
            'transport' => 0, 'donation' => 0, 'dayboarding' => 0, 'neat' => 0,
            'other' => 0, 'cashback' => 0, 'dsal' => 0, 'ssal' => 0, 'vfuel' => 0,
            'vmaint' => 0, 'electricity' => 0, 'canteen' => 0, 'expense' => 0,
            'maintcost' => 0, 'projectcost' => 0, 'total' => 0
        ];
        
        // Process current records
        while ($req_row = mysqli_fetch_assoc($odin_get_req_qry)) {
            $detail_query = "
                SELECT 
                    `stc_school_fee_which_school`, `stc_school_fee_monthly_fee`,
                    `stc_school_fee_admission_fee`, `stc_school_fee_readmission_fee`,
                    `stc_school_fee_book_charge`, `stc_school_fee_transportation`,
                    `stc_school_fee_donation`, `stc_school_fee_dayboarding`,
                    `stc_school_fee_neat`, `stc_school_fee_others`, `stc_school_fee_cashback`,
                    `stc_school_fee_dstaffsal`, `stc_school_fee_teacherssal`,
                    `stc_school_fee_vehiclefuel`, `stc_school_fee_vehiclemaintenance`,
                    `stc_school_fee_electricity`, `stc_school_fee_canteen`,
                    `stc_school_fee_expense`, `stc_school_fee_maint_cost`,
                    `stc_school_fee_project_cost`, `stc_school_fee_remarks`,
                    `stc_school_user_fullName`
                FROM `stc_school_fee`
                INNER JOIN `stc_school` 
                    ON `stc_school_fee_created_by`=`stc_school_user_id`
                WHERE `stc_school_fee_id`='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_fee_id'])."'
                AND `stc_school_fee_which_school`='".mysqli_real_escape_string($this->stc_dbs, $req_row['stc_school_fee_which_school'])."'
            ";
            
            $detail_result = mysqli_query($this->stc_dbs, $detail_query);
            $detail_row = mysqli_fetch_assoc($detail_result);
            
            $current_total = $detail_row['stc_school_fee_monthly_fee'] + $detail_row['stc_school_fee_admission_fee'] + 
                            $detail_row['stc_school_fee_readmission_fee'] + $detail_row['stc_school_fee_book_charge'] + 
                            $detail_row['stc_school_fee_transportation'] + $detail_row['stc_school_fee_donation'] + 
                            $detail_row['stc_school_fee_dayboarding'] + $detail_row['stc_school_fee_neat'] + 
                            $detail_row['stc_school_fee_others'] + $detail_row['stc_school_fee_cashback'];
            
            // Add to list table
            $list_table .= '
                <tr>
                    <td class="text-center">'.$detail_row['stc_school_fee_which_school'].'</td>
                    <td class="text-center">'.date('d-m-Y', strtotime($req_row['stc_school_fee_date'])).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_monthly_fee'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_admission_fee'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_readmission_fee'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_book_charge'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_transportation'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_donation'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_dayboarding'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_neat'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_others'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_cashback'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_dstaffsal'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_teacherssal'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_vehiclefuel'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_vehiclemaintenance'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_electricity'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_canteen'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_maint_cost'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_project_cost'], 2).'</td>
                    <td class="text-right">'.number_format($detail_row['stc_school_fee_expense'], 2).'</td>
                    <td class="text-right">'.number_format($current_total, 2).'</td>
                    <td class="text-right">'.$detail_row['stc_school_fee_remarks'].'</td>
                    <td class="text-right">'.$detail_row['stc_school_user_fullName'].'</td>
                </tr>
            ';
            
            // Update totals
            $totals['monthfee'] += $detail_row['stc_school_fee_monthly_fee'];
            $totals['admfee'] += $detail_row['stc_school_fee_admission_fee'];
            $totals['readmfee'] += $detail_row['stc_school_fee_readmission_fee'];
            $totals['book'] += $detail_row['stc_school_fee_book_charge'];
            $totals['transport'] += $detail_row['stc_school_fee_transportation'];
            $totals['donation'] += $detail_row['stc_school_fee_donation'];
            $totals['dayboarding'] += $detail_row['stc_school_fee_dayboarding'];
            $totals['neat'] += $detail_row['stc_school_fee_neat'];
            $totals['other'] += $detail_row['stc_school_fee_others'];
            $totals['cashback'] += $detail_row['stc_school_fee_cashback'];
            $totals['dsal'] += $detail_row['stc_school_fee_dstaffsal'];
            $totals['ssal'] += $detail_row['stc_school_fee_teacherssal'];
            $totals['vfuel'] += $detail_row['stc_school_fee_vehiclefuel'];
            $totals['vmaint'] += $detail_row['stc_school_fee_vehiclemaintenance'];
            $totals['electricity'] += $detail_row['stc_school_fee_electricity'];
            $totals['canteen'] += $detail_row['stc_school_fee_canteen'];
            $totals['expense'] += $detail_row['stc_school_fee_expense'];
            $totals['maintcost'] += $detail_row['stc_school_fee_maint_cost'];
            $totals['projectcost'] += $detail_row['stc_school_fee_project_cost'];
            $totals['total'] += $current_total;
        }
        
        // Get fee target
        $school_list = "'" . implode("','", $bjorneschool) . "'";
        $tgmonth= date('m', strtotime($bjornebegdate));
        $tgyear= date('Y', strtotime($bjornebegdate));
        $tfilterdate = date('Y-m', strtotime("{$tgyear}-{$tgmonth}"));
        $target_query = "
            SELECT `target_amount` FROM `stc_school_feetarget`
            WHERE `school` IN ({$school_list}) AND `month` = '".mysqli_real_escape_string($this->stc_dbs, $tfilterdate)."'
        ";
        $target_result = mysqli_query($this->stc_dbs, $target_query);
        
        $fee_target = 0;
        while ($target_row = mysqli_fetch_assoc($target_result)) {
            $fee_target += $target_row['target_amount'];
        }
        
        // Calculate balance and grand total
        $balance_amount = $totals['monthfee'] - $fee_target;
        $colorAlert = $balance_amount < 0 ? 'style="color: #cf0000;"' : '';
        
        $total_expenditure = $totals['dsal'] + $totals['ssal'] + $totals['vfuel'] + 
                            $totals['vmaint'] + $totals['electricity'] + $totals['canteen'] + 
                            $totals['maintcost'] + $totals['projectcost'] + $totals['expense'];
        
        $grand_total = ($totalprevmonthincome + $totals['total']) - $total_expenditure;
        
        // Build summary table
        $summary_table = '
            <table class="table table-bordered">
                <tr style="font-size: 20px;font-weight: bold;">
                    <td class="text-right" colspan="2">Previous Total Income:</td>
                    <td class="text-right">'.number_format($totalprevmonthincome, 2).'</td>
                    <td class="text-right" colspan="2">Total D Staff Salary:</td>
                    <td class="text-right">'.number_format($totals['dsal'], 2).'</td>
                </tr>
                
                <tr style="font-size: 20px;font-weight: bold;">
                    <td class="text-right" colspan="2">
                        Total Monthly Fee Amount:<br/>
                        Monthly Fee Target:<br/>
                        Balance:
                    </td>
                    <td class="text-right">
                        '.number_format($totals['monthfee'], 2).'<br/>
                        '.number_format($fee_target, 2).'<br/>
                        <span '.$colorAlert.'>'.number_format($balance_amount, 2).'</span><br/>
                    </td>
                    <td class="text-right" colspan="2">Total Teachers Salary:</td>
                    <td class="text-right">'.number_format($totals['ssal'], 2).'</td>
                </tr>
                
                <tr style="font-size: 20px;font-weight: bold;">
                    <td class="text-right" colspan="2">Total New Admission Fee Amount:</td>
                    <td class="text-right">'.number_format($totals['admfee'], 2).'</td>
                    <td class="text-right" colspan="2">Total Vehicle Fuel Amount:</td>
                    <td class="text-right">'.number_format($totals['vfuel'], 2).'</td>
                </tr>
                
                <tr style="font-size: 20px;font-weight: bold;">
                    <td class="text-right" colspan="2">Total Re Admission Fee Amount:</td>
                    <td class="text-right">'.number_format($totals['readmfee'], 2).'</td>
                    <td class="text-right" colspan="2">Total Vehicle Maintenance Amount:</td>
                    <td class="text-right">'.number_format($totals['vmaint'], 2).'</td>
                </tr>
                
                <tr style="font-size: 20px;font-weight: bold;">
                    <td class="text-right" colspan="2">Total Books Amount:</td>
                    <td class="text-right">'.number_format($totals['book'], 2).'</td>
                    <td class="text-right" colspan="2">Total Electricity Expenses Amount:</td>
                    <td class="text-right">'.number_format($totals['electricity'], 2).'</td>
                </tr>
                
                <tr style="font-size: 20px;font-weight: bold;">
                    <td class="text-right" colspan="2">Total Transportation Amount:</td>
                    <td class="text-right">'.number_format($totals['transport'], 2).'</td>
                    <td class="text-right" colspan="2">Total Maintenance Cost:</td>
                    <td class="text-right">'.number_format($totals['maintcost'], 2).'</td>
                </tr>
                
                <tr style="font-size: 20px;font-weight: bold;">
                    <td class="text-right" colspan="2">Total Donation Amount:</td>
                    <td class="text-right">'.number_format($totals['donation'], 2).'</td>
                    <td class="text-right" colspan="2">Total Project Cost:</td>
                    <td class="text-right">'.number_format($totals['projectcost'], 2).'</td>
                </tr>
                
                <tr style="font-size: 20px;font-weight: bold;">
                    <td class="text-right" colspan="2">Total Day Boarding Amount:</td>
                    <td class="text-right">'.number_format($totals['dayboarding'], 2).'</td>
                    <td class="text-right" colspan="2">Total Canteen Expenses Amount:</td>
                    <td class="text-right">'.number_format($totals['canteen'], 2).'</td>
                </tr>
                
                <tr style="font-size: 20px;font-weight: bold;">
                    <td class="text-right" colspan="2">Total NEET Amount:</td>
                    <td class="text-right">'.number_format($totals['neat'], 2).'</td>
                    <td class="text-right" colspan="2">Total Other Expenses Amount:</td>
                    <td class="text-right">'.number_format($totals['expense'], 2).'</td>
                </tr>
                
                <tr style="font-size: 20px;font-weight: bold;">
                    <td class="text-right" colspan="2">Total Other Charges Amount:</td>
                    <td class="text-right">'.number_format($totals['other'], 2).'</td>
                    <td class="text-right" colspan="2">Total Expenditure Amount:</td>
                    <td class="text-right">'.number_format($total_expenditure, 2).'</td>
                </tr>
                
                <tr style="font-size: 20px;font-weight: bold;">
                    <td class="text-right" colspan="2">Total Cashback Amount:</td>
                    <td class="text-right">'.number_format($totals['cashback'], 2).'</td>
                    <td class="text-right" colspan="2">Total Income Amount:</td>
                    <td class="text-right">'.number_format($totalprevmonthincome + $totals['total'], 2).'</td>
                </tr>
                
                <tr style="font-size: 20px;font-weight: bold;">
                    <td class="text-right" colspan="5">
                        Grand Total (Inc Previous Month) = '.number_format($totalprevmonthincome + $totals['total'], 2).' - 
                        '.number_format($total_expenditure, 2).' = 
                        '.number_format($grand_total, 2).'
                    </td>
                    <td class="text-right" colspan="2"></td>
                </tr>
            </table>
        ';
    } else {
        $list_table = '
            <tr>
                <td colspan="24" class="text-center">No Records Found!!!</td>
            </tr>
        ';
    }
    
    return [
        'list_table' => $list_table,
        'summary_table' => $summary_table
    ];
}

   // call attendance
   public function stc_school_call_attendance($stc_school_month){
      $cmonth=date('F', strtotime($stc_school_month));
      $month=date('m', strtotime($stc_school_month));
      $year=date('Y', strtotime($stc_school_month));
      $date_array=array(
         'January' => 31,
         'February' => 28,
         'March' => 31,
         'April' => 30,
         'May' => 31,
         'June' => 30,
         'July' => 31,
         'August' => 31,
         'September' => 30,
         'October' => 31,
         'November' => 30,
         'December' => 31
      );
      $att_date='';
      $day_count=0;
      foreach($date_array as $key => $date_row){
         if($cmonth==$key){
            if ($year % 400 == 0)
               $day_count=29;
            else
               $day_count=$date_row;
         }
      }
      for($i=1;$i<=$day_count;$i++){
         $att_date.='<th class="text-center">'.$i.'</th>';
      }

      $ivar='
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th class="text-center">Sl No</th>
                  <th class="text-center">Teacher Name</th>
                  '.$att_date.'
                  <th class="text-center">Total</th>
               </tr>
            </thead>
            <tbody>
      ';
      $ivar_sqlquery=mysqli_query($this->stc_dbs, "
         SELECT DISTINCT
            `stc_school_teacher_userid`,
            `stc_school_teacher_firstname`,
            `stc_school_teacher_lastname`
         FROM
            `stc_school_teacher_attendance`
         LEFT JOIN
            `stc_school_teacher`
         ON
            `stc_school_teacher_userid`=`stc_school_teacher_attendance_createdby`
         WHERE
             MONTH(`stc_school_teacher_attendance_createdate`) = '".mysqli_real_escape_string($this->stc_dbs, $month)."'
         AND 
            YEAR(`stc_school_teacher_attendance_createdate`) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'
      ");
      if(mysqli_num_rows($ivar_sqlquery)>0){
         $slno=0;
         $total_time=0;
         foreach($ivar_sqlquery as $ivar_sqlrow){
            $attended_date='';
            $slno++;
            $fullname = $ivar_sqlrow['stc_school_teacher_firstname'].' '.$ivar_sqlrow['stc_school_teacher_lastname'];
            for($i=1;$i<=$day_count;$i++){
               $ivar_getattqry=mysqli_query($this->stc_dbs, "
                  SELECT
                      `stc_school_teacher_attendance_begtime`,
                      `stc_school_teacher_attendance_endtime`
                  FROM
                      `stc_school_teacher_attendance`
                  WHERE
                      DAY(`stc_school_teacher_attendance_createdate`) = '".$i."'
                  AND
                      MONTH(`stc_school_teacher_attendance_createdate`) = '".mysqli_real_escape_string($this->stc_dbs, $month)."'
                  AND
                      YEAR(`stc_school_teacher_attendance_createdate`) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'
                  AND
                      `stc_school_teacher_attendance_createdby` = '".$ivar_sqlrow['stc_school_teacher_userid']."'
               ");
               $day_time=0;
               foreach($ivar_getattqry as $ivar_getattrow){
                  $time1 = date('H:i:s', strtotime($ivar_getattrow['stc_school_teacher_attendance_begtime']));
                  $time2 = date('H:i:s', strtotime($ivar_getattrow['stc_school_teacher_attendance_endtime']));
                  $array1 = explode(':', $time1);
                  $array2 = explode(':', $time2);

                  $minutes1 = ($array1[0] * 60 + $array1[1]);
                  $minutes2 = ($array2[0] * 60 + $array2[1]);

                  $day_time += abs($minutes1 - $minutes2);
                  $total_time+=$day_time;
               }
               $attended_date.='<td class="text-center">'.round($day_time/60, 2).' <sup>hr</sup></td>';
            }
            $attended_date.='<td class="text-center">'.round($total_time/60, 2).' hrs</td>';
            $ivar.='
               <tr>
                  <td class="text-center">'.$slno.'</td>
                  <td>'.$fullname.'</td>
                  '.$attended_date.'
               </tr>
            ';
         }
      }else{
         $ivar.='
            <tr>
               <td class="text-center" colspan="20">No record found.</td>
            </tr>
         ';
      }

      $ivar.='
            </tbody>
         </table>
      ';
      return $ivar;
   }
}

class ragnarReportsViewMaterialRequisitionDetails extends tesseract{
   // call mrd location
   public function stc_mrd_location_call($customer_id){
      $odin='<option value="NA">Select</option>';
      $odin_get_locqry=mysqli_query($this->stc_dbs, "
         SELECT DISTINCT `stc_status_down_list_department_location` 
         FROM `stc_status_down_list_department` 
         INNER JOIN `stc_cust_project` 
         ON `stc_status_down_list_department_loc_id`=`stc_cust_project_id` 
         WHERE `stc_cust_project_cust_id`='".mysqli_real_escape_string($this->stc_dbs, $customer_id)."'
         AND `stc_status_down_list_department_location`<>''
         ORDER BY `stc_status_down_list_department_location` ASC
      ");
      if(mysqli_num_rows($odin_get_locqry)>0){
         foreach($odin_get_locqry as $odin_get_locrow){
            $odin.='
               <option>'.$odin_get_locrow['stc_status_down_list_department_location'].'</option>
            ';
         }
      }else{
         $odin.='
            <option value="NA">Location not found.</option>
         ';
      }
      return $odin;
   }

   // call mrd dept
   public function stc_mrd_dept_call($location){
      $odin='<option value="NA">Select</option>';
      $odin_get_deptqry=mysqli_query($this->stc_dbs, "
         SELECT DISTINCT `stc_status_down_list_department_loc_id`, `stc_status_down_list_department_dept` 
         FROM `stc_status_down_list_department` 
         WHERE `stc_status_down_list_department_location`='".mysqli_real_escape_string($this->stc_dbs, $location)."'
         AND `stc_status_down_list_department_dept`<>''
         ORDER BY `stc_status_down_list_department_dept` ASC
      ");
      if(mysqli_num_rows($odin_get_deptqry)>0){
         foreach($odin_get_deptqry as $odin_get_deptrow){
            $odin.='
               <option value="'.$odin_get_deptrow['stc_status_down_list_department_loc_id'].'">'.$odin_get_deptrow['stc_status_down_list_department_dept'].'</option>
            ';
         }
      }else{
         $odin.='
            <option value="NA">Department not found.</option>
         ';
      }
      return $odin;
   }

   // call mrd
   public function stc_mrd_find($from, $to, $tojob, $customer, $location, $dept, $pro_id, $tomaterial, $page, $limit){  
      $offset = ($page - 1) * $limit;     
      $odin='';
      $filter_query='';
      if(empty($from)){
         $filter_query.='';
      }
      if($tojob=="2"){
         $filter_query.=" AND R.stc_cust_super_requisition_list_sdlid<>0";
      }
      if($customer!="NA"){
         $filter_query.=" AND stc_cust_project_cust_id='".mysqli_real_escape_string($this->stc_dbs, $customer)."'";
      }
      $sdl_joiner='';
      if(($tojob=="1" || $tojob=="NA") && $dept!="NA" && $pro_id!="NA"){
         $filter_query.=" AND P.`stc_cust_project_id`='".mysqli_real_escape_string($this->stc_dbs, $pro_id)."'";
      }else if($tojob=="2" && $dept!="NA" && $pro_id!="NA"){
         if($location!="NA"){
            $sdl_joiner='            
               LEFT JOIN `stc_status_down_list`
               ON R.`stc_cust_super_requisition_list_sdlid`=`stc_status_down_list_id`
            ';
            $filter_query.=" AND stc_status_down_list_plocation='".mysqli_real_escape_string($this->stc_dbs, $location)."'";
         }
         $filter_query.=" AND stc_status_down_list_sub_location='".mysqli_real_escape_string($this->stc_dbs, $dept)."'";
      }
      if($tomaterial!="NA"){
         $filter_query.=" AND I.stc_cust_super_requisition_items_type='".mysqli_real_escape_string($this->stc_dbs, $tomaterial)."'";
      }
      $query= "
         SELECT 
            R.`stc_cust_super_requisition_list_id` as req_id,
            I.`stc_cust_super_requisition_list_id` as req_item_id,
            R.`stc_cust_super_requisition_list_date`,
            R.`stc_cust_super_requisition_list_sdlid`,
            I.`stc_cust_super_requisition_list_items_title`, 
            I.`stc_cust_super_requisition_list_items_unit`,
            I.`stc_cust_super_requisition_list_items_reqqty`,
            I.`stc_cust_super_requisition_list_items_approved_qty`,
            I.`stc_cust_super_requisition_items_finalqty`,
            I.`stc_cust_super_requisition_items_type`,
            I.`stc_cust_super_requisition_items_priority`,
            P.`stc_cust_project_cust_id`,
            P.`stc_cust_project_title`,
            S.`stc_cust_pro_supervisor_fullname`,
            S.`stc_cust_pro_supervisor_contact`  
         FROM `stc_cust_super_requisition_list_items` I
         LEFT JOIN `stc_cust_super_requisition_list` R
         ON R.`stc_cust_super_requisition_list_id`=I.`stc_cust_super_requisition_list_items_req_id`
         LEFT JOIN `stc_cust_project` P
         ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id`
         LEFT JOIN `stc_cust_pro_supervisor` S
         ON S.`stc_cust_pro_supervisor_id`=R.`stc_cust_super_requisition_list_super_id`
         ".$sdl_joiner."
         WHERE DATE(`stc_cust_super_requisition_list_date`) BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $from)."'
         AND '".mysqli_real_escape_string($this->stc_dbs, $to)."' ".$filter_query."
         ORDER BY DATE(R.`stc_cust_super_requisition_list_date`) DESC, R.`stc_cust_super_requisition_list_sdlid` DESC         
      ";

      $total_result = mysqli_query($this->stc_dbs, $query);
      $total_record = mysqli_num_rows($total_result);

      $query.="LIMIT $limit OFFSET $offset";
      $odin_get_mrdqry=mysqli_query($this->stc_dbs, $query);
      if(mysqli_num_rows($odin_get_mrdqry)>0){
         $prev_pno='';
         $prev_check=0;
         foreach($odin_get_mrdqry as $odin_get_mrdrow){
            $pno='';
            if($odin_get_mrdrow['stc_cust_super_requisition_list_sdlid']>0){
               $pno=$odin_get_mrdrow['stc_cust_super_requisition_list_sdlid'];
               // $prev_pno=$odin_get_mrdrow['stc_cust_super_requisition_list_sdlid']!=$prev_pno ? $odin_get_mrdrow['stc_cust_super_requisition_list_sdlid'] : $prev_pno;
               if($prev_check==$pno && $pno!=0){
                  $prev_pno++;
               }else{
                  $prev_check=$pno;
                  $prev_pno=10;
               }
            }
            $prev_pno=$prev_pno==0 ? "" : $prev_pno;
            $stcdispatchedqty=0;
            $stcdecqtyqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_cust_super_requisition_list_items_rec_recqty`
					FROM `stc_cust_super_requisition_list_items_rec` 
					WHERE 
						(
                     `stc_cust_super_requisition_list_items_rec_list_id`='".$odin_get_mrdrow['req_id']."' 
					   AND `stc_cust_super_requisition_list_items_rec_list_item_id`='".$odin_get_mrdrow['req_item_id']."'  
               )
            ");
				foreach($stcdecqtyqry as $dispatchedrow){
					$stcdispatchedqty+=$dispatchedrow['stc_cust_super_requisition_list_items_rec_recqty'];
				}

            $stcrecqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_cust_super_requisition_rec_items_fr_supervisor_date`,
						`stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty`
					FROM `stc_cust_super_requisition_rec_items_fr_supervisor` 
					WHERE `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`='".$odin_get_mrdrow['req_item_id']."'  
				");
            
            $stcrecieveddate='';
            $stcrecievedqty=0;
            if(mysqli_num_rows($stcrecqry)>0){
               foreach($stcrecqry as $recievedrow){
                  $stcrecieveddate=date('d-m-Y', strtotime($recievedrow['stc_cust_super_requisition_rec_items_fr_supervisor_date']));
                  $stcrecievedqty+=$recievedrow['stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty'];
               }
            }

            $stcconsumedqty=0;
				$stcconsrecqtyqry=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_cust_super_list_items_consumption_items_qty`) AS consumable_qty
					FROM `stc_cust_super_list_items_consumption_items` 
					WHERE `stc_cust_super_list_items_consumption_items_name`='".mysqli_real_escape_string($this->stc_dbs, $odin_get_mrdrow['req_item_id'])."'  
				");
				foreach($stcconsrecqtyqry as $consumedrow){
					$stcconsumedqty+=$consumedrow['consumable_qty']==null ? 0 : $consumedrow['consumable_qty'];
				}
            $stcpendingqty=$odin_get_mrdrow['stc_cust_super_requisition_items_finalqty'] - $stcdispatchedqty;
            $stockqty=$stcrecievedqty - $stcconsumedqty;
            $bgcolor=$odin_get_mrdrow['stc_cust_super_requisition_items_priority']==2 ? 'style="background:#ffa5a5;color:black"' : "";
            $materialpriority=$odin_get_mrdrow['stc_cust_super_requisition_items_priority']==2 ? "Urgent" : "Normal";            

            $odin.='
               <tr data-toggle="modal" data-target=".stc-mrdmodal-res" class="showmrd-details" reqnumber="'.$odin_get_mrdrow['req_id'].'" reqdate="'.date('d-m-Y', strtotime($odin_get_mrdrow['stc_cust_super_requisition_list_date'])).'" reqraisedby="'.$odin_get_mrdrow['stc_cust_pro_supervisor_fullname'].' - '.$odin_get_mrdrow['stc_cust_pro_supervisor_contact'].'" reqraisedfrom="'.$odin_get_mrdrow['stc_cust_project_title'].'" itemdesc="'.$odin_get_mrdrow['stc_cust_super_requisition_list_items_title'].'" itemqty="'.number_format($odin_get_mrdrow['stc_cust_super_requisition_list_items_reqqty'], 2).'" itemunit="'.$odin_get_mrdrow['stc_cust_super_requisition_list_items_unit'].'" itempriority="'.$materialpriority.'" >
                  <td class="text-center">'.$pno.'</td>
                  <td class="text-center">'.$odin_get_mrdrow['req_id'].'</td>
                  <td class="text-center">'.$prev_pno.'</td>
                  <td class="text-center">'.date('d-m-Y', strtotime($odin_get_mrdrow['stc_cust_super_requisition_list_date'])).'</td>
                  <td>'.$odin_get_mrdrow['stc_cust_super_requisition_list_items_title'].'</td>
                  <td class="text-center">'.$odin_get_mrdrow['stc_cust_super_requisition_list_items_unit'].'</td>
                  <td class="text-right">'.number_format($odin_get_mrdrow['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
                  <td class="text-right">'.number_format($odin_get_mrdrow['stc_cust_super_requisition_list_items_approved_qty'], 2).'</td>
                  <td class="text-right">'.number_format($odin_get_mrdrow['stc_cust_super_requisition_items_finalqty'], 2).'</td>
                  <td class="text-right">'.number_format($stcdispatchedqty, 2).'</td>
                  <td class="text-center">'.$stcrecieveddate.'</td>
                  <td class="text-right">'.number_format($stcpendingqty, 2).'</td>
                  <td class="text-right">'.number_format($stcconsumedqty, 2).'</td>
                  <td class="text-right">'.number_format($stockqty, 2).'</td>
                  <td class="text-center" '.$bgcolor.'>'.$materialpriority.'</td>
               </tr>
            ';
         }
      } else {
         $odin .= '<tr><td colspan="15">No record found.</td></tr>';
     }
 
     // Add pagination controls
     $pages = ceil($total_record / $limit);
     $pagination = '<tr><td colspan="15"><div class="pagination">';
     for ($i = 1; $i <= $pages; $i++) {
         $active = ($i == $page) ? 'active' : '';
         $activestyle = ($i == $page) ? "style='background-color: #afaeff; font-weight: bold;padding: 5px;'" : "style='font-weight: bold;padding: 5px;'";
         $pagination .= "<a href='javascript:void(0)' class='stc-mrd-page $active' $activestyle data-page='$i'>$i</a>";
     }
     $pagination .= '</div></td></tr>';
 
     return $odin . $pagination;
   }
}

class ragnarReportsViewEPermitAttReports extends tesseract{
   // call attendance
   public function stc_epermit_call_attendance($stc_epermit_month){
      $cmonth=date('F', strtotime($stc_epermit_month));
      $month=date('m', strtotime($stc_epermit_month));
      $year=date('Y', strtotime($stc_epermit_month));
      $date_array=array(
         'January' => 31,
         'February' => 28,
         'March' => 31,
         'April' => 30,
         'May' => 31,
         'June' => 30,
         'July' => 31,
         'August' => 31,
         'September' => 30,
         'October' => 31,
         'November' => 30,
         'December' => 31
      );
      $att_date='';
      $day_count=0;
      foreach($date_array as $key => $date_row){
         if($cmonth==$key){
            if ($year % 400 == 0)
               $day_count=29;
            else
               $day_count=$date_row;
         }
      }

      $ivar='';
      $ivar_sqlquery=mysqli_query($this->stc_dbs, "
         SELECT DATE(created_date) as date, COUNT(emp_name) as total_employees
         FROM stc_epermit_enrollment
         WHERE MONTH(created_date) = '".mysqli_real_escape_string($this->stc_dbs, $month)."' AND YEAR(created_date) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'
         GROUP BY DATE(created_date)
         ORDER BY DATE(created_date) DESC;
      ");
      if(mysqli_num_rows($ivar_sqlquery)>0){
         $slno=0;
         $total_attendance=0;
         foreach($ivar_sqlquery as $ivar_sqlrow){
            $slno++;
            $totalempermit=0;
            $remarks = '';
            $ivar_getattqry=mysqli_query($this->stc_dbs, "
               SELECT SUM(`totalpermitenr`) as totalpermitenrs, GROUP_CONCAT(`remarks` SEPARATOR ', ') AS remarks FROM `stc_totalpermitenrollment` WHERE DATE(`created_date`)='".$ivar_sqlrow['date']."'
            ");
            foreach($ivar_getattqry as $ivar_getattrow){
               $totalempermit = $ivar_getattrow['totalpermitenrs'];
               $remarks = $ivar_getattrow['remarks'];
            }
            $totalepermit = $ivar_sqlrow['total_employees'] - $totalempermit;

            $totalepermitStyle = $totalepermit < 0 ? 'style="color: red;"' : '';
            $displayTotalepermit = abs($totalepermit);


            $ivar .= '
               <tr>
                  <td class="text-center">' . $slno . '</td>
                  <td>' . date('d-m-Y', strtotime($ivar_sqlrow['date'])) . '</td>
                  <td class="text-center">' . round($ivar_sqlrow['total_employees'], 2) . '</td>
                  <td class="text-center">' . round($totalempermit, 2) . '</td>
                  <td class="text-center" ' . $totalepermitStyle . '>' . round($displayTotalepermit, 2) . '</td>
                  <td class="text-center" style=" width: 25%; scroll-behavior: unset;">' . $remarks . '</td>
                  <td class="text-center"><a href="javascript:void(0)" class="btn btn-primary stc-epermit-att-show" date="'.date('Y-m-d', strtotime($ivar_sqlrow['date'])).'" data-toggle="modal" data-target=".bd-epermitfilter-modal-lg">View</a></td>
               </tr>
            ';

         }
      }else{
         $ivar.='
            <tr>
               <td class="text-center" colspan="3">No record found.</td>
            </tr>
         ';
      }
      return $ivar;
   }

   // call attendance details
   public function stc_epermit_call_attendance_details($stc_epermit_month){
      $ivar='
         <thead>
            <tr>
               <th colspan="15" class="text-center">E-Permit Enrolment Record</th>
            </tr>
            <tr>
      ';

      $showdate=date('d-m-Y');
      $shiftAtotal='';
      $shiftBtotal='';
      $shiftCtotal='';
      $shiftEtotal='';
      $shiftAdata='';
      $shiftBdata='';
      $shiftCdata='';
      $shiftEdata='';
      $totalplantentry=0;
      $shifttdata='';
      $filter=' WHERE DATE(`created_date`)="'.$stc_epermit_month.'" AND `dep_id`<>0';
      $filter2=' AND DATE(`created_date`)="'.$stc_epermit_month.'"';
      $ivar.='<th class="text-center">Date</th>';
      $ivar.='<th class="text-center">'.date('d-m-Y', strtotime($stc_epermit_month)).'</th>';
      $sql=mysqli_query($this->stc_dbs, "SELECT `dep_id`, `shift`, `stc_status_down_list_department_dept` FROM `stc_epermit_enrollment` LEFT JOIN  `stc_status_down_list_department` ON `dep_id`=`stc_status_down_list_department_id` WHERE DATE(`created_date`)='".$stc_epermit_month."' AND `dep_id`<>0 ORDER BY `stc_status_down_list_department_dept` ASC");
      $TotalShiftAcounter=0;
      $TotalShiftBcounter=0;
      $TotalShiftCcounter=0;
      $TotalShiftEcounter=0;
      $Totalepermitcounter=0;
      $deptepermitcounter='';
      $departments = array();
      $departmentsid = array();
      $Remarks='';
      $deptnonenrollmentcounter='';
      if(mysqli_num_rows($sql)>0){
         foreach($sql as $row){
            if (!in_array($row['stc_status_down_list_department_dept'], $departments)) {
               $departments[] = $row['stc_status_down_list_department_dept'];
               $departmentsid[] = $row['dep_id'];
            }
         }
         foreach($departments as $key=>$department) {
            $RemarksData='';
            $ShiftAcounter=0;
            $ShiftBcounter=0;
            $ShiftCcounter=0;
            $ShiftEcounter=0;
            $ivar.= '<th rowspan="2">' . $department . '</th>';
            $dept_id=$departmentsid[$key];
            $sql2=mysqli_query($this->stc_dbs, "SELECT `id`, `shift`, `emp_name`, `created_date` FROM `stc_epermit_enrollment` WHERE `dep_id`='".$dept_id."' AND DATE(`created_date`)='".$stc_epermit_month."'");
            foreach($sql2 as $row2){
               if($row2['shift']=="A"){ $totalplantentry++;$ShiftAcounter++; }
               if($row2['shift']=="B"){ $totalplantentry++;$ShiftBcounter++; }
               if($row2['shift']=="C"){ $totalplantentry++;$ShiftCcounter++; }
               if($row2['shift']=="E (General)"){ $totalplantentry++;$ShiftEcounter++; }
            }
            $sql3=mysqli_query($this->stc_dbs, "SELECT `id`, `totalpermitenr`, `dep_id`, `remarks` FROM `stc_totalpermitenrollment` WHERE `dep_id`='".$dept_id."' ".$filter2."");
            $Epermitcounter=0;
            $rema='';
            if(mysqli_num_rows($sql3)>0){
               foreach($sql3 as $row3){
                     $Epermitcounter+=$row3['totalpermitenr'];
                     $Totalepermitcounter+=$row3['totalpermitenr'];
                     $rema=$row3['remarks'];
               }
               if($Epermitcounter==0){
                     $deptepermitcounter.='<td style="font-weight:bold">0</td>';
               }else{
                     $deptepermitcounter.='<td style="font-weight:bold">'.$Epermitcounter.'</td>';
                     $RemarksData.=$rema;
               }
            }else{
               $deptepermitcounter.='<td style="font-weight:bold">0</td>';
            }
            $Remarks.='<td>'.$RemarksData.'</td>';
            $TotalShiftAcounter+=$ShiftAcounter;
            $TotalShiftBcounter+=$ShiftBcounter;
            $TotalShiftCcounter+=$ShiftCcounter;
            $TotalShiftEcounter+=$ShiftEcounter;

            $shiftAdata.='<td>'.$ShiftAcounter.'</td>';
            $shiftBdata.='<td>'.$ShiftBcounter.'</td>';
            $shiftCdata.='<td>'.$ShiftCcounter.'</td>';
            $shiftEdata.='<td>'.$ShiftEcounter.'</td>';
            $total = $ShiftAcounter + $ShiftBcounter + $ShiftCcounter + $ShiftEcounter;
            $shifttdata.='<td style="font-weight:bold">'.$total.'</td>';
            $totalnonenrollment=$total - $Epermitcounter;
            $deptnonenrollmentcounter.='<td style="font-weight:bold">'.$totalnonenrollment.'</td>';
         }
         $shiftAtotal='<td>'.$TotalShiftAcounter.'</td>';
         $shiftBtotal='<td>'.$TotalShiftBcounter.'</td>';
         $shiftCtotal='<td>'.$TotalShiftCcounter.'</td>';
         $shiftEtotal='<td>'.$TotalShiftEcounter.'</td>';
      }
      $totalnonenrollment=$totalplantentry-$Totalepermitcounter;
      $ivar.='
               </tr>
               <tr>
                  <th class="text-center">Shift</th>
                  <th class="text-center">Manpower Entry in TSL</th>
               </tr>
         </thead>
         <tbody>
                  <tr>
                     <td>1st (A)</td>
                     '.$shiftAtotal.'
                     '.$shiftAdata.'
                  </tr>
                  <tr>
                     <td>General (E)</td>
                     '.$shiftEtotal.'
                     '.$shiftEdata.'
                  </tr>
                  <tr>
                     <td>2nd (B)</td>
                     '.$shiftBtotal.'
                     '.$shiftBdata.'
                  </tr>
                  <tr>
                     <td>3rd (C)</td>
                     '.$shiftCtotal.'
                     '.$shiftCdata.'
                  </tr>
                  <tr>
                     <td style="font-weight:bold">Total Entry In Plant</td>
                     <td style="font-weight:bold">'.$totalplantentry.'</td>
                     '.$shifttdata.'
                  </tr>
                  <tr>
                     <td style="font-weight:bold">Total E-Permit Enrolment</td>
                     <td style="font-weight:bold">'.$Totalepermitcounter.'</td>
                     '.$deptepermitcounter.'
                  </tr>
                  <tr>
                     <td style="font-weight:bold">Total Non Enrolment</td>
                     <td style="font-weight:bold">'.$totalnonenrollment.'</td>
                     '.$deptnonenrollmentcounter.'
                  </tr>
                  <tr>
                     <td style="font-weight:bold">Remarks</td>
                     <td></td>
                     '.$Remarks.'
                  </tr>
         </tbody>
      ';
      return $ivar;
   }

   public function stc_save_monthlytarget($school, $label, $target, $month){
      $school = mysqli_real_escape_string($this->stc_dbs, $school);
      $label = mysqli_real_escape_string($this->stc_dbs, $label);
      $target = mysqli_real_escape_string($this->stc_dbs, $target);
      $month = mysqli_real_escape_string($this->stc_dbs, $month);
      $query = "INSERT INTO `stc_school_feetarget` (`school`, `label`, `target_amount`, `month`, `created_by`) VALUES ('$school', '$label', '$target', '$month', '".$_SESSION['stc_empl_id']."')";
      if(mysqli_query($this->stc_dbs, $query)){
         return 'success';
      } else {
         return 'Error saving target: ' . mysqli_error($this->stc_dbs);
      }
   }
}
#<--------------------------------------------------------------------------------------------------------->
#<--------------------------------------Object sections of reports class----------------------------------->
#<--------------------------------------------------------------------------------------------------------->
// merchant ledger merchant all
if(isset($_POST['Stc_merchant_reporsts_call_merchant'])){
   $bjornecustomer=new ragnarReportsViewMerchantLedger();
   $outbjornecustomer=$bjornecustomer->stc_call_merchants();
   echo $outbjornecustomer;
}

if(isset($_POST['Stc_call_reports_on_merchants'])){   
   unset($_SESSION["stc_merchant_invoice_sort"]);
   $bjornebegdate=date('Y-m-d',strtotime($_POST['begdate']));
   $bjorneenddate=date('Y-m-d',strtotime($_POST['enddate']));
   $bjornemerchantid=$_POST['merchantid'];
   $bjorneledger=new ragnarReportsViewMerchantLedger();
   // $out='';
   $outbjorneledger=$bjorneledger->stc_call_ledger($bjornebegdate, $bjorneenddate, $bjornemerchantid);
   echo $outbjorneledger;
}

#<-----------------------------Object sections of requisition reports class-------------------------------->
// call location
if(isset($_POST['Stc_std_department'])){
   $location      =  $_POST['location'];
   $bjornedepartment=new ragnarReportsViewRequiReports();  
   $outbjornedepartment=$bjornedepartment->stc_call_departments($location);
   echo $outbjornedepartment;
}

// call std details
if(isset($_POST['Stc_std_details'])){
   $status=$_POST['status'];
   $datefrom      =  $_POST['datefrom'];
   $dateto        =  $_POST['dateto'];
   $location      =  $_POST['location'];
   $department    =  $_POST['department'];
   $typeofjob     =  $_POST['typeofjob'];
   $status        =  $_POST['status'];
   $pagination    =  $_POST['pagination'];
   $filter        =  $_POST['filter'];
   $type        =  $_POST['type'];
   $bjornecustomer=new ragnarReportsViewRequiReports();   
   $outbjornecustomer=$bjornecustomer->stc_call_std($datefrom, $dateto, $location, $department, $typeofjob, $status, $pagination, $filter, $type);
   echo $outbjornecustomer;
}

// update sdl
if(isset($_POST['stc_sdl_update'])){
   $sdl_id=$_POST['sdl_id'];
   $target_date=$_POST['target_date'];
   $remarks=$_POST['remarks'];
   $bjornecustomer=new ragnarReportsViewRequiReports();   
   $outbjornecustomer=$bjornecustomer->stc_call_std_update($sdl_id, $target_date, $remarks);
   echo $outbjornecustomer;
}

// call material  sdl
if(isset($_POST['stc_sdl_material_call'])){
   $sdl_id=$_POST['sdl_id'];
   $bjornecustomer=new ragnarReportsViewRequiReports();   
   $outbjornecustomer=$bjornecustomer->stc_call_std_material($sdl_id);
   echo json_encode($outbjornecustomer);
}

// call customer
if(isset($_POST['Stc_customer_reporsts_call_customer'])){
   $bjornecustomer=new ragnarReportsViewRequiReports();
   $outbjornecustomer=$bjornecustomer->stc_call_customer();
   echo $outbjornecustomer;
}

// call agent
if(isset($_POST['Stc_on_change_customer_agent_call'])){
   $custid=$_POST['custid'];
   $bjorneagent=new ragnarReportsViewRequiReports();
   $outbjorneagent=$bjorneagent->stc_call_agent_on_customer($custid);
   echo $outbjorneagent;
}

// call project
if(isset($_POST['Stc_on_change_agent_project_call'])){
   $agentid=$_POST['agentid'];
   $custid=$_POST['custid'];
   $bjorneproject=new ragnarReportsViewRequiReports();
   $outbjorneproject=$bjorneproject->stc_call_project_on_agent($agentid, $custid);
   echo $outbjorneproject;
}

// get supervisors orders requisitions
if(isset($_POST['stc_requisition_reports_req'])){
   $stc_begdate=date("Y-m-d", strtotime($_POST['stc_begdate']));
   $stc_enddate=date("Y-m-d", strtotime($_POST['stc_enddate']));
   $stc_custid=$_POST['stc_custid'];
   $stc_agentid=$_POST['stc_agentid'];
   $stc_projeid=$_POST['stc_projeid'];
   @$stc_count_id=$_POST['stc_count_id'];
   $start_date = strtotime($stc_begdate);
   $end_date = strtotime($stc_enddate);
   if($stc_count_id==0){
      $stc_count_id=0;
   }
   $out='';
      $objloki=new ragnarReportsViewRequiReports();
      if(empty($stc_begdate) || empty($stc_enddate) || $stc_custid=='NA' || $stc_agentid=='NA' || $stc_projeid=='NA'){
         $out='
            <tr>
               <td colspan="10">Dont late any fields empty</td>
            </tr>
         ';
      }else{
         $opobjloki=$objloki->stc_get_supervisors_requisition_records($stc_begdate, $stc_enddate, $stc_custid, $stc_agentid, $stc_projeid, $stc_count_id);
         $out.=$opobjloki;
      }
   echo $out;
}

// get supervisors orders requisitions
if(isset($_POST['stc_pending_reports_req'])){
   $stc_begdate=date("Y-m-d", strtotime($_POST['stc_begdate']));
   $stc_enddate=date("Y-m-d", strtotime($_POST['stc_enddate']));
   $stc_custid=$_POST['stc_custid'];
   $stc_agentid=$_POST['stc_agentid'];
   $stc_projeid=$_POST['stc_projeid'];
   $start_date = strtotime($stc_begdate);
   $end_date = strtotime($stc_enddate);
   $out='';
      $objloki=new ragnarReportsViewRequiReports();
      if((empty($stc_begdate) && empty($stc_enddate)) || $stc_custid=='NA' || $stc_agentid=='NA' || $stc_projeid=="Please Select Agent First" || $stc_projeid=='NA'){
         $out='
            <tr>
               <td colspan="10">Dont late any fields empty</td>
            </tr>
         ';
      }else{
         // Calculate the difference between start and end dates
         $date_diff = (strtotime($stc_enddate) - strtotime($stc_begdate)) / (60 * 60 * 24);
         
         // Allow same date or up to 30 days difference for pending reports
         if($date_diff < 0 || $date_diff > 30){
         $out='
            <tr>
               <td colspan="10">Date range should be within one month (30 days) for pending list.</td>
            </tr>
         ';
         }else{
            $opobjloki=$objloki->stc_get_supervisors_pending_records($stc_begdate, $stc_enddate, $stc_custid, $stc_agentid, $stc_projeid);
            $out.=$opobjloki;
         }
      }
   echo $out;
}

#<-------------------------------Object sections of projects reports class-------------------------------->
// get supervisors orders requisitions
if(isset($_POST['stc_proj_reports_req'])){
   $stc_custid=$_POST['stc_custid'];
   $stc_agentid=$_POST['stc_agentid'];
   $out='';
   $objloki=new ragnarReportsViewProjReports();
   if($stc_custid=='NA' || $stc_agentid=='NA'){
      $out='
         <tr>
            <td colspan="10">Dont late any fields empty</td>
         </tr>
      ';
   }else{
      $opobjloki=$objloki->stc_get_project_details($stc_custid, $stc_agentid);
      $out=$opobjloki;
   }
   echo $out;
}

// retrieve project details
if(isset($_POST['stc_ag_rproject_retrive'])){
   $project_id=$_POST['project_id'];
   $objcrproj=new ragnarReportsViewProjReports();
   $opobjcrproj=$objcrproj->stc_call_project_details($project_id);
   echo json_encode($opobjcrproj);
   // echo $opobjcrproj;
}

#<----------------------------------Object sections of sandp reports class-------------------------------->
// call sale purchase electronics
if(isset($_POST['stc_find_purchase_sale_reports'])){
   $out='';
   $bjornebegdate=date('Y-m-d',strtotime($_POST['beg_date']));
   $bjorneenddate=date('Y-m-d',strtotime($_POST['end_date']));
   $cat_id=$_POST['cat_id'];

   $bjornepurchase=new ragnarReportsViewElectronicsPurchaseSaleReports();
   $bjornesale=new ragnarReportsViewElectronicsPurchaseSaleReports();

   if($cat_id==1){
      $out=$bjornepurchase->stc_electronics_call_purchase($bjornebegdate, $bjorneenddate);
   }else{
      $out=$bjornesale->stc_electronics_call_sale($bjornebegdate, $bjorneenddate);
   }
   echo $out;
}

#<----------------------------------Object sections of sandp reports class-------------------------------->
// call sale purchase trading
if(isset($_POST['stc_find_trading_purchase_sale_reports'])){
   $out='';
   $bjornebegdate=date('Y-m-d',strtotime($_POST['beg_date']));
   $bjorneenddate=date('Y-m-d',strtotime($_POST['end_date']));
   $cat_id=$_POST['cat_id'];

   $bjornepurchase=new ragnarReportsViewTradingPurchaseSaleReports();
   $bjornesale=new ragnarReportsViewTradingPurchaseSaleReports();

   if($cat_id==1){
      $out=$bjornepurchase->stc_trading_call_purchase($bjornebegdate, $bjorneenddate);
   }else{
      $out=$bjornesale->stc_trading_call_sale($bjornebegdate, $bjorneenddate);
   }
   echo $out;
}

// call sale purchase trading
if(isset($_POST['stc_find_gld_purchase_sale_reports'])){
   $out='';
   $bjornebegdate=date('Y-m-d',strtotime($_POST['beg_date']));
   $bjorneenddate=date('Y-m-d',strtotime($_POST['end_date']));
   $stcgldsearch=$_POST['stcgldsearch'];
   $branch=$_POST['branch'];

   $bjornesale=new ragnarReportsViewTradingPurchaseSaleReports();
   $out=$bjornesale->stc_gld_call_sale($bjornebegdate, $bjorneenddate, $branch);
   echo $out;
}
#<----------------------------------Object sections of sandp reports class-------------------------------->
// call sale purchase groceries
if(isset($_POST['stc_find_groceries_purchase_sale_reports'])){
   $out='';
   $bjornebegdate=date('Y-m-d',strtotime($_POST['beg_date']));
   $bjorneenddate=date('Y-m-d',strtotime($_POST['end_date']));
   $cat_id=$_POST['cat_id'];

   $bjornepurchase=new ragnarReportsViewGroceriesPurchaseSaleReports();
   $bjornesale=new ragnarReportsViewGroceriesPurchaseSaleReports();

   if($cat_id==1){
      $out=$bjornepurchase->stc_groceries_call_purchase($bjornebegdate, $bjorneenddate);
   }else{
      $out=$bjornesale->stc_groceries_call_sale($bjornebegdate, $bjorneenddate);
   }
   echo $out;
}

#<------------------------Object sections of school canteen reports class--------------------------------->
// call school canteen
if(isset($_POST['stc_find_school_canteen_reports'])){
   $out='';
   $bjornebegdate=date('Y-m-d',strtotime($_POST['beg_date']));
   $bjorneenddate=date('Y-m-d',strtotime($_POST['end_date']));

   $bjorneschool=new ragnarReportsViewSchoolCanteenReports();

   $out=$bjorneschool->stc_school_call_canteen($bjornebegdate, $bjorneenddate);
   echo $out;
}

// call by date
if(isset($_POST['stc_call_by_date'])){ 
   $search=$_POST['req_date'];
   $valkyrie=new ragnarReportsViewSchoolCanteenReports();
   $lokiheck=$valkyrie->stc_search_by_date($search);
   echo $lokiheck;
}

#<----------------------------Object sections of school fee reports class--------------------------------->
// call school fee
if(isset($_POST['stc_find_school_fee_reports'])){
   $out='';
   $bjornebegdate=date('Y-m-d',strtotime($_POST['beg_date']));
   $bjorneenddate=date('Y-m-d',strtotime($_POST['end_date']));
   $bjorneschool=@$_POST['school_name'];

   $bjorneschoolfee=new ragnarReportsViewSchoolFeeReports();

   $out=$bjorneschoolfee->stc_school_call_fee($bjornebegdate, $bjorneenddate, $bjorneschool);
   echo json_encode($out);
}

// call school fee
if(isset($_POST['stc_find_school_attendance'])){
   $out='';
   $stc_school_month=$_POST['stc_school_month'];

   $bjorneschoolfee=new ragnarReportsViewSchoolFeeReports();

   $out=$bjorneschoolfee->stc_school_call_attendance($stc_school_month);
   echo $out;
}

#<----------------------------Object sections of mrd reports class--------------------------------->
// call mrd location
if(isset($_POST['stc_mrd_call_location'])){
   $out='';
   $customer_id=$_POST['customer_id'];

   $bjorneschoolfee=new ragnarReportsViewMaterialRequisitionDetails();

   $out=$bjorneschoolfee->stc_mrd_location_call($customer_id);
   echo $out;
}

// call mrd dept
if(isset($_POST['stc_mrd_call_dept'])){
   $out='';
   $location=$_POST['location'];

   $bjorneschoolfee=new ragnarReportsViewMaterialRequisitionDetails();

   $out=$bjorneschoolfee->stc_mrd_dept_call($location);
   echo $out;
}

if (isset($_POST['stc_mrd_call_mrd'])) {
   $out = '';
   $from = date('Y-m-d', strtotime($_POST['from']));
   $to = date('Y-m-d', strtotime($_POST['to']));
   $tojob = $_POST['tojob'];
   $customer = $_POST['customer'];
   $location = $_POST['location'];
   $dept = $_POST['dept'];
   $pro_id = $_POST['pro_id'];
   $tomaterial = $_POST['tomaterial'];
   $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
   $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 10;

   $bjorneschoolfee = new ragnarReportsViewMaterialRequisitionDetails();
   $out = $bjorneschoolfee->stc_mrd_find($from, $to, $tojob, $customer, $location, $dept, $pro_id, $tomaterial, $page, $limit);
   echo json_encode($out);
}

#<----------------------------Object sections of epermit reports class--------------------------------->
// call school fee
if(isset($_POST['stc_find_epermit_attendance'])){
   $out='';
   $stc_epermit_month=$_POST['stc_epermit_month'];

   $bjorneschoolfee=new ragnarReportsViewEPermitAttReports();

   $out=$bjorneschoolfee->stc_epermit_call_attendance($stc_epermit_month);
   echo $out;
}
// call school fee
if(isset($_POST['stc_find_epermit_attendance_details'])){
   $out='';
   $stc_epermit_month=$_POST['stc_epermit_month'];

   $bjorneschoolfee=new ragnarReportsViewEPermitAttReports();

   $out=$bjorneschoolfee->stc_epermit_call_attendance_details($stc_epermit_month);
   echo $out;
}

// call school fee
if(isset($_POST['stc_school_fee_add_monthly_target'])){
   $out='';

   $school=$_POST['stc_school'];
   $label=$_POST['stc_label'];
   $target=$_POST['stc_target'];
   $month=$_POST['stc_month'];

   $bjorneschoolfee=new ragnarReportsViewEPermitAttReports();

   $out=$bjorneschoolfee->stc_save_monthlytarget($school, $label, $target, $month);
   echo $out;
}
?>