<?php
include "../../MCU/obdb.php";
class sceptor extends tesseract{
	// call total product
	public function stc_product(){
		$result=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_product`");
		$productcount=mysqli_num_rows($result);
		return $productcount;
	}

	// call total inventory
	public function stc_inventory(){
		$result=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_item_inventory` WHERE `stc_item_inventory_pd_qty`>0");
		$rackcount=mysqli_num_rows($result);
		return $rackcount;
	}

	// call total merchants
	public function stc_merchant(){
		$result=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_merchant`");
		$mercount=mysqli_num_rows($result);
		return $mercount;
	}

	// call total customer
	public function stc_customer(){
		$result=mysqli_query($this->stc_dbs, "SELECT * FROM `stc_customer`");
		$custcount=mysqli_num_rows($result);
		return $custcount;
	}

	// call total purchased amounts
	public function stc_purchased(){
		$ptamount=0;
		$ptgstamount=0;
		$dcamount=0;
		$dcgstamount=0;
		$result=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_product_grn_items`
			INNER JOIN `stc_product` 
			ON `stc_product_grn_items_product_id`=`stc_product_id`
		");
		foreach($result as $trigger){
			$value=$trigger['stc_product_grn_items_qty'] * $trigger['stc_product_grn_items_rate'];
			$ptamount += $value;
			$ptgstamount += ($value * $trigger["stc_product_gst"])/100;
		}

		$resultdc=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_dc_items`
			INNER JOIN `stc_product` 
			ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
		");
		foreach($resultdc as $trigger){
			$value=$trigger['stc_sale_product_dc_items_product_qty'] * $trigger['stc_sale_product_dc_items_product_rate'];
			$dcamount += $value;
			$dcgstamount += ($value * $trigger["stc_product_gst"])/100;
		}

		$totalpurchasedamt=($ptamount + $ptgstamount) + ($dcamount + $dcgstamount);
		return number_format($totalpurchasedamt, 2);
	}

	// call total total soled amounts
	public function stc_soled(){
		$ptamount=0;
		$ptgstamount=0;
		$dcamount=0;
		$dcgstamount=0;
		$vcamount=0;
		$vcgstamount=0;
		$result=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_items`
			INNER JOIN `stc_product`
			ON `stc_sale_product_items_product_id`=`stc_product_id`
		");
		foreach($result as $trigger){
			$value=$trigger['stc_sale_product_items_product_qty'] * $trigger['stc_sale_product_items_product_sale_rate'];
			$ptamount += $value;
			$ptgstamount += ($value * $trigger['stc_product_gst'])/100;
		}

		$resultdc=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_dc_items`
			INNER JOIN `stc_product` 
			ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
		");
		foreach($resultdc as $trigger){
			$value=$trigger['stc_sale_product_dc_items_product_qty'] * $trigger['stc_sale_product_dc_items_product_sale_rate'];
			$dcamount += $value;
			$dcgstamount += ($value * $trigger['stc_product_gst'])/100;
		}

		$resultvc=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_sale_product_vc`
			INNER JOIN `stc_product` 
			ON `stc_sale_product_vc_items_product_id`=`stc_product_id`
		");
		foreach($resultvc as $trigger){
			$value=$trigger['stc_sale_product_vc_items_product_qty'] * $trigger['stc_sale_product_vc_items_product_sale_rate'];
			$vcamount += $value;
			$vcgstamount += ($value * $trigger['stc_product_gst'])/100;
		}

		$totalsolamt=($ptamount + $ptgstamount) + ($dcamount + $dcgstamount) + ($vcamount + $vcgstamount);
		return number_format($totalsolamt, 2);
	}

	// call total merchants paid
	public function stc_merchant_paid(){
		$merchantpaid=0;

		$advanceresult = mysqli_query($this->stc_dbs, "SELECT sum(`stc_merchant_advance_payment_advance_value`) as advance_payments FROM `stc_merchant_advance_payment`");
		$regularresult = mysqli_query($this->stc_dbs, "SELECT sum(`stc_merchant_regular_payment_amount`) as regular_payments FROM `stc_merchant_regular_payment`");
		$dcresult = mysqli_query($this->stc_dbs, "SELECT SUM(`stc_sale_product_dc_payment_value`)as dcamnt FROM `stc_sale_product_dc_payment`");

		$advanceamount = mysqli_fetch_assoc($advanceresult);
		$regularamount = mysqli_fetch_assoc($regularresult);
		$dcamount = mysqli_fetch_assoc($dcresult);

		$merchantpaid = $advanceamount['advance_payments'] + $regularamount['regular_payments'] + $dcamount['dcamnt'];

		return number_format($merchantpaid, 2);
	}

	// call total customer paid
	public function stc_customer_paid(){
		$custpaid=0;

		$totalresult = mysqli_query($this->stc_dbs, "SELECT SUM(`stc_sale_product_payment_value`) AS totalcustpaid FROM `stc_sale_product_payment`");

		$totalamount = mysqli_fetch_assoc($totalresult);

		$custpaid = $totalamount['totalcustpaid'];

		return number_format($custpaid, 2);
	}

