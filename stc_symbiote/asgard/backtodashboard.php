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
			$result=mysqli_query($this->stc_dbs, "
					SELECT * FROM `stc_sale_product_bill`
					RIGHT JOIN `stc_sale_product_bill_no` 
					ON `stc_sale_product_bill_id`=`stc_sale_product_bill_no_bill_id`
					RIGHT JOIN `stc_sale_product` 
					ON `stc_sale_product_bill_no_bill_challan_id`=`stc_sale_product_id`
					RIGHT JOIN `stc_sale_product_items` 
					ON `stc_sale_product_id`=`stc_sale_product_items_product_id`
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
}

#<--------------------------------------------------------------------->
#<----------------------------Object Section--------------------------->
#<--------------------------------------------------------------------->

if(isset($_POST["dashboard"])){	
	$objtitems=new sceptor();
	$objinventory=new sceptor();
	$objtmerchant=new sceptor();
	$objtcustomer=new sceptor();
	$objtpurchased=new sceptor();
	$objtsoled=new sceptor();
	$objmerpaid=new sceptor();
	$objcustpaid=new sceptor();

	$opobjtitems=$objtitems->stc_product();
	$opobjinventory=$objinventory->stc_inventory();
	$opobjtmerchant=$objtmerchant->stc_merchant();
	$opobjtcustomer=$objtcustomer->stc_customer();
	$opobjtpurchased=$objtpurchased->stc_purchased();
	$opobjtsoled=$objtsoled->stc_soled();
	$opobjmerdue=$objmerpaid->stc_merchant_paid();
	$opobjcustdue=$objcustpaid->stc_customer_paid();

	$cursedyouout=array($opobjtitems, $opobjinventory, $opobjtmerchant, $opobjtcustomer, $opobjtpurchased, $opobjtsoled, $opobjmerdue, $opobjcustdue);

	echo json_encode($cursedyouout);
	// echo $cursedyouout;
}

if(isset($_POST["dashboardpurchasedcharts"])){
	$objchartsmonth=new sceptor();
	$opobjchartsmonth=$objchartsmonth->stc_purchased_charts();
	header('Content-type: application/json');
	echo json_encode($opobjchartsmonth);
	// echo $opobjchartsmonth;
}

if(isset($_POST["dashboardsoledcharts"])){
	$objchartsmonth=new sceptor();
	$opobjchartsmonth=$objchartsmonth->stc_soled_charts();
	header('Content-type: application/json');
	echo json_encode($opobjchartsmonth);
	// echo $opobjchartsmonth;
}


?>