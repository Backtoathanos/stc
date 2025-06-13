<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents--------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class transformers extends tesseract{

	public function stc_load_cart_ag(){
		$sl=1;
		$order_table='
			<table class="mb-0 table table-hover">
    	    	<thead>
    	    	    <th style="width:10%;">Sl No.</th>
    	    	    <th style="width:50%;">Item Desc</th>
    	    	    <th>Unit</th>
    	    	    <th>Action</th>
    	    	</thead>
    	    	<tbody>
		';
		if(!empty($_SESSION["stc_agent_sup_cart_sess"])){  		
			foreach($_SESSION["stc_agent_sup_cart_sess"] as $cartpditem){
				$total = 0;  
				$totalgst = 0;
			
				$bumblebeequery=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_product`
					INNER JOIN `stc_category`
					ON `stc_product_cat_id`=`stc_cat_id`
					INNER JOIN `stc_sub_category`
					ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
					WHERE `stc_product_id`='".$cartpditem['product_id']."'
				");
				$bumblebeetakeit=mysqli_fetch_assoc($bumblebeequery);

				if($bumblebeetakeit['stc_sub_cat_name']=="OTHERS"){
					$subcat='';
				}else{
					$subcat=$bumblebeetakeit['stc_sub_cat_name'];
				}
				$order_table.='
					<tr>
						<td>'.$sl.'</td>
						<td>'.$subcat.' '.$bumblebeetakeit['stc_product_name'].'<br>
							<input 
								type="text" 
								style="float: left;width: 80%;background: #8787ff;border-radius: 15px;padding: 3px 15px;" 
								value="'.number_format($cartpditem['product_quantity'], 2).'" 
								id="itemqty'.$cartpditem['product_id'].'"
							>
							<a href="#" id="'.$cartpditem['product_id'].'" class="updateitem">
								<span style="font-size: 15px;background: yellow;border-radius: 11px;padding: 5px;">
									<i class="fas fa-arrow-right"></i>
								</span>
							</a>
						</td>
						<td>'.$bumblebeetakeit['stc_product_unit'].'</td>
						<td><a href="#" class="btn btn-success removitems" id="'.$cartpditem['product_id'].'"><i class="fas fa-trash"></i></a></td>
					</tr>
				';
				$total=+$cartpditem['product_quantity'];
				$sl++;
			}

			$order_table.='
				<tr>
					<td colspan=""></td>
					<td><b>Total</b></td>
					<td>: '.number_format($total, 2).'</td>
				</tr>
			';
		}else{
			$order_table.='
					<tr>
						<td colspan="4" align="center">Cart Empty!!!</td>
					</tr>
			';
		}
		$order_table.='
				</tbody>
			</table>
		';
		return $odin=$order_table;
	}

	public function stc_call_cust_for_agent(){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_cust_project_id`, 
				`stc_cust_project_title` 
			FROM 
				`stc_cust_pro_attend_supervise`
			INNER JOIN 
				`stc_cust_project` 
			ON 
				`stc_cust_project_id`=`stc_cust_pro_attend_supervise_pro_id` 
			WHERE 
				`stc_cust_pro_attend_supervise_super_id`='".$_SESSION['stc_agent_sub_id']."'
		");
		$optimusprime='';
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "<option value='NA' selected>No Site Found !!</option>";
		}elseif($do_action == 1){
			foreach ($optimusprimequery as $row) {
				$optimusprime.='
							<option value="'.$row["stc_cust_project_id"].'">'.$row["stc_cust_project_title"].'</option>		               	
		            	';				
			}
		}else{
			$optimusprime='<option value="NA" selected>Select Site</option>';
			foreach ($optimusprimequery as $row) {
				$optimusprime.='
							<option value="'.$row["stc_cust_project_id"].'">'.$row["stc_cust_project_title"].'</option>		               	
		            	';				
			}
			
		}
		return $optimusprime;
	}

	public function stc_agent_order_add($site_id){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_cust_super_requisition`(
				`stc_cust_super_requisition_date`, 
				`stc_cust_super_requisition_super_id`, 
				`stc_cust_super_requisition_project_id`, 
				`stc_cust_super_requisition_status`, 
				`stc_cust_super_requisition_approvedby`
			) VALUES (
				'".$date."',
				'".$_SESSION['stc_agent_sub_id']."',
				'".$site_id."',
				'1',
				'0'
			)
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	public function stc_agent_order_items_add(){
	    $optimusprime='';
		$optimusprimegetid=mysqli_query($this->stc_dbs, "
			SELECT MAX(`stc_cust_super_requisition_id`) AS orderid 
			FROM `stc_cust_super_requisition` 
			ORDER BY `stc_cust_super_requisition_id` DESC
		");
		$getorderid=mysqli_fetch_assoc($optimusprimegetid);
		foreach($_SESSION['stc_agent_sup_cart_sess'] as $getitems){
			$optimusprimegoitems=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_super_requisition_items`(
					`stc_cust_super_requisition_items_sup_req_id`, 
					`stc_cust_super_requisition_items_product_id`,  
					`stc_cust_super_requisition_items_product_qty`,
					`stc_cust_super_requisition_items_status`
				) VALUES (
					'".$getorderid['orderid']."',
					'".$getitems['product_id']."',
					'".$getitems['product_quantity']."',
					'1'
				)
			");
		}
		$optimusprime="success";
		return $optimusprime;
	}
}

