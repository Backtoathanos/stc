<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Purchase Order --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class aetherSale extends tesseract{	

	// call Customer on Sale Order page
	public function stc_call_vendor(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_customer`");
		$odin='<option selected>Please Select Customer</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option selected>No Customer Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				if($row["stc_customer_id"]==2){
					$odin.='
								<option selected value="'.$row["stc_customer_id"].'">'.$row["stc_customer_name"].'</option>		               	
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

	// Search product name in Sale Orde Page
	public function stc_search_product_name($searchme){
		$perfectsearchme=strtoupper($searchme);
		$purchase_rate='';
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
					LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
					WHERE (
						`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."' OR 
						`stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."'
					) AND `stc_item_inventory_pd_qty` != '0' 
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
					             	          	type="number" 
					             	          	name="stcpdprice" 
					             	          	id="stcpdprice'.$row["stc_product_id"].'" 
					             	          	placeholder="Enter Percent % Below 100"
					             	          	class="form-control validate"
			             	         			value="'.number_format($purchase_rate['stc_purchase_product_items_rate'], 2, '.', '').'" 
			             	         			style="color:black;font-weight:bold;text-align:center"
			             	         			disabled
					             	          />
					             	        <input 
					             	        	type="hidden"
					             	          	id="stcpdinvent'.$row["stc_product_id"].'" 
					             	        	value="'.number_format($stcqty, 2, '.', '').'" 
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

		             					<a class="cart add_to_sale_cart" href="#" id="'.$row["stc_product_id"].'">
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

	// stc call sale orders
	public function stc_call_sale_orders($challanbegdate, $challanenddate){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product` 
			LEFT JOIN `stc_customer` 
			ON `stc_sale_product_cust_id`=`stc_customer_id` 
			WHERE (
				(`stc_sale_product_status`='challaned' AND `stc_sale_product_dc_keys`!='directchallaned')
				OR (`stc_sale_product_status`='billed' AND `stc_sale_product_dc_keys`!='directchallaned')
			)
			AND (
				`stc_sale_product_date`
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $challanbegdate)."'
				AND '".mysqli_real_escape_string($this->stc_dbs, $challanenddate)."'
			)
			ORDER BY `stc_sale_product`.`stc_sale_product_id` 
			DESC LIMIT 0,50
		");
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Challan No</th>
	        	    <th scope="col">Customer Order Date</th>
	        	    <th scope="col">Customer Order No</th>
	        	    <th scope="col">Customer Name</th> 
	        	    <th scope="col">Way Bill No</th>
	        	    <th scope="col">Customer Site</th>
	        	    <th scope="col">Basic Value</th>
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
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_sale_product_items` WHERE `stc_sale_product_items_sale_product_id`='".$row["stc_sale_product_id"]."'
				");
				$soamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$soiqty=$row2nd['stc_sale_product_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_items_product_sale_rate'];
					$soamount+=$soiqty * $soirate;
				}
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$ponumber = substr("0000{$row["stc_sale_product_id"]}", -$str_length);
				$odin.='
					<tr>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_date"])).'</p>
       					</td>
       					<td>
       					  <h6>STC/DC/'.$ponumber.'</h6>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_cust_order_date"])).'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_cust_order_no"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_customer_name"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_way_bill_no"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_sitename"].'</p>
       					</td>
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'</p>
       					</td>
       					<td>
       					  <h6>'.$row["stc_sale_product_status"].'</h6>
       					</td>
       					<td>
       					  <div class="row">       					  
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="edit-challan.php?cid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="challan-preview.php?pid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
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

	// go data to sale table
	public function sale_process($customer_id, $sale_custorderdate,  $sale_custordernumber,  $sale_stcfc,  $sale_stcpf, $order_waybillno, $order_lrno, $order_suppdate, $order_suppplace, $order_refrence, $order_sitename, $order_contperson, $order_contnumber, $order_shipadd, $order_tandc, $order_notes){	
		$maxgst='';
		foreach($_SESSION["stc_sale_order_sess"] as $keys => $values) {
			$maxgst=array($values['product_gst']);
		}
		$odin='';
		$lokiinsertsale=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_sale_product`(
				`stc_sale_product_date`, 
				`stc_sale_product_cust_id`, 
				`stc_sale_product_cust_order_no`, 
				`stc_sale_product_cust_order_date`, 
				`stc_sale_product_dispatch_through_id`, 
				`stc_sale_product_way_bill_no`, 
				`stc_sale_product_lr_no`, 
				`stc_sale_product_freight_charge`, 
				`stc_sale_product_packing_and_forwarding_charge`, 
				`stc_sale_product_gst_calc_value`, 
				`stc_sale_product_notes`, 
				`stc_sale_product_refrence`, 
				`stc_sale_product_dosupply`, 
				`stc_sale_product_posupply`, 
				`stc_sale_product_sitename`, 
				`stc_sale_product_siteaddress`, 
				`stc_sale_product_cont_person`, 
				`stc_sale_product_cont_number`, 
				`stc_sale_product_tandc`, 
				`stc_sale_product_status`, 
				`stc_sale_product_order_id`, 
				`stc_sale_product_agent_id`, 
				`stc_sale_product_created_by`
			) VALUES (
				NOW(), 
				'".mysqli_real_escape_string($this->stc_dbs, $customer_id)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $sale_custordernumber)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $sale_custorderdate)."', 
				'0', 
				'".mysqli_real_escape_string($this->stc_dbs, $order_waybillno)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_lrno)."',
				'".mysqli_real_escape_string($this->stc_dbs, $sale_stcfc)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $sale_stcpf)."',
				'".max($maxgst)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_notes)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_refrence)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_suppdate)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_suppplace)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_sitename)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_shipadd)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_contperson)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_contnumber)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_tandc)."',
				'challaned',
				'0',
				'0',
				'".$_SESSION['stc_admin_info_id']."'
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

	// go sale session data to sale items table 
	public function stc_save_sale_pro_items(){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product` ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_sale_product_id'];
		if(empty($_SESSION['stc_sale_order_sess'])){
			$odin = "Do not leave Items on Sale Order!!!";
		}else{
			foreach ($_SESSION["stc_sale_order_sess"] as $key=>$value) {
		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_sale_product_items`(
		    			`stc_sale_product_items_sale_product_id`, 
		    			`stc_sale_product_items_product_id`, 
		    			`stc_sale_product_items_product_qty`, 
		    			`stc_sale_product_items_product_rate`, 
		    			`stc_sale_product_items_product_sale_rate`
		    		) VALUES (
		    			'".mysqli_real_escape_string($this->stc_dbs, $get_purchase_product_id)."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_id'])."',
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_quantity'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_price'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_sale_price'])."'
		    		)
		    	");
		    	$friday_loki_check_inventory_exist_pd=mysqli_query($this->stc_dbs, "
		    		SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_id`='".$value['product_id']."'
		    	");
		    	$loki_getinvenitems=mysqli_fetch_assoc($friday_loki_check_inventory_exist_pd);
				$inventory_pd_id=$loki_getinvenitems['stc_item_inventory_pd_id'];
				$inventory_pd_qty=$loki_getinvenitems['stc_item_inventory_pd_qty'];
				$iventoryrow=mysqli_num_rows($friday_loki_check_inventory_exist_pd);
				if($iventoryrow == 0){
					$odin .= "This Items is not in Inventory!!!";
				}else{
					$finalqty = $inventory_pd_qty - $value['product_quantity'];
			    	$friday_loki_inventory=mysqli_query($this->stc_dbs, "
			    		UPDATE `stc_item_inventory` 
			    		SET `stc_item_inventory_pd_qty`='".mysqli_real_escape_string($this->stc_dbs, $finalqty)."' 
			    		WHERE `stc_item_inventory_pd_id`='".mysqli_real_escape_string($this->stc_dbs, $value['product_id'])."'
			    	");
			    }
		    }
		    $odin = "Sale Item Done!!!";
		}
		return $odin;
	}

	public function stc_edit_search_product($searchme){
		$perfectsearchme=strtoupper($searchme);
		$purchase_rate='';
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
					LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
					WHERE `stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."' 
					AND `stc_item_inventory_pd_qty` != '0' ORDER BY `stc_product_id` ASC
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$loki_findratefrompo=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_purchase_product_items` 
					WHERE `stc_purchase_product_items_product_id`='".$row["stc_product_id"]."' 
				");
				$purchase_rate=mysqli_fetch_assoc($loki_findratefrompo);
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
				             	          	type="number" 
				             	          	name="stcpdprice" 
				             	          	id="stceditchallanprice'.$row["stc_product_id"].'" 
				             	          	placeholder="Enter Percent % Below 100"
				             	          	class="form-control validate"
		             	         			value="'.number_format($purchase_rate['stc_purchase_product_items_rate'], 2, '.', '').'" 
		             	         			style="color:black;font-weight:bold;text-align:center"
		             	         			disabled
				             	          />		  
				             	        <input 
				             	          	type="number" 
				             	          	name="stcpdsaleprice" 
				             	          	id="stcpdchallanperc'.$row["stc_product_id"].'" 
				             	          	placeholder="Enter Percent % Below 100"
				             	          	class="form-control validate" 
				             	          	value="'.number_format($row["stc_product_sale_percentage"], 2).'"
				             	          />           	            
			             	        </div>
		             	         	<div class="a-size">
			             	        	<input
					                	  id="stceditchallanqty'.$row["stc_product_id"].'"
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

		             	        <a class="cart add_to_cartforeditchallan" href="#" id="'.$row["stc_product_id"].'">
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

	// add to challan table sale order
	public function stc_edit_add_product_in_challan($pd_id, $salechallanid, $sale_qnty, $sale_purchase_price, $sale_price){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_sale_product_items`(
				`stc_sale_product_items_sale_product_id`, 
				`stc_sale_product_items_product_id`, 
				`stc_sale_product_items_product_qty`, 
				`stc_sale_product_items_product_rate`, 
				`stc_sale_product_items_product_sale_rate`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $salechallanid)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $pd_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $sale_qnty)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $sale_purchase_price)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $sale_price)."'
			)
		");
		$friday_loki_check_inventory_exist_pd=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_id`='".$pd_id."'
		");
		$loki_getinvenitems=mysqli_fetch_assoc($friday_loki_check_inventory_exist_pd);
		$inventory_pd_id=$loki_getinvenitems['stc_item_inventory_pd_id'];
		$inventory_pd_qty=$loki_getinvenitems['stc_item_inventory_pd_qty'];
		$iventoryrow=mysqli_num_rows($friday_loki_check_inventory_exist_pd);
		if($iventoryrow == 0){
			$odin .= "This items is not in inventory!!!";
		}else{
			$finalqty = $inventory_pd_qty - $sale_qnty;
			$friday_loki_inventory=mysqli_query($this->stc_dbs, "
				UPDATE `stc_item_inventory` 
				SET `stc_item_inventory_pd_qty`='".mysqli_real_escape_string($this->stc_dbs, $finalqty)."' 
				WHERE `stc_item_inventory_pd_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id)."'
			");
			if($friday_loki_inventory){
				$odin="Product added!!!";
			}else{
				$odin="Please try again!!!";
			}
		}
		return $odin;
	}

	// edit qty from sale product table
	public function stc_edit_updateqty_product_in_challan($challan_id, $challan_pd_qty){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product_items` 
			SET `stc_sale_product_items_product_qty`= '".mysqli_real_escape_string($this->stc_dbs, $challan_pd_qty)."'
			WHERE `stc_sale_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $challan_id)."'
		");
		if($friday_loki){
			$odin = "success";
		}else{
			$odin = "not done";
		}
		
		return $odin;
	}

	// increae quantity
	public function stc_increase_inventory_items($challan_id, $challan_pd_qty, $reverseqty){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_sale_product_items_product_id` AS pd_id FROM `stc_sale_product_items` 
			WHERE `stc_sale_product_items_product_qty`= '".mysqli_real_escape_string($this->stc_dbs, $challan_pd_qty)."' 
			AND `stc_sale_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $challan_id)."'
		");

		$pd_id=mysqli_fetch_assoc($friday_loki);

		$friday_loki_check_inventory_exist_pd=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_id`='".$pd_id['pd_id']."'
		");
		$loki_getinvenitems=mysqli_fetch_assoc($friday_loki_check_inventory_exist_pd);
		$inventory_pd_id=$loki_getinvenitems['stc_item_inventory_pd_id'];
		$inventory_pd_qty=$loki_getinvenitems['stc_item_inventory_pd_qty'];
			$finalqty = $inventory_pd_qty + $reverseqty;
			$friday_loki_inventory=mysqli_query($this->stc_dbs, "
				UPDATE `stc_item_inventory` 
				SET `stc_item_inventory_pd_qty`='".mysqli_real_escape_string($this->stc_dbs, $finalqty)."' 
				WHERE `stc_item_inventory_pd_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id['pd_id'])."'
			");
			if($friday_loki_inventory){
				$odin = "Quantity increased!!!";
			}else{
				$odin = "Please try again!!!";
			}
		return $odin;
	}

	// decrease quantity
	public function stc_decrease_inventory_items($challan_id, $challan_pd_qty, $reverseqty){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_sale_product_items_product_id` AS pd_id FROM `stc_sale_product_items` 
			WHERE `stc_sale_product_items_product_qty`= '".mysqli_real_escape_string($this->stc_dbs, $challan_pd_qty)."' 
			AND `stc_sale_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $challan_id)."'
		");

		$pd_id=mysqli_fetch_assoc($friday_loki);

		$friday_loki_check_inventory_exist_pd=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_id`='".$pd_id['pd_id']."'
		");
		$loki_getinvenitems=mysqli_fetch_assoc($friday_loki_check_inventory_exist_pd);
		$inventory_pd_id=$loki_getinvenitems['stc_item_inventory_pd_id'];
		$inventory_pd_qty=$loki_getinvenitems['stc_item_inventory_pd_qty'];
		$iventoryrow=mysqli_num_rows($friday_loki_check_inventory_exist_pd);
		// if($iventoryrow == 0){
		// 	$odin .= "This items is not in inventory!!!";
		// }else{
			$finalqty = $inventory_pd_qty + $reverseqty;
			$friday_loki_inventory=mysqli_query($this->stc_dbs, "
				UPDATE `stc_item_inventory` 
				SET `stc_item_inventory_pd_qty`='".mysqli_real_escape_string($this->stc_dbs, $finalqty)."' 
				WHERE `stc_item_inventory_pd_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id['pd_id'])."'
			");
			if($friday_loki_inventory){
				$odin = "Quantity decreased!!!";
			}else{
				$odin = "Please try again!!!";
			}
		// }
		return $odin;
	}

	// price update challan
	public function stc_edit_updateprice_product_in_challan($challan_id, $challan_pd_price){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product_items` 
			SET `stc_sale_product_items_product_sale_rate`= '".mysqli_real_escape_string($this->stc_dbs, $challan_pd_price)."'
			WHERE `stc_sale_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $challan_id)."'
		");
		if($friday_loki){
			$odin = "Product price updated!!!";
		}else{
			$odin = "Please try again!!!";
		}
		return $odin;
	}

	// delete product from challan
	public function stc_edit_delete_product_in_challan($challan_id, $challan_delqty){
		$odin='';

		$friday_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_sale_product_items_product_id` AS pd_id FROM `stc_sale_product_items` 
			WHERE `stc_sale_product_items_id`='".$challan_id."'
		");

		$pd_id=mysqli_fetch_assoc($friday_loki);

		$friday_loki_check_inventory_exist_pd=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_id`='".$pd_id['pd_id']."'
		");

		$loki_getinvenitems=mysqli_fetch_assoc($friday_loki_check_inventory_exist_pd);
		$inventory_pd_id=$loki_getinvenitems['stc_item_inventory_pd_id'];
		$inventory_pd_qty=$loki_getinvenitems['stc_item_inventory_pd_qty'];
		$iventoryrow=mysqli_num_rows($friday_loki_check_inventory_exist_pd);

		$finalqty = $inventory_pd_qty + $challan_delqty;
		$friday_loki_delete=mysqli_query($this->stc_dbs, "
			UPDATE `stc_item_inventory` 
			SET `stc_item_inventory_pd_qty`='".mysqli_real_escape_string($this->stc_dbs, $finalqty)."' 
			WHERE `stc_item_inventory_pd_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id['pd_id'])."'
			
		");

		if($friday_loki_delete){
			$friday_loki_inventory=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_sale_product_items` WHERE `stc_sale_product_items_id`='".$challan_id."'
			");
			if($friday_loki_inventory){
				$odin= "Item removed!!!";
			}else{
				$odin= "Please try again!!!";
			}
		}else{
			$odin .= "This items is not in inventory!!!";
		}
		return $odin;
	}

	// save edited challan
	public function stc_edit_save_challan($esono, $esomerchantid, $esocustorderno, $esowaybillno, $esowaylrno, $esocustorddate, $esodosupply, $esoposupply, $esoorderrefrence, $esocustsitename, $esocustcontperson, $esocustcontnumber, $esoshipaddres, $esotermanc, $esontes){
		$maxgst='';
		$odin='';
		$lokiinsertsale=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product` SET 
				`stc_sale_product_cust_id`				='".mysqli_real_escape_string($this->stc_dbs, $esomerchantid)."',
				`stc_sale_product_cust_order_no`		='".mysqli_real_escape_string($this->stc_dbs, $esocustorderno)."',
				`stc_sale_product_cust_order_date`		='".mysqli_real_escape_string($this->stc_dbs, $esocustorddate)."',
				`stc_sale_product_way_bill_no`			='".mysqli_real_escape_string($this->stc_dbs, $esowaybillno)."',
				`stc_sale_product_lr_no`				='".mysqli_real_escape_string($this->stc_dbs, $esowaylrno)."',
				`stc_sale_product_dosupply`				='".mysqli_real_escape_string($this->stc_dbs, $esodosupply)."',
				`stc_sale_product_posupply`				='".mysqli_real_escape_string($this->stc_dbs, $esoposupply)."',
				`stc_sale_product_refrence`				='".mysqli_real_escape_string($this->stc_dbs, $esoorderrefrence)."',
				`stc_sale_product_sitename`				='".mysqli_real_escape_string($this->stc_dbs, $esocustsitename)."',
				`stc_sale_product_cont_person`			='".mysqli_real_escape_string($this->stc_dbs, $esocustcontperson)."',
				`stc_sale_product_cont_number`			='".mysqli_real_escape_string($this->stc_dbs, $esocustcontnumber)."',
				`stc_sale_product_siteaddress`			='".mysqli_real_escape_string($this->stc_dbs, $esoshipaddres)."',
				`stc_sale_product_tandc`				='".mysqli_real_escape_string($this->stc_dbs, $esotermanc)."',
				`stc_sale_product_notes`				='".mysqli_real_escape_string($this->stc_dbs, $esontes)."'
			WHERE `stc_sale_product_id`					='".mysqli_real_escape_string($this->stc_dbs, $esono)."'
		");
		$odin='';
		if($lokiinsertsale){
			$odin .= "Challan updated!!!";
		}else{
			$odin .= "Please check & try again!!!";
		}
		return $odin;
	}

	public function stc_filter_challan($search, $challanbegdate, $challanenddate){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product` 
			LEFT JOIN `stc_customer` 
			ON `stc_sale_product_cust_id`=`stc_customer_id` 
			WHERE (`stc_customer_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_id` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_sitename` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			) 
			AND (
				(`stc_sale_product_status`='challaned' AND `stc_sale_product_dc_keys`!='directchallaned')
				OR (`stc_sale_product_status`='billed' AND `stc_sale_product_dc_keys`!='directchallaned')
			)
			AND (
                `stc_sale_product_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $challanbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $challanenddate)."'
            )
			ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC
		");
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Challan No</th>
	        	    <th scope="col">Customer Order Date</th>
	        	    <th scope="col">Customer Order No</th>
	        	    <th scope="col">Customer Name</th> 
	        	    <th scope="col">Way Bill No</th>
	        	    <th scope="col">Customer Site</th>
	        	    <th scope="col">Basic Value</th>
	        	    <th scope="col">Status</th>                         
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='9' align='center'><a class='form-control btn btn-primary'>No Record Found!!!</a></td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_sale_product_items` WHERE `stc_sale_product_items_sale_product_id`='".$row["stc_sale_product_id"]."'
				");
				$soamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$soiqty=$row2nd['stc_sale_product_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_items_product_sale_rate'];
					$soamount+=$soiqty * $soirate;
				}
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$ponumber = substr("0000{$row["stc_sale_product_id"]}", -$str_length);
				$odin.='
					<tr>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_date"])).'</p>
       					</td>
       					<td>
       					  <h6>STC/DC/'.$ponumber.'</h6>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_cust_order_date"])).'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_cust_order_no"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_customer_name"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_way_bill_no"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_sitename"].'</p>
       					</td>
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'</p>
       					</td>
       					<td>
       					  <h6>'.$row["stc_sale_product_status"].'</h6>
       					</td>
       					<td>
       					  <div class="row">       					  
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="edit-challan.php?cid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="challan-preview.php?pid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
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
	public function stc_search_product_thr_ch($searchme){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT * FROM `stc_sale_product_items` 
			LEFT JOIN `stc_product` 
			ON `stc_sale_product_items_product_id`=`stc_product_id`
			LEFT JOIN `stc_sale_product`
			ON `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
			LEFT JOIN `stc_customer` 
			ON `stc_sale_product_cust_id`=`stc_customer_id` 
			WHERE (`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchme)."'
			OR `stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchme)."')
			AND ((`stc_sale_product_status`='challaned' AND `stc_sale_product_dc_keys`!='directchallaned')
			OR (`stc_sale_product_status`='billed' AND `stc_sale_product_dc_keys`!='directchallaned'))
			ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC
			LIMIT 0,50
		");
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Challan No</th>
	        	    <th scope="col">Customer Order Date</th>
	        	    <th scope="col">Customer Order No</th>
	        	    <th scope="col">Customer Name</th> 
	        	    <th scope="col">Way Bill No</th>
	        	    <th scope="col">Customer Site</th> 
	        	    <th scope="col">Basic Value</th>
	        	    <th scope="col">Status</th>                         
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='9' align='center'><a class='form-control btn btn-primary'>No Record Found!!!</a></td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_sale_product_items` WHERE `stc_sale_product_items_sale_product_id`='".$row["stc_sale_product_id"]."'
				");
				$soamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$soiqty=$row2nd['stc_sale_product_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_items_product_sale_rate'];
					$soamount+=$soiqty * $soirate;
				}
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$ponumber = substr("0000{$row["stc_sale_product_id"]}", -$str_length);
				$odin.='
					<tr>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_date"])).'</p>
       					</td>
       					<td>
       					  <h6>STC/DC/'.$ponumber.'</h6>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_cust_order_date"])).'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_cust_order_no"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_customer_name"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_way_bill_no"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_sitename"].'</p>
       					</td>
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'</p>
       					</td>
       					<td>
       					  <h6>'.$row["stc_sale_product_status"].'</h6>
       					</td>
       					<td>
       					  <div class="row">       					  
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="edit-challan.php?cid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="challan-preview.php?pid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
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

// show Customer on Sale order page
if(isset($_POST['friday_customer'])){
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_call_vendor();
	$date=date('l jS \ F Y h:i:s A');
	$outarray=array(
		'time' => $date,
		'customer' => $objlokiout
	);
	echo json_encode($outarray);
}

// show Product in sale Order page
if(isset($_POST['search_sale_pd_name_in'])){
	$searchme=$_POST['search_sale_pd_name_in'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_search_product_name($searchme);
	echo json_encode($objlokiout);
}

// Show Sale Orders On Sale Order Page
if(isset($_POST['stccallss'])){
	$challanbegdate=date("Y-m-d h:i:s", strtotime($_POST['begdate']));
	$challanenddate=date("Y-m-d h:i:s", strtotime($_POST['enddate']));
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_call_sale_orders($challanbegdate, $challanenddate);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// add to cart sale session obj
if(isset($_POST['add_sale'])){
	if($_POST["add_sale"] == "addsalecart") {  		
		$percalamount = ($_POST['product_price'] * $_POST['product_sale_percent'])/100;
		$pdsale_price = $percalamount + $_POST['product_price'];
		if(isset($_SESSION["stc_sale_order_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_sale_order_sess"] as $keys => $values) {  
				if($_SESSION["stc_sale_order_sess"][$keys]['product_id'] == $_POST["product_id"]) {  
				     $is_available++;  
				     $_SESSION["stc_sale_order_sess"][$keys]['product_quantity'] = $_SESSION["stc_sale_order_sess"][$keys]['product_quantity'] + $_POST["product_quantity"];  
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
		    	     'product_gst'       	  =>     $_POST["product_gst"],
		    	     'product_price'          =>     $_POST["product_price"],
		    	     'product_sale_price'     =>     $pdsale_price 
		    	);  
		    	$_SESSION["stc_sale_order_sess"][] = $item_array;  
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
		    	'product_price'          =>     $_POST["product_price"],
		    	'product_sale_price'     =>     $pdsale_price
			);   
			$_SESSION["stc_sale_order_sess"][] = $item_array;  
		     echo "Item Added to Sale Order Cart!!!";
		}  
    }  
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
			    <th scope="col" width="10%">Amount</th>
			    <th scope="col">Action</th>
			  </tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_sale_order_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_sale_order_sess"] as $keys => $values) {  
				$amount=$values["product_quantity"] * $values["product_sale_price"];
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
						  <a data-product_id="'.$values["product_id"].'" class="stcqtysale" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td>
						  <input
						  	style="width: 55%;padding: 5px;height: 40px;"
						    type="text"
						    id="stcratepot'.$values["product_id"].'"
						    class="validate"
						    placeholder="Rate"
						    value="'.number_format($values["product_sale_price"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcratesale" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
						<td> '.$values["product_gst"].'% </td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount + $gstamount, 2).'</td>
						<td><a style="color: red;font-size:20px;" href="#" id="'.$values["product_id"].'" class="stcdelsalebtn"><i class="fas fa-trash"></i></a></td>
					</tr>
				';  
				$total = $total + ($values["product_quantity"] * $values["product_sale_price"]) ;  
				$totalgst +=$gstamount;
			}  
			$gstamount = ($amount * $values["product_gst"])/100;
			$ekdumt=$total + $totalgst;
			$tcs=(($total + $totalgst) * 0.075)/100;
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
            	  <td colspan="8"><h4 align="right">Freight Charges</h4></td>
            	  <td colspan="2" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcfc" name="stcfc" value="0" placeholder="FC" style="width:100%;">
            	  </td>
            	</tr>
            	<tr>
            	  <td colspan="8"><h4 align="right">Packing & Forwarding</h4></td>
            	  <td colspan="2" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcpf" name="stcpf" value="0" placeholder="PF" style="width:100%;">
            	    <a 
            	    	style="font-size: 25px;font-weight: bold;color: red;"
             		  	class="stcsalec"
             		  	href="#"
             		>
             		  <i class="fas fa-arrow-down"></i>
             		</a>
            	  </td>
            	</tr>
        		<tr>
        		  <td colspan="9"><h4 align="right">TCS@ 0.075</h4></td>
        		  <td align="right"><i class="fas fa-rupee-sign"></i> '.round($tcs, 2).'</td>
        		</tr>
				<tr>
				  <td colspan="8"><h4 align="right">Grand Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.round($total + $totalgst + $tcs, 2).'</span></td>
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
}

// sale cart qty change
if(isset($_POST["sale_quantity_action"])){  
  	foreach($_SESSION["stc_sale_order_sess"] as $keys => $values){  
	    if($_SESSION["stc_sale_order_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_sale_order_sess"][$keys]['product_quantity'] = $_POST["quantity"];
    	}  
  	}  
}

// change price of session Sale order
if(isset($_POST["stc_price_action"])){  
  	foreach($_SESSION["stc_sale_order_sess"] as $keys => $values){  
	    if($_SESSION["stc_sale_order_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_sale_order_sess"][$keys]['product_sale_price'] = $_POST["price"];
    	}  
  	}  
}

// delete from sale cart of session Sale order
if(isset($_POST['stcdelsalelinei'])){  
     foreach($_SESSION["stc_sale_order_sess"] as $keys => $values){  
          if($values["product_id"] == $_POST["product_id"]){  
               unset($_SESSION["stc_sale_order_sess"][$keys]);  
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
	$customer_id=$_POST['customer_id'];
	$sale_custorderdate=$_POST['sale_custorderdate'];
	$sale_custordernumber=$_POST['sale_custordernumber'];
	$sale_stcfc=$_POST['sale_stcfc'];
	$sale_stcpf=$_POST['sale_stcpf'];
	$order_waybillno=$_POST['sale_waybillno'];
	$order_lrno=$_POST['sale_lrno'];
	$order_suppdate=$_POST['sale_supplydate'];
	$order_suppplace=$_POST['sale_supplyplace'];
	$order_refrence=$_POST['sale_refrence'];
	$order_sitename=$_POST['sale_sitename'];
	$order_contperson=$_POST['sale_contperson'];
	$order_contnumber=$_POST['sale_contnumber'];
	$order_shipadd=$_POST['sale_shipaddress'];
	$order_tandc=$_POST['order_tandc'];
	$order_notes=$_POST['order_notes'];

	
	$fridaysale=new aetherSale();
	$fridaysaleitems=new aetherSale();
	if(empty($sale_custorderdate) || empty($sale_custordernumber)){
		echo "Please Fill All Fields!!!";
	}else{
		$objfridaygrn=$fridaysale->sale_process(
			$customer_id, 
			$sale_custorderdate, 
			$sale_custordernumber, 
			$sale_stcfc, 
			$sale_stcpf,
			$order_waybillno,
			$order_lrno,
			$order_suppdate,
			$order_suppplace,
			$order_refrence,
			$order_sitename,
			$order_contperson,
			$order_contnumber,
			$order_shipadd,
			$order_tandc,
			$order_notes
		);
		if($objfridaygrn=='Sale Done!!!'){
			$objfridaygrnitems=$fridaysaleitems->stc_save_sale_pro_items();
			$out=$objfridaygrnitems;
			unset($_SESSION['stc_sale_order_sess']);
		}else{
			$out .= "Something went Wrong Please Check & Try Again!!!";
		}
	}
	echo $out;
}

// add challan items to sale product_id
if(isset($_POST['search_edit_sale_pd_name_in'])){
	$searchme=$_POST['search_edit_sale_pd_name_in'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_edit_search_product($searchme);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// edit challan and add product to databases
if(isset($_POST['edit_challan'])){
	$pd_id=$_POST['product_id'];
	$salechallanid=$_POST['product_soid'];
	$sale_qnty=$_POST['product_quantity'];
	$sale_purchase_price=$_POST['product_price'];
	$sale_percent=$_POST['product_price_perc'];

	$percalamount = ($sale_purchase_price * $sale_percent)/100;
	$sale_price=$percalamount + $sale_purchase_price;

	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_edit_add_product_in_challan($pd_id, $salechallanid, $sale_qnty, $sale_purchase_price, $sale_price);
	echo $objlokiout;
}

// quantity update
if(isset($_POST['updatechallanqty'])){
	$challan_id=$_POST['challanid'];
	$challan_pd_qty=$_POST['product_qty'];
	$inward_qty=$_POST['inward_qty'];
	$reverseqty=0;
	$outobjlokiinv='';
	$objloki=new aetherSale();
	$objlokiinv=new aetherSale();

	$objlokiout=$objloki->stc_edit_updateqty_product_in_challan($challan_id, $challan_pd_qty);
	if($objlokiout=="success"){
		if($challan_pd_qty >= $inward_qty){
			$reverseqty=$inward_qty - $challan_pd_qty;
			$outobjlokiinv=$objlokiinv->stc_increase_inventory_items($challan_id, $challan_pd_qty, $reverseqty);
		}else{
			$reverseqty=$inward_qty - $challan_pd_qty;
			$outobjlokiinv=$objlokiinv->stc_decrease_inventory_items($challan_id, $challan_pd_qty, $reverseqty);
		}
	}else{
		$outobjlokiinv = "Please recheck & try again later!!!";
	}
	echo $outobjlokiinv;
}

// price change 
if(isset($_POST['updatechallanprice'])){
	$challan_id=$_POST['soid'];
	$challan_pd_price=$_POST['product_price'];

	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_edit_updateprice_product_in_challan($challan_id, $challan_pd_price);
	echo json_encode($objlokiout);
}

// delete products from challan
if(isset($_POST['delchallanli'])){
	$challan_id=$_POST['soid'];
	$challan_delqty=$_POST['delqty'];

	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_edit_delete_product_in_challan($challan_id, $challan_delqty);
	echo json_encode($objlokiout);
	// echo $challan_id.'-'.$challan_delqty;
}

if(isset($_POST['edit_save_challan'])){
	$esono               	=	$_POST['esono'];
	$esomerchantid       	=	$_POST['esomerchantid'];
	$esocustorderno      	=	$_POST['esocustorderno'];
	$esowaybillno      		=	$_POST['esowaybillno'];
	$esowaylrno		      	=	$_POST['esowaylrno'];
	$esocustorddate      	=	$_POST['esocustorddate'];
	$esodosupply         	=	$_POST['esodosupply'];
	$esoposupply         	=	$_POST['esoposupply'];
	$esoorderrefrence    	=	$_POST['esoorderrefrence'];
	$esocustsitename     	=	$_POST['esocustsitename'];
	$esocustcontperson   	=	$_POST['esocustcontperson'];
	$esocustcontnumber   	=	$_POST['esocustcontnumber'];
	$esoshipaddres       	=	$_POST['esoshipaddres'];
	$esotermanc          	=	$_POST['esotermanc'];
	$esontes 				=	$_POST['esontes'];

	$objloki=new aetherSale();

	if($esomerchantid=='Please Select Customer'){
		$objlokiout=$objloki="Please select customer first!!!";
	}else{
		$objlokiout=$objloki->stc_edit_save_challan(
			$esono,
			$esomerchantid,
			$esocustorderno,
			$esowaybillno,
			$esowaylrno,
			$esocustorddate,
			$esodosupply,
			$esoposupply,
			$esoorderrefrence,
			$esocustsitename,
			$esocustcontperson,
			$esocustcontnumber,
			$esoshipaddres,
			$esotermanc,
			$esontes
		);
	}
	echo json_encode($objlokiout);
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

if(isset($_POST['search_ch_pd_name_in_ch'])){
	$out='';
	$searchme=$_POST['search_ch_pd_name_in_ch'];
	$objloki=new aetherSale();
	if(strlen($searchme)>=3){
		$objlokiout=$objloki->stc_search_product_thr_ch($searchme);
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
}
?>