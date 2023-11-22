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
                		<th>From</th>
                		<th>Value</th>
                		<th>Status</th>
                		<th>View</th>
                	</thead>
                	<tbody>
		';
		$lokigetorder=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_own_agent_order`
			INNER JOIN `stc_own_agents` 
			ON `stc_own_agent_order_agent_id`=`stc_own_agents_id`
			ORDER BY `stc_own_agent_order_id` DESC
			LIMIT 0,15
		");
		$do_action=mysqli_num_rows($lokigetorder);
		if($do_action == 0){
			$lokiout .= "<tr><td colspan='6' align='center'>No Record Found!!!</td></tr>";
		}else{
			$totalval=0;
			foreach ($lokigetorder as $lokivalue) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_own_agent_order_items` WHERE `stc_own_agent_order_items_order_id`='".$lokivalue["stc_own_agent_order_id"]."'
				");
				$agamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$agiqty=$row2nd['stc_own_agent_order_items_pd_qty'];
					$agirate=$row2nd['stc_own_agent_order_items_pd_price'];
					$agamount+=$agiqty * $agirate;
				}

				$lokiout.='
	            		<tr>
	            			<td>STC/A/'.substr("0000{$lokivalue["stc_own_agent_order_id"]}", -5).'</td>
	            			<td>'.date('d-m-Y',strtotime($lokivalue["stc_own_agent_order_date"])).'</td>
	            			<td>'.$lokivalue["stc_own_agents_name"].'</td>
	            			<td><p align="right"><i class="fa fa-rupee-sign"></i> <b>'.number_format($agamount, 2).'</b></p></td>
	            			<td>'.$lokivalue["stc_own_agent_order_status"].'</td>
	            			<td><a class="stc_view_ag_order" href="#" id="'.$lokivalue["stc_own_agent_order_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
	            		</tr>
				';
				$totalval+=$agamount;
			}
		}
		$out=array(
			'total_value' => $totalval,
			'ag_all_record' => $lokiout
		);
		return $out;
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
			SELECT * FROM `stc_own_agent_order`
			INNER JOIN `stc_own_agents` 
			ON `stc_own_agent_order_agent_id`=`stc_own_agents_id`
			WHERE `stc_own_agent_order_id`='".$stc_agorder_id."'
		");
		$stc_purchase_product_items_view_fedit_rec=mysqli_fetch_assoc($check_loki);
		$get_view_fedit_purchase_product_id=substr("0000{$stc_purchase_product_items_view_fedit_rec['stc_own_agent_order_id']}", -5);
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "Something went Wrong!!";
			header('location:index.php');
		}else{
			$nested_check_loki=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_own_agent_order_items`
				LEFT JOIN `stc_product`
				ON `stc_own_agent_order_items_pd_id` =`stc_product_id`
				WHERE `stc_own_agent_order_items_order_id`='".$stc_agorder_id."'
			");
			$order_line_items .= '  
					<table class="table table-hover" align="centre">
						<thead>
						  <tr>
						    <th scope="col">#</th>
						    <th scope="col" width="20%;">Items</th>
						    <th scope="col">HSN Code</th> 
						    <th scope="col">Unit</th>
						    <th scope="col" width="20%;">Total Ordered</th>
						    <th scope="col">Agent Inventory</th>
						    <th scope="col">Agent Soled</th>
						    <th scope="col">Ordered Qty</th>
						    <th scope="col">STC Inventory</th>
						    <th scope="col">Remains qty</th>  
						    <th scope="col">Price</th>
						    <th scope="col">Action</th>
						  </tr>
						</thead>
						<tbody>
			';  
			if(!empty($nested_check_loki)){ 
				$slno= 0; 
				$total = 0;  
				$totalgst = 0;
				foreach($nested_check_loki as $values) { 
					$challanclick=''; 						

					$torderqry=mysqli_query($this->stc_dbs, "
						SELECT SUM(`stc_own_agent_order_items_pd_qty`) AS tordered
						FROM `stc_own_agent_order_items` 
						WHERE `stc_own_agent_order_items_pd_id`='".$values["stc_product_id"]."'
					");
					$torderqty=mysqli_fetch_assoc($torderqry);
					$invqry=mysqli_query($this->stc_dbs, "
						SELECT SUM(`stc_own_agent_inventory_pd_qty`) AS inventory 
						FROM `stc_own_agent_inventory` 
						WHERE `stc_own_agent_inventory_pd_id`='".$values["stc_product_id"]."'
					");
					$invqty=mysqli_fetch_assoc($invqry);
					$tsoled=mysqli_query($this->stc_dbs, "
						SELECT SUM(`stc_own_agent_sale_items_pd_qty`) AS tsoled
						FROM `stc_own_agent_sale_items` 
						WHERE `stc_own_agent_sale_items_pd_id`='".$values["stc_product_id"]."'
					");
					$tsoledqty=mysqli_fetch_assoc($tsoled);
					$stcinvqry=mysqli_query($this->stc_dbs, "
						SELECT SUM(`stc_item_inventory_pd_qty`) AS stcinventory
						FROM `stc_item_inventory` 
						WHERE `stc_item_inventory_pd_id`='".$values["stc_product_id"]."'
					");
					$stcinvqty=mysqli_fetch_assoc($stcinvqry);

					if($stcinvqty["stcinventory"]<=0){
						$challanclick.='
							<a href="#" style="font-size:40px;text-align:center;color:red;" class="btn btn-alert">
								<i class="fas fa-times-circle"></i>
							</a>
						';
					}else{
						$challanclick.='
							<a href="#" id="'.$values["stc_product_id"].'" style="font-size:40px;text-align:center;color:#fff;" class="btn btn-success stcagordgoaginv">
								<i class="fas fa-check-circle"></i>
							</a>
						';
					}
					
					$mainqty= number_format($values["stc_own_agent_order_items_pd_qty"], 2) - (number_format($tsoledqty["tsoled"], 2) + number_format($invqty["inventory"], 2));
					$amount=$values["stc_own_agent_order_items_pd_qty"] * $values["stc_own_agent_order_items_pd_price"];
					$gstamount = ($amount * $values["stc_product_gst"])/100;
					$slno++;
						$order_line_items .= ' 
							<tr>
								<td>'.$slno.'</td>
								<td>'.$values["stc_product_name"].'</td>
								<td>'.$values["stc_product_hsncode"].'</td>
								<td>'.$values["stc_product_unit"].'</td>
								<td align="right">'.number_format($torderqty["tordered"], 2).'</td>
								<td align="right">'.number_format($invqty["inventory"], 2).'</td>
								<td align="right">'.number_format($tsoledqty["tsoled"], 2).'</td>
								<td align="right">'.number_format($values["stc_own_agent_order_items_pd_qty"], 2).'</td>
								<td align="right">'.number_format($stcinvqty["stcinventory"], 2).'</td>
								<td align="right">'.number_format($mainqty, 2).'</td>
								<td align="right">
									<i class="fas fa-rupee-sign"></i> 
									<input type="hidden" 
										class="pd-ag-qty'.$values["stc_product_id"].'" 
										value="'.$values["stc_own_agent_order_items_pd_qty"].'"
									>
										'.number_format($values["stc_own_agent_order_items_pd_price"], 2).'
								</td>
								<td>'.$challanclick.'</td>
							</tr>
						';  
					$total = $total + ($values["stc_own_agent_order_items_pd_qty"] * $values["stc_own_agent_order_items_pd_price"]) ;  
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
			'real_order_number' => $stc_purchase_product_items_view_fedit_rec['stc_own_agent_order_id'],
			'order_date' => date('d-m-Y h:i:s', strtotime($stc_purchase_product_items_view_fedit_rec['stc_own_agent_order_date'])),
			'agentname' => $stc_purchase_product_items_view_fedit_rec['stc_own_agents_id'].'-'.$stc_purchase_product_items_view_fedit_rec['stc_own_agents_name'],
			'line_items' => $order_line_items,
			'agents_id' => $stc_purchase_product_items_view_fedit_rec['stc_own_agents_id']
		);
		return $odin;
	}

	// save order to challan
	public function stc_ag_go_to_challan(){
		$heimdall='';
		foreach($_SESSION['stc_accepted_ag_ord_require'] as $pdrecrow){
			$checkquery=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_own_agent_inventory` 
				WHERE `stc_own_agent_inventory_pd_id`='".$pdrecrow['product_id']."'
			");
			@$getrecqty=mysqli_fetch_assoc($checkquery);
			$finalexqty=$pdrecrow['product_quantity'] + $getrecqty['stc_own_agent_inventory_pd_qty'];
			if(mysqli_num_rows($checkquery)>0){
				$actionquery=mysqli_query($this->stc_dbs, "
					UPDATE `stc_own_agent_inventory` 
					SET `stc_own_agent_inventory_pd_qty`='".$finalexqty."', 
					`stc_own_agent_inventory_agent_id`='".$pdrecrow['agent_id']."'
					WHERE `stc_own_agent_inventory_pd_id`='".$pdrecrow['product_id']."'
				");
			}else{
				$actionquery=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_own_agent_inventory`(
						`stc_own_agent_inventory_pd_id`, 
						`stc_own_agent_inventory_pd_qty`
					) VALUES (
						'".$pdrecrow['product_id']."',
						'".$pdrecrow['product_quantity']."'
					)
				");
			}

			$loki_check_inventory_exist_pd=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_item_inventory` 
				WHERE `stc_item_inventory_pd_id`='".$pdrecrow['product_id']."'
			");
			$loki_getinvenitems=mysqli_fetch_assoc($loki_check_inventory_exist_pd);
			$inventory_pd_id=$loki_getinvenitems['stc_item_inventory_pd_id'];
			$inventory_pd_qty=$loki_getinvenitems['stc_item_inventory_pd_qty'];
			$iventoryrow=mysqli_num_rows($loki_check_inventory_exist_pd);
			if($iventoryrow == 0){
				$odin .= "This Items is not in Inventory!!!";
			}else{
				$finalqty = $inventory_pd_qty - $pdrecrow['product_quantity'];
				$friday_loki_inventory=mysqli_query($this->stc_dbs, "
					UPDATE `stc_item_inventory` 
					SET `stc_item_inventory_pd_qty`='".$finalqty."'
					WHERE `stc_item_inventory_pd_id`='".$pdrecrow['product_id']."'
				");
				if($friday_loki_inventory){
					$heimdall="Items sent successfully!!!";
				}
			}
		}
		return $heimdall;
	}

	// filter agent order
	public function stc_filter_ag_ord($search){
		$lokiout='
				<table class="table table-hover form-group call-order">
                	<thead>
                		<th>Order No</th>
                		<th>Date</th>
                		<th>From</th>
                		<th>Value</th>
                		<th>Status</th>
                		<th>View</th>
                	</thead>
                	<tbody>
		';
		$lokigetorder=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_own_agent_order`
			INNER JOIN `stc_own_agents` 
			ON `stc_own_agent_order_agent_id`=`stc_own_agents_id`
			WHERE `stc_own_agent_order_agent_id` REGEXP '".$search."'
			OR `stc_own_agents_name` REGEXP '".$search."'
			ORDER BY `stc_own_agent_order_id` DESC
		");
		$do_action=mysqli_num_rows($lokigetorder);
		if($do_action == 0){
			$lokiout .= "<tr><td colspan='6' align='center'>No Record Found!!!</td></tr>";
		}else{
			$totalval=0;
			foreach ($lokigetorder as $lokivalue) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_own_agent_order_items` WHERE `stc_own_agent_order_items_order_id`='".$lokivalue["stc_own_agent_order_id"]."'
				");
				$agamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$agiqty=$row2nd['stc_own_agent_order_items_pd_qty'];
					$agirate=$row2nd['stc_own_agent_order_items_pd_price'];
					$agamount+=$agiqty * $agirate;
				}

				$lokiout.='
	            		<tr>
	            			<td>STC/A/'.substr("0000{$lokivalue["stc_own_agent_order_id"]}", -5).'</td>
	            			<td>'.date('d-m-Y',strtotime($lokivalue["stc_own_agent_order_date"])).'</td>
	            			<td>'.$lokivalue["stc_own_agents_name"].'</td>
	            			<td><p align="right"><i class="fa fa-rupee-sign"></i> <b>'.number_format($agamount, 2).'</b></p></td>
	            			<td>'.$lokivalue["stc_own_agent_order_status"].'</td>
	            			<td><a class="stc_view_ag_order" href="#" id="'.$lokivalue["stc_own_agent_order_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
	            		</tr>
				';
				$totalval+=$agamount;
			}
		}
		$lokiout.='
            		</tbody>
            	</table>

		';
		$out=array(
			'total_value' => $totalval,
			'ag_all_record' => $lokiout
		);
		return $out;
	}

	// change status
	public function stc_ag_status($stc_order_no){
		$loki='';
		$lokigo=mysqli_query($this->stc_dbs, "
			UPDATE `stc_own_agent_order` 
			SET `stc_own_agent_order_status`='CLEANED' 
			WHERE `stc_own_agent_order_id`='".$stc_order_no."'
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

	// stc agent creation
	public function stc_agent_hit($agname ,$agemail ,$agcontact ,$agaddress ,$agstate ,$agcity ,$agpincode, $agimagename){
		$odin='';
		$escapeuserid = preg_replace('/\s+/', '', $agname);
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_own_agents` 
			WHERE `stc_own_agents_name`='".mysqli_real_escape_string($this->stc_dbs, $agname)."'  
			AND `stc_own_agents_email`='".mysqli_real_escape_string($this->stc_dbs, $agemail)."'  
			AND `stc_own_agents_contact`='".mysqli_real_escape_string($this->stc_dbs, $agcontact)."'  
			AND `stc_own_agents_pincode`='".mysqli_real_escape_string($this->stc_dbs, $agpincode)."'
		");
		$count_loki_out=mysqli_num_rows($check_loki);
		if($count_loki_out == 0){
			$loki_query=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_own_agents`(
					`stc_own_agents_name`, 
					`stc_own_agents_email`, 
					`stc_own_agents_contact`, 
					`stc_own_agents_address`, 
					`stc_own_agents_state_id`, 
					`stc_own_agents_city_id`, 
					`stc_own_agents_pincode`, 
					`stc_own_agents_img`, 
					`stc_own_agents_userid`, 
					`stc_own_agents_password`
				)
				VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $agname)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $agemail)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $agcontact)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $agaddress)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $agstate)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $agcity)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $agpincode)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $agimagename)."',
					'".mysqli_real_escape_string($this->stc_dbs, $escapeuserid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $agcontact)."'
				)
			");
			if($loki_query){
				$odin = "success";
			}else{
				$odin = "You Need To Try Again!!";
			}
		}else{
			$odin = "This Agent Is Already In Record!!!";
		}
		return $odin;
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
	$stc_pd_qty=$_POST['stc_pd_qty'];
	$agent_id=$_POST['ag_id'];

	if(isset($_SESSION["stc_accepted_ag_ord_require"])) {  
		$is_available = 0;  
		foreach($_SESSION["stc_accepted_ag_ord_require"] as $keys => $values) {  
			if($_SESSION["stc_accepted_ag_ord_require"][$keys]['product_id'] == $pdid) {  
			     $is_available++;  
			     echo "This Item Is Already In A Cart!!!";
			}  
		}  
	    if($is_available < 1) {  
	    	$item_array = array(  
	    		'product_id'			=>     $pdid,
	    		'product_quantity'		=>     $stc_pd_qty,
	    		'agent_id'				=>		$agent_id
	    	);  
	    	$_SESSION["stc_accepted_ag_ord_require"][] = $item_array;  
	    	echo "Item Added to Agent Challan Cart!!!";
	    }  
	}else{  
		$item_array = array(  
		    'product_id'             =>     $pdid,
	    	'product_quantity'		 =>     $stc_pd_qty,
	    	'agent_id'				 =>		$agent_id
		);   
		$_SESSION["stc_accepted_ag_ord_require"][] = $item_array;  
	     echo "Agent Challan Cart Created & Item Added To Agent Challan Cart!!!";	    
	}
}

