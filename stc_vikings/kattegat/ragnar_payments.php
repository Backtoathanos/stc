<?php
include "../../MCU/obdb.php";
session_start();
// view all payment class
class ragnarPaymentView extends tesseract{
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

	// filter product by all
	public function stc_getpo_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltermerid, $bjornefilterpono, $bjornefilterstatus, $bjornebegval, $bjorneendval){
		$odin='';
			
		$array = array(
			"bymerchant" => $bjornefiltermerid,
			"bypono" => $bjornefilterpono,
			"bystatus" => $bjornefilterstatus
		);

		$merchant='';
		$po='';
		$status='';
		foreach($array as $key => $value){
			if($array['bymerchant']!="NA"){
				$merchant="
					AND `stc_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bymerchant'])."'
				";
			}

			if(!empty($array['bypono'])){
				$po="
					AND `stc_purchase_product_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bypono'])."'
				";
			}

			if($array['bystatus']!="NA"){
				$status="
					AND `stc_purchase_product_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bystatus'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_purchase_product_id` DESC LIMIT '.$bjornebegval.','.$bjorneendval;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_purchase_product_id`, 
				`stc_merchant_name`, 
				`stc_purchase_product_order_date`
			FROM `stc_purchase_product` 
			LEFT JOIN `stc_merchant` 
			ON `stc_purchase_product_merchant_id`=`stc_merchant_id` 
			WHERE (
                `stc_purchase_product_order_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterenddate)."'
            )
			".$merchant.$po.$status.$endfilterqry
		);
		$str_length = 5;

		
		$allcount_query = "SELECT count(`stc_purchase_product_id`) as allcount FROM stc_purchase_product";
		$allcount_result = mysqli_query($this->stc_dbs, $allcount_query);
		$allcount_fetch = mysqli_fetch_array($allcount_result);
		$allcount = $allcount_fetch['allcount'];
		$do_action=mysqli_num_rows($ivarfilterquery);
		if($do_action == 0){
			$odin = '
				<tr>
		    		<td colspan="13">
						<a 
						  	href="#" 
						  	class="btn btn-primary btn-block text-uppercase mb-3">
						 	No Records Found!!!
						</a>
					</td>
				</tr>
			';
		}else{
			foreach ($ivarfilterquery as $row) {
				$grn='';
				$invoice='';
				$invoamt='';
				$invototamt='';
				$totalinvo=0;
				$poamt='';
				$progst='';
				$rpfvalue=0;
				$apfvalue=0;
				$rp='';
				$rpvalue='';
				$callgrn_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_product_grn_id`,
						`stc_product_grn_date`,
						`stc_product_grn_invoice_number`,
						`stc_product_grn_invoice_date`,
						`stc_product_grn_freight_charges`,
						`stc_product_grn_packing_forwarding`,
						`stc_product_grn_others_charges`
					FROM `stc_product_grn` 
					WHERE `stc_product_grn_purchase_order_id`='".$row["stc_purchase_product_id"]."'
				");

				foreach($callgrn_loki as $grnrow){
					$ininvoamtgrn=0;
					$invototamtgrn=0;
					$grn.='
						GRN/'.substr("0000{$grnrow["stc_product_grn_id"]}", -$str_length).'</br>'.
						date('d-m-Y',strtotime($grnrow["stc_product_grn_date"])).'</br>'
					;
					$invoice.=
						$grnrow["stc_product_grn_invoice_number"].'</br>'.
						date('d-m-Y',strtotime($grnrow["stc_product_grn_invoice_date"])).'</p>'
					;

					$callamot_loki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_product_grn_items_qty`, 
							`stc_product_grn_items_rate`,
							`stc_product_gst`
						FROM `stc_product_grn_items`
						INNER JOIN `stc_product`
						ON `stc_product_grn_items_product_id`=`stc_product_id`
						WHERE `stc_product_grn_items_grn_order_id`='".$grnrow["stc_product_grn_id"]."'
					");

					foreach ($callamot_loki as $amtrow){
						$ininvoamt = $amtrow['stc_product_grn_items_qty'] * $amtrow['stc_product_grn_items_rate'];						
						$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
						$ininvoamtgrn += $ininvoamt;
						$invototamtgrn += $ininvototamt;
						$progst=$amtrow['stc_product_gst'];
					}

					$gstfc=($grnrow['stc_product_grn_freight_charges'] * $progst)/100;
					$gstpf=($grnrow['stc_product_grn_packing_forwarding'] * $progst)/100;
					$gstoc=($grnrow['stc_product_grn_others_charges'] * $progst)/100;

					$invototamtgrna =
						($gstfc + $grnrow['stc_product_grn_freight_charges']) + 
						($gstpf + $grnrow['stc_product_grn_packing_forwarding']) + 
						($gstoc + $grnrow['stc_product_grn_others_charges']) + 
						($invototamtgrn);
					$totalinvo+=$invototamtgrna;
					$invoamt.=number_format($ininvoamtgrn, 2).'<br>';
					$invototamt.=number_format($invototamtgrna, 2).'<br>';

					$callrp_loki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_merchant_regular_payment_id`, 
							`stc_merchant_regular_payment_date`, 
							`stc_merchant_regular_payment_amount` 
						FROM `stc_merchant_regular_payment` 
						WHERE `stc_merchant_regular_payment_grn_number`='".$grnrow["stc_product_grn_id"]."'
					");

					foreach($callrp_loki as $rprow){
						$rp.='
							RP/'.substr("0000{$rprow["stc_merchant_regular_payment_id"]}", -$str_length).'</br>'.
							date('d-m-Y',strtotime($rprow["stc_merchant_regular_payment_date"])).'<br>'
						;
						$rpvalue.=number_format($rprow["stc_merchant_regular_payment_amount"], 2).'<br>';
						$rpfvalue += $rprow["stc_merchant_regular_payment_amount"];
					}
				}

				$callap_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_merchant_advance_payment_id`, 
						`stc_merchant_advance_payment_date`, 
						`stc_merchant_advance_payment_pi_number`, 
						`stc_merchant_advance_payment_pi_date`, 
						`stc_merchant_advance_payment_advance_value`,
						`stc_merchant_advance_payment_charges`,
						`stc_merchant_advance_payment_charges_tax` 
					FROM `stc_merchant_advance_payment` 
					WHERE `stc_merchant_advance_payment_purhcase_product_id`='".$row["stc_purchase_product_id"]."'
				");

				$ap='';
				$apvalue='';
				$chargesamount=0;
				foreach($callap_loki as $aprow){
					$ap.='
						AP/'.substr("0000{$aprow["stc_merchant_advance_payment_id"]}", -$str_length).'</br>'.
						date('d-m-Y',strtotime($aprow["stc_merchant_advance_payment_date"])).'</p>'
					;
					$apvalue.=number_format($aprow["stc_merchant_advance_payment_advance_value"], 2).'</br>';
					$apfvalue += $aprow["stc_merchant_advance_payment_advance_value"];
					$chargesamount += $aprow["stc_merchant_advance_payment_charges"] + ($aprow["stc_merchant_advance_payment_charges"] * $aprow["stc_merchant_advance_payment_charges_tax"]/100);
				}

				$callpovalue_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_purchase_product_items_qty`, `stc_purchase_product_items_rate`,
						`stc_product_gst`
					FROM `stc_purchase_product_items`
					INNER JOIN `stc_product`
					ON `stc_purchase_product_items_product_id`=`stc_product_id`
					WHERE `stc_purchase_product_items_order_id`='".$row["stc_purchase_product_id"]."'
				");	
				$insidepoamt=0;
				foreach ($callpovalue_loki as $amtrow){
					$currvalue=0;	
					$currvalue=$amtrow['stc_purchase_product_items_qty'] * $amtrow['stc_purchase_product_items_rate'];			
					$ininvototamt = $currvalue + ($currvalue * $amtrow["stc_product_gst"])/100;
					$insidepoamt += $ininvototamt;
				}
				$poamt=number_format($insidepoamt, 2);

				$paidvalue=$rpfvalue + $apfvalue;
				$balanceamt=$insidepoamt - ($paidvalue) + $chargesamount;
				$odin.='
					<tr>
		       			<td>
		       			  <b>'.$row["stc_merchant_name"].'</b>
		       			</td>
		       			<td>
		       			  STC/'.substr("0000{$row["stc_purchase_product_id"]}", -$str_length).'<br>
		       			  '.date('d-m-Y',strtotime($row["stc_purchase_product_order_date"])).'
		       			</td>
						<td>
							'.$grn.'
		       			</td>
		       			<td class="grn-h-out">
		       				'.$invoice.'
		       			</td>
		       			<td>
		       				'.$ap.'
		       			</td>
		       			<td align="right" class="ap-h-out">
		       				'.$apvalue.'
		       			</td>
		       			<td>
		       				'.$rp.'
		       			</td>
		       			<td align="right" class="rp-h-out">
		       				'.$rpvalue.'
		       			</td>
		       			<td align="right">
		       				'.$invoamt.'
		       			</td>
		       			<td align="right">
		       				'.$invototamt.'
		       				-----------<br>
		       				'.number_format($totalinvo, 2).'
		       			</td>
		       			<td align="right">
		       				'.$poamt.'
		       			</td>
		       			<td align="right">
		       				'.(number_format($paidvalue, 2)).'
		       			</td>
		       			<td align="right">
		       				'.(number_format($balanceamt, 2)).'
		       			</td>
		        	</tr>	               	
		        ';				
			}			
		}		

		$odin.= '
			<tr>
				<td>
					'.$bjornebegval.' to '.$bjorneendval.'
					<button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="begvalueinputsearch" value="'.$bjornebegval.'">
				</td>
				<td colspan="12">
					<button type="button" class="btn btn-primary endbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="endvalueinputsearch" value="'.$bjorneendval.'">
				</td>
			</tr>
		';	
		return $odin;
	}	
}

