<?php
include "../../MCU/obdb.php";
session_start();
// call order class
class ragnarOrderView extends tesseract{
	// call customer on po page
	public function stc_call_customer(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT DISTINCT stc_customer_id, stc_customer_name FROM `stc_customer`INNER JOIN `stc_cust_project`ON `stc_customer_id`=`stc_cust_project_cust_id` ORDER BY `stc_customer_name` ASC");
		$odin='<option value="NA" selected>Select Customer</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Customer Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
				if($row["stc_customer_id"]==2){
					$odin.='
						<option value="'.$row["stc_customer_id"].'" selected>'.$row["stc_customer_name"].'</option>		               	
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

	// call all agents order records
	public function stc_call_ag_order(){
		$lokiout='
			<table class="table table-hover form-group call-order">
				<thead>
					<th>For</th>
					<th>Order No<br>Order Date</th>
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
			WHERE `stc_agent_order_status`='PROCESS'
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
						<td>'.$lokivalue["stc_customer_name"].'</td>
						<td>
							STC/A/'.substr("0000{$lokivalue["stc_agent_order_id"]}", -5).'<br>
							'.date('d-m-Y',strtotime($lokivalue["stc_agent_order_date"])).'
						</td>
						<td>'.$lokivalue["stc_agents_name"].'</td>
						<td><p align="right"><i class="fa fa-rupee-sign"></i> <b>'.number_format($agamount, 2).'</b></p></td>
						<td>'.$lokivalue["stc_agent_order_cust_site_name"].'</td>
						<td>'.$lokivalue["stc_agent_order_status"].'</td>
						<td>
							<a 
								class="stc_view_order" 
								href="#" 
								id="'.$lokivalue["stc_agent_order_id"].'" 
								style="font-size: 25px;color: black;"
							><i class="fa fa-eye" aria-hidden="true"></i>
							</a>
						</td>
					</tr>
				';
			}
		}
		$lokiout.= '
			<tr>
				<td>
					0 to 15
					<button type="button" class="btn btn-primary ordbegbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="orbegvalueinputsearch" value="0">
				</td>
				<td colspan="9">
					<button type="button" class="btn btn-primary ordendbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="orendvalueinputsearch" value="20">
				</td>
			</tr>
		';
		$lokiout.='
				</tbody>
			</table>
		';
		return $lokiout;
	}

	// filter challan by all
	public function stc_getorder_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustomerid, $bjornefilterordernumber, $bjornefilterorderagents, $bjornefilterstatus, $bjorneordbegvalue, $bjorneordendvalue){
		$ivar='
			<table class="table table-hover form-group call-order">
				<thead>
					<th>For</th>
					<th>Order No<br>Order Date</th>
					<th>From</th>
					<th>Value</th>
					<th>Reference</th>
					<th>Status</th>
					<th>View</th>
				</thead>
				<tbody>
        ';
			
		$array = array(
			"bycustomer" => $bjornefiltercustomerid,
			"byordernumber" => $bjornefilterordernumber,
			"byagentname" => $bjornefilterorderagents,
			"bystatus" => $bjornefilterstatus
		);

		$customer='';
		$orderno='';
		$agentname='';
		$status='';
		$counter=0;
		foreach($array as $key => $value){
			if($array['bycustomer']!="NA"){
				$customer="
					AND `stc_customer_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bycustomer'])."'
				";
			}

			if(!empty($array['byordernumber'])){
				$orderno="
					AND `stc_agent_order_id`='".mysqli_real_escape_string($this->stc_dbs, $array['byordernumber'])."'
				";
			}

			if(!empty($array['byagentname'])){
				$agentname="
					AND `stc_agents_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byagentname'])."'
				";
			}

			if($array['bystatus']!="NA"){
				$status="
					AND `stc_sale_product_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bystatus'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_agent_order`.`stc_agent_order_id` DESC LIMIT '.$bjorneordbegvalue.','.$bjorneordendvalue;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_agent_order`
			INNER JOIN `stc_customer`
			ON `stc_agent_order_cust_id`=`stc_customer_id`
			INNER JOIN `stc_agents`
			ON `stc_agent_order_agent_id`=`stc_agents_id`
			WHERE `stc_agent_order_status`='PROCESS'
			AND (
                `stc_agent_order_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterenddate)."'
            )".$customer.$orderno.$agentname.$status.$endfilterqry
		);

		$do_action=mysqli_num_rows($ivarfilterquery);
		if($do_action == 0){
			$ivar .= "<tr><td colspan='7' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($ivarfilterquery as $lokivalue) {
				$counter++;
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_agent_order_items` WHERE `stc_agent_order_items_order_id`='".$lokivalue["stc_agent_order_id"]."'
				");
				$agamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$agiqty=$row2nd['stc_agent_order_items_pd_qty'];
					$agirate=$row2nd['stc_agent_order_items_pd_price'];
					$agamount+=$agiqty * $agirate;
				}

				$ivar.='
					<tr>
						<td>'.$lokivalue["stc_customer_name"].'</td>
						<td>
							STC/A/'.substr("0000{$lokivalue["stc_agent_order_id"]}", -5).'<br>
							'.date('d-m-Y',strtotime($lokivalue["stc_agent_order_date"])).'
						</td>
						<td>'.$lokivalue["stc_agents_name"].'</td>
						<td><p align="right"><i class="fa fa-rupee-sign"></i> <b>'.number_format($agamount, 2).'</b></p></td>
						<td>'.$lokivalue["stc_agent_order_cust_site_name"].'</td>
						<td>'.$lokivalue["stc_agent_order_status"].'</td>
						<td>
							<a class="stc_view_order" 
								href="#" id="'.$lokivalue["stc_agent_order_id"].'" 
								style="font-size: 25px;color: black;"
							><i class="fa fa-eye" aria-hidden="true"></i></a>
						</td>
					</tr>
				';

				if($counter==20){
					break;
				}
			}
		}
		$ivar.= '
			<tr>
				<td>
					'.$bjorneordbegvalue.' to '.$bjorneordendvalue.'
					<button type="button" class="btn btn-primary ordbegbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="orbegvalueinputsearch" value="'.$bjorneordbegvalue.'">
				</td>
				<td colspan="9">
					<button type="button" class="btn btn-primary ordendbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="orendvalueinputsearch" value="'.$bjorneordendvalue.'">
				</td>
			</tr>
		';

		$ivar.='
				</tbody>
			</table>
        ';
		return $ivar;
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
}

// add order class
class ragnarOrderAdd extends tesseract{
	// save order to challan
	public function stc_ag_go_to_challan($challan_id, $stc_ch_notes, $stc_ch_tandc, $stc_ch_waybilno, $stc_ch_lrno){
		$heimdall='';
		$date=date("Y-m-d H:i:s");
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
				'".$date."',
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
				'".$_SESSION['stc_empl_id']."'
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
}

// call requisition combiner class
class ragnarRequisitionView extends tesseract{
	// call all agents order records
	public function stc_call_ag_requisition(){
		$ivar='
			<table class="table table-hover table-bordered form-group call-order">
				<thead>
					<tr>
						<th class="text-center">Requisition For</th>
						<th class="text-center">Parent Requisition ID<br>Parent Requisition Date</th>
						<th class="text-center">Requisition Sitename</th>
						<th class="text-center">Requisition Accepted By</th>
						<th class="text-center">Requisition Status</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
		';
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT 
				`stc_requisition_combiner_id`,
				`stc_requisition_combiner_date`,
			    `stc_requisition_combiner_refrence`,
			    `stc_agents_name`,
                `stc_customer_name`,
				`stc_requisition_combiner_status`
			FROM `stc_requisition_combiner`
			INNER JOIN `stc_requisition_combiner_req` 
			ON `stc_requisition_combiner_id`=`stc_requisition_combiner_req_comb_id`
			INNER JOIN `stc_cust_super_requisition_list` 
			ON `stc_cust_super_requisition_list_id`=`stc_requisition_combiner_req_requisition_id`
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_super_requisition_list_project_id`=`stc_cust_project_id`
			INNER JOIN `stc_customer` 
			ON `stc_cust_project_cust_id`=`stc_customer_id`
			INNER JOIN `stc_agents` 
			ON `stc_agents_id`=`stc_requisition_combiner_agent_id`
			ORDER BY `stc_requisition_combiner_id` DESC LIMIT 0,15
		");
		if(mysqli_num_rows($ivarfilterquery)!=0){
			foreach($ivarfilterquery as $requisrow){
				$reqcomstatus='';
				if($requisrow['stc_requisition_combiner_status']==1){$reqcomstatus="PROCESS";}else{$reqcomstatus="ACCEPTED";}
				$badgeurgent='<span class="urgent" style="position: relative;display: inline-block;top: -10px;padding: 1px 3px;font-size: 10px;font-weight: bold;color: #fff;background-color: #dc3545; border-radius: 15px;">Urgent</span>';
				$chursql=mysqli_query($this->stc_dbs, "
					SELECT DISTINCT `stc_cust_super_requisition_list_id`
					FROM `stc_cust_super_requisition_list_items`
					INNER JOIN `stc_requisition_combiner_req`
					ON `stc_cust_super_requisition_list_items_req_id`=`stc_requisition_combiner_req_requisition_id`
					WHERE `stc_requisition_combiner_req_comb_id`='".$requisrow['stc_requisition_combiner_id']."' AND `stc_cust_super_requisition_items_priority`=2
				");
				if(mysqli_num_rows($chursql)==0){
					$badgeurgent="";
				}
				$ivar.= '
					<tr>
						<td class="text-center">'.$requisrow['stc_customer_name'].'</td>
						<td class="text-center">							
							<a href="#" class="stc-call-for-select-merchant-req" title="Add product and merchant." id="'.$requisrow["stc_requisition_combiner_id"].'" style="color: black;">
								'.$requisrow['stc_requisition_combiner_id'].'<br>
								'.date('d-m-Y', strtotime($requisrow['stc_requisition_combiner_date'])).'
							</a>
						</td>
						<td>						
							<a target="_blank" href="stc-requisition-combiner-fsale.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="Process requisitions." style="font-size: 15px;color: black;">
								'.$requisrow['stc_requisition_combiner_refrence'].'	
								<i class="fa fa-pen-square" aria-hidden="true"></i>
							</a>
						</td>
						<td class="text-center">
							'.$requisrow['stc_agents_name'].'
						</td>
						<td class="text-center">'.$reqcomstatus.$badgeurgent.'</td>
						<td class="text-center">
							<a href="stc-requisition-combiner-fshow.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="P.M Requisition" style="font-size: 25px;color: black;">
								<i class="fa fa-print" aria-hidden="true"></i>
							</a>
							<a href="stc-requisition-combiner-dcprintpreview.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="DC Receiving Requisition" style="font-size: 25px;color: black;">
								<i class="fa fa-print" aria-hidden="true"></i>
							</a>
							<a href="stc-print-preview-directchallan.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="Delivery Challan" style="font-size: 25px;color: black;">
								<i class="fa fa-print" aria-hidden="true"></i>
							</a>
						</td>
					</tr>
				';
			}
		}else{
    	        $ivar.= '
    	            <tr>
    	                <td colspan="6">
    	                    <b>No Requisition Found!!!</b>
    	                </td>
    	            </tr>
    	        ';
		}
		$ivar.= '
			<tr>
				<td>
					0 to 15
					<button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="begvalueinputsearch" value="0">
				</td>
				<td colspan="9">
					<button type="button" class="btn btn-primary endbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="endvalueinputsearch" value="20">
				</td>
			</tr>
		';

		$ivar.='
				</tbody>
			</table>
		';
		return $ivar;
	}

	// filter combiner requisition by all
	public function stc_getrequisition_by_multiple_inp($bjornefilterreqbegdate, $bjornefilterreqenddate, $bjornefilterreqcustomerid, $bjornefilterreqnumber, $bjornefilterreqsitename, $bjornefilterreqmaterials, $bjornebegval, $bjorneendval){
		$ivar='
			<table class="table table-hover table-bordered form-group call-order">
				<thead>
					<tr>
						<th class="text-center">Requisition For</th>
						<th class="text-center">Parent Requisition ID<br>Parent Requisition Date</th>
						<th class="text-center">Requisition Sitename</th>
						<th class="text-center">Requisition Accepted By</th>
						<th class="text-center">Requisition Status</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
		';
			
		$array = array(
			"byreqcustomer" => $bjornefilterreqcustomerid,
			"byreqnumber" => $bjornefilterreqnumber,
			"byreqsitename" => $bjornefilterreqsitename,
			"byreqmaterial" => $bjornefilterreqmaterials
		);

		$reqcustomer='';
		$reqno='';
		$reqsitename='';
		$reqmaterialsname='';

		$itemjoiner='';
		$loopcount=0;
		foreach($array as $key => $value){
			if($array['byreqcustomer']!="NA"){
				$reqcustomer="
					AND `stc_customer_id`='".mysqli_real_escape_string($this->stc_dbs, $array['byreqcustomer'])."'
				";
			}

			if(!empty($array['byreqnumber'])){
				$reqno="
					AND (`stc_requisition_combiner_id` regexp '".mysqli_real_escape_string($this->stc_dbs, $array['byreqnumber'])."' OR L.`stc_cust_super_requisition_list_id` regexp '".mysqli_real_escape_string($this->stc_dbs, $array['byreqnumber'])."')
				";
			}

			if(!empty($array['byreqsitename'])){
				$reqsitename="
					AND (
							`stc_cust_project_title` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byreqsitename'])."'
						OR
							`stc_requisition_combiner_refrence` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byreqsitename'])."')
				";
			}

			if(!empty($array['byreqmaterial'])){
				$itemjoiner='INNER JOIN `stc_cust_super_requisition_list_items` I ON L.`stc_cust_super_requisition_list_id`=I.`stc_cust_super_requisition_list_items_req_id`';
				$reqmaterialsname="
					AND `stc_cust_super_requisition_list_items_title` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byreqmaterial'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_requisition_combiner_id` DESC LIMIT '.$bjornebegval.','.$bjorneendval;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT 
				`stc_requisition_combiner_id`,
				`stc_requisition_combiner_date`,
			    `stc_requisition_combiner_refrence`,
			    `stc_agents_name`,
		        `stc_customer_name`,
				`stc_requisition_combiner_status`
			FROM `stc_requisition_combiner`
			INNER JOIN `stc_requisition_combiner_req` ON `stc_requisition_combiner_id`=`stc_requisition_combiner_req_comb_id`
			INNER JOIN `stc_cust_super_requisition_list` L ON L.`stc_cust_super_requisition_list_id`=`stc_requisition_combiner_req_requisition_id`
			".$itemjoiner."
			INNER JOIN `stc_cust_project` ON L.`stc_cust_super_requisition_list_project_id`=`stc_cust_project_id`
			INNER JOIN `stc_customer` ON `stc_cust_project_cust_id`=`stc_customer_id`
			INNER JOIN `stc_agents` ON `stc_agents_id`=`stc_requisition_combiner_agent_id`
		    WHERE (
		        DATE(`stc_requisition_combiner_date`)
		         BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterreqbegdate)."'
		         AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterreqenddate)."'
		    )".$reqcustomer.$reqno.$reqsitename.$reqmaterialsname.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)!=0){
			foreach($ivarfilterquery as $requisrow){
				$reqcomstatus='';
				if($requisrow['stc_requisition_combiner_status']==1){$reqcomstatus="PROCESS";}else{$reqcomstatus="ACCEPTED";}
				$badgeurgent='<span class="urgent" style="position: relative;display: inline-block;top: -10px;padding: 1px 3px;font-size: 10px;font-weight: bold;color: #fff;background-color: #dc3545; border-radius: 15px;">Urgent</span>';
				$chursql=mysqli_query($this->stc_dbs, "
					SELECT DISTINCT `stc_cust_super_requisition_list_id`
					FROM `stc_cust_super_requisition_list_items`
					INNER JOIN `stc_requisition_combiner_req`
					ON `stc_cust_super_requisition_list_items_req_id`=`stc_requisition_combiner_req_requisition_id`
					WHERE `stc_requisition_combiner_req_comb_id`='".$requisrow['stc_requisition_combiner_id']."' AND `stc_cust_super_requisition_items_priority`=2
				");
				if(mysqli_num_rows($chursql)==0){
					$badgeurgent="";
				}
				$ivar.= '
					<tr>
						<td class="text-center">'.$requisrow['stc_customer_name'].'</td>
						<td class="text-center">							
							<a href="#" class="stc-call-for-select-merchant-req" title="Add product and merchant." id="'.$requisrow["stc_requisition_combiner_id"].'" style="color: black;">
								'.$requisrow['stc_requisition_combiner_id'].'<br>
								'.date('d-m-Y', strtotime($requisrow['stc_requisition_combiner_date'])).'
							</a>
						</td>
						<td>						
							<a target="_blank" href="stc-requisition-combiner-fsale.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="Process requisitions." style="font-size: 15px;color: black;">
								'.$requisrow['stc_requisition_combiner_refrence'].'	
								<i class="fa fa-pen-square" aria-hidden="true"></i>
							</a>
						</td>
						<td class="text-center">
							'.$requisrow['stc_agents_name'].'
						</td>
						<td class="text-center">'.$reqcomstatus.$badgeurgent.'</td>
						<td class="text-center">
							<a href="stc-requisition-combiner-fshow.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="P.M Requisition" style="font-size: 25px;color: black;">
								<i class="fa fa-print" aria-hidden="true"></i>
							</a>
							<a href="stc-requisition-combiner-dcprintpreview.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="DC Receiving Requisition" style="font-size: 25px;color: black;">
								<i class="fa fa-print" aria-hidden="true"></i>
							</a>
							<a href="stc-print-preview-directchallan.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="Delivery Challan" style="font-size: 25px;color: black;">
								<i class="fa fa-print" aria-hidden="true"></i>
							</a>
						</td>
					</tr>
				';
			}
		}else{
    	        $ivar.= '
    	            <tr>
    	                <td colspan="6">
    	                    <b>No Requisition Found!!!</b>
    	                </td>
    	            </tr>
    	        ';
		}
		$ivar.= '
			<tr>
				<td>
					0 to 15
					<button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="begvalueinputsearch" value="0">
				</td>
				<td colspan="9">
					<button type="button" class="btn btn-primary endbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="endvalueinputsearch" value="20">
				</td>
			</tr>
		';

