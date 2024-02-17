<?php
// stc_procurement.php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*-------------------------------------For Procurements-------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class procurement extends tesseract{
	// call supervisor reuisition items 
	public function stc_ag_req_items_call($odid){
		$sl=1;
		$subcat='';
		$countqty='';
		$pdid=0;
		$transformers='
			<table class="mb-0 table table-hover table-responsive">
				<thead>
				    <th>Sl No</th>
				    <th width="45%">Item Desc</th>
				    <th>Unit</th>
				    <th>Requis Qty</th>
				    <th>Requis Approved Qty</th>
				    <th>Requis Remains Qty</th>
				    <th>Status</th>
				    <th>Action</th>
				</thead>
				<tbody>
		';
		$transformersquery=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_list_items_req_id`,
				`stc_cust_super_requisition_list_id`,
				`stc_cust_super_requisition_list_items_title`,
			    `stc_cust_super_requisition_list_items_unit`,
			    `stc_cust_super_requisition_list_items_reqqty`,
			    `stc_cust_super_requisition_list_items_approved_qty`,
			    `stc_cust_super_requisition_list_items_status`,
				`stc_cust_super_requisition_items_finalqty`
			FROM `stc_cust_super_requisition_list_items` 
			WHERE `stc_cust_super_requisition_list_items_req_id`='".$odid."'
		");
		if(mysqli_num_rows($transformersquery)>0){
			foreach($transformersquery as $firstrow){
				if($firstrow['stc_cust_super_requisition_list_items_approved_qty']!=0){
					$pdid=0;
					$statusitems='';
					$changedstatus='';

					$recqtyqry=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_cust_super_requisition_list_items_rec_list_id`,
						    `stc_cust_super_requisition_list_items_rec_list_item_id`,
						    `stc_cust_super_requisition_list_items_rec_list_pd_id`,
						    `stc_cust_super_requisition_list_items_rec_recqty`
						FROM `stc_cust_super_requisition_list_items_rec` 
						WHERE `stc_cust_super_requisition_list_items_rec_list_id`='".$firstrow['stc_cust_super_requisition_list_items_req_id']."'
					");
					$recqty=0;
					foreach($recqtyqry as $recrow){
						$recqty+=$recrow['stc_cust_super_requisition_list_items_rec_recqty'];
					}

					$recqtyoperations=mysqli_query($this->stc_dbs, "
						SELECT `stc_cust_super_requisition_list_items_rec_recqty` FROM `stc_cust_super_requisition_list_items_rec` 
						WHERE `stc_cust_super_requisition_list_items_rec_list_id`='".$firstrow['stc_cust_super_requisition_list_items_req_id']."'
						AND `stc_cust_super_requisition_list_items_rec_list_item_id`='".$firstrow['stc_cust_super_requisition_list_id']."'
					");

					$recoprqty=0;
					foreach($recqtyoperations as $recoprrow){
						$recoprqty+=$recoprrow['stc_cust_super_requisition_list_items_rec_recqty'];
					}

					$transformers.='
						<tr>
							<td>'.$sl.'</td>
							<td>'.$subcat.' '.$firstrow['stc_cust_super_requisition_list_items_title'].'</td>
							<td>'.$firstrow['stc_cust_super_requisition_list_items_unit'].'</td>
							<td>'.number_format($firstrow['stc_cust_super_requisition_list_items_approved_qty'], 2).'</td>
							<td>
								<input type="number" class="form-control stc-sup-appr-qty'.$firstrow['stc_cust_super_requisition_list_id'].'"
								value="'.$firstrow['stc_cust_super_requisition_list_items_approved_qty'].'">
							</td>
							<td>'.number_format($recoprqty, 2).'</td>
							<td>
								<select class="form-control stc-sup-items-status'.$firstrow['stc_cust_super_requisition_list_id'].'">
									<option value="1" selected>Allow</option>
									<option value="0">Not Allow</option>
								</select>
							</td>
							<td>'.$changedstatus.'
								<a class="btn btn-alert form-control add_to_accept_cart" id="'.$firstrow['stc_cust_super_requisition_list_id'].'" req-id="'.$firstrow['stc_cust_super_requisition_list_items_req_id'].'" style="font-size: 20px;"><i class="fas fa-check-circle"></i></a>
							</td>
						</tr>
					';
					$sl++;
					$pdid=$firstrow['stc_cust_super_requisition_list_items_req_id'];
				}
			}
		}else{
			$transformers.='
					<tr>
						<td colspan="7"><h4>No Products Found!!!</h6></td>
					</tr>
			';
		}
		$transformers.='
					<tr>
						<td colspan="8"><a class="btn btn-success form-control addtoreqcombinercart" id="'.$pdid.'">Add to combine requisition list</a></td>
					</tr>
				</tbody>
			</table>
		';
		return $transformers;
	}

	// requisition cart show
	public function stc_requis_apr_call(){
		$transformers='';
		foreach($_SESSION["stc_supervisor_req_final_cart_req_sess"] as $requisition_row){
			$requisquery=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_cust_super_requisition_list_id`,
				    `stc_cust_project_title`,
				    `stc_agents_name`
				FROM `stc_cust_super_requisition_list` 
				INNER JOIN `stc_cust_pro_supervisor` 
				ON `stc_cust_super_requisition_list_super_id`=`stc_cust_pro_supervisor_id`
				INNER JOIN `stc_agents` 
				ON `stc_agents_id`=`stc_cust_pro_supervisor_created_by`
				INNER JOIN `stc_cust_project` 
				ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id` 
				WHERE `stc_cust_super_requisition_list_id`='".$requisition_row['req_id']."'
				ORDER BY `stc_cust_super_requisition_list_id` DESC
			");
			foreach($requisquery as $reqqryrow){
				$itemssec='';
				$insidetable='
					<table class="table table-bordered">
						<thead>
							<tr>
								<td class="text-center text-success bg-dark">Sl No</td>
								<td class="text-center text-success bg-dark">Requisition Items</td>
								<td class="text-center text-success bg-dark">Requisition Request Qty</td>
								<td class="text-center text-success bg-dark">Requisition Approved Qty</td>
								<td class="text-center text-success bg-dark">Unit</td>
							</tr>
						</thead>
						<tbody>
				';
				$reqitemqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_cust_super_requisition_list_id`,
						`stc_cust_super_requisition_list_items_title`,
					    `stc_cust_super_requisition_list_items_unit`,
					    `stc_cust_super_requisition_list_items_approved_qty`,
					    `stc_cust_super_requisition_items_finalqty`
					FROM `stc_cust_super_requisition_list_items` 
					WHERE `stc_cust_super_requisition_list_items_req_id`='".$reqqryrow['stc_cust_super_requisition_list_id']."'
				");

				$findalqty=0;
				$slno=0;
				foreach($_SESSION["stc_supervisor_req_final_cart_req_item_sess"] as $reqitemsessrow){
					// foreach(@$_SESSION["stc_supervisor_req_final_cart_req_item_sess"] as $keys => $values){  
					//     if(@$_SESSION["stc_supervisor_req_final_cart_req_item_sess"][$keys]['list_id'] == $reqqryrow['stc_cust_super_requisition_list_id'] && $_SESSION["stc_supervisor_req_final_cart_req_item_sess"][$keys]['list_line_id'] == $reqitemrow['stc_cust_super_requisition_list_id']){  
					//         $findalqty=$_SESSION["stc_supervisor_req_final_cart_req_item_sess"][$keys]['product_quantity'];
				    // 	}  
				  	// }  

					// $itemssec.='<p>
					// 		'.$reqitemrow['stc_cust_super_requisition_list_items_title'].' : 
					// 		'.number_format($reqitemrow['stc_cust_super_requisition_list_items_approved_qty'], 2).' : 
					// 		'.number_format($findalqty, 2).' : 
					// 		'.$reqitemrow['stc_cust_super_requisition_list_items_unit'].'
					// </p><br>';
					$title='';
					$req_qty=0;
					$req_unit='';
					foreach($reqitemqry as $reqitemrow){
						if($reqitemrow['stc_cust_super_requisition_list_id']==$reqitemsessrow['list_line_id']){
							$slno++;
							$title=$reqitemrow['stc_cust_super_requisition_list_items_title'];
							$req_qty=$reqitemrow['stc_cust_super_requisition_list_items_approved_qty'];
							$req_unit=$reqitemrow['stc_cust_super_requisition_list_items_unit'];
							$insidetable.='
								<tr>
									<td class="text-center">'.$slno.'</td>
									<td class="text-left">'.$title.'</td>
									<td class="text-right">'.number_format($req_qty, 2).'</td>
									<td class="text-right">'.number_format($reqitemsessrow['product_quantity'], 2).'</td>
									<td class="text-center">'.$req_unit.'</td>
								</tr>
							';
						}
					}
				}
				$insidetable.='
						</tbody>
					</table>
				';		

				$transformers.='
					<tr>
						<td class="text-center">'.$reqqryrow['stc_cust_super_requisition_list_id'].'</td>
						<td class="text-center">'.$reqqryrow['stc_agents_name'].'</td>
						<td class="text-center">'.$reqqryrow['stc_cust_project_title'].'</td>
						<td>'.$insidetable.'</td>
						<td>
							<a href="#" class="btn btn-success removreqitems" id="'.$reqqryrow['stc_cust_super_requisition_list_id'].'">
								<i class="fas fa-trash"></i>
							</a>
						</td>
					</tr>
				';
			}	
		}
		$transformers.='
			<tr>
				<td colspan="3"><input type="text" class="form-control stc-proc-place-order-refrenece" placeholder="Enter your order no.."></td>
				<td colspan="3"><button class="form-control btn btn-success stc-proc-place-order-btn">Place order</button></td>
			</tr>
		';
		return $transformers;
	}

	// item cart for placing  requisitions
	public function stc_requisitionto_cart($cust_order_refr){
		$transformers="";
		$missminute="";
		$date=date("Y-m-d H:i:s");
		$reqplqry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_requisition_combiner`(
				`stc_requisition_combiner_refrence`, 
				`stc_requisition_combiner_date`,
				`stc_requisition_combiner_agent_id`,
				`stc_requisition_combiner_status`
			) VALUES (
				'".$cust_order_refr."', 
				'".$date."', 
				'".@$_SESSION['stc_agent_id']."',
				'1'
			)
		");
		if($reqplqry){
			$req_id=0;
			$getreqplqry=mysqli_query($this->stc_dbs, "
				SELECT 
					MAX(`stc_requisition_combiner_id`) 
					AS currentreqid
				FROM `stc_requisition_combiner` 
				ORDER BY `stc_requisition_combiner_id` DESC 
			");
			foreach($getreqplqry as $grprow){$req_id=$grprow['currentreqid'];}
			foreach($_SESSION["stc_supervisor_req_final_cart_req_sess"] as $reqplqryrow){
				$missminute='';
				$reqplcomqry=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_requisition_combiner_req`(
						`stc_requisition_combiner_req_comb_id`, 
						`stc_requisition_combiner_req_requisition_id`
					) VALUES (
						'".$req_id."',
						'".$reqplqryrow['req_id']."'
					)
				");
				if($reqplcomqry){
					$transformers="yes";
				}else{
					$transformers="no";
				}

				$updatereqstqry=mysqli_query($this->stc_dbs, "
					UPDATE `stc_cust_super_requisition_list` 
					SET `stc_cust_super_requisition_list_status`='4' 
					WHERE `stc_cust_super_requisition_list_id`='".$reqplqryrow['req_id']."'
				");

				if($updatereqstqry){
					$transformers="yes";
				}else{
					$transformers="no";
				}
			}

			foreach($_SESSION['stc_supervisor_req_final_cart_req_item_sess'] as $custreqitemrow){
				$missminute='';
				$updatefinalqtqry=mysqli_query($this->stc_dbs, "
					UPDATE `stc_cust_super_requisition_list_items` 
					SET `stc_cust_super_requisition_items_finalqty`='".$custreqitemrow['product_quantity']."', 
					`stc_cust_super_requisition_list_items_status`='".$custreqitemrow['itemstatus']."' 
					WHERE `stc_cust_super_requisition_list_id`='".$custreqitemrow['list_line_id']."'
					AND `stc_cust_super_requisition_list_items_req_id`='".$custreqitemrow['list_id']."' 
				");

				$req_id=$custreqitemrow['list_line_id'];
				$getproduct_qry=mysqli_query($this->stc_dbs, "
					SELECT `stc_cust_super_requisition_list_items_title`
					FROM `stc_cust_super_requisition_list_items`
					WHERE `stc_cust_super_requisition_list_id`='".$req_id."'
				");
				$pd_title='';
				foreach($getproduct_qry as $getproduct_row){
					$pd_title=$getproduct_row['stc_cust_super_requisition_list_items_title'];
				}

				$getproductinfo_qry=mysqli_query($this->stc_dbs, "
					SELECT `stc_cust_super_requisition_list_purchaser_list_item_id`, `stc_cust_super_requisition_list_purchaser_pd_id`,`stc_cust_super_requisition_list_purchaser_mer_id`
					FROM `stc_cust_super_requisition_list_items`
					INNER JOIN `stc_cust_super_requisition_list_purchaser`
					ON `stc_cust_super_requisition_list_id`=`stc_cust_super_requisition_list_purchaser_list_item_id`
					WHERE `stc_cust_super_requisition_list_items_title`='".mysqli_real_escape_string($this->stc_dbs, $pd_title)."'
					ORDER BY `stc_cust_super_requisition_list_purchaser_id` DESC LIMIT 0,1
				");
				if(mysqli_num_rows($getproductinfo_qry)>0){					
					$reqlineitem_id=$custreqitemrow['list_line_id'];
					$pd_id=0;
					$merch_id=0;
					foreach($getproductinfo_qry as $getproductinfo_row){
						$pd_id=$getproductinfo_row['stc_cust_super_requisition_list_purchaser_pd_id'];
						$merch_id=$getproductinfo_row['stc_cust_super_requisition_list_purchaser_mer_id'];
					}
					$odinset_qry=mysqli_query($this->stc_dbs, "
						INSERT INTO `stc_cust_super_requisition_list_purchaser`(
							`stc_cust_super_requisition_list_purchaser_list_item_id`, 
							`stc_cust_super_requisition_list_purchaser_pd_id`, 
							`stc_cust_super_requisition_list_purchaser_mer_id`, 
							`stc_cust_super_requisition_list_purchaser_created_by`
						) VALUES (
							'".mysqli_real_escape_string($this->stc_dbs, $reqlineitem_id)."',
							'".mysqli_real_escape_string($this->stc_dbs, $pd_id)."',
							'".mysqli_real_escape_string($this->stc_dbs, $merch_id)."',
							'1'
						)
					");
				}

				if($updatefinalqtqry){
					$transformers="yes";
				}else{
					$transformers="no";
				}
			}			
		}else{
			$transformers="Hmm!!! Something went wrong on order creation. Please contact STC support team.";
		}
		return $transformers;
	}

	// remvoe requisition
	public function stc_requisition_remove($req_id, $req_item_id){
		$transformers="";
		$trans_checkreqqry=mysqli_query($this->stc_dbs, "
			SELECT `stc_cust_super_requisition_list_items_req_id` FROM `stc_cust_super_requisition_list_items` WHERE `stc_cust_super_requisition_list_items_req_id`='".mysqli_real_escape_string($this->stc_dbs, $req_id)."'
		");
		if(mysqli_num_rows($trans_checkreqqry)==1){
			mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_cust_super_requisition_list_items` WHERE `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $req_item_id)."'
			");
			mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_cust_super_requisition_list` WHERE `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $req_id)."'
			");
			$transformers="Requisition removed.";
		}else{
			mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_cust_super_requisition_list_items` WHERE `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $req_item_id)."'
			");
			$transformers="Requisition item removed.";
		}
		return $transformers;
	}
}

/*------------------------------------------------------------------------------------------------*/
/*------------------------------------Object Procurements-----------------------------------------*/
/*------------------------------------------------------------------------------------------------*/

// call requisition perticular
if(isset($_POST['get_req_orders_pert'])){
	$odid=$_POST['odid'];
	$megabots=new procurement();
	$outmegabots=$megabots->stc_ag_req_items_call($odid);
	echo $outmegabots;
}

// add to requisition cart
if(isset($_POST['go_for_req_sess'])){
	$item_id=$_POST['item_id'];
	$itemqty=$_POST['itemqty'];
	$itemstatus=$_POST['itemstatus'];
	if($itemqty>0){
		if(isset($_SESSION["stc_supervisor_req_final_cart_req_item_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_supervisor_req_final_cart_req_item_sess"] as $keys => $values) {  
				if($_SESSION["stc_supervisor_req_final_cart_req_item_sess"][$keys]['list_id'] == $_POST["item_id"]) {  
				     $is_available++;  
				     $_SESSION["stc_supervisor_req_final_cart_req_item_sess"][$keys]['product_quantity'] = 
				     $_SESSION["stc_supervisor_req_final_cart_req_item_sess"][$keys]['product_quantity'] + $_POST["itemqty"];  
				     echo "Item Cart Quantity Increased!!!!!!";break;
				}  
			}  
		    if($is_available < 1) {  
		    	$item_array = array(  
		    	     'list_id'             		=>     $_POST["item_req_id"],  
		    	     'list_line_id'          	=>     $_POST["item_id"],  
		    	     'product_quantity'       	=>     $_POST["itemqty"],
		    	     'itemstatus'				=>     $_POST["itemstatus"]  
		    	);  
		    	$_SESSION["stc_supervisor_req_final_cart_req_item_sess"][] = $item_array;
		    	echo "Item Added to Requisition Cart!!!";
		    }  
		}else{  
			$item_array = array(  
		    	     'list_id'             		=>     $_POST["item_req_id"],  
		    	     'list_line_id'          	=>     $_POST["item_id"],  
		    	     'product_quantity'       	=>     $_POST["itemqty"],
		    	     'itemstatus'				=>     $_POST["itemstatus"] 
			);   
			$_SESSION["stc_supervisor_req_final_cart_req_item_sess"][] = $item_array;  
		    echo "Requisition Cart Created & Item Added to Requisition Cart!!!";
		}  
	}else{
		echo "Invalid Quantity.";
	}
	if(isset($_SESSION["stc_supervisor_req_final_cart_req_sess"])) {  
		$is_available = 0;  
		foreach($_SESSION["stc_supervisor_req_final_cart_req_sess"] as $keys => $values) {  
			if($_SESSION["stc_supervisor_req_final_cart_req_sess"][$keys]['req_id'] == $_POST["item_req_id"]) {  
			    $is_available++;
			    // echo "Requisition already added in a list!!!";
			    break;
			}  
		}  
	    if($is_available < 1) {  
	    	$item_array = array(  
	    	     'req_id'             		=>     $_POST["item_req_id"]
	    	);  
	    	$_SESSION["stc_supervisor_req_final_cart_req_sess"][] = $item_array;  
	    	echo "Item Added to Requisition Cart!!!";
	    }  
	}else{
		$item_array = array(
	    	     'req_id'             		=>     $_POST["item_req_id"]
		);
		$_SESSION["stc_supervisor_req_final_cart_req_sess"][] = $item_array;  
	    echo "Requisition Cart Created & Item Added to Requisition Cart!!!";
	}
}

// call requisition cart
if(isset($_POST['call_reuisition_appr_cart_call'])){
	$reqapprtale='';
	if(!empty($_SESSION["stc_supervisor_req_final_cart_req_sess"])){
		$requisapr=new procurement();
		$outrequisapr=$requisapr->stc_requis_apr_call();
		$reqapprtale=$outrequisapr;
	}else{
		$reqapprtale="<tr><td colspan='5' class='text-center'>Requisition approval cart is empty. :(</td></tr>";
	}

	echo $reqapprtale;
} 

// remove requisition from cart
if(isset($_POST['remove_req_sess_act'])){
	$prod_id=$_POST['req_id'];
	foreach($_SESSION["stc_supervisor_req_final_cart_req_sess"] as $keys => $values){  
		if($values["req_id"] == $prod_id){  
			unset($_SESSION["stc_supervisor_req_final_cart_req_sess"][$keys]); 
		}  
	} 
	foreach($_SESSION["stc_supervisor_req_final_cart_req_item_sess"] as $keys => $values){  
		if($values["list_id"] == $prod_id){  
			unset($_SESSION["stc_supervisor_req_final_cart_req_item_sess"][$keys]);  
		}  
	} 
	echo "Item Removed!!!";  
}

// placed requisition to the stc
if(isset($_POST['place_req_sess_act'])){
	$cust_order_refr=$_POST['cust_order_refr'];
	$out='';
	$objrequipl=new procurement();
	$outobjrequipl=$objrequipl->stc_requisitionto_cart($cust_order_refr);
	if($outobjrequipl=="no"){
		$out="Please recheck your requisition & try again.";
	}elseif(empty($_SESSION['stc_agent_id'])){
		$out="Please check ";
	}elseif($outobjrequipl=="yes"){
		$out="Thankyou for your requisition #TeamSTC will start working on it soon.";
		unset($_SESSION["stc_supervisor_req_final_cart_req_sess"]);
		unset($_SESSION["stc_supervisor_req_final_cart_req_item_sess"]);
	}else{
		$out=$outobjrequipl;
	}
	echo $out;
}

// placed requisition to the stc
if(isset($_POST['remove_requisition'])){
	$req_id=$_POST['req_id'];
	$req_item_id=$_POST['req_item_id'];
	$out='';
	$objrequipl=new procurement();
	$outobjrequipl=$objrequipl->stc_requisition_remove($req_id, $req_item_id);
	echo $outobjrequipl;
}
?>