// view advance payment class
class ragnarAdvancePaymentView extends tesseract{
	// call po on choose vendor advance payment
	public function stc_call_po_choosing_vendor($merchant_id){
		$str_length = 5;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_purchase_product_id`
			FROM `stc_purchase_product` 
			WHERE `stc_purchase_product_merchant_id`='".$merchant_id."'
			ORDER BY `stc_purchase_product_id` DESC
		");
		$odin='<option selected>Please Select Purchase Order Number</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Purchase Ordrer Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_purchase_product_id"].'">'.'STC/'.substr("0000{$row["stc_purchase_product_id"]}", -$str_length).'</option>		               	
		            	';				
			}			
		}
		return $odin;
	}

	// call po date choosing vendor in advance payment
	public function stc_call_po_number_choosing_merchant($po_number){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_purchase_product` WHERE `stc_purchase_product_id`='".$po_number."'");
		$do_action=mysqli_fetch_assoc($check_loki);
		$odin=$do_action['stc_purchase_product_order_date'];
		return $odin;
	}

	// call po amount in advance payment
	public function stc_call_po_amount($po_number){
		$odin=0;
		$check_loki=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_purchase_product_items` 
				WHERE `stc_purchase_product_items_order_id`='".$po_number."'
		");
		foreach($check_loki as $row){
			$nest_check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_product` WHERE `stc_product_id`='".$row['stc_purchase_product_items_product_id']."'");
			$pd_out_gst=mysqli_fetch_assoc($nest_check_loki);
			$amount=$row['stc_purchase_product_items_qty'] * $row['stc_purchase_product_items_rate'];
			$odin += $amount;
		}
		return $odin;
	}
}