		$ivar.='
				</tbody>
			</table>
		';
		return $ivar;
	}

	// filter combiner requisition by all & items name
	public function stc_getrequisition_by_multiple_inp_for_materials($bjornefilterreqbegdate, $bjornefilterreqenddate, $bjornefilterreqcustomerid, $bjornefilterreqnumber, $bjornefilterreqsitename, $bjornefilterreqmaterials, $bjornebegval, $bjorneendval){

		$ivar='
			<table class="table table-hover form-group call-order">
				<thead>
					<th class="text-center">Requisition For</th>
					<th class="text-center">Requisition ID<br>Requisition Date</th>
					<th class="text-center">Requisition Sitename</th>
					<th class="text-center">Requisition Accepted By</th>
					<th class="text-center">Requisition Status</th>
					<th class="text-center">Action</th>
				</thead>
				<tbody>
		';
			
		$array = array(
			"byreqcustomer" => $bjornefilterreqcustomerid,
			"byreqnumber" => $bjornefilterreqnumber,
			"byreqsitename" => $bjornefilterreqsitename,
			"byreqmaterials" => $bjornefilterreqmaterials
		);

		$reqcustomer='';
		$reqno='';
		$reqsitename='';
		$reqmaterials='';
		$loopcount=0;
		foreach($array as $key => $value){
			if($array['byreqcustomer']!="NA"){
				$reqcustomer="
					AND `stc_customer_id`='".mysqli_real_escape_string($this->stc_dbs, $array['byreqcustomer'])."'
				";
			}

			if(!empty($array['byreqnumber'])){
				$reqno="
					AND `stc_requisition_combiner_id`='".mysqli_real_escape_string($this->stc_dbs, $array['byreqnumber'])."'
				";
			}

			if(!empty($array['byreqsitename'])){
				$reqsitename="
					AND (
							`stc_cust_project_title` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byreqsitename'])."'
						OR
							`stc_requisition_combiner_refrence` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byreqsitename'])."')
				";
			}

			if($array['byreqmaterials']!="NA"){
				$reqmaterials="
					AND `stc_cust_super_requisition_list_items_title` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byreqmaterials'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_requisition_combiner_id` DESC LIMIT '.$bjornebegval.','.$bjorneendval;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT 
				`stc_requisition_combiner_id`,
				`stc_requisition_combiner_date`,
			    `stc_requisition_combiner_refrence`,
			    `stc_agents_name`,
		        `stc_customer_name`,
				`stc_requisition_combiner_status`
			FROM `stc_requisition_combiner`
			INNER JOIN `stc_requisition_combiner_req` 
			ON `stc_requisition_combiner_id`=`stc_requisition_combiner_req_comb_id`
			INNER JOIN `stc_cust_super_requisition_list` 
			ON `stc_cust_super_requisition_list_id`=`stc_requisition_combiner_req_requisition_id`
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_super_requisition_list_project_id`=`stc_cust_project_id`
			INNER JOIN `stc_customer` 
			ON `stc_cust_project_cust_id`=`stc_customer_id`
			INNER JOIN `stc_agents` 
			ON `stc_agents_id`=`stc_requisition_combiner_agent_id`
			INNER JOIN `stc_cust_super_requisition_list_items` 
			ON `stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id`=`stc_cust_super_requisition_list_items_req_id`
		    WHERE (
		        DATE(`stc_requisition_combiner_date`)
		         BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterreqbegdate)."'
		         AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterreqenddate)."'
		    )".$reqcustomer.$reqno.$reqsitename.$reqmaterials.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)!=0){
			foreach($ivarfilterquery as $requisrow){
				$loopcount++;
				$reqcomstatus='';
				if($requisrow['stc_requisition_combiner_status']==1){$reqcomstatus="PROCESS";}else{$reqcomstatus="ACCEPTED";}
				$ivar.= '
					<tr>
						<td>'.$requisrow['stc_customer_name'].'</td>
						<td>
							'.$requisrow['stc_requisition_combiner_id'].'<br>
							'.date('d-m-Y', strtotime($requisrow['stc_requisition_combiner_date'])).'
						</td>
						<td>'.$requisrow['stc_requisition_combiner_refrence'].'</td>
						<td>'.$requisrow['stc_agents_name'].'</td>
						<td>'.$reqcomstatus.'</td>
						<td>
							<a href="#" class="stc-call-for-select-merchant-req" id="'.$requisrow["stc_requisition_combiner_id"].'" style="font-size: 25px;color: black;">
								<i class="fa fa-ship" aria-hidden="true"></i>
							</a>
							<a href="stc-requisition-combiner-fsale.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" style="font-size: 25px;color: black;">
								<i class="fa fa-pen-square" aria-hidden="true"></i>
							</a>
							<a href="stc-requisition-combiner-fshow.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" style="font-size: 25px;color: black;">
								<i class="fa fa-print" aria-hidden="true"></i>
							</a>
						</td>
					</tr>
				';
				if($loopcount==20){
					break;
				}
			}
		}else{
    	        $ivar.= '
    	            <tr>
    	                <td colspan="6">
    	                    <b>No Requisition Found!!!</b>
    	                </td>
    	            </tr>
    	        ';
		}
		$ivar.= '
			<tr>
				<td>
					'.$bjornebegval.' to '.$bjorneendval.'
					<button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="begvalueinputsearch" value="'.$bjornebegval.'">
				</td>
				<td colspan="9">
					<button type="button" class="btn btn-primary endbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="endvalueinputsearch" value="'.$bjorneendval.'">
				</td>
			</tr>
		';

		$ivar.='
				</tbody>
			</table>
		';
		return $ivar;
	}

	// call req item from merchandise
	public function stc_call_ag_requisition_items_merchandise($stc_req_comb_id){
		$ivar='
			<table class="table table-bordered" style="color: black;">
		';

		$ivarsiteqry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
			  `stc_requisition_combiner_req_comb_id`,
			  `stc_cust_super_requisition_list_id`,
			  `stc_cust_project_title`
			FROM `stc_requisition_combiner_req` 
			INNER JOIN `stc_cust_super_requisition_list` 
			ON `stc_requisition_combiner_req_requisition_id`=`stc_cust_super_requisition_list_id` 
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id`
			WHERE `stc_requisition_combiner_req_comb_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_req_comb_id)."'
		");
		foreach($ivarsiteqry as $ivarsiterow){
			$ivar.='
				<tr>
					<td align="center">
						'.$ivarsiterow['stc_cust_super_requisition_list_id'].'
					</td>
					<td colspan="6" align="center">
						<b>'.$ivarsiterow['stc_cust_project_title'].'<b>
					</td>
				</tr>
			';

			$ivarreqitemqry=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_cust_super_requisition_list_id`,
					`stc_cust_super_requisition_list_items_title`,
					`stc_cust_super_requisition_list_items_unit`,
					`stc_cust_super_requisition_list_items_approved_qty`,
					`stc_cust_super_requisition_items_priority`,
					`stc_cust_super_requisition_items_type`
				FROM `stc_cust_super_requisition_list_items`
				WHERE `stc_cust_super_requisition_list_items_req_id`='".$ivarsiterow['stc_cust_super_requisition_list_id']."'
			");
			$ivar.='
						<tr>
	                        <th class="text-center">#</th>
	                        <th class="text-center" width="30%" class="text-left">ITEMS DESCRIPTIONS</th>
	                        <th class="text-center">TYPE</th>
	                        <th class="text-center">UNIT</th>
	                        <th class="text-center">QTY</th>
	                        <th class="text-center">PRODUCT</th>
	                        <th class="text-center">MERCHANT</th>
	                        <th class="text-center">ACTION</th>
	                    </tr>
			';
			$sl=0;
			foreach($ivarreqitemqry as $ivarreqitemrow){
				$sl++;
				$getmerchndiseqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_product_name`,
					    `stc_cust_super_requisition_list_purchaser_mer_id`
					FROM`stc_cust_super_requisition_list_purchaser`
					INNER JOIN `stc_product` 
					ON `stc_product_id`=`stc_cust_super_requisition_list_purchaser_pd_id` 
					WHERE `stc_cust_super_requisition_list_purchaser_list_item_id`='".$ivarreqitemrow['stc_cust_super_requisition_list_id']."'
				");
				$pd_name='';
				$merchant_id='';
				foreach($getmerchndiseqry as $merchandrow){
					$pd_name=$merchandrow['stc_product_name'];
					$merchant_id=$merchandrow['stc_cust_super_requisition_list_purchaser_mer_id'];
				}
				$typearray = array('Consumable', 'PPE', 'Supply',  'Tools & Tackles');
				$type_select= '<option value="NA">Please Select Item Type</option>';
				foreach($typearray as $typearrayorw){
					if($typearrayorw==$ivarreqitemrow['stc_cust_super_requisition_items_type']){
						$type_select.='<option value="'.$typearrayorw.'" selected>'.$typearrayorw.'</option>';
					}else{
						$type_select.='<option value="'.$typearrayorw.'">'.$typearrayorw.'</option>';
					}
				}
				$unitarray = array('Nos', 'Set', 'Feet', 'Mtr', 'Sqmt', 'Ltr', 'Bag', 'Roll', 'Lot', 'Kgs', 'Pkt', 'Case', 'Bundle', 'Pair');
				$unit_select= '<option value="NA">Please Select Unit</option>';
				foreach($unitarray as $unitarrayorw){
					if(strtolower($unitarrayorw)==strtolower($ivarreqitemrow['stc_cust_super_requisition_list_items_unit'])){
						$unit_select.='<option value="'.$unitarrayorw.'" selected>'.$unitarrayorw.'</option>';
					}else{
						$unit_select.='<option value="'.$unitarrayorw.'">'.$unitarrayorw.'</option>';
					}
				}
				$check_loki=mysqli_query($this->stc_dbs, "SELECT `stc_merchant_id`, `stc_merchant_name` FROM `stc_merchant` GROUP BY `stc_merchant_name` ASC");
				$odin='<option value="NA" selected>Select Merchant</option>';
				$do_action=mysqli_num_rows($check_loki);
				if($do_action == 0){
					$odin = "<option value='NA' selected>No Merchant Found !!</option>";
				}else{
					foreach ($check_loki as $row) {
						if($row["stc_merchant_id"]==$merchant_id){
							$odin.='<option value="'.$row["stc_merchant_id"].'" selected>'.$row["stc_merchant_name"].'</option>';
						}else{
							$odin.='<option value="'.$row["stc_merchant_id"].'">'.$row["stc_merchant_name"].'</option>';
						}	
					}
					
				}
				$badgeurgent="";
				if($ivarreqitemrow['stc_cust_super_requisition_items_priority']==2){
					$badgeurgent='<span class="urgent" style="position: relative;display: inline-block;top: -10px;padding: 1px 3px;font-size: 10px;font-weight: bold;color: #fff;background-color: #dc3545; border-radius: 15px;">Urgent</span>';
				}
				$ivar.='
						<tr>
							<td class="no">'.$sl.'</td>
							<td class="text-left">
							'.$ivarreqitemrow['stc_cust_super_requisition_list_items_title'].'
								<input 
									type="text" 
									class="form-control stc-update-requis-material-name stc-update-requis-material-name-hit" 
									placeholder="Please Enter Item Name" 
									value="'.htmlspecialchars($ivarreqitemrow['stc_cust_super_requisition_list_items_title']).'"
									id="'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'"
									style="display:none;"
								>
							</td>
							<td class="unit">
								<select
								  class="custom-select stc-sup-req-type-change'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'"
								  name="stcpdunit"
								>
                                	'.$type_select.'
								</select>
							</td>
							<td class="unit">
								<select
								  class="custom-select stc-sup-req-unit-change'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'"
								  name="stcpdunit"
								>	
								  '.$unit_select.'						  
								</select>
							</td>
							<td class="qty">
								<input 
									style="position:relative;top:14px;"
									type="text" 
									class="form-control stc-sup-req-qty-change'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'" 
									placeholder="Quantity" title="Please Enter Requisition Quantity"
									id="'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'"
									value="'.number_format($ivarreqitemrow['stc_cust_super_requisition_list_items_approved_qty'], 2).'"
								>
								<span style="position:relative;top:-19px;left:50px;">
									<a 
										href="#" 
										style="font-size: 16px;border-radius: 20%;background: yellow;padding: 10px 8px;"
										class="stc-sup-req-something-change-hit"
										id="'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'"
									><i class="fa fa-arrow-right"></i></a>
								</span>
							</td>
							<td class="no">
								<a href="#" id="'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'" class="form-control btn btn-primary stc-agent-req-get-mer-product">
									<i class="fa fa-edit"></i>
								</a>
								<input type="hidden" class="stc-req-product-id-show-tag'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'" id="stc-req-pd'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'">
								<span class="stc-req-product-show-tag'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'">'.$pd_name.'</span>
							</td>
							<td class="no">
								<select class="form-control js-example-basic-single" id="stc-req-mer'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'">'.$odin.'</select>
							</td>
							<td class="no">
								<a href="#" class="btn btn-success stc-req-merchandise-save" id="'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'">Save</a>
							</td>
						</tr>
				';
			}
		}
		$ivar.='
			</table>
		';
		return $ivar;
	} 

	// filter product by all
	public function stc_getpd_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout){
		$ivar='';
			
		$array = array(
			"bycat" => $bjornefiltercatout,
			"bysubcat" => $bjornefiltersubcatout,
			"byname" => $bjornefilternameout
		);
		$category='';
		$subcategory='';
		$productname='';
		foreach($array as $key => $value){
			if($array['bycat']!="NA"){
				$category="
					AND `stc_product_cat_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bycat'])."'
				";
			}

			if($array['bysubcat']!="NA"){
				$subcategory="
					AND `stc_product_sub_cat_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bysubcat'])."'
				";
			}

			if(!empty($array['byname'])){
				$productname="
					AND (`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byname'])."'
					OR 
					`stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byname'])."')
				";
			}
		}
		$endfilterqry='ORDER BY `stc_product_id` ASC LIMIT 0,30';

		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT * FROM `stc_product` 
			LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
			LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
			LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
			LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
			LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
			WHERE `stc_product_avail`='1' 
			".$category.$subcategory.$productname.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)>0){
			foreach($ivarfilterquery as $filterrow){$loki_findratefrompo=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_purchase_product_items` 
					WHERE `stc_purchase_product_items_product_id`='".$filterrow["stc_product_id"]."' 
					AND `stc_purchase_product_items_id`=(
						SELECT MAX(`stc_purchase_product_items_id`) FROM `stc_purchase_product_items` 
						WHERE `stc_purchase_product_items_product_id`='".$filterrow["stc_product_id"]."'
					)
				");
				$purchase_rate=mysqli_fetch_assoc($loki_findratefrompo);
				$correct_stcpdname=strlen($filterrow["stc_product_name"]);
				$stcqty=$filterrow["stc_item_inventory_pd_qty"];
				if($stcqty==0){
					$stcqty=0;
				}else{
					$stcqty;
				}

				$ivar.='
					<div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
						<div class="row">
							<div class="el-wrapper">
								<div class="box-up">
									<img class="img" style="height: 100%; width:100%;" src="../stc_symbiote/stc_product_image/'.$filterrow["stc_product_image"].'" alt="">
									<div class="img-info">
										<div class="info-inner">
											<span class="p-name">'.htmlentities($filterrow["stc_product_name"]).'</span>
											<span class="p-company">'.$filterrow["stc_cat_name"].' / '.$filterrow["stc_product_hsncode"].'</span>
											<span class="p-qty" >'.$stcqty.''.$filterrow["stc_product_unit"].'</span>
											<input 
												type="hidden"
												id="stcpdinvent'.$filterrow["stc_product_id"].'" 
												value="'.number_format($stcqty, 2, '.', '').'" 
											/>	 
										</div>
									</div>
								</div>

								<div class="box-down">
									<div class="h-bg">
										<div class="h-bg-inner"></div>
									</div>

									<a 
										class="cart add_to_requist_mer" 
										href="#" 
										id="'.$filterrow["stc_product_id"].'" 
										pd-name="'.htmlentities($filterrow["stc_product_name"]).'"
									>
									<span class="price">'.$filterrow["stc_sub_cat_name"].' / '.$filterrow["stc_brand_title"].'</span>
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
			$ivar .= '
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
					<a 
				      href="#" 
				      class="btn btn-primary btn-block text-uppercase mb-3">
				      No Records Found!!!
				    </a>
				</div>
			';
		}
		return $ivar;
	}

	// call merchant
	public function stc_call_merchant(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT `stc_merchant_id`, `stc_merchant_name` FROM `stc_merchant` GROUP BY `stc_merchant_name` ASC");
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

	// call sitename
	public function stc_call_sitename($sitebegdate, $siteenddate){
		$odin='';
		$odinsitename_qry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_requisition_combiner_id`, 
				`stc_requisition_combiner_date`,
			    `stc_requisition_combiner_refrence`
			FROM 
				`stc_requisition_combiner` 
			INNER JOIN 
				`stc_requisition_combiner_req` 
			ON 
				`stc_requisition_combiner_req_comb_id`=`stc_requisition_combiner_id` 
			WHERE 
			    DATE(`stc_requisition_combiner_date`) BETWEEN 
			    	'".mysqli_real_escape_string($this->stc_dbs, $sitebegdate)."' 
				AND 
					'".mysqli_real_escape_string($this->stc_dbs, $siteenddate)."'
			ORDER BY `stc_requisition_combiner_refrence` ASC
		");
		if(mysqli_num_rows($odinsitename_qry)>0){
			foreach($odinsitename_qry as $odinsitename_row){
				$sitename=substr($odinsitename_row['stc_requisition_combiner_refrence'], 0, 20);
				$sitedetails=$sitename.' ('.$odinsitename_row['stc_requisition_combiner_id'].')';
				$odin.='
					<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
						<div class="position-relative form-check form-check-inline">
							<label class="form-check-label">
								<input 
									type="checkbox" 
									class="form-check-input" 
									name="stc-check-box-site-view" 
									value="'.$odinsitename_row['stc_requisition_combiner_id'].'"
								> 	<span style="position: relative;left: 30px;top: -18px;">
										'.$sitedetails.'
									</span>
							</label>
						</div>
					</div>
				';
			}
			$odin.='
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
					<a href="#" class="form-control btn btn-success" id="stc-check-box-site-submit">Get</a>
				</div>
			';
		}else{
			$odin.='
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
					No Records Found!!!
				</div>
			';
		}
		return $odin;
	}

	// call post merchant
	public function stc_call_post_merchant($req_id){
		$odin='';
		$odinmerchant_qry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_merchant_name` 
			FROM 
				`stc_cust_super_requisition_list_purchaser` 
			INNER JOIN 
				`stc_merchant` 
			ON 
				`stc_merchant_id`=`stc_cust_super_requisition_list_purchaser_mer_id` 
			WHERE 
				`stc_cust_super_requisition_list_purchaser_list_item_id`='".mysqli_real_escape_string($this->stc_dbs, $req_id)."'
		");
		foreach($odinmerchant_qry as $odinmerchant_row){
			$odin=$odinmerchant_row['stc_merchant_name'];
		}
		return $odin;
	}

	// call pre merchant
	public function stc_call_pre_merchant($prod_id){
		$odin='<option value="NA" selected>Select Merchant</option>';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_merchant_id`, 
				`stc_merchant_name` 
			FROM 
				`stc_merchant` 
			INNER JOIN 
				`stc_purchase_product` 
			ON 
				`stc_purchase_product_merchant_id`=`stc_merchant_id`
			INNER JOIN 
				`stc_purchase_product_items` 
			ON 
				`stc_purchase_product_items_order_id`=`stc_purchase_product_id`
			WHERE 
				`stc_purchase_product_items_product_id` = '".mysqli_real_escape_string($this->stc_dbs, $prod_id)."'
			ORDER BY `stc_merchant_name` ASC
		");
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

	// call merchant rate
	public function stc_call_pre_merchant_rate($merchant_id, $prod_id){
		$odin='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_purchase_product_items_rate`,
				`stc_product_unit`
			FROM
				`stc_purchase_product_items`
			INNER JOIN 
				`stc_purchase_product` 
			ON 
				`stc_purchase_product_id` = `stc_purchase_product_items_order_id`
			INNER JOIN 
				`stc_product` 
			ON 
				`stc_product_id` = `stc_purchase_product_items_product_id`
			WHERE
				`stc_purchase_product_merchant_id` = '".mysqli_real_escape_string($this->stc_dbs, $merchant_id)."'
			AND 
				`stc_product_id` = '".mysqli_real_escape_string($this->stc_dbs, $prod_id)."'
			ORDER BY
				`stc_purchase_product_items_id`
			DESC
		");
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "No record found!!!";
		}else{
			foreach ($check_loki as $row) {
				$odin=number_format($row["stc_purchase_product_items_rate"], 2).'/'.$row['stc_product_unit'];	
			}
			
		}
		return $odin;
	}

	// change merchant
	public function stc_req_change_merchant($req_id, $merchant_id){
		$odin='';
		$odincmerchant_qry=mysqli_query($this->stc_dbs, "
			UPDATE
				`stc_cust_super_requisition_list_purchaser`
			SET
				`stc_cust_super_requisition_list_purchaser_mer_id` = '".mysqli_real_escape_string($this->stc_dbs, $merchant_id)."'
			WHERE
				`stc_cust_super_requisition_list_purchaser_list_item_id` = '".mysqli_real_escape_string($this->stc_dbs, $req_id)."'
		");
		if($odincmerchant_qry){
			$odin='Merchant updated succefully.';
		}else{
			$odin='Hmmm!!! Something went wrong, Merchant not updated properly.';
		}
		return $odin;
	}

	// approved quantity
	public function stc_approved_quantity($req_id, $appr_qty){
		$odin='';
		$odinupdate_qry=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_cust_super_requisition_list_purchaser`
			SET
			    `stc_cust_super_requisition_list_purchaser_qty` = '".mysqli_real_escape_string($this->stc_dbs, $appr_qty)."'
			WHERE
			    `stc_cust_super_requisition_list_purchaser_list_item_id` = '".mysqli_real_escape_string($this->stc_dbs, $req_id)."'
		");
		if($odinupdate_qry){
			$odin='Approved successfully.';
		}else{
			$odin='Hmm!!! Something went wrong on Approving Requisition.';
		}
		return $odin;
	}

	// process for po after the approved qunatity
	public function stc_approved_for_po($pd_id, $req_id, $appr_qty){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$req_list_id='';
		$req_list_mer_id='';
		$po_id='';
		$po_item_rate='';
		$purchased_for='';
		$odin_getreq_detqry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
			    `stc_cust_super_requisition_list_items_req_id`,
			    `stc_cust_super_requisition_list_purchaser_mer_id`
			FROM
			    `stc_cust_super_requisition_list_items`
			INNER JOIN 
				`stc_cust_super_requisition_list_purchaser`
			ON 
				`stc_cust_super_requisition_list_purchaser_list_item_id`=`stc_cust_super_requisition_list_id`
			WHERE
			    `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $req_id)."'
		");

		foreach($odin_getreq_detqry as $odin_getreq_detrow){
			$req_list_id=$odin_getreq_detrow['stc_cust_super_requisition_list_items_req_id'];
			$req_list_mer_id=$odin_getreq_detrow['stc_cust_super_requisition_list_purchaser_mer_id'];
		}

		$odin_getpo_detqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_purchase_product_id`
			FROM
				`stc_purchase_product`
			WHERE
				`stc_purchase_product_merchant_id` = '".mysqli_real_escape_string($this->stc_dbs, $req_list_mer_id)."'
			AND 
				`stc_purchase_product_status` = 'process'
			AND 
				`stc_purchase_product_req_no` = '".mysqli_real_escape_string($this->stc_dbs, $req_list_id)."'
		");

		foreach($odin_getpo_detqry as $odin_getpo_detrow){
			$po_id=$odin_getpo_detrow['stc_purchase_product_id'];
		}

		$odin_getpoitems_qry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_purchase_product_items_rate`
			FROM
				`stc_purchase_product_items`
			WHERE
				`stc_purchase_product_items_product_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id)."'
			ORDER BY 
				`stc_purchase_product_items_id`
			DESC
		");

		foreach($odin_getpoitems_qry as $odin_getpoitems_row){
			$po_item_rate=$odin_getpoitems_row['stc_purchase_product_items_rate'];
		}

		if(mysqli_num_rows($odin_getpo_detqry)>0){
			$odin_update_insertpoitems_qry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_purchase_product_items`(
					`stc_purchase_product_items_order_id`,
					`stc_purchase_product_items_product_id`,
					`stc_purchase_product_items_qty`,
					`stc_purchase_product_items_rate`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $po_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pd_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $appr_qty)."',
					'".mysqli_real_escape_string($this->stc_dbs, $po_item_rate)."'
				)
			");
			if($odin_update_insertpoitems_qry){
				$odin="Approved successfully.";
			}
		}else{
			$new_po_id='';
			$odin_getsite_qry=mysqli_query($this->stc_dbs, "
				SELECT
					`stc_cust_project_refr`
				FROM
					`stc_cust_super_requisition_list`
				INNER JOIN 
					`stc_cust_project` 
				ON
					`stc_cust_project_id`=`stc_cust_super_requisition_list_project_id` 
				WHERE
					`stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $req_list_id)."'
			");

			foreach($odin_getsite_qry as $odin_getsite_row){
				$purchased_for=$odin_getsite_row['stc_cust_project_refr'];
			}
			$tandc='
				1. GST : , 
				2. Payment : NA, 
				3. Packing & Forwarding : NA, 
				4. Freight : NA, 
				5. Warranty/Guarantee : , 
				6. Delivery Time : , 
				7. Certificates : , 
				Others 
				Delivery Address : Global AC System JSR PVT.LTD. C/O Majesty,  
				79 A Block Dhatkidih PO-Bistupur, Jamshedpur-831001, Jharkhand                                        
			';
			$odin_insertpo_qry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_purchase_product`(
					`stc_purchase_product_merchant_id`,
					`stc_purchase_product_order_date`,
					`stc_purchase_product_basic_value`,
					`stc_purchase_product_status`,
					`stc_purchase_product_created_by`,
					`stc_purchase_product_notes`,
					`stc_purchase_product_purchased_for`,
					`stc_purchase_product_req_no`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $req_list_mer_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'',
					'process',
					'2',
					'".mysqli_real_escape_string($this->stc_dbs, $tandc)."',
					'".mysqli_real_escape_string($this->stc_dbs, $purchased_for)."',
					'".mysqli_real_escape_string($this->stc_dbs, $req_list_id)."'
				)
			");

			$odin_getpo_qry=mysqli_query($this->stc_dbs, "
				SELECT
					`stc_purchase_product_id`
				FROM
					`stc_purchase_product`
				WHERE
					`stc_purchase_product_merchant_id` = '".mysqli_real_escape_string($this->stc_dbs, $req_list_mer_id)."'
				AND 
					`stc_purchase_product_status` = 'process'
				AND 
					`stc_purchase_product_req_no` = '".mysqli_real_escape_string($this->stc_dbs, $req_list_id)."'
				ORDER BY `stc_purchase_product_id` DESC
			");
			foreach($odin_getpo_qry as $odin_getpo_row){
				$new_po_id=$odin_getpo_row['stc_purchase_product_id'];
			}

			$odin_insertpoitems_qry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_purchase_product_items`(
					`stc_purchase_product_items_order_id`,
					`stc_purchase_product_items_product_id`,
					`stc_purchase_product_items_qty`,
					`stc_purchase_product_items_rate`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $new_po_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pd_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $appr_qty)."',
					'".mysqli_real_escape_string($this->stc_dbs, $po_item_rate)."'
				)
			");
			if($odin_insertpoitems_qry){
				$odin="Approved successfully.";
			}
		}

		return $odin;
	}

	// set requisition & qty
	public function stc_req_item_attr_change($req_item_id, $req_item_type, $req_item_unit, $req_item_qty){
		$odin='';
		$odinattrchqry=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_cust_super_requisition_list_items`
			SET
			    `stc_cust_super_requisition_list_items_unit`='".mysqli_real_escape_string($this->stc_dbs, $req_item_unit)."',
			    `stc_cust_super_requisition_list_items_reqqty`='".mysqli_real_escape_string($this->stc_dbs, $req_item_qty)."',
			    `stc_cust_super_requisition_list_items_approved_qty`='".mysqli_real_escape_string($this->stc_dbs, $req_item_qty)."',
			    `stc_cust_super_requisition_items_finalqty`='".mysqli_real_escape_string($this->stc_dbs, $req_item_qty)."',
			    `stc_cust_super_requisition_items_type`='".mysqli_real_escape_string($this->stc_dbs, $req_item_type)."'
			WHERE
			    `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $req_item_id)."'
		");
		if($odinattrchqry){
			$odin="Requisition Item Type, Quantity & Unit Updated Successfully. Reload to see.";
		}else{
			$odin="Hmmm!!! Something Went Wrong While Updating Requisition Item Quantity & Unit.";
		}
		return $odin;
	}

	// call product
	public function stc_call_product_merchant($pd_id){
		$odin='';
		$odin_callmercqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_merchant_name`
			FROM
			    `stc_purchase_product_items`
			INNER JOIN 
				`stc_purchase_product` 
			ON 
				`stc_purchase_product_id`=`stc_purchase_product_items_order_id` 
			INNER JOIN 
				`stc_merchant` 
			ON 
				`stc_merchant_id`=`stc_purchase_product_merchant_id` 
			WHERE
			    `stc_purchase_product_items_product_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id)."'
			ORDER BY DATE(`stc_purchase_product_order_date`) DESC 
			LIMIT 0,1
		");
		foreach($odin_callmercqry as $odin_callmercrow){
			$odin=$odin_callmercrow['stc_merchant_name'];
		}
		return $odin;
	}

	// call requisition by list
	public function stc_getrequisitionlist($req_begdate, $req_enddate, $req_customer, $req_reqnumber, $req_sitenmae, $req_materialtype, $offset, $records_per_page, $page){
		$ivar='
			<input type"text" class="form-control stc-req-item-static-search" placeholder="Search here">
			<table class="table table-bordered table-hover table-responsive stc-reqbyitem-table">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Parent Requisition Id & Date</th>
						<th class="text-center">Requisition Id & Date</th>
						<th class="text-center">Reference</th>
						<th class="text-center">Location</th>
						<th class="text-center">From</th>
						<th class="text-center">Accepted By</th>
						<th class="text-center">Items Desc</th>
						<th class="text-center">Unit</th>
						<th class="text-center">Ordered Qty</th> 
						<th class="text-center">Approved Qty</th> 
						<th class="text-center">GST Qty</th>  
						<th class="text-center">Dispatched Qty</th> 
						<th class="text-center">Status</th>   
						<th class="text-center">Priority</th>  
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
		';
		$filter='';
		
		if($req_customer!="NA"){
			$filter.="
				AND `stc_cust_project_cust_id`='".mysqli_real_escape_string($this->stc_dbs, $req_customer)."'
			";
		}

		if($req_materialtype!="NA"){
			$filter.="
				AND `stc_cust_super_requisition_items_type`='".mysqli_real_escape_string($this->stc_dbs, $req_materialtype)."'
			";
		}

		if($req_reqnumber!=""){
			$filter.="
				AND `stc_cust_super_requisition_list_items_req_id` regexp '".mysqli_real_escape_string($this->stc_dbs, $req_reqnumber)."'
			";
		}

		if($req_sitenmae!=""){
			$filter.="
				AND `stc_cust_project_title` regexp '".mysqli_real_escape_string($this->stc_dbs, $req_sitenmae)."'
			";
		}
		
		$query="
			SELECT
				I.`stc_cust_super_requisition_list_id` as item_id,
				`stc_cust_project_id`,
				`stc_cust_project_title`,
				`stc_requisition_combiner_id`,
				`stc_requisition_combiner_date`,
				`stc_cust_super_requisition_list_items_req_id`,
				L.`stc_cust_super_requisition_list_id` as list_id,
				L.`stc_cust_super_requisition_list_date`,
				`stc_requisition_combiner_date`,
				`stc_requisition_combiner_refrence`,
				`stc_cust_super_requisition_list_items_title`,
				`stc_cust_super_requisition_list_items_unit`,
				`stc_cust_super_requisition_list_items_reqqty`,
				`stc_cust_super_requisition_list_items_approved_qty`,
				`stc_cust_super_requisition_items_finalqty`,
				`stc_cust_super_requisition_items_type`,
				`stc_cust_super_requisition_items_priority`,
				`stc_cust_super_requisition_list_items_status`,
				`stc_cust_super_requisition_list_status`,
				`stc_cust_pro_supervisor_contact`,
				`stc_cust_pro_supervisor_id`,
				`stc_cust_pro_supervisor_fullname`,
				`stc_agents_name`
			FROM `stc_cust_super_requisition_list_items` I
			INNER JOIN `stc_cust_super_requisition_list` L
			ON I.`stc_cust_super_requisition_list_items_req_id`=L.`stc_cust_super_requisition_list_id`
			INNER JOIN `stc_cust_project`
			ON L.`stc_cust_super_requisition_list_project_id`=`stc_cust_project_id`
			INNER JOIN `stc_requisition_combiner_req`
			ON L.`stc_cust_super_requisition_list_id`=`stc_requisition_combiner_req_requisition_id`
			INNER JOIN `stc_requisition_combiner`
			ON `stc_requisition_combiner_req_comb_id`=`stc_requisition_combiner_id`
			INNER JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_super_requisition_list_super_id`=`stc_cust_pro_supervisor_id`
			INNER JOIN `stc_agents`
			ON `stc_cust_pro_supervisor_created_by`=`stc_agents_id`
			WHERE DATE(`stc_cust_super_requisition_list_date`) 
			BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $req_begdate)."' 
			AND '".mysqli_real_escape_string($this->stc_dbs, $req_enddate)."'
			".$filter."
		";

		$total_result = mysqli_query($this->stc_dbs, $query);
		$total_records = mysqli_num_rows($total_result);
		$total_pages = ceil($total_records / $records_per_page);

		$query=$query." LIMIT $offset, $records_per_page ";
		$ivar_qry=mysqli_query($this->stc_dbs, $query);
		if(mysqli_num_rows($ivar_qry)>0){
			$slno=0;
			foreach($ivar_qry as $ivar_row){
				$slno++;
				if($ivar_row['stc_cust_super_requisition_list_status']==1){
				    $reqstatus="PROCESS";
				}elseif($ivar_row['stc_cust_super_requisition_list_status']==2){
				    $reqstatus="PASSED";
				}elseif($ivar_row['stc_cust_super_requisition_list_status']==3){
				    $reqstatus="COMPLETED";
				}else{
				    $reqstatus="ACCEPTED";
				}
				$badgeurgent='<span class="urgent" style="position: relative;display: inline-block;top: -10px;padding: 1px 3px;font-size: 10px;font-weight: bold;color: #fff;background-color: #dc3545; border-radius: 15px;">Urgent</span>';
				$chursql=mysqli_query($this->stc_dbs, "
					SELECT `stc_cust_super_requisition_items_priority` FROM `stc_cust_super_requisition_list_items` WHERE `stc_cust_super_requisition_list_items_req_id`='".$ivar_row['list_id']."' AND `stc_cust_super_requisition_items_priority`=2
				");
				if(mysqli_num_rows($chursql)==0){
					$badgeurgent="";
				}
				$status='';
				if($ivar_row["stc_cust_super_requisition_list_items_status"]==1){
					$status='By GST';
				}else{
					$status='By Normal';
				}
				$getdispatchedtransformers=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_cust_super_requisition_list_items_rec_recqty`) AS dispatched_qty
					FROM `stc_cust_super_requisition_list_items_rec` 
					WHERE `stc_cust_super_requisition_list_items_rec_list_item_id`='".$ivar_row['item_id']."'
				");
				$dispatchedgqty=0;
				foreach($getdispatchedtransformers as $decqtyrow){
					$dispatchedgqty+=$decqtyrow['dispatched_qty'];
				}
				
				$lokigetappritemqry=mysqli_query($this->stc_dbs, "
					SELECT `stc_product_name`, SUM(`stc_cust_super_requisition_list_purchaser_qty`) as stc_appr_qty 
					FROM `stc_cust_super_requisition_list_purchaser` 
					INNER JOIN `stc_product` 
					ON `stc_product_id`=`stc_cust_super_requisition_list_purchaser_pd_id` 
					WHERE `stc_cust_super_requisition_list_purchaser_list_item_id`='".$ivar_row['item_id']."'
				");
				$apprpd_name='';
				$apprpd_qty=0;
				foreach($lokigetappritemqry as $lokigetappritemrow){
					$apprpd_name=$lokigetappritemrow['stc_product_name'];
					$apprpd_qty=$lokigetappritemrow['stc_appr_qty'];
				}
				$checkqty=$ivar_row["stc_cust_super_requisition_list_items_approved_qty"] - $dispatchedgqty;
				$actiondeliver='<a class="req-product-Modal-cash-close" data-toggle="modal" data-target=".res-product-Modal-cash-close" style="font-size:25px;color:black;" title="Dispatch by direct" id="'.$ivar_row['item_id'].'" list-id="'.$ivar_row["stc_cust_super_requisition_list_items_req_id"].'" orderqty="'.$checkqty.'" href="#"><i class="fa fa-file"></i></a>';
				$actiondeliver=$ivar_row["stc_cust_super_requisition_list_items_approved_qty"]>$dispatchedgqty ? $actiondeliver : "";
				$priority=$ivar_row['stc_cust_super_requisition_items_priority']==2 ? "Urgent" : "Normal";
				$bgcolor=$ivar_row['stc_cust_super_requisition_items_priority']==2 ? "style='background:#ffb0b0;'" : "";

				$query=mysqli_query($this->stc_dbs, "SELECT DISTINCT `stc_agents_id`, `stc_agents_name` FROM `stc_agents` LEFT JOIN `stc_cust_pro_supervisor_collaborate` ON `stc_agents_id`=`stc_cust_pro_supervisor_collaborate_teamid` LEFT JOIN `stc_cust_project` ON `stc_agents_id`=`stc_cust_project_createdby` WHERE `stc_cust_pro_supervisor_collaborate_userid`='".$ivar_row['stc_cust_pro_supervisor_id']."' AND (`stc_cust_project_id`='".$ivar_row['stc_cust_project_id']."')");
				
				$agent_name='';
				if(mysqli_num_rows($query)>0){
					$result=mysqli_fetch_assoc($query);
					$agent_name=$result['stc_agents_name'];
				}
				if($agent_name==''){
					$agent_name=$ivar_row['stc_agents_name'];
				}
				$ivar.='
					<tr>
						<td>'.$slno.'</td>
						<td class="text-center"><a href="#" class="stc-call-for-select-merchant-req" title="Add product and merchant." id="'.$ivar_row['stc_requisition_combiner_id'].'" style="color: black;">'.$ivar_row['stc_requisition_combiner_id'].' <br>'.date('d-m-Y', strtotime($ivar_row['stc_requisition_combiner_date'])).'</a></td>
						<td class="text-center">'.$ivar_row['list_id'].'<br>'.date('d-m-Y', strtotime($ivar_row['stc_requisition_combiner_date'])).'</td>
						<td class="text-center">'.$ivar_row['stc_requisition_combiner_refrence'].'</td>
						<td class="text-center">'.$ivar_row['stc_cust_project_title'].'</td>
						<td class="text-center">'.$ivar_row['stc_cust_pro_supervisor_fullname'].' <br>('.$ivar_row['stc_cust_pro_supervisor_contact'].')</td>
						<td class="text-center">'.$agent_name.'</td>
						<td>'.$ivar_row['stc_cust_super_requisition_list_items_title'].'</td>
						<td class="text-center">'.$ivar_row['stc_cust_super_requisition_list_items_unit'].'</td>
						<td class="text-right">'.number_format($ivar_row['stc_cust_super_requisition_list_items_approved_qty'], 2).'</td>
						<td class="text-right">'.number_format($apprpd_qty, 2).'</td>
						<td align="right">'.number_format($dispatchedgqty, 2).'</td>
						<td align="right">'.number_format($dispatchedgqty, 2).'</td>
						<td>'.$status.'</td>
						<td class="text-center" '.$bgcolor.'>'.$priority.'</td>
						<td>
							'.$actiondeliver.'
							<a href="stc-requisition-combiner-fshow.php?requi_id='.$ivar_row['stc_requisition_combiner_id'].'" title="P.M Requisition" style="font-size: 25px;color: black;">
								<i class="fa fa-print" aria-hidden="true"></i>
							</a>
							<a href="stc-requisition-combiner-dcprintpreview.php?requi_id='.$ivar_row['stc_requisition_combiner_id'].'" title="DC Receiving Requisition" style="font-size: 25px;color: black;">
								<i class="fa fa-print" aria-hidden="true"></i>
							</a>
							<a href="stc-print-preview-directchallan.php?requi_id='.$ivar_row['stc_requisition_combiner_id'].'" title="Delivery Challan" style="font-size: 25px;color: black;">
								<i class="fa fa-print" aria-hidden="true"></i>
							</a>	
						</td>
					</tr>
				';
			}
		}else{
			$ivar.='
				<tr>
					<td>Record not found.</td>
				</tr>
			';
		}
		$ivar .= '
				</tbody>		
			</table>
		';
		$ivar .= '<div class="pagination">'.$offset.'/'.$total_records;
		for ($i = 1; $i <= $total_pages; $i++) {
			if($i==$page){
				$ivar .= '<a href="javascript:void(0)" style="background: #00ff5a; color: black;" class="page-link" data-page="' . $i . '">' . $i . '</a> ';
			}else{
				$ivar .= '<a href="javascript:void(0)" class="page-link" data-page="' . $i . '">' . $i . '</a> ';
			}
		}
		$ivar .= '</div>';
		return $ivar;
	}
}

// add requisition combiner class
class ragnarRequisitionAdd extends tesseract{
	public function stc_add_req_merchandise($stc_req_id, $stc_pd_id, $stc_merchant_id){
		$odin='';
		$odincheck_qry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_list_purchaser_list_item_id` 
			FROM 
				`stc_cust_super_requisition_list_purchaser` 
			WHERE 
				`stc_cust_super_requisition_list_purchaser_list_item_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."'
		");
		if(mysqli_num_rows($odincheck_qry)>0){
			$odinset_qry=mysqli_query($this->stc_dbs, "
				UPDATE
				    `stc_cust_super_requisition_list_purchaser`
				SET
				    `stc_cust_super_requisition_list_purchaser_pd_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_pd_id)."',
				    `stc_cust_super_requisition_list_purchaser_mer_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_merchant_id)."'
				WHERE
				    `stc_cust_super_requisition_list_purchaser_list_item_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."'
			");
			if($odinset_qry){
				$odin='Product & merchant updated successfully.';
			}else{
				$odin='Hmmm!!! Something went wrong on updating product & merchant.';
			}
		}else{
			$odinset_qry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_super_requisition_list_purchaser`(
					`stc_cust_super_requisition_list_purchaser_list_item_id`, 
					`stc_cust_super_requisition_list_purchaser_pd_id`, 
					`stc_cust_super_requisition_list_purchaser_mer_id`, 
					`stc_cust_super_requisition_list_purchaser_qty`, 
					`stc_cust_super_requisition_list_purchaser_created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_pd_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_merchant_id)."',
					'0',
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_empl_id'])."'
				)
			");
			if($odinset_qry){
				$odin='Product & merchant added successfully.';
			}else{
				$odin='Hmmm!!! Something went wrong on adding product & merchant.';
			}
		}
		return $odin;
	}
}

