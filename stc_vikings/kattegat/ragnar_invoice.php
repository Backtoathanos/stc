<?php
include "../../MCU/obdb.php";
session_start();
// call adjust tax invoice 
class ragnarAdjustTaxInvoiceView extends tesseract{
	// call vendor on po page
	public function stc_call_customer(){
		$check_loki=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_customer` GROUP BY `stc_customer_name` ASC");
		$odin='<option value="NA" selected>Select Customer</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='NA' selected>No Customer Found !!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
					<option value="'.$row["stc_customer_id"].'">'.$row["stc_customer_name"].'</option>		               	
				';	
			}
			
		}
		return $odin;
	}

	// show sale order session
	public function stc_show_invo_sess(){
		$odin='';
		$slno=0;
		$grnadtotal=0;
		foreach($_SESSION["stc_sale_order_invo_sess"] as $keys => $values) {
		  	$check_loki=mysqli_query($this->stc_dbs, "
		  		SELECT * FROM `stc_sale_product` 
		  		WHERE `stc_sale_product_id`='".$values['invo_challan_id']."'
		  	");
			$invoamount=0;
			$invogstamount=0;

			foreach($check_loki as $invorow){
				$check_loki_nest='';
				if($invorow['stc_sale_product_dc_keys']=='directchallaned'){
					$check_loki_nest=mysqli_query($this->stc_dbs, "
						SELECT
							`stc_sale_product_dc_items_product_qty` AS pd_qty,
							`stc_sale_product_dc_items_product_sale_rate` AS pd_rate,
							`stc_product_gst`
						FROM `stc_sale_product_dc_items` 
						LEFT JOIN `stc_product`
						ON `stc_sale_product_dc_items_product_id` =`stc_product_id`
						WHERE `stc_sale_product_dc_items_sale_product_id`='".$invorow["stc_sale_product_id"]."'
					");
				}elseif($invorow['stc_sale_product_dc_keys']=='virtualchallaned'){
					$check_loki_nest=mysqli_query($this->stc_dbs, "
						SELECT							
							`stc_sale_product_vc_items_product_qty` AS pd_qty,
							`stc_sale_product_vc_items_product_sale_rate` AS pd_rate,
							`stc_product_gst`
						FROM `stc_sale_product_vc` 
						LEFT JOIN `stc_product`
						ON `stc_sale_product_vc_items_product_id` =`stc_product_id`
						WHERE `stc_sale_product_vc_items_sale_product_id`='".$invorow["stc_sale_product_id"]."'
					");
				}else{
					$check_loki_nest=mysqli_query($this->stc_dbs, "
						SELECT
							`stc_sale_product_items_product_qty` AS pd_qty,
							`stc_sale_product_items_product_sale_rate` AS pd_rate,
							`stc_product_gst`
						FROM `stc_sale_product_items` 
						LEFT JOIN `stc_product`
						ON `stc_sale_product_items_product_id` =`stc_product_id`
						WHERE `stc_sale_product_items_sale_product_id`='".$invorow["stc_sale_product_id"]."'
					");
				}
				foreach ($check_loki_nest as $row2nd) {
					$amt=0;
					$gstamt=0;
					$amt=$row2nd['pd_qty'] * $row2nd['pd_rate'];
					$gstamt=$amt * $row2nd['stc_product_gst']/100;
					$invoamount+=$amt;
					$invogstamount+=$gstamt;
				}
				$grnadtotal+=$invoamount + $invogstamount;
				$slno++;
				$odin .= ' 
					<tr>
						<td>'.$slno.'</td>
						<td>STC/DC/'.substr("0000{$invorow["stc_sale_product_id"]}", -5).'</td>
						<td>'.date('d-m-y', strtotime($invorow["stc_sale_product_date"])).'</td>
						<td>'.$invorow["stc_sale_product_cust_order_no"].'</td>
						<td> '.date('d-m-y', strtotime($invorow["stc_sale_product_cust_order_date"])).'</td>
						<td>'.$invorow["stc_sale_product_sitename"].'</td>
						<td align="right">
							<i class="fas fa-rupee-sign"></i> 
							'.number_format($invoamount, 2).'
						</td>
						<td align="right">
							<i class="fas fa-rupee-sign"></i> 
							'.number_format($invogstamount, 2).'
						</td>
						<td>
							<a 
								style="color: red;font-size:20px;" 
								href="#" 
								id="'.$values["invo_challan_id"].'" 
								class="stcdelinvobtn"
							><i class="fas fa-trash"></i>
							</a>
						</td>
					</tr>
				';  
			}				
		}
		$odin .= ' 
			<tr>
				<td colspan="7" align="right">Total Invoice Value : </td>
				<td align="right"><i class="fas fa-rupee-sign"></i> '.number_format($grnadtotal, 2).'</td>
			</tr>
		';
		return $odin;
	}

	// call site on select of customer
	public function stc_search_site($searchme){
		$check_loki=mysqli_query($this->stc_dbs, "
				SELECT DISTINCT `stc_sale_product_sitename` 
				FROM `stc_sale_product` 
				WHERE `stc_sale_product_cust_id`='".$searchme."'
				AND `stc_sale_product_status`!='billed'
				ORDER BY `stc_sale_product_sitename` ASC
		");
		$odin='<option value="NA" selected>Select Sitename</option>';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin = "<option value='0' selected>No Site Found!!!</option>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
							<option value="'.$row["stc_sale_product_sitename"].'">'.$row["stc_sale_product_sitename"].'</option>		               	
		            	';				
			}
			
		}
		return $odin;
	}

	// Search Challan in Sale Orde Page
	public function stc_search_challan($searchme, $custid){
		$purchase_rate='';
		$alagsequery="					
			SELECT * FROM `stc_sale_product` 
			WHERE `stc_sale_product_sitename`='".mysqli_real_escape_string($this->stc_dbs, $searchme)."' 
			AND `stc_sale_product_cust_id`='".mysqli_real_escape_string($this->stc_dbs, $custid)."'
			AND `stc_sale_product_status`!='billed'
		";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$nestedquery='';
				$totalgst=0;
				$total=0;
				$amount=0;
				$gstamount=0;
				if($row["stc_sale_product_dc_keys"]==""){
					$nestedquery=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_items_product_qty` AS pd_qty, 
							`stc_sale_product_items_product_sale_rate` AS pd_rate, 
							`stc_product_gst` 
						FROM `stc_sale_product_items`
	                 	LEFT JOIN `stc_product`
	                 	ON `stc_product_id`=`stc_sale_product_items_product_id`
	                 	LEFT JOIN `stc_sub_category`
	                 	ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
	                 	WHERE `stc_sale_product_items_sale_product_id`='".$row["stc_sale_product_id"]."'
					");
				}elseif($row["stc_sale_product_dc_keys"]=="directchallaned"){
					$nestedquery=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_dc_items_product_qty` AS pd_qty, 
							`stc_sale_product_dc_items_product_sale_rate` AS pd_rate, 
							`stc_product_gst` 
						FROM `stc_sale_product_dc_items`
	                 	INNER JOIN `stc_product`
	                 	ON `stc_product_id`=`stc_sale_product_dc_items_product_id`
	                 	INNER JOIN `stc_sub_category`
	                 	ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
	                 	WHERE `stc_sale_product_dc_items_sale_product_id`='".$row["stc_sale_product_id"]."'
					");
				}else{
					$nestedquery=mysqli_query($this->stc_dbs, "
						SELECT 
							`stc_sale_product_vc_items_product_qty` AS pd_qty, 
							`stc_sale_product_vc_items_product_sale_rate` AS pd_rate,
							`stc_product_gst` 
						FROM `stc_sale_product_vc`
	                 	INNER JOIN `stc_product`
	                 	ON `stc_product_id`=`stc_sale_product_vc_items_product_id`
	                 	INNER JOIN `stc_sub_category`
	                 	ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
	                 	WHERE `stc_sale_product_vc_items_sale_product_id`='".$row["stc_sale_product_id"]."'
					");
				}
				foreach($nestedquery as $nestedqueryrow){
					$basic=0;
					$gst=0;
					$basic=$nestedqueryrow['pd_qty'] * $nestedqueryrow['pd_rate'];
					$total+=$basic;

					$gst=$basic * $nestedqueryrow['stc_product_gst']/100;
					$totalgst+=$gst;
				}

				$grandtotal=$total + $totalgst;
				$odin.='	
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
							<div class="row">
								<div class="el-wrapper">
								  	<div class="box-up">
								        <div class="img-info">
								 	        <div class="info-inner">
								 	        	<span class="p-name">'.$row["stc_sale_product_cust_order_no"].'</span>
								 	        	<span class="p-name">'.date('d-m-y', strtotime($row["stc_sale_product_cust_order_date"])).'</span>
												<p>'.number_format($grandtotal, 2).'</p>
								 	            <span class="p-name">'.htmlentities($row["stc_sale_product_sitename"]).'</span>
								 	            <span class="p-qty">'.$row["stc_sale_product_dc_keys"].'</span>
								 	        </div>
								         	<div class="a-size">
								 	        	<span class="p-name">'.$row["stc_sale_product_way_bill_no"].'</span><br>
								 	            <span class="p-qty">'.$row["stc_sale_product_cont_person"].'</span><br>
								 	            <span class="p-qty">+91-'.$row["stc_sale_product_cont_number"].'</span>
								          	</div>
								    	</div>
								  	</div>

								  	<div class="box-down">
										<div class="h-bg">
											<div class="h-bg-inner"></div>
										</div>

										<a class="cart add_to_invo_cart" href="#" id="'.$row["stc_sale_product_id"].'">
											<span class="price">STC/DC/'.htmlentities(substr("0000{$row["stc_sale_product_id"]}", -5)).'</span>
											<span class="add-to-cart">
												<span class="txt">Add to Bill</span>
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

	// filter challan by all
	public function stc_getinvoice_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustomerid, $bjornefilterinvoiceid, $bjornefilterchallaninvoiceno, $bjornefilterchallansitename, $bjornebegval, $bjorneendval){
		$ivar='';
		$odinchllanno='';
		$odinchllandate='';
		$odincustordno='';
		$odincustname='';
		$odinchllanstatus='';
		$odinchllanvalue='';
		$odincustorddate='';
		$odintotal=0;	
		$array = array(
			"bycustomer" => $bjornefiltercustomerid,
			"byinvoiceid" => $bjornefilterinvoiceid,
			"byinvoiceno" => $bjornefilterchallaninvoiceno,
			"bysitename" => $bjornefilterchallansitename
		);

		$customer='';
		$invoiceid='';
		$invoiceno='';
		$sitename='';
		$loopcount=0;
		foreach($array as $key => $value){
			if($array['bycustomer']!="NA"){
				$customer="
					AND `stc_customer_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bycustomer'])."'
				";
			}

			if(!empty($array['byinvoiceid'])){
				$invoiceid="
					AND `stc_sale_product_bill_id`='".mysqli_real_escape_string($this->stc_dbs, $array['byinvoiceid'])."'
				";
			}

			if(!empty($array['byinvoiceno'])){
				$invoiceno="
					AND `stc_sale_product_bill_no`='".mysqli_real_escape_string($this->stc_dbs, $array['byinvoiceno'])."'
				";
			}

