<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Silent Invoice --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
// purchase class
class aetherPurchase extends tesseract{	

	// Search product name in trading purchase Page
	public function stc_search_product_name($searchme){
		$purchase_rate='';
		$alagsequery="
			SELECT
				`stc_trading_product_id`,
			    `stc_trading_product_title`,
			    `stc_trading_product_details`,
			    `stc_trading_product_gst`,
			    `stc_trading_product_created_by`
			FROM
			    `stc_trading_product`
			WHERE 
				`stc_trading_product_title` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchme)."' 
				OR 
				`stc_trading_product_details` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchme)."'
			ORDER BY `stc_trading_product_id` 
			ASC LIMIT 0,20
		";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		if(mysqli_num_rows($check_loki) > 0){
			foreach ($check_loki as $row) {
				$odin.='	
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
		             	  	<div class="row">
		             			<div class="el-wrapper">
			            		    <div class="box-up">
			            		        <div class="img-info">
				             	        <div class="info-inner">
				             	            <span class="p-name">'.htmlentities($row["stc_trading_product_title"]).'</span>
				             	            <span class="p-company">GST : '.$row["stc_trading_product_gst"].'% </span>
				             	            <span class="p-qty" >
				             	            	<select class="form-control validate " id="stcpdunit'.$row["stc_trading_product_id"].'" style="color:black;font-weight:bold;text-align:center">
				             	            		<option value="BAG">BAG</option>
				             	            		<option value="KG">KG</option>
				             	            		<option value="MT">MT</option>
				             	            		<option value="MTR">MTR</option>
				             	            		<option value="NOS" selected>NOS</option>
				             	            		<option value="PKT">PKT</option>
				             	            		<option value="ROLL">ROLL</option>
				             	            		<option value="SQMT">SQMT</option>
				             	            	</select>
				             	            </span> 
				             	        	<input
						                	  	id="stcpdqty'.$row["stc_trading_product_id"].'"
						                	  	name="stcpdqty"
						                	  	type="number"
						                	  	placeholder="Enter Quantity"
						                	  	class="form-control validate"
			             	         			style="color:black;font-weight:bold;text-align:center"
						                	/>
				             	        </div>
			             	         	<input 
			             	         		type="hidden" 
			             	         		name="hidden_name" 
			             	         		id="stcpdgst'.$row["stc_trading_product_id"].'" 
			             	         		value="'.htmlentities($row["stc_trading_product_gst"]).'" 
			             	         	/>
			             	         	<input 
			             	         		type="hidden" 
			             	         		name="hidden_name" 
			             	         		id="stcpdname'.$row["stc_trading_product_id"].'" 
			             	         		value="'.htmlentities($row["stc_trading_product_title"]).'" 
			             	         	/>
			             	         	<div class="a-size">
				             	            <input 
					             	          	type="number" 
					             	          	name="stcpdprice" 
					             	          	id="stcpdprice'.$row["stc_trading_product_id"].'" 
					             	          	placeholder="Enter Rate"
					             	          	class="form-control validate"
			             	         			style="color:black;font-weight:bold;text-align:center"
					             	    	/>      	
			             	          	</div>
			            		        </div>
			            		    </div>

		             				<div class="box-down">
		             					<div class="h-bg">
		             						<div class="h-bg-inner"></div>
		             					</div>

		             					<a class="cart add_to_silent_purchase_cart" href="#" id="'.$row["stc_trading_product_id"].'">
		             					<span class="price"></span>
		             					<span class="add-to-cart">
		             						<span class="txt">Add Item</span>
		             					</span>
		             					</a>
		             				</div>
		             			</div>
		             	  	</div>
		             	</div>
							               	
		            	';				
			}
		}else{
			$odin .= '
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<a 
			              href="#" 
			              class="btn btn-primary btn-block text-uppercase mb-3">
			              No Records Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}

	// go data to trading purchase table
	public function purchase_process($silent_purchase_merchantname, $silent_purchase_refreno, $silent_purchase_redredate, $silent_purchase_remarks){
		$maxgst='';
		foreach($_SESSION["stc_silent_purchase_sess"] as $keys => $values) {
			$maxgst=array($values['product_gst']);
		}
		$odin='';
		$date=date("Y-m-d H:i:s");
		$lokiinsertsale=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_trading_purchase`(
			    `stc_trading_purchase_date`,
			    `stc_trading_purchase_refrence_no`,
			    `stc_trading_purchase_refrence_date`,
			    `stc_trading_purchase_purchaser_id`,
			    `stc_trading_purchase_remarks`,
			    `stc_trading_purchase_createdby`
			) VALUES ( 
				'".mysqli_real_escape_string($this->stc_dbs, $date)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $silent_purchase_refreno)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $silent_purchase_redredate)."',
				'".mysqli_real_escape_string($this->stc_dbs, $silent_purchase_merchantname)."',
				'".mysqli_real_escape_string($this->stc_dbs, $silent_purchase_remarks)."',
				'".$_SESSION['stc_trading_user_id']."'
			)
		");
		$odin='';
		if($lokiinsertsale){
			$loki_get_max_id=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_trading_purchase` ORDER BY `stc_trading_purchase`.`stc_trading_purchase_id` DESC
			");
			$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
			$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_trading_purchase_id'];
			if(empty($_SESSION['stc_silent_purchase_sess'])){
				$odin = "Do not leave Items on Sale Order!!!";
			}else{
				foreach ($_SESSION["stc_silent_purchase_sess"] as $key=>$value) {
			    	$friday_loki=mysqli_query($this->stc_dbs, "
			    		INSERT INTO `stc_trading_purchase_items`(
							`stc_trading_purchase_items_purchase_id`,
							`stc_trading_purchase_items_item_id`,
							`stc_trading_purchase_items_qty`,
							`stc_trading_purchase_items_price`,
							`stc_trading_purchase_items_unit`,
							`stc_trading_purchase_items_gst`
			    		) VALUES (
			    			'".mysqli_real_escape_string($this->stc_dbs, $get_purchase_product_id)."', 
			    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_id'])."',
			    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_quantity'])."', 
			    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_price'])."', 
			    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_unit'])."',
			    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_gst'])."'
			    		)
			    	");
			    }
			}
			$odin .= "Purchase Added Successfully!!!";
		}else{
			$odin .= "Please Check & Try Again!!!";
		}
		return $odin;
	}

	// stc search trading purchase
	public function stc_filter_purchase($search, $begdate, $endDate){
		$postatus='';
		$grnopt='';
		$totalpurchased=0;
		$totaldues=0;

		if($search==''){
			$search='';
		}else{
			$search="
				(
					`stc_merchant_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
					OR `stc_trading_purchase_refrence_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
					OR `stc_trading_purchase_remarks` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				) AND 
			";
		}
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_trading_purchase` 
			INNER JOIN `stc_merchant` 
			ON `stc_merchant_id`=`stc_trading_purchase_purchaser_id` 
			INNER JOIN `stc_trading_user` 
			ON `stc_trading_user_id`=`stc_trading_purchase_createdby` 
			WHERE ".$search."(
                DATE(`stc_trading_purchase_date`)
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $begdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $endDate)."'
            )
			ORDER BY DATE(`stc_trading_purchase_date`) 
			DESC
		");
		$odin='
			<table class="table table-hover table-bordered ">
	        	<thead>
	        	  <tr>
	        	    <th class="text-center" scope="col">Purchase Date/<br>Purchase No</th>
	        	    <th class="text-center" scope="col">Merchant Name</th>
	        	    <th class="text-center" scope="col">Reference Date/<br>Reference No</th>
	        	    <th class="text-center" scope="col">Amount</th>
	        	    <th class="text-center" scope="col">Dues</th>
	        	    <th class="text-center" scope="col">Remarks</th>
	        	    <th class="text-center" scope="col">Created By</th>
	        	    <th class="text-center" scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='6' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_trading_purchase_items` 
					WHERE `stc_trading_purchase_items_purchase_id`='".$row["stc_trading_purchase_id"]."'
				");
				$soamount=0;
				$sogstamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$amt = $row2nd['stc_trading_purchase_items_qty'] * $row2nd['stc_trading_purchase_items_price'];
					$gst = $amt * $row2nd['stc_trading_purchase_items_gst']/100;
					$soamount += $amt;
					$sogstamount += $gst;
				}
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$purchasenumber = substr("0000{$row["stc_trading_purchase_id"]}", -$str_length);
              	$total= $soamount + $sogstamount;

              	$purchase_payqry=mysqli_query($this->stc_dbs, "
              		SELECT 
              			`stc_trading_purchase_payment_value` 
              		FROM 
              			`stc_trading_purchase_payment`
					WHERE 
						`stc_trading_purchase_payment_purchase_id`='".$row["stc_trading_purchase_id"]."'
              	");
              	$paid_amt=0;
              	foreach($purchase_payqry as $purchase_payrow){
              		$paid_amt+=$purchase_payrow['stc_trading_purchase_payment_value'];
              	}

              	$dues=$total -$paid_amt;
              	$numberDays=''; 

				$today = date("Y/m/d"); 
				$startTimeStamp = strtotime($row["stc_trading_purchase_date"]);
				$endTimeStamp = strtotime($today);

				$timeDiff = abs($endTimeStamp - $startTimeStamp);

				$numberDays = $timeDiff/86400;  // 86400 seconds in one day

				$numberDays = intval($numberDays);

				$editopt='
					<a 
       					class="stc_tra_purchase_edit" 
       					id="'.$row["stc_trading_purchase_id"].'" 
       					href="#" 
       					style="font-size: 25px;color: black;"
       				><i class="fa fa-edit" aria-hidden="true"></i>
       				</a>
				';

				if($numberDays>15){
					$editopt='';
				}

				$odin.='
					<tr>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_trading_purchase_date"])).'
       						<br>
       						STC/T/P/'.$purchasenumber.'
       					</td>
       					<td class="text-center">
       						'.$row["stc_merchant_name"].'
       					</td>
       					<td class="text-center">
       						'.date('d-m-y', strtotime($row["stc_trading_purchase_refrence_date"])).'
	       					<br>
	       					'.$row["stc_trading_purchase_refrence_no"].'
       					</td>
       					<td class="text-right">
       						'.number_format($total, 2).'
       					</td>
       					<td class="text-right">
       						'.number_format($dues, 2).'
       					</td>
       					<td>
       						'.$row["stc_trading_purchase_remarks"].'
       					</td>
       					<td class="text-center">
       						'.strtoupper($row["stc_trading_user_name"]).'
       					</td>
       					<td class="text-center">
       						'.$editopt.'
       						<a class="stc_print_purchase_items" id="'.$row["stc_trading_purchase_id"].'" href="#" style="font-size: 25px;color: black;">
       							<i class="fa fa-print" aria-hidden="true"></i>
       						</a> <br>
       						<a class="stc_print_payment" id="'.$row["stc_trading_purchase_id"].'" data-bs-toggle="modal" data-bs-target=".stc-purchase-payment-model" pay-val="'.$total.'" href="#" style="font-size: 25px;color: black;">
       							Pay
       						</a> 
       					</td>
            		</tr>         	
		    	';
				$totalpurchased+=$total;
				$totaldues+=$dues;
			}
		}
		$odin.='
			<tr>
				<td class="text-center" colspan="3">Total Purchased :</td>
				<td class="text-right"><b>'.number_format($totalpurchased, 2).'</b></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td class="text-center" colspan="3">Total Dues :</td>
				<td></td>
				<td class="text-right"><b>'.number_format($totaldues, 2).'</b></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		';
		$odin.='
				</tbody>
	       	</table>
		';
		return $odin;
	}

	// call purchase items
	public function stc_filter_purchase_items($purchase_id){
		$odin_getqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_trading_purchase_items_item_id`,
				`stc_trading_product_title`,
				`stc_trading_purchase_items_qty`,
				`stc_trading_purchase_items_price`,
				`stc_trading_purchase_items_unit`,
				`stc_trading_purchase_items_gst`
			FROM
				`stc_trading_purchase_items`
			INNER JOIN `stc_trading_product` 
			ON `stc_trading_purchase_items_item_id`=`stc_trading_product_id`
			WHERE
				`stc_trading_purchase_items_purchase_id` = '".mysqli_real_escape_string($this->stc_dbs, $purchase_id)."'
			ORDER BY `stc_trading_product_title` ASC
		");

		$odin='
			<table class="table table-hover table-bordered ">
	        	<thead>
	        	  <tr>
	        	    <th class="text-center" scope="col">Sl No</th>
	        	    <th class="text-center" scope="col">Item Code</th>
	        	    <th class="text-center" scope="col">Item Desc</th>
	        	    <th class="text-center" scope="col">Unit</th>
	        	    <th class="text-center" scope="col">Qty</th>
	        	    <th class="text-center" scope="col">Price</th>
	        	    <th class="text-center" scope="col">Amount</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
	    $slno=0;
	    $total=0;
	    if(mysqli_num_rows($odin_getqry)>0){
	    	foreach($odin_getqry as $odin_getqryrow){
	    		$slno++;
	    		$price=$odin_getqryrow['stc_trading_purchase_items_price'] + ($odin_getqryrow['stc_trading_purchase_items_price'] * $odin_getqryrow['stc_trading_purchase_items_gst']/100);
	    		$amount=$price * $odin_getqryrow['stc_trading_purchase_items_qty'];
	    		$odin.='
	    			<tr>
	    				<td class="text-center">'.$slno.'.</td>
	    				<td class="text-center">'.$odin_getqryrow['stc_trading_purchase_items_item_id'].'</td>
	    				<td>'.$odin_getqryrow['stc_trading_product_title'].'</td>
	    				<td class="text-center">'.$odin_getqryrow['stc_trading_purchase_items_unit'].'</td>
	    				<td class="text-right">'.number_format($odin_getqryrow['stc_trading_purchase_items_qty'], 2).'</td>
	    				<td class="text-right">'.number_format($price, 2).'</td>
	    				<td class="text-right">'.number_format($amount, 2).'</td>
	    			</tr>
	    		';
	    		$total+=$amount;
	    	}
	    	$odin.='
	    		<tr>
	    			<td class="text-right" colspan="6">Total :</td>
	    			<td class="text-right">'.number_format($total, 2).'</td>
	    		</tr>
	    	';
	    }else{
	    	$odin.='
	    		<tr><td>No Record Found!!!</td></tr>
	    	';
	    }
		$odin.='
				</tbody>
	       	</table>
		';
		return $odin;
	}

	// set payment
	public function stc_payment_set($purchase_id, $payment_type, $payment_amount, $payment_remarks, $check_amount){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$paid_amount=0;
		$odin_checkqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_trading_purchase_payment_value` 
			FROM 
				`stc_trading_purchase_payment` 
			WHERE 
				`stc_trading_purchase_payment_purchase_id` = '".mysqli_real_escape_string($this->stc_dbs, $purchase_id)."'
		");
		foreach($odin_checkqry as $odin_checkrow){
			$paid_amount+=$odin_checkrow['stc_trading_purchase_payment_value'];
		}
		$due_amount=$check_amount - $paid_amount;
		if($payment_amount>$due_amount){
			$odin='Invalid Amount.';
		}else{
			$odin_paycheckqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_trading_purchase_payment`(
					`stc_trading_purchase_payment_date`,
					`stc_trading_purchase_payment_purchase_id`,
					`stc_trading_purchase_payment_type`,
					`stc_trading_purchase_payment_value`,
					`stc_trading_purchase_payment_remarks`,
					`stc_trading_purchase_payment_created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".mysqli_real_escape_string($this->stc_dbs, $purchase_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $payment_type)."',
					'".mysqli_real_escape_string($this->stc_dbs, $payment_amount)."',
					'".mysqli_real_escape_string($this->stc_dbs, $payment_remarks)."',
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_trading_user_id'])."'
				)
			");
			if($odin_paycheckqry){
				$odin='Payment Done!!!';
			}else{
				$odin='Hmmm!!! Payment not done properly. Please check & try again';
			}
		}
		return $odin;
	}

	// call purchase items for edit
	public function stc_sale_call_for_purchaseedit($purchase_id){
		$odin='';
		$purchase_items='';

		$odincall_saleitemqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_trading_purchase_items_id`,
			    `stc_trading_purchase_items_purchase_id`,
				`stc_trading_product_id`,
				`stc_trading_product_title`,
			    `stc_trading_purchase_items_qty`,
			    `stc_trading_purchase_items_price`,
			    `stc_trading_purchase_items_unit`,
			    `stc_trading_purchase_items_gst`
			FROM
				`stc_trading_purchase_items`
			INNER JOIN 
				`stc_trading_product` 
			ON 
				`stc_trading_product_id`=`stc_trading_purchase_items_item_id` 
			WHERE
				`stc_trading_purchase_items_purchase_id` = '".mysqli_real_escape_string($this->stc_dbs, $purchase_id)."'
			ORDER BY `stc_trading_product_title` DESC
		");

		$total = 0;  
		$totalgst = 0;
		$slno=0;
		foreach($odincall_saleitemqry as $values) {  
			$amount=$values["stc_trading_purchase_items_price"];
			$gstamount = $amount * ($values["stc_trading_purchase_items_gst"]/100);
			$slno++;
			$price=$amount + $gstamount;
			
			$purchase_items .= ' 
				<tr>
					<td align="center">'.$slno.'.</td>
					<td>'.$values['stc_trading_product_title'].'</td>
					<td>'.$values["stc_trading_purchase_items_unit"].'</td>
					<td>
					  <input
					  	style="padding: 5px;height: 40px;"
					    type="text"
					    id="stc-trading-item-qtysc'.$values["stc_trading_purchase_items_id"].'"
					    class="validate"
					    placeholder="Quantity"
					    value="'.number_format($values["stc_trading_purchase_items_qty"], 2, '.', '').'"
					  />
					</td>
					<td class="text-right">
					  <input
					  	style="padding: 5px;height: 40px;"
					    type="text"
					    id="stc-trading-item-ratesc'.$values["stc_trading_purchase_items_id"].'"
					    class="validate"
					    placeholder="Quantity"
					    value="'.number_format($price, 2, '.', '').'"
					  />
					  <input type="hidden" id="stc-trading-item-gstsc'.$values["stc_trading_purchase_items_id"].'" value="'.$values["stc_trading_purchase_items_gst"].'">
					</td>
					<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($price * $values['stc_trading_purchase_items_qty'], 2).'</td>
					<td align="center">
						<a href="#" class="btn btn-primary stc-customer-update-item-edit" id="'.$values["stc_trading_purchase_items_id"].'"><i class="fa fa-check-circle"></i></a>
						<a href="#" class="btn btn-danger stc-customer-remove-edit" id="'.$values["stc_trading_purchase_items_id"].'"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
			';  
			$total += $values["stc_trading_purchase_items_qty"] * $price;  
			$totalgst +=$gstamount;
		}  
		$purchase_items .= ' 
     		<tr>
			  <td colspan="5"><h4 align="right">Total :</h4></td>
			  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.number_format(round($total, 2), 2).'</span></td>
			</tr>
		';  

		$odin = array(
			'purchase_id' 				=> $purchase_id,
			'purchase_items' 				=> $purchase_items
		);
		return $odin;
	}

	// add item on edit
	public function stc_trading_sale_item_add($sale_id, $item_id, $item_unit, $item_qty, $item_rate){
		$odin='';
		$odin_checkqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_trading_purchase_items_id`
			FROM 
				`stc_trading_purchase_items` 
			WHERE 
				`stc_trading_purchase_items_id`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."' 
			AND 
				`stc_trading_purchase_items_purchase_id`='".mysqli_real_escape_string($this->stc_dbs, $sale_id)."' 
		");
		if(mysqli_num_rows($odin_checkqry)>0){
			$odin = 'Item Already Added!!!';
		}else{
			$item_gst='';
			$odin_callgstqry=mysqli_query($this->stc_dbs, "
				SELECT 
           			`stc_trading_product_gst`
            	FROM 
            		`stc_trading_product`
            	WHERE 
					`stc_trading_product_id`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."' 
			");
			foreach($odin_callgstqry as $odin_callgstrow){
				$item_gst=$odin_callgstrow['stc_trading_product_gst'];
			}

			$basic=$item_rate;
			$gstamount = $basic-($basic*(100/(100+$item_gst)));
			$saleprice=$basic - $gstamount;

			$odin_addqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_trading_purchase_items`(
					`stc_trading_purchase_items_purchase_id`,
					`stc_trading_purchase_items_item_id`,
					`stc_trading_purchase_items_qty`,
					`stc_trading_purchase_items_price`,
					`stc_trading_purchase_items_unit`,
					`stc_trading_purchase_items_gst`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $sale_id)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $item_id)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $item_qty)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $saleprice)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $item_unit)."',
					'".mysqli_real_escape_string($this->stc_dbs, $item_gst)."'
				)
			");
			if($odin_addqry){
				$odin = 'Item Added Successfully!!!';
			}else{
				$odin = 'Hmmm!!! Something Went Wrong On Item Adding. Please Reheck & Try Again.';
			}
		}
		return $odin;
	}

	// update items
	public function stc_trading_purchase_item_update($item_id, $item_qty, $item_rate, $item_gst){
		$odin='';
		$odin_checkqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_trading_purchase_items_id` 
			FROM 
				`stc_trading_purchase_items` 
			WHERE 
				`stc_trading_purchase_items_id` = '".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
		");
		if(mysqli_num_rows($odin_checkqry)>0){
			$basic=$item_rate;
			$gstamount = $basic-($basic*(100/(100+$item_gst)));
			$saleprice=$basic - $gstamount;
			$odin_updateqry=mysqli_query($this->stc_dbs, "
				UPDATE
				    `stc_trading_purchase_items`
				SET
					`stc_trading_purchase_items_qty` = '".mysqli_real_escape_string($this->stc_dbs, $item_qty)."',
					`stc_trading_purchase_items_price` = '".mysqli_real_escape_string($this->stc_dbs, $saleprice)."'
				WHERE
				    `stc_trading_purchase_items_id` = '".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
			");
			if($odin_updateqry){
				$odin='Item Updated Successfully.';
			}else{
				$odin='Hmmmm!!! Something Went Wrong On Item Updation. Please Reheck & Try Again.';
			}
		}else{
			$odin='Item Not Found!!! Please Reload.';
		}	
		return $odin;
	}

	// remove items
	public function stc_trading_purchase_item_remove($item_id){
		$odin='';
		$odin_removeqry=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_trading_purchase_items` WHERE `stc_trading_purchase_items_id` = '".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
		");
		if($odin_removeqry){
			$odin="Item Removed Successfully.";
		}else{
			$odin="Hmmm!!! Something Went Wrong. Item Can't Removed Successfully.";
		}
		return $odin;
	}
}

// sale class
class aetherSale extends tesseract{	
	// Search product name in trading purchase Page
	public function stc_search_product_name($searchme){
		$purchase_rate='';
		$alagsequery="
			SELECT
				`stc_trading_product_id`,
			    `stc_trading_product_title`,
			    `stc_trading_product_details`,
			    `stc_trading_product_gst`,
			    `stc_trading_product_created_by`
			FROM
			    `stc_trading_product`
			WHERE 
				`stc_trading_product_title` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchme)."' 
				OR 
				`stc_trading_product_details` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchme)."'
			ORDER BY `stc_trading_product_title` 
			ASC LIMIT 0,20
		";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		if(mysqli_num_rows($check_loki) > 0){
			foreach ($check_loki as $row) {
				$prerate_qry=mysqli_query($this->stc_dbs, "
					SELECT DISTINCT
						`stc_trading_purchase_items_price`,
						`stc_trading_purchase_items_gst`,
						`stc_trading_purchase_items_unit`
					FROM
						`stc_trading_purchase_items`
					WHERE
						`stc_trading_purchase_items_item_id`='".$row['stc_trading_product_id']."'
					ORDER BY `stc_trading_purchase_items_id` DESC LIMIT 0,4
				");
				foreach($prerate_qry as $prerate_row){
					$gst=$prerate_row['stc_trading_purchase_items_price'] * $prerate_row['stc_trading_purchase_items_gst']/100;
					$rate=$prerate_row['stc_trading_purchase_items_price'] + $gst;
					$purchase_rate.='<option value="'.number_format($rate, 2).'">'.number_format($rate, 2).'/'.$prerate_row['stc_trading_purchase_items_unit'].'</option>';
				}
				$odin.='	
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
		             	  	<div class="row">
		             			<div class="el-wrapper">
			            		    <div class="box-up">
			            		        <div class="img-info">
				             	        <div class="info-inner">
				             	            <span class="p-name">'.htmlentities($row["stc_trading_product_title"]).'</span>
				             	            <span class="p-company">GST : '.$row["stc_trading_product_gst"].'% </span>
				             	            <span class="p-qty" >
				             	            	<select class="form-control validate " id="stcpdunit'.$row["stc_trading_product_id"].'" style="color:black;font-weight:bold;text-align:center">
				             	            		<option value="BAG">BAG</option>
				             	            		<option value="KG">KG</option>
				             	            		<option value="MT">MT</option>
				             	            		<option value="MTR">MTR</option>
				             	            		<option value="NOS" selected>NOS</option>
				             	            		<option value="PKT">PKT</option>
				             	            		<option value="ROLL">ROLL</option>
				             	            		<option value="SQMT">SQMT</option>
				             	            	</select>
				             	            </span> 
				             	        	<input
						                	  	id="stcpdqty'.$row["stc_trading_product_id"].'"
						                	  	name="stcpdqty"
						                	  	type="number"
						                	  	placeholder="Enter Quantity"
						                	  	class="form-control validate"
			             	         			style="color:black;font-weight:bold;text-align:center"
						                	/>
				             	        </div>
			             	         	<input 
			             	         		type="hidden" 
			             	         		name="hidden_name" 
			             	         		id="stcpdgst'.$row["stc_trading_product_id"].'" 
			             	         		value="'.htmlentities($row["stc_trading_product_gst"]).'" 
			             	         	/>
			             	         	<input 
			             	         		type="hidden" 
			             	         		name="hidden_name" 
			             	         		id="stcpdname'.$row["stc_trading_product_id"].'" 
			             	         		value="'.htmlentities($row["stc_trading_product_title"]).'" 
			             	         	/>
			             	         	<div class="a-size">
				             	        	<select class="form-control validate " id="stcpdrate'.$row["stc_trading_product_id"].'" style="color:black;font-weight:bold;text-align:center">'.$purchase_rate.'
				             	        	</select>
				             	            <input 
					             	          	type="number" 
					             	          	name="stcpdprice" 
					             	          	id="stcpdprice'.$row["stc_trading_product_id"].'" 
					             	          	placeholder="Enter Rate"
					             	          	class="form-control validate"
			             	         			style="color:black;font-weight:bold;text-align:center"
					             	    	/>      	
			             	          	</div>
			            		        </div>
			            		    </div>

		             				<div class="box-down">
		             					<div class="h-bg">
		             						<div class="h-bg-inner"></div>
		             					</div>

		             					<a class="cart add_to_silent_sale_cart" href="#" id="'.$row["stc_trading_product_id"].'">
		             					<span class="price"></span>
		             					<span class="add-to-cart">
		             						<span class="txt">Add Item</span>
		             					</span>
		             					</a>
		             				</div>
		             			</div>
		             	  	</div>
		             	</div>
							               	
		            	';				
			}
		}else{
			$odin .= '
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<a 
			              href="#" 
			              class="btn btn-primary btn-block text-uppercase mb-3">
			              No Records Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}

	// go data to trading sale table
	public function sale_process($sale_date, $customer_name, $ship_address, $order_on, $delever_on, $order_by, $delever_through, $remarks){
		$maxgst='';
		foreach($_SESSION["stc_silent_sale_sess"] as $keys => $values) {
			$maxgst=array($values['product_gst']);
		}
		$odin='';
		$date=date("Y-m-d H:i:s");
		$lokiinsertsale=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_trading_sale`(
				`stc_trading_sale_date`,
				`stc_trading_sale_cust_id`,
				`stc_trading_sale_cust_address`,
				`stc_trading_sale_order_date`,
				`stc_trading_sale_delevered_date`,
				`stc_trading_sale_order_by`,
				`stc_trading_sale_delever_through`,
				`stc_trading_sale_status`,
				`stc_trading_sale_remarks`,
				`stc_trading_sale_created_by`
			) VALUES ( 
				'".mysqli_real_escape_string($this->stc_dbs, $sale_date)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $customer_name)."',
				'".mysqli_real_escape_string($this->stc_dbs, $ship_address)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_on)."',
				'".mysqli_real_escape_string($this->stc_dbs, $delever_on)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $order_by)."',
				'".mysqli_real_escape_string($this->stc_dbs, $delever_through)."',
				'billed',
				'".mysqli_real_escape_string($this->stc_dbs, $remarks)."',
				'".$_SESSION['stc_trading_user_id']."'
			)
		");
		$odin='';
		if($lokiinsertsale){
			$loki_get_max_id=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_trading_sale` ORDER BY `stc_trading_sale`.`stc_trading_sale_id` DESC
			");
			$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
			$get_sale_product_id=$stc_purchase_product_items_order_id['stc_trading_sale_id'];
			if(empty($_SESSION['stc_silent_sale_sess'])){
				$odin = "Do not leave Items on Sale Order!!!";
			}else{
				foreach ($_SESSION["stc_silent_sale_sess"] as $key=>$value) {
			    	$friday_loki=mysqli_query($this->stc_dbs, "
			    		INSERT INTO `stc_trading_sale_items`(
							`stc_trading_sale_items_sale_id`,
							`stc_trading_sale_items_item_id`,
							`stc_trading_sale_items_item_qty`,
							`stc_trading_sale_items_item_price`,
							`stc_trading_sale_items_item_unit`,
							`stc_trading_sale_items_item_gst`
			    		) VALUES (
			    			'".mysqli_real_escape_string($this->stc_dbs, $get_sale_product_id)."', 
			    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_id'])."',
			    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_quantity'])."', 
			    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_price'])."', 
			    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_unit'])."',
			    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_gst'])."'
			    		)
			    	");
			    }
			}
			$odin .= "Sale Added Successfully!!!";
		}else{
			$odin .= "Please Check & Try Again!!!";
		}
		return $odin;
	}

	// stc search trading sale
	public function stc_filter_sale($search, $begdate, $endDate){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_trading_sale` 
			INNER JOIN `stc_trading_customer` 
			ON `stc_trading_customer_id`=`stc_trading_sale_cust_id` 
			INNER JOIN `stc_trading_user` 
			ON `stc_trading_user_id`=`stc_trading_sale_created_by` 
			WHERE (
				`stc_trading_customer_title` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_trading_sale_id` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_trading_customer_cont_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_trading_sale_order_by` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_trading_sale_remarks` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			) AND (
                DATE(`stc_trading_sale_date`)
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $begdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $endDate)."'
            )
			ORDER BY `stc_trading_sale`.`stc_trading_sale_id` 
			DESC LIMIT 0,20
		");
		$odin='
			<table class="table table-hover table-bordered ">
	        	<thead>
	        	  <tr>
	        	    <th class="text-center" scope="col">Sale Date/<br>Sale No</th>
	        	    <th class="text-center" scope="col">Customer Name</th>
	        	    <th class="text-center" scope="col">Address</th>
	        	    <th class="text-center" scope="col">Contact No</th>
	        	    <th class="text-center" scope="col">Order Date/<br>Order By</th>
	        	    <th class="text-center" scope="col">Deliver Date/<br>Deliver By</th>
	        	    <th class="text-center" scope="col">Amount</th>
	        	    <th class="text-center" scope="col">Dues</th>
	        	    <th class="text-center" scope="col">Remarks</th>
	        	    <th class="text-center" scope="col">Status</th>
	        	    <th class="text-center" scope="col">Created By</th>
	        	    <th class="text-center" scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='12' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_trading_sale_items` 
					WHERE `stc_trading_sale_items_sale_id`='".$row["stc_trading_sale_id"]."'
				");
				$soamount=0;
				$sogstamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$amt = $row2nd['stc_trading_sale_items_item_qty'] * $row2nd['stc_trading_sale_items_item_price'];
					$gst = $amt * $row2nd['stc_trading_sale_items_item_gst']/100;
					$soamount += $amt;
					$sogstamount += $gst;
				}
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$salenumber = substr("0000{$row["stc_trading_sale_id"]}", -$str_length);
              	$total= $soamount + $sogstamount;

              	$sale_payqry=mysqli_query($this->stc_dbs, "
              		SELECT 
              			`stc_trading_sale_payment_value` 
              		FROM 
              			`stc_trading_sale_payment`
					WHERE 
						`stc_trading_sale_payment_sale_id`='".$row["stc_trading_sale_id"]."'
              	");
              	$paid_amt=0;
              	foreach($sale_payqry as $sale_payrow){
              		$paid_amt+=$sale_payrow['stc_trading_sale_payment_value'];
              	}

              	$dues=$total -$paid_amt;
              	$numberDays=''; 

				$today = date("Y/m/d"); 
				$startTimeStamp = strtotime($row["stc_trading_sale_date"]);
				$endTimeStamp = strtotime($today);

				$timeDiff = abs($endTimeStamp - $startTimeStamp);

				$numberDays = $timeDiff/86400;  // 86400 seconds in one day

				if($dues>10){
					$numberDays = '<h6 style="color:red;">'.intval($numberDays).'</h6>';
				}else{
					$numberDays = '<h6">0 </h6>';
				}

				$editopt='
					<a 
       					class="stc_tra_sale_edit" 
       					id="'.$row["stc_trading_sale_id"].'" 
       					href="#" 
       					style="font-size: 25px;color: black;"
       				><i class="fa fa-edit" aria-hidden="true"></i>
       				</a>
				';

				if($numberDays>15){
					$editopt='';
				}
				$odin.='
					<tr>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_trading_sale_date"])).'
       						<br>
       						STC/T/S/'.$salenumber.'
       					</td>
       					<td class="text-center">
       						'.$row["stc_trading_customer_title"].'
       					</td>
       					<td class="text-center">
       						'.$row["stc_trading_customer_address"].'
       					</td>
       					<td class="text-center">
       						'.$row["stc_trading_customer_cont_no"].'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_trading_sale_order_date"])).'
       						<br>
       						'.$row['stc_trading_sale_order_by'].'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_trading_sale_delevered_date"])).'
       						<br>
       						'.$row['stc_trading_sale_delever_through'].'
       					</td>
       					<td class="text-right">
       						'.number_format($total, 2).'
       					</td>
       					<td class="text-right">
       						'.number_format($dues, 2).'
       					</td>
       					<td>
       						'.$row["stc_trading_sale_remarks"].'
       					</td>
       					<td>
       						'.$row["stc_trading_sale_status"].'
       					</td>
       					<td class="text-center">
       						'.strtoupper($row["stc_trading_user_name"]).'
       					</td>
       					<td class="text-center">
       						'.$editopt.'
       						<a 
       							class="stc_print_purchase_items" 
       							id="'.$row["stc_trading_sale_id"].'" 
       							href="silent-invoice-print-preview.php?pid='.$row["stc_trading_sale_id"].'" 
       							style="font-size: 25px;color: black;"
       						><i class="fa fa-print" aria-hidden="true"></i>
       						</a>
       						<a class="stc_print_payment" id="'.$row["stc_trading_sale_id"].'" pay-val="'.$total.'" href="#" style="font-size: 25px;color: black;">
       							Pay
       						</a> 
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

	// stc search trading sale by date
	public function stc_filter_sale_bydate($begdate, $endDate){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_trading_sale` 
			INNER JOIN `stc_trading_customer` 
			ON `stc_trading_customer_id`=`stc_trading_sale_cust_id` 
			INNER JOIN `stc_trading_user` 
			ON `stc_trading_user_id`=`stc_trading_sale_created_by` 
			WHERE 
				DATE(`stc_trading_sale_date`)
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $begdate)."'
				AND '".mysqli_real_escape_string($this->stc_dbs, $endDate)."'
			ORDER BY `stc_trading_sale`.`stc_trading_sale_id` 
			DESC
		");
		$odin='
			<table class="table table-hover table-bordered ">
	        	<thead>
	        	  <tr>
	        	    <th class="text-center" scope="col">Sale Date/<br>Sale No</th>
	        	    <th class="text-center" scope="col">Customer Name</th>
	        	    <th class="text-center" scope="col">Address</th>
	        	    <th class="text-center" scope="col">Contact No</th>
	        	    <th class="text-center" scope="col">Order Date/<br>Order By</th>
	        	    <th class="text-center" scope="col">Deliver Date/<br>Deliver By</th>
	        	    <th class="text-center" scope="col">Amount</th>
	        	    <th class="text-center" scope="col">Dues</th>
	        	    <th class="text-center" scope="col">Remarks</th>
	        	    <th class="text-center" scope="col">Status</th>
	        	    <th class="text-center" scope="col">Created By</th>
	        	    <th class="text-center" scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='12' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_trading_sale_items` 
					WHERE `stc_trading_sale_items_sale_id`='".$row["stc_trading_sale_id"]."'
				");
				$soamount=0;
				$sogstamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$amt = $row2nd['stc_trading_sale_items_item_qty'] * $row2nd['stc_trading_sale_items_item_price'];
					$gst = $amt * $row2nd['stc_trading_sale_items_item_gst']/100;
					$soamount += $amt;
					$sogstamount += $gst;
				}
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$salenumber = substr("0000{$row["stc_trading_sale_id"]}", -$str_length);
              	$total= $soamount + $sogstamount;

              	$sale_payqry=mysqli_query($this->stc_dbs, "
              		SELECT 
              			`stc_trading_sale_payment_value` 
              		FROM 
              			`stc_trading_sale_payment`
					WHERE 
						`stc_trading_sale_payment_sale_id`='".$row["stc_trading_sale_id"]."'
              	");
              	$paid_amt=0;
              	foreach($sale_payqry as $sale_payrow){
              		$paid_amt+=$sale_payrow['stc_trading_sale_payment_value'];
              	}

              	$dues=$total -$paid_amt;
              	
              	$numberDays=''; 

				$today = date("Y/m/d"); 
				$startTimeStamp = strtotime($row["stc_trading_sale_date"]);
				$endTimeStamp = strtotime($today);

				$timeDiff = abs($endTimeStamp - $startTimeStamp);

				$numberDays = $timeDiff/86400;  // 86400 seconds in one day

				if($dues>10){
					$numberDays = '<h6 style="color:red;">'.intval($numberDays).'</h6>';
				}else{
					$numberDays = '<h6">0 </h6>';
				}

				$editopt='
					<a 
       					class="stc_tra_sale_edit" 
       					id="'.$row["stc_trading_sale_id"].'" 
       					href="#" 
       					style="font-size: 25px;color: black;"
       				><i class="fa fa-edit" aria-hidden="true"></i>
       				</a>
				';

				if($numberDays>15){
					$editopt='';
				}
				$odin.='
					<tr>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_trading_sale_date"])).'
       						<br>
       						STC/T/S/'.$salenumber.'
       					</td>
       					<td class="text-center">
       						'.$row["stc_trading_customer_title"].'
       					</td>
       					<td class="text-center">
       						'.$row["stc_trading_customer_address"].'
       					</td>
       					<td class="text-center">
       						'.$row["stc_trading_customer_cont_no"].'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_trading_sale_order_date"])).'
       						<br>
       						'.$row['stc_trading_sale_order_by'].'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_trading_sale_delevered_date"])).'
       						<br>
       						'.$row['stc_trading_sale_delever_through'].'
       					</td>
       					<td class="text-right">
       						'.number_format($total, 2).'
       					</td>
       					<td class="text-right">
       						'.number_format($dues, 2).'
       					</td>
       					<td>
       						'.$row["stc_trading_sale_remarks"].'
       					</td>
       					<td>
       						'.$row["stc_trading_sale_status"].'
       					</td>
       					<td class="text-center">
       						'.strtoupper($row["stc_trading_user_name"]).'
       					</td>
       					<td class="text-center">
       						'.$editopt.'
       						<a 
       							class="stc_print_purchase_items" 
       							id="'.$row["stc_trading_sale_id"].'" 
       							href="silent-invoice-print-preview.php?pid='.$row["stc_trading_sale_id"].'" 
       							style="font-size: 25px;color: black;"
       						><i class="fa fa-print" aria-hidden="true"></i>
       						</a>
       						<a class="stc_print_payment" id="'.$row["stc_trading_sale_id"].'" pay-val="'.$total.'" href="#" style="font-size: 25px;color: black;">
       							Pay
       						</a> 
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

	// call sale for edit
	public function stc_sale_call_for_edit($sale_id){
		$odin='';
		$sale_cust_name='';
		$sale_cust_addr='';
		$sale_order_by='';
		$sale_deliver_through='';
		$sale_remarks='';
		$sale_items='';
		$odincall_saleqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_trading_customer_id`,
				`stc_trading_customer_title`,
				`stc_trading_sale_cust_address`,
				`stc_trading_sale_order_by`,
				`stc_trading_sale_delever_through`,
				`stc_trading_sale_remarks`
			FROM
				`stc_trading_sale`
			INNER JOIN 
				`stc_trading_customer` 
			ON 
				`stc_trading_sale_cust_id`=`stc_trading_customer_id` 
			WHERE
				`stc_trading_sale_id` = '".mysqli_real_escape_string($this->stc_dbs, $sale_id)."'
			ORDER BY `stc_trading_sale_id` DESC
			LIMIT 0,1
		");

		foreach($odincall_saleqry as $odincall_salerow){
			$odin_callcustqry=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_trading_customer_id`,
					`stc_trading_customer_title`
				FROM `stc_trading_customer` 
				ORDER BY `stc_trading_customer_title` ASC
			");
			$cust_name='';
			foreach($odin_callcustqry as $odin_callcustrow){
				if($odin_callcustrow['stc_trading_customer_id']==$odincall_salerow['stc_trading_customer_id']){
					$cust_name.='<option value="'.$odincall_salerow['stc_trading_customer_id'].'" selected>'.$odincall_salerow['stc_trading_customer_title'].'</option>';
				}else{
					$cust_name.='<option value="'.$odincall_salerow['stc_trading_customer_id'].'">'.$odin_callcustrow['stc_trading_customer_title'].'</option>';
				}
			}
			$sale_cust_name=$cust_name;
			$sale_cust_addr=$odincall_salerow['stc_trading_sale_cust_address'];
			$sale_order_by=$odincall_salerow['stc_trading_sale_order_by'];
			$sale_deliver_through=$odincall_salerow['stc_trading_sale_delever_through'];
			$sale_remarks=$odincall_salerow['stc_trading_sale_remarks'];
		}


		$odincall_saleitemqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_trading_sale_items_id`,
				`stc_trading_sale_items_sale_id`,
				`stc_trading_product_id`,
				`stc_trading_product_title`,
				`stc_trading_sale_items_item_qty`,
				`stc_trading_sale_items_item_price`,
				`stc_trading_sale_items_item_unit`,
				`stc_trading_sale_items_item_gst`
			FROM
				`stc_trading_sale_items`
			INNER JOIN 
				`stc_trading_product` 
			ON 
				`stc_trading_product_id`=`stc_trading_sale_items_item_id` 
			WHERE
				`stc_trading_sale_items_sale_id` = '".mysqli_real_escape_string($this->stc_dbs, $sale_id)."'
			ORDER BY `stc_trading_product_title` DESC
		");

		$total = 0;  
		$totalgst = 0;
		$slno=0;
		foreach($odincall_saleitemqry as $values) {  
			$amount=$values["stc_trading_sale_items_item_price"];
			$gstamount = $amount * ($values["stc_trading_sale_items_item_gst"]/100);
			$slno++;
			$price=$amount + $gstamount;
			
			$sale_items .= ' 
				<tr>
					<td align="center">'.$slno.'.</td>
					<td>'.$values['stc_trading_product_title'].'</td>
					<td>'.$values["stc_trading_sale_items_item_unit"].'</td>
					<td>
					  <input
					  	style="padding: 5px;height: 40px;"
					    type="text"
					    id="stc-trading-item-qtysc'.$values["stc_trading_sale_items_id"].'"
					    class="validate"
					    placeholder="Quantity"
					    value="'.number_format($values["stc_trading_sale_items_item_qty"], 2, '.', '').'"
					  />
					</td>
					<td class="text-right">
					  <input
					  	style="padding: 5px;height: 40px;"
					    type="text"
					    id="stc-trading-item-ratesc'.$values["stc_trading_sale_items_id"].'"
					    class="validate"
					    placeholder="Quantity"
					    value="'.number_format($price, 2, '.', '').'"
					  />
					  <input type="hidden" id="stc-trading-item-gstsc'.$values["stc_trading_sale_items_id"].'" value="'.$values["stc_trading_sale_items_item_gst"].'">
					</td>
					<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($price * $values['stc_trading_sale_items_item_qty'], 2).'</td>
					<td align="center">
						<a href="#" class="btn btn-primary stc-customer-update-item-edit" id="'.$values["stc_trading_sale_items_id"].'"><i class="fa fa-check-circle"></i></a>
						<a href="#" class="btn btn-danger stc-customer-remove-edit" id="'.$values["stc_trading_sale_items_id"].'"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
			';  
			$total += $values["stc_trading_sale_items_item_qty"] * $price;  
			$totalgst +=$gstamount;
		}  
		$sale_items .= ' 
     		<tr>
			  <td colspan="5"><h4 align="right">Total :</h4></td>
			  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.number_format(round($total, 2), 2).'</span></td>
			</tr>
		';  

		$odin = array(
			'sale_id' 					=> $sale_id,
			'sale_cust_name' 			=> $sale_cust_name,
			'sale_cust_addr' 			=> $sale_cust_addr,
			'sale_order_by' 			=> $sale_order_by,
			'sale_deliver_through' 		=> $sale_deliver_through,
			'sale_remarks' 				=> $sale_remarks,
			'sale_items' 				=> $sale_items
		);
		return $odin;
	}

	// call sale item
	public function stc_trading_sale_item_call($sale_id){
		$odin='';
		$odincall_saleitemqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_trading_sale_items_id`,
				`stc_trading_sale_items_sale_id`,
				`stc_trading_product_id`,
				`stc_trading_product_title`,
				`stc_trading_sale_items_item_qty`,
				`stc_trading_sale_items_item_price`,
				`stc_trading_sale_items_item_unit`,
				`stc_trading_sale_items_item_gst`
			FROM
				`stc_trading_sale_items`
			INNER JOIN 
				`stc_trading_product` 
			ON 
				`stc_trading_product_id`=`stc_trading_sale_items_item_id` 
			WHERE
				`stc_trading_sale_items_sale_id` = '".mysqli_real_escape_string($this->stc_dbs, $sale_id)."'
			ORDER BY `stc_trading_product_title` DESC
		");

		$total = 0;  
		$totalgst = 0;
		$slno=0;
		foreach($odincall_saleitemqry as $values) {  
			$amount=$values["stc_trading_sale_items_item_price"];
			$gstamount = $amount * ($values["stc_trading_sale_items_item_gst"]/100);
			$slno++;
			$price=$amount + $gstamount;
			
			$odin .= ' 
				<tr>
					<td align="center">'.$slno.'.</td>
					<td>'.$values['stc_trading_product_title'].'</td>
					<td>'.$values["stc_trading_sale_items_item_unit"].'</td>
					<td>
					  <input
					  	style="padding: 5px;height: 40px;"
					    type="text"
					    id="stc-trading-item-qtysc'.$values["stc_trading_sale_items_id"].'"
					    class="validate"
					    placeholder="Quantity"
					    value="'.number_format($values["stc_trading_sale_items_item_qty"], 2, '.', '').'"
					  />
					</td>
					<td class="text-right">
					  <input
					  	style="padding: 5px;height: 40px;"
					    type="text"
					    id="stc-trading-item-ratesc'.$values["stc_trading_sale_items_id"].'"
					    class="validate"
					    placeholder="Quantity"
					    value="'.number_format($price, 2, '.', '').'"
					  />
					  <input type="hidden" id="stc-trading-item-gstsc'.$values["stc_trading_sale_items_id"].'" value="'.$values["stc_trading_sale_items_item_gst"].'">
					</td>
					<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($price * $values['stc_trading_sale_items_item_qty'], 2).'</td>
					<td align="center">
						<a href="#" class="btn btn-primary stc-customer-update-item-edit" id="'.$values["stc_trading_sale_items_id"].'"><i class="fa fa-check-circle"></i></a>
						<a href="#" class="btn btn-danger stc-customer-remove-edit" id="'.$values["stc_trading_sale_items_id"].'"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
			';  
			$total += $values["stc_trading_sale_items_item_qty"] * $price;  
			$totalgst +=$gstamount;
		}  
		$odin .= ' 
     		<tr>
			  <td colspan="5"><h4 align="right">Total :</h4></td>
			  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.number_format(round($total, 2), 2).'</span></td>
			</tr>
		';  
		return $odin;
	}

	// add item on edit
	public function stc_trading_sale_item_add($sale_id, $item_id, $item_unit, $item_qty, $item_rate){
		$odin='';
		$odin_checkqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_trading_sale_items_item_id`
			FROM 
				`stc_trading_sale_items` 
			WHERE 
				`stc_trading_sale_items_item_id`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."' 
			AND 
				`stc_trading_sale_items_sale_id`='".mysqli_real_escape_string($this->stc_dbs, $sale_id)."' 
		");
		if(mysqli_num_rows($odin_checkqry)>0){
			$odin = 'Item Already Added!!!';
		}else{
			$item_gst='';
			$odin_callgstqry=mysqli_query($this->stc_dbs, "
				SELECT 
           			`stc_trading_product_gst`
            	FROM 
            		`stc_trading_product`
            	WHERE 
					`stc_trading_product_id`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."' 
			");
			foreach($odin_callgstqry as $odin_callgstrow){
				$item_gst=$odin_callgstrow['stc_trading_product_gst'];
			}

			$basic=$item_rate;
			$gstamount = $basic-($basic*(100/(100+$item_gst)));
			$saleprice=$basic - $gstamount;

			$odin_addqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_trading_sale_items`(
					`stc_trading_sale_items_sale_id`,
					`stc_trading_sale_items_item_id`,
					`stc_trading_sale_items_item_qty`,
					`stc_trading_sale_items_item_price`,
					`stc_trading_sale_items_item_unit`,
					`stc_trading_sale_items_item_gst`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $sale_id)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $item_id)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $item_qty)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $saleprice)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $item_unit)."',
					'".mysqli_real_escape_string($this->stc_dbs, $item_gst)."'
				)
			");
			if($odin_addqry){
				$odin = 'Item Added Successfully!!!';
			}else{
				$odin = 'Hmmm!!! Something Went Wrong On Item Adding. Please Reheck & Try Again.';
			}
		}
		return $odin;
	}

	// update items
	public function stc_trading_sale_item_update($item_id, $item_qty, $item_rate, $item_gst){
		$odin='';
		$odin_checkqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_trading_sale_items_id` 
			FROM 
				`stc_trading_sale_items` 
			WHERE 
				`stc_trading_sale_items_id` = '".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
		");
		if(mysqli_num_rows($odin_checkqry)>0){
			$basic=$item_rate;
			$gstamount = $basic-($basic*(100/(100+$item_gst)));
			$saleprice=$basic - $gstamount;
			$odin_updateqry=mysqli_query($this->stc_dbs, "
				UPDATE
				    `stc_trading_sale_items`
				SET
					`stc_trading_sale_items_item_qty` = '".mysqli_real_escape_string($this->stc_dbs, $item_qty)."',
					`stc_trading_sale_items_item_price` = '".mysqli_real_escape_string($this->stc_dbs, $saleprice)."'
				WHERE
				    `stc_trading_sale_items_id` = '".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
			");
			if($odin_updateqry){
				$odin='Item Updated Successfully.';
			}else{
				$odin='Hmmmm!!! Something Went Wrong On Item Updation. Please Reheck & Try Again.';
			}
		}else{
			$odin='Item Not Found!!! Please Reload.';
		}	
		return $odin;
	}

	// remove items
	public function stc_trading_sale_item_remove($item_id){
		$odin='';
		$odin_removeqry=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_trading_sale_items` WHERE `stc_trading_sale_items_id` = '".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
		");
		if($odin_removeqry){
			$odin="Item Removed Successfully.";
		}else{
			$odin="Hmmm!!! Something Went Wrong. Item Can't Removed Successfully.";
		}
		return $odin;
	}
	
	// update edited sale
	public function stc_trading_sale_edit_update($sale_id, $cust_id, $cust_addresss, $cust_order_by, $cust_del_through, $cust_remarks){
		$odin='';
		$odin_updteqry=mysqli_query($this->stc_dbs, "
			UPDATE
				`stc_trading_sale`
			SET
				`stc_trading_sale_cust_id` = '".mysqli_real_escape_string($this->stc_dbs, $cust_id)."',
				`stc_trading_sale_cust_address` = '".mysqli_real_escape_string($this->stc_dbs, $cust_addresss)."',
				`stc_trading_sale_order_by` = '".mysqli_real_escape_string($this->stc_dbs, $cust_order_by)."',
				`stc_trading_sale_delever_through` = '".mysqli_real_escape_string($this->stc_dbs, $cust_del_through)."',
				`stc_trading_sale_remarks` = '".mysqli_real_escape_string($this->stc_dbs, $cust_remarks)."'
			WHERE
				`stc_trading_sale_id` = '".mysqli_real_escape_string($this->stc_dbs, $sale_id)."'
		");
		if($odin_updteqry){
			$odin='Sale Updated.';
		}else{
			$odin='Hmmm!!! Something Went Wrong, Sale Not Updated Properly Try Again.';
		}
		return $odin;
	}

	// set payment
	public function stc_sale_payment_set($sale_id, $payment_type, $payment_amount, $payment_remarks, $check_amount){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$paid_amount=0;
		$odin_checkqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_trading_sale_payment_value`
			FROM
				`stc_trading_sale_payment`
			WHERE 
				`stc_trading_sale_payment_sale_id` = '".mysqli_real_escape_string($this->stc_dbs, $sale_id)."'
		");
		foreach($odin_checkqry as $odin_checkrow){
			$paid_amount+=$odin_checkrow['stc_trading_sale_payment_value'];
		}
		$due_amount=$check_amount - $paid_amount;
		if($payment_amount>$due_amount){
			$odin='Invalid Amount.';
		}else{
			$odin_paycheckqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_trading_sale_payment`(
					`stc_trading_sale_payment_date`,
					`stc_trading_sale_payment_sale_id`,
					`stc_trading_sale_payment_type`,
					`stc_trading_sale_payment_value`,
					`stc_trading_sale_payment_remarks`,
					`stc_trading_sale_payment_created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sale_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $payment_type)."',
					'".mysqli_real_escape_string($this->stc_dbs, $payment_amount)."',
					'".mysqli_real_escape_string($this->stc_dbs, $payment_remarks)."',
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_trading_user_id'])."'
				)
			");
			if($odin_paycheckqry){
				$odin='Payment Done!!!';
			}else{
				$odin='Hmmm!!! Payment not done properly. Please check & try again';
			}
		}
		return $odin;
	}
}
#<------------------------------------------------------------------------------------------>
#<---------------------------------Silent purchase Section---------------------------------->
#<------------------------------------------------------------------------------------------>

// show Product in purchase Order page
if(isset($_POST['search_purchase_pd_name_in'])){
	$searchme=$_POST['search_purchase_pd_name_in'];
	$objloki=new aetherPurchase();
	$objlokiout=$objloki->stc_search_product_name($searchme);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// show purchase cart
if(isset($_POST['stc_show_purchase_sess'])){
	$order_table = '';  
	$slno = '';  
	$order_table .= '  
		<table class="table table-hover table-bordered" align="centre">
			<thead>
			  <tr>
			    <th class="text-center" scope="col">#</th>
			    <th class="text-center" scope="col" style="width: 20%;">Items</th>
			    <th class="text-center" scope="col">Unit</th>
			    <th class="text-center" scope="col">Qty</th>                         
			    <th class="text-center" scope="col">Price</th>
			    <th class="text-center" scope="col">Amount</th>
			    <th class="text-center" scope="col">Action</th>
			  </tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_silent_purchase_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_silent_purchase_sess"] as $keys => $values) {  
				$amount=$values["product_price"];
				$gstamount = $amount * ($values["product_gst"]/100);
				$slno++;
				$price=$amount + $gstamount;
				$order_table .= ' 
					<tr>
						<td align="center">'.$slno.'.</td>
						<td>'.$values["product_name"].'</td>
						<td>'.$values["product_unit"].'</td>
						<td>
						  <input
						  	style="width: 55%;padding: 5px;height: 40px;"
						    type="text"
						    id="stcqtysc'.$values["product_id"].'"
						    class="validate"
						    placeholder="Quantity"
						    value="'.number_format($values["product_quantity"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcqtysilent" href="#">
						  	<span class="material-icons">done</span>
						  </a>
						</td>
						<td class="align-right">'.number_format($price, 2, '.', '').'</td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($price * $values['product_quantity'], 2).'</td>
						<td align="center">
							<a style="color: red;font-size:20px;" href="#" id="'.$values["product_id"].'" class="stcdelsilentbtn">
								<span class="material-icons">delete</span>
							</a>
						</td>
					</tr>
				';  
				$total += $values["product_quantity"] * $price;  
				$totalgst +=$gstamount;
			}  
			$ekdumt=$total;
			$order_table .= ' 
        		<tr>
				  <td colspan="5"><h4 align="right">Total :</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.number_format(round($total, 2), 2).'</span></td>
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

// add to cart purchase session obj
if(isset($_POST['add_purchase'])){
	if($_POST["add_purchase"] == "addpurchasecart") {  		
		$basic=$_POST["product_price"];
		$gstamount = $basic-($basic*(100/(100+$_POST["product_gst"])));
		$purchaseprice=$basic - $gstamount;

		if(isset($_SESSION["stc_silent_purchase_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_silent_purchase_sess"] as $keys => $values) {  
				if($_SESSION["stc_silent_purchase_sess"][$keys]['product_id'] == $_POST["product_id"]) {  
					$is_available++;  
					$_SESSION["stc_silent_purchase_sess"][$keys]['product_quantity'] = $_SESSION["stc_silent_purchase_sess"][$keys]['product_quantity'] + $_POST["product_quantity"];  
					echo "Purchase Order Cart Item Quantity Increased!!!";
				}  
			}  
		    if($is_available < 1) {  
		    	$item_array = array(  
		    		'product_id'             =>     $_POST["product_id"],  
		    		'product_name'           =>     $_POST["product_name"],  
		    		'product_unit'           =>     $_POST["product_unit"],  
		    		'product_quantity'       =>     $_POST["product_quantity"],
		    		'product_gst'       	 =>     $_POST["product_gst"],
		    		'product_price'          =>     $purchaseprice
		    	);  
		    	$_SESSION["stc_silent_purchase_sess"][] = $item_array;  
		    	echo "Purchase Order Created & Item Added to Cart!!!";
		    }  
		}else{  
			$item_array = array(  
			    'product_id'             =>     $_POST["product_id"],  
		    	'product_name'           =>     $_POST["product_name"],  
		    	'product_unit'           =>     $_POST["product_unit"],  
		    	'product_quantity'       =>     $_POST["product_quantity"],
		    	'product_gst'       	 =>     $_POST["product_gst"],
		    	'product_price'          =>     $purchaseprice
			);   
			$_SESSION["stc_silent_purchase_sess"][] = $item_array;  
		    echo "Item Added to Purchase Order Cart!!!";
		}  
    }  
}

// purchase cart qty change
if(isset($_POST["purchase_quantity_action"])){  
  	foreach($_SESSION["stc_silent_purchase_sess"] as $keys => $values){  
	    if($_SESSION["stc_silent_purchase_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_silent_purchase_sess"][$keys]['product_quantity'] = $_POST["quantity"];
    	}  
  	}  
}

// change price of session purchase order
if(isset($_POST["stc_price_action"])){  
  	foreach($_SESSION["stc_silent_purchase_sess"] as $keys => $values){  
	    if($_SESSION["stc_silent_purchase_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_silent_purchase_sess"][$keys]['product_price'] = $_POST["price"];
    	}  
  	}  
}

// delete from purchase cart of session purchase order
if(isset($_POST['stcdelpurchaselinei'])){  
     foreach($_SESSION["stc_silent_purchase_sess"] as $keys => $values){  
          if($values["product_id"] == $_POST["product_id"]){  
               unset($_SESSION["stc_silent_purchase_sess"][$keys]);  
               echo "Product Removed!!!";  
          }  
     }  
}

// calculate charges of purchase orders
if(isset($_POST['do_plus_minus_on_sale'])){
	$fc=$_POST['freightcharge'];
	$pf=$_POST['packingandforwardingcharge'];
	$gt=$_POST['grand_total'];

	$gstfc=($fc * 18)/100;
	$gstpf=($pf * 18)/100;

	$out=($gstfc + $fc) + ($gstpf + $pf) + $gt;
	echo $out;
}

// save purchase order
if(isset($_POST['save_purchase_action'])){
	$out='';
	$silent_purchase_merchantname		= $_POST['merchant_name'];
	$silent_purchase_refreno			= $_POST['refrence_no'];
	$silent_purchase_redredate			= date('Y-m-d h:i:s', strtotime($_POST['refrence_date']));
	$silent_purchase_remarks			= $_POST['remarks'];

	$fridaysale=new aetherPurchase();
	if(empty($silent_purchase_merchantname) || empty($silent_purchase_refreno) || empty($silent_purchase_redredate)){
		$out.="Please Fill All Fields!!!";
	}elseif(empty($_SESSION['stc_trading_user_id'])){
		header("Location:../index.html");
	}elseif(empty($_SESSION['stc_silent_purchase_sess'])){
		$out.="Excuse me!!! Are you blind??? If you ain't then please add items first.";
	}else{
		$objfridaygrn=$fridaysale->purchase_process(
			$silent_purchase_merchantname,
			$silent_purchase_refreno,
			$silent_purchase_redredate,
			$silent_purchase_remarks
		);
		if($objfridaygrn=='Purchase Added Successfully!!!'){
			unset($_SESSION['stc_silent_purchase_sess']);
			$out.=$objfridaygrn;
		}else{
			$out .= "Something went Wrong Please Check & Try Again!!!";
		}
	}
	echo $out;
}

// filter purchase
if(isset($_POST['stc_show_purchase'])){
	$search = str_replace(",", "|", $_POST["search"]);
	$begdate = date('Y-m-d', strtotime($_POST['begdate']));
	$endDate = date('Y-m-d', strtotime($_POST['enddate'])); 

	$objloki=new aetherPurchase();
	$objlokiout=$objloki->stc_filter_purchase($search, $begdate, $endDate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call purchase items
if(isset($_POST['stc_call_purchase_items'])){
	$purchase_id=$_POST['purchase_id'];
	$objloki=new aetherPurchase();
	$objlokiout=$objloki->stc_filter_purchase_items($purchase_id);
	// echo json_encode($objlokiout);
	echo $objlokiout;
}

// set payment
if(isset($_POST['stc_payment_set'])){
	$out='';
	$purchase_id=$_POST['purchase_id'];
	$payment_type=$_POST['payment_type'];
	$payment_amount=$_POST['payment_amount'];
	$payment_remarks=$_POST['payment_remarks'];
	$purchase_amount=$_POST['purchase_amount'];

	$check_amount=$purchase_amount + 1;

	if($payment_amount>$check_amount){
		$out='Invalid Amount!!! Please recheck & try again.';
	}else{
		$objloki=new aetherPurchase();
		$objlokiout=$objloki->stc_payment_set($purchase_id, $payment_type, $payment_amount, $payment_remarks, $check_amount);
		// echo json_encode($objlokiout);
		$out=$objlokiout;
	}
	echo $out;
}

// call sale for edit
if(isset($_POST['stc_trading_editp_call'])){
	$purchase_id=$_POST['purchase_id'];
	$objloki=new aetherPurchase();
	$objlokiout=$objloki->stc_sale_call_for_purchaseedit($purchase_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// add item on edit
if(isset($_POST['stc_trading_edit_padd_item'])){
	$sale_id=$_POST['sale_id'];
	$item_id=$_POST['item_id'];
	$item_unit=$_POST['item_unit'];
	$item_qty=$_POST['item_qty'];
	$item_rate=$_POST['item_rate'];
	$objloki=new aetherPurchase();
	$objlokiout=$objloki->stc_trading_sale_item_add($sale_id, $item_id, $item_unit, $item_qty, $item_rate);
	echo $objlokiout;
}
// quantity change on edit
if(isset($_POST['stc_trading_edit_pupdate'])){
	$item_id=$_POST['item_id'];
	$item_qty=$_POST['item_qty'];
	$item_rate=$_POST['item_rate'];
	$item_gst=$_POST['item_gst'];

	$objloki=new aetherPurchase();
	$objlokiout=$objloki->stc_trading_purchase_item_update($item_id, $item_qty, $item_rate, $item_gst);
	echo $objlokiout;
}

// remove items
if(isset($_POST['stc_trading_edit_premove'])){
	$item_id=$_POST['item_id'];
	$objloki=new aetherPurchase();
	$objlokiout=$objloki->stc_trading_purchase_item_remove($item_id);
	echo $objlokiout;
}
#<---------------------------------Silent Sale Section----------------------------------->
// show Product in Sale Order page
if(isset($_POST['search_sale_pd_name_in'])){
	$searchme=$_POST['search_sale_pd_name_in'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_search_product_name($searchme);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// show sale cart
if(isset($_POST['stc_show_sale_sess'])){
	$order_table = '';  
	$slno = '';  
	$order_table .= '  
		<table class="table table-hover table-bordered" align="centre">
			<thead>
			  <tr>
			    <th class="text-center" scope="col">#</th>
			    <th class="text-center" scope="col" style="width: 40%;">Items</th>
			    <th class="text-center" scope="col">Unit</th>
			    <th class="text-center" scope="col" style="width: 15%;">Qty</th>                         
			    <th class="text-center" scope="col">Price</th>
			    <th class="text-center" scope="col">Amount</th>
			    <th class="text-center" scope="col">Action</th>
			  </tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_silent_sale_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_silent_sale_sess"] as $keys => $values) {  
				$amount=$values["product_price"];
				$gstamount = $amount * ($values["product_gst"]/100);
				$slno++;
				$price=$amount + $gstamount;
				$order_table .= ' 
					<tr>
						<td align="center">'.$slno.'.</td>
						<td>'.$values["product_name"].'</td>
						<td>'.$values["product_unit"].'</td>
						<td>
						  <input
						  	style="width: 55%;padding: 5px;height: 40px;"
						    type="text"
						    id="stcqtysc'.$values["product_id"].'"
						    class="validate"
						    placeholder="Quantity"
						    value="'.number_format($values["product_quantity"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcqtysilent" href="#">
						  	<span class="material-icons">done</span>
						  </a>
						</td>
						<td class="text-right">'.number_format($price, 2, '.', '').'</td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($price * $values['product_quantity'], 2).'</td>
						<td align="center">
							<a style="color: red;font-size:20px;" href="#" id="'.$values["product_id"].'" class="stcdelsilentbtn">
								<span class="material-icons">delete</span>
							</a>
						</td>
					</tr>
				';  
				$total += $values["product_quantity"] * $price;  
				$totalgst +=$gstamount;
			}  
			$ekdumt=$total;
			$order_table .= ' 
        		<tr>
				  <td colspan="5"><h4 align="right">Total :</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.number_format(round($total, 2), 2).'</span></td>
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

// add to cart sale session obj
if(isset($_POST['add_sale'])){
	if($_POST["add_sale"] == "addsalecart") {  		
		$basic=$_POST["product_price"];
		$gstamount = $basic-($basic*(100/(100+$_POST["product_gst"])));
		$purchaseprice=$basic - $gstamount;

		if(isset($_SESSION["stc_silent_sale_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_silent_sale_sess"] as $keys => $values) {  
				if($_SESSION["stc_silent_sale_sess"][$keys]['product_id'] == $_POST["product_id"]) {  
					$is_available++;  
					$_SESSION["stc_silent_sale_sess"][$keys]['product_quantity'] = $_SESSION["stc_silent_sale_sess"][$keys]['product_quantity'] + $_POST["product_quantity"];  
					echo "Sale Order Cart Item Quantity Increased!!!";
				}  
			}  
		    if($is_available < 1) {  
		    	$item_array = array(  
		    		'product_id'             =>     $_POST["product_id"],  
		    		'product_name'           =>     $_POST["product_name"],  
		    		'product_unit'           =>     $_POST["product_unit"],  
		    		'product_quantity'       =>     $_POST["product_quantity"],
		    		'product_gst'       	 =>     $_POST["product_gst"],
		    		'product_price'          =>     $purchaseprice
		    	);  
		    	$_SESSION["stc_silent_sale_sess"][] = $item_array;  
		    	echo "Sale Order Created & Item Added to Cart!!!";
		    }  
		}else{  
			$item_array = array(  
			    'product_id'             =>     $_POST["product_id"],  
		    	'product_name'           =>     $_POST["product_name"],  
		    	'product_unit'           =>     $_POST["product_unit"],  
		    	'product_quantity'       =>     $_POST["product_quantity"],
		    	'product_gst'       	 =>     $_POST["product_gst"],
		    	'product_price'          =>     $purchaseprice
			);   
			$_SESSION["stc_silent_sale_sess"][] = $item_array;  
		    echo "Item Added to Sale Order Cart!!!";
		}  
    }  
}

// sale cart qty change
if(isset($_POST["sale_quantity_action"])){  
  	foreach($_SESSION["stc_silent_sale_sess"] as $keys => $values){  
	    if($_SESSION["stc_silent_sale_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_silent_sale_sess"][$keys]['product_quantity'] = $_POST["quantity"];
    	}  
  	}  
}

// delete from purchase cart of session purchase order
if(isset($_POST['stcdelsalelinei'])){  
     foreach($_SESSION["stc_silent_sale_sess"] as $keys => $values){  
          if($values["product_id"] == $_POST["product_id"]){  
               unset($_SESSION["stc_silent_sale_sess"][$keys]);  
               echo "Product Removed!!!";  
          }  
     }  
}

// save sale order
if(isset($_POST['save_sale_action'])){
	$out='';

	$sale_date=date('Y-m-d', strtotime($_POST['sale_date']));
	$customer_name=$_POST['customer_name'];
	$ship_address=$_POST['ship_address'];
	$order_on=date('d-m-Y', strtotime($_POST['order_on']));
	$delever_on=date('d-m-Y', strtotime($_POST['delever_on']));
	$order_by=$_POST['order_by'];
	$delever_through=$_POST['delever_through'];
	$remarks=$_POST['remarks'];

	$fridaysale=new aetherSale();
	if($customer_name=="NA" || empty($ship_address) || empty($delever_through)){
		$out.="Please Fill All Fields!!!";
	}elseif(empty($_SESSION['stc_trading_user_id'])){
		header("Location:../index.html");
	}elseif(empty($_SESSION['stc_silent_sale_sess'])){
		$out.="Excuse me!!! Are you blind??? If you ain't then please add items first.";
	}else{
		$objfridaygrn=$fridaysale->sale_process(
			$sale_date,
			$customer_name,
			$ship_address,
			$order_on,
			$delever_on,
			$order_by,
			$delever_through,
			$remarks
		);
		if($objfridaygrn=='Sale Added Successfully!!!'){
			unset($_SESSION['stc_silent_sale_sess']);
			$out.=$objfridaygrn;
		}else{
			$out .= "Something went Wrong Please Check & Try Again!!!";
		}
	}
	echo $out;
}

// filter sale
if(isset($_POST['stc_show_sale'])){
	$search = str_replace(",", "|", $_POST["search"]);
	$begdate = date('Y-m-d', strtotime($_POST['begdate']));
	$endDate = date('Y-m-d', strtotime($_POST['enddate'])); 

	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_filter_sale($search, $begdate, $endDate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// filter sale by date
if(isset($_POST['stc_show_sale_bydate'])){
	$begdate = date('Y-m-d', strtotime($_POST['begdate']));
	$endDate = date('Y-m-d', strtotime($_POST['enddate'])); 

	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_filter_sale_bydate($begdate, $endDate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call sale for edit
if(isset($_POST['stc_trading_edit_call'])){
	$sale_id=$_POST['sale_id'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_sale_call_for_edit($sale_id);
	echo json_encode($objlokiout);
}

// call sale item
if(isset($_POST['stc_trading_edit_call_item'])){
	$sale_id=$_POST['sale_id'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_trading_sale_item_call($sale_id);
	echo $objlokiout;
}

// add item on edit
if(isset($_POST['stc_trading_edit_add_item'])){
	$sale_id=$_POST['sale_id'];
	$item_id=$_POST['item_id'];
	$item_unit=$_POST['item_unit'];
	$item_qty=$_POST['item_qty'];
	$item_rate=$_POST['item_rate'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_trading_sale_item_add($sale_id, $item_id, $item_unit, $item_qty, $item_rate);
	echo $objlokiout;
}

// quantity change on edit
if(isset($_POST['stc_trading_edit_update'])){
	$item_id=$_POST['item_id'];
	$item_qty=$_POST['item_qty'];
	$item_rate=$_POST['item_rate'];
	$item_gst=$_POST['item_gst'];

	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_trading_sale_item_update($item_id, $item_qty, $item_rate, $item_gst);
	echo $objlokiout;
}

// remove items
if(isset($_POST['stc_trading_edit_remove'])){
	$item_id=$_POST['item_id'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_trading_sale_item_remove($item_id);
	echo $objlokiout;
}

// save edit sale
if(isset($_POST['stc_trading_sale_save_edit'])){
	$sale_id=$_POST['sale_id'];
	$cust_id=$_POST['cust_id'];
	$cust_addresss=$_POST['cust_addresss'];
	$cust_order_by=$_POST['cust_order_by'];
	$cust_del_through=$_POST['cust_del_through'];
	$cust_remarks=$_POST['cust_remarks'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_trading_sale_edit_update($sale_id, $cust_id, $cust_addresss, $cust_order_by, $cust_del_through, $cust_remarks);
	echo $objlokiout;	
}

// set payment
if(isset($_POST['stc_sold_payment_set'])){
	$out='';
	$sale_id=$_POST['sale_id'];
	$payment_type=$_POST['payment_type'];
	$payment_amount=$_POST['payment_amount'];
	$payment_remarks=$_POST['payment_remarks'];
	$sale_amount=$_POST['sale_amount'];

	$check_amount=$sale_amount + 1;

	if($payment_amount>$check_amount){
		$out='Invalid Amount!!! Please recheck & try again.';
	}else{
		$objloki=new aetherSale();
		$objlokiout=$objloki->stc_sale_payment_set($sale_id, $payment_type, $payment_amount, $payment_remarks, $check_amount);
		// echo json_encode($objlokiout);
		$out=$objlokiout;
	}
	echo $out;
}
?>