class witcher_supervisor extends tesseract{
	public function stc_requisition_add($site, $desc, $qty, $unit, $type, $priority, $sdlno){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_cust_super_requisition_list`(
				`stc_cust_super_requisition_list_date`, 
				`stc_cust_super_requisition_list_sdlid`, 
				`stc_cust_super_requisition_list_super_id`, 
				`stc_cust_super_requisition_list_project_id`, 
				`stc_cust_super_requisition_list_status`, 
				`stc_cust_super_requisition_list_approved_by`
			) VALUES (
				'".$date."',
				'".$sdlno."',
				'".$_SESSION['stc_agent_sub_id']."',
				'".$site."',
				'1',
				'0'
			)
		");
		if($optimusprimequery){
			$last_id = mysqli_insert_id($this->stc_dbs);
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_super_requisition_list_items`(
					`stc_cust_super_requisition_list_items_req_id`, 
					`stc_cust_super_requisition_list_items_title`, 
					`stc_cust_super_requisition_list_items_unit`, 
					`stc_cust_super_requisition_list_items_reqqty`, 
					`stc_cust_super_requisition_list_items_approved_qty`, 
					`stc_cust_super_requisition_items_type`,
					`stc_cust_super_requisition_items_priority`,
					`stc_cust_super_requisition_list_items_status`
				) VALUES (
					'".$last_id."',
					'".mysqli_real_escape_string($this->stc_dbs, $desc)."',
					'".mysqli_real_escape_string($this->stc_dbs, $unit)."',
					'".mysqli_real_escape_string($this->stc_dbs, $qty)."',
					'0',
					'".mysqli_real_escape_string($this->stc_dbs, $type)."',
					'".mysqli_real_escape_string($this->stc_dbs, $priority)."',
					'1'
				)
			");
			$last_itemid = mysqli_insert_id($this->stc_dbs);
			$title="Ordered";
			$message="Ordered by ".$_SESSION['stc_agent_sub_name']." on ".date('d-m-Y h:i A');
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_super_requisition_list_items_log`(
					`item_id`, 
					`title`, 
					`message`, 
					`status`, 
					`created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $last_itemid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $title)."',
					'".mysqli_real_escape_string($this->stc_dbs, $message)."',
					'1',
					'".$_SESSION['stc_agent_sub_id']."'
				)
			");
			
			if($optimusprimequery){
				$optimusprime="success";
			}else{
				$optimusprime="not success";
			}
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	public function stc_super_list_add($sup_site, $sdlno){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		$priority=0;
		foreach($_SESSION['stc_agent_sup_dailylist_cart_sess'] as $getitems){
			if($getitems['priority']==2){
				$priority=$getitems['priority'];
				break;
			}
		}
		
		$status=$priority==2 ? 2 : 1;
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_cust_super_requisition_list`(
				`stc_cust_super_requisition_list_date`, 
				`stc_cust_super_requisition_list_sdlid`, 
				`stc_cust_super_requisition_list_super_id`, 
				`stc_cust_super_requisition_list_project_id`, 
				`stc_cust_super_requisition_list_status`, 
				`stc_cust_super_requisition_list_approved_by`
			) VALUES (
				'".$date."',
				'".$sdlno."',
				'".$_SESSION['stc_agent_sub_id']."',
				'".$sup_site."',
				'".$status."',
				'0'
			)
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	public function stc_super_list_items_add(){
		$optimusprime='';
		$optimusprimegetid=mysqli_query($this->stc_dbs, "
			SELECT MAX(`stc_cust_super_requisition_list_id`) AS orderid 
			FROM `stc_cust_super_requisition_list` 
			ORDER BY `stc_cust_super_requisition_list_id` DESC
		");
		$getorderid=mysqli_fetch_assoc($optimusprimegetid);
		foreach($_SESSION['stc_agent_sup_dailylist_cart_sess'] as $getitems){
			$optimusprimegoitems=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_super_requisition_list_items`(
					`stc_cust_super_requisition_list_items_req_id`,
					`stc_cust_super_requisition_list_items_title`, 
					`stc_cust_super_requisition_list_items_unit`, 
					`stc_cust_super_requisition_list_items_reqqty`, 
					`stc_cust_super_requisition_list_items_approved_qty`, 
					`stc_cust_super_requisition_items_type`,
					`stc_cust_super_requisition_items_priority`,
					`stc_cust_super_requisition_list_items_status`
				) VALUES (
					'".$getorderid['orderid']."',
					'".mysqli_real_escape_string($this->stc_dbs, $getitems['items_title'])."',
					'".mysqli_real_escape_string($this->stc_dbs, $getitems['items_unit'])."',
					'".mysqli_real_escape_string($this->stc_dbs, $getitems['items_quantity'])."',
					'".mysqli_real_escape_string($this->stc_dbs, $getitems['items_quantity'])."',
					'".mysqli_real_escape_string($this->stc_dbs, $getitems['items_type'])."',
					'".mysqli_real_escape_string($this->stc_dbs, $getitems['priority'])."',
					'1'
				)
			");
		}
		$optimusprime="success";
		return $optimusprime;
	}

	public function stc_search_requisitions($supreqfromdate, $supreqtodate){ 
		$requisition_table='
			<table class="mb-0 table table-hover table-bordered">
				<thead>
				    <th class="text-center">Req No.</th>
				    <th class="text-center">Req Date.</th>
				    <th class="text-center">Req Site Name.</th>
				    <th class="text-center">Material Desc</th>
				    <th class="text-center">Unit</th>
				    <th class="text-center">Order Qty</th>
				    <th class="text-center">Approved Qty</th>
				    <th class="text-center">Dispatched Qty</th>
				    <th class="text-center">Recieved Qty</th>
				    <th class="text-center">Balance Qty</th>
				    <th class="text-center">Consumed Qty</th>
				    <th class="text-center">Stock Qty</th>
				    <th class="text-center">Status</th>
				    <th class="text-center">Priority</th>
				    <th class="text-center">Type</th>
				    <th class="text-center">Action</th>
				    <th class="text-center">Log Book</th>
				</thead>
				<tbody>
		';
		$requisitioni_qry=mysqli_query($this->stc_dbs, "
			SELECT
				L.`stc_cust_super_requisition_list_id` as list_id,
				L.`stc_cust_super_requisition_list_date`,
				`stc_cust_project_title`,
				`stc_cust_super_requisition_list_items_title`,
				`stc_cust_super_requisition_list_items_unit`,
				`stc_cust_super_requisition_list_items_reqqty`,
				`stc_cust_super_requisition_list_items_approved_qty`,
				`stc_cust_super_requisition_items_finalqty`,
				`stc_cust_super_requisition_items_type`,
				`stc_cust_super_requisition_items_priority`,
				`stc_cust_super_requisition_list_items_status`,
				`stc_cust_super_requisition_items_priority`,
				`stc_cust_super_requisition_items_type`,
				I.`stc_cust_super_requisition_list_id` as list_item_id
			FROM `stc_cust_super_requisition_list_items` I
			LEFT JOIN `stc_cust_super_requisition_list` L
			ON L.`stc_cust_super_requisition_list_id`= I.`stc_cust_super_requisition_list_items_req_id`
			LEFT JOIN `stc_cust_project` P
			ON P.`stc_cust_project_id`= L.`stc_cust_super_requisition_list_project_id`
			WHERE (
				DATE(`stc_cust_super_requisition_list_date`) BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $supreqfromdate)."'
				AND '".mysqli_real_escape_string($this->stc_dbs, $supreqtodate)."'
			) AND `stc_cust_super_requisition_list_super_id`='".$_SESSION['stc_agent_sub_id']."'
			ORDER BY `stc_cust_super_requisition_items_priority` DESC, TIMESTAMP(`stc_cust_super_requisition_list_date`) DESC
		");
		if(mysqli_num_rows($requisitioni_qry)>0){
			foreach($requisitioni_qry as $requisitioni_row){
				$reqstaus='';
				if($requisitioni_row['stc_cust_super_requisition_list_items_status']==1){
					$reqstaus='Ordered';
				}elseif($requisitioni_row['stc_cust_super_requisition_list_items_status']==2){
					$reqstaus='Approved';
				}elseif($requisitioni_row['stc_cust_super_requisition_list_items_status']==3){
					$reqstaus='Accepted';
				}elseif($requisitioni_row['stc_cust_super_requisition_list_items_status']==4){
					$reqstaus='Dispatched';
				}elseif($requisitioni_row['stc_cust_super_requisition_list_items_status']==5){
					$reqstaus='Received';
				}elseif($requisitioni_row['stc_cust_super_requisition_list_items_status']==6){
					$reqstaus='Rejected';
				}elseif($requisitioni_row['stc_cust_super_requisition_list_items_status']==7){
					$reqstaus='Canceled';
				}elseif($requisitioni_row['stc_cust_super_requisition_list_items_status']==8){
					$reqstaus='Returned';
				}else{
					$reqstaus='Closed';
				}

				$priority = $requisitioni_row['stc_cust_super_requisition_items_priority']==2 ? "Urgent" : "Normal";
				$getdispatchedtransformers=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_cust_super_requisition_list_items_rec_recqty`) AS dispatched_qty
					FROM `stc_cust_super_requisition_list_items_rec` 
					WHERE `stc_cust_super_requisition_list_items_rec_list_item_id`='".$requisitioni_row['list_item_id']."'
				");
				$recievingqty=0;
				$sumbalanceqty=0;
				$dispatchedgqty=0;
				foreach($getdispatchedtransformers as $decqtyrow){
					$dispatchedgqty+=$decqtyrow['dispatched_qty'];
				}

				$getrecivedtransformers=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty`) AS received_qty
					FROM `stc_cust_super_requisition_rec_items_fr_supervisor` 
					WHERE `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`='".$requisitioni_row['list_item_id']."'
				");
				$recievingqty=0;
				$sumbalanceqty=0;
				foreach($getrecivedtransformers as $recqtyrow){
					$recievingqty+=$recqtyrow['received_qty'];
				}

				$getconsrecivedtransformers=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_cust_super_list_items_consumption_items_qty`) AS consumable_qty
					FROM `stc_cust_super_list_items_consumption_items` 
					WHERE `stc_cust_super_list_items_consumption_items_name`='".$requisitioni_row['list_item_id']."'
				");
				$consrecievingqty=0;
				foreach($getconsrecivedtransformers as $consrecqtyrow){
					$consrecievingqty+=$consrecqtyrow['consumable_qty'];
				}

				$sumbalanceqty=$requisitioni_row['stc_cust_super_requisition_items_finalqty'] - $recievingqty;
				$action='';
				if(
					(
						number_format($requisitioni_row['stc_cust_super_requisition_items_finalqty'], 2)>0
					) 
					&& 
					(
						number_format($dispatchedgqty, 2)>0
					)
					&& 
					(
						number_format($sumbalanceqty, 2)!=0
					)
				){
					$action='
						<a 
							href="#" 
							stc-req-item-id="'.$requisitioni_row['list_item_id'].'"
							stc-req-item-checkqty="'.$requisitioni_row['stc_cust_super_requisition_items_finalqty'].'"
							stc-req-id="'.$requisitioni_row['list_id'].'"
							class="btn btn-info btn-sm stc-sup-requisition-rece-modal-btn">	 
							Recieving
						</a>
					';
				}
				if($requisitioni_row['stc_cust_super_requisition_list_items_status']==1 || $requisitioni_row['stc_cust_super_requisition_list_items_status']==6){
					$action.='
							<a href="javascript:void(0)" data-toggle="modal" data-target="#stc-sup-requisition-item-edit-modal" class="btn btn-success edit-req-item" id="'.$requisitioni_row['list_item_id'].'" style="font-size: 15px;color: black;"><i class="fas fa-edit"></i></a>
					';
				}
				if($requisitioni_row['stc_cust_super_requisition_items_finalqty']==0){
					$action.='
						<a href="#" class="btn btn-danger remove_from_purchase" list_id="'.$requisitioni_row['list_id'].'" item_id="'.$requisitioni_row['list_item_id'].'" style="font-size: 15px;color: black;"><i class="fas fa-trash"></i></a>
					';
				}
				$log='
					<a 
							href="#" 
							data-toggle="modal"
							data-target=".bd-log-modal-lg"
							class="btn btn-info btn-sm stc-sup-requisition-viewlog-modal-btn">	 
							View Log
						</a>
				';
				$item_id=$requisitioni_row['list_item_id'];
				$query=mysqli_query($this->stc_dbs, "
					SELECT 
						`title`, 
						`message`, 
						`created_by`, 
						`created_date`
					FROM `stc_cust_super_requisition_list_items_log` 
					WHERE `item_id`='".$item_id."'
					ORDER BY `id` DESC
				");
				if(mysqli_num_rows($query)>0){
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
				$bgcolor=$requisitioni_row['stc_cust_super_requisition_items_priority']=="2" ? 'style="background:#ffa5a5;color:black"' : "";
				$stockqty=$recievingqty - $consrecievingqty;
				$requisition_table.='
					<tr '.$bgcolor.'>
						<td class="text-center">'.$requisitioni_row['list_id'].'</td>
						<td class="text-center">'.date('d-m-Y', strtotime($requisitioni_row['stc_cust_super_requisition_list_date'])).'</td>
						<td>'.$requisitioni_row['stc_cust_project_title'].'</td>
						<td>'.$requisitioni_row['stc_cust_super_requisition_list_items_title'].'</td>
						<td>'.$requisitioni_row['stc_cust_super_requisition_list_items_unit'].'</td>
						<td class="text-right">'.number_format($requisitioni_row['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
						<td class="text-right">'.number_format($requisitioni_row['stc_cust_super_requisition_items_finalqty'], 2).'</td>
						<td class="text-right">'.number_format($dispatchedgqty, 2).'</td>
						<td class="text-right">'.number_format($recievingqty, 2).'</td>
						<td class="text-right">'.number_format($sumbalanceqty, 2).'</td>
						<td class="text-right">'.number_format($consrecievingqty, 2).'</td>
						<td class="text-right">'.number_format($stockqty, 2).'</td>
						<td>'.$reqstaus.'</td>
						<td>'.$priority.'</td>
						<td>'.$requisitioni_row['stc_cust_super_requisition_items_type'].'</td>
						<td class="text-center">'.$action.'</td>
						<td class="text-center">'.$log.'</td>
					</tr>
				';
			}
		}else{
			$requisition_table.='
				<tr>
					<td colspan="8">No record found.</td>
				</tr>
			';
		}
		$requisition_table.='
				</tbody>
			</table>
		';
		return $requisition_table;
	}

	public function stc_ag_requisition_items_call($odid){
		$sl=0;
		$subcat='';
		$countqty=0;
		$transformers.='
			<table class="mb-0 table table-hover table-bordered">
				<thead>
				    <th>Sl No</th>
				    <th width="45%">Material Desc</th>
				    <th>Unit</th>
				    <th>Order Qty</th>
				    <th>Approved Qty</th>
				    <th>Dispatched Qty</th>
				    <th>Recieved Qty</th>
				    <th>Balance Qty</th>
				    <th>Consumed Qty</th>
				    <th>Status</th>
				    <th>Priority</th>
				    <th>Add Recieving</th>
				</thead>
				<tbody>
		';
		$transformersquery=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_list_id`,
				`stc_cust_super_requisition_list_items_title`,
				`stc_cust_super_requisition_list_items_unit`,
				`stc_cust_super_requisition_list_items_reqqty`,
				`stc_cust_super_requisition_items_finalqty`,
				`stc_cust_super_requisition_list_items_status`,
				`stc_cust_super_requisition_items_priority`
			FROM `stc_cust_super_requisition_list_items` 
			WHERE `stc_cust_super_requisition_list_items_req_id`='".$odid."'
		");
		if(mysqli_num_rows($transformersquery)>0){
			foreach($transformersquery as $firstrow){
				$sl++;
				$reqstaus='';
				if($firstrow['stc_cust_super_requisition_list_items_status']==1){
					$reqstaus='ALLOW';
				}elseif($firstrow['stc_cust_super_requisition_list_items_status']==2){
					$reqstaus='ALLOW';
				}else{
					$reqstaus='NOT ALLOW';
				}

				$priority = $firstrow['stc_cust_super_requisition_items_priority']==2 ? "Urgent" : "Normal";
				$getdispatchedtransformers=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_cust_super_requisition_list_items_rec_recqty`) AS dispatched_qty
					FROM `stc_cust_super_requisition_list_items_rec` 
					WHERE `stc_cust_super_requisition_list_items_rec_list_item_id`='".$firstrow['stc_cust_super_requisition_list_id']."'
				");
				$recievingqty=0;
				$sumbalanceqty=0;
				$dispatchedgqty=0;
				foreach($getdispatchedtransformers as $decqtyrow){
					$dispatchedgqty+=$decqtyrow['dispatched_qty'];
				}

				$getrecivedtransformers=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty`) AS received_qty
					FROM `stc_cust_super_requisition_rec_items_fr_supervisor` 
					WHERE `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`='".$firstrow['stc_cust_super_requisition_list_id']."'
				");
				$recievingqty=0;
				$sumbalanceqty=0;
				foreach($getrecivedtransformers as $recqtyrow){
					$recievingqty+=$recqtyrow['received_qty'];
				}

				$getconsrecivedtransformers=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_cust_super_list_items_consumption_items_qty`) AS consumable_qty
					FROM `stc_cust_super_list_items_consumption_items` 
					WHERE `stc_cust_super_list_items_consumption_items_name`='".$firstrow['stc_cust_super_requisition_list_id']."'
				");
				$consrecievingqty=0;
				foreach($getconsrecivedtransformers as $consrecqtyrow){
					$consrecievingqty+=$consrecqtyrow['consumable_qty'];
				}

				$sumbalanceqty=$firstrow['stc_cust_super_requisition_items_finalqty'] - $recievingqty;
				if(
					(
						number_format($firstrow['stc_cust_super_requisition_items_finalqty'], 2)>0
					) 
					&& 
					(
						number_format($dispatchedgqty, 2)>0
					)
					&& 
					(
						number_format($sumbalanceqty, 2)!=0
					)
				){
					$transformers.='					
						<tr>
							<td>'.$sl.'</td>
							<td>'.$firstrow['stc_cust_super_requisition_list_items_title'].'</td>
							<td>'.$firstrow['stc_cust_super_requisition_list_items_unit'].'</td>
							<td>'.number_format($firstrow['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
							<td>'.number_format($firstrow['stc_cust_super_requisition_items_finalqty'], 2).'</td>
							<td>'.number_format($dispatchedgqty, 2).'</td>
							<td>'.number_format($recievingqty, 2).'</td>
							<td>'.number_format($sumbalanceqty, 2).'</td>
							<td>'.number_format($recievingqty, 2).'</td>
							<td>'.$reqstaus.'</td>
							<td>'.$priority.'</td>
							<td>
								<a 
									href="#" 
									stc-req-item-id="'.$firstrow['stc_cust_super_requisition_list_id'].'"
									stc-req-item-checkqty="'.$firstrow['stc_cust_super_requisition_items_finalqty'].'"
									stc-req-id="'.$odid.'"
									class="btn btn-info btn-sm stc-sup-requisition-rece-modal-btn">	 
									Recieving
								</a>
							</td>
						</tr>
					';
				}else{
					$transformers.='
						<tr>
							<td>'.$sl.'</td>
							<td>'.$firstrow['stc_cust_super_requisition_list_items_title'].'</td>
							<td>'.$firstrow['stc_cust_super_requisition_list_items_unit'].'</td>
							<td>'.number_format($firstrow['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
							<td>'.number_format($firstrow['stc_cust_super_requisition_items_finalqty'], 2).'</td>
							<td>'.number_format($dispatchedgqty, 2).'</td>
							<td>'.number_format($recievingqty, 2).'</td>
							<td>'.number_format($sumbalanceqty, 2).'</td>
							<td>'.number_format($recievingqty, 2).'</td>
							<td>'.$reqstaus.'</td>
							<td>'.$priority.'</td>
							<td>#</td>
						</tr>
					';
				}
			}
		}else{
			$transformers.='
					<tr>
						<td colspan="7"><h4>No Items Found!!!</h6></td>
					</tr>
			';
		}
		$transformers.='
				</tbody>
			</table>
		';
		return $transformers;
	}

	public function stc_supervisor_list_rec_items_hit($stc_req_id, $stc_req_item_id, $stc_rec_qty, $stc_req_item_cqty){
		$returnrecieve='';
		$stcinitvalue=0;
		$stcsuporderqty=0;
		$stcsupdisqty=0;
		$stcfinalvalue=0;
		$date=date("Y-m-d H:i:s");
		$stcceckqntyqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty`
			FROM `stc_cust_super_requisition_rec_items_fr_supervisor`
			WHERE `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`='".mysqli_real_escape_string($this->stc_dbs, $stc_req_item_id)."'
		");
		foreach($stcceckqntyqry as $qry){
			$stcinitvalue+=$qry['stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty'];
		}
		$totalqnty=$stcinitvalue + $stc_rec_qty;
		if($totalqnty>$stc_req_item_cqty){
			$returnrecieve="Item is already recieved.";
		}else{
			$stccheckrecqry=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_cust_super_requisition_items_finalqty`,
				    `stc_cust_super_requisition_list_items_rec_recqty`
				FROM `stc_cust_super_requisition_list_items`
				INNER JOIN `stc_cust_super_requisition_list_items_rec` 
				ON `stc_cust_super_requisition_list_items_rec_list_item_id`=`stc_cust_super_requisition_list_id` 
				WHERE `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_req_item_id)."' 
				AND `stc_cust_super_requisition_list_items_req_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_req_id)."'
			");
			foreach($stccheckrecqry as $stccheckrow){
				$stcsuporderqty+=$stccheckrow['stc_cust_super_requisition_items_finalqty'];
				$stcsupdisqty+=$stccheckrow['stc_cust_super_requisition_list_items_rec_recqty'];
			}
			$stcfinalvalue=$stcsuporderqty - $stcsupdisqty;
			if($stc_rec_qty>$stcsupdisqty){
				$returnrecieve="Invalid Quantity!!!";
			}else{
				$stcinsertqry=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_cust_super_requisition_rec_items_fr_supervisor`(
						`stc_cust_super_requisition_rec_items_fr_supervisor_date`, 
						`stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`, 
						`stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty`
					) VALUES (
						'".mysqli_real_escape_string($this->stc_dbs, $date)."',
						'".mysqli_real_escape_string($this->stc_dbs, $stc_req_item_id)."',
						'".mysqli_real_escape_string($this->stc_dbs, $stc_rec_qty)."'
					)
				");
				if($stcinsertqry){
					
					$stcceckqntyqry=mysqli_query($this->stc_dbs, "
						UPDATE `stc_cust_super_requisition_list_items` SET `stc_cust_super_requisition_list_items_status`='5' WHERE `stc_cust_super_requisition_list_id`='$stc_req_item_id'
					");
					$title="Recieved";
					$message="Recieved by ".$_SESSION['stc_agent_sub_name']." on ".date('d-m-Y h:i A')."<br/> Recieving Qty: ".$stc_rec_qty."<br/> Final Qty: ".$stcsuporderqty;
					$optimusprimequery=mysqli_query($this->stc_dbs, "
						INSERT INTO `stc_cust_super_requisition_list_items_log`(
							`item_id`, 
							`title`, 
							`message`, 
							`status`, 
							`created_by`
						) VALUES (
							'".mysqli_real_escape_string($this->stc_dbs, $stc_req_item_id)."',
							'".mysqli_real_escape_string($this->stc_dbs, $title)."',
							'".mysqli_real_escape_string($this->stc_dbs, $message)."',
							'1',
							'".$_SESSION['stc_agent_sub_id']."'
						)
					");
					$returnrecieve="yes";
				}else{
					$stcinsertqry="Hmmm!!! Something went wrong. Please check quantity & try again.";
				}
			}
		}
		return $returnrecieve;
	}

	public function stc_remove_requisition($list_id, $item_id){
		$blackpearl='';
		$deleteqry=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_cust_super_requisition_list_items` WHERE `stc_cust_super_requisition_list_id`='".$item_id."'
		");
		$itemsqry=mysqli_query($this->stc_dbs, "
			SELECT `stc_cust_super_requisition_list_id` FROM `stc_cust_super_requisition_list_items` WHERE `stc_cust_super_requisition_list_items_req_id`='".$list_id."'
		");
		if(mysqli_num_rows($itemsqry)==0){
			$deleteqry=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_cust_super_requisition_list` WHERE `stc_cust_super_requisition_list_id`='".$list_id."'
			");
			if($deleteqry){
				$blackpearl="yes";
			}else{
				$blackpearl="no";
			}
		}
		return $blackpearl;
	}

	// req item change
	public function stc_change_req_item_call($req_id){
		$odin = array();
		$odin_getitemqry = mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_list_items_title`,
				`stc_cust_super_requisition_list_items_unit`,
				`stc_cust_super_requisition_items_type`
			FROM `stc_cust_super_requisition_list_items` 
			WHERE `stc_cust_super_requisition_list_id` = '".mysqli_real_escape_string($this->stc_dbs, $req_id)."'
		");

		while($odin_getitemrow = mysqli_fetch_assoc($odin_getitemqry)) {
			// Option 1: Add as associative array item (title + unit in one row)
			$odin[] = [
				'title' => $odin_getitemrow['stc_cust_super_requisition_list_items_title'],
				'unit'  => $odin_getitemrow['stc_cust_super_requisition_list_items_unit'],
				'type'  => $odin_getitemrow['stc_cust_super_requisition_items_type']
			];
		}
		return $odin;

	}

	// update rewq line items
	public function stc_change_req_item_update($req_item_id, $req_item_name, $req_item_unit, $req_item_type){
		$odin='';
		$odin_requpdateqry=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_cust_super_requisition_list_items`
			SET
			    `stc_cust_super_requisition_list_items_title` = '".mysqli_real_escape_string($this->stc_dbs, $req_item_name)."',
			    `stc_cust_super_requisition_list_items_unit` = '".mysqli_real_escape_string($this->stc_dbs, $req_item_unit)."',
			    `stc_cust_super_requisition_items_type` = '".mysqli_real_escape_string($this->stc_dbs, $req_item_type)."',
			    `stc_cust_super_requisition_list_items_status` = '1'
			WHERE
			    `stc_cust_super_requisition_list_id` = '".mysqli_real_escape_string($this->stc_dbs, $req_item_id)."'
		");
		$query=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_list_items_status` 
			FROM `stc_cust_super_requisition_list_items` 
			WHERE `stc_cust_super_requisition_list_id` = '".mysqli_real_escape_string($this->stc_dbs, $req_item_id)."' AND `stc_cust_super_requisition_list_items_status` = '5'");
		
		if(mysqli_num_rows($query)>0){
			$title="Reordered";
			$message="Reordered by ".$_SESSION['stc_agent_sub_name']." on ".date('d-m-Y h:i A');
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_super_requisition_list_items_log`(
					`item_id`, 
					`title`, 
					`message`, 
					`status`, 
					`created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $req_item_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $title)."',
					'".mysqli_real_escape_string($this->stc_dbs, $message)."',
					'1',
					'".$_SESSION['stc_agent_sub_id']."'
				)
			");
		}
		if($odin_requpdateqry){
			$odin='Item Updated Successfully.';
		}else{
			$odin='Hmmm!!! Something went wrong, Item not updated successfully.';
		}
		return $odin;
	}

	public function stc_search_item($search){
		$odin=[];
		$query=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_cust_super_requisition_list_items_title` FROM `stc_cust_super_requisition_list_items` WHERE `stc_cust_super_requisition_list_items_title`<>'' AND `stc_cust_super_requisition_list_items_title` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' ORDER BY `stc_cust_super_requisition_list_items_title` ASC
		");
		if(mysqli_num_rows($query)>0){
			while($row=mysqli_fetch_assoc($query)){
				$odin[] = $row['stc_cust_super_requisition_list_items_title'];
			}
		}
		return $odin;
	}
}

/*-----------------------------------------------------------------------------------*/
/*---------------------------------For Order items-----------------------------------*/
/*-----------------------------------------------------------------------------------*/

if(isset($_POST['prodsuccess'])){
	if($_POST["prod_qty"]==0){
		echo "Please Add Quantity First!!!";		
	}else{
		if(isset($_SESSION["stc_agent_sup_cart_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_agent_sup_cart_sess"] as $keys => $values) {  
				if($_SESSION["stc_agent_sup_cart_sess"][$keys]['product_id'] == $_POST["prod_id"]) {  
				     $is_available++;  
				     $_SESSION["stc_agent_sup_cart_sess"][$keys]['product_quantity'] = $_SESSION["stc_agent_sup_cart_sess"][$keys]['product_quantity'] + $_POST["prod_qty"];  
				     echo "Cart Item Quantity Increased!!!";
				}  
			}  
			if($is_available < 1) {  
				$item_array = array(  
				     'product_id'             =>     $_POST["prod_id"],  
				     'product_quantity'       =>     $_POST["prod_qty"],
				     'product_price'       =>     $_POST["prod_price"]
				);  
				$_SESSION["stc_agent_sup_cart_sess"][] = $item_array;  
				echo "Created & Item Added to Cart!!!";
			}  
		}else{  
			$item_array = array(  
				     'product_id'             =>     $_POST["prod_id"],  
				     'product_quantity'       =>     $_POST["prod_qty"],
				     'product_price'       =>     $_POST["prod_price"]
			);   
			$_SESSION["stc_agent_sup_cart_sess"][] = $item_array;  
			echo "Item Added to Cart!!!";
		}
	}  
}

if(isset($_POST['load_cart_ag'])){
	$objlogin=new transformers();
	$opobjlogin=$objlogin->stc_load_cart_ag();
	$out = $opobjlogin;
	echo $out;
}

if(isset($_POST['delitems'])){
	$prod_id=$_POST['items_id'];
	foreach($_SESSION["stc_agent_sup_cart_sess"] as $keys => $values){  
		if($values["product_id"] == $prod_id){  
			unset($_SESSION["stc_agent_sup_cart_sess"][$keys]);  
			echo "Item Removed!!!";  
		}  
	} 
}

if(isset($_POST["updateitems"])){
  	foreach($_SESSION["stc_agent_sup_cart_sess"] as $keys => $values){
	    if($_SESSION["stc_agent_sup_cart_sess"][$keys]['product_id'] == $_POST["items_id"]){
	         $_SESSION["stc_agent_sup_cart_sess"][$keys]['product_quantity'] = $_POST["pd_qty"];
    	}
  	}
  	echo "Quanty Updated!!!";
}

if(isset($_POST['load_cust'])){
	$bumblebee=new transformers();
	$outbumblebee=$bumblebee->stc_call_cust_for_agent();
	echo $outbumblebee;
}

if(isset($_POST['order_add'])){
	$out='';
	$site_id=$_POST['site_id'];

	$bumblebee=new transformers();
	$megatron=new transformers();

	if(empty($site_id)){
		$out="Please Fill All Fields!!!";
	}elseif($site_id=="NA"){
		$out="Please Select Customer First!!!";
	}elseif(empty($_SESSION['stc_agent_sup_cart_sess'])){
		$out="Please Buy Something!!!";
	}else{
		$outbumblembee=$bumblebee->stc_agent_order_add($site_id);
		if($outbumblembee=="success"){
			$outmegatron=$megatron->stc_agent_order_items_add();
    			
			if($outmegatron=="success"){
			    $out.="Thank You!!!Your Order Will Be Placed ASAP.";
			    unset($_SESSION['stc_agent_sup_cart_sess']);
    			// mail("stc111213@gmail.com", "Order Alert", "Some order from ".$_SESSION['stc_agent_sub_name']."!!!:)", "info@stcassociate.com");
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

/*-----------------------------------------------------------------------------------*/
/*---------------------------------For list items------------------------------------*/
/*-----------------------------------------------------------------------------------*/

if(isset($_POST['stc-sup-hit'])){
	$out='';
	$site=$_POST['load_cust_sup_site'];
	$desc=$_POST['stc-sup-desc'];
	$qty=$_POST['stc-sup-qty'];
	$unit=$_POST['stc-sup-unit'];
	$type=$_POST['stc-sup-type'];
	$priority=$_POST['stc-sup-priority'];
	$sdlno=$_POST['stc-sup-sdlnumber'];

	$bumblebee=new witcher_supervisor();
	$megatron=new witcher_supervisor();

	if(empty($_SESSION['stc_agent_sub_id'])){
		$out="logout";
	}else{
		$out=$bumblebee->stc_requisition_add($site, $desc, $qty, $unit, $type, $priority, $sdlno);
		if($out=="success"){
		    $out="Thank You!!!Your Order Will Be Placed ASAP.";
		}else{
		    $out="Something went wrong Please check & try again.";
		}
	}
	echo $out;
}
// if(isset($_POST['stc-sup-hit'])){
	// if(empty($_POST["stc-sup-desc"])){
	// 	echo "Please Add Atleast One Item Before Saving!!!";	
	// }elseif(empty($_POST["stc-sup-qty"])){
	// 	echo "Please Add Quantity First!!!";	
	// }else{
	// 	if(isset($_SESSION["stc_agent_sup_dailylist_cart_sess"])) {  
	// 		$is_available = 0;  
	// 		foreach($_SESSION["stc_agent_sup_dailylist_cart_sess"] as $keys => $values) {  
	// 			if($_SESSION["stc_agent_sup_dailylist_cart_sess"][$keys]['items_title'] == $_POST["stc-sup-desc"]) {  
	// 			     $is_available++;  
	// 			     $_SESSION["stc_agent_sup_dailylist_cart_sess"][$keys]['stc-sup-qty'] = $_SESSION["stc_agent_sup_dailylist_cart_sess"][$keys]['items_quantity'] + $_POST["stc-sup-qty"];  
	// 			     echo "List Item Quantity Increased!!!";
	// 			}  
	// 		}  
	// 		if($is_available < 1) {  
	// 			$item_array = array(  
	// 			     'items_title'				=>     $_POST["stc-sup-desc"],  
	// 			     'items_quantity'       	=>     $_POST["stc-sup-qty"],
	// 			     'items_unit'				=>     $_POST["stc-sup-unit"],
	// 			     'priority'					=>     $_POST["stc-sup-priority"],
	// 			     'items_type'				=>     $_POST["stc-sup-type"]
	// 			);  
	// 			$_SESSION["stc_agent_sup_dailylist_cart_sess"][] = $item_array;  
	// 			echo "List Created & Item Added to List!!!";
	// 		}  
	// 	}else{  
	// 		$item_array = array(  
	// 			     'items_title'				=>     $_POST["stc-sup-desc"],  
	// 			     'items_quantity'       	=>     $_POST["stc-sup-qty"],
	// 			     'items_unit'				=>     $_POST["stc-sup-unit"],
	// 			     'priority'					=>     $_POST["stc-sup-priority"],
	// 			     'items_type'				=>     $_POST["stc-sup-type"]
	// 		);   
	// 		$_SESSION["stc_agent_sup_dailylist_cart_sess"][] = $item_array;  
	// 		echo "Items Added to List!!!";
	// 	}
	// }  
// }

if(isset($_POST['show_Dailylist'])){
	$out='
		<table class="mb-0 table table-hover">
			<thead>
				<th>Sl No</th>
				<th>Item Description</th>
				<th>Item Qty</th>
				<th>Item Unit</th>
				<th>Item Type</th>
				<th>Priority</th>
				<th>Action</th>
			</thead>
			<tbody>
	';
	$slno=0;
	if(!empty($_SESSION["stc_agent_sup_dailylist_cart_sess"])){
		foreach($_SESSION["stc_agent_sup_dailylist_cart_sess"] as $key=>$listrow){
			$slno++;
			$priority = $listrow['priority'] == 2 ? "Urgent" : "Normal";
			$out.='
				<tr>
					<td>'.$slno.'</td>
					<td>'.$listrow['items_title'].'</td>
					<td>'.number_format($listrow['items_quantity'], 2).'</td>
					<td>'.$listrow['items_unit'].'</td>
					<td>'.$listrow['items_type'].'</td>
					<td>'.$priority.'</td>
					<td>
						<a href="#" class="btn btn-success removlistitems" id="'.$key.'">
							<i class="fas fa-trash"></i>
						</a>
					</td>
				</tr>
			';
		}
		$out.='
			<tr>
				<td colspan="7">
					<a class="btn btn-success stc-save-requisition">Save</a>
				</td>
			</tr>
		';

	}else{
			$out.='
				<tr>
					<td colspan="5" align="center">Requisition list is empty:(</td>
				</tr>
			';
	}
	$out.='
			</tbody>
		</table>
	';
	echo $out;
}

if(isset($_POST['delete_Dailylist'])){
	$prod_id=$_POST['del_item'];
	foreach($_SESSION["stc_agent_sup_dailylist_cart_sess"] as $keys => $values){  
		if($keys == $prod_id){  
			unset($_SESSION["stc_agent_sup_dailylist_cart_sess"][$keys]);  
			echo "Item Removed!!!";  
		}  
	} 
}

if(isset($_POST['save_Dailylist'])){
	$out='';
	$sup_site=$_POST['sup_site'];
	$sdlno=isset($_POST['sdlno'])? $_POST['sdlno'] : 0;
	$bumblebee=new witcher_supervisor();
	$megatron=new witcher_supervisor();

	if(empty($sup_site)){
		$out="Please Fill All Fields!!!";
	}elseif($sup_site=="NA"){
		$out="Please Select Your Site First!!!";
	}elseif(empty($_SESSION['stc_agent_sub_id'])){
		$out="logout";
	}elseif(empty($_SESSION['stc_agent_sup_dailylist_cart_sess'])){
		$out="Please Order atleast One Product!!!";
	}else{
		$outbumblembee=$bumblebee->stc_super_list_add($sup_site, $sdlno);
		if($outbumblembee=="success"){
			$outmegatron=$megatron->stc_super_list_items_add();
    			
			if($outmegatron=="success"){
			    $out.="Thank You!!!Your Order Will Be Placed ASAP.";
			    unset($_SESSION['stc_agent_sup_dailylist_cart_sess']);
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

if(isset($_POST['call_searched_requisition'])){
	$supreqfromdate=date("Y-m-d", strtotime($_POST['supreqfromdate']));
	$supreqtodate=date("Y-m-d", strtotime($_POST['supreqtodate']));
	$objsearchreq=new witcher_supervisor();
	$opobjsearchreq=$objsearchreq->stc_search_requisitions($supreqfromdate, $supreqtodate);
	echo $opobjsearchreq;
}

if(isset($_POST['get_requisition_pert'])){
	$odid=$_POST['odid'];
	$megabots=new witcher_supervisor();
	$outmegabots=$megabots->stc_ag_requisition_items_call($odid);
	echo $outmegabots;
}

if(isset($_POST['stc_rec_qntyhit'])){
	$stc_rec_qty=$_POST['stc_super_rec_qnty_text'];
	$stc_req_id=$_POST['super_rec_value'];
	$stc_req_item_id=$_POST['super_rec_item_value'];
	$stc_req_item_cqty=$_POST['js_stc_super_rec_qnty_cqty'];
	$objloki=new witcher_supervisor();
	if(!empty($_SESSION["stc_agent_sub_id"])){
		$opobjloki=$objloki->stc_supervisor_list_rec_items_hit($stc_req_id, $stc_req_item_id, $stc_rec_qty, $stc_req_item_cqty);
		echo $opobjloki;
	}else{
		echo "exit";
	}
}

if(isset($_POST['stc_req_edit_item_delete'])){
	$list_id=$_POST['list_id'];
	$item_id=$_POST['item_id'];
	$objloki=new witcher_supervisor();
	if(!empty($_SESSION["stc_agent_sub_id"])){
		$opobjloki=$objloki->stc_remove_requisition($list_id, $item_id);
		echo $opobjloki;
	}else{
		echo "exit";
	}
}

// call req items for edit
if(isset($_POST['stc_req_edit_item_show'])){
	$req_id=$_POST['req_id'];
	$odin_req=new witcher_supervisor();
	$odin_req_out=$odin_req->stc_change_req_item_call($req_id);
	echo json_encode($odin_req_out);
}

// update item
if(isset($_POST['stc_req_edit_item_update'])){
	$req_item_id=$_POST['req_item_id'];
	$req_item_name=$_POST['req_item_name'];
	$req_item_unit=$_POST['req_item_unit'];
	$req_item_type=$_POST['req_item_type'];
	$odin_req=new witcher_supervisor();
	$odin_req_out=$odin_req->stc_change_req_item_update($req_item_id, $req_item_name, $req_item_unit, $req_item_type);
	echo $odin_req_out;
}

// update item
if(isset($_POST['stc_search_items'])){
	$search=$_POST['search'];
	$odin_req=new witcher_supervisor();
	$odin_req_out=$odin_req->stc_search_item($search);
	echo json_encode($odin_req_out);
}
?>