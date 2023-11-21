<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Purchase Order --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class po_process extends tesseract{	

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

	// call product on po page
	public function stc_search_product_name($searchme){
		$perfectsearchme=strtoupper($searchme);
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
					LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
					WHERE `stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."' 
					OR  `stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."' 
					ORDER BY `stc_product_id` ASC LIMIT 0,50
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_product_name"]);
				$stcqty=$row["stc_item_inventory_pd_qty"];
				if($stcqty==0){
					$stcqty=0;
				}else{
					$stcqty;
				}
				$odin.='	
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
		             	  <div class="row">
		             	    <div class="el-wrapper">
		             	      <div class="box-up">
		             	        <img class="img" style="height: 100%; width:100%;" src="stc_product_image/'.$row["stc_product_image"].'" alt="">
		             	        <div class="img-info">
		             	          <div class="info-inner">
		             	            <span class="p-name">'.htmlentities($row["stc_product_name"]).'</span>
		             	            <span class="p-company">'.$row["stc_cat_name"].' / '.$row["stc_product_hsncode"].'</span>
		             	            <span class="p-qty" >'.$stcqty.''.$row["stc_product_unit"].'</span>
		             	            	<input
					                	  id="stcpoqty'.$row["stc_product_id"].'"
					                	  name="stcpoqty"
					                	  type="number"
					                	  placeholder="Quantity"
					                	  class="form-control validate"
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
			             	          	type="number" 
			             	          	name="stcpdprice" 
			             	          	id="stcpdprice'.$row["stc_product_id"].'" 
			             	          	placeholder="Price"
			             	          	class="form-control validate"
			             	          />
		             	          	</div>
		             	        </div>
		             	      </div>

		             	      <div class="box-down">
		             	        <div class="h-bg">
		             	          <div class="h-bg-inner"></div>
		             	        </div>

		             	        <a class="cart add_to_cart" href="#" id="'.$row["stc_product_id"].'">
		             	          <span class="price">'.$row["stc_sub_cat_name"].'/'.$row["stc_brand_title"].'</span>
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

	// save purchase product order
	public function stc_save_purchase_pro($stcmerchantid, $stcbasicvalue, $stctermsandcond, $gtonumberinst){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_purchase_product`(
				`stc_purchase_product_merchant_id`, 
				`stc_purchase_product_order_date`, 
				`stc_purchase_product_basic_value`, 
				`stc_purchase_product_status`, 
				`stc_purchase_product_notes`, 
				`stc_purchase_product_created_by`,
				`stc_purchase_product_purchased_for`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $stcmerchantid)."', 
				NOW(), 
				'".mysqli_real_escape_string($this->stc_dbs, $stcbasicvalue)."', 
				'process', 
				'".mysqli_real_escape_string($this->stc_dbs, $stctermsandcond)."', 
				'".$_SESSION['stc_admin_info_id']."',
				'".mysqli_real_escape_string($this->stc_dbs, $gtonumberinst)."'
			)
		");
		if($friday_loki){
			$odin = "Success";
		}else{
			$odin = "Not Ok!!";
		}
		return $odin;
	}

	// save purchase product order items
	public function stc_save_purchase_pro_items(){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_purchase_product` ORDER BY `stc_purchase_product`.`stc_purchase_product_id` DESC");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_purchase_product_id'];
		if(empty($_SESSION['stc_purchase_order_sess'])){
			$odin = "Please Add Item First!!";
		}else{
			foreach ($_SESSION["stc_purchase_order_sess"] as $key=>$value) {
		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_purchase_product_items`(
		    			`stc_purchase_product_items_order_id`, 
		    			`stc_purchase_product_items_product_id`, 
		    			`stc_purchase_product_items_qty`, 
		    			`stc_purchase_product_items_rate`
		    		) VALUES (
		    			'".mysqli_real_escape_string($this->stc_dbs, $get_purchase_product_id)."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_id'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_quantity'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_price'])."'
		    		)
		    	");	    	
		    }
		}
		return $odin;
	}

	// call purchase product on po page for edit grn & payment
	public function stc_call_purchase_product($begdate, $enddate){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_purchase_product` 
			LEFT JOIN `stc_merchant` 
			ON `stc_purchase_product_merchant_id`=`stc_merchant_id` 
			WHERE `stc_purchase_product_order_date`
			BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $begdate)."'
			AND '".mysqli_real_escape_string($this->stc_dbs, $enddate)."'
			ORDER BY `stc_purchase_product`.`stc_purchase_product_id` 
			DESC LIMIT 0,50
		");
		$odin='
			<table class="table table-hover ">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Purchase Order Number</th>
	        	    <th scope="col">Merchant</th> 
	        	    <th scope="col">Status</th>
	        	    <th style="width: 15%;" scope="col">Basic Value</th>                         
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td>No record found</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_purchase_product_items` WHERE `stc_purchase_product_items_order_id`='".$row["stc_purchase_product_id"]."'
				");
				$grnamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$grniqty=$row2nd['stc_purchase_product_items_qty'];
					$grnirate=$row2nd['stc_purchase_product_items_rate'];
					$grnamount+=$grniqty * $grnirate;
				}
				
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	if( $row["stc_purchase_product_status"] == 'accepted'){
              		$postatus='<span style="background-color: #3bea22;color: black;padding: 8px;border-radius: 40%;font-weight: bold;width: 100px;margin: 5px;display: inline-block;text-align: center;">'.$row["stc_purchase_product_status"].'</span>';
              		$grnopt='
              		<div class="col-sm-3">
       				 	<a class="stc_print_page" href="grn.php?purchaseorderid='.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-handshake" aria-hidden="true"></i></a>
       				</div>
              		';
              	}elseif( $row["stc_purchase_product_status"] == 'canceled'){
              		$postatus='<span style="background-color: red;color: black;padding: 8px;border-radius: 40%;font-weight: bold;width: 100px;margin: 5px;display: inline-block;text-align: center;">'.$row["stc_purchase_product_status"].'</span>';
              		$grnopt='
              		<div class="col-sm-3">
       				 	<a class="stc_print_page" href="grn.php?purchaseorderid='.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;" disabled><i class="fa fa-handshake" aria-hidden="true"></i></a>
       				</div>
              		';
              	}else{
              		$postatus='<span style="background-color: #ffe939;color: black;padding: 8px;border-radius: 40%;font-weight: bold;width: 100px;margin: 5px;display: inline-block;text-align: center;">'.$row["stc_purchase_product_status"].'</span>';
              		$grnopt='
              		<div class="col-sm-3">
       				 	<a class="stc_print_page" href="grn.php?purchaseorderid='.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-handshake" aria-hidden="true"></i></a>
       				</div>
              		';
              	}              	
              	$ponumber = substr("0000{$row["stc_purchase_product_id"]}", -$str_length);
				$odin.='
					<tr>
       					<td>
       					  <p>'.date('d-m-Y',strtotime($row["stc_purchase_product_order_date"])).'</p>
       					</td>
       					<td>
       					  <h6>STC/'.$ponumber.'</h6>
       					</td>
       					<td>
       					  <h6>'.$row["stc_merchant_name"].'</h6>
       					</td>
       					<td>
       					  <h6 class="text-uppercase">
       					  	'.$postatus.'
       					  </h6>
       					</td>
       					<td>
       					  <p align="right"><i class="fa fa-rupee-sign"></i> <b>'.number_format($grnamount, 2).'</b></p>
       					</td>
       					<td>
       					  <div class="row">
       					    <div class="col-sm-3">
       					     	<a class="stc_edit_page" href="#" id="'.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-eye" aria-hidden="true"></i></a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="" href="edit-purchase-order.php?epid='.$row["stc_purchase_product_id"].'" id="'.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
       					    '.$grnopt.'
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="print-preview.php?pid='.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
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

	// call product in click of view for edit
	public function stc_view_for_edit_purchase_product($view_for_edit_product_id){
		$odin='';
		$order_line_items='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_purchase_product`
			LEFT JOIN `stc_merchant`
			ON `stc_purchase_product_merchant_id` =`stc_merchant_id`
			WHERE `stc_purchase_product_id`='".$view_for_edit_product_id."'
		");
		$stc_purchase_product_items_view_fedit_rec=mysqli_fetch_assoc($check_loki);
		$get_view_fedit_purchase_product_id=$stc_purchase_product_items_view_fedit_rec['stc_purchase_product_id'];
		$get_view_fedit_purchase_merchant_name=$stc_purchase_product_items_view_fedit_rec['stc_merchant_name'];
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "Something went Wrong!!";
			header('location:index.php');
		}else{
			$nested_check_loki=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_purchase_product_items`
				LEFT JOIN `stc_product`
				ON `stc_purchase_product_items_product_id` =`stc_product_id`
				WHERE `stc_purchase_product_items_order_id`='".$view_for_edit_product_id."'
			");
			$order_line_items .= '  
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
						    <th scope="col" width="10%">Amount</th>
						    <th scope="col">Action</th>
						  </tr>
						</thead>
						<tbody>
			';  
			if(!empty($nested_check_loki)){ 
				$slno= 0; 
				$total = 0;  
				$totalgst = 0;
				foreach($nested_check_loki as $keys => $values) {  
					$amount=$values["stc_purchase_product_items_qty"] * $values["stc_purchase_product_items_rate"];
					$gstamount = ($amount * $values["stc_product_gst"])/100;
					$slno++;
					$order_line_items .= ' 
						<tr>
							<td>'.$slno.'</td>
							<td>'.$values["stc_product_name"].'</td>
							<td>'.$values["stc_product_hsncode"].'</td>
							<td>'.$values["stc_product_unit"].'</td>
							<td>
							  <input
							  	style="width: 55%;padding: 5px;height: 40px;"
							    type="text"
							    id="stcqtypot'.$values["stc_purchase_product_items_product_id"].'"
							    class="validate"
							    placeholder="Quantity"
							    value="'.number_format($values["stc_purchase_product_items_qty"], 2).'"
							  />
							  <a data-product_id="'.$values["stc_purchase_product_items_product_id"].'" class="stcqtypo" href="#"><i class="fas fa-arrow-right"></i></a>
							</td>
							<td>
							  <input
							  	style="width: 55%;padding: 5px;height: 40px;"
							    type="text"
							    id="stcratepot'.$values["stc_purchase_product_items_product_id"].'"
							    class="validate"
							    placeholder="Rate"
							    value="'.number_format($values["stc_purchase_product_items_rate"], 2).'"
							  />
							  <a data-product_id="'.$values["stc_purchase_product_items_product_id"].'" class="stcratepo" href="#"><i class="fas fa-arrow-right"></i></a>
							</td>
							<td><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
							<td> '.$values["stc_product_gst"].'% </td>
							<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount + $gstamount, 2).'</td>
							<td><a style="color: red;font-size:20px;" href="#" id="'.$values["stc_purchase_product_items_product_id"].'" class="stcdelpobtn"><i class="fas fa-trash"></i></a></td>
						</tr>
					';  
					$total = $total + ($values["stc_purchase_product_items_qty"] * $values["stc_purchase_product_items_rate"]) ;  
					$totalgst +=$gstamount;
				}  
				$gstamount = ($amount * $values["stc_product_gst"])/100;
				$order_line_items .= ' 
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
					  <td></td>
					</tr>
					<tr>
					  <td colspan="8"><h4 align="right">Grand Total</h4></td>
					  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total + $totalgst, 2).'</td>
					  <td></td>
					</tr>  
				';
			}else{
				$order_line_items.='
					<tr>  
						<td colspan="10" align="center">  
						    <h3>Cart Empty!!!</h3>
						</td>  
					</tr>  
				';
			}

			$order_line_items .= '						
				  </tbody>
				</table>
			';  
						
		}
		$odin=array(
			'gto_number' => $get_view_fedit_purchase_product_id,
			'Vendor_name' => $get_view_fedit_purchase_merchant_name,
			'line_items' => $order_line_items
		);
		return $odin;
	}

	// call product line items from db
	public function stc_update_po_lineItemQty($pdid, $pdqty){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			UPDATE `stc_purchase_product_items` 
			SET `stc_purchase_product_items_qty`='".mysqli_real_escape_string($this->stc_dbs, $pdqty)."'
			WHERE `stc_purchase_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $pdid)."'
		");
		if($loki_get_max_id){
			$odin = "Item Quantity Updated!!";
		}else{
			$odin = "Please recheck & try again";
		}
		return $odin;
	}

	// edit po line rate 
	public function stc_update_po_lineItemRate($pdid, $pdprice){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			UPDATE `stc_purchase_product_items` 
			SET `stc_purchase_product_items_rate`='".mysqli_real_escape_string($this->stc_dbs, $pdprice)."'
			WHERE `stc_purchase_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $pdid)."'
		");
		if($loki_get_max_id){
			$odin = "Item Price Updated!!";
		}else{
			$odin = "Please recheck & try again";
		}
		return $odin;
	}

	// edit po delete line items
	public function stc_delete_po_lineItem($pdid){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_purchase_product_items` WHERE `stc_purchase_product_items_id`='".$pdid."'
		");
		if($loki_get_max_id){
			$odin = "Item Deleted!!";
		}else{
			$odin = "Please recheck & try again";
		}
		return $odin;
	}

	// find poedit
	public function stc_search_edit_product_name($searchme){
		$perfectsearchme=strtoupper($searchme);
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
					LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
					WHERE `stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."' 
					OR `stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."' 
					ORDER BY `stc_product_id` ASC LIMIT 0,50
		";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_product_name"]);
				$stcqty=$row["stc_item_inventory_pd_qty"];
				if($stcqty==0){
					$stcqty=0;
				}else{
					$stcqty;
				}
				$odin.='	
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
		             	  <div class="row">
		             	    <div class="el-wrapper">
		             	      <div class="box-up for'.$row["stc_product_id"].'">
		             	        
		             	        <div class="img-info">
		             	          <div class="info-inner">
		             	            <span class="p-name">'.htmlentities($row["stc_product_name"]).'</span>
		             	            <span class="p-company">'.$row["stc_cat_name"].' / '.$row["stc_product_hsncode"].'</span>
		             	            <span class="p-qty" >'.$stcqty.''.$row["stc_product_unit"].'</span>
										<input
					                	  id="stceditpoqty'.$row["stc_product_id"].'"
					                	  name="stcpoqty"
					                	  type="number"
					                	  placeholder="Quantity"
					                	  class="form-control validate"
					                	/>
		             	         </div>
		             	         	<input 
		             	         		type="hidden" 
		             	         		name="hidden_name" 
		             	         		id="stceditpopdname'.$row["stc_product_id"].'" 
		             	         		value="'.htmlentities($row["stc_product_name"]).'" 
		             	         	/>
		             	         	<input 
		             	         		type="hidden" 
		             	         		name="hidden_unit" 
		             	         		id="stceditpopdunit'.$row["stc_product_id"].'" 
		             	         		value="'.$row["stc_product_unit"].'" 
		             	         	/>

		             	         	<input 
		             	         		type="hidden" 
		             	         		name="hidden_hsncode" 
		             	         		id="stceditpopdhsncode'.$row["stc_product_id"].'" 
		             	         		value="'.$row["stc_product_hsncode"].'" 
		             	         	/>
		             	         	<input 
		             	         		type="hidden" 
		             	         		name="hidden_gst" 
		             	         		id="stceditpopdgst'.$row["stc_product_id"].'" 
		             	         		value="'.$row["stc_product_gst"].'" 
		             	         	/>
		             	         	<div class="a-size">
					                	<input 
				             	          	type="number" 
				             	          	name="stcpdprice" 
				             	          	id="stceditpdprice'.$row["stc_product_id"].'" 
				             	          	placeholder="Price"
				             	          	class="form-control validate"
				             	          />
		             	          	</div>
		             	        </div>
		             	      </div>

		             	      <div class="box-down">
		             	        <div class="h-bg">
		             	          <div class="h-bg-inner"></div>
		             	        </div>

		             	        <a class="cart add_to_cartforedit" href="#" id="'.$row["stc_product_id"].'">
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

	// edit po add line item
	public function stc_add_po_lineItem($product_id, $product_poid, $product_quantity, $product_price){
		$odin='';
		$loki_checkqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_purchase_product_items`
			WHERE `stc_purchase_product_items_product_id`='".mysqli_real_escape_string($this->stc_dbs, $product_id)."'
			AND `stc_purchase_product_items_order_id`='".mysqli_real_escape_string($this->stc_dbs, $product_poid)."'
		");
		if(mysqli_num_rows($loki_checkqry)>0){
			$editemsid=0;
			$edproduct_quantity=0;
			foreach($loki_checkqry as $editrow){
				$edproduct_quantity=$editrow['stc_purchase_product_items_qty'];
				$editemsid=$editrow['stc_purchase_product_items_id'];
			}
			$product_quantity+=$edproduct_quantity;
			$loki_addlitems=mysqli_query($this->stc_dbs, "
				UPDATE `stc_purchase_product_items` 
				SET `stc_purchase_product_items_qty`='".mysqli_real_escape_string($this->stc_dbs, $product_quantity)."'
				WHERE `stc_purchase_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $editemsid)."'
			");
			if($loki_addlitems){
				$odin = "Item Added!!";
			}else{
				$odin = "Please recheck & try again";
			}
		}else{
			$loki_addlitems=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_purchase_product_items`(
					`stc_purchase_product_items_order_id`, 
					`stc_purchase_product_items_product_id`, 
					`stc_purchase_product_items_qty`, 
					`stc_purchase_product_items_rate`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $product_poid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $product_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $product_quantity)."',
					'".mysqli_real_escape_string($this->stc_dbs, $product_price)."'
				)
			");
			if($loki_addlitems){
				$odin = "Item Added!!";
			}else{
				$odin = "Please recheck & try again";
			}
		}
		return $odin;
	}

	// edit po save updated
	public function stc_update_po($pro_id, $mername, $tandc, $gto_number){
		$odin='';
		$loki_update_po=mysqli_query($this->stc_dbs, "
			UPDATE `stc_purchase_product` 
			SET 
				`stc_purchase_product_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $mername)."', 
				`stc_purchase_product_notes`='".mysqli_real_escape_string($this->stc_dbs, $tandc)."', 
				`stc_purchase_product_purchased_for`='".mysqli_real_escape_string($this->stc_dbs, $gto_number)."'
			WHERE `stc_purchase_product_id`='".mysqli_real_escape_string($this->stc_dbs, $pro_id)."'
		");
		if($loki_update_po){
			$odin = "PO updated!!";
		}else{
			$odin = "Please recheck & try again";
		}
		return $odin;
	}

	// filter po
	public function stc_filter_po($search, $begdate, $enddate){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_purchase_product` 
			LEFT JOIN `stc_merchant` 
			ON `stc_purchase_product_merchant_id`=`stc_merchant_id` 
			WHERE `stc_merchant_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			OR `stc_purchase_product_id` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			OR `stc_purchase_product_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			OR `stc_purchase_product_purchased_for` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			AND (
                `stc_purchase_product_order_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $begdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $enddate)."'
            )
			ORDER BY `stc_purchase_product`.`stc_purchase_product_id` DESC
		");
		$odin='
			<table class="table table-hover ">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Purchase Order Number</th>
	        	    <th scope="col">Merchant</th> 
	        	    <th scope="col">Status</th>
	        	    <th style="width: 15%;" scope="col">Basic Value</th>                         
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<tr><td>No PO found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_purchase_product_items` WHERE `stc_purchase_product_items_order_id`='".$row["stc_purchase_product_id"]."'
				");
				$grnamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$grniqty=$row2nd['stc_purchase_product_items_qty'];
					$grnirate=$row2nd['stc_purchase_product_items_rate'];
					$grnamount+=$grniqty * $grnirate;
				}
				
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	if( $row["stc_purchase_product_status"] == 'accepted'){
              		$postatus='<span style="background-color: #3bea22;color: black;padding: 8px;border-radius: 40%;font-weight: bold;width: 100px;margin: 5px;display: inline-block;text-align: center;">'.$row["stc_purchase_product_status"].'</span>';
              		$grnopt='
              		<div class="col-sm-3">
       				 	<a class="stc_print_page" href="grn.php?purchaseorderid='.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-handshake" aria-hidden="true"></i></a>
       				</div>
              		';
              	}elseif( $row["stc_purchase_product_status"] == 'canceled'){
              		$postatus='<span style="background-color: red;color: black;padding: 8px;border-radius: 40%;font-weight: bold;width: 100px;margin: 5px;display: inline-block;text-align: center;">'.$row["stc_purchase_product_status"].'</span>';
              		$grnopt='
              		<div class="col-sm-3">
       				 	<a class="stc_print_page" href="grn.php?purchaseorderid='.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;" disabled><i class="fa fa-handshake" aria-hidden="true"></i></a>
       				</div>
              		';
              	}else{
              		$postatus='<span style="background-color: #ffe939;color: black;padding: 8px;border-radius: 40%;font-weight: bold;width: 100px;margin: 5px;display: inline-block;text-align: center;">'.$row["stc_purchase_product_status"].'</span>';
              		$grnopt='
              		<div class="col-sm-3">
       				 	<a class="stc_print_page" href="grn.php?purchaseorderid='.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-handshake" aria-hidden="true"></i></a>
       				</div>
              		';
              	}              	
              	$ponumber = substr("0000{$row["stc_purchase_product_id"]}", -$str_length);
				$odin.='
					<tr>
       					<td>
       					  <p>'.date('d-m-Y',strtotime($row["stc_purchase_product_order_date"])).'</p>
       					</td>
       					<td>
       					  <h6>STC/'.$ponumber.'</h6>
       					</td>
       					<td>
       					  <h6>'.$row["stc_merchant_name"].'</h6>
       					</td>
       					<td>
       					  <h6 class="text-uppercase">
       					  	'.$postatus.'
       					  </h6>
       					</td>
       					<td>
       					  <p align="right"><i class="fa fa-rupee-sign"></i> <b>'.number_format($grnamount, 2).'</b></p>
       					</td>
       					<td>
       					  <div class="row">
       					    <div class="col-sm-3">
       					     	<a class="stc_edit_page" href="#" id="'.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-eye" aria-hidden="true"></i></a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="" href="edit-purchase-order.php?epid='.$row["stc_purchase_product_id"].'" id="'.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
       					    '.$grnopt.'
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="print-preview.php?pid='.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
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

	// filter po by materials name
	public function stc_search_product_thr_po($searchme, $begdate, $enddate){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_purchase_product_items`
			LEFT JOIN `stc_product` 
			ON `stc_purchase_product_items_product_id`=`stc_product_id`
        	LEFT JOIN `stc_purchase_product` 
			ON `stc_purchase_product_items_order_id`=`stc_purchase_product_id`
			LEFT JOIN `stc_merchant` 
			ON `stc_purchase_product_merchant_id`=`stc_merchant_id` 
			WHERE `stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchme)."' 
			OR `stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchme)."'
			AND (
                `stc_purchase_product_order_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $begdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $enddate)."'
            )
			ORDER BY `stc_purchase_product`.`stc_purchase_product_id` DESC
		");
		$odin='
			<table class="table table-hover ">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Purchase Order Number</th>
	        	    <th scope="col">Merchant</th> 
	        	    <th scope="col">Status</th>
	        	    <th style="width: 15%;" scope="col">Basic Value</th>                         
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='6' align='center'>No PO found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_purchase_product_items` WHERE `stc_purchase_product_items_order_id`='".$row["stc_purchase_product_id"]."'
				");
				$grnamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$grniqty=$row2nd['stc_purchase_product_items_qty'];
					$grnirate=$row2nd['stc_purchase_product_items_rate'];
					$grnamount+=$grniqty * $grnirate;
				}
				
				$str_length = 5;
              	if( $row["stc_purchase_product_status"] == 'accepted'){
              		$postatus='<span style="background-color: #3bea22;color: black;padding: 8px;border-radius: 40%;font-weight: bold;width: 100px;margin: 5px;display: inline-block;text-align: center;">'.$row["stc_purchase_product_status"].'</span>';
              		$grnopt='
              		<div class="col-sm-3">
       				 	<a class="stc_print_page" href="grn.php?purchaseorderid='.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-handshake" aria-hidden="true"></i></a>
       				</div>
              		';
              	}elseif( $row["stc_purchase_product_status"] == 'canceled'){
              		$postatus='<span style="background-color: red;color: black;padding: 8px;border-radius: 40%;font-weight: bold;width: 100px;margin: 5px;display: inline-block;text-align: center;">'.$row["stc_purchase_product_status"].'</span>';
              		$grnopt='
              		<div class="col-sm-3">
       				 	<a class="stc_print_page" href="grn.php?purchaseorderid='.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;" disabled><i class="fa fa-handshake" aria-hidden="true"></i></a>
       				</div>
              		';
              	}else{
              		$postatus='<span style="background-color: #ffe939;color: black;padding: 8px;border-radius: 40%;font-weight: bold;width: 100px;margin: 5px;display: inline-block;text-align: center;">'.$row["stc_purchase_product_status"].'</span>';
              		$grnopt='
              		<div class="col-sm-3">
       				 	<a class="stc_print_page" href="grn.php?purchaseorderid='.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-handshake" aria-hidden="true"></i></a>
       				</div>
              		';
              	}              	
              	$ponumber = substr("0000{$row["stc_purchase_product_id"]}", -$str_length);
				$odin.='
					<tr>
       					<td>
       					  <p>'.date('d-m-Y',strtotime($row["stc_purchase_product_order_date"])).'</p>
       					</td>
       					<td>
       					  <h6>STC/'.$ponumber.'</h6>
       					</td>
       					<td>
       					  <h6>'.$row["stc_merchant_name"].'</h6>
       					</td>
       					<td>
       					  <h6 class="text-uppercase">
       					  	'.$postatus.'
       					  </h6>
       					</td>
       					<td>
       					  <p align="right"><i class="fa fa-rupee-sign"></i> <b>'.number_format($grnamount, 2).'</b></p>
       					</td>
       					<td>
       					  <div class="row">
       					    <div class="col-sm-3">
       					     	<a class="stc_edit_page" href="#" id="'.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-eye" aria-hidden="true"></i></a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="" href="edit-purchase-order.php?epid='.$row["stc_purchase_product_id"].'" id="'.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
       					    '.$grnopt.'
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="print-preview.php?pid='.$row["stc_purchase_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
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
}