	// call total purchased amount for purchased
	public function stc_purchased_charts(){	
		$jsonArray = array();	
		$jsonArrayItem = array();
		$amount=0;
			
		for($months=1;$months<=12; $months++){
			$amount=0;
			$gstamount=0;
			$dcamount=0;
			$dcgstamount=0;
			$result=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_product_grn`
					RIGHT JOIN `stc_product_grn_items` 
					ON `stc_product_grn_id`=`stc_product_grn_items_grn_order_id`
					INNER JOIN `stc_product`
					ON `stc_product_grn_items_product_id`=`stc_product_id`
					WHERE MONTH(`stc_product_grn_date`)='".$months."' AND YEAR(`stc_product_grn_date`)='2021' 
					ORDER BY `stc_product_grn_date` ASC
			");

			foreach($result as $trigger){				
				$value= $trigger['stc_product_grn_items_qty'] * $trigger['stc_product_grn_items_rate'];
				$amount+=$value;
				$gstamount+=($value * $trigger['stc_product_gst'])/100;

			}

			$dcresult=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_sale_product`
					RIGHT JOIN `stc_sale_product_dc_items` 
					ON `stc_sale_product_id`=`stc_sale_product_dc_items_sale_product_id`
					INNER JOIN `stc_product`
					ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
					WHERE MONTH(`stc_sale_product_dc_invo_date`)='".$months."' AND  YEAR(`stc_sale_product_dc_invo_date`)='2021'
					ORDER BY `stc_sale_product_dc_invo_date` ASC
			");

			foreach($dcresult as $trigger){				
				$value= $trigger['stc_sale_product_dc_items_product_qty'] * $trigger['stc_sale_product_dc_items_product_rate'];
				$dcamount+=$value;
				$dcgstamount+=($value * $trigger['stc_product_gst'])/100;

			}

			$totalvalue=$amount + $gstamount;

			$jsonArrayItem['label'] = $months;
			$jsonArrayItem['value'] = $totalvalue;
			array_push($jsonArray, $jsonArrayItem);
			$amount=0;
		}
			
		return $jsonArray;
	}

	// call total soled amount for soled
	public function stc_soled_charts(){	
		$jsonArray = array();	
		$jsonArrayItem = array();
		$amount=0;
			
		for($months=1;$months<=12; $months++){
			$gstval=0;
			$simpchresult=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_sale_product_items_product_qty`,
				    `stc_sale_product_items_product_sale_rate`,
				    `stc_product_gst`
				FROM `stc_sale_product_items`
				INNER JOIN `stc_product` 
				ON `stc_product_id`=`stc_sale_product_items_sale_product_id` 
				INNER JOIN `stc_sale_product` 
				ON `stc_sale_product_id`=`stc_sale_product_items_sale_product_id` 
				INNER JOIN `stc_sale_product_bill_no` 
				ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id` 
				INNER JOIN `stc_sale_product_bill` 
				ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
				WHERE MONTH(`stc_sale_product_bill_date`)='".$months."' AND YEAR(`stc_sale_product_bill_date`)='2021' 
				ORDER BY `stc_sale_product_bill_date` ASC 
			");

			foreach($result as $trigger){				
				$amount = $trigger['stc_sale_product_items_product_qty'] * $trigger['stc_sale_product_items_product_sale_rate'];
				$gstval += ($amount * $trigger['stc_product_gst'])/100;
			}	
					
			$jsonArrayItem['label'] = $months;
			$jsonArrayItem['value'] = $amount;
			array_push($jsonArray, $jsonArrayItem);
			$amount=0;
		}
			
		return $jsonArray;
	}

