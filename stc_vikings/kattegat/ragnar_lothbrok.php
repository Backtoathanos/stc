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
    // $type: 'NA' (monthly), 'Y' (yearly), 'A' (all time), 'R' (date range)
    public function stc_gld($month, $year, $type, $date_from = '', $date_to = '') {
        // $year = date('Y');
		$queryFilter="WHERE MONTH(stc_purchase_product_adhoc_created_date) = '$month' AND YEAR(stc_purchase_product_adhoc_created_date) = '$year'";
		$queryFilter1="WHERE stc_purchase_product_adhoc_cherrypickby=0 AND MONTH(s.stc_cust_super_requisition_list_items_rec_date) = '$month' AND YEAR(s.stc_cust_super_requisition_list_items_rec_date) = '$year'";
		$queryFilter2="WHERE MONTH(A.created_date)='$month' AND YEAR(A.created_date)='$year'";
		if($type == 'Y') {
			$queryFilter = "WHERE YEAR(stc_purchase_product_adhoc_created_date) = '$year'";
			$queryFilter1="WHERE stc_purchase_product_adhoc_cherrypickby=0 AND YEAR(s.stc_cust_super_requisition_list_items_rec_date) = '$year'";
			$queryFilter2="WHERE YEAR(A.created_date)='$year'";
		} elseif($type == 'A') {
			$queryFilter = "WHERE 1=1";
			$queryFilter1 = "WHERE stc_purchase_product_adhoc_cherrypickby=0";
			$queryFilter2 = "WHERE 1=1";
		} elseif($type == 'R') {
			$df = mysqli_real_escape_string($this->stc_dbs, (string)$date_from);
			$dt = mysqli_real_escape_string($this->stc_dbs, (string)$date_to);
			// Expect YYYY-MM-DD, use inclusive range; if missing, fall back to 1=1
			if($df != '' && $dt != ''){
				$queryFilter = "WHERE DATE(stc_purchase_product_adhoc_created_date) BETWEEN '".$df."' AND '".$dt."'";
				$queryFilter1 = "WHERE stc_purchase_product_adhoc_cherrypickby=0 AND DATE(s.stc_cust_super_requisition_list_items_rec_date) BETWEEN '".$df."' AND '".$dt."'";
				$queryFilter2 = "WHERE DATE(A.created_date) BETWEEN '".$df."' AND '".$dt."'";
			} else {
				$queryFilter = "WHERE 1=1";
				$queryFilter1 = "WHERE stc_purchase_product_adhoc_cherrypickby=0";
				$queryFilter2 = "WHERE 1=1";
			}
		}

		// Location list (branches) should be complete even if there is no data for the selected period.
		// We source locations from stc_trading_user and then fill missing values as 0.
		$all_locations = [];
		$locSql = mysqli_query(
			$this->stc_dbs,
			"SELECT DISTINCT TRIM(stc_trading_user_location) AS loc FROM stc_trading_user WHERE TRIM(stc_trading_user_location) <> '' AND stc_trading_user_location <> 'root'"
		);
		if ($locSql && mysqli_num_rows($locSql) > 0) {
			while ($locRow = mysqli_fetch_assoc($locSql)) {
				$loc = trim((string)($locRow['loc'] ?? ''));
				if ($loc !== '') {
					$all_locations[] = $loc;
				}
			}
		}
		// Fallback: if stc_trading_user has no locations, keep old behavior.
		if (count($all_locations) === 0) {
			$all_locations = null;
		}
        // Total Purchased
        $purchase_q = mysqli_query($this->stc_dbs, "
            SELECT SUM(stc_purchase_product_adhoc_qty * stc_purchase_product_adhoc_prate) as purchased
            FROM stc_purchase_product_adhoc
            $queryFilter
        ");
        $purchase = mysqli_fetch_assoc($purchase_q);
        $total_purchase = $purchase['purchased'] ? floatval($purchase['purchased']) : 0;
        // Total Sold (join to get rate +5%)
        $sold_q = mysqli_query($this->stc_dbs, "
            SELECT SUM(s.stc_cust_super_requisition_list_items_rec_recqty * (a.stc_purchase_product_adhoc_rate)) as sold
            FROM stc_cust_super_requisition_list_items_rec s
            INNER JOIN stc_purchase_product_adhoc a ON s.stc_cust_super_requisition_list_items_rec_list_poaid = a.stc_purchase_product_adhoc_id
            $queryFilter1
        ");
        $sold = mysqli_fetch_assoc($sold_q);
        $total_sale = $sold['sold'] ? floatval($sold['sold']) : 0;

        $sale_locations = [];
		$sale_amount_by_location = [];
		$sql = mysqli_query($this->stc_dbs, "SELECT stc_trading_user_location, COALESCE(SUM(A.qty * COALESCE(A.rate, 0) - COALESCE(A.discount, 0)), 0) AS amount FROM gld_challan A INNER JOIN stc_trading_user ON A.created_by=stc_trading_user_id $queryFilter2 GROUP BY stc_trading_user_location");
		if(mysqli_num_rows($sql)>0){
			while($row = mysqli_fetch_assoc($sql)){
				$loc = trim((string)($row['stc_trading_user_location'] ?? ''));
				$amt = floatval($row['amount'] ?? 0);
				$total_sale += $amt;
				if ($loc !== '') {
					$sale_amount_by_location[$loc] = $amt;
				}
			}
		}

		if ($all_locations !== null) {
			foreach ($all_locations as $loc) {
				$sale_locations[] = [
					'sale_location' => $loc,
					'sale_amount' => isset($sale_amount_by_location[$loc]) ? (float)$sale_amount_by_location[$loc] : 0.0
				];
			}
		} else {
			// Old behavior (only locations present in the query result).
			foreach ($sale_amount_by_location as $loc => $amt) {
				$sale_locations[] = [
					'sale_location' => $loc,
					'sale_amount' => (float)$amt
				];
			}
		}
        $purchase_locations = [];
        // $sql = mysqli_query($this->stc_dbs, "SELECT S.shopname, SUM(S.qty * A.stc_purchase_product_adhoc_prate) AS amount  FROM `stc_shop` S INNER JOIN `stc_purchase_product_adhoc` A ON S.adhoc_id=A.stc_purchase_product_adhoc_id $queryFilter2 GROUP BY S.shopname");
		$sql = mysqli_query($this->stc_dbs, "
			SELECT
				A.shopname,
				SUM(A.qty * (B.stc_purchase_product_adhoc_prate * (1 + (COALESCE(P.stc_product_gst, 0) / 100)))) AS amount
			FROM `stc_shop` A
			INNER JOIN `stc_purchase_product_adhoc` B ON A.adhoc_id = B.stc_purchase_product_adhoc_id
			INNER JOIN `stc_product` P ON P.stc_product_id = B.stc_purchase_product_adhoc_productid
			$queryFilter2
			GROUP BY A.shopname
		");
		$purchase_amount_by_location = [];
		if(mysqli_num_rows($sql)>0){
			while($row = mysqli_fetch_assoc($sql)){
				$loc = trim((string)($row['shopname'] ?? ''));
				$amt = floatval($row['amount'] ?? 0);
				if ($loc !== '') {
					$purchase_amount_by_location[$loc] = $amt;
				}
			}
		}

		if ($all_locations !== null) {
			foreach ($all_locations as $loc) {
				$purchase_locations[] = [
					'purchase_location' => $loc,
					'purchase_amount' => isset($purchase_amount_by_location[$loc]) ? (float)$purchase_amount_by_location[$loc] : 0.0
				];
			}
		} else {
			foreach ($purchase_amount_by_location as $loc => $amt) {
				$purchase_locations[] = [
					'purchase_location' => $loc,
					'purchase_amount' => (float)$amt
				];
			}
		}

		// STOCK (overall + branch-wise) should reflect current balance qty.
		//
		// Concept you described:
		// 1) Overall (central) remaining per adhoc:
		//    adhoc.qty - (SUM(rec.qty by poaid) + SUM(shop.qty by adhoc_id))
		//    then multiply by prate and include GST from product.
		//
		// 2) Branch-wise remaining:
		//    shop.qty - SUM(gld_challan.qty by adhoc_id)
		//    then multiply by prate and include GST from product.
		//
		// Note: Stock is "current", so we intentionally do NOT apply date filters here.
		$total_stock = 0.0;
		$stock_locations = [];
		$stock_amount_by_location = [];

		// (1) Overall / central remaining stock value from adhoc master qty
		$overallStockSql = mysqli_query($this->stc_dbs, "
			SELECT
				SUM(
					GREATEST(
						COALESCE(A.stc_purchase_product_adhoc_qty, 0)
						- (COALESCE(R.rec_qty, 0) + COALESCE(SH.shop_qty, 0)),
						0
					) * (
						COALESCE(A.stc_purchase_product_adhoc_prate, 0)
						* (1 + (COALESCE(P.stc_product_gst, 0) / 100))
					)
				) AS amount
			FROM stc_purchase_product_adhoc A
			INNER JOIN stc_product P
				ON P.stc_product_id = A.stc_purchase_product_adhoc_productid
			LEFT JOIN (
				SELECT
					stc_cust_super_requisition_list_items_rec_list_poaid AS poaid,
					SUM(stc_cust_super_requisition_list_items_rec_recqty) AS rec_qty
				FROM stc_cust_super_requisition_list_items_rec
				GROUP BY stc_cust_super_requisition_list_items_rec_list_poaid
			) R ON R.poaid = A.stc_purchase_product_adhoc_id
			LEFT JOIN (
				SELECT
					adhoc_id,
					SUM(qty) AS shop_qty
				FROM stc_shop
				GROUP BY adhoc_id
			) SH ON SH.adhoc_id = A.stc_purchase_product_adhoc_id
			WHERE A.stc_purchase_product_adhoc_status = 1
		");
		if ($overallStockSql && mysqli_num_rows($overallStockSql) > 0) {
			$overallRow = mysqli_fetch_assoc($overallStockSql);
			$total_stock = floatval($overallRow['amount'] ?? 0);
		}

		// (2) Branch-wise stock value from shop table (minus challan qty)
		$sql = mysqli_query($this->stc_dbs, "
			SELECT
				S.shopname,
				SUM(
					GREATEST(
						COALESCE(S.qty, 0) - COALESCE(C.sold_qty, 0),
						0
					) * (
						COALESCE(A.stc_purchase_product_adhoc_prate, 0)
						* (1 + (COALESCE(P.stc_product_gst, 0) / 100))
					)
				) AS amount
			FROM stc_shop S
			INNER JOIN stc_purchase_product_adhoc A
				ON S.adhoc_id = A.stc_purchase_product_adhoc_id
				AND A.stc_purchase_product_adhoc_status = 1
			INNER JOIN stc_product P
				ON P.stc_product_id = A.stc_purchase_product_adhoc_productid
			LEFT JOIN (
				SELECT
					adhoc_id,
					SUM(qty) AS sold_qty
				FROM gld_challan
				GROUP BY adhoc_id
			) C ON C.adhoc_id = S.adhoc_id
			GROUP BY S.shopname
		");
		if($sql && mysqli_num_rows($sql)>0){
			while($row = mysqli_fetch_assoc($sql)){
				$amt = floatval($row['amount'] ?? 0);
				$loc = trim((string)($row['shopname'] ?? ''));
				if ($loc !== '') {
					$stock_amount_by_location[$loc] = $amt;
				}
			}
		}

		if ($all_locations !== null) {
			foreach ($all_locations as $loc) {
				$stock_locations[] = [
					'stock_location' => $loc,
					'stock_amount' => isset($stock_amount_by_location[$loc]) ? (float)$stock_amount_by_location[$loc] : 0.0
				];
			}
		} else {
			foreach ($stock_amount_by_location as $loc => $amt) {
				$stock_locations[] = [
					'stock_location' => $loc,
					'stock_amount' => (float)$amt
				];
			}
		}

        return array(
			'total_purchase' => $total_purchase,
			'total_sale' => $total_sale,
			'total_stock' => $total_stock,
			'sub_locations_purchase' => $purchase_locations,
			'sub_locations_sale' => $sale_locations,
			'sub_locations_stock' => $stock_locations
		);
    }

	// GLD purchase breakup for a specific shop location (product-wise)
	public function stc_gld_purchase_breakdown($month, $year, $type, $location, $date_from = '', $date_to = ''){
		$locationEsc = mysqli_real_escape_string($this->stc_dbs, (string)$location);
		$month = (int)$month;
		$year = (int)$year;

		// Keep date filtering consistent with dashboard purchase_locations query (stc_shop alias A)
		$dateFilter = "YEAR(A.created_date) = '".$year."'";
		if($type !== 'Y'){
			$dateFilter .= " AND MONTH(A.created_date) = '".$month."'";
		}
		if($type == 'A'){
			$dateFilter = "1=1";
		} elseif($type == 'R'){
			$df = mysqli_real_escape_string($this->stc_dbs, (string)$date_from);
			$dt = mysqli_real_escape_string($this->stc_dbs, (string)$date_to);
			if($df != '' && $dt != ''){
				$dateFilter = "DATE(A.created_date) BETWEEN '".$df."' AND '".$dt."'";
			} else {
				$dateFilter = "1=1";
			}
		}

		$q = mysqli_query($this->stc_dbs, "
			SELECT
				P.stc_product_id AS product_id,
				CASE
					WHEN S.stc_sub_cat_name != 'OTHERS' THEN CONCAT(S.stc_sub_cat_name, ' ', P.stc_product_name)
					ELSE P.stc_product_name
				END AS product_name,
				C.stc_cat_name AS category,
				COALESCE(SUM(A.qty), 0) AS purchase_qty,
				ROUND(
					COALESCE(SUM(A.qty * (B.stc_purchase_product_adhoc_prate * (1 + (COALESCE(P.stc_product_gst, 0) / 100)))), 0)
					/ NULLIF(SUM(A.qty), 0),
				2) AS rate,
				COALESCE(SUM(A.qty * (B.stc_purchase_product_adhoc_prate * (1 + (COALESCE(P.stc_product_gst, 0) / 100)))), 0) AS total
			FROM stc_shop A
			INNER JOIN stc_purchase_product_adhoc B ON A.adhoc_id = B.stc_purchase_product_adhoc_id
			INNER JOIN stc_product P ON P.stc_product_id = B.stc_purchase_product_adhoc_productid
			INNER JOIN stc_sub_category S ON S.stc_sub_cat_id = P.stc_product_sub_cat_id
			INNER JOIN stc_category C ON C.stc_cat_id = P.stc_product_cat_id
			WHERE A.shopname = '".$locationEsc."'
				AND ".$dateFilter."
			GROUP BY P.stc_product_id, product_name, category
			ORDER BY stc_cat_name, stc_product_name ASC
		");

		$rows = [];
		$grand = 0.0;
		if($q && mysqli_num_rows($q) > 0){
			while($r = mysqli_fetch_assoc($q)){
				$qty = (float)($r['purchase_qty'] ?? 0);
				$rate = (float)($r['rate'] ?? 0);
				$total = (float)($r['total'] ?? 0);
				$grand += $total;
				$rows[] = [
					'product_id' => (int)($r['product_id'] ?? 0),
					'product_name' => (string)($r['product_name'] ?? ''),
					'category' => (string)($r['category'] ?? ''),
					'purchase_qty' => $qty,
					'rate' => $rate,
					'total' => $total
				];
			}
		}
		return ['success' => true, 'rows' => $rows, 'total' => $grand];
	}

	// GLD sale breakup for a specific trading user location (product-wise)
	public function stc_gld_sale_breakdown($month, $year, $type, $location, $date_from = '', $date_to = ''){
		$locationEsc = mysqli_real_escape_string($this->stc_dbs, (string)$location);
		$month = (int)$month;
		$year = (int)$year;

		// Keep date filtering consistent with dashboard sale_locations query (gld_challan alias A)
		$dateFilter = "YEAR(A.created_date) = '".$year."'";
		if($type !== 'Y'){
			$dateFilter .= " AND MONTH(A.created_date) = '".$month."'";
		}
		if($type == 'A'){
			$dateFilter = "1=1";
		} elseif($type == 'R'){
			$df = mysqli_real_escape_string($this->stc_dbs, (string)$date_from);
			$dt = mysqli_real_escape_string($this->stc_dbs, (string)$date_to);
			if($df != '' && $dt != ''){
				$dateFilter = "DATE(A.created_date) BETWEEN '".$df."' AND '".$dt."'";
			} else {
				$dateFilter = "1=1";
			}
		}

		$q = mysqli_query($this->stc_dbs, "
			SELECT
				P.stc_product_id AS product_id,
				CASE
					WHEN S.stc_sub_cat_name != 'OTHERS' THEN CONCAT(S.stc_sub_cat_name, ' ', P.stc_product_name)
					ELSE P.stc_product_name
				END AS product_name,
				C.stc_cat_name AS category,
				COALESCE(SUM(A.qty), 0) AS sold_qty,
				ROUND(
					COALESCE(SUM(A.qty * A.rate), 0) / NULLIF(SUM(A.qty), 0),
				2) AS rate,
				COALESCE(SUM(A.qty * COALESCE(A.rate, 0) - COALESCE(A.discount, 0)), 0) AS total
			FROM gld_challan A
			INNER JOIN stc_trading_user TU ON A.created_by = TU.stc_trading_user_id
			INNER JOIN stc_product P ON P.stc_product_id = A.product_id
			INNER JOIN stc_sub_category S ON S.stc_sub_cat_id = P.stc_product_sub_cat_id
			INNER JOIN stc_category C ON C.stc_cat_id = P.stc_product_cat_id
			WHERE TRIM(TU.stc_trading_user_location) = TRIM('".$locationEsc."')
				AND ".$dateFilter."
			GROUP BY P.stc_product_id, product_name, category
			ORDER BY total DESC
		");

		$rows = [];
		$grand = 0.0;
		if($q && mysqli_num_rows($q) > 0){
			while($r = mysqli_fetch_assoc($q)){
				$qty = (float)($r['sold_qty'] ?? 0);
				$rate = (float)($r['rate'] ?? 0);
				$total = (float)($r['total'] ?? 0);
				$grand += $total;
				$rows[] = [
					'product_id' => (int)($r['product_id'] ?? 0),
					'product_name' => (string)($r['product_name'] ?? ''),
					'category' => (string)($r['category'] ?? ''),
					'sold_qty' => $qty,
					'rate' => $rate,
					'total' => $total
				];
			}
		}
		return ['success' => true, 'rows' => $rows, 'total' => $grand];
	}

	// GLD stock breakup for a specific shop location (product-wise)
	// Stock is current; we don't apply period/date filters here.
	public function stc_gld_stock_breakdown($location){
		$locationEsc = mysqli_real_escape_string($this->stc_dbs, (string)$location);

		$q = mysqli_query($this->stc_dbs, "
			SELECT
				P.stc_product_id AS product_id,
				CASE
					WHEN S.stc_sub_cat_name != 'OTHERS' THEN CONCAT(S.stc_sub_cat_name, ' ', P.stc_product_name)
					ELSE P.stc_product_name
				END AS product_name,
				C.stc_cat_name AS category,
				COALESCE(SUM(GREATEST(COALESCE(SH.qty, 0) - COALESCE(GLD.sold_qty, 0), 0)), 0) AS stock_qty,
				ROUND(
					COALESCE(
						SUM(GREATEST(COALESCE(SH.qty, 0) - COALESCE(GLD.sold_qty, 0), 0) * (A.stc_purchase_product_adhoc_prate * (1 + (COALESCE(P.stc_product_gst, 0) / 100)))),
					0) / NULLIF(SUM(GREATEST(COALESCE(SH.qty, 0) - COALESCE(GLD.sold_qty, 0), 0)), 0),
				2) AS rate,
				COALESCE(
					SUM(GREATEST(COALESCE(SH.qty, 0) - COALESCE(GLD.sold_qty, 0), 0) * (A.stc_purchase_product_adhoc_prate * (1 + (COALESCE(P.stc_product_gst, 0) / 100)))),
				0) AS total
			FROM stc_shop SH
			INNER JOIN stc_purchase_product_adhoc A ON SH.adhoc_id = A.stc_purchase_product_adhoc_id
			INNER JOIN stc_product P ON P.stc_product_id = A.stc_purchase_product_adhoc_productid
			INNER JOIN stc_sub_category S ON S.stc_sub_cat_id = P.stc_product_sub_cat_id
			INNER JOIN stc_category C ON C.stc_cat_id = P.stc_product_cat_id
			LEFT JOIN (
				SELECT adhoc_id, SUM(qty) AS sold_qty
				FROM gld_challan
				GROUP BY adhoc_id
			) GLD ON GLD.adhoc_id = SH.adhoc_id
			WHERE SH.shopname = '".$locationEsc."'
				AND A.stc_purchase_product_adhoc_status = 1
			GROUP BY P.stc_product_id, product_name, category
			ORDER BY total DESC
		");

		$rows = [];
		$grand = 0.0;
		if($q && mysqli_num_rows($q) > 0){
			while($r = mysqli_fetch_assoc($q)){
				$qty = (float)($r['stock_qty'] ?? 0);
				$rate = (float)($r['rate'] ?? 0);
				$total = (float)($r['total'] ?? 0);
				$grand += $total;
				$rows[] = [
					'product_id' => (int)($r['product_id'] ?? 0),
					'product_name' => (string)($r['product_name'] ?? ''),
					'category' => (string)($r['category'] ?? ''),
					'stock_qty' => $qty,
					'rate' => $rate,
					'total' => $total
				];
			}
		}
		return ['success' => true, 'rows' => $rows, 'total' => $grand];
	}

	// Item audit by Product ID (Item Code) for GLD summary page
	public function stc_gld_item_audit($item_code){
		$item_code = (int)$item_code;
		if($item_code <= 0){
			return ['success' => false, 'message' => 'Invalid item code'];
		}

		// Product info
		$product_name = '';
		$pq = mysqli_query($this->stc_dbs, "
			SELECT
				P.stc_product_id,
				CASE
					WHEN S.stc_sub_cat_name != 'OTHERS' THEN CONCAT(S.stc_sub_cat_name, ' ', P.stc_product_name)
					ELSE P.stc_product_name
				END AS product_name
			FROM stc_product P
			LEFT JOIN stc_sub_category S ON S.stc_sub_cat_id = P.stc_product_sub_cat_id
			WHERE P.stc_product_id = '".$item_code."'
			LIMIT 1
		");
		if($pq && mysqli_num_rows($pq) > 0){
			$pr = mysqli_fetch_assoc($pq);
			$product_name = (string)($pr['product_name'] ?? '');
		}

		// Purchase rows from adhoc (by product id)
		$purchase_rows = [];
		$purchase_total_qty = 0.0;
		$adhoc_ids = [];
		$qPurchase = mysqli_query($this->stc_dbs, "
			SELECT
				stc_purchase_product_adhoc_id AS adhoc_id,
				stc_purchase_product_adhoc_source AS source_loc,
				stc_purchase_product_adhoc_destination AS dest_loc,
				stc_purchase_product_adhoc_qty AS qty,
				stc_purchase_product_adhoc_prate AS prate,
				stc_purchase_product_adhoc_created_date AS created_date
			FROM stc_purchase_product_adhoc
			WHERE stc_purchase_product_adhoc_productid = '".$item_code."'
				AND stc_purchase_product_adhoc_cherrypickby = 0
			ORDER BY stc_purchase_product_adhoc_id DESC
			LIMIT 80
		");
		if($qPurchase && mysqli_num_rows($qPurchase) > 0){
			while($r = mysqli_fetch_assoc($qPurchase)){
				$aid = (int)($r['adhoc_id'] ?? 0);
				$qty = (float)($r['qty'] ?? 0);
				$purchase_total_qty += $qty;
				if($aid > 0){
					$adhoc_ids[$aid] = true;
				}
				$purchase_rows[] = [
					'adhoc_id' => $aid,
					'source' => (string)($r['source_loc'] ?? ''),
					'destination' => (string)($r['dest_loc'] ?? ''),
					'qty' => $qty,
					'prate' => (float)($r['prate'] ?? 0),
					'date' => isset($r['created_date']) ? date('d-m-Y', strtotime((string)$r['created_date'])) : ''
				];
			}
		}

		$adhoc_id_list_sql = '0';
		if(!empty($adhoc_ids)){
			$adhoc_id_list_sql = implode(',', array_map('intval', array_keys($adhoc_ids)));
		}

		// Dispatch with requisition (rec table) for those adhoc ids
		$requisition_rows = [];
		$requisition_total_qty = 0.0;
		$qReq = mysqli_query($this->stc_dbs, "
			SELECT
				R.stc_cust_super_requisition_list_items_rec_list_id AS requisition_no,
				R.stc_cust_super_requisition_list_items_rec_list_poaid AS adhoc_id,
				R.stc_cust_super_requisition_list_items_rec_recqty AS qty,
				RI.stc_cust_super_requisition_list_items_unit AS unit,
				R.stc_cust_super_requisition_list_items_rec_date AS rec_date,
				PRJ.stc_cust_project_title AS project_name,
				SUP.stc_cust_pro_supervisor_fullname AS supervisor_name
			FROM stc_cust_super_requisition_list_items_rec R
			LEFT JOIN stc_cust_super_requisition_list L ON L.stc_cust_super_requisition_list_id = R.stc_cust_super_requisition_list_items_rec_list_id
			LEFT JOIN stc_cust_project PRJ ON PRJ.stc_cust_project_id = L.stc_cust_super_requisition_list_project_id
			LEFT JOIN stc_cust_pro_supervisor SUP ON SUP.stc_cust_pro_supervisor_id = L.stc_cust_super_requisition_list_super_id
			LEFT JOIN stc_purchase_product_adhoc A ON A.stc_purchase_product_adhoc_id = R.stc_cust_super_requisition_list_items_rec_list_poaid
			LEFT JOIN stc_cust_super_requisition_list_items RI ON RI.stc_cust_super_requisition_list_id = R.stc_cust_super_requisition_list_items_rec_list_item_id
			WHERE A.stc_purchase_product_adhoc_productid = '".$item_code."'
				AND R.stc_cust_super_requisition_list_items_rec_list_poaid IN (".$adhoc_id_list_sql.")
			ORDER BY TIMESTAMP(R.stc_cust_super_requisition_list_items_rec_date) DESC
			LIMIT 200
		");
		if($qReq && mysqli_num_rows($qReq) > 0){
			while($r = mysqli_fetch_assoc($qReq)){
				$qty = (float)($r['qty'] ?? 0);
				$requisition_total_qty += $qty;
				$requisition_rows[] = [
					'requisition_no' => (string)($r['requisition_no'] ?? ''),
					'adhoc_id' => (int)($r['adhoc_id'] ?? 0),
					'qty' => $qty,
					'unit' => (string)($r['unit'] ?? ''),
					'project_name' => (string)($r['project_name'] ?? ''),
					'supervisor_name' => (string)($r['supervisor_name'] ?? ''),
					'date' => isset($r['rec_date']) ? date('d-m-Y', strtotime((string)$r['rec_date'])) : ''
				];
			}
		}

		// Transfer to shop (stc_shop) by those adhoc ids
		$shop_rows = [];
		$shop_total_qty = 0.0;
		$qShop = mysqli_query($this->stc_dbs, "
			SELECT
				TRIM(COALESCE(SH.shopname, '')) AS shop,
				COALESCE(SUM(SH.qty), 0) AS qty
			FROM stc_shop SH
			LEFT JOIN stc_purchase_product_adhoc A ON A.stc_purchase_product_adhoc_id = SH.adhoc_id
			WHERE A.stc_purchase_product_adhoc_productid = '".$item_code."'
				AND SH.adhoc_id IN (".$adhoc_id_list_sql.")
			GROUP BY shop
			ORDER BY qty DESC
		");
		if($qShop && mysqli_num_rows($qShop) > 0){
			while($r = mysqli_fetch_assoc($qShop)){
				$qty = (float)($r['qty'] ?? 0);
				$shop_total_qty += $qty;
				$shop_rows[] = [
					'shop' => (string)($r['shop'] ?? ''),
					'qty' => $qty
				];
			}
		}

		// Challan dispatch (gld_challan) by product id
		$challan_rows = [];
		$challan_total_qty = 0.0;
		$qCh = mysqli_query($this->stc_dbs, "
			SELECT
				G.bill_number AS bill_no,
				C.gld_customer_title AS customer,
				G.qty AS qty,
				G.rate AS rate,
				G.created_date AS created_date
			FROM gld_challan G
			LEFT JOIN gld_customer C ON C.gld_customer_id = G.cust_id
			WHERE G.product_id = '".$item_code."'
			ORDER BY TIMESTAMP(G.created_date) DESC
			LIMIT 200
		");
		if($qCh && mysqli_num_rows($qCh) > 0){
			while($r = mysqli_fetch_assoc($qCh)){
				$qty = (float)($r['qty'] ?? 0);
				$challan_total_qty += $qty;
				$challan_rows[] = [
					'bill_no' => (string)($r['bill_no'] ?? ''),
					'customer' => (string)($r['customer'] ?? ''),
					'qty' => $qty,
					'rate' => (float)($r['rate'] ?? 0),
					'date' => isset($r['created_date']) ? date('d-m-Y', strtotime((string)$r['created_date'])) : ''
				];
			}
		}

		// Stock math
		$adhoc_qty = $purchase_total_qty;
		$req_qty = $requisition_total_qty;
		$challan_qty = $challan_total_qty;
		$balance_qty = $adhoc_qty - ($req_qty + $challan_qty);

		return [
			'success' => true,
			'item_code' => (string)$item_code,
			'product_name' => $product_name,
			'purchase_rows' => $purchase_rows,
			'purchase_total_qty' => $purchase_total_qty,
			'stock' => [
				'adhoc_qty' => $adhoc_qty,
				'requisition_qty' => $req_qty,
				'challan_qty' => $challan_qty,
				'balance_qty' => $balance_qty
			],
			'requisition_rows' => $requisition_rows,
			'shop_rows' => $shop_rows,
			'shop_total_qty' => $shop_total_qty,
			'challan_rows' => $challan_rows,
			'challan_total_qty' => $challan_total_qty
		];
	}
	
	public function stc_gldprofit_analyzer($month, $year, $type, $date_from = '', $date_to = ''){
		$ragnar = '';
		$dateFilterAdhoc = "YEAR(a.stc_purchase_product_adhoc_created_date) = '$year'".($type != 'Y' ? " AND MONTH(a.stc_purchase_product_adhoc_created_date) = '$month'" : "");
		$dateFilterRec = "YEAR(r.stc_cust_super_requisition_list_items_rec_date) = '$year'".($type != 'Y' ? " AND MONTH(r.stc_cust_super_requisition_list_items_rec_date) = '$month'" : "");
		$dateFilterGld = "YEAR(g.created_date) = '$year'".($type != 'Y' ? " AND MONTH(g.created_date) = '$month'" : "");

		if($type == 'A'){
			$dateFilterAdhoc = "1=1";
			$dateFilterRec = "1=1";
			$dateFilterGld = "1=1";
		} elseif($type == 'R'){
			$df = mysqli_real_escape_string($this->stc_dbs, (string)$date_from);
			$dt = mysqli_real_escape_string($this->stc_dbs, (string)$date_to);
			if($df != '' && $dt != ''){
				$dateFilterAdhoc = "DATE(a.stc_purchase_product_adhoc_created_date) BETWEEN '".$df."' AND '".$dt."'";
				$dateFilterRec = "DATE(r.stc_cust_super_requisition_list_items_rec_date) BETWEEN '".$df."' AND '".$dt."'";
				$dateFilterGld = "DATE(g.created_date) BETWEEN '".$df."' AND '".$dt."'";
			} else {
				$dateFilterAdhoc = "1=1";
				$dateFilterRec = "1=1";
				$dateFilterGld = "1=1";
			}
		}

		// Step 1: Get purchase data from adhoc table - get qty and rate, do sum in loop and add to purchase array
		$purchaseQuery = mysqli_query($this->stc_dbs, "
			SELECT 
				stc_purchase_product_adhoc_qty,
				stc_purchase_product_adhoc_prate
			FROM stc_purchase_product_adhoc a
			LEFT JOIN stc_product p ON p.stc_product_id = a.stc_purchase_product_adhoc_productid
			WHERE a.stc_purchase_product_adhoc_cherrypickby = 0
				AND ".$dateFilterAdhoc."
		");

		$purchaseArray = 0;
		while ($row = mysqli_fetch_assoc($purchaseQuery)) {
			$purchaseAmount = $row['stc_purchase_product_adhoc_qty'] * $row['stc_purchase_product_adhoc_prate'];
			$purchaseArray += $purchaseAmount;
		}

		// Step 2: Get sold data from rec table with rate from adhoc table join
		$soldQuery = mysqli_query($this->stc_dbs, "
			SELECT 
				r.stc_cust_super_requisition_list_items_rec_recqty,
				a.stc_purchase_product_adhoc_rate,
				a.stc_purchase_product_adhoc_prate
			FROM stc_cust_super_requisition_list_items_rec r
			LEFT JOIN stc_purchase_product_adhoc a ON a.stc_purchase_product_adhoc_id = r.stc_cust_super_requisition_list_items_rec_list_poaid
			WHERE ".$dateFilterRec."
		");

		$soldArray = 0;
		$profitArray = 0;
		while ($row = mysqli_fetch_assoc($soldQuery)) {
			// Calculate sold amount: rate from adhoc * qty from rec table
			$soldAmount = $row['stc_purchase_product_adhoc_rate'] * $row['stc_cust_super_requisition_list_items_rec_recqty'];
			$soldArray += $soldAmount;
			
			// Calculate profit: (rate from adhoc - prate from adhoc) * qty from rec table
			$profitAmount = ($row['stc_purchase_product_adhoc_rate'] - $row['stc_purchase_product_adhoc_prate']) * $row['stc_cust_super_requisition_list_items_rec_recqty'];
			$profitArray += $profitAmount;
		}

		// Step 3: Get GLD data from gld_challan table with adhoc table join
		$gldQuery = mysqli_query($this->stc_dbs, "
			SELECT 
				g.qty,
				g.rate,
				a.stc_purchase_product_adhoc_rate,
				a.stc_purchase_product_adhoc_prate
			FROM gld_challan g
			LEFT JOIN stc_purchase_product_adhoc a ON a.stc_purchase_product_adhoc_id = g.adhoc_id
			WHERE ".$dateFilterGld."
		");

		while ($row = mysqli_fetch_assoc($gldQuery)) {
			// Calculate sold amount: rate from adhoc * qty from gld table
			$gldSoldAmount = $row['stc_purchase_product_adhoc_rate'] * $row['qty'];
			$soldArray += $gldSoldAmount;
			
			// Calculate profit: (rate from adhoc - prate from adhoc) * qty from gld table
			$gldProfitAmount = ($row['stc_purchase_product_adhoc_rate'] - $row['stc_purchase_product_adhoc_prate']) * $row['qty'];
			$profitArray += $gldProfitAmount;
		}

		$profitClass = $profitArray >= 0 ? 'text-success' : 'text-danger';
		$profitDisplay = ($profitArray >= 0 ? '+' : '') . number_format($profitArray, 2);

		$ragnar .= "
			<tr>
				<td class='text-right'>" . number_format($purchaseArray, 2) . "</td>
				<td class='text-right'>" . number_format($soldArray, 2) . "</td>
				<td class='text-right'><span class='$profitClass'>$profitDisplay</span></td>
			</tr>
		";

		return $ragnar;
	}

	// Update requisition item status with logging
	public function stc_update_pending_requisition_status($item_id, $status, $reason = ''){
		if($item_id <= 0 || ($status != 6 && $status != 10)){
			return ['success' => false, 'message' => 'Invalid parameters'];
		}
		
		// Update the status
		$query = "UPDATE stc_cust_super_requisition_list_items SET stc_cust_super_requisition_list_items_status = $status WHERE stc_cust_super_requisition_list_id = $item_id";
		
		if(mysqli_query($this->stc_dbs, $query)){
			// Determine title based on status
			$title = ($status == 6) ? 'Rejected' : 'Closed';
			
			// Get user name from session
			$user_name = isset($_SESSION['stc_empl_name']) ? $_SESSION['stc_empl_name'] : 'System';
			$user_id = isset($_SESSION['stc_empl_id']) ? $_SESSION['stc_empl_id'] : 0;
			
			// Create message
			$message = $title . " by " . $user_name . " on " . date('d-m-Y h:i A');
			if(!empty($reason)){
				$message .= " <br> Reason: " . $reason;
			}
			
			// Insert log entry
			$logQuery = "
				INSERT INTO `stc_cust_super_requisition_list_items_log`(
					`item_id`, 
					`title`, 
					`message`, 
					`status`, 
					`created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $item_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $title)."',
					'".mysqli_real_escape_string($this->stc_dbs, $message)."',
					'1',
					'".mysqli_real_escape_string($this->stc_dbs, $user_id)."'
				)
			";
			
			mysqli_query($this->stc_dbs, $logQuery);
			
			return ['success' => true, 'message' => 'Status updated successfully'];
		} else {
			return ['success' => false, 'message' => 'Failed to update status: ' . mysqli_error($this->stc_dbs)];
		}
	}
}

#<------------------------------------------------------------------------------->
#<----------------------------Dashboard Object Section--------------------------->
#<------------------------------------------------------------------------------->

if(isset($_POST["dashboard"])){	
	$Omonth=$month=isset($_POST['month']) ? $_POST['month'] : '01';
	$preload=isset($_POST['preload']) ? $_POST['preload'] : '';
	$type=isset($_POST['type']) ? $_POST['type'] : 'NA';
	$date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
	$date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';
	$year=date('Y');
	$month=date('m', strtotime($month ? $month : date('Y-m')));
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
	$date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
	$date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';
	$opobjstcgld=$objstcgropaid->stc_gld($month, $year, $type, $date_from, $date_to);
	$opobjstcgldanalyzer=$objstcgropaid->stc_gldprofit_analyzer($month, $year, $type, $date_from, $date_to);
	if($preload=='preload'){
		$month=date('Y-m');
	}else{
		$month=date('Y-m', strtotime($Omonth));
	}
	$cursedyouout=array($opobjtitems, $opobjinventory, $opobjtmerchant, $opobjtcustomer, $opobjtpurchased, $opobjtsoled, $opobjmerdue, $opobjcustdue, $opobjstcelec, $opobjstctra, $opobjstcgro, $opobjstcgld, $month, $opobjstcgldanalyzer);

	echo json_encode($cursedyouout);
	// echo $cursedyouout;
}

// GLD purchase breakup (product-wise) for a clicked location on dashboard
if(isset($_POST["gld_purchase_breakdown"])){
	$Omonth = $month = isset($_POST['month']) ? $_POST['month'] : date('Y-m');
	$type = isset($_POST['type']) ? $_POST['type'] : 'NA';
	$location = isset($_POST['location']) ? $_POST['location'] : '';
	$date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
	$date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';

	$year = date('Y');
	$month = date('m', strtotime($month));
	if($type=="Y"){
		$year = date('Y', strtotime($Omonth));
	}

	$obj = new sceptor();
	$out = $obj->stc_gld_purchase_breakdown((int)$month, (int)$year, $type, $location, $date_from, $date_to);
	header('Content-Type: application/json');
	echo json_encode($out);
	exit;
}

// GLD sale breakup (product-wise) for a clicked location on dashboard
if(isset($_POST["gld_sale_breakdown"])){
	$Omonth = $month = isset($_POST['month']) ? $_POST['month'] : date('Y-m');
	$type = isset($_POST['type']) ? $_POST['type'] : 'NA';
	$location = isset($_POST['location']) ? $_POST['location'] : '';
	$date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
	$date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';

	$year = date('Y');
	$month = date('m', strtotime($month));
	if($type=="Y"){
		$year = date('Y', strtotime($Omonth));
	}

	$obj = new sceptor();
	$out = $obj->stc_gld_sale_breakdown((int)$month, (int)$year, $type, $location, $date_from, $date_to);
	header('Content-Type: application/json');
	echo json_encode($out);
	exit;
}

// GLD stock breakup (product-wise) for a clicked location on GLD summary/dashboard
if(isset($_POST["gld_stock_breakdown"])){
	$location = isset($_POST['location']) ? $_POST['location'] : '';
	$obj = new sceptor();
	$out = $obj->stc_gld_stock_breakdown($location);
	header('Content-Type: application/json');
	echo json_encode($out);
	exit;
}

// GLD item audit (Purchase/Stock/Dispatch/Shop/Challan) by item code (product id)
if(isset($_POST["gld_item_audit"])){
	$item_code = isset($_POST['item_code']) ? $_POST['item_code'] : '';
	$obj = new sceptor();
	$out = $obj->stc_gld_item_audit($item_code);
	header('Content-Type: application/json');
	echo json_encode($out);
	exit;
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

// API endpoint for updating pending requisition status
if(isset($_POST["update_pending_requisition_status"])){
    session_start();
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;
    $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
    
    $obj = new sceptor();
    $result = $obj->stc_update_pending_requisition_status($item_id, $status, $reason);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}
?>