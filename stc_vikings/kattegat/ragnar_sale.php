<?php
include "../../MCU/obdb.php";
session_start();
// call standard challan class
class ragnarStandardChallanView extends tesseract{
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
			AND `stc_item_inventory_pd_qty` != '0' 
			".$category.$subcategory.$productname.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)>0){
			foreach($ivarfilterquery as $filterrow){$loki_findratefrompo=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_purchase_product_items` 
					WHERE `stc_purchase_product_items_product_id`='".$filterrow["stc_product_id"]."' 
					AND `stc_purchase_product_items_id`=(
						SELECT MAX(`stc_purchase_product_items_id`) FROM `stc_purchase_product_items` 
						WHERE `stc_purchase_product_items_product_id`='".$filterrow["stc_product_id"]."'
					)
				");
				$purchase_rate=mysqli_fetch_assoc($loki_findratefrompo);
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
						         	          	type="number" 
						         	          	name="stcpdprice" 
						         	          	id="stcpdprice'.$filterrow["stc_product_id"].'" 
						         	          	placeholder="Enter Percent % Below 100"
						         	          	class="form-control validate"
						 	         			value="'.number_format($purchase_rate['stc_purchase_product_items_rate'], 2, '.', '').'" 
						 	         			style="color:black;font-weight:bold;text-align:center"
						 	         			disabled
						         	          />
						         	        <input 
						         	        	type="hidden"
						         	          	id="stcpdinvent'.$filterrow["stc_product_id"].'" 
						         	        	value="'.number_format($stcqty, 2, '.', '').'" 
						         	        />	  
						         	        <input 
						         	    		type="number" 
						         	    		name="stcpdsaleprice" 
						         	    		id="stcpdsaleperc'.$filterrow["stc_product_id"].'" 
						         	    		placeholder="Enter Percent % Below 100"
						         	    		class="form-control validate" 
						         	    		value="'.number_format($filterrow["stc_product_sale_percentage"], 2).'"
						         	    	/>           	            
						     	        </div>
						 	         	<input 
						 	         		type="hidden" 
						 	         		name="hidden_name" 
						 	         		id="stcpopdname'.$filterrow["stc_product_id"].'" 
						 	         		value="'.htmlentities($filterrow["stc_product_name"]).'" 
						 	         	/>
						 	         	<input 
						 	         		type="hidden" 
						 	         		name="hidden_unit" 
						 	         		id="stcpopdunit'.$filterrow["stc_product_id"].'" 
						 	         		value="'.$filterrow["stc_product_unit"].'" 
						 	         	/>
						 	         	<input 
						 	         		type="hidden" 
						 	         		name="hidden_hsncode" 
						 	         		id="stcpopdhsncode'.$filterrow["stc_product_id"].'" 
						 	         		value="'.$filterrow["stc_product_hsncode"].'" 
						 	         	/>
						 	         	<input 
						 	         		type="hidden" 
						 	         		name="hidden_gst" 
						 	         		id="stcpopdgst'.$filterrow["stc_product_id"].'" 
						 	         		value="'.$filterrow["stc_product_gst"].'" 
						 	         	/>
						 	         	<div class="a-size">
						     	        	<input
						                	  id="stcpoqty'.$filterrow["stc_product_id"].'"
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

										<a class="cart add_to_sale_cart" href="#" id="'.$filterrow["stc_product_id"].'">
										<span class="price">'.$filterrow["stc_sub_cat_name"].' / '.$filterrow["stc_brand_title"].'</span>
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

	// call invenotry for checkup
	public function stc_check_item_in_inventory($product_id){
		$ivar=0;
		$ivarqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_item_inventory_pd_qty` 
			FROM `stc_item_inventory` 
			WHERE `stc_item_inventory_pd_qty`!=0
			AND `stc_item_inventory_pd_id`='".mysqli_real_escape_string($this->stc_dbs, $product_id)."' 
		");
		if(mysqli_num_rows($ivarqry)!=0){
			foreach($ivarqry as $invqry){
				$ivar+=$invqry['stc_item_inventory_pd_qty'];
			}
		}else{
			$ivar=0;
		}
		return $ivar;
	}

	// filter challan by all
	public function stc_getchallan_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustid, $bjornefilterchallannumber, $bjornefiltersitename, $bjornefilterstatus, $bjornebegval, $bjorneendval){
		$ivar='';
			
		$array = array(
			"bycustomer" => $bjornefiltercustid,
			"bychallannumber" => $bjornefilterchallannumber,
			"bysitename" => $bjornefiltersitename,
			"bystatus" => $bjornefilterstatus
		);

		$customer='';
		$challanno='';
		$sitename='';
		$status='';
		$loopcount=0;
		foreach($array as $key => $value){
			if($array['bycustomer']!="NA"){
				$customer="
					AND `stc_customer_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bycustomer'])."'
				";
			}

			if(!empty($array['bychallannumber'])){
				$challanno="
					AND `stc_sale_product_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bychallannumber'])."'
				";
			}

			if(!empty($array['bysitename'])){
				$sitename="
					AND `stc_sale_product_sitename` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bysitename'])."'
				";
			}

			if($array['bystatus']!="NA"){
				$status="
					AND `stc_sale_product_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bystatus'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC LIMIT '.$bjornebegval.','.$bjorneendval;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product` 
			LEFT JOIN `stc_customer` 
			ON `stc_sale_product_cust_id`=`stc_customer_id` 
			WHERE (
				(`stc_sale_product_status`='challaned' AND `stc_sale_product_dc_keys`!='directchallaned')
				OR (`stc_sale_product_status`='billed' AND `stc_sale_product_dc_keys`!='directchallaned')
			)
			AND (
                DATE(`stc_sale_product_date`)
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterenddate)."'
            )
            AND ( 
            	`stc_customer_id` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $bjornefiltercustid)."'
			".$customer.$challanno.$sitename.$status.")".$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)>0){
			foreach ($ivarfilterquery as $row) {
				$loopcount++;
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT DISTINCT
						`stc_sale_product_items_product_qty`,
						`stc_sale_product_items_product_sale_rate`,
						`stc_product_gst`
					FROM `stc_sale_product_items`
					INNER JOIN `stc_product` 
					ON `stc_sale_product_items_product_id`=`stc_product_id` 
					WHERE `stc_sale_product_items_sale_product_id`='".$row["stc_sale_product_id"]."'
				");
				$soamount=0;
				$sogstamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$amt=0;
					$gstamt=0;
					$soiqty=$row2nd['stc_sale_product_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_items_product_sale_rate'];
					$amt=$soiqty * $soirate;
					$gstamt=$amt * $row2nd['stc_product_gst']/100;
					$soamount+=$soiqty * $soirate;
					$sogstamount+=$gstamt;
				}
				$gtotal=$soamount + $sogstamount;
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$challannumber = substr("0000{$row["stc_sale_product_id"]}", -$str_length);
				$ivar.='
					<tr>
       					<td>
       					  '.$row["stc_customer_name"].'
       					</td>
       					<td>
       					  '.date('d-m-y', strtotime($row["stc_sale_product_date"])).'</br>
       					  <h6>STC/DC/'.$challannumber.'</h6>
       					</td>
       					<td>
       					  '.date('d-m-y', strtotime($row["stc_sale_product_cust_order_date"])).'</br>
       					  '.$row["stc_sale_product_cust_order_no"].'
       					</td>
       					<td>
       					  '.$row["stc_sale_product_way_bill_no"].'
       					</td>
       					<td>
       					  '.$row["stc_sale_product_sitename"].'
       					</td>
       					<td style="width:auto;text-align:right;">
       					  <i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'</br>
       					  <i class="fa fa-rupee-sign"></i> '.number_format($gtotal, 2).'
       					</td>
       					<td>
       					  <h6>'.$row["stc_sale_product_status"].'</h6>
       					</td>
       					<td>
       					  <div class="row">       					  
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="edit-challan.php?cid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="challan-preview.php?pid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
       					    </div>
       					  </div>   
       					</td>
            		</tr>         	
		    	';		

				if($loopcount==20){
					break;
				}		
			}
		}else{
			$ivar .= '
				<tr>
					<td colspan="8">
						<a 
							href="#" 
							class="btn btn-primary btn-block text-uppercase mb-3">
							No Records Found!!!
						</a>
					</td>
				</tr>
			';
		}
		$ivar.= '
			<tr>
				<td>
					'.$bjornebegval.' to '.$bjorneendval.'
					<button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="begvalueinputsearch" value="'.$bjornebegval.'">
				</td>
				<td colspan="7">
					<button type="button" class="btn btn-primary endbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="endvalueinputsearch" value="'.$bjorneendval.'">
				</td>
			</tr>
		';
		return $ivar;
	}
}

// add standard challan calss
class ragnarStandardChallanAdd extends tesseract{
	// go data to sale table
	public function sale_process($customer_id, $sale_custorderdate,  $sale_custordernumber,  $sale_stcfc,  $sale_stcpf, $order_waybillno, $order_lrno, $order_suppdate, $order_suppplace, $order_refrence, $order_sitename, $order_contperson, $order_contnumber, $order_shipadd, $order_tandc, $order_notes){	
		$maxgst='';
		$date=date("Y-m-d H:i:s");
		foreach($_SESSION["stc_sale_order_sess"] as $keys => $values) {
			$maxgst=array($values['product_gst']);
		}
		$odin='';
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
				`stc_sale_product_created_by`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $date)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $customer_id)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $sale_custordernumber)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $sale_custorderdate)."', 
				'0', 
				'".mysqli_real_escape_string($this->stc_dbs, $order_waybillno)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_lrno)."',
				'".mysqli_real_escape_string($this->stc_dbs, $sale_stcfc)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $sale_stcpf)."',
				'".max($maxgst)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_notes)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_refrence)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_suppdate)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_suppplace)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_sitename)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_shipadd)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_contperson)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_contnumber)."',
				'".mysqli_real_escape_string($this->stc_dbs, $order_tandc)."',
				'challaned',
				'0',
				'0',
				'".$_SESSION['stc_empl_id']."'
			)
		");
		$odin='';
		if($lokiinsertsale){
			$odin .= "Sale Done!!!";
		}else{
			$odin .= "Please Check & Try Again!!!";
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
		if(empty($_SESSION['stc_sale_order_sess'])){
			$odin = "Do not leave Items on Sale Order!!!";
		}else{
			foreach ($_SESSION["stc_sale_order_sess"] as $key=>$value) {
		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_sale_product_items`(
		    			`stc_sale_product_items_sale_product_id`, 
		    			`stc_sale_product_items_product_id`, 
		    			`stc_sale_product_items_product_qty`, 
		    			`stc_sale_product_items_product_rate`, 
		    			`stc_sale_product_items_product_sale_rate`
		    		) VALUES (
		    			'".mysqli_real_escape_string($this->stc_dbs, $get_purchase_product_id)."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_id'])."',
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_quantity'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_price'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_sale_price'])."'
		    		)
		    	");
		    	$friday_loki_check_inventory_exist_pd=mysqli_query($this->stc_dbs, "
		    		SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_id`='".$value['product_id']."'
		    	");
		    	$loki_getinvenitems=mysqli_fetch_assoc($friday_loki_check_inventory_exist_pd);
				$inventory_pd_id=$loki_getinvenitems['stc_item_inventory_pd_id'];
				$inventory_pd_qty=$loki_getinvenitems['stc_item_inventory_pd_qty'];
				$iventoryrow=mysqli_num_rows($friday_loki_check_inventory_exist_pd);
				if($iventoryrow == 0){
					$odin .= "This Items is not in Inventory!!!";
				}else{
					$finalqty = $inventory_pd_qty - $value['product_quantity'];
			    	$friday_loki_inventory=mysqli_query($this->stc_dbs, "
			    		UPDATE `stc_item_inventory` 
			    		SET `stc_item_inventory_pd_qty`='".mysqli_real_escape_string($this->stc_dbs, $finalqty)."' 
			    		WHERE `stc_item_inventory_pd_id`='".mysqli_real_escape_string($this->stc_dbs, $value['product_id'])."'
			    	");
			    }
		    }
		    $odin = "Sale Item Done!!!";
		}
		return $odin;
	}
}

