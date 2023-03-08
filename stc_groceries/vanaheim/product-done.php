<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For Silent Product --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class aetherProduct extends tesseract{

	// go data to purchase challan table
	public function purchase_process($productname, $productdetails, $producttax){	
		$odin='';
		$date=date("Y-m-d H:i:s");
		$lokicheckqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_groceries_product_title`
			FROM
			    `stc_groceries_product`
			WHERE
			    `stc_groceries_product_title` = '".mysqli_real_escape_string($this->stc_dbs, $productname)."'
		");
		if(mysqli_num_rows($lokicheckqry)>0){
			$odin .= "Hmmm!!! This Product is already in record.";
		}else{
			$lokiinsertsale=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_groceries_product`(
					`stc_groceries_product_title`,
					`stc_groceries_product_details`,
					`stc_groceries_product_gst`,
					`stc_groceries_product_created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $productname)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $productdetails)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $producttax)."',
					'".$_SESSION['stc_groceries_user_id']."'
				)
			");
			$odin='';
			if($lokiinsertsale){
				$odin .= "Product Added Successfully!!!";
			}else{
				$odin .= "Hmm!!! Something went wrong. Please check product name details or gst & try again!!!";
			}
		}
		return $odin;
	}	

	// stc call silent purchase
	public function stc_call_purchase(){
		$check_loki=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_groceries_product_title`,
				`stc_groceries_product_details`,
				`stc_groceries_product_gst`
			FROM
				`stc_groceries_product`
			ORDER BY `stc_groceries_product_id` DESC LIMIT 0,50
		");
		$odin='';
		$do_action=mysqli_num_rows($check_loki);
		if($do_action == 0){
			$odin .= "<tr><td colspan='13' align='center'>No Record Found!!!</td></tr>";
		}else{
			foreach ($check_loki as $row) {
				$odin.='
					<tr>
       					<td class="text-left">
       					  <p>'.$row["stc_groceries_product_title"].'</p>
       					</td>
       					<td class="text-left">
       					  <p>'.$row["stc_groceries_product_details"].'</p>
       					</td>
       					<td class="text-center">
       					  <p>'.$row["stc_groceries_product_gst"].'%</p>
       					</td>
            		</tr>         	
		    	';				
			}
			
		}
		return $odin;
	}
}
#<------------------------------------------------------------------------------------------>
#<---------------------------------Silent product  Section---------------------------------->
#<------------------------------------------------------------------------------------------>
// save product
if(isset($_POST['save_product_action'])){
	$out='';
	$productname	= $_POST['productname'];
	$productdetails		= $_POST['productdetails'];
	$producttax		= $_POST['producttax'];

	$fridayproduct=new aetherProduct();
	if(empty($productname) || empty($productdetails) || empty($producttax)){
		$out= "Please Fill All Fields!!!";
	}elseif(empty($_SESSION['stc_groceries_user_id'])){
		$out= "Session Expired!!!Please Login Again.";
	}else{
		$fridayproduct=$fridayproduct->purchase_process(
			$productname,
			$productdetails,
			$producttax
		);
		$out=$fridayproduct;
	}
	echo $out;
}

// Show product On product Page
if(isset($_POST['stc_show_product'])){
	$objloki=new aetherProduct();
	$objlokiout=$objloki->stc_call_purchase();
	echo json_encode($objlokiout);
	// echo $objlokiout;
}
?>