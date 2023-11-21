<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents ----------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class transformers extends tesseract{	

	public function stc_load_cart_ag(){
		$sl=1;
		$order_table='
			<table class="mb-0 table table-hover">
    	    	<thead>
    	    	    <th style="width:50%;">Item Desc</th>
    	    	    <th>Quantity</th>
    	    	    <th>Total</th>
    	    	    <th>Action</th>
    	    	</thead>
    	    	<tbody>
		';
		if(!empty($_SESSION["stc_agent_cart_sess"])){  		
			foreach($_SESSION["stc_agent_cart_sess"] as $cartpditem){
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

				$pdname='';
				if (strlen($bumblebeetakeit['stc_product_name']) >= 30) {
				    $pdname=substr($bumblebeetakeit['stc_product_name'], 0, 30);
				    $pdname=$pdname.'...';
				}else{
				    $pdname=$bumblebeetakeit['stc_product_name'];
				}

				$order_table.='
					<tr>
						<td>
							<p>'.$subcat.' '.$pdname.'</p>
							<b>'.$cartpditem['product_price'].'/'.$bumblebeetakeit['stc_product_unit'].'</b>
						</td>
						<td>
							<input 
								type="text" 
								class="form-control" 
								value="'.round($cartpditem['product_quantity'], 2).'" 
								id="itemqty'.$cartpditem['product_id'].'"
							>
							<a href="#" id="'.$cartpditem['product_id'].'" class="updateitem">
								<span style="font-size: 15px;background: yellow;border-radius: 11px;padding: 5px;">
									<i class="fas fa-arrow-right"></i>
								</span>
							</a>
						</td>
						<td align="right">'.round($cartpditem['product_quantity'] * $cartpditem['product_price'], 2).'</td>
						<td><a href="#" class="btn btn-success removitems" id="'.$cartpditem['product_id'].'"><i class="fas fa-trash"></i></a></td>
					</tr>
				';
				$total=$cartpditem['product_quantity'] * $cartpditem['product_price'];
				$sl++;
			}

			$order_table.='
				<tr>
					<td colspan="1"></td>
					<td><b>Total</b></td>
					<td colspan="2">: '.$total.'</td>
				</tr>
			';
		}else{
			$order_table.='
					<tr>
						<td colspan="5" align="center">Cart Empty!!!</td>
					</tr>
			';
		}
		$order_table.='
				</tbody>
			</table>
		';


		// $export=array(
		//   "orderTable" => $order_table		  
		// ); 
		return $order_table;
	}

	// public function stc_call_cust_for_agent(){
	// 	$optimusprimequery=mysqli_query($this->stc_dbs, "
	// 		SELECT `stc_customer_id`,`stc_customer_name` FROM `stc_customer`
	// 		INNER JOIN `stc_agent_requested_customer`
	// 		ON `stc_customer_id`=`stc_agent_requested_customer_cust_id`
	// 		WHERE `stc_agent_requested_customer_agent_id`='".$_SESSION['stc_agent_id']."'
	// 	");
	// 	$optimusprime='<option value="NA" selected>Select Customer</option>';
	// 	$do_action=mysqli_num_rows($optimusprimequery);
	// 	if($do_action == 0){
	// 		$optimusprime = "<option value='NA' selected>No Customer Found !!</option>";
	// 	}else{
	// 		foreach ($optimusprimequery as $row) {
	// 			$optimusprime.='
	// 						<option value="'.$row["stc_customer_id"].'">'.$row["stc_customer_name"].'</option>		               	
	// 	            	';				
	// 		}
			
	// 	}
	// 	return $optimusprime;
	// }

	public function stc_agent_order_add(){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_own_agent_order`(
				`stc_own_agent_order_date`, 
				`stc_own_agent_order_agent_id`, 
				`stc_own_agent_order_status`, 
				`stc_own_agent_order_accepted_by`
			) VALUES (
				NOW(),
				'".$_SESSION['stc_agent_id']."',
				'PROCESS',
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
		$optimusprimegetid=mysqli_query($this->stc_dbs, "SELECT MAX(`stc_own_agent_order_id`) as orderid FROM `stc_own_agent_order` ORDER BY `stc_own_agent_order_id` DESC");
		$getorderid=mysqli_fetch_assoc($optimusprimegetid);
		foreach($_SESSION['stc_agent_cart_sess'] as $getitems){
			$optimusprimegoitems=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_own_agent_order_items`(
					`stc_own_agent_order_items_order_id`, 
					`stc_own_agent_order_items_pd_id`, 
					`stc_own_agent_order_items_pd_qty`, 
					`stc_own_agent_order_items_pd_price`
				) VALUES (
					'".$getorderid['orderid']."',
					'".$getitems['product_id']."',
					'".$getitems['product_quantity']."',
					'".$getitems['product_price']."'
				)
			");
		}
		$optimusprime="success";
		return $optimusprime;
	}
}