// add advance payment class
class ragnarAdvancePaymentAdd extends tesseract{
	// go to advance payment table
	public function stc_advance_payment($merchant, $apponumbershow, $stcapinvonumber, $stcapinvodate, $stcappaymenttype, $stcaprefnumber, $stcapamount, $stcapchargesamount, $stcapchargesgst, $stcaptandc){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_merchant_advance_payment` 
			WHERE 
				`stc_merchant_advance_payment_purhcase_product_id`='".$apponumbershow."' 
			AND
				stc_merchant_advance_payment_advance_value='".$stcapamount."' 
			");
		$get_loki=mysqli_num_rows($check_loki);
		if($get_loki == 0){
			$insert_ap_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_merchant_advance_payment`(
					`stc_merchant_advance_payment_date`, 
					`stc_merchant_advance_payment_merchant_id`, 
					`stc_merchant_advance_payment_purhcase_product_id`, 
					`stc_merchant_advance_payment_pi_number`, 
					`stc_merchant_advance_payment_pi_date`, 
					`stc_merchant_advance_payment_refr_number`, 
					`stc_merchant_advance_payment_payment_type`, 
					`stc_merchant_advance_payment_advance_value`, 
					`stc_merchant_advance_payment_charges`,
					`stc_merchant_advance_payment_charges_tax`,
					`stc_merchant_advance_payment_notes`, 
					`stc_merchant_advance_payment_created_by`
				) VALUES (
					'".$date."',
					'".$merchant."',
					'".$apponumbershow."',
					'".mysqli_real_escape_string($this->stc_dbs,$stcapinvonumber)."',
					'".mysqli_real_escape_string($this->stc_dbs,$stcapinvodate)."',
					'".mysqli_real_escape_string($this->stc_dbs,$stcaprefnumber)."',
					'".$stcappaymenttype."',
					'".mysqli_real_escape_string($this->stc_dbs,$stcapamount)."',
					'".mysqli_real_escape_string($this->stc_dbs,$stcapchargesamount)."',
					'".mysqli_real_escape_string($this->stc_dbs,$stcapchargesgst)."',
					'".mysqli_real_escape_string($this->stc_dbs,$stcaptandc)."',
					'".$_SESSION['stc_empl_id']."'
				)
			");
			if($insert_ap_loki){
				$odin="Advance payment saved!!!";
			}else{
				$odin="Hmmm!!! Something went wrong!! Please try again later!!";
			}
		}else{
			$odin="Advance payment on this order already done!!!";
		}
		return $odin;
	}
}