// edit tandard challan class
class ragnarStandardChallanEdit extends tesseract{
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
			AND `stc_item_inventory_pd_qty` != '0' 
			".$category.$subcategory.$productname.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)>0){
			foreach($ivarfilterquery as $filterrow){$loki_findratefrompo=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_purchase_product_items` 
					WHERE `stc_purchase_product_items_product_id`='".$filterrow["stc_product_id"]."' 
					AND `stc_purchase_product_items_id`=(
						SELECT MAX(`stc_purchase_product_items_id`) FROM `stc_purchase_product_items` 
						WHERE `stc_purchase_product_items_product_id`='".$filterrow["stc_product_id"]."'
					)
				");
				$purchase_rate=mysqli_fetch_assoc($loki_findratefrompo);
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
													type="hidden"
													id="stceditchllaninvent'.$filterrow["stc_product_id"].'"
													value="'.number_format($stcqty, 2).'"
												/>
							     	            <input 
													type="number" 
													name="stcpdprice" 
													id="stceditchallanprice'.$filterrow["stc_product_id"].'" 
													placeholder="Enter Percent % Below 100"
													class="form-control validate"
													value="'.number_format($purchase_rate['stc_purchase_product_items_rate'], 2, '.', '').'" 
													style="color:black;font-weight:bold;text-align:center"
													disabled
							             		/>		  
											    <input 
													type="number" 
													name="stcpdsaleprice" 
													id="stcpdchallanperc'.$filterrow["stc_product_id"].'" 
													placeholder="Enter Percent % Below 100"
													class="form-control validate" 
													value="'.number_format($filterrow["stc_product_sale_percentage"], 2).'"
							             		/>           	  
							     	        </div>
							 	         	<div class="a-size">
							     	        	<input
												  id="stceditchallanqty'.$filterrow["stc_product_id"].'"
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

										<a class="cart add_to_cartforeditchallan" href="#" id="'.$filterrow["stc_product_id"].'">
											<span class="price">'.$filterrow["stc_sub_cat_name"].' / '.$filterrow["stc_brand_title"].'</span>
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

	// check status of challan
	public function stc_check_challan_status($salechallanid){
		$ivar='';
		$ivarcheckbillstatusqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_id` 
			FROM `stc_sale_product` 
			WHERE `stc_sale_product_status`='billed'
			AND `stc_sale_product_id`='".$salechallanid."'
		");
		if(mysqli_num_rows($ivarcheckbillstatusqry)>0){
			$ivar='yes';
		}else{
			$ivar='no';
		}
		return $ivar;
	}

	// add to challan table sale order
	public function stc_edit_add_product_in_challan($pd_id, $salechallanid, $sale_qnty, $sale_purchase_price, $sale_price){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_sale_product_items`(
				`stc_sale_product_items_sale_product_id`, 
				`stc_sale_product_items_product_id`, 
				`stc_sale_product_items_product_qty`, 
				`stc_sale_product_items_product_rate`, 
				`stc_sale_product_items_product_sale_rate`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $salechallanid)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $pd_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $sale_qnty)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $sale_purchase_price)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $sale_price)."'
			)
		");
		$friday_loki_check_inventory_exist_pd=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_id`='".$pd_id."'
		");
		$loki_getinvenitems=mysqli_fetch_assoc($friday_loki_check_inventory_exist_pd);
		$inventory_pd_id=$loki_getinvenitems['stc_item_inventory_pd_id'];
		$inventory_pd_qty=$loki_getinvenitems['stc_item_inventory_pd_qty'];
		$iventoryrow=mysqli_num_rows($friday_loki_check_inventory_exist_pd);
		if($iventoryrow == 0){
			$odin .= "This items is not in inventory!!!";
		}else{
			$finalqty = $inventory_pd_qty - $sale_qnty;
			$friday_loki_inventory=mysqli_query($this->stc_dbs, "
				UPDATE `stc_item_inventory` 
				SET `stc_item_inventory_pd_qty`='".mysqli_real_escape_string($this->stc_dbs, $finalqty)."' 
				WHERE `stc_item_inventory_pd_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id)."'
			");
			if($friday_loki_inventory){
				$odin="Product added!!!";
			}else{
				$odin="Please try again!!!";
			}
		}
		return $odin;
	}

	// edit qty from sale product table
	public function stc_edit_updateqty_product_in_challan($challan_id, $challan_pd_qty){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product_items` 
			SET `stc_sale_product_items_product_qty`= '".mysqli_real_escape_string($this->stc_dbs, $challan_pd_qty)."'
			WHERE `stc_sale_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $challan_id)."'
		");
		if($friday_loki){
			$odin = "success";
		}else{
			$odin = "not done";
		}
		
		return $odin;
	}

	// increae quantity
	public function stc_increase_inventory_items($challan_id, $challan_pd_qty, $reverseqty){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_sale_product_items_product_id` AS pd_id FROM `stc_sale_product_items` 
			WHERE `stc_sale_product_items_product_qty`= '".mysqli_real_escape_string($this->stc_dbs, $challan_pd_qty)."' 
			AND `stc_sale_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $challan_id)."'
		");

		$pd_id=mysqli_fetch_assoc($friday_loki);

		$friday_loki_check_inventory_exist_pd=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_id`='".@$pd_id['pd_id']."'
		");
		$loki_getinvenitems=mysqli_fetch_assoc($friday_loki_check_inventory_exist_pd);
		$inventory_pd_id=@$loki_getinvenitems['stc_item_inventory_pd_id'];
		$inventory_pd_qty=$loki_getinvenitems['stc_item_inventory_pd_qty'];
			$finalqty = $inventory_pd_qty + $reverseqty;
			$friday_loki_inventory=mysqli_query($this->stc_dbs, "
				UPDATE `stc_item_inventory` 
				SET `stc_item_inventory_pd_qty`='".mysqli_real_escape_string($this->stc_dbs, $finalqty)."' 
				WHERE `stc_item_inventory_pd_id`='".mysqli_real_escape_string($this->stc_dbs, @$pd_id['pd_id'])."'
			");
			if($friday_loki_inventory){
				$odin = "Quantity increased!!!";
			}else{
				$odin = "Please try again!!!";
			}
		return $odin;
	}

	// decrease quantity
	public function stc_decrease_inventory_items($challan_id, $challan_pd_qty, $reverseqty){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_sale_product_items_product_id` AS pd_id FROM `stc_sale_product_items` 
			WHERE `stc_sale_product_items_product_qty`= '".mysqli_real_escape_string($this->stc_dbs, $challan_pd_qty)."' 
			AND `stc_sale_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $challan_id)."'
		");

		$pd_id=mysqli_fetch_assoc($friday_loki);

		$friday_loki_check_inventory_exist_pd=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_id`='".$pd_id['pd_id']."'
		");
		$loki_getinvenitems=mysqli_fetch_assoc($friday_loki_check_inventory_exist_pd);
		$inventory_pd_id=$loki_getinvenitems['stc_item_inventory_pd_id'];
		$inventory_pd_qty=$loki_getinvenitems['stc_item_inventory_pd_qty'];
		$iventoryrow=mysqli_num_rows($friday_loki_check_inventory_exist_pd);
		// if($iventoryrow == 0){
		// 	$odin .= "This items is not in inventory!!!";
		// }else{
			$finalqty = $inventory_pd_qty + $reverseqty;
			$friday_loki_inventory=mysqli_query($this->stc_dbs, "
				UPDATE `stc_item_inventory` 
				SET `stc_item_inventory_pd_qty`='".mysqli_real_escape_string($this->stc_dbs, $finalqty)."' 
				WHERE `stc_item_inventory_pd_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id['pd_id'])."'
			");
			if($friday_loki_inventory){
				$odin = "Quantity decreased!!!";
			}else{
				$odin = "Please try again!!!";
			}
		// }
		return $odin;
	}

	// price update challan
	public function stc_edit_updateprice_product_in_challan($challan_id, $challan_pd_price){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product_items` 
			SET `stc_sale_product_items_product_sale_rate`= '".mysqli_real_escape_string($this->stc_dbs, $challan_pd_price)."'
			WHERE `stc_sale_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $challan_id)."'
		");
		if($friday_loki){
			$odin = "Product price updated!!!";
		}else{
			$odin = "Please try again!!!";
		}
		return $odin;
	}

	// delete product from challan
	public function stc_edit_delete_product_in_challan($challan_id, $challan_delqty){
		$odin='';

		$friday_loki=mysqli_query($this->stc_dbs, "
			SELECT `stc_sale_product_items_product_id` AS pd_id FROM `stc_sale_product_items` 
			WHERE `stc_sale_product_items_id`='".$challan_id."'
		");

		$pd_id=mysqli_fetch_assoc($friday_loki);

		$friday_loki_check_inventory_exist_pd=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_id`='".$pd_id['pd_id']."'
		");

		$loki_getinvenitems=mysqli_fetch_assoc($friday_loki_check_inventory_exist_pd);
		$inventory_pd_id=$loki_getinvenitems['stc_item_inventory_pd_id'];
		$inventory_pd_qty=$loki_getinvenitems['stc_item_inventory_pd_qty'];
		$iventoryrow=mysqli_num_rows($friday_loki_check_inventory_exist_pd);

		$finalqty = $inventory_pd_qty + $challan_delqty;
		$friday_loki_delete=mysqli_query($this->stc_dbs, "
			UPDATE `stc_item_inventory` 
			SET `stc_item_inventory_pd_qty`='".mysqli_real_escape_string($this->stc_dbs, $finalqty)."' 
			WHERE `stc_item_inventory_pd_id`='".mysqli_real_escape_string($this->stc_dbs, $pd_id['pd_id'])."'
			
		");

		if($friday_loki_delete){
			$friday_loki_inventory=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_sale_product_items` WHERE `stc_sale_product_items_id`='".$challan_id."'
			");
			if($friday_loki_inventory){
				$odin= "Item removed!!!";
			}else{
				$odin= "Please try again!!!";
			}
		}else{
			$odin .= "This items is not in inventory!!!";
		}
		return $odin;
	}