if(isset($_POST['prodsuccess'])){
	if($_POST["prod_qty"]==0){
		echo "Please Add Quantity First!!!";		
	}else{
		if(isset($_SESSION["stc_agent_cart_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_agent_cart_sess"] as $keys => $values) {  
				if($_SESSION["stc_agent_cart_sess"][$keys]['product_id'] == $_POST["prod_id"]) {  
				     $is_available++;  
				     $_SESSION["stc_agent_cart_sess"][$keys]['product_quantity'] = $_SESSION["stc_agent_cart_sess"][$keys]['product_quantity'] + $_POST["prod_qty"];  
				     echo "Cart Item Quantity Increased!!!";
				}  
			}  
			if($is_available < 1) {  
				$item_array = array(  
				     'product_id'             =>     $_POST["prod_id"],  
				     'product_quantity'       =>     $_POST["prod_qty"],
				     'product_price'       =>     $_POST["prod_price"]
				);  
				$_SESSION["stc_agent_cart_sess"][] = $item_array;  
				echo "Created & Item Added to Cart!!!";
			}  
		}else{  
			$item_array = array(  
				     'product_id'             =>     $_POST["prod_id"],  
				     'product_quantity'       =>     $_POST["prod_qty"],
				     'product_price'       =>     $_POST["prod_price"]
			);   
			$_SESSION["stc_agent_cart_sess"][] = $item_array;  
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
	foreach($_SESSION["stc_agent_cart_sess"] as $keys => $values){  
		if($values["product_id"] == $prod_id){  
			unset($_SESSION["stc_agent_cart_sess"][$keys]);  
			echo "Item Removed!!!";  
		}  
	} 
}

if(isset($_POST["updateitems"])){  
  	foreach($_SESSION["stc_agent_cart_sess"] as $keys => $values){  
	    if($_SESSION["stc_agent_cart_sess"][$keys]['product_id'] == $_POST["items_id"]){  
	         $_SESSION["stc_agent_cart_sess"][$keys]['product_quantity'] = $_POST["pd_qty"];
    	}  
  	}  
  	echo "Quanty Updated!!!";
}

// if(isset($_POST['load_cust'])){
// 	$bumblebee=new transformers();
// 	$outbumblebee=$bumblebee->stc_call_cust_for_agent();
// 	echo $outbumblebee;
// }

if(isset($_POST['order_add'])){
	$out='';

	$bumblebee=new transformers();
	$megatron=new transformers();

	if(empty($_SESSION['stc_agent_cart_sess'])){
		$out="Please Buy Something!!!";
	}else{
		$outbumblembee=$bumblebee->stc_agent_order_add();
		if($outbumblembee=="success"){
			$outmegatron=$megatron->stc_agent_order_items_add();
    			
			if($outmegatron=="success"){
			    $out.="Thank You!!!Your Order Will Be Placed ASAP.";
			    unset($_SESSION['stc_agent_cart_sess']);
    			// mail("stc111213@gmail.com", "Order Alert", "Material Ke liye Order aaya hai!!!:)", "info@stcassociate.com");
			}else{
			    $out.="Items not inserted.";
			}
		}else{
			$out.="Something Went Wrong!!! Please Try Again Later.";
		}
		// $out;
	}
	echo $out;
}
?>