#<------------------------------------------------------------------------------------------>
#<-------------------------------------Purchase Objects------------------------------------->
#<------------------------------------------------------------------------------------------>

// search product by name in purcahse order page
if(isset($_POST['search_po_pd_name_in'])){
	$out='';
	if(strlen($_POST['search_po_pd_name_in'])>=3){
		$searchme=$_POST['search_po_pd_name_in'];
		$objloki=new po_process();
		$objlokiout=$objloki->stc_search_product_name($searchme);
		$out=$objlokiout;
	}else{
		$out='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			      No Records Found!!!
			    </a>
			</div>
		';
	}
	echo json_encode($out);
}

// show vendor on purhcase order page
if(isset($_POST['friday_vendor'])){
	$objloki=new po_process();
	$objlokiout=$objloki->stc_call_vendor();
	$date=date('l jS \ F Y h:i:s A');
	$outarray=array(
		'time' => $date,
		'vendor' => $objlokiout
	);
	echo json_encode($outarray);
}

// add to session for purchase order
if(isset($_POST['add_po'])){
	if($_POST["add_po"] == "add") {  
		if(isset($_SESSION["stc_purchase_order_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_purchase_order_sess"] as $keys => $values) {  
				if($_SESSION["stc_purchase_order_sess"][$keys]['product_id'] == $_POST["product_id"]) {  
				     $is_available++;  
				     $_SESSION["stc_purchase_order_sess"][$keys]['product_quantity'] = $_SESSION["stc_purchase_order_sess"][$keys]['product_quantity'] + $_POST["product_quantity"];  
				     echo "Item Cart Quantity Increased!!!!!!";
				}  
			}  
		    if($is_available < 1) {  
		    	$item_array = array(  
		    	     'product_id'             =>     $_POST["product_id"],  
		    	     'product_name'           =>     $_POST["product_name"],  
		    	     'product_unit'           =>     $_POST["product_unit"],  
		    	     'product_quantity'       =>     $_POST["product_quantity"],
		    	     'product_hsncode'        =>     $_POST["product_hsncode"],
		    	     'product_gst'       	  =>     $_POST["product_gst"],
		    	     'product_price'          =>     $_POST["product_price"]  
		    	);  
		    	$_SESSION["stc_purchase_order_sess"][] = $item_array;  
		    	echo "Item Added to PO Cart!!!";
		    }  
		}else{  
			$item_array = array(  
			    'product_id'             =>     $_POST["product_id"],  
		    	'product_name'           =>     $_POST["product_name"],  
		    	'product_unit'           =>     $_POST["product_unit"],  
		    	'product_quantity'       =>     $_POST["product_quantity"],
		    	'product_hsncode'        =>     $_POST["product_hsncode"],
		    	'product_gst'       	 =>     $_POST["product_gst"],
		    	'product_price'          =>     $_POST["product_price"]   
			);   
			$_SESSION["stc_purchase_order_sess"][] = $item_array;  
		     echo "PO Cart Created & Item Added to PO Cart!!!";
		}  
    }  
}

