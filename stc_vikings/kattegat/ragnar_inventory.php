<?php
include "../../MCU/obdb.php";
class ragnarInventory extends tesseract{
	// call rack
	public function call_rack(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_rack` ORDER BY `stc_rack`.`stc_rack_name` ASC");
		$odin='<option value="0" selected>Select Rack</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option selected>No Rack Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_rack_id"].'">'.$row["stc_rack_name"].'</option>		               	
		            	';				
			}
			
		}
		return $odin;
	}

	// call inventory
	public function stc_call_inventory($begval, $endval){
		$slno=0;
		if($begval==0){
			$slno=0;
		}else{
			$slno=$begval;
		}
		$subcat='';
		$rackname='';
		$result=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_product_id`,
				`stc_product_name`,
				`stc_item_inventory_pd_qty`,
				`stc_product_unit`,
				`stc_sub_cat_name`,
				`stc_rack_name`,
				`stc_brand_title`
			FROM `stc_item_inventory`
			LEFT JOIN `stc_product`
			ON `stc_item_inventory_pd_id`=`stc_product_id`
			LEFT JOIN `stc_rack`
			ON `stc_product_rack_id`=`stc_rack_id`
			LEFT JOIN `stc_sub_category`
			ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
			LEFT JOIN `stc_product_grn_items`
			ON `stc_product_grn_items_product_id`=`stc_product_id`
			LEFT JOIN `stc_product_grn`
			ON `stc_product_grn_items_grn_order_id`=`stc_product_grn_id`
			LEFT JOIN `stc_brand`
			ON `stc_product_brand_id`=`stc_brand_id`
			WHERE `stc_item_inventory_pd_qty`!=0 
			ORDER BY `stc_product_desc` 
			ASC
			LIMIT ".$begval.",".$endval."
		");
		$productcount = '
			<table class="table table-hover" align="centre">
				<thead>
				  	<tr>
	            	  <th scope="col">Sl No.</th>
	            	  <th scope="col" style="width:30%;">Material Desc</th>
	            	  <th scope="col">Inv Qty.</th> 
	            	  <th scope="col">Unit.</th>
	            	  <th scope="col">Rack</th>
	            	  <th scope="col">Change Rack</th>
	            	  <th scope="col">Action</th>
	            	</tr>
				</thead>
				<tbody>
		';
		
		$loopcount=0;
		foreach($result as $row){
			$loopcount++;
			if($row["stc_sub_cat_name"] == 'OTHERS'){
				$subcat='';
			}else{
				$subcat=$row["stc_sub_cat_name"];
			}
			$brandtitle='';
			if(empty($row["stc_brand_title"])){
				$brandtitle='';
			}else{
				$brandtitle=' MAKE - '.$row["stc_brand_title"];
			}

			if(empty($row["stc_rack_name"])){
				$rackname="Not in Rack!!!";
			}else{
				$rackname=$row["stc_rack_name"];
			}
			$queryforprice_loki=mysqli_query($this->stc_dbs, "
				SELECT `stc_purchase_product_items_rate` 
				AS pdprice 
				FROM `stc_purchase_product_items` 
				WHERE `stc_purchase_product_items_product_id`='".$row["stc_product_id"]."' 
				ORDER BY `stc_purchase_product_items`.`stc_purchase_product_items_rate` 
				DESC
			");

			$pdprice = mysqli_fetch_assoc($queryforprice_loki);

			$slno++;
			$productcount .= '
					<tr>
	            	  <td>'.$slno.'</td>
	            	  <td>'.$subcat.' '.$row["stc_product_name"].$brandtitle.'</td>
	            	  <td align="right">'.number_format($row["stc_item_inventory_pd_qty"], 2).'</td>
	            	  <td>'.$row["stc_product_unit"].'</td>
	            	  <td>'.$rackname.'</td>
	            	  <td>
	            	  	<select
	                      class="custom-select stc-inventory-callrack"
	                      name="stcpdrack"
	                      id="stcpdrack'.$row["stc_product_id"].'" 
	                    ><option value="0" selected>Please select rack</option>
	                    </select>
	                   </td>
	                   <td>
	                    <a class="btn btn-success changerack" id='.$row["stc_product_id"].' href="#" style="font-size:20px;text-align:center;color:#fff;">
                    	  <i class="fas fa-check-circle"></i>
                    	</a>
	            	  </td>
	            	</tr>
			';

			if($loopcount==20){
				break;
			}
		}

		$productcount .= '
				<tr>
					<td colspan="3">
						<button type="button" class="btn btn-primary begbuttoninv">
							<i class="fas fa-arrow-left"></i>
						</button>
						<input type="hidden" class="begvalueinput" value="'.$begval.'">
					</td>
					<td colspan="4">
						<button type="button" class="btn btn-primary endbuttoninv" style="float:right;">
							<i class="fas fa-arrow-right"></i>
						</button>
						<input type="hidden" class="endvalueinput" value="'.$endval.'">
					</td>
				</tr>
			  	</tbody>
			</table>
		';
		return $productcount;
	}

	// rack change
	public function stc_edit_rack($pdid, $invrack){
		$odin='';
		$do_loki = mysqli_query($this->stc_dbs, "
			UPDATE `stc_product` SET `stc_product_rack_id`='".$invrack."' WHERE `stc_product_id`='".$pdid."' 
		");
		if($do_loki){
			$odin = "Rack Updated!!!";
		}else{
			$odin = "Hmmm!!! Something Went Wrong!!!";
		}
		return $odin;
	} 

	// filter by name in inventory
	public function stc_search_product_name($searchme, $begval, $endval){
		$slno=0;
		if($begval==0){
			$slno=0;
		}else{
			$slno=$begval;
		}
		$subcat='';
		$rackname='';
		$result=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_product_id`,
				`stc_product_name`,
				`stc_item_inventory_pd_qty`,
				`stc_product_unit`,
				`stc_sub_cat_name`,
				`stc_rack_name`,
				`stc_brand_title`
			FROM `stc_item_inventory`
			LEFT JOIN `stc_product`
			ON `stc_item_inventory_pd_id`=`stc_product_id`
			LEFT JOIN `stc_rack`
			ON `stc_product_rack_id`=`stc_rack_id`
			LEFT JOIN `stc_sub_category`
			ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
			LEFT JOIN `stc_product_grn_items`
			ON `stc_product_grn_items_product_id`=`stc_product_id`
			LEFT JOIN `stc_product_grn`
			ON `stc_product_grn_items_grn_order_id`=`stc_product_grn_id`
			LEFT JOIN `stc_brand`
			ON `stc_product_brand_id`=`stc_brand_id`
			WHERE (`stc_item_inventory_pd_qty`!=0) 
			AND (
				`stc_product_name` REGEXP '".$searchme."' OR
				`stc_product_desc` REGEXP '".$searchme."' OR
				`stc_rack_name` REGEXP '".$searchme."' OR 
				`stc_sub_cat_name` REGEXP '".$searchme."'
			) 
			ORDER BY `stc_product_desc` 
			DESC
			LIMIT ".$begval.",".$endval."
		");
		$productcount = '
			<table class="table table-hover" align="centre">
				<thead>
				  	<tr>
	            	  <th scope="col">Sl No.</th>
	            	  <th scope="col" style="width:30%;">Material Desc</th>
	            	  <th scope="col">Inv Qty.</th> 
	            	  <th scope="col">Unit.</th>
	            	  <th scope="col">Rack</th>
	            	  <th scope="col">Change Rack</th>
	            	  <th scope="col">Action</th>
	            	</tr>
				</thead>
				<tbody>
		';
		if(mysqli_num_rows($result)==0){
			$productcount.= "
				<tr>
		        	<td colspan='7'>No Records Found!!!</td>
		        </tr>
			";
		}else{
			$loopcount=0;
			foreach($result as $row){
				$loopcount++;
				if($row["stc_sub_cat_name"] == 'OTHERS'){
					$subcat='';
				}else{
					$subcat=$row["stc_sub_cat_name"];
				}

				$brandtitle='';
				if(empty($row["stc_brand_title"])){
					$brandtitle='';
				}else{
					$brandtitle=' MAKE - '.$row["stc_brand_title"];
				}

				if(empty($row["stc_rack_name"])){
					$rackname="Not in Rack!!!";
				}else{
					$rackname=$row["stc_rack_name"];
				}
				$queryforprice_loki=mysqli_query($this->stc_dbs, "
					SELECT `stc_purchase_product_items_rate` 
					AS pdprice 
					FROM `stc_purchase_product_items` 
					WHERE `stc_purchase_product_items_product_id`='".$row["stc_product_id"]."' 
					ORDER BY `stc_purchase_product_items`.`stc_purchase_product_items_rate` 
					DESC
				");

				$pdprice = mysqli_fetch_assoc($queryforprice_loki);

				$slno++;
				$productcount .= '
						<tr>
		            	  	<td>'.$slno.'</td>
	            	  		<td>'.$subcat.' '.$row["stc_product_name"].$brandtitle.'</td>
		            	  	<td align="right">'.number_format($row["stc_item_inventory_pd_qty"], 2).'</td>
		            	  	<td>'.$row["stc_product_unit"].'</td>
		            	  	<td>'.$rackname.'</td>
		            	  	<td>
		            	  		<select
		                    	  class="custom-select stc-inventory-callrack"
		                    	  name="stcpdrack"
		                    	  id="stcpdrack'.$row["stc_product_id"].'" 
		                    	><option value="0" selected>Please select rack</option>
		                    	</select>
		                   	</td>
		                   	<td>
		                    	<a class="btn btn-success changerack" id='.$row["stc_product_id"].' href="#" 	style="font-size:20px;text-align:center;color:#fff;">
	                    		  <i class="fas fa-check-circle"></i>
	                    		</a>
		            	  	</td>
		            	</tr>
				';
				if($loopcount==20){
					break;
				}
			}
		}

		$productcount .= '
				<tr>
					<td colspan="3">
						<button type="button" class="btn btn-primary begbuttoninvsearch">
							<i class="fas fa-arrow-left"></i>
						</button>
						<input type="hidden" class="begvalueinput" value="0">
						<input type="hidden" class="begvalueinputsearch" value="'.$begval.'">
					</td>
					<td colspan="4">
						<button type="button" class="btn btn-primary endbuttoninvsearch" style="float:right;">
							<i class="fas fa-arrow-right"></i>
						</button>
						<input type="hidden" class="endvalueinput" value="20">
						<input type="hidden" class="endvalueinputsearch" value="'.$endval.'">
					</td>
				</tr>
			  	</tbody>
			</table>
		';
		return $productcount;
	}
}

#<--------------------------------------------------------------------------------------------------------->
#<--------------------------------------Object sections of inventory class--------------------------------->
#<--------------------------------------------------------------------------------------------------------->

// page load inventory call
if(isset($_POST["invent_call"])){	
	$out='';
	$objinventory=new ragnarInventory();
	$objrack=new ragnarInventory();

	$begval=$_POST['begvalue'];
	$endval=$_POST['endvalue'];
	$opobjinventory=$objinventory->stc_call_inventory($begval, $endval);
	$opobjrack=$objrack->call_rack();

	$out= array(
		'inventory_items' => $opobjinventory,
		'rack' => $opobjrack
	);
	echo json_encode($out);
	// echo $out;
}

// rack change inventory call
if(isset($_POST['inventchange_rack'])){
	$pdid = $_POST['pdid_edit_rck'];
	$invrack = $_POST['rack_id'];
	$objinventory=new ragnarInventory();

	$opobjinventory=$objinventory->stc_edit_rack($pdid, $invrack);

	// echo json_encode($opobjinventory);
	echo $opobjinventory;
}

// filter by name
if(isset($_POST['search_alo_in'])){	
	$searchme=$_POST['search_alo_in'];
	$begval=$_POST['begvalue'];
	$endval=$_POST['endvalue'];
	$objinventory=new ragnarInventory();
	$objrack=new ragnarInventory();

	$opobjinventory=$objinventory->stc_search_product_name($searchme, $begval, $endval);
	$opobjrack=$objrack->call_rack();

	$out= array(
		'inventory_items_byname' => $opobjinventory,
		'rack' => $opobjrack
	);
	echo json_encode($out);
	// echo $out;

}
?>