	// save edited challan
	public function stc_edit_save_challan($esono, $esocustomerid, $esocustorderno, $esowaybillno, $esowaylrno, $esocustorddate, $esodosupply, $esoposupply, $esoorderrefrence, $esocustsitename, $esocustcontperson, $esocustcontnumber, $esoshipaddres, $esotermanc, $esontes){
		$maxgst='';
		$odin='';
		$lokiinsertsale=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product` SET 
				`stc_sale_product_cust_id`				='".mysqli_real_escape_string($this->stc_dbs, $esocustomerid)."',
				`stc_sale_product_cust_order_no`		='".mysqli_real_escape_string($this->stc_dbs, $esocustorderno)."',
				`stc_sale_product_cust_order_date`		='".mysqli_real_escape_string($this->stc_dbs, $esocustorddate)."',
				`stc_sale_product_way_bill_no`			='".mysqli_real_escape_string($this->stc_dbs, $esowaybillno)."',
				`stc_sale_product_lr_no`				='".mysqli_real_escape_string($this->stc_dbs, $esowaylrno)."',
				`stc_sale_product_dosupply`				='".mysqli_real_escape_string($this->stc_dbs, $esodosupply)."',
				`stc_sale_product_posupply`				='".mysqli_real_escape_string($this->stc_dbs, $esoposupply)."',
				`stc_sale_product_refrence`				='".mysqli_real_escape_string($this->stc_dbs, $esoorderrefrence)."',
				`stc_sale_product_sitename`				='".mysqli_real_escape_string($this->stc_dbs, $esocustsitename)."',
				`stc_sale_product_cont_person`			='".mysqli_real_escape_string($this->stc_dbs, $esocustcontperson)."',
				`stc_sale_product_cont_number`			='".mysqli_real_escape_string($this->stc_dbs, $esocustcontnumber)."',
				`stc_sale_product_siteaddress`			='".mysqli_real_escape_string($this->stc_dbs, $esoshipaddres)."',
				`stc_sale_product_tandc`				='".mysqli_real_escape_string($this->stc_dbs, $esotermanc)."',
				`stc_sale_product_notes`				='".mysqli_real_escape_string($this->stc_dbs, $esontes)."'
			WHERE `stc_sale_product_id`					='".mysqli_real_escape_string($this->stc_dbs, $esono)."'
		");
		$odin='';
		if($lokiinsertsale){
			$odin .= "Challan updated!!!";
		}else{
			$odin .= "Please check & try again!!!";
		}
		return $odin;
	}
}

// call direct challan class
class ragnarDirectChallanView extends tesseract{
	// filter product by all
	public function stc_getpddc_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout){
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
			foreach($ivarfilterquery as $filterrow){$loki_findratefrompo=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_purchase_product_items` 
					WHERE `stc_purchase_product_items_product_id`='".$filterrow["stc_product_id"]."' 
					AND `stc_purchase_product_items_id`=(
						SELECT MAX(`stc_purchase_product_items_id`) FROM `stc_purchase_product_items` 
						WHERE `stc_purchase_product_items_product_id`='".$filterrow["stc_product_id"]."'
					)
				");
				$purchase_rate=mysqli_fetch_assoc($loki_findratefrompo);
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
													type="number" 
													name="stcpdprice" 
													id="stcpdprice'.$filterrow["stc_product_id"].'" 
													placeholder="Enter Amount"
													class="form-control validate"
													style="color:black;font-weight:bold;text-align:center"
												/>		  
											    <input 
													type="number" 
													name="stcpdsaleprice" 
													id="stcpdsaleperc'.$filterrow["stc_product_id"].'" 
													placeholder="Enter Percent % Below 100"
													value="'.number_format($filterrow['stc_product_sale_percentage'], 2, '.', '').'"
													class="form-control validate" 
												/>           	            
											</div>
											<input 
												type="hidden" 
												name="hidden_name" 
												id="stcpopdname'.$filterrow["stc_product_id"].'" 
												value="'.htmlentities($filterrow["stc_product_name"]).'" 
											/>
											<input 
												type="hidden" 
												name="hidden_unit" 
												id="stcpopdunit'.$filterrow["stc_product_id"].'" 
												value="'.$filterrow["stc_product_unit"].'" 
											/>
											<input 
												type="hidden" 
												name="hidden_hsncode" 
												id="stcpopdhsncode'.$filterrow["stc_product_id"].'" 
												value="'.$filterrow["stc_product_hsncode"].'" 
											/>
											<input 
												type="hidden" 
												name="hidden_gst" 
												id="stcpopdgst'.$filterrow["stc_product_id"].'" 
												value="'.$filterrow["stc_product_gst"].'" 
											/>
											<div class="a-size">
												<input
													id="stcpoqty'.$filterrow["stc_product_id"].'"
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

										<a class="cart add_to_direct_cart" href="#" id="'.$filterrow["stc_product_id"].'">
											<span class="price">'.$filterrow["stc_sub_cat_name"].'</span>
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

	// filter direct challan
	public function stc_getdchallan_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustid, $bjornefiltermerid, $bjornefilterchallannumber, $bjornefilterivoicenumber, $bjornefiltersitename, $bjornefilterstatus, $bjornebegval, $bjorneendval){
		$ivar='';
			
		$array = array(
			"bycustomer" => $bjornefiltercustid,
			"bymerchant" => $bjornefiltermerid,
			"bychallannumber" => $bjornefilterchallannumber,
			"byinvoicenumber" => $bjornefilterivoicenumber,
			"bysitename" => $bjornefiltersitename,
			"bystatus" => $bjornefilterstatus
		);

		$customer='';
		$merchant='';
		$challanno='';
		$invoiceno='';
		$sitename='';
		$status='';
		$loopcount=0;
		foreach($array as $key => $value){
			if($array['bycustomer']!="NA"){
				$customer="
					AND `stc_customer_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bycustomer'])."'
				";
			}

			if($array['bymerchant']!="NA"){
				$customer="
					AND `stc_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bymerchant'])."'
				";
			}

			if(!empty($array['bychallannumber'])){
				$challanno="
					AND `stc_sale_product_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bychallannumber'])."'
				";
			}

			if(!empty($array['byinvoicenumber'])){
				$invoiceno="
					AND `stc_sale_product_dc_invo_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byinvoicenumber'])."'
				";
			}

			if(!empty($array['bysitename'])){
				$sitename="
					AND `stc_sale_product_sitename` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bysitename'])."'
				";
			}

			if($array['bystatus']!="NA"){
				$status="
					AND `stc_sale_product_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bystatus'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC LIMIT '.$bjornebegval.','.$bjorneendval;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_id`,
				`stc_sale_product_date`,
				`stc_sale_product_cust_order_date`,
				`stc_sale_product_cust_order_no`,
				`stc_customer_name`,
				`stc_sale_product_way_bill_no`,
				`stc_sale_product_sitename`,
				`stc_merchant_name`,
				`stc_sale_product_dc_invo_no`,
				`stc_sale_product_dc_invo_date`,
				`stc_sale_product_status`
			FROM `stc_sale_product` 
			LEFT JOIN `stc_customer` 
			ON `stc_sale_product_cust_id`=`stc_customer_id` 
			LEFT JOIN `stc_merchant` 
			ON `stc_sale_product_dc_merchant`=`stc_merchant_id` 
			WHERE (
				(
					`stc_sale_product_status`='direct_sale' 
					AND `stc_sale_product_dc_keys`='directchallaned'
				) OR (
					`stc_sale_product_status`='billed' 
					AND `stc_sale_product_dc_keys`='directchallaned'
				)
			) AND (
                DATE(`stc_sale_product_date`)
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterenddate)."'
            )".$customer.$merchant.$challanno.$invoiceno.$sitename.$status.$endfilterqry
		);
		$do_action=mysqli_num_rows($ivarfilterquery);
		if($do_action == 0){
			$ivar .= "<tr><td colspan='6' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($ivarfilterquery as $row) {
				$loopcount++;
				$paidvalue=0;
				$getpaid=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_sale_product_dc_payment_value`) AS paidamt
					FROM `stc_sale_product_dc_payment` 
					WHERE `stc_sale_product_dc_payment_order_id`='".$row["stc_sale_product_id"]."'
				");

				$paidvalue=mysqli_fetch_assoc($getpaid);
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_sale_product_dc_items_product_qty`, 
						`stc_sale_product_dc_items_product_sale_rate`, 
						`stc_sale_product_dc_items_product_rate`,
						`stc_product_gst`
					FROM `stc_sale_product_dc_items`
					INNER JOIN `stc_product` 
					ON `stc_sale_product_dc_items_product_id`=`stc_product_id` 
					WHERE `stc_sale_product_dc_items_sale_product_id`='".$row["stc_sale_product_id"]."'
				");
				$poamount=0;
				$soamount=0;
				$pogstamount=0;
				$sogstamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$soamt=0;
					$sogstamt=0;
					$poamt=0;
					$pogstamt=0;

					$soiqty=$row2nd['stc_sale_product_dc_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_dc_items_product_sale_rate'];
					$poirate=$row2nd['stc_sale_product_dc_items_product_rate'];

					$soamt=$soiqty * $soirate;
					$poamt=$soiqty * $poirate;

					$sogstamt=$soamt * $row2nd['stc_product_gst']/100;
					$pogstamt=$poamt * $row2nd['stc_product_gst']/100;


					$soamount+=$soamt;
					$sogstamount+=$sogstamt;

					$poamount+=$poamt;
					$pogstamount+=$pogstamt;
				}
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$totalpoamt=$soamount + $sogstamount;
              	$totalsoamt=$poamount + $pogstamount;
              	$ponumber = substr("0000{$row["stc_sale_product_id"]}", -$str_length);
				$ivar.='
					<tr>
       					<td>
       						'.$row["stc_customer_name"].'<br>'.$row["stc_merchant_name"].'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_date"])).'<br> 
       						STC/DC/'.$ponumber.'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_dc_invo_date"])).'<br>
       						'.$row["stc_sale_product_dc_invo_no"].'
       					</td>
       					<td>
       						'.$row["stc_sale_product_way_bill_no"].'
       					</td>
       					<td>
       						'.$row["stc_sale_product_sitename"].'
       					</td>
       					<td style="width:auto;text-align:right;">
       						<i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'<br>
       						<i class="fa fa-rupee-sign"></i> '.number_format($sogstamount, 2).'<br>
       						--------<br>
       						<i class="fa fa-rupee-sign"></i> '.number_format($totalpoamt, 2).'

       					</td>
       					<td style="width:auto;text-align:right;">
       						<i class="fa fa-rupee-sign"></i> '.number_format($poamount, 2).'<br>
       						<i class="fa fa-rupee-sign"></i> '.number_format($pogstamount, 2).'<br>
       						--------<br>
       						<i class="fa fa-rupee-sign"></i> '.number_format($totalsoamt, 2).'
       					</td>
       					<td style="width:auto;text-align:right;">
       						<i class="fa fa-rupee-sign"></i> '.number_format($paidvalue["paidamt"], 2).'
       					</td>
       					<td>
       						<h6>'.$row["stc_sale_product_status"].'</h6>
       					</td>
       					<td>
       					  <div class="row">       					  
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="edit-direct-challan.php?dcid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-edit" aria-hidden="true"></i></a>
       					    </div>
       					    <div class="col-sm-3">
       					     	<a class="stc_print_page" href="direct-challan-preview.php?dcid='.$row["stc_sale_product_id"].'" style="font-size: 25px;color: white;"><i class="fa fa-print" aria-hidden="true"></i></a>
       					    </div>
       					  </div>   
       					</td>
            		</tr>         	
		    	';	
				if($loopcount==20){
					break;
				}			
			}
		}
		$ivar.= '
			<tr>
				<td>
					'.$bjornebegval.' to '.$bjorneendval.'
					<button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="begvalueinputsearch" value="'.$bjornebegval.'">
				</td>
				<td colspan="9">
					<button type="button" class="btn btn-primary endbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="endvalueinputsearch" value="'.$bjorneendval.'">
				</td>
			</tr>
		';
		return $ivar;
	}

	// filter product
	public function stc_getpddcedit_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout){
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
			foreach($ivarfilterquery as $filterrow){$loki_findratefrompo=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_purchase_product_items` 
					WHERE `stc_purchase_product_items_product_id`='".$filterrow["stc_product_id"]."' 
					AND `stc_purchase_product_items_id`=(
						SELECT MAX(`stc_purchase_product_items_id`) FROM `stc_purchase_product_items` 
						WHERE `stc_purchase_product_items_product_id`='".$filterrow["stc_product_id"]."'
					)
				");
				$purchase_rate=mysqli_fetch_assoc($loki_findratefrompo);
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
													type="number" 
													name="stcpdprice" 
													id="stceditchallanprice'.$filterrow["stc_product_id"].'" 
													placeholder="Enter Amount"
													class="form-control validate"
												/>		  
											    <input 
												 	type="number" 
												 	name="stcpdsaleprice" 
												 	id="stcpdchallanperc'.$filterrow["stc_product_id"].'" 
												 	placeholder="Enter Percent % Below 100"
												 	class="form-control validate" 
												 	value="'.number_format($filterrow["stc_product_sale_percentage"], 2).'"
												 />           	            
											</div>
											<div class="a-size">
												<input
												  id="stceditchallanqty'.$filterrow["stc_product_id"].'"
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

										<a class="cart add_to_cartforeditdirectchallan" href="#" id="'.$filterrow["stc_product_id"].'">
											<span class="price">'.$filterrow["stc_sub_cat_name"].'</span>
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
}

// add direct challan class
class ragnarDirectChallanAdd extends tesseract{
	// check direct challan bill invoice existance
	public function stc_check_sale_bill($sale_mername, $sale_billinvono, $sale_billinvodate){
		$odin='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_id` 
			FROM `stc_sale_product` 
			WHERE `stc_sale_product_dc_merchant`='".$sale_mername."'
			AND `stc_sale_product_dc_invo_no`='".$sale_billinvono."'
			AND `stc_sale_product_dc_invo_date`='".$sale_billinvodate."'
		");
		if(mysqli_num_rows($check_loki)>0){
			$odin="yes";
		}else{
			$odin="no";
		}
		return $odin;
	}

	// go data to direct challan table
	public function sale_process($customer_id, $sale_custorderdate,  $sale_custordernumber,  $sale_mername, $sale_billinvono, $sale_billinvodate, $sale_stcfc,  $sale_stcpf, $order_waybillno, $order_lrno, $order_suppdate, $order_suppplace, $order_refrence, $order_sitename, $order_contperson, $order_contnumber, $order_shipadd, $order_tandc, $order_notes){	
		$maxgst='';
		$date=date("Y-m-d H:i:s");
		foreach($_SESSION["stc_direct_challan_sess"] as $keys => $values) {
			$maxgst=array($values['product_gst']);
		}
		$odin='';
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
				`stc_sale_product_dc_invo_no`,
				`stc_sale_product_dc_invo_date`,
				`stc_sale_product_dc_merchant`,
				`stc_sale_product_dc_keys`
			) VALUES (
				'".$date."', 
				'".$customer_id."', 
				'".$sale_custordernumber."', 
				'".$sale_custorderdate."', 
				'0', 
				'".$order_waybillno."',
				'".$order_lrno."',
				'".$sale_stcfc."', 
				'".$sale_stcpf."',
				'".max($maxgst)."',
				'".$order_notes."',
				'".$order_refrence."',
				'".$order_suppdate."',
				'".$order_suppplace."',
				'".$order_sitename."',
				'".$order_shipadd."',
				'".$order_contperson."',
				'".$order_contnumber."',
				'".$order_tandc."',
				'direct_sale',
				'0',
				'0',
				'".$_SESSION['stc_empl_id']."',
				'".$sale_billinvono."',
				'".$sale_billinvodate."',
				'".$sale_mername."',
				'directchallaned'
			)
		");
		$odin='';
		if($lokiinsertsale){
			$odin .= "Sale Done!!!";
		}else{
			$odin .= "Please Check & Try Again!!!";
		}
		return $odin;
	}

	// go direct challan session data to direct challan items table 
	public function stc_save_sale_pro_items(){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product` ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_sale_product_id'];
		if(empty($_SESSION['stc_direct_challan_sess'])){
			$odin = "Do not leave Items on Sale Order!!!";
		}else{
			foreach ($_SESSION["stc_direct_challan_sess"] as $key=>$value) {
		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_sale_product_dc_items`(
		    			`stc_sale_product_dc_items_sale_product_id`, 
		    			`stc_sale_product_dc_items_product_id`, 
		    			`stc_sale_product_dc_items_product_qty`, 
		    			`stc_sale_product_dc_items_product_rate`, 
		    			`stc_sale_product_dc_items_product_sale_rate`
		    		) VALUES (
		    			'".$get_purchase_product_id."', 
		    			'".$value['product_id']."',
		    			'".$value['product_quantity']."', 
		    			'".$value['product_price']."', 
		    			'".$value['product_sale_price']."'
		    		)
		    	");
		    }
		    $odin = "Sale Item Done!!!";
		}
		return $odin;
	}
}

