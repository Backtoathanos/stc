<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*-------------------------------------------For Ledger-------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
// purchase class
class aetherLedger extends tesseract{	
	// call ledger
	public function stc_call_ledger($cust_id){
		$odin_getqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_trading_sale_id`,
				`stc_trading_sale_date`,
				`stc_trading_customer_title`,
				`stc_trading_customer_cont_no`
			FROM
				`stc_trading_sale`
			INNER JOIN 
				`stc_trading_customer` 
			ON 
				`stc_trading_customer_id`=`stc_trading_sale_cust_id`
			WHERE
				`stc_trading_sale_cust_id` = '".mysqli_real_escape_string($this->stc_dbs, $cust_id)."'
			ORDER BY `stc_trading_customer_title` ASC
		");

		$odin='
			<table class="table table-hover table-bordered ">
				<thead>
				  <tr>
				    <th class="text-center" scope="col">Reference No</th>
				    <th class="text-center" scope="col">Reference Date</th>
				    <th class="text-center" scope="col">Customer Name</th>
				    <th class="text-center" scope="col">Contact Number</th>
				    <th class="text-center" scope="col">Sold Amount</th>
				    <th class="text-center" scope="col">Paid Amount</th>
				  </tr>
				</thead>
				<tbody>
		';
	    $slno=0;
		$gtotal=0;
		$gtotalpaid=0;
	    if(mysqli_num_rows($odin_getqry)>0){
	    	foreach($odin_getqry as $odin_getqryrow){
	    		$total=0;
	    		$totalpaid=0;
	    		$slno++;
	    		$odin_getitemqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_trading_sale_items_id`,
					    `stc_trading_sale_items_sale_id`,
					    `stc_trading_sale_items_item_qty`,
					    `stc_trading_sale_items_item_price`,
					    `stc_trading_sale_items_item_unit`,
					    `stc_trading_sale_items_item_gst`
					FROM
						`stc_trading_sale_items`
					INNER JOIN `stc_trading_product` 
					ON `stc_trading_sale_items_item_id`=`stc_trading_product_id`
					WHERE
						`stc_trading_sale_items_sale_id` = '".mysqli_real_escape_string($this->stc_dbs, $odin_getqryrow['stc_trading_sale_id'])."'
					ORDER BY `stc_trading_product_title` ASC
				");
				foreach($odin_getitemqry as $odin_getitemrow){
		    		$basic=$odin_getitemrow['stc_trading_sale_items_item_qty'] * $odin_getitemrow['stc_trading_sale_items_item_price'];
		    		$gst=$basic * $odin_getitemrow['stc_trading_sale_items_item_gst']/100;
		    		$total+=$basic + $gst;
		    	}
	    		$odin.='
	    			<tr>
	    				<td class="text-center">STC/T/S/'.$odin_getqryrow['stc_trading_sale_id'].'</td>
	    				<td class="text-center">'.$odin_getqryrow['stc_trading_sale_date'].'</td>
	    				<td class="text-center">'.$odin_getqryrow['stc_trading_customer_title'].'</td>
	    				<td class="text-center">'.$odin_getqryrow['stc_trading_customer_cont_no'].'</td>
	    				<td class="text-right">'.number_format($total, 2).'</td>
	    				<td class="text-right"></td>
	    			</tr>
	    		';
	    		$odin_getpayqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_trading_sale_payment_value`
					FROM
					    `stc_trading_sale_payment`
					WHERE
						`stc_trading_sale_payment_sale_id` = '".mysqli_real_escape_string($this->stc_dbs, $odin_getqryrow['stc_trading_sale_id'])."'
				");
				foreach($odin_getpayqry as $odin_getpayrow){
		    		$totalpaid+=$odin_getpayrow['stc_trading_sale_payment_value'];
		    	}
	    		$odin.='
	    			<tr>
	    				<td class="text-center"></td>
	    				<td class="text-center"></td>
	    				<td class="text-center"></td>
	    				<td class="text-center"></td>
	    				<td class="text-center"></td>
	    				<td class="text-right">'.number_format($totalpaid, 2).'</td>
	    			</tr>
	    		';
	    		// $total+=$amount;
	    		$gtotal+=$total;
				$gtotalpaid+=$totalpaid;
	    	}
	    	$dues=$gtotal - $gtotalpaid;
	    	$odin.='
	    		<tr>
	    			<td class="text-right" colspan="4"><b>Total Invoice :</b></td>
	    				<td class="text-center"></td>
	    			<td class="text-right"><b>'.number_format($gtotal, 2).'</b></td>
	    		</tr>
	    		<tr>
	    			<td class="text-right" colspan="4"><b>Total Paid :</b></td>
	    			<td class="text-right"><b>'.number_format($gtotalpaid, 2).'</b></td>
	    		</tr>
	    		<tr>
	    			<td class="text-right" colspan="4"><b>Total Dues :</b></td>
	    			<td class="text-right" colspan="2"><b>'.number_format($dues, 2).'</b></td>
	    		</tr>
	    	';
	    }else{
	    	$odin.='
	    		<tr><td colspan="6" class="text-center">No Record Found!!!</td></tr>
	    	';
	    }
		$odin.='
				</tbody>
	       	</table>
		';
		return $odin;
	}
}
#<------------------------------------------------------------------------------------------>
#<---------------------------------Silent Ledger Section------------------------------------>
#<------------------------------------------------------------------------------------------>

// call ledger items
if(isset($_POST['stc_call_ledger'])){
	$out='';
	$cust_id=$_POST['cust_id'];
	$objloki=new aetherLedger();
	if($cust_id=="NA"){
		$out='Please select customer first!!!';
	}else{
		$objlokiout=$objloki->stc_call_ledger($cust_id);
		$out=$objlokiout;
	}
	// echo json_encode($objlokiout);
	echo $out;
}

?>