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
		$filter=" AND (td.`unique_id` = '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR td.`itemdescription` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR td.`machinesrno` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR td.`make` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR td.`tooltype` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."') ";
		$search=$search==''?'':$filter;
	
		// Check for duplicate unique ID
		$blackpearl_qry = mysqli_query($this->stc_dbs, "
			SELECT tdt.`id` tdt_id, tdt.`user_id`, tdt.`status`, td.`id`, td.`unique_id`, td.`itemdescription`, td.`machinesrno`, td.`make`, td.`tooltype`, td.`remarks` FROM `stc_tooldetails_track` tdt INNER JOIN `stc_tooldetails` td ON tdt.`toolsdetails_id`=td.`id` WHERE tdt.`user_id`='".$_SESSION['stc_agent_sub_id']."' ".$search."
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
		$sqlcheck=mysqli_query($this->stc_dbs, "SELECT `stc_cust_pro_supervisor_id`, `stc_cust_pro_supervisor_fullname` FROM `stc_cust_pro_supervisor` WHERE `stc_cust_pro_supervisor_contact`='".mysqli_real_escape_string($this->stc_dbs, $issuedby)."'");
		if(mysqli_num_rows($sqlcheck)>0){
			$issedbyname='';
			foreach($sqlcheck as $sqlcheckrow){
				$issedbyname=$sqlcheckrow['stc_cust_pro_supervisor_fullname'];
				$issuedby=$sqlcheckrow['stc_cust_pro_supervisor_id'];
			}
			$date1=date("Y-m-d H:i:s");// Check if a record exists for the given toolsdetails_id
			
			// Update the handoverto field of the most recent record
			$update_qry = mysqli_query($this->stc_dbs, "UPDATE stc_tooldetails_track SET handoverto = '".mysqli_real_escape_string($this->stc_dbs, $issedbyname)."' WHERE toolsdetails_id = '".mysqli_real_escape_string($this->stc_dbs, $itt_id)."' order by DATE(`created_date`) desc limit 1");
			
			// Insert the new record
			$blackpearl_qry = mysqli_query($this->stc_dbs, "INSERT INTO stc_tooldetails_track (toolsdetails_id, issuedby, user_id, status, location, issueddate, receivedby, `handoverto`, created_date, created_by, id_type) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $itt_id)."', '".mysqli_real_escape_string($this->stc_dbs, $issedbyname)."', '".$issuedby."', '0', '".mysqli_real_escape_string($this->stc_dbs, $location)."', '".mysqli_real_escape_string($this->stc_dbs, $date)."', '".mysqli_real_escape_string($this->stc_dbs, $issedbyname)."', '', '".mysqli_real_escape_string($this->stc_dbs, $date1)."', '".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."', 'subagent')");

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
	public function stc_equipement_details_recieve($id){	
		// Check for duplicate unique ID
		$blackpearl_qry = mysqli_query($this->stc_dbs, "
			UPDATE `stc_tooldetails_track` SET `status`='1', `receivedby`='".$_SESSION['stc_agent_sub_name']."' WHERE `id`='".mysqli_real_escape_string($this->stc_dbs, $id)."'
		");
		$blackpearl='';
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

	// save equipment details
	public function stc_equipement_details_save($location, $department, $area, $sublocation, $equipment_name, $slno, $unit, $equipment_type, $equipment_no, $model_no, $capacity){
		$blackpearl = '';
		$date1 = date("Y-m-d H:i:s");
		$prev_unit='';
		$incrementer=str_pad(1, 3, '0', STR_PAD_LEFT);
		$check_qry = mysqli_query($this->stc_dbs, "SELECT `unit_no` FROM `equipment_details` WHERE `slno` = '" . mysqli_real_escape_string($this->stc_dbs, $slno) . "' ORDER BY `id` DESC LIMIT 0,1");
		if(mysqli_num_rows($check_qry) > 0){
			foreach($check_qry as $check_row){
				$prev_unit=$check_row['unit_no'];
				// Extract the integer part from `prev_unit`
				$unit_number = preg_replace('/[^0-9]/', '', $prev_unit);

				// Increment the integer part by 1
				$new_number = (int)$unit_number + 1;
		
				// Pad the new number with leading zeros
				$padded_number = str_pad($new_number, 3, '0', STR_PAD_LEFT);
		
				// Combine the unit prefix with the padded number
				$unit = 'G' . $unit . $padded_number;
			}
		}else{				
			$unit='G'.$unit.$incrementer;
		}
		// Insert the new record into the equipment_details table
		$blackpearl_qry = mysqli_query($this->stc_dbs, "INSERT INTO equipment_details (`location`, `department`, `area`, `sub_location`, `model_no`, `capacity`, `equipment_name`, `slno`, `unit_no`, `equipment_type`, `equipment_no`, `created_by`, `created_date`) VALUES ('" . mysqli_real_escape_string($this->stc_dbs, $location) . "', '" . mysqli_real_escape_string($this->stc_dbs, $department) . "', '" . mysqli_real_escape_string($this->stc_dbs, $area) . "', '" . mysqli_real_escape_string($this->stc_dbs, $sublocation) . "', '" . mysqli_real_escape_string($this->stc_dbs, $model_no) . "', '" . mysqli_real_escape_string($this->stc_dbs, $capacity) . "', '" . mysqli_real_escape_string($this->stc_dbs, $equipment_name) . "', '" . mysqli_real_escape_string($this->stc_dbs, $slno) . "', '" . mysqli_real_escape_string($this->stc_dbs, $unit) . "', '" . mysqli_real_escape_string($this->stc_dbs, $equipment_type) . "', '" . mysqli_real_escape_string($this->stc_dbs, $equipment_no) . "', '" . mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id']) . "', '" . mysqli_real_escape_string($this->stc_dbs, $date1) . "')");

		if ($blackpearl_qry) {
			$blackpearl = 'yes';
		} else {
			$blackpearl = 'no';
		}
		return $blackpearl;
	}

	// update equipment details
	public function stc_equipement_details_update($id, $label, $value){
		$blackpearl = '';
		$date1 = date("Y-m-d H:i:s");
		
		// Insert the new record into the equipment_details table
		$blackpearl_qry = mysqli_query($this->stc_dbs, "UPDATE `equipment_details` SET ".$label."='".mysqli_real_escape_string($this->stc_dbs, $value)."' WHERE `id`='".mysqli_real_escape_string($this->stc_dbs, $id)."'");

		if ($blackpearl_qry) {
			$blackpearl = 'yes';
		} else {
			$blackpearl = 'no';
		}
		return $blackpearl;
	}

	// show equipment details perticular
	public function stc_get_equipmentdetails($id){
		$blackpearl = array();		
		// Insert the new record into the equipment_details table
		$blackpearl_qry = mysqli_query($this->stc_dbs, "SELECT * FROM `equipment_details` WHERE `id`='".mysqli_real_escape_string($this->stc_dbs, $id)."'");
		if(mysqli_num_rows($blackpearl_qry)>0){
			foreach($blackpearl_qry as $blackpearl_row){
				$blackpearl[]=$blackpearl_row;
			}
		}
		return $blackpearl;
	}

	// show equipmentdetails 
	public function stc_equipement_details_get($search){
		$filter=" AND (`equipment_name` = '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR `equipment_type` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR `area` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR `sub_location` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR `stc_status_down_list_department_dept` regexp '".mysqli_real_escape_string($this->stc_dbs, $search)."')";
		$search=$search==''?'':$filter;
	
		// Check for duplicate unique ID
		$blackpearl_qry = mysqli_query($this->stc_dbs, "
			SELECT `id`, `area`, `stc_status_down_list_department_location`, `stc_status_down_list_department_dept`, `sub_location`, `model_no`, `capacity`, `equipment_name`, `equipment_no`, `equipment_type`, `stc_cust_pro_supervisor_fullname`, `created_date`  FROM `equipment_details` INNER JOIN `stc_cust_project` ON `stc_cust_project_id`=`equipment_details`.`location` INNER JOIN `stc_status_down_list_department` ON `stc_status_down_list_department_id`=`equipment_details`.`department` INNER JOIN `stc_cust_pro_supervisor` ON `equipment_details`.`created_by`=`stc_cust_pro_supervisor_id` WHERE `department` IN (SELECT `stc_status_down_list_department_id` FROM `stc_cust_pro_attend_supervise` INNER JOIN `stc_status_down_list_department` ON `stc_status_down_list_department_loc_id`=`stc_cust_pro_attend_supervise_pro_id` WHERE `stc_cust_pro_attend_supervise_super_id`='".$_SESSION['stc_agent_sub_id']."') ".$search." ORDER BY TIMESTAMP(`created_date`) DESC
		");
		$blackpearl=[];
		if(mysqli_num_rows($blackpearl_qry)>0){
			while ($blackpearl_row = mysqli_fetch_assoc($blackpearl_qry)) {
				$blackpearl_row['stc_agent_sub_category'] = $_SESSION['stc_agent_sub_category'];
				$blackpearl[] = $blackpearl_row;
			}
		}
	
		return $blackpearl;
	}

	// call location	
	public function stc_call_locdept(){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_status_down_list_department_location`
			FROM `stc_cust_pro_attend_supervise`
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_cust_pro_attend_supervise_pro_id` 
			INNER JOIN `stc_status_down_list_department` 
			ON `stc_cust_project_id`=`stc_status_down_list_department_loc_id` 
			WHERE `stc_cust_pro_attend_supervise_super_id`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."' ORDER BY `stc_status_down_list_department_location` ASC
		");
		$optimusprime = "";
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "empty";
		}else{
			$optimusprime.='<option value="NA">Select</option>';	
			foreach ($optimusprimequery as $row) {
				$optimusprime.='<option>'.$row['stc_status_down_list_department_location'].'</option>';		
			}			
		}
		return $optimusprime;
	}

	// call location department	
	public function stc_call_deptloc($loca_id){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT  `stc_status_down_list_department_dept`, `stc_status_down_list_department_id`, `stc_cust_project_id`
			FROM `stc_cust_pro_attend_supervise`
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_cust_pro_attend_supervise_pro_id` 
			INNER JOIN `stc_status_down_list_department` 
			ON `stc_cust_project_id`=`stc_status_down_list_department_loc_id` 			
			WHERE `stc_status_down_list_department_location`='".mysqli_real_escape_string($this->stc_dbs, $loca_id)."'
			AND `stc_cust_pro_attend_supervise_super_id`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."'
		");
		$optimusprime = "<option value='NA'>Select</option>";
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "<option value='NA'>No Deparmtent Found.</option>";
		}else{
			foreach ($optimusprimequery as $row) {
				$optimusprime.='<option value="'.$row['stc_status_down_list_department_id'].'" project-id="'.$row['stc_cust_project_id'].'">'.$row['stc_status_down_list_department_dept'].'</option>';		
			}			
		}
		return $optimusprime;
	}

	// delete equipment details
	public function stc_delete_equipmentdetails($id){
		$blackpearl='no';
		$blackpearl_qry = mysqli_query($this->stc_dbs, "
			DELETE FROM `equipment_details` WHERE `id`=".mysqli_real_escape_string($this->stc_dbs, $id)." AND `created_by`='".$_SESSION['stc_agent_sub_id']."'
		");
		if($blackpearl_qry){
			$blackpearl="success";
		}
		return $blackpearl;
	}

	public function stc_get_equipment_log_ids($id) {
		$blackpearl = array();		
		$date = date("Y-m-d");

		// Escape input
		$id_safe = mysqli_real_escape_string($this->stc_dbs, $id);

		// Check if records already exist for today
		$blackpearl_qry = mysqli_query($this->stc_dbs, "SELECT EDL.*, ED.unit_no FROM `equipment_details_log` EDL INNER JOIN `equipment_details` ED ON EDL.`equipment_details_id`=ED.`id` WHERE EDL.`equipment_details_id` = '$id_safe' AND `status`=2 ORDER BY EDL.`id` DESC LIMIT 1");

		if (mysqli_num_rows($blackpearl_qry) > 0) {
			// Fetch rows
			while ($row = mysqli_fetch_assoc($blackpearl_qry)) {
				$row['date'] = date('Y-m-d', strtotime($row['created_date']));
				$row['time'] = date('h:i', strtotime($row['created_date']));
				$query=mysqli_query($this->stc_dbs, "SELECT * FROM `equipment_details_log_comp` WHERE `equipment_details_log_id`=".$row['id']);
				$compressor_reading_array=array();
				if($query && mysqli_num_rows($query)>0){
					foreach($query as $queryrow){
						$compressor_reading_array[]=$queryrow;
					}
				}
				$row['compressor_reading'] = $compressor_reading_array ? $compressor_reading_array : 'NA';
				$blackpearl[] = $row;
			}
		} else {
			$date = date("Y-m-d h:i A");
			// Insert a new record if none exist
			$insert_qry = mysqli_query( $this->stc_dbs, "INSERT INTO `equipment_details_log` (`equipment_details_id`, `status`, `created_date`, `created_by`) VALUES ('$id_safe', '2', '$date', '".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."')" );
			$date = date("Y-m-d");

			if ($insert_qry) {
				// Fetch the newly inserted row(s)
				$new_qry = mysqli_query( $this->stc_dbs, "SELECT EDL.*, ED.unit_no FROM `equipment_details_log` EDL INNER JOIN `equipment_details` ED ON EDL.`equipment_details_id`=ED.`id` WHERE EDL.`equipment_details_id` = '$id_safe' AND status=2 ORDER BY EDL.`id` DESC LIMIT 1" );

				while ($row = mysqli_fetch_assoc($new_qry)) {
					$row['date'] = date('Y-m-d', strtotime($row['created_date']));
					$row['time'] = date('h:i', strtotime($row['created_date']));
					$query=mysqli_query($this->stc_dbs, "SELECT * FROM `equipment_details_log_comp` WHERE `equipment_details_log_id`=".$row['id']);
					$compressor_reading_array=array();
					if($query && mysqli_num_rows($query)>0){
						foreach($query as $queryrow){
							$compressor_reading_array[]=$queryrow;
						}
					}
					$row['compressor_reading'] = $compressor_reading_array ? $compressor_reading_array : 'NA';
					$blackpearl[] = $row;
				}
			}
		}

		return $blackpearl;
	}

	public function stc_save_equipment_log_comp_reading($ed_log_id, $id, $suction_pr_psig, $disc_pr, $disc_temp_degC, $dsh, $oil_level, $comp_load){
		if(empty($_SESSION['stc_agent_sub_id'])){
			return 'reload';
		}
		if($id>0){
			$query=mysqli_query($this->stc_dbs, "UPDATE `equipment_details_log_comp` SET `suction_pr_psig`='".mysqli_real_escape_string($this->stc_dbs, $suction_pr_psig)."', `disc_pr`='".mysqli_real_escape_string($this->stc_dbs, $disc_pr)."', `disc_temp_degC`='".mysqli_real_escape_string($this->stc_dbs, $disc_temp_degC)."', `dsh`='".mysqli_real_escape_string($this->stc_dbs, $dsh)."', `oil_level`='".mysqli_real_escape_string($this->stc_dbs, $oil_level)."', `comp_load`='".mysqli_real_escape_string($this->stc_dbs, $comp_load)."', `updated_by`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."', `updated_date`='".date("Y-m-d H:i:s")."' WHERE `id`='".mysqli_real_escape_string($this->stc_dbs, $id)."'");
		}else{
			$query=mysqli_query($this->stc_dbs, "INSERT INTO `equipment_details_log_comp` (`equipment_details_log_id`, `suction_pr_psig`, `disc_pr`, `disc_temp_degC`, `dsh`, `oil_level`, `comp_load`, `created_by`, `created_date`) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $ed_log_id)."', '".mysqli_real_escape_string($this->stc_dbs, $suction_pr_psig)."', '".mysqli_real_escape_string($this->stc_dbs, $disc_pr)."', '".mysqli_real_escape_string($this->stc_dbs, $disc_temp_degC)."', '".mysqli_real_escape_string($this->stc_dbs, $dsh)."', '".mysqli_real_escape_string($this->stc_dbs, $oil_level)."', '".mysqli_real_escape_string($this->stc_dbs, $comp_load)."', '".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."', '".date("Y-m-d H:i:s")."')");
		}
		
		return "yes";
	}

	public function stc_update_equipment_log($id, $label, $value){
		if (empty($_SESSION['stc_agent_sub_id'])) {
			return 'reload';
		}

		// Validate the label to prevent SQL injection
		$allowed_labels = ['voltage', 'chw_inlet_temp', 'chw_outlet_temp', 'chw_inlet_pr', 'chw_outlet_pr', 'cow_inlet_temp', 'cow_outlet_temp', 'cow_inlet_pr', 'cow_outlet_pr'];
		if (!in_array($label, $allowed_labels)) {
			return 'invalid_label';
		}

		$query = mysqli_query($this->stc_dbs, "UPDATE `equipment_details_log` SET `$label`='" . mysqli_real_escape_string($this->stc_dbs, $value) . "', `updated_by`='" . mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id']) . "', `updated_date`='" . date("Y-m-d H:i:s") . "' WHERE `id`='" . mysqli_real_escape_string($this->stc_dbs, $id) . "'");

		return $query ? 'yes' : 'no';
	}

	public function stc_update_ed_log_status($id){
		if (empty($_SESSION['stc_agent_sub_id'])) {
			return 'reload';
		}

		$query = mysqli_query($this->stc_dbs, "UPDATE `equipment_details_log` SET `status`=1 WHERE `id`='" . mysqli_real_escape_string($this->stc_dbs, $id) . "'");

		return $query ? 'yes' : 'no';
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

// save tools tracker
if(isset($_POST['save_tool_trackertrack'])){
	$issuedby=$_POST['issuedby'];
	$location=$_POST['location'];
	$date=$_POST['date'];
	$receivedby=$_POST['receivedby'];
	$handoverto=$_POST['handoverto'];
	$itt_id=$_POST['itt_id'];
	$out='';
	if(empty($_SESSION['stc_agent_sub_id'])){
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
#<-----------------Object section of equipment details Class------------------->
// save equipment details
if (isset($_POST['save_equipementdetails'])) {
    $location = $_POST['location'];
    $department = $_POST['department'];
    $area = $_POST['area'];
    $sublocation = $_POST['sublocation'];
    $equipment_name = $_POST['equipment_name'];
    $equipment_type = $_POST['equipment_type'];
    $equipment_no = $_POST['equipment_no'];
    $slno = $_POST['slno'];
    $unit = $_POST['unit'];
    $model_no = $_POST['model_no'];
    $capacity = $_POST['capacity'];
    $out = '';

    if (empty($_SESSION['stc_agent_sub_id'])) {
        $out = 'reload';
    } else {
        $odin_req = new prime();
        $out = $odin_req->stc_equipement_details_save($location, $department, $area, $sublocation, $equipment_name, $slno, $unit, $equipment_type, $equipment_no, $model_no, $capacity);
    }
    echo $out;
}

// save equipment details
if (isset($_POST['update_equipementdetails'])) {
    $id = $_POST['id'];
    $label = $_POST['label'];
    $value = $_POST['value'];
    $out = '';

    if (empty($_SESSION['stc_agent_sub_id'])) {
        $out = 'reload';
    } else {
        $odin_req = new prime();
        $out = $odin_req->stc_equipement_details_update($id, $label, $value);
    }
    echo $out;
}

// save equipment details
if (isset($_POST['call_tool_trackertrackrecieve'])) {
    $id = $_POST['itt_id'];
    $out = '';

    if (empty($_SESSION['stc_agent_sub_id'])) {
        $out = 'reload';
    } else {
        $odin_req = new prime();
        $out = $odin_req->stc_equipement_details_recieve($id);
    }
    echo $out;
}

// delete equipment details
if(isset($_POST['get_equipementdetails'])){
	$id=$_POST['id'];
	$metabots=new prime();
	$opmetabots=$metabots->stc_get_equipmentdetails($id);
	echo json_encode($opmetabots);
}

// call equipment details
if(isset($_POST['call_equipementdetails'])){
	$search=isset($_POST['search']) ? $_POST['search'] : '';
	$odin_req=new prime();
	$odin_req_out=$odin_req->stc_equipement_details_get($search);
	echo json_encode($odin_req_out);
}

// call location
if(isset($_POST['call_location'])){
	$metabots=new prime();
	$opmetabots=$metabots->stc_call_locdept();
	echo json_encode($opmetabots);
}

// call departemnt
if(isset($_POST['call_department'])){
	$loca_id=$_POST['loca_id'];
	$metabots=new prime();
	$opmetabots=$metabots->stc_call_deptloc($loca_id);
	echo json_encode($opmetabots);
}

// delete equipment details
if(isset($_POST['delete_equipmentdetails'])){
	$id=$_POST['id'];
	$metabots=new prime();
	$opmetabots=$metabots->stc_delete_equipmentdetails($id);
	echo json_encode($opmetabots);
}

if(isset($_POST['get_equipment_log_changes'])) {
	$id = $_POST['equipment_id'];
	$metabots = new prime();
	$opmetabots = $metabots->stc_get_equipment_log_ids($id);
	echo json_encode($opmetabots);
}

if(isset($_POST['stc_ed_log_comp_reading_save'])) {
	$ed_log_id = $_POST['ed_log_id'];
	$id = $_POST['eq_comp_reading_id'];
	$suction_pr_psig = $_POST['suction_pr_psig'];
	$disc_pr = $_POST['disc_pr'];
	$disc_temp_degC = $_POST['disc_temp_degC'];
	$dsh = $_POST['dsh'];
	$oil_level = $_POST['oil_level'];
	$comp_load = $_POST['comp_load'];
	$metabots = new prime();
	$opmetabots = $metabots->stc_save_equipment_log_comp_reading($ed_log_id, $id, $suction_pr_psig, $disc_pr, $disc_temp_degC, $dsh, $oil_level, $comp_load);
	echo json_encode($opmetabots);
}

if(isset($_POST['update_equipementdetails'])) {
	$id = $_POST['id'];
	$label = $_POST['label'];
	$value = $_POST['value'];
	$metabots = new prime();
	$opmetabots = $metabots->stc_update_equipment_log($id, $label, $value);
	echo json_encode($opmetabots);
}

if(isset($_POST['stc_ed_log_status_update'])) {
	$id = $_POST['id'];
	$metabots = new prime();
	$opmetabots = $metabots->stc_update_ed_log_status($id);
	echo json_encode($opmetabots);
}

?>