	// call sale purcashe
	public function stc_electronics($month, $year){
		$electronics_array=array();
		$date = date("d-m-Y");
    	$year = date("Y");
    	// $month = date("m");
    	$day = 1;
    	$combinedtodate=$day.'-'.$month.'-'.$year;
    	$newDate = date('Y-m-d', strtotime($date)); 
		$effectiveDate = $month;
		$purchaseresult=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_daily_purchase_items_qty`,
			    `stc_daily_purchase_items_rate`,
			    `stc_daily_purchase_items_tax`
			FROM
			    `stc_daily_purchase_items`
			INNER JOIN
			    `stc_daily_purchase`
			ON
			    `stc_daily_purchase_id`=`stc_daily_purchase_items_order_id`
			WHERE
			    MONTH(`stc_daily_purchase_refr_date`)='$effectiveDate'
			AND 
			    YEAR(`stc_daily_purchase_refr_date`)='$year'
			ORDER BY
			    DATE(`stc_daily_purchase_refr_date`)
			DESC
		");
		$purcahse_total=0;
		foreach($purchaseresult as $purchaseresultrow){
			$basic=0;
			$total=0;
			$basic=$purchaseresultrow['stc_daily_purchase_items_qty'] * $purchaseresultrow['stc_daily_purchase_items_rate'];
			$total=$basic + ($basic * $purchaseresultrow['stc_daily_purchase_items_tax']/100);
			$purcahse_total+=$total;
		}
		$electronics_array['elecpurchase']=number_format($purcahse_total, 2);

		$saleresult=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_daily_sale_items_qty`,
			    `stc_daily_sale_items_rate`,
			    `stc_daily_sale_items_tax`
			FROM
			    `stc_daily_sale_items`
			INNER JOIN
			    `stc_daily_sale`
			ON
			    `stc_daily_sale_id`=`stc_daily_sale_items_order_id`
			WHERE
			    MONTH(`stc_daily_sale_refr_date`)='$effectiveDate'
			AND 
			    YEAR(`stc_daily_sale_refr_date`)='$year'
			ORDER BY
			    DATE(`stc_daily_sale_refr_date`)
			DESC
		");
		$sale_total=0;
		foreach($saleresult as $saleresultrow){
			$basic=0;
			$total=0;
			$basic=$saleresultrow['stc_daily_sale_items_qty'] * $saleresultrow['stc_daily_sale_items_rate'];
			$total=$basic + ($basic * $saleresultrow['stc_daily_sale_items_tax']/100);
			$sale_total+=$total;
		}
		$electronics_array['elecsale']=number_format($sale_total, 2);
		
		$totalmpaid=0;
		$mpaypurchaseqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_daily_purchase_id` 
			FROM 
				`stc_daily_purchase` 
			WHERE 
				MONTH(`stc_daily_purchase_refr_date`)='$effectiveDate'
			AND 
			    YEAR(`stc_daily_purchase_refr_date`)='$year'
		");
		foreach($mpaypurchaseqry as $mpaypurchaserow){			
			$mpayresult=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_daily_purchase_payments_amount` 
				FROM 
					`stc_daily_purchase_payments` 
				WHERE 
					`stc_daily_purchase_payments_order_no`='".$mpaypurchaserow['stc_daily_purchase_id']."'");
			foreach($mpayresult as $mpayresultrow){
				$totalmpaid+=$mpayresultrow['stc_daily_purchase_payments_amount'];
			}
		}
		$electronics_array['elecmpaid']=number_format($totalmpaid, 2);