			if(!empty($array['bysitename'])){
				$sitename="
					AND `stc_sale_product_sitename` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bysitename'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_sale_product_bill`.`stc_sale_product_bill_id` 
			DESC LIMIT '.$bjornebegval.','.$bjorneendval;
			// .$initval.','.$endval;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_sale_product_bill_id`, 
				`stc_sale_product_bill_no`, 
				`stc_sale_product_bill_date`, 
				`stc_sale_product_bill_notes` 
			FROM `stc_sale_product_bill` 
            INNER JOIN `stc_sale_product_bill_no`
            ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
            INNER JOIN `stc_sale_product`
            ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
            INNER JOIN `stc_customer`
            ON `stc_sale_product_cust_id`=`stc_customer_id`
            WHERE (
            	`stc_sale_product_bill_date`
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterbegdate)."'
				AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterenddate)."'
            )".$customer.$invoiceid.$invoiceno.$sitename.$endfilterqry
		);

		$do_action=mysqli_num_rows($ivarfilterquery);
		if($do_action == 0){
			$ivar .= "<tr><td colspan='6' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($ivarfilterquery as $row) {
				$loopcount++;
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT SUM(`stc_sale_product_items_product_qty` * `stc_sale_product_items_product_sale_rate`) 
                    AS TOTAL_INVO 
                    FROM `stc_sale_product_items` 
                    INNER JOIN `stc_sale_product`
                    ON `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
                    INNER JOIN `stc_sale_product_bill_no`
                    ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
                    INNER JOIN `stc_sale_product_bill`
                    ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_bill_id`
                    WHERE `stc_sale_product_bill_id`='".$row["stc_sale_product_bill_id"]."'
				");

				$check_loki_nest_again=mysqli_query($this->stc_dbs, "
					SELECT SUM(`stc_sale_product_dc_items_product_qty` * `stc_sale_product_dc_items_product_sale_rate`) 
                    AS TOTAL_INVO 
                    FROM `stc_sale_product_dc_items` 
                    INNER JOIN `stc_sale_product`
                    ON `stc_sale_product_dc_items_sale_product_id`=`stc_sale_product_id`
                    INNER JOIN `stc_sale_product_bill_no`
                    ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
                    INNER JOIN `stc_sale_product_bill`
                    ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_bill_id`
                    WHERE `stc_sale_product_bill_id`='".$row["stc_sale_product_bill_id"]."'
				");
				$finalbasic=0;
				$incgsttotal=0;
				$soamount=0;
				$soamountagain=0;
				$soamount=mysqli_fetch_assoc($check_loki_nest);
				$soamountagain=mysqli_fetch_assoc($check_loki_nest_again);
				$challankeyident='';
				$waybillno='';
				$again_check_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_sale_product_id`, 
						`stc_sale_product_date`, 
						`stc_sale_product_cust_order_no`, 
						`stc_sale_product_cust_order_date`,
						`stc_customer_name`, 
						`stc_sale_product_sitename`,
						`stc_sale_product_dc_keys`,
						`stc_sale_product_way_bill_no`
					FROM `stc_sale_product_bill_no`
					INNER JOIN `stc_sale_product`
					ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
					INNER JOIN `stc_customer`
					ON `stc_sale_product_cust_id`=`stc_customer_id`
					WHERE `stc_sale_product_bill_no_bill_id`='".$row["stc_sale_product_bill_id"]."'
				");
				$itemcount=0;
				foreach($again_check_loki as $secrow){
					$again_check_loki_nest='';
					$finalvalue=0;					
					$ininvoamtch=0;
					$invototamtch=0;
					$callamot_loki='';
					if($secrow['stc_sale_product_dc_keys']=='directchallaned'){
						$again_check_loki_nest=mysqli_query($this->stc_dbs, "
							SELECT SUM(`stc_sale_product_dc_items_product_qty` * `stc_sale_product_dc_items_product_sale_rate`) 
		                    AS challan_invo 
							FROM `stc_sale_product_dc_items`
                            INNER JOIN `stc_sale_product`
		                    ON `stc_sale_product_dc_items_sale_product_id`=`stc_sale_product_id`
		                    WHERE `stc_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");
						$finalvalue+=$soamountagain['TOTAL_INVO'];

						$callamot_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_sale_product_dc_items_product_qty`, 
								`stc_sale_product_dc_items_product_sale_rate`,
								`stc_product_gst`
							FROM `stc_sale_product_dc_items`
							INNER JOIN `stc_product`
							ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
							WHERE `stc_sale_product_dc_items_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");

						foreach ($callamot_loki as $amtrow){
							$itemcount++;
							$ininvoamt = round($amtrow['stc_sale_product_dc_items_product_qty'], 2) * round($amtrow['stc_sale_product_dc_items_product_sale_rate'], 2);						
							$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
							$ininvoamtch += $ininvoamt;
							$invototamtch += $ininvototamt;
						}
					}elseif($secrow['stc_sale_product_dc_keys']=='virtualchallaned'){
						$again_check_loki_nest=mysqli_query($this->stc_dbs, "
							SELECT SUM(`stc_sale_product_vc_items_product_qty` * `stc_sale_product_vc_items_product_sale_rate`) 
		                    AS challan_invo
							FROM `stc_sale_product_vc`
                            INNER JOIN `stc_sale_product`
		                    ON `stc_sale_product_vc_items_sale_product_id`=`stc_sale_product_id`
		                    WHERE `stc_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");
						$finalvalue+=$soamount['TOTAL_INVO'];

						$callamot_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_sale_product_vc_items_product_qty`, 
								`stc_sale_product_vc_items_product_sale_rate`,
								`stc_product_gst`
							FROM `stc_sale_product_vc`
							INNER JOIN `stc_product`
							ON `stc_sale_product_vc_items_product_id`=`stc_product_id`
							WHERE `stc_sale_product_vc_items_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");

						foreach ($callamot_loki as $amtrow){
							$itemcount++;
							$ininvoamt = round($amtrow['stc_sale_product_vc_items_product_qty'], 2) * round($amtrow['stc_sale_product_vc_items_product_sale_rate'], 2);					
							$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
							$ininvoamtch += $ininvoamt;
							$invototamtch += $ininvototamt;
						}
					}else{
						$again_check_loki_nest=mysqli_query($this->stc_dbs, "
							SELECT SUM(`stc_sale_product_items_product_qty` * `stc_sale_product_items_product_sale_rate`) 
		                    AS challan_invo
							FROM `stc_sale_product_items`
                            INNER JOIN `stc_sale_product`
		                    ON `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
		                    WHERE `stc_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");
						$finalvalue+=$soamount['TOTAL_INVO'];

						$callamot_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_sale_product_items_product_qty`, 
								`stc_sale_product_items_product_sale_rate`,
								`stc_product_gst`
							FROM `stc_sale_product_items`
							INNER JOIN `stc_product`
							ON `stc_sale_product_items_product_id`=`stc_product_id`
							WHERE `stc_sale_product_items_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");

						foreach ($callamot_loki as $amtrow){
							$itemcount++;
							$ininvoamt = round($amtrow['stc_sale_product_items_product_qty'], 2) * round($amtrow['stc_sale_product_items_product_sale_rate'], 2);					
							$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
							$ininvoamtch += $ininvoamt;
							$invototamtch += $ininvototamt;
						}
					}
					$challankeyident .= (empty($secrow['stc_sale_product_dc_keys'])) ? "NA".'<br>' : $secrow['stc_sale_product_dc_keys'].'<br>';
					$waybillno.=$secrow['stc_sale_product_way_bill_no'].'<br>';
					$getinvoqry=mysqli_query($this->stc_dbs, "
						SELECT `stc_sale_product_bill_id` FROM `stc_sale_product_bill` ORDER BY `stc_sale_product_bill_id` DESC
					");
					
					$chsoamount=mysqli_fetch_assoc($again_check_loki_nest);
					$odinchllanno.='STC/DC/'.substr("0000{$secrow["stc_sale_product_id"]}", -5).'</br>';
					$odinchllandate.=date('d-m-y', strtotime($secrow["stc_sale_product_date"])).'</br>';
					$odincustordno.=$secrow["stc_sale_product_cust_order_no"].'</br>';
					$odincustorddate.=$secrow["stc_sale_product_cust_order_date"].'</br>';
					$odincustname=$secrow["stc_customer_name"];
					$odinchllanstatus.=$secrow["stc_sale_product_sitename"].'</br>';
					$odinchllanvalue.='<i class="fas fa-rupee-sign"></i>'.number_format($chsoamount['challan_invo'], 2).'<br>';
					$incgsttotal+=$invototamtch;
					$finalbasic+=$ininvoamtch;					
				}

				$odintotal+=$incgsttotal;
              	// hardcoded left padding if number < $str_length
              	$invoid = substr("0000{$row["stc_sale_product_bill_id"]}", -5);
              	$invonumber = substr("0000{$row["stc_sale_product_bill_no"]}", -5);
				$ivar.='
					<tr>
       					<td>
       						'.$odincustname.'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_bill_date"])).'<br>
       						<h6>STC/'.$invoid.'</h6>
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_bill_date"])).'<br>
       					  	<h6>STC/'.$invonumber.'</h6>
       					</td>
       					<td>
       						'.$odinchllanno.'
       					</td>
       					<td>
       						'.$waybillno.'
       					</td>
       					<td>
       						'.$odinchllanstatus.'
       					</td>
       					<td align="right" style="width:auto;">
       						'.$odinchllanvalue.'
       					</td>
       					<td align="right" style="width:auto;">
       					  <b><i class="fa fa-rupee-sign"></i> '.number_format($finalbasic, 2).'</b><br>
       					  <b><i class="fa fa-rupee-sign"></i> '.number_format($incgsttotal, 2).'</b>
       					</td>
       					<td>
       						'.$challankeyident.'
       					</td>
       					<td class="text-right">
       						'.number_format($itemcount, 2).'
       					</td>
       					<td>
       					  <div class="row">
       					    <div class="col-sm-2">
       					     	<a 
       					     		class="stc_edit_invo" 
       					     		data-toggle="tooltip" 
       					     		data-placement="left" 
       					     		title="Edit Sale Order" 
       					     		href="#"
       					     		id="'.$row["stc_sale_product_bill_id"].'"
       					     		style="font-size: 25px;color: white;"
       					     	><i 
									class="fa fa-edit" 
									aria-hidden="true"
								></i>
       					     	</a>
       					    </div>
       					    <div class="col-sm-2">
       					     	<a 
       					     		class="stc_print_page" 
       					     		data-toggle="tooltip" 
       					     		data-placement="left" 
       					     		title="Tax Invoice" 
       					     		href="
       					     			sale-print-preview.php?pid='.$row["stc_sale_product_bill_id"].' & invono='.$row["stc_sale_product_bill_no"].' 
       					     		" 
       					     		style="font-size: 25px;color: white;"
	       					    ><i 
									class="fa fa-print" 
									aria-hidden="true"
								></i>
	       					    </a>
       					    </div>
       					    <div class="col-sm-2">
       					     	<a 
       					     		class="stc_print_page" 
       					     		data-toggle="tooltip" 
       					     		data-placement="left" 
       					     		title="Retail Invoice" 
       					     		href="
       					     			sale-print-upgraded.php?pid='.$row["stc_sale_product_bill_id"].'&invono='.$row["stc_sale_product_bill_no"].' 
       					     		" 
       					     		style="font-size: 25px;color: white;"
       					     	><i 
									class="fa fa-print" 
									aria-hidden="true"
								></i>
       					     	</a>
       					    </div>
       					  </div>   
       					</td>
            		</tr>         	
		    	';	

		    	$odinchllanno='';
				$odinchllandate='';
				$odincustordno='';
				$odincustname='';
				$odinchllanstatus='';
				$odinchllanvalue='';	
				$odincustorddate='';
				if($loopcount==10){
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

	// call annexture sitename
	public function stc_call_annexture_sitename($begdate, $enddate){
		$odin='<option value="NA" selected>Please Select Sitename</option>';
		$odinannextureqry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_sale_product_sitename`
			FROM
				`stc_sale_product_bill`
			INNER JOIN 
				`stc_sale_product_bill_no` 
			ON 
				`stc_sale_product_bill_no_bill_id`=`stc_sale_product_bill_id` 
			INNER JOIN 
				`stc_sale_product` 
			ON 
				`stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id` 
			WHERE
			    DATE(`stc_sale_product_bill_date`) 
			    BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $begdate)."' 
			    AND '".mysqli_real_escape_string($this->stc_dbs, $enddate)."' 	
			ORDER BY `stc_sale_product_sitename` ASC		    
		");
		foreach($odinannextureqry as $odinannexturerow){
			$odin.='<option value="'.$odinannexturerow['stc_sale_product_sitename'].'">'.$odinannexturerow['stc_sale_product_sitename'].'</option>';
		}
		return $odin;
	}

	// call annexture
	public function stc_call_annexture($begdate, $enddate, $sitename){
		$odin='
			<table class="table table-hover table-bordered" style="font-size : 10px;">
				<thead class="text-center">
					<tr>
						<th>Sl No</th>
						<th>Invoice No<br> Invoice Date</th>
						<th>Challan No<br> Challan Date</th>
						<th>Order No</th>
						<th>Item Description</th>
						<th>HSN Code</th>
						<th>Unit</th>
						<th>Quantity</th>
						<th>Rate</th>
						<th>Amount</th>
						<th>GST %</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
		';
		$slno=0;
		$totbasic=0;
		$tottotal=0;
		$odingetinvoiceqry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_sale_product_bill_no`,
				`stc_sale_product_bill_date`,
				`stc_sale_product_id`,
				`stc_sale_product_date`,
				`stc_sale_product_cust_order_no`
			FROM
				`stc_sale_product_bill`
			INNER JOIN 
				`stc_sale_product_bill_no` 
			ON 
				`stc_sale_product_bill_no_bill_id`=`stc_sale_product_bill_id` 
			INNER JOIN 
				`stc_sale_product` 
			ON 
				`stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id` 
			WHERE (
			    DATE(`stc_sale_product_bill_date`) 
			    BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $begdate)."' 
			    AND '".mysqli_real_escape_string($this->stc_dbs, $enddate)."' 	
			) AND 
			    `stc_sale_product_sitename` LIKE '%".mysqli_real_escape_string($this->stc_dbs, $sitename)."%' 
			ORDER BY `stc_sale_product_sitename` ASC		   
		");
		foreach($odingetinvoiceqry as $odingetinvoiceqry){
			$odingetitemqry=mysqli_query($this->stc_dbs, "
				SELECT
					`stc_product_name`,
					`stc_cat_name`,
					`stc_sub_cat_name`,
					`stc_product_unit`,
					`stc_product_hsncode`,
					`stc_product_gst`,
					`stc_sale_product_items_product_qty`,
					`stc_sale_product_items_product_sale_rate`
				FROM
				    `stc_sale_product_items`
				INNER JOIN 
					`stc_product` 
				ON 
					`stc_product_id` = `stc_sale_product_items_product_id` 
				INNER JOIN 
					`stc_category` 
				ON 
					`stc_cat_id` = `stc_product_cat_id` 
				INNER JOIN 
					`stc_sub_category` 
				ON 
					`stc_sub_cat_id` = `stc_product_sub_cat_id`
				WHERE
				    `stc_sale_product_items_sale_product_id` = '".mysqli_real_escape_string($this->stc_dbs, $odingetinvoiceqry['stc_sale_product_id'])."'
				ORDER BY `stc_product_name` ASC
			");
			foreach($odingetitemqry as $odingetitemrow){
				$slno++;
				$basic=$odingetitemrow['stc_sale_product_items_product_qty'] * $odingetitemrow['stc_sale_product_items_product_sale_rate'];
				$total=$basic + ($basic * $odingetitemrow['stc_product_gst']/100);
				$subcatname= $odingetitemrow['stc_sub_cat_name'] == "OTHERS" ? '' : $odingetitemrow['stc_sub_cat_name'].' ';
				$odin.='
						<tr>
							<td class="text-center">'.$slno.'.</td>
							<td class="text-center">STC/'.substr("0000{$odingetinvoiceqry["stc_sale_product_bill_no"]}", -5).'<br>'.date('d-m-Y', strtotime($odingetinvoiceqry['stc_sale_product_bill_date'])).'</td>
							<td class="text-center">STC/DC/'.substr("0000{$odingetinvoiceqry["stc_sale_product_id"]}", -5).'<br>'.date('d-m-Y', strtotime($odingetinvoiceqry['stc_sale_product_date'])).'</td>
							<td class="text-center">'.$odingetinvoiceqry['stc_sale_product_cust_order_no'].'</td>
							<td width="20%">'.$subcatname.''.$odingetitemrow['stc_product_name'].'</td>
							<td class="text-center">'.$odingetitemrow['stc_product_hsncode'].'</td>
							<td class="text-center">'.$odingetitemrow['stc_product_unit'].'</td>
							<td class="text-right">'.number_format($odingetitemrow['stc_sale_product_items_product_qty'], 2).'</td>
							<td class="text-right">'.number_format($odingetitemrow['stc_sale_product_items_product_sale_rate'], 2).'</td>
							<td class="text-right">'.number_format($basic, 2).'</td>
							<td class="text-center">'.$odingetitemrow['stc_product_gst'].'%</td>
							<td class="text-right">'.number_format($total, 2).'</td>
						</tr>
				';
				$totbasic+=$basic;
				$tottotal+=$total;
			}
		}
		$odin.='
						<tr>
							<td class="text-center" colspan="9">Total</td>
							<td class="text-right">'.number_format($totbasic, 2).'</td>
							<td class="text-center"></td>
							<td class="text-right">'.number_format($tottotal, 2).'</td>
						</tr>
		';
		$odin.='
				</tbody>
			</table>
		';
		return $odin;
	}
}

// add adjust tax invoice
class ragnarAdjustTaxInvoiceAdd extends tesseract{
	// go data to sale table
	public function stc_invo_process($stcinvodate, $stcinvonotes){	
		$odin='';
		$currdate=date('d', strtotime($stcinvodate));
		$currmonth=date('m', strtotime($stcinvodate));
		if($currdate==1 && $currmonth==4){
			$lokichecksale=mysqli_query($this->stc_dbs, "
				SELECT *
				FROM `stc_sale_product_bill` 
	            WHERE DAY(`stc_sale_product_bill_date`)='1'
	            AND MONTH(`stc_sale_product_bill_date`)='4'
				LIMIT 0,1
			");
			if(mysqli_num_rows($lokichecksale)==1){
				$lokigetsale=mysqli_query($this->stc_dbs, "
					SELECT  
						`stc_sale_product_bill_no` AS maxbillno
					FROM `stc_sale_product_bill` 
					ORDER BY `stc_sale_product_bill_id` DESC
					LIMIT 0,1 
				");
				foreach ($lokigetsale as $getrow) {
					$lokiinvogenno=$getrow['maxbillno'] + 1;
					$lokiinsertsale=mysqli_query($this->stc_dbs, "
						INSERT INTO `stc_sale_product_bill`(
							`stc_sale_product_bill_no`,
							`stc_sale_product_bill_date`, 
							`stc_sale_product_bill_notes`
						) VALUES (
							'".$lokiinvogenno."', 
							'".$stcinvodate."', 
							'".$stcinvonotes."'
						)
					");
					if($lokiinsertsale){
						$odin = "invoinserted";
					}else{
						$odin = "Hmm Something went wrong!!!";
					}
					break;				
				}
			}else{
				$lokiinvogenno=1;
				$lokiinsertsale=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_sale_product_bill`(
						`stc_sale_product_bill_no`,
						`stc_sale_product_bill_date`, 
						`stc_sale_product_bill_notes`
					) VALUES (
						'".$lokiinvogenno."', 
						'".$stcinvodate."', 
						'".$stcinvonotes."'
					)
				");
				if($lokiinsertsale){
					$odin = "invoinserted";
				}else{
					$odin = "Hmm Something went wrong!!!";
				}
			}
		}else{
			$lokigetsale=mysqli_query($this->stc_dbs, "
				SELECT  
					`stc_sale_product_bill_no` AS maxbillno
				FROM `stc_sale_product_bill` 
				ORDER BY `stc_sale_product_bill_id` DESC
				LIMIT 0,1 
			");
			foreach ($lokigetsale as $getrow) {
				$lokiinvogenno=$getrow['maxbillno'] + 1;
				$lokiinsertsale=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_sale_product_bill`(
						`stc_sale_product_bill_no`,
						`stc_sale_product_bill_date`, 
						`stc_sale_product_bill_notes`
					) VALUES (
						'".$lokiinvogenno."', 
						'".$stcinvodate."', 
						'".$stcinvonotes."'
					)
				");
				if($lokiinsertsale){
					$odin = "invoinserted";
				}else{
					$odin = "Hmm Something went wrong!!!";
				}
				break;				
			}
		}
		return $odin;
	}

	// go sale session data to sale items table 
	public function stc_invo_challan_process(){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_bill` ORDER BY `stc_sale_product_bill`.`stc_sale_product_bill_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_sale_product_bill_id'];
		if(empty($_SESSION['stc_sale_order_invo_sess'])){
			$odin = "Do not leave Items on Sale Order!!!";
		}else{
			foreach ($_SESSION["stc_sale_order_invo_sess"] as $key=>$value) {
				$lokiinsertsale=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_sale_product_bill_no`(
						`stc_sale_product_bill_no_bill_id`, 
						`stc_sale_product_bill_no_bill_challan_id`,
						`stc_sale_product_bill_no_bill_series`
					) VALUES (
						'".$get_purchase_product_id."', 
						'".$value['invo_challan_id']."',
						'1'
					)
				");
				if($lokiinsertsale){
					$lokiupdatesale=mysqli_query($this->stc_dbs, "
						UPDATE `stc_sale_product` 
						SET `stc_sale_product_status` = 'billed'
						WHERE `stc_sale_product_id`='".$value['invo_challan_id']."'
				");
				}
			}
			$odin .= "Invoice Saved!!!";
		}
		return $odin;
	}
}

// edit adjust tax invoice
class ragnarAdjustTaxInvoiceEdit extends tesseract{
	// change challan to invoices
	public function stc_reset_invo($invo_id, $invo_cid, $invo_change_id){
		$odin='';
		$update_loki=mysqli_query($this->stc_dbs, "
			UPDATE `stc_sale_product_bill_no` 
			SET `stc_sale_product_bill_no_bill_id`='".$invo_change_id."' 
			WHERE `stc_sale_product_bill_no_bill_challan_id`='".$invo_cid."'  
			AND `stc_sale_product_bill_no_bill_id`='".$invo_id."' 
		");
		if($update_loki){
			$odin='Invoice Updated!!!';
		}else{
			$odin='Invoice Not Updated!!!';
		}
		return $odin;
	}

	// call perticular invoice no challan
	public function stc_call_perticular_invo($invo_id){
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Challan Number</th>
	        	    <th scope="col">Challan Date</th>
	        	    <th scope="col">Customer Name</th> 
	        	    <th scope="col">Customer <br>Order No</th>
	        	    <th scope="col">Customer <br>Order Date</th>
	        	    <th scope="col">Sitename</th>
	        	    <th scope="col">Challan Basic</th>                          
	        	    <th scope="col">Sale Order Keys</th>
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
		';
		$call_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_bill_id`,
				`stc_sale_product_id`,
				`stc_sale_product_date`,
				`stc_customer_name`,
				`stc_sale_product_cust_order_no`,
				`stc_sale_product_cust_order_date`,
				`stc_sale_product_sitename`,
    			`stc_sale_product_dc_keys`
			FROM `stc_sale_product_bill` 
			INNER JOIN `stc_sale_product_bill_no` 
			ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
			INNER JOIN `stc_sale_product`
			ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
			INNER JOIN `stc_customer`
			ON `stc_customer_id`=`stc_sale_product_cust_id`
			WHERE `stc_sale_product_bill_id`='".$invo_id."' 
		");

		foreach($call_loki as $row){
			$chdropdown='';
			$hitbtn='';
			$key='';
			$getinvoqry=mysqli_query($this->stc_dbs, "
				SELECT `stc_sale_product_bill_id` 
				FROM `stc_sale_product_bill` 
				ORDER BY `stc_sale_product_bill_id` DESC LIMIT 0,200
			");
			$chdropdown='
				<select 
					style="padding:5px 5px;" 
					class="btn btn-success invo-change-btn-vaue'.$row["stc_sale_product_id"].'"
				>
			';
			foreach($getinvoqry as $outbillno){
				$chdropdown.='
					<option 
						value='.$outbillno['stc_sale_product_bill_id'].'
					>'.$outbillno['stc_sale_product_bill_id'].'
					</option>
				';
			}
			$chdropdown.='</select>';
			$hitbtn='
				<a 
					href="#" 
					style="padding:5px 5px;" 
					class="btn btn-primary invo-change-btn-hit" 
					id="'.$row["stc_sale_product_id"].'"
				>submit</a>
				<input 
					type="hidden" 
					id="chall-id'.$row["stc_sale_product_id"].'" 
					value="'.$row["stc_sale_product_bill_id"].'"
				>
			';
			$key .= (empty($row['stc_sale_product_dc_keys'])) ? "NA".'<br>' : $row['stc_sale_product_dc_keys'].'<br>';
			$odin.='
				<tr>
					<td>STC/DC/'.$row['stc_sale_product_id'].'</td>
					<td>'.date('d-m-y', strtotime($row['stc_sale_product_date'])).'</td>
					<td>'.$row['stc_customer_name'].'</td>
					<td>'.$row['stc_sale_product_cust_order_no'].'</td>
					<td>'.date('d-m-y', strtotime($row['stc_sale_product_cust_order_date'])).'</td>
					<td>'.$row['stc_sale_product_sitename'].'</td>
					<td>'.date('d-m-y', strtotime($row['stc_sale_product_cust_order_date'])).'</td>
					<td>'.$key.'</td>
					<td>'.$chdropdown.$hitbtn.'</td>
				</tr>
			';
		}

		$odin.='
				</tbody>
			</table>
		';
		return $odin;
	}
}

// call additinal tax invoice
class ragnarAdditionalTaxInvoiceView extends tesseract{
	// additional invoice order call
	public function stc_call_sale_orders_add_invoices(){
		$odinchllanno='';
		$odinchllandate='';
		$odincustordno='';
		$odincustname='';
		$odinchllanstatus='';
		$odinchllanvalue='';
		$odincustorddate='';
		$odintotal=0;
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_sale_product_additional_bill_id`, 
				`stc_sale_product_additional_bill_no`, 
				`stc_sale_product_additional_bill_date`, 
				`stc_sale_product_additional_bill_notes` 
			FROM `stc_sale_product_additional_bill` 
			ORDER BY `stc_sale_product_additional_bill`.`stc_sale_product_additional_bill_id`
			DESC LIMIT 0,25
		");
		$odin='
			<table class="table table-hover">
	        	<thead>
	        	  <tr>
	        	    <th scope="col">Invoice Date</th>
	        	    <th scope="col">Invoice Id</th>
	        	    <th scope="col">Invoice Number</th>
	        	    <th scope="col">Challan Number</th>
	        	    <th scope="col">Customer Name</th> 
	        	    <th scope="col">Way Bill No</th> 
	        	    <th scope="col">Sitename</th>
	        	    <th scope="col">Challan Basic</th>                                 
	        	    <th scope="col">Invo Basic Value</th>                                 
	        	    <th scope="col">Total Value</th>                               
	        	    <th scope="col">Sale Order Keys</th>
	        	    <th scope="col">Action</th>
	        	  </tr>
	        	</thead>
	        	<tbody>  
	    ';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='6' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT SUM(`stc_sale_product_items_product_qty` * `stc_sale_product_items_product_sale_rate`) 
                    AS TOTAL_INVO 
                    FROM `stc_sale_product_items` 
                    INNER JOIN `stc_sale_product`
                    ON `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
                    INNER JOIN `stc_sale_product_bill_no`
                    ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
                    INNER JOIN `stc_sale_product_additional_bill`
                    ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_additional_bill_id`
                    WHERE `stc_sale_product_additional_bill_id`='".$row["stc_sale_product_additional_bill_id"]."'
                    AND `stc_sale_product_bill_no_bill_series`='2'
				");

				$check_loki_nest_again=mysqli_query($this->stc_dbs, "
					SELECT SUM(`stc_sale_product_dc_items_product_qty` * `stc_sale_product_dc_items_product_sale_rate`) 
                    AS TOTAL_INVO 
                    FROM `stc_sale_product_dc_items` 
                    INNER JOIN `stc_sale_product`
                    ON `stc_sale_product_dc_items_sale_product_id`=`stc_sale_product_id`
                    INNER JOIN `stc_sale_product_bill_no`
                    ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
                    INNER JOIN `stc_sale_product_additional_bill`
                    ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_additional_bill_id`
                    WHERE `stc_sale_product_additional_bill_id`='".$row["stc_sale_product_additional_bill_id"]."'
                    AND `stc_sale_product_bill_no_bill_series`='2'
				");
				$finalbasic=0;
				$incgsttotal=0;
				$soamount=0;
				$soamountagain=0;
				$soamount=mysqli_fetch_assoc($check_loki_nest);
				$soamountagain=mysqli_fetch_assoc($check_loki_nest_again);
				$challankeyident='';
				$waybillno='';
				$again_check_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_sale_product_id`, 
						`stc_sale_product_date`, 
						`stc_sale_product_cust_order_no`, 
						`stc_sale_product_cust_order_date`,
						`stc_customer_name`, 
						`stc_sale_product_sitename`,
						`stc_sale_product_dc_keys`,
						`stc_sale_product_way_bill_no`
					FROM `stc_sale_product_bill_no`
					INNER JOIN `stc_sale_product`
					ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
					INNER JOIN `stc_customer`
					ON `stc_sale_product_cust_id`=`stc_customer_id`
					WHERE `stc_sale_product_bill_no_bill_id`='".$row["stc_sale_product_additional_bill_id"]."'
					AND `stc_sale_product_bill_no_bill_series`='2'
				");
				foreach($again_check_loki as $secrow){
					$again_check_loki_nest='';
					$finalvalue=0;					
					$ininvoamtch=0;
					$invototamtch=0;
					$callamot_loki='';
					if($secrow['stc_sale_product_dc_keys']=='directchallaned'){
						$again_check_loki_nest=mysqli_query($this->stc_dbs, "
							SELECT SUM(`stc_sale_product_dc_items_product_qty` * `stc_sale_product_dc_items_product_sale_rate`) 
		                    AS challan_invo 
							FROM `stc_sale_product_dc_items`
                            INNER JOIN `stc_sale_product`
		                    ON `stc_sale_product_dc_items_sale_product_id`=`stc_sale_product_id`
		                    WHERE `stc_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");
						$finalvalue+=$soamountagain['TOTAL_INVO'];

						$callamot_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_sale_product_dc_items_product_qty`, 
								`stc_sale_product_dc_items_product_sale_rate`,
								`stc_product_gst`
							FROM `stc_sale_product_dc_items`
							INNER JOIN `stc_product`
							ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
							WHERE `stc_sale_product_dc_items_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");

						foreach ($callamot_loki as $amtrow){
							$ininvoamt = round($amtrow['stc_sale_product_dc_items_product_qty'], 2) * round($amtrow['stc_sale_product_dc_items_product_sale_rate'], 2);						
							$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
							$ininvoamtch += $ininvoamt;
							$invototamtch += $ininvototamt;
						}
					}elseif($secrow['stc_sale_product_dc_keys']=='virtualchallaned'){
						$again_check_loki_nest=mysqli_query($this->stc_dbs, "
							SELECT SUM(`stc_sale_product_vc_items_product_qty` * `stc_sale_product_vc_items_product_sale_rate`) 
		                    AS challan_invo
							FROM `stc_sale_product_vc`
                            INNER JOIN `stc_sale_product`
		                    ON `stc_sale_product_vc_items_sale_product_id`=`stc_sale_product_id`
		                    WHERE `stc_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");
						$finalvalue+=$soamount['TOTAL_INVO'];

						$callamot_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_sale_product_vc_items_product_qty`, 
								`stc_sale_product_vc_items_product_sale_rate`,
								`stc_product_gst`
							FROM `stc_sale_product_vc`
							INNER JOIN `stc_product`
							ON `stc_sale_product_vc_items_product_id`=`stc_product_id`
							WHERE `stc_sale_product_vc_items_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");

						foreach ($callamot_loki as $amtrow){
							$ininvoamt = round($amtrow['stc_sale_product_vc_items_product_qty'], 2) * round($amtrow['stc_sale_product_vc_items_product_sale_rate'], 2);					
							$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
							$ininvoamtch += $ininvoamt;
							$invototamtch += $ininvototamt;
						}
					}else{
						$again_check_loki_nest=mysqli_query($this->stc_dbs, "
							SELECT SUM(`stc_sale_product_items_product_qty` * `stc_sale_product_items_product_sale_rate`) 
		                    AS challan_invo
							FROM `stc_sale_product_items`
                            INNER JOIN `stc_sale_product`
		                    ON `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
		                    WHERE `stc_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");
						$finalvalue+=$soamount['TOTAL_INVO'];

						$callamot_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_sale_product_items_product_qty`, 
								`stc_sale_product_items_product_sale_rate`,
								`stc_product_gst`
							FROM `stc_sale_product_items`
							INNER JOIN `stc_product`
							ON `stc_sale_product_items_product_id`=`stc_product_id`
							WHERE `stc_sale_product_items_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");

						foreach ($callamot_loki as $amtrow){
							$ininvoamt = round($amtrow['stc_sale_product_items_product_qty'], 2) * round($amtrow['stc_sale_product_items_product_sale_rate'], 2);					
							$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
							$ininvoamtch += $ininvoamt;
							$invototamtch += $ininvototamt;
						}
					}
					$challankeyident .= (empty($secrow['stc_sale_product_dc_keys'])) ? "NA".'<br>' : $secrow['stc_sale_product_dc_keys'].'<br>';
					$waybillno.=$secrow['stc_sale_product_way_bill_no'].'<br>';
					$getinvoqry=mysqli_query($this->stc_dbs, "
						SELECT `stc_sale_product_additional_bill_id` FROM `stc_sale_product_additional_bill` ORDER BY `stc_sale_product_additional_bill_id` DESC
					");
					
					$chsoamount=mysqli_fetch_assoc($again_check_loki_nest);
					$odinchllanno.='<p>STC/DC/'.substr("0000{$secrow["stc_sale_product_id"]}", -5).'</p>';
					$odinchllandate.='<p>'.date('d-m-y', strtotime($secrow["stc_sale_product_date"])).'</p>';
					$odincustordno.='<p>'.$secrow["stc_sale_product_cust_order_no"].'</p>';
					$odincustorddate.='<p>'.$secrow["stc_sale_product_cust_order_date"].'</p>';
					$odincustname.='<p>'.$secrow["stc_customer_name"].'</p>';
					$odinchllanstatus.='<p>'.$secrow["stc_sale_product_sitename"].'</p>';
					$odinchllanvalue.='<p align="right"><i class="fas fa-rupee-sign"></i>'.number_format($chsoamount['challan_invo'], 2).'</p>';
					$incgsttotal+=$invototamtch;
					$finalbasic+=$ininvoamtch;					
				}

				$odintotal+=$incgsttotal;
              	// hardcoded left padding if number < $str_length
              	$invoid = substr("0000{$row["stc_sale_product_additional_bill_id"]}", -5);
              	$invonumber = substr("0000{$row["stc_sale_product_additional_bill_no"]}", -5);
				$odin.='
					<tr>
       					<td>
       					  <p>'.date('d-m-y', strtotime($row["stc_sale_product_additional_bill_date"])).'</p>
       					</td>
       					<td>
       					  <h6>STC/'.$invoid.'</h6>
       					</td>
       					<td>
       					  <h6>STC/'.$invonumber.'</h6>
       					</td>
       					<td>
       						'.$odinchllanno.'
       					</td>
       					<td>
       						'.$odincustname.'
       					</td>
       					<td>
       						'.$waybillno.'
       					</td>
       					<td>
       						'.$odinchllanstatus.'
       					</td>
       					<td>
       						'.$odinchllanvalue.'
       					</td>
       					<td align="right" style="width:auto;">
       					  <p><b><i class="fa fa-rupee-sign"></i> '.number_format($finalbasic, 2).'</b></p>
       					</td>
       					<td align="right" style="width:auto;">
       					  <p><b><i class="fa fa-rupee-sign"></i> '.number_format($incgsttotal, 2).'</b></p>
       					</td>
       					<td>
       						'.$challankeyident.'
       					</td>
       					<td>
       					  <div class="row">
       					    <div class="col-sm-2">
       					     	<a 
       					     		class="stc_print_page" 
       					     		data-toggle="tooltip" 
       					     		data-placement="left" 
       					     		title="Tax Invoice" 
       					     		href="sale-print-preview-additional.php?aiid='.$row["stc_sale_product_additional_bill_id"].'" 
       					     		style="font-size: 25px;color: white;"
	       					    >	
	       					    	<i 
	       					     		class="fa fa-print" 
	       					     		aria-hidden="true"
	       					     		></i>
	       					    </a>
       					    </div>
       					    <div class="col-sm-2">
       					     	<a 
       					     		class="stc_print_page" 
       					     		data-toggle="tooltip" 
       					     		data-placement="left" 
       					     		title="Retail Invoice" 
       					     		href="sale-print-retail-preview.php?pid='.$row["stc_sale_product_additional_bill_id"].'" 
       					     		style="font-size: 25px;color: white;"
       					     	>
       					     		<i 
	       					     		class="fa fa-print" 
	       					     		aria-hidden="true"
	       					     		></i>
       					     	</a>
       					    </div>
       					  </div>   
       					</td>
            		</tr>         	
		    	';	

		    	$odinchllanno='';
				$odinchllandate='';
				$odincustordno='';
				$odincustname='';
				$odinchllanstatus='';
				$odinchllanvalue='';	
				$odincustorddate='';		
			}
			
		}
		$odin.='
				</tbody>
	       	</table>
		';
		$out=array(
			'bills_all' => $odin,
			'value_total' => number_format($odintotal, 2)
		);
		return $out;
	}

	// additional invoice challan call
	public function stc_search_challan_for_add_invo($searchme){
		$purchase_rate='';
		$alagsequery="					
					SELECT * FROM `stc_sale_product` 
					WHERE `stc_sale_product_cust_id`='".mysqli_real_escape_string($this->stc_dbs, $searchme)."' 
					AND `stc_sale_product_status`!='billed'
		";
		$check_loki=mysqli_query($this->stc_dbs, $alagsequery);
		$odin='';
		$totalgst=0;
		$total=0;
		$amount=0;
		$gstamount=0;
		$do_action=mysqli_num_rows($check_loki);
		if($do_action > 0){
			foreach ($check_loki as $row) {
				$nestedquery='';
				if($row["stc_sale_product_dc_keys"]==""){
					$nestedquery=mysqli_query($this->stc_dbs, "
						SELECT SUM(`stc_sale_product_items_product_qty` * `stc_sale_product_items_product_sale_rate`) 
							AS `stc_total_value`, `stc_product_gst` 
							FROM `stc_sale_product_items`
	                 	LEFT JOIN `stc_product`
	                 	ON `stc_product_id`=`stc_sale_product_items_sale_product_id`
	                 	LEFT JOIN `stc_sub_category`
	                 	ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
	                 	WHERE `stc_sale_product_items_sale_product_id`='".$row["stc_sale_product_id"]."'
					");
				}elseif($row["stc_sale_product_dc_keys"]=="directchallaned"){
					$nestedquery=mysqli_query($this->stc_dbs, "
						SELECT SUM(`stc_sale_product_dc_items_product_qty` * `stc_sale_product_dc_items_product_sale_rate`) 
							AS `stc_total_value`, `stc_product_gst` 
							FROM `stc_sale_product_dc_items`
	                 	INNER JOIN `stc_product`
	                 	ON `stc_product_id`=`stc_sale_product_dc_items_product_id`
	                 	INNER JOIN `stc_sub_category`
	                 	ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
	                 	WHERE `stc_sale_product_dc_items_sale_product_id`='".$row["stc_sale_product_id"]."'
					");
				}else{
					$nestedquery=mysqli_query($this->stc_dbs, "
						SELECT SUM(`stc_sale_product_vc_items_product_qty` * `stc_sale_product_vc_items_product_sale_rate`) 
							AS `stc_total_value`, `stc_product_gst` 
							FROM `stc_sale_product_vc`
	                 	INNER JOIN `stc_product`
	                 	ON `stc_product_id`=`stc_sale_product_vc_items_product_id`
	                 	INNER JOIN `stc_sub_category`
	                 	ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
	                 	WHERE `stc_sale_product_vc_items_sale_product_id`='".$row["stc_sale_product_id"]."'
					");
				}

				$row2=mysqli_fetch_assoc($nestedquery);
				$amount=$row2['stc_total_value'];
				$gstamount = ($amount * $row2["stc_product_gst"])/100;
	            $grandtotal=round($amount + $gstamount, 2);
				$odin.='	
						<div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
							<div class="row">
								<div class="el-wrapper">
								  	<div class="box-up">
								        <div class="img-info">
								 	        <div class="info-inner">
								 	        	<span class="p-name">'.$row["stc_sale_product_cust_order_no"].'</span>
								 	        	<span class="p-name">'.date('d-m-y', strtotime($row["stc_sale_product_cust_order_date"])).'</span>
								 	            <span class="p-name">'.htmlentities($row["stc_sale_product_sitename"]).'</span>
								 	            <span class="p-qty">'.$row["stc_sale_product_cont_person"].'</span>
								 	            <span class="p-qty">+91-'.$row["stc_sale_product_cont_number"].'</span>
								 	            <span class="p-qty">'.$row["stc_sale_product_dc_keys"].'</span>
								 	        </div>
								         	<div class="a-size">
												<p>'.$grandtotal.'</p>
								          	</div>
								    	</div>
								  	</div>

								  	<div class="box-down">
										<div class="h-bg">
											<div class="h-bg-inner"></div>
										</div>

										<a class="cart add_to_add_invo_cart" href="#" id="'.$row["stc_sale_product_id"].'">
											<span class="price">STC/DC/'.htmlentities(substr("0000{$row["stc_sale_product_id"]}", -5)).'</span>
											<span class="add-to-cart">
												<span class="txt">Add to Bill</span>
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

	// additional show sale order session
	public function stc_show_additional_invo_sess(){
		$odin='';
		$slno=0;
		foreach($_SESSION["stc_sale_order_add_invo_sess"] as $keys => $values) {
		  	$check_loki=mysqli_query($this->stc_dbs, "
		  		SELECT * FROM `stc_sale_product` 
		  		WHERE `stc_sale_product_id`='".$values['invo_challan_id']."'
		  	");

			foreach($check_loki as $invorow){
				$check_loki_nest='';
				$grnamount=0;
				if($invorow['stc_sale_product_dc_keys']=='directchallaned'){
					$check_loki_nest=mysqli_query($this->stc_dbs, "
						SELECT * FROM `stc_sale_product_dc_items` 
						LEFT JOIN `stc_product`
						ON `stc_sale_product_dc_items_sale_product_id` =`stc_product_id`
						WHERE `stc_sale_product_dc_items_sale_product_id`='".$invorow["stc_sale_product_id"]."'
					");
					foreach ($check_loki_nest as $row2nd) {
						$grniqty=$row2nd['stc_sale_product_dc_items_product_qty'];
						$grnirate=$row2nd['stc_sale_product_dc_items_product_sale_rate'];
						$grnamount+=$grniqty * $grnirate;
					}
				}elseif($invorow['stc_sale_product_dc_keys']=='virtualchallaned'){
					$check_loki_nest=mysqli_query($this->stc_dbs, "
						SELECT * FROM `stc_sale_product_vc` 
						LEFT JOIN `stc_product`
						ON `stc_sale_product_vc_items_product_id` =`stc_product_id`
						WHERE `stc_sale_product_vc_items_sale_product_id`='".$invorow["stc_sale_product_id"]."'
					");
					foreach ($check_loki_nest as $row2nd) {
						$grniqty=$row2nd['stc_sale_product_vc_items_product_qty'];
						$grnirate=$row2nd['stc_sale_product_vc_items_product_sale_rate'];
						$grnamount+=$grniqty * $grnirate;
					}
				}else{
					$check_loki_nest=mysqli_query($this->stc_dbs, "
						SELECT * FROM `stc_sale_product_items` 
						LEFT JOIN `stc_product`
						ON `stc_sale_product_items_sale_product_id` =`stc_product_id`
						WHERE `stc_sale_product_items_sale_product_id`='".$invorow["stc_sale_product_id"]."'
					");
					foreach ($check_loki_nest as $row2nd) {
						$grniqty=$row2nd['stc_sale_product_items_product_qty'];
						$grnirate=$row2nd['stc_sale_product_items_product_sale_rate'];
						$grnamount+=$grniqty * $grnirate;
					}
				}
				$slno++;
				$odin .= ' 
					<tr>
						<td>'.$slno.'</td>
						<td>STC/DC/'.substr("0000{$invorow["stc_sale_product_id"]}", -5).'</td>
						<td>'.date('d-m-y', strtotime($invorow["stc_sale_product_date"])).'</td>
						<td>'.$invorow["stc_sale_product_cust_order_no"].'</td>
						<td> '.date('d-m-y', strtotime($invorow["stc_sale_product_cust_order_date"])).'</td>
						<td>'.$invorow["stc_sale_product_sitename"].'</td>
						<td>'.$invorow["stc_sale_product_cont_person"].'</td>
						<td><i class="fas fa-rupee-sign"></i> '.number_format($grnamount, 2).'</td>
						<td><a style="color: red;font-size:20px;" href="#" id="'.$values["invo_challan_id"].'" class="stcdeladdinvobtn"><i class="fas fa-trash"></i></a></td>
					</tr>
				';  
			}
		}  
		return $odin;
	}

	public function stc_getadditionalinvoice_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustomerid, $bjornefilterinvoiceid, $bjornefilterchallaninvoiceno, $bjornefilterchallansitename, $bjornebegval, $bjorneendval){
		$ivar='';
		$odinchllanno='';
		$odinchllandate='';
		$odincustordno='';
		$odincustname='';
		$odinchllanstatus='';
		$odinchllanvalue='';
		$odincustorddate='';
		$odintotal=0;	
		$array = array(
			"bycustomer" => $bjornefiltercustomerid,
			"byinvoiceid" => $bjornefilterinvoiceid,
			"byinvoiceno" => $bjornefilterchallaninvoiceno,
			"bysitename" => $bjornefilterchallansitename
		);

		$customer='';
		$invoiceid='';
		$invoiceno='';
		$sitename='';
		$loopcount=0;
		foreach($array as $key => $value){
			if($array['bycustomer']!="NA"){
				$customer="
					AND `stc_customer_id`='".mysqli_real_escape_string($this->stc_dbs, $array['bycustomer'])."'
				";
			}

			if(!empty($array['byinvoiceid'])){
				$invoiceid="
					AND `stc_sale_product_additional_bill_id`='".mysqli_real_escape_string($this->stc_dbs, $array['byinvoiceid'])."'
				";
			}

			if(!empty($array['byinvoiceno'])){
				$invoiceno="
					AND `stc_sale_product_additional_bill_no`='".mysqli_real_escape_string($this->stc_dbs, $array['byinvoiceno'])."'
				";
			}

			if(!empty($array['bysitename'])){
				$sitename="
					AND `stc_sale_product_sitename` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $array['bysitename'])."'
				";
			}
		}
		$endfilterqry='ORDER BY `stc_sale_product_additional_bill`.`stc_sale_product_additional_bill_id`
			DESC LIMIT '.$bjornebegval.','.$bjorneendval;
			// .$initval.','.$endval;
		$ivarfilterquery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_sale_product_additional_bill_id`, 
				`stc_sale_product_additional_bill_no`, 
				`stc_sale_product_additional_bill_date`, 
				`stc_sale_product_additional_bill_notes` 
			FROM `stc_sale_product_additional_bill`
            INNER JOIN `stc_sale_product_bill_no`
            ON `stc_sale_product_additional_bill_id`=`stc_sale_product_bill_no_bill_id`
            INNER JOIN `stc_sale_product`
            ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
            INNER JOIN `stc_customer`
            ON `stc_sale_product_cust_id`=`stc_customer_id`
            WHERE (
            	`stc_sale_product_additional_bill_date`
				BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterbegdate)."'
				AND '".mysqli_real_escape_string($this->stc_dbs, $bjornefilterenddate)."'
            )".$customer.$invoiceid.$invoiceno.$sitename.$endfilterqry
		);

		$do_action=mysqli_num_rows($ivarfilterquery);
		if($do_action == 0){
			$ivar .= "<tr><td colspan='6' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($ivarfilterquery as $row) {
				$loopcount++;
				$check_loki_nest=mysqli_query($this->stc_dbs, "
					SELECT SUM(`stc_sale_product_items_product_qty` * `stc_sale_product_items_product_sale_rate`) 
                    AS TOTAL_INVO 
                    FROM `stc_sale_product_items` 
                    INNER JOIN `stc_sale_product`
                    ON `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
                    INNER JOIN `stc_sale_product_bill_no`
                    ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
                    INNER JOIN `stc_sale_product_additional_bill`
                    ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_additional_bill_id`
                    WHERE `stc_sale_product_additional_bill_id`='".$row["stc_sale_product_additional_bill_id"]."'
                    AND `stc_sale_product_bill_no_bill_series`='2'
				");

				$check_loki_nest_again=mysqli_query($this->stc_dbs, "
					SELECT SUM(`stc_sale_product_dc_items_product_qty` * `stc_sale_product_dc_items_product_sale_rate`) 
                    AS TOTAL_INVO 
                    FROM `stc_sale_product_dc_items` 
                    INNER JOIN `stc_sale_product`
                    ON `stc_sale_product_dc_items_sale_product_id`=`stc_sale_product_id`
                    INNER JOIN `stc_sale_product_bill_no`
                    ON `stc_sale_product_id`=`stc_sale_product_bill_no_bill_challan_id`
                    INNER JOIN `stc_sale_product_additional_bill`
                    ON `stc_sale_product_bill_no_bill_id`=`stc_sale_product_additional_bill_id`
                    WHERE `stc_sale_product_additional_bill_id`='".$row["stc_sale_product_additional_bill_id"]."'
                    AND `stc_sale_product_bill_no_bill_series`='2'
				");
				$finalbasic=0;
				$incgsttotal=0;
				$soamount=0;
				$soamountagain=0;
				$soamount=mysqli_fetch_assoc($check_loki_nest);
				$soamountagain=mysqli_fetch_assoc($check_loki_nest_again);
				$challankeyident='';
				$waybillno='';
				$again_check_loki=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_sale_product_id`, 
						`stc_sale_product_date`, 
						`stc_sale_product_cust_order_no`, 
						`stc_sale_product_cust_order_date`,
						`stc_customer_name`, 
						`stc_sale_product_sitename`,
						`stc_sale_product_dc_keys`,
						`stc_sale_product_way_bill_no`
					FROM `stc_sale_product_bill_no`
					INNER JOIN `stc_sale_product`
					ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
					INNER JOIN `stc_customer`
					ON `stc_sale_product_cust_id`=`stc_customer_id`
					WHERE `stc_sale_product_bill_no_bill_id`='".$row["stc_sale_product_additional_bill_id"]."'
					AND `stc_sale_product_bill_no_bill_series`='2'
				");
				foreach($again_check_loki as $secrow){
					$again_check_loki_nest='';
					$finalvalue=0;					
					$ininvoamtch=0;
					$invototamtch=0;
					$callamot_loki='';
					if($secrow['stc_sale_product_dc_keys']=='directchallaned'){
						$again_check_loki_nest=mysqli_query($this->stc_dbs, "
							SELECT SUM(`stc_sale_product_dc_items_product_qty` * `stc_sale_product_dc_items_product_sale_rate`) 
		                    AS challan_invo 
							FROM `stc_sale_product_dc_items`
                            INNER JOIN `stc_sale_product`
		                    ON `stc_sale_product_dc_items_sale_product_id`=`stc_sale_product_id`
		                    WHERE `stc_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");
						$finalvalue+=$soamountagain['TOTAL_INVO'];

						$callamot_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_sale_product_dc_items_product_qty`, 
								`stc_sale_product_dc_items_product_sale_rate`,
								`stc_product_gst`
							FROM `stc_sale_product_dc_items`
							INNER JOIN `stc_product`
							ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
							WHERE `stc_sale_product_dc_items_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");

						foreach ($callamot_loki as $amtrow){
							$ininvoamt = round($amtrow['stc_sale_product_dc_items_product_qty'], 2) * round($amtrow['stc_sale_product_dc_items_product_sale_rate'], 2);						
							$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
							$ininvoamtch += $ininvoamt;
							$invototamtch += $ininvototamt;
						}
					}elseif($secrow['stc_sale_product_dc_keys']=='virtualchallaned'){
						$again_check_loki_nest=mysqli_query($this->stc_dbs, "
							SELECT SUM(`stc_sale_product_vc_items_product_qty` * `stc_sale_product_vc_items_product_sale_rate`) 
		                    AS challan_invo
							FROM `stc_sale_product_vc`
                            INNER JOIN `stc_sale_product`
		                    ON `stc_sale_product_vc_items_sale_product_id`=`stc_sale_product_id`
		                    WHERE `stc_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");
						$finalvalue+=$soamount['TOTAL_INVO'];

						$callamot_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_sale_product_vc_items_product_qty`, 
								`stc_sale_product_vc_items_product_sale_rate`,
								`stc_product_gst`
							FROM `stc_sale_product_vc`
							INNER JOIN `stc_product`
							ON `stc_sale_product_vc_items_product_id`=`stc_product_id`
							WHERE `stc_sale_product_vc_items_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");

						foreach ($callamot_loki as $amtrow){
							$ininvoamt = round($amtrow['stc_sale_product_vc_items_product_qty'], 2) * round($amtrow['stc_sale_product_vc_items_product_sale_rate'], 2);					
							$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
							$ininvoamtch += $ininvoamt;
							$invototamtch += $ininvototamt;
						}
					}else{
						$again_check_loki_nest=mysqli_query($this->stc_dbs, "
							SELECT SUM(`stc_sale_product_items_product_qty` * `stc_sale_product_items_product_sale_rate`) 
		                    AS challan_invo
							FROM `stc_sale_product_items`
                            INNER JOIN `stc_sale_product`
		                    ON `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
		                    WHERE `stc_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");
						$finalvalue+=$soamount['TOTAL_INVO'];

						$callamot_loki=mysqli_query($this->stc_dbs, "
							SELECT 
								`stc_sale_product_items_product_qty`, 
								`stc_sale_product_items_product_sale_rate`,
								`stc_product_gst`
							FROM `stc_sale_product_items`
							INNER JOIN `stc_product`
							ON `stc_sale_product_items_product_id`=`stc_product_id`
							WHERE `stc_sale_product_items_sale_product_id`='".$secrow["stc_sale_product_id"]."'
						");

						foreach ($callamot_loki as $amtrow){
							$ininvoamt = round($amtrow['stc_sale_product_items_product_qty'], 2) * round($amtrow['stc_sale_product_items_product_sale_rate'], 2);					
							$ininvototamt = $ininvoamt + (($ininvoamt * $amtrow["stc_product_gst"])/100);
							$ininvoamtch += $ininvoamt;
							$invototamtch += $ininvototamt;
						}
					}
					$challankeyident .= (empty($secrow['stc_sale_product_dc_keys'])) ? "NA".'<br>' : $secrow['stc_sale_product_dc_keys'].'<br>';
					$waybillno.=$secrow['stc_sale_product_way_bill_no'].'<br>';
					$getinvoqry=mysqli_query($this->stc_dbs, "
						SELECT `stc_sale_product_additional_bill_id` FROM `stc_sale_product_additional_bill` 
						ORDER BY `stc_sale_product_additional_bill_id` DESC
					");
					
					$chsoamount=mysqli_fetch_assoc($again_check_loki_nest);
					$odinchllanno.='STC/DC/'.substr("0000{$secrow["stc_sale_product_id"]}", -5).'</br>';
					$odinchllandate.=date('d-m-y', strtotime($secrow["stc_sale_product_date"])).'</br>';
					$odincustordno.='<p>'.$secrow["stc_sale_product_cust_order_no"].'</p>';
					$odincustorddate.='<p>'.$secrow["stc_sale_product_cust_order_date"].'</p>';
					$odincustname.=$secrow["stc_customer_name"];
					$odinchllanstatus.=$secrow["stc_sale_product_sitename"].'<br>';
					$odinchllanvalue.='<i class="fas fa-rupee-sign"></i>'.number_format($chsoamount['challan_invo'], 2);
					$incgsttotal+=$invototamtch;
					$finalbasic+=$ininvoamtch;
				}

				$odintotal+=$incgsttotal;
              	// hardcoded left padding if number < $str_length
              	$invoid = substr("0000{$row["stc_sale_product_additional_bill_id"]}", -5);
              	$invonumber = substr("0000{$row["stc_sale_product_additional_bill_no"]}", -5);
				$ivar.='
					<tr>
       					<td>
       						'.$odincustname.'
       					</td>
       					<td>
       						'.date('d-m-y', strtotime($row["stc_sale_product_additional_bill_date"])).'<br>
       						<h6>STC/'.$invoid.'</h6>
       					</td>
       					<td>
       					  <h6>STC/'.$invonumber.'</h6>
       					</td>
       					<td>
       						'.$odinchllanno.'
       					</td>
       					<td>
       						'.$waybillno.'
       					</td>
       					<td>
       						'.$odinchllanstatus.'
       					</td>
       					<td align="right" >
       						'.$odinchllanvalue.'
       					</td>
       					<td align="right" style="width:auto;">
       					  <b><i class="fa fa-rupee-sign"></i> '.number_format($finalbasic, 2).'</b><br>
       					  <b><i class="fa fa-rupee-sign"></i> '.number_format($incgsttotal, 2).'</b>
       					</td>
       					<td>
       						'.$challankeyident.'
       					</td>
       					<td>
       					  <div class="row">
       					    <div class="col-sm-2">
       					     	<a 
       					     		class="stc_print_page" 
       					     		data-toggle="tooltip" 
       					     		data-placement="left" 
       					     		title="Tax Invoice" 
       					     		href="sale-print-preview-additional.php?aiid='.$row["stc_sale_product_additional_bill_id"].'" 
       					     		style="font-size: 25px;color: white;"
	       					    ><i 
									class="fa fa-print" 
									aria-hidden="true"
								></i>
	       					    </a>
       					    </div>
       					    <div class="col-sm-2">
       					     	<a 
       					     		class="stc_print_page" 
       					     		data-toggle="tooltip" 
       					     		data-placement="left" 
       					     		title="Retail Invoice" 
       					     		href="sale-print-upgraded-additional.php?aiid='.$row["stc_sale_product_additional_bill_id"].'" 
       					     		style="font-size: 25px;color: white;"
       					     	><i 
									class="fa fa-print" 
									aria-hidden="true"
								></i>
       					     	</a>
       					    </div>
       					  </div>   
       					</td>
            		</tr>         	
		    	';	

		    	$odinchllanno='';
				$odinchllandate='';
				$odincustordno='';
				$odincustname='';
				$odinchllanstatus='';
				$odinchllanvalue='';	
				$odincustorddate='';	
				if($loopcount==10){
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

// add additional tax invoice
class ragnarAdditionalTaxInvoiceAdd extends tesseract{
	// additional go data to sale table
	public function stc_additional_invo_process($stcinvodate, $stcinvonotes){	
		$odin='';
		$currdate=date('d', strtotime($stcinvodate));
		$currmonth=date('m', strtotime($stcinvodate));
		if($currdate==1 && $currmonth==4){
			$lokichecksale=mysqli_query($this->stc_dbs, "
				SELECT *
				FROM `stc_sale_product_additional_bill` 
	            WHERE DAY(`stc_sale_product_additional_bill_date`)='1'
	            AND MONTH(`stc_sale_product_additional_bill_date`)='4'
				LIMIT 0,1
			");
			if(mysqli_num_rows($lokichecksale)==1){
				$lokigetsale=mysqli_query($this->stc_dbs, "
					SELECT  
						`stc_sale_product_additional_bill_no` AS maxbillno
					FROM `stc_sale_product_additional_bill` 
					ORDER BY `stc_sale_product_additional_bill_id` DESC
					LIMIT 0,1 
				");
				foreach ($lokigetsale as $getrow) {
					$lokiinvogenno=$getrow['maxbillno'] + 1;
					$lokiinsertsale=mysqli_query($this->stc_dbs, "
						INSERT INTO `stc_sale_product_additional_bill`(
							`stc_sale_product_additional_bill_no`,
							`stc_sale_product_additional_bill_date`, 
							`stc_sale_product_additional_bill_notes`
						) VALUES (
							'".$lokiinvogenno."', 
							'".$stcinvodate."', 
							'".$stcinvonotes."'
						)
					");
					if($lokiinsertsale){
						$odin = "invoinserted";
					}else{
						$odin = "Hmm Something went wrong!!!";
					}
					break;				
				}
			}else{
				$lokiinvogenno=1;
				$lokiinsertsale=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_sale_product_additional_bill`(
						`stc_sale_product_additional_bill_no`,
						`stc_sale_product_additional_bill_date`, 
						`stc_sale_product_additional_bill_notes`
					) VALUES (
						'".$lokiinvogenno."', 
						'".$stcinvodate."', 
						'".$stcinvonotes."'
					)
				");
				if($lokiinsertsale){
					$odin = "invoinserted";
				}else{
					$odin = "Hmm Something went wrong!!!";
				}
			}
		}else{
			$lokigetsale=mysqli_query($this->stc_dbs, "
				SELECT  
					`stc_sale_product_additional_bill_no` AS maxbillno
				FROM `stc_sale_product_additional_bill` 
				ORDER BY `stc_sale_product_additional_bill_id` DESC
				LIMIT 0,1  
			");
			foreach ($lokigetsale as $getrow) {
				$lokiinvogenno=$getrow['maxbillno'] + 1;
				$lokiinsertsale=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_sale_product_additional_bill`(
						`stc_sale_product_additional_bill_no`,
						`stc_sale_product_additional_bill_date`, 
						`stc_sale_product_additional_bill_notes`
					) VALUES (
						'".$lokiinvogenno."', 
						'".$stcinvodate."', 
						'".$stcinvonotes."'
					)
				");
				if($lokiinsertsale){
					$odin = "invoinserted";
				}else{
					$odin = "Hmm Something went wrong!!!";
				}
				break;				
			}
		}
		return $odin;
	}

	// additional go sale session data to sale items table 
	public function stc_additional_invo_challan_process(){
		$odin='';
		$loki_get_max_id=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_additional_bill` 
			ORDER BY `stc_sale_product_additional_bill`.`stc_sale_product_additional_bill_id` DESC
		");
		$stc_purchase_product_items_order_id=mysqli_fetch_assoc($loki_get_max_id);
		$get_purchase_product_id=$stc_purchase_product_items_order_id['stc_sale_product_additional_bill_id'];
		if(empty($_SESSION['stc_sale_order_add_invo_sess'])){
			$odin = "Do not leave Items on Sale Order!!!";
		}else{
			foreach ($_SESSION["stc_sale_order_add_invo_sess"] as $key=>$value) {
				$lokiinsertsale=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_sale_product_bill_no`(
						`stc_sale_product_bill_no_bill_id`, 
						`stc_sale_product_bill_no_bill_challan_id`,
						`stc_sale_product_bill_no_bill_series`
					) VALUES (
						'".$get_purchase_product_id."', 
						'".$value['invo_challan_id']."',
						'2'
					)
				");
				if($lokiinsertsale){
					$lokiupdatesale=mysqli_query($this->stc_dbs, "
						UPDATE `stc_sale_product` 
						SET `stc_sale_product_status` = 'billed'
						WHERE `stc_sale_product_id`='".$value['invo_challan_id']."'
				");
				}
			}
			$odin .= "Invoice Saved!!!";
		}
		return $odin;
	}
}
#<------------------------------------------------------------------------------------------------------>
#<--------------------------------------Object sections of Invoice class-------------------------------->
#<------------------------------------------------------------------------------------------------------>
// show vendor on quotation page
if(isset($_POST['friday_customer'])){
	$objloki=new ragnarAdjustTaxInvoiceView();
	$objlokiout=$objloki->stc_call_customer();
	$date=date('l jS \ F Y h:i:s A');
	$outarray=array(
		'time' => $date,
		'customer' => $objlokiout
	);
	echo json_encode($outarray);
	// echo $outarray;
}

// show sale cart
if(isset($_POST['comon_invo_sess'])){
	$objloki=new ragnarAdjustTaxInvoiceView();	
	$order_table = ''; 
	$order_table .= '  
		<table class="table table-hover" align="centre">
			<thead>
			  <tr>
			    <th scope="col">#</th>
			    <th scope="col">Challan No</th>
			    <th scope="col">Challan Date</th> 
			    <th scope="col">Cust PO Number</th>
			    <th scope="col">Cust PO Date</th>                         
			    <th scope="col">Site Name</th>
			    <th scope="col">Basic Value</th>
			    <th scope="col">GST Value</th>
			    <th scope="col">Action</th>
			  </tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_sale_order_invo_sess"])){  
			$objlokiout=$objloki->stc_show_invo_sess();
			$order_table .= $objlokiout;
		}else{
			$order_table.='
				<tr>  
					<td colspan="10" align="center">  
					    <h3>Invoice Session Empty!!!</h3>
					</td>  
				</tr>  
			';
		}
	$order_table .= '
			
		  </tbody>
		</table>
		'; 
	echo $order_table;
}

// show Product in sale Order page
if(isset($_POST['call_site_on_choose_customer'])){
	$searchme=$_POST['customer_id'];
	$objloki=new ragnarAdjustTaxInvoiceView();
	$objlokiout=$objloki->stc_search_site($searchme);
	// echo json_encode($objlokiout);
	echo $objlokiout;
}

// show Product in sale Order page
if(isset($_POST['call_challan_on_choose_customer_site'])){
	$searchme=$_POST['js_sitename'];
	$custid=$_POST['js_custid'];
	$objloki=new ragnarAdjustTaxInvoiceView();
	$objlokiout=$objloki->stc_search_challan($searchme, $custid);
	// echo json_encode($objlokiout);
	echo $objlokiout;
}

// add to cart sale session obj
if(isset($_POST['add_invo_sess_action'])){
	if(isset($_SESSION["stc_sale_order_invo_sess"])) {
		$is_available=0;
		foreach($_SESSION["stc_sale_order_invo_sess"] as $keys => $values) {  
			if($_SESSION["stc_sale_order_invo_sess"][$keys]['invo_challan_id'] == $_POST["invo_challan_id"]) {  
			     $is_available++; 
			     echo "This Challan is Already on a List!!!";
			}  
		} 
		if($is_available < 1) {  
			$item_array = array(  
			     'invo_challan_id' => $_POST["invo_challan_id"]
			);  
			$_SESSION["stc_sale_order_invo_sess"][] = $item_array;  
			echo "Invoice Session Created & Challan Added to Cart!!!";
		}
	}else{  
		$item_array = array(  
		    'invo_challan_id' => $_POST["invo_challan_id"]
		);
		$_SESSION["stc_sale_order_invo_sess"][] = $item_array;
	     echo "Challan Added to Invoice Session Cart!!!";
	}
}

// delete from sale cart of session Sale order
if(isset($_POST['stcdelinvolinei'])){  
	foreach($_SESSION["stc_sale_order_invo_sess"] as $keys => $values){  
		if($values["invo_challan_id"] == $_POST["product_id"]){  
			unset($_SESSION["stc_sale_order_invo_sess"][$keys]);  
			echo "Challan Removed!!!";  
		}  
	}  
}

// save sale order
if(isset($_POST['save_invo_action'])){
	$out='';
	$stcinvodate=date('Y-m-d', strtotime($_POST['stcinvodate']));
	$invo_cust_id=$_POST['invo_cust_id'];
	$stcinvonotes=$_POST['stcinvonotes'];
	
	$fridayinvo=new ragnarAdjustTaxInvoiceAdd();
	$fridayinvochallan=new ragnarAdjustTaxInvoiceAdd();
	if(empty($stcinvodate)){
		$out.="Please Fill Date First!!!";
	}else{
		$objfridayinvo=$fridayinvo->stc_invo_process(
				$stcinvodate,
				$stcinvonotes
		);
		if($objfridayinvo=='invoinserted'){
			$objfridayinvochallan=$fridayinvochallan->stc_invo_challan_process();
			$out=$objfridayinvochallan;
			unset($_SESSION['stc_sale_order_invo_sess']);
		}else{
			$out .= "Something went Wrong Please Check & Try Again!!!";
		}
	}
	echo $out;
}

// search by same
if(isset($_POST['stcadjusttaxinvoaction'])){
	@$bjornebegval=$_POST['phpbegvalue'];
	@$bjorneendval=$_POST['phpendvalue'];
	$bjornefilterbegdate=$_POST['phpbegdate'];
	$bjornefilterenddate=$_POST['phpenddate'];
	$bjornefiltercustomerid=$_POST['phpcustomerid'];
	$bjornefilterinvoiceid=$_POST['phpinvoiceid'];
	$bjornefilterchallaninvoiceno=$_POST['phpchallaninvoiceno'];
	$bjornefilterchallansitename=$_POST['phpchallansitename'];
	$out='';
	if(empty($bjornebegval) || empty($bjorneendval)){
		$bjornebegval=0;
		$bjorneendval=10;
	}
	$objpdres=new ragnarAdjustTaxInvoiceView();	
	$opobjpdres=$objpdres->stc_getinvoice_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustomerid, $bjornefilterinvoiceid, $bjornefilterchallaninvoiceno, $bjornefilterchallansitename, $bjornebegval, $bjorneendval);
	$out=$opobjpdres;
	echo $out;
}

// reset challan to another challan
if(isset($_POST['invo_reset_hit'])){
	$invo_id=$_POST['invo_id'];
	$invo_cid=$_POST['invo_challan_id'];
	$invo_change_id=$_POST['invo_change_id'];
	$objloki=new ragnarAdjustTaxInvoiceEdit();
	$objlokiout=$objloki->stc_reset_invo($invo_id, $invo_cid, $invo_change_id);
	echo $objlokiout;
}

// call challan on perticular invoice
if(isset($_POST['loadpertinvo'])){
	$invo_id=$_POST['invoid'];
	$objloki=new ragnarAdjustTaxInvoiceEdit();
	$objlokiout=$objloki->stc_call_perticular_invo($invo_id);
	// echo json_encode($objlokiout);
	echo $objlokiout;
}

// call annexture sitename
if(isset($_POST['friday_call_sitename'])){
	$begdate=$_POST['begdate'];
	$enddate=$_POST['enddate'];
	$objloki=new ragnarAdjustTaxInvoiceView();
	$objlokiout=$objloki->stc_call_annexture_sitename($begdate, $enddate);
	// echo json_encode($objlokiout);
	echo $objlokiout;
}

// call annexture
if(isset($_POST['friday_call_annexture'])){
	$begdate=date('Y-m-d', strtotime($_POST['begdate']));
	$enddate=date('Y-m-d', strtotime($_POST['enddate']));	
	$sitename=$_POST['sitename'];
	$objloki=new ragnarAdjustTaxInvoiceView();
	$objlokiout=$objloki->stc_call_annexture($begdate, $enddate, $sitename);
	echo $objlokiout;
}
#<----------------------------Object sections of Additional Invoice class-------------------------->
// 
if(isset($_POST['load_additional_invoices'])){
	$objloki=new ragnarAdditionalTaxInvoiceView();
	$objlokiout=$objloki->stc_call_sale_orders_add_invoices();
	echo json_encode($objlokiout);
	// echo $objlokiout;
}

// show Product in sale Order page
if(isset($_POST['call_challan_on_choose_customer_addinvo'])){
	$searchme=$_POST['customer_id'];
	$objloki=new ragnarAdditionalTaxInvoiceView();
	$objlokiout=$objloki->stc_search_challan_for_add_invo($searchme);
	echo $objlokiout;
}

// show additional invoice values
if(isset($_POST['comon_additional_invo_sess'])){
	$objloki=new ragnarAdditionalTaxInvoiceView();	
	$order_table = ''; 
	$order_table .= '  
		<table class="table table-hover table-bordered" align="centre">
			<thead>
			  <tr>
			    <th scope="col">#</th>
			    <th scope="col">Challan No</th>
			    <th scope="col">Challan Date</th> 
			    <th scope="col">Cust PO Number</th>
			    <th scope="col">Cust PO Date</th>                         
			    <th scope="col">Site</th>
			    <th scope="col">Contact Person</th>
			    <th scope="col">Basic Value</th>
			    <th scope="col">Action</th>
			  </tr>
			</thead>
			<tbody>
		';  
		if(!empty($_SESSION["stc_sale_order_add_invo_sess"])){  
			$objlokiout=$objloki->stc_show_additional_invo_sess();
			$order_table .= $objlokiout;
		}else{
			$order_table.='
				<tr>  
					<td colspan="10" align="center">  
					    <h3>Invoice Session Empty!!!</h3>
					</td>  
				</tr>  
			';
		}
	$order_table .= '			
		  </tbody>
		</table>
	'; 
	echo $order_table;
}

// add additional invoice to the session
if(isset($_POST['add_add_invo_sess_action'])){
	if(isset($_SESSION["stc_sale_order_add_invo_sess"])) {
		$is_available=0;
		foreach($_SESSION["stc_sale_order_add_invo_sess"] as $keys => $values) {  
			if($_SESSION["stc_sale_order_add_invo_sess"][$keys]['invo_challan_id'] == $_POST["invo_challan_id"]) {  
				$is_available++; 
				echo "This Challan is Already on a List!!!";
			}  
		} 
		if($is_available < 1) {  
			$item_array = array(  
				'invo_challan_id' => $_POST["invo_challan_id"]
			);  
			$_SESSION["stc_sale_order_add_invo_sess"][] = $item_array;  
			echo "Invoice Session Created & Challan Added to Cart!!!";
		}
	}else{  
		$item_array = array(  
		    'invo_challan_id' => $_POST["invo_challan_id"]
		);
		$_SESSION["stc_sale_order_add_invo_sess"][] = $item_array;
		echo "Challan Added to Invoice Session Cart!!!";
	}
}

// delete from sale cart of session Sale order
if(isset($_POST['stcdeladdinvolinei'])){  
	foreach($_SESSION["stc_sale_order_add_invo_sess"] as $keys => $values){  
		if($values["invo_challan_id"] == $_POST["product_id"]){  
			unset($_SESSION["stc_sale_order_add_invo_sess"][$keys]);  
			echo "Challan Removed!!!";  
		}  
	}  
}

// save sale order current
if(isset($_POST['save_add_invo_action'])){
	$out='';
	$stcinvodate=date('Y-m-d', strtotime($_POST['stcinvodate']));
	$invo_cust_id=$_POST['invo_cust_id'];
	$stcinvonotes=$_POST['stcinvonotes'];
	
	$fridayinvo=new ragnarAdditionalTaxInvoiceAdd();
	$fridayinvochallan=new ragnarAdditionalTaxInvoiceAdd();
	if(empty($stcinvodate)){
		$out.="Please Fill Date First!!!";
	}else{
		$objfridayinvo=$fridayinvo->stc_additional_invo_process(
				$stcinvodate,
				$stcinvonotes
		);
		if($objfridayinvo=='invoinserted'){
			$objfridayinvochallan=$fridayinvochallan->stc_additional_invo_challan_process();
			$out=$objfridayinvochallan;
			unset($_SESSION['stc_sale_order_add_invo_sess']);
		}else{
			$out .= "Something went Wrong Please Check & Try Again!!!";
		}
	}
	echo $out;
}

// search by same
if(isset($_POST['stcadditionaltaxinvoaction'])){
	@$bjornebegval=$_POST['phpbegvalue'];
	@$bjorneendval=$_POST['phpendvalue'];
	$bjornefilterbegdate=$_POST['phpbegdate'];
	$bjornefilterenddate=$_POST['phpenddate'];
	$bjornefiltercustomerid=$_POST['phpcustomerid'];
	$bjornefilterinvoiceid=$_POST['phpinvoiceid'];
	$bjornefilterchallaninvoiceno=$_POST['phpchallaninvoiceno'];
	$bjornefilterchallansitename=$_POST['phpchallansitename'];
	$out='';

	if(empty($bjornebegval) || empty($bjorneendval)){
		$bjornebegval=0;
		$bjorneendval=10;
	}
	$objpdres=new ragnarAdditionalTaxInvoiceView();	
	$opobjpdres=$objpdres->stc_getadditionalinvoice_by_multiple_inp($bjornefilterbegdate, $bjornefilterenddate, $bjornefiltercustomerid, $bjornefilterinvoiceid, $bjornefilterchallaninvoiceno, $bjornefilterchallansitename, $bjornebegval, $bjorneendval);
	$out=$opobjpdres;
	echo $out;
}
?>