// show session on line items on purchase order
if(isset($_POST['stc_show_po_sess'])){
    // $Deliverycharge=20;
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
			    <th scope="col" width="10%">Amount</th>
			    <th scope="col">Action</th>
			  </tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_purchase_order_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_purchase_order_sess"] as $keys => $values) {  
				$amount=$values["product_quantity"] * $values["product_price"];
				$gstamount = ($amount * $values["product_gst"])/100;
				$slno++;
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
						    id="stcqtypot'.$values["product_id"].'"
						    class="validate"
						    placeholder="Quantity"
						    value="'.number_format($values["product_quantity"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcqtypo" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td>
						  <input
						  	style="width: 55%;padding: 5px;height: 40px;"
						    type="text"
						    id="stcratepot'.$values["product_id"].'"
						    class="validate"
						    placeholder="Rate"
						    value="'.number_format($values["product_price"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcratepo" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
						<td> '.$values["product_gst"].'% </td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount + $gstamount, 2).'</td>
						<td><a style="color: red;font-size:20px;" href="#" id="'.$values["product_id"].'" class="stcdelpobtn"><i class="fas fa-trash"></i></a></td>
					</tr>
				';  
				$total = $total + ($values["product_quantity"] * $values["product_price"]) ;  
				$totalgst +=$gstamount;
			}  
			$gstamount = ($amount * $values["product_gst"])/100;
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
				  <td></td>
				</tr>
				<tr>
				  <td colspan="8"><h4 align="right">Grand Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total + $totalgst, 2).'</td>
				  <td></td>
				</tr>  
			';  
		}else{
			$order_table.='
				<tr>  
					<td colspan="10" align="center">  
					    <h3>Cart Empty!!!</h3>
					</td>  
				</tr>  
			';
		}
	$order_table .= '
			
		  </tbody>
		</table>
		';  
	// $countopr=count($_SESSION["stc_purchase_order_sess"]);
	// $countop=$countopr;
	$export=array(
	  "orderTable" => $order_table
	  
	); 
	header('Content-type: application/json');
	echo json_encode($export);
}

