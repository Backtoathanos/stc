<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents ----------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class vergil extends tesseract{
	public function stc_load_product_ag($pd_name){
		$perfectsearchme=strtoupper($pd_name);
		$purchase_rate='';
		$alagsequery="
					SELECT * FROM `stc_own_agent_inventory`
					INNER JOIN `stc_product` 
					ON `stc_own_agent_inventory_pd_id`=`stc_product_id` 
					INNER JOIN `stc_category` 
					ON `stc_cat_id`=`stc_product_cat_id` 
					INNER JOIN `stc_sub_category` 
                    ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
                    WHERE (
						`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."' 
						OR 
						`stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $perfectsearchme)."'
					)
					AND `stc_own_agent_inventory_pd_qty`>0
					ORDER BY `stc_product_id` 
					ASC LIMIT 0,15
		";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$odin.='	
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
		             	  <div class="row">
		             	    <div class="el-wrapper">
		             	      <div class="box-up">
		             	        <img class="img" style="height: 100%; width:100%;" src="../stc_symbiote/stc_product_image/'.$row["stc_product_image"].'" alt="">
		             	        <div class="img-info">
			             	        <div class="info-inner">
			             	            <span class="p-name">'.htmlentities($row["stc_product_name"]).'</span>
			             	            <span class="p-company">'.$row["stc_cat_name"].'</span>
			             	            <span class="p-qty" >'.$row["stc_own_agent_inventory_pd_qty"].'/'.$row["stc_product_unit"].'</span>
			             	           <input
					                	  id="stcpoqty'.$row["stc_product_id"].'"
					                	  name="stcpoqty"
					                	  type="number"
					                	  placeholder="Quantity"
					                	  class="form-control validate"
		             	         		style="color:black;font-weight:bold;text-align:center"
					                	/>            
			             	        </div>
		             	         	<input 
		             	         		type="hidden" 
		             	         		name="hidden_unit" 
		             	         		id="stcinvqty'.$row["stc_product_id"].'" 
		             	         		value="'.$row["stc_own_agent_inventory_pd_qty"].'" 
		             	         	/>
		             	         	<input 
		             	         		type="hidden" 
		             	         		name="hidden_hsncode" 
		             	         		id="stcpopdhsncode'.$row["stc_product_id"].'" 
		             	         		value="'.$row["stc_product_hsncode"].'" 
		             	         	/>
		             	         	<input 
		             	         		type="hidden" 
		             	         		name="hidden_gst" 
		             	         		id="stcpopdgst'.$row["stc_product_id"].'" 
		             	         		value="'.$row["stc_product_gst"].'" 
		             	         	/>
		             	         	<div class="a-size">
					                	<input 
				             	          	type="number" 
				             	          	name="stcpdprice" 
				             	          	id="stcpdprice'.$row["stc_product_id"].'" 
				             	          	placeholder="Enter Amount"
				             	          	class="form-control validate"
		             	         			style="color:black;font-weight:bold;text-align:center"
				             	          />	
		             	          	</div>
		             	        </div>
		             	      </div>

		             	      <div class="box-down">
		             	        <div class="h-bg">
		             	          <div class="h-bg-inner"></div>
		             	        </div>

		             	        <a class="cart add_to_ag_cust" href="#" id="'.$row["stc_product_id"].'">
		             	          <span class="price">'.$row["stc_sub_cat_name"].'</span>
		             	          <span class="add-to-cart">
		             	            <span class="txt">Add Item</span>
		             	          </span>
		             	        </a>
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

	public function stc_load_cart_ag(){
		$odin='
			<table class="table">
        		<thead>
        		    <th>Item Desc</th>
        		    <th>Total</th>
        		    <th>Action</th>
        		</thead>
        		<tbody>
		';
		if(empty($_SESSION["stc_agent_sale_cart_sess"])){
			$odin.='
			<tr>
				<td>
					Cart empty!!!
				</td>
			</tr>
			';
		}else{
			foreach($_SESSION["stc_agent_sale_cart_sess"] as $row){
				$qry=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_product`
					INNER JOIN `stc_sub_category`
					ON `stc_sub_cat_id`=`stc_product_sub_cat_id`
					WHERE `stc_product_id`='".$row['product_id']."'
				");
				foreach($qry as $agrow){
					$scat=$agrow['stc_sub_cat_name'];
					if($scat=="OTHERS"){
						$scat='';
					}
					$total=$row['product_quantity'] * $row['product_price'];
					$odin.='
						<tr>
							<td>
								'.$scat.' '.$agrow['stc_product_name'].'<br>
								<b>'.number_format($row['product_quantity'], 2).' X '.number_format($row['product_price'], 2).'/'.$agrow['stc_product_unit'].'</b>
							</td>
							<td>'.number_format($total, 2).'</td>
							<td>
								<a href="#" style="font-size:25px;color:red;" id="'.$row['product_id'].'" class="rem_pd_cart">
									<i class="pe-7s-close-circle"></i>
								</a>
							</td>
						</tr>
					';
				}
			}
		}
		$odin.='
				</tbody>
			</table>
		';
		return $odin;
	}

	public function stc_goes_to_ag_cust_sale($cust_name, $cust_cont, $cust_address, $cust_whatsapp){
		$nero='';
		$neroquery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_own_agent_sale`(
				`stc_own_agent_sale_cust_name`, 
				`stc_own_agent_sale_cust_address`, 
				`stc_own_agent_sale_cust_contact`, 
				`stc_own_agent_sale_cust_whatsapp`
			) VALUES (
				'".$cust_name."',
				'".$cust_cont."',
				'".$cust_address."',
				'".$cust_whatsapp."'
			)
		");
		if($neroquery){
			$nero.="success";
		}
		return $nero;
	}

	public function stc_goes_to_ag_cust_sale_items(){
		$nero='';
		$nerogetid=mysqli_query($this->stc_dbs, "SELECT MAX(`stc_own_agent_sale_id`) AS orderid FROM `stc_own_agent_sale` ORDER BY `stc_own_agent_sale_id` DESC");
		$getorderid=mysqli_fetch_assoc($nerogetid);
		foreach($_SESSION['stc_agent_sale_cart_sess'] as $getitems){
			$nerogoitems=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_own_agent_sale_items`(
					`stc_own_agent_sale_items_ord_id`, 
					`stc_own_agent_sale_items_pd_id`,
					`stc_own_agent_sale_items_pd_qty`, 
					`stc_own_agent_sale_items_pd_price`
				) VALUES (
					'".$getorderid['orderid']."',
					'".$getitems['product_id']."',
					'".$getitems['product_quantity']."',
					'".$getitems['product_price']."'
				)
			");

			$nerogetinvitems=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_own_agent_inventory` WHERE `stc_own_agent_inventory_pd_id`='".$getitems['product_id']."'
			");

			$invqty=0;
			foreach($nerogetinvitems as $items){
				$invqty+=$items['stc_own_agent_inventory_pd_qty'];
			}
			$mainqty = $invqty - $getitems['product_quantity'];
			$neroupdateitems=mysqli_query($this->stc_dbs, "
				UPDATE `stc_own_agent_inventory` 
				SET `stc_own_agent_inventory_pd_qty`='".$mainqty."' 
				WHERE `stc_own_agent_inventory_pd_id`='".$getitems['product_id']."'
			");
		}
		unset($_SESSION['stc_agent_sale_cart_sess']);
		$nero="success";
		return $nero;
	}
}

if(isset($_POST['search_pd'])){
	$pd_name=$_POST['pd_name'];
	$objlogin=new vergil();
	$opobjlogin=$objlogin->stc_load_product_ag($pd_name);
	$out = $opobjlogin;
	echo $out;
}

if(isset($_POST['add_to_c_ag'])){
	if($_POST["pd_qty"]==0){
		echo "Please Add Quantity First!!!";		
	}else{
		if(isset($_SESSION["stc_agent_sale_cart_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_agent_sale_cart_sess"] as $keys => $values) {  
				if($_SESSION["stc_agent_sale_cart_sess"][$keys]['product_id'] == $_POST["id"]) {  
				     $is_available++;  
				     $_SESSION["stc_agent_sale_cart_sess"][$keys]['product_quantity'] = $_SESSION["stc_agent_sale_cart_sess"][$keys]['product_quantity'] + $_POST["pd_qty"];  
				     echo "Cart Item Quantity Increased!!!";
				}  
			}  
			if($is_available < 1) {  
				$item_array = array(  
				     'product_id'             =>     $_POST["id"],  
				     'product_quantity'       =>     $_POST["pd_qty"],
				     'product_price'       =>     $_POST["pd_price"]
				);  
				$_SESSION["stc_agent_sale_cart_sess"][] = $item_array;  
				echo "Created & Item Added to Cart!!!";
			}  
		}else{  
			$item_array = array(  
				     'product_id'             =>     $_POST["id"],  
				     'product_quantity'       =>     $_POST["pd_qty"],
				     'product_price'       =>     $_POST["pd_price"]
			);   
			$_SESSION["stc_agent_sale_cart_sess"][] = $item_array;  
			echo "Item Added to Cart!!!";
		}
	}  
}

if(isset($_POST['load_acart_ag'])){
	$objlogin=new vergil();
	$opobjlogin=$objlogin->stc_load_cart_ag();
	$out = $opobjlogin;
	echo $out;
}

if(isset($_POST['rem_fro_cart'])){
	$prod_id=$_POST['items_id'];
	foreach($_SESSION["stc_agent_sale_cart_sess"] as $keys => $values){  
		if($values["product_id"] == $prod_id){  
			unset($_SESSION["stc_agent_sale_cart_sess"][$keys]);  
			echo "Item Removed!!!";  
		}  
	} 
}

if(isset($_POST["updateitems"])){  
  	foreach($_SESSION["stc_agent_sale_cart_sess"] as $keys => $values){  
	    if($_SESSION["stc_agent_sale_cart_sess"][$keys]['product_id'] == $_POST["items_id"]){  
	         $_SESSION["stc_agent_sale_cart_sess"][$keys]['product_quantity'] = $_POST["pd_qty"];
    	}  
  	}  
  	echo "Quanty Updated!!!";
}
if(isset($_POST['cust_goes_on'])){
	$out='';
	$cust_name=$_POST['cust_name'];
	$cust_cont=$_POST['cust_cont'];
	$cust_address=$_POST['cust_address'];
	$cust_whatsapp=$_POST['cust_whatsapp'];

	$objcustsale=new vergil();
	$objcustsalea=new vergil();

	if(empty($_SESSION["stc_agent_sale_cart_sess"])){
		$out.="Please enter item first!!!";
	}elseif(empty($cust_name) || empty($cust_cont) || empty($cust_address) || empty($cust_whatsapp)){
		$out.="Please fill all fields!!!";
	}else{		
		$opobjcustsale=$objcustsale->stc_goes_to_ag_cust_sale($cust_name, $cust_cont, $cust_address, $cust_whatsapp);
		if($opobjcustsale=="success"){
			$opobjcustsalea=$objcustsalea->stc_goes_to_ag_cust_sale_items();
			if($opobjcustsalea=="asuccess"){				
				$out.="Thankyou!!!";
			}
		}
	}
	echo $out;
}
?>