// update reuisition material name
class raganarRequisitionUpdate extends tesseract{
	public function stc_update_material_requistion_name($material_id, $material_name){		
		$lokiout='';
		$lokireqnameupdateqry=mysqli_query($this->stc_dbs, "
			UPDATE
				`stc_cust_super_requisition_list_items`
			SET
				`stc_cust_super_requisition_list_items_title` = '".mysqli_real_escape_string($this->stc_dbs, $material_name)."'
			WHERE
				`stc_cust_super_requisition_list_id` = '".mysqli_real_escape_string($this->stc_dbs, $material_id)."'
		");
		if($lokireqnameupdateqry){
			$lokiout='Material Name Updated Successfully. Load Again to Show.';
		}else{
			$lokiout='Hmmm!!! Material Name Not Updated Properly.';
		}
		return $lokiout;
	}
}

// call perticular requistion class
class ragnarRequisitionPertView extends tesseract{
	// call perticular 
	public function stc_show_requisition_list($url_param){
		$lokiout='
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th class="text-center">Sl No</th>
						<th class="text-center">ID<br>Date</th>
						<th class="text-center">From</th>
						<th class="text-center">For</th>
						<th class="text-center" style="width: 20%;">Items Desc</th>
						<th class="text-center">Unit</th>
						<th class="text-center">Ordered Qty</th> 
						<th class="text-center">Dispatched Qty</th> 
						<th class="text-center">Status</th>   
						<th class="text-center">Priority</th>  
						<th class="text-center">Action</th>
						<th class="text-center">Log</th>
					</tr>
				</thead>
				<tbody>
		';
		$reqstatus='';
		$requissuperqry=mysqli_query($this->stc_dbs, "
			SELECT 
				L.`stc_cust_super_requisition_list_id` as list_id,
				`stc_cust_super_requisition_list_date`,
				`stc_cust_project_title`,
				`stc_cust_pro_supervisor_fullname`,
				`stc_cust_pro_supervisor_contact`,
				`stc_agents_name`,
				`stc_cust_super_requisition_list_status`,
				I.`stc_cust_super_requisition_list_id` as list_item_id,
				I.`stc_cust_super_requisition_list_items_req_id`,
				I.`stc_cust_super_requisition_list_items_title`,
				I.`stc_cust_super_requisition_list_items_unit`,
				I.`stc_cust_super_requisition_list_items_approved_qty`,
				I.`stc_cust_super_requisition_items_priority`,
				I.`stc_cust_super_requisition_list_items_status`
			FROM `stc_cust_super_requisition_list_items` I
			INNER JOIN `stc_cust_super_requisition_list` L 
			ON I.`stc_cust_super_requisition_list_items_req_id`=L.`stc_cust_super_requisition_list_id`
			INNER JOIN `stc_cust_pro_supervisor` 
			ON `stc_cust_pro_supervisor_id`=`stc_cust_super_requisition_list_super_id`
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id`
			INNER JOIN `stc_agents` 
			ON `stc_agents_id`=`stc_cust_pro_supervisor_created_by`
			INNER JOIN `stc_requisition_combiner_req`
			ON `stc_requisition_combiner_req_requisition_id`=L.`stc_cust_super_requisition_list_id`
			INNER JOIN `stc_requisition_combiner`
			ON `stc_requisition_combiner_req_comb_id`=`stc_requisition_combiner_id`
			WHERE `stc_cust_super_requisition_list_status`!='1'
			AND `stc_requisition_combiner_id`='".mysqli_real_escape_string($this->stc_dbs, $url_param)."'
			ORDER BY L.`stc_cust_super_requisition_list_id` DESC
		");
		if(mysqli_num_rows($requissuperqry)!=0){
			$slno=0;
			foreach($requissuperqry as $requisrow){
				if($requisrow['stc_cust_super_requisition_list_status']==1){
				    $reqstatus="PROCESS";
				}elseif($requisrow['stc_cust_super_requisition_list_status']==2){
				    $reqstatus="PASSED";
				}elseif($requisrow['stc_cust_super_requisition_list_status']==3){
				    $reqstatus="COMPLETED";
				}else{
				    $reqstatus="ACCEPTED";
				}
				$badgeurgent='<span class="urgent" style="position: relative;display: inline-block;top: -10px;padding: 1px 3px;font-size: 10px;font-weight: bold;color: #fff;background-color: #dc3545; border-radius: 15px;">Urgent</span>';
				$chursql=mysqli_query($this->stc_dbs, "
					SELECT `stc_cust_super_requisition_items_priority` FROM `stc_cust_super_requisition_list_items` WHERE `stc_cust_super_requisition_list_items_req_id`='".$requisrow['list_id']."' AND `stc_cust_super_requisition_items_priority`=2
				");
				if(mysqli_num_rows($chursql)==0){
					$badgeurgent="";
				}

				$status='';
				if($requisrow['stc_cust_super_requisition_list_items_status']==1){
					$status='<span style="background-color: #3498db; color: white; padding: 2px 6px; border-radius: 3px;">Ordered</span>';
				}elseif($requisrow['stc_cust_super_requisition_list_items_status']==2){
					$status='<span style="background-color: #2ecc71; color: white; padding: 2px 6px; border-radius: 3px;">Approved</span>';
				}elseif($requisrow['stc_cust_super_requisition_list_items_status']==3){
					$status='<span style="background-color: #27ae60; color: white; padding: 2px 6px; border-radius: 3px;">Accepted</span>';
				}elseif($requisrow['stc_cust_super_requisition_list_items_status']==4){
					$status='<span style="background-color: #f39c12; color: white; padding: 2px 6px; border-radius: 3px;">Dispatched</span>';
				}elseif($requisrow['stc_cust_super_requisition_list_items_status']==5){
					$status='<span style="background-color: #16a085; color: white; padding: 2px 6px; border-radius: 3px;">Received</span>';
				}elseif($requisrow['stc_cust_super_requisition_list_items_status']==6){
					$status='<span style="background-color: #e74c3c; color: white; padding: 2px 6px; border-radius: 3px;">Rejected</span>';
				}elseif($requisrow['stc_cust_super_requisition_list_items_status']==7){
					$status='<span style="background-color: #95a5a6; color: white; padding: 2px 6px; border-radius: 3px;">Canceled</span>';
				}elseif($requisrow['stc_cust_super_requisition_list_items_status']==8){
					$status='<span style="background-color: #9b59b6; color: white; padding: 2px 6px; border-radius: 3px;">Returned</span>';
				}elseif($requisrow['stc_cust_super_requisition_list_items_status']==9){
					$status='<span style="background-color:rgb(255, 47, 47); color: white; padding: 2px 6px; border-radius: 3px;">Pending</span>';
				}else{
					$status='<span style="background-color: #34495e; color: white; padding: 2px 6px; border-radius: 3px;">Closed</span>';
				}
				$getdispatchedtransformers=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_cust_super_requisition_list_items_rec_recqty`) AS dispatched_qty
					FROM `stc_cust_super_requisition_list_items_rec` 
					WHERE `stc_cust_super_requisition_list_items_rec_list_item_id`='".$requisrow['list_item_id']."'
				");
				$dispatchedgqty=0;
				foreach($getdispatchedtransformers as $decqtyrow){
					$dispatchedgqty+=$decqtyrow['dispatched_qty'];
				}

				$lokigetappritemqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_product_name`,
						SUM(`stc_cust_super_requisition_list_purchaser_qty`) as stc_appr_qty 
					FROM `stc_cust_super_requisition_list_purchaser` 
					INNER JOIN `stc_product` 
					ON `stc_product_id`=`stc_cust_super_requisition_list_purchaser_pd_id` 
					WHERE `stc_cust_super_requisition_list_purchaser_list_item_id`='".$requisrow['list_item_id']."'
				");
				$apprpd_name='';
				$apprpd_qty=0;
				foreach($lokigetappritemqry as $lokigetappritemrow){
					$apprpd_name=$lokigetappritemrow['stc_product_name'];
					$apprpd_qty=$lokigetappritemrow['stc_appr_qty'];
				}
				$checkqty=$requisrow["stc_cust_super_requisition_list_items_approved_qty"] - $dispatchedgqty;
				
					// <a class="req-product-Modal" style="font-size:25px;color:black;" title="Dispatch by inventory" id="'.$requisrow['list_item_id'].'" list-id="'.$requisrow["stc_cust_super_requisition_list_items_req_id"].'" href="#"><i class="fa fa-truck"></i></a>
				$actiondeliver='
					<a class="btn-change-status" data-toggle="modal" data-target="#statusRemarkModal" style="font-size:25px;color:black;" status="'.$requisrow['stc_cust_super_requisition_list_items_status'].'" title="Update to pending" id="'.$requisrow['list_item_id'].'" list-id="'.$requisrow["stc_cust_super_requisition_list_items_req_id"].'" orderqty="'.$checkqty.'" href="#"><i class="fa fa-clock-o"></i></a>
					<a class="req-product-Modal-cash-close" style="font-size:25px;color:black;" title="Dispatch by direct" id="'.$requisrow['list_item_id'].'" list-id="'.$requisrow["stc_cust_super_requisition_list_items_req_id"].'" orderqty="'.$checkqty.'" href="#"><i class="fa fa-file"></i></a>
					<a class="stc_add_togld" style="font-size:25px;color:black;" title="Add to GLD" id="'.$requisrow['list_item_id'].'" list-id="'.$requisrow["stc_cust_super_requisition_list_items_req_id"].'" href="#"><i class="fa fa-shopping-cart"></i></a>
				';
				$calculation=$requisrow["stc_cust_super_requisition_list_items_approved_qty"] - $dispatchedgqty;
				$actiondeliver= $calculation>0.1 ? $actiondeliver.'<a class="req-recieving-Modal" title="Recieving" style="font-size:25px;color:black;" id="'.$requisrow['list_item_id'].'" list-id="'.$requisrow["stc_cust_super_requisition_list_items_req_id"].'" href="#"><i class="fa fa-hand-grab-o"></i></a>' : '<a class="req-recieving-Modal" title="Recieving" style="font-size:25px;color:black;" id="'.$requisrow['list_item_id'].'" list-id="'.$requisrow["stc_cust_super_requisition_list_items_req_id"].'" href="#"><i class="fa fa-hand-grab-o"></i></a>';
				$lokigetappritemqry=mysqli_query($this->stc_dbs, "
					SELECT `id`, `requisition_list_id`, `status`, `created_by`, `created_date` FROM `stc_requisition_gld` WHERE `requisition_list_id`='".$requisrow['list_item_id']."'
				");
				if(mysqli_num_rows($lokigetappritemqry)>0){
					$actiondeliver='GLD';
				}
				$priority=$requisrow['stc_cust_super_requisition_items_priority']==2 ? "Urgent" : "Normal";
				$bgcolor=$requisrow['stc_cust_super_requisition_items_priority']==2 ? "style='background:#ffb0b0;'" : "";
				
				$list_item_id=$requisrow['list_item_id'];
				$query=mysqli_query($this->stc_dbs, "
					SELECT 
						`title`, 
						`message`, 
						`created_by`, 
						`created_date`
					FROM `stc_cust_super_requisition_list_items_log` 
					WHERE `item_id`='".$list_item_id."'
					ORDER BY `id` DESC
				");
				$log='';
				if(mysqli_num_rows($query)>0){
					$log='<a href="#" data-toggle="modal" data-target=".bd-log-modal-lg" class="btn btn-info btn-sm stc-sup-requisition-viewlog-modal-btn">	 
							View Log
						</a>';
					foreach($query as $row){
						$log.='
							<div style="display:none;border: 1px solid #e0e0e0; border-radius: 8px; padding: 12px 16px; margin: 12px 0; font-family: "Segoe UI", sans-serif; box-shadow: 0 1px 2px rgba(0,0,0,0.05); background-color: #fff;">
								<div style="display: flex; justify-content: space-between; align-items: center;">
									<span style="font-weight: 600; color: #212121; font-size: 16px;">'.$row['title'].'</span>
									<span style="font-size: 12px; color: #757575;">'.date('d-m-Y h:i A', strtotime($row['created_date'])).'</span>
								</div>
								<div style="margin-top: 4px; font-size: 14px; color: #424242;">
									'.$row['message'].'
								</div>
							</div>
						';
					}
				}
				$slno++;
				$lokiout.= '
					<tr>
						 <td class="text-center"> '.$slno.' </td>
						 <td class="text-center"> '.$requisrow['list_id'].'<br> '.date('d-m-Y h:i A', strtotime($requisrow['stc_cust_super_requisition_list_date'])).' </td>
						 <td class="text-center">'.$requisrow['stc_cust_pro_supervisor_fullname'].'<br>'.$requisrow['stc_cust_pro_supervisor_contact'].'</td>
						 <td>'.$requisrow['stc_cust_project_title'].'</td>
						 <td>'.$requisrow['stc_cust_super_requisition_list_items_title'].'</td>
						 <td class="text-center">'.$requisrow['stc_cust_super_requisition_list_items_unit'].'</td>
						 <td class="text-right">'.number_format($requisrow['stc_cust_super_requisition_list_items_approved_qty'], 2).'</td>
						 <td align="right">'.number_format($dispatchedgqty, 2).'</td>
						 <td class="text-center">'.$status.'</td>
						 <td class="text-center" '.$bgcolor.'>'.$priority.'</td>
						 <td>'.$actiondeliver.'</td>
						 <td>'.$log.'</td>
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

		$lokiout.= '
				</tbody>
			</table>
		';
		return $lokiout;
	}

	public function stc_view_agents_requist_dispatch($stc_repid, $stc_repitemid){
		$lokiout='';
		$sqlqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_cust_super_requisition_list_items_rec` LEFT JOIN `stc_purchase_product_adhoc` ON `stc_cust_super_requisition_list_items_rec_list_poaid`=`stc_purchase_product_adhoc_id` WHERE `stc_cust_super_requisition_list_items_rec_list_item_id`='".$stc_repid."' AND `stc_cust_super_requisition_list_items_rec_list_id`='".$stc_repitemid."'
		");
		if(mysqli_num_rows($sqlqry)!=0){
			foreach($sqlqry as $sqlrow){
				$result="";
				if($sqlrow['stc_cust_super_requisition_list_items_rec_list_poaid']==0){
					$result="-";
				}
				$desc=$sqlrow['stc_purchase_product_adhoc_itemdesc'].'<input type="number" class="form-control set-adhoc-id" placeholder="Enter Adhoc ID"><a href="javascript:void(0)" id='.$sqlrow['stc_cust_super_requisition_list_items_rec_id'].' title="Remove" class="updaterecadhoc btn btn-primary">Save</a>';
         	  $lokiout.='<tr>';
         	  $lokiout.='<td class="text-center">'.date('d-m-Y h:i A', strtotime($sqlrow['stc_cust_super_requisition_list_items_rec_date'])).'</td>';
         	  $lokiout.='<td>'.$desc.'</td>';
         	  $lokiout.='<td class="text-right">'.$result.number_format($sqlrow['stc_cust_super_requisition_list_items_rec_recqty'], 2).'</td>';
         	  $lokiout.='<td class="text-center">'.$sqlrow['stc_purchase_product_adhoc_unit'].'</td>';
         	  $lokiout.='<td><a href="javascript:void(0)" id='.$sqlrow['stc_cust_super_requisition_list_items_rec_id'].' title="Remove" class="removeitemsfromdispatch" style="font-size:25px;color:black;"><i class="fas fa-trash"></i></a></td>';
         	  $lokiout.='</tr>';
         	}
		}
		return $lokiout;
	}
	public function stc_update_req_rec_adhoc_id($stc_repid, $stc_adhoc_id){
		$lokiout='';
		$sqlqry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_cust_super_requisition_list_items_rec` 
			SET `stc_cust_super_requisition_list_items_rec_list_poaid`='".mysqli_real_escape_string($this->stc_dbs, $stc_adhoc_id)."'
			WHERE `stc_cust_super_requisition_list_items_rec_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_repid)."'
		");
		if($sqlqry){
			$lokiout='Success';

		}else{
			$lokiout='Hmmm!!! Something went wrong on updating adhoc ID.';
		}
		return $lokiout;
	}

