<?php
include "../../MCU/obdb.php";
session_start();
// view deposit & amount
class ragnarliveAccountView extends tesseract{
	// call live account amount
	public function stc_live_account(){
		$ivar=0;
		$ivarcallamount=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_accounts_live`
		");
		foreach($ivarcallamount as $ivarcallamountrow){
			$ivar=$ivarcallamountrow['stc_accounts_live_amount'];
		}
		return $ivar;
	}
}

// view deposit
class ragnarDepositView extends tesseract{
	// call deposit
	// filter product by all
	public function stc_getdeposit_by_multiple_inp($bjornebegdate, $bjorneenddate, $bjornejsbankname, $bjornejsdepositor, $bjornejsrefrence, $bjornebegvalue, $bjorneendvalue){
		$ivar='';
			
		$array = array(
			"bybankname" => $bjornejsbankname,
			"bydepositor" => $bjornejsdepositor,
			"byrefrence" => $bjornejsrefrence
		);

		$bankname='';
		$depositor='';
		$refrence='';
		foreach($array as $key => $value){

			if(!empty($array['bybankname'])){
				$bankname="
					AND `stc_accounts_deposit_bank_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bybankname'])."'
				";
			}

			if(!empty($array['bydepositor'])){
				$depositor="
					AND `stc_accounts_deposit_depositer` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bydepositor'])."'
				";
			}

