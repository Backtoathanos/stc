<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Purchase Order --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class aetherSale extends tesseract{	

	// Search product name in Sale Orde Page
	public function stc_search_product_name($searchme){
		$perfectsearchme=strtoupper($searchme);
		$purchase_rate='';
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
					WHERE (
						`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."' OR 
						`stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."'
					)
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
				             	            <span class="p-qty" >'.$row["stc_product_unit"].'</span>
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

	// stc call sale orders
	public function stc_call_sale_orders($challanbegdate, $challanenddate){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_silent_challan`  
			ORDER BY `stc_sale_product_silent_challan`.`stc_sale_product_silent_challan_id` 
			DESC LIMIT 0,50
		");
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Silent Challan No</th>
	        	    <th scope="col">Customer Name</th>
	        	    <th scope="col">Customer Cont</th>
	        	    <th scope="col">Order On</th> 
	        	    <th scope="col">Delivered On</th>
	        	    <th scope="col">Order By</th>
	        	    <th scope="col">Deliver Through</th>
	        	    <th scope="col">Warranty status</th>
	        	    <th scope="col">Basic Value</th>
	        	    <th scope="col">Status</th>     
	        	    <th scope="col">Notes</th>                         
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
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_date"])).'</p>
       					</td>
       					<td>
       					  <h6>STC/DC/'.$challannumber.'</h6>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_silent_challan_customer_name"].'</p>
       					</td>     					
       					<td>
       					  <p>'.$row["stc_sale_product_silent_challan_contact_no"].'</p>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_order_on"])).'</p>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_delivered_on"])).'</p>
       					</td>     					
       					<td>
       					  <p>'.$row["stc_sale_product_silent_challan_order_by"].'</p>
       					</td>				
       					<td>
       					  <p>'.$row["stc_sale_product_silent_challan_delivered_through"].'</p>
       					</td>			
       					<td>
       					  <p>'.$row["stc_sale_product_silent_challan_warranty_status"].'</p>
       					</td>
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'</p>
       					</td>
       					<td>
       					  <h6>'.$row["stc_sale_product_silent_challan_status"].'</h6>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_silent_challan_notes"].'</p>
       					</td>
       					<td>
       					  <div class="row">       					  
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="edit-silent-challan.php?edcid='.$row["stc_sale_product_silent_challan_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
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

	// go data to sale table
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
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_sale_price'])."'
		    		)
		    	");
		    }
		    $odin = "Sale Item Done!!!";
		}
		return $odin;
	}

	public function stc_filter_challan($search, $challanbegdate, $challanenddate){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_silent_challan` 
			WHERE (
				`stc_sale_product_silent_challan_id` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_customer_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_contact_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_silent_challan_delivered_through` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			) AND (
                `stc_sale_product_silent_challan_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $challanbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $challanenddate)."'
            )
			ORDER BY `stc_sale_product_silent_challan`.`stc_sale_product_silent_challan_id` 
			DESC LIMIT 0,50
		");
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Silent Challan No</th>
	        	    <th scope="col">Customer Name</th>
	        	    <th scope="col">Customer Cont</th>
	        	    <th scope="col">Order On</th> 
	        	    <th scope="col">Delivered On</th>
	        	    <th scope="col">Order By</th>
	        	    <th scope="col">Deliver Through</th>
	        	    <th scope="col">Warranty status</th>
	        	    <th scope="col">Basic Value</th>
	        	    <th scope="col">Status</th>     
	        	    <th scope="col">Notes</th>                         
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
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_date"])).'</p>
       					</td>
       					<td>
       					  <h6>STC/DC/'.$challannumber.'</h6>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_silent_challan_customer_name"].'</p>
       					</td>     					
       					<td>
       					  <p>'.$row["stc_sale_product_silent_challan_contact_no"].'</p>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_order_on"])).'</p>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_delivered_on"])).'</p>
       					</td>     					
       					<td>
       					  <p>'.$row["stc_sale_product_silent_challan_order_by"].'</p>
       					</td>				
       					<td>
       					  <p>'.$row["stc_sale_product_silent_challan_delivered_through"].'</p>
       					</td>			
       					<td>
       					  <p>'.$row["stc_sale_product_silent_challan_warranty_status"].'</p>
       					</td>
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'</p>
       					</td>
       					<td>
       					  <h6>'.$row["stc_sale_product_silent_challan_status"].'</h6>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_silent_challan_notes"].'</p>
       					</td>
       					<td>
       					  <div class="row">       					  
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="edit-silent-challan.php?edcid='.$row["stc_sale_product_silent_challan_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
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


