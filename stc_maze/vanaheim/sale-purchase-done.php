<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Silent Invoice --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class aetherPurchase extends tesseract{

	// go data to purchase challan table
	public function purchase_process($silent_purchasepartyname, $silent_purchaserefrno, $silent_purchasedate, $silent_purchaseremarks){	
		$odin='';
		$date=date("Y-m-d H:i:s");
		$lokicheckqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_daily_purchase` 
			WHERE `stc_daily_purchase_refr_no` = '".mysqli_real_escape_string($this->stc_dbs, $silent_purchaserefrno)."'
		");
		if(mysqli_num_rows($lokicheckqry)>0){
			$odin .= "Hmmm!!! This Invoice or Refrences already in record.";
		}else{
			$lokiinsertsale=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_daily_purchase`(
				    `stc_daily_purchase_date`,
				    `stc_daily_purchase_party_name`,
				    `stc_daily_purchase_refr_no`,
				    `stc_daily_purchase_refr_date`,
				    `stc_daily_purchase_remarks`,
				    `stc_daily_purchase_createdby`
				) VALUES (
					'".$date."',
					'".mysqli_real_escape_string($this->stc_dbs, $silent_purchasepartyname)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $silent_purchaserefrno)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $silent_purchasedate)."',
					'".mysqli_real_escape_string($this->stc_dbs, $silent_purchaseremarks)."',
					'".$_SESSION['stc_electro_user_id']."'
				)
			");
			$odin='';
			if($lokiinsertsale){
				$odin .= "Purchase Done!!!";
			}else{
				$odin .= "Please Check & Try Again!!!";
			}
		}
		return $odin;
	}

	// go purchase session data to silent purchase items table 
	public function stc_save_purchase_pro_items(){
		// $odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_daily_purchase` ORDER BY `stc_daily_purchase`.`stc_daily_purchase_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_daily_purchase_id'];
		if(empty($_SESSION['stc_silent_purchase_sess'])){
			$odin = "Do not leave Items on Purchase!!!";
		}else{
			foreach ($_SESSION["stc_silent_purchase_sess"] as $key=>$value) {
		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_daily_purchase_items`(
		    			`stc_daily_purchase_items_order_id`,
						`stc_daily_purchase_items_material_desc`,
						`stc_daily_purchase_items_unit`,
						`stc_daily_purchase_items_tax`,
						`stc_daily_purchase_items_qty`,
						`stc_daily_purchase_items_rate`
					) VALUES (
		    			'".mysqli_real_escape_string($this->stc_dbs, $get_purchase_product_id)."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_name'])."',
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_unit'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_tax'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_qty'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_rate'])."'
		    		)
		    	");
		    }
		    $odin = "Purchase Item Done!!!";
		}
		return $odin;
	}	

	// stc call silent purchase
	public function stc_call_purchase($purchasebegdate, $purchaseenddate){
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
				`stc_daily_purchase_remarks`
			FROM
				`stc_daily_purchase_items`
			INNER JOIN 
				`stc_daily_purchase` 
			ON 
				`stc_daily_purchase_id` = `stc_daily_purchase_items_order_id`
			WHERE
			    (DATE(`stc_daily_purchase_refr_date`) 
			    BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $purchasebegdate)."' 
			    AND '".mysqli_real_escape_string($this->stc_dbs, $purchaseenddate)."') 
			    AND `stc_daily_purchase_createdby`='".$_SESSION['stc_electro_user_id']."'
			   ORDER BY `stc_daily_purchase_id` DESC LIMIT 0,30
		");
		$odin='
			<table class="table table-hover table-bordered">
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
	        	    <th scope="col">Action</th>
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
				$odin.='
					<tr>
       					<td class="text-center">
       					  <p>'.$row["stc_daily_purchase_party_name"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_purchase_items_material_desc"].'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.$row["stc_daily_purchase_refr_no"].'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.date('d-m-y', strtotime($row["stc_daily_purchase_refr_date"])).'</p>
       					</td>
       					<td class="text-right">
       					  <p>'.number_format($row["stc_daily_purchase_items_qty"], 2).'</p>
       					</td>				
       					<td class="text-right">
       					  <p>'.number_format($row["stc_daily_purchase_items_rate"], 2).'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_purchase_items_tax"].'%</p>
       					</td>
       					<td class="text-right">
       					  <h6>'.number_format($total, 2).'</h6>
       					</td>
       					<td class="text-center">
       					  <p>'.$paytype.'.</p>
       					</td>
       					<td>
       					  <p>'.$paydate.'</p>
       					</td>
       					<td class="text-right">
       					  <h6>'.number_format($dues, 2).'</h6>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_purchase_remarks"].'</p>
       					</td>
       					<td class="text-center">
       					  <a href="#" class="stc-purchase-pay-hit" id="'.$row["stc_daily_purchase_items_id"].'">Pay</a>
       					</td>
            		</tr>         	
		    	';				
			}
			
		}
		$odin.='
				</tbody>
	       	</table>
		';
		return $odin;
	}

	// stc search silent purchase
	public function stc_filter_purchase($search, $purchasebegdate, $purchaseenddate){
		$postatus='';
		$grnopt='';
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
				`stc_daily_purchase_remarks`
			FROM
				`stc_daily_purchase_items`
			INNER JOIN 
				`stc_daily_purchase` 
			ON 
				`stc_daily_purchase_id` = `stc_daily_purchase_items_order_id`
			WHERE(
				`stc_daily_purchase_party_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_daily_purchase_items_material_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_daily_purchase_refr_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			) AND (
                `stc_daily_purchase_refr_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $purchasebegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $purchaseenddate)."'
            ) AND `stc_daily_purchase_createdby`='".$_SESSION['stc_electro_user_id']."'
			ORDER BY `stc_daily_purchase`.`stc_daily_purchase_id` 
			DESC LIMIT 0,30
		");
		$odin='
			<table class="table table-hover table-bordered">
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
	        	    <th scope="col">Action</th>
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
				$odin.='
					<tr>
       					<td class="text-center">
       					  <p>'.$row["stc_daily_purchase_party_name"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_purchase_items_material_desc"].'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.$row["stc_daily_purchase_refr_no"].'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.date('d-m-y', strtotime($row["stc_daily_purchase_refr_date"])).'</p>
       					</td>
       					<td class="text-right">
       					  <p>'.number_format($row["stc_daily_purchase_items_qty"], 2).'</p>
       					</td>				
       					<td class="text-right">
       					  <p>'.number_format($row["stc_daily_purchase_items_rate"], 2).'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_purchase_items_tax"].'%</p>
       					</td>
       					<td class="text-right">
       					  <h6>'.number_format($total, 2).'</h6>
       					</td>
       					<td class="text-center">
       					  <p>'.$paytype.'.</p>
       					</td>
       					<td>
       					  <p>'.$paydate.'</p>
       					</td>
       					<td class="text-right">
       					  <h6>'.number_format($dues, 2).'</h6>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_purchase_remarks"].'</p>
       					</td>
       					<td class="text-center">
       					  <a href="#" class="stc-purchase-pay-hit" id="'.$row["stc_daily_purchase_items_id"].'">Pay</a>
       					</td>
            		</tr>         	
		    	';				
			}
			
		}
		$odin.='
				</tbody>
	       	</table>
		';
		return $odin;
	}

	// update payment
	public function stc_update_payment($item_id, $pay_val, $pay_type){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$odin_item_ugetqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_daily_purchase_items_tax`,
				`stc_daily_purchase_items_qty`,
				`stc_daily_purchase_items_rate`
			FROM
				`stc_daily_purchase_items`
			WHERE
				`stc_daily_purchase_items_id`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
		");
		$item_amount=0;
		foreach($odin_item_ugetqry as $item_row){
			$basic=$item_row['stc_daily_purchase_items_qty'] * $item_row['stc_daily_purchase_items_rate'];
			$gst=$basic * ($item_row['stc_daily_purchase_items_tax']/100);
			$item_amount=$basic + $gst;
		}

		$odin_payment_ugetqry=mysqli_query($this->stc_dbs, "
			SELECT
				SUM(`stc_daily_purchase_payments_amount`) AS payment_value
			FROM
				`stc_daily_purchase_payments`
			WHERE
				`stc_daily_purchase_payments_order_no`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
		");
		$payment_amount=0;
		foreach($odin_payment_ugetqry as $payment_row){
			$payment_amount=$payment_row['payment_value'];
		}

		$current_amount=$pay_val + $payment_amount;
		if($item_amount>$current_amount){
			$odin_setpayment=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_daily_purchase_payments`(
					`stc_daily_purchase_payments_date`,
					`stc_daily_purchase_payments_order_no`,
					`stc_daily_purchase_payments_type`,
					`stc_daily_purchase_payments_amount`
				)VALUES(
					'".$date."',
					'".mysqli_real_escape_string($this->stc_dbs, $item_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pay_type)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pay_val)."'
				)
			");
			if($odin_setpayment){
				$odin='Payment updated succesfully.';
			}else{
				$odin='Hmmm!!! Something went wrong, Payment not updated successfully. please check & try again.';
			}
		}else{
			$odin='Pal!!! Amount cannot be greater than material value.';
		}
		return $odin;
	}
}

class aetherSale extends tesseract{

	// go data to Sale table
	public function sale_process($salepartyname, $salerefrno, $salerefrdate, $saleorderby, $saleremarks){	
		$odin='';
		$date=date("Y-m-d H:i:s");
		$lokicheckqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_daily_sale` 
			WHERE `stc_daily_sale_refr_no` = '".mysqli_real_escape_string($this->stc_dbs, $salerefrno)."'
		");
		if(mysqli_num_rows($lokicheckqry)>0){
			$odin .= "Hmmm!!! This Invoice or Refrences already in record.";
		}else{
			$lokiinsertsale=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_daily_sale`(
				    `stc_daily_sale_date`,
				    `stc_daily_sale_party_name`,
				    `stc_daily_sale_refr_no`,
				    `stc_daily_sale_refr_date`,
				    `stc_daily_sale_by`,
				    `stc_daily_sale_remarks`,
				    `stc_daily_sale_createdby`
				) VALUES (
					'".$date."',
					'".mysqli_real_escape_string($this->stc_dbs, $salepartyname)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $salerefrno)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $salerefrdate)."',
					'".mysqli_real_escape_string($this->stc_dbs, $saleorderby)."',
					'".mysqli_real_escape_string($this->stc_dbs, $saleremarks)."',
					'".$_SESSION['stc_electro_user_id']."'
				)
			");
			$odin='';
			if($lokiinsertsale){
				$odin .= "Sale Done!!!";
			}else{
				$odin .= "Please Check & Try Again!!!";
			}
		}
		return $odin;
	}

	// go Sale session data to silent Sale items table 
	public function stc_save_sale_pro_items(){
		// $odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_daily_sale` ORDER BY `stc_daily_sale`.`stc_daily_sale_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_daily_sale_id'];
		if(empty($_SESSION['stc_silent_sale_sess'])){
			$odin = "Do not leave Items on Sale!!!";
		}else{
			foreach ($_SESSION["stc_silent_sale_sess"] as $key=>$value) {
		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_daily_sale_items`(
		    			`stc_daily_sale_items_order_id`,
						`stc_daily_sale_items_material_desc`,
						`stc_daily_sale_items_unit`,
						`stc_daily_sale_items_tax`,
						`stc_daily_sale_items_qty`,
						`stc_daily_sale_items_rate`
					) VALUES (
		    			'".mysqli_real_escape_string($this->stc_dbs, $get_purchase_product_id)."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_name'])."',
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_unit'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_tax'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_qty'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_rate'])."'
		    		)
		    	");
		    }
		    $odin = "Sale Item Done!!!";
		}
		return $odin;
	}	

	// stc call silent sale
	public function stc_call_sale($purchasebegdate, $purchaseenddate){
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
				`stc_daily_sale_by`
			FROM
				`stc_daily_sale_items`
			INNER JOIN 
				`stc_daily_sale` 
			ON 
				`stc_daily_sale_id` = `stc_daily_sale_items_order_id`
			WHERE
			    (DATE(`stc_daily_sale_refr_date`) 
			    BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $purchasebegdate)."' 
			    AND '".mysqli_real_escape_string($this->stc_dbs, $purchaseenddate)."'
			) AND `stc_daily_sale_createdby`='".$_SESSION['stc_electro_user_id']."'
			ORDER BY `stc_daily_sale_id` DESC LIMIT 0,30
		");
		$odin='
			<table class="table table-hover table-bordered table-responsive">
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
					<th scope="col">Action</th>
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
				$odin.='
					<tr>
       					<td class="text-center">
       					  <p>'.$row["stc_daily_sale_party_name"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_sale_items_material_desc"].'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.$row["stc_daily_sale_refr_no"].'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.date('d-m-y', strtotime($row["stc_daily_sale_refr_date"])).'</p>
       					</td>
       					<td class="text-right">
       					  <p>'.number_format($row["stc_daily_sale_items_qty"], 2).'</p>
       					</td>				
       					<td class="text-right">
       					  <p>'.number_format($row["stc_daily_sale_items_rate"], 2).'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_sale_items_tax"].'%</p>
       					</td>
       					<td class="text-right">
       					  <h6>'.number_format($total, 2).'</h6>
       					</td>
       					<td class="text-center">
       					  <p>'.$paytype.'.</p>
       					</td>
       					<td>
       					  <p>'.$paydate.'</p>
       					</td>
       					<td class="text-right">
       					  <h6>'.number_format($dues, 2).'</h6>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_sale_remarks"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_sale_by"].'</p>
       					</td>
       					<td class="text-center">
       					  <a href="#" class="stc-sale-pay-hit" id="'.$row["stc_daily_sale_items_id"].'">Pay</a>
       					</td>
            		</tr>         	
		    	';				
			}
		}
		$odin.='
				</tbody>
	       	</table>
		';
		return $odin;
	}

	// stc search silent sale
	public function stc_filter_sale($search, $salebegdate, $saleenddate){
		$postatus='';
		$grnopt='';
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
				`stc_daily_sale_by`
			FROM
				`stc_daily_sale_items`
			INNER JOIN 
				`stc_daily_sale` 
			ON 
				`stc_daily_sale_id` = `stc_daily_sale_items_order_id`
			WHERE(
				`stc_daily_sale_party_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_daily_sale_items_material_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_daily_sale_refr_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_daily_sale_by` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			) AND (
                `stc_daily_sale_refr_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $salebegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $saleenddate)."'
            ) AND `stc_daily_sale_createdby`='".$_SESSION['stc_electro_user_id']."'
			ORDER BY `stc_daily_sale`.`stc_daily_sale_id` 
			DESC LIMIT 0,30
		");
		$odin='
			<table class="table table-hover table-bordered">
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
					<th scope="col">Action</th>
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
				$odin.='
					<tr>
       					<td class="text-center">
       					  <p>'.$row["stc_daily_sale_party_name"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_sale_items_material_desc"].'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.$row["stc_daily_sale_refr_no"].'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.date('d-m-y', strtotime($row["stc_daily_sale_refr_date"])).'</p>
       					</td>
       					<td class="text-right">
       					  <p>'.number_format($row["stc_daily_sale_items_qty"], 2).'</p>
       					</td>				
       					<td class="text-right">
       					  <p>'.number_format($row["stc_daily_sale_items_rate"], 2).'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_sale_items_tax"].'%</p>
       					</td>
       					<td class="text-right">
       					  <h6>'.number_format($total, 2).'</h6>
       					</td>
       					<td class="text-center">
       					  <p>'.$paytype.'.</p>
       					</td>
       					<td>
       					  <p>'.$paydate.'</p>
       					</td>
       					<td class="text-right">
       					  <h6>'.number_format($dues, 2).'</h6>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_sale_remarks"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_daily_sale_by"].'</p>
       					</td>
       					<td class="text-center">
       					  <a href="#" class="stc-sale-pay-hit" id="'.$row["stc_daily_sale_items_id"].'">Pay</a>
       					</td>
            		</tr>         	
		    	';				
			}
		}
		$odin.='
				</tbody>
	       	</table>
		';
		return $odin;
	}

	// update payment
	public function stc_sale_update_payment($item_id, $pay_val, $pay_type){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$odin_item_ugetqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_daily_sale_items_tax`,
				`stc_daily_sale_items_qty`,
				`stc_daily_sale_items_rate`
			FROM
				`stc_daily_sale_items`
			WHERE
				`stc_daily_sale_items_id`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
		");
		$item_amount=0;
		foreach($odin_item_ugetqry as $item_row){
			$basic=$item_row['stc_daily_sale_items_qty'] * $item_row['stc_daily_sale_items_rate'];
			$gst=$basic * ($item_row['stc_daily_sale_items_tax']/100);
			$item_amount=$basic + $gst;
		}

		$odin_payment_ugetqry=mysqli_query($this->stc_dbs, "
			SELECT
				SUM(`stc_daily_sale_payments_amount`) AS payment_value
			FROM
				`stc_daily_sale_payments`
			WHERE
				`stc_daily_sale_payments_order_no`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
		");
		$payment_amount=0;
		foreach($odin_payment_ugetqry as $payment_row){
			$payment_amount=$payment_row['payment_value'];
		}

		$current_amount=$pay_val + $payment_amount;
		if($item_amount>$current_amount){
			$odin_setpayment=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_daily_sale_payments`(
					`stc_daily_sale_payments_date`,
					`stc_daily_sale_payments_order_no`,
					`stc_daily_sale_payments_type`,
					`stc_daily_sale_payments_amount`
				)VALUES(
					'".$date."',
					'".mysqli_real_escape_string($this->stc_dbs, $item_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pay_type)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pay_val)."'
				)
			");
			if($odin_setpayment){
				$odin='Payment updated succesfully.';
			}else{
				$odin='Hmmm!!! Something went wrong, Payment not updated successfully. please check & try again.';
			}
		}else{
			$odin='Pal!!! Amount cannot be greater than Material value.';
		}
		return $odin;
	}
}
#<------------------------------------------------------------------------------------------>
#<---------------------------------Silent purchase Section---------------------------------->
#<------------------------------------------------------------------------------------------>
// show purchase cart
if(isset($_POST['stc_show_purchase_sess'])){
	$order_table = '';  
	$slno = '';  
	$order_table .= '  
		<table class="table table-hover table-responsive" align="centre">
			<thead>
			  <tr>
			    <th scope="col">#</th>
			    <th scope="col" style="width: 20%;">Material Details</th>
			    <th scope="col">Unit</th>
			    <th scope="col">Qty</th>                         
			    <th scope="col">Rate</th>
			    <th scope="col">Amount</th>
			    <th scope="col">GST</th>
			    <th scope="col" width="10%">Amount</th>
			    <th scope="col">Action</th>
			  </tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_silent_purchase_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_silent_purchase_sess"] as $keys => $values) {  
				$amount=$values["product_qty"] * $values["product_rate"];
				$gstamount = ($amount * $values["product_tax"])/100;
				$slno++;
				$order_table .= ' 
					<tr>
						<td>'.$slno.'</td>
						<td>'.$values["product_name"].'</td>
						<td>'.$values["product_unit"].'</td>
						<td>'.number_format($values["product_qty"], 2).'</td>
						<td>'.number_format($values["product_rate"], 2).'</td>
						<td><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
						<td> '.$values["product_tax"].'% </td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount + $gstamount, 2).'</td>
						<td>
							<a style="color: red;font-size:20px;" href="#" id="'.$values["product_name"].'" class="stcdelsilentbtn">
								<span class="material-icons">delete</span>
							</a>
						</td>
					</tr>
				';  
				$total = $total + ($values["product_qty"] * $values["product_rate"]) ;  
				$totalgst +=$gstamount;
			}  
			$gstamount = ($amount * $values["product_tax"])/100;
			$order_table .= ' 
			   <tr>
				  <td colspan="7"><h4 align="right">Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total, 2).'</td>
				  <td><input type="hidden" name="hidden_bvalue" id="stcbasicvalue" value="'.$total.'"></td>
				</tr>
				<tr>
				  <td colspan="7"><h4 align="right">CGST</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
				  <td></td>
				</tr>
				<tr>
				  <td colspan="7"><h4 align="right">SGST</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
				  <td></td>
				</tr>
        		<tr>
				  <td colspan="7"><h4 align="right">Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.number_format($total + $totalgst, 2).'</span></td>
				  <td></td>
				</tr> 
			';  
		}else{
			$order_table.='
				<tr>  
					<td colspan="10" align="center">  
					    <h3>Purchase Cart Empty!!!</h3>
					</td>  
				</tr>  
			';
		}
	$order_table .= '
			
		  </tbody>
		</table>
		'; 
	$export=array(
	  "orderTable" => $order_table	  
	); 
	header('Content-type: application/json');
	echo json_encode($export);
		// echo $order_table;
}

// add to cart add_purchase session obj
if(isset($_POST['add_purchase'])){
	if(isset($_SESSION["stc_silent_purchase_sess"])) {  
		$is_available = 0;  
		foreach($_SESSION["stc_silent_purchase_sess"] as $keys => $values) {  
			if($_SESSION["stc_silent_purchase_sess"][$keys]['product_name'] == $_POST["product_name"]) {  
			     $is_available++;  
			     $_SESSION["stc_silent_purchase_sess"][$keys]['product_qty'] = $_SESSION["stc_silent_purchase_sess"][$keys]['product_qty'] + $_POST["product_qty"];  
			     echo "Purchase Cart Material Quantity Increased!!!";
			}  
		}  
		if($is_available < 1) {  
			$item_array = array(  
				'product_name' 	 => 	$_POST["product_name"], 
				'product_unit' 	 => 	$_POST["product_unit"], 
				'product_tax' 	 => 	$_POST["product_tax"], 
				'product_qty' 	 => 	$_POST["product_qty"], 
				'product_rate' 	 => 	$_POST["product_rate"] 
			);  
			$_SESSION["stc_silent_purchase_sess"][] = $item_array;  
			echo "Purchase Cart Created & Material Added to Cart!!!";
		}  
	}else{  
		$item_array = array(  
			'product_name' 	 => 	$_POST["product_name"], 
			'product_unit' 	 => 	$_POST["product_unit"], 
			'product_tax' 	 => 	$_POST["product_tax"], 
			'product_qty' 	 => 	$_POST["product_qty"], 
			'product_rate' 	 => 	$_POST["product_rate"] 
		);   
		$_SESSION["stc_silent_purchase_sess"][] = $item_array;  
		echo "Material Added to Purchase Cart!!!";
	} 
}

// delete from sale cart of session Sale order
if(isset($_POST['stcdelpurchaselinei'])){
     foreach($_SESSION["stc_silent_purchase_sess"] as $keys => $values){  
          if($values["product_name"] == $_POST["product_id"]){  
               unset($_SESSION["stc_silent_purchase_sess"][$keys]);  
               echo "Material Removed!!!";  
          }  
     }  
}

// save purchase
if(isset($_POST['save_purchase_action'])){
	$out='';
	$silent_purchasepartyname	= $_POST['purchasepartyname'];
	$silent_purchaserefrno		= $_POST['purchaserefrno'];
	$silent_purchasedate		= date('Y-m-d h:i:s', strtotime($_POST['purchaserefrdate']));
	$silent_purchaseremarks		= $_POST['purchaseremarks'];

	$fridaysale=new aetherPurchase();
	$fridaysaleitems=new aetherPurchase();
	if(empty($silent_purchasepartyname) || empty($silent_purchaserefrno) || empty($silent_purchasedate)){
		$out= "Please Fill All Fields!!!";
	}elseif(empty($_SESSION['stc_silent_purchase_sess'])){
		$out= "Please add Materials first.";
	}else{
		$objfridaygrn=$fridaysale->purchase_process(
			$silent_purchasepartyname,
			$silent_purchaserefrno,
			$silent_purchasedate,
			$silent_purchaseremarks
		);
		if($objfridaygrn=='Purchase Done!!!'){
			$objfridaygrnitems=$fridaysaleitems->stc_save_purchase_pro_items();
			$out=$objfridaygrnitems;
			unset($_SESSION['stc_silent_purchase_sess']);
		}else{
			$out .= "Something went Wrong, Purchase not added properly!!!".$objfridaygrn;
		}
	}
	echo $out;
}

// Show purchase On purchase Page
if(isset($_POST['stccallss'])){
	$purchasebegdate=date("Y-m-d", strtotime($_POST['begdate']));
	$purchaseenddate=date("Y-m-d", strtotime($_POST['enddate']));
	$objloki=new aetherPurchase();
	$objlokiout=$objloki->stc_call_purchase($purchasebegdate, $purchaseenddate);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// filter purchase
if(isset($_POST['stcfilterpurchase'])){
	$search = str_replace(",", "|", $_POST["stcfilterpurchase"]);
	$purchasebegdate=date("Y-m-d h:i:s", strtotime($_POST['purchasebegdate']));
	$purchaseenddate=date("Y-m-d h:i:s", strtotime($_POST['purchaseenddate']));
	$objloki=new aetherPurchase();
	$objlokiout=$objloki->stc_filter_purchase($search, $purchasebegdate, $purchaseenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// update purchase payment
if(isset($_POST['stc_update_payment_hit'])){
	$item_id=$_POST['item_id'];
	$pay_val=$_POST['pay_val'];
	$pay_type=$_POST['pay_type'];
	$objloki=new aetherPurchase();
	$objlokiout=$objloki->stc_update_payment($item_id, $pay_val, $pay_type);
	echo $objlokiout;
}

#<-----------------------------------Silent Sale Section------------------------------------>

// show sale cart
if(isset($_POST['stc_show_sale_sess'])){
	$order_table = '';  
	$slno = '';  
	$order_table .= '  
		<table class="table table-hover table-responsive" align="centre">
			<thead>
			  <tr>
			    <th scope="col">#</th>
			    <th scope="col" style="width: 20%;">Material Details</th>
			    <th scope="col">Unit</th>
			    <th scope="col">Qty</th>                         
			    <th scope="col">Rate</th>
			    <th scope="col">Amount</th>
			    <th scope="col">GST</th>
			    <th scope="col" width="10%">Amount</th>
			    <th scope="col">Action</th>
			  </tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_silent_sale_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_silent_sale_sess"] as $keys => $values) {  
				$amount=$values["product_qty"] * $values["product_rate"];
				$gstamount = ($amount * $values["product_tax"])/100;
				$slno++;
				$order_table .= ' 
					<tr>
						<td>'.$slno.'</td>
						<td>'.$values["product_name"].'</td>
						<td>'.$values["product_unit"].'</td>
						<td>'.number_format($values["product_qty"], 2).'</td>
						<td>'.number_format($values["product_rate"], 2).'</td>
						<td><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
						<td> '.$values["product_tax"].'% </td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount + $gstamount, 2).'</td>
						<td>
							<a style="color: red;font-size:20px;" href="#" id="'.$values["product_name"].'" class="stcdelsilentsalebtn">
								<span class="material-icons">delete</span>
							</a>
						</td>
					</tr>
				';  
				$total = $total + ($values["product_qty"] * $values["product_rate"]) ;  
				$totalgst +=$gstamount;
			}  
			$gstamount = ($amount * $values["product_tax"])/100;
			$order_table .= ' 
			   <tr>
				  <td colspan="7"><h4 align="right">Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total, 2).'</td>
				  <td><input type="hidden" name="hidden_bvalue" id="stcbasicvalue" value="'.$total.'"></td>
				</tr>
				<tr>
				  <td colspan="7"><h4 align="right">CGST</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
				  <td></td>
				</tr>
				<tr>
				  <td colspan="7"><h4 align="right">SGST</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
				  <td></td>
				</tr>
        		<tr>
				  <td colspan="7"><h4 align="right">Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.number_format($total + $totalgst, 2).'</span></td>
				  <td></td>
				</tr> 
			';  
		}else{
			$order_table.='
				<tr>  
					<td colspan="10" align="center">  
					    <h3>Sale Cart Empty!!!</h3>
					</td>  
				</tr>  
			';
		}
	$order_table .= '
			
		  </tbody>
		</table>
		'; 
	$export=array(
	  "orderTable" => $order_table	  
	); 
	header('Content-type: application/json');
	echo json_encode($export);
		// echo $order_table;
}

// add to cart add_sale session obj
if(isset($_POST['add_sale'])){
	if(isset($_SESSION["stc_silent_sale_sess"])) {  
		$is_available = 0;  
		foreach($_SESSION["stc_silent_sale_sess"] as $keys => $values) {  
			if($_SESSION["stc_silent_sale_sess"][$keys]['product_name'] == $_POST["product_name"]) {  
			     $is_available++;  
			     $_SESSION["stc_silent_sale_sess"][$keys]['product_qty'] = $_SESSION["stc_silent_sale_sess"][$keys]['product_qty'] + $_POST["product_qty"];  
			     echo "Sale Cart Material Quantity Increased!!!";
			}  
		}  
		if($is_available < 1) {  
			$item_array = array(  
				'product_name' 	 => 	$_POST["product_name"], 
				'product_unit' 	 => 	$_POST["product_unit"], 
				'product_tax' 	 => 	$_POST["product_tax"], 
				'product_qty' 	 => 	$_POST["product_qty"], 
				'product_rate' 	 => 	$_POST["product_rate"] 
			);  
			$_SESSION["stc_silent_sale_sess"][] = $item_array;  
			echo "Sale Cart Created & Material Added to Cart!!!";
		}  
	}else{  
		$item_array = array(  
			'product_name' 	 => 	$_POST["product_name"], 
			'product_unit' 	 => 	$_POST["product_unit"], 
			'product_tax' 	 => 	$_POST["product_tax"], 
			'product_qty' 	 => 	$_POST["product_qty"], 
			'product_rate' 	 => 	$_POST["product_rate"] 
		);   
		$_SESSION["stc_silent_sale_sess"][] = $item_array;  
		echo "Material Added to Sale Cart!!!";
	} 
}

// delete from sale cart of session Sale order
if(isset($_POST['stcdelsalelinei'])){
	foreach($_SESSION["stc_silent_sale_sess"] as $keys => $values){  
		if($values["product_name"] == $_POST["product_id"]){  
			unset($_SESSION["stc_silent_sale_sess"][$keys]);  
			echo "Material Removed!!!";  
		}  
	}  
}

// save sale
if(isset($_POST['saleorderby'])){
	$out='';
	$salepartyname		= $_POST['salepartyname'];
	$salerefrno			= $_POST['salerefrno'];
	$salerefrdate		= date('Y-m-d h:i:s', strtotime($_POST['salerefrdate']));
	$saleorderby 		= $_POST['saleorderby'];
	$saleremarks		= $_POST['saleremarks'];

	$fridaysale=new aetherSale();
	$fridaysaleitems=new aetherSale();
	if(empty($salepartyname) || empty($salerefrno) || empty($salerefrdate)){
		$out= "Please Fill All Fields!!!";
	}elseif(empty($_SESSION['stc_silent_sale_sess'])){
		$out= "Please add Materials first.";
	}else{
		$objfridaygrn=$fridaysale->sale_process(
			$salepartyname,
			$salerefrno,
			$salerefrdate,
			$saleorderby,
			$saleremarks
		);
		if($objfridaygrn=='Sale Done!!!'){
			$objfridaygrnitems=$fridaysaleitems->stc_save_sale_pro_items();
			$out=$objfridaygrnitems;
			unset($_SESSION['stc_silent_sale_sess']);
		}else{
			$out .= "Something went Wrong, Purchase not added properly!!!".$objfridaygrn;
		}
	}
	echo $out;
}

// Show sale On sale Page
if(isset($_POST['stccallsale'])){
	$purchasebegdate=date("Y-m-d", strtotime($_POST['begdate']));
	$purchaseenddate=date("Y-m-d", strtotime($_POST['enddate']));
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_call_sale($purchasebegdate, $purchaseenddate);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// filter sale
if(isset($_POST['stcfiltersale'])){
	$search = str_replace(",", "|", $_POST["stcfiltersale"]);
	$salebegdate=date("Y-m-d h:i:s", strtotime($_POST['salebegdate']));
	$saleenddate=date("Y-m-d h:i:s", strtotime($_POST['saleenddate']));
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_filter_sale($search, $salebegdate, $saleenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// update purchase payment
if(isset($_POST['stc_sale_update_payment_hit'])){
	$item_id=$_POST['item_id'];
	$pay_val=$_POST['pay_val'];
	$pay_type=$_POST['pay_type'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_sale_update_payment($item_id, $pay_val, $pay_type);
	echo $objlokiout;
}
?>