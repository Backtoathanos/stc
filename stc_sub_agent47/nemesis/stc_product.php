<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------For Sub supervisor------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class prime extends tesseract{

	// search product
	public function stc_search_product($search_prod_name){
		$perfectsearchme=strtoupper($search_prod_name);
		$purchase_rate='';
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					WHERE `stc_product_name` REGEXP '".$perfectsearchme."' 
					OR `stc_product_desc` REGEXP '".$perfectsearchme."' 
					ORDER BY `stc_product_id` 
					ASC LIMIT 0,30
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
				$percalamount = ($purchase_rate['stc_purchase_product_items_rate'] * 5)/100;
				$pdsale_price = $percalamount + $purchase_rate['stc_purchase_product_items_rate'];
				$odin.='
						<div class="el-wrapper">
						  	<div class="box-up">
						        <img class="img" style="height: 100%; width:100%;" src="../stc_symbiote/stc_product_image/'.$row["stc_product_image"].'" alt="">
						        <div class="img-info">
						 	        <div class="info-inner">
						 	            <span class="p-name">'.htmlentities($row["stc_product_name"]).'</span>
						 	            <span class="p-company">'.$row["stc_product_unit"].'</span>
						 	            <input 
						     	        	type="hidden" 
						     	        	name="stcpdprice" 
						     	        	id="stcpdprice'.$row["stc_product_id"].'" 
						     	        	placeholder=""
						     	        	class="form-control validate"
						         			value="'.number_format($pdsale_price, 2, '.', '').'" 
						         			style="color:black;font-weight:bold;text-align:center"
						         			disabled
						     	    	/>
						 	        </div>
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

						        <a class="cart addtocartagent" href="#" id="'.$row["stc_product_id"].'">
						          <span class="price">'.$row["stc_sub_cat_name"].'</span>
						          <span class="add-to-cart">
						            <span class="txt">Add Item</span>
						          </span>
						        </a>
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
			             No Product Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}

	// call product
	public function call_products($row, $rowperpage){
		$purchase_rate='';
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					ORDER BY `stc_product_id` ASC LIMIT ".$row.",".$rowperpage
		;
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
				$percalamount = ($purchase_rate['stc_purchase_product_items_rate'] * 5)/100;
				$pdsale_price = $percalamount + $purchase_rate['stc_purchase_product_items_rate'];
				$odin.='
						<div class="el-wrapper">
						  	<div class="box-up">
						        <img class="img" style="height: 100%; width:100%;" src="../stc_symbiote/stc_product_image/'.$row["stc_product_image"].'" alt="">
						        <div class="img-info">
						 	        <div class="info-inner">
						 	            <span class="p-name">'.htmlentities($row["stc_product_name"]).'</span>
						 	            <span class="p-company">'.$row["stc_product_unit"].'</span>
						 	            <input 
						     	        	type="hidden" 
						     	        	name="stcpdprice" 
						     	        	id="stcpdprice'.$row["stc_product_id"].'" 
						     	        	placeholder=""
						     	        	class="form-control validate"
						         			value="'.number_format($pdsale_price, 2, '.', '').'" 
						         			style="color:black;font-weight:bold;text-align:center"
						         			disabled
						     	    	/>
						 	        </div>
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

						        <a class="cart addtocartagent" href="#" id="'.$row["stc_product_id"].'">
						          <span class="price">'.$row["stc_sub_cat_name"].'</span>
						          <span class="add-to-cart">
						            <span class="txt">Add Item</span>
						          </span>
						        </a>
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
			             No Product Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}

	// call agent orders
	public function stc_ag_order_on_agent(){
		$optotal=0;
		$optimusprime='
			<table class="mb-0 table table-hover table-responsive">
				<thead>
				    <th>Order No</th>
				    <th>Order Date</th>
				    <th>For</th>
				    <th>Status</th>
				    <th>View</th>
				</thead>
				<tbody>
		';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_id`,
			    `stc_cust_super_requisition_date`,
			    `stc_cust_super_requisition_status`,
			    `stc_cust_project_title`
			FROM `stc_cust_super_requisition`
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_cust_super_requisition_project_id`
			WHERE `stc_cust_super_requisition_super_id`='".$_SESSION["stc_agent_sub_id"]."'
			ORDER BY `stc_cust_super_requisition_id` DESC
		");
		$countopquery=mysqli_num_rows($optimusprimequery);
		if($countopquery>0){
			foreach($optimusprimequery as $primerow){
				$primerowstatus="";
				if($primerow['stc_cust_super_requisition_status']=="1"){
					$primerowstatus="PROCESS";
				}else{
					$primerowstatus="ACCEPTED";
				}
				$optimusprime.="
					<tr>
						<td>STC/O/A/S/".substr("0000{$primerow['stc_cust_super_requisition_id']}", -5)."</td>
						<td>".date('d-M-Y', strtotime($primerow['stc_cust_super_requisition_date']))."</td>
						<td>".$primerow['stc_cust_project_title']."</td>
						<td>".$primerowstatus."</td>
						<td><a href='#' style='font-size: 25px;font-weight: bold;color: black;' class='ag-show-grid' id='".$primerow['stc_cust_super_requisition_id']."'><i class='fas fa-eye'></i></a></td>
					</tr>
					<tr>
						<td colspan='6'>
							<div style='display:none;' id='togdiv".$primerow['stc_cust_super_requisition_id']."'>
								Loading...
							</div>
						</td>
					</tr>
				";
			}
		}else{
				$optimusprime.="
					<tr>
						<td colspan='6' align='center'>No Records Found!!!</td>
					</tr>
				";
		}
		$optimusprime.='
				</tbody>
			</table>
		';
		return $optimusprime;
	}

	// call items 
	public function stc_ag_order_items_call($odid){
		$sl=1;
		$subcat='';
		$countqty=0;
		$transformers='
			<table class="mb-0 table table-hover">
				<thead>
				    <th>Sl No</th>
				    <th width="45%">Material Desc</th>
				    <th>Unit</th>
				    <th>Order Qty</th>
				    <th>Balance Qty</th>
				    <th>Recieved Qty</th>
				    <th>Status</th>
				    <th>Consumed Qty</th>
				</thead>
				<tbody>
		';
		$transformersquery=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_product_id`,
				`stc_product_name`,
				`stc_sub_cat_name`,
				`stc_product_unit`,
				`stc_cust_super_requisition_items_product_qty`,
				`stc_cust_super_requisition_items_status`,
				`stc_cust_super_requisition_items_sup_req_id`
			FROM `stc_cust_super_requisition_items`
			INNER JOIN `stc_product`
			ON `stc_cust_super_requisition_items_product_id`=`stc_product_id`
			INNER JOIN `stc_sub_category`
			ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
			WHERE `stc_cust_super_requisition_items_sup_req_id`='".$odid."'
		");
		if(mysqli_num_rows($transformersquery)>0){
			foreach($transformersquery as $firstrow){
				$reqstaus='';
				if($firstrow['stc_cust_super_requisition_items_status']==1){
					$reqstaus='ALLOW';
				}else{
					$reqstaus='NOT ALLOW';
				}
				$getrecivedtransformers=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_cust_super_requisition_rec_items_product_qty` 
					FROM `stc_cust_super_requisition_rec_items` 
					WHERE 
						`stc_cust_super_requisition_rec_items_sup_req_id`='".$firstrow['stc_cust_super_requisition_items_sup_req_id']."'
					AND 
						`stc_cust_super_requisition_rec_items_product_id`='".$firstrow['stc_product_id']."'
				");
				foreach($getrecivedtransformers as $countrecqtyrow){
					$countqty+=$countrecqtyrow['stc_cust_super_requisition_rec_items_product_qty'];
				}
				if($firstrow['stc_sub_cat_name']=="OTHERS"){
					$subcat='';
				}else{
					$subcat=$firstrow['stc_sub_cat_name'];
				}
				$transformers.='
					<tr>
						<td>'.$sl.'</td>
						<td>'.$subcat.' '.$firstrow['stc_product_name'].'</td>
						<td>'.$firstrow['stc_product_unit'].'</td>
						<td>'.number_format($firstrow['stc_cust_super_requisition_items_product_qty'], 2).'</td>
						<td>'.number_format($firstrow['stc_cust_super_requisition_items_product_qty'], 2).'</td>
						<td>'.number_format($countqty, 2).'</td>
						<td>'.$reqstaus.'</td>
						<td>'.number_format($countqty, 2).'</td>
					</tr>
				';
				$sl++;
				$countqty=0;
			}
		}else{
			$transformers.='
					<tr>
						<td colspan="7"><h4>No Products Found!!!</h6></td>
					</tr>
			';
		}
		$transformers.='
				</tbody>
			</table>
		';
		return $transformers;
	}

	// show tool track
	public function stc_tool_tracker_get($search){
		$filter=" WHERE `unique_id` = '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR `itemdescription` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR `machinesrno` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR `make` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR `tooltype` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR `purchase_details` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR `taxinvono` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' ";
		$search=$search==''?'':$filter;
	
		// Check for duplicate unique ID
		$blackpearl_qry = mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_tooldetails` LEFT JOIN `stc_user` ON `stc_tooldetails`.`created_by`=`stc_user`.`stc_user_id` ".$search."
		");
		$blackpearl=[];
		if(mysqli_num_rows($blackpearl_qry)>0){
			while ($blackpearl_row = mysqli_fetch_assoc($blackpearl_qry)) {
				$blackpearl[] = $blackpearl_row;
			}
		}
	
		return $blackpearl;
	}

	// save tracking
	public function stc_tool_trackertrack_save($issuedby, $location, $date, $receivedby, $handoverto, $itt_id){
		$blackpearl='';
		$date1=date("Y-m-d H:i:s");// Check if a record exists for the given toolsdetails_id
		$check_qry = mysqli_query($this->stc_dbs, "SELECT `id` FROM `stc_tooldetails_track` WHERE `toolsdetails_id` = '".mysqli_real_escape_string($this->stc_dbs, $itt_id)."' ORDER BY TIMESTAMP(`created_date`) DESC LIMIT 1");
		
		if (mysqli_num_rows($check_qry) > 0) {
			// Get the most recent record
			$record = mysqli_fetch_assoc($check_qry);
		
			// Update the handoverto field of the most recent record
			$update_qry = mysqli_query($this->stc_dbs, "UPDATE stc_tooldetails_track SET handoverto = '".mysqli_real_escape_string($this->stc_dbs, $issuedby)."' WHERE id = '".mysqli_real_escape_string($this->stc_dbs, $record['id'])."'");
		}
		
		// Insert the new record
		$blackpearl_qry = mysqli_query($this->stc_dbs, "INSERT INTO stc_tooldetails_track (toolsdetails_id, issuedby, location, issueddate, receivedby, `handoverto`, created_date, created_by, id_type) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $itt_id)."', '".mysqli_real_escape_string($this->stc_dbs, $issuedby)."', '".mysqli_real_escape_string($this->stc_dbs, $location)."', '".mysqli_real_escape_string($this->stc_dbs, $date)."', '".mysqli_real_escape_string($this->stc_dbs, $receivedby)."', '', '".mysqli_real_escape_string($this->stc_dbs, $date1)."', '".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_empl_id'])."', 'subagent')");

		if($blackpearl_qry){
			$blackpearl='yes';
		}else{
			$blackpearl='no';
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
// search product
if(isset($_POST['search_prod_name'])){
	if(strlen($_POST["search_prod_name"])>=4){	  	
		$search_prod_name=$_POST['search_prod_name'];
		$objlogin=new prime();
		$opobjlogin=$objlogin->stc_search_product($search_prod_name);
		$out = $opobjlogin;
	}else{
		$out = '
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			     	No Products Found!!!
			    </a>
			</div>
		';
	}
	echo $out;
}

// call product
if(isset($_POST['go_through'])){
	$row = $_POST['row'];
	$rowperpage = 3;
	$bumblebee=new prime();
	$outbumblebee=$bumblebee->call_products($row, $rowperpage);
	echo $outbumblebee;
}

// call orders
if(isset($_POST['load_order_ag'])){
	$cybertron=new prime();
	$outcybertron=$cybertron->stc_ag_order_on_agent();
	echo $outcybertron;
}

// call items
if(isset($_POST['get_orders_pert'])){
	$odid=$_POST['odid'];
	$megabots=new prime();
	$outmegabots=$megabots->stc_ag_order_items_call($odid);
	echo $outmegabots;
}
#<-----------------Object section of tool tracker Class------------------->
// call tools tracker
if(isset($_POST['call_tools_tracker'])){
	$search=isset($_POST['search']) ? $_POST['search'] : '';
	$odin_req=new prime();
	$odin_req_out=$odin_req->stc_tool_tracker_get($search);
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
		$odin_req=new prime();
		$out=$odin_req->stc_tool_trackertrack_save($issuedby, $location, $date, $receivedby, $handoverto, $itt_id);
	}
	echo $out;
}

// call tools tracking tracker
if(isset($_POST['call_tool_trackertrack'])){
	$itt_id=$_POST['itt_id'];
	$odin_req=new prime();
	$odin_req_out=$odin_req->stc_tool_trackertrack_get($itt_id);
	echo json_encode($odin_req_out);
}

?>