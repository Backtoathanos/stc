<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Silent Invoice --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class aetherSale extends tesseract{	

	// stc call silent challan
	public function stc_call_product_links($challanbegdate, $challanenddate){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT 
				`stc_product_grn_id`, 
				`stc_product_id`, 
				`stc_product_name`, 
				`stc_cat_name`, 
				`stc_product_grn_merchant_id` 
			FROM 
				`stc_electronics_stocking`
			INNER JOIN 
				`stc_product_grn` 
			ON 
				`stc_product_grn_id`=`stc_electronics_stocking_refrence_id` 
			INNER JOIN 
				`stc_product_grn_items` 
			ON 
				`stc_product_grn_items_grn_order_id`=`stc_product_grn_id`
			INNER JOIN 
				`stc_product` 
			ON 
				`stc_product_id`=`stc_product_grn_items_product_id`
			INNER JOIN 
				`stc_category` 
			ON 
				`stc_cat_id`=`stc_product_cat_id`
			WHERE 
				DATE(`stc_product_grn_invoice_date`) 
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $challanbegdate)."' 
				AND '".mysqli_real_escape_string($this->stc_dbs, $challanenddate)."'
			ORDER BY `stc_product_grn_id` ASC
		");
		$odin='
			<table class="table table-hover table-bordered table-responsive">
	        	<thead>
	        	  <tr>
	        	    <th class="text-center" scope="col">GRN ID</th>
	        	    <th class="text-center" scope="col">Item ID</th>
	        	    <th class="text-center" scope="col">Item Description</th>
	        	    <th class="text-center" scope="col">Item Category</th>
	        	    <th class="text-center" scope="col">Total PO Qty</th>
	        	    <th class="text-center" scope="col">PO Qty</th>
	        	    <th class="text-center" scope="col">Total GRN Qty</th> 
	        	    <th class="text-center" scope="col">GRN Qty</th>
	        	    <th class="text-center" scope="col">Inventory Qty</th>
	        	    <th class="text-center" scope="col">Challan Qty</th>
	        	    <th class="text-center" scope="col">Electronics Inventory Qty</th>
	        	    <th class="text-center" scope="col">Electronics Challan Qty</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='10' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {

				$odinallpoqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_purchase_product_items_qty`) as total_po_qty
					FROM
						`stc_purchase_product_items`
					WHERE
						`stc_purchase_product_items_product_id` ='".$row['stc_product_id']."'
				");

				$total_po_qty= 0;
				foreach($odinallpoqry as $odinallporow){
					$total_po_qty=$odinallporow['total_po_qty'];
				}

				$odinpoqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_purchase_product_items_qty`) as merchant_po_qty
					FROM
						`stc_purchase_product_items`
					INNER JOIN 
						`stc_purchase_product` 
					ON 
						`stc_purchase_product_id`=`stc_purchase_product_items_order_id`
					WHERE
						`stc_purchase_product_items_product_id` ='".$row['stc_product_id']."'
					AND 
						`stc_purchase_product_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $row['stc_product_grn_merchant_id'])."'
				");

				$merchant_po_qty= 0;
				foreach($odinpoqry as $odiporow){
					$merchant_po_qty=$odiporow['merchant_po_qty'];
				}

				$odinallgrnqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_product_grn_items_qty`) as allgrn_qty
					FROM
						`stc_product_grn_items`
					WHERE
						`stc_product_grn_items_product_id` ='".$row['stc_product_id']."'
				");

				$allgrn_qty= 0;
				foreach($odinallgrnqry as $odinallgrnrow){
					$allgrn_qty=$odinallgrnrow['allgrn_qty'];
				}

				$odingrnqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_product_grn_items_qty`) as grn_qty
					FROM
						`stc_product_grn_items`
					INNER JOIN 
						`stc_product_grn` 
					ON 
						`stc_product_grn_id`=`stc_product_grn_items_grn_order_id`
					WHERE
						`stc_product_grn_items_product_id` ='".$row['stc_product_id']."'
					AND 
						`stc_product_grn_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $row['stc_product_grn_merchant_id'])."'
				");

				$grn_qty= 0;
				foreach($odingrnqry as $odingrnrow){
					$grn_qty=$odingrnrow['grn_qty'];
				}

				$odininvqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_item_inventory_pd_qty`) as inv_qty
					FROM
						`stc_item_inventory`
					WHERE
						`stc_item_inventory_pd_id`='".$row['stc_product_id']."'
				");

				$inv_qty= 0;
				foreach($odininvqry as $odininvrow){
					$inv_qty=$odininvrow['inv_qty'];
				}

				$odinchallanqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_sale_product_items_product_qty`) as challan_qty
					FROM
						`stc_sale_product_items`
					WHERE
						`stc_sale_product_items_product_id`='".$row['stc_product_id']."'
				");

				$challan_qty= 0;
				foreach($odinchallanqry as $odinchallanrow){
					$challan_qty=$odinchallanrow['challan_qty'];
				}

				$odineinvqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_electronics_inventory_item_qty`) as einv_qty
					FROM
						`stc_electronics_inventory`
					WHERE
						`stc_electronics_inventory_item_id`='".$row['stc_product_id']."'
				");

				$einv_qty= 0;
				foreach($odineinvqry as $odineinvrow){
					$einv_qty=$odineinvrow['einv_qty'];
				}

				$odinechallanqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_sale_product_silent_challan_items_product_qty`) as echallan_qty
					FROM
						`stc_sale_product_silent_challan_items`
					WHERE
						`stc_sale_product_silent_challan_items_product_id`='".$row['stc_product_id']."'
				");

				$echallan_qty= 0;
				foreach($odinechallanqry as $odinechallanrow){
					$echallan_qty=$odinechallanrow['echallan_qty'];
				}
				$total_in=$allgrn_qty + $inv_qty + $challan_qty;
				$total_out=$einv_qty + $echallan_qty;

				$colortr='';
				if($total_out>$total_in){
					$colortr='style="color: red;background: #bdbdff;font-weight: bold;"';
				}
				$odin.='
					<tr '.$colortr.'>
       					<td>
       					  <p>GRN/'.$row["stc_product_grn_id"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_product_id"].'</p>
       					</td>
       					<td>
       					  <p>'.$row["stc_product_name"].'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.$row["stc_cat_name"].'</p>
       					</td>
       					<td class="text-right">
       					  <p>'.number_format($total_po_qty, 2).'</p>
       					</td>
       					<td class="text-right">
       					  <p>'.number_format($merchant_po_qty, 2).'</p>
       					</td>
       					<td class="text-right">
       					  <p>'.number_format($allgrn_qty, 2).'</p>
       					</td>
       					<td class="text-right">
       					  <p>'.number_format($grn_qty, 2).'</p>
       					</td>
       					<td class="text-right">
       					  <p>'.number_format($inv_qty, 2).'</p>
       					</td>
       					<td class="text-right">
       					  <p>'.number_format($challan_qty, 2).'</p>
       					</td>
       					<td class="text-right">
       					  <p>'.number_format($einv_qty, 2).'</p>
       					</td>
       					<td class="text-right">
       					  <p>'.number_format($echallan_qty, 2).'</p>
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

	// call by product inv
	public function stc_call_product_links_inv($begval, $endval){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT 
				`stc_product_id`, 
				`stc_product_name`, 
				`stc_cat_name`
			FROM 
				`stc_electronics_inventory`
			INNER JOIN 
				`stc_product` 
			ON 
				`stc_product_id`=`stc_electronics_inventory_item_id`
			INNER JOIN 
				`stc_category` 
			ON 
				`stc_cat_id`=`stc_product_cat_id`
			ORDER BY 
				`stc_product_name` 
			ASC LIMIT ".$begval.",".$endval."
		");
		$odin='
			<table class="table table-hover table-bordered table-responsive">
	        	<thead>
	        	  <tr>
	        	    <th class="text-center" scope="col">Item ID</th>
	        	    <th class="text-center" scope="col">Item Description</th>
	        	    <th class="text-center" scope="col">Item Category</th>
	        	    <th class="text-center" scope="col">Total PO Qty</th>
	        	    <th class="text-center" scope="col">Total GRN Qty</th> 
	        	    <th class="text-center" scope="col">Inventory Qty</th>
	        	    <th class="text-center" scope="col">Challan Qty</th>
	        	    <th class="text-center" scope="col">Electronics Inventory Qty</th>
	        	    <th class="text-center" scope="col">Electronics Challan Qty</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='10' align='center'>No Record Found!!!</td></tr>";
		}else{
			$counter=0;
			foreach ($check_loki as $row) {
				$counter++;

				$odinallpoqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_purchase_product_items_qty`) as total_po_qty
					FROM
						`stc_purchase_product_items`
					WHERE
						`stc_purchase_product_items_product_id` ='".$row['stc_product_id']."'
				");

				$total_po_qty= 0;
				foreach($odinallpoqry as $odinallporow){
					$total_po_qty=$odinallporow['total_po_qty'];
				}

				$odinallgrnqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_product_grn_items_qty`) as allgrn_qty
					FROM
						`stc_product_grn_items`
					WHERE
						`stc_product_grn_items_product_id` ='".$row['stc_product_id']."'
				");

				$allgrn_qty= 0;
				foreach($odinallgrnqry as $odinallgrnrow){
					$allgrn_qty=$odinallgrnrow['allgrn_qty'];
				}

				$odininvqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_item_inventory_pd_qty`) as inv_qty
					FROM
						`stc_item_inventory`
					WHERE
						`stc_item_inventory_pd_id`='".$row['stc_product_id']."'
				");

				$inv_qty= 0;
				foreach($odininvqry as $odininvrow){
					$inv_qty=$odininvrow['inv_qty'];
				}

				$odinchallanqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_sale_product_items_product_qty`) as challan_qty
					FROM
						`stc_sale_product_items`
					WHERE
						`stc_sale_product_items_product_id`='".$row['stc_product_id']."'
				");

				$challan_qty= 0;
				foreach($odinchallanqry as $odinchallanrow){
					$challan_qty=$odinchallanrow['challan_qty'];
				}

				$odineinvqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_electronics_inventory_item_qty`) as einv_qty
					FROM
						`stc_electronics_inventory`
					WHERE
						`stc_electronics_inventory_item_id`='".$row['stc_product_id']."'
				");

				$einv_qty= 0;
				foreach($odineinvqry as $odineinvrow){
					$einv_qty=$odineinvrow['einv_qty'];
				}

				$odinechallanqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_sale_product_silent_challan_items_product_qty`) as echallan_qty
					FROM
						`stc_sale_product_silent_challan_items`
					WHERE
						`stc_sale_product_silent_challan_items_product_id`='".$row['stc_product_id']."'
				");

				$echallan_qty= 0;
				foreach($odinechallanqry as $odinechallanrow){
					$echallan_qty=$odinechallanrow['echallan_qty'];
				}
				$total_in=$allgrn_qty + $inv_qty + $challan_qty;
				$total_out=$einv_qty + $echallan_qty;

				$colortr='';
				if($total_out>$total_in){
					$colortr='style="color: red;background: #bdbdff;font-weight: bold;"';
				}elseif($inv_qty>$einv_qty){
					$colortr='style="color: red;background: #ffcc00;font-weight: bold;"';
				}
				$odin.='
					<tr '.$colortr.'>
       					<td title="Item ID">
       					  <p>'.$row["stc_product_id"].'</p>
       					</td>
       					<td title="Item Description">
       					  <p>'.$row["stc_product_name"].'</p>
       					</td>
       					<td class="text-center" title="Item Category">
       					  <p>'.$row["stc_cat_name"].'</p>
       					</td>
       					<td class="text-right" title="Total PO Quantity">
       					  <p>'.number_format($total_po_qty, 2).'</p>
       					</td>
       					<td class="text-right" title="Total GRN Quantity">
       					  <p>'.number_format($allgrn_qty, 2).'</p>
       					</td>
       					<td class="text-right" title="Inventory Quantity">
       					  <p>'.number_format($inv_qty, 2).'</p>
       					</td>
       					<td class="text-right" title="Challan Quantity">
       					  <p>'.number_format($challan_qty, 2).'</p>
       					</td>
       					<td class="text-right" title="Electronics Inventory Quantity">
       					  <p>'.number_format($einv_qty, 2).'</p>
       					</td>
       					<td class="text-right" title="Electronics Challan Quantity">
       					  <p>'.number_format($echallan_qty, 2).'</p>
       					</td>
            		</tr>         	
		    	';		

		    	if($counter==15){
		    		break;
		    	}		
			}
			
		}
		$odin.='
					<tr>
						<td colspan="5">
							'.$begval.' to '.$endval.'
							<button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
								<i class="fa fa-arrow-left"></i>
							</button>
							<input type="hidden" class="begvalueinputsearch" value="'.$begval.'">
						</td>
						<td colspan="4">
							<button type="button" class="btn btn-primary endbuttoninvsearch">
								<i class="fa fa-arrow-right"></i>
							</button>
							<input type="hidden" class="endvalueinputsearch" value="'.$endval.'">
						</td>
					</tr>
				</tbody>
	       	</table>
		';
		return $odin;
	}
}

#<------------------------------------------------------------------------------------------>
#<---------------------------------Silent Challan Section----------------------------------->
#<------------------------------------------------------------------------------------------>

// Show Sale Orders On Sale Order Page
if(isset($_POST['stccallprolinks'])){
	$challanbegdate=date("Y-m-d h:i:s", strtotime($_POST['begdate']));
	$challanenddate=date("Y-m-d h:i:s", strtotime($_POST['enddate']));
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_call_product_links($challanbegdate, $challanenddate);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call product links by inventory
if(isset($_POST['stccallprolinksinv'])){
	$begval=$_POST['begval'];
	$endval=$_POST['endval'];
	$objloki=new aetherSale();
	$objlokiout=$objloki->stc_call_product_links_inv($begval, $endval);
	echo $objlokiout;
	// echo json_encode($objlokiout);
}
?>