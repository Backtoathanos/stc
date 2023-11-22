<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Purchase Order --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class soulstone extends tesseract{	

	// call Customer on Sale Order page
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

	// call po number choosing vendor in advance payment
	public function stc_call_po_number_choosing_merchant($po_number){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_purchase_product` WHERE `stc_purchase_product_id`='".$po_number."'");
		$do_action=mysqli_fetch_assoc($check_loki);
		$odin=$do_action['stc_purchase_product_order_date'];
		return $odin;
	}

	// call po mount in advance payment
	public function stc_call_po_amount($po_number){
		$odin='';
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

	// go to advance payment table
	public function stc_advance_payment($merchant, $apponumbershow, $stcapinvonumber, $stcapinvodate, $stcappaymenttype, $stcaprefnumber, $stcapamount, $stcapchargesamount, $stcapchargesgst, $stcaptandc){
		$odin='';
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
					NOW(),
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
					'".$_SESSION['stc_admin_info_id']."'
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
				SELECT * FROM `stc_merchant_advance_payment` WHERE `stc_merchant_advance_payment_purhcase_product_id`='".$out_grnData['stc_product_grn_purchase_order_id']."'
			");
			$advance_fetch=0;
			$regular_fetch=0;
			// $advance_fetch = mysqli_fetch_assoc($advancepaid_check_loki);

			$regularpaid_check_loki=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_merchant_regular_payment` WHERE `stc_merchant_regular_payment_grn_number`='".$out_grnData['stc_product_grn_id']."'
			");
			// $regular_fetch = mysqli_fetch_assoc($regularpaid_check_loki);

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
				'due_basic_amount' => number_format($basicamount, 3),
				'due_gst_amount' => number_format($totalincgst, 3),
				'paid_amount' => number_format($advance_fetch + $regular_fetch, 3),
				'due_amount' => number_format($total_due_amount, 3)
			);
		}else{
			$odin="No record found!!!";
		}
		return $odin;
	}

	// go to regular payment table
	public function stc_regular_payment($grn_number, $payment_type,	$refr_number, $payment_amount, $regularpayment_notes){
	    $odin='';
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
					NOW(),
					'".mysqli_real_escape_string($this->stc_dbs,$grn_number)."',
					'".mysqli_real_escape_string($this->stc_dbs,$payment_type)."',
					'".mysqli_real_escape_string($this->stc_dbs,$refr_number)."',
					'".mysqli_real_escape_string($this->stc_dbs,$payment_amount)."',
					'".mysqli_real_escape_string($this->stc_dbs,$regularpayment_notes)."',
					'".$_SESSION['stc_admin_info_id']."'
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

	#------------------------------------Call Payment----------------------------------------------------#
	// advance payment call
	public function stc_call_adv_pay(){
		$odin='';
		$str_length = 5;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_merchant_advance_payment` 
			LEFT JOIN `stc_merchant` 
			ON `stc_merchant_advance_payment_merchant_id`=`stc_merchant_id` 
            ORDER BY `stc_merchant_advance_payment`.`stc_merchant_advance_payment_id` 
            DESC
		");
		$odin .= '  
			<table class="table table-hover" align="centre">
				<thead>
				  	<tr>
	            	  <th scope="col">Advance Payment No.</th>
	            	  <th scope="col">Advance Payment Date</th>
	            	  <th scope="col" style="width: 20%;">Merchant Name</th>
	            	  <th scope="col">PO No.</th> 
	            	  <th scope="col">PI No.</th>
	            	  <th scope="col">PI Date</th>
	            	  <th scope="col">Advance Payment Refr No.</th>
	            	  <th scope="col">Advance Payment Type</th>
	            	  <th scope="col">Advance Payment Value</th>
	            	  <th scope="col">Advance Payment Notes</th>
	            	</tr>
				</thead>
				<tbody>
		';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "

				";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
					<tr>
		       			<td>
		       			  <h6>AP/'.substr("0000{$row["stc_merchant_advance_payment_id"]}", -$str_length).'</h6>
		       			</td>
						<td>
		       			  <p>'.date('d-m-Y',strtotime($row["stc_merchant_advance_payment_date"])).'</p>
		       			</td>
		       			<td>
		       			  <p>'.$row["stc_merchant_name"].'</p>
		       			</td>
		       			<td>
		       			  <p>STC/'.substr("0000{$row["stc_merchant_advance_payment_purhcase_product_id"]}", -$str_length).'</p>
		       			</td>
		       			<td>
		       			  <p>'.$row["stc_merchant_advance_payment_pi_number"].'</p>
		       			</td>
		       			<td>
		       			  <p>'.$row["stc_merchant_advance_payment_pi_date"].'</p>
		       			</td>
		       			<td>
		       			  <p>'.$row["stc_merchant_advance_payment_refr_number"].'</p>
		       			</td>
		       			<td>
		       			  <p>'.$row["stc_merchant_advance_payment_payment_type"].'</p>
		       			</td>
		       			<td>
		       			  <p>'.number_format($row["stc_merchant_advance_payment_advance_value"], 2).'</p>
		       			</td>
		       			<td>
		       			  <p>'.$row["stc_merchant_advance_payment_notes"].'</p>
		       			</td>
		        	</tr>	               	
		        ';				
			}			
		}
		$odin .= '
				
		  </tbody>
		</table>
		'; 
		return $odin;
	}

	// call regular payments
	public function stc_call_reg_pay(){
		$odin='';
		$str_length = 5;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_merchant_regular_payment` 
			LEFT JOIN `stc_product_grn` 
			ON `stc_merchant_regular_payment_grn_number`=`stc_product_grn_id`
            LEFT JOIN `stc_merchant` 
			ON `stc_product_grn_merchant_id`=`stc_merchant_id`
			ORDER BY `stc_merchant_regular_payment`.`stc_merchant_regular_payment_id` 
            DESC
		");
		$odin .= '  
			<table class="table table-hover" align="centre">
				<thead>
				  	<tr>
	            	  <th scope="col">Regular Payment No.</th>
	            	  <th scope="col">Regular Payment Date</th>
	            	  <th scope="col" style="width: 20%;">Merchant Name</th>
	            	  <th scope="col">PO No.</th> 
	            	  <th scope="col">GRN No.</th>
	            	  <th scope="col">GRN Date</th>
	            	  <th scope="col">Regular Payment Refr No.</th>
	            	  <th scope="col">Regular Payment Type</th>
	            	  <th scope="col">Regular Payment Value</th>
	            	  <th scope="col">Regular Payment Notes</th>
	            	</tr>
				</thead>
				<tbody>
		';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "

				";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
					<tr>
		       			<td>
		       			  <h6>RP/'.substr("0000{$row["stc_merchant_regular_payment_id"]}", -$str_length).'</h6>
		       			</td>
						<td>
		       			  <p>'.date('d-m-Y',strtotime($row["stc_merchant_regular_payment_date"])).'</p>
		       			</td>
		       			<td>
		       			  <p>'.$row["stc_merchant_name"].'</p>
		       			</td>
		       			<td>
		       			  <p>STC/'.substr("0000{$row["stc_product_grn_purchase_order_id"]}", -$str_length).'</p>
		       			</td>
		       			<td>
		       			  <p>'.$row["stc_product_grn_invoice_number"].'</p>
		       			</td>
		       			<td>
		       			  <p>'.$row["stc_product_grn_invoice_date"].'</p>
		       			</td>
		       			<td>
		       			  <p>'.$row["stc_merchant_regular_payment_refr_number"].'</p>
		       			</td>
		       			<td>
		       			  <p>'.$row["stc_merchant_regular_payment_payment_type"].'</p>
		       			</td>
		       			<td>
		       			  <p>'.number_format($row["stc_merchant_regular_payment_amount"], 2).'</p>
		       			</td>
		       			<td>
		       			  <p>'.$row["stc_merchant_regular_payment_notes"].'</p>
		       			</td>
		        	</tr>	               	
		        ';				
			}			
		}
		$odin .= '
				
		  </tbody>
		</table>
		'; 
		return $odin;
	}

	// call all payments
	public function stc_call_all_pay($popaybegdate, $popayenddate){
		$odin='';
		$str_length = 5;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_purchase_product_id`, `stc_merchant_name` ,`stc_purchase_product_order_date`
			FROM `stc_purchase_product`
			INNER JOIN `stc_merchant`
			ON `stc_purchase_product_merchant_id`=`stc_merchant_id`
			WHERE `stc_purchase_product_order_date`
			BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $popaybegdate)."'
			AND '".mysqli_real_escape_string($this->stc_dbs, $popayenddate)."'
			ORDER BY `stc_purchase_product_id` DESC
			LIMIT 0,20
		");
		$odin .= '  
			<table class="table table-hover" align="centre">
				<thead>
				  	<tr>
	            	  <th scope="col">Merchant Name</th>
	            	  <th scope="col">PO No.</th>
	            	  <th scope="col">PO Date</th>
	            	  <th scope="col">GRN No. <a href="#" id="grn-h-in"><i class="fa fa-arrow-right"></i></a></th>
	            	  <th scope="col" class="grn-h-out">GRN Date</th>
	            	  <th scope="col" class="grn-h-out">Invoice No.</th>
	            	  <th scope="col" class="grn-h-out">Invoice Date</th>
	            	  <th scope="col">AP No. <a href="#" id="ap-h-in"><i class="fa fa-arrow-right"></i></a></th>
	            	  <th scope="col" class="ap-h-out">AP Date</th>
	            	  <th scope="col" class="ap-h-out">AP PI No.</th>
	            	  <th scope="col" class="ap-h-out">AP PI Date</th>
	            	  <th scope="col" class="ap-h-out">AP Value</th>
	            	  <th scope="col">RP No. <a href="#" id="rp-h-in"><i class="fa fa-arrow-right"></i></a></th>
	            	  <th scope="col" class="rp-h-out">RP Date</th>
	            	  <th scope="col" class="rp-h-out">RP Value</th>
	            	  <th scope="col">Invoice Basic Amount</th>
	            	  <th scope="col">Invoice Total Amount</th>
	            	  <th scope="col">PO Value</th>
	            	  <th scope="col">Paid Amount</th>
	            	  <th scope="col">Balance Amount</th>
	            	</tr>
				</thead>
				<tbody class="growit">
		';
		$allcount_query = "SELECT count(`stc_purchase_product_id`) as allcount FROM stc_purchase_product";
		$allcount_result = mysqli_query($this->stc_dbs, $allcount_query);
		$allcount_fetch = mysqli_fetch_array($allcount_result);
		$allcount = $allcount_fetch['allcount'];
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "
				<tr>
		       		<td colspan='9'>
		       		  <h6 align='center'>No Records Found!!!</h6>
		       		</td>
		       	</tr>
				";
		}else{
			foreach ($check_loki as $row) {
				$grnno='';
				$grndate='';
				$invoiceno='';
				$invoicedate='';
				$invoamt='';
				$invototamt='';
				$poamt='';
				$progst='';
				$rpfvalue=0;
				$apfvalue=0;
				$rpno='';
				$rpdate='';
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
					$grnno.='<p>GRN/'.substr("0000{$grnrow["stc_product_grn_id"]}", -$str_length).'</p>';
					$grndate.='<p>'.date('d-m-Y',strtotime($grnrow["stc_product_grn_date"])).'</p>';
					$invoiceno.='<p>'.$grnrow["stc_product_grn_invoice_number"].'</p>';
					$invoicedate.='<p>'.date('d-m-Y',strtotime($grnrow["stc_product_grn_invoice_date"])).'</p>';

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
						$rpno.='<p>RP/'.substr("0000{$rprow["stc_merchant_regular_payment_id"]}", -$str_length).'</p>';
						$rpdate.='<p>'.date('d-m-Y',strtotime($rprow["stc_merchant_regular_payment_date"])).'</p>';
						$rpvalue.='<p>'.number_format($rprow["stc_merchant_regular_payment_amount"], 2).'</p>';
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

				$apno='';
				$apdate='';
				$pino='';
				$pidate='';
				$apvalue='';
				$chargesamount=0;
				foreach($callap_loki as $aprow){
					$apno.='<p>AP/'.substr("0000{$aprow["stc_merchant_advance_payment_id"]}", -$str_length).'</p>';
					$apdate.='<p>'.date('d-m-Y',strtotime($aprow["stc_merchant_advance_payment_date"])).'</p>';
					$pino.='<p>'.$aprow["stc_merchant_advance_payment_pi_number"].'</p>';
					$pidate.='<p>'.date('d-m-Y',strtotime($aprow["stc_merchant_advance_payment_pi_date"])).'</p>';
					$apvalue.='<p>'.number_format($aprow["stc_merchant_advance_payment_advance_value"], 2).'</p>';
					$rpfvalue += $aprow["stc_merchant_advance_payment_advance_value"];
					$chargesamount += $aprow["stc_merchant_advance_payment_charges"] + ($aprow["stc_merchant_advance_payment_charges"] * $aprow["stc_merchant_advance_payment_charges_tax"]/100);
				}

				$callpovalue_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_purchase_product_items_qty` * `stc_purchase_product_items_rate`) AS `stc_total_po`,
						`stc_product_gst`
					FROM `stc_purchase_product_items`
					INNER JOIN `stc_product`
					ON `stc_purchase_product_items_product_id`=`stc_product_id`
					WHERE `stc_purchase_product_items_order_id`='".$row["stc_purchase_product_id"]."'
				");	
				$insidepoamt=0;
				foreach ($callpovalue_loki as $amtrow){					
					$ininvototamt = $amtrow['stc_total_po'] + ($amtrow['stc_total_po'] * $amtrow["stc_product_gst"])/100;
					$insidepoamt += $ininvototamt;
				}
				$poamt=number_format($insidepoamt, 2);

				$paidvalue=$rpfvalue + $apfvalue;
				$balanceamt=$insidepoamt - ($paidvalue) + $chargesamount;
				$odin.='
					<tr>
		       			<td>
		       			  <p>'.$row["stc_merchant_name"].'</p>
		       			</td>
		       			<td>
		       			  <h6>STC/'.substr("0000{$row["stc_purchase_product_id"]}", -$str_length).'</h6>
		       			</td>
		       			<td>
		       			  <p>'.date('d-m-Y',strtotime($row["stc_purchase_product_order_date"])).'</p>
		       			</td>
						<td>
							'.$grnno.'
		       			</td>
		       			<td class="grn-h-out">
		       				'.$grndate.'
		       			</td>
		       			<td class="grn-h-out">
		       				'.$invoiceno.'
		       			</td>
		       			<td class="grn-h-out">
		       				'.$invoicedate.'
		       			</td>
		       			<td>
		       				'.$apno.'
		       			</td>
		       			<td class="ap-h-out">
		       				'.$apdate.'
		       			</td>
		       			<td class="ap-h-out">
		       				'.$pino.'
		       			</td>
		       			<td class="ap-h-out">
		       				'.$pidate.'
		       			</td>
		       			<td align="right" class="ap-h-out">
		       				'.$apvalue.'
		       			</td>
		       			<td>
		       				'.$rpno.'
		       			</td>
		       			<td class="rp-h-out">
		       				'.$rpdate.'
		       			</td>
		       			<td align="right" class="rp-h-out">
		       				'.$rpvalue.'
		       			</td>
		       			<td align="right">
		       				'.$invoamt.'
		       			</td>
		       			<td align="right">
		       				'.$invototamt.'
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
		$odin .= '
			  </tbody>
			</table>
			<input type="hidden" id="stc_pay_curr_row" value="0">
        	<input type="hidden" id="stc_pay_all_row" value="'.$allcount.'">
		'; 
		return $odin;
	}

	public function call_orders($row, $rowperpage){
		$odin='';
		$str_length = 5;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_purchase_product_id`, `stc_merchant_name` ,`stc_purchase_product_order_date`
			FROM `stc_purchase_product`
			INNER JOIN `stc_merchant`
			ON `stc_purchase_product_merchant_id`=`stc_merchant_id`
			ORDER BY `stc_purchase_product_id` DESC
			LIMIT ".$row.",".$rowperpage
		);
		$allcount_query = "SELECT count(`stc_purchase_product_id`) as allcount FROM stc_purchase_product";
		$allcount_result = mysqli_query($this->stc_dbs, $allcount_query);
		$allcount_fetch = mysqli_fetch_array($allcount_result);
		$allcount = $allcount_fetch['allcount'];
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "
				<tr>
		       		<td colspan='9'>
		       		  <h6 align='center'>No Records Found!!!</h6>
		       		</td>
		       	</tr>
				";
		}else{
			foreach ($check_loki as $row) {
				$grnno='';
				$grndate='';
				$invoiceno='';
				$invoicedate='';
				$invoamt='';
				$invototamt='';
				$poamt='';
				$progst='';
				$rpfvalue=0;
				$apfvalue=0;
				$rpno='';
				$rpdate='';
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
					$grnno.='<p>GRN/'.substr("0000{$grnrow["stc_product_grn_id"]}", -$str_length).'</p>';
					$grndate.='<p>'.date('d-m-Y',strtotime($grnrow["stc_product_grn_date"])).'</p>';
					$invoiceno.='<p>'.$grnrow["stc_product_grn_invoice_number"].'</p>';
					$invoicedate.='<p>'.date('d-m-Y',strtotime($grnrow["stc_product_grn_invoice_date"])).'</p>';

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
						$rpno.='<p>RP/'.substr("0000{$rprow["stc_merchant_regular_payment_id"]}", -$str_length).'</p>';
						$rpdate.='<p>'.date('d-m-Y',strtotime($rprow["stc_merchant_regular_payment_date"])).'</p>';
						$rpvalue.='<p>'.number_format($rprow["stc_merchant_regular_payment_amount"], 2).'</p>';
						$rpfvalue += $rprow["stc_merchant_regular_payment_amount"];
					}
				}

				$callap_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_merchant_advance_payment_id`, 
						`stc_merchant_advance_payment_date`, 
						`stc_merchant_advance_payment_pi_number`, 
						`stc_merchant_advance_payment_pi_date`, 
						`stc_merchant_advance_payment_advance_value` 
					FROM `stc_merchant_advance_payment` 
					WHERE `stc_merchant_advance_payment_purhcase_product_id`='".$row["stc_purchase_product_id"]."'
				");

				$apno='';
				$apdate='';
				$pino='';
				$pidate='';
				$apvalue='';
				foreach($callap_loki as $aprow){
					$apno.='<p>AP/'.substr("0000{$aprow["stc_merchant_advance_payment_id"]}", -$str_length).'</p>';
					$apdate.='<p>'.date('d-m-Y',strtotime($aprow["stc_merchant_advance_payment_date"])).'</p>';
					$pino.='<p>'.$aprow["stc_merchant_advance_payment_pi_number"].'</p>';
					$pidate.='<p>'.date('d-m-Y',strtotime($aprow["stc_merchant_advance_payment_pi_date"])).'</p>';
					$apvalue.='<p>'.number_format($aprow["stc_merchant_advance_payment_advance_value"], 2).'</p>';
					$rpfvalue += $aprow["stc_merchant_advance_payment_advance_value"];
				}

				$callpovalue_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_purchase_product_items_qty` * `stc_purchase_product_items_rate`) AS `stc_total_po`,
						`stc_product_gst`
					FROM `stc_purchase_product_items`
					INNER JOIN `stc_product`
					ON `stc_purchase_product_items_product_id`=`stc_product_id`
					WHERE `stc_purchase_product_items_order_id`='".$row["stc_purchase_product_id"]."'
				");	
				$insidepoamt=0;
				foreach ($callpovalue_loki as $amtrow){					
					$ininvototamt = $amtrow['stc_total_po'] + ($amtrow['stc_total_po'] * $amtrow["stc_product_gst"])/100;
					$insidepoamt += $ininvototamt;
				}
				$poamt=number_format($insidepoamt, 2);

				$paidvalue=$rpfvalue + $apfvalue;
				$balanceamt=$insidepoamt - ($paidvalue);
				$odin.='
					<tr>
		       			<td>
		       			  <p>'.$row["stc_merchant_name"].'</p>
		       			</td>
		       			<td>
		       			  <h6>STC/'.substr("0000{$row["stc_purchase_product_id"]}", -$str_length).'</h6>
		       			</td>
		       			<td>
		       			  <p>'.date('d-m-Y',strtotime($row["stc_purchase_product_order_date"])).'</p>
		       			</td>
						<td>
							'.$grnno.'
		       			</td>
		       			<td class="grn-h-out">
		       				'.$grndate.'
		       			</td>
		       			<td class="grn-h-out">
		       				'.$invoiceno.'
		       			</td>
		       			<td class="grn-h-out">
		       				'.$invoicedate.'
		       			</td>
		       			<td>
		       				'.$apno.'
		       			</td>
		       			<td class="ap-h-out">
		       				'.$apdate.'
		       			</td>
		       			<td class="ap-h-out">
		       				'.$pino.'
		       			</td>
		       			<td class="ap-h-out">
		       				'.$pidate.'
		       			</td>
		       			<td align="right" class="ap-h-out">
		       				'.$apvalue.'
		       			</td>
		       			<td>
		       				'.$rpno.'
		       			</td>
		       			<td class="rp-h-out">
		       				'.$rpdate.'
		       			</td>
		       			<td align="right" class="rp-h-out">
		       				'.$rpvalue.'
		       			</td>
		       			<td align="right">
		       				'.$invoamt.'
		       			</td>
		       			<td align="right">
		       				'.$invototamt.'
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
		return $odin;
	}

	// filter records
	public function stc_find_records($key_pay, $popaybegdate, $popayenddate){
		$odin='';
		$str_length = 5;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_purchase_product_id`, `stc_merchant_name` ,`stc_purchase_product_order_date`
			FROM `stc_purchase_product`
			INNER JOIN `stc_merchant`
			ON `stc_purchase_product_merchant_id`=`stc_merchant_id`
			WHERE `stc_merchant_name` REGEXP '".$key_pay."' 
			OR `stc_purchase_product_id` REGEXP '".$key_pay."'
			AND (
                `stc_purchase_product_order_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $popaybegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $popayenddate)."'
            )
			ORDER BY `stc_purchase_product_id` DESC
		");
		$odin .= '  
			<table class="table table-hover" align="centre">
				<thead>
				  	<tr>
	            	  <th scope="col">Merchant Name</th>
	            	  <th scope="col">PO No.</th>
	            	  <th scope="col">PO Date</th>
	            	  <th scope="col">GRN No. <a href="#" id="grn-h-in"><i class="fa fa-arrow-right"></i></a></th>
	            	  <th scope="col" class="grn-h-out">GRN Date</th>
	            	  <th scope="col" class="grn-h-out">Invoice No.</th>
	            	  <th scope="col" class="grn-h-out">Invoice Date</th>
	            	  <th scope="col">AP No. <a href="#" id="ap-h-in"><i class="fa fa-arrow-right"></i></a></th>
	            	  <th scope="col" class="ap-h-out">AP Date</th>
	            	  <th scope="col" class="ap-h-out">AP PI No.</th>
	            	  <th scope="col" class="ap-h-out">AP PI Date</th>
	            	  <th scope="col" class="ap-h-out">AP Value</th>
	            	  <th scope="col">RP No. <a href="#" id="rp-h-in"><i class="fa fa-arrow-right"></i></a></th>
	            	  <th scope="col" class="rp-h-out">RP Date</th>
	            	  <th scope="col" class="rp-h-out">RP Value</th>
	            	  <th scope="col">Invoice Basic Amount</th>
	            	  <th scope="col">Invoice Total Amount</th>
	            	  <th scope="col">PO Value</th>
	            	  <th scope="col">Paid Amount</th>
	            	  <th scope="col">Balance Amount</th>
	            	</tr>
				</thead>
				<tbody class="growit">
		';
		$allcount_query = "SELECT count(`stc_purchase_product_id`) as allcount FROM stc_purchase_product";
		$allcount_result = mysqli_query($this->stc_dbs, $allcount_query);
		$allcount_fetch = mysqli_fetch_array($allcount_result);
		$allcount = $allcount_fetch['allcount'];
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = '
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
					<a 
				      href="#" 
				      class="btn btn-primary btn-block text-uppercase mb-3">
				     	No Records Found!!!
				    </a>
				</div>
				';
			$odin .= '
				  </tbody>
				</table>
				<input type="hidden" id="stc_pay_curr_row" value="0">
	        	<input type="hidden" id="stc_pay_all_row" value="'.$allcount.'">
			'; 
		}else{
			foreach ($check_loki as $row) {
				$grnno='';
				$grndate='';
				$invoiceno='';
				$invoicedate='';
				$invoamt='';
				$invototamt='';
				$poamt='';
				$progst='';
				$rpfvalue=0;
				$apfvalue=0;
				$rpno='';
				$rpdate='';
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
					$grnno.='<p>GRN/'.substr("0000{$grnrow["stc_product_grn_id"]}", -$str_length).'</p>';
					$grndate.='<p>'.date('d-m-Y',strtotime($grnrow["stc_product_grn_date"])).'</p>';
					$invoiceno.='<p>'.$grnrow["stc_product_grn_invoice_number"].'</p>';
					$invoicedate.='<p>'.date('d-m-Y',strtotime($grnrow["stc_product_grn_invoice_date"])).'</p>';

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
						$rpno.='<p>RP/'.substr("0000{$rprow["stc_merchant_regular_payment_id"]}", -$str_length).'</p>';
						$rpdate.='<p>'.date('d-m-Y',strtotime($rprow["stc_merchant_regular_payment_date"])).'</p>';
						$rpvalue.='<p>'.number_format($rprow["stc_merchant_regular_payment_amount"], 2).'</p>';
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

				$apno='';
				$apdate='';
				$pino='';
				$pidate='';
				$apvalue='';
				$chargesamount=0;
				foreach($callap_loki as $aprow){
					$apno.='<p>AP/'.substr("0000{$aprow["stc_merchant_advance_payment_id"]}", -$str_length).'</p>';
					$apdate.='<p>'.date('d-m-Y',strtotime($aprow["stc_merchant_advance_payment_date"])).'</p>';
					$pino.='<p>'.$aprow["stc_merchant_advance_payment_pi_number"].'</p>';
					$pidate.='<p>'.date('d-m-Y',strtotime($aprow["stc_merchant_advance_payment_pi_date"])).'</p>';
					$apvalue.='<p>'.number_format($aprow["stc_merchant_advance_payment_advance_value"], 2).'</p>';
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
		       			  <p>'.$row["stc_merchant_name"].'</p>
		       			</td>
		       			<td>
		       			  <h6>STC/'.substr("0000{$row["stc_purchase_product_id"]}", -$str_length).'</h6>
		       			</td>
		       			<td>
		       			  <p>'.date('d-m-Y',strtotime($row["stc_purchase_product_order_date"])).'</p>
		       			</td>
						<td>
							'.$grnno.'
		       			</td>
		       			<td class="grn-h-out">
		       				'.$grndate.'
		       			</td>
		       			<td class="grn-h-out">
		       				'.$invoiceno.'
		       			</td>
		       			<td class="grn-h-out">
		       				'.$invoicedate.'
		       			</td>
		       			<td>
		       				'.$apno.'
		       			</td>
		       			<td class="ap-h-out">
		       				'.$apdate.'
		       			</td>
		       			<td class="ap-h-out">
		       				'.$pino.'
		       			</td>
		       			<td class="ap-h-out">
		       				'.$pidate.'
		       			</td>
		       			<td align="right" class="ap-h-out">
		       				'.$apvalue.'
		       			</td>
		       			<td>
		       				'.$rpno.'
		       			</td>
		       			<td class="rp-h-out">
		       				'.$rpdate.'
		       			</td>
		       			<td align="right" class="rp-h-out">
		       				'.$rpvalue.'
		       			</td>
		       			<td align="right">
		       				'.$invoamt.'
		       			</td>
		       			<td align="right">
		       				'.$invototamt.'
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
		return $odin;
	}
}

class powerstone extends tesseract{	

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

	// go to customer payment table
	public function stc_go_customer_table($cust, $invo, $cattype, $type, $value, $notes){
		$odin='';
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
					NOW(),
					'".$invo."',
					'".mysqli_real_escape_string($this->stc_dbs,$cust)."',
					'".mysqli_real_escape_string($this->stc_dbs,$cattype)."',
					'".mysqli_real_escape_string($this->stc_dbs,$type)."',
					'".mysqli_real_escape_string($this->stc_dbs,$value)."',
					'".mysqli_real_escape_string($this->stc_dbs,$notes)."',
					'".$_SESSION['stc_admin_info_id']."'
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

	public function load_customer_payments(){
		$odin='';
		$str_length = 5;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_sale_product_bill_id`, `stc_sale_product_bill_date`, `stc_customer_name`
			FROM `stc_sale_product_bill` 
			INNER JOIN `stc_sale_product_bill_no`
			ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
			INNER JOIN `stc_sale_product`
			ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
			INNER JOIN `stc_customer`
			ON `stc_sale_product_cust_id`=`stc_customer_id` 
			ORDER BY `stc_sale_product_bill_id` DESC			
		");
		$odin .= '  
			<table class="table table-hover" align="centre">
				<thead>
				  	<tr>
	            	  <th scope="col">Sale Order No.</th>
	            	  <th scope="col">Sale Order Date</th>
	            	  <th scope="col" style="width: 20%;">Customer Name</th>
	            	  <th scope="col">Payment Date.</th> 
	            	  <th scope="col">Payment Category.</th> 
	            	  <th scope="col">Payment Type.</th>
	            	  <th scope="col">Payment Value</th>
	            	  <th scope="col">Notes.</th>
	            	</tr>
				</thead>
				<tbody>
		';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "
				Payment not done!!!
				";
		}else{
			foreach ($check_loki as $row) {
				$cust='';
				$pdate='';
				$pcat='';
				$ptype='';
				$pvalue='';
				$notes='';
				$nested_query_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_customer_name`, 
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
					// $cust.='<p>'.$nest_row["stc_customer_name"].'</p>';
					$pdate.='<p>'.date('d-m-Y',strtotime($nest_row["stc_sale_product_payment_date"])).'</p>';
					$pcat.='<p>'.$nest_row["stc_sale_product_payment_cat_type"].'</p>';
					$ptype.='<p>'.$nest_row["stc_sale_product_payment_pay_type"].'</p>';
					$pvalue.='<p>'.$nest_row["stc_sale_product_payment_value"].'</p>';
					$notes.='<p>'.$nest_row["stc_sale_product_payment_notes"].'</p>';
				}

				$odin.='
					<tr>
		       			<td>
		       			  <h6>STC/'.substr("0000{$row['stc_sale_product_bill_id']}", -$str_length).'/20-21</h6>
		       			</td>
						<td>
		       			  <p>'.date('d-m-Y',strtotime($row["stc_sale_product_bill_date"])).'</p>
		       			</td>
		       			<td>
		       			  <h6>'.$row['stc_customer_name'].'</h6>
		       			</td>
		       			<td>
		       			  '.$pdate.'
		       			</td>
		       			<td>
		       			  '.$pcat.'
		       			</td>
		       			<td>
		       			  '.$ptype.'
		       			</td>
		       			<td>
		       			   '.$pvalue.'
		       			</td>
		       			<td>
		       			   '.$notes.'
		       			</td>
		        	</tr>	               	
		        ';				
			}			
		}
		$odin .= '
				
		  </tbody>
		</table>
		'; 
		return $odin;
	}

	public function stc_filter_cust_payments($key_pay){
		$odin='';
		$find_query_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_sale_product_bill_id`, `stc_customer_name`
			FROM `stc_sale_product_bill` 
			INNER JOIN `stc_sale_product_bill_no`
			ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
			INNER JOIN `stc_sale_product`
			ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
			INNER JOIN `stc_customer`
			ON `stc_sale_product_cust_id`=`stc_customer_id`
			WHERE `stc_sale_product_bill_id` REGEXP '".$key_pay."'
			OR `stc_customer_name` REGEXP '".$key_pay."'
			ORDER BY `stc_sale_product_bill_id` DESC
		");

		$odin .= '  
			<table class="table table-hover" align="centre">
				<thead>
				  	<tr>
		        	  <th scope="col">Sale Order No.</th>
		        	  <th scope="col">Sale Order Date</th>
		        	  <th scope="col" style="width: 20%;">Customer Name</th>
		        	  <th scope="col">Payment Date.</th> 
		        	  <th scope="col">Payment Category.</th> 
		        	  <th scope="col">Payment Type.</th>
		        	  <th scope="col">Payment Value</th>
		        	  <th scope="col">Notes.</th>
		        	</tr>
				</thead>
				<tbody>
		';

		foreach($find_query_loki as $filterrow){
			$str_length = 5;
			$check_loki=mysqli_query($this->stc_dbs, "
				SELECT DISTINCT `stc_sale_product_bill_id`, `stc_sale_product_bill_date`, `stc_customer_name`
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
				$odin = "
					No record found!!!
					";
			}else{
				foreach ($check_loki as $row) {
					$cust='';
					$pdate='';
					$pcat='';
					$ptype='';
					$pvalue='';
					$notes='';
					$nested_query_loki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_customer_name`, 
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
						// $cust.='<p>'.$nest_row["stc_customer_name"].'</p>';
						$pdate.='<p>'.date('d-m-Y',strtotime($nest_row["stc_sale_product_payment_date"])).'</p>';
						$pcat.='<p>'.$nest_row["stc_sale_product_payment_cat_type"].'</p>';
						$ptype.='<p>'.$nest_row["stc_sale_product_payment_pay_type"].'</p>';
						$pvalue.='<p>'.$nest_row["stc_sale_product_payment_value"].'</p>';
						$notes.='<p>'.$nest_row["stc_sale_product_payment_notes"].'</p>';
					}

					$odin.='
						<tr>
			       			<td>
			       			  <h6>STC/'.substr("0000{$row['stc_sale_product_bill_id']}", -$str_length).'/20-21</h6>
			       			</td>
							<td>
			       			  <p>'.date('d-m-Y',strtotime($row["stc_sale_product_bill_date"])).'</p>
			       			</td>
			       			<td>
			       			  <h6>'.$row['stc_customer_name'].'</h6>
			       			</td>
			       			<td>
			       			  '.$pdate.'
			       			</td>
			       			<td>
			       			  '.$pcat.'
			       			</td>
			       			<td>
			       			  '.$ptype.'
			       			</td>
			       			<td>
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

		$odin .= '				
		  </tbody>
		</table>
		'; 
		return $odin;
	}
}

class gauntlet extends tesseract{	

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

	// go to dc invoice payment table
	public function stc_go_dc_merchant_table($mer, $invo, $type, $value, $notes){
        $odin='';
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
					NOW(),
					'".$gotorder_id['stc_sale_product_id']."',
					'".mysqli_real_escape_string($this->stc_dbs,$mer)."',
					'".mysqli_real_escape_string($this->stc_dbs,$type)."',
					'".mysqli_real_escape_string($this->stc_dbs,$value)."',
					'".mysqli_real_escape_string($this->stc_dbs,$notes)."',
					'".$_SESSION['stc_admin_info_id']."'
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

#<------------------------------------------------------------------------------------------>
#<-------------------------------------Purchase Objects------------------------------------->
#<------------------------------------------------------------------------------------------>

// show Customer on Sale order page
if(isset($_POST['call_po_on_choose_merchant'])){
	$merchant_id=$_POST['merchant_id'];
	$objloki=new soulstone();
	$objlokiout=$objloki->stc_call_po_choosing_vendor($merchant_id);
	echo json_encode($objlokiout);
}

if(isset($_POST['call_po_date_on_choose_merchant'])){
	$po_number=$_POST['po_number'];
	$objloki=new soulstone();
	$objloki2=new soulstone();
	$objlokiout=$objloki->stc_call_po_number_choosing_merchant($po_number);
	$objloki2out=$objloki2->stc_call_po_amount($po_number);
	$outobjdata= array(
		'order_date' => $objlokiout,
		'due_amount' => $objloki2out
	);
	echo json_encode($outobjdata);
}

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
		$objloki=new soulstone();
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

if(isset($_POST['call_mer_pay'])){ 
	$popaybegdate=date("Y-m-d h:i:s", strtotime($_POST['begdate']));
	$popayenddate=date("Y-m-d h:i:s", strtotime($_POST['enddate']));
	$objloki=new soulstone();
	$objloki2=new soulstone();
	$objloki3=new soulstone();
	// $objlokiout=$objloki->stc_call_adv_pay();
	// $objloki2out=$objloki2->stc_call_reg_pay();
	$objloki3out=$objloki3->stc_call_all_pay($popaybegdate, $popayenddate);
	$outobjdata= array(
		'stc_all_payment' => $objloki3out
	);
	echo json_encode($outobjdata);
	// echo $outobjdata;
}

if(isset($_POST['call_invo_on_choose_merchant'])){
	$merchant_id=$_POST['merchant_id'];
	$objloki=new soulstone();
	$objlokiout=$objloki->stc_call_invo_choosing_merchant($merchant_id);
	echo json_encode($objlokiout);
}

if(isset($_POST['call_rec_for_reg_pay'])){
	$invo_number=$_POST['invo_number'];
	$merchant_id=$_POST['merchant_id'];
	$objloki=new soulstone();
	$objlokiout=$objloki->stc_call_rec_for_reg_pay($invo_number, $merchant_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

if(isset($_POST['stcsaverp'])){
	$grn_number=$_POST['stcrp_grnnumber'];
	$payment_type=$_POST['stcrp_paymenttype'];
	$refr_number=$_POST['stcrp_refrno'];
	$payment_amount=$_POST['stcrp_amount'];
	$regularpayment_notes=$_POST['stcrp_notes'];

	if(empty($grn_number) || $payment_type == "NA" || empty($payment_amount)){
		echo "All fields are needed!!!";
	}else{
		$objloki=new soulstone();
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

// call product
if(isset($_POST['go_through'])){
	$row = $_POST['row'];
	$rowperpage = 10;
	$bumblebee=new soulstone();
	$outbumblebee=$bumblebee->call_orders($row, $rowperpage);
	echo $outbumblebee;
}

if(isset($_POST['go_through_again'])){
	$row = $_POST['row'];
	$key_pay = $_POST['key_pay'];
	$rowperpage = 10;
	$bumblebee=new soulstone();
	$outbumblebee=$bumblebee->call_orders_using_search($row, $rowperpage, $key_pay);
	echo $outbumblebee;
}

// search
if(isset($_POST['get_rec_fro_key'])){
	$key_pay = $_POST['key_pay'];
	$popaybegdate=date("Y-m-d h:i:s", strtotime($_POST['popaybegdate']));
	$popayenddate=date("Y-m-d h:i:s", strtotime($_POST['popayenddate']));
	$bumblebee=new soulstone();
	$outbumblebee='';
	if(is_string($key_pay)==1 && empty($key_pay)){
		$outbumblebee='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			     	Ka be khali q search maarraha hai!!!
			    </a>
			</div>
		';
	}else{
		$outbumblebee=$bumblebee->stc_find_records($key_pay, $popaybegdate, $popayenddate);
	}
	echo $outbumblebee;
}

/*--------------------------------------------------------------------------------------------------------------*/
/*---------------------------------------------Customer Payments------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------*/

// show Customer on Sale order page
if(isset($_POST['call_so_on_choose_cust'])){
	$cust_id=$_POST['customer_id'];
	$objloki=new powerstone();
	$objlokiout=$objloki->stc_call_so_choosing_customer($cust_id);
	echo json_encode($objlokiout);
}

// call date and amount customer so
if(isset($_POST['call_so_date_on_choose_customer'])){
	$so_number=$_POST['so_number'];
	$objloki=new powerstone();
	$objloki2=new powerstone();
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

	$objloki=new powerstone();
	$objloki2=new powerstone();
	$objlokiout=$objloki->stc_go_customer_table($cust, $invo, $cattype, $type, $value, $notes);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

if(isset($_POST['load_cust_pay'])){
	$objloki=new powerstone();
	$objlokiout=$objloki->load_customer_payments();
	echo json_encode($objlokiout);
}

if(isset($_POST['get_rec_from_cust_key'])){
	$key_pay = $_POST['key_pay'];
	$bumblebee=new powerstone();
	$outbumblebee='';
	$outbumblebee=$bumblebee->stc_filter_cust_payments($key_pay);
	echo $outbumblebee;
}


/*--------------------------------------------------------------------------------------------------------------*/
/*------------------------------------------Dc Merchant Payments------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------*/

// show Customer on Sale order page
if(isset($_POST['call_dc_on_choose_mer'])){
	$mer_id=$_POST['dc_merchant_id'];
	$objloki=new gauntlet();
	$objlokiout=$objloki->stc_call_dc_choosing_merchant($mer_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call date and amount customer so
if(isset($_POST['call_dc_date_on_choose_merchant'])){
	$dc_number=$_POST['dc_number'];
	$objloki=new gauntlet();
	$objloki2=new gauntlet();
	$objlokiout=$objloki->stc_call_dc_number_choosing_merchant($dc_number);
	$objloki2out=$objloki2->stc_call_dc_amount($dc_number);
	$outobjdata= array(
		'order_date' => $objlokiout,
		'due_amount' => round($objloki2out, 2)
	);
	echo json_encode($outobjdata);
	// echo $outobjdata;
}

if(isset($_POST['on_dc_mer_pay_hit'])){
	$mer=$_POST['mer'];
	$invo=$_POST['invo'];
	$type=$_POST['type'];
	$value=$_POST['value'];
	$notes=$_POST['notes'];

	$objloki=new gauntlet();
	$objlokiout=$objloki->stc_go_dc_merchant_table($mer, $invo, $type, $value, $notes);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}
?>