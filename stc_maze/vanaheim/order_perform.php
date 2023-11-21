<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Silent Invoice --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
// sale challan
class aetherSale extends tesseract{	

	// stc call silent challan
	public function stc_call_sale_orders($challanbegdate, $challanenddate){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_silent_challan`  
			INNER JOIN `stc_electronics_user` 
			ON `stc_sale_product_silent_challan_createdby`=`stc_electronics_user_id`
			WHERE 
			DATE(`stc_sale_product_silent_challan_date`) 
			BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $challanbegdate)."' 
			AND '".mysqli_real_escape_string($this->stc_dbs, $challanenddate)."' 
			ORDER BY `stc_sale_product_silent_challan`.`stc_sale_product_silent_challan_id` 
			DESC LIMIT 0,15
		");
		$odin='
			<table class="table table-hover table-bordered">
	        	<thead>
	        	  <tr>
	        	    <th class="text-center" scope="col">Silent Challan Date/<br>Silent Challan No</th>
	        	    <th class="text-center" scope="col">Customer Name<br>Customer Cont</th>
	        	    <th class="text-center" scope="col">Order On/<br>Order By</th>
	        	    <th class="text-center" scope="col">Delivered On/<br>Deliver Through</th>
	        	    <th class="text-center" scope="col">Warranty status</th>
	        	    <th class="text-center" scope="col">Basic Value</th>
	        	    <th class="text-center" scope="col">Status</th>     
	        	    <th class="text-center" scope="col">Notes</th>      
	        	    <th class="text-center" scope="col">By</th>                         
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
					SELECT * FROM `stc_sale_product_silent_challan_items` 
					WHERE `stc_sale_product_silent_challan_items_order_id`='".$row["stc_sale_product_silent_challan_id"]."'
				");
				$soamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$soiqty=$row2nd['stc_sale_product_silent_challan_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_silent_challan_items_product_sale_rate'];
					$soamount+=$soiqty * $soirate;
				}
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$challannumber = substr("0000{$row["stc_sale_product_silent_challan_id"]}", -$str_length);
				$odin.='
					<tr>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_date"])).'
       						<br>
       						STC/DC/'.$challannumber.'
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_customer_name"].'
	       					<br>
       						'.$row["stc_sale_product_silent_challan_contact_no"].'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_order_on"])).'
	       					<br>
	       					'.$row["stc_sale_product_silent_challan_order_by"].'
       					</td>	
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_delivered_on"])).'
	       					<br>
	       					'.$row["stc_sale_product_silent_challan_delivered_through"].'
       					</td>			
       					<td>
       					  	'.$row["stc_sale_product_silent_challan_warranty_status"].'
       					</td>
       					<td style="width:auto;text-align:right;">
       					  	<i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'
       					</td>
       					<td class="text-center">
       						'.strtoupper($row["stc_sale_product_silent_challan_status"]).'
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_notes"].'
       					</td>
       					<td class="text-center">
       						'.$row["stc_electronics_user_fullName"].'
       					</td>
       					<td class="text-center">
       					  <div class="row">
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="silent-challan-preview.php?pdcid='.$row["stc_sale_product_silent_challan_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
       					    </div>
       					  </div>   
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

	// stc search silent challan
	public function stc_filter_challan($search, $challanbegdate, $challanenddate){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_silent_challan` 
			INNER JOIN `stc_electronics_user` 
			ON `stc_sale_product_silent_challan_createdby`=`stc_electronics_user_id`
			WHERE (
			`stc_sale_product_silent_challan_customer_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_contact_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_delivered_through` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			) AND (
                DATE(`stc_sale_product_silent_challan_date`)
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $challanbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $challanenddate)."'
            )
			ORDER BY `stc_sale_product_silent_challan`.`stc_sale_product_silent_challan_id` 
			DESC LIMIT 0,15
		");
		$odin='
			<table class="table table-hover table-bordered">
	        	<thead>
	        	  <tr>
	        	    <th class="text-center" scope="col">Silent Challan Date/<br>Silent Challan No</th>
	        	    <th class="text-center" scope="col">Customer Name<br>Customer Cont</th>
	        	    <th class="text-center" scope="col">Order On/<br>Order By</th>
	        	    <th class="text-center" scope="col">Delivered On/<br>Deliver Through</th>
	        	    <th class="text-center" scope="col">Warranty status</th>
	        	    <th class="text-center" scope="col">Basic Value</th>
	        	    <th class="text-center" scope="col">Status</th>     
	        	    <th class="text-center" scope="col">Notes</th>      
	        	    <th class="text-center" scope="col">By</th>                         
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
					SELECT * FROM `stc_sale_product_silent_challan_items` 
					WHERE `stc_sale_product_silent_challan_items_order_id`='".$row["stc_sale_product_silent_challan_id"]."'
				");
				$soamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$soiqty=$row2nd['stc_sale_product_silent_challan_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_silent_challan_items_product_sale_rate'];
					$soamount+=$soiqty * $soirate;
				}
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$challannumber = substr("0000{$row["stc_sale_product_silent_challan_id"]}", -$str_length);
				$odin.='
					<tr>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_date"])).'
       						<br>
       						STC/DC/'.$challannumber.'
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_customer_name"].'
	       					<br>
       						'.$row["stc_sale_product_silent_challan_contact_no"].'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_order_on"])).'
	       					<br>
	       					'.$row["stc_sale_product_silent_challan_order_by"].'
       					</td>	
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_delivered_on"])).'
	       					<br>
	       					'.$row["stc_sale_product_silent_challan_delivered_through"].'
       					</td>			
       					<td>
       					  	'.$row["stc_sale_product_silent_challan_warranty_status"].'
       					</td>
       					<td style="width:auto;text-align:right;">
       					  	<i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'
       					</td>
       					<td class="text-center">
       						'.strtoupper($row["stc_sale_product_silent_challan_status"]).'
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_notes"].'
       					</td>
       					<td class="text-center">
       						'.$row["stc_electronics_user_fullName"].'
       					</td>
       					<td class="text-center">
       					  <div class="row">
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="silent-challan-preview.php?pdcid='.$row["stc_sale_product_silent_challan_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
       					    </div>
       					  </div>   
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

	// Search product name in silent challan Page
	public function stc_search_product_name($searchme){
		$perfectsearchme=strtoupper($searchme);
		$purchase_rate='';
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
					LEFT JOIN `stc_electronics_inventory` ON `stc_electronics_inventory_item_id`=`stc_product_id`
					WHERE (
						`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."' 
						OR 
						`stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."'
					) AND `stc_electronics_inventory_item_qty`!=0
					ORDER BY `stc_product_id` 
					ASC LIMIT 0,50
		";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$loki_findratefrompo=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_purchase_product_items` 
					WHERE `stc_purchase_product_items_product_id`='".$row["stc_product_id"]."' 
					AND `stc_purchase_product_items_id`=(
						SELECT MAX(`stc_purchase_product_items_id`) FROM `stc_purchase_product_items` 
						WHERE `stc_purchase_product_items_product_id`='".$row["stc_product_id"]."'
					)
				");
				$purchase_rate=mysqli_fetch_assoc($loki_findratefrompo);
				$correct_stcpdname=strlen($row["stc_product_name"]);
				$gstamt = ($purchase_rate['stc_purchase_product_items_rate'] * $row['stc_product_gst'])/100;
				$totalamt=$purchase_rate['stc_purchase_product_items_rate'] + $gstamt;
				$odin.='	
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
		             	  	<div class="row">
		             			<div class="el-wrapper">
			            		    <div class="box-up">
			            		        <img class="img" style="height: 100%; width:100%;" src="../../stc_symbiote/stc_product_image/'.$row["stc_product_image"].'" alt="">
			            		        <div class="img-info">
				             	        <div class="info-inner">
				             	            <span class="p-name">'.htmlentities($row["stc_product_name"]).'</span>
				             	            <span class="p-company">'.$row["stc_cat_name"].' / '.$row["stc_product_hsncode"].'</span>
				             	            <span class="p-qty" >'.$row["stc_product_unit"].'</span>
				             	            <input 
					             	          	type="number" 
					             	          	name="stcpdprice" 
					             	          	id="stcpdprice'.$row["stc_product_id"].'" 
					             	          	placeholder="Enter Percent % Below 100"
					             	          	class="form-control validate"
			             	         			value="'.number_format($totalamt, 2, '.', '').'" 
			             	         			style="color:black;font-weight:bold;text-align:center"
			             	         			disabled
					             	          />
					             	        <input 
					             	    		type="number" 
					             	    		name="stcpdsaleprice" 
					             	    		id="stcpdsaleperc'.$row["stc_product_id"].'" 
					             	    		placeholder="Enter Percent % Below 100"
					             	    		class="form-control validate" 
					             	    		value="'.number_format($row["stc_product_sale_percentage"], 2).'"
					             	    	/>           	            
				             	        </div>
			             	         	<input 
			             	         		type="hidden" 
			             	         		name="hidden_name" 
			             	         		id="stcpopdname'.$row["stc_product_id"].'" 
			             	         		value="'.htmlentities($row["stc_product_name"]).'" 
			             	         	/>
			             	         	<input 
			             	         		type="hidden" 
			             	         		name="hidden_unit" 
			             	         		id="stcpopdunit'.$row["stc_product_id"].'" 
			             	         		value="'.$row["stc_product_unit"].'" 
			             	         	/>
			             	         	<input 
			             	         		type="hidden" 
			             	         		name="hidden_hsncode" 
			             	         		id="stcpopdhsncode'.$row["stc_product_id"].'" 
			             	         		value="'.$row["stc_product_hsncode"].'" 
			             	         	/>
			             	         	<input 
			             	         		type="hidden" 
			             	         		name="hidden_gst" 
			             	         		id="stcpopdgst'.$row["stc_product_id"].'" 
			             	         		value="'.$row["stc_product_gst"].'" 
			             	         	/>
			             	         	<div class="a-size">
				             	        	<input
						                	  id="stcpoqty'.$row["stc_product_id"].'"
						                	  name="stcpoqty"
						                	  type="number"
						                	  placeholder="Quantity"
						                	  class="form-control validate"
						                	/>
			             	          	</div>
			            		        </div>
			            		    </div>

		             				<div class="box-down">
		             					<div class="h-bg">
		             						<div class="h-bg-inner"></div>
		             					</div>

		             					<a class="cart add_to_silent_cart" href="#" id="'.$row["stc_product_id"].'">
		             					<span class="price">'.$row["stc_sub_cat_name"].' / '.$row["stc_brand_title"].'</span>
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

	// go data to silent challan table
	public function sale_process($silent_challandate, $silent_customername, $silent_order_billaddress, $silent_order_shipaddress, $silent_order_contnumber, $silent_order_orderon, $silent_order_deliveron, $silent_order_orderby, $silent_order_deliverthrough, $silent_order_warrantystatus, $silent_order_stcfc, $silent_order_stcpf, $silent_order_notes){	
		$maxgst='';
		foreach($_SESSION["stc_silent_challan_sess"] as $keys => $values) {
			$maxgst=array($values['product_gst']);
		}
		$odin='';
		$lokiinsertsale=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_sale_product_silent_challan`(
				`stc_sale_product_silent_challan_date`, 
				`stc_sale_product_silent_challan_customer_name`, 
				`stc_sale_product_silent_challan_customer_billaddress`, 
				`stc_sale_product_silent_challan_customer_shipaddress`, 
				`stc_sale_product_silent_challan_contact_no`, 
				`stc_sale_product_silent_challan_order_on`, 
				`stc_sale_product_silent_challan_delivered_on`, 
				`stc_sale_product_silent_challan_order_by`, 
				`stc_sale_product_silent_challan_delivered_through`, 
				`stc_sale_product_silent_challan_freight`, 
				`stc_sale_product_silent_challan_pandf`, 
				`stc_sale_product_silent_challan_warranty_status`, 
				`stc_sale_product_silent_challan_status`, 
				`stc_sale_product_silent_challan_notes`, 
				`stc_sale_product_silent_challan_createdby`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $silent_challandate)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $silent_customername)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $silent_order_billaddress)."',
				'".mysqli_real_escape_string($this->stc_dbs, $silent_order_shipaddress)."',
				'".mysqli_real_escape_string($this->stc_dbs, $silent_order_contnumber)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $silent_order_orderon)."',
				'".mysqli_real_escape_string($this->stc_dbs, $silent_order_deliveron)."',
				'".mysqli_real_escape_string($this->stc_dbs, $silent_order_orderby)."',
				'".mysqli_real_escape_string($this->stc_dbs, $silent_order_deliverthrough)."',
				'".mysqli_real_escape_string($this->stc_dbs, $silent_order_warrantystatus)."',
				'".mysqli_real_escape_string($this->stc_dbs, $silent_order_stcfc)."',
				'".mysqli_real_escape_string($this->stc_dbs, $silent_order_stcpf)."',
				'PROCESS',
				'".mysqli_real_escape_string($this->stc_dbs, $silent_order_notes)."',
				'".$_SESSION['stc_electro_user_id']."'
			)
		");
		$odin='';
		if($lokiinsertsale){
			$odin .= "Sale Done!!!";
		}else{
			$odin .= "Please Check & Try Again!!!";
		}
		return $odin;
	}

	// go sale session data to silent challan items table 
	public function stc_save_sale_pro_items(){
		// $odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_silent_challan` ORDER BY `stc_sale_product_silent_challan`.`stc_sale_product_silent_challan_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_sale_product_silent_challan_id'];
		if(empty($_SESSION['stc_silent_challan_sess'])){
			$odin = "Do not leave Items on Sale Order!!!";
		}else{
			foreach ($_SESSION["stc_silent_challan_sess"] as $key=>$value) {
				$amount=$value["product_quantity"] * $value["product_sale_price"];
				$gstamount = $amount-($amount*(100/(100+$value["product_gst"])));
				$saleprice=$amount - $gstamount;
		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_sale_product_silent_challan_items`( 
		    			`stc_sale_product_silent_challan_items_order_id`, 
		    			`stc_sale_product_silent_challan_items_product_id`, 
		    			`stc_sale_product_silent_challan_items_product_qty`, 
		    			`stc_sale_product_silent_challan_items_product_rate`, 
		    			`stc_sale_product_silent_challan_items_product_sale_rate`
		    		) VALUES (
		    			'".mysqli_real_escape_string($this->stc_dbs, $get_purchase_product_id)."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_id'])."',
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_quantity'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_price'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $saleprice)."'
		    		)
		    	");

		    	// check item in inventory
		    	$friday_checkinvqry=mysqli_query($this->stc_dbs, "
		    		SELECT * FROM `stc_electronics_inventory` 
		    		WHERE `stc_electronics_inventory_item_id`='".mysqli_real_escape_string($this->stc_dbs, $value['product_id'])."'
		    	");
		    	$current_invqty=0;
		    	foreach($friday_checkinvqry as $rowinv){
		    		$current_invqty+=$rowinv['stc_electronics_inventory_item_qty'];
		    	}
		    	$finalqtygo= $current_invqty - $value['product_quantity'];
		    	// update inventory
		    	$friday_updateinvqry=mysqli_query($this->stc_dbs, "
		    		UPDATE `stc_electronics_inventory` 
		    		SET `stc_electronics_inventory_item_qty`='".$finalqtygo."'
		    		WHERE `stc_electronics_inventory_item_id`='".mysqli_real_escape_string($this->stc_dbs, $value['product_id'])."'
		    	");
		    }
		    $odin = "Sale Item Done!!!";
		}
		return $odin;
	}
}

