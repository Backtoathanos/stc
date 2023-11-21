<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------------For GRN -------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/

class aetherGrn extends tesseract{	
	public function call_po_items_for_grn($pr_id, $pr_qty, $pr_price){		
		$lokigrn=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_product` WHERE `stc_product_id`='".$pr_id."'
		");
		$loki_getgrn=mysqli_fetch_assoc($lokigrn);
		$odin='';
		if(isset($_SESSION["stc_grn_sess"])) {  
			$is_available = 0;
			foreach($_SESSION["stc_grn_sess"] as $keys => $values){  
        	     if($_SESSION["stc_grn_sess"][$keys]['product_id'] == $pr_id){  
        	          $is_available++;  
        	          $_SESSION["stc_grn_sess"][$keys]['product_quantity'] = $_SESSION["stc_grn_sess"][$keys]['product_quantity'] + $pr_qty;  
        	          $odin .= "GRN List Quantity Increased!!!!!!";
        	     }  
        	}  
			if($is_available < 1) {  
				$item_array = array(  
				     'product_id'             =>     $loki_getgrn["stc_product_id"], 
				     'product_name'       	  	=>     $loki_getgrn["stc_product_name"],
				     'product_hsncode'        	   =>     $loki_getgrn["stc_product_hsncode"],
				     'product_unit'           	   	  =>     $loki_getgrn["stc_product_unit"],
				     'product_quantity'       	   =>     $pr_qty,
				     'product_price'     	  	=>     $pr_price,
				     'product_gst'        	  =>     $loki_getgrn["stc_product_gst"]
				);  
				$_SESSION["stc_grn_sess"][] = $item_array;  
				$odin .= "Item Added to GRN List!!!";
			}
		}else{  
			$item_array = array(  
			    	 'product_id'             =>     $loki_getgrn["stc_product_id"], 
		    	     'product_name'       	  	=>     $loki_getgrn["stc_product_name"],
		    	     'product_hsncode'        	   =>     $loki_getgrn["stc_product_hsncode"],
		    	     'product_unit'           		  =>     $loki_getgrn["stc_product_unit"],
		    	     'product_quantity'       	   =>     $pr_qty,
				     'product_price'     	  	=>     $pr_price,
		    	     'product_gst'        	  =>     $loki_getgrn["stc_product_gst"] 
			);   
			$_SESSION["stc_grn_sess"][] = $item_array;  
		     $odin .= "GRN List Created & Item Added to GRN List!!!";
		}  
		return $odin;
	}

	// grn go on grn table
	public function grn_process($grn_order_id, $grn_invodate, $grn_invonumber, $grn_stcfc, $grn_stcpf, $grn_scoc, $grnnotes){	
		$odin='';		
		$lokigrn=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_purchase_product` WHERE `stc_purchase_product_id`='".$grn_order_id."'
		");
		$loki_getgrn=mysqli_fetch_assoc($lokigrn);
		$po_id=$grn_order_id;
		$stc_product_grn_purchase_order_date=$loki_getgrn['stc_purchase_product_order_date'];
		$stc_product_grn_invoice_number=$grn_invonumber;
		$stc_product_grn_invoice_date=$grn_invodate;
		$stc_product_grn_merchant_id=$loki_getgrn['stc_purchase_product_merchant_id'];
		$stc_product_grn_freight_charges=$grn_stcfc;
		$stc_product_grn_packing_forwarding=$grn_stcpf;
		$stc_product_grn_others_charges=$grn_scoc;

		$lokicheckgrn=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_product_grn` 
			WHERE `stc_product_grn_invoice_number`='".$stc_product_grn_invoice_number."' 
			AND `stc_product_grn_merchant_id`='".$loki_getgrn['stc_purchase_product_merchant_id']."'
		");
		$count_lokigrn=mysqli_num_rows($lokicheckgrn);
		if($count_lokigrn==0){
			$lokiinsertgrn=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_product_grn`(
					`stc_product_grn_purchase_order_id`, 
					`stc_product_grn_date`, 
					`stc_product_grn_purchase_order_date`, 
					`stc_product_grn_invoice_number`, 
					`stc_product_grn_invoice_date`, 
					`stc_product_grn_merchant_id`, 
					`stc_product_grn_freight_charges`, 
					`stc_product_grn_packing_forwarding`, 
					`stc_product_grn_others_charges`,
					`stc_product_grn_created_by`,
					`stc_product_grn_notes`
				) VALUES (
					'".$grn_order_id."', 
					NOW(), 
					'".$stc_product_grn_purchase_order_date."', 
					'".$stc_product_grn_invoice_number."', 
					'".$stc_product_grn_invoice_date."', 
					'".$stc_product_grn_merchant_id."', 
					'".$stc_product_grn_freight_charges."', 
					'".$stc_product_grn_packing_forwarding."', 
					'".$stc_product_grn_others_charges."',
					'".$_SESSION['stc_admin_info_id']."',
					'".$grnnotes."'
				)
			");

			if($lokiinsertgrn){
				$lokiupdatepostatusfromgrn=mysqli_query($this->stc_dbs, "
					UPDATE `stc_purchase_product` SET `stc_purchase_product_status`='accepted' WHERE `stc_purchase_product_id`='".$po_id."'
				");
				$odin = "GRN Done!!!";
			}else{
				$odin = "Please Check & Try Again!!!";
			}
		}else{			
			$odin = "Already GRN!!!";
		}
		return $odin;
	}

