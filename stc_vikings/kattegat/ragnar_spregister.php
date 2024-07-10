<?php
include "../../MCU/obdb.php";
session_start();
class ragnarPurchaseRegisterView extends tesseract{
	// call vendor on po page
	public function stc_call_vendor(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_merchant` GROUP BY `stc_merchant_name` ASC");
		$odin='<option value="NA" selected>Select Merchant</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Merchant Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
					<option value="'.$row["stc_merchant_id"].'">'.$row["stc_merchant_name"].'</option>		               	
				';
			}
			
		}
		return $odin;
	}

	// filter purchase record from date
	public function stc_filter_pur_reg($purbegdate, $purenddate){
		$odin='';
		$basic=0;
		$gst=0;
		$igst=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_product_grn_items_qty`, 
				`stc_product_grn_items_rate`, 
				`stc_merchant_state_id`,
				`stc_product_gst` 
			FROM `stc_product_grn_items` 
			INNER JOIN `stc_product_grn` 
			ON `stc_product_grn_items_grn_order_id`=`stc_product_grn_id`
			INNER JOIN `stc_merchant` 
			ON `stc_product_grn_merchant_id`=`stc_merchant_id`
			INNER JOIN `stc_product` 
			ON `stc_product_grn_items_product_id`=`stc_product_id`
			WHERE `stc_product_grn_invoice_date`
			BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $purbegdate)."'
			AND '".mysqli_real_escape_string($this->stc_dbs, $purenddate)."'
			ORDER BY `stc_product_grn`.`stc_product_grn_id` DESC 
		");

		foreach($check_loki as $row){
			$bastempval=0;
			$gsttempval=0;
			$bastempval=round($row['stc_product_grn_items_qty'], 2) * round($row['stc_product_grn_items_rate'], 2);
			$basic+=$bastempval;
			if($row['stc_merchant_state_id']==16){
				$gsttempval = ($bastempval * $row["stc_product_gst"])/100;
				$gst+=$gsttempval;
			}else{
				$gsttempval = ($bastempval * $row["stc_product_gst"])/100;
				$igst+=$gsttempval;
			}
		}