// sale invoice
class Yggdrasil extends tesseract{
	// stc call sale orders
	public function stc_call_sale_orders_invoices($loadchallanbegdate, $loadchallanenddate){
		$odingrand=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_silent_invoice`
			INNER JOIN `stc_sale_product_silent_challan` 
			ON `stc_sale_product_silent_invoice_challan_id`=`stc_sale_product_silent_challan_id`
			WHERE `stc_sale_product_silent_invoice_date`
			BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $loadchallanbegdate)."'
			AND '".mysqli_real_escape_string($this->stc_dbs, $loadchallanenddate)."'
			ORDER BY `stc_sale_product_silent_invoice`.`stc_sale_product_silent_invoice_id` 
			DESC LIMIT 0,20
		");
		$odin='
			<table class="table table-hover table-responsive">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Invoice Id<br>Invoice Date</th>
	        	    <th scope="col">Challan Number<br>Challan Date</th>
	        	    <th scope="col">Customer Name<br>Contact Number</th>
	        	    <th scope="col">Order On<br>Order By</th> 
	        	    <th scope="col">Deliver On<br>Deliver Through</th> 
	        	    <th scope="col">Challan Basic<br>Total Value</th>  
	        	    <th scope="col">Payment Value</th>
	        	    <th scope="col">Payment Type</th>
	        	    <th scope="col">Total Dues</th>
	        	    <th scope="col">Status</th>
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='6' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$silentbvalue=0;
				$silenttvalue=0;
				$getsilentvalueqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_sale_product_silent_challan_items_product_qty`,
					    `stc_sale_product_silent_challan_items_product_sale_rate`,
					    `stc_product_gst`
					FROM `stc_sale_product_silent_challan_items` 
					INNER JOIN `stc_product` 
					ON `stc_sale_product_silent_challan_items_product_id`=`stc_product_id`
					WHERE `stc_sale_product_silent_challan_items_order_id`='".$row["stc_sale_product_silent_challan_id"]."'
				");

				foreach($getsilentvalueqry as $silentrow){
					$bvalue=$silentrow['stc_sale_product_silent_challan_items_product_qty'] * $silentrow['stc_sale_product_silent_challan_items_product_sale_rate'];
					$silentbvalue+=$bvalue;
					$totalgst=$bvalue * ($silentrow['stc_product_gst']/100);
					$silenttvalue+=$bvalue * ($silentrow['stc_product_gst']/100);
				}
				$totalvalue=$silentbvalue + $silenttvalue;

				$getsilentpaymentqry=mysqli_query($this->stc_dbs, "
					SELECT
						`stc_sale_product_silent_invoice_payment_value`,
						`stc_sale_product_silent_invoice_payment_through`
					FROM
						`stc_sale_product_silent_invoice_payment`
					WHERE
						`stc_sale_product_silent_invoice_payment_order_id`='".$row["stc_sale_product_silent_invoice_id"]."'
				");
				$payment_value=0;
				$payment_type='';
				foreach($getsilentpaymentqry as $silentpayrow){
					$payment_value+=$silentpayrow['stc_sale_product_silent_invoice_payment_value'];
					$payment_type=$silentpayrow['stc_sale_product_silent_invoice_payment_through'];
				}
				if(empty($payment_type)){
					$payment_type='Credit';
				}
				$dues=$totalvalue - $payment_value;
				$odin.='
					<tr>
       					<td>
       					  STC/'.substr("0000{$row["stc_sale_product_silent_invoice_id"]}", -5).'<br>
       					  '.date('d-m-y', strtotime($row["stc_sale_product_silent_invoice_date"])).'
       					</td>
       					<td>
       					  STC/'.substr("0000{$row["stc_sale_product_silent_challan_id"]}", -5).'<br>
       					  '.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_date"])).'
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_customer_name"].'<br>
       						'.$row["stc_sale_product_silent_challan_contact_no"].'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_order_on"])).'<br>
       						'.$row["stc_sale_product_silent_challan_order_by"].'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_delivered_on"])).'<br>
       						'.$row["stc_sale_product_silent_challan_delivered_through"].'
       					</td>
       					<td align="right" style="width:auto;">
       					  <p><b><i class="fas fa-rupee-sign"></i> '.number_format($silentbvalue, 2).'</b><br>
       					  <b><i class="fas fa-rupee-sign"></i> '.number_format($totalvalue, 2).'</b></p>
       					</td>
       					<td align="right" style="width:auto;"><b>'.number_format($payment_value, 2).'</b></td>
       					<td align="center">'.$payment_type.'.</td>
       					<td align="right" style="width:auto;"><b>'.number_format($dues, 2).'</b></td>
       					<td>
       						'.strtoupper($row["stc_sale_product_silent_challan_status"]).'
       					</td>
       					<td>
       					  <div class="row">
       					    <div class="col-sm-12">
       					     	<a 
       					     		class="stc_print_page" 
       					     		data-toggle="tooltip" 
       					     		data-placement="left" 
       					     		title="Tax Invoice" 
       					     		href="silent-invoice-print-preview.php?pid='.$row["stc_sale_product_silent_invoice_id"].'" 
       					     		style="font-size: 25px;color: white;"
	       					    >	
	       					    	<i 
	       					     		class="fa fa-print" 
	       					     		aria-hidden="true"
	       					     		></i>
	       					    </a>
       					    </div>
       					    <div class="col-sm-12">
       					     	<a 
       					     		class="stc-pay-invoice" 
       					     		id="'.$row["stc_sale_product_silent_invoice_id"].'" 
       					     		data-placement="left" 
       					     		title="Payments" 
       					     		href="#" 
       					     		style="font-size: 25px;color: black;"
	       					    >
	       					    Pay
	       					    </a>
       					    </div>
       					  </div>   
       					</td>
            		</tr>         	
		    	';
				$odingrand+=$silentbvalue + $silenttvalue;
			}
		}
		$odin.='
				</tbody>
	       	</table>
		';
		$out=array(
			'bills_all' => $odin,
			'value_total' => number_format($odingrand, 2)
		);
		return $out;
	}

	// Search Challan in Sale Orde Page
	public function stc_search_challan(){
		$purchase_rate='';
		$alagsequery="					
					SELECT * FROM `stc_sale_product_silent_challan`
					WHERE `stc_sale_product_silent_challan_status`='PROCESS' 
		";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';
		$totalgst=0;
		$total=0;
		$amount=0;
		$gstamount=0;
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$nestedquery='';
					$nestedquery=mysqli_query($this->stc_dbs, "
						SELECT SUM(`stc_sale_product_silent_challan_items_product_qty` * `stc_sale_product_silent_challan_items_product_sale_rate`) 
							AS `stc_total_value`, `stc_product_gst` 
						FROM `stc_sale_product_silent_challan_items`
	                 	LEFT JOIN `stc_product`
	                 	ON `stc_product_id`=`stc_sale_product_silent_challan_items_product_id`
	                 	LEFT JOIN `stc_sub_category`
	                 	ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
	                 	WHERE `stc_sale_product_silent_challan_items_order_id`='".$row["stc_sale_product_silent_challan_id"]."'
					");

				$row2=mysqli_fetch_assoc($nestedquery);
				$amount=$row2['stc_total_value'];
				$gstamount = ($amount * $row2["stc_product_gst"])/100;
	            $grandtotal=round($amount + $gstamount, 2);
				$odin.='	
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
							<div class="row">
								<div class="el-wrapper">
								  	<div class="box-up">
								        <div class="img-info">
								 	        <div class="info-inner">
								 	        	<span class="p-name">'.$row["stc_sale_product_silent_challan_contact_no"].'</span>
								 	        	<span class="p-name">'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_date"])).'</span>
								 	            <span class="p-name">'.htmlentities($row["stc_sale_product_silent_challan_customer_name"]).'</span>
								 	        </div>
								         	<div class="a-size">
												<p>'.$grandtotal.'</p>
								          	</div>
								    	</div>
								  	</div>

								  	<div class="box-down">
										<div class="h-bg">
											<div class="h-bg-inner"></div>
										</div>

										<a class="cart add_to_silent_invo_cart" href="#" id="'.$row["stc_sale_product_silent_challan_id"].'">
											<span class="price">STC/DC/'.htmlentities(substr("0000{$row["stc_sale_product_silent_challan_id"]}", -5)).'</span>
											<span class="add-to-cart">
												<span class="txt">Add to Bill</span>
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

	// show sale order session
	public function stc_show_invo_sess(){
		$odin='';
		$slno=0;
		foreach($_SESSION["stc_sale_order_silent_invo_sess"] as $keys => $values) {
		  	$check_loki=mysqli_query($this->stc_dbs, "
		  		SELECT * FROM `stc_sale_product_silent_challan` 
		  		WHERE `stc_sale_product_silent_challan_id`='".$values['invo_challan_id']."'
		  	");

			foreach($check_loki as $invorow){
				$check_loki_nest='';
				$grnamount=0;
					$check_loki_nest=mysqli_query($this->stc_dbs, "
						SELECT * FROM `stc_sale_product_silent_challan_items` 
						LEFT JOIN `stc_product`
						ON `stc_sale_product_silent_challan_items_product_id` =`stc_product_id`
						WHERE `stc_sale_product_silent_challan_items_order_id`='".$invorow["stc_sale_product_silent_challan_id"]."'
					");
					foreach ($check_loki_nest as $row2nd) {
						$grniqty=$row2nd['stc_sale_product_silent_challan_items_product_qty'];
						$grnirate=$row2nd['stc_sale_product_silent_challan_items_product_sale_rate'];
						$grnamount+=$grniqty * $grnirate;
					}
				$slno++;
				$odin .= ' 
					<tr>
						<td>'.$slno.'</td>
						<td>STC/DC/'.substr("0000{$invorow["stc_sale_product_silent_challan_id"]}", -5).'</td>
						<td>'.date('d-m-y', strtotime($invorow["stc_sale_product_silent_challan_date"])).'</td>
						<td>'.$invorow["stc_sale_product_silent_challan_customer_name"].'</td>
						<td>'.$invorow["stc_sale_product_silent_challan_customer_name"].'</td>
						<td>'.$invorow["stc_sale_product_silent_challan_contact_no"].'</td>
						<td><i class="fas fa-rupee-sign"></i> '.number_format($grnamount, 2).'</td>
						<td><a style="color: red;font-size:20px;" href="#" id="'.$values["invo_challan_id"].'" class="stcdelsilentinvobtn"><i class="fas fa-trash"></i></a></td>
					</tr>
				';  
			}
		}  
		return $odin;
	}

	// go data to sale table
	public function stc_invo_process($stcinvodate, $stcinvonotes){	
		$odin='';
		$currdate=date('d', strtotime($stcinvodate));
		$currmonth=date('m', strtotime($stcinvodate));
		foreach ($_SESSION["stc_sale_order_silent_invo_sess"] as $key=>$value) {	
			$lokiinsertsale=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_sale_product_silent_invoice`(
					`stc_sale_product_silent_invoice_date`, 
					`stc_sale_product_silent_invoice_challan_id`, 
					`stc_sale_product_silent_invoice_notes`
				) VALUES (
					'".$stcinvodate."', 
					'".$value['invo_challan_id']."',
					'".$stcinvonotes."'
				)
			");
			if($lokiinsertsale){
				$odin = "invoinserted";
			}else{
				$odin = "Hmm Something went wrong!!!";
			}
		}
		return $odin;
	}

	// go sale session data to sale items table 
	public function stc_invo_challan_process(){
		$odin='';
		if(empty($_SESSION['stc_sale_order_silent_invo_sess'])){
			$odin = "Do not leave Items on Sale Order!!!";
		}else{
			foreach ($_SESSION["stc_sale_order_silent_invo_sess"] as $key=>$value) {
				$lokiinsertsale=mysqli_query($this->stc_dbs, "
					UPDATE `stc_sale_product_silent_challan` 
					SET`stc_sale_product_silent_challan_status`='billed'
					WHERE `stc_sale_product_silent_challan_id`='".$value['invo_challan_id']."'
				");
			}
			$odin .= "Invoice Saved!!!";
		}
		return $odin;
	}

	// search by key
	public function stc_filter_saleorder($search, $directchallanbegdate, $directchallanenddate, $begno, $endno){
		$odingrand=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_silent_invoice`
			INNER JOIN `stc_sale_product_silent_challan` 
			ON `stc_sale_product_silent_invoice_challan_id`=`stc_sale_product_silent_challan_id`
			WHERE (
				`stc_sale_product_silent_invoice_date`
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $directchallanbegdate)."'
				AND '".mysqli_real_escape_string($this->stc_dbs, $directchallanenddate)."'
			) AND (
				`stc_sale_product_silent_invoice_id`='".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_id`='".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_customer_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_contact_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_order_by` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_delivered_through` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			) 
			ORDER BY `stc_sale_product_silent_invoice`.`stc_sale_product_silent_invoice_id` DESC 
			LIMIT $begno,$endno
		");
		$odin='
			<table class="table table-hover table-responsive">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Invoice Id<br>Invoice Date</th>
	        	    <th scope="col">Challan Number<br>Challan Date</th>
	        	    <th scope="col">Customer Name<br>Contact Number</th>
	        	    <th scope="col">Order On<br>Order By</th> 
	        	    <th scope="col">Deliver On<br>Deliver Through</th> 
	        	    <th scope="col">Challan Basic<br>Total Value</th>  
	        	    <th scope="col">Payment Value</th>
	        	    <th scope="col">Payment Type</th>
	        	    <th scope="col">Total Dues</th>
	        	    <th scope="col">Status</th>
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='6' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$silentbvalue=0;
				$silenttvalue=0;
				$getsilentvalueqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_sale_product_silent_challan_items_product_qty`,
					    `stc_sale_product_silent_challan_items_product_sale_rate`,
					    `stc_product_gst`
					FROM `stc_sale_product_silent_challan_items` 
					INNER JOIN `stc_product` 
					ON `stc_sale_product_silent_challan_items_product_id`=`stc_product_id`
					WHERE `stc_sale_product_silent_challan_items_order_id`='".$row["stc_sale_product_silent_challan_id"]."'
				");

				foreach($getsilentvalueqry as $silentrow){
					$bvalue=$silentrow['stc_sale_product_silent_challan_items_product_qty'] * $silentrow['stc_sale_product_silent_challan_items_product_sale_rate'];
					$silentbvalue+=$bvalue;
					$totalgst=$bvalue * ($silentrow['stc_product_gst']/100);
					$silenttvalue+=$bvalue * ($silentrow['stc_product_gst']/100);
				}
				$totalvalue=$silentbvalue + $silenttvalue;

				$getsilentpaymentqry=mysqli_query($this->stc_dbs, "
					SELECT
						`stc_sale_product_silent_invoice_payment_value`,
						`stc_sale_product_silent_invoice_payment_through`
					FROM
						`stc_sale_product_silent_invoice_payment`
					WHERE
						`stc_sale_product_silent_invoice_payment_order_id`='".$row["stc_sale_product_silent_invoice_id"]."'
				");
				$payment_value=0;
				$payment_type='';
				foreach($getsilentpaymentqry as $silentpayrow){
					$payment_value+=$silentpayrow['stc_sale_product_silent_invoice_payment_value'];
					$payment_type=$silentpayrow['stc_sale_product_silent_invoice_payment_through'];
				}
				if(empty($payment_type)){
					$payment_type='Credit';
				}
				$dues=$totalvalue - $payment_value;
				$odin.='
					<tr>
       					<td>
       					  STC/'.substr("0000{$row["stc_sale_product_silent_invoice_id"]}", -5).'<br>
       					  '.date('d-m-y', strtotime($row["stc_sale_product_silent_invoice_date"])).'
       					</td>
       					<td>
       					  STC/'.substr("0000{$row["stc_sale_product_silent_challan_id"]}", -5).'<br>
       					  '.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_date"])).'
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_customer_name"].'<br>
       						'.$row["stc_sale_product_silent_challan_contact_no"].'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_order_on"])).'<br>
       						'.$row["stc_sale_product_silent_challan_order_by"].'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_delivered_on"])).'<br>
       						'.$row["stc_sale_product_silent_challan_delivered_through"].'
       					</td>
       					<td align="right" style="width:auto;">
       					  <p><b><i class="fas fa-rupee-sign"></i> '.number_format($silentbvalue, 2).'</b><br>
       					  <b><i class="fas fa-rupee-sign"></i> '.number_format($totalvalue, 2).'</b></p>
       					</td>
       					<td align="right" style="width:auto;"><b>'.number_format($payment_value, 2).'</b></td>
       					<td align="center">'.$payment_type.'.</td>
       					<td align="right" style="width:auto;"><b>'.number_format($dues, 2).'</b></td>
       					<td>
       						'.strtoupper($row["stc_sale_product_silent_challan_status"]).'
       					</td>
       					<td>
       					  <div class="row">
       					    <div class="col-sm-12">
       					     	<a 
       					     		class="stc_print_page" 
       					     		data-toggle="tooltip" 
       					     		data-placement="left" 
       					     		title="Tax Invoice" 
       					     		href="silent-invoice-print-preview.php?pid='.$row["stc_sale_product_silent_invoice_id"].'" 
       					     		style="font-size: 25px;color: white;"
	       					    >	
	       					    	<i 
	       					     		class="fa fa-print" 
	       					     		aria-hidden="true"
	       					     		></i>
	       					    </a>
       					    </div>
       					    <div class="col-sm-12">
       					     	<a 
       					     		class="stc-pay-invoice" 
       					     		id="'.$row["stc_sale_product_silent_invoice_id"].'" 
       					     		data-placement="left" 
       					     		title="Payments" 
       					     		href="#" 
       					     		style="font-size: 25px;color: black;"
	       					    >
	       					    Pay
	       					    </a>
       					    </div>
       					  </div>   
       					</td>
            		</tr>         	
		    	';
				$odingrand+=$silentbvalue + $silenttvalue;
			}
		}
		$odin.='
				</tbody>
	       	</table>
		';
		$out=array(
			'bills_all' => $odin,
			'value_total' => number_format($odingrand, 2)
		);
		return $out;
	}

	// update payment
	public function stc_invo_payment($order_id, $pay_type, $pay_amount, $notes){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$odin_item_ugetqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_sale_product_silent_challan_items_product_qty`,
				`stc_sale_product_silent_challan_items_product_sale_rate`,
				`stc_product_gst`
			FROM
				`stc_sale_product_silent_challan_items`
			INNER JOIN 
				`stc_sale_product_silent_challan` 
			ON 
				`stc_sale_product_silent_challan_id` = `stc_sale_product_silent_challan_items_order_id`
			INNER JOIN 
				`stc_sale_product_silent_invoice` 
			ON 
				`stc_sale_product_silent_invoice_challan_id` = `stc_sale_product_silent_challan_id`
			INNER JOIN 
				`stc_product` 
			ON 
				`stc_product_id` = `stc_sale_product_silent_challan_items_product_id`
			WHERE
			    `stc_sale_product_silent_invoice_id`='".mysqli_real_escape_string($this->stc_dbs, $order_id)."'
		");
		$item_amount=0;
		foreach($odin_item_ugetqry as $item_row){
			$basic=$item_row['stc_sale_product_silent_challan_items_product_qty'] * $item_row['stc_sale_product_silent_challan_items_product_sale_rate'];
			$gst=$basic * ($item_row['stc_product_gst']/100);
			$item_amount=$basic + $gst;
		}

		$odin_payment_ugetqry=mysqli_query($this->stc_dbs, "
			SELECT
				SUM(`stc_sale_product_silent_invoice_payment_value`) AS stc_payment_val
			FROM
				`stc_sale_product_silent_invoice_payment`
			WHERE
				`stc_sale_product_silent_invoice_payment_order_id`='".mysqli_real_escape_string($this->stc_dbs, $order_id)."'
		");
		$payment_amount=0;
		foreach($odin_payment_ugetqry as $payment_row){
			$payment_amount=$payment_row['stc_payment_val'];
		}

		$current_amount=$pay_amount + $payment_amount;
		if($item_amount>$current_amount){
			$odin_setpayment=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_sale_product_silent_invoice_payment`(
					`stc_sale_product_silent_invoice_payment_order_id`,
					`stc_sale_product_silent_invoice_payment_value`,
					`stc_sale_product_silent_invoice_payment_through`,
					`stc_sale_product_silent_invoice_payment_notes`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $order_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pay_amount)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pay_type)."',
					'".mysqli_real_escape_string($this->stc_dbs, $notes)."'
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

// ivoice return
class Kraven extends tesseract{
	// call invoice records
	public function stc_get_invo_records($invoice_no){
		$odinout='';
		$invoicedate='';
		$challanno='';
		$challandate='';
		$custname='';
		$cont_number='';
		$item_name='<option value="NA">Please Select Items</option>';
		$odin_getinvo=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_sale_product_silent_invoice_id`,
				`stc_sale_product_silent_invoice_date`,
				`stc_sale_product_silent_invoice_challan_id`,
				`stc_sale_product_silent_challan_date`,
				`stc_sale_product_silent_challan_customer_name`,
				`stc_sale_product_silent_challan_contact_no`,
				`stc_sale_product_silent_invoice_notes`
			FROM
				`stc_sale_product_silent_invoice`
			INNER JOIN 
				`stc_sale_product_silent_challan` 
			ON 
				`stc_sale_product_silent_challan_id` = `stc_sale_product_silent_invoice_challan_id`
			WHERE
			    `stc_sale_product_silent_invoice_id` = '".mysqli_real_escape_string($this->stc_dbs, $invoice_no)."'
		");

		if(mysqli_num_rows($odin_getinvo)>0){
			foreach($odin_getinvo as $invorow){
				$invoicedate=$invorow['stc_sale_product_silent_invoice_date'];
				$challanno=$invorow['stc_sale_product_silent_invoice_challan_id'];
				$challandate=$invorow['stc_sale_product_silent_challan_date'];
				$custname=$invorow['stc_sale_product_silent_challan_customer_name'];
				$cont_number=$invorow['stc_sale_product_silent_challan_contact_no'];
			}

			$get_chall_itemqry=mysqli_query($this->stc_dbs, "
				SELECT
				    `stc_product_id`,
				    `stc_product_name`
				FROM
				    `stc_sale_product_silent_challan_items`
				    INNER JOIN `stc_product` 
				    ON `stc_product_id`=`stc_sale_product_silent_challan_items_product_id` 
				WHERE
				    `stc_sale_product_silent_challan_items_order_id` = '".mysqli_real_escape_string($this->stc_dbs, $invorow['stc_sale_product_silent_invoice_challan_id'])."'
			");
			foreach($get_chall_itemqry as $challani_row){
				$item_name.='<option value="'.$challani_row['stc_product_id'].'">'.$challani_row['stc_product_name'].'</option>';
			}
		}else{
			$odinout="No Records Found!!!";
		}
		$odin=array(
			'invo-date' => date('Y-m-d', strtotime($invoicedate)),
			'challan-no' => $challanno,
			'challan-date' => date('Y-m-d', strtotime($challandate)),
			'cust-name' => $custname,
			'cont-number' => $cont_number,
			'item-name' => $item_name
		);
		return $odin;
	}

	// call item records
	public function stc_get_item_records($item_id, $challan_id){
		$odin=0;
		$odin_getproduct=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_sale_product_silent_challan_items_product_id`,
				`stc_sale_product_silent_challan_items_product_qty`
			FROM
				`stc_sale_product_silent_challan_items`
			WHERE
				`stc_sale_product_silent_challan_items_order_id` = '".mysqli_real_escape_string($this->stc_dbs, $challan_id)."'
			AND 
				`stc_sale_product_silent_challan_items_product_id` = '".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
		");
		foreach($odin_getproduct as $item_row){
			$odin+=$item_row['stc_sale_product_silent_challan_items_product_qty'];
		}
		return $odin;
	}

	// call items
	public function stc_call_items(){
		$odin='';
		if(empty($_SESSION["stc_return_invoice_sess"])){
			$odin.='
					<tr>
						<td colspan="6" class="text-center">Cart Empty</td>
					</tr>
			';
		}else{
			foreach($_SESSION["stc_return_invoice_sess"] as $itemssess_row){
				$odingetqry=mysqli_query($this->stc_dbs, "
					SELECT
						`stc_sale_product_silent_challan_items_product_id`,
						`stc_product_name`,
						`stc_sale_product_silent_challan_items_product_qty`
					FROM
						`stc_sale_product_silent_challan_items`
					INNER JOIN
						`stc_product` 
					ON 
						`stc_product_id`=`stc_sale_product_silent_challan_items_product_id` 
					WHERE
					    `stc_sale_product_silent_challan_items_order_id` = '".mysqli_real_escape_string($this->stc_dbs, $itemssess_row['invo_challan_id'])."'
					AND 
						`stc_sale_product_silent_challan_items_product_id` = '".mysqli_real_escape_string($this->stc_dbs, $itemssess_row['invo_item_id'])."'
				");
				foreach($odingetqry as $qryitemrow){
					$odin.='
						<tr>
							<td>'.$itemssess_row['invo_challan_id'].'</td>
							<td>'.$itemssess_row['invo_item_id'].'</td>
							<td>'.$qryitemrow['stc_product_name'].'</td>
							<td class="text-right">'.number_format($itemssess_row['invo_quantity'], 2).'</td>
							<td class="text-right">'.number_format($itemssess_row['invo_ret_qty'], 2).'</td>
							<td class="text-center">
								<a href="#" style="font-size: 40px;" id="'.$itemssess_row['invo_item_id'].'" class="stc-remove-items">
									<i class="fa fa-times-circle"></i>
								</a>
							</td>
						</tr>
					';
				}
			}
		}
		return $odin;
	}

	// save return
	public function stc_save_return($challan_id, $remarks){
		$odin='';
		$odinqryreturncheck=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_silent_challan_return_id`,
				`stc_silent_challan_return_date`,
				`stc_silent_challan_return_challan_id`,
				`stc_silent_challan_return_remarks`,
				`stc_silent_challan_return_created_by`
			FROM
				`stc_silent_challan_return`
			WHERE
				`stc_silent_challan_return_challan_id` = '".mysqli_real_escape_string($this->stc_dbs, $challan_id)."'
		");

		if(mysqli_num_rows($odinqryreturncheck)>0){
			$odin='This Invoice/Challan is already created.';
		}else{
			$date=date("Y-m-d H:i:s");
			$odinqryreturnset=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_silent_challan_return`(
				    `stc_silent_challan_return_date`,
				    `stc_silent_challan_return_challan_id`,
				    `stc_silent_challan_return_remarks`,
				    `stc_silent_challan_return_created_by`
				)VALUES(
					'".$date."',
					'".mysqli_real_escape_string($this->stc_dbs, $challan_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $remarks)."',
					'".$_SESSION['stc_electro_user_id']."'
				)
			");
			if($odinqryreturnset){
				$return_id=0;
				$odingethighqry=mysqli_query($this->stc_dbs, "
					SELECT `stc_silent_challan_return_id` FROM `stc_silent_challan_return` ORDER BY `stc_silent_challan_return_id` DESC LIMIT 0,1
				");
				foreach($odingethighqry as $returnrow){
					$return_id=$returnrow['stc_silent_challan_return_id'];
				}
				foreach($_SESSION['stc_return_invoice_sess'] as $isert_returnrow){
					$odinqryreturnitemset=mysqli_query($this->stc_dbs, "
						INSERT INTO `stc_silent_challan_return_items`(
						    `stc_silent_challan_return_items_order_id`,
						    `stc_silent_challan_return_items_item_id`,
						    `stc_silent_challan_return_items_qty`
						)VALUES(
							'".mysqli_real_escape_string($this->stc_dbs, $return_id)."',
							'".mysqli_real_escape_string($this->stc_dbs, $isert_returnrow['invo_item_id'])."',
							'".mysqli_real_escape_string($this->stc_dbs, $isert_returnrow['invo_ret_qty'])."'
						)
					");

					$odingetinvqry=mysqli_query($this->stc_dbs, "
						SELECT
						    `stc_electronics_inventory_item_qty`
						FROM
						    `stc_electronics_inventory`
						WHERE
							`stc_electronics_inventory_item_id` = '".mysqli_real_escape_string($this->stc_dbs, $isert_returnrow['invo_item_id'])."'
					");

					$itemqty=0;
					foreach($odingetinvqry as $odingetinvrow){
						$itemqty+=$odingetinvrow['stc_electronics_inventory_item_qty'];
					}

					$inpqty=$itemqty + $isert_returnrow['invo_ret_qty'];

					$updateinvqry=mysqli_query($this->stc_dbs, "
						UPDATE
							`stc_electronics_inventory`
						SET 
							`stc_electronics_inventory_item_qty` = '".mysqli_real_escape_string($this->stc_dbs, $isert_returnrow['invo_ret_qty'])."'
						WHERE
							`stc_electronics_inventory_item_id` = '".mysqli_real_escape_string($this->stc_dbs, $isert_returnrow['invo_item_id'])."'
					");
				}
				unset($_SESSION['stc_return_invoice_sess']);
				$odin='Booyeah!!! Return Done Successfully.';
			}else{
				$odin='Hmm!!! Something went wrong, Return Not Completed Successfully.';
			}

			$updatechallanqry=mysqli_query($this->stc_dbs, "
				UPDATE
					`stc_sale_product_silent_challan`
				SET
					`stc_sale_product_silent_challan_status` = 'returned'
				WHERE
					`stc_sale_product_silent_challan_id` = '".mysqli_real_escape_string($this->stc_dbs, $challan_id)."'
			");
		}
		return $odin;
	}

	// call return
	public function stc_call_return($begdate, $enddate){
		$odin='';
		$odinqryreturncallqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_silent_challan_return_id`,
				`stc_silent_challan_return_date`,
				`stc_sale_product_silent_invoice_id`,
				`stc_sale_product_silent_invoice_date`,
				`stc_sale_product_silent_challan_id`,
				`stc_sale_product_silent_challan_date`,
				`stc_sale_product_silent_challan_customer_name`,
				`stc_sale_product_silent_challan_contact_no`,
				`stc_sale_product_silent_challan_customer_billaddress`,
				`stc_silent_challan_return_remarks`,
				`stc_electronics_user_fullName`
			FROM
				`stc_silent_challan_return`
			INNER JOIN 
				`stc_sale_product_silent_challan` 
			ON 
				`stc_sale_product_silent_challan_id` = `stc_silent_challan_return_challan_id`
			INNER JOIN 
				`stc_sale_product_silent_invoice` 
			ON 
				`stc_sale_product_silent_invoice_challan_id` = `stc_sale_product_silent_challan_id`
			INNER JOIN 
				`stc_electronics_user` 
			ON 
				`stc_electronics_user_id` = `stc_silent_challan_return_created_by`
			WHERE
			    DATE(`stc_silent_challan_return_date`) 
			    BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $begdate)."'
			    AND '".mysqli_real_escape_string($this->stc_dbs, $enddate)."'
			ORDER BY `stc_silent_challan_return_id` DESC
		");
		if(mysqli_num_rows($odinqryreturncallqry)>0){
			foreach($odinqryreturncallqry as $odinqryreturnrow){
				$odin.='
					<tr>
						<td title="Return Id, Return Date">
							STC/R/'.$odinqryreturnrow['stc_silent_challan_return_id'].'<br>
							'.date('d-m-Y', strtotime($odinqryreturnrow['stc_silent_challan_return_date'])).'
						</td>
						<td title="Invoice Id, Invoice Date">
							STC/'.$odinqryreturnrow['stc_sale_product_silent_invoice_id'].'<br>
							'.date('d-m-Y', strtotime($odinqryreturnrow['stc_sale_product_silent_invoice_date'])).'
						</td>
						<td title="Challan Id, Challan Date">
							STC/'.$odinqryreturnrow['stc_sale_product_silent_challan_id'].'<br>
							'.date('d-m-Y', strtotime($odinqryreturnrow['stc_sale_product_silent_challan_date'])).'
						</td>
						<td title="Customer Name">
							'.$odinqryreturnrow['stc_sale_product_silent_challan_customer_name'].'
						</td>
						<td title="Customer Contact Number">
							'.$odinqryreturnrow['stc_sale_product_silent_challan_contact_no'].'
						</td>
						<td title="Customer Address">
							'.$odinqryreturnrow['stc_sale_product_silent_challan_customer_billaddress'].'
						</td>
						<td title="Remarks">
							'.$odinqryreturnrow['stc_silent_challan_return_remarks'].'
						</td>
						<td title="Created By">
							'.$odinqryreturnrow['stc_electronics_user_fullName'].'
						</td>
						<td title="Action">
							<a href="#" class="form-control btn btn-success stc-show-return-items" id="'.$odinqryreturnrow['stc_silent_challan_return_id'].'">
								<i class="fa fa-eye"></i>
							</a>
						</td>
					</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td colspan="8">No Record found!!!</td>
				</tr>
			';
		}
		return $odin;
	}

	// search return
	public function stc_search_return($query, $begdate, $enddate){
		$odin='';
		$odinqryreturncallqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_silent_challan_return_id`,
				`stc_silent_challan_return_date`,
				`stc_sale_product_silent_invoice_id`,
				`stc_sale_product_silent_invoice_date`,
				`stc_sale_product_silent_challan_id`,
				`stc_sale_product_silent_challan_date`,
				`stc_sale_product_silent_challan_customer_name`,
				`stc_sale_product_silent_challan_contact_no`,
				`stc_sale_product_silent_challan_customer_billaddress`,
				`stc_silent_challan_return_remarks`,
				`stc_electronics_user_fullName`
			FROM
				`stc_silent_challan_return`
			INNER JOIN 
				`stc_sale_product_silent_challan` 
			ON 
				`stc_sale_product_silent_challan_id` = `stc_silent_challan_return_challan_id`
			INNER JOIN 
				`stc_sale_product_silent_invoice` 
			ON 
				`stc_sale_product_silent_invoice_challan_id` = `stc_sale_product_silent_challan_id`
			INNER JOIN 
				`stc_electronics_user` 
			ON 
				`stc_electronics_user_id` = `stc_silent_challan_return_created_by`
			WHERE (
				DATE(`stc_silent_challan_return_date`) 
			    BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $begdate)."'
			    AND '".mysqli_real_escape_string($this->stc_dbs, $enddate)."'
			) AND (
				`stc_silent_challan_return_id` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $query)."' OR 
				`stc_sale_product_silent_challan_id` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $query)."' OR 
				`stc_sale_product_silent_challan_customer_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $query)."' OR 
				`stc_sale_product_silent_challan_contact_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $query)."' OR 
				`stc_electronics_user_fullName` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $query)."'
			)
			ORDER BY `stc_silent_challan_return_id` DESC
		");
		if(mysqli_num_rows($odinqryreturncallqry)>0){
			foreach($odinqryreturncallqry as $odinqryreturnrow){
				$odin.='
					<tr>
						<td title="Return Id, Return Date">
							STC/R/'.$odinqryreturnrow['stc_silent_challan_return_id'].'<br>
							'.date('d-m-Y', strtotime($odinqryreturnrow['stc_silent_challan_return_date'])).'
						</td>
						<td title="Invoice Id, Invoice Date">
							STC/'.$odinqryreturnrow['stc_sale_product_silent_invoice_id'].'<br>
							'.date('d-m-Y', strtotime($odinqryreturnrow['stc_sale_product_silent_invoice_date'])).'
						</td>
						<td title="Challan Id, Challan Date">
							STC/'.$odinqryreturnrow['stc_sale_product_silent_challan_id'].'<br>
							'.date('d-m-Y', strtotime($odinqryreturnrow['stc_sale_product_silent_challan_date'])).'
						</td>
						<td title="Customer Name">
							'.$odinqryreturnrow['stc_sale_product_silent_challan_customer_name'].'
						</td>
						<td title="Customer Contact Number">
							'.$odinqryreturnrow['stc_sale_product_silent_challan_contact_no'].'
						</td>
						<td title="Customer Address">
							'.$odinqryreturnrow['stc_sale_product_silent_challan_customer_billaddress'].'
						</td>
						<td title="Remarks">
							'.$odinqryreturnrow['stc_silent_challan_return_remarks'].'
						</td>
						<td title="Created By">
							'.$odinqryreturnrow['stc_electronics_user_fullName'].'
						</td>
						<td title="Action">
							<a href="#" class="form-control btn btn-success stc-show-return-items" id="'.$odinqryreturnrow['stc_silent_challan_return_id'].'">
								<i class="fa fa-eye"></i>
							</a>
						</td>
					</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td colspan="8">No Record found!!!</td>
				</tr>
			';
		}
		return $odin;
	}

	// call return items
	public function stc_call_return_items($return_id){
		$odin='';
		$odinreturnitemsqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_product_id`,
				`stc_product_name`,
				`stc_cat_name`,
				`stc_silent_challan_return_items_qty`
			FROM
				`stc_silent_challan_return_items`
			INNER JOIN 
				`stc_product` 
			ON 
				`stc_product_id` = `stc_silent_challan_return_items_item_id`
			INNER JOIN 
				`stc_category` 
			ON 
				`stc_product_cat_id` = `stc_cat_id`
			WHERE
				`stc_silent_challan_return_items_order_id` = '".mysqli_real_escape_string($this->stc_dbs, $return_id)."'
		");
		if(mysqli_num_rows($odinreturnitemsqry)>0){
			foreach($odinreturnitemsqry as $odinreturnitemsrow){
				$odin.='
					<tr>
						<td>'.$odinreturnitemsrow['stc_product_id'].'</td>
						<td>'.$odinreturnitemsrow['stc_product_name'].'</td>
						<td>'.$odinreturnitemsrow['stc_cat_name'].'</td>
						<td class="text-right">'.number_format($odinreturnitemsrow['stc_silent_challan_return_items_qty'], 2).'</td>
					</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td colspan="4" class="text-center">No Item Found!!!</td>
				</tr>
			';
		}
		return $odin;
	}
}
#<------------------------------------------------------------------------------------------>
#<---------------------------------Silent Challan Section----------------------------------->
#<------------------------------------------------------------------------------------------>

// Show Sale Orders On Sale Order Page
if(isset($_POST['stccallss'])){
	$challanbegdate=date("Y-m-d h:i:s", strtotime($_POST['begdate']));
	$challanenddate=date("Y-m-d h:i:s", strtotime($_POST['enddate']));
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_call_sale_orders($challanbegdate, $challanenddate);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

if(isset($_POST['stcfilterchallan'])){
	$search = str_replace(",", "|", $_POST["stcfilterchallan"]);
	$challanbegdate=date("Y-m-d h:i:s", strtotime($_POST['challanbegdate']));
	$challanenddate=date("Y-m-d h:i:s", strtotime($_POST['challanenddate']));
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_filter_challan($search, $challanbegdate, $challanenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// show Product in sale Order page
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
		<table class="table table-hover" align="centre">
			<thead>
			  <tr>
			    <th scope="col">#</th>
			    <th scope="col" style="width: 20%;">Items</th>
			    <th scope="col">HSN Code</th> 
			    <th scope="col">Unit</th>
			    <th scope="col">Qty</th>                         
			    <th scope="col">Price</th>
			    <th scope="col">Amount</th>
			    <th scope="col">GST</th>
			    <th scope="col">Amount</th>
			    <th scope="col">Action</th>
			  </tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_silent_challan_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_silent_challan_sess"] as $keys => $values) {  
				$amount=$values["product_quantity"] * $values["product_sale_price"];
				$gstamount = $amount-($amount*(100/(100+$values["product_gst"])));
				$slno++;
				$basicamt=$amount - $gstamount;
				$order_table .= ' 
					<tr>
						<td>'.$slno.'</td>
						<td>'.$values["product_name"].'</td>
						<td>'.$values["product_hsncode"].'</td>
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
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($basicamt, 2).'</td>
						<td><i class="fas fa-rupee-sign"></i> '.number_format($basicamt * $values['product_quantity'], 2).'</td>
						<td> '.$values["product_gst"].'% </td>
						<td>
							<input
								style="width: 55%;padding: 5px;height: 40px;"
							  type="text"
							  id="stcratesc'.$values["product_id"].'"
							  class="validate"
							  placeholder="Rate"
							  value="'.number_format($values["product_sale_price"], 2, '.', '').'"
							/>
							<a data-product_id="'.$values["product_id"].'" class="stcratesilent" href="#">
								<span class="material-icons">done</span>
							</a>
						</td>
						<td>
							<a style="color: red;font-size:20px;" href="#" id="'.$values["product_id"].'" class="stcdelsilentbtn">
								<span class="material-icons">delete</span>
							</a>
						</td>
					</tr>
				';  
				$total += $values["product_quantity"] * $values["product_sale_price"];  
				$totalgst +=$gstamount;
			}  
			$ekdumt=$total;
			$order_table .= ' 
			   <tr>
				  <td colspan="8"><h4 align="right">Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total, 2).'</td>
				  <td><input type="hidden" name="hidden_bvalue" id="stcbasicvalue" value="'.$total.'"></td>
				</tr>
				<tr>
				  <td colspan="8"><h4 align="right">CGST</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
				  <td></td>
				</tr>
				<tr>
				  <td colspan="8"><h4 align="right">SGST</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
				  <td></td>
				</tr>
				<tr>
				  <td colspan="8"><h4 align="right">Total Tax</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst, 2).'</td>
				  <input type="hidden" id="stc_grand_offset_table_value" value="'.$ekdumt.'">
				  <td></td>
				</tr>

				<tr>
            	  <td colspan="3"><h4 align="right">Freight Charges</h4></td>
            	  <td colspan="2" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcfc" name="stcfc" value="0" placeholder="FC" style="width:100%;">
            	  </td>
            	  <td colspan="3"><h4 align="right">Packing & Forwarding</h4></td>
            	  <td colspan="2" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcpf" name="stcpf" value="0" placeholder="PF" style="width:100%;">
            	    <a 
            	    	style="font-size: 25px;font-weight: bold;color: red;"
             		  	class="stcsalec"
             		  	href="#"
             		>
						<span class="material-icons">done</span>
             		</a>
            	  </td>
            	</tr> 
        		<tr>
				  <td colspan="8"><h4 align="right">Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.round($total, 2).'</span></td>
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
		$percalamount = ($_POST['product_price'] * $_POST['product_sale_percent'])/100;
		$pdsale_price = $percalamount + $_POST['product_price'];

		$amount=$_POST["product_quantity"] * $_POST["product_price"];
		$gstamount = $amount-($amount*(100/(100+$_POST["product_gst"])));
		$purchaseprice=$amount - $gstamount;

		if(isset($_SESSION["stc_silent_challan_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_silent_challan_sess"] as $keys => $values) {  
				if($_SESSION["stc_silent_challan_sess"][$keys]['product_id'] == $_POST["product_id"]) {  
					$is_available++;  
					$_SESSION["stc_silent_challan_sess"][$keys]['product_quantity'] = $_SESSION["stc_silent_challan_sess"][$keys]['product_quantity'] + $_POST["product_quantity"];  
					echo "Sale Order Cart Item Quantity Increased!!!";
				}  
			}  
		    if($is_available < 1) {  
		    	$item_array = array(  
		    		'product_id'             =>     $_POST["product_id"],  
		    		'product_name'           =>     $_POST["product_name"],  
		    		'product_unit'           =>     $_POST["product_unit"],  
		    		'product_quantity'       =>     $_POST["product_quantity"],
		    		'product_hsncode'        =>     $_POST["product_hsncode"],
		    		'product_gst'       	 =>     $_POST["product_gst"],
		    		'product_price'          =>     $purchaseprice,
		    		'product_sale_price'     =>     $pdsale_price 
		    	);  
		    	$_SESSION["stc_silent_challan_sess"][] = $item_array;  
		    	echo "Sale Order Created & Item Added to Cart!!!";
		    }  
		}else{  
			$item_array = array(  
			    'product_id'             =>     $_POST["product_id"],  
		    	'product_name'           =>     $_POST["product_name"],  
		    	'product_unit'           =>     $_POST["product_unit"],  
		    	'product_quantity'       =>     $_POST["product_quantity"],
		    	'product_hsncode'        =>     $_POST["product_hsncode"],
		    	'product_gst'       	 =>     $_POST["product_gst"],
		    	'product_price'          =>     $purchaseprice,
		    	'product_sale_price'     =>     $pdsale_price
			);   
			$_SESSION["stc_silent_challan_sess"][] = $item_array;  
		     echo "Item Added to Sale Order Cart!!!";
		}  
    }  
}

// sale cart qty change
if(isset($_POST["sale_quantity_action"])){  
  	foreach($_SESSION["stc_silent_challan_sess"] as $keys => $values){  
	    if($_SESSION["stc_silent_challan_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_silent_challan_sess"][$keys]['product_quantity'] = $_POST["quantity"];
    	}  
  	}  
}

// change price of session Sale order
if(isset($_POST["stc_price_action"])){  
  	foreach($_SESSION["stc_silent_challan_sess"] as $keys => $values){  
	    if($_SESSION["stc_silent_challan_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_silent_challan_sess"][$keys]['product_sale_price'] = $_POST["price"];
    	}  
  	}  
}

// delete from sale cart of session Sale order
if(isset($_POST['stcdelsalelinei'])){  
     foreach($_SESSION["stc_silent_challan_sess"] as $keys => $values){  
          if($values["product_id"] == $_POST["product_id"]){  
               unset($_SESSION["stc_silent_challan_sess"][$keys]);  
               echo "Product Removed!!!";  
          }  
     }  
}

// calculate charges of sale orders
if(isset($_POST['do_plus_minus_on_sale'])){
	$fc=$_POST['freightcharge'];
	$pf=$_POST['packingandforwardingcharge'];
	$gt=$_POST['grand_total'];

	$gstfc=($fc * 18)/100;
	$gstpf=($pf * 18)/100;

	$out=($gstfc + $fc) + ($gstpf + $pf) + $gt;
	echo $out;
}

// save sale order
if(isset($_POST['save_sale_action'])){
	$out='';
	$silent_challandate				= date('Y-m-d', strtotime($_POST['silent_challandate']));
	$silent_customername			= $_POST['silent_customername'];
	$silent_order_billaddress		= $_POST['silent_order_billaddress'];
	$silent_order_shipaddress		= $_POST['silent_order_shipaddress'];
	$silent_order_contnumber		= $_POST['silent_order_contnumber'];
	$silent_order_orderon			= date('Y-m-d h:i:s', strtotime($_POST['silent_order_orderon']));
	$silent_order_deliveron			= date('Y-m-d h:i:s', strtotime($_POST['silent_order_deliveron']));
	$silent_order_orderby			= $_POST['silent_order_orderby'];
	$silent_order_deliverthrough	= $_POST['silent_order_deliverthrough'];
	$silent_order_warrantystatus	= $_POST['silent_order_warrantystatus'];
	$silent_order_stcfc				= $_POST['silent_order_stcfc'];
	$silent_order_stcpf				= $_POST['silent_order_stcpf'];
	$silent_order_notes				= $_POST['silent_order_notes'];

	$fridaysale=new aetherSale();
	$fridaysaleitems=new aetherSale();
	if(empty($silent_customername) || empty($silent_challandate) || empty($silent_order_billaddress)){
		$out.="Please Fill All Fields!!!";
	}elseif(empty($_SESSION['stc_electro_user_id'])){
		header("Location:../index.html");
	}elseif(empty($_SESSION['stc_silent_challan_sess'])){
		$out.="Excuse me!!! Are you blind??? If you ain't then please add items first.";
	}else{
		$objfridaygrn=$fridaysale->sale_process(
			$silent_challandate,
			$silent_customername,
			$silent_order_billaddress,
			$silent_order_shipaddress,
			$silent_order_contnumber,
			$silent_order_orderon,
			$silent_order_deliveron,
			$silent_order_orderby,
			$silent_order_deliverthrough,
			$silent_order_warrantystatus,
			$silent_order_stcfc,
			$silent_order_stcpf,
			$silent_order_notes
		);
		if($objfridaygrn=='Sale Done!!!'){
			$objfridaygrnitems=$fridaysaleitems->stc_save_sale_pro_items();
			$out=$objfridaygrnitems;
			unset($_SESSION['stc_silent_challan_sess']);
		}else{
			$out .= "Something went Wrong Please Check & Try Again!!!";
		}
	}
	echo $out;
}

/*---------------------------silent invoice section-------------------------------*/
// Show Sale Orders invoice On Sale Order Page
if(isset($_POST['load_invoices'])){
	$loadchallanbegdate=date("Y-m-d", strtotime($_POST['loadchallanbegdate']));
	$loadchallanenddate=date("Y-m-d", strtotime($_POST['loadchallanenddate']));
	$objloki=new Yggdrasil();
	$objlokiout=$objloki->stc_call_sale_orders_invoices($loadchallanbegdate, $loadchallanenddate);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// search by key
if(isset($_POST['stcfilterinvoice'])){
	$search = str_replace(",", "|", $_POST["stcfilterinvoice"]);
	$directchallanbegdate=date("Y-m-d", strtotime($_POST['directchallanbegdate']));
	$directchallanenddate=date("Y-m-d", strtotime($_POST['directchallanenddate']));
	$begno=$_POST['begno'];
	$endno=$_POST['endno'];

	$objloki=new Yggdrasil();
	$objlokiout=$objloki->stc_filter_saleorder($search, $directchallanbegdate, $directchallanenddate, $begno, $endno);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// show Product in sale Order page
if(isset($_POST['call_po_on_choose_customer'])){
	$objloki=new Yggdrasil();
	$objlokiout=$objloki->stc_search_challan();
	// echo json_encode($objlokiout);
	echo $objlokiout;
}

// show sale cart
if(isset($_POST['comon_invo_sess'])){
	$objloki=new Yggdrasil();	
	$order_table = ''; 
	$order_table .= '  
		<table class="table table-hover" align="centre">
			<thead>
			  <tr>
			    <th scope="col">#</th>
			    <th scope="col">Challan No</th>
			    <th scope="col">Challan Date</th> 
			    <th scope="col">Customer Name</th> 
			    <th scope="col">Contact Person</th>  
			    <th scope="col">Contact Number</th>
			    <th scope="col">Basic Value</th>
			    <th scope="col">Action</th>
			  </tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_sale_order_silent_invo_sess"])){  
			$objlokiout=$objloki->stc_show_invo_sess();
			$order_table .= $objlokiout;
		}else{
			$order_table.='
				<tr>  
					<td colspan="10" align="center">  
					    <h3>Invoice Session Empty!!!</h3>
					</td>  
				</tr>  
			';
		}
	$order_table .= '
			
		  </tbody>
		</table>
		'; 
	echo $order_table;
}

// add to cart sale session obj
if(isset($_POST['add_invo_sess_action'])){
	if(empty($_SESSION["stc_sale_order_silent_invo_sess"])){
		if(isset($_SESSION["stc_sale_order_silent_invo_sess"])) {
			$is_available=0;
			foreach($_SESSION["stc_sale_order_silent_invo_sess"] as $keys => $values) {  
				if($_SESSION["stc_sale_order_silent_invo_sess"][$keys]['invo_challan_id'] == $_POST["invo_challan_id"]) {  
				     $is_available++; 
				     echo "This Challan is Already on a List!!!";
				}  
			} 
			if($is_available < 1) {  
				$item_array = array(  
				     'invo_challan_id' => $_POST["invo_challan_id"]
				);  
				$_SESSION["stc_sale_order_silent_invo_sess"][] = $item_array;  
				echo "Invoice Session Created & Challan Added to Cart!!!";
			}
		}else{  
			$item_array = array(  
			    'invo_challan_id' => $_POST["invo_challan_id"]
			);
			$_SESSION["stc_sale_order_silent_invo_sess"][] = $item_array;
		     echo "Challan Added to Invoice Session Cart!!!";
		}
	}else{
		echo "Hey you dont have to insert this again and again!!! check in cart below:)";
	}
}

// delete from sale cart of session Sale order
if(isset($_POST['stcdelinvolinei'])){  
	foreach($_SESSION["stc_sale_order_silent_invo_sess"] as $keys => $values){  
		if($values["invo_challan_id"] == $_POST["product_id"]){  
			unset($_SESSION["stc_sale_order_silent_invo_sess"][$keys]);  
			echo "Challan Removed!!!";  
		}  
	}  
}

// save sale order
if(isset($_POST['save_invo_action'])){
	$out='';
	$stcinvodate=date('Y-m-d', strtotime($_POST['stcinvodate']));
	$stcinvonotes=$_POST['stcinvonotes'];
	
	$fridayinvo=new Yggdrasil();
	$fridayinvochallan=new Yggdrasil();
	if(empty($stcinvodate)){
		$out.="Please Fill Date First!!!";
	}elseif(empty($_SESSION['stc_sale_order_silent_invo_sess'])){
		$out .= "Dude!!! You need to add challan in session first.";
	}else{
		$objfridayinvo=$fridayinvo->stc_invo_process(
				$stcinvodate,
				$stcinvonotes
		);
		if($objfridayinvo=='invoinserted'){
			$objfridayinvochallan=$fridayinvochallan->stc_invo_challan_process();
			$out=$objfridayinvochallan;
			unset($_SESSION['stc_sale_order_silent_invo_sess']);
		}else{
			$out .= "Something went wrong. Please check & try again!!!";
		}
	}
	echo $out;
}

// update payment
if(isset($_POST['save_payment_update'])){
	$order_id=$_POST['order_id'];
	$pay_type=$_POST['pay_type'];
	$pay_amount=$_POST['pay_amount'];
	$notes=$_POST['notes'];
	$fridayinvopayment=new Yggdrasil();
	$objfridayinvo=$fridayinvopayment->stc_invo_payment($order_id, $pay_type, $pay_amount, $notes);
	echo $objfridayinvo;
}

/*---------------------------silent return section-------------------------------*/
// invoice record
if(isset($_POST['stc_get_rec_from_invo'])){
	$invoice_no=$_POST['invoice_no'];
	$fridayinvopayment=new Kraven();
	$objfridayinvo=$fridayinvopayment->stc_get_invo_records($invoice_no);
	echo json_encode($objfridayinvo);
}

// product_record
if(isset($_POST['stc_get_rec_from_pd'])){
	$item_id=$_POST['item_id'];
	$challan_id=$_POST['challan_id'];
	$fridayinvopayment=new Kraven();
	$objfridayinvo=$fridayinvopayment->stc_get_item_records($item_id, $challan_id);
	echo json_encode($objfridayinvo);
}

// add item to cart
if(isset($_POST['stc_set_return_items_cart'])){
	if(isset($_SESSION["stc_return_invoice_sess"])) {
		$is_available=0;
		foreach($_SESSION["stc_return_invoice_sess"] as $keys => $values) {  
			if(($_SESSION["stc_return_invoice_sess"][$keys]['invo_item_id'] == $_POST['item_id']) || ($_SESSION["stc_return_invoice_sess"][$keys]['invo_challan_id'] == $_POST['challan_id'])) {  
				$is_available++; 
				echo "This Item is Already on a List or You are Adding Another Challan Line Items!!!";
			}  
		} 
		if($is_available < 1) {  
			$item_array = array(  
				'invo_challan_id' => $_POST['challan_id'],
				'invo_item_id' 	  => $_POST['item_id'],
				'invo_quantity'   => $_POST['pd_qty'],
				'invo_ret_qty'    => $_POST['pd_ret_qty']
			);  
			$_SESSION["stc_return_invoice_sess"][] = $item_array;  
			echo "Invoice Session Created & Challan Added to Cart!!!";
		}
	}else{  
		$item_array = array(  
			'invo_challan_id' => $_POST['challan_id'],
			'invo_item_id' 	  => $_POST['item_id'],
			'invo_quantity'   => $_POST['pd_qty'],
			'invo_ret_qty'    => $_POST['pd_ret_qty']
		);
		$_SESSION["stc_return_invoice_sess"][] = $item_array;
		echo "Item Added to Invoice Return Session Cart!!!";
	}
}

// show cart
if(isset($_POST['stc_call_items'])){
	$fridayinvopayment=new Kraven();
	$objfridayinvo=$fridayinvopayment->stc_call_items();
	// echo json_encode($objfridayinvo);
	echo $objfridayinvo;
}

// remove item
if(isset($_POST['stc_remove_item_hit'])){
	foreach($_SESSION["stc_return_invoice_sess"] as $keys => $values){  
		if($values["invo_item_id"] == $_POST["item_id"]){  
			unset($_SESSION["stc_return_invoice_sess"][$keys]);  
			echo "Item Removed!!!";  
		}  
	}  
}

// save return
if(isset($_POST['stc_save_return'])){
	$challan_id=$_POST['challan_id'];
	$remarks=$_POST['remarks'];
	$fridayinvopayment=new Kraven();
	$objfridayinvo=$fridayinvopayment->stc_save_return($challan_id, $remarks);
	echo $objfridayinvo;
}

// call return invoice
if(isset($_POST['stccallreturn'])){
	$begdate=$_POST['begdate'];
	$enddate=$_POST['enddate'];
	$fridayinvopayment=new Kraven();
	$objfridayinvo=$fridayinvopayment->stc_call_return($begdate, $enddate);
	echo json_encode($objfridayinvo);
	// echo $objfridayinvo;
}

// search return invoice
if(isset($_POST['stcfilterreturn'])){
	$query=$_POST['query'];
	$begdate=$_POST['returnbegdate'];
	$enddate=$_POST['returnenddate'];
	$fridayinvopayment=new Kraven();
	$objfridayinvo=$fridayinvopayment->stc_search_return($query, $begdate, $enddate);
	echo json_encode($objfridayinvo);
	// echo $objfridayinvo;
}

// return items call
if(isset($_POST['stcreturnitemscall'])){
	$return_id=$_POST['return_id'];
	$fridayinvopayment=new Kraven();
	$objfridayinvo=$fridayinvopayment->stc_call_return_items($return_id);
	echo json_encode($objfridayinvo);
	// echo $objfridayinvo;
}
?>