// save challan to database
if(isset($_POST['go_to_challan'])){
	$out='';

	$ravens=new odin();
	$ravens2=new odin();
	if(!empty($_SESSION['stc_accepted_ag_ord_require'])){
		$outravens=$ravens->stc_ag_go_to_challan();
		unset($_SESSION['stc_accepted_ag_ord_require']);
		$out.="Thankyou!!";
	}else{
		$out.="Please Add Item First!!!";
		// $out.="Abe Boka Saale Cart me Item Add Kar Pahle!!!";
	}
	// echo json_encode($out);
	echo $out;
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

if(isset($_POST['ag-create-perticular'])){
	$agname=$_POST['cr-ag-name'];
	$agemail=$_POST['cr-ag-email'];
	$agcontact=$_POST['cr-ag-contact'];
	$agaddress=$_POST['cr-ag-address'];
	$agstate=$_POST['cr-ag-state'];
	$agcity=$_POST['cr-ag-city'];
	$agpincode=$_POST['cr-ag-pincode'];

	$agimagename=$_FILES['cr-ag-image']['name'];
	$agtmpname=$_FILES['cr-ag-image']['tmp_name'];

	$adago=new odin();

	// function calling
	if($agname=="" || $agemail=="" || $agcontact=="" || $agaddress=="" || $agpincode==""){
		echo "Sab field bhar pahle!!!";
	}else{
		$objadago=$adago->stc_agent_hit($agname ,$agemail ,$agcontact ,$agaddress ,$agstate ,$agcity ,$agpincode, $agimagename);

		if($objadago == "success"){	
			$stcassemble=move_uploaded_file($agtmpname, "../stc_agent_image/".$agimagename);
			if($stcassemble){
				echo "Agent's added!!";
			}else{
				echo "Agent Uploaded Successfully but Except Images!!!";
			}
		}else{
			echo $objadago;
		}
	}
}
?>