<?php
include "../../MCU/obdb.php";
session_start();
// call order class
class ragnarOrderView extends tesseract{
	// call customer on po page
	public function stc_call_customer(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_customer` GROUP BY `stc_customer_name` ASC");
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
					<th class="text-center">Requisition For</th>
					<th class="text-center">Parent Requisition ID<br>Parent Requisition Date</th>
					<th class="text-center">Requisition Sitename</th>
					<th class="text-center">Requisition Accepted By</th>
					<th class="text-center">Requisition Status</th>
					<th class="text-center">Action</th>
				</thead>
				<tbody>
		';
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT 
				`stc_requisition_combiner_id`,
				`stc_requisition_combiner_date`,
			    `stc_requisition_combiner_refrence`,
				`stc_cust_super_requisition_list_id`,
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
					SELECT `stc_cust_super_requisition_items_priority` FROM `stc_cust_super_requisition_list_items` WHERE `stc_cust_super_requisition_list_items_req_id`='".$requisrow['stc_cust_super_requisition_list_id']."' AND `stc_cust_super_requisition_items_priority`=2
				");
				if(mysqli_num_rows($chursql)==0){
					$badgeurgent="";
				}
				$ivar.= '
					<tr>
						<td class="text-center">'.$requisrow['stc_customer_name'].'</td>
						<td class="text-center">
							'.$requisrow['stc_requisition_combiner_id'].'<br>
							'.date('d-m-Y', strtotime($requisrow['stc_requisition_combiner_date'])).'
						</td>
						<td>'.$requisrow['stc_requisition_combiner_refrence'].'</td>
						<td class="text-center">'.$requisrow['stc_agents_name'].'</td>
						<td class="text-center">'.$reqcomstatus.$badgeurgent.'</td>
						<td class="text-center">
							<a href="#" class="stc-call-for-select-merchant-req" title="Add product and merchant." id="'.$requisrow["stc_requisition_combiner_id"].'" style="font-size: 25px;color: black;">
								<i class="fa fa-ship" aria-hidden="true"></i>
							</a>
							<a href="stc-requisition-combiner-fsale.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="Process requisitions." style="font-size: 25px;color: black;">
								<i class="fa fa-pen-square" aria-hidden="true"></i>
							</a>
							<a href="stc-requisition-combiner-fshow.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="P.M Requisition" style="font-size: 25px;color: black;">
								<i class="fa fa-print" aria-hidden="true"></i>
							</a>
							<a href="stc-requisition-combiner-dcprintpreview.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="DC Receiving Requisition" style="font-size: 25px;color: black;">
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
					<th class="text-center">Requisition For</th>
					<th class="text-center">Parent Requisition ID<br>Parent Requisition Date</th>
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
			"byreqsitename" => $bjornefilterreqsitename
		);

		$reqcustomer='';
		$reqno='';
		$reqsitename='';
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
		}
		$endfilterqry='ORDER BY `stc_requisition_combiner_id` DESC LIMIT '.$bjornebegval.','.$bjorneendval;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT 
				`stc_requisition_combiner_id`,
				`stc_requisition_combiner_date`,
			    `stc_requisition_combiner_refrence`,
				`stc_cust_super_requisition_list_id`,
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
		    WHERE (
		        DATE(`stc_requisition_combiner_date`)
		         BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterreqbegdate)."'
		         AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterreqenddate)."'
		    )".$reqcustomer.$reqno.$reqsitename.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)!=0){
			foreach($ivarfilterquery as $requisrow){
				$loopcount++;
				$reqcomstatus='';
				if($requisrow['stc_requisition_combiner_status']==1){$reqcomstatus="PROCESS";}else{$reqcomstatus="ACCEPTED";}
				$badgeurgent='<span class="urgent" style="position: relative;display: inline-block;top: -10px;padding: 1px 3px;font-size: 10px;font-weight: bold;color: #fff;background-color: #dc3545; border-radius: 15px;">Urgent</span>';
				$chursql=mysqli_query($this->stc_dbs, "
					SELECT `stc_cust_super_requisition_items_priority` FROM `stc_cust_super_requisition_list_items` WHERE `stc_cust_super_requisition_list_items_req_id`='".$requisrow['stc_cust_super_requisition_list_id']."' AND `stc_cust_super_requisition_items_priority`=2
				");
				if(mysqli_num_rows($chursql)==0){
					$badgeurgent="";
				}
				$ivar.= '
					<tr>
						<td class="text-center">'.$requisrow['stc_customer_name'].'</td>
						<td class="text-center">
							'.$requisrow['stc_requisition_combiner_id'].'<br>
							'.date('d-m-Y', strtotime($requisrow['stc_requisition_combiner_date'])).'
						</td>
						<td>'.$requisrow['stc_requisition_combiner_refrence'].'</td>
						<td class="text-center">'.$requisrow['stc_agents_name'].'</td>
						<td class="text-center">'.$reqcomstatus.$badgeurgent.'</td>
						<td class="text-center">
							<a href="#" class="stc-call-for-select-merchant-req" title="Add product and merchant." id="'.$requisrow["stc_requisition_combiner_id"].'" style="font-size: 25px;color: black;">
								<i class="fa fa-ship" aria-hidden="true"></i>
							</a>
							<a href="stc-requisition-combiner-fsale.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'"  title="Process requisitions." style="font-size: 25px;color: black;">
								<i class="fa fa-pen-square" aria-hidden="true"></i>
							</a>
							<a href="stc-requisition-combiner-fshow.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="P.M Requisition" style="font-size: 25px;color: black;">
								<i class="fa fa-print" aria-hidden="true"></i>
							</a>
							<a href="stc-requisition-combiner-dcprintpreview.php?requi_id='.$requisrow["stc_requisition_combiner_id"].'" title="DC Receiving Requisition" style="font-size: 25px;color: black;">
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
				$ivar.='
						<tr>
							<td class="no">'.$sl.'</td>
							<td class="text-left">
								<input 
									type="text" 
									class="form-control stc-update-requis-material-name stc-update-requis-material-name-hit" 
									placeholder="Please Enter Item Name" 
									value="'.htmlspecialchars($ivarreqitemrow['stc_cust_super_requisition_list_items_title']).'"
									id="'.$ivarreqitemrow['stc_cust_super_requisition_list_id'].'"
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
			<table class="table table-hover table-bordered table-responsive">
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
		$reqstatus='';
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
				if($requisrow['stc_cust_super_requisition_list_status']==1){
				    $reqstatus="PROCESS";
				}elseif($requisrow['stc_cust_super_requisition_list_status']==2){
				    $reqstatus="PASSED";
				}elseif($requisrow['stc_cust_super_requisition_list_status']==3){
				    $reqstatus="COMPLETED";
				}else{
				    $reqstatus="ACCEPTED";
				}
				$lokiout.= '
					<tr>
						 <td>
						 	'.$requisrow['stc_cust_super_requisition_list_id'].'<br>
						 	'.date('d-m-Y', strtotime($requisrow['stc_cust_super_requisition_list_date'])).'
						 </td>
						 <td>'.$requisrow['stc_cust_pro_supervisor_fullname'].'</td>
						 <td>'.$requisrow['stc_cust_project_title'].'</td>
						 <td>'.$requisrow['stc_agents_name'].'</td>
						 <td>'.$reqstatus.'</td>
						 <td>
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

	// filter requiition by all
	public function stc_getpertrequisition_by_multiple_inp($bjornefilterreqbegdate, $bjornefilterreqenddate, $bjornefilterreqcustomerid, $bjornefilterreqnumber, $bjornefilterreqsitename, $bjornefilterreqmaterials, $bjornefilterrequrl_param){
		$ivar='
			<table class="table table-hover form-group call-order">
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
				$ivar.= '
					<tr>
						 <td>
						 	'.$requisrow['stc_cust_super_requisition_list_id'].'<br>
						 	'.date('d-m-Y', strtotime($requisrow['stc_cust_super_requisition_list_date'])).'
						 </td>
						 <td>'.$requisrow['stc_cust_pro_supervisor_fullname'].'</td>
						 <td>'.$requisrow['stc_cust_project_title'].'</td>
						 <td>'.$requisrow['stc_agents_name'].'</td>
						 <td>'.$reqstatus.'</td>
						 <td>
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
				    `stc_cust_super_requisition_list_items_status`
				FROM `stc_cust_super_requisition_list_items` 
				WHERE `stc_cust_super_requisition_list_items_req_id`='".$stc_agorder_id."'
			");
			$order_line_items .= '  
					<table class="table table-hover table-bordered table-responsive">
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
						    <th class="text-center" scope="col">Action</th>
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
							$order_line_items .= '
								<tr>
									<td>'.$slno.'</td>
									<td>
										> '.$dispatchrow["stc_cust_super_requisition_list_items_title"].'<br>
										> '.$apprpd_name.'<br>
										> '.$requistitemrow["stc_product_name"].'
									</td>
									<td class="text-center">'.$dispatchrow["stc_cust_super_requisition_list_items_unit"].'</td>
								    <td align="right">'.number_format($dispatchrow["stc_cust_super_requisition_list_items_approved_qty"], 2).'</td>
									<td align="right">'.number_format($apprpd_qty, 2).'</td>
									<td align="right">'.number_format($dispatchedgqty, 2).'</td>
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

	public function stc_ag_req_direct($stc_req_id, $stc_req_item_id, $dispatch_qty){
		$loki='';
		$gamorarecgoqry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_cust_super_requisition_list_items_rec`(
				`stc_cust_super_requisition_list_items_rec_list_id`, 
				`stc_cust_super_requisition_list_items_rec_list_item_id`, 
				`stc_cust_super_requisition_list_items_rec_list_pd_id`, 
				`stc_cust_super_requisition_list_items_rec_recqty`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $stc_req_item_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."',
				'0',
				'".mysqli_real_escape_string($this->stc_dbs, $dispatch_qty)."'
			)
		");
		if($gamorarecgoqry){
			$gamoraupdateqry=mysqli_query($this->stc_dbs, "
				UPDATE
					`stc_cust_super_requisition_list_items`
				SET
					`stc_cust_super_requisition_list_items_status` = 2
				WHERE
					`stc_cust_super_requisition_list_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."'
				AND `stc_cust_super_requisition_list_items_req_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_req_item_id)."'
			");
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
			FROM
				`stc_cust_super_requisition_list_purchaser`
			INNER JOIN 
				`stc_product` 
			ON 
				`stc_product_id` =`stc_cust_super_requisition_list_purchaser_pd_id` 
			INNER JOIN 
				`stc_category` 
			ON 
				`stc_product_cat_id` =`stc_cat_id` 
			INNER JOIN 
				`stc_sub_category` 
			ON 
				`stc_product_sub_cat_id` =`stc_sub_cat_id` 
			INNER JOIN 
				`stc_cust_super_requisition_list_items` 
			ON 
				`stc_cust_super_requisition_list_items`.`stc_cust_super_requisition_list_id` =`stc_cust_super_requisition_list_purchaser_list_item_id`
			INNER JOIN 
				`stc_cust_super_requisition_list` 
			ON 
				`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id` = `stc_cust_super_requisition_list_items_req_id` 
			WHERE (
				DATE(`stc_cust_super_requisition_list_date`) 
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $rit_begdate)."' 
				AND '".mysqli_real_escape_string($this->stc_dbs, $rit_enddate)."'
			) ".$itemtype."
		   	ORDER BY 
		   		`stc_product_name` ASC
		");
	    while ($item_rec =  mysqli_fetch_assoc($odin_getreqqry)){
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

	    foreach($_SESSION["stc_track_req_item_sess"] as $fetch_row){
	    	$subcatname='';
	    	if($fetch_row['product_sub_category']=="OTHERS"){
	    		$subcatname='';
	    	}else{
	    		$subcatname=$fetch_row['product_sub_category'];
	    	}
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
	    $odin.='
	    		</tbody>
	    	</table>
	    ';
		return $odin;
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
	if(empty($bjornefilterreqmaterials)){
		$opobjpdres=$objpdres->stc_getrequisition_by_multiple_inp($bjornefilterreqbegdate, $bjornefilterreqenddate, $bjornefilterreqcustomerid, $bjornefilterreqnumber, $bjornefilterreqsitename, $bjornefilterreqmaterials, $bjornebegval, $bjorneendval);
	}else{
		$opobjpdres=$objpdres->stc_getrequisition_by_multiple_inp_for_materials($bjornefilterreqbegdate, $bjornefilterreqenddate, $bjornefilterreqcustomerid, $bjornefilterreqnumber, $bjornefilterreqsitename, $bjornefilterreqmaterials, $bjornebegval, $bjorneendval);
	}
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
// 	if($outobjapprqnty=="Approved successfully."){
// 		$objapprpo=new ragnarRequisitionView();
// 		$outobjapprpo=$objapprpo->stc_approved_for_po($pd_id, $req_id, $appr_qty);
// 		$out=$outobjapprpo;
// 	}else{
// 		$out=$outobjapprqnty;
// 	}
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
	$raven=new ragnarRequisitionPertAdd();
	$outraven=$raven->stc_ag_req_direct($stc_req_id, $stc_req_item_id, $dispatch_qty);
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
?>