// edi direct challan class
class ragnarDirectChallanEdit extends tesseract{
	// add to challan table direct challan
	public function stc_edit_add_product_in_challan($pd_id, $salechallanid, $sale_qnty, $sale_purchase_price, $sale_price){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_sale_product_dc_items`(
				`stc_sale_product_dc_items_sale_product_id`, 
				`stc_sale_product_dc_items_product_id`, 
				`stc_sale_product_dc_items_product_qty`, 
				`stc_sale_product_dc_items_product_rate`, 
				`stc_sale_product_dc_items_product_sale_rate`
			) VALUES (
				'".$salechallanid."', 
				'".$pd_id."',
				'".$sale_qnty."', 
				'".$sale_purchase_price."', 
				'".$sale_price."'
			)
		");
		if($friday_loki){
			$odin="Product added!!!";
		}else{
			$odin="Please try again!!!";
		}
		return $odin;
	}

	// edit qty from direct challan table
	public function stc_edit_updateqty_product_in_challan($challan_id, $challan_pd_qty){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product_dc_items` 
			SET `stc_sale_product_dc_items_product_qty`= '".$challan_pd_qty."'
			WHERE `stc_sale_product_dc_items_id`='".$challan_id."'
		");
		if($friday_loki){
			$odin = "success";
		}else{
			$odin = "not done";
		}
		
		return $odin;
	}

	// price update direct challan
	public function stc_edit_updateprice_product_in_challan($challan_id, $challan_pd_price){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product_dc_items` SET `stc_sale_product_dc_items_product_sale_rate`= '".$challan_pd_price."'
			WHERE `stc_sale_product_dc_items_id`='".$challan_id."'
		");
		if($friday_loki){
			$odin = "Product price updated!!!";
		}else{
			$odin = "Please try again!!!";
		}
		return $odin;
	}

	// purchase price update direct challan
	public function stc_edit_updatepurchaseprice_product_in_challan($challan_id, $challan_pd_price){
		$odin='';
		$friday_loki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product_dc_items` SET `stc_sale_product_dc_items_product_rate`= '".$challan_pd_price."'
			WHERE `stc_sale_product_dc_items_id`='".$challan_id."'
		");
		if($friday_loki){
			$odin = "Product purchase price updated!!!";
		}else{
			$odin = "Please try again!!!";
		}
		return $odin;
	}

	// delete product from direct challan
	public function stc_edit_delete_product_in_challan($challan_id, $challan_delqty){
		$odin='';
		$friday_loki_inventory=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_sale_product_dc_items` WHERE `stc_sale_product_dc_items_id`='".$challan_id."'
		");
		if($friday_loki_inventory){
			$odin= "Item removed!!!";
		}else{
			$odin= "Please try again!!!";
		}
		return $odin;
	}

	// save edited direct challan
	public function stc_edit_save_challan($esono, $esomerchantid, $esocustorderno, $esomername, $esobillinvono, $esobillinvodate, $esowaybillno, $esowaylrno, $esocustorddate, $esodosupply, $esoposupply, $esoorderrefrence, $esocustsitename, $esocustcontperson, $esocustcontnumber, $esoshipaddres, $esotermanc, $esontes){
		$maxgst='';
		$odin='';
		$lokiinsertsale=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product` SET 
				`stc_sale_product_cust_id`='".$esomerchantid."',
				`stc_sale_product_cust_order_no`='".$esocustorderno."',
				`stc_sale_product_cust_order_date`='".$esocustorddate."',
				`stc_sale_product_way_bill_no`='".$esowaybillno."',
				`stc_sale_product_lr_no`='".$esowaylrno."',
				`stc_sale_product_dosupply`='".$esodosupply."',
				`stc_sale_product_posupply`='".$esoposupply."',
				`stc_sale_product_refrence`='".$esoorderrefrence."',
				`stc_sale_product_sitename`='".$esocustsitename."',
				`stc_sale_product_cont_person`='".$esocustcontperson."',
				`stc_sale_product_cont_number`='".$esocustcontnumber."',
				`stc_sale_product_siteaddress`='".$esoshipaddres."',
				`stc_sale_product_tandc`='".$esotermanc."',
				`stc_sale_product_notes`='".$esontes."',
				`stc_sale_product_dc_invo_no`='".$esobillinvono."',
				`stc_sale_product_dc_invo_date`='".$esobillinvodate."',
				`stc_sale_product_dc_merchant`='".$esomername."'
			WHERE `stc_sale_product_id`='".$esono."'
		");
		$odin='';
		if($lokiinsertsale){
			$odin .= "Challan updated!!!";
		}else{
			$odin .= "Please check & try again!!!";
		}
		return $odin;
	}
}

