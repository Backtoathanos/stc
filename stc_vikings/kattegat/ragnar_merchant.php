<?php
include "../../MCU/obdb.php";
class ragnarMerchants extends tesseract{
	// by merchant name
	public function stc_search_merchant_byname($searchmebyname){
		$perfectsearchme=strtoupper($searchmebyname);
		$alagsequery="
					SELECT DISTINCT * FROM `stc_merchant` 
					LEFT JOIN `stc_state` ON `stc_state_id`=`stc_merchant_state_id` 
					LEFT JOIN `stc_city` ON `stc_city_id`=`stc_merchant_city_id` 
					WHERE 
						`stc_merchant_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchmebyname)."' OR 
						`stc_merchant_address` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchmebyname)."'
					ORDER BY `stc_merchant_id` ASC
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_merchant_name"]);
				$odin.='	
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
			             	<div style="padding: 15px; background-color: #fff;border-radius:20px;">
			             		<div class="row">
				             	  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				             	  		<h5>Merchant Name - '.$row['stc_merchant_name'].'</h5>
				             	  	</div>
				             	  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				             	  		<p>Address - '.$row['stc_merchant_address'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>Email - '.$row['stc_merchant_email'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>Contact - '.$row['stc_merchant_phone'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>GSTIN - '.$row['stc_merchant_gstin'].'</p>
				             	  	</div>
			             	 	</div>
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
			              No Records Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}

	// by merchant skf
	public function stc_search_merchant_byskf($searchmebyskf){
		$perfectsearchme=strtoupper($searchmebyskf);
		$alagsequery="
					SELECT DISTINCT * FROM `stc_merchant` 
					LEFT JOIN `stc_state` ON `stc_state_id`=`stc_merchant_state_id` 
					LEFT JOIN `stc_city` ON `stc_city_id`=`stc_merchant_city_id` 
					WHERE `stc_merchant_specially_known_for` REGEXP '".$searchmebyskf."' ORDER BY `stc_merchant_id` ASC
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_merchant_name"]);
				$odin.='	
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
			             	<div style="padding: 15px; background-color: #fff;border-radius:20px;">
			             		<div class="row">
				             	  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				             	  		<h5>Merchant Name - '.$row['stc_merchant_name'].'</h5>
				             	  	</div>
				             	  	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				             	  		<p>Address - '.$row['stc_merchant_address'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>Email - '.$row['stc_merchant_email'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>Contact - '.$row['stc_merchant_phone'].'</p>
				             	  	</div>
				             	  	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
				             	  		<p>GSTIN - '.$row['stc_merchant_gstin'].'</p>
				             	  	</div>
			             	 	</div>
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
			              No Records Found!!!
			            </a>
					</div>

					';
		}
		$odin.='';
		return $odin;
	}

	// vendor add to table
	public function stc_vendor_hit($stcmername, $stcmeraddress, $stcmercity, $stcmerstate, $stcmercontperson, $stcmeremail, $stcmercontnumber, $stcmerskf, $stcmerpan, $stcmergst, $stcmerimages){
		$stc_filter_add_merchant=mysqli_real_escape_string($this->stc_dbs, $stcmername);
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_merchant` 
			WHERE `stc_merchant_name`='".mysqli_real_escape_string($this->stc_dbs, $stc_filter_add_merchant)."'
			OR `stc_merchant_pan`='".mysqli_real_escape_string($this->stc_dbs, $stcmerpan)."'
			OR `stc_merchant_gstin`='".mysqli_real_escape_string($this->stc_dbs, $stcmergst)."'
		");
		$count_loki_out=mysqli_num_rows($check_loki);
		if($count_loki_out == 0){
			$loki_query=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_merchant`(
					`stc_merchant_name`, 
					`stc_merchant_address`, 
					`stc_merchant_city_id`, 
					`stc_merchant_state_id`, 
					`stc_merchant_contact_person`, 
					`stc_merchant_email`, 
					`stc_merchant_phone`, 
					`stc_merchant_pan`, 
					`stc_merchant_gstin`, 
					`stc_merchant_specially_known_for`, 
					`stc_merchant_image`
				) VALUES(
					'".$stc_filter_add_merchant."', 
					'".$stcmeraddress."', 
					'".$stcmercity."', 
					'".$stcmerstate."', 
					'".$stcmercontperson."', 
					'".$stcmeremail."', 
					'".$stcmercontnumber."', 
					'".$stcmerpan."', 
					'".$stcmergst."', 
					'".$stcmerskf."', 
					'".$stcmerimages."'
				)
			");
			if($loki_query){
				$odin = "success";
			}else{
				$odin = "You Need to Try Again!!";
			}
		}else{
			$odin = "This Product Is Already In Record!!";
		}
		return $odin;
	}

	// call state
	public function call_state(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_state` ORDER BY `stc_state`.`stc_state_name` ASC");
		$odin='<option selected>Select State</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option selected>No State Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
				if($row["stc_state_id"]==16){
					$odin.='
								<option selected value="'.$row["stc_state_id"].'">'.$row["stc_state_name"].'</option>		               	
			            	';		
		        }else{
		        		$odin.='
								<option value="'.$row["stc_state_id"].'">'.$row["stc_state_name"].'</option>		               	
			            	';	
		        }		
			}
			
		}
		return $odin;
	}

	// call city
	public function call_city(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_city` ORDER BY `stc_city`.`stc_city_name` ASC");
		$odin='<option selected>Select City</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option selected>No City Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
		        if($row["stc_city_id"]==65){
					$odin.='
								<option selected value="'.$row["stc_city_id"].'">'.$row["stc_city_name"].'</option>		               	
			            	';		
		        }else{
		        		$odin.='
								<option value="'.$row["stc_city_id"].'">'.$row["stc_city_name"].'</option>		               	
			            	';		
		        }			
			}
			
		}
		return $odin;
	}

	public function stc_call_merchant_items($mer_id){
		$odin='';
		$odin_getqry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_product_id`,
				`stc_product_name`,
				`stc_cat_name`,
				`stc_sub_cat_name`   
			FROM
				`stc_product`
			INNER JOIN 
				`stc_category` 
			ON 
				`stc_cat_id`=`stc_product_cat_id` 
			INNER JOIN 
				`stc_sub_category` 
			ON 
				`stc_sub_cat_id`=`stc_product_sub_cat_id` 
			INNER JOIN 
				`stc_purchase_product_items` 
			ON 
				`stc_purchase_product_items_product_id`=`stc_product_id`
			INNER JOIN 
				`stc_purchase_product` 
			ON 
				`stc_purchase_product_id`=`stc_purchase_product_items_order_id`
			INNER JOIN 
				`stc_merchant` 
			ON 
				`stc_merchant_id`=`stc_purchase_product_merchant_id`
			WHERE 
				`stc_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $mer_id)."'
			ORDER BY `stc_product_name` ASC
		");
		if(mysqli_num_rows($odin_getqry)>0){
			foreach($odin_getqry as $odin_getrow){

				$sub_cat= ($odin_getrow['stc_sub_cat_name']=="OTHERS") ? "" : $odin_getrow['stc_sub_cat_name'];

				$odinallpoqry=mysqli_query($this->stc_dbs, "
					SELECT
						SUM(`stc_purchase_product_items_qty`) as total_po_qty
					FROM
						`stc_purchase_product_items`
					WHERE
						`stc_purchase_product_items_product_id` ='".$odin_getrow['stc_product_id']."'
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
						`stc_purchase_product_items_product_id` ='".$odin_getrow['stc_product_id']."'
					AND 
						`stc_purchase_product_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $mer_id)."'
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
						`stc_product_grn_items_product_id` ='".$odin_getrow['stc_product_id']."'
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
						`stc_product_grn_items_product_id` ='".$odin_getrow['stc_product_id']."'
					AND 
						`stc_product_grn_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $mer_id)."'
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
						`stc_item_inventory_pd_id`='".$odin_getrow['stc_product_id']."'
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
						`stc_sale_product_items_product_id`='".$odin_getrow['stc_product_id']."'
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
						`stc_electronics_inventory_item_id`='".$odin_getrow['stc_product_id']."'
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
						`stc_sale_product_silent_challan_items_product_id`='".$odin_getrow['stc_product_id']."'
				");

				$echallan_qty= 0;
				foreach($odinechallanqry as $odinechallanrow){
					$echallan_qty=$odinechallanrow['echallan_qty'];
				}

				$odin.='
					<tr>
						<td class="text-center">'.$odin_getrow['stc_product_id'].'</td>
						<td>'.$sub_cat.' '.$odin_getrow['stc_product_name'].' '.$odin_getrow['stc_product_name'].'</td>
						<td class="text-right">'.number_format($total_po_qty, 2).'</td>
						<td class="text-right">'.number_format($merchant_po_qty, 2).'</td>
						<td class="text-right">'.number_format($allgrn_qty, 2).'</td>
						<td class="text-right">'.number_format($grn_qty, 2).'</td>
						<td class="text-right">'.number_format($inv_qty, 2).'</td>
						<td class="text-right">'.number_format($challan_qty, 2).'</td>
						<td class="text-right">'.number_format($einv_qty, 2).'</td>
						<td class="text-right">'.number_format($echallan_qty, 2).'</td>
					</tr>
				';
			}
		}else{
			$odin.='
				<tr>
					<td>No Record Find!!</td>
				</tr>
			';
		}
		return $odin;
	}
}

#<------------------------------------------------------------------------------------------------------>
#<--------------------------------------Object sections of Merchant class-------------------------------->
#<------------------------------------------------------------------------------------------------------>
// by merchant name
if(isset($_POST['search_mer_byname_var_in'])){
	$out='';
	$searchmebyname=$_POST['search_mer_byname_var_in'];
	if(strlen($searchmebyname)<=3){
		$out='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			      Enter At least a word of 3 characters!!!
			    </a>
			</div>
		';
	}else{
		$objloki=new ragnarMerchants();
		$objlokiout=$objloki->stc_search_merchant_byname($searchmebyname);
		$out=$objlokiout;
	}
	echo json_encode($out);
}

// by merchant specially known for
if(isset($_POST['search_mer_var_byskf_var_in'])){
	$out='';
	$searchmebyskf=$_POST['search_mer_var_byskf_var_in'];
	if(strlen($searchmebyskf)<=3){
		$out='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			      Enter At least a word of 3 characters!!!
			    </a>
			</div>
		';
	}else{
		$objloki=new ragnarMerchants();
		$objlokiout=$objloki->stc_search_merchant_byskf($searchmebyskf);
		$out=$objlokiout;
	}
	echo json_encode($out);
}

// input merchant
if(isset($_POST['stc_add_merchant_hit'])){
	$stcmername=strtoupper($_POST['stcmername']);
	$stcmeraddress=strtoupper($_POST['stcmeraddress']);
	$stcmercity=$_POST['stcmercity'];
	$stcmerstate=$_POST['stcmerstate'];
	$stcmercontperson=$_POST['stcmercontperson'];
	$stcmeremail=$_POST['stcmeremail'];
	$stcmercontnumber=$_POST['stcmercontnumber'];
	$stcmerskf=$_POST['stcmerskf'];
	$stcmerpan=strtoupper($_POST['stcmerpan']);
	$stcmergst=strtoupper($_POST['stcmergstin']);
	$stcmerimages='NA';

	$adago=new ragnarMerchants();

	if(empty($stcmername) || empty($stcmeraddress) || empty($stcmeremail) || empty($stcmercontnumber) || empty($stcmerpan) || empty($stcmergst)){
		echo "Please Fill All Fields!!!";
	}else{
		// function calling
		$objadago=$adago->stc_vendor_hit($stcmername, $stcmeraddress, $stcmercity, $stcmerstate, $stcmercontperson, $stcmeremail, $stcmercontnumber, $stcmerskf, $stcmerpan, $stcmergst, $stcmerimages);

		if($objadago == "success"){	
			echo "Merchant added!!";
		}else{
			echo $objadago;
		}
	}
}

// call state on merchants
// call to the product page
if(isset($_POST['indialocation'])){
	$indialocationstate=new ragnarMerchants();
	$indialocationcity=new ragnarMerchants();

	$outindialocationstate=$indialocationstate->call_state();
	$outindialocationcity=$indialocationcity->call_city();

	$mount=array($outindialocationcity, $outindialocationstate);
	echo json_encode($mount);
}

// call merchant
if(isset($_POST['stc_get_merchant_item'])){
	$out='';
	$mer_id=$_POST['mer_id'];
	if($mer_id=="NA"){
		$out='
			<tr>
				<td>
				</td>
			</tr>
		';
	}else{
		$objloki=new ragnarMerchants();
		$objlokiout=$objloki->stc_call_merchant_items($mer_id);
		$out=$objlokiout;
	}
	echo $out;
}
?>