// view regular [payment] class
class ragnarRegularPaymentView extends tesseract{
	// call invoice number choosing vendor in regular Payment
	public function stc_call_invo_choosing_merchant($merchant_id){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_product_grn_id`,
				`stc_product_grn_invoice_number`
			FROM `stc_product_grn` 
			WHERE `stc_product_grn_merchant_id`='".$merchant_id."'
			ORDER BY DATE(`stc_product_grn_purchase_order_date`) DESC
		");
		$odin='<option selected>Please Select Invoice Number</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Invoice Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_product_grn_id"].'">'.$row["stc_product_grn_invoice_number"].'</option>		               	
		            	';				
			}			
		}
		return $odin;
	}

	// call regular payment records
	public function stc_call_rec_for_reg_pay($invo_number, $merchant_id){
		$gstamount=0;
		$totalgst=0;
		$str_length = 5;
		$odin='';
		$lineamount=0;
		$basicamount=0;
		$pertgst=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_product_grn` WHERE  
			`stc_product_grn_id`='".$invo_number."' 
			AND 
			`stc_product_grn_merchant_id`='".$merchant_id."'
		");
		$count_loki=mysqli_num_rows($check_loki);
		if($count_loki != 0){
			$out_grnData=mysqli_fetch_assoc($check_loki);

			$basicvalue_check_loki=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_product_grn_items` 
				LEFT JOIN `stc_product` ON `stc_product_grn_items_product_id`=`stc_product_id` 
				WHERE `stc_product_grn_items_grn_order_id`='".$out_grnData['stc_product_grn_id']."'
			");

			foreach($basicvalue_check_loki as $basicvaluecount){
				$lineamount = $basicvaluecount['stc_product_grn_items_qty'] * $basicvaluecount['stc_product_grn_items_rate'];
				$gstamount = ($lineamount * $basicvaluecount["stc_product_gst"])/100;
				$totalgst += $gstamount;
				$basicamount += $lineamount;
				$pertgst = $basicvaluecount['stc_product_gst'];
			}

			$gstfc=($out_grnData['stc_product_grn_freight_charges'] * $pertgst)/100;
			$gstpf=($out_grnData['stc_product_grn_packing_forwarding'] * $pertgst)/100;
			$gstoc=($out_grnData['stc_product_grn_others_charges'] * $pertgst)/100;

			$totalincgst =
				($gstfc + $out_grnData['stc_product_grn_freight_charges']) + 
				($gstpf + $out_grnData['stc_product_grn_packing_forwarding']) + 
				($gstoc + $out_grnData['stc_product_grn_others_charges']) + 
				($basicamount + $totalgst);

			$advancepaid_check_loki=mysqli_query($this->stc_dbs, "
				SELECT `stc_merchant_advance_payment_advance_value` FROM `stc_merchant_advance_payment` WHERE `stc_merchant_advance_payment_purhcase_product_id`='".$out_grnData['stc_product_grn_purchase_order_id']."'
			");
			$advance_fetch=0;
			$regular_fetch=0;

			$regularpaid_check_loki=mysqli_query($this->stc_dbs, "
				SELECT `stc_merchant_regular_payment_amount` FROM `stc_merchant_regular_payment` 
				INNER JOIN `stc_product_grn` 
				ON `stc_product_grn_id`=`stc_merchant_regular_payment_grn_number` 
				WHERE `stc_product_grn_purchase_order_id`='".$out_grnData['stc_product_grn_purchase_order_id']."'
			");

			foreach($advancepaid_check_loki as $aprow){
				$advance_fetch+=$aprow['stc_merchant_advance_payment_advance_value'];
			}

			foreach($regularpaid_check_loki as $rprow){
				$regular_fetch+=$rprow['stc_merchant_regular_payment_amount'];
			}
			$total_due_amount = $totalincgst - ($advance_fetch - $regular_fetch);

			$odin=array(
				'invo_date' => $out_grnData['stc_product_grn_invoice_date'],
				'grn_number' => 'GRN/'.substr("0000{$out_grnData['stc_product_grn_id']}", -$str_length),
				'hidden_grn_number' => $out_grnData['stc_product_grn_id'],
				'grn_date' => $out_grnData['stc_product_grn_date'],
				'po_number' => 'STC/'.substr("0000{$out_grnData['stc_product_grn_purchase_order_id']}", -$str_length),
				'po_date' => $out_grnData['stc_product_grn_purchase_order_date'],
				'due_gst_amount' => number_format($totalincgst, 3),
				'paid_amount' => number_format($advance_fetch + $regular_fetch, 3),
				'due_amount' => number_format($total_due_amount, 3)
			);
		}else{
			$odin="No record found!!!";
		}
		return $odin;
	}
}

// add regular [payment] class
class ragnarRegularPaymentAdd extends tesseract{
	// go to regular payment table
	public function stc_regular_payment($grn_number, $payment_type,	$refr_number, $payment_amount, $regularpayment_notes){
	    $odin='';
		$date=date("Y-m-d H:i:s");
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_merchant_regular_payment` 
			WHERE `stc_merchant_regular_payment_grn_number`='".mysqli_real_escape_string($this->stc_dbs,$grn_number)."'
			AND `stc_merchant_regular_payment_amount`='".mysqli_real_escape_string($this->stc_dbs,$payment_amount)."'
		");
		$get_loki=mysqli_num_rows($check_loki);
		if($get_loki == 0){
			$insert_ap_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_merchant_regular_payment`(
					`stc_merchant_regular_payment_date`, 
					`stc_merchant_regular_payment_grn_number`, 
					`stc_merchant_regular_payment_payment_type`, 
					`stc_merchant_regular_payment_refr_number`, 
					`stc_merchant_regular_payment_amount`, 
					`stc_merchant_regular_payment_notes`, 
					`stc_merchant_regular_payment_created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs,$date)."',
					'".mysqli_real_escape_string($this->stc_dbs,$grn_number)."',
					'".mysqli_real_escape_string($this->stc_dbs,$payment_type)."',
					'".mysqli_real_escape_string($this->stc_dbs,$refr_number)."',
					'".mysqli_real_escape_string($this->stc_dbs,$payment_amount)."',
					'".mysqli_real_escape_string($this->stc_dbs,$regularpayment_notes)."',
					'".$_SESSION['stc_empl_id']."'
				)
			");
			if($insert_ap_loki){
				$odin="Regular payment saved!!!";
			}else{
				$odin="Hmmm!!! Something went wrong!! Please try again later!!";
			}
		}else{
			$odin="This payment on this GRN already done!!!";
		}
		return $odin;
	}
}