		$check_loki_again=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_dc_items_product_qty`, 
				`stc_sale_product_dc_items_product_rate`, 
				`stc_merchant_state_id`,
				`stc_product_gst` 
			FROM `stc_sale_product_dc_items` 
			INNER JOIN `stc_sale_product` 
			ON `stc_sale_product_dc_items_sale_product_id`=`stc_sale_product_id`
			INNER JOIN `stc_merchant` 
			ON`stc_sale_product_dc_merchant`=`stc_merchant_id`
			INNER JOIN `stc_product` 
			ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
			WHERE (`stc_sale_product_dc_invo_date`
                BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $purbegdate)."'
                AND '".mysqli_real_escape_string($this->stc_dbs, $purenddate)."')
               AND `stc_sale_product_dc_keys`='directchallaned'
			ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC 
		");

		foreach($check_loki_again as $row){
			$bastempval=0;
			$gsttempval=0;
			$bastempval=round($row['stc_sale_product_dc_items_product_qty'], 2) * round($row['stc_sale_product_dc_items_product_rate'], 2);
			$basic+=$bastempval;
			if($row['stc_merchant_state_id']==16){
				$gsttempval = ($bastempval * $row["stc_product_gst"])/100;
				$gst+=$gsttempval;
			}else{
				$gsttempval = ($bastempval * $row["stc_product_gst"])/100;
				$igst+=$gsttempval;
			}
		}

		$check_loki_again_again=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_product_grn_freight_charges`, 
				`stc_product_grn_packing_forwarding`, 
				`stc_product_grn_others_charges`, 
				`stc_product_grn_merchant_id`,
				`stc_merchant_state_id`
			FROM `stc_product_grn` 
			INNER JOIN `stc_merchant` 
			ON `stc_product_grn_merchant_id`=`stc_merchant_id`
			WHERE `stc_product_grn_invoice_date`
			BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $purbegdate)."'
			AND '".mysqli_real_escape_string($this->stc_dbs, $purenddate)."'
			ORDER BY `stc_product_grn`.`stc_product_grn_id` DESC 
		");

		$tfreight=0;
		$tpandf=0;
		$tothers=0;
		$tfreightgst=0;
		$tpandfgst=0;
		$tothersgst=0;
		$tfreightigst=0;
		$tpandfigst=0;
		$tothersigst=0;
		foreach($check_loki_again_again as $chargesrow){
			$tfreight+=$chargesrow['stc_product_grn_freight_charges'];
			$tpandf+=$chargesrow['stc_product_grn_packing_forwarding'];
			$tothers+=$chargesrow['stc_product_grn_others_charges'];

			if($chargesrow['stc_product_grn_merchant_id']==17){
				$tfreight+=$chargesrow['stc_product_grn_freight_charges'];
				$tpandf+=$chargesrow['stc_product_grn_packing_forwarding'];
				$tothers+=$chargesrow['stc_product_grn_others_charges'];
			}elseif($chargesrow['stc_merchant_state_id']!=16){
				$tfreightigst += ($chargesrow['stc_product_grn_freight_charges'] * 18)/100;
				$tpandfigst += ($chargesrow['stc_product_grn_packing_forwarding'] * 18)/100;
				$tothersigst += ($chargesrow['stc_product_grn_others_charges'] * 18)/100;
			}else{
				$tfreightgst += ($chargesrow['stc_product_grn_freight_charges'] * 18)/100;
				$tpandfgst += ($chargesrow['stc_product_grn_packing_forwarding'] * 18)/100;
				$tothersgst += ($chargesrow['stc_product_grn_others_charges'] * 18)/100;
			}
		}
		$odin=array(
			'basic' => round($basic, 2),
			'bgst' => round($gst, 2),
			'bigst' => round($igst, 2),
			'bfright' => round($tfreight, 2),
			'btandf' => round($tpandf, 2),
			'bothers' => round($tothers, 2),
			'bfgst' => round($tfreightgst, 2),
			'bpandcgst' => round($tpandfgst, 2),
			'bothergst' => round($tothersgst, 2),
			'bfigst' => round($tfreightigst, 2),
			'bpandcigst' => round($tpandfigst, 2),
			'botherigst' => round($tothersigst, 2)
		);
		return $odin;
	}

	// filter purchase record from date & merchant 
	public function stc_filter_pur_reg_by_mer($search, $purbegdate, $purenddate){
		$odin='';
		$basic=0;
		$gst=0;
		$igst=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_product_grn_items_qty`, 
				`stc_product_grn_items_rate`, 
				`stc_merchant_state_id`,
				`stc_product_gst` 
			FROM `stc_product_grn_items` 
			INNER JOIN `stc_product_grn` 
			ON `stc_product_grn_items_grn_order_id`=`stc_product_grn_id`
			INNER JOIN `stc_merchant` 
			ON `stc_product_grn_merchant_id`=`stc_merchant_id`
			INNER JOIN `stc_product` 
			ON `stc_product_grn_items_product_id`=`stc_product_id`
			WHERE (`stc_product_grn_invoice_date`
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $purbegdate)."'
				AND '".mysqli_real_escape_string($this->stc_dbs, $purenddate)."'
			)
			AND `stc_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $search)."'
			ORDER BY `stc_product_grn`.`stc_product_grn_id` DESC 
		");

		foreach($check_loki as $row){
			$bastempval=0;
			$gsttempval=0;
			$bastempval=round($row['stc_product_grn_items_qty'], 2) * round($row['stc_product_grn_items_rate'], 2);
			$basic+=$bastempval;
			if($row['stc_merchant_state_id']==16){
				$gsttempval = ($bastempval * $row["stc_product_gst"])/100;
				$gst+=$gsttempval;
			}else{
				$gsttempval = ($bastempval * $row["stc_product_gst"])/100;
				$igst+=$gsttempval;
			}
		}

		$check_loki_again=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_dc_items_product_qty`, 
				`stc_sale_product_dc_items_product_rate`, 
				`stc_merchant_state_id`,
				`stc_product_gst` 
			FROM `stc_sale_product_dc_items` 
			INNER JOIN `stc_sale_product` 
			ON `stc_sale_product_dc_items_sale_product_id`=`stc_sale_product_id`
			INNER JOIN `stc_merchant` 
			ON`stc_sale_product_dc_merchant`=`stc_merchant_id`
			INNER JOIN `stc_product` 
			ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
			WHERE (`stc_sale_product_dc_invo_date`
                BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $purbegdate)."'
                AND '".mysqli_real_escape_string($this->stc_dbs, $purenddate)."'
            )
            AND `stc_sale_product_dc_keys`='directchallaned'
            AND `stc_merchant_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC 
		");

		foreach($check_loki_again as $row){
			$bastempval=0;
			$gsttempval=0;
			$bastempval=round($row['stc_sale_product_dc_items_product_qty'], 2) * round($row['stc_sale_product_dc_items_product_rate'], 2);
			$basic+=$bastempval;
			if($row['stc_merchant_state_id']==16){
				$gsttempval = ($bastempval * $row["stc_product_gst"])/100;
				$gst+=$gsttempval;
			}else{
				$gsttempval = ($bastempval * $row["stc_product_gst"])/100;
				$igst+=$gsttempval;
			}
		}

		$check_loki_again_again=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_product_grn_freight_charges`, 
				`stc_product_grn_packing_forwarding`, 
				`stc_product_grn_others_charges`, 
				`stc_product_grn_merchant_id`,
				`stc_merchant_state_id`
			FROM `stc_product_grn` 
			INNER JOIN `stc_merchant` 
			ON `stc_product_grn_merchant_id`=`stc_merchant_id`
			WHERE (`stc_product_grn_invoice_date`
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $purbegdate)."'
				AND '".mysqli_real_escape_string($this->stc_dbs, $purenddate)."'
			)
			AND `stc_merchant_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			ORDER BY `stc_product_grn`.`stc_product_grn_id` DESC 
		");

		$tfreight=0;
		$tpandf=0;
		$tothers=0;
		$tfreightgst=0;
		$tpandfgst=0;
		$tothersgst=0;
		$tfreightigst=0;
		$tpandfigst=0;
		$tothersigst=0;
		foreach($check_loki_again_again as $chargesrow){
			$tfreight+=$chargesrow['stc_product_grn_freight_charges'];
			$tpandf+=$chargesrow['stc_product_grn_packing_forwarding'];
			$tothers+=$chargesrow['stc_product_grn_others_charges'];

			if($chargesrow['stc_product_grn_merchant_id']==17){
				$tfreight+=$chargesrow['stc_product_grn_freight_charges'];
				$tpandf+=$chargesrow['stc_product_grn_packing_forwarding'];
				$tothers+=$chargesrow['stc_product_grn_others_charges'];
			}elseif($chargesrow['stc_merchant_state_id']!=16){
				$tfreightigst += ($chargesrow['stc_product_grn_freight_charges'] * 18)/100;
				$tpandfigst += ($chargesrow['stc_product_grn_packing_forwarding'] * 18)/100;
				$tothersigst += ($chargesrow['stc_product_grn_others_charges'] * 18)/100;
			}else{
				$tfreightgst += ($chargesrow['stc_product_grn_freight_charges'] * 18)/100;
				$tpandfgst += ($chargesrow['stc_product_grn_packing_forwarding'] * 18)/100;
				$tothersgst += ($chargesrow['stc_product_grn_others_charges'] * 18)/100;
			}
		}
		$odin=array(
			'basic' 		=> round($basic, 2),
			'bgst' 			=> round($gst, 2),
			'bigst' 		=> round($igst, 2),
			'bfright' 		=> round($tfreight, 2),
			'btandf' 		=> round($tpandf, 2),
			'bothers' 		=> round($tothers, 2),
			'bfgst' 		=> round($tfreightgst, 2),
			'bpandcgst' 	=> round($tpandfgst, 2),
			'bothergst' 	=> round($tothersgst, 2),
			'bfigst' 		=> round($tfreightigst, 2),
			'bpandcigst' 	=> round($tpandfigst, 2),
			'botherigst' 	=> round($tothersigst, 2)
		);
		return $odin;
	}
}

