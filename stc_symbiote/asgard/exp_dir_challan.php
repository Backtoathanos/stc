<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Purchase Order --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class aetherSale extends tesseract{	

	// stc call direct challan
	public function stc_call_sale_orders($directchallanbegdate, $directchallanenddate){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_id`,
				`stc_sale_product_date`,
				`stc_sale_product_cust_order_date`,
				`stc_sale_product_cust_order_no`,
				`stc_customer_name`,
				`stc_sale_product_way_bill_no`,
				`stc_sale_product_sitename`,
				`stc_merchant_name`,
				`stc_sale_product_status`,
				`stc_sale_product_dc_invo_no`,
				`stc_sale_product_dc_invo_date`
			FROM `stc_sale_product` 
			LEFT JOIN `stc_customer` 
			ON `stc_sale_product_cust_id`=`stc_customer_id` 
			INNER JOIN `stc_merchant`
			ON `stc_sale_product_dc_merchant`=`stc_merchant_id`
			WHERE (
				(
					`stc_sale_product_status`='direct_sale' 
					AND `stc_sale_product_dc_keys`='directchallaned'
				) OR (
					`stc_sale_product_status`='billed' 
					AND `stc_sale_product_dc_keys`='directchallaned'
				)
			)AND (
                `stc_sale_product_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $directchallanbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $directchallanenddate)."'
            )
			ORDER BY `stc_sale_product`.`stc_sale_product_id` 
			DESC LIMIT 0,20
		");
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Direct Challan No</th>
	        	    <th scope="col">Customer Order Date</th>
	        	    <th scope="col">Customer Order No</th>
	        	    <th scope="col">Customer Name</th> 
	        	    <th scope="col">Way Bill No</th> 
	        	    <th scope="col">Customer Site</th> 
	        	    <th scope="col">Merchant Name</th>
	        	    <th scope="col">Merchant Invoice</th>
	        	    <th scope="col">Merchant Date</th>
	        	    <th scope="col">Soled Basic Value</th>
	        	    <th scope="col">Pur Basic Value</th>
	        	    <th scope="col">Paid Amount</th>
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
				$paidvalue=0;
				$getpaid=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_sale_product_dc_payment_value`) AS paidamt
					FROM `stc_sale_product_dc_payment` 
					WHERE `stc_sale_product_dc_payment_order_id`='".$row["stc_sale_product_id"]."'
				");

				$paidvalue=mysqli_fetch_assoc($getpaid);
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_sale_product_dc_items_product_qty`, 
						`stc_sale_product_dc_items_product_sale_rate`, 
						`stc_sale_product_dc_items_product_rate`
					FROM `stc_sale_product_dc_items` 
					WHERE `stc_sale_product_dc_items_sale_product_id`='".$row["stc_sale_product_id"]."'
				");
				$poamount=0;
				$soamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$soiqty=$row2nd['stc_sale_product_dc_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_dc_items_product_sale_rate'];
					$poirate=$row2nd['stc_sale_product_dc_items_product_rate'];
					$soamount+=$soiqty * $soirate;
					$poamount+=$soiqty * $poirate;
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
       					<td>
       					  <p>'.$row["stc_merchant_name"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_dc_invo_no"].'</p>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_dc_invo_date"])).'</p>
       					</td>
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'</p>
       					</td>
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($poamount, 2).'</p>
       					</td>
       					<td  style="width:auto;text-align:right;">
       						<p><i class="fa fa-rupee-sign"></i> '.number_format($paidvalue["paidamt"], 2).'</p>
       					</td>
       					<td>
       					  <h6>'.$row["stc_sale_product_status"].'</h6>
       					</td>
       					<td>
       					  <div class="row">       					  
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="edit-direct-challan.php?dcid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="direct-challan-preview.php?dcid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
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

	// Search Product Name in direct challan Page
	public function stc_search_product_name($searchme){
		$perfectsearchme=strtoupper($searchme);
		$purchase_rate='';
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
					WHERE (
						`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."' OR 
						`stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."'
					)
					ORDER BY `stc_product_id` 
					ASC LIMIT 0,15
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
			             	            <span class="p-company">'.$row["stc_cat_name"].'</span>
			             	            <span class="p-qty" >'.$row["stc_product_unit"].'</span>
			             	            <input 
				             	          	type="number" 
				             	          	name="stcpdprice" 
				             	          	id="stcpdprice'.$row["stc_product_id"].'" 
				             	          	placeholder="Enter Amount"
				             	          	class="form-control validate"
		             	         			style="color:black;font-weight:bold;text-align:center"
				             	          />		  
				             	        <input 
				             	          	type="number" 
				             	          	name="stcpdsaleprice" 
				             	          	id="stcpdsaleperc'.$row["stc_product_id"].'" 
				             	          	placeholder="Enter Percent % Below 100"
				             	          	value="'.number_format($row['stc_product_sale_percentage'], 2, '.', '').'"
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

		             	        <a class="cart add_to_direct_cart" href="#" id="'.$row["stc_product_id"].'">
		             	          <span class="price">'.$row["stc_sub_cat_name"].'</span>
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

	public function stc_check_sale_bill($sale_mername, $sale_billinvono, $sale_billinvodate){
		$odin='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_id` 
			FROM `stc_sale_product` 
			WHERE `stc_sale_product_dc_merchant`='".$sale_mername."'
			AND `stc_sale_product_dc_invo_no`='".$sale_billinvono."'
			AND `stc_sale_product_dc_invo_date`='".$sale_billinvodate."'
		");
		if(mysqli_num_rows($check_loki)>0){
			$odin="yes";
		}else{
			$odin="no";
		}
		return $odin;
	}
	// go data to direct challan table
	public function sale_process($customer_id, $sale_custorderdate,  $sale_custordernumber,  $sale_mername, $sale_billinvono, $sale_billinvodate, $sale_stcfc,  $sale_stcpf, $order_waybillno, $order_lrno, $order_suppdate, $order_suppplace, $order_refrence, $order_sitename, $order_contperson, $order_contnumber, $order_shipadd, $order_tandc, $order_notes){	
		$maxgst='';
		foreach($_SESSION["stc_direct_challan_sess"] as $keys => $values) {
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
				`stc_sale_product_created_by`,
				`stc_sale_product_dc_invo_no`,
				`stc_sale_product_dc_invo_date`,
				`stc_sale_product_dc_merchant`,
				`stc_sale_product_dc_keys`
			) VALUES (
				NOW(), 
				'".$customer_id."', 
				'".$sale_custordernumber."', 
				'".$sale_custorderdate."', 
				'0', 
				'".$order_waybillno."',
				'".$order_lrno."',
				'".$sale_stcfc."', 
				'".$sale_stcpf."',
				'".max($maxgst)."',
				'".$order_notes."',
				'".$order_refrence."',
				'".$order_suppdate."',
				'".$order_suppplace."',
				'".$order_sitename."',
				'".$order_shipadd."',
				'".$order_contperson."',
				'".$order_contnumber."',
				'".$order_tandc."',
				'direct_sale',
				'0',
				'0',
				'".$_SESSION['stc_admin_info_id']."',
				'".$sale_billinvono."',
				'".$sale_billinvodate."',
				'".$sale_mername."',
				'directchallaned'
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

	// go direct challan session data to direct challan items table 
	public function stc_save_sale_pro_items(){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product` ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_sale_product_id'];
		if(empty($_SESSION['stc_direct_challan_sess'])){
			$odin = "Do not leave Items on Sale Order!!!";
		}else{
			foreach ($_SESSION["stc_direct_challan_sess"] as $key=>$value) {
		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_sale_product_dc_items`(
		    			`stc_sale_product_dc_items_sale_product_id`, 
		    			`stc_sale_product_dc_items_product_id`, 
		    			`stc_sale_product_dc_items_product_qty`, 
		    			`stc_sale_product_dc_items_product_rate`, 
		    			`stc_sale_product_dc_items_product_sale_rate`
		    		) VALUES (
		    			'".$get_purchase_product_id."', 
		    			'".$value['product_id']."',
		    			'".$value['product_quantity']."', 
		    			'".$value['product_price']."', 
		    			'".$value['product_sale_price']."'
		    		)
		    	");
		    }
		    $odin = "Sale Item Done!!!";
		}
		return $odin;
	}

	// search edit items
	public function stc_edit_search_product($searchme){
		$perfectsearchme=strtoupper($searchme);
		$purchase_rate='';
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
					WHERE `stc_product_name` REGEXP '".$perfectsearchme."'
					ORDER BY `stc_product_id` ASC
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
			             	            <span class="p-company">'.$row["stc_cat_name"].'</span>
			             	            <span class="p-qty" >'.$row["stc_product_unit"].'</span>
			             	            <input 
				             	          	type="number" 
				             	          	name="stcpdprice" 
				             	          	id="stceditchallanprice'.$row["stc_product_id"].'" 
				             	          	placeholder="Enter Amount"
				             	          	class="form-control validate"
		             	         			style="color:black;font-weight:bold;text-align:center"
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

		             	        <a class="cart add_to_cartforeditdirectchallan" href="#" id="'.$row["stc_product_id"].'">
		             	          <span class="price">'.$row["stc_sub_cat_name"].'</span>
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

	// add to challan table direct challan
	public function stc_edit_add_product_in_challan($pd_id, $salechallanid, $sale_qnty, $sale_purchase_price, $sale_price){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_sale_product_dc_items`(
				`stc_sale_product_dc_items_sale_product_id`, 
				`stc_sale_product_dc_items_product_id`, 
				`stc_sale_product_dc_items_product_qty`, 
				`stc_sale_product_dc_items_product_rate`, 
				`stc_sale_product_dc_items_product_sale_rate`
			) VALUES (
				'".$salechallanid."', 
				'".$pd_id."',
				'".$sale_qnty."', 
				'".$sale_purchase_price."', 
				'".$sale_price."'
			)
		");
		if($friday_loki){
			$odin="Product added!!!";
		}else{
			$odin="Please try again!!!";
		}
		return $odin;
	}

	// edit qty from direct challan table
	public function stc_edit_updateqty_product_in_challan($challan_id, $challan_pd_qty){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product_dc_items` 
			SET `stc_sale_product_dc_items_product_qty`= '".$challan_pd_qty."'
			WHERE `stc_sale_product_dc_items_id`='".$challan_id."'
		");
		if($friday_loki){
			$odin = "success";
		}else{
			$odin = "not done";
		}
		
		return $odin;
	}

	// price update direct challan
	public function stc_edit_updateprice_product_in_challan($challan_id, $challan_pd_price){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product_dc_items` SET `stc_sale_product_dc_items_product_sale_rate`= '".$challan_pd_price."'
			WHERE `stc_sale_product_dc_items_id`='".$challan_id."'
		");
		if($friday_loki){
			$odin = "Product price updated!!!";
		}else{
			$odin = "Please try again!!!";
		}
		return $odin;
	}

	// purchase price update direct challan
	public function stc_edit_updatepurchaseprice_product_in_challan($challan_id, $challan_pd_price){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product_dc_items` SET `stc_sale_product_dc_items_product_rate`= '".$challan_pd_price."'
			WHERE `stc_sale_product_dc_items_id`='".$challan_id."'
		");
		if($friday_loki){
			$odin = "Product purchase price updated!!!";
		}else{
			$odin = "Please try again!!!";
		}
		return $odin;
	}

	// delete product from direct challan
	public function stc_edit_delete_product_in_challan($challan_id, $challan_delqty){
		$odin='';
		$friday_loki_inventory=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_sale_product_dc_items` WHERE `stc_sale_product_dc_items_id`='".$challan_id."'
		");
		if($friday_loki_inventory){
			$odin= "Item removed!!!";
		}else{
			$odin= "Please try again!!!";
		}
		return $odin;
	}

	// save edited direct challan
	public function stc_edit_save_challan($esono, $esomerchantid, $esocustorderno, $esomername, $esobillinvono, $esobillinvodate, $esowaybillno, $esowaylrno, $esocustorddate, $esodosupply, $esoposupply, $esoorderrefrence, $esocustsitename, $esocustcontperson, $esocustcontnumber, $esoshipaddres, $esotermanc, $esontes){
		$maxgst='';
		$odin='';
		$lokiinsertsale=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product` SET 
				`stc_sale_product_cust_id`='".$esomerchantid."',
				`stc_sale_product_cust_order_no`='".$esocustorderno."',
				`stc_sale_product_cust_order_date`='".$esocustorddate."',
				`stc_sale_product_way_bill_no`='".$esowaybillno."',
				`stc_sale_product_lr_no`='".$esowaylrno."',
				`stc_sale_product_dosupply`='".$esodosupply."',
				`stc_sale_product_posupply`='".$esoposupply."',
				`stc_sale_product_refrence`='".$esoorderrefrence."',
				`stc_sale_product_sitename`='".$esocustsitename."',
				`stc_sale_product_cont_person`='".$esocustcontperson."',
				`stc_sale_product_cont_number`='".$esocustcontnumber."',
				`stc_sale_product_siteaddress`='".$esoshipaddres."',
				`stc_sale_product_tandc`='".$esotermanc."',
				`stc_sale_product_notes`='".$esontes."',
				`stc_sale_product_dc_invo_no`='".$esobillinvono."',
				`stc_sale_product_dc_invo_date`='".$esobillinvodate."',
				`stc_sale_product_dc_merchant`='".$esomername."'
			WHERE `stc_sale_product_id`='".$esono."'
		");
		$odin='';
		if($lokiinsertsale){
			$odin .= "Challan updated!!!";
		}else{
			$odin .= "Please check & try again!!!";
		}
		return $odin;
	}

	public function stc_filter_challan($search, $directchallanbegdate, $directchallanenddate){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_id`,
				`stc_sale_product_date`,
				`stc_sale_product_cust_order_date`,
				`stc_sale_product_cust_order_no`,
				`stc_customer_name`,
				`stc_sale_product_way_bill_no`,
				`stc_sale_product_sitename`,
				`stc_merchant_name`,
				`stc_sale_product_dc_invo_no`,
				`stc_sale_product_dc_invo_date`,
				`stc_sale_product_status`
			FROM `stc_sale_product` 
			LEFT JOIN `stc_customer` 
			ON `stc_sale_product_cust_id`=`stc_customer_id` 
			LEFT JOIN `stc_merchant` 
			ON `stc_sale_product_dc_merchant`=`stc_merchant_id` 
			WHERE (`stc_customer_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_id` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_sitename` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_way_bill_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_dc_invo_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_merchant_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				OR `stc_sale_product_dc_keys` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
			) AND (
				(
					`stc_sale_product_status`='direct_sale' 
					AND `stc_sale_product_dc_keys`='directchallaned'
				) OR (
					`stc_sale_product_status`='billed' 
					AND `stc_sale_product_dc_keys`='directchallaned'
				)
			) AND (
                `stc_sale_product_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $directchallanbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $directchallanenddate)."'
            )
			ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC
		");
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Direct Challan No</th>
	        	    <th scope="col">Customer Order Date</th>
	        	    <th scope="col">Customer Order No</th>
	        	    <th scope="col">Customer Name</th> 
	        	    <th scope="col">Way Bill No</th> 
	        	    <th scope="col">Customer Site</th> 
	        	    <th scope="col">Merchant Name</th>
	        	    <th scope="col">Merchant Invoice</th>
	        	    <th scope="col">Merchant Date</th>
	        	    <th scope="col">Soled Basic Value</th>
	        	    <th scope="col">Pur Basic Value</th>
	        	    <th scope="col">Paid Amount</th>
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
				$paidvalue=0;
				$getpaid=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_sale_product_dc_payment_value`) AS paidamt
					FROM `stc_sale_product_dc_payment` 
					WHERE `stc_sale_product_dc_payment_order_id`='".$row["stc_sale_product_id"]."'
				");

				$paidvalue=mysqli_fetch_assoc($getpaid);
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_sale_product_dc_items_product_qty`, 
						`stc_sale_product_dc_items_product_sale_rate`, 
						`stc_sale_product_dc_items_product_rate`
					FROM `stc_sale_product_dc_items` 
					WHERE `stc_sale_product_dc_items_sale_product_id`='".$row["stc_sale_product_id"]."'
				");
				$poamount=0;
				$soamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$soiqty=$row2nd['stc_sale_product_dc_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_dc_items_product_sale_rate'];
					$poirate=$row2nd['stc_sale_product_dc_items_product_rate'];
					$soamount+=$soiqty * $soirate;
					$poamount+=$soiqty * $poirate;
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
       					<td>
       					  <p>'.$row["stc_merchant_name"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_dc_invo_no"].'</p>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_dc_invo_date"])).'</p>
       					</td>
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'</p>
       					</td>
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($poamount, 2).'</p>
       					</td>
       					<td  style="width:auto;text-align:right;">
       						<p><i class="fa fa-rupee-sign"></i> '.number_format($paidvalue["paidamt"], 2).'</p>
       					</td>
       					<td>
       					  <h6>'.$row["stc_sale_product_status"].'</h6>
       					</td>
       					<td>
       					  <div class="row">       					  
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="edit-direct-challan.php?dcid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="direct-challan-preview.php?dcid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
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

	// filter direct Challan by materials name
	public function stc_search_product_thr_ch($searchme){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT * FROM `stc_sale_product_dc_items` 
			LEFT JOIN `stc_product` 
			ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
			LEFT JOIN `stc_sale_product`
			ON `stc_sale_product_dc_items_sale_product_id`=`stc_sale_product_id`
			LEFT JOIN `stc_customer` 
			ON `stc_sale_product_cust_id`=`stc_customer_id`
			LEFT JOIN `stc_merchant` 
			ON `stc_sale_product_dc_merchant`=`stc_merchant_id` 
			WHERE (`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchme)."'
			OR `stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchme)."') 
			AND (
				(
					`stc_sale_product_status`='direct_sale' 
					AND `stc_sale_product_dc_keys`='directchallaned'
				) OR (
					`stc_sale_product_status`='billed' 
					AND `stc_sale_product_dc_keys`='directchallaned'
				)
			)
			ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC
			LIMIT 0,30
		");
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Direct Challan No</th>
	        	    <th scope="col">Customer Order Date</th>
	        	    <th scope="col">Customer Order No</th>
	        	    <th scope="col">Customer Name</th> 
	        	    <th scope="col">Way Bill No</th> 
	        	    <th scope="col">Customer Site</th> 
	        	    <th scope="col">Merchant Name</th>
	        	    <th scope="col">Merchant Invoice</th>
	        	    <th scope="col">Merchant Date</th>
	        	    <th scope="col">Soled Basic Value</th>
	        	    <th scope="col">Pur Basic Value</th>
	        	    <th scope="col">Paid Amount</th>
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
				$paidvalue=0;
				$getpaid=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_sale_product_dc_payment_value`) AS paidamt
					FROM `stc_sale_product_dc_payment` 
					WHERE `stc_sale_product_dc_payment_order_id`='".$row["stc_sale_product_id"]."'
				");

				$paidvalue=mysqli_fetch_assoc($getpaid);
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_sale_product_dc_items_product_qty`, 
						`stc_sale_product_dc_items_product_sale_rate`, 
						`stc_sale_product_dc_items_product_rate`
					FROM `stc_sale_product_dc_items` 
					WHERE `stc_sale_product_dc_items_sale_product_id`='".$row["stc_sale_product_id"]."'
				");
				$poamount=0;
				$soamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$soiqty=$row2nd['stc_sale_product_dc_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_dc_items_product_sale_rate'];
					$poirate=$row2nd['stc_sale_product_dc_items_product_rate'];
					$soamount+=$soiqty * $soirate;
					$poamount+=$soiqty * $poirate;
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
       					<td>
       					  <p>'.$row["stc_merchant_name"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_sale_product_dc_invo_no"].'</p>
       					</td>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_dc_invo_date"])).'</p>
       					</td>
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'</p>
       					</td>
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($poamount, 2).'</p>
       					</td>
       					<td  style="width:auto;text-align:right;">
       						<p><i class="fa fa-rupee-sign"></i> '.number_format($paidvalue["paidamt"], 2).'</p>
       					</td>
       					<td>
       					  <h6>'.$row["stc_sale_product_status"].'</h6>
       					</td>
       					<td>
       					  <div class="row">       					  
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="edit-direct-challan.php?dcid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="direct-challan-preview.php?dcid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
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

// Show Sale Orders On Sale Order Page
if(isset($_POST['stccalldc'])){
	$directchallanbegdate=date("Y-m-d h:i:s", strtotime($_POST['begdate']));
	$directchallanenddate=date("Y-m-d h:i:s", strtotime($_POST['enddate']));
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_call_sale_orders($directchallanbegdate, $directchallanenddate);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// show Product in direct challan Order page
if(isset($_POST['search_sale_pd_name_in'])){
	$searchme=$_POST['search_sale_pd_name_in'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_search_product_name($searchme);
	echo json_encode($objlokiout);
}

// add to cart direct challan session obj
if(isset($_POST['add_dc'])){
	if($_POST["add_dc"] == "addsalecart") {  		
		$percalamount = ($_POST['product_price'] * $_POST['product_sale_percent'])/100;
		$pdsale_price = $percalamount + $_POST['product_price'];
		if(isset($_SESSION["stc_direct_challan_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_direct_challan_sess"] as $keys => $values) {  
				if($_SESSION["stc_direct_challan_sess"][$keys]['product_id'] == $_POST["product_id"]) {  
				     $is_available++;  
				     $_SESSION["stc_direct_challan_sess"][$keys]['product_quantity'] = $_SESSION["stc_direct_challan_sess"][$keys]['product_quantity'] + $_POST["product_quantity"];  
				     echo "Direct Challan Cart Item Quantity Increased!!!";
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
		    	$_SESSION["stc_direct_challan_sess"][] = $item_array;  
		    	echo "Direct Challan Created & Item Added to Cart!!!";
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
			$_SESSION["stc_direct_challan_sess"][] = $item_array;  
		     echo "Item Added to Direct Challan Cart!!!";
		}  
    }  
}

// show direct challan cart
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
		if(!empty($_SESSION["stc_direct_challan_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_direct_challan_sess"] as $keys => $values) {  
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
						  <a data-product_id="'.$values["product_id"].'" class="stcqtydc" href="#"><i class="fas fa-arrow-right"></i></a>
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
						  <a data-product_id="'.$values["product_id"].'" class="stcratedc" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
						<td> '.$values["product_gst"].'% </td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount + $gstamount, 2).'</td>
						<td><a style="color: red;font-size:20px;" href="#" id="'.$values["product_id"].'" class="stcdeldcbtn"><i class="fas fa-trash"></i></a></td>
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
             		  	class="stcdcc"
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
				  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.round($total + $totalgst+ $tcs, 2).'</span></td>
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

// direct challan cart qty change
if(isset($_POST["sale_quantity_action"])){  
  	foreach($_SESSION["stc_direct_challan_sess"] as $keys => $values){  
	    if($_SESSION["stc_direct_challan_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_direct_challan_sess"][$keys]['product_quantity'] = $_POST["quantity"];
    	}  
  	}  
}

// change price of session Sale order
if(isset($_POST["stc_price_action"])){  
  	foreach($_SESSION["stc_direct_challan_sess"] as $keys => $values){  
	    if($_SESSION["stc_direct_challan_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_direct_challan_sess"][$keys]['product_sale_price'] = $_POST["price"];
    	}  
  	}  
}

// delete from direct challan cart of session Sale order
if(isset($_POST['stcdelsalelinei'])){  
     foreach($_SESSION["stc_direct_challan_sess"] as $keys => $values){  
          if($values["product_id"] == $_POST["product_id"]){  
               unset($_SESSION["stc_direct_challan_sess"][$keys]);  
               echo "Product Removed!!!";  
          }  
     }  
}

// calculate charges of direct challan
if(isset($_POST['do_plus_minus_on_sale'])){
	$fc=$_POST['freightcharge'];
	$pf=$_POST['packingandforwardingcharge'];
	$gt=$_POST['grand_total'];

	$gstfc=($fc * 18)/100;
	$gstpf=($pf * 18)/100;

	$out=($gstfc + $fc) + ($gstpf + $pf) + $gt;
	echo $out;
}

// save Direct Challan
if(isset($_POST['save_dc_action'])){
	$out					=	'';
	$customer_id			=	$_POST['customer_id'];
	$sale_custorderdate		=	$_POST['sale_custorderdate'];
	$sale_custordernumber	=	$_POST['sale_custordernumber'];
	$sale_mername			=	$_POST['sale_mername'];
	$sale_billinvono		=	$_POST['sale_billinvono'];
	$sale_billinvodate		=	$_POST['sale_billinvodate'];
	$sale_stcfc				=	$_POST['sale_stcfc'];
	$sale_stcpf				=	$_POST['sale_stcpf'];
	$order_waybillno		=	$_POST['sale_waybillno'];
	$order_lrno				=	$_POST['sale_lrno'];
	$order_suppdate			=	$_POST['sale_supplydate'];
	$order_suppplace		=	$_POST['sale_supplyplace'];
	$order_refrence			=	$_POST['sale_refrence'];
	$order_sitename			=	$_POST['sale_sitename'];
	$order_contperson		=	$_POST['sale_contperson'];
	$order_contnumber		=	$_POST['sale_contnumber'];
	$order_shipadd			=	$_POST['sale_shipaddress'];
	$order_tandc			=	$_POST['order_tandc'];
	$order_notes			=	$_POST['order_notes'];

	
	$fridaysale=new aetherSale();
	$fridaysaleitems=new aetherSale();
	$fridaychecksales=new aetherSale();
	$outfridaychecksales=$fridaychecksales->stc_check_sale_bill($sale_mername, $sale_billinvono, $sale_billinvodate);
	if(empty($sale_custorderdate) || empty($sale_custordernumber)){
		$out="Please Fill All Fields!!!";
	}elseif($outfridaychecksales=="yes"){
		$out="This bill is already in records!!!";
	}else{
		$objfridaygrn=$fridaysale->sale_process(
			$customer_id, 
			$sale_custorderdate, 
			$sale_custordernumber,
			$sale_mername, 
			$sale_billinvono,
			$sale_billinvodate,
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
			unset($_SESSION['stc_direct_challan_sess']);
		}else{
			$out .= "Something went Wrong Please Check & Try Again!!!";
		}
	}
	echo $out;
}

// add challan items to direct challan product_id
if(isset($_POST['search_edit_sale_pd_name_in'])){
	$searchme=$_POST['search_edit_sale_pd_name_in'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_edit_search_product($searchme);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// edit challan and add product to databases
if(isset($_POST['edit_direct_challan'])){
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
if(isset($_POST['updatedirectchallanqty'])){
	$challan_id=$_POST['challanid'];
	$challan_pd_qty=$_POST['product_qty'];
	$inward_qty=$_POST['inward_qty'];
	$reverseqty=0;
	$outobjlokiinv='';
	$objloki=new aetherSale();
	$objlokiinv=new aetherSale();

	$objlokiout=$objloki->stc_edit_updateqty_product_in_challan($challan_id, $challan_pd_qty);
	echo $objlokiout;
}

// price change 
if(isset($_POST['updatediectchallanprice'])){
	$challan_id=$_POST['soid'];
	$challan_pd_price=$_POST['product_price'];

	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_edit_updateprice_product_in_challan($challan_id, $challan_pd_price);
	echo json_encode($objlokiout);
}

// purchase price change 
if(isset($_POST['updatediectchallanpurprice'])){
	$challan_id=$_POST['soid'];
	$challan_pd_price=$_POST['product_price'];

	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_edit_updatepurchaseprice_product_in_challan($challan_id, $challan_pd_price);
	echo json_encode($objlokiout);
}

// delete products from challan
if(isset($_POST['deldchallanli'])){
	$challan_id=$_POST['soid'];
	$challan_delqty=$_POST['delqty'];

	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_edit_delete_product_in_challan($challan_id, $challan_delqty);
	echo json_encode($objlokiout);
}

if(isset($_POST['edit_save_direct_challan'])){
	$esono               	=	$_POST['esono'];
	$esomerchantid       	=	$_POST['esomerchantid'];
	$esocustorderno      	=	$_POST['esocustorderno']; 
    $esomername				=	$_POST['esomername'];  
    $esobillinvono			=	$_POST['esobillinvono'];  
    $esobillinvodate		=	$_POST['esobillinvodate'];
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
	$objhela=new aetherSale();

	if($esomerchantid=='Please Select Customer'){
		$objlokiout=$objloki="Please select customer first!!!";
	}elseif($objhela=="yes"){
		$objlokiout="This bill is already in records!!!";
	}else{
		$objlokiout=$objloki->stc_edit_save_challan(
			$esono,
			$esomerchantid,
			$esocustorderno,  
        	$esomername,  
        	$esobillinvono,  
        	$esobillinvodate,
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
	$directchallanbegdate=date("Y-m-d h:i:s", strtotime($_POST['directchallanbegdate']));
	$directchallanenddate=date("Y-m-d h:i:s", strtotime($_POST['directchallanenddate']));

	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_filter_challan($search, $directchallanbegdate, $directchallanenddate);

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
	// echo $out;
}
?>