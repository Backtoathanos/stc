<?php
include "../../MCU/obdb.php";
session_start();
class ragnarPurchaseAdd extends tesseract{
	// call vendor on po page
	public function stc_call_vendor(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_merchant` GROUP BY `stc_merchant_name` ASC");
		$odin='<option value="NA" selected>Select Merchant</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Merchant Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_merchant_id"].'">'.$row["stc_merchant_name"].'</option>		               	
		            	';				
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
					<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
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
												id="stcpoqty'.$filterrow["stc_product_id"].'"
												name="stcpoqty"
												type="number"
												placeholder="Quantity"
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
											type="number" 
											name="stcpdprice" 
											id="stcpdprice'.$filterrow["stc_product_id"].'" 
											placeholder="Price"
											class="form-control validate"
										/></div>
									</div>
								</div>

								<div class="box-down">
									<div class="h-bg">
									  <div class="h-bg-inner"></div>
									</div>

									<a class="cart add_to_cart" href="#" id="'.$filterrow["stc_product_id"].'">
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

	// save purchase product order
	public function stc_save_purchase_pro($stcmerchantid, $stcbasicvalue, $stctermsandcond, $gtonumberinst, $reqnoinst){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$friday_loki=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_purchase_product`(
				`stc_purchase_product_merchant_id`, 
				`stc_purchase_product_order_date`, 
				`stc_purchase_product_basic_value`, 
				`stc_purchase_product_status`, 
				`stc_purchase_product_notes`, 
				`stc_purchase_product_created_by`,
				`stc_purchase_product_purchased_for`,
				`stc_purchase_product_req_no`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $stcmerchantid)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $date)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $stcbasicvalue)."', 
				'process', 
				'".mysqli_real_escape_string($this->stc_dbs, $stctermsandcond)."', 
				'".$_SESSION['stc_empl_id']."',
				'".mysqli_real_escape_string($this->stc_dbs, $gtonumberinst)."',
				'".mysqli_real_escape_string($this->stc_dbs, $reqnoinst)."'
			)
		");
		if($friday_loki){
			$odin = "Success";
		}else{
			$odin = "Not Ok!!";
		}
		return $odin;
	}

	// save purchase product order items
	public function stc_save_purchase_pro_items(){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_purchase_product` ORDER BY `stc_purchase_product`.`stc_purchase_product_id` DESC");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_purchase_product_id'];
		if(empty($_SESSION['stc_purchase_order_sess'])){
			$odin = "Please Add Item First!!";
		}else{
			foreach ($_SESSION["stc_purchase_order_sess"] as $key=>$value) {
		    	$friday_loki=mysqli_query($this->stc_dbs, "
		    		INSERT INTO `stc_purchase_product_items`(
		    			`stc_purchase_product_items_order_id`, 
		    			`stc_purchase_product_items_product_id`, 
		    			`stc_purchase_product_items_qty`, 
		    			`stc_purchase_product_items_rate`
		    		) VALUES (
		    			'".mysqli_real_escape_string($this->stc_dbs, $get_purchase_product_id)."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_id'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_quantity'])."', 
		    			'".mysqli_real_escape_string($this->stc_dbs, $value['product_price'])."'
		    		)
		    	");	    	
		    }
		}
		return $odin;
	}
}

// view poclass
class ragnarPurchaseView extends tesseract{
	// filter product by all
	public function stc_getpo_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltermerid, $bjornefilterpono, $bjornefilterphpgrnno, $bjornefilterphpinvono, $bjornefilterstatus, $begval, $endval){
		$ivar='';
			
		$array = array(
			"bymerchant" => $bjornefiltermerid,
			"bypono" => $bjornefilterpono,
			"bygrnno" => $bjornefilterphpgrnno,
			"byinvono" => $bjornefilterphpinvono,
			"bystatus" => $bjornefilterstatus
		);

		$merchant='';
		$po='';
		$grn='';
		$invo='';
		$status='';
		foreach($array as $key => $value){
			if($array['bymerchant']!="NA"){
				$merchant="
					AND `stc_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bymerchant'])."'
				";
			}

			if(!empty($array['bypono'])){
				$po="
					AND `stc_purchase_product_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bypono'])."'
				";
			}

			if(!empty($array['bygrnno'])){
				$grn="
					AND `stc_product_grn_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bygrnno'])."'
				";
			}

			if(!empty($array['byinvono'])){
				$invo="
					AND `stc_product_grn_invoice_number` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['byinvono'])."'
				";
			}

			if($array['bystatus']!="NA"){
				$status="
					AND `stc_purchase_product_status` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bystatus'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_purchase_product_id` DESC LIMIT '.$begval.','.$endval;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_purchase_product_id`,
				`stc_purchase_product_order_date`,
				`stc_merchant_name`,
				`stc_purchase_product_status`,
				`stc_purchase_product_purchased_for`,
				`stc_purchase_product_req_no`
			FROM `stc_purchase_product` 
			LEFT JOIN `stc_merchant` 
			ON `stc_purchase_product_merchant_id`=`stc_merchant_id` 
			LEFT JOIN `stc_product_grn` 
			ON `stc_product_grn_purchase_order_id`=`stc_purchase_product_id`
			WHERE (
                DATE(`stc_purchase_product_order_date`)
                 BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterbegdate)."'
                 AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterenddate)."'
            )
			".$merchant.$po.$grn.$invo.$status.$endfilterqry
		);

		if(mysqli_num_rows($ivarfilterquery)>0){
			foreach($ivarfilterquery as $row){
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_purchase_product_items_qty`,
						`stc_purchase_product_items_rate`,
						`stc_product_gst`
					FROM `stc_purchase_product_items`
					INNER JOIN `stc_product`
					ON `stc_purchase_product_items_product_id`=`stc_product_id` 
					WHERE `stc_purchase_product_items_order_id`='".$row["stc_purchase_product_id"]."'
				");
				$poamount='';
				$poamountbas=0;
				$poamounttot=0;
				foreach ($check_loki_nest as $poamtrow) {
					$basicamt=0;
					$totalamt=0;
					$poqty=$poamtrow['stc_purchase_product_items_qty'];
					$porate=$poamtrow['stc_purchase_product_items_rate'];
					$basicamt+=$poqty * $porate;					
					$totalamt+= $basicamt + (($basicamt * $poamtrow["stc_product_gst"])/100);
					$poamountbas+=$basicamt;
					$poamounttot+=$totalamt;
				}
				$poamount.='
						<i class="fa fa-rupee-sign"></i> 
						<b>'.number_format($poamountbas, 2).'</b><br>
						<i class="fa fa-rupee-sign"></i> 
						<b>'.number_format($poamounttot, 2).'</b>
				';
				

				$callap_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_merchant_advance_payment_id`
					FROM `stc_merchant_advance_payment` 
					WHERE `stc_merchant_advance_payment_purhcase_product_id`='".$row["stc_purchase_product_id"]."'
				");
				$paymenttypes='';

				if($apamt=mysqli_num_rows($callap_loki)>0){
					$paymenttypes='
							<b>ADVANCE</b>
					';
				}else{
					$paymenttypes='NA';
				}

				$getgrndetqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_product_grn_id`,
						`stc_product_grn_date`,
						`stc_product_grn_invoice_number`,
						`stc_product_grn_invoice_date`,
						`stc_product_grn_freight_charges`,
						`stc_product_grn_packing_forwarding`,
						`stc_product_grn_others_charges`
					FROM `stc_product_grn` 
					WHERE `stc_product_grn_purchase_order_id`='".$row["stc_purchase_product_id"]."' 
				");
				$grndetails='';
				$invodetails='';
				$invoamt='';
				$str_length = 5;
				if(mysqli_num_rows($getgrndetqry)>0){
					foreach($getgrndetqry as $grndetrow){
						$grndetails.='
							<a
								href="#"
								class="stc-call-grn-via-grn-no"
								id="'.$grndetrow["stc_product_grn_id"].'"
								style="color: white;background: #359760;padding: 5px;border-radius: 50px;"
							>
								'.date('d-m-Y',strtotime($grndetrow["stc_product_grn_date"])).'<br>
       					  		GRN/'.substr("0000{$grndetrow["stc_product_grn_id"]}", -5).'<br>
       					  	</a>
						';
						$invodetails.='
							<br>'.date('d-m-Y',strtotime($grndetrow["stc_product_grn_invoice_date"])).'</br>
       					  	'.$grndetrow["stc_product_grn_invoice_number"].'
						';

						$ininvoamtgrn=0;
						$invototamtgrn=0;

						$callamot_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_product_grn_items_qty`, 
								`stc_product_grn_items_rate`,
								`stc_product_gst`
							FROM `stc_product_grn_items`
							INNER JOIN `stc_product`
							ON `stc_product_grn_items_product_id`=`stc_product_id`
							WHERE `stc_product_grn_items_grn_order_id`='".$grndetrow["stc_product_grn_id"]."'
						");

						foreach ($callamot_loki as $amtrow){
							$ininvoamt = $amtrow['stc_product_grn_items_qty'] * $amtrow['stc_product_grn_items_rate'];						
							$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
							$ininvoamtgrn += $ininvoamt;
							$invototamtgrn += $ininvototamt;
							$progst=$amtrow['stc_product_gst'];
						}

						$gstfc=($grndetrow['stc_product_grn_freight_charges'] * $progst)/100;
						$gstpf=($grndetrow['stc_product_grn_packing_forwarding'] * $progst)/100;
						$gstoc=($grndetrow['stc_product_grn_others_charges'] * $progst)/100;

						$invototamtgrna =
							($gstfc + $grndetrow['stc_product_grn_freight_charges']) + 
							($gstpf + $grndetrow['stc_product_grn_packing_forwarding']) + 
							($gstoc + $grndetrow['stc_product_grn_others_charges']) + 
							($invototamtgrn);

						$invoamt.='
							<p align="right">
								<i class="fa fa-rupee-sign"></i> 
								<b>'.number_format($ininvoamtgrn, 2).'</b>
							</p>
							<p align="right">
								<i class="fa fa-rupee-sign"></i> 
								<b>'.number_format($invototamtgrna, 2).'</b>
							</p>
						';

						$callrp_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_merchant_regular_payment_id`, 
								`stc_merchant_regular_payment_date`, 
								`stc_merchant_regular_payment_amount` 
							FROM `stc_merchant_regular_payment` 
							WHERE `stc_merchant_regular_payment_grn_number`='".$grndetrow["stc_product_grn_id"]."'
						");

						if($rpamt=mysqli_num_rows($callrp_loki)>0){
							$paymenttypes.='<br>
									<b>REGULAR</b>
							';
						}else{
							$paymenttypes.='<br>NA';
						}
					}
				}else{
					$grndetails='NA';
					$invodetails='NA';
				}

              	// hardcoded left padding if number < $str_length
              	if($row["stc_purchase_product_status"] == 'accepted'){
              		$postatus='
              			<span 
              				style="
              					background-color: #3bea22;
              					color: black;
              					padding: 8px;
              					border-radius: 40%;
              					font-weight: bold;
              					width: 110px;
              					margin: 5px;
              					display: inline-block;
              					text-align: center;
              					"
              				>'.$row["stc_purchase_product_status"].'
              			</span>
              		';
              	}elseif( $row["stc_purchase_product_status"] == 'canceled'){
              		$postatus='
              			<span 
              				style="
              					background-color: red;
              					color: black;padding: 8px;
              					border-radius: 40%;
              					font-weight: bold;
              					width: 110px;
              					margin: 5px;
              					display: inline-block;
              					text-align: center;
              				"
              			>'.$row["stc_purchase_product_status"].'
              			</span>';
              	}elseif( $row["stc_purchase_product_status"] == 'granted'){
              		$postatus='
              			<span 
              				style="
              					background-color: #3bea22;
              					color: black;padding: 8px;
              					border-radius: 40%;
              					font-weight: bold;
              					width: 110px;
              					margin: 5px;
              					display: inline-block;
              					text-align: center;
              				"
              			>'.$row["stc_purchase_product_status"].'
              			</span>';
              	}else{
              		$postatus='
              			<span 
              				style="
              					background-color: #ffe939;
              					color: black;
              					padding: 8px;
              					border-radius: 40%;
              					font-weight: bold;
              					width: 110px;
              					margin: 5px;
              					display: inline-block;
              					text-align: center;
              				"
              			>'.$row["stc_purchase_product_status"].'
              			</span>';
              	}          	
              	$ponumber = substr("0000{$row["stc_purchase_product_id"]}", -$str_length);

              	$permissiongrant='';

              	if(($_SESSION['stc_empl_role']==6) && ($row["stc_purchase_product_status"] == 'process')){
              		$permissiongrant='
              			<a 
							class="stc-grant-product-purchase-trigger"
							href="#"
							id="'.$row["stc_purchase_product_id"].'" 
							style="font-size: 25px;color: white;"
						><i class="fa fa-key" aria-hidden="true"></i>
						</a>
              		';
              	}elseif((($_SESSION['stc_empl_role']==9) || ($_SESSION['stc_empl_role']==1)) && ($row["stc_purchase_product_status"] == 'process')){
              		$permissiongrant='
						<a 
							class="stc-edit-product-purchase-trigger"
							href="edit-purchase-order.php?epid='.$row["stc_purchase_product_id"].'"
							id="'.$row["stc_purchase_product_id"].'" 
							style="font-size: 25px;color: white;"
						><i class="fa fa-edit" aria-hidden="true"></i>
						</a>
						<a 
							href="print-preview.php?pid='.$row["stc_purchase_product_id"].'&postatus='.$row["stc_purchase_product_status"].'" 
							style="font-size: 25px;color: white;"
						><i class="fa fa-print" aria-hidden="true"></i>
						</a>
              		';
              	}elseif((($_SESSION['stc_empl_role']==9) || ($_SESSION['stc_empl_role']==1)) && ($row["stc_purchase_product_status"] == 'canceled')){
              		$permissiongrant='
						<a 
							class="stc-edit-product-purchase-trigger"
							href="edit-purchase-order.php?epid='.$row["stc_purchase_product_id"].'&epstatus='.$row["stc_purchase_product_status"].'"
							id="'.$row["stc_purchase_product_id"].'" 
							style="font-size: 25px;color: white;"
						><i class="fa fa-edit" aria-hidden="true"></i>
						</a>
						<a 
							href="print-preview.php?pid='.$row["stc_purchase_product_id"].'&postatus='.$row["stc_purchase_product_status"].'" 
							style="font-size: 25px;color: white;"
						><i class="fa fa-print" aria-hidden="true"></i>
						</a>
              		';
              	}else{
              		$permissiongrant='
						<a 
							href="print-preview.php?pid='.$row["stc_purchase_product_id"].'&postatus='.$row["stc_purchase_product_status"].'" 
							style="font-size: 25px;color: white;"
						><i class="fa fa-print" aria-hidden="true"></i>
						</a>
              		';
              	}

              	$requisit_no='';
              	if($row["stc_purchase_product_req_no"]==0){
              		$requisit_no='NA';
              	}else{
              		$requisit_no='
              			<a href="stc-requisition-combiner-fshow.php?requi_id='.$row["stc_purchase_product_req_no"].'" >STC/PM/'.$row["stc_purchase_product_req_no"].'</a>
              		';
              	}

				$ivar.='
					<tr>
						<td>
							<h6>'.$row["stc_merchant_name"].'</h6>
						</td>
						<td align="center">
							'.date('d-m-Y',strtotime($row["stc_purchase_product_order_date"])).'<br>
							STC/'.$ponumber.'
						</td>
						<td align="center">
							'.$grndetails.'
						</td>
						<td align="center">
							'.$invodetails.'
						</td> 					
						<td align="right">
							'.$poamount.'
						</td>
						<td align="center">
							'.$paymenttypes.'
						</td>
						<td>
							'.$row["stc_purchase_product_purchased_for"].'<br>
							'.$requisit_no.'
						</td>
						<td>
							<h6 class="text-uppercase text-center">'.$postatus.'</h6>
						</td>
						<td align="center">
						'.$permissiongrant.'
						</td>
					</tr>
		    	';	
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
				<td colspan="4">
					'.$begval.' to '.$endval.'
					<button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
						<i class="fas fa-arrow-left"></i>
					</button>
					<input type="hidden" class="begvalueinput" value="0">
					<input type="hidden" class="begvalueinputsearch" value="'.$begval.'">
				</td>
				<td colspan="5">
					<button type="button" class="btn btn-primary endbuttoninvsearch">
						<i class="fas fa-arrow-right"></i>
					</button>
					<input type="hidden" class="endvalueinput" value="20">
					<input type="hidden" class="endvalueinputsearch" value="'.$endval.'">
				</td>
			</tr>
		';
		return $ivar;
	}

	// filter po by materials name
	public function stc_search_product_thr_po($searchme){
		$postatus='';
		$grnopt='';
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_purchase_product_items`
			LEFT JOIN `stc_product` 
			ON `stc_purchase_product_items_product_id`=`stc_product_id`
        	LEFT JOIN `stc_purchase_product` 
			ON `stc_purchase_product_items_order_id`=`stc_purchase_product_id`
			LEFT JOIN `stc_merchant` 
			ON `stc_purchase_product_merchant_id`=`stc_merchant_id` 
			WHERE `stc_product_name` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchme)."' 
			OR `stc_product_desc` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $searchme)."'
			ORDER BY `stc_purchase_product`.`stc_purchase_product_id` DESC
			LIMIT 0,30
		");
		$ivar='';
		$do_action=mysqli_num_rows($check_loki);
		if(mysqli_num_rows($check_loki)>0){
			foreach($check_loki as $row){
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_purchase_product_items_qty`,
						`stc_purchase_product_items_rate`,
						`stc_product_gst`
					FROM `stc_purchase_product_items`
					INNER JOIN `stc_product`
					ON `stc_purchase_product_items_product_id`=`stc_product_id` 
					WHERE `stc_purchase_product_items_order_id`='".$row["stc_purchase_product_id"]."'
				");
				$poamount='';
				$poamountbas=0;
				$poamounttot=0;
				foreach ($check_loki_nest as $poamtrow) {
					$basicamt=0;
					$totalamt=0;
					$poqty=$poamtrow['stc_purchase_product_items_qty'];
					$porate=$poamtrow['stc_purchase_product_items_rate'];
					$basicamt+=$poqty * $porate;					
					$totalamt+= $basicamt + (($basicamt * $poamtrow["stc_product_gst"])/100);
					$poamountbas+=$basicamt;
					$poamounttot+=$totalamt;
				}
				$poamount.='
						<i class="fa fa-rupee-sign"></i> 
						<b>'.number_format($poamountbas, 2).'</b><br>
						<i class="fa fa-rupee-sign"></i> 
						<b>'.number_format($poamounttot, 2).'</b>
				';
				

				$callap_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_merchant_advance_payment_id`
					FROM `stc_merchant_advance_payment` 
					WHERE `stc_merchant_advance_payment_purhcase_product_id`='".$row["stc_purchase_product_id"]."'
				");
				$paymenttypes='';

				if($apamt=mysqli_num_rows($callap_loki)>0){
					$paymenttypes='
							<b>ADVANCE</b>
					';
				}else{
					$paymenttypes='NA';
				}

				$getgrndetqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_product_grn_id`,
						`stc_product_grn_date`,
						`stc_product_grn_invoice_number`,
						`stc_product_grn_invoice_date`,
						`stc_product_grn_freight_charges`,
						`stc_product_grn_packing_forwarding`,
						`stc_product_grn_others_charges`
					FROM `stc_product_grn` 
					WHERE `stc_product_grn_purchase_order_id`='".$row["stc_purchase_product_id"]."' 
				");
				$grndetails='';
				$invodetails='';
				$invoamt='';
				$str_length = 5;
				if(mysqli_num_rows($getgrndetqry)>0){
					foreach($getgrndetqry as $grndetrow){
						$grndetails.='
							<a
								href="#"
								class="stc-call-grn-via-grn-no"
								id="'.$grndetrow["stc_product_grn_id"].'"
								style="color: white;background: #359760;padding: 5px;border-radius: 50px;"
							>
								'.date('d-m-Y',strtotime($grndetrow["stc_product_grn_date"])).'<br>
       					  		GRN/'.substr("0000{$grndetrow["stc_product_grn_id"]}", -5).'<br>
       					  	</a>
						';
						$invodetails.='
							<br>'.date('d-m-Y',strtotime($grndetrow["stc_product_grn_invoice_date"])).'</br>
       					  	'.$grndetrow["stc_product_grn_invoice_number"].'
						';

						$ininvoamtgrn=0;
						$invototamtgrn=0;

						$callamot_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_product_grn_items_qty`, 
								`stc_product_grn_items_rate`,
								`stc_product_gst`
							FROM `stc_product_grn_items`
							INNER JOIN `stc_product`
							ON `stc_product_grn_items_product_id`=`stc_product_id`
							WHERE `stc_product_grn_items_grn_order_id`='".$grndetrow["stc_product_grn_id"]."'
						");

						foreach ($callamot_loki as $amtrow){
							$ininvoamt = $amtrow['stc_product_grn_items_qty'] * $amtrow['stc_product_grn_items_rate'];						
							$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
							$ininvoamtgrn += $ininvoamt;
							$invototamtgrn += $ininvototamt;
							$progst=$amtrow['stc_product_gst'];
						}

						$gstfc=($grndetrow['stc_product_grn_freight_charges'] * $progst)/100;
						$gstpf=($grndetrow['stc_product_grn_packing_forwarding'] * $progst)/100;
						$gstoc=($grndetrow['stc_product_grn_others_charges'] * $progst)/100;

						$invototamtgrna =
							($gstfc + $grndetrow['stc_product_grn_freight_charges']) + 
							($gstpf + $grndetrow['stc_product_grn_packing_forwarding']) + 
							($gstoc + $grndetrow['stc_product_grn_others_charges']) + 
							($invototamtgrn);

						$invoamt.='
							<p align="right">
								<i class="fa fa-rupee-sign"></i> 
								<b>'.number_format($ininvoamtgrn, 2).'</b>
							</p>
							<p align="right">
								<i class="fa fa-rupee-sign"></i> 
								<b>'.number_format($invototamtgrna, 2).'</b>
							</p>
						';

						$callrp_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_merchant_regular_payment_id`, 
								`stc_merchant_regular_payment_date`, 
								`stc_merchant_regular_payment_amount` 
							FROM `stc_merchant_regular_payment` 
							WHERE `stc_merchant_regular_payment_grn_number`='".$grndetrow["stc_product_grn_id"]."'
						");

						if($rpamt=mysqli_num_rows($callrp_loki)>0){
							$paymenttypes.='<br>
									<b>REGULAR</b>
							';
						}else{
							$paymenttypes.='<br>NA';
						}
					}
				}else{
					$grndetails='NA';
					$invodetails='NA';
				}

              	// hardcoded left padding if number < $str_length
              	if($row["stc_purchase_product_status"] == 'accepted'){
              		$postatus='
              			<span 
              				style="
              					background-color: #3bea22;
              					color: black;
              					padding: 8px;
              					border-radius: 40%;
              					font-weight: bold;
              					width: 110px;
              					margin: 5px;
              					display: inline-block;
              					text-align: center;
              					"
              				>'.$row["stc_purchase_product_status"].'
              			</span>
              		';
              	}elseif( $row["stc_purchase_product_status"] == 'canceled'){
              		$postatus='
              			<span 
              				style="
              					background-color: red;
              					color: black;padding: 8px;
              					border-radius: 40%;
              					font-weight: bold;
              					width: 110px;
              					margin: 5px;
              					display: inline-block;
              					text-align: center;
              				"
              			>'.$row["stc_purchase_product_status"].'
              			</span>';
              	}elseif( $row["stc_purchase_product_status"] == 'granted'){
              		$postatus='
              			<span 
              				style="
              					background-color: #3bea22;
              					color: black;padding: 8px;
              					border-radius: 40%;
              					font-weight: bold;
              					width: 110px;
              					margin: 5px;
              					display: inline-block;
              					text-align: center;
              				"
              			>'.$row["stc_purchase_product_status"].'
              			</span>';
              	}else{
              		$postatus='
              			<span 
              				style="
              					background-color: #ffe939;
              					color: black;
              					padding: 8px;
              					border-radius: 40%;
              					font-weight: bold;
              					width: 110px;
              					margin: 5px;
              					display: inline-block;
              					text-align: center;
              				"
              			>'.$row["stc_purchase_product_status"].'
              			</span>';
              	}

              	$ponumber = substr("0000{$row["stc_purchase_product_id"]}", -$str_length);

              	$permissiongrant='';

              	if(($_SESSION['stc_empl_role']==6) && ($row["stc_purchase_product_status"] == 'process')){
              		$permissiongrant='
              			<a 
							class="stc-grant-product-purchase-trigger"
							href="#"
							id="'.$row["stc_purchase_product_id"].'" 
							style="font-size: 25px;color: white;"
						><i class="fa fa-key" aria-hidden="true"></i>
						</a>
              		';
              	}elseif((($_SESSION['stc_empl_role']==9) || ($_SESSION['stc_empl_role']==1)) && ($row["stc_purchase_product_status"] == 'process')){
              		$permissiongrant='
						<a 
							class="stc-edit-product-purchase-trigger"
							href="edit-purchase-order.php?epid='.$row["stc_purchase_product_id"].'"
							id="'.$row["stc_purchase_product_id"].'" 
							style="font-size: 25px;color: white;"
						><i class="fa fa-edit" aria-hidden="true"></i>
						</a>
						<a 
							href="print-preview.php?pid='.$row["stc_purchase_product_id"].'&postatus='.$row["stc_purchase_product_status"].'" 
							style="font-size: 25px;color: white;"
						><i class="fa fa-print" aria-hidden="true"></i>
						</a>
              		';
              	}elseif((($_SESSION['stc_empl_role']==9) || ($_SESSION['stc_empl_role']==1)) && ($row["stc_purchase_product_status"] == 'canceled')){
              		$permissiongrant='
						<a 
							class="stc-edit-product-purchase-trigger"
							href="edit-purchase-order.php?epid='.$row["stc_purchase_product_id"].'&epstatus='.$row["stc_purchase_product_status"].'"
							id="'.$row["stc_purchase_product_id"].'" 
							style="font-size: 25px;color: white;"
						><i class="fa fa-edit" aria-hidden="true"></i>
						</a>
						<a 
							href="print-preview.php?pid='.$row["stc_purchase_product_id"].'&postatus='.$row["stc_purchase_product_status"].'" 
							style="font-size: 25px;color: white;"
						><i class="fa fa-print" aria-hidden="true"></i>
						</a>
              		';
              	}else{
              		$permissiongrant='
						<a 
							href="print-preview.php?pid='.$row["stc_purchase_product_id"].'&postatus='.$row["stc_purchase_product_status"].'" 
							style="font-size: 25px;color: white;"
						><i class="fa fa-print" aria-hidden="true"></i>
						</a>
              		';
              	}
				$requisit_no='';
              	if($row["stc_purchase_product_req_no"]==0){
              		$requisit_no='NA';
              	}else{
              		$requisit_no='
              			<a href="stc-requisition-combiner-fshow.php?requi_id='.$row["stc_purchase_product_req_no"].'" >STC/PM/'.$row["stc_purchase_product_req_no"].'</a>
              		';
              	}

				$ivar.='
					<tr>
						<td>
							<h6>'.$row["stc_merchant_name"].'</h6>
						</td>
						<td align="center">
							'.date('d-m-Y',strtotime($row["stc_purchase_product_order_date"])).'<br>
							STC/'.$ponumber.'
						</td>
						<td align="center">
							'.$grndetails.'
						</td>
						<td align="center">
							'.$invodetails.'
						</td> 					
						<td align="right">
							'.$poamount.'
						</td>
						<td align="center">
							'.$paymenttypes.'
						</td>
						<td>
							'.$row["stc_purchase_product_purchased_for"].'<br>
							'.$requisit_no.'
						</td>
						<td>
							<h6 class="text-uppercase text-center">'.$postatus.'</h6>
						</td>
						<td align="center">
						'.$permissiongrant.'
						</td>
					</tr>
		    	';	
			}
		}else{
			$ivar .= '
				<tr>
					<td colspan="9">
						<a 
							href="#" 
							class="btn btn-primary btn-block text-uppercase mb-3">
							No Records Found!!!
						</a>
					</td>
				</tr>
			';
		}
		return $ivar;
	}

	// call po items 
	public function stc_getpoitems_by_po_id($po_id){
		$ivar='';
		$sl=0;
		$total=0;
		$totalgst=0;
		$totalbasic=0;
		$totalincgst=0;

		$ivar_getqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM 
				`stc_purchase_product_items`
			LEFT JOIN 
				`stc_product`
			ON 
				`stc_purchase_product_items_product_id`=`stc_product_id`
			WHERE 
				`stc_purchase_product_items_order_id`='".mysqli_real_escape_string($this->stc_dbs, $po_id)."'
		");

		$dataqty='';
		if(mysqli_num_rows($ivar_getqry)>0){
			foreach ($ivar_getqry as $row) {
			  $sl++;
			  $amount=$row['stc_purchase_product_items_qty'] * $row['stc_purchase_product_items_rate'];
			  $gstamount = ($amount * $row["stc_product_gst"])/100;
			  $ivar.="
					<tr>
						<td class='text-center'>".$sl."</td>
						<td class='text-center'>".$row['stc_product_id']."</td>
						<td>".$row['stc_product_name']."</td>
						<td class='text-center'>".$row['stc_product_unit']."</td>
						<td>
							<input 
								type='text' 
								class='form-control stcgrantqtyepoi".$row["stc_purchase_product_items_id"]."'
								placeholder='Please Enter Quantity' 
								value='".$row['stc_purchase_product_items_qty']."'
								style='width:120px;'
							>
							<a 
								id='".$row["stc_purchase_product_items_id"]."' 
								style='background: yellow;border-radius: 50%;padding: 5px;margin: 45px;font-size: 20px;'
								class='stcgrantchangepoqty' 
								href='#'
							><i class='fas fa-arrow-right'></i>
							</a>
						</td>
						<td class='text-right'><i class='fas fa-rupee-sign'></i> ".number_format($row['stc_purchase_product_items_rate'], 2)."</td>
						<td><i class='fas fa-rupee-sign'></i> ".number_format($amount, 2)."</td>
						<td class='text-center'>".$row['stc_product_gst']."%</td>
						<td class='text-right'><i class='fas fa-rupee-sign'></i> ".number_format($amount + $gstamount, 2)."
						</td>
						<td align='right'>
							<a class='btn btn-danger deltogpoitems' id='".$row["stc_purchase_product_items_id"]."' href='#' style='font-size:40px;text-align:center;color:#fff;'>
								<i class='fa fa-times'></i>
							</a>
						</td>
					</tr>
			  ";
			  $totalbasic+=$amount;
			  $totalincgst+=$gstamount;
			}

			$total=$totalbasic + $totalincgst;
			$ivar.="
				<tr>
					<td colspan='8' align='right'>Total Basic : </td>
					<td align='right'><i class='fas fa-rupee-sign'></i> ".number_format($totalbasic, 2)."</td>
					<td></td>
				</tr>
				<tr>
					<td colspan='8' align='right'>Total GST (Tax) : </td>
					<td align='right'><i class='fas fa-rupee-sign'></i> ".number_format($totalincgst, 2)."</td>
					<td></td>
				</tr>
				<tr>
					<td colspan='8' align='right'>Grand Total : </td>
					<td align='right'><i class='fas fa-rupee-sign'></i> ".number_format($total, 2)."</td>
					<td></td>
				</tr>
			";
		}else{
			$ivar.='
				<tr>
					<td colspan="10" class="text-center">No record found!!!</td>
				</tr>
			';
		}

		return $ivar;
	}

	// change quantity 
	public function stc_changepo_qnty_for_grant_po($po_items_id, $po_items_qty){
		$ivar='';
		$ivar_changeqtyqry=mysqli_query($this->stc_dbs, "
			UPDATE
				`stc_purchase_product_items`
			SET
				`stc_purchase_product_items_qty` = '".mysqli_real_escape_string($this->stc_dbs, $po_items_qty)."'
			WHERE
				`stc_purchase_product_items_id` = '".mysqli_real_escape_string($this->stc_dbs, $po_items_id)."'
		");
		if($ivar_changeqtyqry){
			$ivar='Purchase order quantity updated.';
		}else{
			$ivar='Hmmm!!!Something went wrong on updating of purchase order quantity.';
		}
		return $ivar;
	}

	// delete po items 
	public function stc_deletpo_items_for_grant_po($po_items_id){
		$ivar='';
		$ivar_deleteqtyqry=mysqli_query($this->stc_dbs, "
			DELETE
			FROM
				`stc_purchase_product_items`
			WHERE
				`stc_purchase_product_items_id` =  '".mysqli_real_escape_string($this->stc_dbs, $po_items_id)."'
		");
		if($ivar_deleteqtyqry){
			$ivar='Purchase order item deleted.';
		}else{
			$ivar='Hmmm!!!Something went wrong on deleting of purchase order item.';
		}
		return $ivar;
	}

	// change po to granted 
	public function stc_changepo_to_grant($po_id){
		$ivar='';
		$ivar_setgrqry=mysqli_query($this->stc_dbs, "
			UPDATE
				`stc_purchase_product`
			SET
				`stc_purchase_product_status` = 'granted'
			WHERE
				`stc_purchase_product_id` = '".mysqli_real_escape_string($this->stc_dbs, $po_id)."'
		");
		if($ivar_setgrqry){
			$ivar='Purchase order status changed.';
		}else{
			$ivar='Hmmm!!!Something went wrong purchase order status not changed.';
		}
		return $ivar;
	}

	// change po to cancel 
	public function stc_changepo_to_cancel($po_id){
		$ivar='';
		$ivar_setgrqry=mysqli_query($this->stc_dbs, "
			UPDATE
				`stc_purchase_product`
			SET
				`stc_purchase_product_status` = 'canceled'
			WHERE
				`stc_purchase_product_id` = '".mysqli_real_escape_string($this->stc_dbs, $po_id)."'
		");
		if($ivar_setgrqry){
			$ivar='Purchase order status changed.';
		}else{
			$ivar='Hmmm!!!Something went wrong purchase order status not changed.';
		}
		return $ivar;
	}
}

// edit poclass
class ragnarPurchaseEdit extends tesseract{
	// call product for edit
	public function stc_getpd_by_multiple_inp_for_editpo($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout){
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
			SELECT 
				`stc_product_id`,
				`stc_product_name`,
			    `stc_cat_name`,
			    `stc_sub_cat_name`,
			    `stc_brand_title`,
				`stc_product_hsncode`,
				`stc_product_unit`,
			    `stc_product_gst`,
			    `stc_product_image`,
			    `stc_item_inventory_pd_qty`
			FROM `stc_product` 
			LEFT JOIN `stc_category` 
			ON `stc_product_cat_id`=`stc_cat_id` 
			LEFT JOIN `stc_sub_category` 
			ON `stc_product_sub_cat_id`=`stc_sub_cat_id` 
			LEFT JOIN `stc_brand` 
			ON `stc_product_brand_id`=`stc_brand_id` 
			LEFT JOIN `stc_rack` 
			ON `stc_rack_id`=`stc_product_rack_id` 
			LEFT JOIN `stc_item_inventory` 
			ON `stc_item_inventory_pd_id`=`stc_product_id`
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
							    <div class="box-up for'.$filterrow["stc_product_id"].'">
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
												id="stceditpoqty'.$filterrow["stc_product_id"].'"
												name="stcpoqty"
												type="number"
												placeholder="Quantity"
												class="form-control validate"
											/>
										</div>
										<input 
			             	         		type="hidden" 
			             	         		name="hidden_name" 
			             	         		id="stceditpopdname'.$filterrow["stc_product_id"].'" 
			             	         		value="'.htmlentities($filterrow["stc_product_name"]).'" 
										/>
										<input 
			             	         		type="hidden" 
			             	         		name="hidden_unit" 
			             	         		id="stceditpopdunit'.$filterrow["stc_product_id"].'" 
			             	         		value="'.$filterrow["stc_product_unit"].'" 
										/>
										<input 
			             	         		type="hidden" 
			             	         		name="hidden_hsncode" 
			             	         		id="stceditpopdhsncode'.$filterrow["stc_product_id"].'" 
			             	         		value="'.$filterrow["stc_product_hsncode"].'" 
										/>
										<input 
			             	         		type="hidden" 
			             	         		name="hidden_gst" 
			             	         		id="stceditpopdgst'.$filterrow["stc_product_id"].'" 
			             	         		value="'.$filterrow["stc_product_gst"].'" 
										/>
										<div class="a-size">
											<input 
												type="number" 
												name="stcpdprice" 
												id="stceditpdprice'.$filterrow["stc_product_id"].'" 
												placeholder="Price"
												class="form-control validate"
											/>
							        	</div>
							      	</div>
							    </div>

								<div class="box-down">
									<div class="h-bg">
										<div class="h-bg-inner"></div>
									</div>

									<a class="cart add_to_cartforedit" href="#" id="'.$filterrow["stc_product_id"].'">
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

	// add po line items to the table
	public function stc_add_po_lineItem($product_id, $product_poid, $product_quantity, $product_price){
		$odin='';
		$loki_checkqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_purchase_product_items`
			WHERE `stc_purchase_product_items_product_id`='".mysqli_real_escape_string($this->stc_dbs, $product_id)."'
			AND `stc_purchase_product_items_order_id`='".mysqli_real_escape_string($this->stc_dbs, $product_poid)."'
		");
		if(mysqli_num_rows($loki_checkqry)>0){
			$editemsid=0;
			$edproduct_quantity=0;
			foreach($loki_checkqry as $editrow){
				$edproduct_quantity=$editrow['stc_purchase_product_items_qty'];
				$editemsid=$editrow['stc_purchase_product_items_id'];
			}
			$product_quantity+=$edproduct_quantity;
			$loki_addlitems=mysqli_query($this->stc_dbs, "
				UPDATE `stc_purchase_product_items` 
				SET `stc_purchase_product_items_qty`='".mysqli_real_escape_string($this->stc_dbs, $product_quantity)."'
				WHERE `stc_purchase_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $editemsid)."'
			");
			if($loki_addlitems){
				$odin = "Item Added!!";
			}else{
				$odin = "Please recheck & try again";
			}
		}else{
			$loki_addlitems=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_purchase_product_items`(
					`stc_purchase_product_items_order_id`, 
					`stc_purchase_product_items_product_id`, 
					`stc_purchase_product_items_qty`, 
					`stc_purchase_product_items_rate`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $product_poid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $product_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $product_quantity)."',
					'".mysqli_real_escape_string($this->stc_dbs, $product_price)."'
				)
			");
			if($loki_addlitems){
				$odin = "Item Added!!";
			}else{
				$odin = "Please recheck & try again";
			}
		}
		return $odin;
	}

	// call product line items from db
	public function stc_update_po_lineItemQty($pdid, $pdqty){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			UPDATE `stc_purchase_product_items` 
			SET `stc_purchase_product_items_qty`='".mysqli_real_escape_string($this->stc_dbs, $pdqty)."'
			WHERE `stc_purchase_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $pdid)."'
		");
		if($loki_get_max_id){
			$odin = "Item Quantity Updated!!";
		}else{
			$odin = "Please recheck & try again";
		}
		return $odin;
	}

	// edit po line rate 
	public function stc_update_po_lineItemRate($pdid, $pdprice){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			UPDATE `stc_purchase_product_items` 
			SET `stc_purchase_product_items_rate`='".mysqli_real_escape_string($this->stc_dbs, $pdprice)."'
			WHERE `stc_purchase_product_items_id`='".mysqli_real_escape_string($this->stc_dbs, $pdid)."'
		");
		if($loki_get_max_id){
			$odin = "Item Price Updated!!";
		}else{
			$odin = "Please recheck & try again";
		}
		return $odin;
	}

	// edit po delete line items
	public function stc_delete_po_lineItem($pdid){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_purchase_product_items` WHERE `stc_purchase_product_items_id`='".$pdid."'
		");
		if($loki_get_max_id){
			$odin = "Item Deleted!!";
		}else{
			$odin = "Please recheck & try again";
		}
		return $odin;
	}

	// edit po save updated
	public function stc_update_po($pro_id, $mername, $tandc, $gto_number, $req_number){
		$odin='';
		$loki_update_po=mysqli_query($this->stc_dbs, "
			UPDATE `stc_purchase_product` 
			SET 
				`stc_purchase_product_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $mername)."', 
				`stc_purchase_product_notes`='".mysqli_real_escape_string($this->stc_dbs, $tandc)."', 
				`stc_purchase_product_purchased_for`='".mysqli_real_escape_string($this->stc_dbs, $gto_number)."',
				`stc_purchase_product_req_no`='".mysqli_real_escape_string($this->stc_dbs, $req_number)."'
			WHERE `stc_purchase_product_id`='".mysqli_real_escape_string($this->stc_dbs, $pro_id)."'
		");
		if($loki_update_po){
			$odin = "PO updated!!";
		}else{
			$odin = "Please recheck & try again";
		}
		return $odin;
	}

	// change po status edit
	public function stc_update_status_po($epono){
		$ivar='';
		$ivar_setgrqry=mysqli_query($this->stc_dbs, "
			UPDATE
				`stc_purchase_product`
			SET
				`stc_purchase_product_status` = 'process'
			WHERE
				`stc_purchase_product_id` = '".mysqli_real_escape_string($this->stc_dbs, $epono)."'
		");
		if($ivar_setgrqry){
			$ivar='Purchase order status changed.';
		}else{
			$ivar='Hmmm!!!Something went wrong purchase order status not changed.';
		}
		return $ivar;
	}
}

// show grn class
class ragnarGRNShow extends tesseract{
	// call grn items
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
						    <th scope="col">Item Code</th>
						    <th scope="col" style="width: 20%;">Items</th>
						    <th scope="col">HSN Code</th> 
						    <th scope="col">Unit</th>
						    <th scope="col">Qty</th>                         
						    <th scope="col">Price</th>
						    <th scope="col">Amount</th>
						    <th scope="col">GST</th>
						    <th scope="col">Total GST</th>
						    <th scope="col">Total Amount</th>
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
							<td>'.$values["stc_product_id"].'</td>
							<td>'.$values["stc_product_name"].'</td>
							<td>'.$values["stc_product_hsncode"].'</td>
							<td>'.$values["stc_product_unit"].'</td>
							<td align="right">'.number_format($values["stc_product_grn_items_qty"], 2).'</td>
							<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($values["stc_product_grn_items_rate"], 2).'</td>
							<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
							<td align="right"> '.$values["stc_product_gst"].'% </td>
							<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($gstamount, 2).'</td>
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
					  <td colspan="10"><h4 align="right">Total</h4></td>
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
					  <td colspan="10"><h4 align="right">CGST</h4></td>
					  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
					</tr>
					<tr>
					  <td colspan="10"><h4 align="right">SGST</h4></td>
					  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
					</tr>
					<tr>
					  <td colspan="10"><h4 align="right">Total Tax</h4></td>
					  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst, 2).'</td>
					</tr>
	            	<tr>
	            	  <td colspan="10"><h4 align="right">Grand Total</h4></td>
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
						<td colspan="10" align="center">  
						    <h3>GRN Cart Empty!!!</h3>
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

	// call po from vendor
	public function stc_call_po_from_vendor($merchantid){
		$ivar='<option value="NA">Please Select PO!!!</option>';
		$ivarpoqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_purchase_product_id` 
			FROM `stc_purchase_product` 
			WHERE `stc_purchase_product_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $merchantid)."'
			AND (
			    `stc_purchase_product_status`='granted' 
			    OR
			    `stc_purchase_product_status`='accepted'
			)
			ORDER BY `stc_purchase_product_id` DESC
		");

		if(mysqli_num_rows($ivarpoqry)>0){
			foreach($ivarpoqry as $porow){
				$ponumber = substr("0000{$porow['stc_purchase_product_id']}", -5);
				$ivar.='
					<option value="'.$porow['stc_purchase_product_id'].'">STC/'.$ponumber.'</option>
				';
			}
		}else{
			$ivar.='
				<option value="NA">No Po Found!!!</option>
			';
		}
		return $ivar;
	}

	// call po date from po number
	public function stc_call_podate_from_poid($poid){
		$ivar="";
		$ivarpoqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_purchase_product_order_date` 
			FROM `stc_purchase_product` 
			WHERE `stc_purchase_product_id`='".mysqli_real_escape_string($this->stc_dbs, $poid)."'
			ORDER BY `stc_purchase_product_id` DESC
		");
		if(mysqli_num_rows($ivarpoqry)>0){
			foreach($ivarpoqry as $porow){
				$ivar=date("d-m-Y h:i:s", strtotime($porow['stc_purchase_product_order_date']));
			}
		}else{
			$ivar='Hmmm!!! A PO without PO Date??? I think there is a fault.';
		}
		return $ivar;
	}

	// check grn existance
	public function stc_check_invoice_existance($invoice_no, $invoice_date, $merchant_id){
		$ivar="";
		$ivarcheckinvexistqry=mysqli_query($this->stc_dbs, "
			SELECT `stc_product_grn_id` FROM `stc_product_grn` 
			WHERE `stc_product_grn_invoice_number`='".mysqli_real_escape_string($this->stc_dbs, $invoice_no)."' 
            AND DATE(`stc_product_grn_date`)='".mysqli_real_escape_string($this->stc_dbs, $invoice_date)."'
			AND `stc_product_grn_merchant_id`='".mysqli_real_escape_string($this->stc_dbs, $merchant_id)."'
		");
		if(mysqli_num_rows($ivarcheckinvexistqry)>0){
			$ivar='Please Check Invoice Number or Invoice Date, Beacuase This Invoice Already in My GRN. :)';
		}else{
			$ivarcheckexistqrydc=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_sale_product_id` 
				FROM `stc_sale_product` 
				WHERE `stc_sale_product_dc_invo_no`='".mysqli_real_escape_string($this->stc_dbs, $invoice_no)."'
				AND DATE(`stc_sale_product_dc_invo_date`)='".mysqli_real_escape_string($this->stc_dbs, $invoice_date)."' 
				AND `stc_sale_product_dc_merchant`='".mysqli_real_escape_string($this->stc_dbs, $merchant_id)."'
			");
			if(mysqli_num_rows($ivarcheckexistqrydc)>0){
				$ivar='Please Check Invoice Number or Invoice Date, Beacuase This Invoice Already in My Direct Challan. :)';
			}else{
				$ivar='no';
			}
		}
		return $ivar;
	}

	// call items from po for grn
	public function stc_call_items_for_grn($po_no){
		$ivar='
			<table class="table table-hover grn_data" align="centre">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Item Code</th>
                      <th scope="col" style="width: 15%;">Items Desc</th>
                      <th scope="col">HSN Code</th> 
                      <th scope="col">Unit</th>
                      <th scope="col">PO Qty</th>
                      <th scope="col">Accept Qty</th>
                      <th scope="col" width="10%">Price</th>
                      <th scope="col" width="10%">Amount</th>
                      <th scope="col">GST</th>
                      <th scope="col" width="10%">Amount</th>
                      <th scope="col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
		';
		$ivargrnqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_purchase_product_items`
			LEFT JOIN `stc_product`
			ON `stc_purchase_product_items_product_id`=`stc_product_id`
			WHERE `stc_purchase_product_items_order_id`='".mysqli_real_escape_string($this->stc_dbs, $po_no)."'
		");
		if(mysqli_num_rows($ivargrnqry)>0){
			$sl=0;
			$total=0;
			$totalgst=0;
			foreach($ivargrnqry as $grnrow){
				$ivargetqtyofpd=mysqli_query($this->stc_dbs, "
					SELECT 
						SUM(`stc_product_grn_items_qty`) as acceptedv 
					FROM `stc_product_grn_items` 
					WHERE `stc_product_grn_items_purchase_order_id`='".mysqli_real_escape_string($this->stc_dbs, $po_no)."' 
					AND `stc_product_grn_items_product_id`='".mysqli_real_escape_string($this->stc_dbs, $grnrow['stc_purchase_product_items_product_id'])."'
				"); 
				$outting=0;
				foreach($ivargetqtyofpd as $eqtyrow){
					$outting+=$eqtyrow['acceptedv'];
				}

				$sl++;
				$amount=$grnrow['stc_purchase_product_items_qty'] * $grnrow['stc_purchase_product_items_rate'];
				$gstamount = ($amount * $grnrow["stc_product_gst"])/100;
				if($grnrow['stc_purchase_product_items_qty'] - $outting != 0 ){
				  $ivar.="
				    	<tr>
							<td>".$sl."</td>
							<td>".$grnrow['stc_product_id']."</td>
							<td>".$grnrow['stc_product_name']."</td>
							<td>".$grnrow['stc_product_hsncode']."</td>
							<td>".$grnrow['stc_product_unit']."</td>
							<td>".number_format($grnrow['stc_purchase_product_items_qty'], 2)."</td>
							<td width='12%'>  
								<input 
									type='hidden' 
									class='form-control stcpricegrnt".$grnrow["stc_product_id"]."'
									style='width:100%;'
									value='".$grnrow['stc_purchase_product_items_rate']."'
								> 
								<input 
									type='hidden' 
									class='form-control stcorderid".$grnrow["stc_product_id"]."'
									style='width:100%;'
									value='".$po_no."'
								>                  
								<input 
									type='text' 
									class='form-control stcqtygrnt".$grnrow["stc_product_id"]."' 
									placeholder='Quantity' 
									value='".$outting."'
									style='width:100%;'
								>                            
							</td>
							<td><i class='fas fa-rupee-sign'></i> ".number_format($grnrow['stc_purchase_product_items_rate'], 2)."</td>
							<td><i class='fas fa-rupee-sign'></i> ".number_format($amount, 2)."</td>
							<td>".$grnrow['stc_product_gst']."%</td>
							<td align='right'><i class='fas fa-rupee-sign'></i> ".number_format($amount + $gstamount, 2)."
							</td>
							<td align='right'>
								<a 	
									class='btn btn-success addtogrn' 
									id='".$grnrow["stc_product_id"]."' 
									href='#' 
									style='font-size:40px;text-align:center;color:#fff;'
								><i class='fas fa-check-circle'></i>
								</a>
							</td>
				    	</tr>
				  ";
				}else{
				  $ivar.="
				    <tr>
				      <td>".$sl."</td>
				      <td>".$grnrow['stc_product_name']."</td>
				      <td align='center' colspan='9'>This Item is Accepted!!!</td>
				    </tr>
				  ";
				}
				$total = $total + ($grnrow['stc_purchase_product_items_qty'] * $grnrow['stc_purchase_product_items_rate']);  
				$totalgst +=$gstamount;
				
			}
			$ivar.='
			    <tr>
					<td colspan="10"><h4 align="right">Total</h4></td>
					<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total, 2).'</td>
			    </tr>
			    <tr>
					<td colspan="10"><h4 align="right">CGST</h4></td>
					<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
			    </tr>
			    <tr>
					<td colspan="10"><h4 align="right">SGST</h4></td>
					<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst/2, 2).'</td>
			    </tr>
			    <tr>
					<td colspan="10"><h4 align="right">Total Tax</h4></td>
					<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($totalgst, 2).'</td>
			    </tr>
			    <tr>
					<td colspan="10"><h4 align="right">Grand Total</h4></td>
					<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total + $totalgst, 2).'</td>
			    </tr>
			';
		}else{
			$ivar='
					<tr>
						<td colspan="10"><h4 align="right">Grand Total</h4></td>No Items Found!!!</td>
			    	</tr>
			';
		}

		$ivar.='
				</tbody>
        	</table>
        	<textarea 
                    row="2"
                    placeholder="Notes"
                    class="form-control notesgrn"
                  ></textarea>
        	<button 
			  type="submit" 
			  class="btn btn-primary btn-block text-uppercase stcsavegrn"
			>Save</button>
        ';
        return $ivar;
	}

	// grn items cart 
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

	// send stocking 
	public function stc_stocking_send($refr_id, $item_for){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$odin_getqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_electronics_stocking_id`
			FROM
				`stc_electronics_stocking`
			WHERE
				`stc_electronics_stocking_refrence_id` = '".mysqli_real_escape_string($this->stc_dbs, $refr_id)."'
		");
		if(mysqli_num_rows($odin_getqry)>0){
			$odin='This GRN item is already in a record.';
		}else{
			$odin_setqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_electronics_stocking`(
				    `stc_electronics_stocking_date`,
				    `stc_electronics_stocking_refrence_id`,
				    `stc_electronics_stocking_refrence_type`,
				    `stc_electronics_stocking_for`
				) VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".mysqli_real_escape_string($this->stc_dbs, $refr_id)."',
					'1',
					'".mysqli_real_escape_string($this->stc_dbs, $item_for)."'
				)
			");
			if($odin_setqry){
				$odin_getitemqry=mysqli_query($this->stc_dbs, "
					SELECT
						`stc_product_grn_items_product_id`,
						`stc_product_grn_items_qty`,
						`stc_product_grn_items_rate`
					FROM
						`stc_product_grn_items`
					WHERE
						`stc_product_grn_items_grn_order_id` = '".mysqli_real_escape_string($this->stc_dbs, $refr_id)."'
				");
				foreach($odin_getitemqry as $odin_getitemrow){
					$odin_getiteinvqry=mysqli_query($this->stc_dbs, "
						SELECT
							`stc_electronics_inventory_id`,
							`stc_electronics_inventory_item_qty`
						FROM
							`stc_electronics_inventory`
						WHERE
							`stc_electronics_inventory_item_id` = '".$odin_getitemrow['stc_product_grn_items_product_id']."'
					");
					if(mysqli_num_rows($odin_getiteinvqry)>0){
						$invqty=0;
						foreach($odin_getiteinvqry as $odin_getiteinvrow){
							$invqty+=$odin_getiteinvrow['stc_electronics_inventory_item_qty'];
						}
						$crrqty=0;
						if($invqty>0){
							$crrqty=$invqty + $odin_getitemrow['stc_product_grn_items_qty'];
							$odin_setqry=mysqli_query($this->stc_dbs, "
								UPDATE 
									`stc_electronics_inventory` 
								SET
									`stc_electronics_inventory_item_qty` = '".mysqli_real_escape_string($this->stc_dbs, $crrqty)."',
									`stc_electronics_inventory_dp` = '".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_rate'])."', 
									`stc_electronics_inventory_mrp` = '".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_rate'])."', 
									`stc_electronics_inventory_condition` = '1', 
									`stc_electronics_inventory_sale_rate` = '".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_rate'])."'
								WHERE 
									`stc_electronics_inventory_item_id` = '".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_product_id'])."'
							");
						}else{
							$odin_setqry=mysqli_query($this->stc_dbs, "
								UPDATE 
									`stc_electronics_inventory` 
								SET
									`stc_electronics_inventory_item_qty` = '".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_qty'])."',
									`stc_electronics_inventory_dp` = '".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_rate'])."', 
									`stc_electronics_inventory_mrp` = '".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_rate'])."', 
									`stc_electronics_inventory_condition` = '1', 
									`stc_electronics_inventory_sale_rate` = '".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_rate'])."'
								WHERE 
									`stc_electronics_inventory_item_id` = '".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_product_id'])."'
							");
						}
					}else{
						$odin_setqry=mysqli_query($this->stc_dbs, "
							INSERT INTO `stc_electronics_inventory`(
								`stc_electronics_inventory_item_id`,
								`stc_electronics_inventory_item_qty`,
								`stc_electronics_inventory_dp`,
								`stc_electronics_inventory_mrp`,
								`stc_electronics_inventory_condition`,
								`stc_electronics_inventory_sale_rate`
							)VALUES(
								'".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_product_id'])."',
								'".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_qty'])."',
								'".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_rate'])."',
								'".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_rate'])."',
								'1',
								'".mysqli_real_escape_string($this->stc_dbs, $odin_getitemrow['stc_product_grn_items_rate'])."'
							)
						");
					}
				}
				$odin='GRN Item Uploaded Successfully.';
			}else{
				$odin='Hmmm. Someting went wrong on stocking.';
			}
		}
		return $odin;
	}
}

// add grn class
class ragnarGRNAdd extends tesseract{

	// grn go on grn table
	public function grn_process($grn_order_id, $grn_invodate, $grn_invonumber, $grn_stcfc, $grn_stcpf, $grn_scoc, $grnnotes){	
		$odin='';
		$date=date("Y-m-d H:i:s");		
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
					'".$date."', 
					'".$stc_product_grn_purchase_order_date."', 
					'".$stc_product_grn_invoice_number."', 
					'".$stc_product_grn_invoice_date."', 
					'".$stc_product_grn_merchant_id."', 
					'".$stc_product_grn_freight_charges."', 
					'".$stc_product_grn_packing_forwarding."', 
					'".$stc_product_grn_others_charges."',
					'".$_SESSION['stc_empl_id']."',
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
}

// add po adhoc class
class ragnarPurchaseAdhoc extends tesseract{

	// save po adhoc trigger
	public function stc_po_adhoc_save($itemcode, $itemname, $quantity, $prate, $rate, $unit, $rack, $condition, $source, $destination, $receivedby, $remarks){	
		$odin='';
		$date=date("Y-m-d H:i:s");		
		$lokipo=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_purchase_product_adhoc`(
				`stc_purchase_product_adhoc_productid`,
				`stc_purchase_product_adhoc_itemdesc`,
				`stc_purchase_product_adhoc_qty`,
				`stc_purchase_product_adhoc_prate`,
				`stc_purchase_product_adhoc_rate`,
				`stc_purchase_product_adhoc_unit`,
				`stc_purchase_product_adhoc_rackid`,
				`stc_purchase_product_adhoc_condition`,
				`stc_purchase_product_adhoc_source`,
				`stc_purchase_product_adhoc_destination`,
				`stc_purchase_product_adhoc_recievedby`,
				`stc_purchase_product_adhoc_status`,
				`stc_purchase_product_adhoc_remarks`,
				`stc_purchase_product_adhoc_created_by`,
				`stc_purchase_product_adhoc_created_date`
			)VALUES(
				'".mysqli_real_escape_string($this->stc_dbs, $itemcode)."',
				'".mysqli_real_escape_string($this->stc_dbs, $itemname)."',
				'".mysqli_real_escape_string($this->stc_dbs, $quantity)."',
				'".mysqli_real_escape_string($this->stc_dbs, $prate)."',
				'".mysqli_real_escape_string($this->stc_dbs, $rate)."',
				'".mysqli_real_escape_string($this->stc_dbs, $unit)."',
				'".mysqli_real_escape_string($this->stc_dbs, $rack)."',
				'".mysqli_real_escape_string($this->stc_dbs, $condition)."',
				'".mysqli_real_escape_string($this->stc_dbs, $source)."',
				'".mysqli_real_escape_string($this->stc_dbs, $destination)."',
				'".mysqli_real_escape_string($this->stc_dbs, $receivedby)."',
				'3',
				'".mysqli_real_escape_string($this->stc_dbs, $remarks)."',
				'".$_SESSION['stc_empl_id']."',
				'".$date."'
			)
		");
		if($lokipo){
			$odin="success";
		}else{
			$odin="failed";
		}
		return $odin;
	}

	public function stc_update_brequisition_status($requisition_id){
		$odin='';
		$query="UPDATE gld_requisitions SET buy_status = 3 WHERE id = $requisition_id";
		if(mysqli_query($this->stc_dbs, $query)){
			$odin="success";
		}
	}

	// call po adhoc
	public function stc_call_poadhoc($itemname, $sourcedestination, $byrack, $status, $page, $pageSize){
		$odin='';
		$filter='';
		if($itemname!=""){
			$filter.="AND (`stc_purchase_product_adhoc_itemdesc` regexp '".mysqli_real_escape_string($this->stc_dbs, $itemname)."' OR `stc_product_name` regexp '".mysqli_real_escape_string($this->stc_dbs, $itemname)."' OR `stc_product_desc` regexp '".mysqli_real_escape_string($this->stc_dbs, $itemname)."' OR `stc_purchase_product_adhoc_id`='".mysqli_real_escape_string($this->stc_dbs, $itemname)."' OR `stc_product_id`='".mysqli_real_escape_string($this->stc_dbs, $itemname)."')";
		}
		if($sourcedestination!=""){
			$filter.="
				AND (`stc_purchase_product_adhoc_source` regexp '".mysqli_real_escape_string($this->stc_dbs, $sourcedestination)."'
				OR `stc_purchase_product_adhoc_destination` regexp '".mysqli_real_escape_string($this->stc_dbs, $sourcedestination)."')
			";
		}
		if($byrack!=""){
			$filter.="AND `stc_rack_name` regexp '".mysqli_real_escape_string($this->stc_dbs, $byrack)."'";
		}
		if($status!="NA"){
			$filter.="AND `stc_purchase_product_adhoc_status`='".mysqli_real_escape_string($this->stc_dbs, $status)."'";
		}
		$query="
			SELECT
				`stc_purchase_product_adhoc_id`,
				`stc_purchase_product_adhoc_productid`,
				`stc_product_id`,
				`stc_product_name`,
				`stc_sub_cat_name`,
				`stc_product_image`,
				`stc_purchase_product_adhoc_itemdesc`,
				`stc_purchase_product_adhoc_qty`,
				`stc_purchase_product_adhoc_prate`,
				`stc_purchase_product_adhoc_rate`,
				`stc_purchase_product_adhoc_unit`,
				`stc_rack_name`,
				`stc_purchase_product_adhoc_condition`,
				`stc_purchase_product_adhoc_source`,
				`stc_purchase_product_adhoc_destination`,
				`stc_purchase_product_adhoc_recievedby`,
				`stc_purchase_product_adhoc_status`,
				`stc_purchase_product_adhoc_remarks`,
				`stc_user_name`,
				`stc_purchase_product_adhoc_created_date`,
				`stc_purchase_product_adhoc_updated_by`,
				`stc_purchase_product_adhoc_updated_date`
			FROM `stc_purchase_product_adhoc`
			LEFT JOIN `stc_product` ON `stc_purchase_product_adhoc_productid`=`stc_product_id`
			LEFT JOIN `stc_sub_category` ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
			LEFT JOIN `stc_rack` ON `stc_purchase_product_adhoc_rackid`=`stc_rack_id`
			LEFT JOIN `stc_user` ON `stc_purchase_product_adhoc_created_by`=`stc_user_id`
			WHERE `stc_purchase_product_adhoc_qty`>0 ".$filter."
			ORDER BY TIMESTAMP(`stc_purchase_product_adhoc_created_date`) DESC
		";
		$query .= " LIMIT " . ($page - 1) * $pageSize . ", " . $pageSize;
		$odinqry=mysqli_query($this->stc_dbs, $query);
		$count_numqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_purchase_product_adhoc_id`,
				`stc_purchase_product_adhoc_productid`,
				`stc_product_id`,
				`stc_product_name`,
				`stc_product_image`,
				`stc_purchase_product_adhoc_itemdesc`,
				`stc_purchase_product_adhoc_qty`,
				`stc_purchase_product_adhoc_prate`,
				`stc_purchase_product_adhoc_rate`,
				`stc_purchase_product_adhoc_unit`,
				`stc_rack_name`,
				`stc_purchase_product_adhoc_condition`,
				`stc_purchase_product_adhoc_source`,
				`stc_purchase_product_adhoc_destination`,
				`stc_purchase_product_adhoc_recievedby`,
				`stc_purchase_product_adhoc_status`,
				`stc_purchase_product_adhoc_remarks`,
				`stc_user_name`,
				`stc_purchase_product_adhoc_created_date`,
				`stc_purchase_product_adhoc_updated_by`,
				`stc_purchase_product_adhoc_updated_date`
			FROM `stc_purchase_product_adhoc`
			LEFT JOIN `stc_product` ON `stc_purchase_product_adhoc_productid`=`stc_product_id`
			LEFT JOIN `stc_rack` ON `stc_purchase_product_adhoc_rackid`=`stc_rack_id`
			LEFT JOIN `stc_user` ON `stc_purchase_product_adhoc_created_by`=`stc_user_id`
			WHERE `stc_purchase_product_adhoc_qty`>0 ".$filter."
			ORDER BY TIMESTAMP(`stc_purchase_product_adhoc_created_date`) DESC
		");
		$count_num=mysqli_num_rows($count_numqry);
		if(mysqli_num_rows($odinqry)>0){
			$slno=0;
			$statusColors = [
				1 => 'badge bg-primary',   // Stock
				2 => 'badge bg-info',      // Dispatched
				3 => 'badge bg-warning',   // Pending
				4 => 'badge bg-success',   // Approved
				5 => 'badge bg-danger'     // Rejected
			];
			$status=array(1 => 'Stock', 2 => 'Dispatched', 3 => 'Pending', 4 => 'Approved', 5 => 'Rejected');
			foreach($odinqry as $odinrow){
				$slno++;
				$delivered=0;
				$sql_qry=mysqli_query($this->stc_dbs, "
					SELECT `stc_cust_super_requisition_list_items_rec_recqty` 
					FROM `stc_cust_super_requisition_list_items_rec` 
					WHERE `stc_cust_super_requisition_list_items_rec_list_poaid`='".$odinrow['stc_purchase_product_adhoc_id']."'
				");
				if(mysqli_num_rows($sql_qry)>0){
					foreach($sql_qry as $sql_row){
						$delivered+=$sql_row['stc_cust_super_requisition_list_items_rec_recqty'];
					}
				}
				$deliveredgld=0;
				
				$stock=$odinrow['stc_purchase_product_adhoc_qty'] - ($delivered + $deliveredgld);
				$sql_qry=mysqli_query($this->stc_dbs, "
					SELECT `stc_product_image` FROM `stc_product` WHERE `stc_product_id`='".$odinrow['stc_purchase_product_adhoc_productid']."'
				");
				$productog='';
				$pro_image='';
				if(mysqli_num_rows($sql_qry)>0){
					foreach($sql_qry as $sql_row){
						$pro_image=$sql_row['stc_product_image'];
						$productog="<img src='../stc_symbiote/stc_product_image/".$pro_image."' style='height:80px;'>";
					}
				}

				$sql_qry=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_shop` WHERE `adhoc_id`='".$odinrow['stc_purchase_product_adhoc_id']."'
				");
				$shop_details='';
				$shop_qty=0;
				if(mysqli_num_rows($sql_qry)>0){
					foreach($sql_qry as $sql_row){
						$shop_qty+=$sql_row['qty'];
						$shop_details.='<span><a href="stc-print-preview-transferchallan.php?transfer_id='.$sql_row['id'].'" id="'.$sql_row['id'].'" target="_blank" title="Print Challan"><i class="fa fa-print"></i></a>'.$sql_row['shopname'].'_'.number_format($sql_row['qty'], 2).'/'.$odinrow['stc_purchase_product_adhoc_unit'].'</span><a href="javascript:void(0)" title="Remove Item" id="'.$sql_row['id'].'" class="remove-shop-item"><i class="fa fa-trash"></i></a></span><br> ';
					}
				}
				$stock=$stock-$shop_qty;
				$rateforpieces=($odinrow['stc_purchase_product_adhoc_prate'] * $odinrow['stc_purchase_product_adhoc_qty'])/$odinrow['stc_purchase_product_adhoc_qty'];
				$cherrypick="<a href='javascript:void(0)' class='btn btn-warning cherry-pick-btn' data-toggle='modal' data-target='#cherryPickModal' data-adhoc-id=".$odinrow['stc_purchase_product_adhoc_id']." data-current-qty=".$odinrow['stc_purchase_product_adhoc_qty']." data-rate=".$rateforpieces." data-unit=".$odinrow['stc_purchase_product_adhoc_unit']." title='Cherry Pick'><i class='fa fa-cut'></i></a>
						";
				if($stock==0){
					$cherrypick='';
				}
				$productog.='<input type="number" placeholder="Enter product id" class="form-control img-idinput"><a href="javascript:void(0)" class="form-control img-inputbtn" id="'.$odinrow['stc_purchase_product_adhoc_id'].'">Add</a>';
				$pro_prate='<input type="number" style="display:none" placeholder="Enter purchase rate" class="form-control img-idprateinput" value="'.$odinrow['stc_purchase_product_adhoc_prate'].'" step="0.01" oninput="calculateInlineSaleRate(this)"><a href="javascript:void(0)" style="display:none" class="form-control img-inputpratebtn" id="'.$odinrow['stc_purchase_product_adhoc_id'].'">Add</a>';
				$pro_percentage='<input type="number" style="display:none" placeholder="Enter profit %" class="form-control img-idpercentageinput" step="0.01" oninput="calculateInlineSaleRate(this)"><a href="javascript:void(0)" style="display:none" class="form-control img-inputpercentagebtn" id="'.$odinrow['stc_purchase_product_adhoc_id'].'">Add</a>';
				$pro_rate='<input type="number" style="display:none" placeholder="Calculated sale rate" class="form-control img-idrateinput" value="'.$odinrow['stc_purchase_product_adhoc_rate'].'" readonly><a href="javascript:void(0)" style="display:none" class="form-control img-inputratebtn" id="'.$odinrow['stc_purchase_product_adhoc_id'].'">Update</a>';
				$product_name=$odinrow['stc_sub_cat_name']!="OTHERS"?$odinrow['stc_sub_cat_name']. ' ' .$odinrow['stc_product_name']:$odinrow['stc_product_name'];
				$statusDropdown='';
				if($_SESSION['stc_empl_id']==1 || $_SESSION['stc_empl_id']==2 || $_SESSION['stc_empl_id']==6){
					if($odinrow['stc_purchase_product_adhoc_status']==3){
						$statusDropdown='
							<div class="dropdown" style="display:inline-block; margin-left: 10px;">
								<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="statusDropdownMenuButton'.$odinrow['stc_purchase_product_adhoc_id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Change Status
								</button>
								<div class="dropdown-menu" aria-labelledby="statusDropdownMenuButton'.$odinrow['stc_purchase_product_adhoc_id'].'">
									<a class="dropdown-item change-adhoc-status" href="javascript:void(0)" data-id="'.$odinrow['stc_purchase_product_adhoc_id'].'" data-status="4">Approve</a>
									<a class="dropdown-item change-adhoc-status" href="javascript:void(0)" data-id="'.$odinrow['stc_purchase_product_adhoc_id'].'" data-status="5">Reject</a>
								</div>
							</div>
						';
					}elseif($odinrow['stc_purchase_product_adhoc_status']==4){
						$acceptData='';
						if($_SESSION['stc_empl_id']==6){
							$acceptData='<a class="dropdown-item change-adhoc-status" href="javascript:void(0)" data-id="'.$odinrow['stc_purchase_product_adhoc_id'].'" data-status="1">Accept</a>';
						}
						$statusDropdown='
							<div class="dropdown" style="display:inline-block; margin-left: 10px;">
								<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="statusDropdownMenuButton'.$odinrow['stc_purchase_product_adhoc_id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Change Status
								</button>
								<div class="dropdown-menu" aria-labelledby="statusDropdownMenuButton'.$odinrow['stc_purchase_product_adhoc_id'].'">
									'.$acceptData.'
									<a class="dropdown-item change-adhoc-status" href="javascript:void(0)" data-id="'.$odinrow['stc_purchase_product_adhoc_id'].'" data-status="3">Set to Pending</a>
									<a class="dropdown-item change-adhoc-status" href="javascript:void(0)" data-id="'.$odinrow['stc_purchase_product_adhoc_id'].'" data-status="5">Reject</a>
								</div>
							</div>';
					}elseif($odinrow['stc_purchase_product_adhoc_status']==5){
						$statusDropdown='
							<div class="dropdown" style="display:inline-block; margin-left: 10px;">
								<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="statusDropdownMenuButton'.$odinrow['stc_purchase_product_adhoc_id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Change Status
								</button>
								<div class="dropdown-menu" aria-labelledby="statusDropdownMenuButton'.$odinrow['stc_purchase_product_adhoc_id'].'">
									<a class="dropdown-item change-adhoc-status" href="javascript:void(0)" data-id="'.$odinrow['stc_purchase_product_adhoc_id'].'" data-status="3">Set to Pending</a>
									<a class="dropdown-item change-adhoc-status" href="javascript:void(0)" data-id="'.$odinrow['stc_purchase_product_adhoc_id'].'" data-status="4">Approve</a>
								</div>
							</div>';
					}
				}
				$updatestatus="<a href='javascript:void(0)' class='btn btn-secondary update-purchased-lineitems' data-toggle='modal' data-target='#myModal' id='".$odinrow['stc_purchase_product_adhoc_id']."' title='Update Status'><i class='fa fa-check-square'></i></a>";
				$recieving="<a href='javascript:void(0)' class='btn btn-success add-receiving' data-toggle='modal' data-target='.receiving-modal' id='".$odinrow['stc_purchase_product_adhoc_id']."' title='Receiving'><i class='fa fa-handshake-o'></i></a>";
				if($odinrow['stc_purchase_product_adhoc_status']>2){
					$updatestatus='';
					$cherrypick='';
				}
				$addtoollist='<a href="javascript:void(0)" class="btn btn-success itt-create" data-toggle="modal" data-target=".bd-toolstracker-modal-lg" id="'.$odinrow['stc_purchase_product_adhoc_id'].'" title="Add to Toollist"><i class="fa fa-tools"></i></a>';
				$odin.="
					<tr>
						<td class='text-center'>".$slno."</td>
						<td class='text-center' style='width: 180px; font-family: Arial, sans-serif; padding: 8px;'>
							<div style='font-weight: bold; color: #34495e;'>
								".$odinrow['stc_purchase_product_adhoc_id']."
							</div>
						</td>
						<td style='width: 180px;'>".$productog."</td>
						<td class='text-center'><b>".$odinrow['stc_product_id']."</b><br><a href='javascript:void(0)' data-toggle='modal' data-target='.bd-modal-product-history' class='form-conrtol show-product-history' id='".$odinrow['stc_product_id']."'>".$product_name."</a></td>
						<td style='width: 180px;'><a href='javascript:void(0)' data-toggle='modal' data-target='.bd-modal-editproductname' class='edit-itemname' id='".$odinrow['stc_purchase_product_adhoc_id']."'>".$odinrow['stc_purchase_product_adhoc_itemdesc']."</a> ".$addtoollist."</td>
						<td class='text-center' style='width: 70px;'>".$odinrow['stc_rack_name']."</td>
						<td class='text-center'>".$odinrow['stc_purchase_product_adhoc_unit']."</td>
						<td class='text-right'>".number_format($odinrow['stc_purchase_product_adhoc_qty'], 2)."</td>
						<td class='text-right' style='width: 125px;'><a href='javascript:void(0)' class='img-inputbtnshow'>".number_format($odinrow['stc_purchase_product_adhoc_prate'], 2)."</br>".number_format($odinrow['stc_purchase_product_adhoc_rate'], 2)."</a>".$pro_prate.$pro_percentage.$pro_rate."</td>
						<td class='text-right'>".number_format($stock, 2)."</td>
						<td class='text-center' style='width: 180px;'>
							".$shop_details."
							<a href='javascript:void(0)' class='btn btn-primary input-shop-item' data-toggle='modal' data-target='.bd-showadhocshop-modal-lg' title='Add Item to Shop' id='".$odinrow['stc_purchase_product_adhoc_id']."'><i class='fa fa-plus'></i></a>
						</td>
						<td class='text-center' style='width: 180px;'>
							<a href='javascript:void(0)' class='btn btn-primary get-dispatch-details' data-toggle='modal' data-target='.bd-showadhocdetails-modal-lg' title='Dispatch details' id='".$odinrow['stc_purchase_product_adhoc_id']."'><i class='fa fa-file'></i></a>
						</td>
						<td style='width: 180px; font-family: Arial, sans-serif; padding: 8px;'>
							<div style='font-weight: bold; color: #2c3e50;'>➤ ".$odinrow['stc_purchase_product_adhoc_source']."</div>
							<div style='font-weight: bold; color: #16a085;'>➤ ".$odinrow['stc_purchase_product_adhoc_destination']."</div>
						</td>

						<td class='text-center'>".$odinrow['stc_purchase_product_adhoc_condition']."</td>
						<td class='text-center'>".$odinrow['stc_purchase_product_adhoc_recievedby']."</td>
						<td style='width: 180px; font-family: Arial, sans-serif; padding: 8px;'>
							<div style='font-weight: bold; color: #34495e;'>
								".$odinrow['stc_user_name']."
							</div>
							<div style='font-weight: bold; color: #2980b9; margin-top: 5px;'>
								📅 ".date('d-m-Y', strtotime($odinrow['stc_purchase_product_adhoc_created_date']))."
							</div>
						</td>
						<td style='width: 180px; font-family: Arial, sans-serif; padding: 8px;'>
							<div style='font-weight: bold; color: #34495e;'>
								".$odinrow['stc_user_name']."
							</div>
							<div style='font-weight: bold; color: #2980b9; margin-top: 5px;'>
								📅 ".date('d-m-Y', strtotime($odinrow['stc_purchase_product_adhoc_updated_date']))."
							</div>
						</td>
						<td class='text-center'><span class='".$statusColors[$odinrow['stc_purchase_product_adhoc_status']]."'>".$status[$odinrow['stc_purchase_product_adhoc_status']]."</span>".$statusDropdown."</td>
						<td class='text-center'>".$odinrow['stc_purchase_product_adhoc_remarks']."</td>
						<td class='text-center'>
							".$updatestatus."
							<!--<a href='javascript:void(0)' class='btn btn-primary add-payment-details' data-toggle='modal' data-target='#myModal' id='".$odinrow['stc_purchase_product_adhoc_id']."' title='Payment details'><i class='fa fa-credit-card'></i></a>-->
							".$recieving."							
							<a href='javascript:void(0)' class='btn btn-danger remove-products' id='".$odinrow['stc_purchase_product_adhoc_id']."' title='Delete'><i class='fa fa-trash'></i></a>
							".$cherrypick."
						</td>
					</tr>
				";
			}
		}else{
			$odin="
				<tr>
					<td>No record found.</td>
				</tr>
			";
		}
		return $odin=array('count_num' => $count_num, 'odin' => $odin);
	}

	public function stc_call_poadhoc_ddetails($poaid){
		$odin='';
		$odin_query=mysqli_query($this->stc_dbs, "SELECT DISTINCT `stc_cust_super_requisition_list_items_rec_list_id`, `stc_cust_project_title`, `stc_cust_pro_supervisor_fullname`, `stc_cust_super_requisition_list_items_rec_recqty`, `stc_cust_super_requisition_list_items_rec_date` FROM `stc_cust_super_requisition_list_items_rec` LEFT JOIN `stc_cust_super_requisition_list` ON `stc_cust_super_requisition_list_id`=`stc_cust_super_requisition_list_items_rec_list_id` LEFT JOIN `stc_cust_project` ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id` LEFT JOIN `stc_cust_pro_supervisor` ON `stc_cust_pro_supervisor_id`=`stc_cust_super_requisition_list_super_id` WHERE `stc_cust_super_requisition_list_items_rec_list_poaid`=".$poaid." ORDER BY TIMESTAMP(`stc_cust_super_requisition_list_items_rec_date`) DESC");
		if(mysqli_num_rows($odin_query)>0){
			$slno=0;
			foreach($odin_query as $odin_row){
				$slno++;
				$odin.='<tr><td>'.$slno.'</td><td>'.$odin_row['stc_cust_super_requisition_list_items_rec_list_id'].'</td><td>'.$odin_row['stc_cust_super_requisition_list_items_rec_date'].'</td><td>'.$odin_row['stc_cust_project_title'].'</td><td>'.$odin_row['stc_cust_pro_supervisor_fullname'].'</td><td class="text-right">'.number_format($odin_row['stc_cust_super_requisition_list_items_rec_recqty'], 2).'</td></tr>';
			}
		}else{
			$odin='<tr><td>No record found.</td></tr>';
		}
		return $odin;
	}


	public function stc_call_poadhoc_gldddetails($poaid){
		$odin='';
		// echo "SELECT `stc_product_name`, GCR.gld_customer_title, GC.`challan_number`, GC.`bill_number`, GC.`qty`, GC.`rate`, GC.`created_date`, TU.stc_trading_user_name FROM `gld_challan` GC LEFT JOIN `gld_customer` GCR ON GC.cust_id=GCR.gld_customer_id LEFT JOIN `stc_product` ON GC.product_id=`stc_product_id` LEFT JOIN `stc_trading_user` TU ON GC.created_by=TU.stc_trading_user_id WHERE GC.adhoc_id=".$poaid." ORDER BY TIMESTAMP(`created_date`) DESC";
		$odin_query=mysqli_query($this->stc_dbs, "SELECT `stc_product_name`, `stc_product_unit`, GCR.gld_customer_title, GC.`challan_number`, GC.`bill_number`, GC.`qty`, GC.`rate`, GC.`created_date`, TU.stc_trading_user_name FROM `gld_challan` GC LEFT JOIN `gld_customer` GCR ON GC.cust_id=GCR.gld_customer_id LEFT JOIN `stc_product` ON GC.product_id=`stc_product_id` LEFT JOIN `stc_trading_user` TU ON GC.created_by=TU.stc_trading_user_id WHERE GC.adhoc_id=".$poaid." ORDER BY TIMESTAMP(`created_date`) DESC");
		if(mysqli_num_rows($odin_query)>0){
			$slno=0;
			foreach($odin_query as $odin_row){
				$slno++;
				$total=$odin_row['qty'] * $odin_row['rate'];
				$odin.='<tr><td>'.$slno.'</td><td>'.$odin_row['gld_customer_title'].'</td><td>'.$odin_row['bill_number'].'</td><td>'.$odin_row['gld_customer_title'].'</td><td class="text-right">'.number_format($odin_row['qty'], 2).'</td><td class="text-right">'.$odin_row['stc_product_unit'].'</td><td class="text-right">'.number_format($odin_row['rate'], 2).'</td><td class="text-right">'.number_format($total, 2).'</td><td class="text-right">'.$odin_row['stc_trading_user_name'].'</td></tr>';
			}
		}else{
			$odin='<tr><td>No record found.</td></tr>';
		}
		return $odin;
	}

	// received po adhoc save trigger
	public function stc_poadhoc_rec_save($adhoc_id, $receiving){
		$odin='';
		$odinqry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_purchase_product_adhoc` 
			SET `stc_purchase_product_adhoc_recievedby`='".mysqli_real_escape_string($this->stc_dbs, $receiving)."'
			WHERE `stc_purchase_product_adhoc_id`='".mysqli_real_escape_string($this->stc_dbs, $adhoc_id)."'
		");
		if($odinqry){
			$odin='success';
		}else{
			$odin='failed';
		}
		return $odin;
	}

	// delete for po adhoc delete trigger
	public function stc_poadhoc_delete($adhoc_id){
		$odin='';
		$checkqry=mysqli_query($this->stc_dbs, "
			SELECT `stc_cust_super_requisition_list_items_rec_recqty` 
			FROM `stc_cust_super_requisition_list_items_rec` 
			WHERE `stc_cust_super_requisition_list_items_rec_list_poaid`='".mysqli_real_escape_string($this->stc_dbs, $adhoc_id)."'
		");
		if(mysqli_num_rows($checkqry)==0){
			$odinqry=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_purchase_product_adhoc` 
				WHERE `stc_purchase_product_adhoc_id`='".mysqli_real_escape_string($this->stc_dbs, $adhoc_id)."'
			");
			if($odinqry){
				$odin='success';
			}else{
				$odin='failed';
			}
		}else{
			$odin='invalid';
		}
		return $odin;
	}

	// update po adhoc item name
	public function stc_poadhoc_update($adhoc_id, $adhoc_name, $adhoc_rack, $adhoc_unit, $adhoc_qty){
		$odin='';
		// Remove commas and ensure proper decimal formatting
		$adhoc_qty = str_replace(',', '', $adhoc_qty);
		// Convert to float to ensure proper number formatting, then back to string
		$adhoc_qty = number_format((float)$adhoc_qty, 2, '.', '');
		
		$checkqry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_purchase_product_adhoc` SET `stc_purchase_product_adhoc_itemdesc`='".mysqli_real_escape_string($this->stc_dbs, $adhoc_name)."', `stc_purchase_product_adhoc_qty`='".mysqli_real_escape_string($this->stc_dbs, $adhoc_qty)."', `stc_purchase_product_adhoc_unit`='".mysqli_real_escape_string($this->stc_dbs, $adhoc_unit)."', `stc_purchase_product_adhoc_rackid`='".mysqli_real_escape_string($this->stc_dbs, $adhoc_rack)."' WHERE `stc_purchase_product_adhoc_id`='".mysqli_real_escape_string($this->stc_dbs, $adhoc_id)."'
		");
		if($checkqry){
			$odin='success';
		}else{
			$odin='failed';
		}
		return $odin;
	}

	// cherry pick function
	public function stc_poadhoc_cherrypic($adhoc_id, $qtyToDecrease, $newQty, $unit, $prate, $rate) {
		// Decrease original quantity
		$update = mysqli_query($this->stc_dbs, "
			UPDATE `stc_purchase_product_adhoc` 
			SET `stc_purchase_product_adhoc_qty` = `stc_purchase_product_adhoc_qty` - '".mysqli_real_escape_string($this->stc_dbs, $qtyToDecrease)."' 
			WHERE `stc_purchase_product_adhoc_id` = '".mysqli_real_escape_string($this->stc_dbs, $adhoc_id)."'");
		
		if (!$update) return 'failed';
		
		$user_id = $_SESSION['stc_empl_id'] ?? 0;
		$now = date('Y-m-d H:i:s');
		
		// Insert new row using SELECT
		$insert = mysqli_query($this->stc_dbs, "
			INSERT INTO stc_purchase_product_adhoc (
				stc_purchase_product_adhoc_productid, stc_purchase_product_adhoc_itemdesc, 
				stc_purchase_product_adhoc_qty, stc_purchase_product_adhoc_prate, stc_purchase_product_adhoc_rate, 
				stc_purchase_product_adhoc_unit, stc_purchase_product_adhoc_rackid, 
				stc_purchase_product_adhoc_condition, stc_purchase_product_adhoc_source, 
				stc_purchase_product_adhoc_destination, stc_purchase_product_adhoc_recievedby, 
				stc_purchase_product_adhoc_qtytodecrease, stc_purchase_product_adhoc_cherrypickby, 
				stc_purchase_product_adhoc_status, stc_purchase_product_adhoc_remarks, 
				stc_purchase_product_adhoc_created_by, stc_purchase_product_adhoc_created_date, 
				stc_purchase_product_adhoc_updated_by, stc_purchase_product_adhoc_updated_date
			) 
			SELECT 
				stc_purchase_product_adhoc_productid, 
				stc_purchase_product_adhoc_itemdesc, 
				'".mysqli_real_escape_string($this->stc_dbs, $newQty)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $prate)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $rate)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $unit)."', 
				stc_purchase_product_adhoc_rackid, 
				stc_purchase_product_adhoc_condition, 
				stc_purchase_product_adhoc_source, 
				stc_purchase_product_adhoc_destination, 
				stc_purchase_product_adhoc_recievedby, 
				'".mysqli_real_escape_string($this->stc_dbs, $qtyToDecrease)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $adhoc_id)."', 
				stc_purchase_product_adhoc_status, 
				stc_purchase_product_adhoc_remarks, 
				'".mysqli_real_escape_string($this->stc_dbs, $user_id)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $now)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $user_id)."', 
				'".mysqli_real_escape_string($this->stc_dbs, $now)."' 
			FROM stc_purchase_product_adhoc 
			WHERE stc_purchase_product_adhoc_id = '".mysqli_real_escape_string($this->stc_dbs, $adhoc_id)."'");
		
		return $insert ? 'success' : 'failed';
	}

	public function stc_poadhoc_imgupdate($adhoc_id, $img_id){
		$odin='';
		$checkqry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_purchase_product_adhoc` SET `stc_purchase_product_adhoc_productid`='".mysqli_real_escape_string($this->stc_dbs, $img_id)."' WHERE `stc_purchase_product_adhoc_id`='".mysqli_real_escape_string($this->stc_dbs, $adhoc_id)."'
		");
		if($checkqry){
			$odin='success';
		}else{
			$odin='failed';
		}
		return $odin;
	}

	public function stc_poadhoc_rateupdate($adhoc_id, $rate, $prate, $percentage = null){
		$odin='';
		// If percentage is provided, calculate the sale rate from purchase rate and percentage
		if($percentage !== null && $percentage !== '' && $prate > 0){
			$calculated_rate = $prate + ($prate * $percentage / 100);
			$rate = $calculated_rate;
		}
		
		$checkqry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_purchase_product_adhoc` SET `stc_purchase_product_adhoc_rate`='".mysqli_real_escape_string($this->stc_dbs, $rate)."', `stc_purchase_product_adhoc_prate`='".mysqli_real_escape_string($this->stc_dbs, $prate)."' WHERE `stc_purchase_product_adhoc_id`='".mysqli_real_escape_string($this->stc_dbs, $adhoc_id)."'
		");
		if($checkqry){
			$odin='success';
		}else{
			$odin='failed';
		}
		return $odin;
	}

	public function stc_poadhoc_rohistory($adhoc_id){
		$odin='';
		$query="SELECT stc_product_name, stc_product_unit FROM stc_product WHERE stc_product_id=".$adhoc_id;
		$result=mysqli_query($this->stc_dbs, $query);
		if(mysqli_num_rows($result)>0){
			// gld challan
			$product_name='';
			$product_unit='';
			$gld_challan = []; // Initialize the array to store results
			foreach($result as $row){
				$product_name=$row['stc_product_name'];
				$product_unit=$row['stc_product_unit'];
			}

			// purchased order data
			$purchased_data=[]; 
			
			$query = "SELECT DISTINCT `stc_merchant_name`, `stc_purchase_product_id`, `stc_purchase_product_order_date`, `stc_product_grn_id`, `stc_product_grn_date`, `stc_product_grn_items_qty`, `stc_product_grn_items_rate`, `stc_user_name` FROM `stc_product_grn_items` LEFT JOIN `stc_product_grn` ON `stc_product_grn_items_grn_order_id`=`stc_product_grn_id` LEFT JOIN `stc_purchase_product` ON `stc_product_grn_items_purchase_order_id`=`stc_purchase_product_id` LEFT JOIN `stc_merchant` ON `stc_purchase_product_merchant_id`=`stc_merchant_id` LEFT JOIN `stc_user` ON `stc_purchase_product_created_by`=`stc_user_id` WHERE `stc_product_grn_items_product_id`=" . intval($adhoc_id);
			$result = mysqli_query($this->stc_dbs, $query);


			if ($result) {
				foreach ($result as $row) {
					$row['stc_purchase_product_order_date']=date('d-m-Y H:i a', strtotime($row['stc_purchase_product_order_date']));
					$row['stc_product_grn_date']=date('d-m-Y H:i a', strtotime($row['stc_product_grn_date']));
					$purchased_data[] = $row; // Add each row to the array
				}
			}

			// adhoc challan data
			$adhocchallan_data=[]; 
			
			$query = "SELECT `stc_customer_name`, `stc_cust_project_title`, `stc_cust_super_requisition_list_items_rec_list_id`, `stc_cust_super_requisition_list_items_rec_recqty`, `stc_cust_super_requisition_list_items_rec_date` FROM `stc_cust_super_requisition_list_items_rec` LEFT JOIN `stc_cust_super_requisition_list` ON `stc_cust_super_requisition_list_id`=`stc_cust_super_requisition_list_items_rec_list_id` LEFT JOIN `stc_cust_project` ON `stc_cust_super_requisition_list_project_id`=`stc_cust_project_id` LEFT JOIN `stc_customer` ON `stc_cust_project_cust_id`=`stc_customer_id` WHERE `stc_cust_super_requisition_list_items_rec_list_pd_id`=" . intval($adhoc_id);
			$result = mysqli_query($this->stc_dbs, $query);


			if ($result) {
				foreach ($result as $row) {
					$row['stc_cust_super_requisition_list_items_rec_date']=date('d-m-Y H:i a', strtotime($row['stc_cust_super_requisition_list_items_rec_date']));
					$adhocchallan_data[] = $row; // Add each row to the array
				}
			}

			// purchased adhoc data
			$challan_data=[]; 
			
			$query = "SELECT `stc_purchase_product_adhoc_source`, `stc_purchase_product_adhoc_destination`, `stc_purchase_product_adhoc_recievedby`, `stc_purchase_product_adhoc_qty`, `stc_purchase_product_adhoc_prate`, `stc_purchase_product_adhoc_destination`, `stc_purchase_product_adhoc_recievedby`, `stc_user_name`, `stc_purchase_product_adhoc_created_date` FROM `stc_purchase_product_adhoc` LEFT JOIN `stc_user` ON `stc_purchase_product_adhoc_created_by`=`stc_user_id` WHERE `stc_purchase_product_adhoc_productid`=" . intval($adhoc_id);
			$result = mysqli_query($this->stc_dbs, $query);


			if ($result) {
				foreach ($result as $row) {
					$row['stc_purchase_product_adhoc_created_date']=date('d-m-Y H:i a', strtotime($row['stc_purchase_product_adhoc_created_date']));
					$purchasedadhoc_data[] = $row; // Add each row to the array
				}
			}

			// challan_data
			$challan_data=[]; 
			
			$query = "SELECT `stc_customer_name`, `stc_cust_project_title`, `stc_sale_product_id`, `stc_sale_product_cust_order_date`, `stc_sale_product_items_product_qty`, `stc_sale_product_items_product_sale_rate` FROM `stc_sale_product_items` LEFT JOIN `stc_sale_product` ON `stc_sale_product_items_sale_product_id`=`stc_sale_product_id` LEFT JOIN `stc_cust_super_requisition_list` ON `stc_sale_product_order_id`=`stc_cust_super_requisition_list_id` LEFT JOIN `stc_cust_project` ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id` LEFT JOIN `stc_customer` ON `stc_sale_product_cust_id`=`stc_customer_id` WHERE `stc_sale_product_items_product_id`=" . intval($adhoc_id) . " ORDER BY DATE(`stc_sale_product_cust_order_date`) DESC";
			$result = mysqli_query($this->stc_dbs, $query);


			if ($result) {
				foreach ($result as $row) {
					$row['stc_sale_product_cust_order_date']=date('d-m-Y H:i a', strtotime($row['stc_sale_product_cust_order_date']));
					$challan_data[] = $row; // Add each row to the array
				}
			}

			// gld challan data
			$query = "SELECT `id`, `product_id`, `gld_customer_title`, `requisition_id`, `challan_number`, `bill_number`, `qty`, `rate`, `paid_amount`, `payment_status`, `status`, `created_date`, `stc_trading_user_name` FROM `gld_challan` LEFT JOIN `gld_customer` ON `gld_customer_id`=`cust_id` LEFT JOIN `stc_trading_user` ON `stc_trading_user_id`=`created_by` WHERE `product_id` = " . intval($adhoc_id);
			$result = mysqli_query($this->stc_dbs, $query);


			if ($result) {
				foreach ($result as $row) {
					$row['created_date']=date('d-m-Y H:i a', strtotime($row['created_date']));
					$gld_challan[] = $row; // Add each row to the array
				}
			}
			
			$odin=array(
				'Product_name' => $product_name,
				'product_unit' => $product_unit,
				'purchased' => $purchased_data,
				'purchasedadhoc' => $purchasedadhoc_data,
				'challan' => $challan_data,
				'directchallan' => $adhocchallan_data,
				'gld_challan' => $gld_challan
			);
		}else{
			$odin=array(
				'Product_name' => "No data.",
				'product_unit' => "No data.",
				'purchased' => "No data.",
				'purchasedadhoc' => "No data.",
				'challan' => "No data.",
				'directchallan' => "No data.",
				'gld_challan' => "No data."
			);
		}
		return $odin;
	}
	
	public function stc_poadhoc_ledger($dateFrom, $dateTo, $siteName) {
		$filter = '';
		$query = "
			SELECT DISTINCT
				`stc_cust_super_requisition_list_items_rec_id`, 
				`stc_purchase_product_adhoc_id`, 
				L.`stc_cust_super_requisition_list_date`,
				`stc_cust_pro_supervisor_fullname`, 
				`stc_cust_project_title`, 
				`stc_purchase_product_adhoc_productid`, 
				I.`stc_cust_super_requisition_list_items_title`,
				I.`stc_cust_super_requisition_list_items_unit`,
				IFNULL(`stc_cust_super_requisition_list_items_rec_recqty`, 0) AS `stc_cust_super_requisition_list_items_rec_recqty`,
				IFNULL(`stc_purchase_product_adhoc_rate`, 0) AS `stc_purchase_product_adhoc_rate`,
				(IFNULL(`stc_cust_super_requisition_list_items_rec_recqty`, 0) * IFNULL(`stc_purchase_product_adhoc_rate`, 0)) AS `total`
			FROM `stc_cust_super_requisition_list` L 
			INNER JOIN `stc_cust_super_requisition_list_items` I ON L.`stc_cust_super_requisition_list_id` = I.`stc_cust_super_requisition_list_items_req_id` 
			INNER JOIN `stc_cust_project` ON L.`stc_cust_super_requisition_list_project_id` = `stc_cust_project_id` 
			INNER JOIN `stc_cust_pro_supervisor` ON L.`stc_cust_super_requisition_list_super_id` = `stc_cust_pro_supervisor_id` 
			INNER JOIN `stc_requisition_combiner_req` ON L.`stc_cust_super_requisition_list_id` = `stc_requisition_combiner_req_requisition_id` 
			LEFT JOIN `stc_cust_super_requisition_list_items_rec` ON `stc_cust_super_requisition_list_items_rec_list_item_id` = I.`stc_cust_super_requisition_list_id`
			LEFT JOIN `stc_purchase_product_adhoc` ON `stc_purchase_product_adhoc_id` = `stc_cust_super_requisition_list_items_rec_list_poaid`
			WHERE L.`stc_cust_super_requisition_list_status`>2
		";
	
		// Add date filter if both dateFrom and dateTo are provided
		if (!empty($dateFrom) && !empty($dateTo)) {
			$query .= " AND (DATE(L.`stc_cust_super_requisition_list_date`) BETWEEN '" . mysqli_real_escape_string($this->stc_dbs, $dateFrom) . "' AND '" . mysqli_real_escape_string($this->stc_dbs, $dateTo) . "')";
		}
	
		// Add siteName filter if provided
		if (!empty($siteName)) {
			$query .= " AND `stc_cust_project_title` REGEXP '" . mysqli_real_escape_string($this->stc_dbs, $siteName) . "'";
		}
	
		// Order by date in descending order
		$query .= " ORDER BY DATE(L.`stc_cust_super_requisition_list_date`) DESC";
	
		// Execute the query
		$result = mysqli_query($this->stc_dbs, $query);
	
		// Check for errors in the query execution
		if (!$result) {
			return "Error executing query: " . mysqli_error($this->stc_dbs);
		}
	
		// Fetch data
		$odin = array();
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$row['stc_cust_super_requisition_list_items_rec_recqty'] = number_format($row['stc_cust_super_requisition_list_items_rec_recqty'], 2);
				$row['stc_purchase_product_adhoc_rate'] = number_format($row['stc_purchase_product_adhoc_rate'], 2);
				$row['total'] = $row['total'];
				$odin[] = $row; // Add each row to the array
			}
		}
	
		return $odin;
	}	

	public function stc_poadhoc_customerledger($dateFrom, $dateTo, $siteName) {
		$filter = '';
		$dateFrom=date('Y-m-d', strtotime($dateFrom));
		$dateTo=date('Y-m-d', strtotime($dateTo));
		$query = "
			SELECT D.stc_cust_project_id, D.stc_cust_project_title, SUM(A.stc_cust_super_requisition_list_items_rec_recqty * E.stc_purchase_product_adhoc_rate) as total FROM `stc_cust_super_requisition_list_items_rec` A 
			INNER JOIN stc_cust_super_requisition_list_items B
			ON A.stc_cust_super_requisition_list_items_rec_list_item_id=B.stc_cust_super_requisition_list_id
			INNER JOIN stc_cust_super_requisition_list C
			ON A.stc_cust_super_requisition_list_items_rec_list_id=C.stc_cust_super_requisition_list_id
			INNER JOIN stc_cust_project D
			ON C.stc_cust_super_requisition_list_project_id=D.stc_cust_project_id
			INNER JOIN stc_purchase_product_adhoc E
			ON A.stc_cust_super_requisition_list_items_rec_list_poaid=E.stc_purchase_product_adhoc_id
			WHERE stc_cust_project_cust_id=$siteName AND DATE(A.stc_cust_super_requisition_list_items_rec_date) BETWEEN '$dateFrom' AND '$dateTo' 
			GROUP BY D.stc_cust_project_title ORDER BY D.stc_cust_project_title
		";
		$result = mysqli_query($this->stc_dbs, $query);
		// Fetch data
		$odin = array();
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				if($row['total']==0){ 
					continue;
				}
				$row['ltotal'] = $row['total'];
				$row['total'] = number_format($row['total'], 2);
				$odin[] = $row; // Add each row to the array
			}
		}
		return $odin;
	}
	
	public function stc_poadhoc_update_ledger($id, $itemcode, $quantity) {
		// Remove commas and ensure proper decimal formatting
		$quantity = str_replace(',', '', $quantity);
		// Convert to float to ensure proper number formatting, then back to string
		$quantity = number_format((float)$quantity, 2, '.', '');
		
		if(!empty($itemcode)){
			mysqli_query($this->stc_dbs, "UPDATE `stc_cust_super_requisition_list_items_rec` SET `stc_cust_super_requisition_list_items_rec_list_poaid`='$itemcode', `stc_cust_super_requisition_list_items_rec_recqty`='$quantity' WHERE `stc_cust_super_requisition_list_items_rec_id`='$id'");
		}else{
			mysqli_query($this->stc_dbs, "UPDATE `stc_cust_super_requisition_list_items_rec` SET `stc_cust_super_requisition_list_items_rec_recqty`='$quantity' WHERE `stc_cust_super_requisition_list_items_rec_id`='$id'");
		}
	
		return "success";
	}
	
	public function stc_poadhoc_itemtoshop($id, $name, $quantity) {
		$odin="no";
		$date=date("Y-m-d H:i:s");		
		$query=mysqli_query($this->stc_dbs, "INSERT INTO `stc_shop`(`adhoc_id`, `shopname`, `qty`, created_date, created_by) VALUES('".mysqli_real_escape_string($this->stc_dbs, $id)."', '".mysqli_real_escape_string($this->stc_dbs, $name)."', '".mysqli_real_escape_string($this->stc_dbs, $quantity)."', '".mysqli_real_escape_string($this->stc_dbs, $date)."', '".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_empl_id'])."')"); 
		
		if($query){
			$odin="yes";
		}
	
		return $odin;
	}	
	
	public function stc_poadhoc_itemtoshopremove($id) {
		$odin="no";
		$query=mysqli_query($this->stc_dbs, "DELETE FROM `stc_shop` WHERE `id`='".mysqli_real_escape_string($this->stc_dbs, $id)."'"); 
		
		if($query){
			$odin="yes";
		}
	
		return $odin;
	}

	public function stc_update_adhoc_item_status($id)	 {
		$odin="no";
		$query = mysqli_query($this->stc_dbs, "UPDATE `stc_purchase_product_adhoc` SET `stc_purchase_product_adhoc_status` = IF(`stc_purchase_product_adhoc_status` = 1, 2, 1) WHERE `stc_purchase_product_adhoc_id` = '".mysqli_real_escape_string($this->stc_dbs, $id)."'");
		
		if($query){
			$odin="yes";
		}
	
		return $odin;
	}

	public function stc_update_adhoc_item_apprstatus($id, $status)	 {
		$odin="no";
		$query = mysqli_query($this->stc_dbs, "UPDATE `stc_purchase_product_adhoc` SET `stc_purchase_product_adhoc_status` = '$status' WHERE `stc_purchase_product_adhoc_id` = '".mysqli_real_escape_string($this->stc_dbs, $id)."'");
		
		if($query){
			$odin="yes";
		}
	
		return $odin;
	}

	public function getPaginatedProducts($page, $search) {
		$limit = 25;
		$offset = ($page - 1) * $limit;

		$searchQuery = "";
		if (!empty($search)) {
			$searchEscaped = mysqli_real_escape_string($this->stc_dbs, $search);
			$searchQuery = " AND (P.stc_product_name LIKE '%$searchEscaped%' OR P.stc_product_desc LIKE '%$searchEscaped%' OR B.stc_brand_title LIKE '%$searchEscaped%' OR S.stc_sub_cat_name LIKE '%$searchEscaped%')";
		}

		if (is_numeric($search)) {
			$searchQuery = " AND P.stc_product_id = $search";
		}

		// Main data with subquery to calculate remaining qty
		$baseSql = "
			FROM stc_product P
			INNER JOIN stc_sub_category S ON P.stc_product_sub_cat_id = S.stc_sub_cat_id
			INNER JOIN stc_category C ON P.stc_product_cat_id = C.stc_cat_id
			LEFT JOIN stc_brand B ON B.stc_brand_id = P.stc_product_brand_id 
			LEFT JOIN (
				SELECT 
					stc_purchase_product_adhoc_productid AS product_id,
					SUM(stc_purchase_product_adhoc_qty) AS total_stock
				FROM stc_purchase_product_adhoc
				WHERE stc_purchase_product_adhoc_status = 1
				GROUP BY stc_purchase_product_adhoc_productid
			) AS stock ON stock.product_id = P.stc_product_id
			LEFT JOIN (
				SELECT product_id, SUM(qty) AS total_challan
				FROM gld_challan
				GROUP BY product_id
			) AS challan ON challan.product_id = P.stc_product_id
			LEFT JOIN (
				SELECT spa.stc_purchase_product_adhoc_productid AS product_id, SUM(srec.stc_cust_super_requisition_list_items_rec_recqty) AS total_direct
				FROM stc_purchase_product_adhoc spa
				JOIN stc_cust_super_requisition_list_items_rec srec ON srec.stc_cust_super_requisition_list_items_rec_list_poaid = spa.stc_purchase_product_adhoc_id
				WHERE spa.stc_purchase_product_adhoc_status = 1
				GROUP BY spa.stc_purchase_product_adhoc_productid
			) AS direct ON direct.product_id = P.stc_product_id
			LEFT JOIN(
				SELECT spa.stc_purchase_product_adhoc_productid AS product_id, SUM(S.qty) AS total_shop
				FROM stc_purchase_product_adhoc spa
				JOIN stc_shop S ON S.adhoc_id = spa.stc_purchase_product_adhoc_id
				WHERE spa.stc_purchase_product_adhoc_status = 1
				GROUP BY spa.stc_purchase_product_adhoc_productid
			) AS shop ON shop.product_id = P.stc_product_id
			WHERE P.stc_product_avail = 1
			AND (IFNULL(stock.total_stock, 0) - IFNULL(challan.total_challan, 0) - IFNULL(direct.total_direct, 0)) > 0
			$searchQuery
		";

		// Count query
		$countSql = "SELECT COUNT(DISTINCT P.stc_product_id) as total $baseSql";
		$countResult = mysqli_query($this->stc_dbs, $countSql);
		$totalData = mysqli_fetch_assoc($countResult);
		$totalRows = $totalData['total'];
		$totalPages = ceil($totalRows / $limit);

		// Actual data
		$query = "
			SELECT DISTINCT
				P.stc_product_id, 
				P.stc_product_name, 
				S.stc_sub_cat_name, 
				C.stc_cat_name,
				B.stc_brand_title,
				P.stc_product_unit, 
				P.stc_product_image, 
				P.stc_product_gst,
				P.stc_product_sale_percentage,
				IFNULL(stock.total_stock, 0) AS total_stock,
				IFNULL(challan.total_challan, 0) AS total_challan,
				IFNULL(direct.total_direct, 0) AS total_direct,
				(IFNULL(stock.total_stock, 0) - IFNULL(challan.total_challan, 0) - IFNULL(direct.total_direct, 0) - IFNULL(shop.total_shop, 0)) AS remainingqty
			$baseSql
			LIMIT $offset, $limit
		";
		// echo $query;
		$result = mysqli_query($this->stc_dbs, $query);
		$html = '
			<table class="table table-bordered">
				<tr>
					<th class="text-center">Item Code</th>
					<th class="text-center">Name</th>
					<th class="text-center">Category</th>
					<th class="text-center">Brand</th>
					<th class="text-center">Stock Qty</th>
					<th class="text-center">Unit</th>
					<th class="text-center">Rate</th>
					<th class="text-center">Total</th>
				</tr>
		';
		if(mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$product_id = $row['stc_product_id'];
				$remainingqty = $row['remainingqty'];

				// Rate logic (kept as is from your original)
				$rate_gst = 0;
				$rate_percentage = 0;

				$rateQry = mysqli_query($this->stc_dbs, "
					SELECT stc_purchase_product_adhoc_prate, 
						ROUND(stc_purchase_product_adhoc_prate * (1 + {$row['stc_product_sale_percentage']} / 100), 2) AS rate_with_margin
					FROM stc_purchase_product_adhoc 
					WHERE stc_purchase_product_adhoc_productid = $product_id
					ORDER BY stc_purchase_product_adhoc_id DESC LIMIT 1
				");
				if ($rateQry && mysqli_num_rows($rateQry)) {
					$r = mysqli_fetch_assoc($rateQry);
					$rate_gst = $r['stc_purchase_product_adhoc_prate'];
					$rate_percentage = $r['rate_with_margin'];
				}

				// If not available, fallback to GRN rate
				if ($rate_gst == 0) {
					$rateQry = mysqli_query($this->stc_dbs, "
						SELECT stc_product_grn_items_rate,
							ROUND(stc_product_grn_items_rate * (1 + {$row['stc_product_sale_percentage']} / 100), 2) AS rate_with_margin
						FROM stc_product_grn_items 
						WHERE stc_product_grn_items_product_id = $product_id
						ORDER BY stc_product_grn_items_id DESC LIMIT 1
					");
					if ($rateQry && mysqli_num_rows($rateQry)) {
						$r = mysqli_fetch_assoc($rateQry);
						$rate_gst = $r['stc_product_grn_items_rate'];
						$rate_percentage = $r['rate_with_margin'];
					}
				}

				// Prepare product name
				$row['stc_product_name'] = $row['stc_sub_cat_name'] != "OTHERS" ? $row['stc_sub_cat_name'] . " " . $row['stc_product_name'] : $row['stc_product_name'];
				$total = $remainingqty * $rate_gst;

				$html .= "<tr>
							<td>{$row['stc_product_id']}</td>
							<td>{$row['stc_product_name']}</td>
							<td>{$row['stc_cat_name']}</td>
							<td>{$row['stc_brand_title']}</td>
							<td class='text-right'>".number_format($remainingqty, 2)."</td>
							<td class='text-right'>{$row['stc_product_unit']}</td>
							<td class='text-right'>".number_format($rate_gst, 2)."</td>
							<td class='text-right'>".number_format($total, 2)."</td>
						</tr>";
			}
		} else {
			$html .= "<tr><td colspan='8' class='text-center'>No records found.</td></tr>";
		}
		$html .= '</table>';

		// Pagination buttons
		$pagination = '<div>';
		for ($i = 1; $i <= $totalPages; $i++) {
			$pagination .= "<button class='pagination_link' data-page='{$i}'>{$i}</button> ";
		}
		$pagination .= '</div>';

		return ['html' => $html, 'pagination' => $pagination];
	}

	public function getPaginatedProducts2($page, $search, $inv_type){
		$limit = 25;
		$offset = ($page - 1) * $limit;
		$searchQuery = "";
		
		// Base query (without LIMIT)
		$baseQuery = "FROM stc_product P 
			INNER JOIN stc_sub_category S ON P.stc_product_sub_cat_id = S.stc_sub_cat_id 
			INNER JOIN stc_category C ON P.stc_product_cat_id = C.stc_cat_id 
			LEFT JOIN stc_brand B ON B.stc_brand_id = P.stc_product_brand_id 
			INNER JOIN stc_purchase_product_adhoc ON stc_purchase_product_adhoc_productid = P.stc_product_id 
			INNER JOIN stc_shop ON stc_purchase_product_adhoc_id = adhoc_id 
			WHERE P.stc_product_avail = 1 AND shopname = '".mysqli_real_escape_string($this->stc_dbs, $inv_type)."'";

		if ($search) {
			$searchEscaped = mysqli_real_escape_string($this->stc_dbs, $search);
			$baseQuery .= " AND (P.stc_product_id = '$searchEscaped' OR P.stc_product_name LIKE '%$searchEscaped%' OR P.stc_product_desc LIKE '%$searchEscaped%')";
		}

		// Count query for pagination
		$countSql = "SELECT COUNT(DISTINCT P.stc_product_id) as total " . $baseQuery;
		$countResult = mysqli_query($this->stc_dbs, $countSql);
		$totalRows = 0;
		$totalPages = 0;
		if ($countResult) {
			$totalData = mysqli_fetch_assoc($countResult);
			$totalRows = $totalData['total'];
			$totalPages = ceil($totalRows / $limit);
		}

		// Main data query with LIMIT
		$query = "SELECT DISTINCT P.stc_product_id, P.stc_product_name, S.stc_sub_cat_name,  C.stc_cat_name, P.stc_product_brand_id, P.stc_product_unit, P.stc_product_image, 0 as remainingqty, P.stc_product_gst, P.stc_product_sale_percentage, B.stc_brand_title " . $baseQuery . " LIMIT $offset, $limit";

		$result = mysqli_query($this->stc_dbs, $query);
		$html = '
			<table class="table table-bordered">
				<tr>
					<th class="text-center">Item Code</th>
					<th class="text-center">Name</th>
					<th class="text-center">Category</th>
					<th class="text-center">Brand</th>
					<th class="text-center">Stock Qty</th>
					<th class="text-center">Unit</th>
					<th class="text-center">Rate</th>
					<th class="text-center">Total</th>
				</tr>
		';
		if ($result && mysqli_num_rows($result) > 0) {	
			while ($row = mysqli_fetch_assoc($result)) {
				$product_id = $row['stc_product_id'];

				// get quantity from shop
				$shopQtyQuery = mysqli_query($this->stc_dbs, "SELECT SUM(qty) AS total_qty FROM stc_shop INNER JOIN stc_purchase_product_adhoc ON stc_purchase_product_adhoc_id = adhoc_id WHERE stc_purchase_product_adhoc_productid = $product_id AND shopname = '".mysqli_real_escape_string($this->stc_dbs, $inv_type)."'");
				$shopQtyRow = mysqli_fetch_assoc($shopQtyQuery);
				$row['remainingqty'] = $shopQtyRow['total_qty'] ?? 0;
				$remainingqty = $row['remainingqty'];

				// Rate logic (kept as is from your original)
				$rate_gst = 0;
				$rate_percentage = 0;

				$rateQry = mysqli_query($this->stc_dbs, "
					SELECT stc_purchase_product_adhoc_prate, 
						ROUND(stc_purchase_product_adhoc_prate * (1 + {$row['stc_product_sale_percentage']} / 100), 2) AS rate_with_margin
					FROM stc_purchase_product_adhoc 
					WHERE stc_purchase_product_adhoc_productid = $product_id
					ORDER BY stc_purchase_product_adhoc_id DESC LIMIT 1
				");
				if ($rateQry && mysqli_num_rows($rateQry)) {
					$r = mysqli_fetch_assoc($rateQry);
					$rate_gst = $r['stc_purchase_product_adhoc_prate'];
					$rate_percentage = $r['rate_with_margin'];
				}

				// If not available, fallback to GRN rate
				if ($rate_gst == 0) {
					$rateQry = mysqli_query($this->stc_dbs, "
						SELECT stc_product_grn_items_rate,
							ROUND(stc_product_grn_items_rate * (1 + {$row['stc_product_sale_percentage']} / 100), 2) AS rate_with_margin
						FROM stc_product_grn_items 
						WHERE stc_product_grn_items_product_id = $product_id
						ORDER BY stc_product_grn_items_id DESC LIMIT 1
					");
					if ($rateQry && mysqli_num_rows($rateQry)) {
						$r = mysqli_fetch_assoc($rateQry);
						$rate_gst = $r['stc_product_grn_items_rate'];
						$rate_percentage = $r['rate_with_margin'];
					}
				}

				// Prepare product name
				$row['stc_product_name'] = $row['stc_sub_cat_name'] != "OTHERS" ? $row['stc_sub_cat_name'] . " " . $row['stc_product_name'] : $row['stc_product_name'];
				$total = $remainingqty * $rate_gst;

				$html .= "<tr>
							<td>{$row['stc_product_id']}</td>
							<td>{$row['stc_product_name']}</td>
							<td>{$row['stc_cat_name']}</td>
							<td>{$row['stc_brand_title']}</td>
							<td class='text-right'>".number_format($remainingqty, 2)."</td>
							<td class='text-right'>{$row['stc_product_unit']}</td>
							<td class='text-right'>".number_format($rate_gst, 2)."</td>
							<td class='text-right'>".number_format($total, 2)."</td>
						</tr>";
			}
		} else {
			$html .= "<tr><td colspan='8' class='text-center'>No records found.</td></tr>";
		}
		$html .= '</table>';

		// Pagination buttons
		$pagination = '<div>';
		for ($i = 1; $i <= $totalPages; $i++) {
			$pagination .= "<button class='pagination_link' data-page='{$i}'>{$i}</button> ";
		}
		$pagination .= '</div>';

		return ['html' => $html, 'query' => $query, 'pagination' => $pagination];
	}

	// save tool track
	public function stc_tool_tracker_save($repid, $unique, $itemdescription, $machineslno, $make, $type, $warranty, $purdetails, $tinnumber, $tindate, $remarks){
		$date=date("Y-m-d H:i:s");
	
		// Check for duplicate unique ID
		$duplicate_check_qry = mysqli_query($this->stc_dbs, "
			SELECT `unique_id` FROM `stc_tooldetails` WHERE `unique_id` = '".mysqli_real_escape_string($this->stc_dbs, $unique)."'
		");
		// Check for duplicate unique ID
		$duplicate_check_qry2 = mysqli_query($this->stc_dbs, "
			SELECT `unique_id` FROM `stc_tooldetails` WHERE `poa_id` = '".mysqli_real_escape_string($this->stc_dbs, $repid)."'
		");
		// If a duplicate is found, set $blackpearl to "duplicate"
		if(mysqli_num_rows($duplicate_check_qry) > 0) {
			$blackpearl = "duplicate";
		} else if(mysqli_num_rows($duplicate_check_qry2) > 0){
			$blackpearl = "duplicate";
		} else {
			$query="
				INSERT INTO `stc_tooldetails`(
					`poa_id`,
					`unique_id`,
					`itemdescription`,
					`machinesrno`,
					`make`,
					`tooltype`,
					`purchase_details`,
					`warranty`,
					`taxinvono`,
					`taxinvodate`,
					`remarks`,
					`created_by`,
					`created_date`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $repid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $unique)."',
					'".mysqli_real_escape_string($this->stc_dbs, $itemdescription)."',
					'".mysqli_real_escape_string($this->stc_dbs, $machineslno)."',
					'".mysqli_real_escape_string($this->stc_dbs, $make)."',
					'".mysqli_real_escape_string($this->stc_dbs, $type)."',
					'".mysqli_real_escape_string($this->stc_dbs, $warranty)."',
					'".mysqli_real_escape_string($this->stc_dbs, $purdetails)."',
					'".mysqli_real_escape_string($this->stc_dbs, $tinnumber)."',
					'".mysqli_real_escape_string($this->stc_dbs, $tindate)."',
					'".mysqli_real_escape_string($this->stc_dbs, $remarks)."',
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_empl_id'])."',
					'".$date."'
				)
			";
			$blackpearl_qry = mysqli_query($this->stc_dbs, $query);
	
			// If the INSERT query is successful, set $blackpearl to "yes"
			if($blackpearl_qry) {
				$blackpearl = "yes";
			} else {
				// If the INSERT query fails, set $blackpearl to "no"
				$blackpearl = "no";
			}
		}
	
		return $blackpearl;
	}

	
}
#<------------------------------------------------------------------------------------------------------>
#<--------------------------------------Object sections of Purchase class------------------------------->
#<------------------------------------------------------------------------------------------------------>
// show vendor on purhcase order page
if(isset($_POST['friday_vendor'])){
	$objloki=new ragnarPurchaseAdd();
	$objlokiout=$objloki->stc_call_vendor();
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
	$objpdres=new ragnarPurchaseAdd();	
	$opobjpdres=$objpdres->stc_getpd_by_multiple_inp($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout);
	$out=$opobjpdres;
	echo $out;
}

// add to session for purchase order
if(isset($_POST['add_po'])){
	if($_POST["add_po"] == "add") {  
		if(isset($_SESSION["stc_purchase_order_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_purchase_order_sess"] as $keys => $values) {  
				if($_SESSION["stc_purchase_order_sess"][$keys]['product_id'] == $_POST["product_id"]) {  
					$is_available++;  
					$_SESSION["stc_purchase_order_sess"][$keys]['product_quantity'] = $_SESSION["stc_purchase_order_sess"][$keys]['product_quantity'] + $_POST["product_quantity"];  
					echo "Item Cart Quantity Increased!!!!!!";
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
		    	     'product_price'          =>     $_POST["product_price"]  
		    	);  
		    	$_SESSION["stc_purchase_order_sess"][] = $item_array;  
		    	echo "Item Added to PO Cart!!!";
		    }  
		}else{  
			$item_array = array(  
			    'product_id'             =>     $_POST["product_id"],  
		    	'product_name'           =>     $_POST["product_name"],  
		    	'product_unit'           =>     $_POST["product_unit"],  
		    	'product_quantity'       =>     $_POST["product_quantity"],
		    	'product_hsncode'        =>     $_POST["product_hsncode"],
		    	'product_gst'       	 =>     $_POST["product_gst"],
		    	'product_price'          =>     $_POST["product_price"]   
			);   
			$_SESSION["stc_purchase_order_sess"][] = $item_array;  
			echo "PO Cart Created & Item Added to PO Cart!!!";
		}  
    }  
}

// show session on line items on purchase order
if(isset($_POST['stc_show_po_sess'])){
    // $Deliverycharge=20;
	$order_table = '';  
	$slno = '';  
	$order_table .= '  
		<table class="table table-hover table-bordered" align="centre">
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
		if(!empty($_SESSION["stc_purchase_order_sess"])){  
			$total = 0;  
			$totalgst = 0;
			foreach($_SESSION["stc_purchase_order_sess"] as $keys => $values) {  
				$amount=$values["product_quantity"] * $values["product_price"];
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
						    class="validate"
						    placeholder="Quantity"
						    value="'.number_format($values["product_quantity"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcqtypo" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td>
						  <input
						  	style="width: 55%;padding: 5px;height: 40px;"
						    type="text"
						    id="stcratepot'.$values["product_id"].'"
						    class="validate"
						    placeholder="Rate"
						    value="'.number_format($values["product_price"], 2).'"
						  />
						  <a data-product_id="'.$values["product_id"].'" class="stcratepo" href="#"><i class="fas fa-arrow-right"></i></a>
						</td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount, 2).'</td>
						<td> '.$values["product_gst"].'% </td>
						<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($amount + $gstamount, 2).'</td>
						<td><a style="color: red;font-size:20px;" href="#" id="'.$values["product_id"].'" class="stcdelpobtn"><i class="fas fa-trash"></i></a></td>
					</tr>
				';  
				$total = $total + ($values["product_quantity"] * $values["product_price"]) ;  
				$totalgst +=$gstamount;
			}  
			$gstamount = ($amount * $values["product_gst"])/100;
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
				  <td></td>
				</tr>
				<tr>
				  <td colspan="9"><h4 align="right">Grand Total</h4></td>
				  <td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($total + $totalgst, 2).'</td>
				  <td></td>
				</tr>  
			';  
		}else{
			$order_table.='
				<tr>  
					<td colspan="11" align="center">  
					    <h3>Purchase Order Cart Empty!!!</h3>
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
	header('Content-type: application/json');
	echo json_encode($export);
}

// change quantity of session purchase order
if(isset($_POST["quantity_action"])){
  	foreach($_SESSION["stc_purchase_order_sess"] as $keys => $values){  
	    if($_SESSION["stc_purchase_order_sess"][$keys]['product_id'] == $_POST["product_id"]){  
			$_SESSION["stc_purchase_order_sess"][$keys]['product_quantity'] = $_POST["quantity"];
    	}  
  	}  
}

// change price of session purchase order
if(isset($_POST["price_action"])){
  	foreach($_SESSION["stc_purchase_order_sess"] as $keys => $values){  
	    if($_SESSION["stc_purchase_order_sess"][$keys]['product_id'] == $_POST["product_id"]){  
			$_SESSION["stc_purchase_order_sess"][$keys]['product_price'] = $_POST["price"];
    	}  
  	}  
}

// delete from cart of session purchase order
if(isset($_POST['stcdelpolinei'])){ 
	foreach($_SESSION["stc_purchase_order_sess"] as $keys => $values){  
		if($values["product_id"] == $_POST["product_id"]){  
			unset($_SESSION["stc_purchase_order_sess"][$keys]);  
			echo "Product Removed!!!";  
		}  
	}  
}

// save po
if(isset($_POST['stcprosavepo'])){
	$objhelaout='';
	$stcmerchantid=$_POST['merchantid'];
	$stcbasicvalue=$_POST['basicvalue'];
	$stctermsandcond=$_POST['termsandcond'];
	$gtonumberinst=$_POST['gtonumberinst'];
	$reqnoinst=$_POST['reqnoinst'];

	$objloki=new ragnarPurchaseAdd();
	$objhela=new ragnarPurchaseAdd();

	if($stcmerchantid=="NA"){
		$objhelaout="Please select merchant first!!!";
	}elseif(empty($_SESSION['stc_purchase_order_sess'])){
		$objhelaout="Please add atleast one item for create a Purchase Order!!!";
	}else{
		$objlokiout=$objloki->stc_save_purchase_pro($stcmerchantid, $stcbasicvalue, $stctermsandcond, $gtonumberinst, $reqnoinst);
		if($objlokiout=='Success'){
			$objhelaout=$objhela->stc_save_purchase_pro_items();
			unset($_SESSION['stc_purchase_order_sess']);
		}else{
			$objhelaout="Hmmm!!! PO created but line items not added!";
		}
	}
	$out = array(
		'objloki' => $objlokiout,
		'objhela' => $objhelaout
	);
	echo json_encode($out);
	// echo $out;
}

#<--------------------------------------Object sections of po class------------------------------->
// search by same
if(isset($_POST['stcpurchaseaction'])){
	@$begval=$_POST['phpbegvalue'];
	@$endval=$_POST['phpendvalue'];
	$bjornefilterbegdate=$_POST['phpbegdate'];
	$bjornefilterenddate=$_POST['phpenddate'];
	$bjornefiltermerid=$_POST['phpmerchantid'];
	$bjornefilterpono=$_POST['phpponumber'];
	$bjornefilterphpgrnno=$_POST['phpgrnnumber'];
	$bjornefilterphpinvono=$_POST['phpinvonumber'];
	$bjornefilterstatus=$_POST['phpstatus'];
	$out='';
	if(empty($begval) || empty($endval)){
		$begval=0;
		$endval=20;
	}
	$objpdres=new ragnarPurchaseView();	
	$opobjpdres=$objpdres->stc_getpo_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltermerid, $bjornefilterpono, $bjornefilterphpgrnno, $bjornefilterphpinvono, $bjornefilterstatus, $begval, $endval);
	$out=$opobjpdres;
	echo $out;
}

// search po using items 
if(isset($_POST['search_po_pd_name_in_po'])){
	$out='';
	$searchme=$_POST['search_po_pd_name_in_po'];
	$objloki=new ragnarPurchaseView();
	if(strlen($searchme)>=3){
		$objlokiout=$objloki->stc_search_product_thr_po($searchme);
		$out.=$objlokiout;
	}else{
		$out='
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
				<a 
			      href="#" 
			      class="btn btn-primary btn-block text-uppercase mb-3">
			      Enter At least 3 key!!!
			    </a>
			</div>
		';
	}
	echo json_encode($out);
	// echo $out;
}

// call po line items
if(isset($_POST['stc_call_items_for_grant'])){
	$po_id=$_POST['po_id'];

	$objpdres=new ragnarPurchaseView();	
	$opobjpdres=$objpdres->stc_getpoitems_by_po_id($po_id);
	echo $opobjpdres;
}

// change item quantity for grant
if(isset($_POST['stc_change_items_for_grant'])){
	$po_items_id=$_POST['po_items_id'];
	$po_items_qty=$_POST['po_items_qty'];
	$out='';
	if(empty($po_items_qty)){
		$out="Invalid Quantity!!!";
	}else{
		$objpdres=new ragnarPurchaseView();	
		$opobjpdres=$objpdres->stc_changepo_qnty_for_grant_po($po_items_id, $po_items_qty);
		$out=$opobjpdres;
	}
	echo $out;
}

// delete item for grant
if(isset($_POST['stc_delete_items_for_grant'])){
	$po_items_id=$_POST['po_items_id'];
	$out='';
	$objpdres=new ragnarPurchaseView();	
	$opobjpdres=$objpdres->stc_deletpo_items_for_grant_po($po_items_id);
	$out=$opobjpdres;
	echo $out;
}

// change po to grant
if(isset($_POST['stc_change_po_to_grant'])){
	$po_id=$_POST['po_id'];
	$out='';
	$objpdres=new ragnarPurchaseView();	
	$opobjpdres=$objpdres->stc_changepo_to_grant($po_id);
	$out=$opobjpdres;
	echo $out;
}

// change po to grant
if(isset($_POST['stc_change_po_to_cancel'])){
	$po_id=$_POST['po_id'];
	$out='';
	$objpdres=new ragnarPurchaseView();	
	$opobjpdres=$objpdres->stc_changepo_to_cancel($po_id);
	$out=$opobjpdres;
	echo $out;
}
#<--------------------------------------Object sections of po class------------------------------->
// search by same
if(isset($_POST['stceditpoaction'])){
	$bjornefiltercatout=$_POST['phpfiltercatout'];
	$bjornefiltersubcatout=$_POST['phpfiltersubcatout'];
	$bjornefilternameout=$_POST['phpfilternameout'];
	$out='';
	$objpdres=new ragnarPurchaseEdit();	
	$opobjpdres=$objpdres->stc_getpd_by_multiple_inp_for_editpo($bjornefiltercatout, $bjornefiltersubcatout, $bjornefilternameout);
	$out=$opobjpdres;
	echo $out;
}

// edit po line items save object
if(isset($_POST['edit_po'])){
	$product_id = $_POST["product_id"];
	$product_poid = $_POST["product_poid"];
	$product_quantity = $_POST["product_quantity"];
	$product_price = $_POST["product_price"];

	$objloki=new ragnarPurchaseEdit();
	$objlokiout=$objloki->stc_add_po_lineItem($product_id, $product_poid, $product_quantity, $product_price);
	echo json_encode($objlokiout);
}

// Edit po update quantity 
if(isset($_POST['updatepdqty'])){
	$pdid=$_POST['poid'];
	$pdqty=$_POST['product_qty'];
	$objloki=new ragnarPurchaseEdit();
	$objlokiout=$objloki->stc_update_po_lineItemQty($pdid, $pdqty);
	echo json_encode($objlokiout);
}

// Edit po update price
if(isset($_POST['updatepdprice'])){
	$pdid=$_POST['poid'];
	$pdprice=$_POST['product_price'];
	$objloki=new ragnarPurchaseEdit();
	$objlokiout=$objloki->stc_update_po_lineItemRate($pdid, $pdprice);
	echo json_encode($objlokiout);
}

// Edit PO delete po items
if(isset($_POST['delpoli'])){
	$pdid=$_POST['poid'];
	$objloki=new ragnarPurchaseEdit();
	$objlokiout=$objloki->stc_delete_po_lineItem($pdid);
	echo json_encode($objlokiout);
}

// save edited po
if(isset($_POST['saveedit_po'])){
	$pro_id=$_POST['product_id'];
	$mername=$_POST['product_merchant'];
	$tandc=$_POST['product_tandc'];
	$gto_number=$_POST['gto_number'];
	$req_number=$_POST['req_number'];

	if($mername=="NA"){
		echo "Please Select Merchant First!!!";
	}else{
		$objloki=new ragnarPurchaseEdit();
		$objlokiout=$objloki->stc_update_po($pro_id, $mername, $tandc, $gto_number, $req_number);
		echo json_encode($objlokiout);
		// echo $objlokiout;
	}		
}

// change status edit po
if(isset($_POST['changestatusedit_po'])){
	$epono=$_POST['epono'];
	$objloki=new ragnarPurchaseEdit();
	$objlokiout=$objloki->stc_update_status_po($epono);
	echo $objlokiout;
}

#<--------------------------------------Object sections of GRN class------------------------------->
// show grn items on grn page
if(isset($_POST['stc_grn_view_for_edit'])){
	$view_grn_product_id=$_POST['stc_grn_edit_pid'];
	$objloki=new ragnarGRNShow();
	$objlokiout=$objloki->stc_view_grn_items($view_grn_product_id);
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// call po from merchant id
if(isset($_POST['stc_call_po_from_grn_act'])){
	$merchantid=$_POST['vendor_id'];
	$bjornepo=new ragnarGRNShow();
	$outbjornepo=$bjornepo->stc_call_po_from_vendor($merchantid);
	echo json_encode($outbjornepo);
}

// call po date from po id
if(isset($_POST['stc_call_po_date_from_grn_act'])){
	$poid=$_POST['po_id'];
	$bjornepo=new ragnarGRNShow();
	$outbjornepo=$bjornepo->stc_call_podate_from_poid($poid);
	echo json_encode($outbjornepo);
}

// call products if tax invoice new
if(isset($_POST['stc_find_invo_toshow_items'])){
	$invoice_no=$_POST['invoice_no'];
	$invoice_date=date("Y-m-d", strtotime($_POST['invoice_date']));
	$merchant_id=$_POST['vendor_id'];
	$po_no=$_POST['po_no'];
	$out='';

	$bjorneinvocheck=new ragnarGRNShow();
	$bjornecallitems=new ragnarGRNShow();

	$outbjorneinvocheck=$bjorneinvocheck->stc_check_invoice_existance($invoice_no, $invoice_date, $merchant_id);

	if($outbjorneinvocheck=="no"){
		$outbjornecallitems=$bjornecallitems->stc_call_items_for_grn($po_no);
		$out=$outbjornecallitems;
	}else{
		$out=$outbjorneinvocheck;
	}
	echo $out;
}

// show grn of session in grn page
if(isset($_POST['comon_grn_sess'])){
	$order_table = '';  
	$slno = '';  
	$total=0;
	$totalgst=0;
	$order_table .= '  
		<table class="table table-hover table-bordered" align="centre">
			<thead>
			  	<tr>
            	  <th scope="col">#</th>
            	  <th scope="col">Item Code</th>
            	  <th scope="col" style="width: 15%;">Items Desc</th>
            	  <th scope="col">HSN Code</th> 
            	  <th scope="col">Unit</th>
            	  <th scope="col">Accepted Qty</th>
            	  <th scope="col">Change Qty</th>                         
            	  <th scope="col" width="10%">Price</th>
            	  <th scope="col" width="10%">Amount</th>
            	  <th scope="col">GST</th>
            	  <th scope="col" width="10%">Amount</th>
            	  <th scope="col">Remove</th>
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
             			<td align='center'>".$row['product_id']."</td>
             			<td>".$row['product_name']."</td>
             			<td align='center'>".$row['product_hsncode']."</td>
             			<td align='center'>".$row['product_unit']."</td>
             			<td align='right'>".number_format($row['product_quantity'], 2)."</td>
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
             			    class='stcchangegrnsessqtybtn' 
             			    href='#'
             			  >
             			    <i class='fas fa-arrow-right'></i>
             			  </a>
             			</td>
             			<td align='right'><i class='fas fa-rupee-sign'></i> ".number_format($row['product_price'], 2)."</td>
             			<td align='right'><i class='fas fa-rupee-sign'></i> ".number_format($amount, 2)."</td>
             			<td align='center'>".$row['product_gst']."%</td>
             			<td align='right'><i class='fas fa-rupee-sign'></i> ".number_format($amount + $gstamount, 2)."
             			</td>
             			<td align='center'>
             				<a 
	             			  id='".$row["product_id"]."' 
	             			  class='stcdelgrnitems' 
	             			  href='#'
	             			  style='font-size: 35px;text-align: center;padding: 5px;'
	             			><i class='fa fa-times'></i>
	             			</a>
             			</td>
                	</tr>
				";	
				$total = $total + ($row['product_quantity'] * $row['product_price']);  
                $totalgst +=$gstamount;
			}
			$ekdumt=$total + $totalgst;
			$order_table.='
				<tr>
        		  <td colspan="11"><h4 align="right">Total</h4></td>
        		  <td align="right"><i class="fas fa-rupee-sign"></i> '.round($total, 2).'</td>
        		</tr>
        		<tr>
        		  <td colspan="11"><h4 align="right">CGST</h4></td>
        		  <td align="right"><i class="fas fa-rupee-sign"></i> '.round($totalgst/2, 2).'</td>
        		</tr>
        		<tr>
        		  <td colspan="11"><h4 align="right">SGST</h4></td>
        		  <td align="right"><i class="fas fa-rupee-sign"></i> '.round($totalgst/2, 2).'</td>
        		</tr>
        		<tr>
        		  <td colspan="11"><h4 align="right">Total Tax</h4></td>
        		  <td align="right"><i class="fas fa-rupee-sign"></i> '.round($totalgst, 2).'</td>
        		</tr>
        		<tr>
        		  <td colspan="11"><h4 align="right">Sub Total</h4></td>
        		  <td align="right">
        		  	<i class="fas fa-rupee-sign"></i> '.round($total + $totalgst, 2).'
        		  	<input type="hidden" id="stc_grand_offset_table_value" value="'.$ekdumt.'">

        		  </td>
        		</tr>
        		<tr>
            	  <td colspan="4"><h4 align="center">Freight Charges <i class="fas fa-arrow-down"></i></h4></td>
            	  <td colspan="4"><h4 align="center">Packing & Forwarding <i class="fas fa-arrow-down"></i></h4></td>
            	  <td colspan="3"><h4 align="center">Other Charges <i class="fas fa-arrow-down"></i></h4></td>
            	  <td rowspan="2" align="center">
            	  	<a 
            	    	style="font-size: 50px;font-weight: bold;color: red;"
             		  	class="stcgrnfcpo"
             		  	href="#"
             		>
             		  <i class="fas fa-arrow-down"></i>
             		</a>
            	  </td>
        		</tr>

        		<tr>
            	  <td colspan="4" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcfc" name="stcfc" value="0" placeholder="FC" style="width:100%;">
            	  </td>
            	  <td colspan="4" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcpf" name="stcpf" value="0" placeholder="PF" style="width:100%;">
            	  </td>
            	  <td colspan="3" align="right">
            	    <i class="fas fa-rupee-sign"></i>
            	    <input type="text" class="form-control stcoc" name="stcoc" value="0" placeholder="OC" style="width:100%;">
            	  </td>
            	</tr>
            	<tr>
            	  <td colspan="11"><h4 align="right">Grand Total</h4></td>
            	  <td align="right">
            	  	<i class="fas fa-rupee-sign"></i> <span id="stc_final_grn_value">'.round($total + $totalgst, 2).'</span>           	  	
            	  </td>
            	</tr>
			';
		}else{
			$order_table.='
				<tr>  
					<td colspan="12" align="center">  
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
	echo json_encode($export);
}

// add po items to the session for grn
if(isset($_POST['add_grn_sess_action'])){
	$pr_id=$_POST['purchase_pro_id'];
	$pr_qty=$_POST['product_qty'];
	$pr_price=$_POST['product_price'];
	$fridaygrn=new ragnarGRNShow();

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

// delete cart
if(isset($_POST['stc_grn_items_del_cart_hit'])){
	foreach($_SESSION["stc_grn_sess"] as $keys => $values){  
		if($values["product_id"] == $_POST["itemsid"]){  
			unset($_SESSION["stc_grn_sess"][$keys]);  
			echo "Product Removed!!!";  
		}  
	} 
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
	$fridaygrn=new ragnarGRNAdd();
	$fridaygrnitems=new ragnarGRNAdd();
	if(empty($_SESSION['stc_grn_sess'])){
		$out="Please Add Item First!!!";
	}else{
		$objfridaygrn=$fridaygrn->grn_process($grn_order_id, $grn_invodate, $grn_invonumber, $grn_stcfc, $grn_stcpf, $grn_scoc, $grnnotes);
		if($objfridaygrn=='GRN Done!!!'){
			$objfridaygrnitems=$fridaygrnitems->stc_save_grn_pro_items($grn_order_id);
			$out=$objfridaygrnitems;
			unset($_SESSION['stc_grn_sess']);
		}elseif($objfridaygrn=='already grn'){
			$out = "I think this GRN is already done!!!";
		}else{
			$out = "Hmmm!!! Something went not good. Please try again later!!!";
		}
	}
	echo $out;
}

if(isset($_POST['stc_stocking_send_hit'])){
	$refr_id=$_POST['refr_id'];
	$item_for='1';
	$bjornestocking=new ragnarGRNShow();
	$outbjornestocking=$bjornestocking->stc_stocking_send($refr_id, $item_for);
	echo $outbjornestocking;
}

#<--------------------------------------Object sections of Po adhoc class------------------------------->
// save po
if(isset($_POST['stc_po_adhoc_save'])) {
    // Check session first
    if(empty($_SESSION['stc_empl_id'])) {
        echo json_encode("Please Login");
        exit;
    }

    // Initialize response
    $response = "No valid items to save";
    
    // Check if items data exists
    if(isset($_POST['items']) && is_array($_POST['items'])) {
        $items = $_POST['items'];
        $requisition_id = isset($_POST['requisition_id']) ? $_POST['requisition_id'] : 0;
        $objloki = new ragnarPurchaseAdhoc();
        $successCount = 0;
        $errorMessages = [];
        
        foreach($items as $index => $item) {
            // Validate required fields for each item
            if(empty($item['itemname']) || empty($item['quantity']) || empty($item['prate'])) {
                $errorMessages[] = "Row ".($index+1).": Missing required fields";
                continue;
            }
            
            // Validate rack
            if($item['rack'] == "NA") {
                $errorMessages[] = "Row ".($index+1).": Please select a rack";
                continue;
            }
            
            // Process the item
            $objlokiout = $objloki->stc_po_adhoc_save(
                $item['itemcode'], 
                $item['itemname'], 
                $item['quantity'], 
                $item['prate'] ?? '', 
                $item['rate'], 
                $item['unit'] ?? '', 
                $item['rack'], 
                $item['condition'] ?? '', 
                $item['sourcerack'] ?? '', 
                $item['destination'] ?? '', 
                $item['receivedby'] ?? '', 
                $item['remarks'] ?? ''
            );
            
            if($objlokiout === "success") { // Assuming your method returns true on success
                $successCount++;
            } else {
                $errorMessages[] = "Row ".($index+1).": ".$objlokiout;
            }
        }
		if($requisition_id > 0){
			$objlokiout = $objloki->stc_update_brequisition_status($requisition_id);
		}
        if($successCount > 0) {
            $response = ($successCount == count($items)) ? "success" : "Partial success - ".$successCount." of ".count($items)." items saved";
        }
        
        if(!empty($errorMessages)) {
            $response .= $successCount."\n".implode("\n", $errorMessages);
        }
    }
    
    echo json_encode($response);
    exit;
}

// call po adhoc
if(isset($_POST['stc_call_poadhoc'])){
	$itemname=$_POST['itemname'];
	$sourcedestination=$_POST['sourcedestination'];
	$byrack=$_POST['byrack'];
	$status=$_POST['status'];
	$page=$_POST['page'];
	$pageSize=$_POST['pageSize'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_call_poadhoc($itemname, $sourcedestination, $byrack, $status, $page, $pageSize);
	echo json_encode($outbjornestocking);
}

// call po adhoc
if(isset($_POST['stc_call_poadhoc_details'])){
	$poaid=$_POST['poaid'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_call_poadhoc_ddetails($poaid);
	echo $outbjornestocking;
}
// call po adhoc
if(isset($_POST['stc_call_poadhoc_glddetails'])){
	$poaid=$_POST['poaid'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_call_poadhoc_gldddetails($poaid);
	echo $outbjornestocking;
}
// receiving saved
if(isset($_POST['stc_po_adhocrec_save'])){
	$adhoc_id=$_POST['adhoc_id'];
	$receiving=$_POST['receiving'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_poadhoc_rec_save($adhoc_id, $receiving);
	echo $outbjornestocking;
}

// delete po adhoc
if(isset($_POST['stc_po_adhoc_delete'])){
	$adhoc_id=$_POST['adhoc_id'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_poadhoc_delete($adhoc_id);
	echo $outbjornestocking;
}

// update po adhoc item name 
if(isset($_POST['stc_po_adhoc_update'])){
	$adhoc_id=$_POST['adhoc_id'];
	$adhoc_name=$_POST['adhoc_name'];
	$adhoc_rack=$_POST['adhoc_rack'];
	$adhoc_unit=$_POST['adhoc_unit'];
	$adhoc_qty=$_POST['adhoc_qty'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_poadhoc_update($adhoc_id, $adhoc_name, $adhoc_rack, $adhoc_unit, $adhoc_qty);
	echo $outbjornestocking;
}

// update po adhoc cherry pick 
if(isset($_POST['stc_po_adhoc_cherrypic'])){
	$adhoc_id=$_POST['adhoc_id'];
	$qtyToDecrease=$_POST['qtyToDecrease'];
	$newQty=$_POST['newQty'];
	$unit=$_POST['unit'];
	$prate=$_POST['prate'];
	$rate=$_POST['rate'];
	
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_poadhoc_cherrypic($adhoc_id, $qtyToDecrease, $newQty, $unit, $prate, $rate);
	echo $outbjornestocking;
}

// update image id
if(isset($_POST['stc_po_adhoc_imgupdate'])){
	$adhoc_id=$_POST['adhoc_id'];
	$img_id=$_POST['img_id'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_poadhoc_imgupdate($adhoc_id, $img_id);
	echo $outbjornestocking;
}

// update image id
if(isset($_POST['stc_po_adhoc_rateupdate'])){
	$adhoc_id=$_POST['adhoc_id'];
	$rate=$_POST['rate'];
	$prate=$_POST['prate'];
	$percentage=$_POST['percentage'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_poadhoc_rateupdate($adhoc_id, $rate, $prate, $percentage);
	echo $outbjornestocking;
}

// update image id
if(isset($_POST['stc_po_adhoc_getprohistory'])){
	$adhoc_id=$_POST['adhoc_id'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_poadhoc_rohistory($adhoc_id);
	echo json_encode($outbjornestocking);
}

// show ledger
if(isset($_GET['stc_get_ledger'])){
	$dateFrom=$_GET['dateFrom'];
	$dateTo=$_GET['dateTo'];
	$siteName=$_GET['siteName'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_poadhoc_ledger($dateFrom, $dateTo, $siteName);
	// echo $outbjornestocking;
	echo json_encode($outbjornestocking);
}
// show ledger
if(isset($_GET['stc_get_customer_ledger'])){
	$dateFrom=$_GET['dateFrom'];
	$dateTo=$_GET['dateTo'];
	$siteName=$_GET['siteName'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_poadhoc_customerledger($dateFrom, $dateTo, $siteName);
	// echo $outbjornestocking;
	echo json_encode($outbjornestocking);
}

// update ledger
if(isset($_POST['stc_update_ledger'])){
	$id=$_POST['id'];
	$itemcode=$_POST['itemcode'];
	$quantity=$_POST['quantity'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_poadhoc_update_ledger($id, $itemcode, $quantity);
	// echo $outbjornestocking;
	echo json_encode($outbjornestocking);
}

if(isset($_POST['stc_addItemshop'])){
	$id=$_POST['id'];
	$name=$_POST['name'];
	$quantity=$_POST['quantity'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_poadhoc_itemtoshop($id, $name, $quantity);
	// echo $outbjornestocking;
	echo json_encode($outbjornestocking);
}

if(isset($_POST['stc_removeItemshop'])){
	$id=$_POST['id'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_poadhoc_itemtoshopremove($id);
	// echo $outbjornestocking;
	echo json_encode($outbjornestocking);
}

if(isset($_POST['stc_changestatus'])){
	$id=$_POST['id'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_update_adhoc_item_status($id);
	// echo $outbjornestocking;
	echo json_encode($outbjornestocking);
}

if(isset($_POST['stc_changeapprovestatus'])){
	$id=$_POST['id'];
	$status=$_POST['status'];
	$bjornestocking=new ragnarPurchaseAdhoc();
	$outbjornestocking=$bjornestocking->stc_update_adhoc_item_apprstatus($id, $status);
	// echo $outbjornestocking;
	echo json_encode($outbjornestocking);
}

if(isset($_POST['stc_getinventory'])){
    $page = $_POST['page'] ?? 1;
    $search = $_POST['searchKey'] ?? '';
    $inv_type = $_POST['inv_type'] ?? '';

    $bjornestocking = new ragnarPurchaseAdhoc();
	if($inv_type=='warehouse'){
		$result = $bjornestocking->getPaginatedProducts($page, $search);
		echo json_encode($result);
	}else{
		$result = $bjornestocking->getPaginatedProducts2($page, $search, $inv_type);
		echo json_encode($result);
	}
}

// save procurment tracker payment
if(isset($_POST['save_tool_tracker'])){
	$repid=$_POST['repid'];
	$unique=$_POST['unique'];
	$itemdescription=$_POST['itemdescription'];
	$machineslno=$_POST['machineslno'];
	$make=$_POST['make'];
	$type=$_POST['type'];
	$warranty=$_POST['warranty'];
	$purdetails=$_POST['purdetails'];
	$tinnumber=$_POST['tinnumber'];
	$tindate=$_POST['tindate'];
	$remarks=$_POST['remarks'];
	$out='';
	if(empty($_SESSION['stc_empl_id'])){
		$out='reload';
	}else{
		$odin_req=new ragnarPurchaseAdhoc();
		$out=$odin_req->stc_tool_tracker_save($repid, $unique, $itemdescription, $machineslno, $make, $type, $warranty, $purdetails, $tinnumber, $tindate, $remarks);
	}
	echo $out;
}

?>