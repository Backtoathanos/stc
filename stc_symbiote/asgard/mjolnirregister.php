<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------------For GRN -------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/

class galactus extends tesseract{	
	
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
			AND `stc_merchant_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
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

	// filter sale record from date
	public function stc_filter_sale_reg_by_date($salebegdate, $saleenddate){
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
			WHERE `stc_sale_product_bill_date` 
			BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $salebegdate)."'
			AND '".mysqli_real_escape_string($this->stc_dbs, $saleenddate)."'
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
			AND `stc_customer_name` REGEXP '".$search."'
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

#<------------------------------------------------------------------------------------------>
/*---------------------------------------GRN Objects---------------------------------------*/
#<------------------------------------------------------------------------------------------>

if(isset($_POST['get_rec_fro_date'])){
	// $search = str_replace(",", "|", $_POST["get_rec_fro_date"]);
	$purbegdate=date("Y-m-d", strtotime($_POST['purbegdate']));
	$purenddate=date("Y-m-d", strtotime($_POST['purenddate']));

	$objloki=new galactus();
	$objlokiout=$objloki->stc_filter_pur_reg($purbegdate, $purenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

if(isset($_POST['get_rec_fro_key'])){
	$search = str_replace(",", "|", $_POST["key_pay"]);
	$purbegdate=date("Y-m-d", strtotime($_POST['purbegdate']));
	$purenddate=date("Y-m-d", strtotime($_POST['purenddate']));

	$objloki=new galactus();
	$objlokiout=$objloki->stc_filter_pur_reg_by_mer($search, $purbegdate, $purenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

if(isset($_POST['get_sale_rec_fro_date'])){
	$salebegdate=date("Y-m-d", strtotime($_POST['salebegdate']));
	$saleenddate=date("Y-m-d", strtotime($_POST['saleenddate']));

	$objloki=new galactus();
	$objlokiout=$objloki->stc_filter_sale_reg_by_date($salebegdate, $saleenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

if(isset($_POST['get_sale_rec_fro_cust'])){
	$search = str_replace(",", "|", $_POST["key_pay"]);
	$salebegdate=date("Y-m-d", strtotime($_POST['salebegdate']));
	$saleenddate=date("Y-m-d", strtotime($_POST['saleenddate']));

	$objloki=new galactus();
	$objlokiout=$objloki->stc_filter_sale_reg_by_cust($search, $salebegdate, $saleenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}
?>