class Yggdrasil extends tesseract{	

	// stc call sale orders
	public function stc_call_sale_orders_invoices($loadchallanbegdate, $loadchallanenddate){
		$silentbvalue=0;
		$silenttvalue=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_silent_invoice`
			INNER JOIN `stc_sale_product_silent_challan` 
			ON `stc_sale_product_silent_invoice_challan_id`=`stc_sale_product_silent_challan_id`
			WHERE `stc_sale_product_silent_invoice_date`
			BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $loadchallanbegdate)."'
			AND '".mysqli_real_escape_string($this->stc_dbs, $loadchallanenddate)."'
			ORDER BY `stc_sale_product_silent_invoice`.`stc_sale_product_silent_invoice_id` 
			DESC LIMIT 0,25
		");
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Invoice Id</th>
	        	    <th scope="col">Invoice Date</th>
	        	    <th scope="col">Challan Number</th>
	        	    <th scope="col">Challan Date</th>
	        	    <th scope="col">Customer Name</th>
	        	    <th scope="col">Contact Number</th>
	        	    <th scope="col">Order On</th>
	        	    <th scope="col">Order By</th> 
	        	    <th scope="col">Deliver On</th>
	        	    <th scope="col">Deliver Through</th> 
	        	    <th scope="col">Challan Basic</th>                                     
	        	    <th scope="col">Total Value</th>                               
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

				$odin.='
					<tr>
       					<td>
       					  <h6>STC/'.substr("0000{$row["stc_sale_product_silent_invoice_id"]}", -5).'</h6>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_silent_invoice_date"])).'</p>
       					</td>
       					<td>
       					  <h6>STC/'.substr("0000{$row["stc_sale_product_silent_challan_id"]}", -5).'</h6>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_date"])).'</p>
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_customer_name"].'
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_contact_no"].'
       					</td>
       					<td>
       						'.date('d-m-y h:i:s', strtotime($row["stc_sale_product_silent_challan_order_on"])).'
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_order_by"].'
       					</td>
       					<td>
       						'.date('d-m-y h:i:s', strtotime($row["stc_sale_product_silent_challan_delivered_on"])).'
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_delivered_through"].'
       					</td>
       					<td align="right" style="width:auto;">
       					  <p><b><i class="fa fa-rupee-sign"></i> '.number_format($silentbvalue, 2).'</b></p>
       					</td>
       					<td align="right" style="width:auto;">
       					  <p><b><i class="fa fa-rupee-sign"></i> '.number_format($silenttvalue, 2).'</b></p>
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_status"].'
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
		$odintotal=$silentbvalue + $silenttvalue;
		$out=array(
			'bills_all' => $odin,
			'value_total' => number_format($odintotal, 2)
		);
		return $out;
	}

	// Search Challan in Sale Orde Page
	public function stc_search_challan(){
		$purchase_rate='';
		$alagsequery="					
					SELECT * FROM `stc_sale_product_silent_challan`
					WHERE `stc_sale_product_silent_challan_status`!='billed'
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
	public function 
		stc_invo_process(
			$stcinvodate,
			$stcinvonotes
		){	
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
		$silentbvalue=0;
		$silenttvalue=0;
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
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Invoice Id</th>
	        	    <th scope="col">Invoice Date</th>
	        	    <th scope="col">Challan Number</th>
	        	    <th scope="col">Challan Date</th>
	        	    <th scope="col">Customer Name</th>
	        	    <th scope="col">Contact Number</th>
	        	    <th scope="col">Order On</th>
	        	    <th scope="col">Order By</th> 
	        	    <th scope="col">Deliver On</th>
	        	    <th scope="col">Deliver Through</th> 
	        	    <th scope="col">Challan Basic</th>                                     
	        	    <th scope="col">Total Value</th>                               
	        	    <th scope="col">Status</th>
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

				$odin.='
					<tr>
       					<td>
       					  <h6>STC/'.substr("0000{$row["stc_sale_product_silent_invoice_id"]}", -5).'</h6>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_silent_invoice_date"])).'</p>
       					</td>
       					<td>
       					  <h6>STC/'.substr("0000{$row["stc_sale_product_silent_challan_id"]}", -5).'</h6>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_silent_challan_date"])).'</p>
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_customer_name"].'
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_contact_no"].'
       					</td>
       					<td>
       						'.date('d-m-y h:i:s', strtotime($row["stc_sale_product_silent_challan_order_on"])).'
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_order_by"].'
       					</td>
       					<td>
       						'.date('d-m-y h:i:s', strtotime($row["stc_sale_product_silent_challan_delivered_on"])).'
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_delivered_through"].'
       					</td>
       					<td align="right" style="width:auto;">
       					  <p><b><i class="fa fa-rupee-sign"></i> '.number_format($silentbvalue, 2).'</b></p>
       					</td>
       					<td align="right" style="width:auto;">
       					  <p><b><i class="fa fa-rupee-sign"></i> '.number_format($silenttvalue, 2).'</b></p>
       					</td>
       					<td>
       						'.$row["stc_sale_product_silent_challan_status"].'
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
		$odintotal=$silentbvalue + $silenttvalue;
		$out=array(
			'bills_all' => $odin,
			'value_total' => number_format($odintotal, 2)
		);
		return $out;
	}
}


class odinVault extends tesseract{	
	public function stc_electronics_item_call($query){
		$perfectsearchme=strtoupper($query);
		$purchase_rate='';
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
					WHERE (
						`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."' OR 
						`stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."'
					)
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
						ORDER BY `stc_purchase_product_items_id` DESC
					)
				");
				$purchase_rate=mysqli_fetch_assoc($loki_findratefrompo);
				$correct_stcpdname=strlen($row["stc_product_name"]);
				if(!empty($purchase_rate['stc_purchase_product_items_rate'])){
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
					             	            <span class="p-qty" >'.$row["stc_product_unit"].'</span>
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
							             	       <div class="row">
							             	       	<div class="col-sm-6">
							             	       		<input 
								             	    		type="number" 
								             	    		name="stcelecinvdpprice" 
								             	    		id="stcelecinvdpprice'.$row["stc_product_id"].'" 
								             	    		placeholder="Enter DP rate"
								             	    		class="form-control validate" 
								             	    		value="'.number_format($purchase_rate["stc_purchase_product_items_rate"], 2).'"
							             	    		/>     
							             	       	</div>
							             	       	<div class="col-sm-6">
							             	       		<input 
								             	    		type="number" 
								             	    		name="stcelecinvmrpprice" 
								             	    		id="stcelecinvmrpprice'.$row["stc_product_id"].'" 
								             	    		placeholder="Enter MRP rate"
								             	    		class="form-control validate" 
								             	    		value="'.number_format($purchase_rate["stc_purchase_product_items_rate"], 2).'"
							             	    		/>     
							             	       	</div>
							             	       </div>      	            
					             	        </div>
				             	         	<div class="a-size">
				             	         		<select 
						             	         	id="stcpdcondition'.$row["stc_product_id"].'" 
						             	         	class="form-control validate"
						             	         	style="padding: 16px 18px;"
						             	        >	
						             	        	<option 
							             	         	value="0"
							             	         >DEFAULT</option>
							             	         <option 
							             	         	value="1"
							             	         	selected
							             	         >GOOD</option>
							             	         <option 
							             	         	value="2"
							             	         >BETTER</option>
							             	         <option 
							             	         	value="3"
							             	         >BEST</option>
						             	         </select>
					             	        	<input
							                	  id="stcelecinvqty'.$row["stc_product_id"].'"
							                	  name="stcpoqty"
							                	  type="number"
							                	  placeholder="Quantity"
							                	  class="form-control validate"
							                	  value="1"
							                	/>
				             	          	</div>
				            		        </div>
				            		    </div>

			             				<div class="box-down">
			             					<div class="h-bg">
			             						<div class="h-bg-inner"></div>
			             					</div>

			             					<a class="cart add_to_elec_inv_cart" href="#" id="'.$row["stc_product_id"].'">
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

	public function stc_electro_inv_show_cart(){
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">#</th>
	        	    <th scope="col">Item Desc</th>
	        	    <th scope="col">Category</th>
	        	    <th scope="col">Qty</th>
	        	    <th scope="col">Tax</th>
	        	    <th scope="col">DP</th>
	        	    <th scope="col">MRP</th>
	        	    <th scope="col">Condition</th> 
	        	    <th scope="col">SP</th>
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
	    $slno=1;
		if(empty($_SESSION['stc_electronics_inventory_session'])){
			$odin.='
						<tr>
							<td colspan="10" align="center">STC Electronics Cart Empty!!!</td>
						</tr>
					';
		}else{
			foreach($_SESSION['stc_electronics_inventory_session'] as $elecinvrow){
				$lokigetinv=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_product`
					INNER JOIN `stc_category`
					ON `stc_cat_id`=`stc_product_cat_id`
					INNER JOIN `stc_sub_category`
					ON `stc_sub_cat_id`=`stc_product_sub_cat_id`
					WHERE `stc_product_id`='".$elecinvrow['sess_pd_pd_id']."'
				");
				foreach($lokigetinv as $nestquerow){
					$odin.='
						<tr>
							<td>'.$slno.'</td>
							<td>'.$nestquerow['stc_product_name'].'</td>
							<td>'.$nestquerow['stc_cat_name'].'</td>
							<td>'.$elecinvrow['sess_pd_pd_qty'].'</td>
							<td>'.$nestquerow['stc_product_name'].'</td>
							<td>'.$elecinvrow['sess_pd_pd_dp'].'</td>
							<td>'.$elecinvrow['sess_pd_pd_mrp'].'</td>
							<td>'.$elecinvrow['sess_pd_pd_cond'].'</td>
							<td>'.$elecinvrow['sess_pd_pd_price'].'</td>
							<td>Ok</td>
						</tr>
					';
					$slno++;
				}
		    }
		}
		
		$odin.='
				</tbody>
	       	</table>
		';
		return $odin;
	}

	public function stc_electro_inv_save(){
		$odin='';
		foreach($_SESSION['stc_electronics_inventory_session'] as $saverow){
			$checkqry=mysqli_query($this->stc_dbs, "
				SELECT `stc_electronics_inventory_item_qty` FROM 
					`stc_electronics_inventory` 
				WHERE
					 `stc_electronics_inventory_item_id` = '".mysqli_real_escape_string($this->stc_dbs, $saverow['sess_pd_pd_id'])."'
			");
			if(mysqli_num_rows($checkqry)>0){
				$getqty=0;

				foreach($checkqry as $qtyrow){
					$getqty+=$qtyrow['stc_electronics_inventory_item_qty'];
				}

				$setqty=$getqty + $saverow['sess_pd_pd_qty'];

				$lokiupdatequery=mysqli_query($this->stc_dbs, "
					UPDATE 
						`stc_electronics_inventory` 
					SET 
						`stc_electronics_inventory_item_qty` = '".mysqli_real_escape_string($this->stc_dbs, $setqty)."'
					WHERE 
						`stc_electronics_inventory_item_id` = '".mysqli_real_escape_string($this->stc_dbs, $saverow['sess_pd_pd_id'])."'
				");
				if($lokiupdatequery){
					$odin="success";
				}else{
					$odin="no";
				}
			}else{
				$lokigoquery=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_electronics_inventory`(
						`stc_electronics_inventory_item_id`, 
						`stc_electronics_inventory_item_qty`, 
						`stc_electronics_inventory_dp`, 
						`stc_electronics_inventory_mrp`, 
						`stc_electronics_inventory_condition`, 
						`stc_electronics_inventory_sale_rate`
					) VALUES (
						'".mysqli_real_escape_string($this->stc_dbs, $saverow['sess_pd_pd_id'])."',
						'".mysqli_real_escape_string($this->stc_dbs, $saverow['sess_pd_pd_qty'])."',
						'".mysqli_real_escape_string($this->stc_dbs, $saverow['sess_pd_pd_dp'])."',
						'".mysqli_real_escape_string($this->stc_dbs, $saverow['sess_pd_pd_mrp'])."',
						'".mysqli_real_escape_string($this->stc_dbs, $saverow['sess_pd_pd_cond'])."',
						'".mysqli_real_escape_string($this->stc_dbs, $saverow['sess_pd_pd_price'])."'
					)
				");
				if($lokigoquery){
					$odin="success";
				}else{
					$odin="no";
				}
			}
		}
		return $odin;
	}
}

#<------------------------------------------------------------------------------------------>
#<-------------------------------------Purchase Objects------------------------------------->
#<------------------------------------------------------------------------------------------>

// show Product in sale Order page
if(isset($_POST['search_sale_pd_name_in'])){
	$searchme=$_POST['search_sale_pd_name_in'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_search_product_name($searchme);
	echo json_encode($objlokiout);
	// echo $objlokiout;
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
		    	     'product_gst'       	  =>     $_POST["product_gst"],
		    	     'product_price'          =>     $_POST["product_price"],
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
		    	'product_price'          =>     $_POST["product_price"],
		    	'product_sale_price'     =>     $pdsale_price
			);   
			$_SESSION["stc_silent_challan_sess"][] = $item_array;  
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
		if(!empty($_SESSION["stc_silent_challan_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_silent_challan_sess"] as $keys => $values) {  
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
						    id="stcqtysc'.$values["product_id"].'"
						    class="validate"
						    placeholder="Quantity"
						    value="'.number_format($values["product_quantity"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcqtysilent" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td>
						  <input
						  	style="width: 55%;padding: 5px;height: 40px;"
						    type="text"
						    id="stcratesc'.$values["product_id"].'"
						    class="validate"
						    placeholder="Rate"
						    value="'.number_format($values["product_sale_price"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcratesilent" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
						<td> '.$values["product_gst"].'% </td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount + $gstamount, 2).'</td>
						<td><a style="color: red;font-size:20px;" href="#" id="'.$values["product_id"].'" class="stcdelsilentbtn"><i class="fas fa-trash"></i></a></td>
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
             		  <i class="fas fa-arrow-down"></i>
             		</a>
            	  </td>
            	</tr>
				<tr>
				  <td colspan="8"><h4 align="right">Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.round($total + $totalgst, 2).'</span></td>
				  <td></td>
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
	$silent_challandate				= date('Y-m-d h:i:s', strtotime($_POST['silent_challandate']));
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
		echo "Please Fill All Fields!!!";
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



#<------------------------------------------------------------------------------------------>
#<---------------------------------Silent Invoice Objects----------------------------------->
#<------------------------------------------------------------------------------------------>

// Show Sale Orders invoice On Sale Order Page
if(isset($_POST['load_invoices'])){
	$loadchallanbegdate=date("Y-m-d", strtotime($_POST['loadchallanbegdate']));
	$loadchallanenddate=date("Y-m-d", strtotime($_POST['loadchallanenddate']));
	$objloki=new Yggdrasil();
	$objlokiout=$objloki->stc_call_sale_orders_invoices($loadchallanbegdate, $loadchallanenddate);
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
			$out .= "Something went Wrong Please Check & Try Again!!!";
		}
	}
	echo $out;
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


#<------------------------------------------------------------------------------------------>
#<---------------------------------Silent Inventory Objects--------------------------------->
#<------------------------------------------------------------------------------------------>
// search item
if(isset($_POST['search_items_stc_add'])){
	$query=$_POST['search_items_stc_add'];
	$out='';
	$objloki=new odinVault();
	if(empty($query)){
		$out="If you search with empty fields then how can i get you???";
	}else{
		$opobjloki=$objloki->stc_electronics_item_call($query);
		$out=$opobjloki;
	}
	echo $out;
}

if(isset($_POST['omega_elec_hit'])){
	$pd_pd_id=$_POST['omega_pd_id'];
	$pd_pd_price=$_POST['omega_pd_price'];
	$pd_pd_dp=$_POST['omega_pd_dp'];
	$pd_pd_mrp=$_POST['omega_pd_mrp'];
	$pd_pd_cond=$_POST['omega_pd_cond'];
	$pd_pd_qty=$_POST['omega_pd_qty'];
	if(isset($_SESSION["stc_electronics_inventory_session"])) {
		$is_available=0;
		foreach($_SESSION["stc_electronics_inventory_session"] as $keys => $values) {  
			if($_SESSION["stc_electronics_inventory_session"][$keys]['sess_pd_pd_id'] == $pd_pd_id) {  
			     $is_available++; 
			     echo "This Product Is Already In A Inventory Cart!!!";
			}  
		} 
		if($is_available < 1) {  
			$item_array = array(  
			     'sess_pd_pd_id' => $pd_pd_id,
			     'sess_pd_pd_price' => $pd_pd_price,
			     'sess_pd_pd_dp' => $pd_pd_dp,
			     'sess_pd_pd_mrp' => $pd_pd_mrp,
			     'sess_pd_pd_cond' => $pd_pd_cond,
			     'sess_pd_pd_qty' => $pd_pd_qty
			);  
			$_SESSION["stc_electronics_inventory_session"][] = $item_array;  
			echo "Inventory Cart Session Created & Product Added To Cart!!!";
		}
	}else{  
		$item_array = array(  
		     'sess_pd_pd_id' => $pd_pd_id,
		     'sess_pd_pd_price' => $pd_pd_price,
		     'sess_pd_pd_dp' => $pd_pd_dp,
		     'sess_pd_pd_mrp' => $pd_pd_mrp,
		     'sess_pd_pd_cond' => $pd_pd_cond,
		     'sess_pd_pd_qty' => $pd_pd_qty
		);  
		$_SESSION["stc_electronics_inventory_session"][] = $item_array;
	     echo "Product Added to Inventory Cart Session Cart!!!";
	}
}

if(isset($_POST['call_stc_elec_inv'])){
	$objloki=new odinVault();
	$objlokiout=$objloki->stc_electro_inv_show_cart();
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

if(isset($_POST['omega_elec_save'])){
	$out='';
	$objloki=new odinVault();
	if(!empty($_SESSION['stc_electronics_inventory_session'])){
		$objlokiout=$objloki->stc_electro_inv_save();
		if($objlokiout=="success"){
			$out="Product added to the STC Electronics. Hope they will do the next:)";
			unset($_SESSION['stc_electronics_inventory_session']);
		}else{
			$out="Hmmm!!! Something went wrong. need your attention to do it again.";
		}
	}else{
		$out="Atleast add item first:<";
	}
	echo json_encode($out);
	// echo $objlokiout;
}

?>