	// filter requiition by all
	public function stc_getpertrequisition_by_multiple_inp($bjornefilterreqbegdate, $bjornefilterreqenddate, $bjornefilterreqcustomerid, $bjornefilterreqnumber, $bjornefilterreqsitename, $bjornefilterreqmaterials, $bjornefilterrequrl_param){
		$ivar='
			<table class="table table-hover table-bordered form-group call-order">
				<thead>
					<th class="text-center">Requisition ID<br>Requisition Date</th>
					<th class="text-center">Requisition From</th>
					<th class="text-center">Requisition For</th>
					<th class="text-center">Requisition Accepted By</th>
					<th class="text-center">Requisition Status</th>
					<th class="text-center">Action</th>
				</thead>
				<tbody>
		';
			
		$array = array(
			"byreqcustomer" => $bjornefilterreqcustomerid,
			"byreqnumber" => $bjornefilterreqnumber,
			"byreqagentname" => $bjornefilterreqsitename,
			"byreqmaterials" => $bjornefilterreqmaterials
		);

		$reqcustomer='';
		$reqno='';
		$reqagentname='';
		$reqstatus='';
		foreach($array as $key => $value){
			if($array['byreqcustomer']!="NA"){
				$reqcustomer="
					AND `stc_customer_id`='".mysqli_real_escape_string($this->stc_dbs, $array['byreqcustomer'])."'
				";
			}

			if(!empty($array['byreqnumber'])){
				$reqno="
					AND `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $array['byreqnumber'])."'
				";
			}

			if(!empty($array['byreqagentname'])){
				$reqagentname="
					AND `stc_agents_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byreqagentname'])."'
				";
			}

			if($array['byreqmaterials']!="NA"){
				$reqstatus="
					AND `stc_requisition_combiner_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byreqmaterials'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_cust_super_requisition_list_id` DESC';
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
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
			INNER JOIN `stc_customer` 
			ON `stc_cust_project_cust_id`=`stc_customer_id`
			WHERE `stc_cust_super_requisition_list_status`!='1'
			AND `stc_requisition_combiner_id`='".mysqli_real_escape_string($this->stc_dbs, $bjornefilterrequrl_param)."' 
			AND (
		        DATE(`stc_requisition_combiner_date`)
		         BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterreqbegdate)."'
		         AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterreqenddate)."'
		    )".$reqcustomer.$reqno.$reqagentname.$reqstatus.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)!=0){
			foreach($ivarfilterquery as $requisrow){
				if($requisrow['stc_cust_super_requisition_list_status']==1){
				    $reqstatus="PROCESS";
				}elseif($requisrow['stc_cust_super_requisition_list_status']==2){
				    $reqstatus="PASSED";
				}elseif($requisrow['stc_cust_super_requisition_list_status']==3){
				    $reqstatus="COMPLETED";
				}else{
				    $reqstatus="ACCEPTED";
				}
				$badgeurgent='<span class="urgent" style="position: relative;display: inline-block;top: -10px;padding: 1px 3px;font-size: 10px;font-weight: bold;color: #fff;background-color: #dc3545; border-radius: 15px;">Urgent</span>';
				$chursql=mysqli_query($this->stc_dbs, "
					SELECT `stc_cust_super_requisition_items_priority` FROM `stc_cust_super_requisition_list_items` WHERE `stc_cust_super_requisition_list_items_req_id`='".$requisrow['stc_cust_super_requisition_list_id']."' AND `stc_cust_super_requisition_items_priority`=2
				");
				if(mysqli_num_rows($chursql)==0){
					$badgeurgent="";
				}
				$ivar.= '
					<tr>
						 <td class="text-center">
						 	'.$requisrow['stc_cust_super_requisition_list_id'].'<br>
						 	'.date('d-m-Y', strtotime($requisrow['stc_cust_super_requisition_list_date'])).'
						 </td>
						 <td class="text-center">'.$requisrow['stc_cust_pro_supervisor_fullname'].'</td>
						 <td>'.$requisrow['stc_cust_project_title'].'</td>
						 <td class="text-center">'.$requisrow['stc_agents_name'].'</td>
						 <td class="text-center">'.$reqstatus.$badgeurgent.'</td>
						 <td class="text-center">
						 	<a 
						 		class="stc_view_requist" 
						 		href="#" 
						 		id="'.$requisrow["stc_cust_super_requisition_list_id"].'" 
						 		style="font-size: 25px;color: black;"
							><i class="fa fa-eye" aria-hidden="true"></i>
						 	</a>
						 </td>
					</tr>
				';
			}
		}else{
			$ivar.= '
				<tr>
					<td colspan="6">
					    <b>No Requisition Found!!!</b>
					</td>
				</tr>
			';
		}

		$ivar.= '
				</tbody>
			</table>
		';
		return $ivar;
	}