class ragnarSaleRegisterView extends tesseract{

	// call vendor on po page
	public function stc_call_customer(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_customer` GROUP BY `stc_customer_name` ASC");
		$odin='<option value="NA" selected>Select Customer</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Customer Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
				if($row["stc_customer_id"]==2){
					$odin.='
						<option value="'.$row["stc_customer_id"].'" selected>'.$row["stc_customer_name"].'</option>		               	
					';
				}else{
					$odin.='
						<option value="'.$row["stc_customer_id"].'">'.$row["stc_customer_name"].'</option>		               	
					';
				}				
			}
			
		}
		return $odin;
	}

	// filter sale record from date
	public function stc_filter_sale_reg_by_date($salebegdate, $saleenddate){
		$odin='';
		$saleregister='';
		$tbasic=0;
		$tgst=0;
		$tigst=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_bill_id`,
				`stc_sale_product_id`, 
				`stc_sale_product_dc_keys`, 
				`stc_customer_state_id`, 
				`stc_customer_name`,
				`stc_sale_product_bill_date`,
				`stc_sale_product_cust_order_no`,
				`stc_sale_product_cust_order_date`,
				`stc_sale_product_refrence`,
				`stc_sale_product_lr_no`,
				`stc_sale_product_way_bill_no`,
				`stc_sale_product_dosupply`,
				`stc_sale_product_posupply`,
				`stc_sale_product_way_bill_no`,
				`stc_sale_product_sitename`
			FROM `stc_sale_product_bill`
			INNER JOIN `stc_sale_product_bill_no`
			ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
			INNER JOIN `stc_sale_product`
			ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
			INNER JOIN `stc_customer`
			ON `stc_sale_product_cust_id`=`stc_customer_id`
			WHERE `stc_sale_product_bill_date` 
			BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $salebegdate)."'
			AND '".mysqli_real_escape_string($this->stc_dbs, $saleenddate)."'
		");
		foreach($check_loki as $getbillid){
			$get_purchase_product_date=$getbillid['stc_sale_product_bill_date'];
			$yearchange = date('Y', strtotime($get_purchase_product_date));
			$monthchange = date('m', strtotime($get_purchase_product_date));
			$yearchangevalue = '';

			// Convert year and month to integers
			$year = intval($yearchange);
			$month = intval($monthchange);

			// Determine the fiscal year range
			if ($month > 3) {
				$startYear = $year % 100;
				$endYear = ($year + 1) % 100;
			} else {
				$startYear = ($year - 1) % 100;
				$endYear = $year % 100;
			}

			// Format the year change value
			$yearchangevalue = sprintf('%02d-%02d', $startYear, $endYear);
			if($getbillid['stc_customer_state_id']==16){
				if($getbillid['stc_sale_product_dc_keys']=="directchallaned"){
					$nestcheckloki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_bill_no`,
                        	`stc_product_id`,
                        	`stc_sale_product_dc_items_product_id`,
                        	`stc_product_name`,
                        	`stc_product_hsncode`,
                        	`stc_product_unit`,
                        	`stc_sale_product_dc_items_product_qty`,
                        	`stc_sale_product_dc_items_product_sale_rate`,
                        	`stc_product_gst`,
                        	`stc_sub_cat_name`,
                        	`stc_product_brand_id`
                        FROM `stc_sale_product_dc_items` 
                        INNER JOIN `stc_sale_product`
                        ON `stc_sale_product_dc_items_sale_product_id`=`stc_sale_product_id`
                        INNER JOIN `stc_product`
                        ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
                        INNER JOIN `stc_sub_category`
                        ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
                        INNER JOIN `stc_sale_product_bill_no`
                        ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
                        INNER JOIN `stc_sale_product_bill`
                        ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_bill_id`
                        WHERE `stc_sale_product_bill_id`='".$getbillid['stc_sale_product_bill_id']."'
                        AND `stc_sale_product_bill_no_bill_series`='1'
                        ORDER BY `stc_product_desc` ASC
					");
					foreach($nestcheckloki as $nestedrow){
						$brand=' MAKE - ';
                        $query=mysqli_query($this->stc_dbs, "
                          SELECT * FROM `stc_brand` WHERE `stc_brand_id`='".$nestedrow['stc_product_brand_id']."'
                        ");
                        if(mysqli_num_rows($query)>0){
                          foreach($query as $brandrow){
                            if($brandrow['stc_brand_id']==0){
                              $brand='';
                            }else{
                              $brand.=$brandrow['stc_brand_title'];
                            }
                            break;
                          }
                        }else{
                          $brand='';
                        }                            
                        $pdname=$nestedrow["stc_product_name"].' '.$brand;
						$amount=$nestedrow['stc_sale_product_dc_items_product_qty'] * $nestedrow['stc_sale_product_dc_items_product_sale_rate'];
						$halfgst=$nestedrow['stc_product_gst']/2;
						$saleregister.='<tr><td>STC/'.substr("0000{$nestedrow['stc_sale_product_bill_no']}", -5).'/'.$yearchangevalue.'</td><td>'.date('d-m-Y', strtotime($getbillid['stc_sale_product_bill_date'])).'</td><td>'.$getbillid['stc_customer_name'].'</td><td>'.$getbillid['stc_sale_product_cust_order_no'].'</td><td>'.date('d-m-Y', strtotime($getbillid['stc_sale_product_cust_order_date'])).'</td><td>Any Local Transport</td><td>'.$getbillid['stc_sale_product_dosupply'].'</td><td>'.$getbillid['stc_sale_product_posupply'].'</td><td>'.$getbillid['stc_sale_product_sitename'].'</td><td>'.$getbillid['stc_sale_product_refrence'].'</td><td>'.$getbillid['stc_sale_product_lr_no'].'</td><td>'.$getbillid['stc_sale_product_way_bill_no'].'</td><td>NO</td><td>'.$pdname.'</td><td>'.$nestedrow['stc_product_hsncode'].'</td><td>'.$nestedrow['stc_product_unit'].'</td><td>'.$nestedrow['stc_sale_product_dc_items_product_qty'].'</td><td>'.$nestedrow['stc_sale_product_dc_items_product_sale_rate'].'</td><td>'.number_format($amount, 2).'</td><td>'.$nestedrow['stc_product_gst'].'%</td><td>'.$halfgst.'</td><td>'.number_format($amount, 2).'</td><td>'.$halfgst.'</td><td>'.number_format($amount, 2).'</td><td>0</td><td>0</td><td>0</td></tr>';
						$tempbasicval=0;
						$tempbasicval=round($nestedrow['stc_sale_product_dc_items_product_qty'], 2) * round($nestedrow['stc_sale_product_dc_items_product_sale_rate'], 2);
						$tbasic+=$tempbasicval;
						$tgst+=($tempbasicval * $nestedrow["stc_product_gst"])/100;;
					}
				}elseif($getbillid['stc_sale_product_dc_keys']=="virtualchallaned"){
					$nestcheckloki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_vc_items_product_qty`, 
							`stc_sale_product_vc_items_product_sale_rate`,
							`stc_product_gst` 
						FROM `stc_sale_product_vc`
						INNER JOIN `stc_product`
						ON `stc_sale_product_vc_items_product_id`=`stc_product_id`
						WHERE `stc_sale_product_vc_items_sale_product_id`='".$getbillid['stc_sale_product_id']."'
					");
					foreach($nestcheckloki as $nestedrow){
						$tempbasicval=0;
						$tempbasicval=round($nestedrow['stc_sale_product_vc_items_product_qty'], 2) * round($nestedrow['stc_sale_product_vc_items_product_sale_rate'], 2);
						$tbasic+=$tempbasicval;
						$tgst+=($tempbasicval * $nestedrow["stc_product_gst"])/100;;
					}
				}else{
					$nestcheckloki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_items_product_qty`, 
							`stc_sale_product_items_product_sale_rate`,
							`stc_product_gst` 
						FROM `stc_sale_product_items`
						INNER JOIN `stc_product`
						ON `stc_sale_product_items_product_id`=`stc_product_id`
						WHERE `stc_sale_product_items_sale_product_id`='".$getbillid['stc_sale_product_id']."'
					");
					foreach($nestcheckloki as $nestedrow){
						$tempbasicval=0;
						$tempbasicval=round($nestedrow['stc_sale_product_items_product_qty'], 2) * round($nestedrow['stc_sale_product_items_product_sale_rate'], 2);
						$tbasic+=$tempbasicval;
						$tgst+=($tempbasicval * $nestedrow["stc_product_gst"])/100;;
					}
				}
			}else{
				if($getbillid['stc_sale_product_dc_keys']=="directchallaned"){
					$nestcheckloki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_dc_items_product_qty`, 
							`stc_sale_product_dc_items_product_sale_rate`,
							`stc_product_gst` 
						FROM `stc_sale_product_dc_items`
						INNER JOIN `stc_product`
						ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
						WHERE `stc_sale_product_dc_items_sale_product_id`='".$getbillid['stc_sale_product_id']."'
					");
					foreach($nestcheckloki as $nestedrow){
						$tempbasicval=0;
						$tempbasicval=round($nestedrow['stc_sale_product_dc_items_product_qty'], 2) * round($nestedrow['stc_sale_product_dc_items_product_sale_rate'], 2);
						$tbasic+=$tempbasicval;
						$tigst+=($tempbasicval * $nestedrow["stc_product_gst"])/100;;
					}
				}elseif($getbillid['stc_sale_product_dc_keys']=="virtualchallaned"){
					$nestcheckloki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_vc_items_product_qty`, 
							`stc_sale_product_vc_items_product_sale_rate`,
							`stc_product_gst` 
						FROM `stc_sale_product_vc`
						INNER JOIN `stc_product`
						ON `stc_sale_product_vc_items_product_id`=`stc_product_id`
						WHERE `stc_sale_product_vc_items_sale_product_id`='".$getbillid['stc_sale_product_id']."'
					");
					foreach($nestcheckloki as $nestedrow){
						$tempbasicval=0;
						$tempbasicval=round($nestedrow['stc_sale_product_vc_items_product_qty'], 2) * round($nestedrow['stc_sale_product_vc_items_product_sale_rate'], 2);
						$tbasic+=$tempbasicval;
						$tgst+=($tempbasicval * $nestedrow["stc_product_gst"])/100;;
					}
				}else{
					$nestcheckloki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_items_product_qty`, 
							`stc_sale_product_items_product_sale_rate`,
							`stc_product_gst` 
						FROM `stc_sale_product_items`
						INNER JOIN `stc_product`
						ON `stc_sale_product_items_product_id`=`stc_product_id`
						WHERE `stc_sale_product_items_sale_product_id`='".$getbillid['stc_sale_product_id']."'
					");
					foreach($nestcheckloki as $nestedrow){
						$tempbasicval=0;
						$tempbasicval=round($nestedrow['stc_sale_product_items_product_qty'], 2) * round($nestedrow['stc_sale_product_items_product_sale_rate'], 2);
						$tbasic+=$tempbasicval;
						$tigst+=($tempbasicval * $nestedrow["stc_product_gst"])/100;;
					}
				}
			}	
		}
		$odin=array(
			'salebasic' => round($tbasic, 2),
			'salegst' => round($tgst, 2),
			'saleigst' => round($tigst, 2),
			'saleregister' => $saleregister
		);
		return $odin;
	}

	// filter sale record from date & customer
	public function stc_filter_sale_reg_by_cust($search, $salebegdate, $saleenddate){
		$odin='';
		$tbasic=0;
		$tgst=0;
		$tigst=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_id`, 
				`stc_sale_product_dc_keys`, 
				`stc_customer_state_id`  
			FROM `stc_sale_product_bill`
			INNER JOIN `stc_sale_product_bill_no`
			ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
			INNER JOIN `stc_sale_product`
			ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
			INNER JOIN `stc_customer`
			ON `stc_sale_product_cust_id`=`stc_customer_id`
			WHERE (`stc_sale_product_bill_date` 
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $salebegdate)."'
				AND '".mysqli_real_escape_string($this->stc_dbs, $saleenddate)."'
			)
			AND `stc_customer_id`='".$search."'
		");

		foreach($check_loki as $getbillid){
			if($getbillid['stc_customer_state_id']==16){
				if($getbillid['stc_sale_product_dc_keys']=="directchallaned"){
					$nestcheckloki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_dc_items_product_qty`, 
							`stc_sale_product_dc_items_product_sale_rate`,
							`stc_product_gst` 
						FROM `stc_sale_product_dc_items`
						INNER JOIN `stc_product`
						ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
						WHERE `stc_sale_product_dc_items_sale_product_id`='".$getbillid['stc_sale_product_id']."'
					");
					foreach($nestcheckloki as $nestedrow){
						$tempbasicval=0;
						$tempbasicval=round($nestedrow['stc_sale_product_dc_items_product_qty'], 2) * round($nestedrow['stc_sale_product_dc_items_product_sale_rate'], 2);
						$tbasic+=$tempbasicval;
						$tgst+=($tempbasicval * $nestedrow["stc_product_gst"])/100;;
					}
				}elseif($getbillid['stc_sale_product_dc_keys']=="virtualchallaned"){
					$nestcheckloki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_vc_items_product_qty`, 
							`stc_sale_product_vc_items_product_sale_rate`,
							`stc_product_gst` 
						FROM `stc_sale_product_vc`
						INNER JOIN `stc_product`
						ON `stc_sale_product_vc_items_product_id`=`stc_product_id`
						WHERE `stc_sale_product_vc_items_sale_product_id`='".$getbillid['stc_sale_product_id']."'
					");
					foreach($nestcheckloki as $nestedrow){
						$tempbasicval=0;
						$tempbasicval=round($nestedrow['stc_sale_product_vc_items_product_qty'], 2) * round($nestedrow['stc_sale_product_vc_items_product_sale_rate'], 2);
						$tbasic+=$tempbasicval;
						$tgst+=($tempbasicval * $nestedrow["stc_product_gst"])/100;;
					}
				}else{
					$nestcheckloki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_items_product_qty`, 
							`stc_sale_product_items_product_sale_rate`,
							`stc_product_gst` 
						FROM `stc_sale_product_items`
						INNER JOIN `stc_product`
						ON `stc_sale_product_items_product_id`=`stc_product_id`
						WHERE `stc_sale_product_items_sale_product_id`='".$getbillid['stc_sale_product_id']."'
					");
					foreach($nestcheckloki as $nestedrow){
						$tempbasicval=0;
						$tempbasicval=round($nestedrow['stc_sale_product_items_product_qty'], 2) * round($nestedrow['stc_sale_product_items_product_sale_rate'], 2);
						$tbasic+=$tempbasicval;
						$tgst+=($tempbasicval * $nestedrow["stc_product_gst"])/100;;
					}
				}
			}else{
				if($getbillid['stc_sale_product_dc_keys']=="directchallaned"){
					$nestcheckloki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_dc_items_product_qty`, 
							`stc_sale_product_dc_items_product_sale_rate`,
							`stc_product_gst` 
						FROM `stc_sale_product_dc_items`
						INNER JOIN `stc_product`
						ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
						WHERE `stc_sale_product_dc_items_sale_product_id`='".$getbillid['stc_sale_product_id']."'
					");
					foreach($nestcheckloki as $nestedrow){
						$tempbasicval=0;
						$tempbasicval=round($nestedrow['stc_sale_product_dc_items_product_qty'], 2) * round($nestedrow['stc_sale_product_dc_items_product_sale_rate'], 2);
						$tbasic+=$tempbasicval;
						$tigst+=($tempbasicval * $nestedrow["stc_product_gst"])/100;;
					}
				}elseif($getbillid['stc_sale_product_dc_keys']=="virtualchallaned"){
					$nestcheckloki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_vc_items_product_qty`, 
							`stc_sale_product_vc_items_product_sale_rate`,
							`stc_product_gst` 
						FROM `stc_sale_product_vc`
						INNER JOIN `stc_product`
						ON `stc_sale_product_vc_items_product_id`=`stc_product_id`
						WHERE `stc_sale_product_vc_items_sale_product_id`='".$getbillid['stc_sale_product_id']."'
					");
					foreach($nestcheckloki as $nestedrow){
						$tempbasicval=0;
						$tempbasicval=round($nestedrow['stc_sale_product_vc_items_product_qty'], 2) * round($nestedrow['stc_sale_product_vc_items_product_sale_rate'], 2);
						$tbasic+=$tempbasicval;
						$tgst+=($tempbasicval * $nestedrow["stc_product_gst"])/100;;
					}
				}else{
					$nestcheckloki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_items_product_qty`, 
							`stc_sale_product_items_product_sale_rate`,
							`stc_product_gst` 
						FROM `stc_sale_product_items`
						INNER JOIN `stc_product`
						ON `stc_sale_product_items_product_id`=`stc_product_id`
						WHERE `stc_sale_product_items_sale_product_id`='".$getbillid['stc_sale_product_id']."'
					");
					foreach($nestcheckloki as $nestedrow){
						$tempbasicval=0;
						$tempbasicval=round($nestedrow['stc_sale_product_items_product_qty'], 2) * round($nestedrow['stc_sale_product_items_product_sale_rate'], 2);
						$tbasic+=$tempbasicval;
						$tigst+=($tempbasicval * $nestedrow["stc_product_gst"])/100;;
					}
				}
			}	
		}
		$odin=array(
			'salebasic' => round($tbasic, 2),
			'salegst' => round($tgst, 2),
			'saleigst' => round($tigst, 2)
		);
		return $odin;
	}
}
#<------------------------------------------------------------------------------------------------------>
#<--------------------------------------Object sections of Quotation class------------------------------>
#<------------------------------------------------------------------------------------------------------>
// show vendor on quotation page
if(isset($_POST['friday_vendor'])){
	$objloki=new ragnarPurchaseRegisterView();
	$objlokiout=$objloki->stc_call_vendor();
	$date=date('l jS \ F Y h:i:s A');
	$outarray=array(
		'time' => $date,
		'vendor' => $objlokiout
	);
	echo json_encode($outarray);
}

// call purchase from date
if(isset($_POST['get_rec_fro_date'])){
	$purbegdate=date("Y-m-d", strtotime($_POST['purbegdate']));
	$purenddate=date("Y-m-d", strtotime($_POST['purenddate']));

	$objloki=new ragnarPurchaseRegisterView();
	$objlokiout=$objloki->stc_filter_pur_reg($purbegdate, $purenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call purchase from date & merchant
if(isset($_POST['get_rec_fro_key'])){
	$search = str_replace(",", "|", $_POST["key_pay"]);
	$purbegdate=date("Y-m-d", strtotime($_POST['purbegdate']));
	$purenddate=date("Y-m-d", strtotime($_POST['purenddate']));

	$objloki=new ragnarPurchaseRegisterView();
	$objlokiout=$objloki->stc_filter_pur_reg_by_mer($search, $purbegdate, $purenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// show customer on register page
if(isset($_POST['friday_customer'])){
	$objloki=new ragnarSaleRegisterView();
	$objlokiout=$objloki->stc_call_customer();
	$date=date('l jS \ F Y h:i:s A');
	$outarray=array(
		'time' => $date,
		'customer' => $objlokiout
	);
	echo json_encode($outarray);
}

// show sale from date
if(isset($_POST['get_sale_rec_fro_date'])){
	$salebegdate=date("Y-m-d", strtotime($_POST['salebegdate']));
	$saleenddate=date("Y-m-d", strtotime($_POST['saleenddate']));

	$objloki=new ragnarSaleRegisterView();
	$objlokiout=$objloki->stc_filter_sale_reg_by_date($salebegdate, $saleenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// show sale from date & customer
if(isset($_POST['get_sale_rec_fro_cust'])){
	$search = str_replace(",", "|", $_POST["key_pay"]);
	$salebegdate=date("Y-m-d", strtotime($_POST['salebegdate']));
	$saleenddate=date("Y-m-d", strtotime($_POST['saleenddate']));

	$objloki=new ragnarSaleRegisterView();
	$objlokiout=$objloki->stc_filter_sale_reg_by_cust($search, $salebegdate, $saleenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}
?>