// view dc payment class
class ragnarDCPaymentView extends tesseract{
	// call Customer on Sale Order payment page
	public function stc_call_dc_choosing_merchant($mer_id){
		$str_length = 5;
		$check_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_sale_product_dc_invo_no`, 
						`stc_sale_product_dc_invo_date` 
					FROM `stc_sale_product` 
					WHERE `stc_sale_product_dc_merchant`='".$mer_id."'
					ORDER BY `stc_sale_product_dc_invo_no` DESC
		");
		$odin='<option selected>Please Select Invoice Number</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Invoice Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_sale_product_dc_invo_no"].'">'.$row["stc_sale_product_dc_invo_no"].'</option>	
				';				
			}			
		}
		return $odin;
	}

	// call dc number choosing customer in dc customer payment
	public function stc_call_dc_number_choosing_merchant($dc_number){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
						`stc_sale_product_dc_invo_date` 
					FROM `stc_sale_product` 
					WHERE `stc_sale_product_dc_invo_no`='".mysqli_real_escape_string($this->stc_dbs, $dc_number)."'
		");
		$do_action=mysqli_fetch_assoc($check_loki);
		$odin=$do_action['stc_sale_product_dc_invo_date'];
		return $odin;
	}

	// call dc amount in dc customer payment
	public function stc_call_dc_amount($dc_number){
		$odin=0;
		$totalval=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
			  `stc_sale_product_dc_items_product_qty`,
			  `stc_sale_product_dc_items_product_rate`,
			  `stc_product_gst`
			FROM `stc_sale_product_dc_items` 
			INNER JOIN `stc_sale_product`
			ON `stc_sale_product_dc_items_sale_product_id`=`stc_sale_product_id`
			INNER JOIN `stc_product`
			ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
			INNER JOIN `stc_sub_category`
			ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
			WHERE `stc_sale_product_dc_invo_no`='".$dc_number."'
		");
		$do_action=mysqli_num_rows($check_loki);
		if($do_action>0){
			foreach($check_loki as $crow){
				$amount=round($crow['stc_sale_product_dc_items_product_qty'], 2) * round($crow['stc_sale_product_dc_items_product_rate'], 2);
				$gstamount = (round($amount, 2) * $crow["stc_product_gst"])/100;
				$totalval+= $gstamount+$amount;
			}
			$getlokipayment=mysqli_query($this->stc_dbs, "
				SELECT `stc_sale_product_dc_payment_value` FROM `stc_sale_product_dc_payment` 
				INNER JOIN `stc_sale_product` 
				ON `stc_sale_product_dc_payment_order_id`=`stc_sale_product_id`
				WHERE `stc_sale_product_dc_invo_no`='".$dc_number."'
			");
			$resultancy=0;
			$getvalue=mysqli_fetch_assoc($getlokipayment);
			foreach($getlokipayment as $resultancyr){
				$resultancy+=$resultancyr['stc_sale_product_dc_payment_value'];
			}
			$odin=$resultancy - $totalval;
		}else{
			$odin="
				No records found!!!
			";
		}
		return $odin;
	}
}

// add dc payment class
class ragnarDCPaymentAdd extends tesseract{

	// go to dc invoice payment table
	public function stc_go_dc_merchant_table($mer, $invo, $type, $value, $notes){
        $odin='';
		$date=date("Y-m-d H:i:s");
		$getorderid=mysqli_query($this->stc_dbs, "
			SELECT `stc_sale_product_id` FROM `stc_sale_product`
			WHERE `stc_sale_product_dc_invo_no`='".$invo."'
		");
		$gotorder_id=mysqli_fetch_assoc($getorderid);

		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_dc_payment` 
			WHERE `stc_sale_product_dc_payment_order_id`='".$gotorder_id['stc_sale_product_id']."'
			AND `stc_sale_product_dc_payment_value`='".$value."'
		");
		$get_loki=mysqli_num_rows($check_loki);
		if($get_loki > 0){
			$odin="Hey you!!! DC invoice payment on this sale order already done.";
		}else{
			$insert_ap_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_sale_product_dc_payment`(
					`stc_sale_product_dc_payment_date`, 
					`stc_sale_product_dc_payment_order_id`, 
					`stc_sale_product_dc_payment_mer_id`, 
					`stc_sale_product_dc_payment_pay_type`, 
					`stc_sale_product_dc_payment_value`, 
					`stc_sale_product_dc_payment_notes`, 
					`stc_sale_product_dc_payment_created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs,$date)."',
					'".$gotorder_id['stc_sale_product_id']."',
					'".mysqli_real_escape_string($this->stc_dbs,$mer)."',
					'".mysqli_real_escape_string($this->stc_dbs,$type)."',
					'".mysqli_real_escape_string($this->stc_dbs,$value)."',
					'".mysqli_real_escape_string($this->stc_dbs,$notes)."',
					'".$_SESSION['stc_empl_id']."'
				)
			");
			if($insert_ap_loki){
				$odin="Booyeah!!! DC invoice payment saved.";
			}else{
				$odin="Hmmm!!! Something went wrong!! Please try again later!!";
			}
		}
		return $odin;
	}
}

// view adjust invoice customer payments
class ragnarAdjustInvoicePaymentView extends tesseract{

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