	// call perticular requisition list items
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
				    `stc_cust_super_requisition_list_items_approved_qty`,
				    `stc_cust_super_requisition_items_priority`,
				    `stc_cust_super_requisition_list_items_status`
				FROM `stc_cust_super_requisition_list_items` 
				WHERE `stc_cust_super_requisition_list_items_req_id`='".$stc_agorder_id."'
			");
			$order_line_items .= '  
					<table class="table table-hover table-bordered">
						<thead>
						  <tr>
						    <th class="text-center" scope="col">#</th>
						    <th class="text-center" scope="col" style="width: 20%;">Items Desc</th>
						    <th class="text-center" scope="col">Unit</th>
						    <th class="text-center" scope="col">Ordered Qty</th> 
						    <th class="text-center" scope="col">Approved Qty</th> 
						    <th class="text-center" scope="col">GST Qty</th>  
						    <th class="text-center" scope="col">Dispatched Qty</th> 
						    <th class="text-center" scope="col">Status</th>   
						    <th class="text-center" scope="col">Priority</th>  
						    <th class="text-center" scope="col">Action</th>
						  </tr>
						</thead>
						<tbody>
			';  
			if(mysqli_num_rows($nested_check_loki)>0){ 
				$slno= 0;				
				foreach($nested_check_loki as $dispatchrow){
					$slno++;
					$lokirecqtycheckqry=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_cust_super_requisition_list_items_rec_list_item_id`,
							`stc_product_name`,
							SUM(`stc_cust_super_requisition_list_items_rec_recqty`) as recqty,
                            SUM(`stc_sale_product_items_product_qty`) as chalqty
						FROM `stc_cust_super_requisition_list_items_rec`
						INNER JOIN `stc_product`
						ON `stc_product_id`=`stc_cust_super_requisition_list_items_rec_list_pd_id`
                        INNER JOIN `stc_sale_product` 
                        ON `stc_sale_product_order_id`=`stc_cust_super_requisition_list_items_rec_list_id` 
                        INNER JOIN `stc_sale_product_items` 
                        ON `stc_sale_product_items_sale_product_id`=`stc_sale_product_id` 
						WHERE
							`stc_cust_super_requisition_list_items_rec_list_item_id`='".$dispatchrow['stc_cust_super_requisition_list_id']."'
					");
					$status='';
					if($dispatchrow["stc_cust_super_requisition_list_items_status"]==1){
						$status='By GST';
					}else{
						$status='By Normal';
					}
					$getdispatchedtransformers=mysqli_query($this->stc_dbs, "
						SELECT 
							SUM(`stc_cust_super_requisition_list_items_rec_recqty`) AS dispatched_qty
						FROM `stc_cust_super_requisition_list_items_rec` 
						WHERE `stc_cust_super_requisition_list_items_rec_list_item_id`='".$dispatchrow['stc_cust_super_requisition_list_id']."'
					");
					$dispatchedgqty=0;
					foreach($getdispatchedtransformers as $decqtyrow){
						$dispatchedgqty+=$decqtyrow['dispatched_qty'];
					}

					$lokigetappritemqry=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_product_name`,
							SUM(`stc_cust_super_requisition_list_purchaser_qty`) as stc_appr_qty 
						FROM 
							`stc_cust_super_requisition_list_purchaser` 
						INNER JOIN 
							`stc_product` 
						ON 
							`stc_product_id`=`stc_cust_super_requisition_list_purchaser_pd_id` 
						WHERE 
							`stc_cust_super_requisition_list_purchaser_list_item_id`='".$dispatchrow['stc_cust_super_requisition_list_id']."'
					");
					$apprpd_name='';
					$apprpd_qty=0;
					foreach($lokigetappritemqry as $lokigetappritemrow){
						$apprpd_name=$lokigetappritemrow['stc_product_name'];
						$apprpd_qty=$lokigetappritemrow['stc_appr_qty'];
					}
					if(mysqli_num_rows($lokirecqtycheckqry)>0){
						foreach($lokirecqtycheckqry as $requistitemrow){
							$checkqty=$dispatchrow["stc_cust_super_requisition_list_items_approved_qty"] - $dispatchedgqty;
							$actiondeliver='<a class="req-product-Modal" title="Dispatch by inventory" id="'.$dispatchrow["stc_cust_super_requisition_list_id"].'" list-id="'.$dispatchrow["stc_cust_super_requisition_list_items_req_id"].'" href="#"><i class="fa fa-truck"></i></a>
								<a class="req-product-Modal-cash-close" title="Dispatch by direct" id="'.$dispatchrow["stc_cust_super_requisition_list_id"].'" list-id="'.$dispatchrow["stc_cust_super_requisition_list_items_req_id"].'" orderqty="'.$checkqty.'" href="#"><i class="fa fa-file"></i></a>';
							$actiondeliver=$dispatchrow["stc_cust_super_requisition_list_items_approved_qty"]>$dispatchedgqty ? $actiondeliver : ""; 
							$priority=$dispatchrow['stc_cust_super_requisition_items_priority']==2 ? "Urgent" : "Normal";
							$bgcolor=$dispatchrow['stc_cust_super_requisition_items_priority']==2 ? "style='background:#ffb0b0;'" : "";
							$order_line_items .= '
								<tr>
									<td>'.$slno.'</td>
									<td>
										<ul>
											<li> '.$dispatchrow["stc_cust_super_requisition_list_items_title"].'</li>
											<li> '.$apprpd_name.'</li>
											<li> '.$requistitemrow["stc_product_name"].'</li>
										</ul>
									</td>
									<td class="text-center">'.$dispatchrow["stc_cust_super_requisition_list_items_unit"].'</td>
								    <td align="right">'.number_format($dispatchrow["stc_cust_super_requisition_list_items_approved_qty"], 2).'</td>
									<td align="right">'.number_format($apprpd_qty, 2).'</td>
									<td align="right">'.number_format($dispatchedgqty, 2).'</td>
									<td align="right">'.number_format($dispatchedgqty, 2).'</td>
									<td>'.$status.'</td>
									<td class="text-center" '.$bgcolor.'>'.$priority.'</td>
									<td colspan="3" class="text-center">
										<h4>
											'.$actiondeliver.'
										</h4>
									</td>
								</tr>
							';
						}
					}else{
						$order_line_items .= '
							<tr>
								<td>'.$slno.'</td>
								<td>
									> '.$dispatchrow["stc_cust_super_requisition_list_items_title"].'<br>
									> '.$apprpd_name.'<br>
								</td>
								<td class="text-center">'.$dispatchrow["stc_cust_super_requisition_list_items_unit"].'</td>
								<td align="right">'.number_format($dispatchrow["stc_cust_super_requisition_list_items_approved_qty"], 2).'</td>
								<td align="right">'.number_format($apprpd_qty, 2).'</td>
								<td align="right">'.number_format($dispatchedgqty, 2).'</td>
								<td>'.$status.'</td>
								<td colspan="3">
									<h4>
										<a class="req-product-Modal" id="'.$dispatchrow["stc_cust_super_requisition_list_id"].'" list-id="'.$dispatchrow["stc_cust_super_requisition_list_items_req_id"].'" href="#"><i class="fa fa-truck"></i></a>
										<a class="req-product-Modal-cash-close" id="'.$dispatchrow["stc_cust_super_requisition_list_id"].'" list-id="'.$dispatchrow["stc_cust_super_requisition_list_items_req_id"].'" href="#"><i class="fa fa-file"></i></a>
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

	// filter product by all
	public function stc_getpd_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout){
		$ivar='';
			
		$array = array(
			"bycat" => $bjornefiltercatout,
			"bysubcat" => $bjornefiltersubcatout,
			"byname" => $bjornefilternameout
		);
		$category='';
		$subcategory='';
		$productname='';
		foreach($array as $key => $value){
			if($array['bycat']!="NA"){
				$category="
					AND `stc_product_cat_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bycat'])."'
				";
			}

			if($array['bysubcat']!="NA"){
				$subcategory="
					AND `stc_product_sub_cat_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bysubcat'])."'
				";
			}

			if(!empty($array['byname'])){
				$productname="
					AND (`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byname'])."'
					OR 
					`stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byname'])."')
				";
			}
		}
		$endfilterqry='ORDER BY `stc_product_id` ASC LIMIT 0,30';

		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT * FROM `stc_product` 
			LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
			LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
			LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
			LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
			LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
			WHERE `stc_product_avail`='1' 
			AND `stc_item_inventory_pd_qty` != '0' 
			".$category.$subcategory.$productname.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)>0){
			foreach($ivarfilterquery as $filterrow){$loki_findratefrompo=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_purchase_product_items` 
					WHERE `stc_purchase_product_items_product_id`='".$filterrow["stc_product_id"]."' 
					AND `stc_purchase_product_items_id`=(
						SELECT MAX(`stc_purchase_product_items_id`) FROM `stc_purchase_product_items` 
						WHERE `stc_purchase_product_items_product_id`='".$filterrow["stc_product_id"]."'
					)
				");
				$purchase_rate=mysqli_fetch_assoc($loki_findratefrompo);
				$correct_stcpdname=strlen($filterrow["stc_product_name"]);
				$stcqty=$filterrow["stc_item_inventory_pd_qty"];
				if($stcqty==0){
					$stcqty=0;
				}else{
					$stcqty;
				}

				$ivar.='
						<div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
						  	<div class="row">
								<div class="el-wrapper">
								    <div class="box-up">
								        <img class="img" style="height: 100%; width:100%;" src="../stc_symbiote/stc_product_image/'.$filterrow["stc_product_image"].'" alt="">
								        <div class="img-info">
						     	        <div class="info-inner">
						     	            <span class="p-name">'.htmlentities($filterrow["stc_product_name"]).'</span>
						     	            <span class="p-company">'.$filterrow["stc_cat_name"].' / '.$filterrow["stc_product_hsncode"].'</span>
						     	            <span class="p-qty" >'.$stcqty.''.$filterrow["stc_product_unit"].'</span>
						     	            <input 
						         	          	type="number" 
						         	          	name="stcpdreqprice" 
						         	          	id="stcpdreqprice'.$filterrow["stc_product_id"].'" 
						         	          	placeholder="Enter Percent % Below 100"
						         	          	class="form-control validate"
						 	         			value="'.number_format($purchase_rate['stc_purchase_product_items_rate'], 2, '.', '').'" 
						 	         			style="color:black;font-weight:bold;text-align:center"
						 	         			disabled
						         	          />
						         	        <input 
						         	        	type="hidden"
						         	          	id="stcpdinvent'.$filterrow["stc_product_id"].'" 
						         	        	value="'.number_format($stcqty, 2, '.', '').'" 
						         	        />	  
						         	        <input 
						         	    		type="number" 
						         	    		name="stcpdreqperc" 
						         	    		id="stcpdreqperc'.$filterrow["stc_product_id"].'" 
						         	    		placeholder="Enter Percent % Below 100"
						         	    		class="form-control validate" 
						         	    		value="'.number_format($filterrow["stc_product_sale_percentage"], 2).'"
						         	    	/>
						     	        </div>
						 	         	<input 
						 	         		type="hidden" 
						 	         		name="hidden_name" 
						 	         		id="stcpopdname'.$filterrow["stc_product_id"].'" 
						 	         		value="'.htmlentities($filterrow["stc_product_name"]).'" 
						 	         	/>
						 	         	<input 
						 	         		type="hidden" 
						 	         		name="hidden_unit" 
						 	         		id="stcpopdunit'.$filterrow["stc_product_id"].'" 
						 	         		value="'.$filterrow["stc_product_unit"].'" 
						 	         	/>
						 	         	<input 
						 	         		type="hidden" 
						 	         		name="hidden_hsncode" 
						 	         		id="stcpopdhsncode'.$filterrow["stc_product_id"].'" 
						 	         		value="'.$filterrow["stc_product_hsncode"].'" 
						 	         	/>
						 	         	<input 
						 	         		type="hidden" 
						 	         		name="hidden_gst" 
						 	         		id="stcpopdgst'.$filterrow["stc_product_id"].'" 
						 	         		value="'.$filterrow["stc_product_gst"].'" 
						 	         	/>
						 	         	<div class="a-size">
						     	        	<input
						                	  id="stcdisqty'.$filterrow["stc_product_id"].'"
						                	  name="stcdisqty"
						                	  type="number"
						                	  placeholder="Dispatch Quantity"
						                	  class="form-control validate"
						                	/>
						     	        	<input
						                	  id="stcreqqty'.$filterrow["stc_product_id"].'"
						                	  name="stcreqqty"
						                	  type="number"
						                	  placeholder="Order Quantity"
						                	  class="form-control validate"
						                	/>
						 	          	</div>
								        </div>
								    </div>

									<div class="box-down">
										<div class="h-bg">
											<div class="h-bg-inner"></div>
										</div>

										<a class="cart add_to_requist_cart" href="#" id="'.$filterrow["stc_product_id"].'">
										<span class="price">'.$filterrow["stc_sub_cat_name"].' / '.$filterrow["stc_brand_title"].'</span>
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
			$ivar .= '
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
					<a 
				      href="#" 
				      class="btn btn-primary btn-block text-uppercase mb-3">
				      No Records Found!!!
				    </a>
				</div>
			';
		}
		return $ivar;
	}

	// show requisition items cart
	public function stc_show_requisition_product(){
		$gamora='
			<table class="table table-hover" align="centre">
				<thead>
				  <tr>
				    <th scope="col">#</th>
				    <th scope="col" style="width: 20%;">Items</th>
				    <th scope="col">Cat</th> 
				    <th scope="col">Unit</th>
				    <th scope="col">Dispatch Qty</th>    
				    <th scope="col">Order Qty</th>                         
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
								<td>'.number_format($requisprorow['product_dis_quantity'], 2).'</td>
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
}

// add requisition perticular class
class ragnarRequisitionPertAdd extends tesseract{
	// save order to challan
	public function stc_ag_req_go_to_challan($challan_id, $stc_ch_notes, $stc_ch_tandc, $stc_ch_waybilno, $stc_ch_lrno){
		$heimdall='';
		$date=date("Y-m-d H:i:s");
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
				'".$date."', 
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
				'".$_SESSION['stc_empl_id']."'
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
		    			'".$value['product_dis_quantity']."', 
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
					$finalqty = $inventory_pd_qty - $value['product_dis_quantity'];
			    	$friday_loki_inventory=mysqli_query($this->stc_dbs, "
			    		UPDATE `stc_item_inventory` SET `stc_item_inventory_pd_qty`='".$finalqty."' WHERE `stc_item_inventory_pd_id`='".$value['product_id']."'
			    	");
			    }
		    }
		    $odin = "Challan Created!!!";
		}
		return $odin;
	}

	// go to dispatched table
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

	// set status change
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

	// iinsert data into recieving table to dispatch show
	public function stc_ag_req_direct($stc_req_id, $stc_req_item_id, $stc_tools_id, $dispatch_qty, $poadhocitem){
		$loki='';
		if($stc_tools_id!='NA'){
			$getuser=mysqli_query($this->stc_dbs, "SELECT `stc_cust_pro_supervisor_id`, `stc_cust_pro_supervisor_fullname`, `stc_cust_project_title` FROM `stc_cust_super_requisition_list` INNER JOIN `stc_cust_pro_supervisor` ON `stc_cust_pro_supervisor_id`=`stc_cust_super_requisition_list_super_id` INNER JOIN `stc_cust_project` ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id` WHERE `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_req_item_id)."'");
			if(mysqli_num_rows($getuser)>0){
				$user_id=0;
				$username='';
				$location='';
				foreach($getuser as $getuserrows){
					$user_id=$getuserrows['stc_cust_pro_supervisor_id'];
					$username=$getuserrows['stc_cust_pro_supervisor_fullname'];
					$location=$getuserrows['stc_cust_project_title'];
				}
				$date1=date("Y-m-d H:i:s");
				$inserttools=mysqli_query($this->stc_dbs, "INSERT INTO `stc_tooldetails_track`(`toolsdetails_id`, `issuedby`, `user_id`, `status`, `location`, `issueddate`, `created_date`, `created_by`, `id_type`) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $stc_tools_id)."', '".mysqli_real_escape_string($this->stc_dbs, $username)."', '".mysqli_real_escape_string($this->stc_dbs, $user_id)."', '1', '".mysqli_real_escape_string($this->stc_dbs, $location)."', '".$date1."', '".$date1."', '".$_SESSION['stc_empl_id']."', 'vikings')");
				$loki="Hmmm!!! Something went wrong. Dispatched not done properly.";
			}
		}
		$gamorarecgoqry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_cust_super_requisition_list_items_rec`(
				`stc_cust_super_requisition_list_items_rec_list_id`, 
				`stc_cust_super_requisition_list_items_rec_list_item_id`, 
				`stc_cust_super_requisition_list_items_rec_list_pd_id`, 
				`stc_cust_super_requisition_list_items_rec_list_poaid`,
				`stc_cust_super_requisition_list_items_rec_recqty`, 
				`stc_cust_super_requisition_list_items_rec_date`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $stc_req_item_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."',
				'0',
				'".mysqli_real_escape_string($this->stc_dbs, $poadhocitem)."',
				'".mysqli_real_escape_string($this->stc_dbs, $dispatch_qty)."',
				NOW()
			)
		");
		if($gamorarecgoqry){
			$updateqry=mysqli_query($this->stc_dbs, "
				UPDATE `stc_purchase_product_adhoc`
				SET `stc_purchase_product_adhoc_status` = '2'
				WHERE `stc_purchase_product_adhoc_id`='".mysqli_real_escape_string($this->stc_dbs, $poadhocitem)."'
				AND `stc_purchase_product_adhoc_qty`<=(
					SELECT SUM(`stc_cust_super_requisition_list_items_rec_recqty`)
					FROM `stc_cust_super_requisition_list_items_rec`
					WHERE `stc_cust_super_requisition_list_items_rec_list_poaid`=`stc_purchase_product_adhoc_id`
				)
			");
			$gamoraupdateqry=mysqli_query($this->stc_dbs, "
				UPDATE
					`stc_cust_super_requisition_list_items`
				SET
					`stc_cust_super_requisition_list_items_status` = 4
				WHERE
					`stc_cust_super_requisition_list_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."'
				AND `stc_cust_super_requisition_list_items_req_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_req_item_id)."'
			");
			
			$title="Dispatched";
			$message="Dispatched by ".$_SESSION['stc_empl_name']." on ".date('d-m-Y h:i A'). " <br> Quantity :".$dispatch_qty;
			$this->stc_generate_log($stc_req_id, $title, $message);
			if($gamoraupdateqry){
				$loki="Item dispatched successfully.";
			}else{
				$loki="Hmmm!!! Something went wrong. status not changed properly.";
			}
		}else{
			$loki="Hmmm!!! Something went wrong. Dispatched not done properly.";
		}
		return $loki;
	}
	
	public function stc_generate_log($stc_req_id, $title, $message){
		$loki = '';
		$date = date("Y-m-d H:i:s");
		
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_cust_super_requisition_list_items_log`(
				`item_id`, 
				`title`, 
				`message`, 
				`status`, 
				`created_by`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $title)."',
				'".mysqli_real_escape_string($this->stc_dbs, $message)."',
				'1',
				'".$_SESSION['stc_empl_id']."'
			)
		");

		if ($optimusprimequery) {
			$loki = "Log entry created successfully.";
		} else {
			$loki = "Failed to create log entry.";
		}

		return $loki;
	}
	
	public function stc_ag_req_gld($stc_req_id){
		$loki = '';
		$date = date("Y-m-d H:i:s");

		// Check if the requisition_list_id already exists
		$checkQuery = mysqli_query($this->stc_dbs, "
			SELECT COUNT(*) as count 
			FROM `stc_requisition_gld` 
			WHERE `requisition_list_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."'
		");

		$result = mysqli_fetch_assoc($checkQuery);

		// Proceed with the insert only if no matching record is found
		if ($result['count'] == 0) {
			$gamorarecgoqry = mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_requisition_gld` (
					`requisition_list_id`,
					`status`, 
					`created_by`, 
					`created_date`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."',
					'1',
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_empl_id'])."',
					'$date'
				)
			");

			if ($gamorarecgoqry) {
				$loki = "success";
			} else {
				$loki = "Hmmm!!! Something went wrong. Please try again later.";
			}
		} else {
			$loki = "Record already exists.";
		}

		return $loki;
	}

	public function stc_ag_req_remove_dispatched($stc_req_id){
		$loki = '';
		$gamorarecgoqry = mysqli_query($this->stc_dbs, "
			SELECT `stc_cust_super_requisition_list_items_rec_list_item_id`, `stc_cust_super_requisition_list_items_rec_list_item_id`, `stc_cust_super_requisition_list_items_rec_status` FROM `stc_cust_super_requisition_list_items_rec` WHERE `stc_cust_super_requisition_list_items_rec_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."'
		");
		if(mysqli_num_rows($gamorarecgoqry) > 0){
			$result=mysqli_fetch_assoc($gamorarecgoqry);
			$rec_id= $result['stc_cust_super_requisition_list_items_rec_list_item_id'];
			$rec_statusid= $result['stc_cust_super_requisition_list_items_rec_status'];
			$rec_itemid= $result['stc_cust_super_requisition_list_items_rec_list_item_id'];
			$check_dispatched = mysqli_query($this->stc_dbs, "
				SELECT `stc_cust_super_requisition_rec_items_fr_supervisor_id` 
				FROM `stc_cust_super_requisition_rec_items_fr_supervisor` 
				WHERE `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid` = '".mysqli_real_escape_string($this->stc_dbs, $rec_id)."' 
			");
			if($rec_statusid!=2 && $check_dispatched && mysqli_num_rows($check_dispatched) > 0){
				return "This item has already been dispatched. You cannot remove it.".mysqli_fetch_assoc($gamorarecgoqry);
			}
		}
		mysqli_query($this->stc_dbs, "DELETE FROM `stc_cust_super_requisition_rec_items_fr_supervisor` WHERE`stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`='".mysqli_real_escape_string($this->stc_dbs, $rec_itemid)."'");
		$gamorarecgoqry = mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_cust_super_requisition_list_items_rec` WHERE `stc_cust_super_requisition_list_items_rec_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."'
		");
		if ($gamorarecgoqry) {
			$loki = "success";
		} else {
			$loki = "Hmmm!!! Something went wrong. Please try again later.";
		}
		return $loki;
	}
}

// call order requisition notification
class ragnarCallOrderRequisition extends tesseract{
	// check notification
	public function stc_call_order_requisition_badges(){
		$ivar='';
		$ordercount=0;
		$requicount=0;

		$ivarbadgesorderqry=mysqli_query($this->stc_dbs, "
			SELECT `stc_agent_order_id` FROM `stc_agent_order` WHERE `stc_agent_order_status`='PROCESS'
		");
		$ordercount=mysqli_num_rows($ivarbadgesorderqry);

		$ivarbadgesrequiqry=mysqli_query($this->stc_dbs, "
			SELECT `stc_requisition_combiner_id` FROM `stc_requisition_combiner` WHERE `stc_requisition_combiner_status`='1'
		");
		$requicount=mysqli_num_rows($ivarbadgesrequiqry);

		$ivar=array(
			'agent_order' => $ordercount,
			'agent_requisition' => $requicount
		);
		return $ivar;
	}
}

// call items from requisition for item track
class ragnarCallRequisitionItemTrack extends tesseract{
	// call requisition items track
	public function stc_call_requisition_items_track($rit_begdate, $rit_enddate, $rit_type){
		$item_arr = array();
		$odin='
			<table class="table table-hover table-bordered" id="stc-req-item-track-table">
				<thead>
					<tr>
						<th class="text-center">Item Code</th>
						<th class="text-center">Item Type</th>
						<th class="text-center">Item Category</th>
						<th class="text-center" style="width:50%;">Item Desc</th>
						<th class="text-center">Total Quantity</th>
					</tr>
				</thead>
				<tbody>
		';
		$itemtype="
			AND `stc_cust_super_requisition_items_type`='".mysqli_real_escape_string($this->stc_dbs, $rit_type)."'
		";
		if($rit_type=="NA"){
			$itemtype='';
		}
		$odin_getreqqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_product_id`,
				`stc_product_name`,
				`stc_cat_name`,
				`stc_sub_cat_name`,
				`stc_cust_super_requisition_list_purchaser_qty`,
				`stc_cust_super_requisition_items_type`
			FROM `stc_cust_super_requisition_list_purchaser`
			INNER JOIN `stc_product` 
			ON `stc_product_id` =`stc_cust_super_requisition_list_purchaser_pd_id` 
			INNER JOIN `stc_category` 
			ON `stc_product_cat_id` =`stc_cat_id` 
			INNER JOIN `stc_sub_category` 
			ON `stc_product_sub_cat_id` =`stc_sub_cat_id` 
			INNER JOIN `stc_cust_super_requisition_list_items` 
			ON `stc_cust_super_requisition_list_items`.`stc_cust_super_requisition_list_id` =`stc_cust_super_requisition_list_purchaser_list_item_id`
			INNER JOIN `stc_cust_super_requisition_list` 
			ON `stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id` = `stc_cust_super_requisition_list_items_req_id` 
			WHERE (
				DATE(`stc_cust_super_requisition_list_date`) 
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $rit_begdate)."' 
				AND '".mysqli_real_escape_string($this->stc_dbs, $rit_enddate)."'
			) ".$itemtype."
		   	ORDER BY 
		   		`stc_product_name` ASC
		");
	    while ($item_rec =  mysqli_fetch_assoc($odin_getreqqry)){
			if($item_rec["stc_cust_super_requisition_list_purchaser_qty"]>0){
				if(isset($_SESSION["stc_track_req_item_sess"])) {  
					$is_available = 0;
					foreach($_SESSION["stc_track_req_item_sess"] as $keys => $values){  
						if($_SESSION["stc_track_req_item_sess"][$keys]['product_id'] == $item_rec['stc_product_id']){  
							$is_available++;  
							$_SESSION["stc_track_req_item_sess"][$keys]['product_qnty'] = $_SESSION["stc_track_req_item_sess"][$keys]['product_qnty'] + $item_rec['stc_cust_super_requisition_list_purchaser_qty']; 
						}  
					}  
					if($is_available < 1) {  
						$item_array = array(  
							'product_id'				=>     $item_rec["stc_product_id"], 
							'product_category'				=>     $item_rec["stc_cat_name"],
							'product_sub_category'				=>     $item_rec["stc_sub_cat_name"],
							'product_name'						=>     $item_rec["stc_product_name"],
							'product_qnty'					=>     $item_rec["stc_cust_super_requisition_list_purchaser_qty"],
							'product_type'				=>     $item_rec["stc_cust_super_requisition_items_type"]
						);  
						$_SESSION["stc_track_req_item_sess"][] = $item_array; 
					}
				}else{  
					$item_array = array(  
						'product_id'				=>     $item_rec["stc_product_id"], 
						'product_category'				=>     $item_rec["stc_cat_name"],
						'product_sub_category'				=>     $item_rec["stc_sub_cat_name"],
						'product_name'						=>     $item_rec["stc_product_name"],
						'product_qnty'					=>     $item_rec["stc_cust_super_requisition_list_purchaser_qty"],
						'product_type'				=>     $item_rec["stc_cust_super_requisition_items_type"]
					);   
					$_SESSION["stc_track_req_item_sess"][] = $item_array;  
				}  
			}
	    }

	    foreach($_SESSION["stc_track_req_item_sess"] as $fetch_row){
	    	$subcatname='';
	    	if($fetch_row['product_sub_category']=="OTHERS"){
	    		$subcatname='';
	    	}else{
	    		$subcatname=$fetch_row['product_sub_category'];
	    	}
			if($fetch_row['product_qnty']>0){
				$odin.='
						<tr>
							<td class="text-center">'.$fetch_row['product_id'].'</td>
							<td class="text-left">'.strtoupper($fetch_row['product_type']).'</td>
							<td class="text-left">'.$fetch_row['product_category'].'</td>
							<td class="text-left">'.$subcatname.' '.$fetch_row['product_name'].'</td>
							<td class="text-right">'.number_format($fetch_row['product_qnty'], 2).'</td>
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

	// save item tracker 
	public function stc_item_tracker_save($user_id, $ppe_type, $qty, $unit, $issue_date, $validity, $remarks){
		$blackpearl='';
		$date=date("Y-m-d H:i:s");
		$ppe_types = $ppe_type;
		$validities = $validity;
		$units = $unit;

		for($i = 0; $i < count($ppe_types); $i++) {
			$ppe_type = mysqli_real_escape_string($this->stc_dbs, $ppe_types[$i]);
			$validity = mysqli_real_escape_string($this->stc_dbs, $validities[$i]);
			$unit = mysqli_real_escape_string($this->stc_dbs, $units[$i]);

			$blackpearl_qry = mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_item_tracker`(
					`stc_item_tracker_user_id`,
					`stc_item_tracker_toppe`,
					`stc_item_tracker_qty`,
					`stc_item_tracker_unit`,
					`stc_item_tracker_issuedate`,
					`stc_item_tracker_validity`,
					`stc_item_tracker_remarks`,
					`stc_item_tracker_createdby`,
					`stc_item_tracker_created_date`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $user_id)."',
					'".$ppe_type."',
					'1',
					'".mysqli_real_escape_string($this->stc_dbs, $unit)."',
					'".mysqli_real_escape_string($this->stc_dbs, $issue_date)."',
					'".$validity."',
					'".mysqli_real_escape_string($this->stc_dbs, $remarks)."',
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_empl_id'])."',
					'".$date."'
				)
			");
		}
		if($blackpearl_qry){
			$blackpearl="yes";
		}else{
			$blackpearl="no";
		}
		return $blackpearl;
	}

	public function stc_item_tracker_call($search, $page){
		$blackpearl = '';
		$filter = '';
		$limit = 10; // Number of records per page
		$offset = ($page - 1) * $limit;
	
		if(!empty($search)){
			$filter="WHERE 
				`stc_item_tracker_toppe` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_item_tracker_user_id` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_item_tracker_issuedate` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_item_tracker_validity` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR 
				`stc_item_tracker_remarks` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."'";
		}
	
		// Count total records for pagination
		$count_query = "SELECT COUNT(*) as total FROM `stc_item_tracker` ".$filter;
		$count_result = mysqli_query($this->stc_dbs, $count_query);
		$total_records = mysqli_fetch_assoc($count_result)['total'];
		$total_pages = ceil($total_records / $limit);
	
		$blackpearl_query = "
			SELECT
				`stc_item_tracker_id`,
				`stc_item_tracker_user_id`,
				`stc_item_tracker_toppe`,
				`stc_item_tracker_qty`,
				`stc_item_tracker_unit`,
				`stc_item_tracker_issuedate`,
				`stc_item_tracker_validity`,
				`stc_item_tracker_remarks`,
				`stc_item_tracker_createdby`,
				`stc_item_tracker_created_date`
			FROM `stc_item_tracker`
			".$filter."
			ORDER BY TIMESTAMP(`stc_item_tracker_created_date`) DESC
			LIMIT $offset, $limit
		";
		$blackpearl_result = mysqli_query($this->stc_dbs, $blackpearl_query);
	
		// Generate the table rows as before
		$slno = $offset;
		if(mysqli_num_rows($blackpearl_result) > 0){
			foreach($blackpearl_result as $blackpearl_row){
				$slno++;
				// Continue with table rows as before
				$validity = $blackpearl_row['stc_item_tracker_validity'] == 1 ? $blackpearl_row['stc_item_tracker_validity'].' month' : $blackpearl_row['stc_item_tracker_validity']." months";
				$validityMonths = $blackpearl_row['stc_item_tracker_validity'];
				$issuedate = new DateTime($blackpearl_row['stc_item_tracker_issuedate']);
				$nextissuedate = $issuedate->add(new DateInterval("P{$validityMonths}M"));
				$nextissuedateFormatted = $nextissuedate->format('d-m-Y');
	
				$dateofissue = $blackpearl_row['stc_item_tracker_issuedate'] == '' ? '' : date('d-m-Y', strtotime($blackpearl_row['stc_item_tracker_issuedate']));
				$blackpearl .= "
					<tr>
						<td>".$slno."</td>
						<td>".$blackpearl_row['stc_item_tracker_user_id']."</td>
						<td>".$blackpearl_row['stc_item_tracker_toppe']."</td>
						<td class='text-right'>".number_format($blackpearl_row['stc_item_tracker_qty'], 2)."</td>
						<td class='text-center'>".$blackpearl_row['stc_item_tracker_unit']."</td>
						<td class='text-center'>".$dateofissue."</td>
						<td class='text-center'>".$validity."</td>
						<td class='text-center'>".$nextissuedateFormatted."</td>
						<td>".$blackpearl_row['stc_item_tracker_remarks']."</td>
					</tr>
				";
			}
		} else {
			$blackpearl .= "
				<tr>
					<td colspan='7' class='text-center'> No data found!!</td>
				</tr>
			";
		}
	
		// Add pagination controls
		$blackpearl .= "<tr><td colspan='9' class='text-center'>";
		for ($i = 1; $i <= $total_pages; $i++) {
			if($i==$page){
				$blackpearl .= '<a href="javascript:void(0)" style="background: #00ff5a; color: black;padding: 10px;" class="page-link2" data-page="' . $i . '">' . $i . '</a> ';
			}else{
				$blackpearl .= '<a href="javascript:void(0)" class="page-link2" data-page="' . $i . '">' . $i . '</a> ';
			}
			if($i==10){break;}
		}
		$blackpearl .= "</td></tr>";
	
		return $blackpearl;
	}
	
	public function stc_item_tracker_ppesummary() {
		// Initialize an empty result array
		$resultData = [];
	
		// SQL Query to fetch data
		$sql = "
			SELECT 
				`stc_item_tracker_id`, 
				`stc_item_tracker_user_id`, 
				`stc_item_tracker_toppe`, 
				`stc_item_tracker_qty`, 
				`stc_item_tracker_unit`, 
				`stc_item_tracker_issuedate`, 
				`stc_item_tracker_validity`, 
				`stc_item_tracker_remarks`, 
				`stc_item_tracker_createdby`, 
				`stc_item_tracker_created_date`,
				DATE_ADD(`stc_item_tracker_issuedate`, INTERVAL `stc_item_tracker_validity` MONTH) AS `expiration_date`
			FROM 
				`stc_item_tracker` 
			WHERE 
				DATE_ADD(`stc_item_tracker_issuedate`, INTERVAL `stc_item_tracker_validity` MONTH) BETWEEN 
					DATE_FORMAT(CURDATE(), '%Y-%m-01') 
					AND LAST_DAY(CURDATE())
			ORDER BY `stc_item_tracker_toppe` ASC
		";
	
		// Execute the query
		$sqlquery = mysqli_query($this->stc_dbs, $sql);
	
		// Check if query execution was successful
		if ($sqlquery) {
			// Fetch rows into the result array
			while ($row = mysqli_fetch_assoc($sqlquery)) {
				$resultData[] = $row;
			}
		} else {
			// Log or handle errors if the query fails
			error_log("Query failed: " . mysqli_error($this->stc_dbs));
		}
	
		// Return the result array
		return $resultData;
	}	

	// save tool track
	public function stc_tool_tracker_save($unique, $itemdescription, $machineslno, $make, $type, $warranty, $purdetails, $tinnumber, $tindate, $remarks){
		$date=date("Y-m-d H:i:s");
	
		// Check for duplicate unique ID
		$duplicate_check_qry = mysqli_query($this->stc_dbs, "
			SELECT `unique_id` FROM `stc_tooldetails` WHERE `unique_id` = '".mysqli_real_escape_string($this->stc_dbs, $unique)."'
		");
		// If a duplicate is found, set $blackpearl to "duplicate"
		if(mysqli_num_rows($duplicate_check_qry) > 0) {
			$blackpearl = "duplicate";
		} else {
			$query="
				INSERT INTO `stc_tooldetails`(
					`unique_id`,
					`itemdescription`,
					`machinesrno`,
					`make`,
					`tooltype`,
					`purchase_details`,
					`warranty`,
					`taxinvono`,
					`taxinvodate`,
					`remarks`,
					`created_by`,
					`created_date`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $unique)."',
					'".mysqli_real_escape_string($this->stc_dbs, $itemdescription)."',
					'".mysqli_real_escape_string($this->stc_dbs, $machineslno)."',
					'".mysqli_real_escape_string($this->stc_dbs, $make)."',
					'".mysqli_real_escape_string($this->stc_dbs, $type)."',
					'".mysqli_real_escape_string($this->stc_dbs, $warranty)."',
					'".mysqli_real_escape_string($this->stc_dbs, $purdetails)."',
					'".mysqli_real_escape_string($this->stc_dbs, $tinnumber)."',
					'".mysqli_real_escape_string($this->stc_dbs, $tindate)."',
					'".mysqli_real_escape_string($this->stc_dbs, $remarks)."',
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_empl_id'])."',
					'".$date."'
				)
			";
			$blackpearl_qry = mysqli_query($this->stc_dbs, $query);
	
			// If the INSERT query is successful, set $blackpearl to "yes"
			if($blackpearl_qry) {
				$blackpearl = "yes";
			} else {
				// If the INSERT query fails, set $blackpearl to "no"
				$blackpearl = "no";
			}
		}
	
		return $blackpearl;
	}

	// show tool track
	public function stc_tool_tracker_get($search, $page){
		$filter = " WHERE `unique_id` = '".mysqli_real_escape_string($this->stc_dbs, $search)."' 
			OR `itemdescription` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' 
			OR `machinesrno` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' 
			OR `make` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' 
			OR `tooltype` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' 
			OR `purchase_details` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' 
			OR `taxinvono` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' ";
		
		$search = $search == '' ? '' : $filter;
	
		// Pagination variables
		$limit = 10; // Number of records per page
		$offset = ($page - 1) * $limit;
	
		// Total record count for pagination
		$count_query = "SELECT COUNT(*) as total FROM `stc_tooldetails` ".$search;
		$count_result = mysqli_query($this->stc_dbs, $count_query);
		$total_records = mysqli_fetch_assoc($count_result)['total'];
		$total_pages = ceil($total_records / $limit);
	
		// Main query with limit and offset
		$blackpearl_qry = mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_tooldetails` 
			LEFT JOIN `stc_user` ON `stc_tooldetails`.`created_by` = `stc_user`.`stc_user_id` 
			".$search."
			ORDER BY `id` DESC
			LIMIT $offset, $limit
		");
	
		$blackpearl = [];
		if(mysqli_num_rows($blackpearl_qry) > 0){
			$i=0;
			while ($blackpearl_row = mysqli_fetch_assoc($blackpearl_qry)) {
				$blackpearl[$i] = $blackpearl_row;
				$blackpearl[$i]['created_dates'] = $blackpearl_row['created_date'] == '' ? '' : date('d-m-Y h:i A', strtotime($blackpearl_row['created_date']));
				$id=$blackpearl_row['id'];
				$blackpearl_qry2 = mysqli_query($this->stc_dbs, "
					SELECT id FROM `stc_tooldetails_track` WHERE `toolsdetails_id` = '".mysqli_real_escape_string($this->stc_dbs, $id)."' 
				");
				$blackpearl[$i]['status'] = 'Inactive';
				if(mysqli_num_rows($blackpearl_qry2) > 0){
					$blackpearl[$i]['status'] = 'Active';
				}
				$i++;
			}
		}
	
		return [
			'data' => $blackpearl,
			'total_pages' => $total_pages
		];
	}

	// for edit
	public function stc_tool_tracker_edit($itt_id, $unique, $itemdescription, $machineslno, $make, $type, $warranty, $purdetails, $tinnumber, $tindate, $remarks){
		$blackpearl='';
		$blackpearl_qry=mysqli_query($this->stc_dbs, "UPDATE `stc_tooldetails` SET `unique_id`='".mysqli_real_escape_string($this->stc_dbs, $unique)."', `itemdescription`='".mysqli_real_escape_string($this->stc_dbs, $itemdescription)."', `machinesrno`='".mysqli_real_escape_string($this->stc_dbs, $machineslno)."', `make`='".mysqli_real_escape_string($this->stc_dbs, $make)."', `tooltype`='".mysqli_real_escape_string($this->stc_dbs, $type)."', `warranty`='".mysqli_real_escape_string($this->stc_dbs, $warranty)."', `purchase_details`='".mysqli_real_escape_string($this->stc_dbs, $purdetails)."', `taxinvono`='".mysqli_real_escape_string($this->stc_dbs, $tinnumber)."', `taxinvodate`='".mysqli_real_escape_string($this->stc_dbs, $tindate)."', `remarks`='".mysqli_real_escape_string($this->stc_dbs, $remarks)."' WHERE `id`='".mysqli_real_escape_string($this->stc_dbs, $itt_id)."'");
		if($blackpearl_qry){
			$blackpearl="yes";
		}else{
			$blackpearl="no";
		}
		return $blackpearl;
	}

	// save tracking
	public function stc_tool_trackertrack_save($issuedby, $location, $date, $receivedby, $handoverto, $itt_id){
		$blackpearl='';
		$user_id=$issuedby;
		$sqlcheck=mysqli_query($this->stc_dbs, "SELECT `stc_cust_pro_supervisor_id`, `stc_cust_pro_supervisor_fullname` FROM `stc_cust_pro_supervisor` WHERE `stc_cust_pro_supervisor_contact`='".mysqli_real_escape_string($this->stc_dbs, $issuedby)."'");
		if(mysqli_num_rows($sqlcheck)>0){
			$issedbyname='';
			foreach($sqlcheck as $sqlcheckrow){
				$issedbyname=$sqlcheckrow['stc_cust_pro_supervisor_fullname'];
				$user_id=$sqlcheckrow['stc_cust_pro_supervisor_id'];
			}
			$date1=date("Y-m-d H:i:s");// Check if a record exists for the given toolsdetails_id
			$check_qry = mysqli_query($this->stc_dbs, "SELECT `id` FROM `stc_tooldetails_track` WHERE `toolsdetails_id` = '".mysqli_real_escape_string($this->stc_dbs, $itt_id)."' ORDER BY TIMESTAMP(`created_date`) DESC LIMIT 1");
			
			if (mysqli_num_rows($check_qry) > 0) {
				// Get the most recent record
				$record = mysqli_fetch_assoc($check_qry);
			
				// Update the handoverto field of the most recent record
				$update_qry = mysqli_query($this->stc_dbs, "UPDATE stc_tooldetails_track SET handoverto = '".mysqli_real_escape_string($this->stc_dbs, $issuedby)."' WHERE id = '".mysqli_real_escape_string($this->stc_dbs, $record['id'])."'");
			}
			
			// Insert the new record
			$blackpearl_qry = mysqli_query($this->stc_dbs, "INSERT INTO stc_tooldetails_track (toolsdetails_id, issuedby, user_id, status, location, issueddate, receivedby, `handoverto`, created_date, created_by, id_type) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $itt_id)."', '".mysqli_real_escape_string($this->stc_dbs, $issedbyname)."', '".mysqli_real_escape_string($this->stc_dbs, $user_id)."', '0', '".mysqli_real_escape_string($this->stc_dbs, $location)."', '".mysqli_real_escape_string($this->stc_dbs, $date)."', '".mysqli_real_escape_string($this->stc_dbs, $receivedby)."', '', '".mysqli_real_escape_string($this->stc_dbs, $date1)."', '".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_empl_id'])."', 'vikings')");

			if($blackpearl_qry){
				$blackpearl='yes';
			}else{
				$blackpearl='no';
			}
		}else{
			$blackpearl='notfound';
		}
		return $blackpearl;
	}

	// show tool track
	public function stc_tool_trackertrack_get($itt_id){	
		// Check for duplicate unique ID
		$blackpearl_qry = mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_tooldetails_track` tt LEFT JOIN `stc_tooldetails` t ON t.`id`=tt.`toolsdetails_id` WHERE tt.`toolsdetails_id`='".mysqli_real_escape_string($this->stc_dbs, $itt_id)."' ORDER BY TIMESTAMP(tt.`issueddate`) DESC
		");
		$blackpearl=[];
		if(mysqli_num_rows($blackpearl_qry)>0){
			$i=0;
			while ($blackpearl_row = mysqli_fetch_assoc($blackpearl_qry)) {
				$blackpearl[] = $blackpearl_row;
				$username='';
				$created_by=$blackpearl_row['created_by'];
				if($blackpearl_row['id_type']=="vikings"){
					$query=mysqli_query($this->stc_dbs, "SELECT `stc_user_name` as name FROM `stc_user` WHERE `stc_user_id`=$created_by");
					$result=mysqli_fetch_assoc($query);
					$username=$result['name'];
				}else if($blackpearl_row['id_type']=="subagent"){
					$query=mysqli_query($this->stc_dbs, "SELECT `stc_cust_pro_supervisor_fullname` as name FROM `stc_cust_pro_supervisor` WHERE `stc_cust_pro_supervisor_id`=$created_by");
					$result=mysqli_fetch_assoc($query);
					$username=$result['name'];
				}
				$blackpearl[$i]['name'] = $username;
				$i++;
			}
		}
	
		return $blackpearl;
	}
}

// call items from requisition for item track
class ragnarCallB2COrders extends tesseract{
	// show b2corders
	public function stc_call_B2COrders($searchQuery = '', $page = 1, $perPage = 10) {
		// Calculate offset for pagination
		$offset = ($page - 1) * $perPage;
	
		// Base query
		$query = "
			SELECT * FROM `orders`
			LEFT JOIN `stc_product` ON product_id = stc_product_id
			LEFT JOIN `stc_sub_category` ON stc_product_sub_cat_id = stc_sub_cat_id
		";
	
		// Add search condition if search query is provided
		if (!empty($searchQuery)) {
			$query .= "
				WHERE (email LIKE '%$searchQuery%'
				OR first_name LIKE '%$searchQuery%'
				OR last_name LIKE '%$searchQuery%'
				OR phone_number LIKE '%$searchQuery%'
				OR stc_product_name LIKE '%$searchQuery%')
			";
		}
	
		// Add order by and limit for pagination
		$query .= " ORDER BY TIMESTAMP(`created_at`) DESC LIMIT $perPage OFFSET $offset";
	
		// Execute the query
		$blackpearl_qry = mysqli_query($this->stc_dbs, $query);
		$blackpearl = [];
	
		if (mysqli_num_rows($blackpearl_qry) > 0) {
			$i = 0;
			while ($blackpearl_row = mysqli_fetch_assoc($blackpearl_qry)) {
				$blackpearl_qrye = mysqli_query($this->stc_dbs, "SELECT `stc_purchase_product_adhoc_id`, `stc_purchase_product_adhoc_productid`, `stc_purchase_product_adhoc_qty` FROM `stc_purchase_product_adhoc` WHERE `stc_purchase_product_adhoc_productid`= '".$blackpearl_row['product_id']."' AND `stc_purchase_product_adhoc_status`='1'");
				if(mysqli_num_rows($blackpearl_qrye)==0){
					$blackpearl_row['status'] = '4';
				}else{
					$gldQty = 0;
					$directqty = 0;
					$adhocqty = 0;
					foreach($blackpearl_qrye as $blackpearl_row2){
						$adhocqty+=$blackpearl_row2['stc_purchase_product_adhoc_qty'];
						$query = mysqli_query($this->stc_dbs, "SELECT SUM(`qty`) AS total_qty FROM `gld_challan` WHERE `product_id` = '".$blackpearl_row['product_id']."'");
						$result = mysqli_fetch_assoc($query);
						$gldQty = $result['total_qty'] ?? 0;
						
						$sql_qry = mysqli_query($this->stc_dbs, "
							SELECT SUM(stc_cust_super_requisition_list_items_rec_recqty) AS total_qty stc_cust_super_requisition_list_items_rec stc_cust_super_requisition_list_items_rec_list_poaid WHERE spa.stc_cust_super_requisition_list_items_rec_list_poaid = '".$blackpearl_row['product_id']."'
						");
						if ($sql_qry && mysqli_num_rows($sql_qry) > 0) {
							$row1 = mysqli_fetch_assoc($sql_qry);
							$directqty = $row1['total_qty'] ?? 0;
						}
					}
					if($adhocqty - ($gldQty + $directqty)==0){
						// $blackpearl_row['status'] = '4';
					}
				}
				$product_name = $blackpearl_row['stc_product_name'];
				if ($blackpearl_row['stc_sub_cat_name'] != "OTHERS") {
					$product_name = $blackpearl_row['stc_sub_cat_name'] . ' ' . $blackpearl_row['stc_product_name'];
				}
				$blackpearl_row['stc_product_name'] = $product_name;
				$blackpearl_row['created_at'] = date('d-m-Y H:i:s a', strtotime($blackpearl_row['created_at']));
				$total=$blackpearl_row['quantity'] * $blackpearl_row['rate'];
				$blackpearl_row['rate'] = number_format($blackpearl_row['rate'], 2);
				$blackpearl_row['quantity'] = number_format($blackpearl_row['quantity'], 2);
				$blackpearl_row['total'] = number_format($total, 2);
				$blackpearl[] = $blackpearl_row;
			}
		}
	
		// Get total count for pagination
		$countQuery = "SELECT COUNT(*) as total FROM `orders`";
		if (!empty($searchQuery)) {
			$countQuery .= "
				WHERE (email LIKE '%$searchQuery%'
				OR first_name LIKE '%$searchQuery%'
				OR last_name LIKE '%$searchQuery%'
				OR phone_number LIKE '%$searchQuery%')
			";
		}
		$countResult = mysqli_query($this->stc_dbs, $countQuery);
		$totalCount = mysqli_fetch_assoc($countResult)['total'];
	
		return [
			'data' => $blackpearl,
			'total' => $totalCount,
			'page' => $page,
			'perPage' => $perPage
		];
	}

	public function stc_action_order($id, $status){	
		$blackpearl_qry = mysqli_query($this->stc_dbs, "
			UPDATE `orders` SET `status`='".mysqli_real_escape_string($this->stc_dbs, $status)."', `handled_by`='".$_SESSION['stc_empl_id']."' WHERE `id`='".mysqli_real_escape_string($this->stc_dbs, $id)."'
		");
		$blackpearl="no";
		if($blackpearl_qry){
			$blackpearl="success";
			if($status==3){
				// Fetch the updated order details
				$orderQuery = mysqli_query($this->stc_dbs, "
					SELECT `id`, `first_name`, `last_name`, `email`, `phone_number`, `street_address`, `city`, `zipCode`, `state`, `product_id`, `order_number`, `status`, `quantity`, `rate`, `handled_by`, `created_at` FROM `orders` WHERE `id`='" . mysqli_real_escape_string($this->stc_dbs, $id) . "'
				");
			
				if (mysqli_num_rows($orderQuery) > 0) {
					$orderData = mysqli_fetch_assoc($orderQuery);
			
					// Insert into gld_challan table
					$insertChallanQuery = mysqli_query($this->stc_dbs, "
						INSERT INTO `gld_challan` (`product_id`, `cust_id`, `challan_number`, `qty`, `rate`, `created_date`, `created_by`) 
						VALUES (
							'" . mysqli_real_escape_string($this->stc_dbs, $orderData['product_id']) . "',
							'" . mysqli_real_escape_string($this->stc_dbs, $orderData['id']) . "',
							'" . mysqli_real_escape_string($this->stc_dbs, $orderData['order_number']) . "',
							'" . mysqli_real_escape_string($this->stc_dbs, $orderData['quantity']) . "',
							'" . mysqli_real_escape_string($this->stc_dbs, $orderData['rate']) . "',
							NOW(),
							'" . $_SESSION['stc_empl_id'] . "'
						)
					");
				} else {
					echo "Order not found!";
				}
			}
		}
	
		return $blackpearl;
	}

	public function stc_call_advanceorderlist(){
		$blackpearl_qry = mysqli_query($this->stc_dbs, "
			SELECT 
				p.`stc_product_id`, 
				psc.`stc_sub_cat_name`, 
				p.`stc_product_name`, 
				p.`stc_product_unit`, 
				COUNT(*) AS total_count
			FROM (
				SELECT `stc_cust_super_requisition_list_items_rec_list_pd_id` AS product_id
				FROM `stc_cust_super_requisition_list_items_rec`
				
				UNION ALL
				
				SELECT `stc_cust_super_requisition_list_purchaser_pd_id` AS product_id
				FROM `stc_cust_super_requisition_list_purchaser`
			) AS combined
			JOIN `stc_product` p ON combined.product_id = p.`stc_product_id`
			JOIN `stc_sub_category` psc ON p.stc_product_sub_cat_id = psc.`stc_sub_cat_id`
			GROUP BY p.`stc_product_id`, p.`stc_product_name`
			HAVING COUNT(*) >=10 ORDER BY p.`stc_product_name` ASC;
		");
		$blackpearl = [];
		while ($blackpearl_row = mysqli_fetch_assoc($blackpearl_qry)) {
			$blackpearl_row['stc_product_name']=$blackpearl_row['stc_product_name'];
			if($blackpearl_row['stc_sub_cat_name']!="OTHERS"){
				$blackpearl_row['stc_product_name']=$blackpearl_row['stc_sub_cat_name'].' '.$blackpearl_row['stc_product_name'];
			}
			$query = mysqli_query($this->stc_dbs, "SELECT SUM(`stc_purchase_product_adhoc_qty`) AS total_qty FROM `stc_purchase_product_adhoc` WHERE `stc_purchase_product_adhoc_productid` = " . $blackpearl_row['stc_product_id']);
			$result=mysqli_fetch_assoc($query);
			$adhocQty=$result['total_qty'];
			$query = mysqli_query($this->stc_dbs, "SELECT SUM(`qty`) AS total_qty FROM `gld_challan` WHERE `product_id` = " . $blackpearl_row['stc_product_id']);
			$result=mysqli_fetch_assoc($query);
			$gldQty=$result['total_qty'];
			$directqty=0;
			$sql_qry=mysqli_query($this->stc_dbs, "
				SELECT `stc_purchase_product_adhoc_id` 
				FROM `stc_purchase_product_adhoc` 
				WHERE `stc_purchase_product_adhoc_productid`='".$blackpearl_row['stc_product_id']."'
			");
			if(mysqli_num_rows($sql_qry)>0){
				foreach($sql_qry as $sql_row){
					$sql_qry2=mysqli_query($this->stc_dbs, "
						SELECT `stc_cust_super_requisition_list_items_rec_recqty` 
						FROM `stc_cust_super_requisition_list_items_rec` 
						WHERE `stc_cust_super_requisition_list_items_rec_list_poaid`='".$sql_row['stc_purchase_product_adhoc_id']."'
					");
					if(mysqli_num_rows($sql_qry2)>0){
						foreach($sql_qry2 as $sql_row2){
							$directqty+=$sql_row2['stc_cust_super_requisition_list_items_rec_recqty'];
						}
					}
				}
			}
			$remainingqty=$adhocQty - ($gldQty + $directqty);
			if($remainingqty<5){
				$blackpearl_row['remaining_qty']=number_format($remainingqty, 2);
				$blackpearl[] = $blackpearl_row;
			}
			
		}
		return $blackpearl;
	}
}

class ragnarCallGLDRequisitions extends tesseract{
	
	public function stc_call_gld_requisitions($search = '', $page = 1, $limit = 25, $offset = 0){
		$where = "";
		if($search != ''){
			$where = "WHERE name LIKE '%".mysqli_real_escape_string($this->stc_dbs, $search)."%' 
					  OR unit LIKE '%".mysqli_real_escape_string($this->stc_dbs, $search)."%' 
					  OR remarks LIKE '%".mysqli_real_escape_string($this->stc_dbs, $search)."%'
					  OR stc_trading_user_location LIKE '%".mysqli_real_escape_string($this->stc_dbs, $search)."%' ";
		}
		
		// Get total count for pagination
		$countQuery = "SELECT COUNT(*) as total FROM gld_requisitions  INNER JOIN stc_trading_user ON gld_requisitions.created_by = stc_trading_user.stc_trading_user_id ".$where;
		$countResult = mysqli_query($this->stc_dbs, $countQuery);
		$total = mysqli_fetch_assoc($countResult)['total'];
		
		// Get records with pagination
		$query = "SELECT * FROM gld_requisitions INNER JOIN stc_trading_user ON gld_requisitions.created_by = stc_trading_user.stc_trading_user_id ".$where." ORDER BY id DESC LIMIT $offset, $limit";
		$result = mysqli_query($this->stc_dbs, $query);
		
		$records = [];
		while($row = mysqli_fetch_assoc($result)){
			// Map status to text for frontend
			$statusText = '';
			switch((int)$row['status']){
				case 1: $statusText = 'Request'; break;
				case 2: $statusText = 'Accepted'; break;
				case 3: $statusText = 'Dispatched'; break;
				case 4: $statusText = 'Received'; break;
				default: $statusText = 'Unknown';
			}
			$row['status_text'] = $statusText;
			$buyStatusText = '';
			switch((int)$row['buy_status']){
				case 1: $buyStatusText = 'Pending'; break;
				case 2: $buyStatusText = 'Purchased'; break;
				case 3: $buyStatusText = 'Recieved'; break;
				default: $buyStatusText = 'NA';
			}
			$row['buyStatus_text'] = $buyStatusText;
			$records[] = $row;
		}
		
		return [
			'records' => $records,
			'total' => $total,
			'page' => $page,
			'limit' => $limit,
			'total_pages' => ceil($total / $limit)
		];
	}
	
	public function stc_update_gld_requisition_status($id, $status){
		if($id <= 0 || $status <= 0){
			return ['success' => false, 'message' => 'Invalid parameters'];
		}
		if($status==1){			
			$query = "UPDATE gld_requisitions SET status = $status WHERE id = $id";
			if(mysqli_query($this->stc_dbs, $query)){
				return ['success' => true, 'message' => 'Status updated successfully'];
			} else {
				return ['success' => false, 'message' => 'Failed to update status'];
			}
		}
		$query=mysqli_query($this->stc_dbs, "SELECT product_id FROM gld_requisitions WHERE id = $id");
		$product_id = 0;
		if($row=mysqli_fetch_assoc($query)){
			$product_id = $row['product_id'];
		} else {
			return ['success' => false, 'message' => 'Requisition not found'];

		}
		$searchQuery = " AND (P.stc_product_id = '$product_id')";
		// Main data with subquery to calculate remaining qty
		$baseSql = "
			FROM stc_product P
			LEFT JOIN stc_brand B ON B.stc_brand_id = P.stc_product_brand_id 
			LEFT JOIN (
				SELECT 
					stc_purchase_product_adhoc_productid AS product_id,
					SUM(stc_purchase_product_adhoc_qty) AS total_stock
				FROM stc_purchase_product_adhoc
				WHERE stc_purchase_product_adhoc_status = 1
				GROUP BY stc_purchase_product_adhoc_productid
			) AS stock ON stock.product_id = P.stc_product_id
			LEFT JOIN (
				SELECT product_id, SUM(qty) AS total_challan
				FROM gld_challan
				GROUP BY product_id
			) AS challan ON challan.product_id = P.stc_product_id
			LEFT JOIN (
				SELECT spa.stc_purchase_product_adhoc_productid AS product_id, SUM(srec.stc_cust_super_requisition_list_items_rec_recqty) AS total_direct
				FROM stc_purchase_product_adhoc spa
				JOIN stc_cust_super_requisition_list_items_rec srec ON srec.stc_cust_super_requisition_list_items_rec_list_poaid = spa.stc_purchase_product_adhoc_id
				WHERE spa.stc_purchase_product_adhoc_status = 1
				GROUP BY spa.stc_purchase_product_adhoc_productid
			) AS direct ON direct.product_id = P.stc_product_id
			LEFT JOIN(
				SELECT spa.stc_purchase_product_adhoc_productid AS product_id, SUM(S.qty) AS total_shop
				FROM stc_purchase_product_adhoc spa
				JOIN stc_shop S ON S.adhoc_id = spa.stc_purchase_product_adhoc_id
				WHERE spa.stc_purchase_product_adhoc_status = 1
				GROUP BY spa.stc_purchase_product_adhoc_productid
			) AS shop ON shop.product_id = P.stc_product_id
			WHERE P.stc_product_avail = 1
			AND (IFNULL(stock.total_stock, 0) - IFNULL(challan.total_challan, 0) - IFNULL(direct.total_direct, 0)) > 0
			$searchQuery
		";
		// Actual data
		$query = "
			SELECT DISTINCT
				P.stc_product_id, 
				(IFNULL(stock.total_stock, 0) - IFNULL(challan.total_challan, 0) - IFNULL(direct.total_direct, 0) - IFNULL(shop.total_shop, 0)) AS remainingqty
			$baseSql
		";
		$date=date("Y-m-d H:i:s");
		if($query=mysqli_query($this->stc_dbs, $query)){
			// Update the status of the requisition
			if($row = mysqli_fetch_assoc($query)){
				$remain_qty=$row['remainingqty'];
				// Find stc_purchase_product_adhoc_id for this product
				$adhoc_id = 0;
				$adhocQry = mysqli_query($this->stc_dbs, "SELECT stc_purchase_product_adhoc_id FROM stc_purchase_product_adhoc WHERE 	stc_purchase_product_adhoc_productid = '".mysqli_real_escape_string($this->stc_dbs, $product_id)."' AND 	stc_purchase_product_adhoc_status = 1 ORDER BY stc_purchase_product_adhoc_id DESC LIMIT 1");
				if($adhocRow = mysqli_fetch_assoc($adhocQry)){
					$shopname='';
					$qty=0;
					$shopqry = mysqli_query($this->stc_dbs, "SELECT DISTINCT `quantity`, `stc_trading_user_location` FROM `gld_requisitions` INNER JOIN `stc_trading_user` ON `created_by`=`stc_trading_user_id` WHERE `id` = '".mysqli_real_escape_string($this->stc_dbs, $id)."'");
					if($shopqry = mysqli_fetch_assoc($shopqry)){
						$shopname = $shopqry['stc_trading_user_location'];
						$qty = $shopqry['quantity'];
					}
					if($remain_qty-$qty<0){
						mysqli_query($this->stc_dbs, "UPDATE gld_requisitions SET remarks = CONCAT(COALESCE(remarks, ''),'".$date.": Item not available. Item will be dispacth soon.') WHERE id = $id");
						return ['success' => false, 'message' => 'Invalid Quanitty'];
					}
					$adhoc_id = $adhocRow['stc_purchase_product_adhoc_id'];
					$created_by = isset($_SESSION['stc_empl_id']) ? $_SESSION['stc_empl_id'] : 0;
					$insertQry = mysqli_query($this->stc_dbs, "INSERT INTO `stc_shop`(`adhoc_id`, `shopname`, `qty`, created_date, created_by) VALUES('".mysqli_real_escape_string($this->stc_dbs, $adhoc_id)."', '".mysqli_real_escape_string($this->stc_dbs, $shopname)."', '".mysqli_real_escape_string($this->stc_dbs, $qty)."', '".mysqli_real_escape_string($this->stc_dbs, $date)."', '".mysqli_real_escape_string($this->stc_dbs, $created_by)."')");
					if($insertQry){
						mysqli_query($this->stc_dbs, "UPDATE gld_requisitions SET status = $status WHERE id = $id");
						return ['success' => true, 'message' => 'Status updated and item dispatched successfully'];
					}
				}else{
					mysqli_query($this->stc_dbs, "UPDATE gld_requisitions SET remarks = CONCAT(COALESCE(remarks, ''),'<br>".$date.": Item not available. Item will be dispacth soon.'), buy_status = 1 WHERE id = $id");
					return ['success' => false, 'message' => 'No adhoc entry found for this product'];
				}
			}else{
				mysqli_query($this->stc_dbs, "UPDATE gld_requisitions SET remarks = CONCAT(COALESCE(remarks, ''),'<br>".$date.": Item not available. Item will be dispacth soon.'), buy_status = 1 WHERE id = $id");
				return ['success' => false, 'message' => 'Invalid Quanity'];
			}
		} else {
			return ['success' => false, 'message' => 'Failed to fetch product data'];
		}
	}	

	public function stc_update_requisition_status($id, $status, $remarks){
		if($id <= 0 || $status <= 0){
			return ['success' => false, 'message' => 'Invalid parameters'];
		}
		
		$query = "UPDATE stc_cust_super_requisition_list_items SET stc_cust_super_requisition_list_items_status = $status WHERE stc_cust_super_requisition_list_id = $id";
		if(mysqli_query($this->stc_dbs, $query)){
			$title="Pending";
			$message="Pending by ".$_SESSION['stc_empl_name']." on ".date('d-m-Y h:i A'). " <br> Reason :".$remarks;
			
			$date = date("Y-m-d H:i:s");
		
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_super_requisition_list_items_log`(
					`item_id`, 
					`title`, 
					`message`, 
					`status`, 
					`created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $title)."',
					'".mysqli_real_escape_string($this->stc_dbs, $message)."',
					'1',
					'".$_SESSION['stc_empl_id']."'
				)
			");
			return ['success' => true, 'message' => 'Status updated successfully'];
		} else {
			return ['success' => false, 'message' => 'Failed to update status'];
		}
	}

	public function stc_update_gld_requisition_bstatus($id, $status){
		if($id <= 0 || $status <= 0){
			return ['success' => false, 'message' => 'Invalid parameters'];
		}	
		$date=date("Y-m-d H:i:s");	
		$query = "UPDATE gld_requisitions SET remarks = CONCAT(COALESCE(remarks, ''),'<br>".$date.": Items purchased successfully.<br>'), buy_status = $status WHERE id = $id";
		if(mysqli_query($this->stc_dbs, $query)){
			return ['success' => true, 'message' => 'Status updated successfully'];
		} else {
			return ['success' => false, 'message' => 'Failed to update status'];
		}
	}
	
	public function stc_update_gld_requisition_remarks($id, $remarks){
		if($id <= 0){
			return ['success' => false, 'message' => 'Invalid requisition ID'];
		}
		
		$date = date("Y-m-d H:i:s");
		$escaped_remarks = mysqli_real_escape_string($this->stc_dbs, $remarks);
		
		// Update buy_remarks field with timestamp
		$query = "UPDATE gld_requisitions SET buy_remarks = CONCAT(COALESCE(buy_remarks, ''), '".$date.": ".$escaped_remarks."<br>') WHERE id = $id";
		
		if(mysqli_query($this->stc_dbs, $query)){
			return ['success' => true, 'message' => 'Remarks updated successfully'];
		} else {
			return ['success' => false, 'message' => 'Failed to update remarks: ' . mysqli_error($this->stc_dbs)];
		}
	}
	
	public function stc_pendingadhoc($datefrom, $dateto){
		
		// Update buy_remarks field with timestamp
		$query = "SELECT A.stc_cust_super_requisition_list_items_rec_id, E.stc_requisition_combiner_req_comb_id, B.stc_cust_super_requisition_list_id, C.stc_cust_project_title, F.stc_cust_pro_supervisor_fullname, F.stc_cust_pro_supervisor_contact, A.stc_cust_super_requisition_list_items_rec_recqty, D.stc_cust_super_requisition_list_items_title, D.stc_cust_super_requisition_list_items_unit, A.stc_cust_super_requisition_list_items_rec_date, A.stc_cust_super_requisition_list_items_rec_list_poaid FROM `stc_cust_super_requisition_list_items_rec` A
			INNER JOIN `stc_cust_super_requisition_list` B ON A.stc_cust_super_requisition_list_items_rec_list_id=B.stc_cust_super_requisition_list_id
			INNER JOIN `stc_cust_project` C ON B.stc_cust_super_requisition_list_project_id=C.stc_cust_project_id
			INNER JOIN `stc_cust_super_requisition_list_items` D ON A.stc_cust_super_requisition_list_items_rec_list_item_id=D.stc_cust_super_requisition_list_id
			INNER JOIN `stc_requisition_combiner_req` E ON B.stc_cust_super_requisition_list_id=E.stc_requisition_combiner_req_requisition_id
			INNER JOIN `stc_cust_pro_supervisor` F ON B.stc_cust_super_requisition_list_super_id=F.stc_cust_pro_supervisor_id
			WHERE `stc_cust_super_requisition_list_items_rec_list_poaid`=0 AND DATE(`stc_cust_super_requisition_list_items_rec_date`) BETWEEN '".$datefrom."' AND '".$dateto."' ORDER BY A.stc_cust_super_requisition_list_items_rec_date DESC";
		$output=array();
		if($result=mysqli_query($this->stc_dbs, $query)){
			while($row=mysqli_fetch_assoc($result)){
				$row['stc_cust_super_requisition_list_items_rec_recqty']=number_format($row['stc_cust_super_requisition_list_items_rec_recqty'], 2);
				$row['stc_cust_super_requisition_list_items_rec_date']=date('d-m-Y', strtotime($row['stc_cust_super_requisition_list_items_rec_date']));
				$output[]=$row;
			}
		}
		return ['success' => true, 'data' => $output, 'message' => 'Remarks updated successfully'];
	}
}
#<------------------------------------------------------------------------------------------------------>
#<--------------------------------------Object sections of Order class---------------------------------->
#<------------------------------------------------------------------------------------------------------>
// show vendor on quotation page
if(isset($_POST['friday_customer'])){
	$objloki=new ragnarOrderView();
	$objlokiout=$objloki->stc_call_customer();
	$date=date('l jS \ F Y h:i:s A');
	$outarray=array(
		'time' => $date,
		'customer' => $objlokiout
	);
	echo json_encode($outarray);
}

// page load order call
if(isset($_POST["callorder"])){	
	$objagentorder=new ragnarOrderView();
	$objagcountorder=new ragnarOrderView();

	$opobjagentorder=$objagentorder->stc_call_ag_order();
	echo json_encode($opobjagentorder);
	// echo $opobjagentorder;
}

// search by same
if(isset($_POST['stcorderaction'])){
	@$bjorneordbegvalue=$_POST['phpordbegvalue'];
	@$bjorneordendvalue=$_POST['phpordendvalue'];
	$bjornefilterbegdate=$_POST['phpbegdate'];
	$bjornefilterenddate=$_POST['phpenddate'];
	$bjornefiltercustomerid=$_POST['phpcustomerid'];
	$bjornefilterordernumber=$_POST['phpordernumber'];
	$bjornefilterorderagents=$_POST['phporderagents'];
	$bjornefilterstatus=$_POST['phpstatus'];
	$out='';

	if(empty($bjorneordbegvalue) || empty($bjorneordendvalue)){
		$bjorneordbegvalue=0;
		$bjorneordendvalue=20;
	}
	$objpdres=new ragnarOrderView();	
	$opobjpdres=$objpdres->stc_getorder_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustomerid, $bjornefilterordernumber, $bjornefilterorderagents, $bjornefilterstatus, $bjorneordbegvalue, $bjorneordendvalue);
	$out=$opobjpdres;
	echo $out;
}

// call perticular order records
if(isset($_POST['call_order_sub'])){
	$stc_agorder_id=$_POST['stc_order_id'];
	$objloki=new ragnarOrderView();
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

	$ravens0=new ragnarOrderAdd();
	$ravens=new ragnarOrderAdd();
	$ravens2=new ragnarOrderAdd();
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
	$raven=new ragnarOrderAdd();
	$outraven=$raven->stc_ag_status($stc_order_no);
	echo json_encode($outraven);
	// echo $outraven;
}

// for po hit
if(isset($_POST['add_po_fr_ag'])){
	$pdid=$_POST['product_id'];
	$remqty=$_POST['stc_rem_ord_qty'];

	$ravens=new ragnarOrderAdd();
	$outravens=$ravens->stc_ag_add_to_po($pdid, $remqty);
	echo $outravens;
}

#<-------------------------------------Object section of Requisiton Class----------------------------->
// search by same
if(isset($_POST['stcreqaction'])){
	@$bjornebegval=$_POST['phpbegvalue'];
	@$bjorneendval=$_POST['phpendvalue'];
	$bjornefilterreqbegdate=$_POST['phpreqbegdate'];
	$bjornefilterreqenddate=$_POST['phpreqenddate'];
	$bjornefilterreqcustomerid=$_POST['phpreqcustomerid'];
	$bjornefilterreqnumber=$_POST['phpreqnumber'];
	$bjornefilterreqsitename=$_POST['phpreqsitename'];
	$bjornefilterreqmaterials=$_POST['phpreqmaterials'];
	$out='';

	if(empty($bjornebegval) || empty($bjorneendval)){
		$bjornebegval=0;
		$bjorneendval=20;
	}
	$objpdres=new ragnarRequisitionView();	
	$opobjpdres=$objpdres->stc_getrequisition_by_multiple_inp($bjornefilterreqbegdate, $bjornefilterreqenddate, $bjornefilterreqcustomerid, $bjornefilterreqnumber, $bjornefilterreqsitename, $bjornefilterreqmaterials, $bjornebegval, $bjorneendval);
	$out=$opobjpdres;
	echo $out;
}

// page load order call
if(isset($_POST["callrequisition"])){
	$objagentorder=new ragnarRequisitionView();

	$opobjagentorder=$objagentorder->stc_call_ag_requisition();
	echo json_encode($opobjagentorder);
	// echo $opobjagentorder;
}

//call req for merchandise
if(isset($_POST['stc_call_req_items_for_merchant'])){
	$stc_req_comb_id=$_POST['stc_req_comb_id'];
	$objagentorder=new ragnarRequisitionView();
	$opobjagentorder=$objagentorder->stc_call_ag_requisition_items_merchandise($stc_req_comb_id);
	// echo json_encode($opobjagentorder);
	echo $opobjagentorder;
} 

// update material name of requisiotn
if(isset($_POST['stc_update_requisition_items_hit'])){
	$material_id=$_POST['material_id'];
	$material_name=$_POST['material_name'];
	$objagentorder=new raganarRequisitionUpdate();
	$opobjagentorder=$objagentorder->stc_update_material_requistion_name($material_id, $material_name);
	echo $opobjagentorder;
}

// call product
if(isset($_POST['stccallmercaction'])){
	$bjornefiltercatout=$_POST['phpfiltercatout'];
	$bjornefiltersubcatout=$_POST['phpfiltersubcatout'];
	$bjornefilternameout=$_POST['phpfilternameout'];
	$out='';
	$objpdres=new ragnarRequisitionView();	
	if(strlen($bjornefilternameout)>=3){
		$opobjpdres=$objpdres->stc_getpd_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout);
		$out=$opobjpdres;
	}else{
		$out='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a href="#" class="btn btn-primary btn-block text-uppercase mb-3">Atleast type word in text field or search via category or sub category!!!</a>
			</div>
		';
	}
	echo $out;
}

// call merchant
if(isset($_POST['stc_call_merchant'])){
	$out='';
	$objmerchant=new ragnarRequisitionView();
	$out=$objmerchant->stc_call_merchant();
	echo $out;
}

// add merchant to database
if(isset($_POST['stc_requst_merchandise_hit'])){
	$out='';
	$stc_req_id=$_POST['stc_req_id'];
	$stc_pd_id=$_POST['stc_pd_id'];
	$stc_merchant_id=$_POST['stc_merchant_id'];

	$obreqmer=new ragnarRequisitionAdd();
	$out=$obreqmer->stc_add_req_merchandise($stc_req_id, $stc_pd_id, $stc_merchant_id);
	echo $out;
}

// call sitename
if(isset($_POST['stc_call_sitename'])){
	$sitebegdate=date('Y-m-d', strtotime($_POST['sitebegdate']));
	$siteenddate=date('Y-m-d', strtotime($_POST['siteenddate']));
	$out='';
	$objsitename=new ragnarRequisitionView();
	$out=$objsitename->stc_call_sitename($sitebegdate, $siteenddate);
	echo $out;
}

// call requisition using sitename
if(isset($_POST['stc_check_box_view'])){
	$out='';
	$selectedLanguage=$_POST['selectedLanguage'];
	unset($_SESSION['stc_get_req_use_sitename']);
	foreach($selectedLanguage as $row){
		$out='';
		if(isset($_SESSION["stc_get_req_use_sitename"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_get_req_use_sitename"] as $keys => $values) {  
				if($_SESSION["stc_get_req_use_sitename"][$keys]['combinator_id'] == $row) {  
					$is_available++; 
					$out="This site already selected.";
				}  
			}  
			if($is_available < 1) {  
				$item_array = array(  
					'combinator_id'             =>     $row
				);  
				$_SESSION["stc_get_req_use_sitename"][] = $item_array;  
				$out="Site selected!!!";
			}  
		}else{  
			$item_array = array(  
				'combinator_id'             =>     $row
			);   
			$_SESSION["stc_get_req_use_sitename"][] = $item_array;    
			$out="Site selected!!!";
		}  
	}
	echo $out;
}

// call post merchant
if(isset($_POST['stc_call_post_merchant'])){
	$req_id=$_POST['req_id'];
	$out='';
	$objpostmerchant=new ragnarRequisitionView();
	$out=$objpostmerchant->stc_call_post_merchant($req_id);
	echo $out;
}

// call post merchant
if(isset($_POST['stc_call_pre_merchant'])){
	$prod_id=$_POST['prod_id'];
	$out='';
	$objpremerchant=new ragnarRequisitionView();
	$out=$objpremerchant->stc_call_pre_merchant($prod_id);
	echo $out;
}

// call merchant rate
if(isset($_POST['stc_call_pre_merchant_rate'])){
	$merchant_id=$_POST['merchant_id'];
	$prod_id=$_POST['prod_id'];
	$objpremerchant=new ragnarRequisitionView();
	$out=$objpremerchant->stc_call_pre_merchant_rate($merchant_id, $prod_id);
	echo $out;
}

// change merchant requisition
if(isset($_POST['stc_req_change_merchant'])){
	$req_id=$_POST['req_id'];
	$merchant_id=$_POST['merchant_id'];
	$objchangemerchant=new ragnarRequisitionView();
	$out=$objchangemerchant->stc_req_change_merchant($req_id, $merchant_id);
	echo $out;
}

// quantity approved
if(isset($_POST['stc_reqqnty_appro'])){
	$out='';
	$req_id=$_POST['req_id'];
	$appr_qty=$_POST['appr_qty'];
	$pd_id=$_POST['pd_id'];
	$objapprqnty=new ragnarRequisitionView();
	$outobjapprqnty=$objapprqnty->stc_approved_quantity($req_id, $appr_qty);
	// if($outobjapprqnty=="Approved successfully."){
		// $objapprpo=new ragnarRequisitionView();
		// $outobjapprpo=$objapprpo->stc_approved_for_po($pd_id, $req_id, $appr_qty);
		// $out=$outobjapprpo;
	// }else{
		// $out=$outobjapprqnty;
	// }
	echo $outobjapprqnty;
}

// change something unit qty of item to process the rate
if(isset($_POST['stc_req_item_change_hit'])){
	$req_item_id=$_POST['req_item_id'];
	$req_item_type=$_POST['req_item_type'];
	$req_item_unit=$_POST['req_item_unit'];
	$req_item_qty=$_POST['req_item_qty'];
	$objchangeattr=new ragnarRequisitionView();
	$out=$objchangeattr->stc_req_item_attr_change($req_item_id, $req_item_type, $req_item_unit, $req_item_qty);
	echo $out;
}

// call purchase from merchant
if(isset($_POST['stc_call_pd_purchase_from'])){
	$pd_id=$_POST['pd_id'];
	$objchangeattr=new ragnarRequisitionView();
	$out=$objchangeattr->stc_call_product_merchant($pd_id);
	echo $out;
}
#<--------------------------------Object section of Requisiton pert Class----------------------------->
// search by same
if(isset($_POST['stcreqperticularaction'])){
	$bjornefilterreqbegdate=$_POST['phpreqbegdate'];
	$bjornefilterreqenddate=$_POST['phpreqenddate'];
	$bjornefilterreqcustomerid=$_POST['phpreqcustomerid'];
	$bjornefilterreqnumber=$_POST['phpreqnumber'];
	$bjornefilterreqsitename=$_POST['phpreqagents'];
	$bjornefilterreqmaterials=isset($_POST['phpreqmaterials']) ? $_POST['phpreqmaterials'] : 'NA';
	$bjornefilterrequrl_param=$_POST['url_param'];
	$out='';
	$objpdres=new ragnarRequisitionPertView();	
	$opobjpdres=$objpdres->stc_getpertrequisition_by_multiple_inp($bjornefilterreqbegdate, $bjornefilterreqenddate, $bjornefilterreqcustomerid, $bjornefilterreqnumber, $bjornefilterreqsitename, $bjornefilterreqmaterials, $bjornefilterrequrl_param);
	$out=$opobjpdres;
	echo $out;
}

// page load order call
if(isset($_POST["callrequisitionlist"])){
	$objagentorder=new ragnarRequisitionPertView();
	$url_param=$_POST['url_param'];
	$opobjagentorder=$objagentorder->stc_show_requisition_list($url_param);
	echo json_encode($opobjagentorder);
	// echo $opobjagentorder;
}

// call perticular order records
if(isset($_POST['call_requist_sub'])){
	$stc_agorder_id=$_POST['stc_order_id'];
	$objloki=new ragnarRequisitionPertView();
	$objlokiout=$objloki->stc_view_agents_requist($stc_agorder_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call perticular order records
if(isset($_POST['call_requistdispatch_sub'])){
	$stc_repid=$_POST['repid'];
	$stc_repitemid=$_POST['repitemid'];
	$objloki=new ragnarRequisitionPertView();
	$objlokiout=$objloki->stc_view_agents_requist_dispatch($stc_repid, $stc_repitemid);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call perticular order records
if(isset($_POST['update_adhoc_id_rec'])){
	$stc_repid=$_POST['repid'];
	$stc_adhoc_id=$_POST['adhoc_id'];
	$objloki=new ragnarRequisitionPertView();
	$objlokiout=$objloki->stc_update_req_rec_adhoc_id($stc_repid, $stc_adhoc_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// add challan items to sale product_id
if(isset($_POST['stceditsaleaction'])){
	$bjornefiltercatout=$_POST['phpfiltercatout'];
	$bjornefiltersubcatout=$_POST['phpfiltersubcatout'];
	$bjornefilternameout=$_POST['phpfilternameout'];
	$out='';
	$objpdres=new ragnarRequisitionPertView();	
	$opobjpdres=$objpdres->stc_getpd_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout);
	$out=$opobjpdres;
	echo $out;
}

// call requisition cart
if(isset($_POST['js_show_requi_cart'])){
	$objloki=new ragnarRequisitionPertView();
	$objlokiout=$objloki->stc_show_requisition_product();
	echo $objlokiout;
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
		    	     'product_dis_quantity'		=>     $_POST["product_disquantity"],
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
		    	     'product_dis_quantity'		=>     $_POST["product_disquantity"],
		    	     'product_quantity'			=>     $_POST["product_quantity"],
		    	     'product_price'			=>     $_POST["product_price"]
			);   
			$_SESSION["stc_cust_requist_cart"][] = $item_array;  
		     echo "Item Added to Requisition Cart!!!";
		}  
    }  
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

	$ravens0=new ragnarRequisitionPertAdd();
	$ravens=new ragnarRequisitionPertAdd();
	$ravens2=new ragnarRequisitionPertAdd();
	$ravens3=new ragnarRequisitionPertAdd();
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
	$raven=new ragnarRequisitionPertAdd();
	$outraven=$raven->stc_ag_req_status($stc_req_no);
	echo json_encode($outraven);
	// echo $outraven;
}

if(isset($_POST['stc_dispatch_hit'])){
	$dispatch_qty=$_POST['stc_dispatch_qty'];
	$stc_req_id=$_POST['stc_req_id'];
	$stc_req_item_id=$_POST['stc_req_item_id'];
	$stc_tools_id=isset($_POST['stc_tools_id']) || 'NA';
	$poadhocitem=$_POST['poadhocitem'];
	$raven=new ragnarRequisitionPertAdd();
	$outraven=$raven->stc_ag_req_direct($stc_req_id, $stc_req_item_id, $stc_tools_id, $dispatch_qty, $poadhocitem);
	// echo json_encode($outraven);
	echo $outraven;
}

if(isset($_POST['stc_gld_hit'])){
	$stc_req_id=$_POST['stc_req_id'];
	$raven=new ragnarRequisitionPertAdd();
	$outraven=$raven->stc_ag_req_gld($stc_req_id);
	// echo json_encode($outraven);
	echo $outraven;
}

if(isset($_POST['stc_remove_dispatched'])){
	$stc_req_id=$_POST['stc_req_id'];
	$raven=new ragnarRequisitionPertAdd();
	$outraven=$raven->stc_ag_req_remove_dispatched($stc_req_id);
	// echo json_encode($outraven);
	echo $outraven;
}

#<-----------------Object section of get notifications of orders & requisitions Class---------------->
// notification object
if(isset($_POST['callorderrequisitionbadge'])){
	$bjorneout='';
	$bjornecall=new ragnarCallOrderRequisition();
	$opbjornecall=$bjornecall->stc_call_order_requisition_badges();
	$bjorneout=$opbjornecall;
	echo json_encode($bjorneout);
}

#<-----------------Object section of get notifications of track requisitions Class------------------->
if(isset($_POST['stc_call_rit_items_req'])){
	unset($_SESSION["stc_track_req_item_sess"]);
	$rit_begdate=date('Y-m-d', strtotime($_POST['rit_begdate']));
	$rit_enddate=date('Y-m-d', strtotime($_POST['rit_enddate']));
	$rit_type=$_POST['js_rit_type'];
	$bjornecall=new ragnarCallRequisitionItemTrack();
	$opbjornecall=$bjornecall->stc_call_requisition_items_track($rit_begdate, $rit_enddate, $rit_type);
	$bjorneout=$opbjornecall;
	echo json_encode($bjorneout);
	// echo $bjorneout;
	// print_r($bjorneout);
}

#<-----------------Object section of requisitions list Class------------------->
// search by same
if(isset($_POST['stc_rquisition_bylist_find'])){
	$req_begdate=date('Y-m-d', strtotime($_POST['req_begdate']));
	$req_enddate=date('Y-m-d', strtotime($_POST['req_enddate']));
	$req_customer=$_POST['req_customer'];
	$req_reqnumber=$_POST['req_reqnumber'];
	$req_sitenmae=$_POST['req_sitenmae'];
	$req_materialtype=$_POST['req_materialtype'];

	$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
	$records_per_page = 10;
	$offset = ($page - 1) * $records_per_page;

	$out='';
	$objpdres=new ragnarRequisitionView();	
	$opobjpdres=$objpdres->stc_getrequisitionlist($req_begdate, $req_enddate, $req_customer, $req_reqnumber, $req_sitenmae, $req_materialtype, $offset, $records_per_page, $page);
	echo $opobjpdres;
}

#<-----------------Object section of ppe tracker Class------------------->
// save procurment tracker payment
if(isset($_POST['save_item_tracker'])){
	$user_id=$_POST['user_id'];
	$ppe_type=$_POST['ppe_type'];
	$qty=$_POST['qty'];
	$unit=$_POST['unit'];
	$issue_date=$_POST['issue_date'];
	$validity=$_POST['validity'];
	$remarks=$_POST['remarks'];
	$out='';

	if($user_id=="" || $ppe_type=="" || empty($issue_date)){
		$out='empty';
	}elseif(empty($_SESSION['stc_empl_id'])){
		$out='reload';
	}else{
		$odin_req=new ragnarCallRequisitionItemTrack();
		$odin_req_out=$odin_req->stc_item_tracker_save($user_id, $ppe_type, $qty, $unit, $issue_date, $validity, $remarks);
		$out=$odin_req_out;
	}
	echo $out;
}

// call procurment tracker
if(isset($_POST['call_item_tracker'])){
	$search=isset($_POST['searchTerm'])?$_POST['searchTerm']:'';
	$page=isset($_POST['page'])?$_POST['page']:'';
	$odin_req=new ragnarCallRequisitionItemTrack();
	$odin_req_out=$odin_req->stc_item_tracker_call($search, $page);
	echo $odin_req_out;
}
// call procurment tracker
if(isset($_POST['ppesummary'])){
	$odin_req=new ragnarCallRequisitionItemTrack();
	$odin_req_out=$odin_req->stc_item_tracker_ppesummary();
	echo json_encode($odin_req_out);
}

#<-----------------Object section of tool tracker Class------------------->
// save procurment tracker payment
if(isset($_POST['save_tool_tracker'])){
	$itt_id=$_POST['itt_id'];
	$unique=$_POST['unique'];
	$itemdescription=$_POST['itemdescription'];
	$machineslno=$_POST['machineslno'];
	$make=$_POST['make'];
	$type=$_POST['type'];
	$warranty=$_POST['warranty'];
	$purdetails=$_POST['purdetails'];
	$tinnumber=$_POST['tinnumber'];
	$tindate=$_POST['tindate'];
	$remarks=$_POST['remarks'];
	$operation=$_POST['operation'];
	$out='';
	if(empty($_SESSION['stc_empl_id'])){
		$out='reload';
	}else{
		$odin_req=new ragnarCallRequisitionItemTrack();
		if($operation=="yes"){
			$out=$odin_req->stc_tool_tracker_edit($itt_id, $unique, $itemdescription, $machineslno, $make, $type, $warranty, $purdetails, $tinnumber, $tindate, $remarks);
		}else{
			$out=$odin_req->stc_tool_tracker_save($unique, $itemdescription, $machineslno, $make, $type, $warranty, $purdetails, $tinnumber, $tindate, $remarks);
		}
	}
	echo $out;
}

// call tools tracker
if(isset($_POST['call_tools_tracker'])){
    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;

    $odin_req = new ragnarCallRequisitionItemTrack();
    $odin_req_out = $odin_req->stc_tool_tracker_get($search, $page);

    echo json_encode($odin_req_out);
}


// savce tools tracker
if(isset($_POST['save_tool_trackertrack'])){
	$issuedby=$_POST['issuedby'];
	$location=$_POST['location'];
	$date=$_POST['date'];
	$receivedby=$_POST['receivedby'];
	$handoverto=$_POST['handoverto'];
	$itt_id=$_POST['itt_id'];
	$out='';
	if(empty($_SESSION['stc_empl_id'])){
		$out='reload';
	}else{
		$odin_req=new ragnarCallRequisitionItemTrack();
		$out=$odin_req->stc_tool_trackertrack_save($issuedby, $location, $date, $receivedby, $handoverto, $itt_id);
	}
	echo $out;
}


// call tools tracking tracker
if(isset($_POST['call_tool_trackertrack'])){
	$itt_id=$_POST['itt_id'];
	$odin_req=new ragnarCallRequisitionItemTrack();
	$odin_req_out=$odin_req->stc_tool_trackertrack_get($itt_id);
	echo json_encode($odin_req_out);
}
#<-----------------Object section of b2corders Class------------------->
if (isset($_POST['stc_call_B2COrders'])) {
    $searchQuery = $_POST['search'] ?? '';
    $page = $_POST['page'] ?? 1;
    $perPage = $_POST['perPage'] ?? 10;

    $odin_req = new ragnarCallB2COrders();
    $odin_req_out = $odin_req->stc_call_B2COrders($searchQuery, $page, $perPage);
    echo json_encode($odin_req_out);
}

if(isset($_POST['stc_action_order'])){
	$id=$_POST['id'];
	$status=$_POST['status'];
	$odin_req=new ragnarCallB2COrders();
	$odin_req_out=$odin_req->stc_action_order($id, $status);
	echo json_encode($odin_req_out);
}


if(isset($_POST['stc_call_advanceorderlist'])){
	$odin_req=new ragnarCallB2COrders();
	$odin_req_out=$odin_req->stc_call_advanceorderlist();
	echo json_encode($odin_req_out);
}

#<-----------------Object section of GLD Requisitions Class------------------->
if(isset($_POST['stc_call_gld_requisitions'])){
	$search = isset($_POST['search']) ? $_POST['search'] : '';
	$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
	$limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;
	$offset = ($page - 1) * $limit;
	
	$odin_req = new ragnarCallGLDRequisitions();
	$odin_req_out = $odin_req->stc_call_gld_requisitions($search, $page, $limit, $offset);
	echo json_encode($odin_req_out);
}

if(isset($_POST['stc_update_gld_requisition_status'])){
	$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
	$status = isset($_POST['status']) ? (int)$_POST['status'] : 0;
	
	$odin_req = new ragnarCallGLDRequisitions();
	$odin_req_out = $odin_req->stc_update_gld_requisition_status($id, $status);
	echo json_encode($odin_req_out);
}

if(isset($_POST['update_requisition_status'])){
	$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
	$status = $_POST['status'];
	$remarks = $_POST['remarks'];
	
	$odin_req = new ragnarCallGLDRequisitions();
	$odin_req_out = $odin_req->stc_update_requisition_status($id, $status, $remarks);
	echo json_encode($odin_req_out);
}

if(isset($_POST['stc_update_gld_requisition_bstatus'])){
	$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
	$status = isset($_POST['status']) ? (int)$_POST['status'] : 0;
	
	$odin_req = new ragnarCallGLDRequisitions();
	$odin_req_out = $odin_req->stc_update_gld_requisition_bstatus($id, $status);
	echo json_encode($odin_req_out);
}

if(isset($_POST['stc_update_gld_requisition_remarks'])){
	$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
	$remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
	
	$odin_req = new ragnarCallGLDRequisitions();
	$odin_req_out = $odin_req->stc_update_gld_requisition_remarks($id, $remarks);
	echo json_encode($odin_req_out);
}

if(isset($_POST['stc_getpendingadhoc'])){
	$datefrom = isset($_POST['datefrom']) ? $_POST['datefrom'] : '';
	$dateto = isset($_POST['dateto']) ? $_POST['dateto'] : '';
	
	$odin_req = new ragnarCallGLDRequisitions();
	$odin_req_out = $odin_req->stc_pendingadhoc($datefrom, $dateto);
	echo json_encode($odin_req_out);
}
