<?php
session_start();
include "../../MCU/obdb.php";
class odin extends tesseract{	
	// call all agents order records
	public function stc_call_ag_order(){
		$lokiout='
				<table class="table table-hover form-group call-order">
                	<thead>
                		<th>Order No</th>
                		<th>Date</th>
                		<th>For</th>
                		<th>From</th>
                		<th>Value</th>
                		<th>Reference</th>
                		<th>Status</th>
                		<th>View</th>
                	</thead>
                	<tbody>
		';
		$lokigetorder=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_agent_order`
			INNER JOIN `stc_customer`
			ON `stc_agent_order_cust_id`=`stc_customer_id`
			INNER JOIN `stc_agents`
			ON `stc_agent_order_agent_id`=`stc_agents_id`
			ORDER BY `stc_agent_order_id` DESC
			LIMIT 0,15
		");
		$do_action=mysqli_num_rows($lokigetorder);
		if($do_action == 0){
			$lokiout .= "<tr><td colspan='7' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($lokigetorder as $lokivalue) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_agent_order_items` WHERE `stc_agent_order_items_order_id`='".$lokivalue["stc_agent_order_id"]."'
				");
				$agamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$agiqty=$row2nd['stc_agent_order_items_pd_qty'];
					$agirate=$row2nd['stc_agent_order_items_pd_price'];
					$agamount+=$agiqty * $agirate;
				}

