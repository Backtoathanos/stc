<?php
session_start();
include_once("../../MCU/obdb.php");
class bringMecart extends tesseract{
	public function reload_stc_ecommm_cart(){
		$odin='
			<table class="table table-responsive">
				<thead>
					<th>Items</th>
					<th>Rate</th>
					<th>Total</th>
				</thead>
				<tbody>
		';
		if(isset($_SESSION['stc_ecomm_cart_sess']) && !empty($_SESSION['stc_ecomm_cart_sess'])){
			$odin.='
					<tr>
						<td></td>
					</tr>
			';
		}else{
			$odin.='
					<tr>
						<td>Your shopping cart is empty!!!</td>
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

/*-----------------------------------------------------------------------------*/
/*------------------------------Object_section---------------------------------*/
/*-----------------------------------------------------------------------------*/

if(isset($_POST['controlllerhatch'])){
	$objcart=new bringMecart();
	$opobjcart=$objcart->reload_stc_ecommm_cart();
	echo $opobjcart;
}
?>