			if(!empty($array['byrefrence'])){
				$refrence="
					AND `stc_accounts_deposit_deposit_reference` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byrefrence'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_accounts_deposit_id` DESC LIMIT '.$bjornebegvalue.','.$bjorneendvalue;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT 				
	    		`stc_accounts_deposit_id`, 
				`stc_accounts_deposit_date`, 
				`stc_accounts_deposit_bank_name`, 
				`stc_accounts_deposit_depositer`, 
				`stc_accounts_deposit_deposit_reference`, 
				`stc_accounts_deposit_amount`, 
				`stc_accounts_deposit_remarks`, 
				`stc_accounts_deposit_created_by`
			FROM 
				`stc_accounts_deposit` 
			WHERE (
                `stc_accounts_deposit_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornebegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $bjorneenddate)."'
            )
			".$bankname.$depositor.$refrence.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)>0){
			foreach($ivarfilterquery as $row){            	
              	// $ponumber = substr("0000{$row["stc_accounts_deposit_id"]}", -$str_length);
				$ivar.='
					<tr>
       					<td>
							<h4>'.$row["stc_accounts_deposit_bank_name"].'</h4>
       					</td>
       					<td>
							'.date('Y-m-d', strtotime($row["stc_accounts_deposit_date"])).'<br>
							'.$row["stc_accounts_deposit_id"].'
       					</td>
       					<td>
							'.$row["stc_accounts_deposit_depositer"].'
       					</td>
       					<td>
							'.$row["stc_accounts_deposit_deposit_reference"].'
       					</td>
       					<td align="right">
							'.number_format($row["stc_accounts_deposit_amount"], 2).'
       					</td>
       					<td>
							'.$row["stc_accounts_deposit_remarks"].'
       					</td>
            		</tr>	               	
		    	';	
			}
		}else{
			$ivar .= '
				<tr>
					<td colspan="8">
						<a 
							href="#" 
							class="btn btn-primary btn-block text-uppercase mb-3">
							No Records Found!!!
						</a>
					</td>
				</tr>
			';
		}

		$ivar.= '
			<tr>
				<td colspan="4">
					'.$bjornebegvalue.' to '.$bjorneendvalue.'
					<button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="begvalueinputsearch" value="'.$bjornebegvalue.'">
				</td>
				<td colspan="4">
					<button type="button" class="btn btn-primary endbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="endvalueinputsearch" value="'.$bjorneendvalue.'">
				</td>
			</tr>
		';
		return $ivar;
	}
}

// add deposit
class ragnarDepositAdd extends tesseract{
	// hit deposit
	public function stc_account_deposit($dep_date, $dep_bank_name, $dep_by, $dep_refr, $dep_amount, $dep_remarks){
		$ivar='';
		$accountamt=0;
		$ivardepositqry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_accounts_deposit`(
				`stc_accounts_deposit_date`, 
				`stc_accounts_deposit_bank_name`, 
				`stc_accounts_deposit_depositer`, 
				`stc_accounts_deposit_deposit_reference`, 
				`stc_accounts_deposit_amount`, 
				`stc_accounts_deposit_remarks`, 
				`stc_accounts_deposit_created_by`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $dep_date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $dep_bank_name)."',
				'".mysqli_real_escape_string($this->stc_dbs, $dep_by)."',
				'".mysqli_real_escape_string($this->stc_dbs, $dep_refr)."',
				'".mysqli_real_escape_string($this->stc_dbs, $dep_amount)."',
				'".mysqli_real_escape_string($this->stc_dbs, $dep_remarks)."',
				'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_empl_id'])."'
			)
		");

		if($ivardepositqry){
			$ivarliveamtgetqry=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_accounts_live`
			");

			foreach($ivarliveamtgetqry as $ivarliveamtrow){
				$accountamt=$ivarliveamtrow['stc_accounts_live_amount'];
			}

			$accountamt+=$dep_amount;

			$ivarliveamtsetqry=mysqli_query($this->stc_dbs, "
				UPDATE `stc_accounts_live` SET `stc_accounts_live_amount`='".mysqli_real_escape_string($this->stc_dbs, $accountamt)."'
			");

			if($ivarliveamtsetqry){
				$ivar='Deposited Successfully!!!';
			}else{
				$ivar='Hmm!!! Something went wrong. Please check deposit amount or balance amount & try again later.';
			}
		}else{
			$ivar='Hmm!!! Something went wrong. Deposit not done properly.';
		}
		return $ivar;
	}
}

// view payment request
class ragnarPaymentRequestView extends tesseract{
	// call merchant function
	public function stc_call_merchant(){
		$odin='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_merchant_id`,
			    `stc_merchant_name`
			FROM
			    `stc_merchant`
			ORDER BY `stc_merchant_name` ASC 
    	");
		$odin='<option value="NA" selected>Select Merchant First.</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Merchant Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
					<option value="'.$row["stc_merchant_id"].'">'.$row["stc_merchant_name"].'</option>		               	
				';	
			}
			
		}
		return $odin;
	}

	// call po number
	public function stc_call_po($merchant_id, $begdate, $enddate){
		$odin='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_purchase_product_id`
			FROM
			    `stc_purchase_product`
			WHERE (
					DATE(`stc_purchase_product_order_date`) BETWEEN 
						'".mysqli_real_escape_string($this->stc_dbs, $begdate)."'
					AND 
						'".mysqli_real_escape_string($this->stc_dbs, $enddate)."'
				) 
			AND
			    `stc_purchase_product_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $merchant_id)."'
			ORDER BY DATE(`stc_purchase_product_order_date`) DESC
    	");
		$odin='<option value="NA" selected>Select PO Number</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No PO's Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
					<option value="'.$row["stc_purchase_product_id"].'">STC/'.substr("0000{$row["stc_purchase_product_id"]}", -5).'</option>
				';	
				if(isset($_SESSION["stc_pay_req_refrence_sess"])) {  
					$is_available = 0;
					foreach($_SESSION["stc_pay_req_refrence_sess"] as $keys => $values){  
		        	     if($_SESSION["stc_pay_req_refrence_sess"][$keys]['ref_id'] == $row["stc_purchase_product_id"]){  
		        	          $is_available++;  
		        	          // $bjorneout = "This Reference is Already In A Cart!!!";
		        	     }  
		        	}  
					if($is_available < 1) {  
						$item_array = array(  
							'ref_id'		=>     $row["stc_purchase_product_id"], 
							'pay_type'		=>     'advance'
						);  
						$_SESSION["stc_pay_req_refrence_sess"][] = $item_array;  
						// $bjorneout = "Reference Added to Payment Request List!!!";
					}
				}else{  
					$item_array = array(  
						'ref_id'		=>     $row["stc_purchase_product_id"], 
						'pay_type'		=>     'advance'
					);   
					$_SESSION["stc_pay_req_refrence_sess"][] = $item_array;  
				     // $bjorneout = " Payment Request List Created & Reference Added to Payment Request List!!!";
				}  
			}
			
		}
		return $odin;
	}

	// call invoice
	public function stc_call_invoice($merchant_id, $begdate, $enddate){
		$odin='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_product_grn_id`,
				`stc_product_grn_invoice_number`
			FROM
				`stc_product_grn`
			WHERE (
					DATE(`stc_product_grn_invoice_date`) BETWEEN 
						'".mysqli_real_escape_string($this->stc_dbs, $begdate)."'
					AND 
						'".mysqli_real_escape_string($this->stc_dbs, $enddate)."'
				) 
			AND
				`stc_product_grn_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $merchant_id)."' 
			ORDER BY DATE(`stc_product_grn_invoice_date`) DESC
		");
		$odin='<option value="NA" selected>Select Invoice Number</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Invoice's Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
					<option value="'.$row["stc_product_grn_id"].'">'.$row["stc_product_grn_invoice_number"].'</option>
				';	
				if(isset($_SESSION["stc_pay_req_refrence_sess"])) {  
					$is_available = 0;
					foreach($_SESSION["stc_pay_req_refrence_sess"] as $keys => $values){  
		        	     if($_SESSION["stc_pay_req_refrence_sess"][$keys]['ref_id'] == $row["stc_product_grn_id"]){  
		        	          $is_available++;  
		        	          // $bjorneout = "This Reference is Already In A Cart!!!";
		        	     }  
		        	}  
					if($is_available < 1) {  
						$item_array = array(  
							'ref_id'		=>     $row["stc_product_grn_id"], 
							'pay_type'		=>     'regular'
						);  
						$_SESSION["stc_pay_req_refrence_sess"][] = $item_array;  
						// $bjorneout = "Reference Added to Payment Request List!!!";
					}
				}else{  
					$item_array = array(  
						'ref_id'		=>     $row["stc_product_grn_id"], 
						'pay_type'		=>     'regular'
					);   
					$_SESSION["stc_pay_req_refrence_sess"][] = $item_array;  
				     // $bjorneout = " Payment Request List Created & Reference Added to Payment Request List!!!";
				}  
			}
			
		}
		return $odin;
	}

	// call payment refrence
	public function stc_call_pay_req_refr(){
		$ivar='';
		$slno=0;
		$gtotal=0;
		if(empty($_SESSION['stc_pay_req_refrence_sess'])){
			$ivar='
				<tr>
					<td colspan="5" align="center"><h5>Payment Request Cart Empty!!!</h5></td>
				</tr>
			';
		}else{
			foreach($_SESSION['stc_pay_req_refrence_sess'] as $sess_row){
				$refinvo='';
				$refdate='';
				$amount=0;
				$slno++;

				if($sess_row['pay_type']=="advance"){
					$ivarpoqry=mysqli_query($this->stc_dbs, "
						SELECT
							`stc_purchase_product_order_date`
						FROM
							`stc_purchase_product`
						WHERE
							`stc_purchase_product_id`='".mysqli_real_escape_string($this->stc_dbs, $sess_row['ref_id'])."'
					");
					foreach($ivarpoqry as $ivarporow){
						$refdate=$ivarporow['stc_purchase_product_order_date'];
					}

					$ivarpoitemsqry=mysqli_query($this->stc_dbs, "
						SELECT
							`stc_purchase_product_items_qty`,
							`stc_purchase_product_items_rate`,
							`stc_product_gst`
						FROM
							`stc_purchase_product_items`
						INNER JOIN `stc_product` 
						ON `stc_product_id` = `stc_purchase_product_items_product_id`
						WHERE
							`stc_purchase_product_items_order_id`='".mysqli_real_escape_string($this->stc_dbs, $sess_row['ref_id'])."'
						ORDER BY `stc_purchase_product_items_id` DESC
					");
					foreach($ivarpoitemsqry as $itemsrow){
						$total=0;
						$gst=0;
						$qty=$itemsrow['stc_purchase_product_items_qty'];
						$rate=$itemsrow['stc_purchase_product_items_rate'];
						$total=$qty * $rate;
						$gst=$total * $itemsrow['stc_product_gst']/100;
						$amount+=$total + $gst;
					}
					$ivar.='
						<tr>
							<td>'.$slno.'</td>
							<td>STC/'.substr("0000{$sess_row['ref_id']}", -5).'</td>
							<td>'.date('d-m-Y', strtotime($refdate)).'</td>
							<td align="right">'.number_format($amount, 2).'</td>
							<td align="center">
								<a
									class="stc-remove-pay-req btn btn-default"
									id="'.$sess_row['ref_id'].'"
									style="color:red;font-size: 20px;padding: 0px;"
								><i class="fa fa-trash"></i></a>
							</td>
						</tr>
					';
				}else{
					$ivargrnqry=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_product_grn_id`,
							`stc_product_grn_invoice_number`,
							`stc_product_grn_invoice_date`,
							`stc_product_grn_freight_charges`,
							`stc_product_grn_packing_forwarding`,
							`stc_product_grn_others_charges`
						FROM `stc_product_grn` 
						WHERE `stc_product_grn_id`='".mysqli_real_escape_string($this->stc_dbs, $sess_row['ref_id'])."'
					");

					foreach($ivargrnqry as $grnrow){
						$ininvoamtgrn=0;
						$invototamtgrn=0;
						$refdate=date('Y-m-d', strtotime($grnrow['stc_product_grn_invoice_date']));
						$refinvo=$grnrow['stc_product_grn_invoice_number'];

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
						$amount+=$invototamtgrna;
					}
					$ivar.='
						<tr>
							<td>'.$slno.'</td>
							<td>'.$refinvo.'</td>
							<td>'.date('d-m-Y', strtotime($refdate)).'</td>
							<td align="right">'.number_format($amount, 2).'</td>
							<td align="center">
								<a
									class="stc-remove-pay-req btn btn-default"
									id="'.$sess_row['ref_id'].'"
									style="color:red;font-size: 20px;padding: 0px;"
								><i class="fa fa-trash"></i></a>
							</td>
						</tr>
					';
				}
				$gtotal+=$amount;
			}
			$ivar.='
				<tr>
					<td colspan="3" >Total</td>
					<td align="right"><b>'.number_format($gtotal, 2).'</b></td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td align="right" colspan="2" ><a href="#" class="form-control btn btn-danger stc-pay-req-remove-pay-cart">Remove All</a></td>
				</tr>
			';
		}
		return $ivar;
	}

	// filter pay req by all
	public function stc_getpayreq_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltermerid, $bjornefilterrefno, $bjornefilterstatus, $begval, $endval){
		$ivar='';
		$totalrequestedamt=0;
		$totalpassedamt=0;
		$totalpaidamt=0;
		$totalbalanceamt=0;
		$array = array(
			"bymerchant" => $bjornefiltermerid,
			"byrefrno" => $bjornefilterrefno,
			"bystatus" => $bjornefilterstatus
		);

		$merchant='';
		$refrno='';
		$status='';
		foreach($array as $key => $value){
			if($array['bymerchant']!="NA"){
				$merchant="
					AND `stc_payment_request_mer_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bymerchant'])."'
				";
			}

			if(!empty($array['byrefrno'])){
				$refrno="
					AND `stc_purchase_product_id`='".mysqli_real_escape_string($this->stc_dbs, $array['byrefrno'])."'
				";
			}

			if($array['bystatus']!="NA"){
				$status="
					AND `stc_payment_request_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bystatus'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_payment_request_id` DESC LIMIT '.$begval.','.$endval;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_payment_request_id`,
				`stc_payment_request_date`,
				`stc_merchant_name`,
				`stc_payment_request_paytype`,
				`stc_payment_request_amount`,
				`stc_payment_request_status`,
				`stc_payment_request_remarks`,
				`stc_user_name`
			FROM
				`stc_payment_request`
			INNER JOIN `stc_merchant` 
			ON `stc_merchant_id` = `stc_payment_request_mer_id`
			INNER JOIN `stc_user`
			ON `stc_user_id` = `stc_payment_request_created_by`
			INNER JOIN `stc_payment_request_reference`  
			ON `stc_payment_request_reference_req_id` = `stc_payment_request_id`
			WHERE (
                DATE(`stc_payment_request_date`) 
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterenddate)."'
            )
			".$merchant.$refrno.$status.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)>0){
			foreach($ivarfilterquery as $ivarpayreqrow){
				$status='';
				$passedamt=0;
				$paidamt=0;
				$balanceamt=0;
				if($ivarpayreqrow['stc_payment_request_status']==1){
					$status='PROCESS';
				}elseif($ivarpayreqrow['stc_payment_request_status']==2){
					$status='ACCEPTED';
				}elseif($ivarpayreqrow['stc_payment_request_status']==3){
					$status='COMPLETED';
				}elseif($ivarpayreqrow['stc_payment_request_status']==4){
					$status='REPROCESS';
				}else{
					$status='CANCELED';
				}

				$ivarpayqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_payment_request_amount_amount`,
						`stc_payment_request_amount_type`
					FROM `stc_payment_request_amount`
					WHERE `stc_payment_request_amount_req_id`='".mysqli_real_escape_string($this->stc_dbs, $ivarpayreqrow['stc_payment_request_id'])."'
				");

				foreach($ivarpayqry as $ivarpayrow){
					if($ivarpayrow['stc_payment_request_amount_type']==1){
						$passedamt+=$ivarpayrow['stc_payment_request_amount_amount'];
					}else{
						$paidamt+=$ivarpayrow['stc_payment_request_amount_amount'];
					}
				}
				$balanceamt=$ivarpayreqrow['stc_payment_request_amount'] - $passedamt;


				// po & payment refrence
				$order_refrence='';
				$payreqamount=0;
				$looprefrence_type='';
				$looprefrence_nudate='';
				$looprefrence_amount='';
				$looppurchasaefor='';
				$ivarpayreqinvqry=mysqli_query($this->stc_dbs, "
					SELECT DISTINCT
						`stc_payment_request_reference_req_id`,
						`stc_payment_request_reference_refr_id`,
						`stc_payment_request_reference_type`,
						`stc_product_grn_invoice_number`,
						`stc_product_grn_invoice_date`,
						`stc_product_grn_freight_charges`,
						`stc_product_grn_packing_forwarding`,
						`stc_product_grn_others_charges`
					FROM 
						`stc_payment_request_reference`
					INNER JOIN 
						`stc_product_grn` 
					ON 
						`stc_product_grn_id`=`stc_payment_request_reference_refr_id` 
					WHERE 
						`stc_payment_request_reference_req_id`='".$ivarpayreqrow['stc_payment_request_id']."'
				");
				$pending_date='';
				foreach($ivarpayreqinvqry as $payreqinvrow){
					$refinvo='';
					$refdate='';
					$amount=0;
					if($payreqinvrow['stc_payment_request_reference_type']=="advance"){
						$ivarpoqry=mysqli_query($this->stc_dbs, "
							SELECT
								`stc_purchase_product_id`,
								`stc_purchase_product_order_date`,
								`stc_purchase_product_purchased_for`
							FROM
								`stc_purchase_product`
							WHERE
								`stc_purchase_product_id`='".mysqli_real_escape_string($this->stc_dbs, $payreqinvrow['stc_payment_request_reference_refr_id'])."'
						");
						foreach($ivarpoqry as $ivarporow){
							$refinvo=$ivarporow['stc_purchase_product_id'];
							$refdate=$ivarporow['stc_purchase_product_order_date'];
							$pending_date=$refdate;
							$looprefrence_type.='<span><b>PO</b></span><br><br>';
							$looprefrence_nudate.='<span>STC/'.substr("0000{$refinvo}", -5).'<br>'.date('d-m-Y', strtotime($refdate)).'</span><br>';
							$looppurchasaefor.=$ivarporow['stc_purchase_product_purchased_for'];
						}

						$ivarpoitemsqry=mysqli_query($this->stc_dbs, "
							SELECT
								`stc_purchase_product_items_qty`,
								`stc_purchase_product_items_rate`,
								`stc_product_gst`
							FROM
								`stc_purchase_product_items`
							INNER JOIN `stc_product` 
							ON `stc_product_id` = `stc_purchase_product_items_product_id`
							WHERE
								`stc_purchase_product_items_order_id`='".mysqli_real_escape_string($this->stc_dbs, $payreqinvrow['stc_payment_request_reference_refr_id'])."'
							ORDER BY `stc_purchase_product_items_id` DESC
						");
						foreach($ivarpoitemsqry as $itemsrow){
							$total=0;
							$gst=0;
							$qty=$itemsrow['stc_purchase_product_items_qty'];
							$rate=$itemsrow['stc_purchase_product_items_rate'];
							$total=$qty * $rate;
							$gst=$total * $itemsrow['stc_product_gst']/100;
							$amount+=$total + $gst;
							$looprefrence_amount.='<span>'.number_format($amount, 2).'</span><br><br>';
						}
						// $order_refrence.='
						// 		<td align="center">PO</td>
						// 		<td align="center">STC/'.substr("0000{$refinvo}", -5).'<br>'.date('d-m-Y', strtotime($refdate)).'</td>
						// 		<td align="right">'.number_format($amount, 2).'</td>
						// ';
					}else{
						$ivargrnqry=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_product_grn_id`,
								`stc_product_grn_invoice_number`,
								`stc_product_grn_invoice_date`,
								`stc_product_grn_freight_charges`,
								`stc_product_grn_packing_forwarding`,
								`stc_product_grn_others_charges`,
								`stc_purchase_product_purchased_for`
							FROM `stc_product_grn` 
							INNER JOIN `stc_purchase_product` 
							ON `stc_product_grn_purchase_order_id`=`stc_purchase_product_id`
							WHERE `stc_product_grn_id`='".mysqli_real_escape_string($this->stc_dbs, $payreqinvrow['stc_payment_request_reference_refr_id'])."'
						");

						foreach($ivargrnqry as $payreqinvrow2){
							$ininvoamtgrn=0;
							$invototamtgrn=0;
							$refdate=date('Y-m-d', strtotime($payreqinvrow2['stc_product_grn_invoice_date']));
							$pending_date=$refdate;
							$refinvo=$payreqinvrow2['stc_product_grn_invoice_number'];
							$looprefrence_type.='<span><b>INVOICE</b></span><br><br>';
							$looprefrence_nudate.='<span>'.$refinvo.'<br>'.date('d-m-Y', strtotime($refdate)).'</span><br>';
							$looppurchasaefor.=$payreqinvrow2['stc_purchase_product_purchased_for'].'<br>';

							$callamot_loki=mysqli_query($this->stc_dbs, "
								SELECT 
									`stc_product_grn_items_qty`, 
									`stc_product_grn_items_rate`,
									`stc_product_gst`
								FROM `stc_product_grn_items`
								INNER JOIN `stc_product`
								ON `stc_product_grn_items_product_id`=`stc_product_id`
								WHERE `stc_product_grn_items_grn_order_id`='".$payreqinvrow2["stc_product_grn_id"]."'
							");

							foreach ($callamot_loki as $amtrow){
								$ininvoamt = $amtrow['stc_product_grn_items_qty'] * $amtrow['stc_product_grn_items_rate'];						
								$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
								$ininvoamtgrn += $ininvoamt;
								$invototamtgrn += $ininvototamt;
								$progst=$amtrow['stc_product_gst'];
							}

							$gstfc=($payreqinvrow2['stc_product_grn_freight_charges'] * $progst)/100;
							$gstpf=($payreqinvrow2['stc_product_grn_packing_forwarding'] * $progst)/100;
							$gstoc=($payreqinvrow2['stc_product_grn_others_charges'] * $progst)/100;

							$invototamtgrna =
								($gstfc + $payreqinvrow2['stc_product_grn_freight_charges']) + 
								($gstpf + $payreqinvrow2['stc_product_grn_packing_forwarding']) + 
								($gstoc + $payreqinvrow2['stc_product_grn_others_charges']) + 
								($invototamtgrn);
							$amount+=$invototamtgrna;
							$looprefrence_amount.='<span>'.number_format($amount, 2).'</span><br><br>';
						}
						// $order_refrence.='
						// 		<td align="center">INVOICE</td>
						// 		<td align="center">'.$refinvo.'<br>'.date('d-m-Y', strtotime($refdate)).'</td>
						// 		<td align="right">'.number_format($amount, 2).'</td>
						// ';
					}
					$payreqamount+=$amount;
				}
			
				$wtpay='
					<a 
						id="'.$ivarpayreqrow['stc_payment_request_id'].'" 
						href="#" 
						style="font-size: 20px;color: white;padding: 5px;background: #3a74a5;border-radius: 100%;"
						class="stc-view-pay-req-invoices"
					><i 
						class="fa fa-eye"
					></i>
					</a>
				';	
				$pay_textbox='';
				$role_check_qry=mysqli_query($this->stc_dbs, "
					SELECT
						`stc_user_role_uid`,
						`stc_user_role_privilege_id`
					FROM
						`stc_user_role`
					WHERE
						`stc_user_role_uid`='".$_SESSION['stc_empl_id']."'
					AND 
						`stc_user_role_privilege_id`='205'
				");			

    			if(mysqli_num_rows($role_check_qry)>0){
					if($balanceamt>=10 && (($ivarpayreqrow['stc_payment_request_status']==1) || ($ivarpayreqrow['stc_payment_request_status']==4))){
						$pay_textbox='<input type="number" class="form-control stcpayget'.$ivarpayreqrow['stc_payment_request_id'].'" id="'.$ivarpayreqrow['stc_payment_request_id'].'" value="'.$balanceamt.'" placeholder="Enter Amount!!!">';
						$wtpay='
							<a 
								id="'.$ivarpayreqrow['stc_payment_request_id'].'"
								req-val="'.number_format($balanceamt, 2).'"
								href="#" 
								style="font-size: 20px;color: white;padding: 5px;background: #5ea53a;border-radius: 100%;"
								class="stc-add-pay-req-pay stcpayhit"
							>Pay
							</a>
						';
						$today = date("Y/m/d"); 
						$startTimeStamp = strtotime($pending_date);
						$endTimeStamp = strtotime($today);

						$timeDiff = abs($endTimeStamp - $startTimeStamp);

						$numberDays = $timeDiff/86400;  // 86400 seconds in one day

						if($numberDays>10){
							$pending_date = '<h6 style="color:red;">'.intval($numberDays).'</h6>';
						}else{
							$pending_date = '<h6">0 </h6>';
						}
					}else{
						$pending_date = '';
					}
				}

				$order_refrence.='
						<td align="center">'.$looprefrence_type.'</td>
						<td align="center">'.$looprefrence_nudate.'</td>
						<td align="right">'.$looprefrence_amount.'</td>
				';
				$ivar.='
					<tr>
						<td>
							'.date('d-m-Y', strtotime($ivarpayreqrow['stc_payment_request_date'])).'<br>
							'.$ivarpayreqrow['stc_payment_request_id'].'
						</td>
						<td>'.$ivarpayreqrow['stc_merchant_name'].'</td>
						<td>'.$looppurchasaefor.'</td>
						<td>'.strtoupper($ivarpayreqrow['stc_payment_request_paytype']).'</td>
						'.$order_refrence.'
						<td align="right">'.number_format($ivarpayreqrow['stc_payment_request_amount'], 2).'</td>
						<td align="right">'.number_format($passedamt, 2).'</td>
						<td align="right">'.number_format($paidamt, 2).'</td>
						<td align="right">'.number_format($balanceamt, 2).'</td>
						<td>'.$status.'</td>
						<td>'.$ivarpayreqrow['stc_payment_request_remarks'].'</td>
						<td>'.$ivarpayreqrow['stc_user_name'].'</td>
						<td class="text-center">'.$pending_date.'</td>
						<td>'.$pay_textbox.'</td>
						<td>							
							'.$wtpay.'
						</td>
					</tr>
				';
				$totalrequestedamt+=$ivarpayreqrow['stc_payment_request_amount'];
				$totalpassedamt+=$passedamt;
				$totalpaidamt+=$paidamt;
				$totalbalanceamt+=$balanceamt;
			}
			$ivar .= '
				<tr>
					<td colspan="7" class="text-center"><b>Total</b></td>
					<td class="text-right"><b>'.number_format($totalrequestedamt, 2).'</b></td>
					<td class="text-right"><b>'.number_format($totalpassedamt, 2).'</b></td>
					<td class="text-right"><b>'.number_format($totalpaidamt, 2).'</b></td>
					<td class="text-right"><b>'.number_format($totalbalanceamt, 2).'</b></td>
					<td colspan="4"></td>
				</tr>
			';
		}else{
			$ivar .= '
				<tr>
					<td colspan="11">
						<a 
							href="#" 
							class="btn btn-primary btn-block text-uppercase mb-3">
							No Records Found!!!
						</a>
					</td>
				</tr>
			';
		}

		$ivar.= '
			<tr>
				<td colspan="4">
					'.$begval.' to '.$endval.'
					<button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="begvalueinputsearch" value="'.$begval.'">
				</td>
				<td colspan="4">
					<button type="button" class="btn btn-primary endbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="endvalueinputsearch" value="'.$endval.'">
				</td>
			</tr>
		';
		return $ivar;
	}

	// call for invoice or po details
	public function stc_call_pay_req_invoices($pay_req_id){
		$ivar='';
		$slno=0;
		$payreqamount=0;
		$ivarpayreqinvqry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_payment_request_reference_req_id`,
				`stc_payment_request_reference_refr_id`,
				`stc_payment_request_reference_type`,
				`stc_product_grn_invoice_number`,
				`stc_product_grn_invoice_date`,
				`stc_product_grn_freight_charges`,
				`stc_product_grn_packing_forwarding`,
				`stc_product_grn_others_charges`
			FROM 
				`stc_payment_request_reference`
			INNER JOIN 
				`stc_product_grn` 
			ON 
				`stc_product_grn_id`=`stc_payment_request_reference_refr_id` 
			WHERE 
				`stc_payment_request_reference_req_id`='".mysqli_real_escape_string($this->stc_dbs, $pay_req_id)."'
		");
		foreach($ivarpayreqinvqry as $payreqinvrow){
			$slno++;
			$refinvo='';
			$refdate='';
			$amount=0;
			if($payreqinvrow['stc_payment_request_reference_type']=="advance"){
				$ivarpoqry=mysqli_query($this->stc_dbs, "
					SELECT
						`stc_purchase_product_id`,
						`stc_purchase_product_order_date`
					FROM
						`stc_purchase_product`
					WHERE
						`stc_purchase_product_id`='".mysqli_real_escape_string($this->stc_dbs, $payreqinvrow['stc_payment_request_reference_refr_id'])."'
				");
				foreach($ivarpoqry as $ivarporow){
					$refinvo=$ivarporow['stc_purchase_product_id'];
					$refdate=$ivarporow['stc_purchase_product_order_date'];
				}

				$ivarpoitemsqry=mysqli_query($this->stc_dbs, "
					SELECT
						`stc_purchase_product_items_qty`,
						`stc_purchase_product_items_rate`,
						`stc_product_gst`
					FROM
						`stc_purchase_product_items`
					INNER JOIN `stc_product` 
					ON `stc_product_id` = `stc_purchase_product_items_product_id`
					WHERE
						`stc_purchase_product_items_order_id`='".mysqli_real_escape_string($this->stc_dbs, $payreqinvrow['stc_payment_request_reference_refr_id'])."'
					ORDER BY `stc_purchase_product_items_id` DESC
				");
				foreach($ivarpoitemsqry as $itemsrow){
					$total=0;
					$gst=0;
					$qty=$itemsrow['stc_purchase_product_items_qty'];
					$rate=$itemsrow['stc_purchase_product_items_rate'];
					$total=$qty * $rate;
					$gst=$total * $itemsrow['stc_product_gst']/100;
					$amount+=$total + $gst;
				}
				$ivar.='
					<tr>
						<td align="center">'.$slno.'</td>
						<td align="center">STC/'.substr("0000{$refinvo}", -5).'</td>
						<td align="center">'.date('d-m-Y', strtotime($refdate)).'</td>
						<td align="right">'.number_format($amount, 2).'</td>
						<td align="center">PO</td>
					</tr>
				';
			}else{
				$ivargrnqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_product_grn_id`,
						`stc_product_grn_invoice_number`,
						`stc_product_grn_invoice_date`,
						`stc_product_grn_freight_charges`,
						`stc_product_grn_packing_forwarding`,
						`stc_product_grn_others_charges`
					FROM `stc_product_grn` 
					WHERE `stc_product_grn_id`='".mysqli_real_escape_string($this->stc_dbs, $payreqinvrow['stc_payment_request_reference_refr_id'])."'
				");

				foreach($ivargrnqry as $payreqinvrow2){
					$ininvoamtgrn=0;
					$invototamtgrn=0;
					$refdate=date('Y-m-d', strtotime($payreqinvrow2['stc_product_grn_invoice_date']));
					$refinvo=$payreqinvrow2['stc_product_grn_invoice_number'];

					$callamot_loki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_product_grn_items_qty`, 
							`stc_product_grn_items_rate`,
							`stc_product_gst`
						FROM `stc_product_grn_items`
						INNER JOIN `stc_product`
						ON `stc_product_grn_items_product_id`=`stc_product_id`
						WHERE `stc_product_grn_items_grn_order_id`='".$payreqinvrow2["stc_product_grn_id"]."'
					");

					foreach ($callamot_loki as $amtrow){
						$ininvoamt = $amtrow['stc_product_grn_items_qty'] * $amtrow['stc_product_grn_items_rate'];						
						$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
						$ininvoamtgrn += $ininvoamt;
						$invototamtgrn += $ininvototamt;
						$progst=$amtrow['stc_product_gst'];
					}

					$gstfc=($payreqinvrow2['stc_product_grn_freight_charges'] * $progst)/100;
					$gstpf=($payreqinvrow2['stc_product_grn_packing_forwarding'] * $progst)/100;
					$gstoc=($payreqinvrow2['stc_product_grn_others_charges'] * $progst)/100;

					$invototamtgrna =
						($gstfc + $payreqinvrow2['stc_product_grn_freight_charges']) + 
						($gstpf + $payreqinvrow2['stc_product_grn_packing_forwarding']) + 
						($gstoc + $payreqinvrow2['stc_product_grn_others_charges']) + 
						($invototamtgrn);
					$amount+=$invototamtgrna;
				}
				$ivar.='
					<tr>
						<td align="center">'.$slno.'</td>
						<td align="center">'.$refinvo.'</td>
						<td align="center">'.date('d-m-Y', strtotime($refdate)).'</td>
						<td align="right">'.number_format($amount, 2).'</td>
						<td align="center">INVOICE</td>
					</tr>
				';
			}
			$payreqamount+=$amount;
		}
		$ivar.='
			<tr>
				<td colspan="3" align="center">Total</td>
				<td align="right">'.number_format($payreqamount, 2).'</td>
			</tr>
		';
		return $ivar;
	}
}

// add payment request
class ragnarPaymentRequestAdd extends tesseract{
	public function stc_pay_req_save($mer_id, $pay_type, $amount, $remarks){
		$ivar='';
		$date=date("Y-m-d H:i:s");		
		foreach($_SESSION['stc_pay_req_refrence_sess'] as $reqsessrow){
			$amount=0;
			if($reqsessrow['pay_type']=="advance"){
				$ivarpoitemsqry=mysqli_query($this->stc_dbs, "
					SELECT
						`stc_purchase_product_items_qty`,
						`stc_purchase_product_items_rate`,
						`stc_product_gst`
					FROM
						`stc_purchase_product_items`
					INNER JOIN `stc_product` 
					ON `stc_product_id` = `stc_purchase_product_items_product_id`
					WHERE
						`stc_purchase_product_items_order_id`='".mysqli_real_escape_string($this->stc_dbs, $reqsessrow['ref_id'])."'
					ORDER BY `stc_purchase_product_items_id` DESC
				");
				foreach($ivarpoitemsqry as $itemsrow){
					$total=0;
					$gst=0;
					$qty=$itemsrow['stc_purchase_product_items_qty'];
					$rate=$itemsrow['stc_purchase_product_items_rate'];
					$total=$qty * $rate;
					$gst=$total * $itemsrow['stc_product_gst']/100;
					$amount+=$total + $gst;
				}
			}else{
				$ivargrnqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_product_grn_id`,
						`stc_product_grn_invoice_number`,
						`stc_product_grn_invoice_date`,
						`stc_product_grn_freight_charges`,
						`stc_product_grn_packing_forwarding`,
						`stc_product_grn_others_charges`,
						`stc_purchase_product_purchased_for`
					FROM `stc_product_grn` 
					INNER JOIN `stc_purchase_product` 
					ON `stc_product_grn_purchase_order_id`=`stc_purchase_product_id`
					WHERE `stc_product_grn_id`='".mysqli_real_escape_string($this->stc_dbs, $reqsessrow['ref_id'])."'
				");

				foreach($ivargrnqry as $payreqinvrow2){
					$ininvoamtgrn=0;
					$invototamtgrn=0;

					$callamot_loki=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_product_grn_items_qty`, 
							`stc_product_grn_items_rate`,
							`stc_product_gst`
						FROM `stc_product_grn_items`
						INNER JOIN `stc_product`
						ON `stc_product_grn_items_product_id`=`stc_product_id`
						WHERE `stc_product_grn_items_grn_order_id`='".$payreqinvrow2["stc_product_grn_id"]."'
					");

					foreach ($callamot_loki as $amtrow){
						$ininvoamt = $amtrow['stc_product_grn_items_qty'] * $amtrow['stc_product_grn_items_rate'];						
						$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
						$ininvoamtgrn += $ininvoamt;
						$invototamtgrn += $ininvototamt;
						$progst=$amtrow['stc_product_gst'];
					}

					$gstfc=($payreqinvrow2['stc_product_grn_freight_charges'] * $progst)/100;
					$gstpf=($payreqinvrow2['stc_product_grn_packing_forwarding'] * $progst)/100;
					$gstoc=($payreqinvrow2['stc_product_grn_others_charges'] * $progst)/100;

					$invototamtgrna =
						($gstfc + $payreqinvrow2['stc_product_grn_freight_charges']) + 
						($gstpf + $payreqinvrow2['stc_product_grn_packing_forwarding']) + 
						($gstoc + $payreqinvrow2['stc_product_grn_others_charges']) + 
						($invototamtgrn);
					$amount+=$invototamtgrna;
				}
			}
			$ivarpayreqqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_payment_request`(
					`stc_payment_request_date`,
					`stc_payment_request_mer_id`,
					`stc_payment_request_paytype`,
					`stc_payment_request_amount`,
					`stc_payment_request_status`,
					`stc_payment_request_remarks`,
					`stc_payment_request_created_by`
				)
				VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".mysqli_real_escape_string($this->stc_dbs, $mer_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pay_type)."',
					'".mysqli_real_escape_string($this->stc_dbs, $amount)."',
					'1',
					'".mysqli_real_escape_string($this->stc_dbs, $remarks)."',
					'".$_SESSION['stc_empl_id']."'
				)
			");
			if($ivarpayreqqry){
				$payreqid=0;
				$ivargetpayreqqry=mysqli_query($this->stc_dbs, "SELECT MAX(`stc_payment_request_id`) as payreqrow FROM `stc_payment_request`");
				foreach($ivargetpayreqqry as $getpayreqrow){
					$payreqid=$getpayreqrow['payreqrow'];
				}
				$ivarpayreqinvqry=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_payment_request_reference`(
						`stc_payment_request_reference_req_id`,
						`stc_payment_request_reference_refr_id`,
						`stc_payment_request_reference_type`
					)
					VALUES(
						'".mysqli_real_escape_string($this->stc_dbs, $payreqid)."',
						'".mysqli_real_escape_string($this->stc_dbs, $reqsessrow['ref_id'])."',
						'".mysqli_real_escape_string($this->stc_dbs, $reqsessrow['pay_type'])."'
					)
				");
				$ivar='success';
			}else{
				$ivar='Hmm!!!Something went wrong Payment Request can not be done.';
			}
		}
		return $ivar;
	}

	// payment passed
	public function stc_req_pay_hit($pay_req_id, $pay_passed_amt){
		$ivar='';
		$ivarreqamt=0;
		$ivarpasamt=0;
		$ivarbalamtcheck=0;

		$ivargetpayreqqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_payment_request_amount`
			FROM
				`stc_payment_request`
			WHERE
				`stc_payment_request_id`='".mysqli_real_escape_string($this->stc_dbs, $pay_req_id)."'
		");

		foreach($ivargetpayreqqry as $getpayreqrow){
			$ivarreqamt=$getpayreqrow['stc_payment_request_amount'];
		}

		$ivargetpaypasqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_payment_request_amount_amount`
			FROM
				`stc_payment_request_amount`
			WHERE
				`stc_payment_request_amount_req_id`='".mysqli_real_escape_string($this->stc_dbs, $pay_req_id)."' 
			AND 
				`stc_payment_request_amount_type`='1'
		");

		foreach($ivargetpaypasqry as $getpaypasrow){
			$ivarpasamt+=$getpaypasrow['stc_payment_request_amount_amount'];
		}

		$ivarbalamtcheck=$ivarreqamt - $ivarpasamt;
		if($pay_passed_amt>$ivarreqamt){
			$ivar='X. Boss please give me lesser than requested amount, That is INR'.number_format($ivarreqamt, 2);
		}elseif($pay_passed_amt>$ivarbalamtcheck){
			$ivar='X. Boss you have already passed INR'.number_format($ivarpasamt, 2).' out of INR'.number_format($ivarreqamt, 2).' before, Current total due is INR'.number_format($ivarbalamtcheck, 2);
		}else{
			$ivarpayreqsetqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_payment_request_amount`(
					`stc_payment_request_amount_req_id`,
					`stc_payment_request_amount_amount`,
					`stc_payment_request_amount_type`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $pay_req_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pay_passed_amt)."',
					'1'
				)
			");
			if($ivarpayreqsetqry){
				$ivarupdatepayreqry=mysqli_query($this->stc_dbs, "
					UPDATE 
						`stc_payment_request` 
					SET 
						`stc_payment_request_status`='2' 
					WHERE 
						`stc_payment_request_id`='".mysqli_real_escape_string($this->stc_dbs, $pay_req_id)."' 
				");
				if($ivarupdatepayreqry){
					$ivar="Payment Passed Successfully. Thankyou Boss.♥♥♥";
				}else{
					$ivar="Hmmm!!!Something went wrong in updating payment status...";
				}
			}else{
				$ivar="Hmm!!! Something went wrong on passing payment.";
			}
		}
		return $ivar;
	}
}

// view payment process
class ragnarPaymentProcessView extends tesseract{
	// filter pay req by all
	public function stc_getpayres_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltermerid, $bjornefilterrefno, $bjornefilterstatus, $begval, $endval){
		$ivar='';
		$totalpassedamt=0;
		$totalpaidamt=0;
		$totalbalanceamt=0;
		$array = array(
			"bymerchant" => $bjornefiltermerid,
			"byrefrno" => $bjornefilterrefno,
			"bystatus" => $bjornefilterstatus
		);

		$merchant='';
		$refrno='';
		$status='';
		foreach($array as $key => $value){
			if($array['bymerchant']!="NA"){
				$merchant="
					AND `stc_payment_request_mer_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bymerchant'])."'
				";
			}

			if(!empty($array['byrefrno'])){
				$refrno="
					AND `stc_purchase_product_id`='".mysqli_real_escape_string($this->stc_dbs, $array['byrefrno'])."'
				";
			}

			if($array['bystatus']!="NA"){
				$status="
					AND `stc_payment_request_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bystatus'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_payment_request_id` DESC LIMIT '.$begval.','.$endval;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_payment_request_id`,
				`stc_payment_request_date`,
				`stc_merchant_name`,
				`stc_payment_request_paytype`,
				`stc_payment_request_amount`,
				`stc_payment_request_status`,
				`stc_payment_request_remarks`,
				`stc_user_name`
			FROM
				`stc_payment_request`
			INNER JOIN `stc_merchant` 
			ON `stc_merchant_id` = `stc_payment_request_mer_id`
			INNER JOIN `stc_user`
			ON `stc_user_id` = `stc_payment_request_created_by`
			INNER JOIN `stc_payment_request_reference`  
			ON `stc_payment_request_reference_req_id` = `stc_payment_request_id`
			WHERE (
                DATE(`stc_payment_request_date`) 
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterenddate)."'
            ) AND `stc_payment_request_status`<>'1'
			".$merchant.$refrno.$status.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)>0){
			foreach($ivarfilterquery as $ivarpayreqrow){
				$status='';
				$passedamt=0;
				$paidamt=0;
				$balanceamt=0;
				if($ivarpayreqrow['stc_payment_request_status']==1){
					$status='PROCESS';
				}elseif($ivarpayreqrow['stc_payment_request_status']==2){
					$status='ACCEPTED';
				}elseif($ivarpayreqrow['stc_payment_request_status']==3){
					$status='COMPLETED';
				}elseif($ivarpayreqrow['stc_payment_request_status']==4){
					$status='REPROCESS';
				}else{
					$status='CANCELED';
				}

				$order_refrence='';
				$payreqamount=0;
				$looprefrence_type='';
				$looprefrence_nudate='';
				$looprefrence_amount='';
				$looppurchasaefor='';
				$ivarpayreqinvqry=mysqli_query($this->stc_dbs, "
					SELECT DISTINCT
						`stc_payment_request_reference_req_id`,
						`stc_payment_request_reference_refr_id`,
						`stc_payment_request_reference_type`,
						`stc_product_grn_invoice_number`,
						`stc_product_grn_invoice_date`,
						`stc_product_grn_freight_charges`,
						`stc_product_grn_packing_forwarding`,
						`stc_product_grn_others_charges`
					FROM 
						`stc_payment_request_reference`
					INNER JOIN 
						`stc_product_grn` 
					ON 
						`stc_product_grn_id`=`stc_payment_request_reference_refr_id` 
					WHERE 
						`stc_payment_request_reference_req_id`='".$ivarpayreqrow['stc_payment_request_id']."'
				");
				foreach($ivarpayreqinvqry as $payreqinvrow){
					$refinvo='';
					$refdate='';
					$amount=0;
					if($payreqinvrow['stc_payment_request_reference_type']=="advance"){
						$ivarpoqry=mysqli_query($this->stc_dbs, "
							SELECT
								`stc_purchase_product_id`,
								`stc_purchase_product_order_date`,
								`stc_purchase_product_purchased_for`
							FROM
								`stc_purchase_product`
							WHERE
								`stc_purchase_product_id`='".mysqli_real_escape_string($this->stc_dbs, $payreqinvrow['stc_payment_request_reference_refr_id'])."'
						");
						foreach($ivarpoqry as $ivarporow){
							$refinvo=$ivarporow['stc_purchase_product_id'];
							$refdate=$ivarporow['stc_purchase_product_order_date'];
							$looprefrence_type.='<span><b>PO</b></span><br><br>';
							$looprefrence_nudate.='<span>STC/'.substr("0000{$refinvo}", -5).'<br>'.date('d-m-Y', strtotime($refdate)).'</span><br>';
							$looppurchasaefor.=$ivarporow['stc_purchase_product_purchased_for'];
						}

						$ivarpoitemsqry=mysqli_query($this->stc_dbs, "
							SELECT
								`stc_purchase_product_items_qty`,
								`stc_purchase_product_items_rate`,
								`stc_product_gst`
							FROM
								`stc_purchase_product_items`
							INNER JOIN `stc_product` 
							ON `stc_product_id` = `stc_purchase_product_items_product_id`
							WHERE
								`stc_purchase_product_items_order_id`='".mysqli_real_escape_string($this->stc_dbs, $payreqinvrow['stc_payment_request_reference_refr_id'])."'
							ORDER BY `stc_purchase_product_items_id` DESC
						");
						foreach($ivarpoitemsqry as $itemsrow){
							$total=0;
							$gst=0;
							$qty=$itemsrow['stc_purchase_product_items_qty'];
							$rate=$itemsrow['stc_purchase_product_items_rate'];
							$total=$qty * $rate;
							$gst=$total * $itemsrow['stc_product_gst']/100;
							$amount+=$total + $gst;
							$looprefrence_amount.='<span>'.number_format($amount, 2).'</span><br><br>';
						}
						// $order_refrence.='
						// 		<td align="center">PO</td>
						// 		<td align="center">STC/'.substr("0000{$refinvo}", -5).'<br>'.date('d-m-Y', strtotime($refdate)).'</td>
						// 		<td align="right">'.number_format($amount, 2).'</td>
						// ';
					}else{
						$ivargrnqry=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_product_grn_id`,
								`stc_product_grn_invoice_number`,
								`stc_product_grn_invoice_date`,
								`stc_product_grn_freight_charges`,
								`stc_product_grn_packing_forwarding`,
								`stc_product_grn_others_charges`,
								`stc_purchase_product_purchased_for`
							FROM `stc_product_grn` 
							INNER JOIN `stc_purchase_product` 
							ON `stc_product_grn_purchase_order_id`=`stc_purchase_product_id`
							WHERE `stc_product_grn_id`='".mysqli_real_escape_string($this->stc_dbs, $payreqinvrow['stc_payment_request_reference_refr_id'])."'
						");

						foreach($ivargrnqry as $payreqinvrow2){
							$ininvoamtgrn=0;
							$invototamtgrn=0;
							$refdate=date('Y-m-d', strtotime($payreqinvrow2['stc_product_grn_invoice_date']));
							$refinvo=$payreqinvrow2['stc_product_grn_invoice_number'];
							$looprefrence_type.='<span><b>INVOICE</b></span><br><br>';
							$looprefrence_nudate.='<span>'.$refinvo.'<br>'.date('d-m-Y', strtotime($refdate)).'</span><br>';
							$looppurchasaefor.=$payreqinvrow2['stc_purchase_product_purchased_for'].'<br>';

							$callamot_loki=mysqli_query($this->stc_dbs, "
								SELECT 
									`stc_product_grn_items_qty`, 
									`stc_product_grn_items_rate`,
									`stc_product_gst`
								FROM `stc_product_grn_items`
								INNER JOIN `stc_product`
								ON `stc_product_grn_items_product_id`=`stc_product_id`
								WHERE `stc_product_grn_items_grn_order_id`='".$payreqinvrow2["stc_product_grn_id"]."'
							");

							foreach ($callamot_loki as $amtrow){
								$ininvoamt = $amtrow['stc_product_grn_items_qty'] * $amtrow['stc_product_grn_items_rate'];						
								$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
								$ininvoamtgrn += $ininvoamt;
								$invototamtgrn += $ininvototamt;
								$progst=$amtrow['stc_product_gst'];
							}

							$gstfc=($payreqinvrow2['stc_product_grn_freight_charges'] * $progst)/100;
							$gstpf=($payreqinvrow2['stc_product_grn_packing_forwarding'] * $progst)/100;
							$gstoc=($payreqinvrow2['stc_product_grn_others_charges'] * $progst)/100;

							$invototamtgrna =
								($gstfc + $payreqinvrow2['stc_product_grn_freight_charges']) + 
								($gstpf + $payreqinvrow2['stc_product_grn_packing_forwarding']) + 
								($gstoc + $payreqinvrow2['stc_product_grn_others_charges']) + 
								($invototamtgrn);
							$amount+=$invototamtgrna;
							$looprefrence_amount.='<span>'.number_format($amount, 2).'</span><br><br>';
						}
						// $order_refrence.='
						// 		<td align="center">INVOICE</td>
						// 		<td align="center">'.$refinvo.'<br>'.date('d-m-Y', strtotime($refdate)).'</td>
						// 		<td align="right">'.number_format($amount, 2).'</td>
						// ';
					}
					$payreqamount+=$amount;
				}

				$order_refrence.='
						<td align="center">'.$looprefrence_type.'</td>
						<td align="center">'.$looprefrence_nudate.'</td>
						<td align="right">'.$looprefrence_amount.'</td>
				';

				$ivarpayqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_payment_request_amount_amount`,
						`stc_payment_request_amount_type`
					FROM `stc_payment_request_amount`
					WHERE `stc_payment_request_amount_req_id`='".mysqli_real_escape_string($this->stc_dbs, $ivarpayreqrow['stc_payment_request_id'])."'
				");

				foreach($ivarpayqry as $ivarpayrow){
					if($ivarpayrow['stc_payment_request_amount_type']==1){
						$passedamt+=$ivarpayrow['stc_payment_request_amount_amount'];
					}else{
						$paidamt+=$ivarpayrow['stc_payment_request_amount_amount'];
					}
				}
				$balanceamt=$passedamt - $paidamt;
				$wtpay='<b>Paid</b>';				
				if($ivarpayreqrow['stc_payment_request_status']==2){
					$wtpay='
						<a 
							id="'.$ivarpayreqrow['stc_payment_request_id'].'"
							req-val="'.number_format($balanceamt, 2).'"
							href="#" 
							style="font-size: 20px;color: white;padding: 5px;background: #5ea53a;border-radius: 100%;"
							class="stc-add-pay-res-pay"
						><i 
							class="fas fa-credit-card"
						></i>
						</a>
					';
				}
				$ivar.='
					<tr>
						<td>
							'.date('d-m-Y', strtotime($ivarpayreqrow['stc_payment_request_date'])).'<br>
							'.$ivarpayreqrow['stc_payment_request_id'].'
						</td>
						<td>'.$ivarpayreqrow['stc_merchant_name'].'</td>
						'.$order_refrence.'
						<td align="right">'.number_format($passedamt, 2).'</td>
						<td align="right">'.number_format($paidamt, 2).'</td>
						<td align="right">'.number_format($balanceamt, 2).'</td>
						<td>'.$ivarpayreqrow['stc_payment_request_remarks'].'</td>
						<td>'.$status.'</td>
						<td class="text-center">'.$wtpay.'</td>
					</tr>
				';
				$totalpassedamt+=$passedamt;
				$totalpaidamt+=$paidamt;
				$totalbalanceamt+=$balanceamt;
			}
			$ivar .= '
				<tr>
					<td colspan="5" class="text-center"><b>Total</b></td>
					<td class="text-right"><b>'.number_format($totalpassedamt, 2).'</b></td>
					<td class="text-right"><b>'.number_format($totalpaidamt, 2).'</b></td>
					<td class="text-right"><b>'.number_format($totalbalanceamt, 2).'</b></td>
					<td colspan="3"></td>
				</tr>
			';
		}else{
			$ivar .= '
				<tr>
					<td colspan="8">
						<a 
							href="#" 
							class="btn btn-primary btn-block text-uppercase mb-3">
							No Records Found!!!
						</a>
					</td>
				</tr>
			';
		}

		$ivar.= '
			<tr>
				<td colspan="4">
					'.$begval.' to '.$endval.'
					<button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="begvalueinputsearch" value="'.$begval.'">
				</td>
				<td colspan="4">
					<button type="button" class="btn btn-primary endbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="endvalueinputsearch" value="'.$endval.'">
				</td>
			</tr>
		';
		return $ivar;
	}
}

// add payment process
class ragnarPaymentProcessAdd extends tesseract{
	public function stc_res_pay_hit($pay_req_id, $pay_passed_amt){
		$ivar='';
		$ivarreqamt=0;
		$ivarpasamt=0;
		$ivarbalamtcheck=0;
		$lamount=0;
		$finallamount=0;

		$ivargetpayreqqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_payment_request_amount_amount`
			FROM
				`stc_payment_request_amount`
			WHERE
				`stc_payment_request_amount_req_id`='".mysqli_real_escape_string($this->stc_dbs, $pay_req_id)."' 
			AND 
				`stc_payment_request_amount_type`='1'
		");

		foreach($ivargetpayreqqry as $getpayreqrow){
			$ivarreqamt+=$getpayreqrow['stc_payment_request_amount_amount'];
		}

		$ivargetpaypasqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_payment_request_amount_amount`
			FROM
				`stc_payment_request_amount`
			WHERE
				`stc_payment_request_amount_req_id`='".mysqli_real_escape_string($this->stc_dbs, $pay_req_id)."' 
			AND 
				`stc_payment_request_amount_type`='2'
		");

		foreach($ivargetpaypasqry as $getpaypasrow){
			$ivarpasamt+=$getpaypasrow['stc_payment_request_amount_amount'];
		}

		$ivarbalamtcheck=$ivarreqamt - $ivarpasamt;

		$ivargetlamt=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_accounts_live_amount` 
			FROM 
				`stc_accounts_live`
		");

		foreach($ivargetlamt as $getlamtrow){
			$lamount=$getlamtrow['stc_accounts_live_amount'];
		}

		$finallamount=$lamount - $pay_passed_amt;

		if($pay_passed_amt>$ivarreqamt){
			$ivar='X. Invalid payment amount';
		}elseif($pay_passed_amt>$ivarbalamtcheck){
			$ivar='X. Warning!!! your amount is greater than passed amount.';
		}elseif($pay_passed_amt>$lamount){
			$ivar='X. Not enough account balance.:(';
		}else{
			$ivarpayresqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_payment_request_amount`(
					`stc_payment_request_amount_req_id`,
					`stc_payment_request_amount_amount`,
					`stc_payment_request_amount_type`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $pay_req_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pay_passed_amt)."',
					'2'
				)
			");
			if($ivarpayresqry){
				$ivarupdatelamt=mysqli_query($this->stc_dbs, "
					UPDATE
						`stc_accounts_live`
					SET
						`stc_accounts_live_amount` = '".mysqli_real_escape_string($this->stc_dbs, $finallamount)."' 
				");

				$ivarupdatepayreqqry=mysqli_query($this->stc_dbs, "
					UPDATE
						`stc_payment_request`
					SET
						`stc_payment_request_status` = '3' 
					WHERE
						`stc_payment_request_id` = '".mysqli_real_escape_string($this->stc_dbs, $pay_req_id)."' 
				");
				if($ivarupdatelamt){
					$ivar="Payment done successfully!!!";
				}else{
					$ivar="Hmmm!!! SOmething went wrong. Account not updated properly!!!";
				}
			}else{
				$ivar="Hmm!!! Something went wrong, Payment not done correctly. Please recheck amount & try again later.";
			}
		}
		return $ivar;
	}
}

// add adjustment
class ragnarAdjustmentAdd extends tesseract{
	public function stc_account_adjustment($adjust_date, $adjust_adj_to, $adjust_refr, $adjust_amount, $adjust_remarks){
		$ivar='';
		$lamount=0;
		$famount=0;
		$ivaradjustqry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_payment_adjustment`(
				`stc_payment_adjustment_date`,
				`stc_payment_adjustment_title`,
				`stc_payment_adjustment_reference`,
				`stc_payment_adjustment_amount`,
				`stc_payment_adjustment_remarks`,
				`stc_payment_adjustment_created_by`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $adjust_date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $adjust_adj_to)."',
				'".mysqli_real_escape_string($this->stc_dbs, $adjust_refr)."',
				'".mysqli_real_escape_string($this->stc_dbs, $adjust_amount)."',
				'".mysqli_real_escape_string($this->stc_dbs, $adjust_remarks)."',
				'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_empl_id'])."'
			)
		");
		if($ivaradjustqry){
			$getliveaccqry=mysqli_query($this->stc_dbs, "
				SELECT
					`stc_accounts_live_amount`
				FROM
					`stc_accounts_live`
			");
			foreach($getliveaccqry as $liveaccrow){
				$lamount=$liveaccrow['stc_accounts_live_amount'];
			}
			$famount=$lamount - $adjust_amount;

			$updateliveaccqry=mysqli_query($this->stc_dbs, "
				UPDATE
					`stc_accounts_live`
				SET
					`stc_accounts_live_amount` = '".mysqli_real_escape_string($this->stc_dbs, $famount)."'
			");
			if($updateliveaccqry){
				$ivar="Payment Adjusted Successfully!!!";
			}else{
				$ivar="Hmm!!! Something went wrong, Account is not updated properly.";
			}
		}else{
			$ivar="Hmm!!! Something went wrong, Adjustment is not done properly.";
		}
		return $ivar;
	}
}
#<--------------------------------------------------------------------------------------------------------->
#<--------------------------------------Object sections of deposit class----------------------------------->
#<--------------------------------------------------------------------------------------------------------->
if(isset($_POST['stc_live_account_call'])){
	$bjorne=new ragnarliveAccountView();
	$outbjorne=$bjorne->stc_live_account();
	echo number_format($outbjorne, 2);
}

// save deposit
if(isset($_POST['stc_deposit_hit'])){
	$out='';
	$dep_date=date('Y-m-d H:i:s', strtotime($_POST['dep_date']));
	$dep_bank_name=$_POST['dep_bank_name'];
	$dep_by=$_POST['dep_by'];
	$dep_refr=$_POST['dep_refr'];
	$dep_amount=$_POST['dep_amount'];
	$dep_remarks=$_POST['dep_remarks'];
	if(empty($dep_date) || empty($dep_bank_name) || empty($dep_by) || empty($dep_refr) || empty($dep_amount) || empty($dep_remarks)){
		$out="Don't let any field empty.";
	}else{
		$bjornedeposit=new ragnarDepositAdd();
		$outbjornedeposit=$bjornedeposit->stc_account_deposit($dep_date, $dep_bank_name, $dep_by, $dep_refr, $dep_amount, $dep_remarks);
		$out=$outbjornedeposit;
	}
	echo $out;
}

// call deposit
if(isset($_POST['stcdepositaction'])){
	$bjornebegdate=$_POST['phpbegdate'];
	$bjorneenddate=$_POST['phpenddate'];
	$bjornejsbankname=$_POST['phpjsbankname'];
	$bjornejsdepositor=$_POST['phpjsdepositor'];
	$bjornejsrefrence=$_POST['phpjsrefrence'];
	$bjornebegvalue=$_POST['phpbegvalue'];
	$bjorneendvalue=$_POST['phpendvalue'];
	$out='';
	if(empty($bjornebegvalue) || empty($bjorneendvalue)){
		$bjornebegvalue=0;
		$bjorneendvalue=20;
	}
	$objpdres=new ragnarDepositView();	
	$opobjpdres=$objpdres->stc_getdeposit_by_multiple_inp($bjornebegdate, $bjorneenddate, $bjornejsbankname, $bjornejsdepositor, $bjornejsrefrence, $bjornebegvalue, $bjorneendvalue);
	$out=$opobjpdres;
	echo $out;
}

#<---------------------------------Object sections of payment request class-------------------------------->
// call merchant
if(isset($_POST['stc_call_merchant'])){
	$bjorne=new ragnarPaymentRequestView();
	$outbjorne=$bjorne->stc_call_merchant();
	echo $outbjorne;
}

// call refrence type
if(isset($_POST['stc_call_reference_type'])){
	$outbjorne='';
	$stc_paytype=$_POST['pay_type'];
	$merchant_id=$_POST['merchant_id'];
	$begdate=$_POST['begdate'];
	$enddate=$_POST['enddate'];
	$bjorne=new ragnarPaymentRequestView();
	if($stc_paytype=="advance"){
		$outbjorne=$bjorne->stc_call_po($merchant_id, $begdate, $enddate);
	}else{
		$outbjorne=$bjorne->stc_call_invoice($merchant_id, $begdate, $enddate);
	}
	echo $outbjorne;
}

// add to refrence cart
if(isset($_POST['stc_refrence_hit'])){
	$bjorneout='';
	$pay_type=$_POST['pay_type'];
	$refrence=$_POST['refrence'];

	if(empty($pay_type) || empty($refrence) || $pay_type=="NA" || $refrence=="NA"){
		$bjorneout="Hmm!!! Look like you have'nt choosed Merchant or Payment Type or Refrence Numbers!!!";
	}else{
		if(isset($_SESSION["stc_pay_req_refrence_sess"])) {  
			$is_available = 0;
			foreach($_SESSION["stc_pay_req_refrence_sess"] as $keys => $values){  
        	     if($_SESSION["stc_pay_req_refrence_sess"][$keys]['ref_id'] == $refrence){  
        	          $is_available++;  
        	          $bjorneout = "This Reference is Already In A Cart!!!";
        	     }  
        	}  
			if($is_available < 1) {  
				$item_array = array(  
					'ref_id'		=>     $refrence, 
					'pay_type'		=>     $pay_type
				);  
				$_SESSION["stc_pay_req_refrence_sess"][] = $item_array;  
				$bjorneout = "Reference Added to Payment Request List!!!";
			}
		}else{  
			$item_array = array(  
				'ref_id'		=>     $refrence, 
				'pay_type'		=>     $pay_type
			);   
			$_SESSION["stc_pay_req_refrence_sess"][] = $item_array;  
		     $bjorneout = " Payment Request List Created & Reference Added to Payment Request List!!!";
		}  
	}
	echo $bjorneout;
}

// call refrence
if(isset($_POST['stc_call_refrence'])){
	$bjorne='';
	$bjorne_pa_re=new ragnarPaymentRequestView();
	$bjorne=$bjorne_pa_re->stc_call_pay_req_refr();
	echo $bjorne;
}

// delete pay request from cart
if(isset($_POST['stc_pay_req_delete'])){
	$ref_id=$_POST['ref_id'];
	foreach($_SESSION["stc_pay_req_refrence_sess"] as $keys => $values){  
		if($values["ref_id"] == $ref_id){  
			unset($_SESSION["stc_pay_req_refrence_sess"][$keys]);  
			echo "Reference Removed!!!";  
		}  
	}  
}

// delete pay request from cart
if(isset($_POST['stc_pay_req_remove'])){
	unset($_SESSION["stc_pay_req_refrence_sess"]); 
	echo "Payment Request Cart Empty!!!"; 
}

// save payment requisition
if(isset($_POST['stc_pay_req_save'])){
	$bjroneout="";
	$mer_id=$_POST['mer_id'];
	$pay_type=$_POST['pay_type'];
	$amount=$_POST['amount'];
	$remarks=$_POST['remarks'];
	if($mer_id=="NA" || empty($pay_type) || empty($amount)){
		$out="Don't let any field empty.";
	}elseif(empty($_SESSION['stc_pay_req_refrence_sess'])){
		$out="Atleast add references(PO or Invoices)!!!";
	}else{
		if(!empty($_SESSION['stc_empl_id'])){
			$bjornereqpay=new ragnarPaymentRequestAdd();
			$bjroneout=$bjornereqpay->stc_pay_req_save($mer_id, $pay_type, $amount, $remarks);
			if($bjroneout=="success"){
				unset($_SESSION['stc_pay_req_refrence_sess']);
			}
			$out=$bjroneout;
		}else{
			header('location:index.html');
		}
	}
	echo $out;
}

// call payment request
if(isset($_POST['stcpayreqaction'])){
	@$begval=$_POST['phpbegvalue'];
	@$endval=$_POST['phpendvalue'];
	$bjornefilterbegdate=$_POST['phpbegdate'];
	$bjornefilterenddate=$_POST['phpenddate'];
	$bjornefiltermerid=$_POST['phpmerchantid'];
	$bjornefilterrefno=$_POST['phprefrnumber'];
	$bjornefilterstatus=$_POST['phpstatus'];
	$out='';
	if(empty($begval) || empty($endval)){
		$begval=0;
		$endval=20;
	}
	$objpdres=new ragnarPaymentRequestView();	
	$opobjpdres=$objpdres->stc_getpayreq_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltermerid, $bjornefilterrefno, $bjornefilterstatus, $begval, $endval);
	$out=$opobjpdres;
	echo $out;
}

// call invoices 
if(isset($_POST['stc_pay_req_call_invoices'])){
	$pay_req_id=$_POST['pay_req_id'];
	$bjorne=new ragnarPaymentRequestView();
	$bjorne=$bjorne->stc_call_pay_req_invoices($pay_req_id);
	echo $bjorne;
}

// payment passed
if(isset($_POST['stc_req_pay_pass_hit'])){	
	if(!empty($_SESSION['stc_empl_id'])){
		$pay_req_id=$_POST['pay_req_id'];
		$pay_passed_amt=$_POST['pay_passed_amt'];
		$bjornepassed=new ragnarPaymentRequestAdd();
		$outbjornepassed=$bjornepassed->stc_req_pay_hit($pay_req_id, $pay_passed_amt);
		echo $outbjornepassed;
	}else{
		header('location:index.html');
	}
}

#<---------------------------------Object sections of payment process class-------------------------------->
#<---------------------------------Object sections of payment adjustment class----------------------------->
// call payment request
if(isset($_POST['stcpayproaction'])){
	@$begval=$_POST['phpbegvalue'];
	@$endval=$_POST['phpendvalue'];
	$bjornefilterbegdate=$_POST['phpbegdate'];
	$bjornefilterenddate=$_POST['phpenddate'];
	$bjornefiltermerid=$_POST['phpmerchantid'];
	$bjornefilterrefno=$_POST['phprefrnumber'];
	$bjornefilterstatus=$_POST['phpstatus'];
	$out='';
	if(empty($begval) || empty($endval)){
		$begval=0;
		$endval=20;
	}
	$objpdres=new ragnarPaymentProcessView();	
	$opobjpdres=$objpdres->stc_getpayres_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltermerid, $bjornefilterrefno, $bjornefilterstatus, $begval, $endval);
	$out=$opobjpdres;
	echo $out;
}

// payment passed
if(isset($_POST['stc_res_pay_paid_hit'])){
	$pay_req_id=$_POST['pay_req_id'];
	$pay_passed_amt=$_POST['pay_passed_amt'];
	if(empty($pay_passed_amt)){
		$out="Invalid amount.";
	}else{
		if(!empty($_SESSION['stc_empl_id'])){
			$bjornepassed=new ragnarPaymentProcessAdd();
			$outbjornepassed=$bjornepassed->stc_res_pay_hit($pay_req_id, $pay_passed_amt);
			$out=$outbjornepassed;
		}else{
			header('location:index.html');
		}
	}
	echo $out;
}

#<---------------------------------Object sections of payment adjustment class----------------------------->
// save to adjustment
if(isset($_POST['stc_adjustment_hit'])){
	$out='';
	$adjust_date=date('Y-m-d H:i:s', strtotime($_POST['adjust_date']));
	$adjust_adj_to=$_POST['adjust_adj_to'];
	$adjust_refr=$_POST['adjust_refr'];
	$adjust_amount=$_POST['adjust_amount'];
	$adjust_remarks=$_POST['adjust_remarks'];
	if(empty($adjust_date) || empty($adjust_adj_to) || empty($adjust_refr) || empty($adjust_amount)){
		$out="Don't let any field empty.";
	}else{
		$bjorneadjustment=new ragnarAdjustmentAdd();
		$outbjorneadjustment=$bjorneadjustment->stc_account_adjustment($adjust_date, $adjust_adj_to, $adjust_refr, $adjust_amount, $adjust_remarks);
		$out=$outbjorneadjustment;
	}
	echo $out;
}
?>