	// insert grn items to grn items table
	public function stc_save_grn_pro_items($grn_order_id){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_product_grn` ORDER BY `stc_product_grn`.`stc_product_grn_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_product_grn_id'];
		if(empty($_SESSION['stc_grn_sess'])){
			$odin = "Do not leave Items on GRN!!!";
		}else{
			foreach ($_SESSION["stc_grn_sess"] as $key=>$value) {
		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_product_grn_items`(
		    			`stc_product_grn_items_grn_order_id`, 
		    			`stc_product_grn_items_purchase_order_id`, 
		    			`stc_product_grn_items_product_id`, 
		    			`stc_product_grn_items_qty`, 
		    			`stc_product_grn_items_rate`
		    		) VALUES (
		    			'".$get_purchase_product_id."', 
		    			'".$grn_order_id."', 
		    			'".$value['product_id']."', 
		    			'".$value['product_quantity']."', 
		    			'".$value['product_price']."')"
		    		);
		    	$friday_loki_check_inventory_exist_pd=mysqli_query($this->stc_dbs, "
		    		SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_id`='".$value['product_id']."'
		    	");
		    	$loki_getinvenitems=mysqli_fetch_assoc($friday_loki_check_inventory_exist_pd);
				$inventory_pd_id=$loki_getinvenitems['stc_item_inventory_pd_id'];
				$inventory_pd_qty=$loki_getinvenitems['stc_item_inventory_pd_qty'];
				$iventoryrow=mysqli_num_rows($friday_loki_check_inventory_exist_pd);
				if($iventoryrow == 0){
					$friday_loki_inventory=mysqli_query($this->stc_dbs, "
			    		INSERT INTO `stc_item_inventory`(
			    			`stc_item_inventory_pd_id`, 
			    			`stc_item_inventory_pd_qty`
			    		) VALUES (
			    			'".$value['product_id']."', 
			    			'".$value['product_quantity']."'
			    		)
			    	");
				}else{
					$finalqty = $inventory_pd_qty + $value['product_quantity'];
			    	$friday_loki_inventory=mysqli_query($this->stc_dbs, "
			    		UPDATE `stc_item_inventory` SET `stc_item_inventory_pd_qty`='".$finalqty."' WHERE `stc_item_inventory_pd_id`='".$value['product_id']."'
			    	");
			    }
		    }
		    $odin = "GRN No ".$get_purchase_product_id." Is Saved!!!";
		}
		return $odin;
	}

	// call grn on grn page for view
	public function stc_call_purchase_product($begdate, $enddate){
		$grniqty='';
		$grnirate='';
		$grnamount='';
		$check_loki_nest='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_product_grn` 
			LEFT JOIN `stc_merchant` 
			ON `stc_product_grn_merchant_id`=`stc_merchant_id` 
			WHERE `stc_product_grn_date`
			BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $begdate)."'
			AND '".mysqli_real_escape_string($this->stc_dbs, $enddate)."'
			ORDER BY `stc_product_grn`.`stc_product_grn_id` DESC 
			");
		$odin='
			<table class="table table-hover ">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">GRN Date</th>
	        	    <th scope="col">GRN Number</th>
	        	    <th scope="col">PO Number</th>
	        	    <th scope="col">Invoice Number</th>
	        	    <th scope="col">Invoice Date</th>
	        	    <th scope="col">Merchant</th>
	        	    <th scope="col">Basic Value</th>   
	        	    <th scope="col">Notes</th>
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td>Empty</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_product_grn_items` WHERE `stc_product_grn_items_grn_order_id`='".$row["stc_product_grn_id"]."'
				");
				$grnamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$grniqty=$row2nd['stc_product_grn_items_qty'];
					$grnirate=$row2nd['stc_product_grn_items_rate'];
					$grnamount+=$grniqty * $grnirate;
				}
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$grnnumber = substr("0000{$row["stc_product_grn_id"]}", -$str_length);
              	$ponumber = substr("0000{$row["stc_product_grn_purchase_order_id"]}", -$str_length);
				$odin.='
					<tr>
						<td>
       					  <p>'.date('d-m-Y',strtotime($row["stc_product_grn_date"])).'</p>
       					</td>
       					<td>
       					  <h6>GRN/'.$grnnumber.'</h6>
       					</td>
       					<td>
       					  <h6>STC/'.$ponumber.'</h6>
       					</td>
       					<td>
       					  <p>'.$row["stc_product_grn_invoice_number"].'</p>
       					</td>
       					<td>
       					  <p>'.date('d-m-Y',strtotime($row["stc_product_grn_invoice_date"])).'</p>
       					</td>
       					<td>
       					  <h6>'.$row["stc_merchant_name"].'</h6>
       					</td>
       					<td>
       					  <h5 align="right">'.number_format($grnamount, 2).'</h5>
       					</td>
       					<td>
       					  <p>'.$row["stc_product_grn_notes"].'</p>
       					</td>
       					<td>
       					  <div class="row">
       					    <div class="col-sm-3">
       					     	<a class="stc_show_grn_page" href="#" id="'.$row["stc_product_grn_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-eye" aria-hidden="true"></i></a>
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

	// edit or view on po page
	public function stc_view_grn_items($view_grn_product_id){
		$odin='';
		$order_line_items='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_product_grn`
			LEFT JOIN `stc_merchant`
			ON `stc_product_grn_merchant_id` =`stc_merchant_id`
			WHERE `stc_product_grn_id`='".$view_grn_product_id."'
		");
		$stc_purchase_product_items_view_fedit_rec=mysqli_fetch_assoc($check_loki);
		$get_view_fedit_grn_id=$stc_purchase_product_items_view_fedit_rec['stc_product_grn_id'];
		$get_view_fedit_purchase_merchant_name=$stc_purchase_product_items_view_fedit_rec['stc_merchant_name'];
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "Something went Wrong!!";
			// header('location:index.php');
		}else{
			$nested_check_loki=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_product_grn_items`
				LEFT JOIN `stc_product`
				ON `stc_product_grn_items_product_id` =`stc_product_id`
				WHERE `stc_product_grn_items_grn_order_id`='".$view_grn_product_id."'
			");
			$order_line_items .= '  
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
						    <th scope="col" style="width: auto;">Amount</th>
						  </tr>
						</thead>
						<tbody>
			';  
			if(!empty($nested_check_loki)){ 
				$slno= 0; 
				$total = 0;  
				$totalgst = 0;
				foreach($nested_check_loki as $keys => $values) {  
					$amount=$values["stc_product_grn_items_qty"] * $values["stc_product_grn_items_rate"];
					$gstamount = ($amount * $values["stc_product_gst"])/100;
					$slno++;
					$order_line_items .= ' 
						<tr>
							<td>'.$slno.'</td>
							<td>'.$values["stc_product_name"].'</td>
							<td>'.$values["stc_product_hsncode"].'</td>
							<td>'.$values["stc_product_unit"].'</td>
							<td>'.number_format($values["stc_product_grn_items_qty"], 2).'</td>
							<td>'.number_format($values["stc_product_grn_items_rate"], 2).'</td>
							<td><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
							<td> '.$values["stc_product_gst"].'% </td>
							<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount + $gstamount, 2).'</td>
						</tr>
					';  
					$total = $total + ($values["stc_product_grn_items_qty"] * $values["stc_product_grn_items_rate"]) ;  
					$totalgst +=$gstamount;
					$gstamount = ($amount * $values["stc_product_gst"])/100;
				}  
				$totalchrgst=($stc_purchase_product_items_view_fedit_rec['stc_product_grn_freight_charges'] + 
	            	  		$stc_purchase_product_items_view_fedit_rec['stc_product_grn_packing_forwarding'] + 
	            	  		$stc_purchase_product_items_view_fedit_rec['stc_product_grn_others_charges']) * 18/100;
				$order_line_items .= ' 				   
					<tr>
					  <td colspan="8"><h4 align="right">Total</h4></td>
					  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total, 2).'</td>
					</tr>
						<tr>
						<td colspan="2"><h6 align="right">Freight Charges : </h6></td>
	            		<td align="right">
	            		  <i class="fas fa-rupee-sign"></i> '.$stc_purchase_product_items_view_fedit_rec['stc_product_grn_freight_charges'].'
	            		</td>
	            		<td colspan="2"><h6 align="right">Packing & Forwarding : </h6></td>
	            		<td align="right">
	            		  <i class="fas fa-rupee-sign"></i> '.$stc_purchase_product_items_view_fedit_rec['stc_product_grn_packing_forwarding'].'
	            		</td>
	            		<td colspan="2"><h6 align="right">Other Charges : </h6></td>
	            		<td align="right">
	            		  <i class="fas fa-rupee-sign"></i> '.$stc_purchase_product_items_view_fedit_rec['stc_product_grn_others_charges'].'
	            		</td>
					</tr>
					<tr>
					  <td colspan="8"><h4 align="right">CGST</h4></td>
					  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
					</tr>
					<tr>
					  <td colspan="8"><h4 align="right">SGST</h4></td>
					  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
					</tr>
					<tr>
					  <td colspan="8"><h4 align="right">Total Tax</h4></td>
					  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst, 2).'</td>
					</tr>
	            	<tr>
	            	  <td colspan="8"><h4 align="right">Grand Total</h4></td>
	            	  <td align="right">
	            	  	<i class="fas fa-rupee-sign"></i> <span id="stc_final_grn_value">'.number_format(
	            	  		$total + 
	            	  		$totalgst + 
	            	  		$stc_purchase_product_items_view_fedit_rec['stc_product_grn_freight_charges'] + 
	            	  		$stc_purchase_product_items_view_fedit_rec['stc_product_grn_packing_forwarding'] + 
	            	  		$stc_purchase_product_items_view_fedit_rec['stc_product_grn_others_charges'] +
	            	  		$totalchrgst
	            	  	, 2).'</span>           	  	
	            	  </td>
	            	</tr>
				';
			}else{
				$order_line_items.='
					<tr>  
						<td colspan="8" align="center">  
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
			'grn_number' => $get_view_fedit_grn_id,
			'grn_date' => $stc_purchase_product_items_view_fedit_rec['stc_product_grn_date'],
			'po_number' => $stc_purchase_product_items_view_fedit_rec['stc_product_grn_purchase_order_id'],
			'po_date' => $stc_purchase_product_items_view_fedit_rec['stc_product_grn_purchase_order_date'],
			'merchant_name' => $get_view_fedit_purchase_merchant_name,
			'chalan_number' => $stc_purchase_product_items_view_fedit_rec['stc_product_grn_invoice_number'],
			'challan_date' => $stc_purchase_product_items_view_fedit_rec['stc_product_grn_invoice_date'],
			'line_items_grn' => $order_line_items
		);
		return $odin;
	}

	// filter grn
	public function stc_filter_grn($search, $grnbegdate, $grnenddate){
		$grniqty='';
		$grnirate='';
		$grnamount='';
		$check_loki_nest='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_product_grn` 
			LEFT JOIN `stc_merchant` 
			ON `stc_product_grn_merchant_id`=`stc_merchant_id` 
			WHERE `stc_product_grn_purchase_order_id` REGEXP '".$search."'
			OR `stc_product_grn_invoice_number` REGEXP '".$search."'
			OR `stc_product_grn_id` REGEXP '".$search."'
			OR `stc_merchant_name` REGEXP '".$search."'
			AND (
                `stc_product_grn_date`
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $grnbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $grnenddate)."'
            )
			ORDER BY `stc_product_grn`.`stc_product_grn_id` DESC 
			");
		$odin='
			<table class="table table-hover ">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">GRN Date</th>
	        	    <th scope="col">GRN Number</th>
	        	    <th scope="col">PO Number</th>
	        	    <th scope="col">Invoice Number</th>
	        	    <th scope="col">Invoice Date</th>
	        	    <th scope="col">Merchant</th>
	        	    <th scope="col">Basic Value</th>                         
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='8'><a class='btn btn-primary form-control'>No Records Found</a></td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_product_grn_items` WHERE `stc_product_grn_items_grn_order_id`='".$row["stc_product_grn_id"]."'
				");
				$grnamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$grniqty=$row2nd['stc_product_grn_items_qty'];
					$grnirate=$row2nd['stc_product_grn_items_rate'];
					$grnamount+=$grniqty * $grnirate;
				}
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$grnnumber = substr("0000{$row["stc_product_grn_id"]}", -$str_length);
              	$ponumber = substr("0000{$row["stc_product_grn_purchase_order_id"]}", -$str_length);
				$odin.='
					<tr>
						<td>
       					  <p>'.date('d-m-Y',strtotime($row["stc_product_grn_date"])).'</p>
       					</td>
       					<td>
       					  <h6>GRN/'.$grnnumber.'</h6>
       					</td>
       					<td>
       					  <h6>STC/'.$ponumber.'</h6>
       					</td>
       					<td>
       					  <p>'.$row["stc_product_grn_invoice_number"].'</p>
       					</td>
       					<td>
       					  <p>'.date('d-m-Y',strtotime($row["stc_product_grn_invoice_date"])).'</p>
       					</td>
       					<td>
       					  <h6>'.$row["stc_merchant_name"].'</h6>
       					</td>
       					<td>
       					  <h5 align="right">'.number_format($grnamount, 2).'</h5>
       					</td>
       					<td>
       					  <div class="row">
       					    <div class="col-sm-3">
       					     	<a class="stc_show_grn_page" href="#" id="'.$row["stc_product_grn_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-eye" aria-hidden="true"></i></a>
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
}

#<------------------------------------------------------------------------------------------>
/*---------------------------------------GRN Objects---------------------------------------*/
#<------------------------------------------------------------------------------------------>
// show grn of session in grn page
if(isset($_POST['comon_grn_sess'])){
	$order_table = '';  
	$slno = '';  
	$total=0;
	$totalgst=0;
	$order_table .= '  
		<table class="table table-hover" align="centre">
			<thead>
			  	<tr>
            	  <th scope="col">#</th>
            	  <th scope="col" style="width: 20%;">Items Desc</th>
            	  <th scope="col">HSN Code</th> 
            	  <th scope="col">Unit</th>
            	  <th scope="col">Accepted Qty</th>
            	  <th scope="col">Change Qty</th>                         
            	  <th scope="col">Price</th>
            	  <th scope="col">Amount</th>
            	  <th scope="col">GST</th>
            	  <th scope="col" width="10%">Amount</th>
            	</tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_grn_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_grn_sess"] as $keys => $row) {  
				$amount=$row["product_quantity"] * $row["product_price"];
				$gstamount = ($amount * $row["product_gst"])/100;
				$slno++;
				$order_table .= "				    	    
					<tr>
             			<td>".$slno."</td>
             			<td>".$row['product_name']."</td>
             			<td>".$row['product_hsncode']."</td>
             			<td>".$row['product_unit']."</td>
             			<td>".number_format($row['product_quantity'], 2)."</td>
             			<td width='12%'>
             			  <input 
             			    type='hidden'
             			    id='stcrategrnt".$row["product_id"]."' 
             			    value=".$row['product_price']."
             			  >                            
             			  <input 
             			    type='text' 
             			    class='form-control stcchangegrnsessqty".$row["product_id"]."'
             			    placeholder='Quantity' 
             			    style='width:100%;'
             			    value='0'
             			  >
             			  <a 
             			    id='".$row["product_id"]."' 
             			    class='stcchangegrnsessqty' 
             			    href='#'
             			  >
             			    <i class='fas fa-arrow-right'></i>
             			  </a>
             			</td>
             			<td><i class='fas fa-rupee-sign'></i> ".number_format($row['product_price'], 2)."</td>
             			<td><i class='fas fa-rupee-sign'></i> ".number_format($amount, 2)."</td>
             			<td>".$row['product_gst']."%</td>
             			<td><i class='fas fa-rupee-sign'></i> ".number_format($amount + $gstamount, 2)."
             			</td>
                	</tr>
				";	
				$total = $total + ($row['product_quantity'] * $row['product_price']);  
                $totalgst +=$gstamount;
			}
			$ekdumt=$total + $totalgst;
			$tcs=(($total + $totalgst) * 0.075)/100;
			$order_table.='
				<tr>
        		  <td colspan="9"><h4 align="right">Total</h4></td>
        		  <td align="right"><i class="fas fa-rupee-sign"></i> '.round($total, 2).'</td>
        		</tr>
        		<tr>
        		  <td colspan="9"><h4 align="right">CGST</h4></td>
        		  <td align="right"><i class="fas fa-rupee-sign"></i> '.round($totalgst/2, 2).'</td>
        		</tr>
        		<tr>
        		  <td colspan="9"><h4 align="right">SGST</h4></td>
        		  <td align="right"><i class="fas fa-rupee-sign"></i> '.round($totalgst/2, 2).'</td>
        		</tr>
        		<tr>
        		  <td colspan="9"><h4 align="right">Total Tax</h4></td>
        		  <td align="right"><i class="fas fa-rupee-sign"></i> '.round($totalgst, 2).'</td>
        		</tr>
        		<tr>
        		  <td colspan="9"><h4 align="right">Sub Total</h4></td>
        		  <td align="right">
        		  	<i class="fas fa-rupee-sign"></i> '.round($total + $totalgst, 2).'
        		  	<input type="hidden" id="stc_grand_offset_table_value" value="'.$ekdumt.'">

        		  </td>
        		</tr>
        		<tr>
            	  <td colspan="3"><h4 align="center">Freight Charges <i class="fas fa-arrow-down"></i></h4></td>
            	  <td colspan="3"><h4 align="center">Packing & Forwarding <i class="fas fa-arrow-down"></i></h4></td>
            	  <td colspan="3"><h4 align="center">Other Charges <i class="fas fa-arrow-down"></i></h4></td>
            	  <td colspan="1"><h4 align="right">Set <i class="fas fa-arrow-down"></i></h4></td>
        		</tr>

        		<tr>
            	  <td colspan="3" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcfc" name="stcfc" value="0" placeholder="FC" style="width:100%;">
            	  </td>
            	  <td colspan="3" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcpf" name="stcpf" value="0" placeholder="PF" style="width:100%;">
            	  </td>
            	  <td colspan="3" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcoc" name="stcoc" value="0" placeholder="OC" style="width:100%;">
            	  </td>
            	  <td colspan="1" align="right">
            	    <a 
            	    	style="font-size: 25px;font-weight: bold;color: red;"
             		  	class="stcgrnfcpo"
             		  	href="#"
             		>
             		  <i class="fas fa-arrow-down"></i>
             		</a>
            	  </td>
            	</tr>
        		<tr>
        		  <td colspan="9"><h4 align="right">TCS@ 0.075</h4></td>
        		  <td align="right"><i class="fas fa-rupee-sign"></i> '.round($tcs, 2).'</td>
        		</tr>
            	<tr>
            	  <td colspan="9"><h4 align="right">Grand Total</h4></td>
            	  <td align="right">
            	  	<i class="fas fa-rupee-sign"></i> <span id="stc_final_grn_value">'.round($total + $totalgst+ $tcs, 2).'</span>           	  	
            	  </td>
            	</tr>
			';
		}else{
			$order_table.='
				<tr>  
					<td colspan="10" align="center">  
					    <h3>Cart Empty!!!</h3>
					</td>  
				</tr>  
			';
		}
	$order_table .= '
				
		  </tbody>
		</table>
		';  
	$export=array(
	  "orderTable" => $order_table
	  
	); 
	// header('Content-type: application/json');
	echo json_encode($export);
	// print_r($_SESSION['stc_grn_sess']);
}
// add po items to the session for grn
if(isset($_POST['add_grn_sess_action'])){
	$pr_id=$_POST['purchase_pro_id'];
	$pr_qty=$_POST['product_qty'];
	$pr_price=$_POST['product_price'];
	$fridaygrn=new aetherGrn();

	$objfridaygrn=$fridaygrn->call_po_items_for_grn($pr_id, $pr_qty, $pr_price);
	echo $objfridaygrn;
}

// change qty of grn session
if(isset($_POST['change_grn_sess_qty_action'])){
	foreach($_SESSION["stc_grn_sess"] as $keys => $values){  
	    if($_SESSION["stc_grn_sess"][$keys]['product_id'] == $_POST["purchase_pro_id"]){  
	         $_SESSION["stc_grn_sess"][$keys]['product_quantity'] = $_POST["product_qty"];
	         echo "Cart Updated!!";
    	}  
  	}  
}
// charges plus minus of grn fields
if(isset($_POST['do_plus_minus_on_grn'])){
	$fc=$_POST['freightcharge'];
	$pf=$_POST['packingandforwardingcharge'];
	$oc=$_POST['othercharge'];
	$gt=$_POST['grand_total'];

	$gstfc=($fc * 18)/100;
	$gstpf=($pf * 18)/100;
	$gstoc=($oc * 18)/100;

	$out=($gstfc + $fc) + ($gstpf + $pf) + ($gstoc + $oc) + $gt;
	echo $out;
}

// cancel session which is loaded for grn
if(isset($_POST['cancel_grn_sess_action'])){
	unset($_SESSION['stc_grn_sess']);
	echo "GRN Cancelled!!!";
}

// save grn
if(isset($_POST['save_grn_action'])){
	$out='';
	$grn_order_id=$_POST['grn_order_id'];
	$grn_invodate=$_POST['grn_invodate'];
	$grn_invonumber=$_POST['grn_invonumber'];
	$grn_stcfc=$_POST['grn_stcfc'];
	$grn_stcpf=$_POST['grn_stcpf'];
	$grn_scoc=$_POST['grn_scoc'];
	$grnnotes=$_POST['grn_notes'];
	$fridaygrn=new aetherGrn();
	$fridaygrnitems=new aetherGrn();
	if(empty($grn_invonumber) || empty($grn_invodate)){
		$out="Please Fill All Fields First!!!";
	}elseif(empty($_SESSION['stc_grn_sess'])){
		$out="Items add kar pahle GRN list mein!!!";
	}else{
		$objfridaygrn=$fridaygrn->grn_process($grn_order_id, $grn_invodate, $grn_invonumber, $grn_stcfc, $grn_stcpf, $grn_scoc, $grnnotes);
		if($objfridaygrn=='GRN Done!!!'){
			$objfridaygrnitems=$fridaygrnitems->stc_save_grn_pro_items($grn_order_id);
			$out=$objfridaygrnitems;
			unset($_SESSION['stc_grn_sess']);
		}elseif($objfridaygrn=='already grn'){
			// $out = "Teri maa ka chola 75 baar grn karega 1 invoice se!!!";
			$out = "I think this GRN is already done!!!";
		}else{
			$out = "Hmmm!!! Something went not good. Please try again later!!!";
		}
	}
	echo $out;
}

// call grn
if(isset($_POST['stccallgrn'])){
	$begdate=date("Y-m-d h:i:s", strtotime($_POST['begdate']));
	$enddate=date("Y-m-d h:i:s", strtotime($_POST['enddate']));
	$objloki=new aetherGrn();
	$objlokiout=$objloki->stc_call_purchase_product($begdate, $enddate);
	// echo json_encode($objlokiout);
	echo $objlokiout;
}

// show grn items on grn page
if(isset($_POST['stc_grn_view_for_edit'])){
	$view_grn_product_id=$_POST['stc_grn_edit_pid'];
	$objloki=new aetherGrn();
	$objlokiout=$objloki->stc_view_grn_items($view_grn_product_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

if(isset($_POST['stcfiltergrn'])){
	$search = str_replace(",", "|", $_POST["stcfiltergrn"]);
	$grnbegdate=date("Y-m-d h:i:s", strtotime($_POST['grnbegdate']));
	$grnenddate=date("Y-m-d h:i:s", strtotime($_POST['grnenddate']));

	$objloki=new aetherGrn();
	$objlokiout=$objloki->stc_filter_grn($search, $grnbegdate, $grnenddate);

	echo json_encode($objlokiout);
	// echo $objlokiout;
}
?>