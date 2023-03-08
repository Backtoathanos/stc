<?php
include "../../MCU/obdb.php";
session_start();
class ragnarQuotationView extends tesseract{
	// call vendor on po page
	public function stc_call_customer(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_customer` GROUP BY `stc_customer_name` ASC");
		$odin='<option value="NA" selected>Select Customer</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Customer Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
				if($row["stc_customer_id"]==2){
					$odin.='
						<option value="'.$row["stc_customer_id"].'" selected>'.$row["stc_customer_name"].'</option>		               	
					';
				}else{
					$odin.='
						<option value="'.$row["stc_customer_id"].'">'.$row["stc_customer_name"].'</option>		               	
					';
				}				
			}
			
		}
		return $odin;
	}

	/*---------------------------- Filter product s-----------------------------*/
	// filter product by all
	public function stc_getpd_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout){
		$ivar='';
			
		$array = array(
			"bycat" => $bjornefiltercatout,
			"bysubcat" => $bjornefiltersubcatout,
			"byname" => $bjornefilternameout
		);
		$category='';
		$subcategory='';
		$productname='';
		foreach($array as $key => $value){
			if($array['bycat']!="NA"){
				$category="
					AND `stc_product_cat_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bycat'])."'
				";
			}

			if($array['bysubcat']!="NA"){
				$subcategory="
					AND `stc_product_sub_cat_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bysubcat'])."'
				";
			}

			if(!empty($array['byname'])){
				$productname="
					AND (`stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byname'])."'
					OR 
					`stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byname'])."')
				";
			}
		}
		$endfilterqry='ORDER BY `stc_product_id` ASC LIMIT 0,30';

		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT * FROM `stc_product` 
			LEFT JOIN `stc_category` ON `stc_cat_id`=`stc_product_cat_id` 
			LEFT JOIN `stc_sub_category` ON `stc_sub_cat_id`=`stc_product_sub_cat_id` 
			LEFT JOIN `stc_rack` ON `stc_rack_id`=`stc_product_rack_id` 
			LEFT JOIN `stc_item_inventory` ON `stc_item_inventory_pd_id`=`stc_product_id`
			LEFT JOIN `stc_brand` ON `stc_brand_id`=`stc_product_brand_id`
			WHERE `stc_product_avail`='1' 
			".$category.$subcategory.$productname.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)>0){
			foreach($ivarfilterquery as $filterrow){
				$correct_stcpdname=strlen($filterrow["stc_product_name"]);
				$stcqty=$filterrow["stc_item_inventory_pd_qty"];
				if($stcqty==0){
					$stcqty=0;
				}else{
					$stcqty;
				}
				$ivar.='
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
		             	  	<div class="row">
		             	    	<div class="el-wrapper">
		             	      		<div class="box-up">
										<img class="img" style="height: 100%; width:100%;" src="../stc_symbiote/stc_product_image/'.$filterrow["stc_product_image"].'" alt="">
		             	        		<div class="img-info">
											<div class="info-inner">
												<span class="p-name">'.htmlentities($filterrow["stc_product_name"]).'</span>
												<span class="p-company">
													'.$filterrow["stc_product_id"].' /
													'.$filterrow["stc_cat_name"].' / 
													'.$filterrow["stc_product_hsncode"].' / 
													'.$filterrow["stc_product_gst"].'%
												</span>
												<span class="p-qty" >'.$stcqty.''.$filterrow["stc_product_unit"].'</span>
												<input
													id="stcquoteqty'.$filterrow["stc_product_id"].'"
													name="stcpoqty"
													type="number"
													placeholder="Quantity"
													class="form-control validate"
												/>
											</div>
											<input 
												type="hidden" 
												name="hidden_name" 
												id="stcquotepdname'.$filterrow["stc_product_id"].'" 
												value="'.htmlentities($filterrow["stc_product_name"]).'" 
											/>
											<input 
												type="hidden" 
												name="hidden_unit" 
												id="stcquotepdunit'.$filterrow["stc_product_id"].'" 
												value="'.$filterrow["stc_product_unit"].'" 
											/>
											<input 
												type="hidden" 
												name="hidden_hsncode" 
												id="stcquotepdhsncode'.$filterrow["stc_product_id"].'" 
												value="'.$filterrow["stc_product_hsncode"].'" 
											/>
											<input 
												type="hidden" 
												name="hidden_gst" 
												id="stcquotepdgst'.$filterrow["stc_product_id"].'" 
												value="'.$filterrow["stc_product_gst"].'" 
											/>
											<div class="a-size">
												<input
													id="stcquoterate'.$filterrow["stc_product_id"].'"
													name="stcquoterate"
													type="number"
													placeholder="Rate"
													class="form-control validate"
												/>
											</div>
		             	        		</div>
		             	      		</div>

									<div class="box-down">
										<div class="h-bg">
											<div class="h-bg-inner"></div>
										</div>
										<a class="cart add_to_cart_for_vendor_quote" href="#" id="'.$filterrow["stc_product_id"].'">
											<span class="price">'.$filterrow["stc_sub_cat_name"].'/'.$filterrow["stc_brand_title"].'</span>
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
			$ivar .= '
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
					<a 
				      href="#" 
				      class="btn btn-primary btn-block text-uppercase mb-3">
				      No Records Found!!!
				    </a>
				</div>
			';
		}
		return $ivar;
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
       							 	<a class="stc_edit_quotation" href="#" id="'.$row["stc_quotation_id"].'" style="font-size: 25px;color: black;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       							</div>
       							<div class="col-sm-3">
       							 	<a class="stc_print_page" href="quotation-preview.php?pid='.$row["stc_quotation_id"].'" style="font-size: 25px;color: black;"><i class="fa fa-print" aria-hidden="true"></i></a>
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

class ragnarQuotationAdd extends tesseract{
	// save purchase customer Quotation
	public function stc_save_vendor_quote($stcvendormerchantidquote, $stcvendortermandcond, $stcvendorquotesnotes){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$friday_loki=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_quotation`(
				`stc_quotation_date`, 
				`stc_quotation_customer_id`, 
				`stc_quotation_tandc`, 
				`stc_quotation_notes`, 
				`stc_quotation_created_by`
			) VALUES (
				'".$date."', 
				'".$stcvendormerchantidquote."', 
				'".mysqli_real_escape_string($this->stc_dbs, $stcvendortermandcond)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $stcvendorquotesnotes)."', 
				'".$_SESSION['stc_empl_id']."'
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
			SELECT * FROM `stc_quotation` WHERE `stc_quotation_created_by`='".$_SESSION['stc_empl_id']."' ORDER BY `stc_quotation`.`stc_quotation_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_quotation_id'];
		if(empty($_SESSION['stc_customer_quote_sess'])){
			$odin = "Please Add Item First!!";
		}else{
			foreach ($_SESSION["stc_customer_quote_sess"] as $key=>$value) {
		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_quotation_items`(
		    			`stc_quotation_items_quotation_id`, 
		    			`stc_quotation_items_product_product_id`, 
		    			`stc_quotation_items_qty`, 
		    			`stc_quotation_items_rate`
		    		) 
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
}
#<------------------------------------------------------------------------------------------------------>
#<--------------------------------------Object sections of Quotation class------------------------------>
#<------------------------------------------------------------------------------------------------------>
// show vendor on quotation page
if(isset($_POST['friday_vendor'])){
	$objloki=new ragnarQuotationView();
	$objlokiout=$objloki->stc_call_customer();
	$date=date('l jS \ F Y h:i:s A');
	$outarray=array(
		'time' => $date,
		'vendor' => $objlokiout
	);
	echo json_encode($outarray);
}

// search by same
if(isset($_POST['stcaction'])){
	$bjornefiltercatout=$_POST['phpfiltercatout'];
	$bjornefiltersubcatout=$_POST['phpfiltersubcatout'];
	$bjornefilternameout=$_POST['phpfilternameout'];
	$out='';
	$objpdres=new ragnarQuotationView();	
	$opobjpdres=$objpdres->stc_getpd_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout);
	$out=$opobjpdres;
	echo $out;
}

// add to session for purchase order
if(isset($_POST['add_quote'])){
	if($_POST["add_quote"] == "add") {  
		if(isset($_SESSION["stc_customer_quote_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_customer_quote_sess"] as $keys => $values) {  
				if($_SESSION["stc_customer_quote_sess"][$keys]['product_id'] == $_POST["product_id"]) {  
				     $is_available++;  
				     $_SESSION["stc_customer_quote_sess"][$keys]['product_quantity'] = $_SESSION["stc_customer_quote_sess"][$keys]['product_quantity'] + $_POST["product_quantity"];  
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
		    	$_SESSION["stc_customer_quote_sess"][] = $item_array;  
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
			$_SESSION["stc_customer_quote_sess"][] = $item_array;  
		     echo "Customer Quote Cart Created & Item Added to Customer Quote Cart!!!";
		}  
    }  
}

// show session on line items on vendor quotation
if(isset($_POST['stc_show_quote_sess'])){
	$order_table = '';  
	$slno = '';  
	$order_table .= '  
		<table class="table table-hover table-bordered" align="centre">
			<thead>
			  <tr>
			    <th scope="col">#</th>
			    <th scope="col">Item Code</th>
			    <th scope="col" style="width: 15%;">Items</th>
			    <th scope="col">HSN Code</th> 
			    <th scope="col">Unit</th>
			    <th scope="col">Qty</th>
			    <th scope="col">Rate</th>
			    <th scope="col">Total</th>
			    <th scope="col">Action</th>
			  </tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_customer_quote_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_customer_quote_sess"] as $keys => $values) {
				$slno++;
				$total=$values["product_quantity"] * $values["product_rate"];
				$order_table .= ' 
					<tr>
						<td>'.$slno.'</td>
						<td>'.$values["product_id"].'</td>
						<td>'.$values["product_name"].'</td>
						<td>'.$values["product_hsncode"].'</td>
						<td>'.$values["product_unit"].'</td>
						<td>
							<input
								style="width: 55%;padding: 5px;height: 40px;"
								type="text"
								id="stcqtyquotet'.$values["product_id"].'"
								class="validate form-control"
								placeholder="Quantity"
								value="'.number_format($values["product_quantity"], 2).'"
							/>
							<a 
								data-product_id="'.$values["product_id"].'" 
								class="stcqtyquotehit" 
								href="#"
							><i class="fas fa-arrow-right"></i>
							</a>
						</td>
						<td>
							<input
								style="width: 55%;padding: 5px;height: 40px;"
								type="text"
								id="stcratequotet'.$values["product_id"].'"
								class="validate form-control"
								placeholder="Rate"
								value="'.number_format($values["product_rate"], 2).'"
							/>
							<a 
								data-product_id="'.$values["product_id"].'" 
								class="stcratequotehit" 
								href="#"
							><i class="fas fa-arrow-right"></i>
							</a>
						</td>
						<td width="10%" align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total, 2).'</td>
						<td>
							<a 
								style="color: red;font-size:20px;" 
								href="#" 
								id="'.$values["product_id"].'" 
								class="stcdelquotebtn"
							><i class="fas fa-trash"></i>
							</a>
						</td>
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
	$export=array(
	  "orderTable" => $order_table
	  
	); 
	echo json_encode($export);
	// echo $export;
}

// change quantity of session vendor quotation
if(isset($_POST["quantity_action"])){  
  	foreach($_SESSION["stc_customer_quote_sess"] as $keys => $values){  
	    if($_SESSION["stc_customer_quote_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_customer_quote_sess"][$keys]['product_quantity'] = $_POST["quantity"];
    	}  
  	}  
  	echo "Quotation Cart Updated!!!";
}

// change quantity of session vendor quotation
if(isset($_POST["rate_action"])){  
  	foreach($_SESSION["stc_customer_quote_sess"] as $keys => $values){  
	    if($_SESSION["stc_customer_quote_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_customer_quote_sess"][$keys]['product_rate'] = $_POST["rate"];
    	}  
  	}  
  	echo "Quotation Cart Updated!!!";
}

// delete from cart of session Quotation
if(isset($_POST['stcdelvendorquotelinei'])){  
     foreach($_SESSION["stc_customer_quote_sess"] as $keys => $values){  
          if($values["product_id"] == $_POST["product_id"]){  
               unset($_SESSION["stc_customer_quote_sess"][$keys]);  
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
		$objloki=new ragnarQuotationAdd();
		$objhela=new ragnarQuotationAdd();
		$objlokiout=$objloki->stc_save_vendor_quote($stcvendormerchantidquote, $stcvendortermandcond, $stcvendorquotesnotes);
		if($objlokiout=='Success'){
			$objhelaout=$objhela->stc_save_qutation_items();	
			// if($objhelaout){
				unset($_SESSION['stc_customer_quote_sess']);
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
	$objloki=new ragnarQuotationView();
	$objlokiout=$objloki->stc_call_quotation();
	echo json_encode($objlokiout);
}
?>