// change quantity of session purchase order
if(isset($_POST["quantity_action"])){  
  	foreach($_SESSION["stc_purchase_order_sess"] as $keys => $values){  
	    if($_SESSION["stc_purchase_order_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_purchase_order_sess"][$keys]['product_quantity'] = $_POST["quantity"];
    	}  
  	}  
}

// change price of session purchase order
if(isset($_POST["price_action"])){  
  	foreach($_SESSION["stc_purchase_order_sess"] as $keys => $values){  
	    if($_SESSION["stc_purchase_order_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_purchase_order_sess"][$keys]['product_price'] = $_POST["price"];
    	}  
  	}  
}

// delete from cart of session purchase order
if(isset($_POST['stcdelpolinei'])){  
	foreach($_SESSION["stc_purchase_order_sess"] as $keys => $values){  
	     if($values["product_id"] == $_POST["product_id"]){  
	          unset($_SESSION["stc_purchase_order_sess"][$keys]);  
	          echo "Product Removed!!!";  
	     }  
	}  
}

// call po on po page
if(isset($_POST['stccallpp'])){
	$begdate=date("Y-m-d h:i:s", strtotime($_POST['begdate']));
	$enddate=date("Y-m-d h:i:s", strtotime($_POST['enddate']));
	$objloki=new po_process();
	$objlokiout=$objloki->stc_call_purchase_product($begdate, $enddate);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// save po
if(isset($_POST['stcprosavepo'])){
	$objhelaout='';
	$stcmerchantid=$_POST['merchantid'];
	$stcbasicvalue=$_POST['basicvalue'];
	$stctermsandcond=$_POST['termsandcond'];
	$gtonumberinst=$_POST['gtonumberinst'];

	$objloki=new po_process();
	$objhela=new po_process();

	if($stcmerchantid=="NA"){
		$objhelaout="Please Select Merchant First!!!";
	}elseif(empty($_SESSION['stc_purchase_order_sess'])){
		$objhelaout="Abe ghansi item to add kar pahle!!!";
	}else{
		$objlokiout=$objloki->stc_save_purchase_pro($stcmerchantid, $stcbasicvalue, $stctermsandcond, $gtonumberinst);
		if($objlokiout=='Success'){
			$objhelaout=$objhela->stc_save_purchase_pro_items();
			unset($_SESSION['stc_purchase_order_sess']);
		}else{
			$objhelaout="Please Try Again";
		}
	}
	$out = array(
		'objloki' => $objlokiout,
		'objhela' => $objhelaout
	);
	echo json_encode($out);
	// echo $out;
}

// view for edit purchase product hit
if(isset($_POST['stc_view_for_edit'])){
	$view_for_edit_product_id=$_POST['stc_ppe_pid'];
	$objloki=new po_process();
	$objlokiout=$objloki->stc_view_for_edit_purchase_product($view_for_edit_product_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// Edit po update quantity 
if(isset($_POST['updatepdqty'])){
	$pdid=$_POST['poid'];
	$pdqty=$_POST['product_qty'];
	$objloki=new po_process();
	$objlokiout=$objloki->stc_update_po_lineItemQty($pdid, $pdqty);
	echo json_encode($objlokiout);
}

// Edit po update price
if(isset($_POST['updatepdprice'])){
	$pdid=$_POST['poid'];
	$pdprice=$_POST['product_price'];
	$objloki=new po_process();
	$objlokiout=$objloki->stc_update_po_lineItemRate($pdid, $pdprice);
	echo json_encode($objlokiout);
}

// Edit PO delete po items
if(isset($_POST['delpoli'])){
	$pdid=$_POST['poid'];
	$objloki=new po_process();
	$objlokiout=$objloki->stc_delete_po_lineItem($pdid);
	echo json_encode($objlokiout);
}

// search product by name in purcahse order page
if(isset($_POST['search_edit_po_pd_name_in'])){
	$out='';
	if(strlen($_POST['search_edit_po_pd_name_in'])>=3){
		$searchme=$_POST['search_edit_po_pd_name_in'];
		$objloki=new po_process();
		$objlokiout=$objloki->stc_search_edit_product_name($searchme);
		$out=$objlokiout;
	}else{
		$out='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			      No Records Found!!!
			    </a>
			</div>
		';
	}
	echo json_encode($out);
}

// edit po items
if(isset($_POST['edit_po'])){	
	$product_id = $_POST["product_id"];
	$product_poid = $_POST["product_poid"];
	$product_quantity = $_POST["product_quantity"];
	$product_price = $_POST["product_price"];

	$objloki=new po_process();
	$objlokiout=$objloki->stc_add_po_lineItem($product_id, $product_poid, $product_quantity, $product_price);
	echo json_encode($objlokiout);
}

// save edited po
if(isset($_POST['saveedit_po'])){
	$pro_id=$_POST['product_id'];
	$mername=$_POST['product_merchant'];
	$tandc=$_POST['product_tandc'];
	$gto_number=$_POST['gto_number'];

	if($mername=="NA"){
		echo "Please Select Merchant First!!!";
	}else{
		$objloki=new po_process();
		$objlokiout=$objloki->stc_update_po($pro_id, $mername, $tandc, $gto_number);
		echo json_encode($objlokiout);
	}		
}

// filters po
if(isset($_POST['stcfilterpo'])){
	$search = str_replace(",", "|", $_POST["stcfilterpo"]);
	$begdate=date("Y-m-d h:i:s", strtotime($_POST['begdate']));
	$enddate=date("Y-m-d h:i:s", strtotime($_POST['enddate']));
	$objloki=new po_process();
	$objlokiout=$objloki->stc_filter_po($search, $begdate, $enddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// search po using items 
if(isset($_POST['search_po_pd_name_in_po'])){
	$out='';
	$searchme=$_POST['search_po_pd_name_in_po'];
	$begdate=date("Y-m-d h:i:s", strtotime($_POST['begdate']));
	$enddate=date("Y-m-d h:i:s", strtotime($_POST['enddate']));
	$objloki=new po_process();
	if(strlen($searchme)>=3){
		$objlokiout=$objloki->stc_search_product_thr_po($searchme, $begdate, $enddate);
		$out.=$objlokiout;
	}else{
		$out='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			      Enter At least 3 key!!!
			    </a>
			</div>
		';
	}
	echo json_encode($out);
	// echo $out;
}
?>