				$lokiout.='
	            		<tr>
	            			<td>STC/A/'.substr("0000{$lokivalue["stc_agent_order_id"]}", -5).'</td>
	            			<td>'.date('d-m-Y',strtotime($lokivalue["stc_agent_order_date"])).'</td>
	            			<td>'.$lokivalue["stc_customer_name"].'</td>
	            			<td>'.$lokivalue["stc_agents_name"].'</td>
	            			<td><p align="right"><i class="fa fa-rupee-sign"></i> <b>'.number_format($agamount, 2).'</b></p></td>
	            			<td>'.$lokivalue["stc_agent_order_cust_site_name"].'</td>
	            			<td>'.$lokivalue["stc_agent_order_status"].'</td>
	            			<td><a class="stc_view_order" href="#" id="'.$lokivalue["stc_agent_order_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
	            		</tr>
				';
			}
		}
		$lokiout.='
            		</tbody>
            	</table>

		';
		return $lokiout;
	}

	// call agents order records perticular
	public function stc_view_agents_orders($stc_agorder_id){
		$odin='';
		$order_line_items='';
		$dispatchvalue=0;
		$lokinestedqueryfororderdispatch='';
		$gocartqty=0;
		$zinventory=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_agent_order`
			LEFT JOIN `stc_customer`
			ON `stc_agent_order_cust_id` =`stc_customer_id`
			LEFT JOIN `stc_agents`
			ON `stc_agent_order_agent_id` =`stc_agents_id`
			WHERE `stc_agent_order_id`='".$stc_agorder_id."'
		");
		$stc_purchase_product_items_view_fedit_rec=mysqli_fetch_assoc($check_loki);
		$get_view_fedit_purchase_product_id=substr("0000{$stc_purchase_product_items_view_fedit_rec['stc_agent_order_id']}", -5);
		$get_view_fedit_purchase_merchant_name=$stc_purchase_product_items_view_fedit_rec['stc_customer_name'];
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "Something went Wrong!!";
			header('location:index.php');
		}else{
			$nested_check_loki=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_agent_order_items`
				LEFT JOIN `stc_product`
				ON `stc_agent_order_items_pd_id` =`stc_product_id`
				LEFT JOIN `stc_item_inventory`
				ON `stc_item_inventory_pd_id` =`stc_product_id`
				WHERE `stc_agent_order_items_order_id`='".$stc_agorder_id."'
			");
			$order_line_items .= '  
					<table class="table table-hover" align="centre">
						<thead>
						  <tr>
						    <th scope="col">#</th>
						    <th scope="col" style="width: 20%;">Items</th>
						    <th scope="col">HSN Code</th> 
						    <th scope="col">Unit</th>
						    <th scope="col">Ordered Qty</th>
						    <th scope="col">Inventory</th>  
						    <th scope="col">Remains Qty</th>
						    <th scope="col">Price</th>
						    <th scope="col">For Challan</th>
						    <th scope="col">For PO</th>
						  </tr>
						</thead>
						<tbody>
			';  
			if(!empty($nested_check_loki)){ 
				$slno= 0; 
				$total = 0;  
				$totalgst = 0;
				foreach($nested_check_loki as $keys => $values) {  
					$lokinestedqueryfororderdispatch=mysqli_query($this->stc_dbs, "
						SELECT `stc_sale_product_items_product_qty`,`stc_sale_product_items_product_sale_rate` FROM `stc_sale_product`
						INNER JOIN `stc_sale_product_items`
						ON `stc_sale_product_id`=`stc_sale_product_items_sale_product_id`
						WHERE `stc_sale_product_order_id`='".$values["stc_agent_order_items_order_id"]."'
						AND `stc_sale_product_items_product_id`='".$values["stc_agent_order_items_pd_id"]."'
					");
					foreach($lokinestedqueryfororderdispatch as $lokioutdispatch){
						$dispatchvalue+=$lokioutdispatch['stc_sale_product_items_product_qty'];
					}

					$challanclick='
						<a href="#" id="'.$values["stc_product_id"].'" style="font-size:40px;text-align:center;color:#fff;" class="btn btn-success stcagordgochcart">
							<i class="fas fa-check-circle"></i>
						</a>
					';
					$poclick='
						<a href="#" id="'.$values["stc_product_id"].'" style="font-size:40px;text-align:center;color:#fff;" class="btn btn-danger stcagordgopocart">
							<i class="fas fa-check-circle"></i>
						</a>
					';
					if(empty($values["stc_item_inventory_pd_qty"])){
						$challanclick='';
					}

					if(!empty($values["stc_item_inventory_pd_qty"])){
						$poclick='';
					}
					
					$amount=$values["stc_agent_order_items_pd_qty"] * $values["stc_agent_order_items_pd_price"];
					$gstamount = ($amount * $values["stc_product_gst"])/100;
					$slno++;
					if($values["stc_agent_order_items_pd_qty"]!=$dispatchvalue){

						if($values["stc_item_inventory_pd_qty"]==0){
							$zinventory='<span style="background:#dc3545;padding:2px;border-radius:50%;">'.number_format($values["stc_item_inventory_pd_qty"], 2).'</span>';
						}else{
							$zinventory=number_format($values["stc_item_inventory_pd_qty"], 2);
						}
						$remainsval=$dispatchvalue - $values["stc_agent_order_items_pd_qty"];
						$absremains=abs($remainsval);
						$order_line_items .= ' 
							<tr>
								<td>'.$slno.'</td>
								<td>'.$values["stc_product_name"].'</td>
								<td>'.$values["stc_product_hsncode"].'</td>
								<td>'.$values["stc_product_unit"].'</td>
								<td align="right">'.number_format($values["stc_agent_order_items_pd_qty"], 2).'</td>							
								<td align="right">'.$zinventory.'</td>
								<td align="right">'.number_format($absremains, 2).'
									<input type="hidden" 
										id="inv-qty"
										class="inv-qty'.$values["stc_product_id"].'" 
										value="'.number_format($values["stc_item_inventory_pd_qty"], 2).'"
									>
									<input type="hidden" 
										class="disp-qty'.$values["stc_product_id"].'" 
										value="'.$absremains.'"
									>
								</td>
								<td align="right">
									<i class="fas fa-rupee-sign"></i> 
									<input type="hidden" 
										class="pd-price'.$values["stc_product_id"].'" 
										value="'.$values["stc_agent_order_items_pd_price"].'"
									>
										'.number_format($values["stc_agent_order_items_pd_price"], 2).'
								</td>
								<td>'.$challanclick.'</td>
								<td>'.$poclick.'</td>
							</tr>
						';  
					}else{
						$order_line_items .= ' 
							<tr>
								<td>'.$slno.'</td>
								<td>'.$values["stc_product_name"].'</td>
								<td>'.$values["stc_product_hsncode"].'</td>
								<td>'.$values["stc_product_unit"].'</td>
								<td align="right">'.number_format($values["stc_agent_order_items_pd_qty"], 2).'</td>							
								<td align="right">'.number_format($values["stc_item_inventory_pd_qty"], 2).'</td>
								<td align="right">'.number_format($dispatchvalue, 2).'</td>
								<td colspan="3"><h4>This Item Is Cleared!!!</h4></td>
							</tr>
						'; 
					}
					$total = $total + ($values["stc_agent_order_items_pd_qty"] * $values["stc_agent_order_items_pd_price"]) ;  
					$totalgst +=$gstamount;

					$dispatchvalue=0;
				}  
				$gstamount = ($amount * $values["stc_product_gst"])/100;
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
			'order_number' => $get_view_fedit_purchase_product_id,
			'real_order_number' => $stc_purchase_product_items_view_fedit_rec['stc_agent_order_id'],
			'order_date' => date('d-m-Y h:i:s', strtotime($stc_purchase_product_items_view_fedit_rec['stc_agent_order_date'])),
			'customer_name' => $get_view_fedit_purchase_merchant_name,
			'agentname' => $stc_purchase_product_items_view_fedit_rec['stc_agents_id'].'-'.$stc_purchase_product_items_view_fedit_rec['stc_agents_name'],
			'sitename' => $stc_purchase_product_items_view_fedit_rec['stc_agent_order_cust_site_name'],	
			'siteaddress' => $stc_purchase_product_items_view_fedit_rec['stc_agent_order_cust_site_address'],
			'line_items' => $order_line_items,
			'project_id' => $stc_purchase_product_items_view_fedit_rec['stc_agent_order_proj_id'],
			'supervisor_id' => $stc_purchase_product_items_view_fedit_rec['stc_agent_order_super_id'],
			'requis_id' => $stc_purchase_product_items_view_fedit_rec['stc_agent_order_requis_id']
		);
		return $odin;
	}

	// call count order
	public function call_rack(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_rack`");
		$odin='<option value="0" selected>Select Rack</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option selected>No Rack Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_rack_id"].'">'.$row["stc_rack_name"].'</option>		               	
		            	';				
			}
		}
		return $odin;
	}

	// save order to challan
	public function stc_ag_go_to_challan($challan_id, $stc_ch_notes, $stc_ch_tandc, $stc_ch_waybilno, $stc_ch_lrno){
		$heimdall='';
		$getinfoheimdall=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_agent_order` 
			INNER JOIN `stc_agents` 
			ON `stc_agent_order_agent_id`=`stc_agents_id` 
			WHERE `stc_agent_order_id`='".$challan_id."'
		");
		$fetchinfoheimdall=mysqli_fetch_assoc($getinfoheimdall);

		$goinfoheimdall=mysqli_query($this->stc_dbs, "
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
				'".$fetchinfoheimdall['stc_agent_order_cust_id']."', 
				'S-B-".$fetchinfoheimdall['stc_agent_order_id']."', 
				'".$fetchinfoheimdall['stc_agent_order_date']."', 
				'0',
				'".$stc_ch_waybilno."',
				'".$stc_ch_lrno."',
				'NA', 
				'NA',
				'NA',
				'".$stc_ch_notes."',
				'S-B',
				'NA',
				'NA',
				'".$fetchinfoheimdall['stc_agent_order_cust_site_name']."',
				'".$fetchinfoheimdall['stc_agent_order_cust_site_address']."',
				'".$fetchinfoheimdall['stc_agents_name']."',
				'".$fetchinfoheimdall['stc_agents_contact']."',
				'".$stc_ch_tandc."',
				'challaned',
				'".$fetchinfoheimdall['stc_agent_order_id']."',
				'".$fetchinfoheimdall['stc_agent_order_agent_id']."',
				'".$_SESSION['stc_admin_info_id']."'
			)
		");

		if($goinfoheimdall){
			$heimdall.='A';
		}else{
			$heimdall.='B';

		}
		return $heimdall;
	}

	// save items to challan & minus inventory
	public function stc_ag_go_to_challan_items(){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT MAX(`stc_sale_product_id`) AS spid FROM `stc_sale_product` ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['spid'];

		if(empty($_SESSION['stc_accepted_ag_require'])){
			$odin = "Do not leave Items on Sale Order!!!";
		}else{
			foreach ($_SESSION["stc_accepted_ag_require"] as $key=>$value) {
				$loki_get_max_price=mysqli_query($this->stc_dbs, "
					SELECT MAX(`stc_product_grn_items_rate`) AS pdmaxrate FROM `stc_product_grn_items` WHERE `stc_product_grn_items_product_id`='".$value['product_id']."'
				");
				$stc_fetch_loki_get_max_price=mysqli_fetch_assoc($loki_get_max_price);
				$finallylokimaxprice=$stc_fetch_loki_get_max_price['pdmaxrate'];

		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_sale_product_items`(
		    			`stc_sale_product_items_sale_product_id`, 
		    			`stc_sale_product_items_product_id`, 
		    			`stc_sale_product_items_product_qty`, 
		    			`stc_sale_product_items_product_rate`, 
		    			`stc_sale_product_items_product_sale_rate`
		    		) VALUES (
		    			'".$get_purchase_product_id."', 
		    			'".$value['product_id']."',
		    			'".$value['product_quantity']."', 
		    			'".$finallylokimaxprice."',
		    			'".$value['product_price']."'
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
			    		UPDATE `stc_item_inventory` SET `stc_item_inventory_pd_qty`='".$finalqty."' WHERE `stc_item_inventory_pd_id`='".$value['product_id']."'
			    	");
			    }
		    }
		    $odin = "Challan Created!!!";
		}
		return $odin;
	}

	// filter agent order
	public function stc_filter_ag_ord($search){
		$lokiout='
				<table class="table table-hover form-group call-order">
                	<thead>
                		<th>Order No</th>
                		<th>Date</th>
                		<th>For</th>
                		<th>From</th>
                		<th>Value</th>
                		<th>Reference</th>
                		<th>Status</th>
                		<th>View</th>
                	</thead>
                	<tbody>
		';
		$lokigetorder=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_agent_order`
			INNER JOIN `stc_customer`
			ON `stc_agent_order_cust_id`=`stc_customer_id`
			INNER JOIN `stc_agents`
			ON `stc_agent_order_agent_id`=`stc_agents_id`
			WHERE `stc_agent_order_id` REGEXP '".$search."'
			OR `stc_customer_name` REGEXP '".$search."'
			OR `stc_agents_name` REGEXP '".$search."'
			OR `stc_agent_order_cust_site_name` REGEXP '".$search."'
			OR `stc_agent_order_status` REGEXP '".$search."'
			ORDER BY `stc_agent_order_id` DESC LIMIT 0,50
		");
		$do_action=mysqli_num_rows($lokigetorder);
		if($do_action == 0){
			$lokiout .= "<tr><td colspan='7' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($lokigetorder as $lokivalue) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_agent_order_items` WHERE `stc_agent_order_items_order_id`='".$lokivalue["stc_agent_order_id"]."'
				");
				$grnamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$grniqty=$row2nd['stc_agent_order_items_pd_qty'];
					$grnirate=$row2nd['stc_agent_order_items_pd_price'];
					$grnamount+=$grniqty * $grnirate;
				}

				$lokiout.='
				
	            			<tr>
	            				<td>STC/A/'.substr("0000{$lokivalue["stc_agent_order_id"]}", -5).'</td>
	            				<td>'.date('d-m-Y',strtotime($lokivalue["stc_agent_order_date"])).'</td>
	            				<td>'.$lokivalue["stc_customer_name"].'</td>
	            				<td>'.$lokivalue["stc_agents_name"].'</td>
	            				<td><p align="right"><i class="fa fa-rupee-sign"></i> <b>'.number_format($grnamount, 2).'</b></p></td>
	            				<td>'.$lokivalue["stc_agent_order_cust_site_name"].'</td>
	            				<td>'.$lokivalue["stc_agent_order_status"].'</td>
	            				<td><a class="stc_view_order" href="#" id="'.$lokivalue["stc_agent_order_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
	            			</tr>
				';
			}
		}
		$lokiout.='
            		</tbody>
            	</table>

		';
		return $lokiout;
	}

	// change status
	public function stc_ag_status($stc_order_no){
		$loki='';
		$lokigo=mysqli_query($this->stc_dbs, "
			UPDATE `stc_agent_order` 
			SET `stc_agent_order_status`='CLEANED'
			WHERE `stc_agent_order_id`='".$stc_order_no."'
		");
		if($lokigo){
			$loki='Order Cleaned!!!';
		}
		return $loki;
	}

	// get notification
	public function stc_get_ag_not(){
		$loki=mysqli_query($this->stc_dbs, "SELECT `stc_agent_order_id` FROM `stc_agent_order` WHERE `stc_agent_order_status`='PROCESS'");
		$countloki=mysqli_num_rows($loki);
		return $countloki;
	}

	// add to po cart
	public function stc_ag_add_to_po($pdid, $remqty){
		$loki='';
		$loki_get_info=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_product` WHERE `stc_product_id`='".$pdid."'");
		$loki_info=mysqli_fetch_assoc($loki_get_info);

		$loki_get_price=mysqli_query($this->stc_dbs, "
			SELECT MIN(`stc_purchase_product_items_rate`) AS pdprice FROM `stc_purchase_product_items` WHERE `stc_purchase_product_items_product_id`='".$pdid."'
		");
		$loki_price=mysqli_fetch_assoc($loki_get_price);

		if(isset($_SESSION["stc_purchase_order_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_purchase_order_sess"] as $keys => $values) {  
				if($_SESSION["stc_purchase_order_sess"][$keys]['product_id'] == $_POST["product_id"]) {  
				     $is_available++;  
				     // $_SESSION["stc_purchase_order_sess"][$keys]['product_quantity'] = $_SESSION["stc_purchase_order_sess"][$keys]['product_quantity'] + $_POST["product_quantity"];  
				     $loki.="Item Already In A Cart!!!!!!";
				}  
			}  
		    if($is_available < 1) {  
		    	$item_array = array(  
		    	     'product_id'             =>     $loki_info["stc_product_id"],  
		    	     'product_name'           =>     $loki_info["stc_product_name"],  
		    	     'product_unit'           =>     $loki_info["stc_product_unit"],  
		    	     'product_quantity'       =>     $remqty,
		    	     'product_hsncode'        =>     $loki_info["stc_product_hsncode"],
		    	     'product_gst'       	  =>     $loki_info["stc_product_gst"],
		    	     'product_price'          =>     $loki_price["pdprice"]  
		    	);  
		    	$_SESSION["stc_purchase_order_sess"][] = $item_array;  
		    	$loki.="Item Added to PO Cart!!!";
		    }  
		}else{  
			$item_array = array(  
				'product_id'             =>     $loki_info["stc_product_id"],  
		    	'product_name'           =>     $loki_info["stc_product_name"],  
		    	'product_unit'           =>     $loki_info["stc_product_unit"],  
		    	'product_quantity'       =>     $remqty,
		    	'product_hsncode'        =>     $loki_info["stc_product_hsncode"],
		    	'product_gst'       	 =>     $loki_info["stc_product_gst"],
		    	'product_price'          =>     $loki_price["pdprice"]   
			);   
			$_SESSION["stc_purchase_order_sess"][] = $item_array;  
		     $loki.="PO Cart Created & Item Added to PO Cart!!!";
		}
		return $loki;
	}

	// goto recieve table agent supervisor
	public function stc_go_items_on_sup_ag_rec($stc_requis){
		$out='';
		foreach($_SESSION['stc_accepted_ag_require'] as $recrow){
			$recqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_super_requisition_rec_items`(
					`stc_cust_super_requisition_rec_items_sup_req_id`, 
					`stc_cust_super_requisition_rec_items_product_id`, 
					`stc_cust_super_requisition_rec_items_product_qty`
				) VALUES (
					'".$stc_requis."',
					'".$recrow['product_id']."',
					'".$recrow['product_quantity']."'
				)
			");
		}
		return $out;
	}
}

class thanos extends tesseract{
	// call all agents order records
	public function stc_call_ag_requisition(){
		$lokiout='
				<table class="table table-hover form-group call-order">
                	<thead>
                		<th>Requisition ID</th>
                    	<th>Requisition Date</th>
                    	<th>Requisition For</th>
                    	<th>Requisition Accepted By</th>
                    	<th>Requisition Status</th>
                    	<th>View</th>
                	</thead>
                	<tbody>
		';
		$requissuperqry=mysqli_query($this->stc_dbs, "
    	   	SELECT DISTINCT 
				`stc_requisition_combiner_id`,
				`stc_requisition_combiner_date`,
			    `stc_requisition_combiner_refrence`,
			    `stc_agents_name`,
				`stc_requisition_combiner_status`
			FROM `stc_requisition_combiner`
			INNER JOIN `stc_requisition_combiner_req` 
			ON `stc_requisition_combiner_id`=`stc_requisition_combiner_req_comb_id`
			INNER JOIN `stc_cust_super_requisition_list` 
			ON `stc_cust_super_requisition_list_id`=`stc_requisition_combiner_req_requisition_id`
			INNER JOIN `stc_agents` 
			ON `stc_agents_id`=`stc_requisition_combiner_agent_id`
    	    WHERE `stc_requisition_combiner_status`='1'
    	    ORDER BY `stc_requisition_combiner_id` DESC
    	");
    	if(mysqli_num_rows($requissuperqry)!=0){
    	    foreach($requissuperqry as $requisrow){
    	    	$reqcomstatus='';
    	    	if($requisrow['stc_requisition_combiner_status']==1){$reqcomstatus="PROCESS";}else{$reqcomstatus="ACCEPTED";}
    	        $lokiout.= '
    	            <tr>
    	                <td>'.$requisrow['stc_requisition_combiner_id'].'</td>
    	                <td>'.$requisrow['stc_requisition_combiner_date'].'</td>
    	                <td>'.$requisrow['stc_requisition_combiner_refrence'].'</td>
    	                <td>'.$requisrow['stc_agents_name'].'</td>
    	                <td>'.$reqcomstatus.'</td>
    	                <td>
    	                	<a href="stc-requisition-combiner-fsale.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" style="font-size: 25px;color: white;">
    	                		<i class="fa fa-pen-square" aria-hidden="true"></i>
    	                	</a>
    	                	<a href="stc-requisition-combiner-fshow.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" style="font-size: 25px;color: white;">
    	                		<i class="fa fa-print" aria-hidden="true"></i>
    	                	</a>
    	                </td>
    	            </tr>
    	        ';
    	    }
    	}else{
    	        $lokiout.= '
    	            <tr>
    	                <td colspan="6">
    	                    <b>No Requisition Found!!!</b>
    	                </td>
    	            </tr>
    	        ';
    	}
		return $lokiout;
	}

	public function stc_call_ag_requisition_search($querybox){
		$lokiout='
				<table class="table table-hover form-group call-order">
                	<thead>
                		<th>Requisition ID</th>
                    	<th>Requisition Date</th>
                    	<th>Requisition For</th>
                    	<th>Requisition Accepted By</th>
                    	<th>Requisition Status</th>
                    	<th>View</th>
                	</thead>
                	<tbody>
		';
		$requissuperqry=mysqli_query($this->stc_dbs, "
    	   	SELECT DISTINCT 
				`stc_requisition_combiner_id`,
				`stc_requisition_combiner_date`,
			    `stc_requisition_combiner_refrence`,
			    `stc_agents_name`,
				`stc_requisition_combiner_status`
			FROM `stc_requisition_combiner`
			INNER JOIN `stc_requisition_combiner_req` 
			ON `stc_requisition_combiner_id`=`stc_requisition_combiner_req_comb_id`
			INNER JOIN `stc_cust_super_requisition_list` 
			ON `stc_cust_super_requisition_list_id`=`stc_requisition_combiner_req_requisition_id`
			INNER JOIN `stc_agents` 
			ON `stc_agents_id`=`stc_requisition_combiner_agent_id`
    	    WHERE `stc_requisition_combiner_status`='1'
    	    AND(
    	    	`stc_requisition_combiner_refrence` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $querybox)."'
    	    	OR `stc_agents_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $querybox)."'
    	    	OR `stc_requisition_combiner_id` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $querybox)."'
    	    )
    	    ORDER BY `stc_requisition_combiner_id` DESC
    	");
    	if(mysqli_num_rows($requissuperqry)!=0){
    	    foreach($requissuperqry as $requisrow){
    	    	$reqcomstatus='';
    	    	if($requisrow['stc_requisition_combiner_status']==1){$reqcomstatus="PROCESS";}else{$reqcomstatus="ACCEPTED";}
    	        $lokiout.= '
    	            <tr>
    	                <td>'.$requisrow['stc_requisition_combiner_id'].'</td>
    	                <td>'.$requisrow['stc_requisition_combiner_date'].'</td>
    	                <td>'.$requisrow['stc_requisition_combiner_refrence'].'</td>
    	                <td>'.$requisrow['stc_agents_name'].'</td>
    	                <td>'.$reqcomstatus.'</td>
    	                <td>
    	                	<a href="stc-requisition-combiner-fsale.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" style="font-size: 25px;color: white;">
    	                		<i class="fa fa-pen-square" aria-hidden="true"></i>
    	                	</a>
    	                	<a href="stc-requisition-combiner-fshow.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" style="font-size: 25px;color: white;">
    	                		<i class="fa fa-print" aria-hidden="true"></i>
    	                	</a>
    	                </td>
    	            </tr>
    	        ';
    	    }
    	}else{
    	        $lokiout.= '
    	            <tr>
    	                <td colspan="6">
    	                    <b>No Requisition Found!!!</b>
    	                </td>
    	            </tr>
    	        ';
    	}
		return $lokiout;
	}

	// call perticular 
	public function stc_show_requisition_list($url_param){
		$lokiout='
				<table class="table table-hover form-group call-order">
                	<thead>
                		<th>Requisition ID</th>
                    	<th>Requisition Date</th>
                    	<th>Requisition From</th>
                    	<th>Requisition For</th>
                    	<th>Requisition Accepted By</th>
                    	<th>Requisition Status</th>
                    	<th>View</th>
                	</thead>
                	<tbody>
		';
		$requissuperqry=mysqli_query($this->stc_dbs, "
    	    SELECT 
    	        `stc_cust_super_requisition_list_id`,
    	        `stc_cust_super_requisition_list_date`,
    	        `stc_cust_project_title`,
    	        `stc_cust_pro_supervisor_fullname`,
                `stc_agents_name`,
    	        `stc_cust_super_requisition_list_status`
    	    FROM `stc_cust_super_requisition_list`
    	    INNER JOIN `stc_cust_pro_supervisor` 
    	    ON `stc_cust_pro_supervisor_id`=`stc_cust_super_requisition_list_super_id`
    	    INNER JOIN `stc_cust_project` 
    	    ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id`
    	    INNER JOIN `stc_agents` 
    	    ON `stc_agents_id`=`stc_cust_pro_supervisor_created_by`
    	    INNER JOIN `stc_requisition_combiner_req`
    	    ON `stc_requisition_combiner_req_requisition_id`=`stc_cust_super_requisition_list_id`
    	    INNER JOIN `stc_requisition_combiner`
    	    ON `stc_requisition_combiner_req_comb_id`=`stc_requisition_combiner_id`
    	    WHERE `stc_cust_super_requisition_list_status`!='1'
    	    AND `stc_requisition_combiner_id`='".mysqli_real_escape_string($this->stc_dbs, $url_param)."'
    	    ORDER BY `stc_cust_super_requisition_list_id` DESC
    	");
    	if(mysqli_num_rows($requissuperqry)!=0){
    	    foreach($requissuperqry as $requisrow){
    	        $lokiout.= '
    	            <tr>
    	                <td>'.$requisrow['stc_cust_super_requisition_list_id'].'</td>
    	                <td>'.$requisrow['stc_cust_super_requisition_list_date'].'</td>
    	                <td>'.$requisrow['stc_cust_pro_supervisor_fullname'].'</td>
    	                <td>'.$requisrow['stc_cust_project_title'].'</td>
    	                <td>'.$requisrow['stc_agents_name'].'</td>
    	                <td>'.$requisrow['stc_cust_super_requisition_list_status'].'</td>
    	                <td><a class="stc_view_requist" href="#" id="'.$requisrow["stc_cust_super_requisition_list_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
    	            </tr>
    	        ';
    	    }
    	}else{
    	        $lokiout.= '
    	            <tr>
    	                <td colspan="6">
    	                    <b>No Requisition Found!!!</b>
    	                </td>
    	            </tr>
    	        ';
    	}
		return $lokiout;
	}

	public function stc_show_requisition_list_search($url_param, $listcontains){
		$lokiout='
				<table class="table table-hover form-group call-order">
                	<thead>
                		<th>Requisition ID</th>
                    	<th>Requisition Date</th>
                    	<th>Requisition From</th>
                    	<th>Requisition For</th>
                    	<th>Requisition Accepted By</th>
                    	<th>Requisition Status</th>
                    	<th>View</th>
                	</thead>
                	<tbody>
		';
		$requissuperqry=mysqli_query($this->stc_dbs, "
    	    SELECT 
    	        `stc_cust_super_requisition_list_id`,
    	        `stc_cust_super_requisition_list_date`,
    	        `stc_cust_project_title`,
    	        `stc_cust_pro_supervisor_fullname`,
                `stc_agents_name`,
    	        `stc_cust_super_requisition_list_status`
    	    FROM `stc_cust_super_requisition_list`
    	    INNER JOIN `stc_cust_pro_supervisor` 
    	    ON `stc_cust_pro_supervisor_id`=`stc_cust_super_requisition_list_super_id`
    	    INNER JOIN `stc_cust_project` 
    	    ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id`
    	    INNER JOIN `stc_agents` 
    	    ON `stc_agents_id`=`stc_cust_pro_supervisor_created_by`
    	    WHERE `stc_cust_super_requisition_list_status`!='1'
    	    AND (
    	    	`stc_cust_super_requisition_list_id`='".$listcontains."'
    	    	OR `stc_cust_pro_supervisor_fullname` REGEXP '".$listcontains."'
    	    	OR `stc_cust_project_title` REGEXP '".$listcontains."'
    	    )
    	    ORDER BY `stc_cust_super_requisition_list_id` DESC
    	");
    	if(mysqli_num_rows($requissuperqry)!=0){
    	    foreach($requissuperqry as $requisrow){
    	        $lokiout.= '
    	            <tr>
    	                <td>'.$requisrow['stc_cust_super_requisition_list_id'].'</td>
    	                <td>'.$requisrow['stc_cust_super_requisition_list_date'].'</td>
    	                <td>'.$requisrow['stc_cust_pro_supervisor_fullname'].'</td>
    	                <td>'.$requisrow['stc_cust_project_title'].'</td>
    	                <td>'.$requisrow['stc_agents_name'].'</td>
    	                <td>'.$requisrow['stc_cust_super_requisition_list_status'].'</td>
    	                <td><a class="stc_view_requist" href="#" id="'.$requisrow["stc_cust_super_requisition_list_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
    	            </tr>
    	        ';
    	    }
    	}else{
    	        $lokiout.= '
    	            <tr>
    	                <td colspan="6">
    	                    <b>No Requisition Found!!!</b>
    	                </td>
    	            </tr>
    	        ';
    	}
		return $lokiout;
	}

	public function stc_filter_ag_req($search){
		$lokiout='
				<table class="table table-hover form-group call-order">
                	<thead>
                		<th>Requisition ID</th>
                    	<th>Requisition Date</th>
                    	<th>Requisition From</th>
                    	<th>Requisition For</th>
                    	<th>Requisition Accepted By</th>
                    	<th>Requisition Status</th>
                    	<th>View</th>
                	</thead>
                	<tbody>
		';
		$requissuperqry=mysqli_query($this->stc_dbs, "
    	    SELECT 
    	        `stc_cust_super_requisition_list_id`,
    	        `stc_cust_super_requisition_list_date`,
    	        `stc_cust_project_title`,
    	        `stc_cust_pro_supervisor_fullname`,
                `stc_agents_name`,
    	        `stc_cust_super_requisition_list_status`
    	    FROM `stc_cust_super_requisition_list`
    	    INNER JOIN `stc_cust_pro_supervisor` 
    	    ON `stc_cust_pro_supervisor_id`=`stc_cust_super_requisition_list_super_id`
    	    INNER JOIN `stc_cust_project` 
    	    ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id`
    	    INNER JOIN `stc_agents` 
    	    ON `stc_agents_id`=`stc_cust_pro_supervisor_created_by`
    	    WHERE (
    	    	`stc_cust_project_title` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
	    	    `stc_cust_pro_supervisor_fullname` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
	    	    `stc_agents_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
	    	) AND `stc_cust_super_requisition_list_status`!=1
    	    ORDER BY `stc_cust_super_requisition_list_id` DESC
    	");
    	if(mysqli_num_rows($requissuperqry)!=0){
    	    foreach($requissuperqry as $requisrow){
    	        $lokiout.= '
    	            <tr>
    	                <td>'.$requisrow['stc_cust_super_requisition_list_id'].'</td>
    	                <td>'.$requisrow['stc_cust_super_requisition_list_date'].'</td>
    	                <td>'.$requisrow['stc_cust_pro_supervisor_fullname'].'</td>
    	                <td>'.$requisrow['stc_cust_project_title'].'</td>
    	                <td>'.$requisrow['stc_agents_name'].'</td>
    	                <td>'.$requisrow['stc_cust_super_requisition_list_status'].'</td>
    	                <td><a class="stc_view_requist" href="#" id="'.$requisrow["stc_cust_super_requisition_list_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
    	            </tr>
    	        ';
    	    }
    	}else{
    	        $lokiout.= '
    	            <tr>
    	                <td colspan="6">
    	                    <b>No Requisition Found!!!</b>
    	                </td>
    	            </tr>
    	        ';
    	}
		return $lokiout;
	}

	public function stc_view_agents_requist($stc_agorder_id){
		$odin='';
		$order_line_items='';
		$dispatchvalue=0;
		$lokinestedqueryfororderdispatch='';
		$gocartqty=0;
		$zinventory=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_list_id`,
				`stc_cust_super_requisition_list_date`,
				`stc_agents_id`,
				`stc_customer_name`,
				`stc_cust_super_requisition_list_project_id`,
				`stc_cust_project_title`,
				`stc_cust_project_address`,
				`stc_cust_super_requisition_list_super_id`,
				`stc_agents_name`
			FROM `stc_cust_super_requisition_list` 
			INNER JOIN `stc_cust_project`
			ON `stc_cust_super_requisition_list_project_id`=`stc_cust_project_id`
			INNER JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_super_requisition_list_super_id`=`stc_cust_pro_supervisor_id`
			INNER JOIN `stc_customer`
			ON `stc_cust_project_cust_id`=`stc_customer_id`
			INNER JOIN `stc_agents`
			ON `stc_cust_pro_supervisor_created_by`=`stc_agents_id`
			WHERE `stc_cust_super_requisition_list_id`='".$stc_agorder_id."'
		");
		$stc_purchase_product_items_view_fedit_rec=mysqli_fetch_assoc($check_loki);
		$get_view_fedit_purchase_product_id=substr("0000{$stc_purchase_product_items_view_fedit_rec['stc_cust_super_requisition_list_id']}", -5);
		$get_view_fedit_purchase_merchant_name=$stc_purchase_product_items_view_fedit_rec['stc_customer_name'];
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "Something went Wrong!!";
		}else{
			$nested_check_loki=mysqli_query($this->stc_dbs, "
				SELECT
					`stc_cust_super_requisition_list_id`,
					`stc_cust_super_requisition_list_items_req_id`,
					`stc_cust_super_requisition_list_items_title`,
				    `stc_cust_super_requisition_list_items_unit`,
				    `stc_cust_super_requisition_list_items_approved_qty`
				FROM `stc_cust_super_requisition_list_items` 
				WHERE `stc_cust_super_requisition_list_items_req_id`='".$stc_agorder_id."'
			");
			$order_line_items .= '  
					<table class="table table-hover" align="centre">
						<thead>
						  <tr>
						    <th scope="col">#</th>
						    <th scope="col" style="width: 20%;">Items Desc</th>
						    <th scope="col">Unit</th>
						    <th scope="col">Ordered Qty</th>
						    <th scope="col">Dispatched Qty</th>  
						    <th scope="col">Remains Qty</th>
						  </tr>
						</thead>
						<tbody>
			';  
			if(!empty($nested_check_loki)){ 
				$slno= 0;				
				foreach($nested_check_loki as $dispatchrow){
					$slno++;
					$lokirecqtycheckqry=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_cust_super_requisition_list_items_rec_list_item_id`,
							`stc_product_name`,
							SUM(`stc_cust_super_requisition_list_items_rec_recqty`) as recqty
						FROM `stc_cust_super_requisition_list_items_rec`
						INNER JOIN `stc_product`
						ON `stc_product_id`=`stc_cust_super_requisition_list_items_rec_list_pd_id`
						WHERE
							`stc_cust_super_requisition_list_items_rec_list_item_id`='".$dispatchrow['stc_cust_super_requisition_list_id']."'
					");

					if(mysqli_num_rows($lokirecqtycheckqry)>0){
						foreach($lokirecqtycheckqry as $requistitemrow){
							$order_line_items .= '
								<tr>
									<td>'.$slno.'</td>
									<td>
										'.$dispatchrow["stc_cust_super_requisition_list_items_title"].'
										('.$requistitemrow['stc_product_name'].')
									</td>
									<td>'.$dispatchrow["stc_cust_super_requisition_list_items_unit"].'</td>
									<td>'.number_format($dispatchrow["stc_cust_super_requisition_list_items_approved_qty"], 2).'</td>
									<td>'.number_format($requistitemrow["recqty"], 2).'</td>
									<td colspan="3">
										<h4>
											<a class="req-product-Modal" id="'.$dispatchrow["stc_cust_super_requisition_list_id"].'" list-id="'.$dispatchrow["stc_cust_super_requisition_list_items_req_id"].'" href="#">Edit</a>
										</h4>
									</td>
								</tr>
							';
						}
					}else{
						$order_line_items .= '
							<tr>
								<td>'.$slno.'</td>
								<td>'.$dispatchrow["stc_cust_super_requisition_list_items_title"].'</td>
								<td>'.$dispatchrow["stc_cust_super_requisition_list_items_unit"].'</td>
								<td>'.$dispatchrow["stc_cust_super_requisition_list_items_approved_qty"].'</td>
								<td colspan="3">
									<h4>
										<a class="req-product-Modal" id="'.$dispatchrow["stc_cust_super_requisition_list_id"].'" list-id="'.$dispatchrow["stc_cust_super_requisition_list_items_req_id"].'" href="#">Edit</a>
									</h4>
								</td>
							</tr>
						';
					}
				}
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
			'order_number' => $get_view_fedit_purchase_product_id,
			'real_order_number' => $stc_purchase_product_items_view_fedit_rec['stc_cust_super_requisition_list_id'],
			'order_date' => date('d-m-Y h:i:s', strtotime($stc_purchase_product_items_view_fedit_rec['stc_cust_super_requisition_list_date'])),
			'customer_name' => $get_view_fedit_purchase_merchant_name,
			'agentname' => $stc_purchase_product_items_view_fedit_rec['stc_agents_id'].'-'.$stc_purchase_product_items_view_fedit_rec['stc_agents_name'],
			'sitename' => $stc_purchase_product_items_view_fedit_rec['stc_cust_project_title'],
			'siteaddress' => $stc_purchase_product_items_view_fedit_rec['stc_cust_project_address'],
			'line_items' => $order_line_items,
			'project_id' => $stc_purchase_product_items_view_fedit_rec['stc_cust_super_requisition_list_project_id'],
			'supervisor_id' => $stc_purchase_product_items_view_fedit_rec['stc_cust_super_requisition_list_super_id'],
			'requis_id' => $stc_purchase_product_items_view_fedit_rec['stc_cust_super_requisition_list_id']
		);
		return $odin;
	}

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
					ORDER BY `stc_product_name` ASC 
					LIMIT 0,20
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
					ORDER BY `stc_purchase_product_items_id` DESC
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
						<div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
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
					             	          	name="stcpdreqprice" 
					             	          	id="stcpdreqprice'.$row["stc_product_id"].'" 
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
					             	    		name="stcpdreqperc" 
					             	    		id="stcpdreqperc'.$row["stc_product_id"].'" 
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
						                	  id="stcreqqty'.$row["stc_product_id"].'"
						                	  name="stcreqqty"
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

		             					<a class="cart add_to_requist_cart" href="#" id="'.$row["stc_product_id"].'">
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

	public function stc_show_requisition_product(){
		$gamora='
			<table class="table table-hover" align="centre">
				<thead>
				  <tr>
				    <th scope="col">#</th>
				    <th scope="col" style="width: 20%;">Items</th>
				    <th scope="col">Cat</th> 
				    <th scope="col">Unit</th>
				    <th scope="col">Qty</th>                         
				    <th scope="col">Price</th>
				    <th scope="col">Amount</th>
				    <th scope="col">Action</th>
				  </tr>
				</thead>
				<tbody>
		';
		$slno=0;
		if(empty($_SESSION['stc_cust_requist_cart'])){
			$gamora.='
					<tr>
						<td colspan="8">
							<a 
						      href="#" 
						      class="btn btn-primary btn-block text-uppercase mb-3">
						      Requisition Cart Empty!!!
						    </a>
						</td>
					</tr>
			';
		}else{
			foreach($_SESSION['stc_cust_requist_cart'] as $requisprorow){
				$gamoracallproqry=mysqli_query($this->stc_dbs, "
					SELECT DISTINCT
						`stc_product_name`,
						`stc_cat_name`,
						`stc_product_unit`
					FROM `stc_product`
					INNER JOIN `stc_category`
					ON `stc_cat_id`=`stc_product_cat_id`
					WHERE `stc_product_id`='".$requisprorow['product_id']."'
				");
				foreach($gamoracallproqry as $againrequisprorow){
					$slno++;
					$gamora.='
							<tr>
								<td>'.$slno.'</td>
								<td>'.$againrequisprorow['stc_product_name'].'</td>
								<td>'.$againrequisprorow['stc_cat_name'].'</td>
								<td>'.$againrequisprorow['stc_product_unit'].'</td>
								<td>'.number_format($requisprorow['product_quantity'], 2).'</td>
								<td>'.number_format($requisprorow['product_price'], 2).'</td>
								<td>'.number_format((round($requisprorow['product_quantity'], 2) * round($requisprorow['product_price'], 2)), 2).'</td>
								<td>
									<a 
										style="color: red;font-size:20px;" 
										href="#" 
										id='.$requisprorow["product_id"].' 
										class="stcdelrequibtn"
									><i class="fas fa-trash"></i>
									</a>
								</td>
							</tr>
					';
				}
			}
		}
		$gamora.='
				</tbody>
			</table>
		';
		return $gamora;
	}

	// save order to challan
	public function stc_ag_req_go_to_challan($challan_id, $stc_ch_notes, $stc_ch_tandc, $stc_ch_waybilno, $stc_ch_lrno){
		$heimdall='';
		$getinfoheimdall=mysqli_query($this->stc_dbs, "
			SELECT 
			    `stc_cust_project_cust_id`,
				`stc_cust_super_requisition_list_id`,
			    `stc_cust_super_requisition_list_date`,
			    `stc_cust_project_title`,
			    `stc_cust_project_address`,
			    `stc_agents_name`,
			    `stc_agents_contact`,
			    `stc_cust_project_createdby`
			FROM `stc_cust_super_requisition_list`
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_super_requisition_list_project_id`=`stc_cust_project_id`
			INNER JOIN `stc_agents` 
			ON `stc_agents_id`=`stc_cust_project_createdby`
			WHERE `stc_cust_super_requisition_list_id`='".$challan_id."'
		");
		$fetchinfoheimdall=mysqli_fetch_assoc($getinfoheimdall);

		$goinfoheimdall=mysqli_query($this->stc_dbs, "
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
				'".$fetchinfoheimdall['stc_cust_project_cust_id']."', 
				'S-B-".$fetchinfoheimdall['stc_cust_super_requisition_list_id']."', 
				'".$fetchinfoheimdall['stc_cust_super_requisition_list_date']."', 
				'0',
				'".$stc_ch_waybilno."',
				'".$stc_ch_lrno."',
				'NA', 
				'NA',
				'NA',
				'".$stc_ch_notes."',
				'S-B',
				'NA',
				'NA',
				'".$fetchinfoheimdall['stc_cust_project_title']."',
				'".$fetchinfoheimdall['stc_cust_project_address']."',
				'".$fetchinfoheimdall['stc_agents_name']."',
				'".$fetchinfoheimdall['stc_agents_contact']."',
				'".$stc_ch_tandc."',
				'challaned',
				'".$fetchinfoheimdall['stc_cust_super_requisition_list_id']."',
				'".$fetchinfoheimdall['stc_cust_project_createdby']."',
				'".$_SESSION['stc_admin_info_id']."'
			)
		");

		if($goinfoheimdall){
			$heimdall.='A';
		}else{
			$heimdall.='B';

		}
		return $heimdall;
	}

	// save items to challan & minus inventory
	public function stc_ag_req_go_to_challan_items(){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT MAX(`stc_sale_product_id`) AS spid FROM `stc_sale_product` ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['spid'];

		if(empty($_SESSION['stc_cust_requist_cart'])){
			$odin = "Do not leave Requisition Cart Empty!!!";
		}else{
			foreach ($_SESSION["stc_cust_requist_cart"] as $key=>$value) {
				$loki_get_max_price=mysqli_query($this->stc_dbs, "
					SELECT MAX(`stc_product_grn_items_rate`) AS pdmaxrate FROM `stc_product_grn_items` WHERE `stc_product_grn_items_product_id`='".$value['product_id']."'
				");
				$stc_fetch_loki_get_max_price=mysqli_fetch_assoc($loki_get_max_price);
				$finallylokimaxprice=$stc_fetch_loki_get_max_price['pdmaxrate'];

		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_sale_product_items`(
		    			`stc_sale_product_items_sale_product_id`, 
		    			`stc_sale_product_items_product_id`, 
		    			`stc_sale_product_items_product_qty`, 
		    			`stc_sale_product_items_product_rate`, 
		    			`stc_sale_product_items_product_sale_rate`
		    		) VALUES (
		    			'".$get_purchase_product_id."', 
		    			'".$value['product_id']."',
		    			'".$value['product_quantity']."', 
		    			'".$finallylokimaxprice."',
		    			'".$value['product_price']."'
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
			    		UPDATE `stc_item_inventory` SET `stc_item_inventory_pd_qty`='".$finalqty."' WHERE `stc_item_inventory_pd_id`='".$value['product_id']."'
			    	");
			    }
		    }
		    $odin = "Challan Created!!!";
		}
		return $odin;
	}

	public function stc_ag_req_go_to_rec_req_items(){
		$gamora="";
		foreach($_SESSION['stc_cust_requist_cart'] as $recrow){
			$gamorarecgoqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_super_requisition_list_items_rec`(
					`stc_cust_super_requisition_list_items_rec_list_id`, 
					`stc_cust_super_requisition_list_items_rec_list_item_id`, 
					`stc_cust_super_requisition_list_items_rec_list_pd_id`, 
					`stc_cust_super_requisition_list_items_rec_recqty`
				) VALUES (
					'".$recrow['list_item_id']."',
					'".$recrow['list_id']."',
					'".$recrow['product_id']."',
					'".$recrow['product_quantity']."'
				)
			");

		}
		return $gamora;
	}

	public function stc_ag_req_status($stc_req_no){
		$loki='';
		$lokigo=mysqli_query($this->stc_dbs, "
			UPDATE `stc_cust_super_requisition_list` 
			SET `stc_cust_super_requisition_list_status`='3' 
			WHERE `stc_cust_super_requisition_list_id`='".$stc_req_no."'
		");
		if($lokigo){
			$loki='Requisition Cleaned!!!';
		}
		return $loki;
	}
}


#<--------------------------------------------------------------------->
#<----------------------------Object Section--------------------------->
#<--------------------------------------------------------------------->

// page load order call
if(isset($_POST["callorder"])){	
	$objagentorder=new odin();
	$objagcountorder=new odin();

	$opobjagentorder=$objagentorder->stc_call_ag_order();
	echo json_encode($opobjagentorder);
	// echo $opobjagentorder;
}

// search orders
if(isset($_POST['stcfilteragord'])){
	$search = str_replace(",", "|", $_POST["stcfilteragord"]);

	$objloki=new odin();
	$objlokiout=$objloki->stc_filter_ag_ord($search);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call perticular order records
if(isset($_POST['call_order_sub'])){
	$stc_agorder_id=$_POST['stc_order_id'];
	$objloki=new odin();
	$objlokiout=$objloki->stc_view_agents_orders($stc_agorder_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// go items on cart
if(isset($_POST['gooritemoncart'])){
	$pdid=$_POST['stc_pd_id'];
	$stc_rem_ord_qty=$_POST['stc_rem_ord_qty'];
	$stc_inv_ord_qty=$_POST['stc_inv_ord_qty'];
	$stc_pd_price=$_POST['stc_pd_price'];

	$cartqty=0;
	if($stc_inv_ord_qty>=$stc_rem_ord_qty){
		$cartqty=$stc_rem_ord_qty;	
	}else{
		$result=$stc_rem_ord_qty - $stc_inv_ord_qty;
		$value=abs($result);
		$cartqty=abs($stc_rem_ord_qty-$value);
	}

	if(isset($_SESSION["stc_accepted_ag_require"])) {  
		$is_available = 0;  
		foreach($_SESSION["stc_accepted_ag_require"] as $keys => $values) {  
			if($_SESSION["stc_accepted_ag_require"][$keys]['product_id'] == $pdid) {  
			     $is_available++;  
			     // $_SESSION["stc_purchase_order_sess"][$keys]['product_quantity'] = $_SESSION["stc_purchase_order_sess"][$keys]['product_quantity'] + $_POST["product_quantity"];  
			     echo "This Item Is Already In A Cart!!!";
			}  
		}  
	    if($is_available < 1) {  
	    	$item_array = array(  
	    		'product_id'			=>     $pdid,
	    		'product_quantity'		=>     $cartqty,
	    		'product_price'			=>     $stc_pd_price
	    	);  
	    	$_SESSION["stc_accepted_ag_require"][] = $item_array;  
	    	echo "Item Added to Agent Challan Cart!!!";
	    }  
	}else{  
		$item_array = array(  
		    'product_id'             =>     $pdid,   
	    	'product_quantity'       =>     $cartqty,
	    	'product_price'      	 =>     $stc_pd_price
		);   
		$_SESSION["stc_accepted_ag_require"][] = $item_array;  
	     echo "Agent Challan Cart Created & Item Added To Agent Challan Cart!!!";	    
	}
}

// save challan to database
if(isset($_POST['go_to_challan'])){
	$out='';
	$challan_id=$_POST['stc_ch_id'];
	$stc_ch_notes=$_POST['stc_ch_notes'];
	$stc_ch_tandc=$_POST['stc_ch_tandc'];
	$stc_ch_waybilno=$_POST['stc_ch_waybilno'];
	$stc_ch_lrno=$_POST['stc_ch_lrno'];
	$stc_invqty=$_POST['stc_invqty'];
	$stc_proj=$_POST['proj_id'];
	$stc_super=$_POST['super_id'];
	$stc_requis=$_POST['requise_id'];

	$ravens0=new odin();
	$ravens=new odin();
	$ravens2=new odin();
	if($stc_proj==0 && $stc_super==0 && $stc_requis==0){
		if(empty($_SESSION['stc_accepted_ag_require'])){
			$out.="Please Add Item First!!!";
		}else{
			$outravens=$ravens->stc_ag_go_to_challan($challan_id, $stc_ch_notes, $stc_ch_tandc, $stc_ch_waybilno, $stc_ch_lrno);
			if($outravens=="A"){
				$out.=$outravens2=$ravens2->stc_ag_go_to_challan_items();
				unset($_SESSION['stc_accepted_ag_require']);
			}else{
				$out.="I Think You Should Try It Again!!!";
			}		
		}
	}else{
		$outravens0=$ravens0->stc_go_items_on_sup_ag_rec($stc_requis);
		if(empty($_SESSION['stc_accepted_ag_require'])){
			$out.="Please Add Item First!!!";
		}else{
			$outravens=$ravens->stc_ag_go_to_challan($challan_id, $stc_ch_notes, $stc_ch_tandc, $stc_ch_waybilno, $stc_ch_lrno);
			if($outravens=="A"){
				$out.=$outravens2=$ravens2->stc_ag_go_to_challan_items();
				unset($_SESSION['stc_accepted_ag_require']);
			}else{
				$out.="I Think You Should Try It Again!!!";
			}		
		}
	}
	echo json_encode($out);
	// echo $out;
}

// set Status
if(isset($_POST['set_to_clean'])){
	$stc_order_no=$_POST['stc_order_no'];
	$raven=new odin();
	$outraven=$raven->stc_ag_status($stc_order_no);
	echo json_encode($outraven);
	// echo $outraven;
}

// get notification
if(isset($_POST['stc_ag_noti'])){
	$destroyer=new odin();
	$outdestroyer=$destroyer->stc_get_ag_not();
	echo json_encode($outdestroyer);
}

// for po hit
if(isset($_POST['add_po_fr_ag'])){
	$pdid=$_POST['product_id'];
	$remqty=$_POST['stc_rem_ord_qty'];

	$ravens=new odin();
	$outravens=$ravens->stc_ag_add_to_po($pdid, $remqty);
	echo $outravens;
}

#<--------------------------------------------------------------------->
#<-------------------------------Requisition--------------------------->
#<--------------------------------------------------------------------->

// page load order call
if(isset($_POST["callrequisition"])){	
	$objagentorder=new thanos();
	$opobjagentorder=$objagentorder->stc_call_ag_requisition();
	echo json_encode($opobjagentorder);
	// echo $opobjagentorder;
}

// search requisitions
if(isset($_POST['stcagentquery'])){
	$querybox=$_POST['querybox'];
	$objagentorder=new thanos();
	$opobjagentorder=$objagentorder->stc_call_ag_requisition_search($querybox);
	// echo json_encode($opobjagentorder);
	echo $opobjagentorder;
}

// call requisition list combined
if(isset($_POST['callrequisitionlist'])){
	$url_param=$_POST['url_param'];
	$objagentorder=new thanos();
	$opobjagentorder=$objagentorder->stc_show_requisition_list($url_param);
	echo json_encode($opobjagentorder);
}

// call requisition list 
if(isset($_POST['searchcallrequisitionlist'])){
	$url_param=$_POST['url_param'];
	$listcontains=$_POST['listcontains'];
	$objagentorder=new thanos();
	$opobjagentorder=$objagentorder->stc_show_requisition_list_search($url_param, $listcontains);
	echo json_encode($opobjagentorder);
	// echo $opobjagentorder;
}

// call perticular order records
if(isset($_POST['call_requist_sub'])){
	$stc_agorder_id=$_POST['stc_order_id'];
	$objloki=new thanos();
	$objlokiout=$objloki->stc_view_agents_requist($stc_agorder_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// filter requisition 
if(isset($_POST['stcfilteragrequis'])){
	$search = str_replace(",", "|", $_POST["stcfilteragrequis"]);

	$objloki=new odin();
	$objlokiout=$objloki->stc_filter_ag_req($search);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

if(isset($_POST['js_go_for_pro_action'])){
	$out='';
	if(strlen($_POST['product_name'])>=3){
		$searchme=$_POST['product_name'];
		$objloki=new thanos();
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

// add to requisition cart
if(isset($_POST['js_requi_sess'])){
	if($_POST["js_requi_sess"] == "addrequistcart") {  		
		if(isset($_SESSION["stc_cust_requist_cart"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_cust_requist_cart"] as $keys => $values) {  
				if(
					($_SESSION["stc_cust_requist_cart"][$keys]['product_id'] == $_POST["product_id"] && $_SESSION["stc_cust_requist_cart"][$keys]['list_item_id'] == $_POST["product_item_tab_id"])
					&& ($_SESSION["stc_cust_requist_cart"][$keys]['list_item_id'] == $_POST["product_item_tab_id"])
				) { 				  
			     	$is_available++;       
				     echo "Hmm!!! Its look like you choosed same item again or want to add item with same.:)";
				}
			}  
		    if($is_available < 1) {  
		    	$item_array = array(  
		    	     'product_id'				=>     $_POST["product_id"],  
		    	     'list_id'					=>     $_POST["list_id"],  
		    	     'list_item_id'				=>     $_POST["product_item_tab_id"],  
		    	     'product_quantity'			=>     $_POST["product_quantity"],
		    	     'product_price'			=>     $_POST["product_price"]
		    	);  
		    	$_SESSION["stc_cust_requist_cart"][] = $item_array;  
		    	echo "Requisition Cart Created & Item Added to Requisition Cart!!!";
		    }  
		}else{  
			$item_array = array(  
			    	 'product_id'				=>     $_POST["product_id"],  
		    	     'list_id'					=>     $_POST["list_id"],  
		    	     'list_item_id'				=>     $_POST["product_item_tab_id"],  
		    	     'product_quantity'			=>     $_POST["product_quantity"],
		    	     'product_price'			=>     $_POST["product_price"]
			);   
			$_SESSION["stc_cust_requist_cart"][] = $item_array;  
		     echo "Item Added to Requisition Cart!!!";
		}  
    }  
}

// call requisition cart
if(isset($_POST['js_show_requi_cart'])){
	$objloki=new thanos();
	$objlokiout=$objloki->stc_show_requisition_product();
	echo $objlokiout;
}

// delete requisiton items
if(isset($_POST['js_req_delitems'])){  
     foreach($_SESSION["stc_cust_requist_cart"] as $keys => $values){  
          if($values["product_id"] == $_POST["product_id"]){  
               unset($_SESSION["stc_cust_requist_cart"][$keys]);  
               echo "Product Removed!!!";  
               break;
          }  
     }  
}

// save requisition and make challan
if(isset($_POST['go_to_req_challan'])){
	$out='';
	$challan_id=$_POST['stc_ch_id'];
	$stc_ch_notes=$_POST['stc_ch_notes'];
	$stc_ch_tandc=$_POST['stc_ch_tandc'];
	$stc_ch_waybilno=$_POST['stc_ch_waybilno'];
	$stc_ch_lrno=$_POST['stc_ch_lrno'];
	$stc_proj=$_POST['proj_id'];
	$stc_super=$_POST['super_id'];
	$stc_requis=$_POST['requise_id'];

	$ravens0=new thanos();
	$ravens=new thanos();
	$ravens2=new thanos();
	$ravens3=new thanos();
	if(empty($_SESSION['stc_cust_requist_cart'])){
		$out.="Please Add Item First!!!";
	}else{
		$outravens=$ravens->stc_ag_req_go_to_challan($challan_id, $stc_ch_notes, $stc_ch_tandc, $stc_ch_waybilno, $stc_ch_lrno);
		if($outravens=="A"){
			$out.=$outravens2=$ravens2->stc_ag_req_go_to_challan_items();
			$outravens3=$ravens3->stc_ag_req_go_to_rec_req_items();
			unset($_SESSION['stc_cust_requist_cart']);
		}else{
			$out.="I Think You Should Try It Again!!!";
		}		
	}
	// echo json_encode($out);
	echo $out;
}

// set Status
if(isset($_POST['req_set_to_clean'])){
	$stc_req_no=$_POST['stc_req_no'];
	$raven=new thanos();
	$outraven=$raven->stc_ag_req_status($stc_req_no);
	echo json_encode($outraven);
	// echo $outraven;
}
?>