<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents--------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class transformers extends tesseract{
	// call sitename
	public function stc_call_location(){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_cust_project_id`, `stc_cust_project_title` FROM `stc_cust_pro_attend_supervise`
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_cust_pro_attend_supervise_pro_id` 
			WHERE `stc_cust_pro_attend_supervise_super_id`='".$_SESSION['stc_agent_sub_id']."'
		");
		$optimusprime='<option value="NA" selected>Select Site</option>';
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "<option value='NA' selected>No Site Found !!</option>";
		}else{
			foreach ($optimusprimequery as $row) {
				$optimusprime.='
					<option value="'.$row["stc_cust_project_id"].'">'.$row["stc_cust_project_title"].'</option>		               	
		    	';				
			}			
		}
		return $optimusprime;
	}

	// call items using siteid
	public function stc_call_items($site_id){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT 
			    `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`,
			    `stc_cust_super_requisition_list_items_title`,
			    `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty`
			FROM `stc_cust_super_requisition_rec_items_fr_supervisor` 
			INNER JOIN `stc_cust_super_requisition_list_items` 
			ON `stc_cust_super_requisition_list_items`.`stc_cust_super_requisition_list_id`=`stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid` 
			INNER JOIN `stc_cust_super_requisition_list` 
			ON `stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id`=`stc_cust_super_requisition_list_items_req_id`
            WHERE `stc_cust_super_requisition_list_super_id`='".$_SESSION['stc_agent_sub_id']."' 
            AND `stc_cust_super_requisition_list_project_id`='".$site_id."'
			ORDER BY `stc_cust_super_requisition_list_items_title` ASC
		");
		$optimusprime='<option value="NA" selected>Select Item</option>';
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "<option value='NA' selected>No Item Found !!</option>";
		}else{
			foreach ($optimusprimequery as $row) {
				$sqlqry=mysqli_query($this->stc_dbs, "
					SELECT `stc_cust_super_list_items_consumption_items_qty`
					FROM `stc_cust_super_list_items_consumption_items`
					WHERE `stc_cust_super_list_items_consumption_items_name`='".$row["stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid"]."'
				");
				$qty=0;
				if(mysqli_num_rows($sqlqry)>0){
					foreach($sqlqry as $sqlrow){
						$qty=$sqlrow['stc_cust_super_list_items_consumption_items_qty'];
					}
				}
				if($row['stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty']>$qty){
					$optimusprime.='
						<option value="'.$row["stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid"].'">'.$row["stc_cust_super_requisition_list_items_title"].'</option>		               	
					';	
				}			
			}			
		}
		return $optimusprime;
	}

	// add consumtion table
	public function stc_super_consumption_add($sup_site, $sup_location, $job_details){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_cust_super_list_items_consumption`( 
				`stc_cust_super_list_items_consumption_date`, 
				`stc_cust_super_list_items_consumption_site_id`, 
				`stc_cust_super_list_items_consumption_super_id`, 
				`stc_cust_super_list_items_consumption_sub_location`,
				`stc_cust_super_list_items_consumption_job_details`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $sup_site)."',
				'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."',
				'".mysqli_real_escape_string($this->stc_dbs, $sup_location)."',
				'".mysqli_real_escape_string($this->stc_dbs, $job_details)."'
			)
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// add consumtion items table
	public function stc_super_consumption_items_add(){
		$optimusprime='';
		$getorderid=0;
		$optimusprimegetid=mysqli_query($this->stc_dbs, "
			SELECT MAX(`stc_cust_super_list_items_consumption_id`) AS orderid 
			FROM `stc_cust_super_list_items_consumption` 
			ORDER BY `stc_cust_super_list_items_consumption_id` DESC
		");
		foreach($optimusprimegetid as $getidrow){
			$getorderid=$getidrow['orderid'];
		}
		foreach($_SESSION['stc_agent_sup_dailyconsumpt_cart_sess'] as $getitems){
			$optimusprimegoitems=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_super_list_items_consumption_items`(
					`stc_cust_super_list_items_consumption_items_consumpid`,
					`stc_cust_super_list_items_consumption_items_name`, 
					`stc_cust_super_list_items_consumption_items_unit`, 
					`stc_cust_super_list_items_consumption_items_qty`
				) VALUES (
					'".$getorderid."',
					'".mysqli_real_escape_string($this->stc_dbs, $getitems['items_id'])."',
					'".mysqli_real_escape_string($this->stc_dbs, $getitems['items_unit'])."',
					'".mysqli_real_escape_string($this->stc_dbs, $getitems['items_quantity'])."'
				)
			");
		}
		$optimusprime="success";
		return $optimusprime;
	}

	// call consumption
	public function stc_call_item_consumption($begdate, $enddate){
		$optimusprime='			
			<table class="mb-0 table table-hover table-responsive">
				<thead>
					<th>Sl No</th>
					<th>Location/Sitename</th>
					<th>Sub Location</th>
					<th>Job Details</th>
					<th>Items Desc</th>
					<th>Qty</th>
					<th>Unit</th>
				</thead>
				<tbody>
		';
		$slno=0;
		$optimusprimeqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_cust_project_title`,
				`stc_cust_super_list_items_consumption_sub_location`,
				`stc_cust_super_list_items_consumption_job_details`,
				`stc_cust_super_requisition_list_items_title`,
				`stc_cust_super_list_items_consumption_items_unit`,
				`stc_cust_super_list_items_consumption_items_qty`
			FROM
				`stc_cust_super_list_items_consumption`
			INNER JOIN 
				`stc_cust_super_list_items_consumption_items` 
			ON 
				`stc_cust_super_list_items_consumption_items_consumpid`=`stc_cust_super_list_items_consumption_id` 
			INNER JOIN 
				`stc_cust_project` 
			ON 
				`stc_cust_project_id`=`stc_cust_super_list_items_consumption_site_id`
			INNER JOIN 
				`stc_cust_super_requisition_list_items` 
			ON 
				`stc_cust_super_requisition_list_id`=`stc_cust_super_list_items_consumption_items_name`
			WHERE (
				DATE(`stc_cust_super_list_items_consumption_date`) BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $begdate)."' 
				AND '".mysqli_real_escape_string($this->stc_dbs, $enddate)."'
			) AND 
				`stc_cust_super_list_items_consumption_super_id`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."'
		");
		if(mysqli_num_rows($optimusprimeqry)>0){
			foreach($optimusprimeqry as $optimusprimerow){
				$slno++;
				$optimusprime.='
					<tr>
						<td>'.$slno.'</td>
						<td>'.$optimusprimerow['stc_cust_project_title'].'</td>
						<td>'.$optimusprimerow['stc_cust_super_list_items_consumption_sub_location'].'</td>
						<td>'.$optimusprimerow['stc_cust_super_list_items_consumption_job_details'].'</td>
						<td>'.$optimusprimerow['stc_cust_super_requisition_list_items_title'].'</td>
						<td align="right">'.number_format($optimusprimerow['stc_cust_super_list_items_consumption_items_qty'], 2).'</td>
						<td>'.$optimusprimerow['stc_cust_super_list_items_consumption_items_unit'].'</td>
					</tr>
				';
			}
		}else{
			$optimusprime.='
					<tr>
						<td>No Record Found!!!</td>
					</tr>
			';
		}
		$optimusprime.='
				</tbody>
			</table>
		';
		return $optimusprime;
	}

	// call items for consumption
	public function stc_call_consumpt_item($item_id){
		$optimusprime='';
		$item_unit='';
		$item_qty=0;
		$item_desc='';
		$optimusprimeqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_list_items_title`,
				`stc_cust_super_requisition_list_items_unit` 
			FROM `stc_cust_super_requisition_list_items` 
			WHERE `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
		");

		foreach($optimusprimeqry as $optimusprimerow){
			$item_unit=$optimusprimerow['stc_cust_super_requisition_list_items_unit'];
			$item_desc=$optimusprimerow['stc_cust_super_requisition_list_items_title'];
		}

		$optimusprimerecqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty` 
			FROM `stc_cust_super_requisition_rec_items_fr_supervisor` 
			WHERE `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
		");

		foreach($optimusprimerecqry as $optimusprimerecrow){
			$item_qty+=$optimusprimerecrow['stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty'];
		}

		$optimusprime=array(
			'item_unit' => $item_unit,
			'item_desc' => $item_desc,
			'item_qty' => $item_qty
		);
		return $optimusprime;
	}
}

/*-----------------------------------------------------------------------------------*/
/*---------------------------------For Order items-----------------------------------*/
/*-----------------------------------------------------------------------------------*/
// call sitename
if(isset($_POST['call_location'])){
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_location();
	echo $opmetabots;
}

// call consumption
if(isset($_POST['call_consumption'])){
	$out='
		<table class="mb-0 table table-hover table-responsive">
			<thead>
				<th>Sl No</th>
				<th>Item Descriptions</th>
				<th>Item Quantity</th>
				<th>Unit</th>
				<th>Action</th>
			</thead>
			<tbody>
	';
	$slno=0;
	if(!empty($_SESSION["stc_agent_sup_dailyconsumpt_cart_sess"])){
		foreach($_SESSION["stc_agent_sup_dailyconsumpt_cart_sess"] as $listrow){
			$slno++;
			$out.='
				<tr>
					<td>'.$slno.'</td>
					<td>'.$listrow['items_title'].'</td>
					<td align="right">'.number_format($listrow['items_quantity'], 2).'</td>
					<td>'.$listrow['items_unit'].'</td>
					<td>
						<a href="#" class="btn btn-success removlistitems" id="'.$listrow['items_id'].'">
							<i class="fas fa-trash"></i>
						</a>
					</td>
				</tr>
			';
		}
		$out.='
			<tr>
				<td colspan="5">
					<a class="btn btn-success stc-save-consumption">Save</a>
				</td>
			</tr>
		';

	}else{
			$out.='
				<tr>
					<td colspan="5" align="center">Requisition List is Empty:(</td>
				</tr>
			';
	}
	$out.='
			</tbody>
		</table>
	';
	echo $out;
}

// call item using site
if(isset($_POST['stc_call_item_using_site'])){
	$site_id=$_POST['site_id'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_items($site_id);
	echo $opmetabots;
}

// add consumption to session
if(isset($_POST['stc-sup-hit'])){
	if(empty($_POST["stc-sup-desc"])){
		echo "Please Add Atleast One Item Before Saving!!!";	
	}elseif(empty($_POST["stc-sup-qty"])){
		echo "Please Add Quantity First!!!";	
	}elseif($_POST["stc-sup-qty"]>$_POST["stc-sup-rqty"]){
		echo "Please Input Quantity Less Than or Equal to Recieved Quantity!!!";	
	}else{
		if(isset($_SESSION["stc_agent_sup_dailyconsumpt_cart_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_agent_sup_dailyconsumpt_cart_sess"] as $keys => $values) {  
				if($_SESSION["stc_agent_sup_dailyconsumpt_cart_sess"][$keys]['items_id'] == $_POST["stc-sup-desc"]) {  
					$is_available++;  
					echo "Already Added!!!";
				}  
			}  
			if($is_available < 1) {  
				$item_array = array(  
				     'items_id'			=>     $_POST["stc-sup-desc"],  
				     'items_quantity'   =>     $_POST["stc-sup-qty"],  
				     'items_title'   	=>     $_POST["stc-sup-desc2"],
				     'items_unit'		=>     $_POST["stc-sup-unit"]
				);  
				$_SESSION["stc_agent_sup_dailyconsumpt_cart_sess"][] = $item_array;  
				echo "List Created & Item Added to List!!!";
			}  
		}else{  
			$item_array = array(  
				'items_id'			=>     $_POST["stc-sup-desc"],  
				'items_quantity'	=>     $_POST["stc-sup-qty"], 
				'items_title'		=>     $_POST["stc-sup-desc2"],
				'items_unit'		=>     $_POST["stc-sup-unit"]
			);   
			$_SESSION["stc_agent_sup_dailyconsumpt_cart_sess"][] = $item_array;  
			echo "Items Added to List!!!";
		}
	}  
}

// delete consumtpiton item
if(isset($_POST['delete_Dailylist'])){
	$prod_id=$_POST['del_item'];
	$item_desc='';
	foreach($_SESSION["stc_agent_sup_dailyconsumpt_cart_sess"] as $keys => $values){ 
		$item_desc=$values["items_id"]; 
		if($values["items_id"] == $prod_id){  
			unset($_SESSION["stc_agent_sup_dailyconsumpt_cart_sess"][$keys]);  
			echo "Item Removed!!!";  
		}  
	} 
}

// save daily consumption
if(isset($_POST['save_Dailyconsumption'])){
	$out='';
	$sup_site=$_POST['sup_site'];
	$sup_location=$_POST['sup_location'];
	$job_details=$_POST['job_details'];
	$bumblebee=new transformers();
	$megatron=new transformers();

	if(empty($sup_site) || empty($sup_location)){
		$out="Please Fill All Fields!!!";
	}elseif($sup_site=="NA"){
		$out="Please Select Your Site First!!!";
	}elseif(empty($_SESSION['stc_agent_sup_dailyconsumpt_cart_sess'])){
		$out="Please Order atleast One Product!!!";
	}else{
		$outbumblembee=$bumblebee->stc_super_consumption_add($sup_site, $sup_location, $job_details);
		if($outbumblembee=="success"){
			$outmegatron=$megatron->stc_super_consumption_items_add();
    			
			if($outmegatron=="success"){
			    $out.="Item Consumption Saved. Thank You!!!";
			    unset($_SESSION['stc_agent_sup_dailyconsumpt_cart_sess']);
			}else{
			    $out.="Items not inserted.";
			}
		}else{
			$out.="Something Went Not Good!!! Please Try Again Later.";
		}
		$out;
	}
	echo $out;
}

// call item consumption
if(isset($_POST['stc_call_consumption'])){
	$begdate=date('Y-m-d', strtotime($_POST['begdate']));
	$enddate=date('Y-m-d', strtotime($_POST['enddate']));
	$metabots=new transformers();
	$outmertabots=$metabots->stc_call_item_consumption($begdate, $enddate);
	echo $outmertabots;
}

// call items qnty
if(isset($_POST['stc_call_recievd_items'])){
	$item_id=$_POST['item_id'];
	$metabots=new transformers();
	$outmertabots=$metabots->stc_call_consumpt_item($item_id);
	echo json_encode($outmertabots);
	// echo $outmertabots;
}
?>