		$totalcpaid=0;
		$mpaysaleqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_daily_sale_id`
			FROM 
				`stc_daily_sale` 
			WHERE 
				MONTH(`stc_daily_sale_refr_date`)='$effectiveDate'
			AND 
			    YEAR(`stc_daily_sale_refr_date`)='$year'
		");
		foreach($mpaysaleqry as $mpaysalerow){			
			$cpayresult=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_daily_sale_payments_amount` 
				FROM 
					`stc_daily_sale_payments` 
				WHERE 
					`stc_daily_sale_payments_order_no`='".$mpaysalerow['stc_daily_sale_id']."'");
			foreach($cpayresult as $cpayresultrow){
				$totalcpaid+=$cpayresultrow['stc_daily_sale_payments_amount'];
			}
		}

		$electronics_array['eleccpaid']=number_format($totalcpaid, 2);

		$totalpdues= $purcahse_total - $totalmpaid;
		$totalsdues= $sale_total - $totalcpaid;

		$expenseresult=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_expenses_amount`
			FROM
			    `stc_expenses`
			WHERE
				`stc_expenses_company`=2
			AND 
			    MONTH(`stc_expenses_date`)='$effectiveDate'
			AND 
			    YEAR(`stc_expenses_date`)='$year'
			ORDER BY
			    DATE(`stc_expenses_date`)
			DESC
		");
		$expense_total=0;
		foreach($expenseresult as $expenseresultrow){
			$total=0;
			$total=$expenseresultrow['stc_expenses_amount'];
			$expense_total+=$total;
		}

		$electronics_array['elecpdues']=number_format($totalpdues, 2);
		$electronics_array['elecsdues']=number_format($totalsdues, 2);
		$electronics_array['elecsexpense']=number_format($expense_total, 2);
		return $electronics_array;
	}

	public function stc_trading($month, $year, $type){
		$trading_array=array();
		$date = date("d-m-Y");
    	// $year = date("Y");
    	// $month = date("m");
    	$day = 1;
    	$combinedtodate=$day.'-'.$month.'-'.$year;
    	$newDate = date('Y-m-d', strtotime($date)); 
    	// $effectiveDate = date('Y-m-d', strtotime($combinedtodate));
		$effectiveDate = $month;
		$queryFilter=" WHERE MONTH(`stc_trading_purchase_refrence_date`)='$effectiveDate' AND YEAR(`stc_trading_purchase_refrence_date`)='$year'";
		$queryFilter1=" WHERE MONTH(`stc_trading_sale_date`)='$effectiveDate' AND YEAR(`stc_trading_sale_date`)='$year'";
		$queryFilter2="AND MONTH(`stc_expenses_date`)='$effectiveDate' AND YEAR(`stc_expenses_date`)='$year'";
		if($type == 'Y'){
			$queryFilter=" WHERE YEAR(`stc_trading_purchase_refrence_date`)='$year'";
			$queryFilter1=" WHERE YEAR(`stc_trading_sale_date`)='$year'";
			$queryFilter2="AND YEAR(`stc_expenses_date`)='$year'";
		}
		$purchaseresult=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_trading_purchase_items_qty`,
			    `stc_trading_purchase_items_price`,
			    `stc_trading_purchase_items_gst`
			FROM
			    `stc_trading_purchase_items`
			INNER JOIN 
			    `stc_trading_purchase`
			ON
			    `stc_trading_purchase_items_purchase_id`=`stc_trading_purchase_id`
			$queryFilter
			ORDER BY
			    DATE(`stc_trading_purchase_refrence_date`)
			DESC
		");
		$purcahse_total=0;
		foreach($purchaseresult as $purchaseresultrow){
			$basic=0;
			$total=0;
			$basic=$purchaseresultrow['stc_trading_purchase_items_qty'] * $purchaseresultrow['stc_trading_purchase_items_price'];
			$total=$basic + ($basic * $purchaseresultrow['stc_trading_purchase_items_gst']/100);
			$purcahse_total+=$total;
		}
		$trading_array['trapurchase']=number_format($purcahse_total, 2);

		$saleresult=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_trading_sale_items_item_qty`,
			    `stc_trading_sale_items_item_price`,
			    `stc_trading_sale_items_item_gst`
			FROM
			    `stc_trading_sale_items`
			INNER JOIN
			    `stc_trading_sale`
			ON
			    `stc_trading_sale_items_sale_id`=`stc_trading_sale_id`
			$queryFilter1
			ORDER BY
			    DATE(`stc_trading_sale_date`)
			DESC
		");
		$sale_total=0;
		foreach($saleresult as $saleresultrow){
			$basic=0;
			$total=0;
			$basic=$saleresultrow['stc_trading_sale_items_item_qty'] * $saleresultrow['stc_trading_sale_items_item_price'];
			$total=$basic + ($basic * $saleresultrow['stc_trading_sale_items_item_gst']/100);
			$sale_total+=$total;
		}
		$trading_array['trasale']=number_format($sale_total, 2);
		
		$totalmpaid=0;
		$mpaypurchaseqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_trading_purchase_id` 
			FROM 
				`stc_trading_purchase` 
			$queryFilter
		");
		foreach($mpaypurchaseqry as $mpaypurchaserow){			
			$cpayresult=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_trading_purchase_payment_value` 
				FROM 
					`stc_trading_purchase_payment` 
				WHERE 
					`stc_trading_purchase_payment_purchase_id`='".$mpaypurchaserow['stc_trading_purchase_id']."'");
			foreach($cpayresult as $cpayresultrow){
				$totalmpaid+=$cpayresultrow['stc_trading_purchase_payment_value'];
			}
		}
		
		$trading_array['trampaid']=number_format($totalmpaid, 2);

		$totalcpaid=0;
		$mpaysaleqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_trading_sale_id` 
			FROM 
				`stc_trading_sale` 
			$queryFilter1
		");
		foreach($mpaysaleqry as $mpaysalerow){			
			$cpayresult=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_trading_sale_payment_value` 
				FROM 
					`stc_trading_sale_payment` 
				WHERE 
					`stc_trading_sale_payment_sale_id`='".$mpaysalerow['stc_trading_sale_id']."'");
			foreach($cpayresult as $cpayresultrow){
				$totalcpaid+=$cpayresultrow['stc_trading_sale_payment_value'];
			}
		}
		
		$trading_array['tracpaid']=number_format($totalcpaid, 2);

		$totalpdues= $purcahse_total - $totalmpaid;
		$totalsdues= $sale_total - $totalcpaid;

		$expenseresult=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_expenses_amount`
			FROM
			    `stc_expenses`
			WHERE
				`stc_expenses_company`=1
			$queryFilter2
			ORDER BY
			    DATE(`stc_expenses_date`)
			DESC
		");
		$expense_total=0;
		foreach($expenseresult as $expenseresultrow){
			$total=0;
			$total=$expenseresultrow['stc_expenses_amount'];
			$expense_total+=$total;
		}
		$trading_array['trapdues']=number_format($totalpdues, 2);
		$trading_array['trasdues']=number_format($totalsdues, 2);
		$trading_array['traexpense']=number_format($expense_total, 2);
		return $trading_array;
	}

	public function stc_groceries($month, $year){
		$groceries_array=array();
		$date = date("d-m-Y");
    	// $year = date("Y");
    	// $month = date("m");
    	$day = 1;
    	$combinedtodate=$day.'-'.$month.'-'.$year;
    	$newDate = date('Y-m-d', strtotime($date)); 
		$effectiveDate = $month;
		$purchaseresult=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_groceries_purchase_items_qty`,
			    `stc_groceries_purchase_items_price`,
			    `stc_groceries_purchase_items_gst`
			FROM
			    `stc_groceries_purchase_items`
			INNER JOIN 
			    `stc_groceries_purchase`
			ON
			    `stc_groceries_purchase_items_purchase_id`=`stc_groceries_purchase_id`
			WHERE
			    MONTH(`stc_groceries_purchase_refrence_date`)='$effectiveDate'
			AND 
			    YEAR(`stc_groceries_purchase_refrence_date`)='$year'
			ORDER BY
			    DATE(`stc_groceries_purchase_refrence_date`)
			DESC
		");
		$purcahse_total=0;
		foreach($purchaseresult as $purchaseresultrow){
			$basic=0;
			$total=0;
			$basic=$purchaseresultrow['stc_groceries_purchase_items_qty'] * $purchaseresultrow['stc_groceries_purchase_items_price'];
			$total=$basic + ($basic * $purchaseresultrow['stc_groceries_purchase_items_gst']/100);
			$purcahse_total+=$total;
		}
		$groceries_array['grospurchase']=number_format($purcahse_total, 2);

		$saleresult=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_groceries_sale_items_item_qty`,
			    `stc_groceries_sale_items_item_price`,
			    `stc_groceries_sale_items_item_gst`
			FROM
			    `stc_groceries_sale_items`
			INNER JOIN
			    `stc_groceries_sale`
			ON
			    `stc_groceries_sale_items_sale_id`=`stc_groceries_sale_id`
			WHERE
			    MONTH(`stc_groceries_sale_date`)='$effectiveDate'
			AND 
			    YEAR(`stc_groceries_sale_date`)='$year'
			ORDER BY
			    DATE(`stc_groceries_sale_date`)
			DESC
		");
		$sale_total=0;
		foreach($saleresult as $saleresultrow){
			$basic=0;
			$total=0;
			$basic=$saleresultrow['stc_groceries_sale_items_item_qty'] * $saleresultrow['stc_groceries_sale_items_item_price'];
			$total=$basic + ($basic * $saleresultrow['stc_groceries_sale_items_item_gst']/100);
			$sale_total+=$total;
		}
		$groceries_array['grossale']=number_format($sale_total, 2);
		
		$totalmpaid=0;
		$mpaypurchaseqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_groceries_purchase_id` 
			FROM 
				`stc_groceries_purchase` 
			WHERE 
				MONTH(`stc_groceries_purchase_refrence_date`)='$effectiveDate'
			AND 
			    YEAR(`stc_groceries_purchase_refrence_date`)='$year'
		");
		foreach($mpaypurchaseqry as $mpaypurchaserow){			
			$mpayresult=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_groceries_purchase_payment_value` 
				FROM 
					`stc_groceries_purchase_payment` 
				WHERE 
					`stc_groceries_purchase_payment_purchase_id`='".$mpaypurchaserow['stc_groceries_purchase_id']."'");
			foreach($mpayresult as $mpayresultrow){
				$totalmpaid+=$mpayresultrow['stc_groceries_purchase_payment_value'];
			}
		}

		$groceries_array['grosmpaid']=number_format($totalmpaid, 2);

		$totalcpaid=0;
		$mpaysaleqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_groceries_sale_id` 
			FROM 
				`stc_groceries_sale` 
			WHERE 
				MONTH(`stc_groceries_sale_date`)='$effectiveDate'
			AND 
			    YEAR(`stc_groceries_sale_date`)='$year'
		");
		foreach($mpaysaleqry as $mpaysalerow){			
			$cpayresult=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_groceries_sale_payment_value` 
				FROM 
					`stc_groceries_sale_payment` 
				WHERE 
					`stc_groceries_sale_payment_sale_id`='".$mpaysalerow['stc_groceries_sale_id']."'");
			foreach($cpayresult as $cpayresultrow){
				$totalcpaid+=$cpayresultrow['stc_groceries_sale_payment_value'];
			}
		}
		
		$groceries_array['groscpaid']=number_format($totalcpaid, 2);

		$totalpdues= $purcahse_total - $totalmpaid;
		$totalsdues= $sale_total - $totalcpaid;

		$expenseresult=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_expenses_amount`
			FROM
			    `stc_expenses`
			WHERE
				`stc_expenses_company`=3 
			AND 
			    MONTH(`stc_expenses_date`)='$effectiveDate'
			AND 
			    YEAR(`stc_expenses_date`)='$year'
			ORDER BY
			    DATE(`stc_expenses_date`)
			DESC
		");
		$expense_total=0;
		foreach($expenseresult as $expenseresultrow){
			$total=0;
			$total=$expenseresultrow['stc_expenses_amount'];
			$expense_total+=$total;
		}
		$groceries_array['grospdues']=number_format($totalpdues, 2);
		$groceries_array['grossdues']=number_format($totalsdues, 2);
		$groceries_array['grosexpense']=number_format($expense_total, 2);
		return $groceries_array;
	}

    // INVENTORY-WISE MONTHLY/YEARLY PURCHASED & SOLD SUMMARY
    public function stc_inventory_summary($month, $year) {
        $summary = array();
        // Get all products
        $products = mysqli_query($this->stc_dbs, "SELECT stc_product_id, stc_product_name, stc_product_unit FROM stc_product");
        while ($product = mysqli_fetch_assoc($products)) {
            $product_id = $product['stc_product_id'];
            $product_name = $product['stc_product_name'];
            $product_unit = $product['stc_product_unit'];
            // Purchased amount for this product
            $purchase_q = mysqli_query($this->stc_dbs, "
                SELECT SUM(stc_product_grn_items_qty * stc_product_grn_items_rate) as purchased
                FROM stc_product_grn_items
                INNER JOIN stc_product_grn ON stc_product_grn_id = stc_product_grn_items_grn_order_id
                WHERE stc_product_grn_items_product_id = '$product_id'
                  AND MONTH(stc_product_grn_date) = '$month'
                  AND YEAR(stc_product_grn_date) = '$year'
            ");
            $purchase = mysqli_fetch_assoc($purchase_q);
            $purchased = $purchase['purchased'] ? floatval($purchase['purchased']) : 0;
            // Sold amount for this product
            $sold_q = mysqli_query($this->stc_dbs, "
                SELECT SUM(stc_sale_product_items_product_qty * stc_sale_product_items_product_sale_rate) as sold
                FROM stc_sale_product_items
                INNER JOIN stc_sale_product ON stc_sale_product_id = stc_sale_product_items_sale_product_id
                WHERE stc_sale_product_items_product_id = '$product_id'
                  AND MONTH(stc_sale_product_bill_date) = '$month'
                  AND YEAR(stc_sale_product_bill_date) = '$year'
            ");
            $sold = mysqli_fetch_assoc($sold_q);
            $soldamt = $sold['sold'] ? floatval($sold['sold']) : 0;
            $summary[] = array(
                'product_id' => $product_id,
                'product_name' => $product_name,
                'unit' => $product_unit,
                'purchased' => $purchased,
                'sold' => $soldamt
            );
        }
        return $summary;
    }

    // INVENTORY SUMMARY CARDS (not product-wise)
    public function stc_inventory_summary_cards($month, $year) {
        $result = [
            'total_purchase' => 0,
            'vendor_payment' => 0,
            'vendor_dues' => 0,
            'total_sale' => 0,
            'received_payments' => 0,
            'sale_dues' => 0,
            'expenses' => 0
        ];
        // Total Purchase
        $purchase_q = mysqli_query($this->stc_dbs, "
            SELECT SUM(stc_product_grn_items_qty * stc_product_grn_items_rate) as purchased
            FROM stc_product_grn_items
            INNER JOIN stc_product_grn ON stc_product_grn_id = stc_product_grn_items_grn_order_id
            WHERE MONTH(stc_product_grn_date) = '$month' AND YEAR(stc_product_grn_date) = '$year'
        ");
        $purchase = mysqli_fetch_assoc($purchase_q);
        $result['total_purchase'] = $purchase['purchased'] ? floatval($purchase['purchased']) : 0;
        // Vendor Payment (simulate as 0.00 for now)
        $result['vendor_payment'] = 0.00;
        // Vendor Dues = Purchase - Payment
        $result['vendor_dues'] = $result['total_purchase'] - $result['vendor_payment'];
        // Total Sale
        $sale_q = mysqli_query($this->stc_dbs, "
            SELECT SUM(stc_sale_product_items_product_qty * stc_sale_product_items_product_sale_rate) as sold
            FROM stc_sale_product_items
            INNER JOIN stc_sale_product ON stc_sale_product_id = stc_sale_product_items_sale_product_id
            WHERE MONTH(stc_sale_product_bill_date) = '$month' AND YEAR(stc_sale_product_bill_date) = '$year'
        ");
        $sale = mysqli_fetch_assoc($sale_q);
        $result['total_sale'] = $sale['sold'] ? floatval($sale['sold']) : 0;
        // Received Payments (simulate as 0.00 for now)
        $result['received_payments'] = 0.00;
        // Sale Dues = Sale - Received
        $result['sale_dues'] = $result['total_sale'] - $result['received_payments'];
        // Expenses (simulate as 0.00 for now)
        $result['expenses'] = 0.00;
        return $result;
    }

    // GLD summary for dashboard
    public function stc_gld($month, $year, $type) {
        // $year = date('Y');
		$queryFilter="WHERE MONTH(stc_purchase_product_adhoc_created_date) = '$month' AND YEAR(stc_purchase_product_adhoc_created_date) = '$year'";
		$queryFilter1="WHERE MONTH(s.stc_cust_super_requisition_list_items_rec_date) = '$month' AND YEAR(s.stc_cust_super_requisition_list_items_rec_date) = '$year'";
		$queryFilter2="WHERE MONTH(created_date)='$month' AND YEAR(created_date)='$year'";
		$queryFilter3="WHERE MONTH(s.stc_cust_super_requisition_list_items_rec_date) = '$month' AND YEAR(s.stc_cust_super_requisition_list_items_rec_date) = '$year'";
		if($type == 'Y') {
			$queryFilter = "WHERE YEAR(stc_purchase_product_adhoc_created_date) = '$year'";
			$queryFilter1="WHERE YEAR(s.stc_cust_super_requisition_list_items_rec_date) = '$year'";
			$queryFilter2="WHERE YEAR(created_date)='$year'";
		}
        // Total Purchased
        $purchase_q = mysqli_query($this->stc_dbs, "
            SELECT SUM(stc_purchase_product_adhoc_qty * stc_purchase_product_adhoc_rate) as purchased
            FROM stc_purchase_product_adhoc
            $queryFilter
        ");
        $purchase = mysqli_fetch_assoc($purchase_q);
        $total_purchase = $purchase['purchased'] ? floatval($purchase['purchased']) : 0;
        // Total Sold (join to get rate +5%)
        $sold_q = mysqli_query($this->stc_dbs, "
            SELECT SUM(s.stc_cust_super_requisition_list_items_rec_recqty * (a.stc_purchase_product_adhoc_rate * 1.05)) as sold
            FROM stc_cust_super_requisition_list_items_rec s
            INNER JOIN stc_purchase_product_adhoc a ON s.stc_cust_super_requisition_list_items_rec_list_poaid = a.stc_purchase_product_adhoc_id
            $queryFilter1
        ");
        $sold = mysqli_fetch_assoc($sold_q);
        $total_sale = $sold['sold'] ? floatval($sold['sold']) : 0;

        $sale_locations = [];
        $sql = mysqli_query($this->stc_dbs, "SELECT stc_trading_user_location, SUM(qty * rate) as amount FROM gld_challan INNER JOIN stc_trading_user ON created_by=stc_trading_user_id $queryFilter2 GROUP BY stc_trading_user_location");
		if(mysqli_num_rows($sql)>0){
			while($row = mysqli_fetch_assoc($sql)){
				$sale_locations[] = [
					'sale_location' => $row['stc_trading_user_location'],
					'sale_amount' => floatval($row['amount'])
				];
			}
		}
        $purchase_locations = [];
        $sql = mysqli_query($this->stc_dbs, "SELECT S.shopname, SUM(S.qty * A.stc_purchase_product_adhoc_rate) AS amount  FROM `stc_shop` S INNER JOIN `stc_purchase_product_adhoc` A ON S.adhoc_id=A.stc_purchase_product_adhoc_id $queryFilter2 GROUP BY S.shopname");
		if(mysqli_num_rows($sql)>0){
			while($row = mysqli_fetch_assoc($sql)){
				$purchase_locations[] = [
					'purchase_location' => $row['shopname'],
					'purchase_amount' => floatval($row['amount'])
				];
			}
		}

        return array('total_purchase' => $total_purchase, 'total_sale' => $total_sale, 'sub_locations_purchase' => $purchase_locations, 'sub_locations_sale' => $sale_locations);
    }

	public function stc_gldprofit_analyzer($month, $year, $type){
		$ragnar='';
		$query=mysqli_query($con, "SELECT stc_purchase_product_adhoc_id, stc_purchase_product_adhoc_itemdesc, stc_purchase_product_adhoc_qty, stc_purchase_product_adhoc_rate, stc_product_sale_percentage FROM stc_purchase_product_adhoc LEFT JOIN `stc_product` ON `stc_product_id`=`stc_purchase_product_adhoc_productid` WHERE stc_purchase_product_adhoc_status=1 ORDER BY stc_purchase_product_adhoc_itemdesc ASC");
    	$totalcount=0;
    	$totalpurchaseamount=0;
    	$totalsoldamount=0;
    	$totalprofitmargin=0;
    	$gtotalprofitmargin=0;
    	foreach($query as $row){
    	    $qty= $row['stc_purchase_product_adhoc_qty'];
    	    $query=mysqli_query($con, "SELECT sum(`stc_cust_super_requisition_list_items_rec_recqty`) as qty FROM `stc_cust_super_requisition_list_items_rec` WHERE stc_cust_super_requisition_list_items_rec_list_poaid='".$row['stc_purchase_product_adhoc_id']."'");
    	    $result=mysqli_fetch_array($query);
    	    $soldqty = $result['qty'];

    	    $salerate= $row['stc_purchase_product_adhoc_rate'] + ($row['stc_purchase_product_adhoc_rate'] * $row['stc_product_sale_percentage'] / 100);

    	    $query=mysqli_query($con, "SELECT SUM(`qty`) as qty, avg(rate) as rate FROM `gld_challan` WHERE adhoc_id='".$row['stc_purchase_product_adhoc_id']."' GROUP BY adhoc_id");
    	    $soldgldqty = 0;
    	    $soldgldrate = 0;
    	    if(mysqli_num_rows($query)>0){
    	        $result=mysqli_fetch_array($query);
    	        $soldgldqty = $result['qty'];
    	        $soldgldrate = $result['rate'];
    	    }

    	    $soldqty = $soldqty + $soldgldqty;
    	    $profit_each = $salerate - $row['stc_purchase_product_adhoc_rate'];
    	    $profit_amount = $profit_each * $soldqty;
    	    $totalpurchaseamount += $row['stc_purchase_product_adhoc_rate'];
    	    $totalsoldamount += $salerate;
    	    $gtotalprofitmargin += $profit_each;
    	    $totalprofitmargin += $profit_amount;
    	    if($soldqty > 0){
    	        $totalcount++;
    	        $ragnar .= '
    	            <tr>
    	                <td>'.$row['stc_purchase_product_adhoc_itemdesc'].'</td>
    	                <td class="text-right">'.number_format($row['stc_purchase_product_adhoc_rate'], 2).'</td>
    	                <td class="text-right">'.number_format($salerate, 2).'</td>
    	                <td class="text-right">'.number_format($soldqty, 2).'</td>
    	                <td class="text-right">'.number_format($profit_each, 2).'</td>
    	                <td class="text-right">'.number_format($profit_amount, 2).'</td>
    	            </tr>
    	        ';
    	    }
    	}
    	$ragnar .= '
    	    <tr>
    	        <td>Total : '.$totalcount.'</td>
    	        <td class="text-right">'.number_format($totalpurchaseamount, 2).'</td>
    	        <td class="text-right">'.number_format($totalsoldamount, 2).'</td>
    	        <td class="text-right"></td>
    	        <td class="text-right">'.number_format($gtotalprofitmargin, 2).'</td>
    	        <td class="text-right">'.number_format($totalprofitmargin, 2).'</td>
    	    </tr>
    	';
		return $ragnar;
	}
}

#<------------------------------------------------------------------------------->
#<----------------------------Dashboard Object Section--------------------------->
#<------------------------------------------------------------------------------->

if(isset($_POST["dashboard"])){	
	$Omonth=$month=isset($_POST['month']) ? $_POST['month'] : '01';
	$preload=isset($_POST['preload']) ? $_POST['preload'] : '';
	$type=isset($_POST['type']) ? $_POST['type'] : 'NA';
	$year=date('Y');
	$month=date('m', strtotime($month));
	if($type=="Y"){
		$year=date('Y', strtotime($Omonth));
	}
	if($preload=='preload'){
		$month=date('m');
	}
	$objtitems=new sceptor();
	$objinventory=new sceptor();
	$objtmerchant=new sceptor();
	$objtcustomer=new sceptor();
	$objtpurchased=new sceptor();
	$objtsoled=new sceptor();
	$objmerpaid=new sceptor();
	$objcustpaid=new sceptor();
	$objstcelecpaid=new sceptor();
	$objstctrapaid=new sceptor();
	$objstcgropaid=new sceptor();

	$opobjtitems=$objtitems->stc_product();
	$opobjinventory=$objinventory->stc_inventory();
	$opobjtmerchant=$objtmerchant->stc_merchant();
	$opobjtcustomer=$objtcustomer->stc_customer();
	$opobjtpurchased=$objtpurchased->stc_purchased();
	$opobjtsoled=$objtsoled->stc_soled();
	$opobjmerdue=$objmerpaid->stc_merchant_paid();
	$opobjcustdue=$objcustpaid->stc_customer_paid();
	$opobjstcelec=$objstcelecpaid->stc_electronics($month, $year);
	$opobjstctra=$objstctrapaid->stc_trading($month, $year, $type);
	$opobjstcgro=$objstcgropaid->stc_groceries($month, $year);
	$opobjstcgld=$objstcgropaid->stc_gld($month, $year, $type);
	$opobjstcgld=$objstcgropaid->stc_gldprofit_analyzer($month, $year, $type);
	if($preload=='preload'){
		$month=date('Y-m');
	}else{
		$month=date('Y-m', strtotime($Omonth));
	}
	$cursedyouout=array($opobjtitems, $opobjinventory, $opobjtmerchant, $opobjtcustomer, $opobjtpurchased, $opobjtsoled, $opobjmerdue, $opobjcustdue, $opobjstcelec, $opobjstctra, $opobjstcgro, $opobjstcgld, $month);

	echo json_encode($cursedyouout);
	// echo $cursedyouout;
}

// if(isset($_POST["dashboardpurchasedcharts"])){
// 	$objchartsmonth=new sceptor();
// 	$opobjchartsmonth=$objchartsmonth->stc_purchased_charts();
// 	header('Content-type: application/json');
// 	echo json_encode($opobjchartsmonth);
// 	// echo $opobjchartsmonth;
// }

// if(isset($_POST["dashboardsoledcharts"])){
// 	$objchartsmonth=new sceptor();
// 	$opobjchartsmonth=$objchartsmonth->stc_soled_charts();
// 	header('Content-type: application/json');
// 	echo json_encode($opobjchartsmonth);
// 	// echo $opobjchartsmonth;
// }

// API endpoint for inventory-wise summary
// if(isset($_POST["dashboard_inventory_summary"])){
//     $month = isset($_POST['month']) ? $_POST['month'] : date('m');
//     $year = isset($_POST['year']) ? $_POST['year'] : date('Y');
//     $obj = new sceptor();
//     $result = $obj->stc_inventory_summary($month, $year);
//     header('Content-Type: application/json');
//     echo json_encode($result);
//     exit;
// }

// API endpoint for inventory summary cards
// if(isset($_POST["dashboard_inventory_summary_cards"])){
//     $month = isset($_POST['month']) ? $_POST['month'] : date('m');
//     $year = isset($_POST['year']) ? $_POST['year'] : date('Y');
//     $obj = new sceptor();
//     $result = $obj->stc_inventory_summary_cards($month, $year);
//     header('Content-Type: application/json');
//     echo json_encode($result);
//     exit;
// }
?>