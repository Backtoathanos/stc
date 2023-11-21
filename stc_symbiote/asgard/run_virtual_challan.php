<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------------For GRN -------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/

class redskull extends tesseract{	
	public function stc_get_grn_value(){
		$slno=0;
		$totalgst=0;
		$total=0;
		$odin = '  
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
		if(!empty($_SESSION["stc_virtual_challan"])){
			foreach($_SESSION["stc_virtual_challan"] as $grn_no){
				$lokivirtchallan=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_product_grn_items` 
					INNER JOIN `stc_product`
					ON `stc_product_id`=`stc_product_grn_items_product_id`
					WHERE `stc_product_grn_items_grn_order_id`='".$grn_no['grn_id']."'
				");
				foreach($lokivirtchallan as $grninfo){
					$slno++;
					$amount=round($grninfo["stc_product_grn_items_rate"], 2) * round($grninfo["stc_product_grn_items_qty"], 2);
					$gstamount=($amount * $grninfo["stc_product_gst"]/100);
					$odin .= ' 
						<tr>
							<td>'.$slno.'</td>
							<td>'.$grninfo["stc_product_name"].'</td>
							<td>'.$grninfo["stc_product_hsncode"].'</td>
							<td>'.$grninfo["stc_product_unit"].'</td>
							<td>'.number_format($grninfo["stc_product_grn_items_qty"], 2).'</td>
							<td><i class="fas fa-rupee-sign"></i> '.number_format($grninfo["stc_product_grn_items_rate"], 2).'</td>
							<td><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
							<td> '.$grninfo["stc_product_gst"].'% </td>
							<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount + $gstamount, 2).'</td>
							<td>
								<a style="color: red;font-size:20px;" href="#" id="'.$grninfo["stc_product_grn_items_grn_order_id"].'" class="stcdelvcbtn">
									<i class="fas fa-trash"></i>
								</a>
							</td>
						</tr>
					';  

					$totalgst+=$gstamount;
					$total+=$amount;
				}
					$ekdumt=$total + $totalgst;
					$tcs=(($total + $totalgst) * 0.075)/100;
					$odin .= ' 
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
			            	  <td colspan="2"><h4 align="right">Freight Charges</h4></td>
			            	  <td colspan="2" align="right">
			            	    <i class="fas fa-rupee-sign"></i>
			            	    <input type="text" class="form-control stcfc" name="stcfc" value="0" placeholder="FC" style="width:100%;">
			            	  </td>
			            	  <td colspan="4"><h4 align="right">Packing & Forwarding</h4></td>
			            	  <td colspan="2" align="right">
			            	    <i class="fas fa-rupee-sign"></i>
			            	    <input type="text" class="form-control stcpf" name="stcpf" value="0" placeholder="PF" style="width:100%;">
			            	    <a 
			            	    	style="font-size: 25px;font-weight: bold;color: red;"
			             		  	class="stcsalec"
			             		  	href="#"
			             		>
			             		  <i class="fas fa-arrow-down"></i>
			             		</a>
			            	  </td>
			            	</tr>
			        		<tr>
			        		  <td colspan="8"><h4 align="right">TCS@ 0.075</h4></td>
			        		  <td align="right"><i class="fas fa-rupee-sign"></i> '.round($tcs, 2).'</td>
			        		</tr>
							<tr>
							  <td colspan="8"><h4 align="right">Grand Total</h4></td>
							  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.round($total + $totalgst + $tcs, 2).'</span></td>
							  <td></td>
							</tr>  
					';  
			}
		}else{
			$odin .= '
				<tr>
					<td colspan="8" align="center">Virtual Challan Cart Empty!!!</td>
				</tr>
			';
		}
		$odin .= '
				</tbody>
			</table>
		';
		return $odin;
	}

	public function sale_process($customer_id, $sale_custorderdate,  $sale_custordernumber,  $sale_stcfc,  $sale_stcpf, $order_waybillno, $order_lrno, $order_suppdate, $order_suppplace, $order_refrence, $order_sitename, $order_contperson, $order_contnumber, $order_shipadd, $order_tandc, $order_notes, $order_grnno){	
		$maxgst='';
		// foreach($_SESSION["stc_virtual_challan"] as $keys => $values) {
		// 	$maxgst=array($values['product_gst']);
		// }
		$odin='';
		$check_query=mysqli_query($this->stc_dbs, "
			SELECT `stc_sale_product_id` 
			FROM `stc_sale_product` 
			WHERE `stc_sale_product_refr_grn_no`='".$order_grnno."'
		");
		$countrow=mysqli_num_rows($check_query);
		if(empty($countrow)){
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
					`stc_sale_product_dc_keys`,
					`stc_sale_product_refr_grn_no`
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
					'18',
					'".$order_notes."',
					'".$order_refrence."',
					'".$order_suppdate."',
					'".$order_suppplace."',
					'".$order_sitename."',
					'".$order_shipadd."',
					'".$order_contperson."',
					'".$order_contnumber."',
					'".$order_tandc."',
					'virtualchallaned',
					'0',
					'0',
					'".$_SESSION['stc_admin_info_id']."',
					'virtualchallaned',
					'".$order_grnno."'
				)
			");
			$odin='';
			if($lokiinsertsale){
				$odin .= "virtual challan done";
			}else{
				$odin .= "Please Check & Try Again!!!";
			}
		}else{
			$odin .= "This GRN Already in records!!!";
		}
		return $odin;
	}

	// go sale session data to sale items table 
	public function stc_save_sale_pro_items(){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product` ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_sale_product_id'];
		if(empty($_SESSION['stc_virtual_challan'])){
			$odin = "Do not leave Items on Sale Order!!!";
		}else{
			foreach ($_SESSION["stc_virtual_challan"] as $key=>$value) {
				$get_grn_info=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_product_grn_items_product_id`,
						`stc_product_grn_items_qty`,
						`stc_product_grn_items_rate`
					FROM `stc_product_grn_items` 
					WHERE `stc_product_grn_items_grn_order_id`='".$value['grn_id']."'
				");
				foreach($get_grn_info as $grnitemsrow){
					$sale_rate=0;
					$total=0;
					$total=round($grnitemsrow['stc_product_grn_items_rate'], 2) * round($grnitemsrow['stc_product_grn_items_qty'], 2);
					$sale_rate=round($grnitemsrow['stc_product_grn_items_rate'], 2) + (round($grnitemsrow['stc_product_grn_items_rate'], 2) * 5/100);
			    	$friday_loki=mysqli_query($this->stc_dbs, "
			    		INSERT INTO `stc_sale_product_vc`(
			    			`stc_sale_product_vc_items_sale_product_id`, 
			    			`stc_sale_product_vc_items_product_id`, 
			    			`stc_sale_product_vc_items_product_qty`, 
			    			`stc_sale_product_vc_items_product_rate`, 
			    			`stc_sale_product_vc_items_product_sale_rate`
			    		) VALUES (
			    			'".$get_purchase_product_id."', 
			    			'".$grnitemsrow['stc_product_grn_items_product_id']."',
			    			'".$grnitemsrow['stc_product_grn_items_qty']."', 
			    			'".$grnitemsrow['stc_product_grn_items_rate']."', 
			    			'".$sale_rate."'
			    		)
			    	");
			    }
		    }
		    $odin = "Virtual Challan Done!!!";
		}
		return $odin;
	}

	// stc call sale orders
	public function stc_call_sale_orders($challanbegdate, $challanenddate){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product` 
			LEFT JOIN `stc_customer` 
			ON `stc_sale_product_cust_id`=`stc_customer_id` 
			WHERE (
				`stc_sale_product_dc_keys`='virtualchallaned'
            	OR (`stc_sale_product_status`='virtualchallaned' AND `stc_sale_product_dc_keys`='virtualchallaned')
			)
			AND (
				`stc_sale_product_date`
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $challanbegdate)."'
				AND '".mysqli_real_escape_string($this->stc_dbs, $challanenddate)."'
			)
			ORDER BY `stc_sale_product`.`stc_sale_product_id` 
			DESC LIMIT 0,50
		");
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Challan No</th>
	        	    <th scope="col">Customer Order Date</th>
	        	    <th scope="col">Customer Order No</th>
	        	    <th scope="col">Customer Name</th> 
	        	    <th scope="col">Way Bill No</th>
	        	    <th scope="col">Customer Site</th>
	        	    <th scope="col">Basic Value</th>
	        	    <th scope="col">Status</th>  
				    <th scope="col">Refrence</th>                       
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
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_sale_product_vc` WHERE `stc_sale_product_vc_items_sale_product_id`='".$row["stc_sale_product_id"]."'
				");
				$soamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$soiqty=$row2nd['stc_sale_product_vc_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_vc_items_product_sale_rate'];
					$soamount+=$soiqty * $soirate;
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
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'</p>
       					</td>
       					<td>
       					  <h6>'.$row["stc_sale_product_status"].'</h6>
       					</td>
       					<td>
       					  <h6>GRN/'.$row["stc_sale_product_refr_grn_no"].'</h6>
       					</td>
       					<td>
       					  <div class="row">       					  
       					    <div class="col-sm-3">
       					     	<a href="#" class="vcmodal" id="'.$row["stc_sale_product_id"].'" grn="'.$row["stc_sale_product_refr_grn_no"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i>
       					     	</a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="" href="virtual-challan-preview.php?vcid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
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

	public function stc_filter_challan($search, $challanbegdate, $challanenddate){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product` 
			LEFT JOIN `stc_customer` 
			ON `stc_sale_product_cust_id`=`stc_customer_id` 
			WHERE (`stc_customer_name` REGEXP '".$search."'
				OR `stc_sale_product_id` REGEXP '".$search."'
				OR `stc_sale_product_status` REGEXP '".$search."'
				OR `stc_sale_product_sitename` REGEXP '".$search."'
			) 
			AND (
				(`stc_sale_product_dc_keys`='virtualchallaned'
            	OR (`stc_sale_product_status`='virtualchallaned' AND `stc_sale_product_dc_keys`='virtualchallaned'))
			)
			AND (
                `stc_sale_product_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $challanbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $challanenddate)."'
            )
			ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC
		");
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Challan No</th>
	        	    <th scope="col">Customer Order Date</th>
	        	    <th scope="col">Customer Order No</th>
	        	    <th scope="col">Customer Name</th> 
	        	    <th scope="col">Way Bill No</th>
	        	    <th scope="col">Customer Site</th>
	        	    <th scope="col">Basic Value</th>
	        	    <th scope="col">Status</th>  
				    <th scope="col">Refrence</th>                         
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='9' align='center'><a class='form-control btn btn-primary'>No Record Found!!!</a></td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_sale_product_vc` WHERE `stc_sale_product_vc_items_sale_product_id`='".$row["stc_sale_product_id"]."'
				");
				$soamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$soiqty=$row2nd['stc_sale_product_vc_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_vc_items_product_sale_rate'];
					$soamount+=$soiqty * $soirate;
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
       					<td style="width:auto;text-align:right;">
       					  <p><i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'</p>
       					</td>
       					<td>
       					  <h6>'.$row["stc_sale_product_status"].'</h6>
       					</td>
       					<td>
       					  <h6>GRN/'.$row["stc_sale_product_refr_grn_no"].'</h6>
       					</td>
       					<td>
       					  <div class="row">       					  
       					    <div class="col-sm-3">
       					     	<a class="vcmodal" href="#" id="'.$row["stc_sale_product_id"].'" grn="'.$row["stc_sale_product_refr_grn_no"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i>
       					     	</a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="" href="virtual-challan-preview.php?vcid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
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

	public function stc_call_update_challan($vcid, $vc_grn, $vc_new_grn){
		$odin='';
		$odin_updatequery=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product` 
			SET `stc_sale_product_refr_grn_no`='".$vc_new_grn."' 
			WHERE `stc_sale_product_id`='".$vcid."' 
			AND `stc_sale_product_refr_grn_no`='".$vc_grn."' 
		");
		if($odin_updatequery){
			$odin="updated";
		}
		return $odin;
	}

	public function stc_call_delete_challan_items($vcid){
		$odin='';
		$odin_deletequery=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_sale_product_vc` 
			WHERE `stc_sale_product_vc_items_sale_product_id`='".$vcid."' 
		");
		if($odin_deletequery){
			$odin="deleted";
		}
		return $odin;
	}

	public function stc_call_add_challan_items($vcid, $vc_new_grn){
		$odin='';
		// if(empty($_SESSION['stc_virtual_challan'])){
		// 	$odin = "Do not leave Items on Sale Order!!!";
		// }else{
			// foreach ($_SESSION["stc_virtual_challan"] as $key=>$value) {
				$get_grn_info=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_product_grn_items_product_id`,
						`stc_product_grn_items_qty`,
						`stc_product_grn_items_rate`
					FROM `stc_product_grn_items` 
					WHERE `stc_product_grn_items_grn_order_id`='".$vc_new_grn."'
				");
				foreach($get_grn_info as $grnitemsrow){
					$sale_rate=0;
					$total=0;
					$total=round($grnitemsrow['stc_product_grn_items_rate'], 2) * round($grnitemsrow['stc_product_grn_items_qty'], 2);
					$sale_rate=$total + ($total * 5/100);
			    	$friday_loki=mysqli_query($this->stc_dbs, "
			    		INSERT INTO `stc_sale_product_vc`(
			    			`stc_sale_product_vc_items_sale_product_id`, 
			    			`stc_sale_product_vc_items_product_id`, 
			    			`stc_sale_product_vc_items_product_qty`, 
			    			`stc_sale_product_vc_items_product_rate`, 
			    			`stc_sale_product_vc_items_product_sale_rate`
			    		) VALUES (
			    			'".$vcid."', 
			    			'".$grnitemsrow['stc_product_grn_items_product_id']."',
			    			'".$grnitemsrow['stc_product_grn_items_qty']."', 
			    			'".$grnitemsrow['stc_product_grn_items_rate']."', 
			    			'".$sale_rate."'
			    		)
			    	");
			    }
		    // }
		    $odin = "added";
		// }
		return $odin;
	}
}

#<------------------------------------------------------------------------------------------>
/*---------------------------------------GRN Objects---------------------------------------*/
#<------------------------------------------------------------------------------------------>
// get grn no for get grn data to session
if(isset($_POST['act_on_vc_hit'])){
	$grn_no=$_POST['grn_no'];
	$output="";
	if(empty($_SESSION["stc_virtual_challan"])){
		if(isset($_SESSION["stc_virtual_challan"])) {  
			$is_available = 0;
			foreach($_SESSION["stc_virtual_challan"] as $keys => $grninfo){  
			     if($_SESSION["stc_virtual_challan"][$keys]['grn_id'] == $grn_no){  
			          $is_available++;  
			          $odin .= "GRN no already added!!!";
			     }  
			}  
			if($is_available < 1) {  
				$item_array = array(  
				     'grn_id'             =>     $grn_no
				);  
				$_SESSION["stc_virtual_challan"][] = $item_array;  
				$output .= "GRN No added!!!";
			}
		}else{  
			$item_array = array(  
			    	 'grn_id'             =>     $grn_no
			);   
			$_SESSION["stc_virtual_challan"][] = $item_array;  
		     $output .= "Virtual Challan List Created & GRN No Added to Virtual CHallan List!!!";
		}
	}else{
		$output.="Can't Accept Multiple GRN Number. First Save Virtual Challan Or Remove GRN Number.";
	}
	echo $output;
}

if(isset($_POST['get_items_cart_from_grn'])){
	$objloki=new redskull();
	$opobjloki=$objloki->stc_get_grn_value();
	echo $opobjloki;
}

if(isset($_POST['del_grn_id_vc'])){
	foreach($_SESSION["stc_virtual_challan"] as $keys => $values){  
		if($values["grn_id"] == $_POST["del_vc_id"]){  
			unset($_SESSION["stc_virtual_challan"][$keys]);  
			echo "GRN Removed!!!";  
		}  
	}  
}

if(isset($_POST['save_vc_action'])){
	$out					='';
	$customer_id			=$_POST['customer_id'];
	$sale_custorderdate		=$_POST['sale_custorderdate'];
	$sale_custordernumber	=$_POST['sale_custordernumber'];
	$sale_stcfc				=$_POST['sale_stcfc'];
	$sale_stcpf				=$_POST['sale_stcpf'];
	$order_waybillno		=$_POST['sale_waybillno'];
	$order_lrno				=$_POST['sale_lrno'];
	$order_suppdate			=$_POST['sale_supplydate'];
	$order_suppplace		=$_POST['sale_supplyplace'];
	$order_refrence			=$_POST['sale_refrence'];
	$order_sitename			=$_POST['sale_sitename'];
	$order_contperson		=$_POST['sale_contperson'];
	$order_contnumber		=$_POST['sale_contnumber'];
	$order_shipadd			=$_POST['sale_shipaddress'];
	$order_tandc			=$_POST['order_tandc'];
	$order_notes			=$_POST['order_notes'];
	$order_grnno			=$_POST['order_grnno'];

	$fridaysale=new redskull();
	$fridaysaleitems=new redskull();
	if(empty($sale_custorderdate) || empty($sale_custordernumber)){
		echo "Please Fill All Fields!!!";
	}else{
		$objfridaygrn=$fridaysale->sale_process(
			$customer_id, 
			$sale_custorderdate, 
			$sale_custordernumber, 
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
			$order_notes,
			$order_grnno
		);
		if($objfridaygrn=='virtual challan done'){
			$objfridaygrnitems=$fridaysaleitems->stc_save_sale_pro_items();
			$out=$objfridaygrnitems;
			unset($_SESSION['stc_virtual_challan']);
		}else{
			$out .= "Something went wrong. Please check & try again!!!";
		}
	}
	echo $out;
}

if(isset($_POST['load_virtual'])){
	$challanbegdate=date("Y-m-d h:i:s", strtotime($_POST['begdateon']));
	$challanenddate=date("Y-m-d h:i:s", strtotime($_POST['enddateon']));
	$objloki=new redskull();
	$objlokiout=$objloki->stc_call_sale_orders($challanbegdate, $challanenddate);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

if(isset($_POST['stcfilterchallan'])){
	$search = str_replace(",", "|", $_POST["stcfilterchallan"]);
	$challanbegdate=date("Y-m-d h:i:s", strtotime($_POST['challanbegdate']));
	$challanenddate=date("Y-m-d h:i:s", strtotime($_POST['challanenddate']));
	$objloki=new redskull();
	$objlokiout=$objloki->stc_filter_challan($search, $challanbegdate, $challanenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}

if(isset($_POST['update_vc'])){
	$out='';
	$vcid=$_POST['vc_id'];
	$vc_grn=$_POST['vc_grn'];
	$vc_new_grn=$_POST['vc_new_grn'];

	$updateval=new redskull();
	$deleteval=new redskull();
	$addval=new redskull();

	$updatevalout=$updateval->stc_call_update_challan($vcid, $vc_grn, $vc_new_grn);
	if($updatevalout=="updated"){
		$deletevalout=$deleteval->stc_call_delete_challan_items($vcid);
		if($deletevalout=="deleted"){
			$addvalout=$addval->stc_call_add_challan_items($vcid, $vc_new_grn);
			if($addvalout=="added"){
				$out.="Virtual challan updated!!!";
			}else{
				$out.="Not added!!!";
			}
		}else{
			$out.="Not deleted!!!";
		}
	}else{
		$out.="Not updated!!!";
	}
	echo $out;
}
?>