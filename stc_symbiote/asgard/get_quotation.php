<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Quotation ----------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class eyeofagamotto extends tesseract{	

	// call vendor on Vendor Quotation page
	public function stc_call_vendor_for_quote(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_customer`");
		$odin='<option value="0" selected>SELECT CUSTOMER</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option selected>NO CUSTOMER FOUND !!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_customer_id"].'">'.$row["stc_customer_name"].'</option>		               	
		            	';				
			}
			
		}
		return $odin;
	}

	// call product on Vendor Quote page
	public function stc_search_quote_product_name($searchme){
		$perfectsearchme=strtoupper($searchme);
		$alagsequery="
					SELECT DISTINCT * FROM `stc_product` 
					LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
					LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
					LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
					LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
					WHERE `stc_product_name` REGEXP '".$perfectsearchme."' ORDER BY `stc_product_id` ASC
					";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';               
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$correct_stcpdname=strlen($row["stc_product_name"]);
				$stcqty=$row["stc_item_inventory_pd_qty"];
				if($stcqty==0){
					$stcqty=0;
				}else{
					$stcqty;
				}
				$odin.='	
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
		             	  <div class="row">
		             	    <div class="el-wrapper">
		             	      <div class="box-up">
		             	        <img class="img" style="height: 100%; width:100%;" src="stc_product_image/'.$row["stc_product_image"].'" alt="">
		             	        <div class="img-info">
		             	          <div class="info-inner">
		             	            <span class="p-name">'.htmlentities($row["stc_product_name"]).'</span>
		             	            <span class="p-company">'.$row["stc_cat_name"].'</span>
		             	            <span class="p-qty" >'.$stcqty.'</span>
		             	         	</div>
		             	         	<input 
		             	         		type="hidden" 
		             	         		name="hidden_name" 
		             	         		id="stcquotepdname'.$row["stc_product_id"].'" 
		             	         		value="'.htmlentities($row["stc_product_name"]).'" 
		             	         	/>
		             	         	<input 
		             	         		type="hidden" 
		             	         		name="hidden_unit" 
		             	         		id="stcquotepdunit'.$row["stc_product_id"].'" 
		             	         		value="'.$row["stc_product_unit"].'" 
		             	         	/>
		             	         	<input 
		             	         		type="hidden" 
		             	         		name="hidden_hsncode" 
		             	         		id="stcquotepdhsncode'.$row["stc_product_id"].'" 
		             	         		value="'.$row["stc_product_hsncode"].'" 
		             	         	/>
		             	         	<input 
		             	         		type="hidden" 
		             	         		name="hidden_gst" 
		             	         		id="stcquotepdgst'.$row["stc_product_id"].'" 
		             	         		value="'.$row["stc_product_gst"].'" 
		             	         	/>
		             	         	<div class="a-size">
			             	        	<input
					                	  id="stcquoteqty'.$row["stc_product_id"].'"
					                	  name="stcpoqty"
					                	  type="number"
					                	  placeholder="Quantity"
					                	  class="form-control validate"
					                	/>
		             	          	</div>
		             	        </div>
		             	      </div>

		             	      <div class="box-down">
		             	        <div class="h-bg">
		             	          <div class="h-bg-inner"></div>
		             	        </div>

		             	        <a class="cart add_to_cart_for_vendor_quote" href="#" id="'.$row["stc_product_id"].'">
		             	          <span class="price">'.$row["stc_sub_cat_name"].'</span>
		             	          <span class="add-to-cart">
		             	            <span class="txt">ADD ITEM</span>
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

	// save purchase Vendor Quotation
	public function stc_save_vendor_quote($stcvendormerchantidquote, $stcvendortermandcond, $stcvendorquotesnotes){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_quotation`(
				`stc_quotation_date`, 
				`stc_quotation_customer_id`, 
				`stc_quotation_tandc`, 
				`stc_quotation_notes`, 
				`stc_quotation_created_by`
			) VALUES (
				NOW(), 
				'".$stcvendormerchantidquote."', 
				'".mysqli_real_escape_string($this->stc_dbs, $stcvendortermandcond)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $stcvendorquotesnotes)."', 
				'".$_SESSION['stc_admin_info_id']."'
			)
		");
		if($friday_loki){
			$odin = "Success";
		}else{
			$odin = "Not Ok!!";
		}
		return $odin;
	}

	// save Vendor Quoation items
	public function stc_save_qutation_items(){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_quotation` WHERE `stc_quotation_created_by`='".$_SESSION['stc_admin_info_id']."' ORDER BY `stc_quotation`.`stc_quotation_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_quotation_id'];
		if(empty($_SESSION['stc_vendor_quote_sess'])){
			$odin = "Please Add Item First!!";
		}else{
			foreach ($_SESSION["stc_vendor_quote_sess"] as $key=>$value) {
		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_quotation_items`(
						`stc_quotation_items_quotation_id`, 
						`stc_quotation_items_product_product_id`, 
						`stc_quotation_items_qty`, 
						`stc_quotation_items_rate`
					) VALUES (
		    		VALUES (
		    			'".$get_purchase_product_id."', 
		    			'".$value['product_id']."', 
		    			'".$value['product_quantity']."', 
		    			'".$value['product_rate']."'
		    		)"
		    	);	    	
		    }
		}
		return $odin;
	}

	// echo Quotations on Vendor Quotation page for edit payment
	public function stc_call_quotation(){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_quotation` 
			LEFT JOIN `stc_customer` 
			ON `stc_quotation_customer_id`=`stc_customer_id` 
			ORDER BY `stc_quotation`.`stc_quotation_id` DESC");
		$odin='
			<table class="table table-hover ">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Date</th>
	        	    <th scope="col">Quotation Number</th>
	        	    <th scope="col">Vendor</th>
	        	    <th scope="col">Notes</th>                         
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<tr><td>No Quotation Available!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {

				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$ponumber = substr("0000{$row["stc_quotation_id"]}", -$str_length);
				$odin.='
					<tr>
       					<td>
       					  <p>'.$row["stc_quotation_date"].'</p>
       					</td>
       					<td>
       					  <h6>STC/Q/'.$ponumber.'</h6>
       					</td>
       					<td>
       					  <h6>'.$row["stc_customer_name"].'</h6>
       					</td>
       					<td>
       					  <h6>'.$row["stc_quotation_notes"].'</h6>
       					</td>
       					<td>
       						<div class="row">
       							<div class="col-sm-3">
       							 	<a class="stc_edit_quotation" href="#" id="'.$row["stc_quotation_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       							</div>
       							<div class="col-sm-3">
       							 	<a class="stc_print_page" href="quotation-preview.php?pid='.$row["stc_quotation_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
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

	// call product in click of view for edit
	public function stc_view_for_edit_quotation($view_for_edit_quotation_id){
		$odin='';
		$order_line_items='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_quotation`
			LEFT JOIN `stc_customer`
			ON `stc_quotation_customer_id` =`stc_customer_id`
			WHERE `stc_quotation_id`='".$view_for_edit_quotation_id."'
		");
		$stc_purchase_product_items_view_fedit_rec=mysqli_fetch_assoc($check_loki);
		$get_view_fedit_purchase_product_id=$stc_purchase_product_items_view_fedit_rec['stc_quotation_id'];
		$get_view_fedit_purchase_merchant_name='<option value="'.$stc_purchase_product_items_view_fedit_rec['stc_customer_id'].'">'.$stc_purchase_product_items_view_fedit_rec['stc_customer_name'].'</option>';
		$get_view_fedit_quote_tandc=$stc_purchase_product_items_view_fedit_rec['stc_quotation_tandc'];
		$get_view_fedit_quote_notes=$stc_purchase_product_items_view_fedit_rec['stc_quotation_notes'];
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "Something went Wrong!!";
			header('location:index.php');
		}else{
			$nested_check_loki=mysqli_query($this->stc_dbs, "
				SELECT * FROM `stc_quotation_items`
				LEFT JOIN `stc_product`
				ON `stc_quotation_items_product_product_id` =`stc_product_id`
				WHERE `stc_quotation_items_quotation_id`='".$view_for_edit_quotation_id."'
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
						    <th scope="col">Action</th>
						  </tr>
						</thead>
						<tbody>
			';  
			if(!empty($nested_check_loki)){ 
				$slno= 0; 
				$total = 0;  
				$totalgst = 0;
				foreach($nested_check_loki as $keys => $values) {  
					$slno++;
					$order_line_items .= ' 
						<tr>
							<td>'.$slno.'</td>
							<td>'.$values["stc_product_name"].'</td>
							<td>'.$values["stc_product_hsncode"].'</td>
							<td>'.$values["stc_product_unit"].'</td>
							<td>
							  <input
							  	style="width: 55%;padding: 5px;height: 40px;"
							    type="text"
							    id="stcqtypot'.$values["stc_quotation_items_product_product_id"].'"
							    class="validate"
							    placeholder="Quantity"
							    value="'.number_format($values["stc_quotation_items_qty"], 2).'"
							  />
							  <a data-product_id="'.$values["stc_quotation_items_product_product_id"].'" class="stcqtypo" href="#"><i class="fas fa-arrow-right"></i></a>
							</td>
							<td><a style="color: red;font-size:20px;" href="#" id="'.$values["stc_quotation_items_product_product_id"].'" class="stcdelpobtn"><i class="fas fa-trash"></i></a></td>
						</tr>
					';  
				}  
			}else{
				$order_line_items.='
					<tr>  
						<td colspan="10" align="center">  
						    <h3>Quotation Cart Empty!!!</h3>
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
			'gto_number' => $get_view_fedit_purchase_product_id,
			'Vendor_name' => $get_view_fedit_purchase_merchant_name,
			'line_items' => $order_line_items,
			'termandcond' => $get_view_fedit_quote_tandc,
			'quotnotes' => $get_view_fedit_quote_notes
		);
		return $odin;
	}
}



#<------------------------------------------------------------------------------------------>
#<-------------------------------------Purchase Objects------------------------------------->
#<------------------------------------------------------------------------------------------>

// search product by name in Vendor Quotation page
if(isset($_POST['search_quote_pd_name_in'])){
	$searchme=$_POST['search_quote_pd_name_in'];
	$objloki=new eyeofagamotto();
	$objlokiout=$objloki->stc_search_quote_product_name($searchme);
	echo json_encode($objlokiout);
}

// show vendor on Vendor Quotation page
if(isset($_POST['friday_quote_vendor'])){
	$objloki=new eyeofagamotto();
	$objlokiout=$objloki->stc_call_vendor_for_quote();
	$date=date('l jS \ F Y h:i:s A');
	$outarray=array(
		'quote_time' => $date,
		'quote_vendor' => $objlokiout
	);
	echo json_encode($outarray);
}

// add to session for purchase order
if(isset($_POST['add_quote'])){
	if($_POST["add_quote"] == "add") {  
		if(isset($_SESSION["stc_vendor_quote_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_vendor_quote_sess"] as $keys => $values) {  
				if($_SESSION["stc_vendor_quote_sess"][$keys]['product_id'] == $_POST["product_id"]) {  
				     $is_available++;  
				     $_SESSION["stc_vendor_quote_sess"][$keys]['product_quantity'] = $_SESSION["stc_vendor_quote_sess"][$keys]['product_quantity'] + $_POST["product_quantity"];  
				     echo "Item Cart Quantity Increased!!!!!!";
				}  
			}  
		    if($is_available < 1) {  
		    	$item_array = array(  
					'product_id'      			=>     $_POST["product_id"],  
					'product_name'    			=>     $_POST["product_name"],  
					'product_unit'    			=>     $_POST["product_unit"],  
					'product_quantity'			=>     $_POST["product_quantity"],
					'product_rate'       		=>     $_POST["product_rate"],
					'product_hsncode'			=>     $_POST["product_hsncode"],
					'product_gst'    			=>     $_POST["product_gst"] 
		    	);  
		    	$_SESSION["stc_vendor_quote_sess"][] = $item_array;  
		    	echo "Item Added to Customer Quote Cart!!!";
		    }  
		}else{  
			$item_array = array(  
				'product_id'      			=>     $_POST["product_id"],  
				'product_name'    			=>     $_POST["product_name"],  
				'product_unit'    			=>     $_POST["product_unit"],  
				'product_quantity'			=>     $_POST["product_quantity"],
				'product_rate'       		=>     $_POST["product_rate"],
				'product_hsncode'			=>     $_POST["product_hsncode"],
				'product_gst'    			=>     $_POST["product_gst"] 
			);   
			$_SESSION["stc_vendor_quote_sess"][] = $item_array;  
		     echo "Customer Quote Cart Created & Item Added to Customer Quote Cart!!!";
		}  
    }  
}

// show session on line items on vendor quotation
if(isset($_POST['stc_show_quote_sess'])){
    // $Deliverycharge=20;
	$order_table = '';  
	$slno = '';  
	$order_table .= '  
		<table class="table table-hover" align="centre">
			<thead>
			  <tr>
			    <th scope="col">#</th>
			    <th scope="col" style="width: 50%;">Items</th>
			    <th scope="col">HSN Code</th> 
			    <th scope="col">Unit</th>
			    <th scope="col">Qty</th>
			    <th scope="col">Action</th>
			  </tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_vendor_quote_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_vendor_quote_sess"] as $keys => $values) {
				$slno++;
				$order_table .= ' 
					<tr>
						<td>'.$slno.'</td>
						<td>'.$values["product_name"].'</td>
						<td>'.$values["product_hsncode"].'</td>
						<td>'.$values["product_unit"].'</td>
						<td>
						  <input
						  	style="width: 55%;padding: 5px;height: 40px;"
						    type="text"
						    id="stcqtyquotet'.$values["product_id"].'"
						    class="validate"
						    placeholder="Quantity"
						    value="'.number_format($values["product_quantity"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcqtyquotehit" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td><a style="color: red;font-size:20px;" href="#" id="'.$values["product_id"].'" class="stcdelquotebtn"><i class="fas fa-trash"></i></a></td>
					</tr>
				';
			}
		}else{
			$order_table.='
				<tr>  
					<td colspan="10" align="center">  
					    <h3>Quotation Cart Empty!!!</h3>
					</td>  
				</tr>  
			';
		}
	$order_table .= '
			
		  </tbody>
		</table>
		';  
	// $countopr=count($_SESSION["stc_purchase_order_sess"]);
	// $countop=$countopr;
	$export=array(
	  "orderTable" => $order_table
	  
	); 
	header('Content-type: application/json');
	echo json_encode($export);
}

// change quantity of session vendor quotation
if(isset($_POST["quantity_action"])){
  	foreach($_SESSION["stc_vendor_quote_sess"] as $keys => $values){  
	    if($_SESSION["stc_vendor_quote_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_vendor_quote_sess"][$keys]['product_quantity'] = $_POST["quantity"];
    	}  
  	}  
  	echo "Quotation Cart Updated!!!";
}

// delete from cart of session Quotation
if(isset($_POST['stcdelvendorquotelinei'])){
     foreach($_SESSION["stc_vendor_quote_sess"] as $keys => $values){  
          if($values["product_id"] == $_POST["product_id"]){  
               unset($_SESSION["stc_vendor_quote_sess"][$keys]);  
               echo "This Product Is Removed From Quotation Cart!!!";  
          }  
     }  
}

// save Quotation
if(isset($_POST['stcprosavevendquote'])){
	$stcvendormerchantidquote=$_POST['vmerchantid'];
	$stcvendortermandcond=$_POST['vtermandc'];
	$stcvendorquotesnotes=$_POST['vquotenotes'];
	$out='';
	if($stcvendormerchantidquote == 0 || empty($stcvendortermandcond) || empty($stcvendorquotesnotes)){
		$out="Please Fill All Fields!!";
	}else{
		$objloki=new eyeofagamotto();
		$objhela=new eyeofagamotto();
		$objlokiout=$objloki->stc_save_vendor_quote($stcvendormerchantidquote, $stcvendortermandcond, $stcvendorquotesnotes);
		if($objlokiout=='Success'){
			$objhelaout=$objhela->stc_save_qutation_items();	
			// if($objhelaout){
				unset($_SESSION['stc_vendor_quote_sess']);
			// }	
		}else{
			$objhelaout="Please Try Again!!!";
		}
		$out = array(
			'objloki' => $objlokiout,
			'objhela' => $objhelaout
		);
	}
	echo json_encode($out);
}

// call po on po page
if(isset($_POST['stccallquotations'])){
	$objloki=new eyeofagamotto();
	$objlokiout=$objloki->stc_call_quotation();
	echo json_encode($objlokiout);
}

// view for edit purchase product hit
if(isset($_POST['stc_ppe_pid'])){
	$view_for_edit_quotation_id=$_POST['stc_ppe_pid'];
	$objloki=new eyeofagamotto();
	$objlokiout=$objloki->stc_view_for_edit_quotation($view_for_edit_quotation_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}



#<------------------------------------------------------------------------------------------>
/*-----------------------------------Payment Objects---------------------------------------*/
#<------------------------------------------------------------------------------------------>
?>