// call virtual challan class
class ragnarVirtualChallanView extends tesseract{	
	// get grn item cart
	public function stc_get_grn_value(){
		$slno=0;
		$totalgst=0;
		$total=0;
		$odin = '  
			<table class="table table-hover" align="centre">
				<thead>
				  <tr>
				    <th scope="col">#</th>
				    <th scope="col">Item Code</th> 
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
							<td>'.$grninfo["stc_product_id"].'</td>
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
							  <td colspan="8"><h4 align="right">Grand Total</h4></td>
							  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.round($total + $totalgst, 2).'</span></td>
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

	// filter direct challan
	public function stc_getvchallan_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustid, $bjornefilterchallannumber, $bjornefiltergrnnumber, $bjornefiltersitename, $bjornefilterstatus, $bjornebegval, $bjorneendval){
		$ivar='';
			
		$array = array(
			"bycustomer" => $bjornefiltercustid,
			"bychallannumber" => $bjornefilterchallannumber,
			"bygrnnumber" => $bjornefiltergrnnumber,
			"bysitename" => $bjornefiltersitename,
			"bystatus" => $bjornefilterstatus
		);

		$customer='';
		$challanno='';
		$grnno='';
		$sitename='';
		$status='';
		$loopcount=0;
		foreach($array as $key => $value){
			if($array['bycustomer']!="NA"){
				$customer="
					AND `stc_customer_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bycustomer'])."'
				";
			}

			if(!empty($array['bychallannumber'])){
				$challanno="
					AND `stc_sale_product_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bychallannumber'])."'
				";
			}

			if(!empty($array['bygrnnumber'])){
				$grnno="
					AND `stc_sale_product_dc_invo_no` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bygrnnumber'])."'
				";
			}

			if(!empty($array['bysitename'])){
				$sitename="
					AND `stc_sale_product_sitename` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bysitename'])."'
				";
			}

			if($array['bystatus']!="NA"){
				$status="
					AND `stc_sale_product_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bystatus'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_sale_product`.`stc_sale_product_id` DESC LIMIT '.$bjornebegval.','.$bjorneendval;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product` 
			LEFT JOIN `stc_customer` 
			ON `stc_sale_product_cust_id`=`stc_customer_id` 
			WHERE (
				(`stc_sale_product_dc_keys`='virtualchallaned' OR 
					(
						`stc_sale_product_status`='virtualchallaned' AND 
						`stc_sale_product_dc_keys`='virtualchallaned'
					)
				)
			)
			AND (
                DATE(`stc_sale_product_date`)
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterenddate)."'
            )".$customer.$challanno.$grnno.$sitename.$status.$endfilterqry
		);
		$do_action=mysqli_num_rows($ivarfilterquery);
		if($do_action == 0){
			$ivar .= "<tr><td colspan='9' align='center'><a class='form-control btn btn-primary'>No Record Found!!!</a></td></tr>";
		}else{
			foreach ($ivarfilterquery as $row) {
				$loopcount++;
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_sale_product_vc` 
					INNER JOIN `stc_product` 
					ON `stc_sale_product_vc_items_product_id`=`stc_product_id`
					WHERE `stc_sale_product_vc_items_sale_product_id`='".$row["stc_sale_product_id"]."'
				");
				$soamount=0;
				$sogstamount=0;
				foreach ($check_loki_nest as $row2nd) {
					$amt=0;
					$gst=0;
					$soiqty=$row2nd['stc_sale_product_vc_items_product_qty'];
					$soirate=$row2nd['stc_sale_product_vc_items_product_sale_rate'];
					$amt=$soiqty * $soirate;
					$gst=$amt * $row2nd['stc_product_gst']/100;
					$soamount+=$amt;
					$sogstamount+=$gst;
				}
				$str_length = 5;
              	// hardcoded left padding if number < $str_length
              	$ponumber = substr("0000{$row["stc_sale_product_id"]}", -$str_length);
              	$total=$soamount + $sogstamount;
				$ivar.='
					<tr>
       					<td>
       					  '.$row["stc_customer_name"].'
       					</td>
       					<td>
       					  '.date('d-m-y', strtotime($row["stc_sale_product_date"])).'<br>
       					  <h6>STC/DC/'.$ponumber.'</h6>
       					</td>
       					<td>
       					  '.date('d-m-y', strtotime($row["stc_sale_product_cust_order_date"])).'<br>
       					  '.$row["stc_sale_product_cust_order_no"].'
       					</td>
       					<td>
       					 	'.$row["stc_sale_product_way_bill_no"].'
       					</td>
       					<td>
       					 	'.$row["stc_sale_product_sitename"].'
       					</td>
       					<td style="width:auto;text-align:right;">
       					 	<i class="fa fa-rupee-sign"></i> '.number_format($soamount, 2).'<br>
       					 	<i class="fa fa-rupee-sign"></i> '.number_format($sogstamount, 2).'<br>
       					 	---------<br>
       					 	'.number_format($total, 2).'
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
				if($loopcount==20){
					break;
				}							
			}
		}
		$ivar.= '
			<tr>
				<td>
					'.$bjornebegval.' to '.$bjorneendval.'
					<button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="begvalueinputsearch" value="'.$bjornebegval.'">
				</td>
				<td colspan="9">
					<button type="button" class="btn btn-primary endbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="endvalueinputsearch" value="'.$bjorneendval.'">
				</td>
			</tr>
		';
		return $ivar;
	}
}

// add virtual class challan
class ragnarVirtualChallanAdd extends tesseract{
	public function sale_process($customer_id, $sale_custorderdate,  $sale_custordernumber,  $sale_stcfc,  $sale_stcpf, $order_waybillno, $order_lrno, $order_suppdate, $order_suppplace, $order_refrence, $order_sitename, $order_contperson, $order_contnumber, $order_shipadd, $order_tandc, $order_notes, $order_grnno){	
		$maxgst='';
		$odin='';
		$date=date("Y-m-d H:i:s");
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
					'".$date."', 
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
					'".$_SESSION['stc_empl_id']."',
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
}

// edit direct challan class
class ragnarVirtualChallanEdit extends tesseract{
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
		$odin = "added";
		return $odin;
	}
}
#<------------------------------------------------------------------------------------------------------>
#<--------------------------------------Object sections of challan class-------------------------------->
#<------------------------------------------------------------------------------------------------------>
// show vendor on quotation page
if(isset($_POST['friday_vendor'])){
	$objloki=new ragnarStandardChallanView();
	$objlokiout=$objloki->stc_call_customer();
	$date=date('l jS \ F Y h:i:s A');
	$outarray=array(
		'time' => $date,
		'customer' => $objlokiout
	);
	echo json_encode($outarray);
}

// search by same
if(isset($_POST['stcaction'])){
	$bjornefiltercatout=$_POST['phpfiltercatout'];
	$bjornefiltersubcatout=$_POST['phpfiltersubcatout'];
	$bjornefilternameout=$_POST['phpfilternameout'];
	$out='';
	$objpdres=new ragnarStandardChallanView();	
	$opobjpdres=$objpdres->stc_getpd_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout);
	$out=$opobjpdres;
	echo $out;
}

// show sale cart
if(isset($_POST['stc_show_sale_sess'])){
	$order_table = '';  
	$slno = '';  
	$order_table .= '';  
		if(!empty($_SESSION["stc_sale_order_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_sale_order_sess"] as $keys => $values) {  
				$amount=$values["product_quantity"] * $values["product_sale_price"];
				$gstamount = ($amount * $values["product_gst"])/100;
				$slno++;
				$order_table .= ' 
					<tr>
						<td>'.$slno.'</td>
						<td align="center">'.$values["product_id"].'</td>
						<td>'.$values["product_name"].'</td>
						<td>'.$values["product_hsncode"].'</td>
						<td>'.$values["product_unit"].'</td>
						<td>
						  <input
						  	style="width: 55%;padding: 5px;height: 40px;"
						    type="text"
						    id="stcqtypot'.$values["product_id"].'"
						    class="validate form-control"
						    placeholder="Quantity"
						    value="'.number_format($values["product_quantity"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcqtysale" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td>
						  <input
						  	style="width: 55%;padding: 5px;height: 40px;"
						    type="text"
						    id="stcratepot'.$values["product_id"].'"
						    class="validate form-control"
						    placeholder="Rate"
						    value="'.number_format($values["product_sale_price"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcratesale" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
						<td> '.$values["product_gst"].'% </td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount + $gstamount, 2).'</td>
						<td align="center">
							<a 
								style="font-size: 20px;font-weight: bold;color: red;background: #ddb133;padding: 10px;border-radius: 100%;"
								href="#" 
								id="'.$values["product_id"].'" 
								class="stcdelsalebtn"
							><i class="fas fa-trash"></i>
							</a>
						</td>
					</tr>
				';  
				$total = $total + ($values["product_quantity"] * $values["product_sale_price"]) ;  
				$totalgst +=$gstamount;
			}  
			$gstamount = ($amount * $values["product_gst"])/100;
			$ekdumt=$total + $totalgst;
			$order_table .= ' 
			   <tr>
				  <td colspan="9"><h4 align="right">Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total, 2).'</td>
				  <td><input type="hidden" name="hidden_bvalue" id="stcbasicvalue" value="'.$total.'"></td>
				</tr>
				<tr>
				  <td colspan="9"><h4 align="right">CGST</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
				  <td></td>
				</tr>
				<tr>
				  <td colspan="9"><h4 align="right">SGST</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
				  <td></td>
				</tr>
				<tr>
				  <td colspan="9"><h4 align="right">Total Tax</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst, 2).'</td>
				  <input type="hidden" id="stc_grand_offset_table_value" value="'.$ekdumt.'">
				  <td></td>
				</tr>

				<tr>
            	  <td colspan="4"><h4 align="right">Freight Charges</h4></td>
            	  <td colspan="5"><h4 align="right">Packing & Forwarding</h4></td>
            	  <td colspan="2" rowspan="2" align="center">
            	  	<a 
						style="font-size: 50px;font-weight: bold;color: red;background: #ddb133;padding: 20px;border-radius: 100%;"
						class="stcsalec"
						href="#"
             		><i class="fas fa-arrow-down"></i>
             		</a>
            	  </td>
            	</tr>
            	<tr>
            	  <td colspan="4" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcfc" name="stcfc" value="0" placeholder="FC" style="width:100%;">
            	  </td>
            	  <td colspan="5" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcpf" name="stcpf" value="0" placeholder="PF" style="width:100%;">
            	  </td>
            	</tr>
				<tr>
				  <td colspan="9"><h4 align="right">Grand Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.round($total + $totalgst, 2).'</span></td>
				  <td></td>
				</tr>  
			';  
		}else{
			$order_table.='
				<tr>  
					<td colspan="10" align="center">  
					    <h3>Sale Cart Empty!!!</h3>
					</td>  
				</tr>  
			';
		}
	$export=array(
	  "orderTable" => $order_table	  
	); 
	// header('Content-type: application/json');
	echo json_encode($export);
}

// add to cart sale session obj
if(isset($_POST['add_sale'])){
	if($_POST["add_sale"] == "addsalecart") {  		
		$percalamount = ($_POST['product_price'] * $_POST['product_sale_percent'])/100;
		$pdsale_price = $percalamount + $_POST['product_price'];
		if(isset($_SESSION["stc_sale_order_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_sale_order_sess"] as $keys => $values) {  
				if($_SESSION["stc_sale_order_sess"][$keys]['product_id'] == $_POST["product_id"]) {  
				     $is_available++;
				     echo "This Item Already In A Cart!!!";
				}  
			}  
		    if($is_available < 1) {  
		    	$item_array = array(  
					'product_id'             =>     $_POST["product_id"],  
					'product_name'           	=>     $_POST["product_name"],  
					'product_unit'           		=>     $_POST["product_unit"],  
					'product_quantity'       			=>     $_POST["product_quantity"],
					'product_hsncode'        			=>     $_POST["product_hsncode"],
					'product_gst'       	 		=>     $_POST["product_gst"],
					'product_price'          	=>     $_POST["product_price"],
					'product_sale_price'     =>     $pdsale_price 
		    	);  
		    	$_SESSION["stc_sale_order_sess"][] = $item_array;  
		    	echo "Standard Challan Item Cart Created & Item Added to Cart!!!";
		    }  
		}else{  
			$item_array = array(  
				'product_id'             =>     $_POST["product_id"],  
				'product_name'           	=>     $_POST["product_name"],  
				'product_unit'           		=>     $_POST["product_unit"],  
				'product_quantity'       			=>     $_POST["product_quantity"],
				'product_hsncode'        			=>     $_POST["product_hsncode"],
				'product_gst'       	 		=>     $_POST["product_gst"],
				'product_price'          	=>     $_POST["product_price"],
				'product_sale_price'     =>     $pdsale_price
			);   
			$_SESSION["stc_sale_order_sess"][] = $item_array;  
		     echo "Cart Updated. Item Added to Cart!!!";
		}  
    }  
}

// sale cart qty change
if(isset($_POST["sale_quantity_action"])){
	$product_id=$_POST["product_id"];
	$product_qnty=$_POST["quantity"];
	$bjorne=new ragnarStandardChallanView();
	$objbjorne=$bjorne->stc_check_item_in_inventory($product_id);
	if($objbjorne>=$product_qnty){
		foreach($_SESSION["stc_sale_order_sess"] as $keys => $values){  
			if($_SESSION["stc_sale_order_sess"][$keys]['product_id'] == $product_id){  
				$_SESSION["stc_sale_order_sess"][$keys]['product_quantity'] = $product_qnty;
			}
		}
		echo "Cart Updated!!!";
	}else{
		echo "Invalid Quantity!!! Check Inventory First.";
	}
}

// change price of session Sale order
if(isset($_POST["stc_price_action"])){
  	foreach($_SESSION["stc_sale_order_sess"] as $keys => $values){  
	    if($_SESSION["stc_sale_order_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_sale_order_sess"][$keys]['product_sale_price'] = $_POST["price"];
    	}  
  	}  
  	echo "Cart Updated!!!";
}

// delete from sale cart of session Sale order
if(isset($_POST['stcdelsalelinei'])){
	foreach($_SESSION["stc_sale_order_sess"] as $keys => $values){  
		if($values["product_id"] == $_POST["product_id"]){  
			unset($_SESSION["stc_sale_order_sess"][$keys]);  
			echo "Product Removed!!!";  
		}  
	}  
}

// calculate charges of sale orders
if(isset($_POST['do_plus_minus_on_sale'])){
	$fc=$_POST['freightcharge'];
	$pf=$_POST['packingandforwardingcharge'];
	$gt=$_POST['grand_total'];

	$gstfc=($fc * 18)/100;
	$gstpf=($pf * 18)/100;

	$out=($gstfc + $fc) + ($gstpf + $pf) + $gt;
	echo number_format($out, 2);
}

// save sale order
if(isset($_POST['save_sale_action'])){
	$out='';
	$customer_id=$_POST['customer_id'];
	$sale_custorderdate=$_POST['sale_custorderdate'];
	$sale_custordernumber=$_POST['sale_custordernumber'];
	$sale_stcfc=$_POST['sale_stcfc'];
	$sale_stcpf=$_POST['sale_stcpf'];
	$order_waybillno=$_POST['sale_waybillno'];
	$order_lrno=$_POST['sale_lrno'];
	$order_suppdate=$_POST['sale_supplydate'];
	$order_suppplace=$_POST['sale_supplyplace'];
	$order_refrence=$_POST['sale_refrence'];
	$order_sitename=$_POST['sale_sitename'];
	$order_contperson=$_POST['sale_contperson'];
	$order_contnumber=$_POST['sale_contnumber'];
	$order_shipadd=$_POST['sale_shipaddress'];
	$order_tandc=$_POST['order_tandc'];
	$order_notes=$_POST['order_notes'];

	
	$fridaysale=new ragnarStandardChallanAdd();
	$fridaysaleitems=new ragnarStandardChallanAdd();
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
			$order_notes
		);
		if($objfridaygrn=='Sale Done!!!'){
			$objfridaygrnitems=$fridaysaleitems->stc_save_sale_pro_items();
			$out=$objfridaygrnitems;
			unset($_SESSION['stc_sale_order_sess']);
		}else{
			$out .= "Something went Wrong Please Check & Try Again!!!";
		}
	}
	echo $out;
}

#<-----------------------Object sections of view standard challan class------------------------------->
// search by same
if(isset($_POST['stcsaleaction'])){
	@$bjornebegval=$_POST['phpbegvalue'];
	@$bjorneendval=$_POST['phpendvalue'];
	$bjornefilterbegdate=$_POST['phpbegdate'];
	$bjornefilterenddate=$_POST['phpenddate'];
	$bjornefiltercustid=$_POST['phpmerchantid'];
	$bjornefilterchallannumber=$_POST['phpchallannumber'];
	$bjornefiltersitename=$_POST['phpchallansitename'];
	$bjornefilterstatus=$_POST['phpstatus'];
	$out='';
	if(empty($bjornebegval) || empty($bjorneendval)){
		$bjornebegval=0;
		$bjorneendval=20;
	}
	$objpdres=new ragnarStandardChallanView();	
	$opobjpdres=$objpdres->stc_getchallan_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustid, $bjornefilterchallannumber, $bjornefiltersitename, $bjornefilterstatus, $bjornebegval, $bjorneendval);
	$out=$opobjpdres;
	echo $out;
}

#<-----------------------Object sections of edit standard challan class------------------------------->

// add challan items to sale product_id
if(isset($_POST['stceditsaleaction'])){
	$bjornefiltercatout=$_POST['phpfiltercatout'];
	$bjornefiltersubcatout=$_POST['phpfiltersubcatout'];
	$bjornefilternameout=$_POST['phpfilternameout'];
	$out='';
	$objpdres=new ragnarStandardChallanEdit();	
	$opobjpdres=$objpdres->stc_getpd_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout);
	$out=$opobjpdres;
	echo $out;
}

// edit challan and add product to databases
if(isset($_POST['edit_challan'])){
	$out='';
	$pd_id=$_POST['product_id'];
	$salechallanid=$_POST['product_soid'];
	$sale_qnty=$_POST['product_quantity'];
	$sale_purchase_price=$_POST['product_price'];
	$sale_percent=$_POST['product_price_perc'];

	$percalamount = ($sale_purchase_price * $sale_percent)/100;
	$sale_price=$percalamount + $sale_purchase_price;

	$objloki=new ragnarStandardChallanEdit();
	$objlokibillcheck=new ragnarStandardChallanEdit();

	$objlokibillcheckout=$objlokibillcheck->stc_check_challan_status($salechallanid);

	if($objlokibillcheckout=="no"){
		$bjorne=new ragnarStandardChallanView();
		$objbjorne=$bjorne->stc_check_item_in_inventory($pd_id);
		if($objbjorne>=$sale_qnty){
			$objlokiout=$objloki->stc_edit_add_product_in_challan($pd_id, $salechallanid, $sale_qnty, $sale_purchase_price, $sale_price);
			$out=$objlokiout;
		}else{
			$out="Invalid Quantity!!! Check Inventory First.";
		}
	}else{
		$out="This Challaned is Billed!!! You Cannot Edit This Challan.";
	}
	echo $out;
}

// quantity update
if(isset($_POST['updatechallanqty'])){
	$challan_id=$_POST['challanid'];
	$product_id=$_POST['pd_id'];
	$challan_pd_qty=$_POST['product_qty'];
	$pdqty=$_POST['pd_qty'];
	$inward_qty=$_POST['inward_qty'];
	$reverseqty=0;
	$outobjlokiinv='';
	$objloki=new ragnarStandardChallanEdit();
	$objlokiinv=new ragnarStandardChallanEdit();
	$bjorne=new ragnarStandardChallanView();
	$objbjorne=$bjorne->stc_check_item_in_inventory($product_id);
	if($challan_pd_qty>$pdqty){
		if($objbjorne>=$challan_pd_qty){
			$objlokiout=$objloki->stc_edit_updateqty_product_in_challan($challan_id, $challan_pd_qty);
			if($objlokiout=="success"){
				if($challan_pd_qty >= $inward_qty){
					$reverseqty=$inward_qty - $challan_pd_qty;
					$outobjlokiinv=$objlokiinv->stc_increase_inventory_items($challan_id, $challan_pd_qty, $reverseqty);
				}else{
					$reverseqty=$inward_qty - $challan_pd_qty;
					$outobjlokiinv=$objlokiinv->stc_decrease_inventory_items($challan_id, $challan_pd_qty, $reverseqty);
				}
			}else{
				$outobjlokiinv = "Please recheck & try again later!!!";
			}
		}else{
			$outobjlokiinv="Invalid Quantity!!! Check Inventory First.";
		}
	}else{
		$objlokiout=$objloki->stc_edit_updateqty_product_in_challan($challan_id, $challan_pd_qty);
		if($objlokiout=="success"){
			if($challan_pd_qty >= $inward_qty){
				$reverseqty=$inward_qty - $challan_pd_qty;
				$outobjlokiinv=$objlokiinv->stc_increase_inventory_items($challan_id, $challan_pd_qty, $reverseqty);
			}else{
				$reverseqty=$inward_qty - $challan_pd_qty;
				$outobjlokiinv=$objlokiinv->stc_decrease_inventory_items($challan_id, $challan_pd_qty, $reverseqty);
			}
		}else{
			$outobjlokiinv = "Please recheck & try again later!!!";
		}
	}
	echo $outobjlokiinv;
}

// price change 
if(isset($_POST['updatechallanprice'])){
	$challan_id=$_POST['soid'];
	$salechallanid=$_POST['salpdid'];
	$challan_pd_price=$_POST['product_price'];
	$objlokibillcheck=new ragnarStandardChallanEdit();

	$objlokibillcheckout=$objlokibillcheck->stc_check_challan_status($salechallanid);

	if($objlokibillcheckout=="no"){

		$objloki=new ragnarStandardChallanEdit();
		$objlokiout=$objloki->stc_edit_updateprice_product_in_challan($challan_id, $challan_pd_price);
		$out=$objlokiout;
	}else{
		$out="This Challaned is Billed!!! You Cannot Edit This Challan.";
	}
	echo json_encode($out);
	// echo $out;
}

// delete products from challan
if(isset($_POST['delchallanli'])){
	$out='';
	$challan_id=$_POST['soid'];
	$salechallanid=$_POST['salpdid'];
	$challan_delqty=$_POST['delqty'];

	$objlokibillcheck=new ragnarStandardChallanEdit();

	$objlokibillcheckout=$objlokibillcheck->stc_check_challan_status($salechallanid);

	if($objlokibillcheckout=="no"){

		$objloki=new ragnarStandardChallanEdit();
		$objlokiout=$objloki->stc_edit_delete_product_in_challan($challan_id, $challan_delqty);	
		$out=$objlokiout;	
	}else{
		$out="This Challaned is Billed!!! You Cannot Edit This Challan.";
	}
	echo json_encode($out);
}

// edit challan save
if(isset($_POST['edit_save_challan'])){
	$esono               	=	$_POST['esono'];
	$esocustomerid       	=	$_POST['esocustomerid'];
	$esocustorderno      	=	$_POST['esocustorderno'];
	$esowaybillno      		=	$_POST['esowaybillno'];
	$esowaylrno		      	=	$_POST['esowaylrno'];
	$esocustorddate      	=	$_POST['esocustorddate'];
	$esodosupply         	=	$_POST['esodosupply'];
	$esoposupply         	=	$_POST['esoposupply'];
	$esoorderrefrence    	=	$_POST['esoorderrefrence'];
	$esocustsitename     	=	$_POST['esocustsitename'];
	$esocustcontperson   	=	$_POST['esocustcontperson'];
	$esocustcontnumber   	=	$_POST['esocustcontnumber'];
	$esoshipaddres       	=	$_POST['esoshipaddres'];
	$esotermanc          	=	$_POST['esotermanc'];
	$esontes 				=	$_POST['esontes'];
	$salechallanid 			=	$esono;

	$objlokibillcheck=new ragnarStandardChallanEdit();

	$objlokibillcheckout=$objlokibillcheck->stc_check_challan_status($salechallanid);

	if($objlokibillcheckout=="no"){
		$objloki=new ragnarStandardChallanEdit();

		if($esocustomerid=='NA'){
			$out="Please select customer first!!!";
		}else{
			$objlokiout=$objloki->stc_edit_save_challan(
				$esono,
				$esocustomerid,
				$esocustorderno,
				$esowaybillno,
				$esowaylrno,
				$esocustorddate,
				$esodosupply,
				$esoposupply,
				$esoorderrefrence,
				$esocustsitename,
				$esocustcontperson,
				$esocustcontnumber,
				$esoshipaddres,
				$esotermanc,
				$esontes
			);
			$out=$objlokiout;
		}	
	}else{
		$out="This Challaned is Billed!!! You Cannot Edit This Challan.";
	}
	echo json_encode($out);
}


#<-----------------------------Object sections of direct challan class------------------------------->

// add challan items to sale product_id
if(isset($_POST['stcdirectcaction'])){
	$bjornefiltercatout=$_POST['phpfiltercatout'];
	$bjornefiltersubcatout=$_POST['phpfiltersubcatout'];
	$bjornefilternameout=$_POST['phpfilternameout'];
	$out='';
	$objpdres=new ragnarDirectChallanView();	
	$opobjpdres=$objpdres->stc_getpddc_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout);
	$out=$opobjpdres;
	echo $out;
}

// show direct challan cart
if(isset($_POST['stc_show_direct_sale_sess'])){
	$order_table = '';  
	$slno = '';  
	$order_table .= ' 
		';  
		if(!empty($_SESSION["stc_direct_challan_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_direct_challan_sess"] as $keys => $values) {  
				$amount=$values["product_quantity"] * $values["product_sale_price"];
				$gstamount = ($amount * $values["product_gst"])/100;
				$slno++;
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
						    id="stcqtypot'.$values["product_id"].'"
						    class="validate form-control"
						    placeholder="Quantity"
						    value="'.number_format($values["product_quantity"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcqtydc" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td>
						  <input
						  	style="width: 55%;padding: 5px;height: 40px;"
						    type="text"
						    id="stcratepot'.$values["product_id"].'"
						    class="validate form-control"
						    placeholder="Rate"
						    value="'.number_format($values["product_sale_price"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcratedc" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
						<td> '.$values["product_gst"].'% </td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount + $gstamount, 2).'</td>
						<td><a style="color: red;font-size:20px;" href="#" id="'.$values["product_id"].'" class="stcdeldcbtn"><i class="fas fa-trash"></i></a></td>
					</tr>
				';  
				$total = $total + ($values["product_quantity"] * $values["product_sale_price"]) ;  
				$totalgst +=$gstamount;
			}  
			$gstamount = ($amount * $values["product_gst"])/100;
			$ekdumt=$total + $totalgst;
			$order_table .= ' 
			   <tr>
				  <td colspan="9"><h4 align="right">Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total, 2).'</td>
				  <td><input type="hidden" name="hidden_bvalue" id="stcbasicvalue" value="'.$total.'"></td>
				</tr>
				<tr>
				  <td colspan="9"><h4 align="right">CGST</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
				  <td></td>
				</tr>
				<tr>
				  <td colspan="9"><h4 align="right">SGST</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
				  <td></td>
				</tr>
				<tr>
				  <td colspan="9"><h4 align="right">Total Tax</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst, 2).'</td>
				  <input type="hidden" id="stc_grand_offset_table_value" value="'.$ekdumt.'">
				  <td></td>
				</tr>

				<tr>
            	  <td colspan="4"><h4 align="right">Freight Charges</h4></td>
            	  <td colspan="5"><h4 align="right">Packing & Forwarding</h4></td>
            	  <td colspan="2" rowspan="2" align="center">            	  	
            	    <a 
            	    	style="font-size: 50px;font-weight: bold;color: red;background: #ddb133;padding: 20px;border-radius: 100%;"
             		  	class="stcdcc"
             		  	href="#"
             		><i class="fas fa-arrow-down"></i>
             		</a>
            	  </td>
            	</tr>
            	<tr>
            	  <td colspan="4" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcfc" name="stcfc" value="0" placeholder="FC" style="width:100%;">
            	  </td>
            	  <td colspan="5" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcpf" name="stcpf" value="0" placeholder="PF" style="width:100%;">
            	  </td>
            	</tr>
				<tr>
				  <td colspan="9"><h4 align="right">Grand Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> <span id="stc_final_sale_value">'.round($total + $totalgst, 2).'</span></td>
				  <td></td>
				</tr>  
			';  
		}else{
			$order_table.='
				<tr>  
					<td colspan="12" align="center">  
					    <h3>Direct Challan Cart Empty!!!</h3>
					</td>  
				</tr>  
			';
		}
	$order_table .= '
		'; 
	$export=array(
	  "orderTable" => $order_table	  
	); 
	// header('Content-type: application/json');
	echo json_encode($export);
}

// add to cart direct challan session obj
if(isset($_POST['add_dc'])){
	if($_POST["add_dc"] == "addsalecart") {  		
		$percalamount = ($_POST['product_price'] * $_POST['product_sale_percent'])/100;
		$pdsale_price = $percalamount + $_POST['product_price'];
		if(isset($_SESSION["stc_direct_challan_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_direct_challan_sess"] as $keys => $values) {  
				if($_SESSION["stc_direct_challan_sess"][$keys]['product_id'] == $_POST["product_id"]) {  
				     $is_available++;  
				     $_SESSION["stc_direct_challan_sess"][$keys]['product_quantity'] = $_SESSION["stc_direct_challan_sess"][$keys]['product_quantity'] + $_POST["product_quantity"];  
				     echo "Direct Challan Cart Item Quantity Increased!!!";
				}  
			}  
		    if($is_available < 1) {  
		    	$item_array = array(  
		    	     'product_id'             =>     $_POST["product_id"],  
		    	     'product_name'           =>     $_POST["product_name"],  
		    	     'product_unit'           =>     $_POST["product_unit"],  
		    	     'product_quantity'       =>     $_POST["product_quantity"],
		    	     'product_hsncode'        =>     $_POST["product_hsncode"],
		    	     'product_gst'       	  =>     $_POST["product_gst"],
		    	     'product_price'          =>     $_POST["product_price"],
		    	     'product_sale_price'     =>     $pdsale_price 
		    	);  
		    	$_SESSION["stc_direct_challan_sess"][] = $item_array;  
		    	echo "Direct Challan Created & Item Added to Cart!!!";
		    }  
		}else{  
			$item_array = array(  
			    'product_id'             =>     $_POST["product_id"],  
		    	'product_name'           =>     $_POST["product_name"],  
		    	'product_unit'           =>     $_POST["product_unit"],  
		    	'product_quantity'       =>     $_POST["product_quantity"],
		    	'product_hsncode'        =>     $_POST["product_hsncode"],
		    	'product_gst'       	 =>     $_POST["product_gst"],
		    	'product_price'          =>     $_POST["product_price"],
		    	'product_sale_price'     =>     $pdsale_price
			);   
			$_SESSION["stc_direct_challan_sess"][] = $item_array;  
		     echo "Item Added to Direct Challan Cart!!!";
		}  
    }  
}

// direct challan cart qty change
if(isset($_POST["sale_quantity_action"])){
  	foreach($_SESSION["stc_direct_challan_sess"] as $keys => $values){  
	    if($_SESSION["stc_direct_challan_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_direct_challan_sess"][$keys]['product_quantity'] = $_POST["quantity"];
    	}  
  	}  
}

// change price of session Sale order
if(isset($_POST["stc_price_action"])){
  	foreach($_SESSION["stc_direct_challan_sess"] as $keys => $values){  
	    if($_SESSION["stc_direct_challan_sess"][$keys]['product_id'] == $_POST["product_id"]){  
	         $_SESSION["stc_direct_challan_sess"][$keys]['product_sale_price'] = $_POST["price"];
    	}  
  	}  
}

// delete from direct challan cart of session Sale order
if(isset($_POST['stcdelsalelinei'])){  
     foreach($_SESSION["stc_direct_challan_sess"] as $keys => $values){  
          if($values["product_id"] == $_POST["product_id"]){  
               unset($_SESSION["stc_direct_challan_sess"][$keys]);  
               echo "Product Removed!!!";  
          }  
     }  
}

// calculate charges of direct challan
if(isset($_POST['dc_do_plus_minus_on_sale'])){
	$fc=$_POST['freightcharge'];
	$pf=$_POST['packingandforwardingcharge'];
	$gt=$_POST['grand_total'];

	$gstfc=($fc * 18)/100;
	$gstpf=($pf * 18)/100;

	$out=($gstfc + $fc) + ($gstpf + $pf) + $gt;
	echo number_format($out, 2);
}

// save Direct Challan
if(isset($_POST['save_dc_action'])){
	$out					=	'';
	$customer_id			=	$_POST['customer_id'];
	$sale_custorderdate		=	$_POST['sale_custorderdate'];
	$sale_custordernumber	=	$_POST['sale_custordernumber'];
	$sale_mername			=	$_POST['sale_mername'];
	$sale_billinvono		=	$_POST['sale_billinvono'];
	$sale_billinvodate		=	$_POST['sale_billinvodate'];
	$sale_stcfc				=	$_POST['sale_stcfc'];
	$sale_stcpf				=	$_POST['sale_stcpf'];
	$order_waybillno		=	$_POST['sale_waybillno'];
	$order_lrno				=	$_POST['sale_lrno'];
	$order_suppdate			=	$_POST['sale_supplydate'];
	$order_suppplace		=	$_POST['sale_supplyplace'];
	$order_refrence			=	$_POST['sale_refrence'];
	$order_sitename			=	$_POST['sale_sitename'];
	$order_contperson		=	$_POST['sale_contperson'];
	$order_contnumber		=	$_POST['sale_contnumber'];
	$order_shipadd			=	$_POST['sale_shipaddress'];
	$order_tandc			=	$_POST['order_tandc'];
	$order_notes			=	$_POST['order_notes'];

	$fridaysale=new ragnarDirectChallanAdd();
	$fridaysaleitems=new ragnarDirectChallanAdd();
	$fridaychecksales=new ragnarDirectChallanAdd();
	$outfridaychecksales=$fridaychecksales->stc_check_sale_bill($sale_mername, $sale_billinvono, $sale_billinvodate);
	if(empty($sale_custorderdate) || empty($sale_custordernumber)){
		$out="Please Fill All Fields!!!";
	}elseif($outfridaychecksales=="yes"){
		$out="This bill is already in records!!!";
	}else{
		$objfridaygrn=$fridaysale->sale_process(
			$customer_id, 
			$sale_custorderdate, 
			$sale_custordernumber,
			$sale_mername, 
			$sale_billinvono,
			$sale_billinvodate,
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
			$order_notes
		);
		if($objfridaygrn=='Sale Done!!!'){
			$objfridaygrnitems=$fridaysaleitems->stc_save_sale_pro_items();
			$out=$objfridaygrnitems;
			unset($_SESSION['stc_direct_challan_sess']);
		}else{
			$out .= "Something went Wrong Please Check & Try Again!!!";
		}
	}
	echo $out;
}

// search by same
if(isset($_POST['stcdcaction'])){
	@$bjornebegval=$_POST['phpbegvalue'];
	@$bjorneendval=$_POST['phpendvalue'];
	$bjornefilterbegdate=$_POST['phpbegdate'];
	$bjornefilterenddate=$_POST['phpenddate'];
	$bjornefiltercustid=$_POST['phpcustomerid'];
	$bjornefiltermerid=$_POST['phpmerchantid'];
	$bjornefilterchallannumber=$_POST['phpchallannumber'];
	$bjornefilterivoicenumber=$_POST['phpchallaninvoiceno'];
	$bjornefiltersitename=$_POST['phpchallansitename'];
	$bjornefilterstatus=$_POST['phpstatus'];
	$out='';
	if(empty($bjornebegval) || empty($bjorneendval)){
		$bjornebegval=0;
		$bjorneendval=20;
	}
	$objpdres=new ragnarDirectChallanView();	
	$opobjpdres=$objpdres->stc_getdchallan_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustid, $bjornefiltermerid, $bjornefilterchallannumber, $bjornefilterivoicenumber, $bjornefiltersitename, $bjornefilterstatus, $bjornebegval, $bjorneendval);
	$out=$opobjpdres;
	echo $out;
}

#<-----------------------------Object sections of direct challan class------------------------------->
// find product for edit direct challan
if(isset($_POST['stceditdcsoaction'])){
	$bjornefiltercatout=$_POST['phpfiltercatout'];
	$bjornefiltersubcatout=$_POST['phpfiltersubcatout'];
	$bjornefilternameout=$_POST['phpfilternameout'];
	$out='';
	$objpdres=new ragnarDirectChallanView();	
	$opobjpdres=$objpdres->stc_getpddcedit_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout);
	$out=$opobjpdres;
	echo $out;
}

// edit challan and add product to databases
if(isset($_POST['edit_direct_challan'])){
	$out='';
	$pd_id=$_POST['product_id'];
	$salechallanid=$_POST['product_soid'];
	$sale_qnty=$_POST['product_quantity'];
	$sale_purchase_price=$_POST['product_price'];
	$sale_percent=$_POST['product_price_perc'];

	$percalamount = ($sale_purchase_price * $sale_percent)/100;
	$sale_price=$percalamount + $sale_purchase_price;

	$objlokibillcheck=new ragnarStandardChallanEdit();

	$objlokibillcheckout=$objlokibillcheck->stc_check_challan_status($salechallanid);

	if($objlokibillcheckout=="no"){
		$objloki=new ragnarDirectChallanEdit();
		$objlokiout=$objloki->stc_edit_add_product_in_challan($pd_id, $salechallanid, $sale_qnty, $sale_purchase_price, $sale_price);
		$out=$objlokiout;
	}else{
		$out="This Challaned is Billed!!! You Cannot Edit This Challan.";
	}
	echo $out;
}

// quantity update
if(isset($_POST['updatedirectchallanqty'])){
	$out='';
	$challan_id=$_POST['challanid'];
	$challan_pd_qty=$_POST['product_qty'];
	$inward_qty=$_POST['inward_qty'];
	$salechallanid=$_POST['dcid'];
	$reverseqty=0;
	$outobjlokiinv='';
	$objloki=new ragnarDirectChallanEdit();
	$objlokibillcheck=new ragnarStandardChallanEdit();

	$objlokibillcheckout=$objlokibillcheck->stc_check_challan_status($salechallanid);

	if($objlokibillcheckout=="no"){
		$objlokiout=$objloki->stc_edit_updateqty_product_in_challan($challan_id, $challan_pd_qty);
		$out=$objlokiout;
	}else{
		$out="This Challaned is Billed!!! You Cannot Edit This Challan.";
	}
	echo $out;
}

// price change 
if(isset($_POST['updatediectchallanprice'])){
	$out='';
	$challan_id=$_POST['soid'];
	$challan_pd_price=$_POST['product_price'];
	$salechallanid=$_POST['dcid'];

	$objloki=new ragnarDirectChallanEdit();
	$objlokibillcheck=new ragnarStandardChallanEdit();

	$objlokibillcheckout=$objlokibillcheck->stc_check_challan_status($salechallanid);

	if($objlokibillcheckout=="no"){
		$objlokiout=$objloki->stc_edit_updateprice_product_in_challan($challan_id, $challan_pd_price);
		$out=$objlokiout;
	}else{
		$out="This Challaned is Billed!!! You Cannot Edit This Challan.";
	}
	echo json_encode($out);
}

// purchase price change 
if(isset($_POST['updatediectchallanpurprice'])){
	$out='';
	$challan_id=$_POST['soid'];
	$challan_pd_price=$_POST['product_price'];
	$salechallanid=$_POST['dcid'];

	$objloki=new ragnarDirectChallanEdit();
	$objlokibillcheck=new ragnarStandardChallanEdit();

	$objlokibillcheckout=$objlokibillcheck->stc_check_challan_status($salechallanid);

	if($objlokibillcheckout=="no"){
		$objlokiout=$objloki->stc_edit_updatepurchaseprice_product_in_challan($challan_id, $challan_pd_price);
		$out=$objlokiout;
	}else{
		$out="This Challaned is Billed!!! You Cannot Edit This Challan.";
	}
	// echo json_encode($out);
	echo $out;
}

// delete products from challan
if(isset($_POST['deldchallanli'])){
	$out='';
	$challan_id=$_POST['soid'];
	$challan_delqty=$_POST['delqty'];
	$salechallanid=$_POST['dcid'];

	$objloki=new ragnarDirectChallanEdit();
	$objlokibillcheck=new ragnarStandardChallanEdit();

	$objlokibillcheckout=$objlokibillcheck->stc_check_challan_status($salechallanid);

	if($objlokibillcheckout=="no"){
		$objlokiout=$objloki->stc_edit_delete_product_in_challan($challan_id, $challan_delqty);
		$out=$objlokiout;
	}else{
		$out="This Challaned is Billed!!! You Cannot Edit This Challan.";
	}
	echo json_encode($out);
}

// save data for direct challan
if(isset($_POST['edit_save_direct_challan'])){
	$out='';
	$esono               	=	$_POST['esono'];
	$esomerchantid       	=	$_POST['esomerchantid'];
	$esocustorderno      	=	$_POST['esocustorderno']; 
    $esomername				=	$_POST['esomername'];  
    $esobillinvono			=	$_POST['esobillinvono'];  
    $esobillinvodate		=	$_POST['esobillinvodate'];
	$esowaybillno      		=	$_POST['esowaybillno'];
	$esowaylrno		      	=	$_POST['esowaylrno'];
	$esocustorddate      	=	$_POST['esocustorddate'];
	$esodosupply         	=	$_POST['esodosupply'];
	$esoposupply         	=	$_POST['esoposupply'];
	$esoorderrefrence    	=	$_POST['esoorderrefrence'];
	$esocustsitename     	=	$_POST['esocustsitename'];
	$esocustcontperson   	=	$_POST['esocustcontperson'];
	$esocustcontnumber   	=	$_POST['esocustcontnumber'];
	$esoshipaddres       	=	$_POST['esoshipaddres'];
	$esotermanc          	=	$_POST['esotermanc'];
	$esontes 				=	$_POST['esontes'];
	$salechallanid 			=	$esono;

	$objloki=new ragnarDirectChallanEdit();
	$objhela=new ragnarDirectChallanEdit();
	$objlokibillcheck=new ragnarStandardChallanEdit();

	$objlokibillcheckout=$objlokibillcheck->stc_check_challan_status($salechallanid);

	if($objlokibillcheckout=="no"){
		if($esomerchantid=='NA'){
			$objlokiout=$objloki="Please select customer first!!!";
		}elseif($objhela=="yes"){
			$objlokiout="This bill is already in records!!!";
		}else{
			$objlokiout=$objloki->stc_edit_save_challan(
				$esono,
				$esomerchantid,
				$esocustorderno,  
	        	$esomername,  
	        	$esobillinvono,  
	        	$esobillinvodate,
				$esowaybillno,
				$esowaylrno,
				$esocustorddate,
				$esodosupply,
				$esoposupply,
				$esoorderrefrence,
				$esocustsitename,
				$esocustcontperson,
				$esocustcontnumber,
				$esoshipaddres,
				$esotermanc,
				$esontes
			);
			$out=$objlokiout;
		}
	}else{
		$out="This Challaned is Billed!!! You Cannot Edit This Challan.";
	}
	echo json_encode($out);
}

#<---------------------------Object sections of virtual challan class------------------------------->
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

// get virtual challan items
if(isset($_POST['get_items_cart_from_grn'])){
	$objloki=new ragnarVirtualChallanView();
	$opobjloki=$objloki->stc_get_grn_value();
	echo $opobjloki;
}

// delete virtual challan items
if(isset($_POST['del_grn_id_vc'])){
	foreach($_SESSION["stc_virtual_challan"] as $keys => $values){  
		if($values["grn_id"] == $_POST["del_vc_id"]){  
			unset($_SESSION["stc_virtual_challan"][$keys]);  
			echo "GRN Removed!!!";  
		}  
	}  
}

// save direct challan
if(isset($_POST['save_vc_action'])){
	$out					= 	'';
	$customer_id			= 	$_POST['customer_id'];
	$sale_custorderdate		= 	$_POST['sale_custorderdate'];
	$sale_custordernumber	= 	$_POST['sale_custordernumber'];
	$sale_stcfc				= 	$_POST['sale_stcfc'];
	$sale_stcpf				= 	$_POST['sale_stcpf'];
	$order_waybillno		= 	$_POST['sale_waybillno'];
	$order_lrno				= 	$_POST['sale_lrno'];
	$order_suppdate			= 	$_POST['sale_supplydate'];
	$order_suppplace		= 	$_POST['sale_supplyplace'];
	$order_refrence			= 	$_POST['sale_refrence'];
	$order_sitename			= 	$_POST['sale_sitename'];
	$order_contperson		= 	$_POST['sale_contperson'];
	$order_contnumber		= 	$_POST['sale_contnumber'];
	$order_shipadd			= 	$_POST['sale_shipaddress'];
	$order_tandc			= 	$_POST['order_tandc'];
	$order_notes			= 	$_POST['order_notes'];
	$order_grnno			= 	$_POST['order_grnno'];

	$fridaysale=new ragnarVirtualChallanAdd();
	$fridaysaleitems=new ragnarVirtualChallanAdd();
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

// search by same
if(isset($_POST['stcvcaction'])){
	@$bjornebegval=$_POST['phpbegvalue'];
	@$bjorneendval=$_POST['phpendvalue'];
	$bjornefilterbegdate=$_POST['phpbegdate'];
	$bjornefilterenddate=$_POST['phpenddate'];
	$bjornefiltercustid=$_POST['phpcustomerid'];
	$bjornefilterchallannumber=$_POST['phpchallannumber'];
	$bjornefiltergrnnumber=$_POST['phpchallangrnno'];
	$bjornefiltersitename=$_POST['phpchallansitename'];
	$bjornefilterstatus=$_POST['phpstatus'];
	$out='';
	if(empty($bjornebegval) || empty($bjorneendval)){
		$bjornebegval=0;
		$bjorneendval=20;
	}
	$objpdres=new ragnarVirtualChallanView();	
	$opobjpdres=$objpdres->stc_getvchallan_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustid, $bjornefilterchallannumber, $bjornefiltergrnnumber, $bjornefiltersitename, $bjornefilterstatus, $bjornebegval, $bjorneendval);
	$out=$opobjpdres;
	echo $out;
}

// update virtual challaned
if(isset($_POST['update_vc'])){
	$out='';
	$vcid=$_POST['vc_id'];
	$vc_grn=$_POST['vc_grn'];
	$vc_new_grn=$_POST['vc_new_grn'];

	$updateval=new ragnarVirtualChallanEdit();
	$deleteval=new ragnarVirtualChallanEdit();
	$addval=new ragnarVirtualChallanEdit();

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