	// call Customer on Sale Order payment page
	public function stc_call_so_choosing_customer($cust_id){
		$str_length = 5;
		$check_loki=mysqli_query($this->stc_dbs, "
					SELECT DISTINCT `stc_sale_product_bill_id` FROM `stc_sale_product_bill`
					INNER JOIN `stc_sale_product_bill_no`
					ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
					INNER JOIN `stc_sale_product` 
					ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
					WHERE `stc_sale_product_cust_id`='".$cust_id."'
					ORDER BY `stc_sale_product_bill_id` DESC
		");
		$odin='<option selected>Please Select Purchase Order Number</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Purchase Ordrer Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_sale_product_bill_id"].'">'.'STC/'.substr("0000{$row["stc_sale_product_bill_id"]}", -$str_length).'/20-21</option>		               	
		            	';				
			}			
		}
		return $odin;
	}

	// call so number choosing customer in so customer payment
	public function stc_call_so_number_choosing_customer($so_number){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_sale_product_bill_date` FROM `stc_sale_product_bill` WHERE `stc_sale_product_bill_id`='".$so_number."'
		");
		$do_action=mysqli_fetch_assoc($check_loki);
		$odin=$do_action['stc_sale_product_bill_date'];
		return $odin;
	}

	// call so amount in so customer payment
	public function stc_call_so_amount($so_number){
		$odin=0;
		$value=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_sale_product_dc_keys` FROM `stc_sale_product_bill`
			INNER JOIN `stc_sale_product_bill_no`
			ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
			INNER JOIN `stc_sale_product` 
			ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
			WHERE `stc_sale_product_bill_id`='".$so_number."'
			ORDER BY `stc_sale_product_bill_id` DESC
		");
		$do_action=mysqli_fetch_assoc($check_loki);
		if(empty($do_action['stc_sale_product_dc_keys'])){			
			$nestedquery=mysqli_query($this->stc_dbs, "
						SELECT 
                          `stc_sale_product_items_product_qty`,
                          `stc_sale_product_items_product_sale_rate`,
                          `stc_product_gst`
                        FROM `stc_sale_product_items` 
                        INNER JOIN `stc_sale_product`
                        ON `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
                        INNER JOIN `stc_product`
                        ON `stc_sale_product_items_product_id`=`stc_product_id`
                        INNER JOIN `stc_sub_category`
                        ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
                        INNER JOIN `stc_sale_product_bill_no`
                        ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
                        INNER JOIN `stc_sale_product_bill`
                        ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_bill_id`
                        WHERE `stc_sale_product_bill_id`='".$so_number."'
			");
			foreach($nestedquery as $crow){
				$amount=round($crow['stc_sale_product_items_product_qty'], 2) * round($crow['stc_sale_product_items_product_sale_rate'], 2);
				$gstamount = (round($amount, 2) * $crow["stc_product_gst"])/100;
				$value+= $gstamount+$amount;
			}
		}else{
			$nestedquery=mysqli_query($this->stc_dbs, "
						SELECT 
                          `stc_sale_product_dc_items_product_qty`,
                          `stc_sale_product_dc_items_product_sale_rate`,
                          `stc_product_gst`
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
                        WHERE `stc_sale_product_bill_id`='".$so_number."'
			");
			foreach($nestedquery as $dcrow){
				$amount=round($dcrow['stc_sale_product_dc_items_product_qty'], 2) * round($dcrow['stc_sale_product_dc_items_product_sale_rate'], 2);
				$gstamount = (round($amount, 2) * $dcrow["stc_product_gst"])/100;
				$value+= $gstamount+$amount;
			}
		}
		$getquery=mysqli_query($this->stc_dbs, "
			SELECT 
				SUM(`stc_sale_product_payment_value`) 
				AS outvalue 
			FROM `stc_sale_product_payment` 
			WHERE `stc_sale_product_payment_order_id`='".$so_number."'
		");
		$fetcrec=mysqli_fetch_assoc($getquery);
		$odin=$value - $fetcrec['outvalue'];
		return $odin;
	}

	// filter direct challan
	public function stc_getadjustpayment_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustomerid, $bjornefilterinvoicenumber){
		$ivar='';
			
		$array = array(
			"bycustomer" => $bjornefiltercustomerid,
			"byinvoicenumber" => $bjornefilterinvoicenumber
		);

		$customer='';
		$invoiceno='';
		foreach($array as $key => $value){
			if($array['bycustomer']!="NA"){
				$customer="
					AND `stc_customer_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bycustomer'])."'
				";
			}

			if(!empty($array['byinvoicenumber'])){
				$invoiceno="
					AND `stc_sale_product_bill_id`='".mysqli_real_escape_string($this->stc_dbs, $array['byinvoicenumber'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC LIMIT 0,20';
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_bill_id`, 
				`stc_customer_name`
			FROM `stc_sale_product_bill` 
			INNER JOIN `stc_sale_product_bill_no`
			ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
			INNER JOIN `stc_sale_product`
			ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
			INNER JOIN `stc_customer`
			ON `stc_sale_product_cust_id`=`stc_customer_id`
			INNER JOIN `stc_sale_product_payment`
			ON `stc_sale_product_payment_order_id`=`stc_sale_product_bill_id`
			WHERE (
				`stc_sale_product_date`
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterbegdate)."'
				AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterenddate)."'
			)".$customer.$invoiceno.$endfilterqry
		);
		foreach($ivarfilterquery as $filterrow){
			$str_length = 5;
			$check_loki=mysqli_query($this->stc_dbs, "
				SELECT DISTINCT 
					`stc_sale_product_bill_id`, 
					`stc_sale_product_bill_date`, 
					`stc_customer_name`
				FROM `stc_sale_product_bill` 
				INNER JOIN `stc_sale_product_bill_no`
				ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
				INNER JOIN `stc_sale_product`
				ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
				INNER JOIN `stc_customer`
				ON `stc_sale_product_cust_id`=`stc_customer_id` 
				WHERE `stc_sale_product_bill_id`='".$filterrow['stc_sale_product_bill_id']."'
				ORDER BY `stc_sale_product_bill_id` DESC			
			");
			$do_action=mysqli_num_rows($check_loki);
			if($do_action == 0){
				$ivar = "
					<tr>
						<td>
							No record found!!!
						</td>
					</tr>
				";
			}else{
				foreach ($check_loki as $row) {
					$cust='';
					$pno='';
					$pdate='';
					$pcat='';
					$ptype='';
					$pvalue='';
					$notes='';
					$nested_query_loki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_customer_name`, 
							`stc_sale_product_payment_id`,
							`stc_sale_product_payment_date`, 
							`stc_sale_product_payment_order_id`, 
							`stc_sale_product_payment_cat_type`, 
							`stc_sale_product_payment_pay_type`, 
							`stc_sale_product_payment_value`, 
							`stc_sale_product_payment_notes` 
						FROM `stc_sale_product_payment`
						INNER JOIN `stc_customer` 
						ON `stc_sale_product_payment_cust_id`=`stc_customer_id`
						WHERE `stc_sale_product_payment_order_id`='".$row['stc_sale_product_bill_id']."'
						ORDER BY `stc_sale_product_payment_order_id` DESC
					");

					foreach($nested_query_loki as $nest_row){
						$pno.=$nest_row["stc_sale_product_payment_id"];
						$pdate.=date('d-m-Y',strtotime($nest_row["stc_sale_product_payment_date"]));
						$pcat.=$nest_row["stc_sale_product_payment_cat_type"];
						$ptype.=$nest_row["stc_sale_product_payment_pay_type"];
						$pvalue.=$nest_row["stc_sale_product_payment_value"];
						$notes.=$nest_row["stc_sale_product_payment_notes"];
					}

					$ivar.='
						<tr>
			       			<td>
			       			  <h6>'.$row['stc_customer_name'].'</h6>
			       			</td>
			       			<td>
			       			  <h6>STC/'.substr("0000{$row['stc_sale_product_bill_id']}", -$str_length).'/20-21</h6>
			       			  '.date('d-m-Y',strtotime($row["stc_sale_product_bill_date"])).'
			       			</td>
			       			<td>
			       			  '.$pno.'<br>
			       			  '.$pdate.'
			       			</td>
			       			<td>
			       			  '.$pcat.'
			       			</td>
			       			<td>
			       			  '.$ptype.'
			       			</td>
			       			<td align="right">
			       			   '.$pvalue.'
			       			</td>
			       			<td>
			       			   '.$notes.'
			       			</td>
			        	</tr>	               	
			        ';				
				}			
			}
		}
		return $ivar;
	}
}

// add adjust invoice customer invoice payments
class ragnarAdjustInvoicePaymentAdd extends tesseract{
	// go to customer payment table
	public function stc_go_customer_table($cust, $invo, $cattype, $type, $value, $notes){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_payment` 
			WHERE `stc_sale_product_payment_order_id`='".$invo."'
			AND `stc_sale_product_payment_value`='".$value."' 
			");
		$get_loki=mysqli_num_rows($check_loki);
		if($get_loki == 0){
			$insert_ap_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_sale_product_payment`(
					`stc_sale_product_payment_date`, 
					`stc_sale_product_payment_order_id`, 
					`stc_sale_product_payment_cust_id`, 
					`stc_sale_product_payment_cat_type`, 
					`stc_sale_product_payment_pay_type`, 
					`stc_sale_product_payment_value`, 
					`stc_sale_product_payment_notes`,
					`stc_sale_product_payment_created_by`
				) VALUES (
					'".$date."',
					'".$invo."',
					'".mysqli_real_escape_string($this->stc_dbs,$cust)."',
					'".mysqli_real_escape_string($this->stc_dbs,$cattype)."',
					'".mysqli_real_escape_string($this->stc_dbs,$type)."',
					'".mysqli_real_escape_string($this->stc_dbs,$value)."',
					'".mysqli_real_escape_string($this->stc_dbs,$notes)."',
					'".$_SESSION['stc_empl_id']."'
				)
			");
			if($insert_ap_loki){
				$odin="Booyeah!!! Customer payment saved.";
			}else{
				$odin="Hmmm!!! Something went wrong!! Please try again later!!";
			}
		}else{
			$odin="Hey you!!! Customer payment on this sale order already done!!!";
		}
		return $odin;
	}
}
#<------------------------------------------------------------------------------------------------------>
#<--------------------------------------Object sections of Payment class-------------------------------->
#<------------------------------------------------------------------------------------------------------>
// show vendor on purhcase order page
if(isset($_POST['friday_vendor'])){
	$objloki=new ragnarPaymentView();
	$objlokiout=$objloki->stc_call_vendor();
	$date=date('l jS \ F Y h:i:s A');
	$outarray=array(
		'time' => $date,
		'vendor' => $objlokiout
	);
	echo json_encode($outarray);
}

// search by same
if(isset($_POST['stcpurchaseaction'])){
	@$bjornebegval=$_POST['phpbegvalue'];
	@$bjorneendval=$_POST['phpendvalue'];
	$bjornefilterbegdate=$_POST['phpbegdate'];
	$bjornefilterenddate=$_POST['phpenddate'];
	$bjornefiltermerid=$_POST['phpmerchantid'];
	$bjornefilterpono=$_POST['phpponumber'];
	$bjornefilterstatus=$_POST['phpstatus'];
	$out='';
	if(empty($bjornebegval) || empty($bjorneendval)){
		$bjornebegval=0;
		$bjorneendval=20;
	}
	$objpdres=new ragnarPaymentView();	
	$opobjpdres=$objpdres->stc_getpo_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltermerid, $bjornefilterpono, $bjornefilterstatus, $bjornebegval, $bjorneendval);
	$out=$opobjpdres;
	echo $out;
}

#<-----------------------------------Object sections of Advance Payment class--------------------------->
// call po number on choose merchant
if(isset($_POST['call_po_on_choose_merchant'])){
	$merchant_id=$_POST['merchant_id'];
	$objloki=new ragnarAdvancePaymentView();
	$objlokiout=$objloki->stc_call_po_choosing_vendor($merchant_id);
	echo json_encode($objlokiout);
}

// call po date & amount on choose po
if(isset($_POST['call_po_date_on_choose_merchant'])){
	$po_number=$_POST['po_number'];
	$objloki=new ragnarAdvancePaymentView();
	$objloki2=new ragnarAdvancePaymentView();
	$objlokiout=$objloki->stc_call_po_number_choosing_merchant($po_number);
	$objloki2out=$objloki2->stc_call_po_amount($po_number);
	$outobjdata= array(
		'order_date' => $objlokiout,
		'due_amount' => $objloki2out
	);
	echo json_encode($outobjdata);
	// echo $outobjdata;
}

// add advance payment to table
if(isset($_POST['stcapsave'])){
	$merchant=$_POST['apmerchantshow'];
	$apponumbershow=$_POST['apponumbershow'];
	$stcapinvonumber=$_POST['stcapinvonumber'];
	$stcapinvodate=$_POST['stcapinvodate'];
	$stcappaymenttype=$_POST['stcappaymenttype'];
	$stcaprefnumber=$_POST['stcaprefnumber'];
	$stcapamount=$_POST['stcapamount'];
	$stcapchargesamount=$_POST['stcapchargesamount'];
	$stcapchargesgst=$_POST['stcapchargesgst'];
	$stcaptandc=$_POST['stcaptandc'];

	if($apponumbershow=="NA" || $merchant=="NA" || empty($stcapamount)){
		echo "All fields are needed!!!";
	}else{
		$objloki=new ragnarAdvancePaymentAdd();
		$objlokiout=$objloki->stc_advance_payment(
			$merchant, 
			$apponumbershow, 
			$stcapinvonumber, 
			$stcapinvodate, 
			$stcappaymenttype, 
			$stcaprefnumber, 
			$stcapamount, 
			$stcapchargesamount,
			$stcapchargesgst,
			$stcaptandc
		);
		echo $objlokiout;
	}
}

#<----------------------------Object sections of Regular Payment class--------------------------------->
// call invoice on choose of merchant
if(isset($_POST['call_invo_on_choose_merchant'])){
	$merchant_id=$_POST['merchant_id'];
	$objloki=new ragnarRegularPaymentView();
	$objlokiout=$objloki->stc_call_invo_choosing_merchant($merchant_id);
	echo json_encode($objlokiout);
}

// call rest with respect to invoice no & merchant id
if(isset($_POST['call_rec_for_reg_pay'])){
	$invo_number=$_POST['invo_number'];
	$merchant_id=$_POST['merchant_id'];
	$objloki=new ragnarRegularPaymentView();
	$objlokiout=$objloki->stc_call_rec_for_reg_pay($invo_number, $merchant_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// add regular payment to table
if(isset($_POST['stcsaverp'])){
	$grn_number=$_POST['stcrp_grnnumber'];
	$payment_type=$_POST['stcrp_paymenttype'];
	$refr_number=$_POST['stcrp_refrno'];
	$payment_amount=$_POST['stcrp_amount'];
	$regularpayment_notes=$_POST['stcrp_notes'];

	if(empty($grn_number) || $payment_type == "NA" || empty($payment_amount)){
		echo "All fields are needed!!!";
	}else{
		$objloki=new ragnarRegularPaymentAdd();
		$objlokiout=$objloki->stc_regular_payment(
			$grn_number, 
			$payment_type, 
			$refr_number, 
			$payment_amount, 
			$regularpayment_notes
		);
		echo $objlokiout;
	}
}

#<----------------------------Object sections of Dc Merchant Payment class---------------------------->

// call dc details on choosing merchants
if(isset($_POST['call_dc_on_choose_mer'])){
	$mer_id=$_POST['dc_merchant_id'];
	$objloki=new ragnarDCPaymentView();
	$objlokiout=$objloki->stc_call_dc_choosing_merchant($mer_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call date and amount customer so
if(isset($_POST['call_dc_date_on_choose_merchant'])){
	$dc_number=$_POST['dc_number'];
	$objloki=new ragnarDCPaymentView();
	$objloki2=new ragnarDCPaymentView();
	$objlokiout=$objloki->stc_call_dc_number_choosing_merchant($dc_number);
	$objloki2out=$objloki2->stc_call_dc_amount($dc_number);
	$outobjdata= array(
		'order_date' => $objlokiout,
		'due_amount' => round($objloki2out, 2)
	);
	echo json_encode($outobjdata);
	// echo $outobjdata;
}

// add dc payment to table
if(isset($_POST['on_dc_mer_pay_hit'])){
	$mer=$_POST['mer'];
	$invo=$_POST['invo'];
	$type=$_POST['type'];
	$value=$_POST['value'];
	$notes=$_POST['notes'];

	$objloki=new ragnarDCPaymentAdd();
	$objlokiout=$objloki->stc_go_dc_merchant_table($mer, $invo, $type, $value, $notes);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

#<----------------------------Object sections of Regular Tax Invoice Payment class---------------------------->
// show vendor on quotation page
if(isset($_POST['friday_customer'])){
	$objloki=new ragnarAdjustInvoicePaymentView();
	$objlokiout=$objloki->stc_call_customer();
	$date=date('l jS \ F Y h:i:s A');
	$outarray=array(
		'time' => $date,
		'customer' => $objlokiout
	);
	echo json_encode($outarray);
}

// show Customer on Sale order page
if(isset($_POST['call_so_on_choose_cust'])){
	$cust_id=$_POST['customer_id'];
	$objloki=new ragnarAdjustInvoicePaymentView();
	$objlokiout=$objloki->stc_call_so_choosing_customer($cust_id);
	echo json_encode($objlokiout);
}

// call date and amount customer so
if(isset($_POST['call_so_date_on_choose_customer'])){
	$so_number=$_POST['so_number'];
	$objloki=new ragnarAdjustInvoicePaymentView();
	$objloki2=new ragnarAdjustInvoicePaymentView();
	$objlokiout=$objloki->stc_call_so_number_choosing_customer($so_number);
	$objloki2out=$objloki2->stc_call_so_amount($so_number);
	$outobjdata= array(
		'order_date' => $objlokiout,
		'due_amount' => round($objloki2out, 2)
	);
	echo json_encode($outobjdata);
	// echo $outobjdata;
}

if(isset($_POST['on_cust_pay_hit'])){
	$cust=$_POST['cust'];
	$invo=$_POST['invo'];
	$cattype=$_POST['cattype'];
	$type=$_POST['type'];
	$value=$_POST['value'];
	$notes=$_POST['notes'];

	$objloki=new ragnarAdjustInvoicePaymentAdd();
	$objloki2=new ragnarAdjustInvoicePaymentAdd();
	$objlokiout=$objloki->stc_go_customer_table($cust, $invo, $cattype, $type, $value, $notes);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// search by same
if(isset($_POST['stcadjustinvoaction'])){
	$bjornefilterbegdate=$_POST['phpbegdate'];
	$bjornefilterenddate=$_POST['phpenddate'];
	$bjornefiltercustomerid=$_POST['phpcustomerid'];
	$bjornefilterinvoicenumber=$_POST['phpinvoicenumber'];
	$out='';
	$objpdres=new ragnarAdjustInvoicePaymentView();	
	$opobjpdres=$objpdres->stc_getadjustpayment_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustomerid, $bjornefilterinvoicenumber);
	$out=$opobjpdres;
	echo $out;
}

#<----------------------------Object sections of Regular Tax Invoice Payment class---------------------------->

?>